<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Entrega;
use App\Models\Extracto;

use Illuminate\Support\Facades\DB;    
use Illuminate\Support\Facades\Auth;   
use App\Models\GrupoEntrega;
use App\Models\GrupoEntregaDetalle;

class EntregaController extends Controller
{

private float $baseLat = -25.5075; // Naranjaisy (Villeta)
private float $baseLng = -57.5555;


    public function index()
    {
        $user = Auth::user();
        $entregas = Entrega::where('gestor_id', $user->id)->with('extractos')->get();

        return response()->json($entregas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_entrega' => 'required|string|max:255',
            'sync_status' => 'required|string|max:255',
            'gestor_id' => 'required|integer',
            //'tipo_tarjeta' => 'required|string|max:255',
            'estado' => 'required|integer',
            'observaciones' => 'nullable|string',
        ]);

        $entrega = Entrega::create([
            'nombre_entrega' => $request->nombre_entrega,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
            'gestor_id' => $request->gestor_id,
           // 'gestor_id' => Auth::id(),
            'sync_status' => $request->sync_status,
        ]);

        return response()->json($entrega, 201);
    }

    // Mostrar una entrega específica
    public function show($id)
    {
        $entrega = Entrega::with('extractos')->findOrFail($id);
        return response()->json($entrega);
    }

    // Actualizar una entrega
    public function update(Request $request, $id)
    {
        $entrega = Entrega::findOrFail($id);

        //$this->authorize('update', $entrega); // opcional: política de acceso

        $entrega->update($request->only(['nombre_entrega', 'sync_status', 'estado', 'observaciones']));

        return response()->json($entrega);
    }

    // Eliminar una entrega
    public function destroy($id)
    {
        $entrega = Entrega::findOrFail($id);

        $this->authorize('delete', $entrega);

        $entrega->delete();

        return response()->json(['message' => 'Entrega eliminada correctamente']);
    }

















//--------------------------------------------------------------------------------------



    /**
     * Parte 1: Mapa con entregas activas
     */
 public function mapaEntregas(Request $request)
{
    $year  = $request->query('year');
    $month = $request->query('month');

    // 1) Determinar el/los entrega_id a usar
    if ($year && $month) {
        // Todas las entregas del mes/año
        $entregasIds = Entrega::query()
            ->whereYear('created_at', (int)$year)
            ->whereMonth('created_at', (int)$month)
            ->pluck('entrega_id');

        if ($entregasIds->isEmpty()) {
            return view('admin.entregas.mapa', [
                'puntos' => collect(),
                'entregaId' => null,
                'couriersDisponibles' => 0,
                'year' => $year,
                'month' => $month,
            ]);
        }

        // Usar como "representativa" la más reciente del mes para el botón Generar
        $entregaId = $entregasIds->sortDesc()->first();
    } else {
        // Flujo original: 1 sola entrega (la última si no viene entrega_id)
        $entregaId = (int) $request->query('entrega_id', 0);
        if ($entregaId <= 0) {
            $ultima = Entrega::orderByDesc('entrega_id')->first();
            if (!$ultima) {
                return view('admin.entregas.mapa', [
                    'puntos' => collect(),
                    'entregaId' => null,
                    'couriersDisponibles' => 0,
                ]);
            }
            $entregaId = (int) $ultima->entrega_id;
        }
        $entregasIds = collect([$entregaId]);
    }

    // 2) Traer TODOS los extractos de las entregas seleccionadas (sin paginar)
    $extractos = Extracto::with(['cliente.ubicacion.barrioRel'])
        ->whereIn('entrega_id', $entregasIds)
        ->get();

    // 3) Mapear a puntos (lat/lng + info)
    $puntos = $extractos->map(function ($x) {
            $u = optional(optional($x->cliente)->ubicacion);
            [$lat, $lng] = $this->parseCoords($u?->coordenadas ?? null);
            $id = $x->getKey(); // extracto_id
            return [
                'id'          => $id,
                'extracto_id' => $id,
                'lat'         => is_numeric($lat) ? (float)$lat : null,
                'lng'         => is_numeric($lng) ? (float)$lng : null,
                'barrio'      => $this->resolverBarrio($u),
                'direccion'   => $u?->descripcion,
            ];
        })
        ->filter(fn ($p) => $p['lat'] !== null && $p['lng'] !== null)
        ->values()
        ->all();

    // 4) Couriers disponibles (como ya tenías)
    $couriersDisponibles = DB::table('usuarios')
        ->join('perfiles_has_usuarios', 'usuarios.usuario_id', '=', 'perfiles_has_usuarios.Usuarios_usuario_id')
        ->join('perfiles', 'perfiles.idPerfiles', '=', 'perfiles_has_usuarios.Perfiles_idPerfiles')
        ->where('perfiles.nombre_perfil', 'Courier')
        ->where('usuarios.disponible', 1)
        ->where('usuarios.activo', 1)
        ->count();

    // 5) Devolver vista
  return view('admin.entregas.mapa', [
    'puntos'              => $puntos,
    'entregaId'           => $entregaId,
    'couriersDisponibles' => $couriersDisponibles,
    'year'                => $year,
    'month'               => $month,
    'baseLat'             => $this->baseLat, // ✅ agrega esto
    'baseLng'             => $this->baseLng, // ✅ y esto
]);
}
    /**
     * Parte 2: Generar grupos por barrios (reales o sintéticos) repartidos
     * equitativamente entre K couriers, manteniendo cercanía.
     */

public function generarRutas(Request $request)
{
    // 1) K = couriers disponibles (activos)
    $k = DB::table('usuarios')
        ->join('perfiles_has_usuarios', 'usuarios.usuario_id', '=', 'perfiles_has_usuarios.Usuarios_usuario_id')
        ->join('perfiles', 'perfiles.idPerfiles', '=', 'perfiles_has_usuarios.Perfiles_idPerfiles')
        ->where('perfiles.nombre_perfil', 'Courier')
        ->where('usuarios.disponible', 1)
        ->where('usuarios.activo', 1)
        ->count();

    if ($k <= 0) {
        return back()->with('error', 'No hay couriers disponibles en este momento.');
    }

  // 2) Determinar el conjunto de cabeceras a rutear y bajo cuál guardar
$entregaIdInput = (int) $request->input('entrega_id');
$year  = (int) $request->input('year');
$month = (int) $request->input('month');

$entregaIds = collect();
$entregaIdParaGuardar = null;

// ✅ Si viene año/mes válido, SIEMPRE ruteamos TODO el mes (ignoramos entrega_id para ruteo)
if ($year > 0 && $month > 0) {
    $entregaIds = Entrega::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->pluck('entrega_id');

    // Guardar bajo la cabecera más reciente del mes (FK consistente)
    $entregaIdParaGuardar = Entrega::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->orderByDesc('entrega_id')
        ->value('entrega_id');
} else {
    // Si NO viene año/mes, recién ahí usamos una cabecera específica
    if ($entregaIdInput > 0) {
        $entregaIds = collect([$entregaIdInput]);
        $entregaIdParaGuardar = $entregaIdInput;
    }
}

if ($entregaIds->isEmpty() || !$entregaIdParaGuardar) {
    return back()->with('error', 'Debés indicar una cabecera válida o un mes/año con cabeceras activas.');
}

    // 3) Tomar TODOS los extractos de TODAS las cabeceras seleccionadas
    $puntos = Extracto::with(['cliente.ubicacion.barrioRel'])
        ->whereIn('entrega_id', $entregaIds)
        ->get()
        ->map(function ($x) {
            $u = optional(optional($x->cliente)->ubicacion);
            [$lat, $lng] = $this->parseCoords($u?->coordenadas ?? null);

            $id = $x->getKey(); // extracto_id
            return [
                'id'          => $id,
                'extracto_id' => $id,
                'lat'         => is_numeric($lat) ? (float)$lat : null,
                'lng'         => is_numeric($lng) ? (float)$lng : null,
                'barrio'      => $this->resolverBarrio($u),
                'direccion'   => $u?->descripcion,
            ];
        })
        ->filter(fn ($p) => $p['lat'] !== null && $p['lng'] !== null)
        ->values()
        ->all();

    if (empty($puntos)) {
        return back()->with('error', 'No hay extractos con coordenadas para el período seleccionado.');
    }

// DEBUG: contadores para comparar con el mapa
$debug = [
    'entregaIds_total'         => $entregaIds->count(),
    'extractos_raw'            => Extracto::whereIn('entrega_id', $entregaIds)->count(),
    'puntos_mapeados'          => count($puntos),
  'sin_coords_descartados'   => Extracto::whereIn('entrega_id', $entregaIds)
    ->whereDoesntHave('cliente.ubicacion', function($q){
        $q->whereNotNull('coordenadas')->where('coordenadas','<>','');
    })->count(),
];

// Si querés ver los IDs exactos que estamos ruteando:
$debug['ids_ruteados'] = collect($puntos)->pluck('extracto_id')->values()->all();

// Guardar en sesión para mostrar en la vista
session()->flash('status', 'DEBUG: '.json_encode($debug));

    // 4) Fallback: si falta barrio, crear barrio sintético por celda (~1.1 km)
    $cellSize = 0.01;
    foreach ($puntos as &$e) {
        if (empty($e['barrio'])) {
            $e['barrio'] = $this->barrioSinteticoPorCelda($e['lat'], $e['lng'], $cellSize);
        }
    }
    unset($e);

    // 5) Agrupar por barrio y calcular centroides
    [$barrios, $centroides] = $this->agruparPorBarrioYCentroides($puntos);
    $nBarrios = count($centroides);
    if ($nBarrios === 0) {
        return back()->with('error', 'No se pudieron identificar barrios.');
    }

    // 6) Ajustar K y partir por cercanía con balance
    $k = min($k, $nBarrios);
    if ($k < 1) $k = 1;

    $asignBarrio = $this->partirBarriosBalanceadoPorCercania($centroides, $k);

    // 7) Construir grupos de EXTRACTOS según el cluster del barrio
    $grupos = array_fill(0, $k, []);
    foreach ($barrios as $nombreBarrio => $items) {
        $idx = $asignBarrio[$nombreBarrio] ?? 0;
        foreach ($items as $it) $grupos[$idx][] = $it;
    }

    // 8) Resumen para vista
    $resumen = [];
    for ($i = 0; $i < $k; $i++) {
        $entGroup     = $grupos[$i];
        $listaBarrios = collect($entGroup)->pluck('barrio')->unique()->values()->all();
        $resumen[] = [
            'idx'     => $i + 1,
            'total'   => count($entGroup),
            'barrios' => $listaBarrios,
        ];
    }

    // 9) Guardar en sesión (para verGrupo)
    Session::put('grupos_entregas', $grupos);
    Session::put('entrega_actual_id', $entregaIdParaGuardar);

    // 10) Persistir en DB: reemplazar grupos previos de la cabecera elegida para guardar
    DB::transaction(function () use ($k, $grupos, $entregaIdParaGuardar) {

        // Si tu FK no tiene ON DELETE CASCADE en detalles, descomenta este bloque:
        /*
        $ids = GrupoEntrega::where('entrega_id', $entregaIdParaGuardar)->pluck('grupo_id');
        if ($ids->isNotEmpty()) {
            GrupoEntregaDetalle::whereIn('grupo_id', $ids)->delete();
            GrupoEntrega::whereIn('grupo_id', $ids)->delete();
        }
        */

        // Si tu FK sí es ON DELETE CASCADE, basta con:
        GrupoEntrega::where('entrega_id', $entregaIdParaGuardar)->delete();

        for ($i = 0; $i < $k; $i++) {
            // Crear grupo (courier aún sin asignar)
            $grupo = GrupoEntrega::create([
                'entrega_id' => $entregaIdParaGuardar,
                'id_courier' => null,
                'estado'     => 'pendiente',
            ]);

            // Orden óptimo por vecino más cercano
            $ordenado = $this->ordenarPorVecinoMasCercano($grupos[$i], $this->baseLat, $this->baseLng);

            // Insert masivo de detalles
            $bulk = [];
            foreach ($ordenado as $idx => $e) {
                $extractoId = $e['extracto_id'] ?? $e['id'] ?? null;
                if (!$extractoId) continue;

                $bulk[] = [
                    'grupo_id'     => $grupo->grupo_id,
                    'extracto_id'  => (int)$extractoId,
                    'orden'        => $idx + 1,
                    'distancia_km' => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            if (!empty($bulk)) {
                GrupoEntregaDetalle::insert($bulk);
            }
        }
    });
$entregaId = $request->input('entrega_id');


 return redirect()->route('admin.entregas.gestionarGrupos', [
    'entrega_id' => $entregaId,
    'year' => $request->input('year'),
    'month' => $request->input('month'),
])->with('status', 'Rutas generadas correctamente.');
}

    /**
     * Detalle de un grupo: muestra la lista ordenada (heurística vecino más cercano).
     */
 public function verGrupo($idx)
{
    $idx = (int)$idx;
    $grupos = Session::get('grupos_entregas', []);
    if ($idx < 1 || $idx > count($grupos)) {
        abort(404);
    }

    $grupo = $grupos[$idx - 1];

    // Orden óptimo por vecino más cercano desde la base
    $ordenado = $this->ordenarPorVecinoMasCercano($grupo, $this->baseLat, $this->baseLng);

    // Distancia total (opcional: ida + vuelta a la base)
    $distKm = 0.0;
    $prevLat = $this->baseLat; 
    $prevLng = $this->baseLng;
    foreach ($ordenado as $p) {
        $distKm += $this->haversine($prevLat, $prevLng, $p['lat'], $p['lng']);
        $prevLat = $p['lat']; 
        $prevLng = $p['lng'];
    }
 
    $gmapsWaypoints = [];
    foreach ($ordenado as $p) {
        if (isset($p['lat'], $p['lng'])) {
            $gmapsWaypoints[] = $p['lat'].','.$p['lng'];
        }
    }
    $origin = $this->baseLat.','.$this->baseLng;
    $gmapsUrl = 'https://www.google.com/maps/dir/?api=1'
              . '&origin=' . urlencode($origin)
              . (count($gmapsWaypoints) ? '&waypoints=' . urlencode(implode('|', $gmapsWaypoints)) : '')
              . '&travelmode=driving';

    return view('admin.entregas.grupo_detalle', [
        'idx'       => $idx,
        'base'      => ['lat' => $this->baseLat, 'lng' => $this->baseLng],
        'entregas'  => $ordenado,
        'entregaId' => Session::get('entrega_actual_id'),
        'distKm'    => round($distKm, 2),
        'gmapsUrl'  => $gmapsUrl,
    ]);
}

    /* ==================== HELPERS ==================== */

    private function parseCoords(?string $s): array
    {
        if (!$s) return [null, null];
        if (!preg_match_all('/-?\d+(?:\.\d+)?/', $s, $m)) return [null, null];
        $nums = $m[0];
        if (count($nums) < 2) return [null, null];
        $lat = (float)$nums[0];
        $lng = (float)$nums[1];
        // Si parece estar invertido (lon,lat), corrige
        $swapped = (abs($lat) > 90) || (abs($lng) > 180);
        if ($swapped) [$lat, $lng] = [$lng, $lat];
        return [$lat, $lng];
    }


  private function resolverBarrio($u): ?string
{
    if (!$u) return null;

    if ($u->relationLoaded('barrio') && $u->barrio) {
        $name = trim((string) $u->barrio->nombre_barrio);
        if ($name !== '') return $name;
    }

    if (is_string($u->barrio ?? null)) {
        $txt = trim($u->barrio);
        if ($txt !== '') return $txt;
    }

    return null;
}
// generar barrio sintetico
    private function barrioSinteticoPorCelda(float $lat, float $lng, float $size = 0.01): string
    {
        $latCell = floor($lat / $size) * $size;
        $lngCell = floor($lng / $size) * $size;
        return 'BARRIO_(' . number_format($latCell, 3, '.', '') . ',' . number_format($lngCell, 3, '.', '') . ')_' . $size;
    }

// agrupar barrio por centroides
    private function agruparPorBarrioYCentroides(array $entregas): array
    {
        $barrios = [];
        $sum = [];

        foreach ($entregas as $e) {
            $b = $e['barrio'] ?? 'SIN_BARRIO';
            if (!isset($barrios[$b])) $barrios[$b] = [];
            $barrios[$b][] = $e;

            if (!isset($sum[$b])) $sum[$b] = ['lat' => 0, 'lng' => 0, 'count' => 0];
            $sum[$b]['lat'] += $e['lat'];
            $sum[$b]['lng'] += $e['lng'];
            $sum[$b]['count']++;
        }

        $centroides = [];
        foreach ($sum as $b => $v) {
            $centroides[$b] = [
                'lat'   => $v['lat'] / max(1, $v['count']),
                'lng'   => $v['lng'] / max(1, $v['count']),
                'count' => $v['count'],
            ];
        }

        return [$barrios, $centroides];
    }
// dividir barrio por cercania, dependiendo de cuantos couriers tenemos
    private function partirBarriosBalanceadoPorCercania(array $centroides, int $k): array
    {
        $barrios = array_keys($centroides);
        $n = count($barrios);
        if ($n === 0) return [];

        // capacidad por grupo (distribución lo más pareja posible)
        $baseCap = intdiv($n, $k);
        $resto   = $n % $k;
        $capacidad = array_fill(0, $k, $baseCap);
        for ($i = 0; $i < $resto; $i++) $capacidad[$i]++;

        // inicializar centros "desparramados" (kmeans++ simple)
        $centros = [];
        $primero = $barrios[array_rand($barrios)];
        $centros[] = ['lat' => $centroides[$primero]['lat'], 'lng' => $centroides[$primero]['lng']];

        while (count($centros) < $k) {
            $bestB = null; $bestD = -1;
            foreach ($barrios as $b) {
                $p = $centroides[$b];
                $dmin = PHP_FLOAT_MAX;
                foreach ($centros as $c) {
                    $d = $this->haversine($p['lat'], $p['lng'], $c['lat'], $c['lng']);
                    if ($d < $dmin) $dmin = $d;
                }
                if ($dmin > $bestD) { $bestD = $dmin; $bestB = $b; }
            }
            $centros[] = ['lat' => $centroides[$bestB]['lat'], 'lng' => $centroides[$bestB]['lng']];
        }

        // ordenar barrios por “dificultad” (lejanía al centro más cercano)
        $orden = [];
        foreach ($barrios as $b) {
            $p = $centroides[$b]; $dmin = PHP_FLOAT_MAX;
            foreach ($centros as $c) {
                $d = $this->haversine($p['lat'], $p['lng'], $c['lat'], $c['lng']);
                if ($d < $dmin) $dmin = $d;
            }
            $orden[] = [$b, $dmin];
        }
        usort($orden, fn($a,$b) => $b[1] <=> $a[1]); // primero los más “difíciles”

        // asignación greedy respetando capacidad
        $capDisp = $capacidad;
        $asign = [];
        foreach ($orden as [$b, $_]) {
            $p = $centroides[$b];

            // clusters ordenados por cercanía a este barrio
            $cand = [];
            for ($j = 0; $j < $k; $j++) {
                $d = $this->haversine($p['lat'], $p['lng'], $centros[$j]['lat'], $centros[$j]['lng']);
                $cand[] = [$j, $d];
            }
            usort($cand, fn($x,$y) => $x[1] <=> $y[1]);

            // elegir el más cercano con capacidad
            $elegido = null;
            foreach ($cand as [$j, $d]) {
                if ($capDisp[$j] > 0) { $elegido = $j; break; }
            }
            if ($elegido === null) $elegido = $cand[0][0]; // fallback (no debería pasar)

            $asign[$b] = $elegido;
            $capDisp[$elegido]--;
        }

        return $asign;
    }

    /**
     * Distancia Haversine (km).
     */
    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
        return 2 * $R * asin(min(1, sqrt($a)));
    }

    /**
     * Ordena puntos por heurística del vecino más cercano desde la base.
     */
    private function ordenarPorVecinoMasCercano(array $entregas, float $baseLat, float $baseLng): array
    {
        $rest = $entregas;
        $orden = [];

        $curLat = $baseLat; $curLng = $baseLng;

        while (!empty($rest)) {
            $bestIdx = 0; $bestD = PHP_FLOAT_MAX;
            foreach ($rest as $i => $e) {
                $d = $this->haversine($curLat, $curLng, $e['lat'], $e['lng']);
                if ($d < $bestD) { $bestD = $d; $bestIdx = $i; }
            }
            $next = $rest[$bestIdx];
            $orden[] = $next;
            $curLat = $next['lat']; $curLng = $next['lng'];
            array_splice($rest, 1*$bestIdx, 1);
        }
        return $orden;
    }

    public function selectorMes(Request $request)
{
    // Años disponibles según entregas (cabeceras)
    $years = DB::table('entregas')
        ->selectRaw('YEAR(created_at) as y')
        ->groupBy('y')
        ->orderByDesc('y')
        ->pluck('y')
        ->toArray();

    // Año seleccionado (por query ?year=...), por defecto el más reciente
    $selectedYear = (int)($request->query('year') ?? ($years[0] ?? date('Y')));

    // Meses disponibles (1..12) para ese año
    $months = DB::table('entregas')
        ->selectRaw('MONTH(created_at) as m, COUNT(*) as c')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('m')
        ->pluck('c','m') // [mes => cantidad]
        ->toArray();

    return view('admin.entregas.selector_mes', [
        'years'        => $years,
        'selectedYear' => $selectedYear,
        'months'       => $months, // Hash: mes => count
    ]);
}

// por ahora aun no conecta al mapa
public function verMes($year, $month)
{
    return redirect()->route('admin.entregas', ['year' => (int)$year, 'month' => (int)$month]);
}


//--------------------------------------------------------


public function mapaPorMes(int $year, int $month)
{

    $entregaIds = Entrega::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->pluck('entrega_id');
    $entregaSeleccionada = Entrega::whereIn('entrega_id', $entregaIds)
        ->orderByDesc('entrega_id')
        ->value('entrega_id');

// construcir puntos 
    $puntos = Extracto::with(['cliente.ubicacion.barrioRel'])
        ->whereIn('entrega_id', $entregaIds)
        ->get()
        ->map(function ($x) {
            $u = optional(optional($x->cliente)->ubicacion);
            [$lat, $lng] = $this->parseCoords($u?->coordenadas ?? null);

            return [
                'id'        => $x->getKey(),
                'lat'       => is_numeric($lat) ? (float)$lat : null,
                'lng'       => is_numeric($lng) ? (float)$lng : null,
                'barrio'    => $this->resolverBarrio($u),
                'direccion' => $u?->descripcion,
            ];
        })
        ->filter(fn ($p) => $p['lat'] !== null && $p['lng'] !== null)
        ->values();

$couriersDisponibles = DB::table('usuarios')
    ->join('perfiles_has_usuarios', 'usuarios.usuario_id', '=', 'perfiles_has_usuarios.Usuarios_usuario_id')
    ->join('perfiles', 'perfiles.idPerfiles', '=', 'perfiles_has_usuarios.Perfiles_idPerfiles')
    ->where('perfiles.nombre_perfil', 'Courier')
    ->where('usuarios.disponible', 1)
    ->where('usuarios.activo', 1)
    ->count();

  return view('admin.entregas.mapa', [
    'puntos'     => $puntos,
    'year'       => $year,
    'month'      => $month,
    'entregaId'  => $entregaSeleccionada,
    'couriersDisponibles' => $couriersDisponibles,
]);
}


// FUNCIONES PARA GRUPO DE ENTREGAS, VER Y DEMAS

public function gestionarGrupos(Request $request)
{
    $entregaId = $request->filled('entrega_id') ? (int)$request->input('entrega_id') : null;
    $year  = $request->input('year');
    $month = $request->input('month');

    // Grupos + detalles (extracto->cliente->persona/ubicacion) + courier (usuario->persona)
    $grupos = \App\Models\GrupoEntrega::query()
        ->with([
            'detalles.extracto.cliente.persona',
            'detalles.extracto.cliente.ubicacion',
            // relación con usuario courier (abajo te dejo cómo definirla)
            'courier.persona',
        ])
        ->when($entregaId, fn($q)=>$q->where('entrega_id',$entregaId))
        ->orderByDesc('created_at')
        ->paginate(15);

    // Couriers disponibles para el selector (usuarios con perfil "Courier" y activos)
    // Si ya creaste campos disponible/activo, los usamos:
    $couriers = \DB::table('usuarios as u')
        ->join('perfiles_has_usuarios as pu', 'u.usuario_id','=','pu.Usuarios_usuario_id')
        ->join('perfiles as p', 'p.idPerfiles','=','pu.Perfiles_idPerfiles')
        ->join('personas as pe', 'pe.idPersonas','=','u.Personas_idPersonas')
        ->where('p.nombre_perfil','Courier')
        ->where('u.activo',1)
        ->select('u.usuario_id','pe.nombre_persona','pe.apellido_persona')
        ->orderBy('pe.nombre_persona')
        ->get();

    return view('admin.entregas.gestionar_grupos', compact('grupos','entregaId','couriers','year','month'));
}



public function guardarAsignacionesMasivas(Request $request)
{
    $raw = $request->input('asignaciones');
    $asignaciones = json_decode($raw, true) ?: [];

    foreach ($asignaciones as $fila) {
        \Validator::make($fila, [
            'grupo_id'   => 'required|integer|exists:grupos_entrega,grupo_id',
            'id_courier' => 'nullable|integer|exists:usuarios,usuario_id',
        ])->validate();

        \App\Models\GrupoEntrega::where('grupo_id', $fila['grupo_id'])
            ->update(['id_courier' => $fila['id_courier'] ?? null]);
    }

    return redirect()->route('admin.entregas.gestionarGrupos', [
        'entrega_id' => $request->input('entrega_id'),
        'year'       => $request->input('year'),
        'month'      => $request->input('month'),
    ])->with('status', 'Asignaciones guardadas.');
}

public function eliminarGrupo($grupoId)
{
    DB::transaction(function () use ($grupoId) {
        GrupoEntregaDetalle::where('grupo_id', $grupoId)->delete();
        GrupoEntrega::where('grupo_id', $grupoId)->delete();
    });

    return back()->with('status', "Grupo #{$grupoId} eliminado correctamente.");
}

public function asignarCourier(Request $request, $grupoId)
{
    // Próximamente validar y actualizar id_courier
    // $request->validate(['id_courier' => 'required|integer|exists:usuarios,idtable1']);
    // GrupoEntrega::where('grupo_id', $grupoId)->update(['id_courier' => (int)$request->id_courier]);

    return back()->with('status', 'Asignar courier: pendiente de implementar.');
}

}

//---------------------------------------------------------------


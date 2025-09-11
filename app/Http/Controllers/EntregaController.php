<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Entrega;

class EntregaController extends Controller
{
    // Punto base (cambiá por tu base real si querés)
    private float $baseLat = -25.2637;
    private float $baseLng = -57.5759;




   // Listar todas las entregas del gestor autenticado
    public function index()
    {
        $user = Auth::user();
        $entregas = Entrega::where('gestor_id', $user->id)->with('extractos')->get();

        return response()->json($entregas);
    }

    // Crear una nueva entrega
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
     * FASE 1: Mapa con entregas activas (Entregas -> Cliente -> Ubicacion).
     */
    public function mapaEntregas()
    {
        $puntos = Entrega::with(['cliente.ubicacion'])
            ->where('estado', 'activo')
            ->get()
            ->map(function ($e) {
                $u = optional(optional($e->cliente)->ubicacion);
                [$lat, $lng] = $this->parseCoords($u?->coordenadas ?? null);

                // Intentamos mostrar algún nombre de barrio en el popup
                $barrio = $this->resolverBarrio($u);

                return [
                    'id'        => $e->getKey(),
                    'lat'       => is_numeric($lat) ? (float)$lat : null,
                    'lng'       => is_numeric($lng) ? (float)$lng : null,
                    'barrio'    => $barrio,
                    'direccion' => $u?->direccion,
                ];
            })
            ->filter(fn ($p) => $p['lat'] !== null && $p['lng'] !== null)
            ->values();

        return view('admin.entregas.mapa', compact('puntos'));
    }

    /**
     * FASE 2: Generar grupos por barrios (reales o sintéticos) repartidos
     * equitativamente entre K couriers, manteniendo cercanía.
     */
    public function generarRutas(Request $request)
    {
        $k = max(1, (int)$request->input('cantidad_couriers', 3));

        // 1) Traer ENTREGAS activas con cliente->ubicacion
        $entregas = Entrega::with(['cliente.ubicacion'])
            ->where('estado', 'activo')
            ->get()
            ->map(function ($e) {
                $u = optional(optional($e->cliente)->ubicacion);

                // barrio desde distintos campos
                $barrio = $this->resolverBarrio($u);

                // coordenadas desde VARCHAR(45)
                [$lat, $lng] = $this->parseCoords($u?->coordenadas ?? null);

                return [
                    'id'        => $e->getKey(),
                    'lat'       => is_numeric($lat) ? (float)$lat : null,
                    'lng'       => is_numeric($lng) ? (float)$lng : null,
                    'barrio'    => $barrio,           // puede venir null
                    'direccion' => $u?->direccion,
                ];
            })
            ->filter(fn ($p) => $p['lat'] !== null && $p['lng'] !== null)
            ->values()
            ->all();

        if (empty($entregas)) {
            return back()->with('error', 'No hay entregas activas con coordenadas.');
        }

        // 2) Si faltan barrios, generamos “barrios sintéticos” por celdas (~1.1 km)
        $cellSize = 0.01;
        foreach ($entregas as &$e) {
            if ($e['barrio'] === null || $e['barrio'] === '') {
                $e['barrio'] = $this->barrioSinteticoPorCelda($e['lat'], $e['lng'], $cellSize);
            }
        }
        unset($e);

        // 3) Agrupar por barrio y sacar centroides por barrio
        [$barrios, $centroides] = $this->agruparPorBarrioYCentroides($entregas);

        $nBarrios = count($centroides);
        if ($nBarrios === 0) {
            return back()->with('error', 'No se pudieron identificar barrios (ni sintéticos).');
        }

        // Ajustar K si hay menos barrios que couriers
        $k = min($k, $nBarrios);
        if ($k < 1) $k = 1;

        // 4) Partir barrios en K grupos equitativos manteniendo cercanía
        $asignBarrio = $this->partirBarriosBalanceadoPorCercania($centroides, $k);

        // 5) Construir grupos de ENTREGAS según el cluster asignado a cada barrio
        $grupos = array_fill(0, $k, []);
        foreach ($barrios as $nombreBarrio => $entregasDelBarrio) {
            $idx = $asignBarrio[$nombreBarrio] ?? 0;
            foreach ($entregasDelBarrio as $ent) {
                $grupos[$idx][] = $ent;
            }
        }

        // 6) Resumen para la vista de grupos
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

        Session::put('grupos_entregas', $grupos);

        return view('admin.entregas.grupos', [
            'k'       => $k,
            'resumen' => $resumen
        ]);
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
        $ordenado = $this->ordenarPorVecinoMasCercano($grupo, $this->baseLat, $this->baseLng);

        return view('admin.entregas.grupo_detalle', [
            'idx'      => $idx,
            'base'     => ['lat' => $this->baseLat, 'lng' => $this->baseLng],
            'entregas' => $ordenado,
        ]);
    }

    /* ==================== HELPERS ==================== */

    /**
     * Parser robusto para coordenadas VARCHAR(45).
     * Acepta: "lat,lon", "(lat,lon)", "lat lon", "POINT(lon lat)", etc.
     * Devuelve [lat, lng] o [null,null].
     */
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

    /**
     * Intenta obtener el "barrio" desde distintos campos de Ubicacion.
     * Ajustá la lista según tus columnas reales.
     */
    private function resolverBarrio($u): ?string
    {
        if (!$u) return null;

        $candidatos = [
            $u->barrio ?? null,
            $u->compania ?? null,
            $u->localidad ?? null,
            $u->zona ?? null,
            $u->sector ?? null,
            $u->barrio_nombre ?? null,
            $u->colonia ?? null,
        ];

        foreach ($candidatos as $val) {
            $val = is_string($val) ? trim($val) : null;
            if ($val !== null && $val !== '') return $val;
        }
        return null;
    }

    /**
     * Genera un "barrio sintético" agrupando por celdas de tamaño fijo.
     * Ej: BARRIO_(-25.260,-57.580)_0.01
     */
    private function barrioSinteticoPorCelda(float $lat, float $lng, float $size = 0.01): string
    {
        $latCell = floor($lat / $size) * $size;
        $lngCell = floor($lng / $size) * $size;
        return 'BARRIO_(' . number_format($latCell, 3, '.', '') . ',' . number_format($lngCell, 3, '.', '') . ')_' . $size;
    }

    /**
     * Agrupa entregas por barrio y calcula centroides por barrio.
     * @param array $entregas con keys: id, lat, lng, barrio, direccion
     * @return array [$barrios, $centroides]
     *   - $barrios:   ['Barrio X' => [entrega, entrega, ...], ...]
     *   - $centroides:['Barrio X' => ['lat'=>..., 'lng'=>..., 'count'=>N], ...]
     */
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

    /**
     * Divide los barrios en K grupos **equitativos** por cantidad de barrios,
     * manteniendo cercanía (greedy por distancia con capacidad).
     * Retorna: ['Barrio' => clusterIdx]
     */
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
}
<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Entrega;
use App\Models\Extracto;
use App\Models\AsignacionCourier;
use Carbon\Carbon;

class SyncController extends Controller
{
    /**
     * Obtener registros pendientes para gestores
     */
    public function getGestorPendingRecords(Request $request)
    {
    //mas adelante lo que podria validar de vuelta de este lado que sea perfil gestor,
    // recibir por parametro
        try {
            DB::beginTransaction();

            $lastSync = $request->query('lastSync', 0);
            $lastSyncDate = $lastSync > 0 ? Carbon::createFromTimestamp($lastSync) : Carbon::create(1970);

            // Obtener entregas que han cambiado desde la última sincronización
            $pendingRecords = Entrega::where('updated_at', '>', $lastSyncDate)
                ->orWhere('created_at', '>', $lastSyncDate)
                ->orWhere('sync_status', 'pending') // Campo personalizado para control de sync
                //->with(['cliente', 'courier']) // Incluir relaciones necesarias
                ->orderBy('updated_at', 'desc')
                ->limit(100) // Limitar para evitar sobrecarga
                ->get();

            //echo "last de paarametro" . $lastSyncDate . "\n";

            // AQUÍ ACTUALIZAMOS EL SYNC_STATUS DESPUÉS DE ENVIAR
            $entregaIds = $pendingRecords->pluck('entrega_id')->toArray();



            // Transformar datos para la app
            $transformedRecords = $pendingRecords->map(function ($entrega) use ($lastSyncDate)  {
               // Incluir extractos
                $extractosQuery = Extracto::where('entrega_id', $entrega->entrega_id)
                                ->where(function ($query) use ($lastSyncDate) {
                                   // echo "dato: ".$lastSyncDate."\n";
                                    $query->where('updated_at', '>', $lastSyncDate)
                                          ->orWhere('created_at', '>', $lastSyncDate)
                                          ->orWhere('sync_status', 'pending');
                                })
                                //->orWhere('sync_status', 'pending') // Campo personalizado para control de sync
                                //->with(['cliente', 'courier']) // Incluir relaciones necesarias
                                ->orderBy('updated_at', 'desc')
                                ->limit(100)
                                ->get(); // Limitar para evitar sobrecarga

               /* echo "contenido: "."\n";
                $resultados = $extractosQuery->get();
                print_r($resultados->toArray());
                echo "\n";
*/
                // Guardar IDs en variable
                $extractoIds = $extractosQuery->pluck('extracto_id')->toArray();

                if (!empty($extractoIds)) {
                    $updated = Extracto::whereIn('extracto_id', $extractoIds)
                        ->update([
                            'sync_status' => 'synced'
                        ]);

                    // Opcional: debug
                    // echo "Registros actualizados: " . $updated;
                }

               /* echo "luego del if : "."\n";
                $resultados = $extractosQuery->get();
                print_r($resultados->toArray());
                echo "\n";
*/
                return [
                    'entrega_id' => $entrega->entrega_id,
                    'nombre_entrega' => $entrega->nombre_entrega,
                    'estado' => $entrega->estado,
                    'observaciones' => $entrega->observaciones,
                    'created_at' => $entrega->created_at->toDateTimeString(),
                    'updated_at' => $entrega->updated_at->toDateTimeString(),
                    /*'cliente_nombre' => $entrega->cliente->nombre ?? '',
                    'direccion' => $entrega->direccion,
                    'fecha_entrega' => $entrega->fecha_entrega,
                    'courier_id' => $entrega->courier_id,
                    'courier_nombre' => $entrega->courier->nombre ?? '',
                    */
                   'extractos' => $extractosQuery
                    ->map(function($extracto) {

                            return [
                                'extracto_id' => $extracto->extracto_id,
                                'cliente_id' => $extracto->cliente_id,
                                'gestor_id' => $extracto->gestor_id,
                                'entrega_id' => $extracto->entrega_id,
                                'estado' => $extracto->estado,
                                'created_at' => $extracto->created_at->toDateTimeString(),
                                'updated_at' => $extracto->updated_at->toDateTimeString(),
                                /*'fecha_entrega_programada' => $extracto->fecha_entrega_programada,
                                'telefono' => $extracto->cliente_telefono,
                                'coordenadas_lat' => $extracto->coordenadas_lat,
                                'coordenadas_lng' => $extracto->coordenadas_lng,
                                'prioridad' => $extracto->prioridad,
                                'intentos_entrega' => $extracto->intentos_entrega,*/
                            ];
                    })
                ];
            });



            if (!empty($entregaIds)) {
                // Marcar entregas como sincronizadas
                //echo "Entro en if" . "\n";
                //echo "Entregas ids:". implode(', ', $entregaIds) . "\n";

                $updated = Entrega::whereIn('entrega_id', $entregaIds)
                       ->update([
                           'sync_status' => 'synced'
                       ]);


               // echo "Actualizados; " . $updated . "\n";

                // También marcar extractos como sincronizados
                /*Extracto::whereIn('entrega_id', $entregaIds)
                        ->where('sync_status', 'pending')
                        ->update([
                            'sync_status' => 'synced'
                        ]);*/
            }


            DB::commit();

            return response()->json([
                'pendingRecords' => $transformedRecords,
                'lastSyncTimestamp' => now()->timestamp,
                'hasMore' => $pendingRecords->count() >= 100
            ]);


        } catch (\Exception $e) {
            Log::error('Error en sync gestor: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al sincronizar datos',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
     * Obtener Ciudades desde App
     */
    public function getCiudadesConBarrios(Request $request)
    {
        //mas adelante lo que podria validar de vuelta de este lado que sea perfil gestor,
        // recibir por parametro
        try {
            DB::beginTransaction();

            $lastSync = $request->query('lastSync', 0);
            $lastSyncDate = $lastSync > 0 ? Carbon::createFromTimestamp($lastSync) : Carbon::create(1970);

            // Obtener entregas que han cambiado desde la última sincronización
            $pendingRecords = Ciudad::where('updated_at', '>', $lastSyncDate)
                ->orWhere('created_at', '>', $lastSyncDate)
                ->orWhere('sync_status', 'pending') // Campo personalizado para control de sync
                //->with(['cliente', 'courier']) // Incluir relaciones necesarias
                ->orderBy('updated_at', 'desc')
                ->limit(100) // Limitar para evitar sobrecarga
                ->get();

            //echo "last de paarametro" . $lastSyncDate . "\n";

            // Ids para actualizar estado a synced - enviado a app
            $ciudadesIds = $pendingRecords->pluck('id')->toArray();

            $transformedRecords = $pendingRecords->map(function ($ciudad) use ($lastSyncDate)  {
                // Incluir barrios
                $barriosQuery = Barrio::where('ciudad_id', $ciudad->id)
                    ->where(function ($query) use ($lastSyncDate) {
                        $query->where('updated_at', '>', $lastSyncDate)
                            ->orWhere('created_at', '>', $lastSyncDate)
                            ->orWhere('sync_status', 'pending');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->limit(100)
                    ->get(); // Limitar para evitar sobrecarga

                // Guardar IDs en variable
                $barriosIds = $barriosQuery->pluck('id')->toArray();

                if (!empty($barriosIds)) {
                    $updated = Barrio::whereIn('id', $barriosIds)
                        ->update([
                            'sync_status' => 'synced'
                        ]);

                    // debug
                    // echo "Registros actualizados: " . $updated;
                }

                return [
                    'id' => $ciudad->id,
                    'nombre_ciudad' => $ciudad->nombre_ciudad,
                    'cobertura' => $ciudad->cobertura,
                    'created_at' => $ciudad->created_at->toDateTimeString(),
                    'updated_at' => $ciudad->updated_at->toDateTimeString(),
                    'barrios' => $barriosQuery
                        ->map(function($barrio) {
                            return [
                                'id' => $barrio->id,
                                'nombre_barrio' => $barrio->nombre_barrio,
                                'cobertura' => $barrio->cobertura,
                                'created_at' => $barrio->created_at->toDateTimeString(),
                                'updated_at' => $barrio->updated_at->toDateTimeString(),
                            ];
                        })
                ];
            });

            if (!empty($ciudadesIds)) {
                // Marcar ciudades como sincronizadas
                $updated = Ciudad::whereIn('id', $ciudadesIds)
                    ->update([
                        'sync_status' => 'synced'
                    ]);
            }


            DB::commit();

            return response()->json([
                'pendingRecords' => $transformedRecords,
                'lastSyncTimestamp' => now()->timestamp,
                'hasMore' => $pendingRecords->count() >= 100
            ]);


        } catch (\Exception $e) {
            Log::error('Error en sync gestor: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al sincronizar datos',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener asignaciones para couriers
     */
    public function getCourierAssignments($userId, Request $request)
    {
        try {
            $lastSync = $request->query('lastSync', 0);
            $lastSyncDate = $lastSync > 0 ? Carbon::createFromTimestamp($lastSync) : Carbon::create(1970);

            // Obtener entregas asignadas a este courier que han cambiado
            $assignments = Entrega::where('courier_id', $userId)
                ->where(function ($query) use ($lastSyncDate) {
                    $query->where('updated_at', '>', $lastSyncDate)
                          ->orWhere('created_at', '>', $lastSyncDate)
                          ->orWhere('sync_status', 'pending');
                })
                ->with(['cliente'])
                ->orderBy('fecha_entrega', 'asc')
                ->limit(50)
                ->get();

            // Transformar datos para la app del courier
            $transformedAssignments = $assignments->map(function ($entrega) {
                return [
                    'id' => $entrega->id,
                    'cliente_nombre' => $entrega->cliente->nombre ?? '',
                    'cliente_telefono' => $entrega->cliente->telefono ?? '',
                    'direccion' => $entrega->direccion,
                    'direccion_detalle' => $entrega->direccion_detalle,
                    'estado' => $entrega->estado,
                    'fecha_entrega' => $entrega->fecha_entrega,
                    'hora_entrega' => $entrega->hora_entrega,
                    'observaciones' => $entrega->observaciones,
                    'prioridad' => $entrega->prioridad ?? 'normal',
                    'valor_cobrar' => $entrega->valor_cobrar ?? 0,
                    'created_at' => $entrega->created_at->timestamp,
                    'updated_at' => $entrega->updated_at->timestamp,
                    'latitude' => $entrega->latitude,
                    'longitude' => $entrega->longitude,
                ];
            });

            return response()->json([
                'pendingRecords' => $transformedAssignments,
                'lastSyncTimestamp' => now()->timestamp,
                'hasMore' => $assignments->count() >= 50,
                'recordsCount' => $transformedAssignments->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error en sync courier: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al sincronizar asignaciones',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recibir cambios desde la app (upload)
     */
    public function uploadEntregas(Request $request)
    {
        try {
            // Recuperar el JSON como array
            $data = $request->json()->all();

            // Recuperar el parámetro como string
            $tabla = $data['tabla'] ?? 'error';
           // echo "Tabla: " . $tabla . "\n";
            $lastSync = $data['fecha'] ?? 0;
            $lastSyncDate = $lastSync > 0 ? Carbon::createFromTimestamp($lastSync) : Carbon::create(1970);

            //echo "fecha: ". $lastSyncDate ."\n";
            $success = [];
            $failed = [];
            // Decodificar a array asociativo
            $changes = $data['cambios'] ?? [];
            // $changes = json_decode($changesRaw, true);
            /*$userType = $request->json('userType');
            $userId = $request->json('userId');
            */
            //verificar error de formato
            if (!is_array($changes)) {
               return response()->json([
                    'success' => [],
                    'failed' => [['error' => 'Formato inválido']]
                ], 400);
           }

          // echo "Cambios: " . count($changes) ."\n";
            foreach ($changes as $change) {
                switch ($tabla) {
                    case "entregas":
                        //echo "Entregas" . "\n";
                        // Buscar si ya existe por UUID
                       try{
                           $entrega = Entrega::where('entrega_id', $change['entrega_id'])->first();

                           if ($entrega) {
                               $entrega->update($change);
                               $message = 'Entrega actualizada correctamente';
                           } else {
                               // si se agregan campos a la tabla agregar a fillable del modelo entrega
                               $entrega = Entrega::create($change);
                               $message = 'Entrega creada correctamente';
                           }

                           $success[] = $entrega->entrega_id;
                       }catch (\Exception $e) {
                           $failed[] = [
                               'entrega_id' => $change['entrega_id'] ?? null,
                               'error' => $e->getMessage()
                           ];
                       }

                    break;

                    case "extractos":

                    break;
                }
            }

            return response()->json([
                'success' => $success,
                'failed' => $failed
            ]);

        } catch (\Exception $e) {
            Log::error('Error al procesar cambios: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al procesar cambios',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function processUpdateFromApp($change, $userType, $userId)
    {
        if ($change['table'] === 'entregas') {
            $entrega = Entrega::find($change['recordId']);
            if ($entrega) {
                // Validar permisos según tipo de usuario
                if ($userType === 'COURIER' && $entrega->courier_id != $userId) {
                    return; // No autorizado
                }

                // Aplicar cambios
                foreach ($change['data'] as $field => $value) {
                    $entrega->$field = $value;
                }

                $entrega->sync_status = 'synced';
                $entrega->save();

                Log::info("Entrega {$entrega->id} actualizada desde app por usuario {$userId}");
            }
        }
    }

    private function processCreateFromApp($change, $userType, $userId)
    {
        // Lógica para crear registros desde la app
        // Implementar según necesidades
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Extracto;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtractoController extends Controller
{
    // Listar extractos por entrega
    public function index($entregaId)
    {
        $extractos = Extracto::where('entrega_id', $entregaId)->with(['cliente', 'gestor'])->get();
        return response()->json($extractos);
    }

    // Crear un extracto asociado a una entrega
    public function store(Request $request, $entregaId)
    {
        $request->validate([
            'cliente_id' => 'required|integer',
            'estado' => 'required|integer',
            'orden_ruta' => 'nullable|integer',
        ]);

        $extracto = Extracto::create([
            'entrega_id' => $entregaId,
            'cliente_id' => $request->cliente_id,
            'estado' => $request->estado,
            'orden_ruta' => $request->orden_ruta,
            'gestor_id' => Auth::id(),
        ]);

        return response()->json($extracto, 201);
    }

    // Mostrar un extracto
    public function show($id)
    {
        $extracto = Extracto::with(['entrega', 'cliente', 'gestor'])->findOrFail($id);
        return response()->json($extracto);
    }

    // Actualizar extracto
    public function update(Request $request, $id)
    {
        $extracto = Extracto::findOrFail($id);

        //$this->authorize('update', $extracto);

        $extracto->update($request->only(['estado', 'sync_status', 'orden_ruta']));

        return response()->json($extracto);
    }

    // Eliminar extracto
    public function destroy($id)
    {
        $extracto = Extracto::findOrFail($id);

        $this->authorize('delete', $extracto);

        $extracto->delete();

        return response()->json(['message' => 'Extracto eliminado correctamente']);
    }
}

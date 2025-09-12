<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use Illuminate\Http\Request;

class BarrioController extends Controller
{
    // Mostrar todos los barrios
    public function index()
    {
        return Barrio::with('ciudad')->get();
    }

    // Crear un nuevo barrio
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_barrio' => 'required|string|max:255',
            'cobertura' => 'boolean',
            'ciudad_id' => 'required|exists:ciudades,id',
        ]);

        $barrio = Barrio::create($validated);

        return response()->json($barrio, 201);
    }

    // Mostrar un barrio específico
    public function show(Barrio $barrio)
    {
        return $barrio->load('ciudad');
    }

    // Actualizar un barrio
    public function update(Request $request, Barrio $barrio)
    {
        $validated = $request->validate([
            'nombre_barrio' => 'sometimes|required|string|max:255',
            'cobertura' => 'sometimes|boolean',
            'ciudad_id' => 'sometimes|required|exists:ciudades,id',
        ]);

        $barrio->update($validated);

        return response()->json($barrio);
    }

    // Eliminar un barrio
    public function destroy(Barrio $barrio)
    {
        $barrio->delete();
        return response()->json(['message' => 'Barrio eliminado correctamente']);
    }
}

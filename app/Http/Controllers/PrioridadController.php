<?php

namespace App\Http\Controllers;

use App\Models\Prioridad;
use Illuminate\Http\Request;

class PrioridadController extends Controller
{
    // Muestra la lista de prioridades
    public function index()
    {
        $prioridades = Prioridad::all();
        return view('prioridades.index', compact('prioridades'));
    }

    // Muestra el formulario para crear una nueva prioridad
    public function create()
    {
        return view('prioridades.create');
    }

    // Guarda una nueva prioridad en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre_prioridad' => 'required|string|max:45',
        ]);

        Prioridad::create($request->all());

        return redirect()->route('prioridades.index')->with('success', 'Prioridad creada correctamente');
    }

    // Muestra el formulario para editar una prioridad existente
    public function edit($id)
    {
        $prioridad = Prioridad::findOrFail($id);
        return view('prioridades.edit', compact('prioridad'));
    }

    // Actualiza una prioridad existente en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_prioridad' => 'required|string|max:45',
        ]);

        $prioridad = Prioridad::findOrFail($id);
        $prioridad->update($request->all());

        return redirect()->route('prioridades.index')->with('success', 'Prioridad actualizada correctamente');
    }

    // Elimina una prioridad de la base de datos
    public function destroy($id)
    {
        $prioridad = Prioridad::findOrFail($id);
        $prioridad->delete();

        return redirect()->route('prioridades.index')->with('success', 'Prioridad eliminada correctamente');
    }
}
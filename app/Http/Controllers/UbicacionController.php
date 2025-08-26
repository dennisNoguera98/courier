<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\Request;

class UbicacionController extends Controller
{
    // Mostrar todas las ubicaciones
    public function index()
    {
        $ubicaciones = Ubicacion::all();
        return view('ubicaciones.index', compact('ubicaciones'));
    }

    // Mostrar el formulario para crear una nueva ubicación
    public function create()
    {
        return view('ubicaciones.create');
    }

    // Guardar una nueva ubicación
    public function store(Request $request)
    {
        $request->validate([
            'coordenadas' => 'required|string|max:45',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|string|max:45',
            'fecha_hora' => 'required|date',
            'Clientes_idClientes' => 'required|exists:clientes,idClientes',
        ]);

        Ubicacion::create($request->all());

        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación creada exitosamente.');
    }

    // Mostrar el formulario para editar una ubicación existente
    public function edit($id)
    {
        $ubicacion = Ubicacion::findOrFail($id);
        return view('ubicaciones.edit', compact('ubicacion'));
    }

    // Actualizar una ubicación existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'coordenadas' => 'required|string|max:45',
            'descripcion' => 'required|string|max:200',
            'estado' => 'required|string|max:45',
            'fecha_hora' => 'required|date',
            'Clientes_idClientes' => 'required|exists:clientes,idClientes',
        ]);

        $ubicacion = Ubicacion::findOrFail($id);
        $ubicacion->update($request->all());

        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación actualizada exitosamente.');
    }

    // Eliminar una ubicación
    public function destroy($id)
    {
        $ubicacion = Ubicacion::findOrFail($id);
        $ubicacion->delete();

        return redirect()->route('ubicaciones.index')->with('success', 'Ubicación eliminada exitosamente.');
    }
}
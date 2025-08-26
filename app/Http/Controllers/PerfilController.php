<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index()
    {
        $perfiles = Perfil::all();
        return view('perfiles.index', compact('perfiles'));
    }

    public function create()
    {
        return view('perfiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_perfil' => 'required|max:45',
            'descripcion_perfil' => 'nullable|max:200',
        ]);

        Perfil::create($request->all());

        return redirect()->route('perfiles.index')->with('success', 'Perfil creado correctamente.');
    }

    public function edit($id)
    {
        $perfil = Perfil::findOrFail($id);
        return view('perfiles.edit', compact('perfil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_perfil' => 'required|max:45',
            'descripcion_perfil' => 'nullable|max:200',
        ]);

        $perfil = Perfil::findOrFail($id);
        $perfil->update($request->all());

        return redirect()->route('perfiles.index')->with('success', 'Perfil actualizado correctamente.');
    }

    public function destroy($id)
    {
        Perfil::destroy($id);
        return redirect()->route('perfiles.index')->with('success', 'Perfil eliminado correctamente.');
    }
}

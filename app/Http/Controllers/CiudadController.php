<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
    {
        $ciudades = Ciudad::all();
        return response()->json($ciudades);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_ciudad' => 'required|max:255'
        ]);

        $ciudad = Ciudad::create($request->all());

        return response()->json($ciudad, 201);
    }

    public function show($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        return response()->json($ciudad);
    }

    public function update(Request $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:255|unique:ciudades,' . $id
        ]);

        $ciudad->update($request->all());

        return response()->json($ciudad);
    }

    public function destroy($id)
    {
        Ciudad::destroy($id);
        return response()->json(null, 204);
    }
}

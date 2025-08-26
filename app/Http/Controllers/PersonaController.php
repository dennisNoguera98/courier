<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Persona;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $personas = Persona::all();
        return view('personas.index', compact('personas'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function create()
     {
         return view('personas.create');
     }
     
     public function store(Request $request)
     {
         // Validación de los datos
         $request->validate([
             'nombre_persona' => 'required|string|max:45',
             'apellido_persona' => 'required|string|max:45',
             'coordenadas_persona' => 'nullable|string|max:45',
             'direccion_persona' => 'required|string|max:200',
             'celular_principal_persona' => 'required|string|max:45',
             'celular_secundario_persona' => 'nullable|string|max:40',
             'observacion' => 'nullable|string|max:200',
         ]);
     
         // Creación de la persona
         Persona::create($request->all());
     
         // Redireccionar con mensaje de éxito
         return redirect()->route('personas.index')->with('success', 'Persona agregada correctamente');
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idPersonas)
    {
        $persona = Persona::findOrFail($idPersonas);
        return view('personas.edit', compact('persona'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);
        
        $request->validate([
            'nombre_persona' => 'required|string|max:45',
            'apellido_persona' => 'required|string|max:45',
            'coordenadas_persona' => 'nullable|string|max:45',
            'direccion_persona' => 'required|string|max:200',
            'celular_principal_persona' => 'required|string|max:45',
            'celular_secundario_persona' => 'nullable|string|max:40',
            'observacion' => 'nullable|string|max:200',
        ]);
    
        $persona->update($request->all());
    
        return redirect()->route('personas.index')->with('success', 'Persona actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idPersonas)
    {
        $persona = Persona::findOrFail($idPersonas); // Busca la persona, si no la encuentra, lanza un error
        $persona->delete(); // Elimina la persona
    
        return redirect()->route('personas.index')->with('success', 'Persona eliminada correctamente');
    }
}

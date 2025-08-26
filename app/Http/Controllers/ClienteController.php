<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Prioridad;
use Illuminate\Http\Request;
use App\Models\Ubicacion;

class ClienteController extends Controller
{
    // Mostrar lista de clientes
    /*public function index()
    {
        $clientes = Cliente::with(['persona', 'prioridad'])->get();
        return view('clientes.index', compact('clientes'));
    }*/

    public function index()
    {
        $clientes = Cliente::with(['persona', 'prioridad', 'ubicacion'])->get();
        return view('clientes.index', compact('clientes'));
    }

    // Mostrar formulario de creación
   /* public function create()
    {
        $personas = Persona::all();
        $prioridades = Prioridad::all();
        return view('clientes.create', compact('personas', 'prioridades'));
    }*/

    public function create()
    {
        $prioridades = Prioridad::all();
        return view('clientes.create', compact('prioridades'));
    }

    // Guardar nuevo cliente
    public function store(Request $request)
{
    $request->validate([
        'nombre_persona' => 'required|string|max:45',
        'apellido_persona' => 'required|string|max:45',
        'direccion_persona' => 'required|string|max:200',
        'celular_principal_persona' => 'required|string|max:45',
        'prioridad_id' => 'required|exists:prioridades,idPrioridades',
        'descripcion' => 'required|string|max:200',
        'coordenadas' => 'required|string|max:45',
        'estado' => 'required|string|max:45',
        'cedula' => 'required|string|max:20|unique:personas,cedula',
        
    ]);

    // Crear persona
    $persona = Persona::create([
        'nombre_persona' => $request->nombre_persona,
        'apellido_persona' => $request->apellido_persona,
        'direccion_persona' => $request->direccion_persona,
        'celular_principal_persona' => $request->celular_principal_persona,
        'celular_secundario_persona' => $request->celular_secundario_persona,
        'observacion' => $request->observacion,
        'cedula' => $request->cedula,
    ]);

    // Buscar ubicación existente
    $ubicacion = Ubicacion::where('coordenadas', $request->coordenadas)->first();

    if (!$ubicacion) {
        $ubicacion = Ubicacion::create([
            'descripcion' => $request->descripcion,
            'coordenadas' => $request->coordenadas,
            'estado' => $request->estado,
            'fecha_hora' => now(),
        ]);
    }

    // Crear cliente
    Cliente::create([
        'Personas_idPersonas' => $persona->idPersonas,
        'Prioridades_idPrioridades' => $request->prioridad_id,
        'Ubicaciones_idUbicaciones' => $ubicacion->idUbicaciones,
    ]);

    return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
}

    // Mostrar formulario de edición

    public function edit($id)
    {
        $cliente = Cliente::with('persona', 'ubicacion', 'prioridad')->findOrFail($id);
        $prioridades = Prioridad::all();
    
        return view('clientes.edit', compact('cliente', 'prioridades'));
    }
    
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $persona = $cliente->persona;
    
        // Validación ahora que tenemos $persona->idPersonas
        $request->validate([
            'nombre_persona' => 'required|string|max:45',
            'apellido_persona' => 'required|string|max:45',
            'direccion_persona' => 'required|string|max:200',
            'celular_principal_persona' => 'required|string|max:45',
            'cedula' => 'required|string|max:45|unique:personas,cedula,' . $persona->idPersonas . ',idPersonas',
            'Prioridades_idPrioridades' => 'required|exists:prioridades,idPrioridades',
            'descripcion' => 'required|string|max:200',
            'coordenadas' => 'required|string|max:100',
        ]);
    
        // Actualizar persona
        $persona->update([
            'nombre_persona' => $request->nombre_persona,
            'apellido_persona' => $request->apellido_persona,
            'direccion_persona' => $request->direccion_persona,
            'celular_principal_persona' => $request->celular_principal_persona,
            'celular_secundario_persona' => $request->celular_secundario_persona,
            'observacion' => $request->observacion,
            'cedula' => $request->cedula,
        ]);
    
        // Verificar si ya existe la ubicación
        $ubicacion = Ubicacion::where('coordenadas', $request->coordenadas)->first();
    
        if (!$ubicacion) {
            $ubicacion = Ubicacion::create([
                'descripcion' => $request->descripcion,
                'coordenadas' => $request->coordenadas,
                'estado' => 'activo',
                'fecha_hora' => now(),
            ]);
        }
    
        // Actualizar cliente
        $cliente->update([
            'Prioridades_idPrioridades' => $request->Prioridades_idPrioridades,
            'Ubicaciones_idUbicaciones' => $ubicacion->idUbicaciones,
        ]);
    
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Eliminar cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
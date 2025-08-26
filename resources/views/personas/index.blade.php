@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Personas</h1>
    <a href="{{ route('personas.create') }}" class="btn btn-primary mb-3">Agregar Persona</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono Principal</th>
                <th>Teléfono Secundario</th>
                <th>Observación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($personas as $persona)
                <tr>
                    <td>{{ $persona->idPersonas }}</td>
                    <td>{{ $persona->nombre_persona }}</td>
                    <td>{{ $persona->apellido_persona }}</td>
                    <td>{{ $persona->direccion_persona }}</td>
                    <td>{{ $persona->celular_principal_persona }}</td>
                    <td>{{ $persona->celular_secundario_persona ?? 'N/A' }}</td>
                    <td>{{ $persona->observacion ?? 'Sin observación' }}</td>
                    <td>
                    <a href="{{ route('personas.edit', $persona->idPersonas) }}" class="btn btn-warning btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $persona->idPersonas }})">Eliminar</button>

<form id="delete-form-{{ $persona->idPersonas }}" action="{{ route('personas.destroy', $persona->idPersonas) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
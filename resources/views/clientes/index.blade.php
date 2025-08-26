@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Listado de Clientes</h2>

    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Agregar Cliente</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre y Apellido</th>
                <th>Cédula</th>
                <th>Celular</th>
                <th>Dirección</th>
                <th>Prioridad</th>
                <th>Ubicación</th>
                <th>Coordenadas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->idClientes }}</td>
                    <td>{{ $cliente->persona->nombre_persona }} {{ $cliente->persona->apellido_persona }}</td>
                    <td>{{ $cliente->persona->cedula }}</td>
                    <td>{{ $cliente->persona->celular_principal_persona }}</td>
                    <td>{{ $cliente->persona->direccion_persona }}</td>
                    <td>{{ $cliente->prioridad->nombre_prioridad }}</td>
                    <td>{{ $cliente->ubicacion->descripcion ?? 'Sin ubicación' }}</td>
                    <td>{{ $cliente->ubicacion->coordenadas ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('clientes.edit', $cliente->idClientes) }}" class="btn btn-sm btn-warning">Editar</a>

                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $cliente->idClientes }})">Eliminar</button>

<form id="delete-form-{{ $cliente->idClientes }}" action="{{ route('clientes.destroy', $cliente->idClientes) }}" method="POST" class="d-none">
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
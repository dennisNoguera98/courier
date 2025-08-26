@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Perfiles</h1>

    <a href="{{ route('perfiles.create') }}" class="btn btn-primary mb-3">Agregar Perfil</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Perfil</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perfiles as $perfil)
                <tr>
                    <td>{{ $perfil->idPerfiles }}</td>
                    <td>{{ $perfil->nombre_perfil }}</td>
                    <td>{{ $perfil->descripcion_perfil }}</td>
                    <td>
                        <a href="{{ route('perfiles.edit', $perfil->idPerfiles) }}" class="btn btn-warning btn-sm">Editar</a>

                        <!-- Botón para eliminar con el modal -->
                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion({{ $perfil->idPerfiles }})">Eliminar</button>

<form id="delete-form-{{ $perfil->idPerfiles }}" action="{{ route('perfiles.destroy', $perfil->idPerfiles) }}" method="POST" class="d-none">
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
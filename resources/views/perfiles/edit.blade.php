@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Perfil</h1>

    <form action="{{ route('perfiles.update', $perfil->idPerfiles) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="nombre_perfil">Nombre del Perfil</label>
            <input type="text" name="nombre_perfil" class="form-control" value="{{ $perfil->nombre_perfil }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="descripcion_perfil">Descripción</label>
            <textarea name="descripcion_perfil" class="form-control" required>{{ $perfil->descripcion_perfil }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('perfiles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Nuevo Perfil</h1>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de creación --}}
    <form action="{{ route('perfiles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre_perfil" class="form-label">Nombre del Perfil</label>
            <input type="text" name="nombre_perfil" class="form-control" value="{{ old('nombre_perfil') }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion_perfil" class="form-label">Descripción</label>
            <textarea name="descripcion_perfil" class="form-control" rows="3" required>{{ old('descripcion_perfil') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Perfil</button>
        <a href="{{ route('perfiles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Persona</h1>
    <form action="{{ route('personas.update', $persona->idPersonas) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre_persona" class="form-control" value="{{ $persona->nombre_persona }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido_persona" class="form-control" value="{{ $persona->apellido_persona }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Coordenadas</label>
            <input type="text" name="coordenadas_persona" class="form-control" value="{{ $persona->coordenadas_persona }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion_persona" class="form-control" value="{{ $persona->direccion_persona }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Celular Principal</label>
            <input type="text" name="celular_principal_persona" class="form-control" value="{{ $persona->celular_principal_persona }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Celular Secundario</label>
            <input type="text" name="celular_secundario_persona" class="form-control" value="{{ $persona->celular_secundario_persona }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Observación</label>
            <textarea name="observacion" class="form-control">{{ $persona->observacion }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Agregar Nueva Persona</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('personas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre_persona" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre_persona" name="nombre_persona" required>
        </div>

        <div class="mb-3">
            <label for="apellido_persona" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido_persona" name="apellido_persona" required>
        </div>

        <div class="mb-3">
            <label for="coordenadas_persona" class="form-label">Coordenadas</label>
            <input type="text" class="form-control" id="coordenadas_persona" name="coordenadas_persona">
        </div>

        <div class="mb-3">
            <label for="direccion_persona" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion_persona" name="direccion_persona" required>
        </div>

        <div class="mb-3">
            <label for="celular_principal_persona" class="form-label">Celular Principal</label>
            <input type="text" class="form-control" id="celular_principal_persona" name="celular_principal_persona" required>
        </div>

        <div class="mb-3">
            <label for="celular_secundario_persona" class="form-label">Celular Secundario</label>
            <input type="text" class="form-control" id="celular_secundario_persona" name="celular_secundario_persona">
        </div>

        <div class="mb-3">
            <label for="observacion" class="form-label">Observación</label>
            <textarea class="form-control" id="observacion" name="observacion"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar Persona</button>
        <a href="{{ route('personas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Cliente</h2>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <h4>Datos personales</h4>
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre_persona" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" name="apellido_persona" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Cédula</label>
            <input type="text" name="cedula" class="form-control">
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion_persona" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Celular Principal</label>
            <input type="text" name="celular_principal_persona" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Celular Secundario</label>
            <input type="text" name="celular_secundario_persona" class="form-control">
        </div>
        <div class="mb-3">
            <label>Observación</label>
            <textarea name="observacion" class="form-control"></textarea>
        </div>

        <h4>Prioridad</h4>
        <div class="mb-3">
            <label>Seleccionar Prioridad</label>
            <select name="prioridad_id" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                @foreach($prioridades as $prioridad)
                    <option value="{{ $prioridad->idPrioridades }}">{{ $prioridad->nombre_prioridad }}</option>
                @endforeach
            </select>
        </div>

        <h4>Ubicación</h4>
        <div class="mb-3">
            <label>Coordenadas</label>
            <input type="text" name="coordenadas" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <input type="text" name="estado" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
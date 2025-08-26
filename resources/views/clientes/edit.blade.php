@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Cliente</h2>
    <form action="{{ route('clientes.update', $cliente->idClientes) }}" method="POST">
        @csrf
        @method('PUT')

        <h5>Datos Personales</h5>
        <div class="row mb-2">
            <div class="col">
                <label>Nombre</label>
                <input type="text" name="nombre_persona" class="form-control" value="{{ old('nombre_persona', $cliente->persona->nombre_persona) }}" required>
            </div>
            <div class="col">
                <label>Apellido</label>
                <input type="text" name="apellido_persona" class="form-control" value="{{ old('apellido_persona', $cliente->persona->apellido_persona) }}" required>
            </div>
        </div>
        <div class="mb-2">
            <label>Dirección</label>
            <input type="text" name="direccion_persona" class="form-control" value="{{ old('direccion_persona', $cliente->persona->direccion_persona) }}" required>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label>Celular Principal</label>
                <input type="text" name="celular_principal_persona" class="form-control" value="{{ old('celular_principal_persona', $cliente->persona->celular_principal_persona) }}" required>
            </div>
            <div class="col">
                <label>Celular Secundario</label>
                <input type="text" name="celular_secundario_persona" class="form-control" value="{{ old('celular_secundario_persona', $cliente->persona->celular_secundario_persona) }}">
            </div>
        </div>
        <div class="mb-2">
            <label>Observación</label>
            <textarea name="observacion" class="form-control">{{ old('observacion', $cliente->persona->observacion) }}</textarea>
        </div>
        <div class="mb-2">
            <label>Cédula</label>
            <input type="text" name="cedula" class="form-control" value="{{ old('cedula', $cliente->persona->cedula) }}" required>
        </div>

        <h5>Ubicación</h5>
        <div class="mb-2">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" value="{{ old('descripcion', $cliente->ubicacion->descripcion) }}" required>
        </div>
        <div class="mb-2">
            <label>Coordenadas</label>
            <input type="text" name="coordenadas" class="form-control" value="{{ old('coordenadas', $cliente->ubicacion->coordenadas) }}" required>
        </div>

        <h5>Prioridad</h5>
        <div class="mb-3">
            <select name="Prioridades_idPrioridades" class="form-select" required>
                <option value="">Seleccionar prioridad</option>
                @foreach($prioridades as $prioridad)
                    <option value="{{ $prioridad->idPrioridades }}" {{ $cliente->Prioridades_idPrioridades == $prioridad->idPrioridades ? 'selected' : '' }}>
                        {{ $prioridad->nombre_prioridad }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar Cliente</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
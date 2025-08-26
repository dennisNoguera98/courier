@extends('layouts.app')

@section('title', 'Registro de Usuario')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Registro de Usuario</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>¡Ups!</strong> Hay algunos problemas con tus datos:<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('registro.store') }}" method="POST">
                        @csrf

                        {{-- Datos de la Persona --}}
                        <h5 class="text-primary">Datos Personales</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_persona">Nombre</label>
                                <input type="text" name="nombre_persona" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido_persona">Apellido</label>
                                <input type="text" name="apellido_persona" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion_persona">Dirección</label>
                            <input type="text" name="direccion_persona" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="celular_principal_persona">Celular Principal</label>
                                <input type="text" name="celular_principal_persona" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="celular_secundario_persona">Celular Secundario</label>
                                <input type="text" name="celular_secundario_persona" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="observacion">Observación</label>
                            <textarea name="observacion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="cedula">Cédula</label>
                            <input type="text" name="cedula" class="form-control" required>
                        </div>

                        <hr>

                        {{-- Datos de Usuario --}}
                        <h5 class="text-primary">Datos de Usuario</h5>
                        <div class="mb-3">
                            <label for="Usuarios_usuario">Usuario</label>
                            <input type="text" name="Usuarios_usuario" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Usuarios_contrasena">Contraseña</label>
                                <input type="password" name="Usuarios_contrasena" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Usuarios_contrasena_confirmation">Confirmar Contraseña</label>
                                <input type="password" name="Usuarios_contrasena_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-block">Registrar</button>
                        </div>

                        <div class="mt-3 text-center">
                            ¿Ya tienes una cuenta? <a href="{{ route('login.form') }}">Iniciar sesión</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
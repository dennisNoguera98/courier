<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Courier Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 10%;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2 class="text-center mb-4">Courier Management</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="mb-3">
            <label for="Usuarios_usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="Usuarios_usuario" name="Usuarios_usuario" required>
        </div>
        <div class="mb-3">
            <label for="Usuarios_contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="Usuarios_contrasena" name="Usuarios_contrasena" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        <div class="text-center mt-3">
            <a href="{{ route('register') }}">¿No tienes cuenta? Crear una</a>
        </div>
    </form>
</div>
</body>
</html>
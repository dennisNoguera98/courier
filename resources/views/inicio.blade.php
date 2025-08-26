<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Courier Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #dbe6f6, #c5796d);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .main-card {
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
            background-color: #fff;
            width: 400px;
        }
        h2 {
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .btn-custom {
            display: block;
            width: 100%;
            margin-bottom: 20px;
            padding: 18px;
            font-size: 1.2rem;
            border: none;
            border-radius: 12px;
            color: #fff;
            background: linear-gradient(to right, #667eea, #764ba2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="main-card text-center">
        <h2>💳 Courier Management</h2>

        @php
            $usuario = Auth::user();
            $perfiles = $usuario?->perfiles->pluck('nombre_perfil')->toArray() ?? [];
        @endphp

        @if (in_array('Courrier', $perfiles))
            <a href="{{ route('admin.entregas') }}" class="btn btn-custom">Entregas</a>
        @else
            <a href="{{ route('clientes.index') }}" class="btn btn-custom">Clientes</a>
            <a href="{{ route('perfiles.index') }}" class="btn btn-custom">Perfiles</a>
            <a href="{{ route('admin.entregas') }}" class="btn btn-custom">Entregas</a>
            <a href="{{ route('reportes.index') }}" class="btn btn-custom">Reportes</a>
        @endif

    </div>
</body>
</html>


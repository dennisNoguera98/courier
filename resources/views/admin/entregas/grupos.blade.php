<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Grupos de Entrega</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-3">
<div class="container">
  <h3>Grupos generados ({{ $k }} courier(s))</h3>
  <p class="text-muted">Seleccioná un grupo para ver sus entregas en orden.</p>

  <div class="list-group">
    @foreach($resumen as $g)
      <a href="{{ route('admin.entregas.grupo', ['idx' => $g['idx']]) }}" class="list-group-item list-group-item-action">
        <strong>Entrega {{ $g['idx'] }}</strong>
        <span class="text-muted">— {{ $g['total'] }} entregas — Barrios: {{ implode(', ', $g['barrios']) }}</span>
      </a>
    @endforeach
  </div>
  <span class="text-muted">— {{ $g['total'] }} entregas — Barrios: {{ implode(', ', $g['barrios']) }}</span>

  <a class="btn btn-secondary mt-3" href="{{ route('admin.entregas') }}">Volver al mapa</a>
</div>
</body>
</html>
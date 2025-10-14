<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Seleccionar Mes de Entregas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .month-btn {
      height: 56px;
      font-size: 1.05rem;
      display: flex; align-items: center; justify-content: center;
      border-radius: 12px;
    }
    .year-select { border-radius: 12px; }
    .sticky-side { position: sticky; top: 16px; }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex align-items-center mb-4">
    <h3 class="mb-0">Entregas por mes</h3>
    <a href="{{ route('inicio') }}" class="btn btn-outline-secondary ms-auto">← Volver</a>
  </div>

  <div class="row g-4">
    {{-- Columna lateral: selector de año --}}
    <div class="col-12 col-md-3">
      <div class="card shadow-sm sticky-side">
        <div class="card-body">
          <label for="year" class="form-label">Año</label>
          <form method="GET" action="{{ route('admin.entregas.selector') }}">
            <select id="year" name="year" class="form-select year-select" onchange="this.form.submit()">
              @foreach($years as $y)
                <option value="{{ $y }}" {{ (int)$y === (int)$selectedYear ? 'selected' : '' }}>
                  {{ $y }}
                </option>
              @endforeach
            </select>
          </form>
          <small class="text-muted d-block mt-2">Elegí un año para ver los meses disponibles.</small>
        </div>
      </div>
    </div>

    {{-- Columna principal: solo meses con cabeceras --}}
    <div class="col-12 col-md-9">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Meses de {{ $selectedYear }}</h5>

          @php
            $labels = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                       7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
            $mesesConDatos = array_keys($months ?? []);
            sort($mesesConDatos);
          @endphp

          @if(!empty($mesesConDatos))
            <div class="d-flex flex-column gap-2">
              @foreach($mesesConDatos as $m)
                <a class="btn btn-primary month-btn w-100 text-start"
                   href="{{ route('admin.entregas.mes', ['year' => $selectedYear, 'month' => $m]) }}">
                  {{ $labels[$m] }}
                </a>
              @endforeach
            </div>
          @else
            <div class="alert alert-warning mb-0">
              No hay cabeceras de entrega registradas en {{ $selectedYear }}.
            </div>
          @endif

          <div class="mt-3">
            <small class="text-muted">Solo se muestran los meses con cabeceras disponibles.</small>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</body>
</html>
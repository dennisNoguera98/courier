<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>
    @php
      $titulo = 'Entregas activas';
      if (!empty($year) && !empty($month)) {
        try {
          $titulo = 'Entregas de ' . \Carbon\Carbon::create($year, $month, 1)->locale('es')->isoFormat('MMMM YYYY');
        } catch (\Throwable $e) {}
      }
    @endphp
    {{ ucfirst($titulo) }}
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
  <style>#map{height:70vh}</style>
</head>
<body class="p-3">
<div class="container">

  {{-- Encabezado y controles alineados --}}
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
    {{-- Título y meta --}}
    <div>
      <h3 class="mb-0">{{ ucfirst($titulo) }}</h3>
      @isset($puntos)
        <div class="text-muted small mt-1">
          Puntos mostrados: {{ is_countable($puntos) ? count($puntos) : 0 }}
        </div>
      @endisset
    </div>

    {{-- Controles a la derecha en una sola línea --}}
    <div class="d-flex align-items-center gap-2">
      {{-- Couriers disponibles (badge) --}}
      <span class="badge rounded-pill text-bg-info px-3 py-2">
        <strong>Couriers disponibles:</strong> {{ $couriersDisponibles }}
      </span>

      {{-- Form Generar rutas (usa couriers disponibles automáticamente) --}}
      <form method="POST" action="{{ route('admin.entregas.generar') }}" class="m-0">
        @csrf
        @if(!empty($year))  <input type="hidden" name="year" value="{{ (int)$year }}"> @endif
        @if(!empty($month)) <input type="hidden" name="month" value="{{ (int)$month }}"> @endif
        @if(!empty($entregaId)) <input type="hidden" name="entrega_id" value="{{ $entregaId }}"> @endif

        <button class="btn btn-primary" type="submit" {{ empty($entregaId) ? 'disabled' : '' }}>
          Generar rutas
        </button>
      </form>

      {{-- Gestionar grupos --}}
   <a class="btn btn-outline-primary {{ empty($entregaId) ? 'disabled' : '' }}"
   href="{{ empty($entregaId) ? '#' : route('admin.entregas.gestionarGrupos', [
     'entrega_id' => $entregaId,
     'year' => $year,
     'month' => $month,
   ]) }}">
  Gestionar grupos
</a>

<a href="{{ route('admin.entregas.selector') }}" class="btn btn-outline-secondary">
  ← Volver al selector de mes
</a>

    </div>
  </div>

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div id="map" class="rounded border"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
  // Puntos desde el controlador
  const puntos = @json($puntos ?? []);

  // Base desde el controlador (con fallback seguro)
  const base = [{{ $baseLat ?? -25.5075 }}, {{ $baseLng ?? -57.5555 }}];

  const map = L.map('map').setView(base, 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19}).addTo(map);

  const baseMarker = L.marker(base, {
    title: 'Punto de partida (Base)',
    icon: L.icon({
      iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -32],
    })
  }).addTo(map);
  baseMarker.bindPopup('<strong>Punto de partida</strong><br>Naranjaisy, Villeta');

  const bounds = L.latLngBounds();
  if (Array.isArray(puntos) && puntos.length) {
    puntos.forEach(p => {
      if (p.lat != null && p.lng != null) {
        L.marker([p.lat, p.lng]).addTo(map)
          .bindPopup(`
            <strong>Entrega #${p.id}</strong><br>
            Barrio: ${p.barrio ?? '-'}<br>
            ${p.direccion ? ('Dir: ' + p.direccion) : ''}
          `);
        bounds.extend([p.lat, p.lng]);
      }
    });
    if (bounds.isValid()) map.fitBounds(bounds.pad(0.2));
  } else {
    L.popup().setLatLng(base).setContent('No hay entregas con coordenadas para este período.').openOn(map);
  }
</script>
</body>
</html>
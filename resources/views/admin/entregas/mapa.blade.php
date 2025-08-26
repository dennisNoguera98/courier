<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Entregas activas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
  <style>#map{height:72vh}</style>
</head>
<body class="p-3">
<div class="container">
  <div class="d-flex align-items-end gap-3 mb-3">
    <div>
      <h3 class="mb-0">Entregas activas</h3>
      <small class="text-muted">Desde tu BD: Entregas → Cliente → Ubicación</small>
    </div>

    <form class="ms-auto d-flex align-items-end gap-2" method="POST" action="{{ route('admin.entregas.generar') }}">
      @csrf
      <div>
        <label class="form-label mb-1">Cantidad de couriers</label>
        <select name="cantidad_couriers" class="form-select">
          <option value="2">2</option>
          <option value="3" selected>3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
      <button class="btn btn-success" type="submit">Generar rutas</button>
    </form>
  </div>

  <div id="map" class="rounded border"></div>
</div>
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
  // Estos puntos vienen del controlador
  const puntos = @json($puntos);
  const base = [-25.2637, -57.5759]; // cambiá por tu base real si querés

  const map = L.map('map').setView(base, 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);

  const bounds = L.latLngBounds();
  if (puntos.length) {
    puntos.forEach(p => {
      L.marker([p.lat, p.lng]).addTo(map)
        .bindPopup(`
          <strong>Entrega #${p.id}</strong><br>
          Barrio: ${p.barrio ?? '-'}<br>
          ${p.direccion ? ('Dir: ' + p.direccion) : ''}
        `);
      bounds.extend([p.lat, p.lng]);
    });
    map.fitBounds(bounds.pad(0.2));
  } else {
    L.popup().setLatLng(base).setContent('No hay entregas activas con coordenadas.').openOn(map);
  }
</script>
</body>
</html>
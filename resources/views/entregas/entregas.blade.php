<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Entregas</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        #map {
            width: 100%;
            height: 500px;
        }
        .lista-entregas {
            margin-top: 20px;
        }
        .entrega-item {
            margin-bottom: 8px;
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h1>Mapa de Entregas</h1>

    <div id="map"></div>

    <div class="lista-entregas">
        <h2>Lista de Entregas (Ruta Óptima)</h2>
        <ol id="entregas-lista"></ol>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Axios para llamadas a OSRM -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const entregas = @json($entregas);

        // Convertir las coordenadas de string a números
        const puntos = entregas.map((e, index) => {
            const [lat, lng] = e.coordenadas.split(',').map(Number);
            return {
                id: index + 1,
                nombre: `${e.nombre_persona} ${e.apellido_persona}`,
                descripcion: e.descripcion,
                lat,
                lng
            };
        });

        // Crear mapa Leaflet
        const map = L.map('map').setView([puntos[0].lat, puntos[0].lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Agregar marcadores
        puntos.forEach(p => {
            L.marker([p.lat, p.lng])
                .addTo(map)
                .bindPopup(`<b>${p.nombre}</b><br>${p.descripcion}`);
        });

        // Llamar a OSRM para calcular la ruta óptima
        const coords = puntos.map(p => `${p.lng},${p.lat}`).join(';');
        const osrmUrl = `https://router.project-osrm.org/trip/v1/driving/${coords}?roundtrip=true&source=first&overview=full&geometries=geojson`;

        axios.get(osrmUrl)
            .then(response => {
                const data = response.data;
                if (data.code !== 'Ok') {
                    console.error('Error OSRM:', data);
                    return;
                }

                // Dibujar la ruta
                const route = L.geoJSON(data.trips[0].geometry, {
                    style: { color: 'blue', weight: 4 }
                }).addTo(map);

                // Mostrar lista de entregas en orden óptimo
                const orderedWaypoints = data.waypoints;
                const lista = document.getElementById('entregas-lista');
                lista.innerHTML = '';

                orderedWaypoints.forEach((wp, index) => {
                    const originalPoint = puntos[wp.waypoint_index];
                    const li = document.createElement('li');
                    li.textContent = `${originalPoint.nombre} (${originalPoint.descripcion})`;
                    lista.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error en OSRM:', error);
            });
    </script>
</body>
</html>
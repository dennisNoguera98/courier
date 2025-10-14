<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestionar grupos de rutas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-3">
<div class="container">

  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">Gestionar grupos de rutas</h3>

    {{-- Formulario global para guardar asignaciones --}}
    <form id="form-asignaciones"
          action="{{ route('admin.entregas.gestionarGrupos.guardarAsignaciones') }}"
          method="POST"
          class="ms-auto d-flex gap-2">
      @csrf
      <input type="hidden" name="asignaciones" id="asignaciones_json">
      {{-- mantener contexto --}}
      @if(!empty($year))  <input type="hidden" name="year"  value="{{ $year }}">  @endif
      @if(!empty($month)) <input type="hidden" name="month" value="{{ $month }}"> @endif
      @if(!empty($entregaId)) <input type="hidden" name="entrega_id" value="{{ $entregaId }}"> @endif

      <a href="{{ route('admin.entregas.mapa', ['year'=>$year,'month'=>$month]) }}"
         class="btn btn-outline-secondary">← Volver al mapa</a>

      <button id="btn-guardar" type="submit" class="btn btn-success" disabled>
        Guardar asignaciones
      </button>
    </form>
  </div>

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead class="table-light">
        <tr>
          <th>#Grupo</th>
          <th>Entrega</th>
          <th>Courier</th>
          <th>Estado</th>
          <th>Cant.de entregas</th>
          <th>Detalles</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
      @forelse($grupos as $g)
        <tr>
          <td>{{ ($grupos->firstItem() ?? 1) + $loop->index }}</td>
          <td>{{ $g->entrega_id }}</td>

          {{-- Selector de courier por grupo --}}
          <td style="min-width:220px">
            <select class="form-select form-select-sm sel-courier"
                    data-grupo="{{ $g->grupo_id }}">
              <option value="">— Sin asignar —</option>
              @foreach($couriers as $c)
                @php
                  $selected = $g->id_courier == $c->usuario_id ? 'selected' : '';
                  $nombre = trim($c->nombre_persona.' '.$c->apellido_persona);
                @endphp
                <option value="{{ $c->usuario_id }}" {{ $selected }}>
                  {{ $nombre }}
                </option>
              @endforeach
            </select>
          </td>

          <td><span class="badge bg-secondary">{{ $g->estado }}</span></td>
          <td>{{ $g->detalles->count() }}</td>

          <td>
            <button class="btn btn-sm btn-info"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#detallesGrupo{{ $g->grupo_id }}"
                    aria-expanded="false"
                    aria-controls="detallesGrupo{{ $g->grupo_id }}">
              Ver detalles
            </button>
          </td>

          <td class="text-end">
            {{-- Eliminar grupo (funcional) --}}
            <form action="{{ route('admin.entregas.gestionarGrupos.eliminar', $g->grupo_id) }}"
                  method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('¿Eliminar el grupo #{{ $g->grupo_id }} y sus detalles?')">
                Eliminar
              </button>
            </form>
          </td>
        </tr>

        {{-- Detalles colapsables (muestra nombre de courier arriba si está asignado) --}}
        <tr class="collapse bg-light" id="detallesGrupo{{ $g->grupo_id }}">
          <td colspan="7">
            <div class="mb-2">
              <strong>Courier asignado:</strong>
              @php
                $cp = $g->courier?->persona;
                $nombreCourier = $cp ? trim(($cp->nombre_persona ?? '').' '.($cp->apellido_persona ?? '')) : '— Sin asignar —';
              @endphp
              {{ $nombreCourier }}
            </div>

            @if($g->detalles->count() > 0)
           <table class="table table-sm table-bordered mb-0">
  <thead class="table-secondary">
    <tr>
      <th>Cliente</th>
      <th>Ubicación</th>
      <th>Orden</th>
      <th>Barrio</th> <!-- ✅ cambiamos el encabezado -->
    </tr>
  </thead>
  <tbody>
  @foreach($g->detalles as $d)
    @php
      $ext = $d->extracto;
      $cli = $ext?->cliente;
      $per = $cli?->persona;
      $ubi = $cli?->ubicacion;
      $barrio = $ubi?->barrio ?? ($ubi?->barrioRel?->nombre_barrio ?? '—');
    @endphp
    <tr>
      <td>{{ trim(($per->nombre_persona ?? '') . ' ' . ($per->apellido_persona ?? '')) ?: '—' }}</td>
      <td>{{ $ubi->descripcion ?? '—' }}</td>
      <td>{{ $d->orden ?? '—' }}</td>
      <td>{{ $barrio }}</td> <!-- ✅ mostramos barrio -->
    </tr>
  @endforeach
  </tbody>
</table>
            @else
              <div class="p-2 text-muted">No hay detalles en este grupo.</div>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center text-muted">No hay grupos generados.</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-2">
    {{ $grupos->withQueryString()->links() }}
  </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Colección de cambios pendientes: {grupo_id, id_courier}
  const cambios = new Map();

  // Al cambiar un select, registramos el cambio y habilitamos Guardar
  document.querySelectorAll('.sel-courier').forEach(sel => {
    sel.addEventListener('change', (e) => {
      const grupoId = e.target.dataset.grupo;
      const idCourier = e.target.value || null;

      cambios.set(grupoId, { grupo_id: parseInt(grupoId, 10), id_courier: idCourier ? parseInt(idCourier, 10) : null });

      document.getElementById('btn-guardar').disabled = cambios.size === 0;
    });
  });

  // Al enviar el form, serializamos cambios a JSON
  document.getElementById('form-asignaciones').addEventListener('submit', (e) => {
    const arr = Array.from(cambios.values());
    document.getElementById('asignaciones_json').value = JSON.stringify(arr);
  });
</script>
</body>
</html>
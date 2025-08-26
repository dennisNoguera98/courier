@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📦 Grupo {{ $idx }} — Entregas asignadas</h2>

    <div class="alert alert-info">
        <strong>Punto de partida:</strong> {{ $base['lat'] }}, {{ $base['lng'] }}  
        <br>
        <strong>Total entregas:</strong> {{ count($entregas) }}
    </div>

    @if(count($entregas) > 0)
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Orden de visitas (optimizado por cercanía)
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Barrio</th>
                            <th>Dirección</th>
                            <th>Coordenadas</th>
                            <th>Mapa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entregas as $i => $e)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $e['barrio'] }}</td>
                                <td>{{ $e['direccion'] ?? 'Sin dirección' }}</td>
                                <td>{{ $e['lat'] }}, {{ $e['lng'] }}</td>
                                <td>
                                    <a 
                                        href="https://www.google.com/maps/search/?api=1&query={{ $e['lat'] }},{{ $e['lng'] }}" 
                                        target="_blank" 
                                        class="btn btn-sm btn-outline-success">
                                        Ver en mapa
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-warning mt-3">
            No hay entregas en este grupo.
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.entregas') }}" class="btn btn-secondary">
            ← Volver al mapa
        </a>
    </div>
</div>
@endsection
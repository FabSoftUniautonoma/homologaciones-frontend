{{-- resources/views/homologacionescoordinador/coordinador.blade.php --}}
@extends('admin.layouts.appvice')

@section('title', 'Dashboard Coordinador')

@section('content')
<style>
    :root {
        --azul-oscuro: #19407b;
        --azul-medio: #0075bf;
        --azul-claro: #0e869b;
        --azul-muy-claro: #e1f5fe;
        --azul-contenedor: #f8fbff;
        --blanco: #ffffff;
        --negro: #000000;
        --texto-oscuro: #212121;
        --texto-medio: #424242;
        --texto-claro: #757575;
        --gris-claro: #f9f9f9;
        --borde: #e0e0e0;
        --sombra: rgba(0, 0, 0, 0.08);
        --sombra-hover: rgba(25, 64, 123, 0.15);
        --rojo-error: #ff4d4d;
        --verde-success: #4CAF50;
        --naranja-warning: #FF9800;
        --azul-info: #2196F3;
    }

    .container-fluid {
        background-color: var(--gris-claro);
        padding: 1.5rem;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px var(--sombra);
        margin-bottom: 1.5rem;
        background-color: var(--blanco);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        box-shadow: 0 8px 16px var(--sombra-hover);
    }

    .card-header {
        background-color: var(--azul-contenedor);
        border-bottom: 1px solid var(--borde);
        padding: 1rem 1.5rem;
        border-radius: 10px 10px 0 0 !important;
    }

    .card-title {
        color: var(--azul-oscuro);
        font-weight: 600;
        font-size: 1.2rem;
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--texto-medio);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid var(--borde);
        padding: 0.6rem 1rem;
        transition: all 0.2s;
        background-color: var(--blanco);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--azul-medio);
        box-shadow: 0 0 0 3px rgba(0, 117, 191, 0.15);
    }

    .btn {
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: var(--azul-medio);
        border-color: var(--azul-medio);
    }

    .btn-primary:hover, .btn-primary:focus {
        background-color: var(--azul-oscuro);
        border-color: var(--azul-oscuro);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-light {
        background-color: var(--gris-claro);
        border-color: var(--borde);
        color: var(--texto-medio);
    }

    .btn-light:hover {
        background-color: var(--borde);
        color: var(--texto-oscuro);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-info {
        background-color: var(--azul-info);
        border-color: var(--azul-info);
        color: white;
    }

    .btn-info:hover {
        background-color: #0d8aee;
        border-color: #0d8aee;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
    }

    .btn .fas {
        margin-right: 5px;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    .table {
        margin-bottom: 0;
        border-spacing: 0;
        width: 100%;
    }

    .table thead th {
        background-color: var(--azul-muy-claro);
        color: var(--azul-oscuro);
        font-weight: 600;
        border-bottom: 2px solid var(--azul-claro);
        padding: 12px 15px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .table tbody tr {
        transition: background-color 0.2s;
    }

    .table tbody tr:hover {
        background-color: var(--azul-contenedor);
    }

    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--borde);
        color: var(--texto-medio);
        vertical-align: middle;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
        border-radius: 30px;
        font-size: 0.75rem;
        text-transform: capitalize;
    }

    .bg-success {
        background-color: var(--verde-success) !important;
    }

    .bg-danger {
        background-color: var(--rojo-error) !important;
    }

    .bg-warning {
        background-color: var(--naranja-warning) !important;
        color: white !important;
    }

    .bg-secondary {
        background-color: var(--texto-claro) !important;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .action-buttons .btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .action-buttons .btn i {
        margin: 0;
        font-size: 1rem;
    }

    .action-buttons .btn:hover {
        transform: translateY(-3px);
    }

    /* Diseño responsive para formularios */
    @media (max-width: 767.98px) {
        .col-12 {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Panel de filtrado -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title m-0"><i class="fas fa-filter me-2"></i>Filtros de búsqueda</h4>
        </div>
        <div class="card-body">
            <form id="filtroForm">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" class="form-control form-select">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="en revisión">En revisión</option>
                            <option value="aprobada">Aprobadas</option>
                            <option value="rechazada">Rechazadas</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha">
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select id="carrera" class="form-control form-select">
                            <option value="">Todas</option>
                            <option value="ingeniería de software">Ingeniería de software</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="estudiante" class="form-label">Estudiante</label>
                        <input type="text" class="form-control" id="estudiante" placeholder="Nombre o ID">
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-primary me-2" id="buscarBtn">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                        <button type="button" class="btn btn-light" id="limpiarBtn">
                            <i class="fa fa-undo"></i> Limpiar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de solicitudes -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title m-0"><i class="fas fa-list-alt me-2"></i>Listado de Solicitudes</h4>
            <span class="badge bg-primary">{{ count($solicitudes ?? []) }} solicitudes</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="solicitudes-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Programa de Interés</th>
                            <th>Correo</th>
                            <th>Fecha de Solicitud</th>
                            <th>Institución de Origen</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitudes as $solicitud)
                            @php
                                $estado = strtolower($solicitud['estado'] ?? '');
                                $numero_radicado = $solicitud['numero_radicado'] ?? '';

                                // Construir nombre completo del usuario
                                $nombre_usuario = trim(
                                    ($solicitud['primer_nombre'] ?? '') .
                                        ' ' .
                                        ($solicitud['segundo_nombre'] ?? '') .
                                        ' ' .
                                        ($solicitud['primer_apellido'] ?? '') .
                                        ' ' .
                                        ($solicitud['segundo_apellido'] ?? ''),
                                );
                            @endphp
                            <tr>
                                <td>{{ $numero_radicado ?: 'N/A' }}</td>
                                <td>{{ $nombre_usuario ?: 'Nombre no disponible' }}</td>
                                <td>{{ $solicitud['programa_destino_nombre'] ?? 'Carrera no encontrada' }}</td>
                                <td>{{ $solicitud['email'] ?? 'Correo no disponible' }}</td>
                                <td>
                                    @if (!empty($solicitud['fecha_solicitud']))
                                        {{ \Carbon\Carbon::parse($solicitud['fecha_solicitud'])->format('d/m/Y') }}
                                    @else
                                        Sin fecha
                                    @endif
                                </td>
                                <td>{{ $solicitud['institucion_origen_nombre'] ?? 'Sin nombre de institución' }}</td>
                                <td>
                                    <span
                                        class="badge {{ $estado == 'aprobado' ? 'bg-success' : ($estado == 'rechazado' ? 'bg-danger' : ($estado == 'en revisión' ? 'bg-warning' : 'bg-secondary')) }}">
                                        {{ $solicitud['estado'] ?? 'Estado no disponible' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- Botón Editar: solo activo si no está cerrado --}}
                                        @if ($estado === 'cerrado')
                                            <button class="btn btn-sm btn-primary" disabled
                                                title="No se puede editar una solicitud cerrada">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('procesohomologacion', $numero_radicado) }}"
                                                class="btn btn-sm btn-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        {{-- Botón Ver Información Homologación --}}
                                        <a href="{{ route('informacion', $numero_radicado) }}"
                                            class="btn btn-sm btn-info" title="Ver información de homologación">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/gestionhomologacioncoordinacion.js') }}"></script>
@endsection

@extends('admin.layouts.appvice')

@section('title', 'Gestión de Homologaciones - Vicerrectoría')

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
    h1.h3 {
        color: var(--azul-oscuro);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    p.mb-4 {
        color: var(--texto-medio);
        font-size: 1rem;
        max-width: 800px;
    }
    /* Tarjetas de estadísticas */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px var(--sombra);
        transition: transform 0.2s, box-shadow 0.2s;
        margin-bottom: 1.5rem;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px var(--sombra-hover);
    }
    .card.shadow {
        box-shadow: 0 4px 12px var(--sombra) !important;
    }

    .card.border-left-primary {
        border-left: 4px solid var(--azul-medio) !important;
    }

    .card.border-left-success {
        border-left: 4px solid var(--verde-success) !important;
    }

    .card.border-left-danger {
        border-left: 4px solid var(--rojo-error) !important;
    }

    .card.border-left-warning {
        border-left: 4px solid var(--naranja-warning) !important;
    }

    .card-body {
        padding: 1.5rem;
    }

    .text-xs {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .text-primary {
        color: var(--azul-medio) !important;
    }

    .text-success {
        color: var(--verde-success) !important;
    }

    .text-danger {
        color: var(--rojo-error) !important;
    }
    .text-warning {
        color: var(--naranja-warning) !important;
    }
    .h5 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--texto-oscuro);
    }
    .text-gray-300 {
        color: #d1d3e2 !important;
    }
    .text-gray-800 {
        color: var(--texto-oscuro) !important;
    }
    /* Botones */
    .btn {
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    .btn-outline-secondary {
        color: var(--texto-medio);
        border-color: var(--borde);
    }

    .btn-outline-secondary:hover {
        background-color: var(--gris-claro);
        border-color: var(--texto-medio);
        color: var(--texto-oscuro);
    }

    .btn-primary {
        background-color: var(--azul-medio);
        border-color: var(--azul-medio);
    }

    .btn-primary:hover {
        background-color: var(--azul-oscuro);
        border-color: var(--azul-oscuro);
    }

    .btn-info {
        background-color: var(--azul-info);
        border-color: var(--azul-info);
        color: white;
    }

    .btn-info:hover {
        background-color: #0d8aee;
        border-color: #0d8aee;
    }



    /* Tabla */
    .card-header {
        background-color: var(--azul-contenedor);
        border-bottom: 1px solid var(--borde);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header h6 {
        color: var(--azul-oscuro);
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    .table {
        color: var(--texto-medio);
        margin-bottom: 0;
    }

    .table-bordered {
        border: 1px solid var(--borde);
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid var(--borde);
        vertical-align: middle;
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

    /* Alertas */
    .alert {
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .alert-success {
        background-color: rgba(76, 175, 80, 0.12);
        color: var(--verde-success);
    }

    .alert-danger {
        background-color: rgba(255, 77, 77, 0.12);
        color: var(--rojo-error);
    }

    .alert .close {
        color: inherit;
        opacity: 0.8;
    }

    /* Estados vacíos */
    .text-center img {
        transition: transform 0.3s;
    }

    .text-center:hover img {
        transform: scale(1.05);
    }

    .text-muted {
        color: var(--texto-claro) !important;
    }

    /* Acciones en tabla */
    .text-center .btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 50%;
        margin: 0 3px;
    }

    .text-center .btn i {
        margin: 0;
        font-size: 1rem;
    }

    /* Mejoras Responsivas */
    @media (max-width: 767.98px) {
        .container-fluid {
            padding: 1rem;
        }

        .table thead th {
            font-size: 0.8rem;
            padding: 10px;
        }

        .table td {
            padding: 10px;
        }
    }
</style>

<div class="container-fluid">


    <!-- Tarjeta de Estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pendientes de Aprobación</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ isset($solicitudes)? count(array_filter($solicitudes, function ($s) {return $s['estado'] == 'En revisión';})): 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Homologaciones Aprobadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ isset($solicitudes)? count(array_filter($solicitudes, function ($s) {return $s['estado'] == 'Aprobado';})): 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Homologaciones Rechazadas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ isset($solicitudes)? count(array_filter($solicitudes, function ($s) {return $s['estado'] == 'Rechazado';})): 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Solicitudes Recibidas (Mes)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ isset($solicitudes)
                                    ? count(
                                        array_filter($solicitudes, function ($s) {
                                            return (new DateTime($s['fecha_solicitud']))->format('m-Y') == date('m-Y');
                                        }),
                                    )
                                    : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción superior -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter fa-sm me-2"></i> Filtrar por Estado
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item {{ request()->query('estado') == null ? 'active' : '' }}"
                    href="{{ route('admin.homologaciones.vice.index') }}">
                    <i class="fas fa-th-list me-2"></i> Todos
                </a>
                <a class="dropdown-item {{ request()->query('estado') == 'En revisión' ? 'active' : '' }}"
                    href="{{ route('admin.homologaciones.vice.index', ['estado' => 'En revisión']) }}">
                    <i class="fas fa-clock me-2"></i> En revisión
                </a>
                <a class="dropdown-item {{ request()->query('estado') == 'Aprobado' ? 'active' : '' }}"
                    href="{{ route('admin.homologaciones.vice.index', ['estado' => 'Aprobado']) }}">
                    <i class="fas fa-check-circle me-2"></i> Aprobados
                </a>
                <a class="dropdown-item {{ request()->query('estado') == 'Rechazado' ? 'active' : '' }}"
                    href="{{ route('admin.homologaciones.vice.index', ['estado' => 'Rechazado']) }}">
                    <i class="fas fa-times-circle me-2"></i> Rechazados
                </a>
                <a class="dropdown-item {{ request()->query('estado') == 'Radicado' ? 'active' : '' }}"
                    href="{{ route('admin.homologaciones.vice.index', ['estado' => 'Radicado']) }}">
                    <i class="fas fa-file-alt me-2"></i> Radicados
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla de homologaciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list-alt me-2"></i> Lista de Homologaciones
            </h6>
            @if (request()->query('estado'))
                <span class="badge badge-info">
                    Filtrando por: {{ request()->query('estado') }}
                    <a href="{{ route('admin.homologaciones.vice.index') }}" class="text-white ms-2">
                        <i class="fas fa-times"></i>
                    </a>
                </span>
            @endif
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="120">No. Radicado</th>
                            <th>Estudiante</th>
                            <th>Programa</th>
                            <th>Institución Origen</th>
                            <th width="110">Fecha Solicitud</th>
                            <th width="90">Estado</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Definir un array vacío si $solicitudes no está definido
                            $dataToShow = isset($solicitudes) ? $solicitudes : [];

                            // Filtrar las solicitudes si hay un parámetro de estado
                            if (request()->query('estado') && isset($solicitudes)) {
                                $dataToShow = array_filter($dataToShow, function ($s) {
                                    return $s['estado'] == request()->query('estado');
                                });
                            }
                        @endphp

                        @forelse($dataToShow as $solicitud)
                            <tr>
                                <td>{{ $solicitud['numero_radicado'] ?? 'N/A' }}</td>
                                <td>
                                    {{ ($solicitud['primer_nombre'] ?? '') . ' ' . ($solicitud['segundo_nombre'] ?? '') . ' ' . ($solicitud['primer_apellido'] ?? '') . ' ' . ($solicitud['segundo_apellido'] ?? '') }}
                                </td>
                                <td>{{ $solicitud['programa_destino_nombre'] ?? 'No disponible' }}</td>
                                <td>{{ $solicitud['institucion_origen_nombre'] ?? 'No disponible' }}</td>
                                <td>{{ isset($solicitud['fecha_solicitud']) ? date('d/m/Y', strtotime($solicitud['fecha_solicitud'])) : 'N/A' }}
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ isset($solicitud['estado'])
                                            ? ($solicitud['estado'] == 'Aprobado'
                                                ? 'success'
                                                : ($solicitud['estado'] == 'En revisión'
                                                    ? 'primary'
                                                    : ($solicitud['estado'] == 'Rechazado'
                                                        ? 'danger'
                                                        : ($solicitud['estado'] == 'Radicado'
                                                            ? 'info'
                                                            : 'secondary'))))
                                            : 'secondary' }}">
                                        {{ $solicitud['estado'] ?? 'Desconocido' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if (isset($solicitud['estado']) && $solicitud['estado'] == 'En revisión')
                                        <a href="{{ route('admin.homologaciones.vice.informacion', $solicitud['numero_radicado']) }}"
                                            class="btn btn-sm btn-info" title="Ver información">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                        <a href="{{ route('admin.homologaciones.vice.procesohomologacion.vicerrectoria', $solicitud['numero_radicado']) }}"
                                            class="btn btn-sm btn-primary" title="Procesar homologación">
                                            <i class="fas fa-clipboard-check"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('admin.homologaciones.vice.informacion', $solicitud['numero_radicado']) }}"
                                            class="btn btn-sm btn-info" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <img src="{{ asset('img/empty-data.svg') }}" alt="No hay datos"
                                        style="width: 120px; opacity: 0.6;">
                                    <p class="text-muted mt-3">No hay homologaciones
                                        disponibles{{ request()->query('estado') ? ' con el estado seleccionado' : '' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                "order": [
                    [4, "desc"]
                ], // Ordenar por fecha de solicitud descendente
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ]
            });
        });
    </script>
@endsection

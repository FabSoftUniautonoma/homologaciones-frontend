@extends('admin.layouts.appvice')

@section('title', 'Gestión de Homologaciones - Vicerrectoría')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Homologaciones Pendientes de Aprobación</h1>
        <p class="mb-4">Gestión de solicitudes de homologación revisadas por coordinación y pendientes de aprobación final.
        </p>

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
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción superior -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter fa-sm"></i> Filtrar por Estado
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item {{ request()->query('estado') == null ? 'active' : '' }}"
                        href="{{ route('admin.homologaciones.vice.index') }}">Todos</a>
                    <a class="dropdown-item {{ request()->query('estado') == 'En revisión' ? 'active' : '' }}"
                        href="{{ route('admin.homologaciones.vice.index', ['estado' => 'En revisión']) }}">En revisión</a>
                    <a class="dropdown-item {{ request()->query('estado') == 'Aprobado' ? 'active' : '' }}"
                        href="{{ route('admin.homologaciones.vice.index', ['estado' => 'Aprobado']) }}">Aprobados</a>
                    <a class="dropdown-item {{ request()->query('estado') == 'Rechazado' ? 'active' : '' }}"
                        href="{{ route('admin.homologaciones.vice.index', ['estado' => 'Rechazado']) }}">Rechazados</a>
                    <a class="dropdown-item {{ request()->query('estado') == 'Radicado' ? 'active' : '' }}"
                        href="{{ route('admin.homologaciones.vice.index', ['estado' => 'Radicado']) }}">Radicados</a>
                </div>
            </div>
        </div>

        <!-- Tabla de homologaciones -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Lista de Homologaciones</h6>
                @if (request()->query('estado'))
                    <span class="badge badge-info">
                        Filtrando por: {{ request()->query('estado') }}
                        <a href="{{ route('admin.homologaciones.vice.index') }}" class="text-white ml-2">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                                                class="btn btn-sm btn-primary" title="Ver información">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <a href="{{ route('admin.homologaciones.vice.procesohomologacion', $solicitud['numero_radicado']) }}"
                                                class="btn btn-sm btn-primary" title="Procesar homologación">
                                                <i class="fas fa-clipboard-check"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.homologaciones.vice.informacion', $solicitud['numero_radicado']) }}"
                                                class="btn btn-sm btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.homologaciones.vice.documentos', $solicitud['numero_radicado']) }}"
                                            class="btn btn-sm btn-secondary" title="Ver documentos">
                                            <i class="fas fa-file-alt"></i>
                                        </a>

                                        @if (isset($solicitud['estado']) && $solicitud['estado'] == 'Aprobado' && isset($solicitud['pdf_resolucion']))
                                            <a href="{{ route('admin.homologaciones.vice.descargar', $solicitud['pdf_resolucion']) }}"
                                                class="btn btn-sm btn-success" title="Descargar acta">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <img src="{{ asset('img/empty-data.svg') }}" alt="No hay datos"
                                            style="width: 120px; opacity: 0.6;">
                                        <p class="text-muted mt-2">No hay homologaciones
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

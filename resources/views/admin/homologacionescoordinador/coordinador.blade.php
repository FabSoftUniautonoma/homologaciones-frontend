{{-- resources/views/homologacionescoordinador/coordinador.blade.php --}}
@extends('admin.layouts.appcoordinacion')

@section('title', 'Dashboard Coordinador')

@section('content')
    <div class="container-fluid py-4">
        <!-- Panel de filtrado -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title m-0">Filtros de búsqueda</h4>
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
                        <div class="col-12 d-flex justify-content-end mt-3">
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
            <div class="card-header">
                <h4 class="card-title m-0">Listado de Solicitudes</h4>
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
                                        ($solicitud['primer_nombre'] ?? '') . ' ' .
                                        ($solicitud['segundo_nombre'] ?? '') . ' ' .
                                        ($solicitud['primer_apellido'] ?? '') . ' ' .
                                        ($solicitud['segundo_apellido'] ?? '')
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
                                        <span class="badge {{ $estado == 'aprobado' ? 'bg-success' : ($estado == 'rechazado' ? 'bg-danger' : ($estado == 'en revisión' ? 'bg-warning' : 'bg-secondary')) }}">
                                            {{ $solicitud['estado'] ?? 'Estado no disponible' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- Botón Editar: solo activo si no está cerrado --}}
                                            @if ($estado === 'cerrado')
                                                <button class="btn btn-sm btn-primary" disabled
                                                    title="No se puede editar una solicitud cerrada">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('admin.homologacionescoordinador.procesohomologacion', $numero_radicado) }}"
                                                    class="btn btn-sm btn-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            {{-- Botón Ver Información Homologación --}}
                                            <a href="{{ route('homologacion.informacion', $numero_radicado) }}"
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

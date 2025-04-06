{{-- resources/views/homologacionescoordinador/coordinador.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard Coordinador')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header">
            <h4 class="page-title">Sistema de Gestión de Homologaciones</h4>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="separator"><i class="fas fa-chevron-right"></i></li>
                <li class="nav-item"><a href="#">Coordinador</a></li>
                <li class="separator"><i class="fas fa-chevron-right"></i></li>
                <li class="nav-item"><a href="#">Solicitudes</a></li>
            </ul>
        </div>

        <!-- Panel de filtrado -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Filtros de búsqueda</h4>
            </div>
            <div class="card-body">
                <form id="filtroForm" class="row">
                    <div class="col-md-3 form-group">
                        <label for="estado">Estado</label>
                        <select id="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="en revisión">En revisión</option>
                            <option value="aprobada">Aprobadas</option>
                            <option value="rechazada">Rechazadas</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="carrera">Carrera</label>
                        <select id="carrera" class="form-control">
                            <option value="">Todas</option>
                            <option value="ingeniería de software">Ingeniería Civil</option>
                            ¿
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="estudiante">Estudiante</label>
                        <input type="text" class="form-control" id="estudiante" placeholder="Nombre o ID">
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-round" id="buscarBtn">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                        <button type="button" class="btn btn-light btn-round" id="limpiarBtn">
                            <i class="fa fa-undo"></i> Limpiar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de solicitudes -->
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h4 class="card-title">Listado de Solicitudes</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="solicitudes-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estudiante</th>
                                <th>Instituto de Origen</th>
                                <th>Fecha Solicitud</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([['id' => 'HOM-2025-001', 'nombre' => 'María González', 'instituto' => 'FUP de Popayán', 'fecha' => '01/04/2025', 'estado' => 'Pendiente'], ['id' => 'HOM-2025-002', 'nombre' => 'Carlos Rodríguez', 'instituto' => 'SENA', 'fecha' => '31/03/2025', 'estado' => 'En revisión'], ['id' => 'HOM-2025-003', 'nombre' => 'Ana López', 'instituto' => 'Colegio Mayor', 'fecha' => '30/03/2025', 'estado' => 'Aprobada']] as $solicitud)
                                <tr>
                                    <td>{{ $solicitud['id'] }}</td>
                                    <td>{{ $solicitud['nombre'] }}</td>
                                    <td>{{ $solicitud['instituto'] }}</td>
                                    <td>{{ $solicitud['fecha'] }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ match (strtolower($solicitud['estado'])) {
                                                'pendiente' => 'warning',
                                                'en revisión' => 'info',
                                                'aprobada' => 'success',
                                                default => 'danger',
                                            } }}">
                                            {{ $solicitud['estado'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" data-id="{{ $solicitud['id'] }}"
                                            class="btn btn-primary btn-sm ver-detalles">
                                            <i class="fa fa-eye"></i> Ver Detalles
                                        </button>

                                        @if (strtolower($solicitud['estado']) === 'aprobada')
                                            <button type="button" class="btn btn-success btn-sm descargar-pdf"
                                                data-id="{{ $solicitud['id'] }}">
                                                <i class="fa fa-download"></i> Descargar PDF
                                            </button>
                                        @endif
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

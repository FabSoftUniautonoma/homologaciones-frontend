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
                            <option value="ingeniería de software">Ingeniería de software</option>
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
                                <th>Correo</th>
                                <th>Fecha Solicitud</th>
                                <th>Institucion de origen </th>
                            </tr>
                        </thead>
                        <tbody>
                        <tbody>
                            @foreach ($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud['numero_radicado'] ?? 'N/A' }}</td>
                                <td>{{ $solicitud['nombre_usuario'] ?? 'Nombre no disponible' }}</td>
                                <td>{{ $solicitud['correo'] ?? 'Correo no disponible' }}</td>
                                <td>
                                    @if (!empty($solicitud['fecha_solicitud']))
                                        {{ \Carbon\Carbon::parse($solicitud['fecha_solicitud'])->format('d/m/Y') }}
                                    @else
                                        Sin fecha
                                    @endif
                                </td>
                                <td>{{ $solicitud['nombre_institucion'] ?? 'Sin nombre de institución' }}</td>
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

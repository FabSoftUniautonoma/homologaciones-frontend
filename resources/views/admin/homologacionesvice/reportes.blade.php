@extends('admin.layouts.appvice')

@section('title', 'Reportes de Homologaciones - Vicerrectoría')

@section('styles')
<!-- Datepicker CSS -->
<link href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<style>
    .card-report {
        transition: transform 0.2s;
        cursor: pointer;
    }
    .card-report:hover {
        transform: translateY(-5px);
    }
    .form-control.datepicker {
        background-color: #fff;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Generación de Reportes</h1>
        <a href="{{ route('admin.homologacionesvice.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver
        </a>
    </div>

    <!-- Mensajes de alerta -->
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

    <!-- Tarjetas de tipos de reportes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Seleccione un tipo de reporte</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card card-report h-100" id="report-homologaciones">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-pdf fa-4x text-primary mb-3"></i>
                                    <h5 class="card-title">Reporte de Homologaciones</h5>
                                    <p class="card-text text-muted">Listado detallado de solicitudes de homologación según criterios de fecha, programa y estado.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card card-report h-100" id="report-estadisticas">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-4x text-info mb-3"></i>
                                    <h5 class="card-title">Estadísticas de Homologaciones</h5>
                                    <p class="card-text text-muted">Resumen estadístico anual de solicitudes por estado, programa y tiempo promedio de respuesta.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card card-report h-100" id="report-excel">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-excel fa-4x text-success mb-3"></i>
                                    <h5 class="card-title">Exportar a Excel</h5>
                                    <p class="card-text text-muted">Descarga de datos completos de homologaciones en formato Excel para análisis detallado.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formularios de reportes (ocultos inicialmente) -->
    <div class="row">
        <div class="col-12">
            <!-- Formulario de Reporte de Homologaciones -->
            <div class="card shadow mb-4" id="form-homologaciones" style="display: none;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Reporte de Homologaciones</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opciones:</div>
                            <a class="dropdown-item toggle-form" href="#" data-target="form-homologaciones">Cerrar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homologacionesvice.reporte-homologaciones') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha Inicial</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="fecha_inicio" name="fecha_inicio"
                                            value="{{ \Carbon\Carbon::now()->subMonths(6)->format('Y-m-d') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_fin">Fecha Final</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="fecha_fin" name="fecha_fin"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="programa">Programa Académico</label>
                                    <select class="form-control" id="programa" name="programa">
                                        <option value="">Todos los programas</option>
                                        @foreach($programas ?? [] as $prog)
                                            <option value="{{ $prog['id_programa'] }}">{{ $prog['programa'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado de la Homologación</label>
                                    <select class="form-control" id="estado" name="estado" required>
                                        <option value="Aprobado">Aprobado</option>
                                        <option value="Rechazado">Rechazado</option>
                                        <option value="En revisión">En revisión</option>
                                        <option value="Radicado">Radicado</option>
                                        <option value="Cerrado">Cerrado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-file-pdf mr-2"></i> Generar Reporte PDF
                        </button>
                    </form>
                </div>
            </div>

            <!-- Formulario de Estadísticas -->
            <div class="card shadow mb-4" id="form-estadisticas" style="display: none;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Estadísticas de Homologaciones</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opciones:</div>
                            <a class="dropdown-item toggle-form" href="#" data-target="form-estadisticas">Cerrar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homologacionesvice.reporte-estadisticas') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="anio">Año</label>
                                    <select class="form-control" id="anio" name="anio" required>
                                        @for($i = \Carbon\Carbon::now()->year; $i >= 2020; $i--)
                                            <option value="{{ $i }}" {{ $i == \Carbon\Carbon::now()->year ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info mt-3">
                            <i class="fas fa-chart-bar mr-2"></i> Generar Estadísticas
                        </button>
                    </form>
                </div>
            </div>

            <!-- Formulario de Exportación a Excel -->
            <div class="card shadow mb-4" id="form-excel" style="display: none;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Exportar a Excel</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opciones:</div>
                            <a class="dropdown-item toggle-form" href="#" data-target="form-excel">Cerrar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.homologacionesvice.exportar-excel') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_inicio_excel">Fecha Inicial</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="fecha_inicio_excel" name="fecha_inicio"
                                            value="{{ \Carbon\Carbon::now()->subMonths(6)->format('Y-m-d') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_fin_excel">Fecha Final</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" id="fecha_fin_excel" name="fecha_fin"
                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado_excel">Estado (Opcional)</label>
                                    <select class="form-control" id="estado_excel" name="estado">
                                        <option value="">Todos los estados</option>
                                        <option value="Aprobado">Aprobado</option>
                                        <option value="Rechazado">Rechazado</option>
                                        <option value="En revisión">En revisión</option>
                                        <option value="Radicado">Radicado</option>
                                        <option value="Cerrado">Cerrado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">
                            <i class="fas fa-file-excel mr-2"></i> Exportar a Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Bootstrap Datepicker JS -->
<script src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Inicializar datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            language: 'es',
            autoclose: true,
            todayHighlight: true
        });

        // Mostrar/ocultar formularios al hacer clic en las tarjetas
        $('#report-homologaciones').click(function() {
            $('#form-homologaciones').show();
            $('#form-estadisticas, #form-excel').hide();
            $('html, body').animate({
                scrollTop: $('#form-homologaciones').offset().top - 100
            }, 500);
        });

        $('#report-estadisticas').click(function() {
            $('#form-estadisticas').show();
            $('#form-homologaciones, #form-excel').hide();
            $('html, body').animate({
                scrollTop: $('#form-estadisticas').offset().top - 100
            }, 500);
        });

        $('#report-excel').click(function() {
            $('#form-excel').show();
            $('#form-homologaciones, #form-estadisticas').hide();
            $('html, body').animate({
                scrollTop: $('#form-excel').offset().top - 100
            }, 500);
        });

        // Cerrar formularios
        $('.toggle-form').click(function(e) {
            e.preventDefault();
            $('#' + $(this).data('target')).hide();
        });
    });
</script>
@endsection

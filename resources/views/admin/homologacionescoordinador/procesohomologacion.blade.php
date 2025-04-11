@extends('admin.layouts.appcoordinacion')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Proceso de Homologación</h2>
        </div>
        <div class="card-body">
            <!-- Datos de la solicitud -->
            <div class="card mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h3 class="h5 mb-0">Solicitud: {{ $solicitud['codigo'] }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user me-2"></i>Nombre:</strong> {{ $solicitud['nombre'] }}</p>
                            <p><strong><i class="fas fa-university me-2"></i>Instituto:</strong>
                                {{ $solicitud['instituto'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-bookmark me-2"></i>Estado:</strong>
                                <span
                                    class="badge bg-{{ $solicitud['estado'] == 'Pendiente' ? 'warning' : ($solicitud['estado'] == 'Aprobada' ? 'success' : 'danger') }}">
                                    {{ $solicitud['estado'] }}
                                </span>
                            </p>
                            <p><strong><i class="fas fa-graduation-cap me-2"></i>Carrera de Interés:</strong>
                                {{ $solicitud['carrera_interes'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progreso de homologación -->
            <div id="resumen-homologaciones" class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="h5 mb-0"><i class="fas fa-chart-pie me-2"></i>Progreso de Homologación</h4>
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                            style="width: 0%;" id="progress-bar">0%</div>
                    </div>
                    <p class="mb-0">Materias homologadas: <span id="contador-homologadas">0</span> de <span
                            id="contador-total">0</span></p>
                </div>
            </div>

            <!-- Materias Cursadas por el Estudiante y Materias del Pensum Autónomo -->
            <h4 class="mb-3"><i class="fas fa-book me-2"></i>Materias Cursadas y Homologación</h4>
            <div class="row">
                <!-- Materias Cursadas por el Estudiante -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-bookmark me-2"></i>Materias Cursadas</h4>
                        </div>
                        <div class="card-body">
                            <select id="materia-select" class="form-select form-select-lg mb-3">
                                <option value="">Seleccione una materia cursada</option>
                                @foreach ($materias_cursadas as $materia)
                                    <option value="{{ $materia['nombre'] }}" data-nombre="{{ $materia['nombre'] }}"
                                        data-nota="{{ $materia['nota'] }}" data-descripcion="{{ $materia['descripcion'] }}"
                                        data-creditos="{{ $materia['creditos'] }}" data-horas="{{ $materia['horas'] }}"
                                        data-temas="{{ implode(', ', $materia['temas']) }}">
                                        {{ $materia['nombre'] }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="materia-details" class="mt-4 card border-primary" style="display:none;">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Detalles de la Materia Cursada</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 30%;">Nombre:</th>
                                                    <td><span id="materia-nombre"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Nota:</th>
                                                    <td><span class="badge bg-info text-dark" id="materia-nota"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Créditos:</th>
                                                    <td><span id="materia-creditos"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Horas:</th>
                                                    <td><span id="materia-horas"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Descripción:</th>
                                                    <td><span id="materia-descripcion"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Temas:</th>
                                                    <td><span id="materia-temas"></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Materias del Pensum Autónomo -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Materias del Pensum Autónomo</h5>
                        </div>
                        <div class="card-body">
                            <select id="materia-pensum-select" class="form-select form-select-lg mb-3"
                                style="display:none;">
                                <option value="">Seleccione una materia del pensum</option>
                                <option value="no_aplica" data-descripcion="No Aplica" data-creditos="0" data-horas="0"
                                    data-temas="No aplica">No Aplica</option>
                                @foreach ($pensum_autonoma as $semestre => $materias)
                                    <optgroup label="Semestre {{ $semestre }}">
                                        @foreach ($materias as $materia)
                                            <option value="{{ $materia['nombre'] }}"
                                                data-nombre="{{ $materia['nombre'] }}"
                                                data-descripcion="{{ $materia['descripcion'] }}"
                                                data-creditos="{{ $materia['creditos'] }}"
                                                data-horas="{{ $materia['horas'] }}"
                                                data-temas="{{ implode(', ', $materia['temas']) }}">
                                                {{ $materia['nombre'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <div id="titulo-pensum" class="alert alert-info" style="display:none;">
                                <i class="fas fa-info-circle me-2"></i>Seleccione una materia del pensum para homologar
                            </div>

                            <div id="pensum-details" class="mt-4 card border-secondary" style="display:none;">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Detalles de la Materia del Pensum</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 30%;">Nombre:</th>
                                                    <td><span id="pensum-nombre"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Créditos:</th>
                                                    <td><span id="pensum-creditos"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Horas:</th>
                                                    <td><span id="pensum-horas"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Descripción:</th>
                                                    <td><span id="pensum-descripcion"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Temas:</th>
                                                    <td><span id="pensum-temas"></span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="nota_homologacion" id="label-nota" class="form-label fw-bold"
                                    style="display:none;">
                                    <i class="fas fa-star me-2"></i>Nota de Homologación
                                </label>
                                <div class="input-group mb-3" id="input-nota-container" style="display:none;">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    <input type="number" id="nota_homologacion" name="nota_homologacion"
                                        class="form-control form-control-lg" step="0.1" min="0"
                                        max="20" placeholder="Ingrese la nota (3.0 - 5.0)" style="width: 100%;">

                                </div>
                            </div>

                            <button id="guardar-materia-btn" class="btn btn-success btn-lg w-100" style="display:none;">
                                <i class="fas fa-save me-2"></i>Guardar Materia
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de materias homologadas -->
            <div class="card mb-4 border-dark">
                <div class="card-header bg-dark text-white">
                    <h4 class="h5 mb-0"><i class="fas fa-list-check me-2"></i>Materias Homologadas</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-homologadas">
                            <thead class="table-dark">
                                <tr>
                                    <th>Materia Cursada</th>
                                    <th>Materia Pensum / Estado</th>
                                    <th>Nota</th>
                                    <th>Créditos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="no-materias-row">
                                    <td colspan="4" class="text-center">No hay materias homologadas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Guardar toda la homologación -->
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card mb-4 border-success">
                        <div class="card-body text-center">
                            <form action="{{ route('homologacion.guardar') }}" method="POST" id="homologacion-form">
                                @csrf
                                <input type="hidden" name="solicitud_id" value="{{ $solicitud['codigo'] }}">
                                <input type="hidden" name="homologaciones_data" id="homologaciones-data"
                                    value="">

                                <button type="button" class="btn btn-success btn-lg w-100" id="guardar-btn" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Guardar Homologación
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regresar -->
            <div class="text-center">
                <a href="{{ route('admin.homologacionescoordinador.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a la solicitud
                </a>
            </div>

            <!-- Contenedor para las alertas -->
            <div id="alerts-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>
        </div>
    </div>

    <!-- Añadir Font Awesome si no está incluido en el layout principal -->
    <link href="{{ asset('css/procesohomologacion.css') }}" rel="stylesheet">

    <!-- JS que maneja la lógica del flujo de homologación -->
    <script src="{{ asset('js/procesovshomologacion.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

@endsection

@extends('admin.layouts.appcoordinacion')

@section('title', 'Proceso de Homologación')

@section('content')
    <!-- proceso-homologacion.blade.php -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h3 class="m-0 font-weight-bold text-primary">Proceso de Homologación</h3>
                <input type="hidden" id="solicitud_id"
                    value="{{ $solicitud['id_solicitud'] ?? ($solicitud['id_homologacion'] ?? '') }}">
            </div>
            <div class="card-body">
                @if (!empty($warnings))
                    @foreach ($warnings as $warning)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ $warning }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endforeach
                @endif

                <!-- Datos del estudiante -->
                <div class="card mb-4">
                    <div class="card-header py-3 bg-light">
                        <h4 class="m-0 font-weight-bold text-dark">Datos del Estudiante</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombre:</strong>
                                    {{ $solicitud['estudiante'] ?? ($solicitud['nombre_estudiante'] ?? 'No disponible') }}</p>
                                <p><strong>Identificación:</strong>
                                    {{ $solicitud['numero_identificacion'] ?? 'No disponible' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Universidad de Origen:</strong>
                                    {{ $solicitud['institucion_origen_nombre'] ?? ($solicitud['institucion_origen'] ?? 'No disponible') }}
                                </p>
                                <p><strong>Programa de Destino:</strong>
                                    {{ $solicitud['programa_destino_nombre'] ?? ($solicitud['programa_destino'] ?? 'No disponible') }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha Solicitud:</strong>
                                    {{ $solicitud['fecha'] ?? ($solicitud['fecha_solicitud'] ?? 'No disponible') }}</p>
                                <p><strong>Número de Radicado:</strong>
                                    {{ $solicitud['numero_radicado'] ?? 'No disponible' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Estado:</strong>
                                    <span id="estado-solicitud"
                                        class="badge {{ ($solicitud['estado'] ?? '') == 'Pendiente' ? 'badge-warning' : (($solicitud['estado'] ?? '') == 'Aprobada' ? 'badge-success' : 'badge-secondary') }}">
                                        {{ $solicitud['estado'] ?? 'No disponible' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($asignaturasOrigen))
                    <div class="card mb-4">
                        <div class="card-header py-3 bg-light">
                            <h4 class="m-0 font-weight-bold text-dark">Asignaturas de Origen</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th>Semestre</th>
                                            <th>Créditos</th>
                                            <th>Nota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignaturasOrigen as $asignatura)
                                            <tr>
                                                <td>{{ $asignatura['nombre'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['codigo'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['semestre'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['creditos'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['nota_origen'] ?? ($asignatura['nota'] ?? 'No disponible') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if (!empty($asignaturasDestino))
                    <div class="card mb-4">
                        <div class="card-header py-3 bg-light">
                            <h4 class="m-0 font-weight-bold text-dark">Asignaturas de Destino</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th>Semestre</th>
                                            <th>Créditos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($asignaturasDestino as $asignatura)
                                            <tr>
                                                <td>{{ $asignatura['nombre'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['codigo'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['semestre'] ?? 'No disponible' }}</td>
                                                <td>{{ $asignatura['creditos'] ?? 'No disponible' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Proceso de Homologación -->
                <div class="card mb-4">
                    <div class="card-header py-3 bg-light">
                        <h4 class="m-0 font-weight-bold text-dark">Proceso de Homologación</h4>
                    </div>
                    <div class="card-body">
                        <!-- Pestañas para semestres -->
                        <ul class="nav nav-tabs" id="semestres-tab" role="tablist">
                            @for ($i = 1; $i <= 10; $i++)
                                <li class="nav-item">
                                    <a class="nav-link {{ $i == 1 ? 'active' : '' }}"
                                        id="semestre-{{ $i }}-tab" data-toggle="tab"
                                        href="#semestre-{{ $i }}" role="tab">
                                        <i class="fas fa-calendar-alt mr-1"></i> Semestre {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content py-3" id="semestres-content">
                            @for ($i = 1; $i <= 10; $i++)
                                <div class="tab-pane fade {{ $i == 1 ? 'show active' : '' }}"
                                    id="semestre-{{ $i }}" role="tabpanel">
                                    <div class="row">
                                        <!-- Asignaturas de origen (materias cursadas por el estudiante) -->
                                        <div class="col-md-6">
                                            <div class="card h-100">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0"><i class="fas fa-university mr-1"></i>
                                                        Asignaturas de Origen (Semestre {{ $i }})</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-sm mb-0">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Asignatura</th>
                                                                    <th>Nota</th>
                                                                    <th>Créditos</th>
                                                                    <th class="text-center">Seleccionar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="asignaturas-origen"
                                                                data-semestre="{{ $i }}">
                                                                @php
                                                                    $asignaturasDelSemestre = array_filter(
                                                                        $asignaturasOrigen,
                                                                        function ($materia) use ($i) {
                                                                            return isset($materia['semestre']) &&
                                                                                $materia['semestre'] == $i;
                                                                        },
                                                                    );
                                                                @endphp

                                                                @if (count($asignaturasDelSemestre) > 0)
                                                                    @foreach ($asignaturasDelSemestre as $materia)
                                                                        <tr>
                                                                            <td>
                                                                                <a href="#"
                                                                                    class="text-primary ver-asignatura-origen"
                                                                                    data-toggle="modal"
                                                                                    data-target="#modalInfoAsignaturaOrigen"
                                                                                    data-nombre="{{ $materia['nombre'] ?? 'Sin nombre' }}"
                                                                                    data-semestre="{{ $materia['semestre'] ?? 'No definido' }}"
                                                                                    data-creditos="{{ $materia['creditos'] ?? 'No asignado' }}"
                                                                                    data-modalidad="{{ $materia['modalidad'] ?? 'No disponible' }}">
                                                                                    {{ $materia['nombre'] ?? 'Sin nombre' }}
                                                                                </a>
                                                                            </td>
                                                                            <td>{{ $materia['nota_origen'] ?? ($materia['nota'] ?? '—') }}
                                                                            </td>
                                                                            <td>{{ $materia['creditos'] ?? '—' }}</td>
                                                                            <td class="text-center">
                                                                                <input type="checkbox" name="seleccionar[]"
                                                                                    value="{{ $materia['id_asignatura'] ?? ($materia['id'] ?? '') }}"
                                                                                    data-asignatura="{{ json_encode($materia) }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No hay
                                                                            asignaturas de origen para este semestre</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>



                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Asignaturas de destino (pensum Autónoma) -->
                                        <div class="col-md-6">
                                            <div class="card h-100">
                                                <div class="card-header bg-success text-white">
                                                    <h5 class="mb-0"><i class="fas fa-graduation-cap mr-1"></i>
                                                        Asignaturas de Destino (Semestre {{ $i }})</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-sm mb-0">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Asignatura</th>
                                                                    <th>Créditos</th>
                                                                    <th class="text-center">Seleccionar</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="asignaturas-destino"
                                                                data-semestre="{{ $i }}">
                                                                @php
                                                                    $asignaturasDestinoDelSemestre = array_filter(
                                                                        $asignaturasDestino,
                                                                        function ($materia) use ($i) {
                                                                            return isset($materia['semestre']) &&
                                                                                $materia['semestre'] == $i;
                                                                        },
                                                                    );
                                                                @endphp

                                                                @if (count($asignaturasDestinoDelSemestre) > 0)
                                                                    @foreach ($asignaturasDestinoDelSemestre as $materia)
                                                                        <tr>
                                                                            <td>
                                                                                <a href="#"
                                                                                    class="text-primary ver-asignatura-destino"
                                                                                    data-toggle="modal"
                                                                                    data-target="#modalInfoAsignatura"
                                                                                    data-nombre="{{ $materia['nombre'] ?? 'Sin nombre' }}"
                                                                                    data-semestre="{{ $materia['semestre'] ?? 'No definido' }}"
                                                                                    data-creditos="{{ $materia['creditos'] ?? 'No asignado' }}"
                                                                                    data-modalidad="{{ $materia['modalidad'] ?? 'No disponible' }}">
                                                                                    {{ $materia['nombre'] ?? 'Sin nombre' }}
                                                                                </a>
                                                                            </td>
                                                                            <td>{{ $materia['creditos'] ?? '—' }}</td>
                                                                            <td class="text-center">
                                                                                <input type="checkbox"
                                                                                    name="seleccionar_destino[]"
                                                                                    value="{{ $materia['id_asignatura'] ?? ($materia['id'] ?? '') }}"
                                                                                    data-asignatura="{{ json_encode($materia) }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="3" class="text-center">No hay
                                                                            asignaturas de destino para este semestre</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Tabla de Homologaciones -->
                <div class="card mb-4">
                    <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold text-dark">Asignaturas Homologadas</h4>
                        <button id="btn-agregar-homologacion" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Agregar Homologación
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabla-homologaciones">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Asignatura Origen</th>
                                        <th>Asignatura Destino</th>
                                        <th>Nota Origen</th>
                                        <th>Nota Homologada</th>
                                        <th>Créditos</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="homologaciones-body">
                                    <!-- Se llena con JavaScript -->
                                    <tr id="no-homologaciones">
                                        <td colspan="6" class="text-center">No hay asignaturas homologadas</td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Total de Créditos:</strong></td>
                                        <td id="total-creditos" class="font-weight-bold">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sección de Firma -->
                <div class="card mb-4">
                    <div class="card-header py-3 bg-light">
                        <h4 class="m-0 font-weight-bold text-dark">Firma del Coordinador</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firma"><i class="fas fa-signature mr-1"></i> Subir Firma:</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="firma" accept="image/*">
                                        <label class="custom-file-label" for="firma">Seleccionar archivo...</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="firma-preview" class="mt-3 border p-3 text-center">
                                    <p class="text-muted mb-0">Vista previa de la firma</p>
                                    <!-- Vista previa de la firma -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <button id="btn-guardar" class="btn btn-primary btn-lg">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                        <button id="btn-generar-pdf" class="btn btn-success btn-lg mx-2">
                            <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                        </button>
                        <button id="btn-cerrar-homologacion" class="btn btn-danger btn-lg">
                            <i class="fas fa-times-circle mr-1"></i> Cerrar Homologación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar homologación -->
    <div class="modal fade" id="modal-agregar-homologacion" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt"></i> Agregar Homologación</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-homologacion">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asignatura-origen">Asignatura de Origen:</label>
                                    <select class="form-control" id="asignatura-origen" required>
                                        <option value="">Seleccionar asignatura...</option>
                                        @foreach ($asignaturasOrigen as $asignatura)
                                            <option value="{{ $asignatura['id_asignatura'] ?? ($asignatura['id'] ?? '') }}"
                                                data-nombre="{{ $asignatura['nombre'] ?? '' }}"
                                                data-creditos="{{ $asignatura['creditos'] ?? 0 }}"
                                                data-nota="{{ $asignatura['nota_origen'] ?? ($asignatura['nota'] ?? 0) }}">
                                                {{ $asignatura['nombre'] ?? 'Sin nombre' }}
                                                ({{ $asignatura['codigo'] ?? 'Sin código' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asignatura-destino">Asignatura de Destino:</label>
                                    <select class="form-control" id="asignatura-destino" required>
                                        <option value="">Seleccionar asignatura...</option>
                                        @foreach ($asignaturasDestino as $asignatura)
                                            <option value="{{ $asignatura['id_asignatura'] ?? ($asignatura['id'] ?? '') }}"
                                                data-nombre="{{ $asignatura['nombre'] ?? '' }}"
                                                data-creditos="{{ $asignatura['creditos'] ?? 0 }}">
                                                {{ $asignatura['nombre'] ?? 'Sin nombre' }}
                                                ({{ $asignatura['codigo'] ?? 'Sin código' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nota-origen">Nota de Origen:</label>
                                    <input type="number" class="form-control" id="nota-origen" step="0.1"
                                        min="0" max="5" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nota-homologada">Nota Homologada:</label>
                                    <input type="number" class="form-control" id="nota-homologada" step="0.1"
                                        min="0" max="5" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="observacion">Observación:</label>
                            <textarea class="form-control" id="observacion" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btn-confirmar-homologacion">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de información de asignatura origen -->
    <div class="modal fade" id="modalInfoAsignaturaOrigen" tabindex="-1" role="dialog"
        aria-labelledby="modalInfoAsignaturaOrigenLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalInfoAsignaturaOrigenLabel">Información de Asignatura de Origen</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="infoNombreOrigen"></span></p>
                    <p><strong>Semestre:</strong> <span id="infoSemestreOrigen"></span></p>
                    <p><strong>Créditos:</strong> <span id="infoCreditosOrigen"></span></p>
                    <p><strong>Modalidad:</strong> <span id="infoModalidadOrigen"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de información de asignatura destino -->
    <div class="modal fade" id="modalInfoAsignatura" tabindex="-1" role="dialog"
        aria-labelledby="modalInfoAsignaturaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalInfoAsignaturaLabel">Información de Asignatura</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="infoNombre"></span></p>
                    <p><strong>Semestre:</strong> <span id="infoSemestre"></span></p>
                    <p><strong>Créditos:</strong> <span id="infoCreditos"></span></p>
                    <p><strong>Modalidad:</strong> <span id="infoModalidad"></span></p>
                    <hr>
                    <div id="infoContenidoProgramatico" style="display: none;">
                        <h6>Contenido Programático:</h6>
                        <p><strong>Tema:</strong> <span id="infoTema"></span></p>
                        <p><strong>Resultados de Aprendizaje:</strong> <span id="infoResultados"></span></p>
                        <p><strong>Descripción:</strong> <span id="infoDescripcion"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vista Previa PDF -->
    <div class="modal fade" id="pdf-preview-modal" tabindex="-1" role="dialog" aria-labelledby="pdfPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfPreviewModalLabel"><i class="fas fa-file-pdf mr-1"></i> Vista
                        Previa del PDF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="pdf-preview-content">
                    <!-- Contenido del PDF se mostrará aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-confirmar-pdf">
                        <i class="fas fa-check mr-1"></i> Confirmar y Descargar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/procesohomologacion.js') }}"></script>
@endsection

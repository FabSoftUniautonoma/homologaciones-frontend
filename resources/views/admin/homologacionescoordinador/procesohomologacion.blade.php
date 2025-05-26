@extends('admin.layouts.appcoordinacion')

@section('title', 'Proceso de Homologación')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-gradient-primary">
                <h3 class="m-0 font-weight-bold text-white">Proceso de Homologación</h3>
                <input type="hidden" id="solicitud_id" value="{{ $solicitud['id_solicitud'] ?? ($solicitud['id'] ?? '') }}">
                <input type="hidden" id="solicitud_id"
                    value="{{ $solicitudId ?? ($solicitud['solicitud_id'] ?? ($solicitud['id_solicitud'] ?? ($solicitud['id'] ?? ($solicitud['solicitud']['id'] ?? '')))) }}">
            </div>
            <div class="card-body">
                {{-- Contenedor para alertas --}}
                <div id="alert-container" class="mb-4"></div>

                {{-- Alertas --}}
                @if (!empty($warnings))
                    <div class="alert-container mb-4">
                        @foreach ($warnings as $warning)
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i> {{ $warning }}
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Datos del estudiante --}}

                <div class="card mb-4 border-left-primary">
                    <div class="card-header py-3" style="background-color: #19407b;">
                        <h4 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-user-graduate mr-2"></i>Datos del Estudiante
                        </h4>
                    </div>

                    <div class="card-body" style="background-color: #f9f9f9;">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Nombre</label>
                                    <p class="font-weight-bold mb-0" style="color: #003366;">
                                        {{ $solicitud['estudiante'] ?? ($solicitud['nombre_estudiante'] ?? 'No disponible') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Identificación</label>
                                    <p class="font-weight-bold mb-0" style="color: #003366;">
                                        {{ $solicitud['numero_identificacion'] ?? 'No disponible' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Universidad de
                                        Origen</label>
                                    <p class="font-weight-bold mb-0" style="color: #003366;">
                                        {{ $solicitud['universidad_origen'] ?? 'No disponible' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Programa de interés</label>
                                    <p class="font-weight-bold mb-0" style="color: #003366;">
                                        {{ $solicitud['programa_destino_nombre'] ?? ($solicitud['programa_destino'] ?? 'No disponible') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Fecha Solicitud</label>
                                    <p class="font-weight-bold mb-0" style="color: #003366;">
                                        {{ $solicitud['fecha'] ?? ($solicitud['fecha_solicitud'] ?? 'No disponible') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Número de Radicado</label>
                                    <p class="font-weight-bold mb-0" style="color: #003366;">
                                        {{ $solicitud['numero_radicado'] ?? 'No disponible' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">Estado</label>
                                    <p class="mb-0">
                                        <span id="estado-solicitud"
                                            class="badge badge-pill px-3 py-2 {{ ($solicitud['estado_solicitud'] ?? '') == 'Pendiente' ? 'badge-warning' : (($solicitud['estado'] ?? '') == 'Aprobada' ? 'badge-success' : 'badge-secondary') }}"
                                            style="font-weight: bold;">
                                            {{ $solicitud['estado_solicitud'] ?? 'No disponible' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="info-item">
                                    <label class="text-muted small mb-1" style="color: #19407b;">ID Homologación</label>
                                    <p class="font-weight-bold mb-0" id="id-homologacion" style="color: #003366;">
                                        {{ $solicitud['id_homologacion'] ?? 'No disponible' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Proceso de Homologación --}}
                <div class="card mb-4 border-left-success">
                    <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold" style="color: #19407b;">
                            <i class="fas fa-exchange-alt mr-2" style="color: #19407b;"></i>Proceso de Homologación
                        </h4>
                        <button id="btn-agregar-homologacion" class="btn btn-success"
                            style="background-color: #19407b; border-color: #19407b;">
                            <i class="fas fa-plus mr-1"></i> Agregar Homologación
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- Panel de selección de asignaturas --}}
                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <div class="card h-100 border-left-primary" style="border-left-color: #19407b;">
                                    <div class="card-header text-white" style="background-color: #19407b !important;">
                                        <h5 class="mb-0" style="color: #ffffff;"><i class="fas fa-university mr-1"></i>
                                            Asignaturas de Origen</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="form-group px-3 pt-3">
                                            <label for="filtro-semestre-origen" class="font-weight-bold"
                                                style="color: #19407b !important;">Filtrar por semestre:</label>
                                            <select id="filtro-semestre-origen" class="form-control form-control-sm"
                                                style="background-color: white; color: #19407b;">
                                                <option value="0" style="color: #19407b;">Todos los semestres
                                                </option>
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" style="color: #19407b;">Semestre
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>

                                        </div>
                                        <div class="table-responsive" style="max-height: 400px;">
                                            <table class="table table-hover mb-0">
                                                <thead class="sticky-top" style="background-color: #e1f5fe;">
                                                    <tr>
                                                        <th style="color: #ffffff;">Asignatura</th>
                                                        <th class="text-center" width="80" style="color: #ffffff;">
                                                            Créditos</th>
                                                        <th class="text-center" width="60" style="color: #ffffff;">
                                                            Nota</th>
                                                        <th class="text-center" width="80" style="color: #ffffff;">
                                                            Semestre</th>
                                                        <th class="text-center" width="60" style="color: #ffffff;">
                                                            Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="asignaturas-origen-body">
                                                    @forelse ($asignaturasOrigen as $materia)
                                                        <tr class="asignatura-row"
                                                            data-semestre="{{ $materia['semestre'] ?? 0 }}">
                                                            <td>
                                                                <span class="text-primary"
                                                                    style="color: #19407b !important; cursor: pointer;">{{ $materia['nombre'] ?? 'Sin nombre' }}</span>
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ $materia['codigo'] ?? 'Sin código' }}</small>
                                                            </td>
                                                            <td class="text-center" style="color: #0277bd;">
                                                                {{ $materia['creditos'] ?? '—' }}</td>
                                                            <td class="text-center" style="color: #0277bd;">
                                                                {{ $materia['nota_origen'] ?? ($materia['nota'] ?? '—') }}
                                                            </td>
                                                            <td class="text-center" style="color: #0277bd;">
                                                                {{ $materia['semestre'] ?? '—' }}</td>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-sm seleccionar-asignatura"
                                                                    style="background-color: #19407b; border-color: #19407b; color: white;"
                                                                    data-tipo="origen"
                                                                    data-asignatura="{{ json_encode($materia) }}">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-3">
                                                                <span class="text-primary" style="color: #6c8ebf;">No hay
                                                                    asignaturas de origen
                                                                    disponibles</span>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card h-100 border-left-success" style="border-left-color: #e1f5fe;">
                                    <div class="card-header text-white"
                                        style="background-color: #0277bd !important; color: white !important;">
                                        <h5 class="mb-0" style="color: white;"><i
                                                class="fas fa-graduation-cap mr-1"></i> Asignaturas de
                                            Destino</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="form-group px-3 pt-3">
                                            <label for="filtro-semestre-destino" class="font-weight-bold"
                                                style="color: #0277bd !important;">Filtrar por semestre:</label>
                                            <select id="filtro-semestre-destino" class="form-control form-control-sm"
                                                style="background-color: white; color: #19407b;">
                                                <option value="0" style="color: #19407b;">Todos los semestres
                                                </option>
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" style="color: #19407b;">Semestre
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>

                                        </div>
                                        <div class="table-responsive" style="max-height: 400px;">
                                            <table class="table table-hover mb-0">
                                                <thead class="sticky-top" style="background-color: #e1f5fe;">
                                                    <tr>
                                                        <th style="color: #ffffff;">Asignatura</th>
                                                        <th class="text-center" width="80" style="color: #ffffff;">
                                                            Créditos</th>
                                                        <th class="text-center" width="80" style="color: #ffffff;">
                                                            Semestre</th>
                                                        <th class="text-center" width="60" style="color: #ffffff;">
                                                            Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="asignaturas-destino-body">
                                                    @forelse ($asignaturasDestino ?? ($pensum ?? []) as $materia)
                                                        <tr class="asignatura-row"
                                                            data-semestre="{{ $materia['semestre'] ?? 0 }}">
                                                            <td>
                                                                <span class="text-success"
                                                                    style="color: #0277bd !important; cursor: pointer;">{{ $materia['nombre'] ?? 'Sin nombre' }}</span>
                                                                <br>
                                                                <small
                                                                    class="text-muted">{{ $materia['codigo_asignatura'] ?? 'Sin código' }}</small>
                                                            </td>
                                                            <td class="text-center" style="color: #19407b;">
                                                                {{ $materia['creditos'] ?? '—' }}</td>
                                                            <td class="text-center" style="color: #19407b;">
                                                                {{ $materia['semestre'] ?? '—' }}</td>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-sm seleccionar-asignatura"
                                                                    style="background-color: #0277bd; border-color: #0277bd; color: white;"
                                                                    data-tipo="destino"
                                                                    data-asignatura="{{ json_encode($materia) }}">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-3">
                                                                <span class="text-success" style="color: #6c8ebf;">No hay
                                                                    asignaturas de destino
                                                                    disponibles</span>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tabla de Homologaciones --}}
                    <div class="card mb-4 border-left-dark" style="border-left: 5px solid #19407b;">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center"
                            style="background-color: #e1f5fe;">
                            <h4 class="m-0 font-weight-bold" style="color: #19407b;">
                                <i class="fas fa-list-alt mr-2" style="color: #19407b;"></i>Asignaturas Homologadas
                            </h4>
                            <div>
                                <button id="btn-guardar-homologaciones" class="btn"
                                    style="background-color: #19407b; color: white;">
                                    <i class="fas fa-save mr-1"></i> Guardar
                                </button>
                                <!-- Botón para limpiar solo asignaturas destino -->
                                <button id="btn-limpiar-destino" class="btn btn-warning"
                                    onclick="limpiarAsignaturasDestino()">
                                    <i class="fas fa-eraser"></i> Limpiar Asignaturas Destino
                                </button>

                                <!-- Botón para limpiar todas las homologaciones -->
                                <button id="btn-limpiar-homologaciones" class="btn btn-danger"
                                    onclick="limpiarHomologaciones()">
                                    <i class="fas fa-trash"></i> Eliminar Todas las Homologaciones
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tabla-homologaciones">
                                    <thead>
                                        <tr style="background-color: #19407b; color: white;">
                                            <th>Asignatura Origen</th>
                                            <th>Asignatura Destino</th>
                                            <th width="100" class="text-center">Nota Origen</th>
                                            <th width="100" class="text-center">Nota Homologada</th>
                                            <th width="80" class="text-center">Créditos</th>
                                            <th width="100" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="homologaciones-body">
                                        <tr id="no-homologaciones">
                                            <td colspan="6" class="text-center py-4"
                                                style="background-color: #f0f8ff;">
                                                <div class="empty-state">
                                                    <i class="fas fa-clipboard-list fa-3x mb-3"
                                                        style="color: #6c8ebf;"></i>
                                                    <p style="color: #19407b;">No hay asignaturas homologadas</p>
                                                    <p class="small" style="color: #0277bd;">Seleccione asignaturas
                                                        de origen y destino para comenzar</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot style="background-color: #e6f0ff;">
                                        <tr>
                                            <td colspan="4" class="text-right" style="color: #19407b;"><strong>Total
                                                    de Créditos:</strong>
                                            </td>
                                            <td id="total-creditos" class="font-weight-bold text-center"
                                                style="color: #19407b;">0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Sección de Firma coordinador --}}
                        <div class="card mb-4 border-left-warning" style="border-left-color: #0277bd;">
                            <div class="card-header py-3 text-white" style="background-color: #0277bd;">
                                <h4 class="m-0 font-weight-bold">
                                    <i class="fas fa-signature mr-2"></i>Firma del Coordinador
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firma" class="font-weight-bold" style="color: #19407b;">
                                                <i class="fas fa-file-upload mr-1"></i> Subir Firma:
                                            </label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="firma"
                                                    accept="image/*" onchange="handleFirmaCoordinadorUpload(event)">
                                                <label class="custom-file-label" for="firma" style="color: #0277bd;">
                                                    Seleccionar archivo...
                                                </label>
                                            </div>
                                            <small class="form-text" style="color: #6c8ebf;">
                                                Formatos aceptados: JPG, PNG, GIF
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="firma-preview"
                                            class="border rounded p-3 text-center d-flex align-items-center justify-content-center"
                                            style="height: 150px; background-color: #e1f5fe; border-color: #6c8ebf;">
                                            <p style="color: #19407b;" class="mb-0" id="firma-coordinador-placeholder">
                                                Vista previa de la firma
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="firma_coordinador_data" name="firma_coordinador_data">

                                <div class="mt-3 text-center">
                                    <button type="button" id="btn-generar-pdf-coordinador" class="btn btn-lg"
                                        style="background-color: #0277bd; color: white;" disabled>
                                        <i class="fas fa-file-pdf mr-2"></i> Generar PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{-- Modal de Vista Previa PDF --}}
        <div class="modal fade" id="pdf-preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0277bd; color: white;">
                        <h5 class="modal-title">
                            <i class="fas fa-file-pdf mr-2"></i>Vista Previa del PDF
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="pdf-preview-content" style="background-color: #f8f9fc;">
                        <div class="text-center p-5 bg-light" style="border-radius: 5px; border: 1px dashed #6c8ebf;">
                            <i class="fas fa-file-pdf fa-3x mb-3" style="color: #19407b;"></i>
                            <h5 style="color: #19407b;">Visualización del documento</h5>
                            <p style="color: #0277bd;">El documento se está generando...</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #e6f0ff;">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #6c8ebf; color: white;">
                            <i class="fas fa-times mr-1"></i> Cerrar
                        </button>
                        <button type="button" class="btn" id="btn-confirmar-pdf"
                            style="background-color: #0277bd; color: white;">
                            <i class="fas fa-paper-plane mr-1"></i> Confirmar y Enviar a Vicerrector
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal para agregar/editar homologación --}}
        <div class="modal fade" id="modal-agregar-homologacion" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #19407b; color: white;">
                        <h5 class="modal-title" id="modal-titulo" style="color: white;">
                            <i class="fas fa-plus-circle mr-2"></i>Agregar Homologación
                        </h5>

                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f8f9fc;">
                        <form id="form-homologacion">
                            <input type="hidden" id="homologacion-index" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asignatura-origen" style="color: #19407b; font-weight: bold;">
                                            <i class="fas fa-university mr-1"></i>Asignatura de Origen:
                                        </label>
                                        <select id="asignatura-origen" class="form-control" required
                                            style="border-color: #6c8ebf;">
                                            <option value="">Seleccione una asignatura...</option>
                                            @foreach ($asignaturasOrigen as $asignatura)
                                                <option
                                                    value="{{ $asignatura['id_asignatura'] ?? ($asignatura['id'] ?? '') }}">
                                                    {{ $asignatura['nombre'] }}
                                                    ({{ $asignatura['codigo'] ?? 'Sin código' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asignatura-destino" style="color: #0277bd; font-weight: bold;">
                                            <i class="fas fa-graduation-cap mr-1"></i>Asignatura de Destino:
                                        </label>
                                        <select id="asignatura-destino" class="form-control" required
                                            style="border-color: #6c8ebf;">
                                            <option value="">Seleccione una asignatura...</option>
                                            @foreach ($asignaturasDestino ?? ($pensum ?? []) as $asignatura)
                                                <option
                                                    value="{{ $asignatura['id_asignatura'] ?? ($asignatura['id'] ?? '') }}">
                                                    {{ $asignatura['nombre'] }}
                                                    ({{ $asignatura['codigo_asignatura'] ?? 'Sin código' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nota-origen" style="color: #19407b; font-weight: bold;">
                                            <i class="fas fa-star-half-alt mr-1"></i>Nota Origen:
                                        </label>
                                        <input type="number" class="form-control" id="nota-origen" step="0.1"
                                            min="0" max="5" readonly
                                            style="background-color: #e6f0ff; border-color: #6c8ebf;">
                                        <small class="form-text" style="color: #6c8ebf;">Nota de la asignatura en la
                                            universidad de
                                            origen</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nota-homologada" style="color: #0277bd; font-weight: bold;">
                                            <i class="fas fa-star mr-1"></i>Nota Homologada:
                                        </label>
                                        <input type="number" class="form-control" id="nota-homologada" step="0.1"
                                            min="0" max="5" required style="border-color: #6c8ebf;">
                                        <small class="form-text" style="color: #6c8ebf;">Nota con la que se homologa
                                            (0.0-5.0)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="creditos-homologados" style="color: #0277bd; font-weight: bold;">
                                            <i class="fas fa-award mr-1"></i>Créditos:
                                        </label>
                                        <input type="number" class="form-control" id="creditos-homologados"
                                            min="0" max="20" readonly
                                            style="background-color: #e6f0ff; border-color: #6c8ebf;">
                                        <small class="form-text" style="color: #6c8ebf;">Créditos de la asignatura
                                            destino</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacion" style="color: #19407b; font-weight: bold;">
                                    <i class="fas fa-comment-alt mr-1"></i>Observación:
                                </label>
                                <textarea class="form-control" id="observacion" rows="2"
                                    placeholder="Escriba observaciones sobre esta homologación (opcional)" style="border-color: #6c8ebf;"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #e6f0ff;">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #6c8ebf; color: white;">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn" id="btn-confirmar-homologacion"
                            style="background-color: #19407b; color: white;">
                            <i class="fas fa-check mr-1"></i> Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal de alertas --}}
        <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #19407b; color: white;">
                        <h5 class="modal-title" style="background-color: #19407b; color: #ffffff;">
                            <i class="fas fa-bell mr-2"></i>Notificación
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="alertModalMessage" style="background-color: #f8f9fc;">
                        <!-- El mensaje se insertará aquí -->
                    </div>
                    <div class="modal-footer" style="background-color: #e6f0ff;">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #19407b; color: white;">
                            <i class="fas fa-check mr-1"></i> Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para información de asignatura --}}
        <div class="modal fade" id="modalInfoAsignatura" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #19407b; color: white;">
                        <h5 class="modal-title" id="modalInfoAsignaturaTitle"
                            style="background-color: #19407b; color: #ffffff;">
                            <i class="fas fa-info-circle mr-2"></i>Información de Asignatura
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f8f9fc;">
                        <!-- Información básica -->
                        <div class="info-item mb-3 p-3 rounded"
                            style="background-color: #e1f5fe; border-left: 4px solid #19407b;">
                            <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Nombre</label>
                            <p class="font-weight-bold mb-0" id="infoNombre" style="color: #19407b;">-</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #19407b;">
                                    <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Código</label>
                                    <p class="font-weight-bold mb-0" id="infoCodigo" style="color: #19407b;">-</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #19407b;">
                                    <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Semestre</label>
                                    <p class="font-weight-bold mb-0" id="infoSemestre" style="color: #19407b;">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #19407b;">
                                    <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Créditos</label>
                                    <p class="font-weight-bold mb-0" id="infoCreditos" style="color: #19407b;">-</p>
                                </div>
                            </div>
                            <div class="col-md-6" id="infoNota">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #19407b;">
                                    <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Nota</label>
                                    <p class="font-weight-bold mb-0" id="infoNotaValue" style="color: #19407b;">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Información de programa -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #0277bd;">
                                    <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Programa</label>
                                    <p class="font-weight-bold mb-0" id="infoPrograma" style="color: #19407b;">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #0277bd;">
                                    <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Facultad</label>
                                    <p class="font-weight-bold mb-0" id="infoFacultad" style="color: #19407b;">-</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3 p-3 rounded"
                                    style="background-color: #e1f5fe; border-left: 4px solid #0277bd;">
                                    <label class="small mb-1"
                                        style="color: #0277bd; font-weight: bold;">Institución</label>
                                    <p class="font-weight-bold mb-0" id="infoInstitucion" style="color: #19407b;">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Programático (inicialmente oculto) -->
                        <div id="infoContenidoProgramatico" style="display:none;">
                            <hr style="border-color: #6c8ebf;">
                            <h5 style="color: #19407b; font-weight: bold; margin-bottom: 15px;">
                                <i class="fas fa-book mr-2"></i>Contenido Programático
                            </h5>

                            <div class="info-item mb-3 p-3 rounded"
                                style="background-color: #e6f0ff; border-left: 4px solid #0277bd;">
                                <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Tema</label>
                                <p class="font-weight-bold mb-0" id="infoTema" style="color: #19407b;">-</p>
                            </div>

                            <div class="info-item mb-3 p-3 rounded"
                                style="background-color: #e6f0ff; border-left: 4px solid #0277bd;">
                                <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Resultados de
                                    Aprendizaje</label>
                                <p class="mb-0" id="infoResultadosAprendizaje" style="color: #19407b;">-</p>
                                <button id="verMasResultados" class="btn btn-sm"
                                    style="display:none; color: white; background-color: #6c8ebf; margin-top: 10px;">
                                    <i class="fas fa-search-plus mr-1"></i>Ver más
                                </button>
                            </div>

                            <div class="info-item mb-3 p-3 rounded"
                                style="background-color: #e6f0ff; border-left: 4px solid #0277bd;">
                                <label class="small mb-1" style="color: #0277bd; font-weight: bold;">Descripción</label>
                                <p class="mb-0" id="infoDescripcion" style="color: #19407b;">-</p>
                                <button id="verMasDescripcion" class="btn btn-sm"
                                    style="display:none; color: white; background-color: #6c8ebf; margin-top: 10px;">
                                    <i class="fas fa-search-plus mr-1"></i>Ver más
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #e6f0ff;">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #19407b; color: white;">
                            <i class="fas fa-times mr-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para mostrar texto completo --}}
        <div class="modal fade" id="modalTextoCompleto" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0277bd; color: white;">
                        <h5 class="modal-title" id="modalTextoCompletoTitle">
                            <i class="fas fa-file-alt mr-2"></i>Contenido Completo
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="background-color: #f8f9fc;">
                        <div class="p-3 rounded" style="background-color: #e1f5fe; border-left: 4px solid #19407b;">
                            <p id="textoCompletoContenido" style="color: #19407b; line-height: 1.6;"></p>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #e6f0ff;">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #0277bd; color: white;">
                            <i class="fas fa-times mr-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estilos adicionales para mejorar la interfaz --}}
    <style>
        /* Mejora visual para los encabezados de las tarjetas */
        .card-header {
            border-bottom: 0;
        }

        /* Mejora la visualización de los elementos seleccionados */
        .asignatura-row.table-primary,
        .asignatura-row.table-success,
        .asignatura-row.table-active {
            font-weight: bold;
        }

        /* Mejora visual para los filtros de semestre */
        #filtro-semestre-origen,
        #filtro-semestre-destino {
            border-radius: 20px;
        }

        /* Mejora la visibilidad de las badges */
        .badge-pill {
            font-size: 14px;
        }

        /* Estado vacío mejorado */
        .empty-state {
            padding: 30px;
            text-align: center;
        }

        /* Mejora visual para la sección de información del estudiante */
        .info-item {
            border-radius: 5px;
        }

        /* Estilo mejorado para los botones principales */
        .action-buttons .btn {
            min-width: 180px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Mejoras para tablas */
        .table-responsive {
            border-radius: 5px;
            overflow: hidden;
        }

        thead.sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Estilo para resaltar filas al pasar el ratón */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('scripts')
    {{-- Estilos para la versión imprimible --}}
    <style media="print">
        .card {
            border: 1px solid #ddd !important;
            margin-bottom: 20px !important;
        }

        .no-print {
            display: none !important;
        }

        .table-bordered {
            border: 1px solid #ddd !important;
        }

        .page-break {
            page-break-before: always;
        }

        .bg-primary,
        .bg-success,
        .bg-info,
        .bg-warning,
        .bg-danger {
            background-color: white !important;
            color: black !important;
        }

        .text-white {
            color: black !important;
        }

        /* Asegura que el contenido importante se muestre correctamente */
        #tabla-homologaciones {
            width: 100% !important;
            page-break-inside: avoid;
        }
    </style>

    <!-- Variables de PHP a JavaScript -->
    <script>
        // Pasar datos del controlador al JavaScript
        window._asignaturasOrigen = @json($asignaturasOrigen ?? []);
        window._asignaturasDestino = @json($asignaturasDestino ?? ($pensum ?? []));
        window._homologacionesExistentes = @json($homologacionesExistentes ?? []);

        // Añadir el ID de solicitud directamente aquí, de manera flexible
        window._solicitudId =
            "{{ $solicitudId ?? ($solicitud['solicitud_id'] ?? ($solicitud['id_solicitud'] ?? ($solicitud['id'] ?? ($solicitud['solicitud']['id'] ?? '')))) }}";

        // También añadir el ID de homologación
        window._homologacionId = "{{ $homologacionId ?? ($solicitud['id_homologacion'] ?? '') }}";

        console.log('IDs inicializados:', {
            solicitudId: window._solicitudId,
            homologacionId: window._homologacionId
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

    <!-- Script personalizado para la filtración de asignaturas por semestre -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Eventos de filtrado para asignaturas por semestre
            document.getElementById('filtro-semestre-origen').addEventListener('change', function() {
                filtrarAsignaturasPorSemestre('origen', this.value);
            });

            document.getElementById('filtro-semestre-destino').addEventListener('change', function() {
                filtrarAsignaturasPorSemestre('destino', this.value);
            });

            // Función para filtrar asignaturas por semestre
            function filtrarAsignaturasPorSemestre(tipo, semestre) {
                const filas = document.querySelectorAll(`#asignaturas-${tipo}-body .asignatura-row`);

                filas.forEach(fila => {
                    const semestreFila = fila.getAttribute('data-semestre');
                    if (semestre === '0' || semestreFila === semestre) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            }
        });
    </script>
    <script>
        /**
         * Script para manejo de información de asignaturas y homologaciones
         * Corregido para asegurar la correcta visualización de información de asignaturas
         * de origen y destino desde sus respectivas APIs
         */
        $(document).ready(function() {
            console.log('Script de información de asignaturas inicializado');

            // Verificar si hay datos de asignaturas al cargar
            console.log('Asignaturas de destino cargadas:', window._asignaturasDestino ? window._asignaturasDestino
                .length : 0);
            console.log('Asignaturas de origen cargadas:', window._asignaturasOrigen ? window._asignaturasOrigen
                .length : 0);

            // Agregar evento click a los nombres de asignaturas en la tabla de origen
            $('#asignaturas-origen-body').on('click', '.text-primary', function() {
                console.log('Click en asignatura de origen');

                // Obtener la fila padre
                const row = $(this).closest('tr');

                // Obtener el atributo data-asignatura del botón en la misma fila
                const asignaturaStr = row.find('.seleccionar-asignatura').attr('data-asignatura');
                let asignaturaData;

                try {
                    asignaturaData = JSON.parse(asignaturaStr);
                    console.log('Datos de asignatura origen:', asignaturaData);
                } catch (e) {
                    console.error('Error al parsear datos de asignatura:', e, asignaturaStr);
                    mostrarAlerta('Error al cargar información de la asignatura', 'danger');
                    return;
                }

                mostrarInformacionAsignatura(asignaturaData, 'origen');
            });

            // Agregar evento click a los nombres de asignaturas en la tabla de destino
            $('#asignaturas-destino-body').on('click', '.text-success', function() {
                console.log('Click en asignatura de destino');

                // Obtener la fila padre
                const row = $(this).closest('tr');

                // Obtener el atributo data-asignatura del botón en la misma fila
                const asignaturaStr = row.find('.seleccionar-asignatura').attr('data-asignatura');
                let asignaturaData;

                try {
                    asignaturaData = JSON.parse(asignaturaStr);
                    console.log('Datos de asignatura destino:', asignaturaData);

                    // Verificar si es necesario buscar información adicional desde la API
                    if (!asignaturaData.nombre_programa && window._asignaturasDestino) {
                        // Buscar datos completos de la asignatura en los datos precargados
                        const asignaturaCompleta = window._asignaturasDestino.find(a =>
                            a.id_asignatura == asignaturaData.id_asignatura ||
                            a.id == asignaturaData.id_asignatura ||
                            a.codigo_asignatura == asignaturaData.codigo_asignatura
                        );

                        if (asignaturaCompleta) {
                            console.log('Encontrada asignatura completa en datos precargados:',
                                asignaturaCompleta);
                            asignaturaData = {
                                ...asignaturaData,
                                ...asignaturaCompleta
                            };
                        }
                    }
                } catch (e) {
                    console.error('Error al parsear datos de asignatura:', e, asignaturaStr);
                    mostrarAlerta('Error al cargar información de la asignatura', 'danger');
                    return;
                }

                mostrarInformacionAsignatura(asignaturaData, 'destino');
            });

            // Agregar evento click a los nombres de asignaturas en la tabla de homologaciones
            $('#homologaciones-body').on('click', '.nombre-asignatura', function() {
                console.log('Click en asignatura homologada');

                // Obtener los datos almacenados del atributo data
                const asignaturaStr = $(this).attr('data-info');
                let asignaturaData;

                try {
                    asignaturaData = JSON.parse(asignaturaStr);
                    console.log('Datos de asignatura homologada:', asignaturaData);
                } catch (e) {
                    console.error('Error al parsear datos de asignatura:', e, asignaturaStr);
                    mostrarAlerta('Error al cargar información de la asignatura', 'danger');
                    return;
                }

                const tipo = $(this).hasClass('text-primary') ? 'origen' : 'destino';
                mostrarInformacionAsignatura(asignaturaData, tipo);
            });

            /**
             * Obtiene información de una asignatura desde la API
             * @param {string} tipo - origen o destino
             * @param {number|string} id - ID de la asignatura
             * @returns {Promise} - Promesa con los datos de la asignatura
             */
            function obtenerDatosAsignatura(tipo, id) {
                if (!id) return Promise.reject('ID no válido');

                let url = '';
                if (tipo === 'destino') {
                    url = `https://homologacionesback.educarenemociones.com/api/asignaturas/${id}`;
                } else {
                    // Para asignaturas de origen, podríamos crear un endpoint específico
                    return Promise.reject('No hay API específica para asignaturas de origen individuales');
                }

                return $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json'
                }).then(response => {
                    if (response.success && response.data) {
                        return response.data;
                    } else {
                        return Promise.reject('No se encontraron datos');
                    }
                });
            }

            /**
             * Prepara y muestra la información de la asignatura en el modal
             * @param {Object} asignaturaData - Datos de la asignatura
             * @param {string} tipo - origen o destino
             */
            function mostrarInformacionAsignatura(asignaturaData, tipo) {
                // Para asignaturas de destino, intentar enriquecer con datos desde la API
                if (tipo === 'destino' &&
                    (asignaturaData.id_asignatura || asignaturaData.id) &&
                    (!asignaturaData.nombre_programa || !asignaturaData.contenidos_programaticos)) {

                    console.log('Enriqueciendo datos de asignatura destino desde datos pre-cargados');

                    // Buscar en datos precargados primero
                    let asignaturaCompleta = null;
                    if (window._asignaturasDestino && window._asignaturasDestino.length > 0) {
                        asignaturaCompleta = window._asignaturasDestino.find(a =>
                            a.id_asignatura == (asignaturaData.id_asignatura || asignaturaData.id) ||
                            a.codigo_asignatura == asignaturaData.codigo_asignatura
                        );
                    }

                    if (asignaturaCompleta) {
                        // Combinar los datos
                        mostrarDatosAsignaturaEnModal({
                            ...asignaturaData,
                            ...asignaturaCompleta
                        }, tipo);
                    } else {
                        // Si no está en datos precargados, mostrar lo que tenemos
                        mostrarDatosAsignaturaEnModal(asignaturaData, tipo);
                    }
                } else {
                    // Para asignaturas de origen o si ya tenemos todos los datos para destino
                    mostrarDatosAsignaturaEnModal(asignaturaData, tipo);
                }
            }

            /**
             * Muestra los datos de la asignatura en el modal
             * @param {Object} datos - Datos formateados de la asignatura
             * @param {string} tipo - origen o destino
             */
            function mostrarDatosAsignaturaEnModal(asignaturaData, tipo) {
                // Normalizar los datos (prevenir undefined)
                const datos = {
                    nombre: asignaturaData.nombre || 'Sin nombre',
                    codigo: tipo === 'origen' ?
                        (asignaturaData.codigo || 'Sin código') : (asignaturaData.codigo_asignatura ||
                            asignaturaData.codigo || 'Sin código'),
                    semestre: asignaturaData.semestre || '—',
                    creditos: asignaturaData.creditos || '—',
                    programa: asignaturaData.nombre_programa || asignaturaData.programa || '—',
                    facultad: asignaturaData.facultad || '—',
                    institucion: asignaturaData.institucion || 'Universidad Autónoma',
                    nota: tipo === 'origen' ?
                        (asignaturaData.nota_origen || asignaturaData.nota || '—') : (asignaturaData
                            .nota_destino || asignaturaData.nota || '—'),
                    esSENA: (asignaturaData.institucion === "Servicio Nacional de Aprendizaje - SENA"),
                    horas_sena: asignaturaData.horas_sena || '—',
                    contenido_programatico: null
                };

                // Procesar contenido programático (ambas estructuras posibles)
                if (asignaturaData.contenido_programatico) {
                    datos.contenido_programatico = asignaturaData.contenido_programatico;
                } else if (asignaturaData.contenidos_programaticos && asignaturaData.contenidos_programaticos
                    .length > 0) {
                    datos.contenido_programatico = asignaturaData.contenidos_programaticos[0];
                }

                console.log('Datos normalizados para mostrar:', datos);

                // Llenar campos básicos del modal
                $('#infoNombre').text(datos.nombre);
                $('#infoCodigo').text(datos.codigo);
                $('#infoSemestre').text(datos.semestre);
                $('#infoPrograma').text(datos.programa);
                $('#infoFacultad').text(datos.facultad);
                $('#infoInstitucion').text(datos.institucion);

                // Manejar créditos/horas SENA
                if (datos.esSENA) {
                    $('#infoCreditos').text(datos.horas_sena !== '—' ? (datos.horas_sena + ' horas') : '—');
                    $('label[for="infoCreditos"]').text('Horas:');
                } else {
                    $('#infoCreditos').text(datos.creditos);
                    $('label[for="infoCreditos"]').text('Créditos:');
                }

                // Manejar la nota según el tipo de asignatura
                if (tipo === 'origen' || datos.nota !== '—') {
                    $('#infoNota').show();
                    $('#infoNotaValue').text(datos.nota);
                } else {
                    $('#infoNota').hide();
                }

                // Manejar el contenido programático
                if (datos.contenido_programatico) {
                    $('#infoContenidoProgramatico').show();

                    // Tema
                    const tema = datos.contenido_programatico.tema || '—';
                    $('#infoTema').text(tema);

                    // Resultados de aprendizaje
                    const resultados = datos.contenido_programatico.resultados_aprendizaje || '—';
                    if (resultados !== '—' && resultados.length > 200) {
                        $('#infoResultadosAprendizaje').text(resultados.substring(0, 200) + '...');
                        $('#verMasResultados').show().off('click').on('click', function() {
                            $('#textoCompletoContenido').text(resultados);
                            $('#modalTextoCompletoTitle').text('Resultados de Aprendizaje');
                            $('#modalTextoCompleto').modal('show');
                        });
                    } else {
                        $('#infoResultadosAprendizaje').text(resultados);
                        $('#verMasResultados').hide();
                    }

                    // Descripción
                    const descripcion = datos.contenido_programatico.descripcion || '—';
                    if (descripcion !== '—' && descripcion.length > 200) {
                        $('#infoDescripcion').text(descripcion.substring(0, 200) + '...');
                        $('#verMasDescripcion').show().off('click').on('click', function() {
                            $('#textoCompletoContenido').text(descripcion);
                            $('#modalTextoCompletoTitle').text('Descripción del Contenido');
                            $('#modalTextoCompleto').modal('show');
                        });
                    } else {
                        $('#infoDescripcion').text(descripcion);
                        $('#verMasDescripcion').hide();
                    }
                } else {
                    $('#infoContenidoProgramatico').hide();
                }

                // Actualizar título del modal según el tipo
                if (tipo === 'origen') {
                    $('#modalInfoAsignaturaTitle').html(
                        '<i class="fas fa-info-circle mr-1"></i> Información de Asignatura de Origen');
                } else {
                    $('#modalInfoAsignaturaTitle').html(
                        '<i class="fas fa-info-circle mr-1"></i> Información de Asignatura de Destino');
                }

                // Mostrar el modal
                $('#modalInfoAsignatura').modal('show');
            }

            // Función auxiliar para mostrar alertas
            function mostrarAlerta(mensaje, tipo = 'warning') {
                $('#alertModalMessage').html(`
            <div class="alert alert-${tipo}" role="alert">
                ${tipo === 'danger' ? '<i class="fas fa-exclamation-triangle mr-2"></i>' : '<i class="fas fa-info-circle mr-2"></i>'}
                ${mensaje}
            </div>
        `);
                $('#alertModal').modal('show');
            }

            // Hacer que los nombres de los cursos se vean clickables con cursor pointer
            $('body').on('mouseenter', '.text-primary, .text-success, .nombre-asignatura', function() {
                $(this).css('cursor', 'pointer');
                $(this).css('text-decoration', 'underline');
            }).on('mouseleave', '.text-primary, .text-success, .nombre-asignatura', function() {
                $(this).css('text-decoration', 'none');
            });
        });
    </script>
    <!-- Cargar el script principal de la aplicación -->
     <script src="{{ asset('js/procesohomologacion.js') }}"></script>
    <script>
        // Intenta recuperar datos de todas las fuentes posibles
        function recuperarDatosPersistentes() {
            console.log('Intentando recuperar datos persistentes...');

            // Paso 1: Verificar localStorage
            if (verificarLocalStorage()) {
                // Intentar recuperar última solicitud usada
                const ultimaSolicitudId = localStorage.getItem('ultimaSolicitudId');

                if (ultimaSolicitudId) {
                    console.log('Última solicitud encontrada:', ultimaSolicitudId);

                    // Si no tenemos solicitudId asignado, usar el último
                    if (!solicitudId) {
                        solicitudId = ultimaSolicitudId;
                    }

                    // Intentar cargar homologaciones
                    try {
                        const datosGuardados = localStorage.getItem(`homologaciones_${solicitudId}`);
                        if (datosGuardados) {
                            const datosParseados = JSON.parse(datosGuardados);

                            if (Array.isArray(datosParseados) && datosParseados.length > 0) {
                                console.log('Datos recuperados de localStorage:', datosParseados.length);
                                homologaciones = datosParseados;

                                // Intentar recuperar homologacionId también
                                homologacionId = localStorage.getItem(`homologacionId_${solicitudId}`) || null;

                                // Actualizar campos ocultos si es necesario
                                actualizarCamposOcultos();

                                // Renderizar datos recuperados
                                actualizarInterfazCompleta();
                                return true;
                            }
                        }
                    } catch (e) {
                        console.error('Error al recuperar datos de localStorage:', e);
                        // Limpiar datos potencialmente corruptos
                        limpiarLocalStorageCorrupto(solicitudId);
                    }
                }
            }

            // Paso 2: Verificar datos precargados (variables Blade)
            if (Array.isArray(window._homologacionesExistentes) && window._homologacionesExistentes.length > 0) {
                console.log('Usando datos precargados desde variables Blade');
                homologaciones = window._homologacionesExistentes;

                // Recuperar otros datos precargados si existen
                if (window._homologacionId) {
                    homologacionId = window._homologacionId;
                }

                if (window._solicitudId) {
                    solicitudId = window._solicitudId;
                }

                // Actualizar interfaz
                actualizarInterfazCompleta();
                return true;
            }

            console.log('No se encontraron datos persistentes');
            return false;
        }

        // Actualiza los campos ocultos en el DOM con los valores actuales
        function actualizarCamposOcultos() {
            if (solicitudId) {
                const inputSolicitudId = document.getElementById('solicitud_id');
                if (inputSolicitudId) {
                    inputSolicitudId.value = solicitudId;
                }
            }

            if (homologacionId) {
                const inputHomologacionId = document.getElementById('homologacion_id');
                if (inputHomologacionId) {
                    inputHomologacionId.value = homologacionId;
                }
            }
        }
        // Verificar la disponibilidad y buen funcionamiento del localStorage
        function verificarLocalStorage() {
            try {
                // Intenta guardar y leer un valor de prueba
                const testKey = 'test_storage_' + Date.now();
                localStorage.setItem(testKey, '1');
                const testValue = localStorage.getItem(testKey);
                localStorage.removeItem(testKey);

                // Verificar si el valor se guardó y recuperó correctamente
                if (testValue !== '1') {
                    console.error('localStorage disponible pero no funciona correctamente');
                    return false;
                }

                return true;
            } catch (e) {
                console.error('localStorage no está disponible:', e);
                mostrarAlerta(
                    'Advertencia: El almacenamiento local no está disponible. El guardado automático no funcionará.',
                    'warning');
                return false;
            }
        }

        // Función para limpiar localStorage potencialmente dañado
        function limpiarLocalStorageCorrupto(solicitudIdALimpiar) {
            try {
                if (solicitudIdALimpiar) {
                    localStorage.removeItem(`homologaciones_${solicitudIdALimpiar}`);
                    localStorage.removeItem(`homologacionId_${solicitudIdALimpiar}`);
                    console.log(`localStorage limpiado para solicitud ${solicitudIdALimpiar}`);
                }
            } catch (e) {
                console.error('Error al limpiar localStorage corrupto:', e);
            }
        }
    </script>

    <script>
        // Función para mostrar alertas si no está definida
        function mostrarAlerta(mensaje, tipo) {
            if (typeof window.mostrarAlerta !== 'function') {
                // Crear contenedor de alerta si no existe
                let alertContainer = document.getElementById('alert-container');
                if (!alertContainer) {
                    alertContainer = document.createElement('div');
                    alertContainer.id = 'alert-container';
                    alertContainer.style.position = 'fixed';
                    alertContainer.style.top = '20px';
                    alertContainer.style.right = '20px';
                    alertContainer.style.zIndex = '9999';
                    document.body.appendChild(alertContainer);
                }

                // Crear alerta
                const alertEl = document.createElement('div');
                alertEl.className = `alert alert-${tipo} alert-dismissible fade show`;
                alertEl.innerHTML = `
                ${mensaje}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;

                alertContainer.appendChild(alertEl);

                // Auto eliminar después de 5 segundos
                setTimeout(() => {
                    alertEl.classList.remove('show');
                    setTimeout(() => alertEl.remove(), 300);
                }, 5000);
            } else {
                window.mostrarAlerta(mensaje, tipo);
            }
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Inicializando página...");

            // Cargar firma si existe
            if (typeof cargarFirmaCoordinador === 'function') {
                console.log("Cargando firma del coordinador...");
                cargarFirmaCoordinador();
            } else {
                console.error("Función cargarFirmaCoordinador no encontrada");
            }

            // Inicializar bs-custom-file-input para mostrar el nombre del archivo seleccionado
            if (typeof bsCustomFileInput !== 'undefined') {
                console.log("Inicializando bootstrap file input...");
                bsCustomFileInput.init();
            }

            // Asegurarnos que los event listeners estén configurados
            const firmaInput = document.getElementById('firma');
            if (firmaInput) {
                console.log("Configurando input de firma...");
                // Asegurar que el evento onchange funcione
                firmaInput.addEventListener('change', function(event) {
                    console.log("Evento change en input firma");
                    if (typeof handleFirmaCoordinadorUpload === 'function') {
                        handleFirmaCoordinadorUpload(event);
                    } else {
                        console.error("Función handleFirmaCoordinadorUpload no encontrada");
                        mostrarAlerta("Error: No se pudo cargar el manejador de firma", "danger");
                    }
                });
            }

            // Evento para el botón de generar PDF
            const btnGenerarPDF = document.getElementById('btn-generar-pdf');
            if (btnGenerarPDF) {
                console.log("Configurando botón generar PDF...");

                // Remover cualquier event listener previo
                const nuevoBtn = btnGenerarPDF.cloneNode(true);
                btnGenerarPDF.parentNode.replaceChild(nuevoBtn, btnGenerarPDF);

                // Agregar nuevo event listener
                nuevoBtn.addEventListener('click', function() {
                    console.log("Botón generar PDF presionado");

                    // Verificar si tenemos la función generarPDF
                    if (typeof generarPDF === 'function') {
                        // Determinar qué versión generar
                        const esVistaVice = typeof esVistaVicerrector === 'function' ?
                            esVistaVicerrector() : false;

                        // Generar PDF
                        generarPDF(esVistaVice);
                    } else {
                        // Fallback básico si no está la función principal
                        console.log("Función generarPDF no encontrada, usando fallback");

                        // Mostrar modal
                        $('#pdf-preview-modal').modal('show');

                        // Simular carga
                        setTimeout(function() {
                            const previewContent = document.getElementById('pdf-preview-content');
                            if (previewContent) {
                                previewContent.innerHTML =
                                    '<div class="alert alert-warning text-center"><i class="fas fa-exclamation-circle mr-2"></i>La función de generación de PDF no está disponible. Por favor, contacte al administrador.</div>';
                            }
                        }, 1000);

                        mostrarAlerta("La función de generación de PDF no está disponible", "warning");
                    }
                });

                // Si la firma está cargada, habilitar el botón
                if (window.firmaCoordinadorData) {
                    nuevoBtn.disabled = false;
                    console.log("Botón generar PDF habilitado (firma encontrada)");
                } else {
                    console.log("Botón generar PDF deshabilitado (sin firma)");
                }
            }

            // Inicializar manejadores completos si está disponible la función
            if (typeof initSignatureHandlers === 'function') {
                console.log("Inicializando manejadores de firma...");
                initSignatureHandlers();
            }

            console.log("Inicialización completada.");
        });

        // Asegurar que la función para habilitar el botón exista
        if (typeof habilitarBotonGenerarPDF !== 'function') {
            window.habilitarBotonGenerarPDF = function() {
                const btnGenerarPDF = document.getElementById('btn-generar-pdf');
                if (btnGenerarPDF) {
                    btnGenerarPDF.disabled = false;
                    console.log("Botón de generar PDF habilitado");

                    // Añadir efecto visual
                    btnGenerarPDF.classList.add('btn-pulse');
                    setTimeout(() => {
                        btnGenerarPDF.classList.remove('btn-pulse');
                    }, 1000);
                }
            };
        }

        // Añadir clase CSS para el efecto si no existe
        if (!document.getElementById('btn-pulse-style')) {
            const style = document.createElement('style');
            style.id = 'btn-pulse-style';
            style.innerHTML = `
            .btn-pulse {
                animation: pulse 1s;
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
        `;
            document.head.appendChild(style);
        }
    </script>
@endsection

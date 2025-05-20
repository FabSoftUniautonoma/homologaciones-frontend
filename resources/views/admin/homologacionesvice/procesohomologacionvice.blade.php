@extends('admin.layouts.appvice')

@section('title', 'Proceso de Homologación Vicerrectoría')

@section('content')
    <div class="container">
        <!-- Mensajes de error si existen -->
        @if (isset($errors) && !empty($errors))
            <div class="alert alert-danger shadow-sm rounded">
                <ul class="mb-0">
                    @foreach ($errors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Encabezado -->
        <div class="header card shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="header-content">
                    <h1 class="mb-1"><i class="fas fa-file-contract text-primary"></i> Proceso de Homologación</h1>
                    <h5 class="text-muted">Radicado: {{ $datos['numero_radicado'] ?? 'N/A' }}</h5>
                </div>
                <span
                    class="header-badge badge badge-pill status-{{ strtolower(str_replace(' ', '-', $datos['estado_solicitud'] ?? 'pendiente')) }} px-3 py-2">
                    <i class="fas fa-flag mr-1"></i> {{ $datos['estado_solicitud'] ?? 'Pendiente' }}
                </span>
            </div>
        </div>

        <!-- Información del estudiante -->
        <div class="section card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h3 class="section-title mb-0"><i class="fas fa-user-graduate text-primary"></i> Información del Estudiante
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row mb-3">
                            <span class="info-label font-weight-bold"><i class="fas fa-user text-secondary mr-2"></i>
                                Nombre:</span>
                            <span>{{ $datos['estudiante'] ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row mb-3">
                            <span class="info-label font-weight-bold"><i class="fas fa-id-card text-secondary mr-2"></i>
                                Identificación:</span>
                            <span>{{ $datos['numero_identificacion'] ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row mb-3">
                            <span class="info-label font-weight-bold"><i
                                    class="fas fa-calendar-alt text-secondary mr-2"></i> Fecha de Solicitud:</span>
                            <span>{{ \Carbon\Carbon::parse($datos['fecha'] ?? now())->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row mb-3">
                            <span class="info-label font-weight-bold"><i class="fas fa-university text-secondary mr-2"></i>
                                Universidad de Origen:</span>
                            <span>{{ $datos['universidad_origen'] ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row mb-3">
                            <span class="info-label font-weight-bold"><i
                                    class="fas fa-graduation-cap text-secondary mr-2"></i> Programa Destino:</span>
                            <span>{{ $datos['programa_destino'] ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row mb-3">
                            <span class="info-label font-weight-bold"><i class="fas fa-id-badge text-secondary mr-2"></i> ID
                                Homologación:</span>
                            <span id="id-homologacion">{{ $datos['id_homologacion'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Homologaciones -->
        <div class="section card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h3 class="section-title mb-0"><i class="fas fa-exchange-alt text-primary"></i> Asignaturas a Homologar</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr class="table-header-group">
                                <th colspan="3" class="text-center border-right">ASIGNATURA ORIGEN</th>
                                <th colspan="3" class="text-center border-right">ASIGNATURA DESTINO</th>
                                <th rowspan="2" class="align-middle text-center">ACCIONES</th>
                            </tr>
                            <tr class="bg-light">
                                <th>Nombre</th>
                                <th>Créditos</th>
                                <th class="border-right">Nota</th>
                                <th>Nombre</th>
                                <th>Créditos</th>
                                <th class="border-right">Nota Propuesta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $asignaturasOrigenFiltradas = array_filter(
                                    $datos['asignaturas_origen'] ?? [],
                                    function ($item) {
                                        return isset($item['id']) && !is_null($item['id']);
                                    },
                                );

                                $asignaturasDestinoFiltradas = array_filter(
                                    $datos['asignaturas_destino'] ?? [],
                                    function ($item) {
                                        return isset($item['id']) && !is_null($item['id']);
                                    },
                                );

                                // Calcular el total de créditos
                                $totalCreditos = 0;
                                if (!empty($asignaturasDestinoFiltradas)) {
                                    foreach ($asignaturasDestinoFiltradas as $asignatura) {
                                        $totalCreditos += isset($asignatura['creditos'])
                                            ? (int) $asignatura['creditos']
                                            : 0;
                                    }
                                }
                            @endphp

                            @if (!empty($asignaturasOrigenFiltradas))
                                @foreach ($asignaturasOrigenFiltradas as $index => $asignaturaOrigen)
                                    <tr data-asignatura-origen='@json($asignaturaOrigen)'>
                                        <td>
                                            <strong
                                                class="asignatura-nombre-link">{{ $asignaturaOrigen['nombre'] ?? 'N/A' }}</strong>
                                            <div class="text-muted small">{{ $asignaturaOrigen['codigo'] ?? 'Sin código' }}
                                            </div>
                                        </td>
                                        <td>{{ $asignaturaOrigen['creditos'] ?? 'N/A' }}</td>
                                        <td class="border-right">{{ $asignaturaOrigen['nota_origen'] ?? 'N/A' }}</td>

                                        @php
                                            $asignaturaDestino = null;

                                            // Buscar en correspondencias existentes
                                            if (!empty($homologacionesExistentes)) {
                                                foreach ($homologacionesExistentes as $homologacion) {
                                                    if (
                                                        isset($homologacion['asignatura_origen_id']) &&
                                                        $homologacion['asignatura_origen_id'] == $asignaturaOrigen['id']
                                                    ) {
                                                        foreach ($asignaturasDestinoFiltradas as $destino) {
                                                            if (
                                                                isset($destino['id']) &&
                                                                $destino['id'] == $homologacion['asignatura_destino_id']
                                                            ) {
                                                                $asignaturaDestino = $destino;
                                                                break;
                                                            }
                                                        }
                                                        break;
                                                    }
                                                }
                                            }

                                            // Si no se encontró en correspondencias, asignar por índice si está disponible
                                            if (
                                                is_null($asignaturaDestino) &&
                                                isset($asignaturasDestinoFiltradas[$index])
                                            ) {
                                                $asignaturaDestino = $asignaturasDestinoFiltradas[$index];
                                            }

                                            // Estado por defecto
                                            $estado = 'pendiente';

                                            // Buscar estado en correspondencias
                                            if (!empty($homologacionesExistentes)) {
                                                foreach ($homologacionesExistentes as $homologacion) {
                                                    if (
                                                        isset($homologacion['asignatura_origen_id']) &&
                                                        $homologacion['asignatura_origen_id'] == $asignaturaOrigen['id']
                                                    ) {
                                                        $estado = $homologacion['estado'] ?? 'pendiente';
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if ($asignaturaDestino)
                                            <td data-asignatura-destino='@json($asignaturaDestino)'>
                                                <strong
                                                    class="asignatura-nombre-link">{{ $asignaturaDestino['nombre'] ?? 'N/A' }}</strong>
                                                <div class="text-muted small">
                                                    {{ $asignaturaDestino['codigo'] ?? 'Sin código' }}</div>
                                            </td>
                                            <td>{{ $asignaturaDestino['creditos'] ?? 'N/A' }}</td>
                                            <td class="border-right">{{ $asignaturaDestino['nota_destino'] ?? '3.0' }}</td>
                                        @else
                                            <td colspan="3" class="text-center text-muted border-right">
                                                <i class="fas fa-exclamation-circle"></i> No asignada
                                            </td>
                                        @endif


                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-info view-subject"
                                                    data-index="{{ $index }}">
                                                    <i class="fas fa-eye" title="Ver detalles"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i> No hay asignaturas de
                                        origen para homologar
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-right"><strong>Total de asignaturas:</strong></td>
                                <td>{{ count($asignaturasOrigenFiltradas) }}</td>
                                <td colspan="2" class="text-right"><strong>Total de créditos:</strong></td>
                                <td id="total-creditos">{{ $totalCreditos }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formulario para decisión final -->
        <form id="formDecision"
            action="{{ url('admin/homologaciones-vice/' . ($datos['id_homologacion'] ?? 0) . '/actualizar-estado') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="section card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="section-title mb-0"><i class="fas fa-clipboard-check text-primary"></i> Decisión Final</h3>
                </div>
                <div class="card-body">
                    <!-- Contenedor para alertas -->
                    <div id="alertas-container" class="mb-3"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row align-items-end">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="estado" class="font-weight-bold">
                                            <i class="fas fa-flag text-secondary mr-2"></i> Estado de la Homologación:
                                        </label>
                                        <select name="estado" id="estado" class="form-control">
                                            <option value="Aprobado"
                                                {{ ($datos['estado_solicitud'] ?? '') == 'Aprobado' ? 'selected' : '' }}>
                                                Aprobado</option>
                                            <option value="Rechazado"
                                                {{ ($datos['estado_solicitud'] ?? '') == 'Rechazado' ? 'selected' : '' }}>
                                                Rechazado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary mt-4 w-100" id="btnguardarestado">
                                        <i class="fas fa-check-circle mr-1"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comentarios" class="font-weight-bold"><i
                                class="fas fa-comment-alt text-secondary mr-2"></i>
                            Comentarios/Observaciones:</label>
                        <textarea name="comentarios" id="comentarios" class="form-control" rows="4">{{ $datos['comentarios'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>


     <div class="row">
            <div class="col-12">
                <!-- Estado de las firmas -->
                <div class="card mb-4 shadow">
                    <div class="card-header py-3 text-white bg-primary">
                        <h5 class="m-0 font-weight-bold">
                            <i class="fas fa-info-circle mr-2"></i>Estado del Proceso
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Estado firma coordinador -->
                        <div id="firma-coordinador-status" class="alert alert-warning">
                            <span id="firma-coordinador-mensaje">
                                <i class="fas fa-spinner fa-spin mr-1"></i>
                                Verificando firma del coordinador...
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Firma del Coordinador (Solo visualización) -->
                <div class="card mb-4 border-left-warning shadow">
                    <div class="card-header py-3 text-white" style="background-color: #0277bd;">
                        <h4 class="m-0 font-weight-bold">
                            <i class="fas fa-signature mr-2"></i>Firma del Coordinador
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="firma-coordinador-preview-vice"
                                    class="border rounded p-3 text-center d-flex align-items-center justify-content-center"
                                    style="height: 150px; background-color: #e1f5fe; border-color: #6c8ebf;">
                                    <p style="color: #19407b;" class="mb-0" id="firma-coordinador-placeholder-vice">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Cargando firma del coordinador...
                                    </p>
                                    <img id="img-firma-coordinador" style="display: none; max-height: 140px; max-width: 100%;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de firma vicerrector -->
                <div id="seccion-firma-vicerrector-contenido" style="display: none;">
                    <div class="card mb-4 border-left-warning shadow">
                        <div class="card-header py-3 text-white" style="background-color: #0277bd;">
                            <h4 class="m-0 font-weight-bold">
                                <i class="fas fa-signature mr-2"></i>Firma del Vicerrector
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firma-vicerrector" class="font-weight-bold" style="color: #19407b;">
                                            <i class="fas fa-file-upload mr-1"></i> Subir Firma:
                                        </label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="firma-vicerrector"
                                                accept="image/*">
                                            <label class="custom-file-label" for="firma-vicerrector" style="color: #0277bd;">
                                                Seleccionar archivo...
                                            </label>
                                        </div>
                                        <small class="form-text" style="color: #6c8ebf;">
                                            Formatos aceptados: JPG, PNG, GIF
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="firma-vicerrector-preview"
                                        class="border rounded p-3 text-center d-flex align-items-center justify-content-center"
                                        style="height: 150px; background-color: #e1f5fe; border-color: #6c8ebf;">
                                        <p style="color: #19407b;" class="mb-0" id="firma-vicerrector-placeholder">
                                            Vista previa de la firma
                                        </p>
                                        <img id="img-firma-vicerrector" style="display: none; max-height: 140px; max-width: 100%;" />
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="firma_vicerrector_data" name="firma_vicerrector_data">
                            <input type="hidden" id="firma_coordinador_data" name="firma_coordinador_data">

                            <div class="mt-3 text-center">
                                <button type="button" id="btn-generar-pdf" class="btn btn-lg"
                                    style="background-color: #0277bd; color: white;" disabled>
                                    <i class="fas fa-file-pdf mr-2"></i> Generar PDF Final
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vista Previa PDF Final -->
    <div class="modal fade" id="pdf-preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0277bd; color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf mr-2"></i>Vista Previa del PDF Final
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="pdf-preview-content" style="background-color: #f8f9fc;">
                    <div class="text-center p-5 bg-light" style="border-radius: 5px; border: 1px dashed #6c8ebf;">
                        <i class="fas fa-file-pdf fa-3x mb-3" style="color: #19407b;"></i>
                        <h5 style="color: #19407b;">Visualización del documento final</h5>
                        <p style="color: #0277bd;">El documento se está generando...</p>

                        <!-- Aquí mostramos las firmas -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 style="color: #19407b;">Firma del Coordinador</h6>
                                <div class="border p-2">
                                    <img id="pdf-firma-coordinador" style="max-height: 100px;" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 style="color: #19407b;">Firma del Vicerrector</h6>
                                <div class="border p-2">
                                    <img id="pdf-firma-vicerrector" style="max-height: 100px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #e6f0ff;">
                    <button type="button" class="btn" data-dismiss="modal"
                        style="background-color: #6c8ebf; color: white;">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn" id="btn-descargar-pdf"
                        style="background-color: #0277bd; color: white;">
                        <i class="fas fa-download mr-1"></i> Descargar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>



        <!-- Modal para detalles de asignatura -->
        <div class="modal fade" id="subjectModal" tabindex="-1" role="dialog" aria-labelledby="subjectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="subjectModalLabel"><i class="fas fa-book text-primary mr-2"></i>
                            Detalles
                            de Asignatura</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3 origen-card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Asignatura de Origen</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Nombre:</span>
                                            <span id="origen-nombre"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Código:</span>
                                            <span id="origen-codigo"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Semestre:</span>
                                            <span id="origen-semestre"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Créditos:</span>
                                            <span id="origen-creditos"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Nota:</span>
                                            <span id="origen-nota"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Programa:</span>
                                            <span id="origen-programa"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-3 destino-card h-100">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Asignatura de Destino</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Nombre:</span>
                                            <span id="destino-nombre"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Código:</span>
                                            <span id="destino-codigo"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Semestre:</span>
                                            <span id="destino-semestre"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Créditos:</span>
                                            <span id="destino-creditos"></span>
                                        </div>
                                        <div class="info-row mb-2">
                                            <span class="info-label font-weight-bold">Nota Propuesta:</span>
                                            <span id="destino-nota"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-bookmark text-primary mr-2"></i> Contenido
                                    Programático
                                </h5>
                            </div>
                            <div class="card-body" id="contenido-programatico">
                                <div class="info-row mb-2">
                                    <span class="info-label font-weight-bold">Tema:</span>
                                    <span id="cp-tema"></span>
                                </div>
                                <div class="info-row mb-2">
                                    <span class="info-label font-weight-bold">Resultados de Aprendizaje:</span>
                                    <span id="cp-resultados"></span>
                                </div>
                                <div class="info-row mb-2">
                                    <span class="info-label font-weight-bold">Descripción:</span>
                                    <span id="cp-descripcion"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="modalClose">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar homologación -->
        <div class="modal fade" id="editHomologacionModal" tabindex="-1" role="dialog"
            aria-labelledby="editHomologacionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editHomologacionModalLabel"><i
                                class="fas fa-edit text-primary mr-2"></i>
                            Editar Homologación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-edit-homologacion">
                            <input type="hidden" id="edit-index">

                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-university text-secondary mr-2"></i>
                                    Asignatura Origen:</label>
                                <input type="text" id="edit-origen-nombre" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-graduation-cap text-secondary mr-2"></i>
                                    Asignatura Destino:</label>
                                <input type="text" id="edit-destino-nombre" class="form-control" readonly>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold"><i
                                            class="fas fa-star-half-alt text-secondary mr-2"></i>
                                        Nota Origen:</label>
                                    <input type="number" id="edit-nota-origen" class="form-control" step="0.1"
                                        min="0" max="5" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold"><i class="fas fa-star text-secondary mr-2"></i>
                                        Nota
                                        Homologada:</label>
                                    <input type="number" id="edit-nota-homologada" class="form-control" step="0.1"
                                        min="0" max="5" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="font-weight-bold"><i
                                            class="fas fa-check-circle text-secondary mr-2"></i>
                                        Estado:</label>
                                    <select id="edit-estado" class="form-control">
                                        <option value="pendiente">Pendiente</option>
                                        <option value="aprobado">Aprobado</option>
                                        <option value="rechazado">Rechazado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-comment text-secondary mr-2"></i>
                                    Observaciones:</label>
                                <textarea id="edit-observaciones" class="form-control" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" id="btnCancelEditHomologacion"
                            data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSaveEditHomologacion">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @section('scripts')
        <!-- Incluir jsPDF para generación de PDFs -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
    <script src="{{ asset(path: 'js/proceso.js') }}"></script>

        <!-- JS para cargar firmas -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log("Inicializando vista de vicerrector...");

                // Verificar firma del coordinador
                const firmaCoordExiste = cargarFirmaCoordinador();
                actualizarEstadoFirmaCoordinador(firmaCoordExiste);

                // Configurar input de firma vicerrector
                const firmaVicerrectorInput = document.getElementById('firma-vicerrector');
                if (firmaVicerrectorInput) {
                    console.log("Configurando input de firma vicerrector");

                    // Remover event listeners anteriores y agregar nuevo
                    const nuevoInput = firmaVicerrectorInput.cloneNode(true);
                    firmaVicerrectorInput.parentNode.replaceChild(nuevoInput, firmaVicerrectorInput);

                    nuevoInput.addEventListener('change', function(event) {
                        console.log("Cambio en input de firma vicerrector");
                        if (typeof handleFirmaVicerrectorUpload === 'function') {
                            handleFirmaVicerrectorUpload(event);
                        } else {
                            console.error("Función handleFirmaVicerrectorUpload no disponible");
                            mostrarAlerta("Error: No se pudo cargar el manejador de firma", "danger");
                        }
                    });

                    // Inicializar bootstrap file input si está disponible
                    if (typeof bsCustomFileInput !== 'undefined') {
                        bsCustomFileInput.init();
                    }
                }

                // Cargar firma del vicerrector si ya existe
                if (typeof cargarFirmaVicerrector === 'function') {
                    cargarFirmaVicerrector();
                }

                // Configurar botón de generar PDF
                const btnGenerarPDF = document.getElementById('btn-generar-pdf');
                if (btnGenerarPDF) {
                    console.log("Configurando botón de generar PDF en vista vicerrector");

                    // Remover event listeners anteriores
                    const nuevoBtn = btnGenerarPDF.cloneNode(true);
                    btnGenerarPDF.parentNode.replaceChild(nuevoBtn, btnGenerarPDF);

                    // Agregar nuevo event listener
                    nuevoBtn.addEventListener('click', function() {
                        console.log("Botón generar PDF presionado en vista vicerrector");
                        if (typeof generarPDF === 'function') {
                            // Llamar a la función con parámetro true (vista vicerrector)
                            generarPDF(true);
                        } else {
                            console.error("Función generarPDF no disponible");
                            mostrarAlerta("Error: No se pudo generar el PDF", "danger");

                            // Fallback simple - mostrar modal
                            if (typeof $ !== 'undefined' && $('#pdf-preview-modal').length) {
                                $('#pdf-preview-modal').modal('show');
                            }
                        }
                    });

                    // Habilitar botón si ambas firmas están disponibles
                    if (window.firmaCoordinadorData && window.firmaVicerrectorData) {
                        nuevoBtn.disabled = false;
                    }
                }

                // Verificar función para mostrar alertas
                if (typeof mostrarAlerta !== 'function') {
                    window.mostrarAlerta = function(mensaje, tipo) {
                        const alertContainer = document.getElementById('alert-container') ||
                            document.createElement('div');

                        if (!document.getElementById('alert-container')) {
                            alertContainer.id = 'alert-container';
                            alertContainer.style.position = 'fixed';
                            alertContainer.style.top = '20px';
                            alertContainer.style.right = '20px';
                            alertContainer.style.zIndex = '9999';
                            document.body.appendChild(alertContainer);
                        }

                        const alertEl = document.createElement('div');
                        alertEl.className = `alert alert-${tipo} alert-dismissible fade show`;
                        alertEl.innerHTML = `
                   ${mensaje}
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               `;

                        alertContainer.appendChild(alertEl);

                        setTimeout(() => {
                            alertEl.classList.remove('show');
                            setTimeout(() => alertEl.remove(), 300);
                        }, 5000);
                    };
                }

                // Configurar botón guardar estado
                const btnGuardarEstado = document.getElementById('btnguardarestado');
                if (btnGuardarEstado) {
                    btnGuardarEstado.addEventListener('click', function() {
                        const estado = document.getElementById('estado').value;
                        // Aquí podrías implementar la lógica para guardar solo el estado
                        // Por ahora, mostramos una alerta de confirmación
                        mostrarAlerta(`Estado de homologación actualizado a: ${estado}`, 'success');
                    });
                }

                // Manejar el clic en el nombre de la asignatura
                document.querySelectorAll('.asignatura-nombre-link').forEach(link => {
                    link.addEventListener('click', function() {
                        const row = this.closest('tr');
                        const asignaturaOrigenData = row.getAttribute('data-asignatura-origen');
                        const asignaturaDestinoData = row.querySelector('td[data-asignatura-destino]')
                            ?.getAttribute('data-asignatura-destino');

                        if (asignaturaOrigenData) {
                            mostrarDetallesAsignatura(asignaturaOrigenData, asignaturaDestinoData);
                        }
                    });
                });

                // Manejar el clic en el botón de ver detalles
                document.querySelectorAll('.view-subject').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const row = this.closest('tr');
                        const asignaturaOrigenData = row.getAttribute('data-asignatura-origen');
                        const asignaturaDestinoData = row.querySelector('td[data-asignatura-destino]')
                            ?.getAttribute('data-asignatura-destino');

                        if (asignaturaOrigenData) {
                            mostrarDetallesAsignatura(asignaturaOrigenData, asignaturaDestinoData);
                        }
                    });
                });

                // Función para mostrar detalles de asignatura en el modal
                function mostrarDetallesAsignatura(origenData, destinoData) {
                    try {
                        const asignaturaOrigen = JSON.parse(origenData);

                        // Llenar datos de origen
                        document.getElementById('origen-nombre').textContent = asignaturaOrigen.nombre || 'N/A';
                        document.getElementById('origen-codigo').textContent = asignaturaOrigen.codigo || 'N/A';
                        document.getElementById('origen-semestre').textContent = asignaturaOrigen.semestre || 'N/A';
                        document.getElementById('origen-creditos').textContent = asignaturaOrigen.creditos || 'N/A';
                        document.getElementById('origen-nota').textContent = asignaturaOrigen.nota_origen ||
                            asignaturaOrigen.nota || 'N/A';
                        document.getElementById('origen-programa').textContent = asignaturaOrigen.programa || 'N/A';

                        // Llenar datos de destino si existen
                        if (destinoData) {
                            const asignaturaDestino = JSON.parse(destinoData);
                            document.getElementById('destino-nombre').textContent = asignaturaDestino.nombre || 'N/A';
                            document.getElementById('destino-codigo').textContent = asignaturaDestino.codigo || 'N/A';
                            document.getElementById('destino-semestre').textContent = asignaturaDestino.semestre ||
                                'N/A';
                            document.getElementById('destino-creditos').textContent = asignaturaDestino.creditos ||
                                'N/A';
                            document.getElementById('destino-nota').textContent = asignaturaDestino.nota_destino ||
                                '3.0';
                        } else {
                            document.getElementById('destino-nombre').textContent = 'No asignado';
                            document.getElementById('destino-codigo').textContent = 'N/A';
                            document.getElementById('destino-semestre').textContent = 'N/A';
                            document.getElementById('destino-creditos').textContent = 'N/A';
                            document.getElementById('destino-nota').textContent = 'N/A';
                        }

                        // Llenar contenido programático si existe
                        if (asignaturaOrigen.contenido_programatico || asignaturaOrigen.contenidos_programaticos) {
                            const contenido = asignaturaOrigen.contenido_programatico ||
                                (asignaturaOrigen.contenidos_programaticos && asignaturaOrigen.contenidos_programaticos
                                    .length > 0 ?
                                    asignaturaOrigen.contenidos_programaticos[0] : null);

                            if (contenido) {
                                document.getElementById('cp-tema').textContent = contenido.tema || 'N/A';
                                document.getElementById('cp-resultados').textContent = contenido
                                    .resultados_aprendizaje || 'N/A';
                                document.getElementById('cp-descripcion').textContent = contenido.descripcion || 'N/A';
                                document.getElementById('contenido-programatico').style.display = 'block';
                            } else {
                                document.getElementById('contenido-programatico').style.display = 'none';
                            }
                        } else {
                            document.getElementById('contenido-programatico').style.display = 'none';
                        }

                        // Mostrar el modal
                        $('#subjectModal').modal('show');

                    } catch (error) {
                        console.error('Error al mostrar detalles de asignatura:', error);
                        mostrarAlerta('Error al mostrar detalles de la asignatura', 'danger');
                    }
                }
            });

            // Definición de la función descargaPDFFinal si no existe
            if (typeof descargaPDFFinal !== 'function') {
                window.descargaPDFFinal = function() {
                    try {
                        $('#pdf-preview-modal').modal('hide');
                        mostrarAlerta('Resolución final generada y descargada correctamente', 'success');
                    } catch (error) {
                        console.error('Error al descargar PDF final:', error);
                        mostrarAlerta('Error al descargar la resolución final', 'danger');
                    }
                };
            }
        </script>

    <script src="{{ asset('js/procesohomologacion.js') }}"></script>

    <script>
        // Código específico para la vista del vicerrector
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar el evento change para el input de firma del vicerrector
            const firmaVicerrectorInput = document.getElementById('firma-vicerrector');
            if (firmaVicerrectorInput) {
                firmaVicerrectorInput.addEventListener('change', handleFirmaVicerrectorUpload);
            }

            // Configurar el botón de generar PDF para abrir el modal
            const btnGenerarPDF = document.getElementById('btn-generar-pdf');
            if (btnGenerarPDF) {
                btnGenerarPDF.addEventListener('click', function() {
                    // Actualizar vista previa en el modal
                    const pdfFirmaCoordinador = document.getElementById('pdf-firma-coordinador');
                    const pdfFirmaVicerrector = document.getElementById('pdf-firma-vicerrector');

                    if (pdfFirmaCoordinador && window.firmaCoordinadorData) {
                        pdfFirmaCoordinador.src = window.firmaCoordinadorData;
                    }

                    if (pdfFirmaVicerrector && window.firmaVicerrectorData) {
                        pdfFirmaVicerrector.src = window.firmaVicerrectorData;
                    }

                    // Mostrar el modal
                    $('#pdf-preview-modal').modal('show');
                });
            }

            // Configurar botón de descargar PDF
            const btnDescargarPDF = document.getElementById('btn-descargar-pdf');
            if (btnDescargarPDF) {
                btnDescargarPDF.addEventListener('click', function() {
                    // Aquí iría la lógica para generar y descargar el PDF
                    // Puedes usar una librería como jsPDF, html2pdf, etc.
                    mostrarAlerta('Descargando PDF...', 'success');
                    // Cerrar el modal
                    $('#pdf-preview-modal').modal('hide');
                });
            }

            // Intentar cargar la firma del coordinador al inicio
            const firmaCoordinadorExiste = cargarFirmaCoordinador();
            if (firmaCoordinadorExiste) {
                // Habilitar la sección de firma del vicerrector
                const seccionVicerrector = document.getElementById('seccion-firma-vicerrector-contenido');
                if (seccionVicerrector) {
                    seccionVicerrector.style.display = 'block';
                }
            }

            // Intentar cargar la firma del vicerrector si ya existe
            cargarFirmaVicerrector();
        });
    </script>
  <script>
// Script corregido para actualizar el estado de homologación de asignaturas
(function() {
    // Ejecutar inmediatamente
    console.clear();
    console.log('Iniciando script corregido para actualizar estado');

    // Elementos DOM - Buscar con más robustez
    const btnGuardar = document.getElementById('btnguardarestado') || document.querySelector('button[data-action="guardar-estado"]');
    const estadoSelect = document.getElementById('estado') || document.querySelector('select[name="estado"]');
    const alertasContainer = document.getElementById('alertas-container') || document.querySelector('.alertas-container') ||
                            document.querySelector('.container').appendChild(document.createElement('div'));

    // Verificación de elementos críticos
    if (!btnGuardar) {
        console.error('Botón de guardar no encontrado');
        return;
    }

    if (!estadoSelect) {
        console.error('Selector de estado no encontrado');
        return;
    }

    // Asegurarse de que el contenedor de alertas exista
    if (!alertasContainer) {
        const container = document.querySelector('.container, .content, main');
        if (container) {
            alertasContainer = document.createElement('div');
            alertasContainer.id = 'alertas-container';
            alertasContainer.className = 'alertas-container my-3';
            container.insertBefore(alertasContainer, container.firstChild);
        } else {
            console.error('No se pudo crear el contenedor de alertas');
            return;
        }
    }

    // ID de homologación - búsqueda más flexible
    let idHomologacion = null;
    const idHomologacionEl = document.getElementById('id-homologacion') ||
                           document.querySelector('[data-id-homologacion]') ||
                           document.querySelector('.homologacion-id');

    if (idHomologacionEl) {
        idHomologacion = idHomologacionEl.textContent.trim() || idHomologacionEl.getAttribute('data-id-homologacion');
    } else {
        // Intentar extraer de la URL
        const urlMatch = window.location.pathname.match(/homologacion(?:es)?[/-](\d+)/i);
        if (urlMatch && urlMatch[1]) {
            idHomologacion = urlMatch[1];
        }
    }

    if (!idHomologacion) {
        console.error('No se pudo determinar el ID de homologación');
        showAlert('No se pudo determinar el ID de homologación', 'danger');
        return;
    }

    console.log('ID de homologación detectado:', idHomologacion);

    // Eliminar cualquier eventListener previo para evitar duplicados
    btnGuardar.removeEventListener('click', handleGuardarClick);

    // Asignar evento al botón de guardar
    btnGuardar.addEventListener('click', handleGuardarClick);

    // Función principal para manejar el click
    function handleGuardarClick(event) {
        event.preventDefault();

        // Evitar múltiples clics
        btnGuardar.disabled = true;

        // Obtener nuevo estado
        const nuevoEstado = estadoSelect.value;
        console.log('Nuevo estado:', nuevoEstado);

        // Confirmar cambio
        if (!confirm(`¿Confirma que desea cambiar el estado a "${nuevoEstado}"?`)) {
            btnGuardar.disabled = false;
            return false;
        }

        // Mostrar alerta de procesamiento
        showAlert('Procesando cambio de estado...', 'info');

        // Obtener asignaturas de la tabla
        const rows = document.querySelectorAll('tr[data-asignatura-origen]');
        console.log(`Filas de asignaturas encontradas: ${rows.length}`);

        if (rows.length === 0) {
            showAlert('No se encontraron asignaturas para actualizar', 'danger');
            btnGuardar.disabled = false;
            return false;
        }

        // Extraer IDs y crear array de homologaciones
        const homologaciones = [];

        rows.forEach(function(row) {
            try {
                // Extraer datos de asignatura origen
                const asignaturaOrigenStr = row.getAttribute('data-asignatura-origen');
                const asignaturaOrigen = JSON.parse(asignaturaOrigenStr);

                // Verificar si tiene ID válido
                if (!asignaturaOrigen || !asignaturaOrigen.id) {
                    console.warn('Asignatura sin ID válido:', asignaturaOrigen);
                    return;
                }

                // Buscar celda de asignatura destino
                const celdaDestino = row.querySelector('td[data-asignatura-destino]');
                let asignaturaDestinoId = null;
                let notaDestino = '3.0';

                // Extraer datos de destino si existen
                if (celdaDestino) {
                    const asignaturaDestinoStr = celdaDestino.getAttribute('data-asignatura-destino');
                    if (asignaturaDestinoStr) {
                        const asignaturaDestino = JSON.parse(asignaturaDestinoStr);
                        if (asignaturaDestino && asignaturaDestino.id) {
                            asignaturaDestinoId = asignaturaDestino.id;
                        }
                        if (asignaturaDestino && asignaturaDestino.nota_destino) {
                            notaDestino = asignaturaDestino.nota_destino;
                        }
                    }
                }

                // Crear objeto de homologación
                homologaciones.push({
                    asignatura_origen_id: asignaturaOrigen.id,
                    asignatura_destino_id: asignaturaDestinoId,
                    nota_destino: notaDestino,
                    comentarios: ""
                });

                console.log(`Añadida homologación para: ${asignaturaOrigen.nombre} (ID: ${asignaturaOrigen.id})`);
            } catch (error) {
                console.error('Error procesando fila:', error);
            }
        });

        // Verificar si tenemos homologaciones
        if (homologaciones.length === 0) {
            showAlert('No se pudieron procesar las asignaturas', 'danger');
            btnGuardar.disabled = false;
            return false;
        }

        console.log(`Homologaciones preparadas: ${homologaciones.length}`);

        // Obtener comentarios
        const comentariosEl = document.getElementById('comentarios');
        const comentarios = comentariosEl ? comentariosEl.value : "";

        // Token CSRF
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = metaToken ? metaToken.getAttribute('content') : '';

        if (!csrfToken) {
            console.warn('No se encontró token CSRF, intentando alternativas');
        }

        // Probar diferentes formatos de endpoints
        const endpoints = [
            `/api/homologaciones/${idHomologacion}/estado`,
            `/api/homologacion/${idHomologacion}/actualizar-estado`,
            `/api/homologacion-asignaturas/${idHomologacion}/estado`,
            `/homologacion-asignaturas/${idHomologacion}/actualizar`
        ];

        // Datos para la solicitud
        const requestData = {
            id: idHomologacion,
            homologacion_id: idHomologacion,
            estado: nuevoEstado,
            comentarios: comentarios,
            homologaciones: homologaciones
        };

        console.log('Datos preparados para enviar:', requestData);

        // Probar el primer endpoint
        intentarEnviar(0);

        // Función para intentar enviar a diferentes endpoints
        function intentarEnviar(indice) {
            if (indice >= endpoints.length) {
                // Todos los endpoints fallaron
                showAlert('No se pudo actualizar el estado. Por favor, contacte al administrador.', 'danger');
                btnGuardar.disabled = false;
                return;
            }

            const apiUrl = endpoints[indice];
            console.log(`Intentando endpoint ${indice+1}/${endpoints.length}: ${apiUrl}`);

            // Primero intentar con método PUT
            enviarSolicitud('PUT', apiUrl, csrfToken, requestData, nuevoEstado)
                .catch(error => {
                    console.log(`Endpoint ${indice+1} falló con PUT:`, error);

                    // Intentar con POST si PUT falla
                    return enviarSolicitud('POST', apiUrl, csrfToken, {
                        ...requestData,
                        _method: 'PUT' // Para simular PUT en formularios
                    }, nuevoEstado);
                })
                .catch(error => {
                    console.log(`Endpoint ${indice+1} falló con POST:`, error);

                    // Probar el siguiente endpoint
                    intentarEnviar(indice + 1);
                });
        }
    }

    // Función para enviar solicitud con mejor manejo de promesas
    function enviarSolicitud(metodo, url, token, datos, nuevoEstado) {
        return new Promise((resolve, reject) => {
            console.log(`Enviando ${metodo} a ${url}`);

            fetch(url, {
                method: metodo,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datos)
            })
            .then(response => {
                console.log(`Respuesta de ${url}:`, response.status);

                // Manejar diferentes códigos de estado
                if (response.status === 204 || response.status === 200) {
                    // Respuesta exitosa
                    return { success: true, mensaje: 'Estado actualizado correctamente' };
                }

                if (response.status === 405 || response.status === 404) {
                    // Método no permitido o recurso no encontrado
                    reject(new Error(`Error ${response.status}: ${response.statusText}`));
                    return null;
                }

                return response.text().then(text => {
                    if (!text) return { success: response.ok };

                    try {
                        return JSON.parse(text);
                    } catch(e) {
                        return {
                            success: response.ok,
                            mensaje: response.ok ? 'Operación completada' : 'Error en la solicitud'
                        };
                    }
                });
            })
            .then(data => {
                if (!data) return; // Ya manejado en el bloque anterior

                console.log('Datos de respuesta:', data);

                if (data && data.success !== false) {
                    // Actualizar UI
                    updateUI(nuevoEstado);

                    // Mostrar mensaje de éxito
                    showAlert(`Estado actualizado correctamente a "${nuevoEstado}"`, 'success');

                    // Recargar página después de un tiempo
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);

                    resolve(data);
                } else {
                    reject(new Error(data.mensaje || 'Error en la actualización'));
                }
            })
            .catch(error => {
                console.error(`Error en solicitud ${metodo}:`, error);
                reject(error);
            });
        });
    }

    // Función para mostrar alertas
    function showAlert(message, type) {
        console.log(`Alerta: ${message} (${type})`);

        // Evitar alertas duplicadas
        const existingAlerts = alertasContainer.querySelectorAll('.alert');
        for (let alert of existingAlerts) {
            if (alert.textContent.includes(message)) {
                return; // No mostrar alertas duplicadas
            }
        }

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';

        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;

        // Insertar al principio
        alertasContainer.insertBefore(alertDiv, alertasContainer.firstChild);

        // Auto-cerrar después de un tiempo
        if (type !== 'danger') {
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 500);
            }, 5000);
        }
    }

    // Función para actualizar la UI
    function updateUI(nuevoEstado) {
        // Deshabilitar controles
        estadoSelect.disabled = true;
        btnGuardar.disabled = true;

        // Actualizar badge
        const headerBadge = document.querySelector('.header-badge, .badge-estado');
        if (headerBadge) {
            headerBadge.textContent = nuevoEstado;
            headerBadge.className = `header-badge badge badge-pill status-${nuevoEstado.toLowerCase().replace(/\s+/g, '-')} px-3 py-2`;
        }

        // Actualizar el estado en el selector si existe
        if (estadoSelect) {
            estadoSelect.value = nuevoEstado;
        }

        console.log('UI actualizada con nuevo estado:', nuevoEstado);
    }

    console.log('Script de actualización de estado inicializado correctamente');
})();

 </script>
    @endsection

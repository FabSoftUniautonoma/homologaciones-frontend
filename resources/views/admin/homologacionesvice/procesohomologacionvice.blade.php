@extends('admin.layouts.appvice')

@section('title', 'Proceso de Homologación Vicerrectoría')

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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Estilos generales de tarjetas */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px var(--sombra);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 8px 16px var(--sombra-hover);
            transform: translateY(-3px);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background-color: var(--azul-contenedor) !important;
            border-bottom: 1px solid var(--borde);
        }

        .card-body {
            padding: 1.5rem;
            background-color: var(--blanco);
        }

        /* Encabezado principal */
        .header {
            background-color: var(--blanco);
            border-left: 5px solid var(--azul-medio);
        }

        .header h1 {
            color: var(--azul-oscuro);
            font-size: 1.8rem;
            font-weight: 700;
        }

        .header h5 {
            color: var(--texto-medio);
            font-weight: 500;
        }

        .header-badge {
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        /* Títulos de secciones */
        .section-title {
            color: var(--azul-oscuro);
            font-weight: 600;
            font-size: 1.35rem;
        }

        .card-header .section-title {
            display: flex;
            align-items: center;
        }

        .card-header .section-title i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            color: var(--azul-medio);
        }

        /* Filas de información */
        .info-row {
            margin-bottom: 1rem;
            display: flex;
            flex-wrap: wrap;
        }

        .info-label {
            display: inline-flex;
            align-items: center;
            min-width: 150px;
            color: var(--texto-oscuro);
            margin-right: 0.5rem;
        }

        .info-label i {
            color: var(--azul-medio) !important;
            margin-right: 0.5rem;
            width: 16px;
            text-align: center;
        }

        /* Tabla de asignaturas */
        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--texto-medio);
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--azul-muy-claro);
            color: var(--azul-oscuro);
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: var(--azul-contenedor);
        }

        .table-header-group th {
            background-color: var(--azul-muy-claro);
            color: var(--azul-oscuro);
            font-weight: 600;
        }

        .table td,
        .table th {
            padding: 0.85rem;
            vertical-align: middle;
            border: 1px solid var(--borde);
        }

        .border-right {
            border-right: 2px solid var(--azul-claro) !important;
        }

        .table tfoot {
            background-color: var(--azul-muy-claro);
            font-weight: 600;
            color: var(--azul-oscuro);
        }

        /* Enlaces de asignaturas */
        .asignatura-nombre-link {
            color: var(--azul-medio);
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
        }

        .asignatura-nombre-link:hover {
            color: var(--azul-oscuro);
            text-decoration: underline;
        }

        /* Badges de estado */
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            border-radius: 30px;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            text-transform: capitalize;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge i {
            margin-right: 0.4rem;
        }

        .badge-pill {
            border-radius: 50rem;
        }

        .status-aprobado {
            background-color: var(--verde-success);
            color: white;
        }

        .status-rechazado {
            background-color: var(--rojo-error);
            color: white;
        }

        .status-pendiente,
        .status-en-revisión {
            background-color: var(--naranja-warning);
            color: white;
        }

        /* Alertas */
        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .alert-danger {
            background-color: rgba(255, 77, 77, 0.1);
            color: var(--rojo-error);
            border-left: 4px solid var(--rojo-error);
        }

        .alert-warning {
            background-color: rgba(255, 152, 0, 0.1);
            color: #e65100;
            border-left: 4px solid var(--naranja-warning);
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--verde-success);
            border-left: 4px solid var(--verde-success);
        }

        .alert-info {
            background-color: rgba(33, 150, 243, 0.1);
            color: var(--azul-info);
            border-left: 4px solid var(--azul-info);
        }

        /* Formularios */
        .form-control {
            border-radius: 8px;
            border: 1px solid var(--borde);
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
            color: var(--texto-oscuro);
            background-color: var(--blanco);
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--azul-medio);
            box-shadow: 0 0 0 0.2rem rgba(0, 117, 191, 0.15);
        }

        select.form-control {
            appearance: none;
            padding-right: 2rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%230075bf' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            font-weight: 500;
            color: var(--texto-oscuro);
            margin-bottom: 0.5rem;
        }

        /* Botones */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.25rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-lg {
            padding: 0.8rem 1.8rem;
            font-size: 1.1rem;
        }

        .btn-sm {
            padding: 0.35rem 0.7rem;
            font-size: 0.85rem;
        }

        .btn-primary {
            background-color: var(--azul-medio);
            border-color: var(--azul-medio);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
        }

        .btn-outline-info {
            color: var(--azul-claro);
            border-color: var(--azul-claro);
        }

        .btn-outline-info:hover {
            background-color: var(--azul-claro);
            color: white;
        }

        .btn-group {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            overflow: hidden;
        }

        .btn-group .btn {
            box-shadow: none;
            border-radius: 0;
        }

        /* Firmas */
        .border-left-warning {
            border-left: 4px solid var(--azul-medio);
        }

        #firma-vicerrector-preview {
            height: 150px;
            border: 2px dashed var(--azul-claro) !important;
            background-color: var(--azul-muy-claro) !important;
            border-radius: 8px;
            transition: all 0.3s;
        }

        #firma-vicerrector-preview:hover {
            border-color: var(--azul-medio) !important;
            background-color: rgba(225, 245, 254, 0.7) !important;
        }

        /* Custom File Input */
        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            height: calc(1.5em + 1.2rem + 2px);
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: calc(1.5em + 1.2rem + 2px);
            margin: 0;
            opacity: 0;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: calc(1.5em + 1.2rem + 2px);
            padding: 0.6rem 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--texto-medio);
            background-color: var(--blanco);
            border: 1px solid var(--borde);
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .custom-file-label::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            display: flex;
            align-items: center;
            padding: 0.6rem 1rem;
            color: white;
            content: "Examinar";
            background-color: var(--azul-medio);
            border-left: 1px solid var(--borde);
            border-radius: 0 8px 8px 0;
        }

        /* Modales */
        .modal-content {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background-color: var(--azul-medio);
            color: white;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .modal-title i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid var(--borde);
            padding: 1rem 1.5rem;
            background-color: var(--gris-claro);
        }

        .close {
            color: white;
            text-shadow: none;
            opacity: 0.8;
            transition: all 0.2s;
        }

        .close:hover {
            opacity: 1;
            color: white;
        }

        /* Cards en modal */
        .origen-card .card-header,
        .destino-card .card-header {
            background-color: var(--azul-muy-claro) !important;
            color: var(--azul-oscuro);
            font-weight: 600;
        }

        .origen-card,
        .destino-card {
            border: 1px solid var(--borde);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Vista previa PDF */
        #pdf-preview-content {
            min-height: 400px;
        }

        /* Notificaciones flotantes */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card-body {
                padding: 1.25rem;
            }

            .info-label {
                min-width: 120px;
            }

            .table {
                font-size: 0.85rem;
            }

            .btn {
                padding: 0.5rem 1rem;
            }
        }

        /* Animaciones */
        .fa-spin {
            animation: fa-spin 1.2s infinite linear;
        }

        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="container">
        <!-- Contenedor de Alertas -->
        <div id="alertas-container" class="mb-3"></div>

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
                                            if (!empty($homologacionesExistentes ?? [])) {
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
                                    <td colspan="7" class="text-center py-4">
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
                                                Aprobado
                                            </option>
                                            <option value="Rechazado"
                                                {{ ($datos['estado_solicitud'] ?? '') == 'Rechazado' ? 'selected' : '' }}>
                                                Rechazado
                                            </option>
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
                        <label for="comentarios" class="font-weight-bold">
                            <i class="fas fa-comment-alt text-secondary mr-2"></i> Comentarios/Observaciones:
                        </label>
                        <textarea name="comentarios" id="comentarios" class="form-control" rows="4">{{ $datos['comentarios'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Sección de firma del vicerrector -->
            <div class="row">
                <div class="col-12">
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
                                                accept="image/*" onchange="handleFirmaVicerrectorUpload(event)">
                                            <label class="custom-file-label" for="firma-vicerrector"
                                                style="color: #0277bd;">
                                                Seleccionar archivo...
                                            </label>
                                        </div>
                                        <small class="form-text" style="color: #6c8ebf;">
                                            Formatos aceptados: JPG, PNG, GIF (Máximo 5MB)
                                        </small>
                                    </div>
                                    <!-- Botón para eliminar firma -->
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="eliminarFirmaVicerrector()" id="btn-eliminar-firma"
                                            style="display: none;">
                                            <i class="fas fa-trash mr-1"></i> Eliminar Firma
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="firma-vicerrector-preview"
                                        class="border rounded p-3 text-center d-flex align-items-center justify-content-center"
                                        style="height: 150px; background-color: #e1f5fe; border-color: #6c8ebf;">
                                        <p style="color: #19407b;" class="mb-0" id="firma-vicerrector-placeholder">
                                            Vista previa de la firma
                                        </p>
                                        <img id="img-firma-vicerrector"
                                            style="display: none; max-height: 140px; max-width: 100%;" />
                                    </div>
                                </div>
                            </div>

                            <!-- Campo oculto para almacenar la firma -->
                            <input type="hidden" id="firma_vicerrector_data" name="firma_vicerrector_data">

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
        </form>
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
                        <h5 style="color: #19407b;">Resolución de Homologación</h5>
                        <p style="color: #0277bd;">Documento listo para descargar</p>

                        <!-- Información del documento -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style="background-color: #e1f5fe;">
                                        <h6 class="mb-0" style="color: #19407b;">Información del Documento</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Estudiante:</strong> {{ $datos['estudiante'] ?? 'N/A' }}</p>
                                                <p><strong>Programa:</strong> {{ $datos['programa_destino'] ?? 'N/A' }}</p>
                                                <p><strong>Estado:</strong> <span
                                                        id="pdf-estado-final">{{ $datos['estado_solicitud'] ?? 'Pendiente' }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Radicado:</strong> {{ $datos['numero_radicado'] ?? 'N/A' }}</p>
                                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                                <p><strong>Total Créditos:</strong> {{ $totalCreditos ?? 0 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Firma del Vicerrector -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header" style="background-color: #e1f5fe;">
                                        <h6 class="mb-0" style="color: #19407b;">Firma del Vicerrector</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="border p-2"
                                            style="min-height: 120px; display: flex; align-items: center; justify-content: center;">
                                            <img id="pdf-firma-vicerrector"
                                                style="max-height: 100px; max-width: 100%; display: none;" />
                                            <span id="vicerrector-firma-placeholder" style="color: #666;">Firma pendiente
                                                de cargar</span>
                                        </div>
                                    </div>
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
                    <h5 class="modal-title" id="subjectModalLabel">
                        <i class="fas fa-book text-primary mr-2"></i>Detalles de Asignatura
                    </h5>
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
                            <h5 class="mb-0">
                                <i class="fas fa-bookmark text-primary mr-2"></i> Contenido Programático
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

@endsection

@section('scripts')
    <!-- Meta token CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Cargar jsPDF primero -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
        integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNhh/TCPM7eTfgV6baB8QLRpCnDrFD/3Vg5K5ajI5CILy5IZ5dBWGC6E="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Cargar jsPDF AutoTable después -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"
        integrity="sha512-YKsyaWDTG/eMRCVcJ52BF2XbdIEzCBJUXhKDU91WsJPW1zt7fUTU8+JZG7ViNq8IAuJl1O3OKnGl4h4wBfjKlQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@section('scripts')
    <!-- Meta token CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // ========================================
        // CAPTURA DE DATOS DEL PROCESO DE HOMOLOGACIÓN
        // ========================================
        // Variables globales
        window.firmaVicerrectorData = null;
        window.firmaCoordinadorData = null;
        window.pdfGenerado = null;
        window.libreriasListas = false;

        // URLs de firmas predeterminadas
        const FIRMAS_PREDETERMINADAS = {
            coordinador: 'https://i.postimg.cc/W4yX2QG8/firma-coordinador.png',
            vicerrector: 'https://i.postimg.cc/h4pG2K1S/firma-vicerrector.png'
        };

        // Inicializar cuando la página se carga
        document.addEventListener('DOMContentLoaded', function() {
            // Intentar cargar firma existente
            cargarFirmaVicerrector();
            // Actualizar estado inicial del botón
            actualizarEstadoBotonPDF();
        });

        // Escuchar cuando las librerías estén listas
        window.addEventListener('pdfLibrariesLoaded', function() {
            window.libreriasListas = true;
            console.log('PDF libraries ready');
            actualizarEstadoBotonPDF();

            // Verificar acceso a jsPDF
            const jsPDFTest = getJsPDF();
            if (jsPDFTest) {
                console.log('jsPDF test exitoso:', typeof jsPDFTest);
            }
        });

        // Función para obtener jsPDF de forma segura
        function getJsPDF() {
            if (window.jspdf && window.jspdf.jsPDF) {
                return window.jspdf.jsPDF;
            }
            if (window.jsPDF && typeof window.jsPDF === 'function') {
                return window.jsPDF;
            }
            if (window.jsPDF && window.jsPDF.jsPDF) {
                return window.jsPDF.jsPDF;
            }
            if (window.jsPDF && window.jsPDF.default) {
                return window.jsPDF.default;
            }

            console.error('jsPDF no encontrado. Window keys relacionadas:',
                Object.keys(window).filter(k => k.toLowerCase().includes('pdf')));
            return null;
        }

        // Función para mostrar alertas
        function mostrarAlerta(mensaje, tipo = 'info') {
            const alertContainer = document.getElementById('alertas-container');
            if (!alertContainer) return;

            const alertId = `alert-${Date.now()}`;
            const alertDiv = document.createElement('div');
            alertDiv.id = alertId;
            alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        <i class="fas fa-${getIconForType(tipo)} mr-2"></i>
        ${mensaje}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

            alertContainer.appendChild(alertDiv);

            // Auto-cerrar después de 5 segundos
            setTimeout(() => {
                if (document.getElementById(alertId)) {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 300);
                }
            }, 5000);
        }

        function getIconForType(tipo) {
            const icons = {
                'success': 'check-circle',
                'danger': 'exclamation-triangle',
                'warning': 'exclamation-circle',
                'info': 'info-circle'
            };
            return icons[tipo] || 'info-circle';
        }

        // Función para cargar imagen desde URL con fondo blanco
        function cargarImagenDesdeURL(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = function() {
                    try {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        canvas.width = this.width;
                        canvas.height = this.height;

                        // Llenar el canvas con fondo blanco primero
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);

                        // Luego dibujar la imagen encima
                        ctx.drawImage(this, 0, 0);

                        resolve(canvas.toDataURL('image/png'));
                    } catch (error) {
                        reject(error);
                    }
                };
                img.onerror = function() {
                    reject(new Error(`No se pudo cargar la imagen: ${url}`));
                };
                img.src = url;
            });
        }

        // Función para crear firma de texto con fondo blanco
        function crearFirmaTexto(nombre) {
            const canvas = document.createElement('canvas');
            canvas.width = 300;
            canvas.height = 100;
            const ctx = canvas.getContext('2d');

            // Fondo blanco explícito
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Configurar texto
            ctx.font = 'italic 24px serif';
            ctx.fillStyle = '#003399';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // Dibujar el texto
            ctx.fillText(nombre, canvas.width / 2, canvas.height / 2);

            // Agregar una línea decorativa debajo
            ctx.strokeStyle = '#003399';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(50, canvas.height - 20);
            ctx.lineTo(canvas.width - 50, canvas.height - 20);
            ctx.stroke();

            return canvas.toDataURL('image/png');
        }

        // Función para cargar firma del vicerrector existente
        function cargarFirmaVicerrector() {
            console.log("Verificando firma del vicerrector...");

            const firmaVicerrector = localStorage.getItem('firmaVicerrector_{{ $datos['id_homologacion'] ?? 0 }}');

            if (firmaVicerrector) {
                window.firmaVicerrectorData = firmaVicerrector;

                // Mostrar la firma en la vista previa
                const img = document.getElementById('img-firma-vicerrector');
                const placeholder = document.getElementById('firma-vicerrector-placeholder');
                const hiddenInput = document.getElementById('firma_vicerrector_data');
                const fileLabel = document.querySelector('label[for="firma-vicerrector"]');
                const btnEliminar = document.getElementById('btn-eliminar-firma');

                if (img && placeholder) {
                    img.src = firmaVicerrector;
                    img.style.display = 'block';
                    placeholder.style.display = 'none';
                }

                if (hiddenInput) {
                    hiddenInput.value = firmaVicerrector;
                }

                // Actualizar etiqueta para indicar que hay una firma cargada
                if (fileLabel) {
                    fileLabel.textContent = 'Firma cargada - Seleccionar nueva...';
                    fileLabel.style.color = '#28a745';
                }

                // Mostrar botón de eliminar
                if (btnEliminar) {
                    btnEliminar.style.display = 'inline-block';
                }

                // Habilitar botón de generar PDF
                actualizarEstadoBotonPDF();

                console.log("Firma del vicerrector cargada exitosamente");
                return true;
            }

            console.log("No se encontró firma del vicerrector guardada");
            return false;
        }

        // Función mejorada para manejar la carga de la firma del vicerrector
        function handleFirmaVicerrectorUpload(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('firma-vicerrector-preview');
            const placeholder = document.getElementById('firma-vicerrector-placeholder');
            const img = document.getElementById('img-firma-vicerrector');
            const hiddenInput = document.getElementById('firma_vicerrector_data');
            const fileLabel = document.querySelector('label[for="firma-vicerrector"]');
            const fileInput = event.target;
            const btnEliminar = document.getElementById('btn-eliminar-firma');

            // Función para limpiar estado anterior
            function limpiarEstadoFirma() {
                if (placeholder) placeholder.style.display = 'block';
                if (img) {
                    img.style.display = 'none';
                    img.src = '';
                }
                if (hiddenInput) hiddenInput.value = '';
                if (fileLabel) {
                    fileLabel.textContent = 'Seleccionar archivo...';
                    fileLabel.style.color = '#0277bd';
                }
                if (btnEliminar) {
                    btnEliminar.style.display = 'none';
                }

                window.firmaVicerrectorData = null;
                localStorage.removeItem('firmaVicerrector_{{ $datos['id_homologacion'] ?? 0 }}');
                actualizarEstadoBotonPDF();
            }

            if (file) {
                // Validar tipo de archivo
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    mostrarAlerta('Por favor, selecciona un archivo de imagen válido (JPG, PNG, GIF)', 'danger');
                    fileInput.value = '';
                    limpiarEstadoFirma();
                    return;
                }

                // Validar tamaño del archivo (máximo 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    mostrarAlerta('El archivo es demasiado grande. Máximo 5MB permitido.', 'danger');
                    fileInput.value = '';
                    limpiarEstadoFirma();
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const base64Data = e.target.result;

                    // Mostrar vista previa
                    if (placeholder) placeholder.style.display = 'none';
                    if (img) {
                        img.src = base64Data;
                        img.style.display = 'block';
                    }

                    // Guardar datos
                    if (hiddenInput) hiddenInput.value = base64Data;
                    window.firmaVicerrectorData = base64Data;

                    // Guardar en localStorage
                    localStorage.setItem('firmaVicerrector_{{ $datos['id_homologacion'] ?? 0 }}', base64Data);

                    // Actualizar etiqueta del archivo
                    if (fileLabel) {
                        fileLabel.textContent = file.name;
                        fileLabel.style.color = '#28a745';
                    }

                    // Mostrar botón de eliminar
                    if (btnEliminar) {
                        btnEliminar.style.display = 'inline-block';
                    }

                    // Habilitar botón PDF
                    actualizarEstadoBotonPDF();

                    mostrarAlerta('Firma del vicerrector cargada exitosamente', 'success');
                };

                reader.onerror = function() {
                    mostrarAlerta('Error al cargar el archivo. Inténtalo de nuevo.', 'danger');
                    fileInput.value = '';
                    limpiarEstadoFirma();
                };

                reader.readAsDataURL(file);
            } else {
                // Si no hay archivo seleccionado, limpiar todo
                limpiarEstadoFirma();
            }
        }

        // Función para eliminar firma manualmente
        function eliminarFirmaVicerrector() {
            const fileInput = document.getElementById('firma-vicerrector');
            const placeholder = document.getElementById('firma-vicerrector-placeholder');
            const img = document.getElementById('img-firma-vicerrector');
            const hiddenInput = document.getElementById('firma_vicerrector_data');
            const fileLabel = document.querySelector('label[for="firma-vicerrector"]');
            const btnEliminar = document.getElementById('btn-eliminar-firma');

            // Limpiar input de archivo
            if (fileInput) fileInput.value = '';

            // Resetear vista previa
            if (placeholder) placeholder.style.display = 'block';
            if (img) {
                img.style.display = 'none';
                img.src = '';
            }

            // Limpiar datos guardados
            if (hiddenInput) hiddenInput.value = '';
            window.firmaVicerrectorData = null;

            // Limpiar localStorage
            localStorage.removeItem('firmaVicerrector_{{ $datos['id_homologacion'] ?? 0 }}');

            // Resetear etiqueta
            if (fileLabel) {
                fileLabel.textContent = 'Seleccionar archivo...';
                fileLabel.style.color = '#0277bd';
            }

            // Ocultar botón de eliminar
            if (btnEliminar) {
                btnEliminar.style.display = 'none';
            }

            // Actualizar estado del botón PDF
            actualizarEstadoBotonPDF();

            mostrarAlerta('Firma eliminada correctamente', 'info');
        }

        // Función para actualizar el estado del botón de generar PDF
        function actualizarEstadoBotonPDF() {
            const btnGenerar = document.getElementById('btn-generar-pdf');
            if (btnGenerar) {
                const tieneFirema = !!window.firmaVicerrectorData;
                const libreriasListas = window.libreriasListas;
                const habilitado = tieneFirema && libreriasListas;

                btnGenerar.disabled = !habilitado;

                if (habilitado) {
                    btnGenerar.style.opacity = '1';
                    btnGenerar.style.cursor = 'pointer';
                    btnGenerar.title = 'Generar PDF de resolución';
                } else {
                    btnGenerar.style.opacity = '0.6';
                    btnGenerar.style.cursor = 'not-allowed';

                    if (!tieneFirema) {
                        btnGenerar.title = 'Debe cargar su firma primero';
                    } else if (!libreriasListas) {
                        btnGenerar.title = 'Cargando librerías PDF...';
                    }
                }
            }
        }

        // Función para preparar firmas automáticamente
        async function prepararFirmas() {
            console.log('Preparando firmas...');

            try {
                // Cargar firma del coordinador si no existe
                if (!window.firmaCoordinadorData) {
                    console.log('Cargando firma predeterminada del coordinador...');
                    try {
                        window.firmaCoordinadorData = await cargarImagenDesdeURL(FIRMAS_PREDETERMINADAS.coordinador);
                        console.log('Firma del coordinador cargada exitosamente');
                    } catch (error) {
                        console.warn('Error al cargar firma del coordinador:', error);
                        // Crear una firma de texto simple como fallback
                        window.firmaCoordinadorData = crearFirmaTexto('Juan Pablo Diago R.');
                    }
                }

                // La firma del vicerrector ya está cargada por el usuario
                console.log('Firma del vicerrector lista:', !!window.firmaVicerrectorData);

            } catch (error) {
                console.error('Error general al preparar firmas:', error);
            }
        }
        // Variables globales para almacenar los datos
        let datosHomologacion = {};
        let asignaturasOrigen = [];
        let asignaturasDestino = [];
        let homologacionesExistentes = [];

        // Función principal para capturar todos los datos
        function capturarTodosLosDatos() {
            // Capturar datos del encabezado
            capturarDatosEncabezado();

            // Capturar información del estudiante
            capturarInformacionEstudiante();

            // Capturar datos de la tabla de homologaciones
            capturarDatosTabla();

            // Capturar estado de decisión final
            capturarDecisionFinal();

            // Capturar datos de firma
            capturarDatosFirma();

            // Mostrar todos los datos capturados
            console.log('=== DATOS COMPLETOS DE HOMOLOGACIÓN ===');
            console.log(datosHomologacion);

            return datosHomologacion;
        }

        // Capturar datos del encabezado
        function capturarDatosEncabezado() {
            datosHomologacion.encabezado = {
                numero_radicado: extraerTexto('.header h5'),
                estado_solicitud: extraerTexto('.header-badge'),
                id_homologacion: extraerTexto('#id-homologacion')
            };

            console.log('Datos de encabezado capturados:', datosHomologacion.encabezado);
        }

        // Capturar información del estudiante
        function capturarInformacionEstudiante() {
            const infoRows = document.querySelectorAll('.info-row');

            datosHomologacion.estudiante = {
                nombre: '',
                identificacion: '',
                fecha_solicitud: '',
                universidad_origen: '',
                programa_destino: '',
                id_homologacion: ''
            };

            infoRows.forEach(row => {
                const label = row.querySelector('.info-label')?.textContent.trim().toLowerCase();
                const value = row.querySelector('span:not(.info-label)')?.textContent.trim();

                if (label && value) {
                    if (label.includes('nombre')) {
                        datosHomologacion.estudiante.nombre = value;
                    } else if (label.includes('identificación')) {
                        datosHomologacion.estudiante.identificacion = value;
                    } else if (label.includes('fecha')) {
                        datosHomologacion.estudiante.fecha_solicitud = value;
                    } else if (label.includes('universidad')) {
                        datosHomologacion.estudiante.universidad_origen = value;
                    } else if (label.includes('programa')) {
                        datosHomologacion.estudiante.programa_destino = value;
                    }
                }
            });

            console.log('Información del estudiante capturada:', datosHomologacion.estudiante);
        }

        // Capturar todos los datos de la tabla de homologaciones
        function capturarDatosTabla() {
            const filasTabla = document.querySelectorAll('tbody tr[data-asignatura-origen]');

            datosHomologacion.homologaciones = [];
            datosHomologacion.resumen = {
                total_asignaturas: 0,
                total_creditos: 0,
                asignaturas_sin_asignar: 0
            };

            filasTabla.forEach((fila, index) => {
                const homologacion = capturarFilaHomologacion(fila, index);
                if (homologacion) {
                    datosHomologacion.homologaciones.push(homologacion);
                }
            });

            // Capturar totales del footer
            const totalAsignaturas = extraerTexto('tfoot td:nth-child(3)');
            const totalCreditos = extraerTexto('#total-creditos');

            datosHomologacion.resumen.total_asignaturas = parseInt(totalAsignaturas) || datosHomologacion.homologaciones
                .length;
            datosHomologacion.resumen.total_creditos = parseInt(totalCreditos) || 0;
            datosHomologacion.resumen.asignaturas_sin_asignar = datosHomologacion.homologaciones.filter(h => !h
                .asignatura_destino.id).length;

            console.log('Datos de homologaciones capturados:', datosHomologacion.homologaciones);
            console.log('Resumen capturado:', datosHomologacion.resumen);
        }

        // Capturar datos de una fila específica de homologación
        function capturarFilaHomologacion(fila, index) {
            try {
                // Extraer datos de asignatura origen (desde el atributo data)
                const dataOrigenStr = fila.getAttribute('data-asignatura-origen');
                let asignaturaOrigen = {};

                if (dataOrigenStr) {
                    try {
                        asignaturaOrigen = JSON.parse(dataOrigenStr);
                    } catch (e) {
                        console.warn('Error parsing asignatura origen data:', e);
                    }
                }

                // Extraer datos adicionales de las celdas si no están en el JSON
                const celdas = fila.querySelectorAll('td');
                if (celdas.length >= 3) {
                    if (!asignaturaOrigen.nombre) {
                        asignaturaOrigen.nombre = celdas[0].querySelector('.asignatura-nombre-link')?.textContent.trim() ||
                            '';
                        asignaturaOrigen.codigo = celdas[0].querySelector('.text-muted')?.textContent.trim() || '';
                    }
                    if (!asignaturaOrigen.creditos) {
                        asignaturaOrigen.creditos = celdas[1].textContent.trim();
                    }
                    if (!asignaturaOrigen.nota_origen) {
                        asignaturaOrigen.nota_origen = celdas[2].textContent.trim();
                    }
                }

                // Extraer datos de asignatura destino
                let asignaturaDestino = {};
                const celdaDestino = fila.querySelector('td[data-asignatura-destino]');

                if (celdaDestino) {
                    const dataDestinoStr = celdaDestino.getAttribute('data-asignatura-destino');
                    if (dataDestinoStr) {
                        try {
                            asignaturaDestino = JSON.parse(dataDestinoStr);
                        } catch (e) {
                            console.warn('Error parsing asignatura destino data:', e);
                        }
                    }

                    // Extraer datos adicionales de las celdas si no están en el JSON
                    if (!asignaturaDestino.nombre && celdas.length >= 6) {
                        asignaturaDestino.nombre = celdas[3].querySelector('.asignatura-nombre-link')?.textContent.trim() ||
                            '';
                        asignaturaDestino.codigo = celdas[3].querySelector('.text-muted')?.textContent.trim() || '';
                        asignaturaDestino.creditos = celdas[4].textContent.trim();
                        asignaturaDestino.nota_destino = celdas[5].textContent.trim();
                    }
                } else {
                    // Asignatura no asignada
                    asignaturaDestino = {
                        id: null,
                        nombre: null,
                        codigo: null,
                        creditos: null,
                        nota_destino: null,
                        estado: 'no_asignada'
                    };
                }

                return {
                    index: index,
                    asignatura_origen: asignaturaOrigen,
                    asignatura_destino: asignaturaDestino,
                    estado_homologacion: determinarEstadoHomologacion(asignaturaOrigen, asignaturaDestino),
                    equivalencia_creditos: calcularEquivalenciaCreditos(asignaturaOrigen.creditos, asignaturaDestino
                        .creditos)
                };

            } catch (error) {
                console.error('Error capturando fila de homologación:', error);
                return null;
            }
        }

        // Capturar datos de decisión final
        function capturarDecisionFinal() {
            datosHomologacion.decision_final = {
                estado: document.getElementById('estado')?.value || '',
                comentarios: document.getElementById('comentarios')?.value || '',
                fecha_decision: new Date().toISOString()
            };

            console.log('Decisión final capturada:', datosHomologacion.decision_final);
        }

        // Capturar datos de firma
        function capturarDatosFirma() {
            const firmaInput = document.getElementById('firma-vicerrector');
            const firmaData = document.getElementById('firma_vicerrector_data');
            const firmaImg = document.getElementById('img-firma-vicerrector');

            datosHomologacion.firma_vicerrector = {
                archivo_cargado: firmaInput?.files?.length > 0 || false,
                nombre_archivo: firmaInput?.files?.[0]?.name || '',
                tipo_archivo: firmaInput?.files?.[0]?.type || '',
                tamaño_archivo: firmaInput?.files?.[0]?.size || 0,
                data_base64: firmaData?.value || '',
                imagen_visible: firmaImg?.style?.display !== 'none'
            };

            console.log('Datos de firma capturados:', datosHomologacion.firma_vicerrector);
        }

        // Funciones auxiliares
        function extraerTexto(selector) {
            const elemento = document.querySelector(selector);
            return elemento ? elemento.textContent.trim().replace(/\s+/g, ' ') : '';
        }

        function determinarEstadoHomologacion(origen, destino) {
            if (!destino.id) return 'sin_asignar';
            if (origen.creditos && destino.creditos) {
                const creditosOrigen = parseInt(origen.creditos);
                const creditosDestino = parseInt(destino.creditos);
                if (creditosOrigen === creditosDestino) return 'equivalente';
                if (creditosOrigen > creditosDestino) return 'creditos_menores';
                if (creditosOrigen < creditosDestino) return 'creditos_mayores';
            }
            return 'asignada';
        }

        function calcularEquivalenciaCreditos(creditosOrigen, creditosDestino) {
            const origen = parseInt(creditosOrigen) || 0;
            const destino = parseInt(creditosDestino) || 0;

            if (origen === 0 || destino === 0) return 0;
            return ((destino / origen) * 100).toFixed(2);
        }

        // Función para capturar datos específicos de una asignatura
        function capturarDatosAsignatura(index) {
            const fila = document.querySelector(`tbody tr[data-asignatura-origen]:nth-child(${index + 1})`);
            if (fila) {
                return capturarFilaHomologacion(fila, index);
            }
            return null;
        }

        // Función para obtener estadísticas de las homologaciones
        function obtenerEstadisticas() {
            if (!datosHomologacion.homologaciones) return null;

            const stats = {
                total: datosHomologacion.homologaciones.length,
                asignadas: 0,
                sin_asignar: 0,
                equivalentes: 0,
                creditos_totales_origen: 0,
                creditos_totales_destino: 0
            };

            datosHomologacion.homologaciones.forEach(h => {
                if (h.asignatura_destino.id) {
                    stats.asignadas++;
                    if (h.estado_homologacion === 'equivalente') {
                        stats.equivalentes++;
                    }
                } else {
                    stats.sin_asignar++;
                }

                stats.creditos_totales_origen += parseInt(h.asignatura_origen.creditos) || 0;
                stats.creditos_totales_destino += parseInt(h.asignatura_destino.creditos) || 0;
            });

            return stats;
        }

        // Función para exportar todos los datos como JSON
        function exportarDatosJSON() {
            const datos = capturarTodosLosDatos();
            const estadisticas = obtenerEstadisticas();

            const exportData = {
                ...datos,
                estadisticas: estadisticas,
                fecha_exportacion: new Date().toISOString(),
                version: '1.0'
            };

            const blob = new Blob([JSON.stringify(exportData, null, 2)], {
                type: 'application/json'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `homologacion_${datos.encabezado.numero_radicado || 'datos'}_${new Date().getTime()}.json`;
            a.click();
            URL.revokeObjectURL(url);

            return exportData;
        }

        // Event listeners para capturar cambios en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            // Capturar datos iniciales
            setTimeout(capturarTodosLosDatos, 1000);

            // Escuchar cambios en el formulario
            const form = document.getElementById('formDecision');
            if (form) {
                form.addEventListener('change', function() {
                    capturarDecisionFinal();
                });
            }

            // Escuchar cambios en la firma
            const firmaInput = document.getElementById('firma-vicerrector');
            if (firmaInput) {
                firmaInput.addEventListener('change', function() {
                    capturarDatosFirma();
                });
            }

            console.log('Sistema de captura de datos inicializado');
        });

        // Función para debug - mostrar todos los datos en consola
        function debugMostrarDatos() {
            console.clear();
            console.log('=== DEBUG: TODOS LOS DATOS ===');
            const datos = capturarTodosLosDatos();
            const stats = obtenerEstadisticas();

            console.table(datos.homologaciones);
            console.log('Estadísticas:', stats);
            console.log('Datos completos:', datos);

            return {
                datos,
                stats
            };
        }
    </script>
    <script>
        // Función para cargar jsPDF de forma asíncrona con fallbacks
        async function cargarJsPDF() {
            const cdns = [
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
                'https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js',
                'https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js'
            ];

            for (const cdn of cdns) {
                try {
                    await new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = cdn;
                        script.onload = () => {
                            console.log(`jsPDF cargado desde: ${cdn}`);
                            resolve();
                        };
                        script.onerror = () => reject(new Error(`Failed to load ${cdn}`));
                        document.head.appendChild(script);
                    });

                    // Verificar que jsPDF está disponible
                    if (window.jspdf || window.jsPDF) {
                        console.log('jsPDF disponible:', !!(window.jspdf || window.jsPDF));
                        return true;
                    }
                } catch (error) {
                    console.warn(`Error cargando jsPDF desde ${cdn}:`, error);
                }
            }

            throw new Error('No se pudo cargar jsPDF desde ningún CDN');
        }

        // Función para cargar AutoTable
        async function cargarAutoTable() {
            const cdns = [
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js',
                'https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.29/dist/jspdf.plugin.autotable.min.js',
                'https://unpkg.com/jspdf-autotable@3.5.29/dist/jspdf.plugin.autotable.min.js'
            ];

            for (const cdn of cdns) {
                try {
                    await new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = cdn;
                        script.onload = () => {
                            console.log(`AutoTable cargado desde: ${cdn}`);
                            resolve();
                        };
                        script.onerror = () => reject(new Error(`Failed to load ${cdn}`));
                        document.head.appendChild(script);
                    });
                    return true;
                } catch (error) {
                    console.warn(`Error cargando AutoTable desde ${cdn}:`, error);
                }
            }

            throw new Error('No se pudo cargar jsPDF AutoTable desde ningún CDN');
        }

        // Inicializar librerías
        (async function initLibraries() {
            try {
                await cargarJsPDF();
                await cargarAutoTable();
                console.log('Todas las librerías PDF cargadas exitosamente');

                // Disparar evento personalizado cuando las librerías estén listas
                window.dispatchEvent(new CustomEvent('pdfLibrariesLoaded'));
            } catch (error) {
                console.error('Error cargando librerías PDF:', error);
                setTimeout(() => {
                    if (window.mostrarAlerta) {
                        window.mostrarAlerta(
                            'Error cargando librerías PDF. Verifique su conexión a internet.', 'danger');
                    }
                }, 1000);
            }
        })();
    </script>
    <script>
        // ========================================
        // CAPTURA DE DATOS DEL PROCESO DE HOMOLOGACIÓN
        // ========================================

        // Variables globales para almacenar los datos
        let datosHomologacion = {};
        let asignaturasOrigen = [];
        let asignaturasDestino = [];
        let homologacionesExistentes = [];

        // Función principal para capturar todos los datos
        function capturarTodosLosDatos() {
            // Capturar datos del encabezado
            capturarDatosEncabezado();

            // Capturar información del estudiante
            capturarInformacionEstudiante();

            // Capturar datos de la tabla de homologaciones
            capturarDatosTabla();

            // Capturar estado de decisión final
            capturarDecisionFinal();

            // Capturar datos de firma
            capturarDatosFirma();

            // Mostrar todos los datos capturados
            console.log('=== DATOS COMPLETOS DE HOMOLOGACIÓN ===');
            console.log(datosHomologacion);

            return datosHomologacion;
        }

        // Capturar datos del encabezado
        function capturarDatosEncabezado() {
            datosHomologacion.encabezado = {
                numero_radicado: extraerTexto('.header h5'),
                estado_solicitud: extraerTexto('.header-badge'),
                id_homologacion: extraerTexto('#id-homologacion')
            };

            console.log('Datos de encabezado capturados:', datosHomologacion.encabezado);
        }

        // Capturar información del estudiante
        function capturarInformacionEstudiante() {
            const infoRows = document.querySelectorAll('.info-row');

            datosHomologacion.estudiante = {
                nombre: '',
                identificacion: '',
                fecha_solicitud: '',
                universidad_origen: '',
                programa_destino: '',
                id_homologacion: ''
            };

            infoRows.forEach(row => {
                const label = row.querySelector('.info-label')?.textContent.trim().toLowerCase();
                const value = row.querySelector('span:not(.info-label)')?.textContent.trim();

                if (label && value) {
                    if (label.includes('nombre')) {
                        datosHomologacion.estudiante.nombre = value;
                    } else if (label.includes('identificación')) {
                        datosHomologacion.estudiante.identificacion = value;
                    } else if (label.includes('fecha')) {
                        datosHomologacion.estudiante.fecha_solicitud = value;
                    } else if (label.includes('universidad')) {
                        datosHomologacion.estudiante.universidad_origen = value;
                    } else if (label.includes('programa')) {
                        datosHomologacion.estudiante.programa_destino = value;
                    }
                }
            });

            console.log('Información del estudiante capturada:', datosHomologacion.estudiante);
        }

        // Capturar todos los datos de la tabla de homologaciones
        function capturarDatosTabla() {
            const filasTabla = document.querySelectorAll('tbody tr[data-asignatura-origen]');

            datosHomologacion.homologaciones = [];
            datosHomologacion.resumen = {
                total_asignaturas: 0,
                total_creditos: 0,
                asignaturas_sin_asignar: 0
            };

            filasTabla.forEach((fila, index) => {
                const homologacion = capturarFilaHomologacion(fila, index);
                if (homologacion) {
                    datosHomologacion.homologaciones.push(homologacion);
                }
            });

            // Capturar totales del footer
            const totalAsignaturas = extraerTexto('tfoot td:nth-child(3)');
            const totalCreditos = extraerTexto('#total-creditos');

            datosHomologacion.resumen.total_asignaturas = parseInt(totalAsignaturas) || datosHomologacion.homologaciones
                .length;
            datosHomologacion.resumen.total_creditos = parseInt(totalCreditos) || 0;
            datosHomologacion.resumen.asignaturas_sin_asignar = datosHomologacion.homologaciones.filter(h => !h
                .asignatura_destino.id).length;

            console.log('Datos de homologaciones capturados:', datosHomologacion.homologaciones);
            console.log('Resumen capturado:', datosHomologacion.resumen);
        }

        // Capturar datos de una fila específica de homologación MEJORADA
        function capturarFilaHomologacion(fila, index) {
            try {
                // Extraer datos de asignatura origen (desde el atributo data)
                const dataOrigenStr = fila.getAttribute('data-asignatura-origen');
                let asignaturaOrigen = {};

                if (dataOrigenStr) {
                    try {
                        asignaturaOrigen = JSON.parse(dataOrigenStr);
                    } catch (e) {
                        console.warn('Error parsing asignatura origen data:', e);
                    }
                }

                // Extraer datos adicionales de las celdas si no están en el JSON
                const celdas = fila.querySelectorAll('td');
                if (celdas.length >= 3) {
                    if (!asignaturaOrigen.nombre) {
                        asignaturaOrigen.nombre = celdas[0].querySelector('.asignatura-nombre-link')?.textContent.trim() ||
                            '';
                        asignaturaOrigen.codigo = celdas[0].querySelector('.text-muted')?.textContent.trim() || '';
                    }
                    if (!asignaturaOrigen.creditos) {
                        asignaturaOrigen.creditos = celdas[1].textContent.trim();
                    }
                    if (!asignaturaOrigen.nota_origen) {
                        asignaturaOrigen.nota_origen = celdas[2].textContent.trim();
                    }
                }

                // Extraer datos de asignatura destino
                let asignaturaDestino = {};
                const celdaDestino = fila.querySelector('td[data-asignatura-destino]');

                if (celdaDestino) {
                    const dataDestinoStr = celdaDestino.getAttribute('data-asignatura-destino');
                    if (dataDestinoStr) {
                        try {
                            asignaturaDestino = JSON.parse(dataDestinoStr);
                        } catch (e) {
                            console.warn('Error parsing asignatura destino data:', e);
                        }
                    }

                    // Extraer datos adicionales de las celdas si no están en el JSON
                    if (!asignaturaDestino.nombre && celdas.length >= 6) {
                        const linkDestino = celdas[3].querySelector('.asignatura-nombre-link');
                        const codigoDestino = celdas[3].querySelector('.text-muted');

                        asignaturaDestino.nombre = linkDestino?.textContent.trim() || '';
                        asignaturaDestino.codigo = codigoDestino?.textContent.trim() || '';
                        asignaturaDestino.creditos = celdas[4].textContent.trim();
                        asignaturaDestino.nota_destino = celdas[5].textContent.trim();
                    }
                } else {
                    // Asignatura no asignada
                    asignaturaDestino = {
                        id: null,
                        nombre: null,
                        codigo: null,
                        creditos: null,
                        nota_destino: null,
                        estado: 'no_asignada'
                    };
                }

                return {
                    index: index,
                    asignatura_origen: asignaturaOrigen,
                    asignatura_destino: asignaturaDestino,
                    estado_homologacion: determinarEstadoHomologacion(asignaturaOrigen, asignaturaDestino),
                    equivalencia_creditos: calcularEquivalenciaCreditos(asignaturaOrigen.creditos, asignaturaDestino
                        .creditos)
                };

            } catch (error) {
                console.error('Error capturando fila de homologación:', error);
                return null;
            }
        }

        // Capturar datos de decisión final
        function capturarDecisionFinal() {
            datosHomologacion.decision_final = {
                estado: document.getElementById('estado')?.value || '',
                comentarios: document.getElementById('comentarios')?.value || '',
                fecha_decision: new Date().toISOString()
            };

            console.log('Decisión final capturada:', datosHomologacion.decision_final);
        }

        // Capturar datos de firma
        function capturarDatosFirma() {
            const firmaInput = document.getElementById('firma-vicerrector');
            const firmaData = document.getElementById('firma_vicerrector_data');
            const firmaImg = document.getElementById('img-firma-vicerrector');

            datosHomologacion.firma_vicerrector = {
                archivo_cargado: firmaInput?.files?.length > 0 || false,
                nombre_archivo: firmaInput?.files?.[0]?.name || '',
                tipo_archivo: firmaInput?.files?.[0]?.type || '',
                tamaño_archivo: firmaInput?.files?.[0]?.size || 0,
                data_base64: firmaData?.value || '',
                imagen_visible: firmaImg?.style?.display !== 'none'
            };

            console.log('Datos de firma capturados:', datosHomologacion.firma_vicerrector);
        }

        // Funciones auxiliares
        function extraerTexto(selector) {
            const elemento = document.querySelector(selector);
            return elemento ? elemento.textContent.trim().replace(/\s+/g, ' ') : '';
        }

        function determinarEstadoHomologacion(origen, destino) {
            if (!destino.id) return 'sin_asignar';
            if (origen.creditos && destino.creditos) {
                const creditosOrigen = parseInt(origen.creditos);
                const creditosDestino = parseInt(destino.creditos);
                if (creditosOrigen === creditosDestino) return 'equivalente';
                if (creditosOrigen > creditosDestino) return 'creditos_menores';
                if (creditosOrigen < creditosDestino) return 'creditos_mayores';
            }
            return 'asignada';
        }

        function calcularEquivalenciaCreditos(creditosOrigen, creditosDestino) {
            const origen = parseInt(creditosOrigen) || 0;
            const destino = parseInt(creditosDestino) || 0;

            if (origen === 0 || destino === 0) return 0;
            return ((destino / origen) * 100).toFixed(2);
        }

        // Función para capturar datos específicos de una asignatura
        function capturarDatosAsignatura(index) {
            const fila = document.querySelector(`tbody tr[data-asignatura-origen]:nth-child(${index + 1})`);
            if (fila) {
                return capturarFilaHomologacion(fila, index);
            }
            return null;
        }

        // Función para obtener estadísticas de las homologaciones
        function obtenerEstadisticas() {
            if (!datosHomologacion.homologaciones) return null;

            const stats = {
                total: datosHomologacion.homologaciones.length,
                asignadas: 0,
                sin_asignar: 0,
                equivalentes: 0,
                creditos_totales_origen: 0,
                creditos_totales_destino: 0
            };

            datosHomologacion.homologaciones.forEach(h => {
                if (h.asignatura_destino.id) {
                    stats.asignadas++;
                    if (h.estado_homologacion === 'equivalente') {
                        stats.equivalentes++;
                    }
                } else {
                    stats.sin_asignar++;
                }

                stats.creditos_totales_origen += parseInt(h.asignatura_origen.creditos) || 0;
                stats.creditos_totales_destino += parseInt(h.asignatura_destino.creditos) || 0;
            });

            return stats;
        }

        // Función para exportar todos los datos como JSON
        function exportarDatosJSON() {
            const datos = capturarTodosLosDatos();
            const estadisticas = obtenerEstadisticas();

            const exportData = {
                ...datos,
                estadisticas: estadisticas,
                fecha_exportacion: new Date().toISOString(),
                version: '1.0'
            };

            const blob = new Blob([JSON.stringify(exportData, null, 2)], {
                type: 'application/json'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `homologacion_${datos.encabezado.numero_radicado || 'datos'}_${new Date().getTime()}.json`;
            a.click();
            URL.revokeObjectURL(url);

            return exportData;
        }

        // Event listeners para capturar cambios en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            // Capturar datos iniciales
            setTimeout(capturarTodosLosDatos, 1000);

            // Escuchar cambios en el formulario
            const form = document.getElementById('formDecision');
            if (form) {
                form.addEventListener('change', function() {
                    capturarDecisionFinal();
                });
            }

            // Escuchar cambios en la firma
            const firmaInput = document.getElementById('firma-vicerrector');
            if (firmaInput) {
                firmaInput.addEventListener('change', function() {
                    capturarDatosFirma();
                });
            }

            console.log('Sistema de captura de datos inicializado');
        });

        // Función para debug - mostrar todos los datos en consola
        function debugMostrarDatos() {
            console.clear();
            console.log('=== DEBUG: TODOS LOS DATOS ===');
            const datos = capturarTodosLosDatos();
            const stats = obtenerEstadisticas();

            console.table(datos.homologaciones);
            console.log('Estadísticas:', stats);
            console.log('Datos completos:', datos);

            return {
                datos,
                stats
            };
        }
    </script>

    <script>
        // ========================================
        // CARGA DE LIBRERÍAS PDF
        // ========================================

        // Función para cargar jsPDF de forma asíncrona con fallbacks
        async function cargarJsPDF() {
            const cdns = [
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
                'https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js',
                'https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js'
            ];

            for (const cdn of cdns) {
                try {
                    await new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = cdn;
                        script.onload = () => {
                            console.log(`jsPDF cargado desde: ${cdn}`);
                            resolve();
                        };
                        script.onerror = () => reject(new Error(`Failed to load ${cdn}`));
                        document.head.appendChild(script);
                    });

                    // Verificar que jsPDF está disponible
                    if (window.jspdf || window.jsPDF) {
                        console.log('jsPDF disponible:', !!(window.jspdf || window.jsPDF));
                        return true;
                    }
                } catch (error) {
                    console.warn(`Error cargando jsPDF desde ${cdn}:`, error);
                }
            }

            throw new Error('No se pudo cargar jsPDF desde ningún CDN');
        }

        // Función para cargar AutoTable
        async function cargarAutoTable() {
            const cdns = [
                'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js',
                'https://cdn.jsdelivr.net/npm/jspdf-autotable@3.5.29/dist/jspdf.plugin.autotable.min.js',
                'https://unpkg.com/jspdf-autotable@3.5.29/dist/jspdf.plugin.autotable.min.js'
            ];

            for (const cdn of cdns) {
                try {
                    await new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = cdn;
                        script.onload = () => {
                            console.log(`AutoTable cargado desde: ${cdn}`);
                            resolve();
                        };
                        script.onerror = () => reject(new Error(`Failed to load ${cdn}`));
                        document.head.appendChild(script);
                    });
                    return true;
                } catch (error) {
                    console.warn(`Error cargando AutoTable desde ${cdn}:`, error);
                }
            }

            throw new Error('No se pudo cargar jsPDF AutoTable desde ningún CDN');
        }

        // Inicializar librerías
        (async function initLibraries() {
            try {
                await cargarJsPDF();
                await cargarAutoTable();
                console.log('Todas las librerías PDF cargadas exitosamente');

                // Disparar evento personalizado cuando las librerías estén listas
                window.dispatchEvent(new CustomEvent('pdfLibrariesLoaded'));
            } catch (error) {
                console.error('Error cargando librerías PDF:', error);
                setTimeout(() => {
                    if (window.mostrarAlerta) {
                        window.mostrarAlerta(
                            'Error cargando librerías PDF. Verifique su conexión a internet.', 'danger');
                    }
                }, 1000);
            }
        })();
    </script>

    <script>
        // ========================================
        // GENERACIÓN DE PDF MEJORADA
        // ========================================








        // ========================================
        // FUNCIÓN PRINCIPAL DE GENERACIÓN DE PDF MEJORADA
        // ========================================

        // Función para generar PDF con vista previa MEJORADA
        async function generarPDF() {
            console.log("🔄 Iniciando generación de PDF...");

            // Verificar que las librerías estén cargadas
            if (!window.libreriasListas) {
                mostrarAlerta('Las librerías PDF aún se están cargando. Espere un momento e intente nuevamente.',
                    'warning');
                return;
            }

            // Verificar que jsPDF esté disponible
            const jsPDF = getJsPDF();
            if (!jsPDF) {
                mostrarAlerta('jsPDF no está disponible. Recargue la página e intente nuevamente.', 'danger');
                return;
            }
            if (!window.firmaVicerrectorData) {
                mostrarAlerta('Se requiere la firma del vicerrector para generar el PDF', 'danger');
                return;
            }

            try {
                // 1. CAPTURAR DATOS COMPLETOS DESDE LA API DE CAPTURA
                const datosCompletos = capturarTodosLosDatos();

                console.log('✅ Datos capturados desde la API:', datosCompletos);

                // 2. PREPARAR DATOS ESTRUCTURADOS PARA EL PDF
                const datosEstructurados = {
                    estudiante: {
                        nombre: datosCompletos.estudiante.nombre || '{{ $datos['estudiante'] ?? 'N/A' }}',
                        identificacion: datosCompletos.estudiante.identificacion ||
                            '{{ $datos['numero_identificacion'] ?? 'N/A' }}'
                    },
                    solicitud: {
                        universidad_origen: datosCompletos.estudiante.universidad_origen ||
                            '{{ $datos['universidad_origen'] ?? 'N/A' }}',
                        programa_destino: datosCompletos.estudiante.programa_destino ||
                            '{{ $datos['programa_destino'] ?? 'N/A' }}',
                        numero_radicado: datosCompletos.encabezado.numero_radicado.replace('Radicado: ', '') ||
                            '{{ $datos['numero_radicado'] ?? 'N/A' }}',
                        estado: document.getElementById('estado').value,
                        comentarios: document.getElementById('comentarios').value,
                        fecha_solicitud: datosCompletos.estudiante.fecha_solicitud
                    },
                    homologaciones: datosCompletos.homologaciones || []
                };

                console.log('✅ Datos estructurados para PDF:', datosEstructurados);

                // 3. PREPARAR FIRMAS AUTOMÁTICAMENTE
                await prepararFirmas();

                // 4. GENERAR PDF CON DATOS COMPLETOS
                await generarPDFConDatos(datosEstructurados, true);

            } catch (error) {
                console.error('❌ Error al generar PDF:', error);
                mostrarAlerta('Error al generar el PDF. Inténtalo de nuevo.', 'danger');
            }
        }

        // Función principal mejorada para generar PDF con datos completos
        async function generarPDFConDatos(datos, esVistaVicerrector = false) {
            try {
                const jsPDF = getJsPDF();
                if (!jsPDF) {
                    throw new Error('jsPDF no está disponible. Las librerías PDF no se cargaron correctamente.');
                }

                console.log('🔄 Generando PDF con datos completos...');

                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'letter',
                    compress: true
                });

                // Configurar colores institucionales
                const colorAzulInstitucional = [0, 51, 153];
                const colorAzulClaro = [230, 236, 250];
                const colorGris = [100, 100, 100];

                // Obtener la fecha actual
                const fechaActual = new Date();
                const dia = fechaActual.getDate();
                const mes = fechaActual.toLocaleString('es-ES', {
                    month: 'long'
                });
                const año = fechaActual.getFullYear();
                const fechaFormateada = `Popayán, ${dia} de ${mes} de ${año}`;

                // Número de resolución
                const numeroResolucion = datos.solicitud.numero_radicado !== 'N/A' ?
                    `RES-${datos.solicitud.numero_radicado}-${año}` :
                    `${año}-${Math.floor(Math.random() * 900) + 100}`;

                let yPos = 25;

                // ========================================
                // PÁGINA 1: ENCABEZADO Y PREÁMBULO
                // ========================================

                // Marco decorativo
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.3);
                doc.roundedRect(10, 10, 195, 260, 2, 2);

                // Título y logo institucional
                doc.setFontSize(14);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, yPos, {
                    align: 'center'
                });

                yPos += 10;
                doc.setFontSize(11);
                doc.setFont('helvetica', 'italic');
                doc.text('Líderes, visionarios y emprendedores', 105, yPos, {
                    align: 'center'
                });

                // Línea decorativa
                yPos += 10;
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.7);
                doc.line(30, yPos, 180, yPos);

                // Número de resolución
                yPos += 15;
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

                // Fondo para el título de resolución
                doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
                doc.roundedRect(50, yPos - 6, 110, 10, 1, 1, 'F');

                doc.text(`RESOLUCIÓN No. ${numeroResolucion}`, 105, yPos, {
                    align: 'center'
                });

                yPos += 15;
                doc.text('Del', 105, yPos, {
                    align: 'center'
                });

                yPos += 10;
                doc.text(`(${dia} ${mes.toUpperCase().substring(0, 3)}. ${año})`, 105, yPos, {
                    align: 'center'
                });

                yPos += 25;

                // Título principal del documento
                doc.setFont('helvetica', 'normal');
                doc.setTextColor(0, 0, 0);
                const accionTexto = datos.solicitud.estado?.toLowerCase() === 'aprobado' ? 'aprueba' : 'rechaza';
                const tituloPrincipal =
                    `Por la cual se ${accionTexto} el estudio de homologación de los cursos aprobados en ${datos.solicitud.universidad_origen.toUpperCase()}, Programa de ${datos.solicitud.programa_destino.toUpperCase()}, por ${datos.estudiante.nombre.toUpperCase()} identificado con ${datos.estudiante.identificacion}.`;

                const lineasTituloPrincipal = doc.splitTextToSize(tituloPrincipal, 170);
                doc.text(lineasTituloPrincipal, 20, yPos);

                yPos += lineasTituloPrincipal.length * 6 + 15;

                // EL VICERRECTOR ACADÉMICO
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(11);
                doc.text('EL VICERRECTOR ACADÉMICO', 20, yPos);

                yPos += 15;

                // CONSIDERANDO
                doc.setFont('helvetica', 'bold');
                doc.text('CONSIDERANDO:', 20, yPos);

                yPos += 10;

                // Texto de consideración
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                const textoConsiderando =
                    `Que el estudiante ${datos.estudiante.nombre}, identificado con cédula ${datos.estudiante.identificacion}, presentó solicitud de homologación de asignaturas cursadas en ${datos.solicitud.universidad_origen}, Programa de ${datos.solicitud.programa_destino}.`;

                const lineasConsiderando = doc.splitTextToSize(textoConsiderando, 170);
                doc.text(lineasConsiderando, 20, yPos);

                yPos += lineasConsiderando.length * 5 + 10;

                // RESUELVE
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(11);
                doc.text('RESUELVE:', 20, yPos);

                yPos += 10;

                // Artículo 1
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(10);
                doc.text('ARTÍCULO PRIMERO:', 20, yPos);

                yPos += 6;

                doc.setFont('helvetica', 'normal');
                const articulo1 =
                    `${accionTexto.toUpperCase()} la homologación de las siguientes asignaturas cursadas por ${datos.estudiante.nombre} en ${datos.solicitud.universidad_origen}:`;
                const lineasArticulo1 = doc.splitTextToSize(articulo1, 170);
                doc.text(lineasArticulo1, 20, yPos);

                yPos += lineasArticulo1.length * 5 + 15;

                // ========================================
                // NUEVA PÁGINA PARA TABLA COMPLETA
                // ========================================

                doc.addPage();

                // Marco decorativo para la nueva página
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.3);
                doc.roundedRect(10, 10, 195, 260, 2, 2);

                // Encabezado en la nueva página
                doc.setFontSize(9);
                doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
                doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, {
                    align: 'center'
                });
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);

                yPos = 35;

                // Título de la tabla
                doc.setFontSize(11);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

                // Fondo para el título de la tabla
                doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
                doc.roundedRect(25, yPos - 6, 160, 10, 2, 2, 'F');

                doc.text('CURSOS ACADÉMICOS HOMOLOGADOS', 105, yPos, {
                    align: 'center'
                });
                doc.setTextColor(0, 0, 0);

                yPos += 15;

                // TABLA COMPLETA CON TODOS LOS DATOS
                if (datos.homologaciones && datos.homologaciones.length > 0) {
                    console.log('📊 Generando tabla con', datos.homologaciones.length, 'homologaciones');

                    // Preparar datos para la tabla COMPLETA con todos los campos
                    const tableData = datos.homologaciones.map((h, index) => [
                        (index + 1).toString(), // Número
                        h.asignatura_origen?.nombre || 'N/A', // Curso origen
                        h.asignatura_origen?.codigo || 'N/A', // Código origen
                        h.asignatura_origen?.creditos || 'N/A', // Créditos origen
                        h.asignatura_origen?.nota_origen || 'N/A', // Nota origen
                        h.asignatura_destino?.nombre || 'No asignada', // Curso destino
                        h.asignatura_destino?.codigo || 'N/A', // Código destino
                        h.asignatura_destino?.creditos || 'N/A', // Créditos destino
                        h.asignatura_destino?.nota_destino || '3.0' // Nota destino
                    ]);

                    // Headers completos
                    const headers = [
                        'No.',
                        'CURSO ORIGEN',
                        'CÓD. ORIGEN',
                        'CRÉD. ORIGEN',
                        'NOTA ORIGEN',
                        'CURSO DESTINO',
                        'CÓD. DESTINO',
                        'CRÉD. DEST.',
                        'NOTA DEST.'
                    ];

                    // Generar tabla con autoTable
                    if (typeof doc.autoTable === 'function') {
                        doc.autoTable({
                            startY: yPos,
                            head: [headers],
                            body: tableData,
                            margin: {
                                left: 10,
                                right: 10
                            },
                            styles: {
                                fontSize: 7,
                                cellPadding: 3,
                                lineWidth: 0.1,
                                valign: 'middle',
                                overflow: 'linebreak',
                                lineColor: [200, 200, 200]
                            },
                            headStyles: {
                                fillColor: colorAzulInstitucional,
                                textColor: [255, 255, 255],
                                fontStyle: 'bold',
                                halign: 'center',
                                fontSize: 7
                            },
                            columnStyles: {
                                0: {
                                    cellWidth: 12,
                                    halign: 'center'
                                }, // No.
                                1: {
                                    cellWidth: 35,
                                    fontSize: 6
                                }, // Curso origen
                                2: {
                                    cellWidth: 15,
                                    halign: 'center',
                                    fontSize: 6
                                }, // Código origen
                                3: {
                                    cellWidth: 12,
                                    halign: 'center'
                                }, // Créditos origen
                                4: {
                                    cellWidth: 12,
                                    halign: 'center'
                                }, // Nota origen
                                5: {
                                    cellWidth: 35,
                                    fontSize: 6
                                }, // Curso destino
                                6: {
                                    cellWidth: 15,
                                    halign: 'center',
                                    fontSize: 6
                                }, // Código destino
                                7: {
                                    cellWidth: 12,
                                    halign: 'center'
                                }, // Créditos destino
                                8: {
                                    cellWidth: 12,
                                    halign: 'center'
                                } // Nota destino
                            },
                            alternateRowStyles: {
                                fillColor: [245, 245, 245]
                            },
                            didDrawCell: function(data) {
                                if (data.section === 'body' || data.section === 'head') {
                                    doc.setDrawColor(150, 150, 150);
                                    doc.setLineWidth(0.1);
                                    doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height,
                                        'S');
                                }
                            }
                        });

                        yPos = doc.lastAutoTable.finalY + 15;
                    } else {
                        console.warn('⚠️ autoTable no disponible');
                        doc.text('Tabla de homologaciones (requiere autoTable para vista completa)', 20, yPos);
                        yPos += 20;
                    }

                    // Calcular totales
                    const totalCreditos = datos.homologaciones.reduce((sum, item) => {
                        return sum + (parseInt(item.asignatura_destino?.creditos) || 0);
                    }, 0);

                    // Resumen de totales MEJORADO
                    doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
                    doc.roundedRect(95, yPos - 5, 90, 30, 2, 2, 'F');

                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'bold');
                    doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

                    doc.text('RESUMEN DE HOMOLOGACIÓN', 140, yPos + 3, {
                        align: 'center'
                    });

                    yPos += 12;
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(9);
                    doc.text('TOTAL CURSOS HOMOLOGADOS:', 100, yPos);
                    doc.text(datos.homologaciones.length.toString(), 175, yPos);

                    yPos += 8;
                    doc.text('TOTAL CRÉDITOS HOMOLOGADOS:', 100, yPos);
                    doc.text(totalCreditos.toString(), 175, yPos);

                } else {
                    console.warn('⚠️ No se encontraron homologaciones para mostrar');
                    doc.setFont('helvetica', 'italic');
                    doc.text('No se encontraron asignaturas homologadas.', 20, yPos);
                    yPos += 15;
                }

                // ========================================
                // NUEVA PÁGINA PARA ARTÍCULOS FINALES Y FIRMAS
                // ========================================

                doc.addPage();

                // Marco decorativo
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.3);
                doc.roundedRect(10, 10, 195, 260, 2, 2);

                // Encabezado
                doc.setFontSize(9);
                doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
                doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, {
                    align: 'center'
                });
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);

                yPos = 50;

                // Artículo Segundo
                doc.setFillColor(240, 240, 240);
                doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
                doc.setFont('helvetica', 'bold');
                doc.text('ARTÍCULO 2°.', 20, yPos);
                doc.setFont('helvetica', 'normal');

                yPos += 10;

                const textoArticuloSegundo =
                    `Ordenar al Departamento de Admisiones, Registro y Control Académico, registrar en el sistema la homologación de los cursos académicos relacionados.`;
                const lineasArticulo2 = doc.splitTextToSize(textoArticuloSegundo, 175);
                doc.text(lineasArticulo2, 20, yPos);

                yPos += lineasArticulo2.length * 6 + 10;

                // Artículo Tercero
                doc.setFillColor(240, 240, 240);
                doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
                doc.setFont('helvetica', 'bold');
                doc.text('ARTÍCULO 3°.', 20, yPos);
                doc.setFont('helvetica', 'normal');

                yPos += 10;

                const textoArticuloTercero = `La presente Resolución rige a partir de la fecha de su expedición.`;
                doc.text(textoArticuloTercero, 20, yPos);

                yPos += 20;

                // Comuníquese y cúmplase
                doc.setFont('helvetica', 'bold');
                doc.text('COMUNÍQUESE Y CÚMPLASE', 105, yPos, {
                    align: 'center'
                });

                yPos += 10;

                // Fecha de expedición
                doc.setFont('helvetica', 'normal');
                doc.text(`Dada en Popayán, a los ${dia} días del mes de ${mes} de ${año}.`, 105, yPos, {
                    align: 'center'
                });

                yPos += 40;

                // ========================================
                // SECCIÓN DE FIRMAS MEJORADA
                // ========================================

                const espacioFirma = 90;

                // Rectángulos de fondo BLANCOS para área de firmas
                doc.setFillColor(255, 255, 255);
                doc.setDrawColor(220, 220, 220);
                doc.setLineWidth(0.5);

                // Área del coordinador
                doc.roundedRect(105 - espacioFirma / 2 - 40, yPos - 25, 80, 75, 3, 3, 'FD');
                // Área del vicerrector
                doc.roundedRect(105 + espacioFirma / 2 - 40, yPos - 25, 80, 75, 3, 3, 'FD');

                // AGREGAR FIRMAS
                try {
                    // Firma del coordinador
                    if (window.firmaCoordinadorData) {
                        console.log('📝 Agregando firma del coordinador al PDF');
                        doc.addImage(
                            window.firmaCoordinadorData,
                            'PNG',
                            (105 - espacioFirma / 2) - 30,
                            yPos - 18,
                            60,
                            35
                        );
                    }

                    // Firma del vicerrector
                    if (window.firmaVicerrectorData) {
                        console.log('📝 Agregando firma del vicerrector al PDF');
                        doc.addImage(
                            window.firmaVicerrectorData,
                            'PNG',
                            (105 + espacioFirma / 2) - 30,
                            yPos - 18,
                            60,
                            35
                        );
                    }
                } catch (error) {
                    console.error('❌ Error al agregar firmas:', error);
                    mostrarAlerta('Advertencia: Error al agregar las firmas al PDF', 'warning');
                }

                // Líneas decorativas bajo las firmas
                doc.setDrawColor(150, 150, 150);
                doc.setLineWidth(0.8);

                // Línea bajo firma del coordinador
                doc.line(
                    (105 - espacioFirma / 2) - 35,
                    yPos + 25,
                    (105 - espacioFirma / 2) + 35,
                    yPos + 25
                );

                // Línea bajo firma del vicerrector
                doc.line(
                    (105 + espacioFirma / 2) - 35,
                    yPos + 25,
                    (105 + espacioFirma / 2) + 35,
                    yPos + 25
                );

                // Nombres y cargos
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);

                // Nombre del coordinador
                doc.text('JUAN PABLO DIAGO RODRÍGUEZ', 105 - espacioFirma / 2, yPos + 35, {
                    align: 'center'
                });

                // Nombre del vicerrector
                doc.text('SEBASTIAN TORO', 105 + espacioFirma / 2, yPos + 35, {
                    align: 'center'
                });

                // Cargos
                yPos += 42;
                doc.setFont('helvetica', 'normal');
                doc.setFontSize(9);
                doc.setTextColor(80, 80, 80);

                // Cargo del coordinador
                doc.text(`Decano Facultad ${datos.solicitud.programa_destino}`, 105 - espacioFirma / 2, yPos, {
                    align: 'center'
                });

                // Cargo del vicerrector
                doc.text('Vicerrector Académico', 105 + espacioFirma / 2, yPos, {
                    align: 'center'
                });

                // ========================================
                // PIE DE PÁGINA PARA TODAS LAS PÁGINAS
                // ========================================

                const totalPages = doc.internal.getNumberOfPages();
                for (let i = 1; i <= totalPages; i++) {
                    doc.setPage(i);

                    // Pie de página
                    doc.setFontSize(7);
                    doc.setFont('helvetica', 'normal');
                    doc.setTextColor(100, 100, 100);

                    const footerY = 265;
                    doc.text('Lic. De Funcionamiento: 12321/79. Resolución MEN Nº. 677 de 2023. Código SNIES: 2849',
                        105, footerY, {
                            align: 'center'
                        });
                    doc.text('Sede principal – Calle 5 Nº 3 – 85 Centro. Popayán - Cauca - Colombia.', 105, footerY +
                        5, {
                            align: 'center'
                        });

                    // Número de página
                    doc.setFontSize(7);
                    doc.setFont('helvetica', 'bold');
                    doc.text(`Página ${i} de ${totalPages}`, 180, footerY + 10, {
                        align: 'right'
                    });
                }

                // Mostrar PDF en modal
                mostrarPDFEnModal(doc, datos.estudiante, esVistaVicerrector);

                console.log('✅ PDF generado exitosamente con tabla completa');
                return true;

            } catch (error) {
                console.error('❌ Error al generar PDF:', error);
                mostrarAlerta(`Error al generar PDF: ${error.message}`, 'danger');

                // Restaurar botón en caso de error
                const btnGenerar = document.getElementById('btn-generar-pdf');
                if (btnGenerar) {
                    btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar PDF Final';
                    btnGenerar.disabled = false;
                }

                return false;
            }
        }

        // ========================================
        // FUNCIONES PRINCIPALES PARA MANEJO DE PDF
        // ========================================

        /**
         * Función para mostrar PDF en modal
         */
        function mostrarPDFEnModal(doc, datosEstudiante, esVistaVicerrector) {
            try {
                // Actualizar información en el modal
                const pdfFirmaVicerrector = document.getElementById('pdf-firma-vicerrector');
                const vicerrectorPlaceholder = document.getElementById('vicerrector-firma-placeholder');

                // Mostrar firma en el modal
                if (pdfFirmaVicerrector && window.firmaVicerrectorData) {
                    pdfFirmaVicerrector.src = window.firmaVicerrectorData;
                    pdfFirmaVicerrector.style.display = 'block';
                    if (vicerrectorPlaceholder) vicerrectorPlaceholder.style.display = 'none';
                }

                // Actualizar estado en el modal
                const estadoElement = document.getElementById('pdf-estado-final');
                if (estadoElement) {
                    const estadoSelect = document.getElementById('estado');
                    if (estadoSelect) {
                        estadoElement.textContent = estadoSelect.value;
                    }
                }

                // Guardar referencia del PDF para descarga posterior
                window.pdfGenerado = doc;

                // Verificar que el PDF se guardó correctamente
                if (window.pdfGenerado && typeof window.pdfGenerado.save === 'function') {
                    console.log('✅ PDF generado correctamente y listo para descarga');

                    // Habilitar botón de descarga en el modal
                    const btnDescargar = document.getElementById('btn-descargar-pdf');
                    if (btnDescargar) {
                        btnDescargar.disabled = false;
                        btnDescargar.style.opacity = '1';
                        btnDescargar.style.cursor = 'pointer';
                    }
                }

                // Mostrar el modal
                $('#pdf-preview-modal').modal('show');

                mostrarAlerta('Vista previa del PDF generada exitosamente. Puede descargarlo desde el modal.', 'success');

            } catch (error) {
                console.error('❌ Error al mostrar PDF en modal:', error);
                mostrarAlerta('Error al mostrar la vista previa del PDF', 'danger');
            }
        }

        /**
         * Función mejorada para descargar el PDF final y guardarlo en la API
         */
        function descargarPDFFinal() {
            console.log('🔽 Función descargarPDFFinal ejecutada');

            try {
                if (!window.pdfGenerado) {
                    mostrarAlerta('No hay PDF generado para descargar. Por favor, genere primero el PDF.', 'warning');
                    console.warn('❌ No hay PDF generado en window.pdfGenerado');
                    return false;
                }

                // Validar que el PDF sea un objeto válido
                if (typeof window.pdfGenerado.save !== 'function') {
                    mostrarAlerta('El PDF generado no es válido. Genere nuevamente el PDF.', 'danger');
                    console.error('❌ El objeto PDF no tiene el método save');
                    return false;
                }

                // Obtener información para el nombre del archivo
                const estudiante = '{{ $datos['estudiante'] ?? 'estudiante' }}';
                const estado = document.getElementById('estado')?.value || 'estado';
                const fechaActual = new Date();

                // Formatear fecha para el nombre del archivo
                const year = fechaActual.getFullYear();
                const month = String(fechaActual.getMonth() + 1).padStart(2, '0');
                const day = String(fechaActual.getDate()).padStart(2, '0');
                const fechaFormateada = `${year}${month}${day}`;

                // Crear nombre de archivo limpio
                const nombreEstudianteLimpio = estudiante
                    .replace(/[^a-zA-Z0-9\s]/g, '') // Remover caracteres especiales
                    .replace(/\s+/g, '_') // Reemplazar espacios con guiones bajos
                    .toLowerCase();

                const filename =
                    `resolucion_homologacion_${nombreEstudianteLimpio}_${estado.toLowerCase()}_${fechaFormateada}.pdf`;

                console.log('📁 Descargando PDF con nombre:', filename);

                // Mostrar indicador de proceso
                mostrarAlerta('Descargando PDF y guardando en el servidor...', 'info');

                // Realizar la descarga primero
                window.pdfGenerado.save(filename);

                // Convertir el PDF a blob para enviarlo a la API
                convertirPDFABlob(window.pdfGenerado, filename)
                    .then(blob => {
                        console.log('📄 PDF convertido a blob exitosamente');
                        return guardarPDFEnAPI(blob, filename);
                    })
                    .then(response => {
                        console.log('✅ PDF guardado exitosamente en la API:', response);
                        mostrarAlerta('Resolución de homologación descargada y guardada exitosamente', 'success');

                        // Actualizar la interfaz con la información del PDF guardado
                        if (response.url_pdf_resolucion || response.ruta_pdf_resolucion) {
                            actualizarInterfazConPDF(response);
                        }
                    })
                    .catch(error => {
                        console.error('❌ Error al guardar PDF en la API:', error);
                        mostrarAlerta(
                            `PDF descargado, pero hubo un error al guardarlo en el servidor: ${error.message}`,
                            'warning');
                    });

                // Cerrar el modal
                const modal = document.getElementById('pdf-preview-modal');
                if (modal) {
                    $('#pdf-preview-modal').modal('hide');
                }

                console.log('✅ PDF descargado exitosamente:', filename);
                return true;

            } catch (error) {
                console.error('❌ Error detallado al descargar PDF:', error);
                mostrarAlerta(`Error al descargar el PDF: ${error.message || 'Error desconocido'}`, 'danger');
                return false;
            }
        }

        /**
         * Convierte el objeto PDF generado a un Blob
         * @param {Object} pdfDoc - El documento PDF generado
         * @param {string} filename - Nombre del archivo
         * @returns {Promise<Blob>} - Promesa que resuelve con el blob del PDF
         */
        function convertirPDFABlob(pdfDoc, filename) {
            return new Promise((resolve, reject) => {
                try {
                    console.log('🔄 Convirtiendo PDF a blob...');

                    // Si el PDF ya es un blob o tiene método output
                    if (typeof pdfDoc.output === 'function') {
                        const pdfBlob = pdfDoc.output('blob');
                        console.log('✅ PDF convertido usando output(blob)');
                        resolve(pdfBlob);
                    } else if (typeof pdfDoc.save === 'function') {
                        // Método alternativo para jsPDF
                        const pdfData = pdfDoc.output('arraybuffer');
                        const blob = new Blob([pdfData], {
                            type: 'application/pdf'
                        });
                        console.log('✅ PDF convertido usando arraybuffer');
                        resolve(blob);
                    } else {
                        throw new Error('El objeto PDF no tiene métodos de salida válidos');
                    }
                } catch (error) {
                    console.error('❌ Error al convertir PDF a blob:', error);
                    reject(error);
                }
            });
        }

        /**
         * Guarda el PDF en la API usando la función existente
         * @param {Blob} pdfBlob - El blob del PDF
         * @param {string} filename - Nombre del archivo
         * @returns {Promise} - Promesa con el resultado de la operación
         */
        function guardarPDFEnAPI(pdfBlob, filename) {
            console.log('💾 Guardando PDF en la API...');

            try {
                // Convertir el blob a un objeto File
                const pdfFile = new File([pdfBlob], filename, {
                    type: 'application/pdf'
                });

                console.log('📁 Archivo PDF creado:', {
                    name: pdfFile.name,
                    size: pdfFile.size,
                    type: pdfFile.type
                });

                // Obtener ID de homologación
                let homologacionId = obtenerIdHomologacion();

                if (!homologacionId) {
                    throw new Error('No se pudo obtener el ID de homologación válido');
                }

                console.log('📤 Enviando PDF a la API con ID:', homologacionId);

                // Usar la función existente para subir el PDF
                return subirSoloPDFResolucion(homologacionId, pdfFile);

            } catch (error) {
                console.error('❌ Error al preparar PDF para API:', error);
                return Promise.reject(error);
            }
        }

        /**
         * Función para obtener el ID de homologación de múltiples fuentes
         * @returns {string|null} - ID de homologación o null
         */
        function obtenerIdHomologacion() {
            let homologacionId = null;

            // Método 1: Desde URL
            const urlParams = new URLSearchParams(window.location.search);
            homologacionId = urlParams.get('id');

            if (homologacionId) {
                console.log('ID obtenido desde URL:', homologacionId);
                return homologacionId;
            }

            // Método 2: Desde elemento DOM con data attribute
            const elementoConId = document.querySelector('[data-homologacion-id]');
            if (elementoConId) {
                homologacionId = elementoConId.dataset.homologacionId;
                console.log('ID obtenido desde data attribute:', homologacionId);
                return homologacionId;
            }

            // Método 3: Desde elemento con ID específico
            const idHomologacionEl = document.getElementById('id-homologacion');
            if (idHomologacionEl) {
                homologacionId = idHomologacionEl.textContent.trim();
                if (homologacionId && homologacionId !== 'N/A') {
                    console.log('ID obtenido desde elemento específico:', homologacionId);
                    return homologacionId;
                }
            }

            // Método 4: Variable global
            if (window.homologacionId) {
                console.log('ID obtenido desde variable global:', window.homologacionId);
                return window.homologacionId;
            }

            console.warn('❌ No se pudo obtener ID de homologación desde ninguna fuente');
            return null;
        }

        /**
         * Función principal para subir solo el PDF de resolución
         * @param {string} homologacionId - ID de homologación
         * @param {File} pdfFile - Archivo PDF
         * @returns {Promise} - Promesa con el resultado
         */
        function subirSoloPDFResolucion(homologacionId, pdfFile) {
            console.log('🚀 Iniciando subida de PDF de resolución...');

            // Verificar parámetros
            if (!homologacionId) {
                return Promise.reject(new Error('ID de homologación no válido'));
            }

            if (!pdfFile || !(pdfFile instanceof File)) {
                return Promise.reject(new Error('Archivo PDF no válido'));
            }

            // Definir API_BASE_URL si no existe
            if (typeof API_BASE_URL === 'undefined') {
                window.API_BASE_URL = 'http://127.0.0.1:8000/api';
                console.log('API_BASE_URL definido:', API_BASE_URL);
            }

            console.log('📊 Detalles de la subida:', {
                endpoint: `${API_BASE_URL}/homologacion-asignaturas/${homologacionId}/pdf`,
                fileName: pdfFile.name,
                fileSize: pdfFile.size,
                fileType: pdfFile.type
            });

            // Crear FormData para el archivo
            const formData = new FormData();
            formData.append('ruta_pdf_resolucion', pdfFile);

            // Obtener el token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.warn('⚠️ No se encontró token CSRF en el documento');
            }

            // Intentar subida directa primero
            return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}/pdf`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: formData
                })
                .then(response => {
                    console.log('📡 Respuesta del servidor:', response.status, response.statusText);

                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('❌ Respuesta del servidor:', text);
                            throw new Error(`Error del servidor: ${response.status} - ${response.statusText}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Respuesta exitosa del servidor:', data);
                    return data;
                })
                .catch(error => {
                    console.error('❌ Error en subida directa:', error);

                    // Si falla la subida directa, intentar método completo
                    console.log('🔄 Intentando método alternativo...');
                    return intentarMetodoCompleto(homologacionId, pdfFile);
                });
        }

        /**
         * Método alternativo que implementa la misma lógica completa para subir el PDF
         * @param {string} homologacionId - ID de homologación
         * @param {File} pdfFile - Archivo PDF
         * @returns {Promise} - Promesa con el resultado
         */
        function intentarMetodoCompleto(homologacionId, pdfFile) {
            console.log('🔄 Utilizando método completo para subir PDF...');

            // Primero obtener datos actuales de homologaciones
            return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`Error al obtener datos: ${response.status} - ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data || !data.datos) {
                        throw new Error('No se pudieron obtener los datos de la homologación');
                    }

                    console.log('📋 Datos obtenidos del servidor:', data.datos);

                    // Preparar array válido de homologaciones basado en los datos existentes
                    let homologacionesArray = [];

                    if (data.datos.homologaciones && Array.isArray(data.datos.homologaciones)) {
                        homologacionesArray = data.datos.homologaciones.map(h => ({
                            asignatura_origen_id: h.asignatura_origen_id,
                            asignatura_destino_id: h.asignatura_destino_id || 1,
                            nota_destino: h.nota_destino || "0",
                            comentarios: h.comentarios || ''
                        }));
                    } else if (data.datos.asignaturas_origen && data.datos.asignaturas_destino) {
                        homologacionesArray = data.datos.asignaturas_origen.map((asignatura, index) => {
                            const destino = data.datos.asignaturas_destino[index] || {};
                            return {
                                asignatura_origen_id: asignatura.id,
                                asignatura_destino_id: destino.id || 1,
                                nota_destino: destino.nota_destino || "0",
                                comentarios: destino.comentarios || ''
                            };
                        });
                    }

                    // Si aún no tenemos homologaciones, crear una entrada mínima válida
                    if (homologacionesArray.length === 0) {
                        homologacionesArray = [{
                            asignatura_origen_id: 1,
                            asignatura_destino_id: 1,
                            nota_destino: "0",
                            comentarios: ''
                        }];
                    }

                    console.log('📝 Homologaciones preparadas para enviar:', homologacionesArray);

                    // Crear FormData con los datos necesarios
                    const formData = new FormData();
                    formData.append('_method', 'PUT'); // Simular PUT
                    formData.append('ruta_pdf_resolucion', pdfFile);

                    // Agregar homologaciones al FormData
                    homologacionesArray.forEach((item, index) => {
                        Object.keys(item).forEach(key => {
                            formData.append(`homologaciones[${index}][${key}]`, item[key]);
                        });
                    });

                    console.log('📤 Enviando solicitud completa...');

                    // Enviar la solicitud con el archivo PDF y los datos existentes
                    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: formData
                    });
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`Error al subir PDF: ${response.status} - ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Método completo exitoso:', data);

                    // Crear un objeto de respuesta estandarizado
                    return {
                        mensaje: data.mensaje || 'PDF actualizado correctamente',
                        ruta_pdf_resolucion: data.ruta_pdf_resolucion || (data.datos && data.datos
                            .ruta_pdf_resolucion) || '',
                        url_pdf_resolucion: data.url_pdf_resolucion || (data.ruta_pdf_resolucion ?
                            `/storage/${data.ruta_pdf_resolucion}` : '')
                    };
                });
        }

        /**
         * Función para actualizar la interfaz cuando se guarda un PDF
         * @param {Object} response - Respuesta del servidor
         */
        function actualizarInterfazConPDF(response) {
            try {
                console.log('🔄 Actualizando interfaz con datos del PDF:', response);

                // Actualizar elementos que muestren la ruta del PDF
                const elementoRutaPDF = document.getElementById('ruta-pdf-display');
                if (elementoRutaPDF && response.ruta_pdf_resolucion) {
                    elementoRutaPDF.textContent = response.ruta_pdf_resolucion;
                }

                // Actualizar enlaces de descarga si existen
                const enlaceDescarga = document.getElementById('enlace-descarga-pdf');
                if (enlaceDescarga && response.url_pdf_resolucion) {
                    enlaceDescarga.href = response.url_pdf_resolucion;
                    enlaceDescarga.style.display = 'inline-block';
                }

                // Mostrar indicador de PDF guardado
                const indicadorPDF = document.getElementById('pdf-guardado-indicator');
                if (indicadorPDF) {
                    indicadorPDF.style.display = 'block';
                    indicadorPDF.innerHTML = '<i class="fas fa-check-circle text-success"></i> PDF guardado';
                }

                console.log('✅ Interfaz actualizada correctamente');

            } catch (error) {
                console.error('❌ Error al actualizar interfaz:', error);
            }
        }

        // ========================================
        // FUNCIONES AUXILIARES Y OTRAS FUNCIONALIDADES
        // ========================================

        /**
         * Función para mostrar detalles de asignatura en el modal
         */
        function mostrarDetallesAsignatura(origenData, destinoData) {
            try {
                const asignaturaOrigen = JSON.parse(origenData);

                // Llenar datos de origen
                document.getElementById('origen-nombre').textContent = asignaturaOrigen.nombre || 'N/A';
                document.getElementById('origen-codigo').textContent = asignaturaOrigen.codigo || 'N/A';
                document.getElementById('origen-semestre').textContent = asignaturaOrigen.semestre || 'N/A';
                document.getElementById('origen-creditos').textContent = asignaturaOrigen.creditos || 'N/A';
                document.getElementById('origen-nota').textContent = asignaturaOrigen.nota_origen || asignaturaOrigen
                    .nota || 'N/A';
                document.getElementById('origen-programa').textContent = asignaturaOrigen.programa || 'N/A';

                // Llenar datos de destino si existen
                if (destinoData) {
                    const asignaturaDestino = JSON.parse(destinoData);
                    document.getElementById('destino-nombre').textContent = asignaturaDestino.nombre || 'N/A';
                    document.getElementById('destino-codigo').textContent = asignaturaDestino.codigo || 'N/A';
                    document.getElementById('destino-semestre').textContent = asignaturaDestino.semestre || 'N/A';
                    document.getElementById('destino-creditos').textContent = asignaturaDestino.creditos || 'N/A';
                    document.getElementById('destino-nota').textContent = asignaturaDestino.nota_destino || '3.0';
                } else {
                    document.getElementById('destino-nombre').textContent = 'No asignado';
                    document.getElementById('destino-codigo').textContent = 'N/A';
                    document.getElementById('destino-semestre').textContent = 'N/A';
                    document.getElementById('destino-creditos').textContent = 'N/A';
                    document.getElementById('destino-nota').textContent = 'N/A';
                }

                // Llenar contenido programático si existe
                const contenidoProgramatico = document.getElementById('contenido-programatico');
                if (asignaturaOrigen.contenido_programatico || asignaturaOrigen.contenidos_programaticos) {
                    const contenido = asignaturaOrigen.contenido_programatico ||
                        (asignaturaOrigen.contenidos_programaticos && asignaturaOrigen.contenidos_programaticos.length > 0 ?
                            asignaturaOrigen.contenidos_programaticos[0] : null);

                    if (contenido) {
                        document.getElementById('cp-tema').textContent = contenido.tema || 'N/A';
                        document.getElementById('cp-resultados').textContent = contenido.resultados_aprendizaje || 'N/A';
                        document.getElementById('cp-descripcion').textContent = contenido.descripcion || 'N/A';
                        contenidoProgramatico.style.display = 'block';
                    } else {
                        contenidoProgramatico.style.display = 'none';
                    }
                } else {
                    contenidoProgramatico.style.display = 'none';
                }

                // Mostrar el modal
                $('#subjectModal').modal('show');

            } catch (error) {
                console.error('❌ Error al mostrar detalles de asignatura:', error);
                mostrarAlerta('Error al mostrar detalles de la asignatura', 'danger');
            }
        }
function guardarEstadoHomologacion(estado, comentarios) {
    const idHomologacionEl = document.getElementById('id-homologacion');
    const idHomologacion = idHomologacionEl ? idHomologacionEl.textContent.trim() : null;

    if (!idHomologacion || idHomologacion === 'N/A') {
        mostrarAlerta('ID de homologación no válido', 'danger');
        return;
    }

    const btnGuardar = document.getElementById('btnguardarestado');
    if (btnGuardar) {
        btnGuardar.disabled = true;
        const originalText = btnGuardar.innerHTML;
        btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

        const estadoSelect = document.getElementById('estado');
        const estadoSeleccionado = estadoSelect ? estadoSelect.value : estado;

        const requestData = {
            estado: estadoSeleccionado,
            comentarios: comentarios || ''
        };

        console.log('📤 Enviando datos:', requestData);
        console.log('📍 Solicitud ID:', idHomologacion);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const backendUrl = `http://127.0.0.1:8000/api/solicitudes/${idHomologacion}/estado`;

        fetch(backendUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('📊 Status:', response.status);

                if (response.ok) {
                    return response.json();
                } else {
                    return response.text().then(errorText => {
                        console.error('❌ Respuesta del servidor:', errorText);
                        try {
                            const errorData = JSON.parse(errorText);
                            throw new Error(errorData.mensaje || `Error ${response.status}`);
                        } catch (parseError) {
                            throw new Error(`Error ${response.status}: ${response.statusText}`);
                        }
                    });
                }
            })
            .then(data => {
                console.log('✅ Respuesta exitosa:', data);
                mostrarAlerta(data.mensaje || 'Estado actualizado correctamente', 'success');

                if (estadoSelect && data.estado_actual) {
                    estadoSelect.value = data.estado_actual;
                }

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            })
            .catch(error => {
                console.error('❌ Error:', error);
                mostrarAlerta(error.message || 'Error al actualizar el estado', 'danger');
            })
            .finally(() => {
                if (btnGuardar) {
                    btnGuardar.innerHTML = originalText;
                    btnGuardar.disabled = false;
                }
            });
    }
}
        /**
         * Función para guardar el estado de la homologación
         */

        // Event Listener para el botón
        document.addEventListener('DOMContentLoaded', function() {
            const btnGuardar = document.getElementById('btnguardarestado');

            if (btnGuardar) {
                btnGuardar.addEventListener('click', function(e) {
                    e.preventDefault();

                    const estadoSelect = document.getElementById('estado');
                    const estado = estadoSelect ? estadoSelect.value : null;

                    if (!estado) {
                        mostrarAlerta('Por favor selecciona un estado', 'warning');
                        return;
                    }

                    console.log('🔄 Iniciando cambio de estado a:', estado);
                    guardarEstadoHomologacion(estado, '');
                });
            }
        });
        /**
         * Función para actualizar la UI después de guardar
         */
        function updateUI(nuevoEstado) {
            // Actualizar badge del encabezado
            const headerBadge = document.querySelector('.header-badge');
            if (headerBadge) {
                headerBadge.textContent = nuevoEstado;
                headerBadge.className =
                    `header-badge badge badge-pill status-${nuevoEstado.toLowerCase().replace(/\s+/g, '-')} px-3 py-2`;
            }

            // Actualizar selector
            const estadoSelect = document.getElementById('estado');
            if (estadoSelect) {
                estadoSelect.value = nuevoEstado;
            }

            console.log('✅ UI actualizada con nuevo estado:', nuevoEstado);
        }

        /**
         * Función para validar antes de generar PDF
         */
        function validarAntesDeGenerarPDF() {
            const estado = document.getElementById('estado').value;
            const comentarios = document.getElementById('comentarios').value.trim();

            if (!window.firmaVicerrectorData) {
                mostrarAlerta('Debe cargar su firma antes de generar el PDF', 'warning');
                return false;
            }

            if (!window.libreriasListas) {
                mostrarAlerta('Las librerías PDF aún se están cargando. Espere un momento.', 'warning');
                return false;
            }

            if (estado === 'Rechazado' && !comentarios) {
                const confirmar = confirm('¿Desea continuar sin comentarios para una homologación rechazada?');
                if (!confirmar) {
                    document.getElementById('comentarios').focus();
                    return false;
                }
            }

            return true;
        }

        // ========================================
        // INICIALIZACIÓN Y CONFIGURACIÓN
        // ========================================

        // Configurar variables globales si no existen
        window.addEventListener('DOMContentLoaded', function() {
            // Definir API_BASE_URL si no existe
            if (typeof API_BASE_URL === 'undefined') {
                window.API_BASE_URL = 'http://127.0.0.1:8000/api';
            }

            console.log('✅ Script de PDF completamente cargado y configurado');
        });

        // ========================================
        // INICIALIZACIÓN Y EVENT LISTENERS
        // ========================================

        // Inicialización cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            console.log("🚀 Inicializando vista de vicerrector...");

            // Hacer mostrarAlerta disponible globalmente desde el inicio
            window.mostrarAlerta = mostrarAlerta;

            // Configurar input de firma vicerrector
            const firmaVicerrectorInput = document.getElementById('firma-vicerrector');
            if (firmaVicerrectorInput) {
                firmaVicerrectorInput.addEventListener('change', handleFirmaVicerrectorUpload);
                console.log('✅ Event listener de firma configurado');
            }

            // Cargar firma del vicerrector si ya existe
            cargarFirmaVicerrector();

            // Configurar botón de generar PDF con validación
            const btnGenerarPDF = document.getElementById('btn-generar-pdf');
            if (btnGenerarPDF) {
                btnGenerarPDF.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('🖱️ Click en botón generar PDF detectado');

                    if (validarAntesDeGenerarPDF()) {
                        // Mostrar indicador de carga
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generando PDF...';
                        this.disabled = true;

                        generarPDF().finally(() => {
                            // Restaurar botón después de generar
                            this.innerHTML = originalText;
                            actualizarEstadoBotonPDF();
                        });
                    }
                });
                console.log('✅ Event listener de generar PDF configurado');
            }

            // Configurar botón de descargar PDF
            const btnDescargarPDF = document.getElementById('btn-descargar-pdf');
            if (btnDescargarPDF) {
                btnDescargarPDF.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('🖱️ Click en botón descargar PDF detectado');
                    descargarPDFFinal();
                });

                // Event listener adicional usando onclick como backup
                btnDescargarPDF.onclick = function(e) {
                    e.preventDefault();
                    console.log('🖱️ OnClick en botón descargar PDF detectado');
                    descargarPDFFinal();
                };
                console.log('✅ Event listeners de descargar PDF configurados');
            }

            // Configurar botón guardar estado
            const btnGuardarEstado = document.getElementById('btnguardarestado');
            if (btnGuardarEstado) {
                btnGuardarEstado.addEventListener('click', function() {
                    const estado = document.getElementById('estado').value;
                    const comentarios = document.getElementById('comentarios').value;

                    // Confirmar cambio
                    if (confirm(`¿Confirma que desea cambiar el estado a "${estado}"?`)) {
                        guardarEstadoHomologacion(estado, comentarios);
                    }
                });
                console.log('✅ Event listener de guardar estado configurado');
            }

            // Manejar clics en nombres de asignaturas
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

            // Manejar clics en botones de ver detalles
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

            console.log('✅ Event listeners de asignaturas configurados');

            // Mejorar inputs de archivo
            document.querySelectorAll('.custom-file-input').forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0]?.name || 'Seleccionar archivo...';
                    const label = this.nextElementSibling;
                    if (label) {
                        label.textContent = fileName;
                    }
                });
            });

            // Auto-expandir textarea de comentarios
            const comentariosTextarea = document.getElementById('comentarios');
            if (comentariosTextarea) {
                comentariosTextarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }

            // Prevenir envío accidental del formulario
            const form = document.getElementById('formDecision');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    mostrarAlerta('Use el botón "Guardar" para actualizar el estado', 'info');
                });
            }

            // Validaciones adicionales y mejoras de UX
            const estadoSelect = document.getElementById('estado');
            if (estadoSelect) {
                // Verificar estado cada vez que cambie
                estadoSelect.addEventListener('change', function() {
                    const estado = this.value;
                    if (estado === 'Rechazado' && !window.firmaVicerrectorData) {
                        mostrarAlerta('Para rechazar la homologación, debe cargar su firma', 'warning');
                    }
                });
            }

            // Escuchar cuando las librerías estén listas
            window.addEventListener('pdfLibrariesLoaded', function() {
                console.log('📚 Librerías PDF cargadas, actualizando botones...');
                actualizarEstadoBotonPDF();
                mostrarAlerta('Sistema PDF listo para generar documentos', 'success');
            });

            // Tooltips informativos
            const tooltips = [{
                    selector: '#estado',
                    title: 'Seleccione Aprobado para autorizar la homologación o Rechazado para denegarla'
                },
                {
                    selector: '#comentarios',
                    title: 'Agregue observaciones o justificaciones para su decisión'
                },
                {
                    selector: '#firma-vicerrector',
                    title: 'Cargue su firma digital para validar oficialmente la resolución'
                },
                {
                    selector: '#btn-generar-pdf',
                    title: 'Genere el documento oficial de resolución de homologación'
                }
            ];

            tooltips.forEach(tooltip => {
                const elemento = document.querySelector(tooltip.selector);
                if (elemento) {
                    elemento.setAttribute('title', tooltip.title);
                    elemento.setAttribute('data-toggle', 'tooltip');
                    elemento.setAttribute('data-placement', 'top');
                }
            });

            // Inicializar tooltips si Bootstrap está disponible
            if (typeof $('[data-toggle="tooltip"]').tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip();
            }

            // Capturar datos iniciales después de que todo esté configurado
            setTimeout(() => {
                capturarTodosLosDatos();
                console.log('📊 Datos iniciales capturados');
            }, 1500);

            // Mensaje de bienvenida
            setTimeout(() => {
                mostrarAlerta(
                    'Vista del vicerrector cargada. Esperando que se carguen las librerías PDF...',
                    'info');
            }, 1000);

            console.log("✅ Vista de vicerrector inicializada correctamente");
        });

        // ========================================
        // FUNCIONES GLOBALES PARA DEBUGGING
        // ========================================

        // Hacer las funciones disponibles globalmente para debugging
        window.descargarPDFFinal = descargarPDFFinal;
        window.generarPDF = generarPDF;
        window.getJsPDF = getJsPDF;
        window.capturarTodosLosDatos = capturarTodosLosDatos;
        window.debugMostrarDatos = debugMostrarDatos;
        window.exportarDatosJSON = exportarDatosJSON;

        // Función de utilidad para debugging completo
        window.debugCompleto = function() {
            console.clear();
            console.log('🔍 === DEBUG COMPLETO DEL SISTEMA ===');

            const datos = debugMostrarDatos();
            const libreriasEstado = {
                jsPDF: !!getJsPDF(),
                autoTable: typeof document.createElement('canvas').getContext === 'function',
                libreriasListas: window.libreriasListas
            };
            const firmasEstado = {
                vicerrector: !!window.firmaVicerrectorData,
                coordinador: !!window.firmaCoordinadorData
            };

            console.log('📚 Estado de librerías:', libreriasEstado);
            console.log('✍️ Estado de firmas:', firmasEstado);
            console.log('📄 PDF generado:', !!window.pdfGenerado);

            return {
                datos,
                libreriasEstado,
                firmasEstado,
                pdfGenerado: !!window.pdfGenerado
            };
        };

        console.log('🎯 Script completo de vicerrector cargado exitosamente');
        console.log('💡 Usa window.debugCompleto() para ver el estado completo del sistema');
    </script>
@endsection

@extends('admin.layouts.appadmin')

@section('content')
<div class="container-fluid py-4" style="background: linear-gradient(135deg, #f5f7ff 0%, #eef1ff 100%);">
<div class="row justify-content-center">
    <div class="col-lg-18">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-md-4 p-3">

    <style>
        /* Variables para personalización institucional */
        :root {
            --primary-color: #1659a0;
            --accent-color: #f0cd45;
            --light-bg: #f8f9fa;
            --text-color: #495057;
        }

        /* Estilos para el encabezado */
        .header-section {
            margin-bottom: 40px;
        }

        .header-section h1 {
            color: var(--primary-color);
            font-weight: 800;
            position: relative;
            display: inline-block;
            padding-bottom: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header-section h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .header-section p {
            font-size: 1.1rem;
            color: #6c757d;
            max-width: 600px;
            margin: 15px auto 0;
        }

        /* Estilos para el stepper */
        .stepper-wrapper {
            position: relative;
            margin-bottom: 40px;
        }

        .progress-container {
            position: relative;
            padding: 0 20px;
        }

        .progress-bar-container {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            position: relative;
            z-index: 1;
            overflow: hidden;
            margin: 0 20px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            width: 33.33%;
            transition: width 0.8s cubic-bezier(0.65, 0, 0.35, 1);
            position: relative;
            border-radius: 3px;
        }

        .stepper-circles {
            position: absolute;
            width: 100%;
            top: -17px;
            left: 0;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
        }

        .step {
            text-align: center;
            position: relative;
            width: 33.3%;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            background-color: white;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 3;
        }

        .step-circle i {
            color: #adb5bd;
            font-size: 16px;
            transition: all 0.5s ease;
        }

        .step-label {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            font-weight: 500;
            color: #6c757d;
            white-space: nowrap;
            transition: all 0.5s ease;
            opacity: 0.7;
            margin-top: 10px;
        }

        /* Estilo para paso inactivo */
        .step.inactive .step-circle {
            transform: scale(0.85);
        }

        /* Estilo para paso activo */
        .step.active .step-circle {
            border-color: var(--primary-color);
            background-color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 86, 179, 0.25);
        }

        .step.active .step-circle i {
            color: var(--primary-color);
        }

        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
            opacity: 1;
        }

        /* Estilo para paso completado */
        .step.completed .step-circle {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: scale(1);
        }

        .step.completed .step-circle i {
            color: white;
        }

        .step.completed .step-label {
            color: var(--primary-color);
            opacity: 0.9;
        }

        /* Animación de pulso para el paso activo */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(31, 67, 105, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(0, 86, 179, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 86, 179, 0);
            }
        }

        .step.active .step-circle {
            animation: pulse 2s infinite;
        }

        /* Animación para entrada de elementos */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fadeInUp {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>

<!-- Header institucional -->
<div class="container-fluid py-3 mb-4" style="background-color: #003366; font-family: 'Source Sans Pro', sans-serif;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
          <class="img-fluid" style="max-height: 60px;">
            </div>
         <div class="col-md-8 text-center">
    <h1 class="fw-bold mb-0 text-white fs-3">Sistema de homologaciones</h1>
    <p class="lead mb-0 text-white fs-5">Crear asignatura</p>
</div>
        </div>
    </div>
</div>


    <!-- Stepper -->
    <div class="stepper-wrapper">
        <div class="progress-container">
            <div class="progress-bar-container">
                <div id="progress-bar-fill" class="progress-bar-fill"></div>
            </div>
            <div class="stepper-circles">
                <div id="step-1" class="step active" data-step="1">
                    <div class="step-circle">
                        <i class="fas fa-university"></i>
                    </div>
                    <span class="step-label">Selección</span>
                </div>
                <div id="step-2" class="step inactive" data-step="2">
                    <div class="step-circle">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span class="step-label">Información Básica</span>
                </div>
                <div id="step-3" class="step inactive" data-step="3">
                    <div class="step-circle">
                        <i class="fas fa-book"></i>
                    </div>
                    <span class="step-label">Contenido Programático</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Step content -->
    <div class="step-content fade-in-up">
                    <!-- Paso 1: Selección de Institución y Programa -->
                    <div id="step1-content" class="active-step">
                        <div class="row g-4">
                            <!-- Columna de Institución -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h5 class="mb-0 fw-bold text-primary">Institución</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control" placeholder="Buscar institución..." id="buscar-institucion">
                                            </div>
                                            <select id="selector-instituciones" class="form-select mb-3">
                                                <option value="">-- Seleccione una institución --</option>
                                            </select>
                                            <button id="btn-cargar-instituciones" class="btn btn-outline-primary w-100">
                                                <i class="fas fa-sync-alt me-2"></i>Recargar instituciones
                                            </button>
                                        </div>
                                        <div id="instituciones-container" class="mt-3 custom-scrollbar" style="max-height: 320px; overflow-y: auto;">
                                            <!-- Aquí se cargan las tarjetas de instituciones -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna de Programa -->
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-light py-3">
                                        <h5 class="mb-0 fw-bold text-primary">Programa</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                                <input type="text" class="form-control" placeholder="Buscar programa..." id="buscar-programa">
                                            </div>
                                            <select id="selector-programas" class="form-select mb-3">
                                                <option value="">-- Seleccione un programa --</option>
                                            </select>
                                            <button id="btn-cargar-programas" class="btn btn-outline-primary w-100">
                                                <i class="fas fa-sync-alt me-2"></i>Recargar programas
                                            </button>
                                        </div>
                                        <div id="programas-container" class="mt-3 custom-scrollbar" style="max-height: 320px; overflow-y: auto;">
                                            <!-- Aquí se cargan las tarjetas de programas -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-secondary px-4" disabled>
                                <i class="fas fa-arrow-left me-2"></i>Anterior
                            </button>
                            <button id="btn-step-1" class="btn btn-primary px-4" disabled>
                                Siguiente<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Información Básica de la Asignatura -->
                    <div id="step2-content" class="d-none">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h4 class="mb-0 text-primary fw-bold">Información Básica de la Asignatura</h4>
                            </div>
                            <div class="card-body">
                                <form id="form-info-basica">
                                    <!-- Campo oculto para programa_id -->
                                    <input type="hidden" id="programa_id" name="programa_id">

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                                                <label for="nombre" class="small">Nombre de la Asignatura*</label>
                                                <div class="invalid-feedback">Por favor ingrese el nombre de la asignatura.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="codigo_asignatura" name="codigo_asignatura" placeholder="Código" required>
                                                <label for="codigo_asignatura" class="small">Código de Asignatura*</label>
                                                <div class="invalid-feedback">Por favor ingrese el código de la asignatura.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="tipo" name="tipo" required>
                                                    <option value="">Seleccione un tipo</option>
                                                    <option value="Competencia">Competencia</option>
                                                    <option value="Materia">Materia</option>
                                                </select>
                                                <label for="tipo" class="small">Tipo*</label>
                                                <div class="invalid-feedback">Por favor seleccione un tipo.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" id="semestre" name="semestre" min="1" max="12" placeholder="Semestre" required>
                                                <label for="semestre" class="small">Semestre*</label>
                                                <div class="invalid-feedback">Por favor ingrese un valor entre 1 y 12.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="modalidad" name="modalidad" required>
                                                    <option value="">Seleccione una modalidad</option>
                                                    <option value="Teórico">Teórico</option>
                                                    <option value="Práctico">Práctico</option>
                                                    <option value="Teórico-Práctico">Teórico-Práctico</option>
                                                </select>
                                                <label for="modalidad" class="small">Modalidad*</label>
                                                <div class="invalid-feedback">Por favor seleccione una modalidad.</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" id="metodologia" name="metodologia" required>
                                                    <option value="">Seleccione una metodología</option>
                                                    <option value="Presencial">Presencial</option>
                                                    <option value="Virtual">Virtual</option>
                                                    <option value="Híbrida">Híbrida</option>
                                                </select>
                                                <label for="metodologia" class="small">Metodología*</label>
                                                <div class="invalid-feedback">Por favor seleccione una metodología.</div>
                                            </div>
                                        </div>

                                        <!-- Campos específicos SENA -->
                                        <div id="campos-sena" class="col-md-8">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" id="horas_sena" name="horas_sena" min="1" placeholder="Horas SENA">
                                                <label for="horas_sena" class="small">Horas SENA*</label>
                                                <div class="invalid-feedback">Por favor ingrese un valor válido mayor a 0.</div>
                                            </div>
                                        </div>

                                        <!-- Campos NO-SENA -->
                                        <div id="campos-no-sena" class="row g-3 mx-0 w-100">
                                            <div class="col-lg-3 col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="creditos" name="creditos" min="1" placeholder="Créditos">
                                                    <label for="creditos" class="small">Créditos*</label>
                                                    <div class="invalid-feedback">Este campo es requerido y debe ser mayor a 0.</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="tiempo_presencial" name="tiempo_presencial" min="1" placeholder="Tiempo Presencial">
                                                    <label for="tiempo_presencial" class="small">Tiempo Presencial (h)*</label>
                                                    <div class="invalid-feedback">Este campo es requerido y debe ser mayor a 0.</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="tiempo_independiente" name="tiempo_independiente" min="1" placeholder="Tiempo Independiente">
                                                    <label for="tiempo_independiente" class="small">Tiempo Independiente (h)*</label>
                                                    <div class="invalid-feedback">Este campo es requerido y debe ser mayor a 0.</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="form-floating mb-3">
                                                    <input type="number" class="form-control" id="horas_totales_semanales" name="horas_totales_semanales" min="1" placeholder="Horas Totales">
                                                    <label for="horas_totales_semanales" class="small">Horas Totales Sem.*</label>
                                                    <div class="invalid-feedback">Este campo es requerido y debe ser mayor a 0.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button id="btn-back-step-1" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Anterior
                            </button>
                            <button id="btn-step-2" class="btn btn-primary px-4">
                                Siguiente<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Paso 3: Contenido Programático -->
                    <div id="step3-content" class="d-none">
                        <div class="col-12">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h4 class="mb-0 text-primary fw-bold">Contenido Programático</h4>
                            </div>
                            <div class="card-body">
                                <form id="form-asignatura">
                                    <!-- Campo oculto para programa_id -->
                                    <input type="hidden" id="programa_id_final" name="programa_id">

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="tema" name="tema" placeholder="Tema">
                                                <label for="tema" class="small">Tema</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <textarea class="form-control" id="resultados_aprendizaje" name="resultados_aprendizaje" placeholder="Resultados de Aprendizaje" style="height: 150px"></textarea>
                                                <label for="resultados_aprendizaje" class="small">Resultados de Aprendizaje</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" style="height: 150px"></textarea>
                                                <label for="descripcion" class="small">Descripción</label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button id="btn-back-step-2" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Anterior
                            </button>
                            <button id="btn-submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-2"></i>Guardar Asignatura
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Agregar SweetAlert2 para mensajes -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Agregar FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* Estilos para steps circulares */
    .step-circle {
        width: 50px;
        height: 50px;
        background-color: #f0f0f0;
        color: #6c757d;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        z-index: 2;
        border: 2px solid white;
    }

    .step-circle.active {
        background: linear-gradient(135deg, #0e1e7c, #0f1191);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 0 0 4px rgba(15, 17, 145, 0.2);
    }

    .step-label {
        color: #6c757d;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .step-label.active {
        color: #0e1e7c;
        font-weight: 600;
    }

    /* Para la línea que conecta los círculos */
    .progress {
        position: absolute;
        top: 25px;
        width: 100%;
        z-index: 1;
    }

    /* Estilos para tarjetas seleccionables */
    .selectable-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
        background-color: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        margin-bottom: 10px;
        border-radius: 8px;
    }

    .selectable-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        border-color: rgba(15, 17, 145, 0.3);
    }

    .selectable-card.selected {
        background-color: rgba(15, 17, 145, 0.05);
        border-color: #0f1191;
        border-left-width: 4px;
    }

    /* Fondo y colores */
    .bg-primary-light {
        background-color: rgba(15, 17, 145, 0.1);
    }


    .btn-primary {
        background: #4b56e7;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #4b56e7;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(15, 17, 145, 0.3);
    }

    .btn-success {
        background: #28a745;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    }

    /* Formularios y campos */
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #ced4da;
        height: 58px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4b56e7;
        box-shadow: 0 0 0 0.25rem rgba(15, 17, 145, 0.25);
    }

    .form-floating > .form-control,
    .form-floating > .form-select {
        height: 58px;
    }

    .form-floating > textarea.form-control {
        height: auto;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .form-control.is-valid {
        border-color: #28a745;
        background-image: none;
    }

    /* Tarjetas uniformes */
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        height: 100%;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }

    /* Scrollbar personalizada */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #4b56e7 #f5f5f5;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f5f5f5;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #4b56e7;
        border-radius: 10px;
    }

    /* Animaciones */
    .fade-in-up {
        animation: fadeInUp 0.4s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive ajustes */
    @media (max-width: 768px) {
        .step-circle {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }

        .progress {
            top: 22px;
        }

        .form-control, .form-select {
            height: 52px;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: 52px;
        }
    }

    @media (max-width: 576px) {
        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 0.85rem;
        }

        .progress {
            top: 20px;
        }

        .form-control, .form-select {
            height: 48px;
            font-size: 0.9rem;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: 48px;
        }
    }
</style>

<script>
  // Variables globales
let institucionSeleccionada = null;
let programaSeleccionado = null;
let currentStep = 1;
const API_URL = 'https://homologacionesback.educarenemociones.com/api';
let todasLasInstituciones = [];
let todosLosProgramas = [];

// DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Intentar cargar instituciones automáticamente
    cargarInstituciones();

    // Configurar búsqueda de instituciones
    document.getElementById('buscar-institucion').addEventListener('input', function() {
        filtrarInstituciones(this.value);
    });

    // Configurar búsqueda de programas
    document.getElementById('buscar-programa').addEventListener('input', function() {
        filtrarProgramas(this.value);
    });

    // Eventos para botones de recarga
    document.getElementById('btn-cargar-instituciones').addEventListener('click', cargarInstituciones);
    document.getElementById('btn-cargar-programas').addEventListener('click', function() {
        if (institucionSeleccionada) {
            cargarProgramas();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Primero debe seleccionar una institución',
                confirmButtonColor: '#0f1191'
            });
        }
    });

    // Evento de cambio para selectores
    document.getElementById('selector-instituciones').addEventListener('change', function() {
        const id = this.value;
        if (id) {
            const institucion = todasLasInstituciones.find(i => i.id_institucion == id);
            if (institucion) {
                institucionSeleccionada = {
                    id: id,
                    tipo: institucion.tipo,
                    nombre: institucion.nombre
                };

                console.log('Institución seleccionada desde selector:', institucionSeleccionada);

                // Actualizar tarjetas visuales
                document.querySelectorAll('.institucion-card').forEach(card => {
                    card.classList.remove('selected');
                    if(card.getAttribute('data-id') == id) {
                        card.classList.add('selected');
                    }
                });

                // Cargar programas automáticamente al seleccionar institución
                cargarProgramas();

                document.getElementById('btn-step-1').disabled = !programaSeleccionado;
            }
        } else {
            institucionSeleccionada = null;
            document.getElementById('btn-step-1').disabled = true;
        }
    });

    document.getElementById('selector-programas').addEventListener('change', function() {
        const id = this.value;
        if (id) {
            programaSeleccionado = { id: id };
            console.log('Programa seleccionado desde selector:', programaSeleccionado);

            // Actualizar tarjetas visuales
            document.querySelectorAll('.programa-card').forEach(card => {
                card.classList.remove('selected');
                if(card.getAttribute('data-id') == id) {
                    card.classList.add('selected');
                }
            });

            document.getElementById('btn-step-1').disabled = !institucionSeleccionada;
        } else {
            programaSeleccionado = null;
            document.getElementById('btn-step-1').disabled = true;
        }
    });

    // Eventos para navegación entre pasos
    document.getElementById('btn-step-1').addEventListener('click', irAPaso2);
    document.getElementById('btn-back-step-1').addEventListener('click', volverAPaso1);
    document.getElementById('btn-step-2').addEventListener('click', irAPaso3);
    document.getElementById('btn-back-step-2').addEventListener('click', volverAPaso2);

    // Evento submit del formulario
    document.getElementById('btn-submit').addEventListener('click', function(e) {
        e.preventDefault();
        guardarAsignatura();
    });

    // Mostrar/ocultar campos según tipo de institución
    document.addEventListener('changeInstitucion', function(e) {
        mostrarCamposSegunInstitucion();
    });

    // Configurar validaciones para todos los campos
    configurarValidaciones();
});

// Función para configurar todas las validaciones
function configurarValidaciones() {
    // Agregar validación a todos los campos del formulario
    const formInfoBasica = document.getElementById('form-info-basica');
    const campos = formInfoBasica.querySelectorAll('input, select, textarea');

    campos.forEach(campo => {
        // Validar al perder el foco
        campo.addEventListener('blur', function() {
            validarCampo(this);
        });

        // Validar al cambiar (para selects)
        campo.addEventListener('change', function() {
            validarCampo(this);
        });

        // Validación especial para campos de texto
        if (campo.type === 'text') {
            campo.addEventListener('input', function() {
                // Si el campo ya estaba marcado como inválido, revalidar en cada keystroke
                if (this.classList.contains('is-invalid')) {
                    validarCampo(this);
                }
            });
        }

        // Validación especial para campos numéricos
        if (campo.type === 'number') {
            campo.addEventListener('input', function() {
                // Prevenir valores negativos en los campos numéricos
                if (this.value && parseFloat(this.value) < 0) {
                    this.value = 0;
                }

                // Revalidar si estaba marcado como inválido
                if (this.classList.contains('is-invalid')) {
                    validarCampo(this);
                }
            });
        }
    });

    // Configurar cálculo automático de horas totales
    const tiempoPresencial = document.getElementById('tiempo_presencial');
    const tiempoIndependiente = document.getElementById('tiempo_independiente');
    const horasTotales = document.getElementById('horas_totales_semanales');

    if (tiempoPresencial && tiempoIndependiente && horasTotales) {
        tiempoPresencial.addEventListener('input', calcularHorasTotales);
        tiempoPresencial.addEventListener('change', calcularHorasTotales);
        tiempoIndependiente.addEventListener('input', calcularHorasTotales);
        tiempoIndependiente.addEventListener('change', calcularHorasTotales);
    }
}

// Funciones para el stepper (navegación entre pasos)
function nextStep(currentStepNum) {
    const totalSteps = 3;
    if (currentStepNum < totalSteps) {
        // Actualizar el paso actual como completado
        document.getElementById(`step-${currentStepNum}`).classList.remove('active');
        document.getElementById(`step-${currentStepNum}`).classList.add('completed');

        // Activar el siguiente paso
        document.getElementById(`step-${currentStepNum + 1}`).classList.remove('inactive');
        document.getElementById(`step-${currentStepNum + 1}`).classList.add('active');

        // Actualizar la barra de progreso
        const progressBar = document.getElementById('progress-bar-fill');
        progressBar.style.width = `${(currentStepNum + 1) * (100 / totalSteps)}%`;

        // Actualizar la variable global currentStep
        currentStep = currentStepNum + 1;
    }
}

function prevStep(currentStepNum) {
    if (currentStepNum > 1) {
        // Actualizar el paso actual como inactivo
        document.getElementById(`step-${currentStepNum}`).classList.remove('active');
        document.getElementById(`step-${currentStepNum}`).classList.add('inactive');

        // Activar el paso anterior
        document.getElementById(`step-${currentStepNum - 1}`).classList.remove('completed');
        document.getElementById(`step-${currentStepNum - 1}`).classList.add('active');

        // Actualizar la barra de progreso
        const progressBar = document.getElementById('progress-bar-fill');
        progressBar.style.width = `${(currentStepNum - 1) * (100 / 3)}%`;

        // Actualizar la variable global currentStep
        currentStep = currentStepNum - 1;
    }
}

// Función para mostrar campos según tipo de institución
function mostrarCamposSegunInstitucion() {
    if (institucionSeleccionada && institucionSeleccionada.tipo === 'SENA') {
        document.getElementById('campos-sena').style.display = 'block';
        document.getElementById('campos-no-sena').style.display = 'none';
        document.getElementById('horas_sena').required = true;

       // Resetear campos no-SENA
        document.getElementById('creditos').required = false;
        document.getElementById('creditos').value = '';
        document.getElementById('tiempo_presencial').required = false;
        document.getElementById('tiempo_presencial').value = '';
        document.getElementById('tiempo_independiente').required = false;
        document.getElementById('tiempo_independiente').value = '';
        document.getElementById('horas_totales_semanales').required = false;
        document.getElementById('horas_totales_semanales').value = '';

        // Quitar clases de validación
        document.querySelectorAll('#campos-no-sena input').forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });
    } else {
        document.getElementById('campos-sena').style.display = 'none';
        document.getElementById('campos-no-sena').style.display = 'flex';
        document.getElementById('horas_sena').required = false;
        document.getElementById('horas_sena').value = '';
        document.getElementById('horas_sena').classList.remove('is-valid', 'is-invalid');

        // Hacer obligatorios los campos para instituciones no-SENA
        document.getElementById('creditos').required = true;
        document.getElementById('tiempo_presencial').required = true;
        document.getElementById('tiempo_independiente').required = true;
        document.getElementById('horas_totales_semanales').required = true;
    }
}

// Función para filtrar instituciones
function filtrarInstituciones(texto) {
    const tarjetas = document.querySelectorAll('.institucion-card');

    texto = texto.toLowerCase().trim();

    tarjetas.forEach(tarjeta => {
        const nombre = tarjeta.getAttribute('data-nombre').toLowerCase();
        const tipo = tarjeta.getAttribute('data-tipo').toLowerCase();
        const contenedor = tarjeta.closest('.col-md-6') || tarjeta.parentElement;

        if (nombre.includes(texto) || tipo.includes(texto)) {
            contenedor.style.display = '';
        } else {
            contenedor.style.display = 'none';
        }
    });
}

// Función para filtrar programas
function filtrarProgramas(texto) {
    const tarjetas = document.querySelectorAll('.programa-card');

    texto = texto.toLowerCase().trim();

    tarjetas.forEach(tarjeta => {
        const nombre = tarjeta.getAttribute('data-nombre') ? tarjeta.getAttribute('data-nombre').toLowerCase() : '';
        const contenedor = tarjeta.closest('.col-md-6') || tarjeta.parentElement;

        if (nombre.includes(texto)) {
            contenedor.style.display = '';
        } else {
            contenedor.style.display = 'none';
        }
    });
}

// Función de validación de campos mejorada
function validarCampo(campo) {
    let esValido = true;

    // Limpiar clases anteriores para empezar con un estado limpio
    campo.classList.remove('is-valid', 'is-invalid');

    // Para el campo de texto del nombre, hacemos una validación especial
    if (campo.id === 'nombre') {
        // Solo consideramos inválido si está vacío y es requerido
        if (campo.hasAttribute('required') && !campo.value.trim()) {
            campo.classList.add('is-invalid');
            return false;
        }
        // Si tiene contenido, lo marcamos como válido
        if (campo.value.trim()) {
            campo.classList.add('is-valid');
            return true;
        }
        // Si no es requerido y está vacío, no marcamos nada
        return true;
    }

    // Para el resto de campos, aplicamos las validaciones generales

    // Validación de campo requerido vacío
    if (campo.hasAttribute('required') && !campo.value.trim()) {
        campo.classList.add('is-invalid');
        return false;
    }

    // Si el campo tiene valor, validamos según su tipo
    if (campo.value.trim()) {
        // Validación para campos numéricos
        if (campo.type === 'number') {
            // Validar valor mínimo
            if (campo.min && Number(campo.value) < Number(campo.min)) {
                campo.classList.add('is-invalid');
                return false;
            }
            // Validar valor máximo
            if (campo.max && Number(campo.value) > Number(campo.max)) {
                campo.classList.add('is-invalid');
                return false;
            }
        }

        // Para campos con valor válido
        campo.classList.add('is-valid');
        return true;
    }

    // Campo no requerido y vacío
    return true;
}

// Función para validar todo el formulario
function validarFormulario() {
    const formInfoBasica = document.getElementById('form-info-basica');
    const camposRequeridos = formInfoBasica.querySelectorAll('[required]');
    let formValido = true;

    camposRequeridos.forEach(campo => {
        if (!validarCampo(campo)) {
            formValido = false;
            // Hacer foco en el primer campo inválido
            if (campo.classList.contains('is-invalid') && !document.activeElement.isEqualNode(campo)) {
                setTimeout(() => campo.focus(), 100);
            }
        }
    });

    return formValido;
}

// Función para calcular automáticamente las horas totales
function calcularHorasTotales() {
    const tiempoPresencial = parseInt(document.getElementById('tiempo_presencial').value) || 0;
    const tiempoIndependiente = parseInt(document.getElementById('tiempo_independiente').value) || 0;

    if (tiempoPresencial >= 0 || tiempoIndependiente >= 0) {
        const horasTotales = tiempoPresencial + tiempoIndependiente;
        document.getElementById('horas_totales_semanales').value = horasTotales;

        // Validar el campo actualizado
        validarCampo(document.getElementById('horas_totales_semanales'));
    }
}

// Función para cargar las instituciones desde la API
async function cargarInstituciones() {
    try {
        // Mostrar indicador de carga
        const contenedor = document.getElementById('instituciones-container');
        const selector = document.getElementById('selector-instituciones');

        contenedor.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Cargando instituciones...</p></div>';
        selector.innerHTML = '<option value="">Cargando instituciones...</option>';

        const response = await fetch(`${API_URL}/instituciones`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            // Sin caché para forzar nueva petición
            cache: 'no-store'
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const instituciones = await response.json();
        console.log('Instituciones cargadas:', instituciones);

        // Guardar todas las instituciones globalmente
        todasLasInstituciones = instituciones;

        // Limpiar contenedor y selector
        contenedor.innerHTML = '';
        selector.innerHTML = '<option value="">-- Seleccione una institución --</option>';

        // Si no hay instituciones
        if (!instituciones || instituciones.length === 0) {
            contenedor.innerHTML = '<div class="alert alert-warning p-3 rounded-3"><i class="fas fa-exclamation-triangle me-2"></i>No se encontraron instituciones disponibles.</div>';
            return;
        }

        // Llenar el selector y ordenar alfabéticamente
        const institucionesOrdenadas = [...instituciones].sort((a, b) => a.nombre.localeCompare(b.nombre));
        institucionesOrdenadas.forEach(institucion => {
            const option = document.createElement('option');
            option.value = institucion.id_institucion;
            option.textContent = institucion.nombre;
            selector.appendChild(option);
        });

        // Crear tarjetas para cada institución
        const row = document.createElement('div');
        row.className = 'row g-2';
        contenedor.appendChild(row);

        instituciones.forEach(institucion => {
            const col = document.createElement('div');
            col.className = 'col-md-6 mb-2';
            col.innerHTML = `
                <div class="selectable-card institucion-card position-relative p-3 rounded-3 cursor-pointer"
                     data-id="${institucion.id_institucion}"
                     data-tipo="${institucion.tipo || ''}"
                     data-nombre="${institucion.nombre}">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light rounded-circle me-3 p-2" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-university text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">${institucion.nombre}</h6>
                            <p class="text-muted mb-0 small">${institucion.ciudad || 'Colombia'}</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge ${institucion.tipo && institucion.tipo.toLowerCase().includes('públic') ? 'bg-info' : 'bg-warning text-dark'} me-1">
                            ${institucion.tipo || 'No especificado'}
                        </span>
                        ${institucion.acreditada ? '<span class="badge bg-success">Acreditada</span>' : ''}
                    </div>
                </div>
            `;
            row.appendChild(col);

            // Agregar evento click para seleccionar institución
            col.querySelector('.institucion-card').addEventListener('click', function() {
                seleccionarInstitucion(this);

                // También seleccionar en el dropdown para mantener sincronizado
                selector.value = this.getAttribute('data-id');

                // Disparar evento para actualizar campos según tipo de institución
                document.dispatchEvent(new CustomEvent('changeInstitucion'));

                // Cargar programas automáticamente
                cargarProgramas();
            });
        });
    } catch (error) {
        console.error('Error al cargar instituciones:', error);

        const contenedor = document.getElementById('instituciones-container');
        const selector = document.getElementById('selector-instituciones');

        contenedor.innerHTML = `
            <div class="alert alert-danger p-3 rounded-3">
                <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar instituciones</h6>
                <p class="mb-0 small">${error.message}</p>
            </div>
        `;

        selector.innerHTML = '<option value="">Error al cargar instituciones</option>';

        // Mostrar mensaje con SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cargar instituciones: ' + error.message,
            footer: 'Intente recargar la página o use el botón "Recargar instituciones"',
            confirmButtonColor: '#0f1191'
        });
    }
}

// Función para seleccionar una institución
function seleccionarInstitucion(elemento) {
    // Quitar selección anterior
    document.querySelectorAll('.institucion-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Marcar la seleccionada
    elemento.classList.add('selected');

    // Guardar datos de institución seleccionada
    institucionSeleccionada = {
        id: elemento.getAttribute('data-id'),
        tipo: elemento.getAttribute('data-tipo'),
        nombre: elemento.getAttribute('data-nombre')
    };

    console.log('Institución seleccionada desde tarjeta:', institucionSeleccionada);

    // Habilitar botón siguiente si también hay programa seleccionado
    document.getElementById('btn-step-1').disabled = !programaSeleccionado;
}

// Función para ir al paso 2 (información básica)
function irAPaso2() {
    if (!institucionSeleccionada) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar una institución primero',
            confirmButtonColor: '#0f1191'
        });
        return;
    }

    if (!programaSeleccionado) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar un programa primero',
            confirmButtonColor: '#0f1191'
        });
        return;
    }

    // Actualizar UI para paso 2
    document.getElementById('step1-content').classList.add('d-none');
    document.getElementById('step2-content').classList.remove('d-none');

    // Actualizar el stepper visual
    nextStep(1);

    // Establecer el ID del programa en el campo oculto
    document.getElementById('programa_id').value = programaSeleccionado.id;

    // Configurar formulario según tipo de institución
    mostrarCamposSegunInstitucion();

    // Focus en el primer campo
    setTimeout(() => {
        document.getElementById('nombre').focus();
    }, 300);
}

// Función para volver al paso 1
function volverAPaso1() {
    document.getElementById('step2-content').classList.add('d-none');
    document.getElementById('step1-content').classList.remove('d-none');

    // Actualizar el stepper visual
    prevStep(2);
}

// Función para ir al paso 3 (contenido programático)
function irAPaso3() {
    // Validar formulario de información básica
    if (!validarFormulario()) {
        Swal.fire({
            icon: 'error',
            title: 'Error de validación',
            text: 'Por favor, complete correctamente todos los campos requeridos.',
            confirmButtonColor: '#0f1191'
        });
        return;
    }

    // Actualizar UI para paso 3
    document.getElementById('step2-content').classList.add('d-none');
    document.getElementById('step3-content').classList.remove('d-none');

    // Actualizar el stepper visual
    nextStep(2);

    // Establecer el ID del programa en el campo oculto final
    document.getElementById('programa_id_final').value = programaSeleccionado.id;

    // Focus en el primer campo
    setTimeout(() => {
        document.getElementById('tema').focus();
    }, 300);
}

// Función para volver al paso 2
function volverAPaso2() {
    document.getElementById('step3-content').classList.add('d-none');
    document.getElementById('step2-content').classList.remove('d-none');

    // Actualizar el stepper visual
    prevStep(3);
}

// Función para cargar programas de una institución
async function cargarProgramas() {
    try {
        if (!institucionSeleccionada || !institucionSeleccionada.nombre) {
            throw new Error('No se ha seleccionado una institución válida');
        }

        // Mostrar indicador de carga
        const contenedor = document.getElementById('programas-container');
        const selector = document.getElementById('selector-programas');

        contenedor.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Cargando programas...</p></div>';
        selector.innerHTML = '<option value="">Cargando programas...</option>';

        // Obtener todos los programas
        const response = await fetch(`${API_URL}/programas`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            cache: 'no-store' // Sin caché para forzar nueva petición
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const programas = await response.json();
        console.log('Todos los programas:', programas);
        console.log('Nombre de institución a filtrar:', institucionSeleccionada.nombre);

        // Guardar todos los programas globalmente
        todosLosProgramas = programas;

        // Filtrar programas por nombre EXACTO de institución
        const programasFiltrados = programas.filter(programa => {
            return programa.institucion && programa.institucion === institucionSeleccionada.nombre;
        });

        console.log('Programas filtrados:', programasFiltrados);

        // Limpiar contenedor y selector
        contenedor.innerHTML = '';
        selector.innerHTML = '<option value="">-- Seleccione un programa --</option>';

        // Verificar si hay programas
        if (programasFiltrados.length === 0) {
            contenedor.innerHTML = `
                <div class="alert alert-info p-3 rounded-3">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No hay programas disponibles</h6>
                    <p class="mb-0 small">No se encontraron programas para la institución: ${institucionSeleccionada.nombre}</p>
                </div>`;
            document.getElementById('btn-step-1').disabled = true;
            programaSeleccionado = null;
            return;
        }

        // Llenar el selector (ordenado alfabéticamente)
        const programasOrdenados = [...programasFiltrados].sort((a, b) => a.programa.localeCompare(b.programa));
        programasOrdenados.forEach(programa => {
            const option = document.createElement('option');
            option.value = programa.id_programa;
            option.textContent = programa.programa;
            selector.appendChild(option);
        });

        // Crear tarjetas para cada programa
        const row = document.createElement('div');
        row.className = 'row g-2';
        contenedor.appendChild(row);

        programasFiltrados.forEach(programa => {
            const col = document.createElement('div');
            col.className = 'col-md-6 mb-2';
            col.innerHTML = `
                <div class="selectable-card programa-card position-relative p-3 rounded-3 cursor-pointer"
                     data-id="${programa.id_programa}"
                     data-nombre="${programa.programa}">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-light rounded-circle me-3 p-2" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-graduation-cap text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">${programa.programa}</h6>
                            <p class="text-muted mb-0 small">
                                ${programa.tipo_formacion ? `${programa.tipo_formacion}` : ''}
                                ${programa.metodologia ? ` • ${programa.metodologia}` : ''}
                                ${programa.codigo_snies ? ` • SNIES: ${programa.codigo_snies}` : ''}
                            </p>
                        </div>
                    </div>
                </div>
            `;
            row.appendChild(col);

            // Agregar evento click para seleccionar programa
            col.querySelector('.programa-card').addEventListener('click', function() {
                seleccionarPrograma(this);

                // También seleccionar en el dropdown para mantener sincronizado
                selector.value = this.getAttribute('data-id');
            });
        });
    } catch (error) {
        console.error('Error al cargar programas:', error);

        const contenedor = document.getElementById('programas-container');
        const selector = document.getElementById('selector-programas');

        contenedor.innerHTML = `
            <div class="alert alert-danger p-3 rounded-3">
                <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Error al cargar programas</h6>
                <p class="mb-0 small">${error.message}</p>
            </div>
        `;

        selector.innerHTML = '<option value="">Error al cargar programas</option>';

        // Mostrar mensaje con SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cargar programas: ' + error.message,
            footer: 'Intente recargar la página o use el botón "Recargar programas"',
            confirmButtonColor: '#0f1191'
        });
    }
}

// Función para seleccionar un programa
function seleccionarPrograma(elemento) {
    // Quitar selección anterior
    document.querySelectorAll('.programa-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Marcar el seleccionado
    elemento.classList.add('selected');

    // Guardar datos de programa seleccionado
    programaSeleccionado = {
        id: elemento.getAttribute('data-id')
    };

    console.log('Programa seleccionado desde tarjeta:', programaSeleccionado);

    // Habilitar botón siguiente si hay institución seleccionada
    document.getElementById('btn-step-1').disabled = !institucionSeleccionada;
}

// Función para guardar la asignatura
async function guardarAsignatura() {
    try {
        if (!programaSeleccionado || !programaSeleccionado.id) {
            throw new Error('No se ha seleccionado un programa válido');
        }

        // Mostrar indicador de carga
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

       // Obtener datos del formulario de información básica
        const formInfoBasica = document.getElementById('form-info-basica');
        const formDataInfoBasica = new FormData(formInfoBasica);

        // Obtener datos del formulario de contenido programático
        const formContenido = document.getElementById('form-asignatura');
        const formDataContenido = new FormData(formContenido);

        // Validar que todos los campos requeridos estén llenos
        const camposRequeridos = formInfoBasica.querySelectorAll('[required]');
        let camposFaltantes = [];

        camposRequeridos.forEach(campo => {
            if (!campo.value.trim()) {
                camposFaltantes.push(campo.name);
                campo.classList.add('is-invalid');
            } else {
                campo.classList.remove('is-invalid');
            }
        });

        if (camposFaltantes.length > 0) {
            throw new Error('Por favor complete todos los campos requeridos');
        }

        // Obtener y validar los valores
        const nombre = formDataInfoBasica.get('nombre') || '';
        const tipo = formDataInfoBasica.get('tipo') || '';
        const codigo = formDataInfoBasica.get('codigo_asignatura') || '';
        const semestre = parseInt(formDataInfoBasica.get('semestre') || '0');
        const modalidad = formDataInfoBasica.get('modalidad') || '';
        const metodologia = formDataInfoBasica.get('metodologia') || '';

        // Validar longitudes y valores
        if (nombre.length > 100) {
            throw new Error('El nombre de la asignatura es demasiado largo (máximo 100 caracteres)');
        }

        if (tipo.length > 20) {
            throw new Error('El tipo de asignatura es demasiado largo (máximo 20 caracteres)');
        }

        if (codigo.length > 20) {
            throw new Error('El código de asignatura es demasiado largo (máximo 20 caracteres)');
        }

       if (semestre < 1 || semestre > 12) {
           throw new Error('El semestre debe estar entre 1 y 12');
       }

       if (modalidad.length > 20) {
           throw new Error('La modalidad es demasiado larga (máximo 20 caracteres)');
       }

       if (metodologia.length > 20) {
           throw new Error('La metodología es demasiado larga (máximo 20 caracteres)');
       }

       // Crear objeto para enviar a la API
       const asignaturaData = {
           programa_id: parseInt(programaSeleccionado.id),
           nombre: nombre,
           tipo: tipo,
           codigo_asignatura: codigo,
           semestre: semestre,
           modalidad: modalidad,
           metodologia: metodologia
       };

       // Agregar campos según tipo de institución
       if (institucionSeleccionada && institucionSeleccionada.tipo === 'SENA') {
           // Para SENA solo incluimos horas_sena
           const horasSena = parseInt(formDataInfoBasica.get('horas_sena') || '0');
           if (horasSena < 0) {
               throw new Error('Las horas SENA no pueden ser negativas');
           }

           asignaturaData.horas_sena = horasSena;
           asignaturaData.creditos = null;
           asignaturaData.tiempo_presencial = null;
           asignaturaData.tiempo_independiente = null;
           asignaturaData.horas_totales_semanales = null;
       } else {
           // Para otras instituciones incluimos todos los campos excepto horas_sena
           const creditos = parseInt(formDataInfoBasica.get('creditos') || '0');
           const tiempoPresencial = parseInt(formDataInfoBasica.get('tiempo_presencial') || '0');
           const tiempoIndependiente = parseInt(formDataInfoBasica.get('tiempo_independiente') || '0');
           const horasTotales = parseInt(formDataInfoBasica.get('horas_totales_semanales') || '0');

           if (creditos < 0) {
               throw new Error('Los créditos no pueden ser negativos');
           }

           if (tiempoPresencial < 0) {
               throw new Error('El tiempo presencial no puede ser negativo');
           }

           if (tiempoIndependiente < 0) {
               throw new Error('El tiempo independiente no puede ser negativo');
           }

           if (horasTotales < 0) {
               throw new Error('Las horas totales semanales no pueden ser negativas');
           }

           asignaturaData.horas_sena = null;
           asignaturaData.creditos = creditos;
           asignaturaData.tiempo_presencial = tiempoPresencial;
           asignaturaData.tiempo_independiente = tiempoIndependiente;
           asignaturaData.horas_totales_semanales = horasTotales;
       }

       console.log('Datos de asignatura a guardar:', asignaturaData);

       // Guardar los datos del contenido programático para usarlos después
       const tema = formDataContenido.get('tema');
       const resultadosAprendizaje = formDataContenido.get('resultados_aprendizaje');
       const descripcion = formDataContenido.get('descripcion');

       let tieneDatosContenido = tema && resultadosAprendizaje && descripcion;

       // Mostrar indicador de carga global
       Swal.fire({
           title: 'Guardando asignatura',
           text: 'Por favor espere...',
           allowOutsideClick: false,
           showConfirmButton: false,
           willOpen: () => {
               Swal.showLoading();
           }
       });

       // Enviar datos a la API
       const response = await fetch(`${API_URL}/asignaturas`, {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'Accept': 'application/json'
           },
           body: JSON.stringify(asignaturaData)
       });

       // Obtener la respuesta como texto
       const responseText = await response.text();
       console.log('Respuesta del servidor (texto):', responseText);

       // Intentar parsear como JSON
       let responseData;
       try {
           responseData = JSON.parse(responseText);
           console.log('Respuesta del servidor (JSON):', responseData);
       } catch (e) {
           console.warn('La respuesta no es un JSON válido:', e);
       }

       if (!response.ok) {
           let mensaje = 'Error al guardar la asignatura';

           if (responseData && responseData.message) {
               mensaje = responseData.message;
           } else if (responseText.includes('SQLSTATE')) {
               const match = responseText.match(/SQLSTATE\[\d+\]:(.+?)(?:\(|$)/);
               if (match && match[1]) {
                   mensaje = `Error en la base de datos: ${match[1].trim()}`;
               }
           }

           throw new Error(mensaje);
       }

       // Si tenemos datos de contenido programático, intentamos guardarlos
       let contenidoGuardado = false;

       if (tieneDatosContenido) {
           try {
               // Actualizar mensaje de carga
               Swal.update({
                   title: 'Guardando contenido programático',
                   text: 'Por favor espere...'
               });

               // Esperar 1 segundo para asegurar que la asignatura se haya guardado en la BD
               await new Promise(resolve => setTimeout(resolve, 1000));

               // Extraer el ID de la asignatura recién creada
               let asignaturaId = null;

               // Buscar dentro de la respuesta con varias opciones
               if (responseData) {
                   if (responseData.data && responseData.data.id_asignatura) {
                       asignaturaId = responseData.data.id_asignatura;
                   } else if (responseData.datos && responseData.datos.id_asignatura) {
                       asignaturaId = responseData.datos.id_asignatura;
                   } else if (responseData.id_asignatura) {
                       asignaturaId = responseData.id_asignatura;
                   }
               }

              // Si no encontramos el ID, intentar extraerlo del texto de respuesta
               if (!asignaturaId && responseText) {
                   const match = responseText.match(/"id_asignatura"[\s]*:[\s]*(\d+)/);
                   if (match && match[1]) {
                       asignaturaId = parseInt(match[1]);
                   }
               }

               // Si aún no tenemos el ID, consultar la API para encontrar la asignatura
               if (!asignaturaId) {
                   try {
                       // Actualizar mensaje de carga
                       Swal.update({
                           title: 'Buscando información de la asignatura',
                           text: 'Esto puede tardar un momento...'
                       });

                       // Consultar la lista de asignaturas por programa
                       const asignaturasResponse = await fetch(`${API_URL}/asignaturas/programa/${programaSeleccionado.id}`);
                       if (asignaturasResponse.ok) {
                           const asignaturasData = await asignaturasResponse.json();

                           // Buscar la asignatura recién creada (última en la lista)
                           if (asignaturasData && asignaturasData.data && asignaturasData.data.length > 0) {
                               // Ordenar por ID de manera descendente y tomar la primera (la más reciente)
                               const asignaturas = asignaturasData.data.sort((a, b) =>
                                   b.id_asignatura - a.id_asignatura
                               );

                               if (asignaturas[0]) {
                                   asignaturaId = asignaturas[0].id_asignatura;
                               }
                           }
                       }
                   } catch (consultaError) {
                       console.error('Error al consultar asignaturas:', consultaError);
                   }
               }

               if (!asignaturaId) {
                   throw new Error('No se pudo obtener el ID de la asignatura');
               }

               console.log('ID de asignatura para contenido programático:', asignaturaId);

               // Preparar los datos del contenido programático
               const contenidoData = {
                   asignatura_id: asignaturaId,
                   tema: tema,
                   resultados_aprendizaje: resultadosAprendizaje,
                   descripcion: descripcion
               };

               console.log('Datos de contenido programático:', contenidoData);

               // Actualizar mensaje de carga
               Swal.update({
                   title: 'Guardando contenido programático',
                   text: 'Finalizando el proceso...'
               });

               // Crear objeto FormData para envío alternativo
               const formDataContenido = new FormData();
               formDataContenido.append('asignatura_id', asignaturaId);
               formDataContenido.append('tema', tema);
               formDataContenido.append('resultados_aprendizaje', resultadosAprendizaje);
               formDataContenido.append('descripcion', descripcion);

               // Intentar guardar con dos métodos diferentes
               let intentos = 0;
               let exito = false;

               while (intentos < 2 && !exito) {
                   try {
                       // Primer intento: JSON
                       if (intentos === 0) {
                           const contenidoResponse = await fetch(`${API_URL}/contenidos-programaticos`, {
                               method: 'POST',
                               headers: {
                                   'Content-Type': 'application/json',
                                   'Accept': 'application/json'
                               },
                               body: JSON.stringify(contenidoData)
                           });

                           if (contenidoResponse.ok) {
                               exito = true;
                               console.log('Contenido programático guardado con éxito (JSON)');
                           } else {
                               throw new Error('Falló el primer intento');
                           }
                       }
                       // Segundo intento: FormData
                       else {
                           const contenidoResponse = await fetch(`${API_URL}/contenidos-programaticos`, {
                               method: 'POST',
                               body: formDataContenido
                           });

                           if (contenidoResponse.ok) {
                               exito = true;
                               console.log('Contenido programático guardado con éxito (FormData)');
                           } else {
                               throw new Error('Falló el segundo intento');
                           }
                       }
                   } catch (intentoError) {
                       console.error(`Error en intento ${intentos + 1}:`, intentoError);
                       intentos++;

                       // Esperar antes del segundo intento
                       if (intentos < 2) {
                           await new Promise(resolve => setTimeout(resolve, 1000));
                       }
                   }
               }

               if (exito) {
                   contenidoGuardado = true;
               } else {
                   throw new Error('No se pudo guardar el contenido programático después de varios intentos');
               }

           } catch (contenidoError) {
               console.error('Error al guardar contenido programático:', contenidoError);

               // Mostrar advertencia pero continuar
               Swal.fire({
                   icon: 'warning',
                   title: 'Advertencia',
                   text: 'La asignatura se guardó correctamente, pero hubo un problema al guardar el contenido programático.',
                   confirmButtonColor: '#f8bb86'
               });
           }
       }

       // Mostrar mensaje de éxito final
       let mensajeExito = 'Asignatura guardada correctamente';
       if (tieneDatosContenido) {
           if (contenidoGuardado) {
               mensajeExito += ' con su contenido programático';
           } else {
               mensajeExito += ', pero hubo un problema al guardar el contenido programático';
           }
       }

       Swal.fire({
           icon: 'success',
           title: '¡Éxito!',
           text: mensajeExito,
           confirmButtonColor: '#28a745',
           showConfirmButton: true
       }).then(() => {
           // Recargar para volver a empezar
           window.location.reload();
       });

   } catch (error) {
       console.error('Error al guardar:', error);

       // Cerrar el diálogo de carga si está abierto
       Swal.close();

       // Restaurar botón
       const btnSubmit = document.getElementById('btn-submit');
       btnSubmit.disabled = false;
       btnSubmit.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Asignatura';

       // Mostrar mensaje de error
       Swal.fire({
           icon: 'error',
           title: 'Error al guardar la asignatura',
           text: error.message,
           footer: 'Por favor, verifique los datos e intente nuevamente.',
           confirmButtonColor: '#dc3545'
       });
   }
}
</script>
@endsection

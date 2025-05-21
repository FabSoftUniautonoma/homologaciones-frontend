@extends('admin.layouts.appadmin')

@section('content')
<!-- Header institucional -->
<!-- Header institucional -->
<div class="container-fluid py-3 mb-4" style="background-color: #003366; font-family: 'Source Sans Pro', sans-serif;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
          <class="img-fluid" style="max-height: 60px;">
            </div>
            <div class="col-md-8 text-center">
                <h1 class="display-5 fw-bold mb-0" style="color: white !important;">Sistema de homologaciones</h1>
                <p class="lead mb-0" style="color: white !important;">Gestión de usuarios</p>
            </div>
        </div>
    </div>
</div>


<div class="container py-4">
    <!-- Wizard Steps Circulares - Institucional -->
    <div class="mb-4">
        <div class="steps-container">
            <div class="steps-wrapper">
                <div class="step active" id="step-personal-indicator">
                    <div class="step-circle">
                        <i class="step-icon fas fa-user"></i>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-label">Información Personal</div>
                </div>
                <div class="step" id="step-institucional-indicator">
                    <div class="step-circle">
                        <i class="step-icon fas fa-university"></i>
                    </div>
                    <div class="step-line"></div>
                    <div class="step-label">Información Institucional</div>
                </div>
                <div class="step" id="step-acceso-indicator">
                    <div class="step-circle">
                        <i class="step-icon fas fa-lock"></i>
                    </div>
                    <div class="step-label">Datos de Acceso</div>
                </div>
            </div>
            <div class="steps-progress">
                <div class="steps-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Contenido del Formulario -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Nuevo Usuario</h4>
                            <p class="text-muted mb-0">Complete el formulario para registrar un nuevo usuario</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="formNuevoUsuario">
                        <!-- SECCIÓN 1: INFORMACIÓN PERSONAL -->
                        <div class="form-section" id="personal-section">
                            <div class="alert alert-light border-start border-4 border-primary mb-4">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle text-primary fa-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Información Personal</h5>
                                        <p class="mb-0">Complete la información personal del usuario</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Nombres -->
                                <div class="col-md-6">
                                    <label for="primer_nombre" class="form-label">Primer Nombre <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user text-primary"></i></span>
                                        <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user text-secondary"></i></span>
                                        <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
                                    </div>
                                </div>

                                <!-- Apellidos -->
                                <div class="col-md-6">
                                    <label for="primer_apellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user text-primary"></i></span>
                                        <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user text-secondary"></i></span>
                                        <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                                    </div>
                                </div>

                                <!-- Identificación -->
                                <div class="col-md-6">
                                    <label for="tipo_identificacion" class="form-label">Tipo de Identificación <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-id-card text-primary"></i></span>
                                        <select class="form-select" id="tipo_identificacion" name="tipo_identificacion" required>
                                            <option value="">Seleccione...</option>
                                            <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                            <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                                            <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="numero_identificacion" class="form-label">Número de Identificación <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-hashtag text-primary"></i></span>
                                        <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" required>
                                    </div>
                                    <div id="identificacion-existe" class="text-danger mt-1" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i> Esta identificación ya está registrada
                                    </div>
                                </div>

                                <!-- Contacto -->
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-phone text-secondary"></i></span>
                                        <input type="text" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-home text-secondary"></i></span>
                                        <input type="text" class="form-control" id="direccion" name="direccion">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" id="btn-next-institucional" class="btn btn-primary">
                                    Siguiente <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: INFORMACIÓN INSTITUCIONAL -->
                        <div class="form-section" id="institucional-section" style="display: none;">
                            <div class="alert alert-light border-start border-4 border-info mb-4">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle text-info fa-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Información Institucional</h5>
                                        <p class="mb-0">Complete la información institucional del usuario</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Ubicación -->
                                <div class="col-md-4">
                                    <label for="pais_id" class="form-label">País</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-globe-americas text-info"></i></span>
                                        <select class="form-select" id="pais_id" name="pais_id">
                                            <option value="">Seleccione...</option>
                                            <!-- Se cargará dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="departamento_id" class="form-label">Departamento</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-map text-info"></i></span>
                                        <select class="form-select" id="departamento_id" name="departamento_id" disabled>
                                            <option value="">Seleccione un país primero</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="municipio_id" class="form-label">Municipio</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-city text-info"></i></span>
                                        <select class="form-select" id="municipio_id" name="municipio_id" disabled>
                                            <option value="">Seleccione un departamento primero</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Institución -->
                                <div class="col-md-6">
                                    <label for="institucion_origen_id" class="form-label">Institución</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-university text-info"></i></span>
                                        <select class="form-select" id="institucion_origen_id" name="institucion_origen_id">
                                            <option value="">Seleccione...</option>
                                            <!-- Se cargará dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="facultad_id" class="form-label">Facultad</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-graduation-cap text-info"></i></span>
                                        <select class="form-select" id="facultad_id" name="facultad_id">
                                            <option value="">Seleccione...</option>
                                            <!-- Se cargará dinámicamente -->
                                        </select>
                                    </div>
                                </div>

                                <!-- Selección de Rol -->
                                <div class="col-12 mt-3">
                                    <label class="form-label mb-3">
                                        <i class="fas fa-user-tag me-1 text-info"></i>
                                        Seleccione el Rol del Usuario <span class="text-danger">*</span>
                                    </label>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="role-card" data-role="1">
                                                <div class="role-icon admin-role">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>
                                                <div class="role-info">
                                                    <h5 class="mb-1">Administrador</h5>
                                                    <p class="mb-0 text-muted small">Control total del sistema</p>
                                                </div>
                                                <div class="role-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="role-card" data-role="2">
                                                <div class="role-icon user-role">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="role-info">
                                                    <h5 class="mb-1">Aspirante</h5>
                                                    <p class="mb-0 text-muted small">Acceso básico al sistema</p>
                                                </div>
                                                <div class="role-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="role-card" data-role="3">
                                                <div class="role-icon manager-role">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div class="role-info">
                                                    <h5 class="mb-1">Vicerrector</h5>
                                                    <p class="mb-0 text-muted small">Gestión académica</p>
                                                </div>
                                                <div class="role-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="rol_id" name="rol_id" required>
                                    <div class="invalid-feedback d-block text-center mt-2" id="rol-feedback" style="display: none !important;">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Por favor seleccione un rol.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" id="btn-prev-personal" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Anterior
                                </button>
                                <button type="button" id="btn-next-acceso" class="btn btn-primary">
                                    Siguiente <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: DATOS DE ACCESO -->
                        <div class="form-section" id="acceso-section" style="display: none;">
                            <div class="alert alert-light border-start border-4 border-success mb-4">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle text-success fa-lg"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Datos de Acceso</h5>
                                        <p class="mb-0">Configure las credenciales de acceso del usuario</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Correo -->
                                <div class="col-12">
                                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-success"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div id="email-existe" class="text-danger mt-1" style="display: none;">
                                        <i class="fas fa-exclamation-circle"></i> Este correo ya está registrado
                                    </div>
                                </div>

                                <!-- Contraseñas -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-lock text-success"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Mínimo 8 caracteres</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-lock text-success"></i></span>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Estado del usuario -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-sm">
                                        <div class="card-body">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" checked>
                                                <label class="form-check-label" for="activo">
                                                    <span id="estado-label" class="fw-bold text-success">
                                                        <i class="fas fa-toggle-on me-2"></i> Usuario Activo
                                                    </span>
                                                    <div class="text-muted mt-1 small">
                                                        Si está activo, el usuario podrá acceder al sistema inmediatamente.
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" id="btn-prev-institucional" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Anterior
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i> Guardar Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de navegación inferior -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('/usuarios') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Volver a la lista
                        </a>
                        <div class="progress rounded-pill" style="height: 8px; width: 60%;">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                 role="progressbar" style="width: 33%;"
                                 aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span id="progress-text" class="badge bg-primary rounded-pill px-3 py-2">
                            <i class="fas fa-tasks me-2"></i> Paso 1 de 3
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos personalizados -->
<style>
    /* Estilos base */
    body {
        background-color: #f8f9fa;
    }

    /* Steps circulares */
    .steps-container {
        position: relative;
        padding: 0 0 25px;
    }

    .steps-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 1;
    }

    .steps-progress {
        position: absolute;
        top: 40px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #e9ecef;
        z-index: 0;
    }

    .steps-progress-bar {
        height: 100%;
        background-color: #0d6efd;
        width: 0%;
        transition: width 0.3s ease;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        width: 33.33%;
    }

    .step-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 3px solid #dee2e6;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: relative;
        transition: all 0.3s ease;
    }

    .step.active .step-circle {
        border-color: #0d6efd;
        background-color: #e7f1ff;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    .step.completed .step-circle {
        border-color: #198754;
        background-color: #d1e7dd;
    }

    .step-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #6c757d;
        line-height: 1;
        position: absolute;
        top: 15px;
    }

    .step-icon {
        font-size: 1.8rem;
        color: #6c757d;
        margin-top: 15px;
    }

    .step.active .step-number,
    .step.active .step-icon {
        color: #0d6efd;
    }

    .step.completed .step-number,
    .step.completed .step-icon {
        color: #198754;
    }

    .step-label {
        font-size: 0.95rem;
        color: #6c757d;
        font-weight: 500;
        text-align: center;
    }

    .step.active .step-label {
        color: #0d6efd;
        font-weight: 600;
    }

    .step.completed .step-label {
        color: #198754;
    }

    /* Role Cards institucionales */
    .role-card {
        display: flex;
        align-items: center;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        background-color: #fff;
    }

    .role-card:hover {
        border-color: #adb5bd;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-3px);
    }

    .role-card.selected {
        border-color: #0d6efd;
        background-color: #f8f9ff;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.15);
    }

    .role-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
        margin-right: 15px;
    }

    .admin-role {
        background-color: #6d76ee;
    }

    .user-role {
        background-color: #0d6efd;
    }

    .manager-role {
        background-color: #fd7e14;
    }

    .role-info {
        flex-grow: 1;
    }

    .role-check {
        color: #0d6efd;
        font-size: 1.25rem;
        opacity: 0;
        transition: all 0.2s ease;
    }

    .role-card.selected .role-check {
        opacity: 1;
    }

    /* Animaciones simples pero elegantes */
    .form-section {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .step-circle {
            width: 60px;
            height: 60px;
        }

        .step-number {
            font-size: 1.2rem;
            top: 10px;
        }

        .step-icon {
            font-size: 1.3rem;
            margin-top: 12px;
        }

        .step-label {
            font-size: 0.8rem;
        }

        .role-card {
            margin-bottom: 15px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Constantes
    const API_URL = 'https://homologacionesback.educarenemociones.com/api';

    // Referencias a elementos DOM
    const formNuevoUsuario = document.getElementById('formNuevoUsuario');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progress-text');
    const stepsProgressBar = document.querySelector('.steps-progress-bar');

    // Secciones del formulario
    const sections = {
        personal: document.getElementById('personal-section'),
        institucional: document.getElementById('institucional-section'),
        acceso: document.getElementById('acceso-section')
    };

    // Elementos de pasos circulares
    const stepItems = {
        personal: document.getElementById('step-personal-indicator'),
        institucional: document.getElementById('step-institucional-indicator'),
        acceso: document.getElementById('step-acceso-indicator')
    };

    // Elementos de navegación
    const btnNextInstitucional = document.getElementById('btn-next-institucional');
    const btnPrevPersonal = document.getElementById('btn-prev-personal');
    const btnNextAcceso = document.getElementById('btn-next-acceso');
    const btnPrevInstitucional = document.getElementById('btn-prev-institucional');

    // Campos del formulario
    const rolIdInput = document.getElementById('rol_id');
    const numeroIdentificacion = document.getElementById('numero_identificacion');
    const email = document.getElementById('email');

    // Selects encadenados
    const paisSelect = document.getElementById('pais_id');
    const departamentoSelect = document.getElementById('departamento_id');
    const municipioSelect = document.getElementById('municipio_id');
    const institucionSelect = document.getElementById('institucion_origen_id');
    const facultadSelect = document.getElementById('facultad_id');

    // Estado del usuario
    const estadoSwitch = document.getElementById('activo');
    const estadoLabel = document.getElementById('estado-label');

    // Array para almacenar los roles de la API
    let rolesAPI = [];

    // Inicialización
    inicializar();

    function inicializar() {
        // Cargar roles desde la API y luego inicializar
        cargarRolesDesdeAPI().then(() => {
            // Cargar datos iniciales
            cargarPaisesEstaticos();
            cargarInstitucionesDesdeAPI();

            // Configurar eventos
            configurarEventos();

            // Configurar navegación de pasos
            configurarNavegacionPasos();

            // Configurar estado de usuario
            configurarEstadoUsuario();

            // Configurar toggle de contraseñas
            configurarTogglePassword();
        });
    }

    // Función para cargar roles desde la API
    async function cargarRolesDesdeAPI() {
        try {
            const response = await fetch(`${API_URL}/roles`);
            if (!response.ok) {
                throw new Error('Error al obtener roles');
            }

            const data = await response.json();
            rolesAPI = Array.isArray(data) ? data : [];

            console.log('Roles cargados desde API:', rolesAPI);

            // Generar las tarjetas de roles dinámicamente
            generarTarjetasRoles();

            return true;
        } catch (error) {
            console.error('Error al cargar roles:', error);
            mostrarNotificacion('error', 'Error', 'No se pudieron cargar los roles. Usando roles predeterminados.');

            // Establecer roles predeterminados en caso de error
            rolesAPI = [
                { id_rol: 1, nombre: "Aspirante" },
                { id_rol: 2, nombre: "Coordinador" },
                { id_rol: 3, nombre: "Decano" },
                { id_rol: 4, nombre: "Vicerrector" },
                { id_rol: 5, nombre: "Administrador" }
            ];

            generarTarjetasRoles();
            return false;
        }
    }

    // Función para generar tarjetas de roles dinámicamente
    function generarTarjetasRoles() {
        // El contenedor donde van las tarjetas - intentamos varias opciones de selector
        let roleCardContainer = document.querySelector('.col-12.mt-3 .row.g-3');

        // Si no encontramos el contenedor con el primer selector, intentamos otro
        if (!roleCardContainer) {
            roleCardContainer = document.querySelector('.row.g-3');
        }

        // Si todavía no encontramos, intentamos crear uno
        if (!roleCardContainer) {
            console.error("No se pudo encontrar el contenedor de tarjetas de roles. Creando uno nuevo");

            // Buscar el contenedor más cercano que podemos encontrar
            const parentContainer = document.querySelector('.col-12.mt-3');
            if (parentContainer) {
                // Crear el contenedor de tarjetas
                roleCardContainer = document.createElement('div');
                roleCardContainer.className = 'row g-3';
                parentContainer.appendChild(roleCardContainer);
            } else {
                console.error("No se pudo crear el contenedor para las tarjetas de roles");
                return;
            }
        }

        console.log("Contenedor de tarjetas encontrado. Generando tarjetas para", rolesAPI.length, "roles");

        // Limpiamos el contenedor
        roleCardContainer.innerHTML = '';

        // Solo mostrar máximo 3 roles por fila
        const maxRolesPerRow = 3;
        const rolesToShow = rolesAPI.slice(0, maxRolesPerRow);

        // Iconos y clases para los diferentes tipos de roles
        const roleIcons = {
            "Administrador": { icon: "fas fa-user-shield", class: "admin-role" },
            "Aspirante": { icon: "fas fa-user", class: "user-role" },
            "Coordinador": { icon: "fas fa-user-cog", class: "user-role" },
            "Vicerrector": { icon: "fas fa-user-tie", class: "manager-role" },
            "Decano": { icon: "fas fa-user-graduate", class: "manager-role" }
        };

        // Generar tarjeta para cada rol
        rolesToShow.forEach(rol => {
            const roleConfig = roleIcons[rol.nombre] || { icon: "fas fa-user", class: "user-role" };

            const colDiv = document.createElement('div');
            colDiv.className = 'col-md-4';

            colDiv.innerHTML = `
                <div class="role-card" data-role="${rol.id_rol}">
                    <div class="role-icon ${roleConfig.class}">
                        <i class="${roleConfig.icon}"></i>
                    </div>
                    <div class="role-info">
                        <h5 class="mb-1">${rol.nombre}</h5>
                        <p class="mb-0 text-muted small">${getRoleDescription(rol.nombre)}</p>
                    </div>
                    <div class="role-check">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            `;

            roleCardContainer.appendChild(colDiv);
        });

        // Si hay más roles que los mostrados, agregar un botón "Ver más roles"
        if (rolesAPI.length > maxRolesPerRow) {
            const verMasDiv = document.createElement('div');
            verMasDiv.className = 'col-12 mt-2 text-center';
            verMasDiv.innerHTML = `
                <button type="button" class="btn btn-sm btn-outline-info" id="btn-ver-mas-roles">
                    <i class="fas fa-plus-circle me-1"></i> Ver más roles (${rolesAPI.length - maxRolesPerRow} adicionales)
                </button>
            `;
            roleCardContainer.appendChild(verMasDiv);

            // Agregar evento para mostrar modal con todos los roles
            document.getElementById('btn-ver-mas-roles').addEventListener('click', function() {
                console.log("Botón Ver más roles clickeado. Mostrando modal...");
                mostrarModalTodosRoles();
            });
        }

        // Agregar eventos a las nuevas tarjetas
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function() {
                // Eliminar selección anterior
                document.querySelectorAll('.role-card').forEach(c => {
                    c.classList.remove('selected');
                });

                // Aplicar selección actual
                this.classList.add('selected');

                // Guardar valor
                const roleId = this.getAttribute('data-role');
                rolIdInput.value = roleId;
                console.log("Rol seleccionado:", roleId);

                document.getElementById('rol-feedback').style.display = 'none';
            });
        });
    }

    // Función para obtener descripción según el rol
    function getRoleDescription(rolNombre) {
        const descriptions = {
            "Administrador": "Control total del sistema",
            "Aspirante": "Acceso básico al sistema",
            "Coordinador": "Gestión de homologaciones",
            "Vicerrector": "Gestión académica",
            "Decano": "Gestión de facultad"
        };

        return descriptions[rolNombre] || "Usuario del sistema";
    }

    // Función para mostrar modal con todos los roles
    function mostrarModalTodosRoles() {
        // Crear contenido HTML para el modal con estilos inline para evitar dependencias de CSS externo
        let rolesHtml = `
        <style>
            .roles-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
                max-height: 400px;
                overflow-y: auto;
                padding: 10px;
            }
            .role-card-modal {
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 15px;
                cursor: pointer;
                background-color: #fff;
                transition: all 0.2s ease;
            }
            .role-card-modal:hover {
                border-color: #0d6efd;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }
            .role-icon-container {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                margin-right: 15px;
            }
            .role-info-modal {
                display: flex;
                align-items: center;
            }
            .role-details h5 {
                margin: 0 0 5px 0;
                font-size: 16px;
            }
            .role-details p {
                margin: 0;
                font-size: 13px;
                color: #6c757d;
            }
            .bg-admin { background-color: #6f42c1; }
            .bg-aspirante { background-color: #0d6efd; }
            .bg-coordinador { background-color: #20c997; }
            .bg-vicerrector { background-color: #fd7e14; }
            .bg-decano { background-color: #6c757d; }
        </style>
        <div class="roles-grid">`;

        rolesAPI.forEach(rol => {
            // Determinar clase de color según el rol
            let bgColorClass = 'bg-secondary';
            let iconClass = 'fas fa-user';

            switch(rol.nombre.toLowerCase()) {
                case 'administrador':
                    bgColorClass = 'bg-admin';
                    iconClass = 'fas fa-user-shield';
                    break;
                case 'aspirante':
                    bgColorClass = 'bg-aspirante';
                    iconClass = 'fas fa-user';
                    break;
                case 'coordinador':
                    bgColorClass = 'bg-coordinador';
                    iconClass = 'fas fa-user-cog';
                    break;
                case 'vicerrector':
                    bgColorClass = 'bg-vicerrector';
                    iconClass = 'fas fa-user-tie';
                    break;
                case 'decano':
                    bgColorClass = 'bg-decano';
                    iconClass = 'fas fa-user-graduate';
                    break;
            }

            rolesHtml += `
                <div class="role-card-modal" data-role-id="${rol.id_rol}">
                    <div class="role-info-modal">
                        <div class="role-icon-container ${bgColorClass}">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="role-details">
                            <h5>${rol.nombre}</h5>
                            <p>${getRoleDescription(rol.nombre)}</p>
                        </div>
                    </div>
                </div>
            `;
        });

        rolesHtml += '</div>';

        // Mostrar modal con SweetAlert2
        Swal.fire({
            title: 'Seleccione un Rol',
            html: rolesHtml,
            width: '600px',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            showConfirmButton: false,
            didOpen: () => {
                console.log("Modal abierto, configurando eventos de tarjetas de roles");

                // Agregar evento a cada tarjeta en el modal
                const tarjetasRoles = document.querySelectorAll('.role-card-modal');
                console.log(`Encontradas ${tarjetasRoles.length} tarjetas de roles`);

                tarjetasRoles.forEach(card => {
                    card.addEventListener('click', function() {
                        const roleId = this.getAttribute('data-role-id');
                        console.log("Rol seleccionado ID:", roleId);

                        const rolSeleccionado = rolesAPI.find(r => r.id_rol == roleId);
                        if (!rolSeleccionado) {
                            console.error("No se encontró el rol con ID:", roleId);
                            return;
                        }

                        console.log("Rol seleccionado:", rolSeleccionado.nombre);

                        // Actualizar el valor del rolIdInput
                        rolIdInput.value = roleId;

                        // Cerrar el modal
                        Swal.close();

                        // Actualizar la UI para mostrar el rol seleccionado
                        document.querySelectorAll('.role-card').forEach(c => {
                            c.classList.remove('selected');
                            if (c.getAttribute('data-role') == roleId) {
                                c.classList.add('selected');
                            }
                        });

                        // Mostrar una notificación independientemente de si está visible o no
                        mostrarNotificacion('success', 'Rol seleccionado', `Ha seleccionado el rol: ${rolSeleccionado.nombre}`);

                        document.getElementById('rol-feedback').style.display = 'none';
                    });
                });
            }
        });
    }

    // DATOS ESTÁTICOS
    function cargarPaisesEstaticos() {
        // Solo incluimos Colombia y Otro
        const paisesEstaticos = [
            { id: '1', nombre: 'Colombia' },
            { id: '2', nombre: 'Otro' }
        ];

        // Resetear y cargar el select
        paisSelect.innerHTML = '';

        // Opción predeterminada
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccione un país...';
        paisSelect.appendChild(defaultOption);

        // Agregar los países estáticos
        paisesEstaticos.forEach(pais => {
            const option = document.createElement('option');
            option.value = pais.id;
            option.textContent = pais.nombre;
            paisSelect.appendChild(option);
        });

        paisSelect.disabled = false;
        console.log('Países cargados estáticamente (Colombia y Otro)');
    }

    function cargarDepartamentosEstaticos() {
        // Lista de departamentos de Colombia
        const departamentosEstaticos = [
            { id: '1', nombre: 'Antioquia' },
            { id: '2', nombre: 'Atlántico' },
            { id: '3', nombre: 'Bogotá D.C.' },
            { id: '4', nombre: 'Bolívar' },
            { id: '5', nombre: 'Boyacá' },
            { id: '6', nombre: 'Caldas' },
            { id: '7', nombre: 'Caquetá' },
            { id: '8', nombre: 'Cauca' },
            { id: '9', nombre: 'Cesar' },
            { id: '10', nombre: 'Córdoba' },
            { id: '11', nombre: 'Cundinamarca' },
            { id: '12', nombre: 'Chocó' },
            { id: '13', nombre: 'Huila' },
            { id: '14', nombre: 'La Guajira' },
            { id: '15', nombre: 'Magdalena' },
            { id: '16', nombre: 'Meta' },
            { id: '17', nombre: 'Nariño' },
            { id: '18', nombre: 'Norte de Santander' },
            { id: '19', nombre: 'Quindío' },
            { id: '20', nombre: 'Risaralda' },
            { id: '21', nombre: 'Santander' },
            { id: '22', nombre: 'Sucre' },
            { id: '23', nombre: 'Tolima' },
            { id: '24', nombre: 'Valle del Cauca' },
            { id: '25', nombre: 'Arauca' },
            { id: '26', nombre: 'Casanare' },
            { id: '27', nombre: 'Putumayo' },
            { id: '28', nombre: 'San Andrés y Providencia' },
            { id: '29', nombre: 'Amazonas' },
            { id: '30', nombre: 'Guainía' },
            { id: '31', nombre: 'Guaviare' },
            { id: '32', nombre: 'Vaupés' },
            { id: '33', nombre: 'Vichada' }
        ];

        // Resetear y cargar el select
        departamentoSelect.innerHTML = '';

        // Opción predeterminada
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccione un departamento...';
        departamentoSelect.appendChild(defaultOption);

        // Agregar los departamentos
        departamentosEstaticos.forEach(depto => {
            const option = document.createElement('option');
            option.value = depto.id;
            option.textContent = depto.nombre;
            departamentoSelect.appendChild(option);
        });

        departamentoSelect.disabled = false;
        console.log('Departamentos de Colombia cargados estáticamente');
    }

    function cargarMunicipiosEstaticos(departamentoId) {
    // Mapa completo de municipios por departamento para Colombia
    const municipiosPorDepartamento = {
        // Antioquia
        '1': [
            { id: '1', nombre: 'Medellín' },
            { id: '2', nombre: 'Bello' },
            { id: '3', nombre: 'Envigado' },
            { id: '4', nombre: 'Itagüí' },
            { id: '5', nombre: 'Rionegro' },
            { id: '6', nombre: 'Apartadó' },
            { id: '7', nombre: 'Turbo' },
            { id: '8', nombre: 'Caucasia' },
            { id: '9', nombre: 'La Estrella' },
            { id: '10', nombre: 'Sabaneta' }
        ],
        // Atlántico
        '2': [
            { id: '11', nombre: 'Barranquilla' },
            { id: '12', nombre: 'Soledad' },
            { id: '13', nombre: 'Malambo' },
            { id: '14', nombre: 'Sabanalarga' },
            { id: '15', nombre: 'Baranoa' },
            { id: '16', nombre: 'Puerto Colombia' },
            { id: '17', nombre: 'Galapa' },
            { id: '18', nombre: 'Santo Tomás' }
        ],
        // Bogotá D.C.
        '3': [
            { id: '19', nombre: 'Bogotá' }
        ],
        // Bolívar
        '4': [
            { id: '20', nombre: 'Cartagena' },
            { id: '21', nombre: 'Magangué' },
            { id: '22', nombre: 'El Carmen de Bolívar' },
            { id: '23', nombre: 'Turbaco' },
            { id: '24', nombre: 'Arjona' },
            { id: '25', nombre: 'María La Baja' }
        ],
        // Boyacá
        '5': [
            { id: '26', nombre: 'Tunja' },
            { id: '27', nombre: 'Duitama' },
            { id: '28', nombre: 'Sogamoso' },
            { id: '29', nombre: 'Chiquinquirá' },
            { id: '30', nombre: 'Paipa' },
            { id: '31', nombre: 'Moniquirá' },
            { id: '32', nombre: 'Villa de Leyva' }
        ],
        // Caldas
        '6': [
            { id: '33', nombre: 'Manizales' },
            { id: '34', nombre: 'La Dorada' },
            { id: '35', nombre: 'Chinchiná' },
            { id: '36', nombre: 'Villamaría' },
            { id: '37', nombre: 'Anserma' },
            { id: '38', nombre: 'Riosucio' }
        ],
        // Caquetá
        '7': [
            { id: '39', nombre: 'Florencia' },
            { id: '40', nombre: 'San Vicente del Caguán' },
            { id: '41', nombre: 'Puerto Rico' },
            { id: '42', nombre: 'El Doncello' },
            { id: '43', nombre: 'Belén de los Andaquíes' }
        ],
        // Cauca
        '8': [
            { id: '44', nombre: 'Popayán' },
            { id: '45', nombre: 'Santander de Quilichao' },
            { id: '46', nombre: 'Puerto Tejada' },
            { id: '47', nombre: 'Patía' },
            { id: '48', nombre: 'Miranda' },
            { id: '49', nombre: 'Caloto' },
            { id: '50', nombre: 'Piendamó' }
        ],
        // Cesar
        '9': [
            { id: '51', nombre: 'Valledupar' },
            { id: '52', nombre: 'Aguachica' },
            { id: '53', nombre: 'Agustín Codazzi' },
            { id: '54', nombre: 'Bosconia' },
            { id: '55', nombre: 'La Paz' },
            { id: '56', nombre: 'Chiriguaná' }
        ],
        // Córdoba
        '10': [
            { id: '57', nombre: 'Montería' },
            { id: '58', nombre: 'Cereté' },
            { id: '59', nombre: 'Lorica' },
            { id: '60', nombre: 'Sahagún' },
            { id: '61', nombre: 'Planeta Rica' },
            { id: '62', nombre: 'Montelíbano' },
            { id: '63', nombre: 'Tierralta' }
        ],
        // Cundinamarca
        '11': [
            { id: '64', nombre: 'Soacha' },
            { id: '65', nombre: 'Facatativá' },
            { id: '66', nombre: 'Zipaquirá' },
            { id: '67', nombre: 'Chía' },
            { id: '68', nombre: 'Mosquera' },
            { id: '69', nombre: 'Madrid' },
            { id: '70', nombre: 'Funza' },
            { id: '71', nombre: 'Cajicá' },
            { id: '72', nombre: 'Girardot' }
        ],
        // Chocó
        '12': [
            { id: '73', nombre: 'Quibdó' },
            { id: '74', nombre: 'Istmina' },
            { id: '75', nombre: 'Tadó' },
            { id: '76', nombre: 'Acandí' },
            { id: '77', nombre: 'Bahía Solano' },
            { id: '78', nombre: 'Nuquí' }
        ],
        // Huila
        '13': [
            { id: '79', nombre: 'Neiva' },
            { id: '80', nombre: 'Pitalito' },
            { id: '81', nombre: 'Garzón' },
            { id: '82', nombre: 'La Plata' },
            { id: '83', nombre: 'Campoalegre' },
            { id: '84', nombre: 'Gigante' }
        ],
        // La Guajira
        '14': [
            { id: '85', nombre: 'Riohacha' },
            { id: '86', nombre: 'Maicao' },
            { id: '87', nombre: 'Uribia' },
            { id: '88', nombre: 'Manaure' },
            { id: '89', nombre: 'Fonseca' },
            { id: '90', nombre: 'San Juan del Cesar' }
        ],
        // Magdalena
        '15': [
            { id: '91', nombre: 'Santa Marta' },
            { id: '92', nombre: 'Ciénaga' },
            { id: '93', nombre: 'Fundación' },
            { id: '94', nombre: 'Plato' },
            { id: '95', nombre: 'El Banco' },
            { id: '96', nombre: 'Zona Bananera' }
        ],
        // Meta
        '16': [
            { id: '97', nombre: 'Villavicencio' },
            { id: '98', nombre: 'Acacías' },
            { id: '99', nombre: 'Granada' },
            { id: '100', nombre: 'Puerto López' },
            { id: '101', nombre: 'La Macarena' },
            { id: '102', nombre: 'San Martín' }
        ],
        // Nariño
        '17': [
            { id: '103', nombre: 'Pasto' },
            { id: '104', nombre: 'Ipiales' },
            { id: '105', nombre: 'Tumaco' },
            { id: '106', nombre: 'Túquerres' },
            { id: '107', nombre: 'La Unión' },
            { id: '108', nombre: 'Samaniego' }
        ],
        // Norte de Santander
        '18': [
            { id: '109', nombre: 'Cúcuta' },
            { id: '110', nombre: 'Ocaña' },
            { id: '111', nombre: 'Pamplona' },
            { id: '112', nombre: 'Villa del Rosario' },
            { id: '113', nombre: 'Los Patios' },
            { id: '114', nombre: 'Tibú' }
        ],
        // Quindío
        '19': [
            { id: '115', nombre: 'Armenia' },
            { id: '116', nombre: 'Calarcá' },
            { id: '117', nombre: 'Montenegro' },
            { id: '118', nombre: 'Quimbaya' },
            { id: '119', nombre: 'La Tebaida' },
            { id: '120', nombre: 'Circasia' }
        ],
        // Risaralda
        '20': [
            { id: '121', nombre: 'Pereira' },
            { id: '122', nombre: 'Dosquebradas' },
            { id: '123', nombre: 'Santa Rosa de Cabal' },
            { id: '124', nombre: 'La Virginia' },
            { id: '125', nombre: 'Belén de Umbría' },
            { id: '126', nombre: 'Quinchía' }
        ],
        // Santander
        '21': [
            { id: '127', nombre: 'Bucaramanga' },
            { id: '128', nombre: 'Floridablanca' },
            { id: '129', nombre: 'Girón' },
            { id: '130', nombre: 'Piedecuesta' },
            { id: '131', nombre: 'Barrancabermeja' },
            { id: '132', nombre: 'San Gil' },
            { id: '133', nombre: 'Socorro' }
        ],
        // Sucre
        '22': [
            { id: '134', nombre: 'Sincelejo' },
            { id: '135', nombre: 'Corozal' },
            { id: '136', nombre: 'San Marcos' },
            { id: '137', nombre: 'San Onofre' },
            { id: '138', nombre: 'Tolú' },
            { id: '139', nombre: 'Sampués' }
        ],
        // Tolima
        '23': [
            { id: '140', nombre: 'Ibagué' },
            { id: '141', nombre: 'Espinal' },
            { id: '142', nombre: 'Chaparral' },
            { id: '143', nombre: 'Mariquita' },
            { id: '144', nombre: 'Honda' },
            { id: '145', nombre: 'Líbano' },
            { id: '146', nombre: 'Melgar' }
        ],
        // Valle del Cauca
        '24': [
            { id: '147', nombre: 'Cali' },
            { id: '148', nombre: 'Buenaventura' },
            { id: '149', nombre: 'Palmira' },
            { id: '150', nombre: 'Tuluá' },
            { id: '151', nombre: 'Yumbo' },
            { id: '152', nombre: 'Jamundí' },
            { id: '153', nombre: 'Cartago' },
            { id: '154', nombre: 'Buga' },
            { id: '155', nombre: 'Candelaria' }
        ],
        // Arauca
        '25': [
            { id: '156', nombre: 'Arauca' },
            { id: '157', nombre: 'Saravena' },
            { id: '158', nombre: 'Tame' },
            { id: '159', nombre: 'Arauquita' },
            { id: '160', nombre: 'Fortul' }
        ],
        // Casanare
        '26': [
            { id: '161', nombre: 'Yopal' },
            { id: '162', nombre: 'Aguazul' },
            { id: '163', nombre: 'Villanueva' },
            { id: '164', nombre: 'Paz de Ariporo' },
            { id: '165', nombre: 'Tauramena' },
            { id: '166', nombre: 'Monterrey' }
        ],
        // Putumayo
        '27': [
            { id: '167', nombre: 'Mocoa' },
            { id: '168', nombre: 'Puerto Asís' },
            { id: '169', nombre: 'Orito' },
            { id: '170', nombre: 'Valle del Guamuez' },
            { id: '171', nombre: 'Puerto Leguízamo' },
            { id: '172', nombre: 'Villagarzón' }
        ],
        // San Andrés y Providencia
        '28': [
            { id: '173', nombre: 'San Andrés' },
            { id: '174', nombre: 'Providencia' }
        ],
        // Amazonas
        '29': [
            { id: '175', nombre: 'Leticia' },
            { id: '176', nombre: 'Puerto Nariño' }
        ],
        // Guainía
        '30': [
            { id: '177', nombre: 'Inírida' },
            { id: '178', nombre: 'Barranco Minas' },
            { id: '179', nombre: 'Mapiripana' },
            { id: '180', nombre: 'San Felipe' }
        ],
        // Guaviare
        '31': [
            { id: '181', nombre: 'San José del Guaviare' },
            { id: '182', nombre: 'El Retorno' },
            { id: '183', nombre: 'Calamar' },
            { id: '184', nombre: 'Miraflores' }
        ],
        // Vaupés
        '32': [
            { id: '185', nombre: 'Mitú' },
            { id: '186', nombre: 'Carurú' },
            { id: '187', nombre: 'Taraira' },
            { id: '188', nombre: 'Papunaua' }
        ],
        // Vichada
        '33': [
            { id: '189', nombre: 'Puerto Carreño' },
            { id: '190', nombre: 'La Primavera' },
            { id: '191', nombre: 'Santa Rosalía' },
            { id: '192', nombre: 'Cumaribo' }
        ]
    };
        // Resetear el select
        municipioSelect.innerHTML = '';

        // Opción predeterminada
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccione un municipio...';
        municipioSelect.appendChild(defaultOption);

        // Si tenemos municipios para el departamento seleccionado
        if (municipiosPorDepartamento[departamentoId]) {
            municipiosPorDepartamento[departamentoId].forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id;
                option.textContent = municipio.nombre;
                municipioSelect.appendChild(option);
            });
        } else {
            // Si no hay municipios definidos para este departamento
            const option = document.createElement('option');
            option.value = departamentoId + '01';
            option.textContent = 'Principal';
            municipioSelect.appendChild(option);
        }

        municipioSelect.disabled = false;
        console.log('Municipios cargados estáticamente para departamento: ' + departamentoId);
    }

    // CARGA DESDE API
    function cargarInstitucionesDesdeAPI() {
        institucionSelect.innerHTML = '<option value="">Cargando instituciones...</option>';
        institucionSelect.disabled = true;

        // Petición a la API
        fetch(`${API_URL}/instituciones`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener instituciones');
                }
                return response.json();
            })
            .then(data => {
                console.log('Instituciones cargadas desde API:', data);

                institucionSelect.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Seleccione una institución...';
                institucionSelect.appendChild(defaultOption);

                // Agregar las instituciones recibidas de la API
                const instituciones = Array.isArray(data) ? data : (data.data || []);
                instituciones.forEach(institucion => {
                    const option = document.createElement('option');
                    option.value = institucion.id || institucion.id_institucion;
                    option.textContent = institucion.nombre;
                    institucionSelect.appendChild(option);
                });

                institucionSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error al cargar instituciones:', error);

                // En caso de error
                institucionSelect.innerHTML = '<option value="">Error al cargar instituciones</option>';
                institucionSelect.disabled = true;

                mostrarNotificacion('error', 'Error', 'No se pudieron cargar las instituciones desde la API.');
            });
    }

    function cargarFacultades(institucionId) {
        facultadSelect.innerHTML = '<option value="">Cargando facultades...</option>';
        facultadSelect.disabled = true;

        // Petición a la API
        fetch(`${API_URL}/facultades?institucion_id=${institucionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener facultades');
                }
                return response.json();
            })
            .then(data => {
                console.log('Facultades cargadas desde API:', data);

                facultadSelect.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Seleccione una facultad...';
                facultadSelect.appendChild(defaultOption);

                // Agregar las facultades recibidas de la API
                const facultades = Array.isArray(data) ? data : (data.data || []);
                facultades.forEach(facultad => {
                    const option = document.createElement('option');
                    option.value = facultad.id || facultad.id_facultad;
                    option.textContent = facultad.nombre;
                    facultadSelect.appendChild(option);
                });

                facultadSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error al cargar facultades:', error);

                // En caso de error
                facultadSelect.innerHTML = '<option value="">Error al cargar facultades</option>';
                facultadSelect.disabled = true;

                mostrarNotificacion('error', 'Error', 'No se pudieron cargar las facultades desde la API.');
            });
    }

    // Gestión de país "Otro"
    function mostrarCampoPaisPersonalizado() {
        // Deshabilitar departamentos y municipios
        departamentoSelect.innerHTML = '<option value="">No aplica para país extranjero</option>';
        departamentoSelect.disabled = true;
        municipioSelect.innerHTML = '<option value="">No aplica para país extranjero</option>';
        municipioSelect.disabled = true;

        // Crear campo de texto para país personalizado si no existe
        if (!document.getElementById('pais_otro_container')) {
            const inputContainer = document.createElement('div');
            inputContainer.className = 'col-md-12 mt-3';
            inputContainer.id = 'pais_otro_container';

            inputContainer.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Ha seleccionado "Otro" como país. Por favor ingrese el nombre del país.
                </div>
                <label for="pais_otro" class="form-label">Nombre del país <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-globe-americas text-info"></i></span>
                    <input type="text" class="form-control" id="pais_otro" name="pais_otro" placeholder="Ingrese el nombre del país">
                </div>
            `;

            // Insertar después del select de países
            const paisContainer = paisSelect.closest('.col-md-4');
            if (paisContainer && paisContainer.parentNode) {
                paisContainer.parentNode.insertBefore(inputContainer, paisContainer.nextSibling);
            } else {
                // Fallback si no encontramos el elemento padre
                const locationSection = document.querySelector('#institucional-section .row.g-3');
                if (locationSection) {
                    locationSection.appendChild(inputContainer);
                }
            }
        } else {
            document.getElementById('pais_otro_container').style.display = 'block';
        }
    }

    function ocultarCampoPaisPersonalizado() {
        const paisOtroContainer = document.getElementById('pais_otro_container');
        if (paisOtroContainer) {
            paisOtroContainer.style.display = 'none';
        }
    }

    function configurarEventos() {
        // Botones de navegación
        btnNextInstitucional.addEventListener('click', function() {
            if (validarSeccionPersonal()) {
                mostrarSeccion('institucional');
            }
        });

        btnPrevPersonal.addEventListener('click', function() {
            mostrarSeccion('personal');
        });

        btnNextAcceso.addEventListener('click', function() {
            if (validarSeccionInstitucional()) {
                mostrarSeccion('acceso');
            }
        });

        btnPrevInstitucional.addEventListener('click', function() {
            mostrarSeccion('institucional');
        });

        // Validación de campos únicos
        numeroIdentificacion.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                // Verificar que no sea un email (contiene @)
                if (!this.value.includes('@')) {
                    verificarIdentificacionExistente(this.value);
                }
            }
        });

        email.addEventListener('blur', function() {
            if (this.value.trim() !== '') {
                verificarEmailExistente(this.value);
            }
        });

        // Evento del select de países
        paisSelect.addEventListener('change', function() {
            if (this.value === '1') { // Colombia
                cargarDepartamentosEstaticos();
                ocultarCampoPaisPersonalizado();
            } else if (this.value === '2') { // Otro
                mostrarCampoPaisPersonalizado();
            } else { // Sin selección
                departamentoSelect.innerHTML = '<option value="">Seleccione un país primero</option>';
                departamentoSelect.disabled = true;
                municipioSelect.innerHTML = '<option value="">Seleccione un departamento primero</option>';
                municipioSelect.disabled = true;
                ocultarCampoPaisPersonalizado();
            }
        });

        // Evento para departamentos
        departamentoSelect.addEventListener('change', function() {
            if (this.value) {
                cargarMunicipiosEstaticos(this.value);
            } else {
                municipioSelect.innerHTML = '<option value="">Seleccione un departamento primero</option>';
                municipioSelect.disabled = true;
            }
        });

        // Evento para instituciones
        institucionSelect.addEventListener('change', function() {
            if (this.value) {
                cargarFacultades(this.value);
            } else {
                facultadSelect.innerHTML = '<option value="">Seleccione una institución primero</option>';
                facultadSelect.disabled = true;
            }
        });

        // Envío del formulario
        formNuevoUsuario.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validarFormulario()) {
                guardarUsuario();
            }
        });
    }

    // Configurar navegación de pasos
    function configurarNavegacionPasos() {
        stepItems.personal.addEventListener('click', function() {
            if (this.classList.contains('completed') || this.classList.contains('active')) {
                mostrarSeccion('personal');
            }
        });

        stepItems.institucional.addEventListener('click', function() {
            if (this.classList.contains('completed') || stepItems.personal.classList.contains('completed')) {
                if (validarSeccionPersonal()) {
                    mostrarSeccion('institucional');
                }
            } else {
                // Notificar que debe completar el paso anterior
                Swal.fire({
                    title: 'Información requerida',
                    text: 'Debe completar la información personal primero',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            }
        });

        stepItems.acceso.addEventListener('click', function() {
            if (this.classList.contains('completed') || stepItems.institucional.classList.contains('completed')) {
                if (validarSeccionPersonal() && validarSeccionInstitucional()) {
                    mostrarSeccion('acceso');
                }
            } else {
                // Notificar que debe completar los pasos anteriores
                Swal.fire({
                    title: 'Información requerida',
                    text: 'Debe completar los pasos anteriores primero',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    }

    // Configurar toggle de contraseñas
    function configurarTogglePassword() {
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const target = document.getElementById(targetId);

                if (target.type === 'password') {
                    target.type = 'text';
                    this.querySelector('i').classList.remove('fa-eye');
                    this.querySelector('i').classList.add('fa-eye-slash');
                } else {
                    target.type = 'password';
                    this.querySelector('i').classList.remove('fa-eye-slash');
                    this.querySelector('i').classList.add('fa-eye');
                }
            });
        });
    }

    // Configurar estado de usuario
    function configurarEstadoUsuario() {
        estadoSwitch.addEventListener('change', function() {
            if (this.checked) {
                estadoLabel.innerHTML = '<i class="fas fa-toggle-on me-2"></i> Usuario Activo';
                estadoLabel.classList.remove('text-danger');
                estadoLabel.classList.add('text-success');
            } else {
                estadoLabel.innerHTML = '<i class="fas fa-toggle-off me-2"></i> Usuario Inactivo';
                estadoLabel.classList.remove('text-success');
                estadoLabel.classList.add('text-danger');
            }
        });
    }

    // Función para mostrar una sección específica
    function mostrarSeccion(seccion) {
        // Ocultar todas las secciones
        for (const key in sections) {
            sections[key].style.display = 'none';
        }

        // Mostrar la sección seleccionada
        sections[seccion].style.display = 'block';

        // Actualizar pasos circulares
        actualizarPasosCirculares(seccion);

        // Actualizar la barra de progreso
        actualizarProgreso(seccion);
    }

    // Actualizar pasos circulares
    function actualizarPasosCirculares(seccionActiva) {
        // Restablecer todos los pasos
        for (const key in stepItems) {
            stepItems[key].classList.remove('active', 'completed');
        }

        // Marcar el paso actual como activo
        stepItems[seccionActiva].classList.add('active');

        // Marcar los pasos completados
        if (seccionActiva === 'institucional' || seccionActiva === 'acceso') {
            stepItems.personal.classList.add('completed');
        }

        if (seccionActiva === 'acceso') {
            stepItems.institucional.classList.add('completed');
        }

        // Actualizar la barra de progreso de los steps
        if (seccionActiva === 'institucional') {
            stepsProgressBar.style.width = '50%';
        } else if (seccionActiva === 'acceso') {
            stepsProgressBar.style.width = '100%';
        } else {
            stepsProgressBar.style.width = '0%';
        }
    }

    // Actualizar la barra de progreso
    function actualizarProgreso(seccion) {
        let width, text;

        switch(seccion) {
            case 'personal':
                width = '33%';
                text = '<i class="fas fa-tasks me-2"></i> Paso 1 de 3';
                break;
            case 'institucional':
                width = '66%';
                text = '<i class="fas fa-tasks me-2"></i> Paso 2 de 3';
                break;
            case 'acceso':
                width = '100%';
                text = '<i class="fas fa-tasks me-2"></i> Paso 3 de 3';
                break;
        }

        // Animar la barra de progreso
        progressBar.style.transition = 'width 0.3s ease';
        progressBar.style.width = width;
        progressText.innerHTML = text;
    }

    // Validación de sección personal
    function validarSeccionPersonal() {
        // Obtener campos obligatorios
        const primerNombre = document.getElementById('primer_nombre').value;
        const primerApellido = document.getElementById('primer_apellido').value;
        const tipoIdentificacion = document.getElementById('tipo_identificacion').value;
        const numeroIdentificacion = document.getElementById('numero_identificacion').value;

        // Validar campos
        if (!primerNombre || !primerApellido || !tipoIdentificacion || !numeroIdentificacion) {
            Swal.fire({
                title: 'Campos obligatorios',
                text: 'Debe completar todos los campos obligatorios',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Verificar si hay error de identificación existente
        if (document.getElementById('identificacion-existe').style.display === 'block') {
            Swal.fire({
                title: 'Identificación existente',
                text: 'La identificación ingresada ya está registrada en el sistema',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        return true;
    }

    // Validación de sección institucional
    function validarSeccionInstitucional() {
        // Validar selección de rol
        const rolId = document.getElementById('rol_id').value;

        if (!rolId) {
            document.getElementById('rol-feedback').style.display = 'block';

            Swal.fire({
                title: 'Rol requerido',
                text: 'Debe seleccionar un rol para el usuario',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });

            return false;
        }

        return true;
    }

    // Validar formulario completo
    function validarFormulario() {
        // Validar secciones previas
        if (!validarSeccionPersonal() || !validarSeccionInstitucional()) {
            return false;
        }

        // Validar campos de acceso
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        // Validar correo
        if (!email) {
            Swal.fire({
                title: 'Correo requerido',
                text: 'Debe ingresar un correo electrónico',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Validar formato de correo
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                title: 'Correo inválido',
                text: 'Ingrese un formato de correo electrónico válido',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Verificar si hay error de email existente
        if (document.getElementById('email-existe').style.display === 'block') {
            Swal.fire({
                title: 'Correo existente',
                text: 'El correo electrónico ingresado ya está registrado en el sistema',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Validar contraseña
        if (!password || password.length < 8) {
            Swal.fire({
                title: 'Contraseña inválida',
                text: 'La contraseña debe tener al menos 8 caracteres',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Validar confirmación
        if (password !== passwordConfirmation) {
            Swal.fire({
                title: 'Las contraseñas no coinciden',
                text: 'La confirmación de contraseña debe coincidir con la contraseña ingresada',
                icon: 'warning',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        // Validar país "Otro"
        if (paisSelect.value === '2') {
            const paisOtro = document.getElementById('pais_otro');
            if (!paisOtro || !paisOtro.value.trim()) {
                Swal.fire({
                    title: 'País requerido',
                    text: 'Debe ingresar el nombre del país',
                    icon: 'warning',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }
        }

        return true;
    }

    // Verificar identificación existente a través de la API
    function verificarIdentificacionExistente(numeroIdentificacion) {
        // Comprobamos que no sea un email antes de verificar
        if (numeroIdentificacion.includes('@')) {
            console.warn('Se intentó verificar un email como identificación, omitiendo verificación');
            return;
        }

        fetch(`${API_URL}/usuarios/verificar-identificacion/${numeroIdentificacion}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la verificación');
                }
                return response.json();
            })
            .then(data => {
                const mensajeError = document.getElementById('identificacion-existe');

                if (data.existe) {
                    mensajeError.style.display = 'block';
                    document.getElementById('numero_identificacion').classList.add('is-invalid');
                } else {
                    mensajeError.style.display = 'none';
                    document.getElementById('numero_identificacion').classList.remove('is-invalid');
                }
            })
            .catch(error => {
                console.error('Error al verificar identificación:', error);
                // No mostrar error al usuario, solo registrar en consola
            });
    }

    // Verificar email existente a través de la API
    function verificarEmailExistente(email) {
        fetch(`${API_URL}/usuarios/verificar-email/${email}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la verificación');
                }
                return response.json();
            })
            .then(data => {
                const mensajeError = document.getElementById('email-existe');

                if (data.existe) {
                    mensajeError.style.display = 'block';
                    document.getElementById('email').classList.add('is-invalid');
                } else {
                    mensajeError.style.display = 'none';
                    document.getElementById('email').classList.remove('is-invalid');
                }
            })
            .catch(error => {
                console.error('Error al verificar email:', error);
                // No mostrar error al usuario, solo registrar en consola
            });
    }

    // Mostrar notificación
    function mostrarNotificacion(tipo, titulo, mensaje) {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
            confirmButtonText: 'Entendido'
        });
    }

    // Guardar usuario a través de la API
    function guardarUsuario() {
        // Mostrar loader
        Swal.fire({
            title: 'Guardando usuario',
            text: 'Procesando la información...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Obtener datos del formulario
        const formData = new FormData(formNuevoUsuario);

        // Convertir FormData a objeto para enviar como JSON
        const usuario = {};
        formData.forEach((value, key) => {
            usuario[key] = value;
        });

        // Ajustar valores booleanos
        usuario.activo = formData.get('activo') === 'on';

        // Manejar país "Otro"
        if (paisSelect.value === '2') {
            const paisOtro = document.getElementById('pais_otro');
            if (paisOtro && paisOtro.value.trim()) {
                usuario.pais_nombre = paisOtro.value.trim();
            }
        } else if (paisSelect.value === '1') {
            usuario.pais_nombre = 'Colombia';
        }

        // Asegurar que se incluya la información de departamento y municipio
        if (departamentoSelect.value) {
            usuario.departamento_id = departamentoSelect.value;
            usuario.departamento_nombre = departamentoSelect.options[departamentoSelect.selectedIndex].text;
        }

        if (municipioSelect.value) {
            usuario.municipio_id = municipioSelect.value;
            usuario.municipio_nombre = municipioSelect.options[municipioSelect.selectedIndex].text;
        }

        // Incluir información adicional de selects
        if (institucionSelect.value) {
            usuario.institucion_origen_id = institucionSelect.value;
            usuario.institucion_nombre = institucionSelect.options[institucionSelect.selectedIndex].text;
        }

        if (facultadSelect.value) {
            usuario.facultad_id = facultadSelect.value;
            usuario.facultad_nombre = facultadSelect.options[facultadSelect.selectedIndex].text;
        }

        // Obtener el nombre del rol desde los datos de la API
        if (rolIdInput.value) {
            const rolSeleccionado = rolesAPI.find(r => r.id_rol == rolIdInput.value);
            if (rolSeleccionado) {
                usuario.rol_nombre = rolSeleccionado.nombre;
            }
        }

        console.log('Datos a enviar:', usuario);

        // Realizar la petición POST a la API
        fetch(`${API_URL}/usuarios`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(usuario)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Error al guardar el usuario');
                });
            }
            return response.json();
        })
        .then(data => {
            // Cerrar el loader
            Swal.close();

            // Mostrar mensaje de éxito
            Swal.fire({
                title: 'Usuario guardado',
                text: 'El usuario ha sido registrado exitosamente',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                // Redirigir a la lista de usuarios
                if (result.isConfirmed) {
                    window.location.href = '/usuarios';
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);

            // Cerrar el loader y mostrar error
            Swal.fire({
                title: 'Error',
                text: error.message || 'No se pudo guardar el usuario. Por favor, intente nuevamente.',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
        });
    }
});
</script>
@endsection




@extends('admin.layouts.appadmin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header institucional -->
    <div class="container-fluid py-3 mb-4" style="background-color: #003366; font-family: 'Source Sans Pro', sans-serif;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                </div>
                <div class="col-md-8 text-center">
                    <h1 class="display-5 fw-bold mb-0" style="color: white !important;">Crear Instituciones y Programas</h1>
                    <p class="lead mb-0" style="color: white !important;">Gestión institucional</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-4">
                        <!-- Steps mejorados -->
                        <div class="steps-container mb-4">
                            <div class="steps-wrapper">
                                <div class="step active" id="step-institucion-indicator">
                                    <div class="step-circle">
                                        <i class="step-icon fas fa-university"></i>
                                    </div>
                                    <div class="step-line"></div>
                                    <div class="step-label">Institución</div>
                                </div>
                                <div class="step" id="step-programa-indicator">
                                    <div class="step-circle">
                                        <i class="step-icon fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="step-label">Programa</div>
                                </div>
                            </div>
                            <div class="steps-progress">
                                <div class="steps-progress-bar"></div>
                            </div>
                        </div>

                        <!-- Paso 1: Institución -->
                        <div id="step1" class="step-content active">
                            <h4 class="text-center mb-4 text-primary">Información de la Institución</h4>

                            <form id="formInstitucion" class="needs-validation">
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-university"></i> Datos Básicos
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="nombre_institucion" placeholder="Nombre" required>
                                        <label for="nombre_institucion">Nombre de la Institución</label>
                                        <div class="invalid-feedback">Este campo es obligatorio</div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="codigo_ies" placeholder="Código IES">
                                        <label for="codigo_ies">Código IES</label>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="tipo_institucion" required>
                                            <option value="">Seleccione el tipo</option>
                                            <option value="Mixta" selected>Mixta</option>
                                            <option value="Universitaria">Universitaria</option>
                                            <option value="SENA">SENA</option>
                                        </select>
                                        <label for="tipo_institucion">Tipo de Institución</label>
                                        <div class="invalid-feedback">Seleccione un tipo de institución</div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-map-marker-alt"></i> Ubicación
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <select class="form-select" id="pais" required>
                                                    <option value="">Seleccione un país</option>
                                                    <option value="1" selected>Colombia</option>
                                                    <option value="2">Otro</option>
                                                </select>
                                                <label for="pais">País</label>
                                                <div class="invalid-feedback">Seleccione un país</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="departamento-container">
                                            <div class="form-floating">
                                                <select class="form-select" id="departamento" required>
                                                    <option value="">Cargando departamentos...</option>
                                                </select>
                                                <label for="departamento">Departamento</label>
                                                <div class="invalid-feedback">Seleccione un departamento</div>
                                            </div>
                                            <div class="mt-2" id="otro-departamento-container" style="display:none;">
                                                <input type="text" class="form-control" id="otro_departamento" placeholder="Ingrese departamento">
                                                <div class="invalid-feedback">Ingrese el departamento</div>
                                            </div>
                                        </div>

                                        <div class="col-md-4" id="municipio-container">
                                            <div class="form-floating">
                                                <select class="form-select" id="municipio" required>
                                                    <option value="">Seleccione primero un departamento</option>
                                                </select>
                                                <label for="municipio">Municipio</label>
                                                <div class="invalid-feedback">Seleccione un municipio</div>
                                            </div>
                                            <div class="mt-2" id="otro-municipio-container" style="display:none;">
                                                <input type="text" class="form-control" id="otro_municipio" placeholder="Ingrese municipio">
                                                <div class="invalid-feedback">Ingrese el municipio</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-group-nav">
                                    <a href="/admin/instituciones" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Volver
                                    </a>
                                    <button type="button" class="btn btn-primary" id="btnGuardarInstitucion">
                                        <i class="fas fa-save me-2"></i> Guardar Institución
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="nextStep(1)">
                                        Continuar <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Paso 2: Programa -->
                        <div id="step2" class="step-content">
                            <h4 class="text-center mb-4 text-primary">Información del Programa</h4>

                            <form id="formPrograma" class="needs-validation">
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-building"></i> Institución Relacionada
                                    </div>

                                    <div class="form-floating mb-4">
                                        <select class="form-select" id="institucion_seleccionada" required>
                                            <option value="">Cargando instituciones...</option>
                                        </select>
                                        <label for="institucion_seleccionada">Institución</label>
                                        <div class="invalid-feedback">Seleccione una institución</div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-graduation-cap"></i> Datos del Programa
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="text" class="form-control" id="nombre_programa" placeholder="Nombre" required>
                                        <label for="nombre_programa">Nombre del Programa</label>
                                        <div class="invalid-feedback">Ingrese el nombre del programa</div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="tipo_formacion" required>
                                                    <option value="">Seleccione el tipo</option>
                                                    <option value="Profesional" selected>Profesional</option>
                                                    <option value="Técnico">Técnico</option>
                                                    <option value="Tecnólogo">Tecnólogo</option>
                                                </select>
                                                <label for="tipo_formacion">Tipo de Formación</label>
                                                <div class="invalid-feedback">Seleccione un tipo de formación</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <select class="form-select" id="metodologia" required>
                                                    <option value="">Seleccione la metodología</option>
                                                    <option value="Presencial" selected>Presencial</option>
                                                    <option value="Virtual">Virtual</option>
                                                    <option value="Híbrido">Híbrido</option>
                                                </select>
                                                <label for="metodologia">Metodología</label>
                                                <div class="invalid-feedback">Seleccione una metodología</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input type="text" class="form-control" id="codigo_snies" placeholder="Código SNIES">
                                        <label for="codigo_snies">Código SNIES</label>
                                    </div>
                                </div>

                                <div class="btn-group-nav">
                                    <button type="button" class="btn btn-outline-secondary" onclick="previousStep(2)">
                                        <i class="fas fa-arrow-left me-2"></i> Volver
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="guardarPrograma()">
                                        <i class="fas fa-save me-2"></i> Guardar Programa
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asegurarnos que Font Awesome esté incluido -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --primary-color: #3349aa;
            --primary-light: #5698e4;
            --secondary-color: #3349aa;
            --success-color: #5698e4;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: var(--transition);
            margin-bottom: 3rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-light));
            border-bottom: 0;
            padding: 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-title {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Steps circulares institucionales (igual a usuarios_crear) */
        .steps-container { position: relative; padding: 0 0 25px; }
.steps-wrapper { display: flex; justify-content: space-between; position: relative; z-index: 1; }
.steps-progress { position: absolute; top: 40px; left: 0; right: 0; height: 3px; background-color: #e9ecef; z-index: 0; }
.steps-progress-bar { height: 100%; background-color: #0d6efd; width: 0%; transition: width 0.3s ease; }
.step { display: flex; flex-direction: column; align-items: center; position: relative; width: 50%; }
.step-circle { width: 80px; height: 80px; border-radius: 50%; background-color: #f8f9fa; border: 3px solid #dee2e6; display: flex; flex-direction: column; justify-content: center; align-items: center; margin-bottom: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); position: relative; transition: all 0.3s ease; }
.step.active .step-circle { border-color: #0d6efd; background-color: #e7f1ff; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2); }
.step.completed .step-circle { border-color: #198754; background-color: #d1e7dd; }
.step-icon { font-size: 1.8rem; color: #6c757d; margin-top: 15px; }
.step.active .step-icon { color: #0d6efd; }
.step.completed .step-icon { color: #198754; }
.step-label { font-size: 0.95rem; color: #6c757d; font-weight: 500; text-align: center; }
.step.active .step-label { color: #0d6efd; font-weight: 600; }
.step.completed .step-label { color: #198754; }
@media (max-width: 768px) { .step-circle { width: 60px; height: 60px; } .step-icon { font-size: 1.3rem; margin-top: 12px; } .step-label { font-size: 0.8rem; } }

        /* Estilos para el contenido de los pasos */
        .step-content {
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos para los elementos del formulario */
        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: var(--transition);
            height: calc(3.5rem + 2px);
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
        }

        .form-floating > label {
            padding: 1rem;
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            transition: var(--transition);
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #3da8cc;
            border-color: #3da8cc;
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* Estilos para las secciones del formulario */
        .form-section {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary-color);
        }

        .form-section-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-group-nav {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        /* Notificaciones institucionales mejoradas */
        .notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 340px;
            max-width: 90vw;
            z-index: 9999;
            padding: 1.5rem 2.5rem 1.5rem 1.5rem;
            border-radius: 1.2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            display: flex;
            align-items: center;
            font-size: 1.15rem;
            font-weight: 500;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s, transform 0.3s;
            animation: fadeInNotif 0.4s forwards;
        }
        .notification.visible {
            opacity: 1;
            pointer-events: auto;
        }
        .notification .notif-icon {
            margin-right: 1.2rem;
            font-size: 2.1rem;
            flex-shrink: 0;
        }
        .notification.success {
            background: linear-gradient(135deg, #0e5ba7 60%, #10b981 100%);
            color: #fff;
            border-left: 7px solid #10b981;
        }
        .notification.error {
            background: linear-gradient(135deg, #b91c1c 60%, #ef4444 100%);
            color: #fff;
            border-left: 7px solid #ef4444;
        }
        .notification.warning {
            background: linear-gradient(135deg, #f59e42 60%, #fbbf24 100%);
            color: #fff;
            border-left: 7px solid #fbbf24;
        }
        .notification.info {
            background: linear-gradient(135deg, #2563eb 60%, #38bdf8 100%);
            color: #fff;
            border-left: 7px solid #38bdf8;
        }
        .notification .notif-close {
            margin-left: 1.5rem;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .notification .notif-close:hover {
            opacity: 1;
        }
        @keyframes fadeInNotif {
            from { opacity: 0; transform: translate(-50%, -60%) scale(0.95); }
            to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }
    </style>

    <div id="notification" class="notification" role="alert" aria-live="assertive" style="display:none;"></div>

    <script>
        // Variables para almacenar los IDs creados
let institucionId = null;
let programaId = null;
let apiBaseUrl = 'https://homologacionesback.educarenemociones.com/api';

// Lista de departamentos de Colombia
const departamentosColombia = [
    { id: 1, nombre: "Antioquia" },
    { id: 2, nombre: "Atlántico" },
    { id: 3, nombre: "Bogotá D.C." },
    { id: 4, nombre: "Bolívar" },
    { id: 5, nombre: "Boyacá" },
    { id: 6, nombre: "Caldas" },
    { id: 7, nombre: "Caquetá" },
    { id: 8, nombre: "Cauca" },
    { id: 9, nombre: "Cesar" },
    { id: 10, nombre: "Córdoba" },
    { id: 11, nombre: "Cundinamarca" },
    { id: 12, nombre: "Chocó" },
    { id: 13, nombre: "Huila" },
    { id: 14, nombre: "La Guajira" },
    { id: 15, nombre: "Magdalena" },
    { id: 16, nombre: "Meta" },
    { id: 17, nombre: "Nariño" },
    { id: 18, nombre: "Norte de Santander" },
    { id: 19, nombre: "Quindío" },
    { id: 20, nombre: "Risaralda" },
    { id: 21, nombre: "Santander" },
    { id: 22, nombre: "Sucre" },
    { id: 23, nombre: "Tolima" },
    { id: 24, nombre: "Valle del Cauca" },
    { id: 25, nombre: "Arauca" },
    { id: 26, nombre: "Casanare" },
    { id: 27, nombre: "Putumayo" },
    { id: 28, nombre: "San Andrés y Providencia" },
    { id: 29, nombre: "Amazonas" },
    { id: 30, nombre: "Guainía" },
    { id: 31, nombre: "Guaviare" },
    { id: 32, nombre: "Vaupés" },
    { id: 33, nombre: "Vichada" }
];

// Mapa de municipios por departamento (completo)
const municipiosPorDepartamento = {
    1: [ // Antioquia
        { id: 1, nombre: "Medellín" },
        { id: 2, nombre: "Bello" },
        { id: 3, nombre: "Envigado" },
        { id: 4, nombre: "Itagüí" },
        { id: 5, nombre: "Rionegro" },
        { id: 6, nombre: "Apartadó" },
        { id: 7, nombre: "Turbo" },
        { id: 8, nombre: "Caucasia" },
        { id: 9, nombre: "La Estrella" },
        { id: 10, nombre: "Sabaneta" }
    ],
    2: [ // Atlántico
        { id: 11, nombre: "Barranquilla" },
        { id: 12, nombre: "Soledad" },
        { id: 13, nombre: "Malambo" },
        { id: 14, nombre: "Sabanalarga" },
        { id: 15, nombre: "Baranoa" },
        { id: 16, nombre: "Puerto Colombia" },
        { id: 17, nombre: "Galapa" },
        { id: 18, nombre: "Santo Tomás" }
    ],
    3: [ // Bogotá D.C.
        { id: 19, nombre: "Bogotá" }
    ],
    4: [ // Bolívar
        { id: 20, nombre: "Cartagena" },
        { id: 21, nombre: "Magangué" },
        { id: 22, nombre: "El Carmen de Bolívar" },
        { id: 23, nombre: "Turbaco" },
        { id: 24, nombre: "Arjona" },
        { id: 25, nombre: "María La Baja" }
    ],
    5: [ // Boyacá
        { id: 26, nombre: "Tunja" },
        { id: 27, nombre: "Duitama" },
        { id: 28, nombre: "Sogamoso" },
        { id: 29, nombre: "Chiquinquirá" },
        { id: 30, nombre: "Paipa" },
        { id: 31, nombre: "Moniquirá" },
        { id: 32, nombre: "Villa de Leyva" }
    ],
    6: [ // Caldas
        { id: 33, nombre: "Manizales" },
        { id: 34, nombre: "La Dorada" },
        { id: 35, nombre: "Chinchiná" },
        { id: 36, nombre: "Villamaría" },
        { id: 37, nombre: "Anserma" },
        { id: 38, nombre: "Riosucio" }
    ],
    7: [ // Caquetá
        { id: 39, nombre: "Florencia" },
        { id: 40, nombre: "San Vicente del Caguán" },
        { id: 41, nombre: "Puerto Rico" },
        { id: 42, nombre: "El Doncello" },
        { id: 43, nombre: "Belén de los Andaquíes" }
    ],
    8: [ // Cauca
        { id: 44, nombre: "Popayán" },
        { id: 45, nombre: "Santander de Quilichao" },
        { id: 46, nombre: "Puerto Tejada" },
        { id: 47, nombre: "Patía" },
        { id: 48, nombre: "Miranda" },
        { id: 49, nombre: "Caloto" },
        { id: 50, nombre: "Piendamó" }
    ],
    9: [ // Cesar
        { id: 51, nombre: "Valledupar" },
        { id: 52, nombre: "Aguachica" },
        { id: 53, nombre: "Agustín Codazzi" },
        { id: 54, nombre: "Bosconia" },
        { id: 55, nombre: "La Paz" },
        { id: 56, nombre: "Chiriguaná" }
    ],
    10: [ // Córdoba
        { id: 57, nombre: "Montería" },
        { id: 58, nombre: "Cereté" },
        { id: 59, nombre: "Lorica" },
        { id: 60, nombre: "Sahagún" },
        { id: 61, nombre: "Planeta Rica" },
        { id: 62, nombre: "Montelíbano" },
        { id: 63, nombre: "Tierralta" }
    ],
    11: [ // Cundinamarca
        { id: 64, nombre: "Soacha" },
        { id: 65, nombre: "Facatativá" },
        { id: 66, nombre: "Zipaquirá" },
        { id: 67, nombre: "Chía" },
        { id: 68, nombre: "Mosquera" },
        { id: 69, nombre: "Madrid" },
        { id: 70, nombre: "Funza" },
        { id: 71, nombre: "Cajicá" },
        { id: 72, nombre: "Girardot" }
    ],
    12: [ // Chocó
        { id: 73, nombre: "Quibdó" },
        { id: 74, nombre: "Istmina" },
        { id: 75, nombre: "Tadó" },
        { id: 76, nombre: "Acandí" },
        { id: 77, nombre: "Bahía Solano" },
        { id: 78, nombre: "Nuquí" }
    ],
    13: [ // Huila
        { id: 79, nombre: "Neiva" },
        { id: 80, nombre: "Pitalito" },
        { id: 81, nombre: "Garzón" },
        { id: 82, nombre: "La Plata" },
        { id: 83, nombre: "Campoalegre" },
        { id: 84, nombre: "Gigante" }
    ],
    14: [ // La Guajira
        { id: 85, nombre: "Riohacha" },
        { id: 86, nombre: "Maicao" },
        { id: 87, nombre: "Uribia" },
        { id: 88, nombre: "Manaure" },
        { id: 89, nombre: "Fonseca" },
        { id: 90, nombre: "San Juan del Cesar" }
    ],
    15: [ // Magdalena
        { id: 91, nombre: "Santa Marta" },
        { id: 92, nombre: "Ciénaga" },
        { id: 93, nombre: "Fundación" },
        { id: 94, nombre: "Plato" },
        { id: 95, nombre: "El Banco" },
        { id: 96, nombre: "Zona Bananera" }
    ],
    16: [ // Meta
        { id: 97, nombre: "Villavicencio" },
        { id: 98, nombre: "Acacías" },
        { id: 99, nombre: "Granada" },
        { id: 100, nombre: "Puerto López" },
        { id: 101, nombre: "La Macarena" },
        { id: 102, nombre: "San Martín" }
    ],
    17: [ // Nariño
        { id: 103, nombre: "Pasto" },
        { id: 104, nombre: "Ipiales" },
        { id: 105, nombre: "Tumaco" },
        { id: 106, nombre: "Túquerres" },
        { id: 107, nombre: "La Unión" },
        { id: 108, nombre: "Samaniego" }
    ],
    18: [ // Norte de Santander
        { id: 109, nombre: "Cúcuta" },
        { id: 110, nombre: "Ocaña" },
        { id: 111, nombre: "Pamplona" },
        { id: 112, nombre: "Villa del Rosario" },
        { id: 113, nombre: "Los Patios" },
        { id: 114, nombre: "Tibú" }
    ],
    19: [ // Quindío
        { id: 115, nombre: "Armenia" },
        { id: 116, nombre: "Calarcá" },
        { id: 117, nombre: "Montenegro" },
        { id: 118, nombre: "Quimbaya" },
        { id: 119, nombre: "La Tebaida" },
        { id: 120, nombre: "Circasia" }
    ],
    20: [ // Risaralda
        { id: 121, nombre: "Pereira" },
        { id: 122, nombre: "Dosquebradas" },
        { id: 123, nombre: "Santa Rosa de Cabal" },
        { id: 124, nombre: "La Virginia" },
        { id: 125, nombre: "Belén de Umbría" },
        { id: 126, nombre: "Quinchía" }
    ],
    21: [ // Santander
        { id: 127, nombre: "Bucaramanga" },
        { id: 128, nombre: "Floridablanca" },
        { id: 129, nombre: "Girón" },
        { id: 130, nombre: "Piedecuesta" },
        { id: 131, nombre: "Barrancabermeja" },
        { id: 132, nombre: "San Gil" },
        { id: 133, nombre: "Socorro" }
    ],
    22: [ // Sucre
        { id: 134, nombre: "Sincelejo" },
        { id: 135, nombre: "Corozal" },
        { id: 136, nombre: "San Marcos" },
        { id: 137, nombre: "San Onofre" },
        { id: 138, nombre: "Tolú" },
        { id: 139, nombre: "Sampués" }
    ],
    23: [ // Tolima
        { id: 140, nombre: "Ibagué" },
        { id: 141, nombre: "Espinal" },
        { id: 142, nombre: "Chaparral" },
        { id: 143, nombre: "Mariquita" },
        { id: 144, nombre: "Honda" },
        { id: 145, nombre: "Líbano" },
        { id: 146, nombre: "Melgar" }
    ],
    24: [ // Valle del Cauca
        { id: 147, nombre: "Cali" },
        { id: 148, nombre: "Buenaventura" },
        { id: 149, nombre: "Palmira" },
        { id: 150, nombre: "Tuluá" },
        { id: 151, nombre: "Yumbo" },
        { id: 152, nombre: "Jamundí" },
        { id: 153, nombre: "Cartago" },
        { id: 154, nombre: "Buga" },
        { id: 155, nombre: "Candelaria" }
    ],
    25: [ // Arauca
        { id: 156, nombre: "Arauca" },
        { id: 157, nombre: "Saravena" },
        { id: 158, nombre: "Tame" },
        { id: 159, nombre: "Arauquita" },
        { id: 160, nombre: "Fortul" }
    ],
    26: [ // Casanare
        { id: 161, nombre: "Yopal" },
        { id: 162, nombre: "Aguazul" },
        { id: 163, nombre: "Villanueva" },
        { id: 164, nombre: "Paz de Ariporo" },
        { id: 165, nombre: "Tauramena" },
        { id: 166, nombre: "Monterrey" }
    ],
    27: [ // Putumayo
        { id: 167, nombre: "Mocoa" },
        { id: 168, nombre: "Puerto Asís" },
        { id: 169, nombre: "Orito" },
        { id: 170, nombre: "Valle del Guamuez" },
        { id: 171, nombre: "Puerto Leguízamo" },
        { id: 172, nombre: "Villagarzón" }
    ],
    28: [ // San Andrés y Providencia
        { id: 173, nombre: "San Andrés" },
        { id: 174, nombre: "Providencia" }
    ],
    29: [ // Amazonas
        { id: 175, nombre: "Leticia" },
        { id: 176, nombre: "Puerto Nariño" }
    ],
    30: [ // Guainía
        { id: 177, nombre: "Inírida" },
        { id: 178, nombre: "Barranco Minas" },
        { id: 179, nombre: "Mapiripana" },
        { id: 180, nombre: "San Felipe" }
    ],
    31: [ // Guaviare
        { id: 181, nombre: "San José del Guaviare" },
        { id: 182, nombre: "El Retorno" },
        { id: 183, nombre: "Calamar" },
        { id: 184, nombre: "Miraflores" }
    ],
    32: [ // Vaupés
        { id: 185, nombre: "Mitú" },
        { id: 186, nombre: "Carurú" },
        { id: 187, nombre: "Taraira" },
        { id: 188, nombre: "Papunaua" }
    ],
    33: [ // Vichada
        { id: 189, nombre: "Puerto Carreño" },
        { id: 190, nombre: "La Primavera" },
        { id: 191, nombre: "Santa Rosalía" },
        { id: 192, nombre: "Cumaribo" }
    ]
};

// Función para obtener el token CSRF
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Función para realizar peticiones AJAX con manejo de errores mejorado
async function fetchWithErrorHandling(url, options = {}) {
    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            let errorMessage = `Error HTTP ${response.status}`;
            try {
                const errorData = await response.json();
                if (errorData.message) errorMessage = errorData.message;
                else if (errorData.error) errorMessage = errorData.error;
            } catch (parseError) {
                errorMessage = `${errorMessage}: ${response.statusText}`;
            }
            throw new Error(errorMessage);
        }
        try {
            return await response.json();
        } catch (jsonError) {
            return { success: true, message: await response.text() };
        }
    } catch (error) {
        console.error('Error en la petición:', error);
        mostrarNotificacion('Error de conexión: ' + error.message, 'error');
        throw error;
    }
}

// Función para mostrar notificaciones institucionales mejoradas
function mostrarNotificacion(mensaje, tipo = 'info') {
    const notif = document.getElementById('notification');
    notif.className = 'notification ' + tipo + ' visible';
    notif.innerHTML = `<span class='notif-icon'>${tipo === 'success' ? '✔️' : tipo === 'error' ? '❌' : tipo === 'warning' ? '⚠️' : 'ℹ️'}</span><span>${mensaje}</span><button class='notif-close' onclick='this.parentNode.style.display="none"'>&times;</button>`;
    notif.style.display = 'flex';
    setTimeout(() => { notif.style.display = 'none'; notif.classList.remove('visible'); }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Steps animados igual que usuarios_crear
    const stepItems = {
        institucion: document.getElementById('step-institucion-indicator'),
        programa: document.getElementById('step-programa-indicator')
    };
    const stepsProgressBar = document.querySelector('.steps-progress-bar');
    // Función para actualizar los steps visualmente
    function actualizarSteps(stepActivo) {
        for (const key in stepItems) {
            stepItems[key].classList.remove('active', 'completed');
        }
        if (stepActivo === 1) {
            stepItems.institucion.classList.add('active');
            stepsProgressBar.style.width = '0%';
        } else if (stepActivo === 2) {
            stepItems.institucion.classList.add('completed');
            stepItems.programa.classList.add('active');
            stepsProgressBar.style.width = '100%';
            // Cargar instituciones al pasar al step 2
            cargarInstituciones();
        }
    }
    // Inicializar en el primer paso
    actualizarSteps(1);
    // Vincular a los botones de navegación
    window.nextStep = function() {
        actualizarSteps(2);
        document.getElementById('step1').classList.remove('active');
        document.getElementById('step2').classList.add('active');
        // Cargar instituciones si no se han cargado
        cargarInstituciones();
    };
    window.previousStep = function() {
        actualizarSteps(1);
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');
    };
    // Si el step2 está visible al cargar, cargar instituciones
    if (document.getElementById('step2').classList.contains('active')) {
        cargarInstituciones();
    }
});

// Función para cargar todas las instituciones desde la API
async function cargarInstituciones() {
    const selectInstitucion = document.getElementById('institucion_seleccionada');
    selectInstitucion.innerHTML = '<option value="">Cargando instituciones...</option>';
    try {
        const instituciones = await fetchWithErrorHandling(`${apiBaseUrl}/instituciones`);
        selectInstitucion.innerHTML = '<option value="">Seleccione una institución</option>';
        if (Array.isArray(instituciones) && instituciones.length > 0) {
            instituciones.forEach(institucion => {
                const option = document.createElement('option');
                option.value = institucion.id_institucion;
                option.textContent = institucion.nombre;
                selectInstitucion.appendChild(option);
            });
            if (institucionId) selectInstitucion.value = institucionId;
        } else {
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "No hay instituciones disponibles";
            selectInstitucion.appendChild(option);
        }
    } catch (error) {
        selectInstitucion.innerHTML = '<option value="">Error al cargar instituciones</option>';
        mostrarNotificacion('Error al cargar instituciones: ' + error.message, 'error');
    }
}

// Cargar departamentos para Colombia (modificado para mejor manejo de errores)
function cargarDepartamentosColombia() {
    try {
        const selectDepartamento = document.getElementById('departamento');
        selectDepartamento.innerHTML = '<option value="">Seleccione un departamento</option>';
        if (!Array.isArray(departamentosColombia) || departamentosColombia.length === 0) {
            throw new Error('No se encontraron datos de departamentos');
        }
        departamentosColombia.forEach(depto => {
            const option = document.createElement('option');
            option.value = depto.id;
            option.textContent = depto.nombre;
            selectDepartamento.appendChild(option);
        });
        selectDepartamento.value = "8";
        cargarMunicipiosPorDepartamento(8);
    } catch (error) {
        console.error('Error al cargar departamentos:', error);
        mostrarNotificacion('Error al cargar departamentos: ' + error.message, 'error');
    }
}

// Cargar municipios por departamento (con mejor manejo de errores)
function cargarMunicipiosPorDepartamento(departamentoId) {
    try {
        const selectMunicipio = document.getElementById('municipio');
        selectMunicipio.innerHTML = '<option value="">Seleccione un municipio</option>';
        if (!municipiosPorDepartamento[departamentoId] ||
            !Array.isArray(municipiosPorDepartamento[departamentoId]) ||
            municipiosPorDepartamento[departamentoId].length === 0) {
            console.warn(`No se encontraron municipios para el departamento ID: ${departamentoId}`);
            return;
        }
        municipiosPorDepartamento[departamentoId].forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id;
            option.textContent = municipio.nombre;
            selectMunicipio.appendChild(option);
        });
        if (parseInt(departamentoId) === 8) {
            const popayan = municipiosPorDepartamento[8].find(m => m.nombre === "Popayán");
            if (popayan) {
                selectMunicipio.value = popayan.id;
            }
        } else if (municipiosPorDepartamento[departamentoId].length > 0) {
            selectMunicipio.value = municipiosPorDepartamento[departamentoId][0].id;
        }
    } catch (error) {
        console.error('Error al cargar municipios:', error);
        mostrarNotificacion('Error al cargar municipios: ' + error.message, 'error');
    }
}

// Función para validar formulario mejorada
function validateForm(form) {
    let valid = true;
    form.querySelectorAll('.form-control, .form-select').forEach(field => {
        field.classList.remove('is-invalid', 'is-valid');
        const feedbackElement = field.nextElementSibling?.classList.contains('invalid-feedback')
            ? field.nextElementSibling
            : field.parentElement.querySelector('.invalid-feedback');
        if (feedbackElement) {
            feedbackElement.style.display = 'none';
        }
    });
    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            const feedbackElement = field.nextElementSibling?.classList.contains('invalid-feedback')
                ? field.nextElementSibling
                : field.parentElement.querySelector('.invalid-feedback');
            if (feedbackElement) {
                feedbackElement.textContent = 'Este campo es obligatorio';
                feedbackElement.style.display = 'block';
            }
            valid = false;
        } else {
            field.classList.add('is-valid');
        }
    });
    const codigoIES = form.querySelector('#codigo_ies');
    if (codigoIES && codigoIES.value.trim()) {
        const regex = /^[a-zA-Z0-9]+$/;
        if (!regex.test(codigoIES.value.trim())) {
            codigoIES.classList.add('is-invalid');
            const feedbackElement = codigoIES.nextElementSibling?.classList.contains('invalid-feedback')
                ? codigoIES.nextElementSibling
                : codigoIES.parentElement.querySelector('.invalid-feedback');
            if (feedbackElement) {
                feedbackElement.textContent = 'El código IES solo debe contener letras y números, sin espacios';
                feedbackElement.style.display = 'block';
            }
            valid = false;
        }
    }
    const codigoSNIES = form.querySelector('#codigo_snies');
    if (codigoSNIES && codigoSNIES.value.trim()) {
        const regex = /^[0-9]+$/;
        if (!regex.test(codigoSNIES.value.trim())) {
            codigoSNIES.classList.add('is-invalid');
            const feedbackElement = codigoSNIES.nextElementSibling?.classList.contains('invalid-feedback')
                ? codigoSNIES.nextElementSibling
                : codigoSNIES.parentElement.querySelector('.invalid-feedback');
            if (feedbackElement) {
                feedbackElement.textContent = 'El código SNIES solo debe contener números';
                feedbackElement.style.display = 'block';
            }
            valid = false;
        }
    }
    return valid;
}

// Función para siguiente paso with mejor manejo de errores
function nextStep(currentStep) {
    try {
        if (currentStep === 1) {
            guardarInstitucion(false);
            changeStep(2);
        }
    } catch (error) {
        console.error('Error al avanzar al siguiente paso:', error);
        mostrarNotificacion('Error: ' + error.message, 'error');
    }
}

// Función para paso anterior
function previousStep(currentStep) {
    try {
        changeStep(currentStep - 1);
    } catch (error) {
        console.error('Error al volver al paso anterior:', error);
        mostrarNotificacion('Error: ' + error.message, 'error');
    }
}

// Función para guardar institución (solo API)
async function guardarInstitucion(validarCompleto = false) {
    try {
        if (validarCompleto) {
            const formInstitucion = document.getElementById('formInstitucion');
            if (!validateForm(formInstitucion)) {
                mostrarNotificacion('Por favor complete todos los campos requeridos correctamente', 'error');
                return false;
            }
        } else {
            const nombre = document.getElementById('nombre_institucion').value.trim();
            if (!nombre) {
                mostrarNotificacion('Debe ingresar al menos el nombre de la institución', 'error');
                return false;
            }
        }
        const nombre = document.getElementById('nombre_institucion').value.trim();
        const paisId = document.getElementById('pais').value;
        let departamentoId = null;
        let municipioId = null;
        let departamentoNombre = null;
        let municipioNombre = null;
        if (paisId == '1') {
            const selectDepartamento = document.getElementById('departamento');
            const selectMunicipio = document.getElementById('municipio');
            departamentoId = selectDepartamento.value;
            municipioId = selectMunicipio.value;
            if (selectDepartamento.selectedIndex > 0) {
                departamentoNombre = selectDepartamento.options[selectDepartamento.selectedIndex].text;
            }
            if (selectMunicipio.selectedIndex > 0) {
                municipioNombre = selectMunicipio.options[selectMunicipio.selectedIndex].text;
            }
        } else {
            departamentoNombre = document.getElementById('otro_departamento').value.trim();
            municipioNombre = document.getElementById('otro_municipio').value.trim();
        }
        const tipoInstitucion = document.getElementById('tipo_institucion').value;
        const codigoIes = document.getElementById('codigo_ies').value.trim();
        const data = { nombre: nombre };
        if (codigoIes) data.codigo_ies = codigoIes;
        if (paisId) data.pais_id = parseInt(paisId);
        if (departamentoId) data.departamento_id = parseInt(departamentoId);
        if (departamentoNombre) data.departamento_nombre = departamentoNombre;
        if (municipioId) data.municipio_id = parseInt(municipioId);
        if (municipioNombre) data.municipio_nombre = municipioNombre;
        if (tipoInstitucion) data.tipo = tipoInstitucion;
        const saveButton = document.getElementById('btnGuardarInstitucion') || document.querySelector('#step1 .btn-primary');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        saveButton.disabled = true;
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            headers['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
        try {
            const result = await fetchWithErrorHandling(`${apiBaseUrl}/instituciones`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });
            if (result && result.id_institucion) {
                institucionId = result.id_institucion;
                mostrarNotificacion('¡Institución guardada correctamente!', 'success');
                if (validarCompleto) {
                    setTimeout(() => {
                        changeStep(2);
                    }, 1000);
                }
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
                return true;
            } else if (result && result.error) {
                if (result.error.includes('already been taken')) {
                    mostrarNotificacion('Este código IES ya está registrado', 'error');
                } else {
                    mostrarNotificacion('Error: ' + result.error, 'error');
                }
            } else if (result && result.mensaje) {
                mostrarNotificacion(result.mensaje, 'success');
                return true;
            } else {
                mostrarNotificacion('Error al guardar institución', 'error');
            }
        } catch (fetchError) {
            console.error('Error al guardar en API:', fetchError);
            mostrarNotificacion('Error de conexión: ' + fetchError.message, 'error');
        }
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        return false;
    } catch (error) {
        console.error('Error al guardar institución:', error);
        const saveButton = document.getElementById('btnGuardarInstitucion') || document.querySelector('#step1 .btn-primary');
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Institución';
            saveButton.disabled = false;
        }
        mostrarNotificacion('Error de conexión: ' + error.message, 'error');
        return false;
    }
}

// Función para guardar programa (solo API)
async function guardarPrograma() {
    try {
        const formPrograma = document.getElementById('formPrograma');
        if (!validateForm(formPrograma)) {
            mostrarNotificacion('Por favor complete todos los campos requeridos correctamente', 'error');
            return;
        }
        const nombrePrograma = document.getElementById('nombre_programa').value.trim();
        if (!nombrePrograma) {
            mostrarNotificacion('Por favor ingrese el nombre del programa', 'error');
            return;
        }
        const institucionSeleccionada = document.getElementById('institucion_seleccionada').value;
        const instId = institucionSeleccionada || institucionId;
        if (!instId) {
            mostrarNotificacion('No hay institución seleccionada para el programa', 'error');
            return;
        }
        const data = {
            institucion_id: parseInt(instId),
            facultad_id: null,
            nombre: nombrePrograma,
            codigo_snies: document.getElementById('codigo_snies').value.trim() || null,
            tipo_formacion: document.getElementById('tipo_formacion').value || 'Profesional',
            metodologia: document.getElementById('metodologia').value || 'Presencial'
        };
        const saveButton = document.querySelector('#step2 .btn-success');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        saveButton.disabled = true;
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            headers['X-CSRF-TOKEN'] = token.getAttribute('content');
        }
        try {
            const result = await fetchWithErrorHandling(`${apiBaseUrl}/programas`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });
            saveButton.innerHTML = '<i class="fas fa-check"></i> ¡Guardado!';
            if (result.mensaje === 'Programa insertado correctamente' || result.id_programa) {
                programaId = result.id_programa;
                institucionId = instId;
                mostrarNotificacion('¡Programa guardado correctamente!', 'success');
                setTimeout(() => {
                    window.location.href = '/admin/programas';
                }, 2000);
                return true;
            } else {
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
                mostrarNotificacion('Error al guardar el programa', 'error');
            }
        } catch (fetchError) {
            console.error('Error al guardar programa en API:', fetchError);
            mostrarNotificacion('Error al guardar programa: ' + fetchError.message, 'error');
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
    } catch (error) {
        console.error('Error al guardar programa:', error);
        const saveButton = document.querySelector('#step2 .btn-success');
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Programa';
            saveButton.disabled = false;
        }
        mostrarNotificacion('Error al guardar programa: ' + error.message, 'error');
    }
}

// Inicializar el formulario cuando se cargue el documento
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando formulario...');
    cargarDepartamentosColombia();
    document.getElementById('pais').addEventListener('change', function() {
        const paisId = this.value;
        const deptoContainer = document.getElementById('departamento-container');
        const muniContainer = document.getElementById('municipio-container');
        const otroDeptoContainer = document.getElementById('otro-departamento-container');
        const otroMuniContainer = document.getElementById('otro-municipio-container');
        if (paisId == '1') {
            document.getElementById('departamento').required = true;
            document.getElementById('municipio').required = true;
            document.getElementById('otro_departamento').required = false;
            document.getElementById('otro_municipio').required = false;
            deptoContainer.querySelector('.form-floating').style.display = 'block';
            muniContainer.querySelector('.form-floating').style.display = 'block';
            otroDeptoContainer.style.display = 'none';
            otroMuniContainer.style.display = 'none';
            cargarDepartamentosColombia();
        } else if (paisId == '2') {
            document.getElementById('departamento').required = false;
            document.getElementById('municipio').required = false;
            document.getElementById('otro_departamento').required = true;
            document.getElementById('otro_municipio').required = true;
            deptoContainer.querySelector('.form-floating').style.display = 'none';
            muniContainer.querySelector('.form-floating').style.display = 'none';
            otroDeptoContainer.style.display = 'block';
            otroMuniContainer.style.display = 'block';
        } else {
            document.getElementById('departamento').required = false;
            document.getElementById('municipio').required = false;
            document.getElementById('otro_departamento').required = false;
            document.getElementById('otro_municipio').required = false;
            deptoContainer.querySelector('.form-floating').style.display = 'none';
            muniContainer.querySelector('.form-floating').style.display = 'none';
            otroDeptoContainer.style.display = 'none';
            otroMuniContainer.style.display = 'none';
        }
    });
    document.getElementById('departamento').addEventListener('change', function() {
        const departamentoId = this.value;
        if (departamentoId) {
            cargarMunicipiosPorDepartamento(departamentoId);
        } else {
            const selectMunicipio = document.getElementById('municipio');
            selectMunicipio.innerHTML = '<option value="">Seleccione primero un departamento</option>';
        }
    });
    const btnGuardarInstitucion = document.getElementById('btnGuardarInstitucion');
    if (btnGuardarInstitucion) {
        btnGuardarInstitucion.addEventListener('click', function(e) {
            e.preventDefault();
            guardarInstitucion(true);
        });
    }
    document.getElementById('formInstitucion').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarInstitucion(true);
    });
    document.getElementById('formPrograma').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarPrograma();
    });
});

// Función para probar la conexión con la API
async function testApiConnection() {
    try {
        await fetch(`${apiBaseUrl}/test-connection`, { method: 'GET' });
        console.log("Conexión a la API exitosa");
    } catch (error) {
        mostrarNotificacion(
            "Advertencia: Problemas de conexión con el servidor. Algunas funciones pueden no estar disponibles.",
            "warning"
        );
    }
}

// Función auxiliar para agregar estilos dinámicamente si falta algún recurso
function addMissingStyles() {
    if (!document.querySelector('link[href*="font-awesome"]') &&
        !document.querySelector('link[href*="fontawesome"]')) {
        const fontAwesomeLink = document.createElement('link');
        fontAwesomeLink.rel = 'stylesheet';
        fontAwesomeLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
        document.head.appendChild(fontAwesomeLink);
        console.log('Font Awesome agregado dinámicamente');
    }
}

// Llamar a la función que agrega estilos faltantes
addMissingStyles();

// Funciones de utilidad para manipulación del DOM
function showElement(element) {
    if (typeof element === 'string') {
        element = document.getElementById(element);
    }
    if (element) {
        element.style.display = 'block';
    }
}

function hideElement(element) {
    if (typeof element === 'string') {
        element = document.getElementById(element);
    }
    if (element) {
        element.style.display = 'none';
    }
}

function toggleElement(element, show) {
    if (typeof element === 'string') {
        element = document.getElementById(element);
    }
    if (element) {
        element.style.display = show ? 'block' : 'none';
    }
}

// Función para sanitizar entradas de usuario (evitar XSS)
function sanitizeInput(input) {
    if (!input) return '';
    input = String(input);
    return input
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Validaciones adicionales para los campos de texto
function setupFieldValidations() {
    const nombreInstitucion = document.getElementById('nombre_institucion');
    if (nombreInstitucion) {
        nombreInstitucion.addEventListener('input', function() {
            this.value = this.value.replace(/[^\w\s.,&()-]/gi, '');
        });
    }
    const codigoIes = document.getElementById('codigo_ies');
    if (codigoIes) {
        codigoIes.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });
    }
    const codigoSnies = document.getElementById('codigo_snies');
    if (codigoSnies) {
        codigoSnies.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    const nombrePrograma = document.getElementById('nombre_programa');
    if (nombrePrograma) {
        nombrePrograma.addEventListener('input', function() {
            this.value = this.value.replace(/[^\w\s.,&()-]/gi, '');
        });
    }
}
    </script>
@endsection

@extends('admin.layouts.appadmin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Header institucional -->
    <div class="container-fluid py-3 mb-4" style="background-color: #003366; font-family: 'Source Sans Pro', sans-serif;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    <img src="/img/logo-small.png" alt="Logo" class="img-fluid" style="max-height: 60px;">
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
                        <div class="steps-container mb-5">
                            <div class="step-circles d-flex justify-content-center position-relative">
                                <div class="progress-line"></div>
                                <div class="step-circle active" data-step="1">
                                    <div class="circle">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="step-label">Institución</div>
                                </div>
                                <div class="step-circle" data-step="2">
                                    <div class="circle">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="step-label">Programa</div>
                                </div>
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

        /* Estilos mejorados para los steps circulares */
        .steps-container {
            margin: 2rem 0 4rem;
        }

        .step-circles {
            width: 70%;
            margin: 0 auto;
        }

        .progress-line {
            position: absolute;
            top: 25px;
            height: 6px;
            width: 100%;
            background-color: #e9ecef;
            z-index: 1;
            border-radius: 3px;
        }

        .progress-line:before {
            content: '';
            position: absolute;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            width: 0%;
            transition: var(--transition);
            z-index: 2;
            border-radius: 3px;
        }

        .progress-line.step-1-active:before {
            width: 0%;
        }

        .progress-line.step-2-active:before {
            width: 100%;
        }

        .step-circle {
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 150px;
            transition: var(--transition);
        }

        .circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 12px;
            transition: var(--transition);
            border: 3px solid #e9ecef;
            box-shadow: 0 0 0 5px rgba(233, 236, 239, 0.5);
        }

        .circle i {
            font-size: 1.4rem;
        }

        .step-label {
            font-weight: 600;
            color: #6c757d;
            transition: var(--transition);
            font-size: 1.1rem;
            margin-top: 5px;
        }

        .step-circle.active .circle {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 5px rgba(67, 97, 238, 0.2);
            transform: scale(1.1);
        }

        .step-circle.active .step-label {
            color: var(--primary-color);
            font-weight: 700;
        }

        .step-circle.completed .circle {
            background-color: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

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

        /* Notificaciones mejoradas */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            display: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            min-width: 300px;
            max-width: 500px;
            transform: translateX(100%);
            animation: slideIn 0.3s forwards;
            display: flex;
            align-items: center;
        }

        .notification i {
            margin-right: 10px;
            font-size: 1.5rem;
        }

        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

        .notification.success {
            background: linear-gradient(135deg, #10b981, #059669);
            border-left: 5px solid #059669;
        }

        .notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-left: 5px solid #dc2626;
        }

        /* Estilo para los mensajes de carga */
        .loading-spinner {
            display: inline-block;
            width: 1.5rem;
            height: 1.5rem;
            vertical-align: middle;
            border: 3px solid rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
    </style>

    <div id="notification" class="notification"></div>

    <script>
// Variables para almacenar los IDs creados
let institucionId = null;
let programaId = null;
let apiBaseUrl = 'https://homologacionesback.educarenemociones.com/api';
let usarProxyLocal = false; // Bandera para usar un proxy local si es necesario

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

// Datos de instituciones para trabajar en modo local si hay problemas CORS
const institucionesDato = [
    { id_institucion: 1, nombre: "Universidad Nacional de Colombia" },
    { id_institucion: 2, nombre: "Universidad de Antioquia" },
    { id_institucion: 3, nombre: "Universidad del Valle" },
    { id_institucion: 4, nombre: "Universidad de Los Andes" },
    { id_institucion: 5, nombre: "Pontificia Universidad Javeriana" },
    { id_institucion: 6, nombre: "Universidad Industrial de Santander" },
    { id_institucion: 7, nombre: "Universidad del Norte" },
    { id_institucion: 8, nombre: "Universidad EAFIT" },
    { id_institucion: 9, nombre: "Universidad Externado de Colombia" },
    { id_institucion: 10, nombre: "Universidad del Rosario" }
];

// Función para obtener el token CSRF
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Determinar si estamos en un entorno local
function isLocalEnvironment() {
    return window.location.hostname === 'localhost' ||
           window.location.hostname === '127.0.0.1';
}

// Función para realizar peticiones AJAX con manejo de errores mejorado y soporte para CORS
async function fetchWithErrorHandling(url, options = {}) {
    try {
        // Si estamos en modo local y la URL es para instituciones y usarProxyLocal está activado
        if (usarProxyLocal && url.includes('/instituciones')) {
            // Para consulta de instituciones, devolver datos locales
            if (options.method === 'GET') {
                console.log("Usando datos locales para instituciones (modo desarrollo)");
                return institucionesDato;
            }

            // Para creación de institución, simular respuesta exitosa
            if (options.method === 'POST') {
                console.log("Simulando creación de institución (modo desarrollo)");
                const data = JSON.parse(options.body);
                // Generar un ID simulado único
                const newId = Math.floor(Math.random() * 1000) + 100;
                const response = {
                    id_institucion: newId,
                    nombre: data.nombre,
                    mensaje: "Institución creada en modo de desarrollo local"
                };
                // Mostrar mensaje al usuario
                mostrarNotificacion("Modo desarrollo: Institución simulada creada correctamente", "info");
                return response;
            }
        }

        // Opciones para solicitudes que pueden tener problemas CORS
        if (isLocalEnvironment()) {
            options.mode = 'cors';
            options.credentials = 'include';
        }

        const response = await fetch(url, options);

        // Si la respuesta no es exitosa, intentar obtener detalles del error
        if (!response.ok) {
            let errorMessage = `Error HTTP ${response.status}`;
            try {
                const errorData = await response.json();
                if (errorData.message) {
                    errorMessage = errorData.message;
                } else if (errorData.error) {
                    errorMessage = errorData.error;
                }
            } catch (parseError) {
                // Si no se puede parsear la respuesta como JSON, usar el statusText
                errorMessage = `${errorMessage}: ${response.statusText}`;
            }
            throw new Error(errorMessage);
        }

        // Intentar parsear la respuesta como JSON
        try {
            return await response.json();
        } catch (jsonError) {
            // Si no es JSON, devolver el texto de la respuesta
            return { success: true, message: await response.text() };
        }
    } catch (error) {
        console.error('Error en la petición:', error);

        // Si estamos en entorno local y hay error de CORS, activar el modo de datos locales
        if (isLocalEnvironment() &&
            (error.message.includes('CORS') || error.message.includes('Failed to fetch'))) {

            if (!usarProxyLocal) {
                usarProxyLocal = true;
                mostrarNotificacion(
                    "Detectado error CORS. Cambiando a modo de desarrollo local para continuar.",
                    "warning"
                );

                // Si la URL era para instituciones, intentar de nuevo con datos locales
                if (url.includes('/instituciones')) {
                    if (options.method === 'GET') {
                        return institucionesDato;
                    } else if (options.method === 'POST') {
                        const data = JSON.parse(options.body);
                        const newId = Math.floor(Math.random() * 1000) + 100;
                        return {
                            id_institucion: newId,
                            nombre: data.nombre,
                            mensaje: "Institución creada en modo de desarrollo local"
                        };
                    }
                }
            }
        }

        throw error;
    }
}

// Función para mostrar notificaciones mejorada
function mostrarNotificacion(mensaje, tipo) {
    const notificacion = document.getElementById('notification');

    // Añadir ícono según tipo
    let icono = '';
    if (tipo === 'success') {
        icono = '<i class="fas fa-check-circle"></i> ';
    } else if (tipo === 'error') {
        icono = '<i class="fas fa-exclamation-circle"></i> ';
    } else if (tipo === 'warning') {
        icono = '<i class="fas fa-exclamation-triangle"></i> ';
    } else if (tipo === 'info') {
        icono = '<i class="fas fa-info-circle"></i> ';
    }

    notificacion.innerHTML = icono + mensaje;
    notificacion.className = 'notification ' + tipo;
    notificacion.style.display = 'flex';

    // Mostrar por 4 segundos y luego ocultar con animación
    setTimeout(() => {
        notificacion.style.opacity = '0';
        setTimeout(() => {
            notificacion.style.display = 'none';
            notificacion.style.opacity = '1';
        }, 300);
    }, 4000);
}

// Función para cambiar de paso con mejor manejo de errores
function changeStep(step) {
    try {
        document.querySelectorAll('.step-content').forEach(s => s.classList.remove('active'));
        const stepElement = document.getElementById('step' + step);
        if (!stepElement) {
            throw new Error(`Step ${step} not found`);
        }
        stepElement.classList.add('active');

        // Actualizar los círculos de progreso
        document.querySelectorAll('.step-circle').forEach(circle => {
            const circleStep = parseInt(circle.getAttribute('data-step'));
            circle.classList.remove('active', 'completed');

            if (circleStep === step) {
                circle.classList.add('active');
            } else if (circleStep < step) {
                circle.classList.add('completed');
            }
        });

        // Actualizar la línea de progreso
        const progressLine = document.querySelector('.progress-line');
        progressLine.className = 'progress-line';
        progressLine.classList.add('step-' + step + '-active');

        // Si avanzamos al paso 2, cargar las instituciones
        if (step === 2) {
            cargarInstituciones();
        }
    } catch (error) {
        console.error('Error al cambiar de paso:', error);
        mostrarNotificacion('Error al cambiar de paso: ' + error.message, 'error');
    }
}

// Función para cargar todas las instituciones desde la API con mejor manejo de errores
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

            // Si ya tenemos una institucion seleccionada, seleccionarla
            if (institucionId) {
                selectInstitucion.value = institucionId;
            }
        } else {
            // Si no hay instituciones, mostrar mensaje
            const option = document.createElement('option');
            option.value = "";
            option.textContent = "No hay instituciones disponibles";
            selectInstitucion.appendChild(option);
        }
    } catch (error) {
        console.error('Error al cargar instituciones:', error);

        // En caso de error, mostrar opciones de datos locales si estamos en modo desarrollo
        if (isLocalEnvironment()) {
            selectInstitucion.innerHTML = '<option value="">Seleccione una institución (modo local)</option>';
            institucionesDato.forEach(institucion => {
                const option = document.createElement('option');
                option.value = institucion.id_institucion;
                option.textContent = institucion.nombre;
                selectInstitucion.appendChild(option);
            });

            // Si ya tenemos una institución seleccionada, seleccionarla
            if (institucionId) {
                selectInstitucion.value = institucionId;
            }
        } else {
            selectInstitucion.innerHTML = '<option value="">Error al cargar instituciones</option>';
        }

        mostrarNotificacion('Error al cargar instituciones: ' + error.message, 'error');
    }
}

// Cargar departamentos para Colombia (modificado para mejor manejo de errores)
function cargarDepartamentosColombia() {
    try {
        const selectDepartamento = document.getElementById('departamento');

        // Limpiar select
        selectDepartamento.innerHTML = '<option value="">Seleccione un departamento</option>';

        // Verificar si tenemos datos
        if (!Array.isArray(departamentosColombia) || departamentosColombia.length === 0) {
            throw new Error('No se encontraron datos de departamentos');
        }

        // Agregar departamentos al select
        departamentosColombia.forEach(depto => {
            const option = document.createElement('option');
            option.value = depto.id;
            option.textContent = depto.nombre;
            selectDepartamento.appendChild(option);
        });

        // Seleccionar Cauca (id: 8) por defecto, ya que estamos en Popayán
        selectDepartamento.value = "8";

        // Cargar municipios del departamento seleccionado
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

        // Limpiar select
        selectMunicipio.innerHTML = '<option value="">Seleccione un municipio</option>';

        // Verificar si existen municipios para este departamento
        if (!municipiosPorDepartamento[departamentoId] ||
            !Array.isArray(municipiosPorDepartamento[departamentoId]) ||
            municipiosPorDepartamento[departamentoId].length === 0) {
            console.warn(`No se encontraron municipios para el departamento ID: ${departamentoId}`);
            return;
        }

        // Agregar municipios al select
        municipiosPorDepartamento[departamentoId].forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id;
            option.textContent = municipio.nombre;
            selectMunicipio.appendChild(option);
        });

        // Si es Cauca (id: 8), seleccionar Popayán por defecto
        if (parseInt(departamentoId) === 8) {
            // Buscar el ID de Popayán en el array de municipios de Cauca
            const popayan = municipiosPorDepartamento[8].find(m => m.nombre === "Popayán");
            if (popayan) {
                selectMunicipio.value = popayan.id;
            }
        } else if (municipiosPorDepartamento[departamentoId].length > 0) {
            // Si no es Cauca, seleccionar el primer municipio por defecto
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

    // Resetear todos los campos
    form.querySelectorAll('.form-control, .form-select').forEach(field => {
        field.classList.remove('is-invalid', 'is-valid');
        const feedbackElement = field.nextElementSibling?.classList.contains('invalid-feedback')
            ? field.nextElementSibling
            : field.parentElement.querySelector('.invalid-feedback');

        if (feedbackElement) {
            feedbackElement.style.display = 'none';
        }
    });

    // Validar campos requeridos
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

    // Validación específica para código IES si tiene valor
    const codigoIES = form.querySelector('#codigo_ies');
    if (codigoIES && codigoIES.value.trim()) {
        // Solo números y letras, sin espacios
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

    // Validación específica para código SNIES si tiene valor
    const codigoSNIES = form.querySelector('#codigo_snies');
    if (codigoSNIES && codigoSNIES.value.trim()) {
        // Solo números, sin espacios
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

// Función para siguiente paso con mejor manejo de errores
function nextStep(currentStep) {
    try {
        if (currentStep === 1) {
            // Intentar guardar la institución (parcialmente, sin validación completa)
            guardarInstitucion(false);

            // Avanzar al siguiente paso
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

// Función para guardar institución (completa o parcial) con mejor manejo de errores y soporte para modo local
async function guardarInstitucion(validarCompleto = false) {
    try {
        // Si se pide validación completa, validar los campos
        if (validarCompleto) {
            const formInstitucion = document.getElementById('formInstitucion');
            if (!validateForm(formInstitucion)) {
                mostrarNotificacion('Por favor complete todos los campos requeridos correctamente', 'error');
                return false;
            }
        } else {
            // En modo parcial, al menos verificar que exista el nombre
            const nombre = document.getElementById('nombre_institucion').value.trim();
            if (!nombre) {
                mostrarNotificacion('Debe ingresar al menos el nombre de la institución', 'error');
                return false;
            }
        }

        // Obtener datos del formulario
        const nombre = document.getElementById('nombre_institucion').value.trim();
        const paisId = document.getElementById('pais').value;
        let departamentoId = null;
        let municipioId = null;
        let departamentoNombre = null;
        let municipioNombre = null;

        if (paisId == '1') { // Colombia
            const selectDepartamento = document.getElementById('departamento');
            const selectMunicipio = document.getElementById('municipio');

            departamentoId = selectDepartamento.value;
            municipioId = selectMunicipio.value;

            // Solo incluir nombres si los select tienen una opción seleccionada
            if (selectDepartamento.selectedIndex > 0) {
                departamentoNombre = selectDepartamento.options[selectDepartamento.selectedIndex].text;
            }

            if (selectMunicipio.selectedIndex > 0) {
                municipioNombre = selectMunicipio.options[selectMunicipio.selectedIndex].text;
            }
        } else { // Otro país
            departamentoNombre = document.getElementById('otro_departamento').value.trim();
            municipioNombre = document.getElementById('otro_municipio').value.trim();
        }

        const tipoInstitucion = document.getElementById('tipo_institucion').value;
        const codigoIes = document.getElementById('codigo_ies').value.trim();

        // Construir objeto de datos solo con valores que existen
        const data = {
            nombre: nombre
        };

        if (codigoIes) data.codigo_ies = codigoIes;
        if (paisId) data.pais_id = parseInt(paisId);
        if (departamentoId) data.departamento_id = parseInt(departamentoId);
        if (departamentoNombre) data.departamento_nombre = departamentoNombre;
        if (municipioId) data.municipio_id = parseInt(municipioId);
        if (municipioNombre) data.municipio_nombre = municipioNombre;
        if (tipoInstitucion) data.tipo = tipoInstitucion;

        console.log('Enviando datos institución:', data);

        // Deshabilitar botón durante la petición
        const saveButton = document.getElementById('btnGuardarInstitucion') || document.querySelector('#step1 .btn-primary');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        saveButton.disabled = true;

        // Cabeceras para la petición
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        // Añadir CSRF si está disponible
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            headers['X-CSRF-TOKEN'] = token.getAttribute('content');
        }

        try {
            // Intentar enviar datos a la API
            const result = await fetchWithErrorHandling(`${apiBaseUrl}/instituciones`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });

            console.log('Respuesta API institución:', result);

            // Procesar resultado
            if (result && result.id_institucion) {
                institucionId = result.id_institucion;
                mostrarNotificacion('¡Institución guardada correctamente!', 'success');

                // Si es guardado completo, avanzar al siguiente paso
                if (validarCompleto) {
                    setTimeout(() => {
                        changeStep(2);
                    }, 1000);
                }

                // Restaurar botón
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

            // Si estamos en ambiente local, generar un ID simulado y continuar
            if (isLocalEnvironment()) {
                const localId = Math.floor(Math.random() * 1000) + 100;
                institucionId = localId;
                mostrarNotificacion('Modo desarrollo: Institución simulada creada con ID: ' + localId, 'warning');

                // Si es guardado completo, avanzar al siguiente paso
                if (validarCompleto) {
                    setTimeout(() => {
                        changeStep(2);
                    }, 1000);
                }

                // Restaurar botón
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;

                return true;
            } else {
                throw fetchError; // Re-lanzar el error si no estamos en entorno local
            }
        }

        // Restaurar botón
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;

        return false;
    } catch (error) {
        console.error('Error al guardar institución:', error);

        // Restaurar botón
        const saveButton = document.getElementById('btnGuardarInstitucion') || document.querySelector('#step1 .btn-primary');
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Institución';
            saveButton.disabled = false;
        }

        mostrarNotificacion('Error de conexión: ' + error.message, 'error');
        return false;
    }
}

// Función para guardar programa completo con mejor manejo de errores y soporte para modo local
async function guardarPrograma() {
    try {
        // Validar el formulario de programa
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

        // Obtener institución seleccionada
        const institucionSeleccionada = document.getElementById('institucion_seleccionada').value;
        // Si no hay institución seleccionada, usar la creada anteriormente o mostrar error
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

        // Deshabilitar botón durante la petición
        const saveButton = document.querySelector('#step2 .btn-success');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        saveButton.disabled = true;

        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        // Añadir CSRF si está disponible
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
                institucionId = instId; // Actualizar institucionId con la seleccionada
                mostrarNotificacion('¡Programa guardado correctamente!', 'success');

                // Redirigir después de un breve retraso
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

            // Si estamos en ambiente local, simular una respuesta exitosa
            if (isLocalEnvironment()) {
                saveButton.innerHTML = '<i class="fas fa-check"></i> ¡Guardado en modo local!';
                mostrarNotificacion('Modo desarrollo: Programa simulado guardado correctamente', 'warning');

                // Simular redirección
                setTimeout(() => {
                    mostrarNotificacion('Modo desarrollo: La redirección está desactivada en modo local', 'info');
                    saveButton.innerHTML = originalText;
                    saveButton.disabled = false;
                }, 2000);

                return true;
            } else {
                throw fetchError; // Re-lanzar el error si no estamos en entorno local
            }
        }
    } catch (error) {
        console.error('Error al guardar programa:', error);

        // Restaurar botón
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

    // Verificar si estamos en entorno local
    if (isLocalEnvironment()) {
        console.log("Detectado entorno local. Activando modos compatibles.");
        mostrarNotificacion("Entorno local detectado. Si hay problemas de CORS, se activará el modo de desarrollo local.", "info");
    }

    // Cargar departamentos de Colombia por defecto
    cargarDepartamentosColombia();

    // Event listeners para cambios en país
    document.getElementById('pais').addEventListener('change', function() {
        const paisId = this.value;
        const deptoContainer = document.getElementById('departamento-container');
        const muniContainer = document.getElementById('municipio-container');
        const otroDeptoContainer = document.getElementById('otro-departamento-container');
        const otroMuniContainer = document.getElementById('otro-municipio-container');

        if (paisId == '1') { // Colombia
            // Mostrar select de departamentos y municipios de Colombia
            document.getElementById('departamento').required = true;
            document.getElementById('municipio').required = true;
            document.getElementById('otro_departamento').required = false;
            document.getElementById('otro_municipio').required = false;

            deptoContainer.querySelector('.form-floating').style.display = 'block';
            muniContainer.querySelector('.form-floating').style.display = 'block';
            otroDeptoContainer.style.display = 'none';
            otroMuniContainer.style.display = 'none';

            cargarDepartamentosColombia();
        } else if (paisId == '2') { // Otro país
            // Mostrar campos de texto para departamento y municipio
            document.getElementById('departamento').required = false;
            document.getElementById('municipio').required = false;
            document.getElementById('otro_departamento').required = true;
            document.getElementById('otro_municipio').required = true;

            deptoContainer.querySelector('.form-floating').style.display = 'none';
            muniContainer.querySelector('.form-floating').style.display = 'none';
            otroDeptoContainer.style.display = 'block';
            otroMuniContainer.style.display = 'block';
        } else {
            // Si no se selecciona país, ocultar todo
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

    // Event listener para cambio de departamento
    document.getElementById('departamento').addEventListener('change', function() {
        const departamentoId = this.value;
        if (departamentoId) {
            cargarMunicipiosPorDepartamento(departamentoId);
        } else {
            // Si no hay departamento seleccionado, limpiar municipios
            const selectMunicipio = document.getElementById('municipio');
            selectMunicipio.innerHTML = '<option value="">Seleccione primero un departamento</option>';
        }
    });

    // Botón para guardar institución explícitamente
    const btnGuardarInstitucion = document.getElementById('btnGuardarInstitucion');
    if (btnGuardarInstitucion) {
        btnGuardarInstitucion.addEventListener('click', function(e) {
            e.preventDefault();
            guardarInstitucion(true); // Guardar con validación completa
        });
    }

    // Manejar eventos de formulario para prevenir envío por defecto
    document.getElementById('formInstitucion').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarInstitucion(true);
    });

    document.getElementById('formPrograma').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarPrograma();
    });

    // Verificar si hay errores de conexión con la API y mostrar notificación
    testApiConnection();

    // Inicializar validaciones de campos
    setupFieldValidations();
});

// Función para probar la conexión con la API
async function testApiConnection() {
    try {
        // Intentar hacer una petición simple para verificar la conexión
        await fetch(`${apiBaseUrl}/test-connection`, {
            method: 'GET',
            mode: 'cors',
            credentials: 'include'
        });

        console.log("Conexión a la API exitosa");
    } catch (error) {
        // Si hay un error de red, mostrar notificación
        console.warn('Advertencia: No se puede conectar con la API. Algunas funciones pueden no estar disponibles.', error);

        if (isLocalEnvironment() && (error.message.includes('CORS') || error.message.includes('Failed to fetch'))) {
            usarProxyLocal = true;
            mostrarNotificacion(
                "Error CORS detectado. Se ha activado el modo de desarrollo local para continuar.",
                "warning"
            );
        } else {
            mostrarNotificacion(
                "Advertencia: Problemas de conexión con el servidor. Algunas funciones pueden no estar disponibles.",
                "warning"
            );
        }
    }
}

// Función auxiliar para agregar estilos dinámicamente si falta algún recurso
function addMissingStyles() {
    // Verificar si Font Awesome está cargado
    if (!document.querySelector('link[href*="font-awesome"]') &&
        !document.querySelector('link[href*="fontawesome"]')) {

        // Agregar Font Awesome dinámicamente
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

    // Convertir a string si no lo es
    input = String(input);

    // Reemplazar caracteres especiales
    return input
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Validaciones adicionales para los campos de texto
function setupFieldValidations() {
    // Validar nombre de institución (solo permitir caracteres válidos)
    const nombreInstitucion = document.getElementById('nombre_institucion');
    if (nombreInstitucion) {
        nombreInstitucion.addEventListener('input', function() {
            // Eliminar caracteres especiales excepto letras, números, espacios y algunos símbolos comunes
            this.value = this.value.replace(/[^\w\s.,&()-]/gi, '');
        });
    }

    // Validar código IES (solo alfanumérico sin espacios)
    const codigoIes = document.getElementById('codigo_ies');
    if (codigoIes) {
        codigoIes.addEventListener('input', function() {
            // Solo permitir letras y números
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });
    }

    // Validar código SNIES (solo números)
    const codigoSnies = document.getElementById('codigo_snies');
    if (codigoSnies) {
        codigoSnies.addEventListener('input', function() {
            // Solo permitir números
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Validar nombre de programa (solo permitir caracteres válidos)
    const nombrePrograma = document.getElementById('nombre_programa');
    if (nombrePrograma) {
        nombrePrograma.addEventListener('input', function() {
            // Eliminar caracteres especiales excepto letras, números, espacios y algunos símbolos comunes
            this.value = this.value.replace(/[^\w\s.,&()-]/gi, '');
        });
    }
}
    </script>
@endsection

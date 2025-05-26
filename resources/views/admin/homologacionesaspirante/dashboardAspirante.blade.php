<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Proceso de Homologación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <script>
        const baseRoute = '/homologaciones-frontend/public';
        const token = localStorage.getItem('auth_token');
        const userData = localStorage.getItem('user_data');

        if (!token || !userData) {
            window.location.href = `${baseRoute}/auth/login`;
        } else {
            const user = JSON.parse(userData);
            if (user.rol_id !== 1) {
                const redirectMap = {
                    2: `${baseRoute}/coordinador/inicio`,
                    3: `${baseRoute}/coordinador/inicio`,
                    4: `${baseRoute}/homologaciones-vicerrectoria/inicio`,
                    5: `${baseRoute}/administrador`,
                    default: `${baseRoute}/auth/login`
                };
                window.location.href = redirectMap[user.rol_id] || redirectMap.default;
            }
        }
    </script>

    <link rel="stylesheet" href="{{ asset('css/dashboard_aspirante.css') }}">

    <!-- Estilos adicionales para documentos -->
    <style>
        .list-group-item .btn-group {
            gap: 0.25rem;
        }

        .alert.mt-3 {
            border-left: 4px solid;
        }

        .alert-success {
            border-left-color: #198754;
        }

        .alert-warning {
            border-left-color: #ffc107;
        }

        .btn-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }

        .documento-resolution {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .documento-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .documento-actions {
                flex-direction: column;
            }

            .documento-actions .btn {
                width: 100%;
            }
        }

        .text-file-pdf {
            color: #dc3545;
        }

        .text-file-doc {
            color: #0d6efd;
        }

        .text-file-general {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building-check me-2"></i>
                Homologaciones Uniautónoma
            </a>
            <div class="d-flex align-items-center">
                <div class="text-center mx-2">
                    <button class="btn p-0" id="btnNotificaciones" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Notificaciones">
                        <i class="bi bi-bell-fill nav-icon"></i>
                        <span class="nav-icon-text">Notificaciones</span>
                    </button>
                </div>
                <div class="text-center mx-2">
                    <button class="btn p-0" data-bs-toggle="modal" data-bs-target="#userProfileModal"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Perfil de Usuario">
                        <i class="bi bi-person-circle nav-icon"></i>
                        <span class="nav-icon-text">Perfil</span>
                    </button>
                </div>
                <div class="text-center mx-2">
                    <button class="btn p-0" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Menú">
                        <i class="bi bi-list nav-icon"></i>
                        <span class="nav-icon-text">Menú</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- User Profile Modal -->
    <div class="modal fade" id="userProfileModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-circle me-2"></i>
                        Perfil de usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle fs-1 me-3 text-primary"></i>
                        <div>
                            <h5 class="mb-0" id="userFullName">Cargando...</h5>
                            <p class="text-muted mb-0" id="userRole">Cargando...</p>
                        </div>
                    </div>

                    <div class="user-credentials">
                        <h6 class="mb-3">Credenciales de acceso</h6>
                        <div class="credential-item">
                            <span><i class="bi bi-envelope-fill me-2"></i>Correo:</span>
                            <span class="credential-value" id="userEmail">Cargando...</span>
                        </div>
                        <div class="credential-item">
                            <span><i class="bi bi-key-fill me-2"></i>Contraseña:</span>
                            <div class="d-flex align-items-center">
                                <span class="credential-value" id="passwordDisplay">********</span>
                                <button type="button" class="btn btn-sm ms-2" id="togglePasswordBtn">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar información
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar información del estudiante
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="edit-form-group">
                            <label class="form-label">Nombres y apellidos:</label>
                            <input type="text" class="form-control" name="nombreCompleto">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Correo electrónico:</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Cédula:</label>
                            <input type="text" class="form-control" name="identificacion">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Teléfono:</label>
                            <input type="tel" class="form-control" name="telefono">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Dirección:</label>
                            <input type="text" class="form-control" name="direccion">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnSaveProfile">
                        <i class="bi bi-save me-2"></i>
                        Guardar cambios
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="bi bi-grid-1x2-fill me-2"></i>
                Menú principal
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body menu-buttons">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#homologacionModal">
                <i class="bi bi-clipboard-check me-2"></i>
                Proceso de homologación
            </button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#miInformacionModal">
                <i class="bi bi-person-vcard me-2"></i>
                Información general
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#primeraHomologacionModal">
                <i class="bi bi-eye me-2"></i>
                Ver primera homologación
            </button>
            <button class="btn btn-danger" id="btnLogout">
                <i class="bi bi-box-arrow-right me-2"></i>
                Cerrar sesión
            </button>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Header -->
    <div class="uac-header">
        <div class="container text-center">
            <h2 class="display-4 fw-bold">Universidad Autónoma del Cauca</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="lead uac-slogan">Formamos líderes con <span class="accent-text">visión</span>, <span
                            class="accent-text">compromiso</span> y <span class="accent-text">excelencia</span></p>
                    <p class="d-none d-md-block">30 años de trayectoria educativa | Acreditación de Alta Calidad |
                        Popayán, Cauca</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Carousel -->
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="carousel-overlay"></div>
                <img src="https://www.uniautonoma.edu.co/sites/default/files/noticia/dsc_0071_0.jpg"
                    class="d-block w-100" alt="Campus Universitario">
                <div class="carousel-caption">
                    <div class="caption-box">
                        <h3>Formación integral y humanista</h3>
                        <p>Desarrollamos profesionales competentes que transforman la realidad regional con
                            responsabilidad social e innovación</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-overlay"></div>
                <img src="https://static1.educaedu-colombia.com/adjuntos/12/00/06/corporaci-n-universitaria-aut-noma-del-cauca-000682_large.jpg"
                    class="d-block w-100" alt="Carreras de Ingeniería">
                <div class="carousel-caption">
                    <div class="caption-box">
                        <h3>Excelencia académica y tecnológica</h3>
                        <p>Programas de vanguardia diseñados para los retos del siglo XXI con proyección internacional
                        </p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="carousel-overlay"></div>
                <img src="https://www.uniautonoma.edu.co/sites/default/files/noticia/dsc_0093_0.jpg"
                    class="d-block w-100" alt="Vida Universitaria">
                <div class="carousel-caption">
                    <div class="caption-box">
                        <h3>Comunidad universitaria vibrante</h3>
                        <p>Un espacio donde el conocimiento, la cultura y el desarrollo personal confluyen para crear
                            experiencias transformadoras</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Interactive Section -->
    <div class="container">
        <div class="interactive-section">
            <div class="row w-100">
                <div class="col-md-4">
                    <div class="feature-card text-center" data-bs-toggle="modal" data-bs-target="#homologacionModal">
                        <i class="bi bi-clipboard-check feature-icon"></i>
                        <h5>Proceso de homologación</h5>
                        <p>Consulta el estado de tu solicitud y los detalles del proceso</p>
                        <span class="badge bg-secondary" id="statusHomologacion">Cargando...</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center" data-bs-toggle="modal"
                        data-bs-target="#miInformacionModal">
                        <i class="bi bi-person-vcard feature-icon"></i>
                        <h5>Mi información</h5>
                        <p>Revisa y actualiza tus datos personales y académicos</p>
                        <span class="badge bg-secondary" id="statusInfo">Cargando...</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center" data-bs-toggle="modal"
                        data-bs-target="#primeraHomologacionModal">
                        <i class="bi bi-eye feature-icon"></i>
                        <h5>Primera homologación</h5>
                        <p>Visualiza los detalles de tu primera solicitud de homologación</p>
                        <span class="badge bg-secondary" id="statusPrimeraHomologacion">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <h2>Corporación Universitaria Autónoma del Cauca</h2>
        <p class="lead">Es una institución de educación superior, sin ánimo de lucro, fundada para responder a las
            necesidades educativas de la región y del país.</p>
        <p>Forma profesionales altamente competitivos por su proyección científica, sensibilidad social y liderazgo
            cívico y empresarial.</p>
        <div class="mt-4">
            <span class="badge bg-primary custom-badge me-2">Calidad educativa</span>
            <span class="badge bg-success custom-badge me-2">Excelencia académica</span>
            <span class="badge bg-info custom-badge">Proyección social</span>
        </div>
    </div>

    <!-- Homologación Modal -->
    <div class="modal fade" id="homologacionModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Proceso de homologación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Información general</h5>
                                </div>
                                <div class="card-body">
                                    <div class="radicado-number mb-3" id="radicado-number">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        No. Radicado: Cargando...
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong>Estudiante:</strong>
                                        <span id="homologacion-estudiante">Cargando...</span>
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong>Programa:</strong>
                                        <span id="homologacion-programa">Cargando...</span>
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong>Fecha de solicitud:</strong>
                                        <span id="homologacion-fecha">Cargando...</span>
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong>Última actualización:</strong>
                                        <span id="homologacion-actualizacion">Cargando...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-secondary text-white" id="estado-header">
                                    <h5 class="mb-0">Estado actual</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-secondary" id="estado-alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Cargando estado de la solicitud...
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-secondary" id="progress-bar" role="progressbar"
                                            style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                            aria-valuemax="100">0% Completado
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-progress mb-4">
                        <div class="step" id="step-radicacion">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Radicación
                        </div>
                        <div class="step" id="step-revision">
                            <i class="bi bi-search me-1"></i>
                            Revisión
                        </div>
                        <div class="step" id="step-evaluacion">
                            <i class="bi bi-clipboard-check me-1"></i>
                            Evaluación
                        </div>
                        <div class="step" id="step-aprobacion">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Aprobación
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="homologacionTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="timeline-tab" data-bs-toggle="tab"
                                data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline"
                                aria-selected="true">
                                <i class="bi bi-clock-history me-1"></i>
                                Historial del proceso
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="subjects-tab" data-bs-toggle="tab"
                                data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects"
                                aria-selected="false">
                                <i class="bi bi-journal-check me-1"></i>
                                Asignaturas homologadas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                data-bs-target="#documents" type="button" role="tab" aria-controls="documents"
                                aria-selected="false">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Documentos
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="homologacionTabContent">
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel"
                            aria-labelledby="timeline-tab">
                            <div class="timeline" id="proceso-timeline">
                                <div class="timeline-placeholder">
                                    <p class="text-center text-muted">Cargando historial del proceso...</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
                            <div id="asignaturas-container">
                                <p class="text-center text-muted">Cargando asignaturas homologadas...</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                            <div id="documentos-container">
                                <div class="text-center">
                                    <i class="bi bi-hourglass-split me-2"></i>
                                    <span class="text-muted">Cargando documentos...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mi Información Modal -->
    <div class="modal fade" id="miInformacionModal" tabindex="-1" aria-labelledby="miInformacionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="miInformacionModalLabel">
                        <i class="bi bi-person-vcard me-2"></i>
                        Mi información
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombres y apellidos:</strong> <span id="info-nombre">Cargando...</span></p>
                            <p><strong>Cédula:</strong> <span id="info-cedula">Cargando...</span></p>
                            <p><strong>Teléfono:</strong> <span id="info-telefono">Cargando...</span></p>
                            <p><strong>Correo:</strong> <span id="info-correo">Cargando...</span></p>
                            <p><strong>Dirección:</strong> <span id="info-direccion">Cargando...</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Programa:</strong> <span id="info-programa">Cargando...</span></p>
                            <p><strong>Estado:</strong> <span class="badge bg-secondary"
                                    id="info-estado">Cargando...</span></p>
                            <p><strong>Institución de Origen:</strong> <span id="info-institucion">Cargando...</span>
                            </p>
                            <p><strong>Departamento de Origen:</strong> <span id="info-departamento">Cargando...</span>
                            </p>
                            <p><strong>Municipio de Origen:</strong> <span id="info-municipio">Cargando...</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Primera Homologación Modal -->
    <div class="modal fade" id="primeraHomologacionModal" tabindex="-1"
        aria-labelledby="primeraHomologacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="primeraHomologacionModalLabel">
                        <i class="bi bi-eye me-2"></i>
                        Primera homologación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <span id="primera-homologacion-info">Cargando información de la primera homologación...</span>
                    </div>
                    <div class="radicado-number mb-3" id="primera-homologacion-radicado">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        No. Radicado: Cargando...
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Código</th>
                                    <th>Asignatura origen</th>
                                    <th>Institución origen</th>
                                    <th>Nota</th>
                                    <th>Código</th>
                                    <th>Asignatura homologada</th>
                                    <th>Nota Destino</th>
                                    <th>Créditos</th>
                                </tr>
                            </thead>
                            <tbody id="primera-homologacion-asignaturas">
                                <tr>
                                    <td colspan="8" class="text-center">Cargando asignaturas...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Total de créditos homologados: <strong id="primera-homologacion-creditos">0</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Cierre de Sesión -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Confirmar cierre de sesión
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-box-arrow-right text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center fs-5">¿Está seguro que desea cerrar su sesión?</p>
                    <p class="text-center text-muted">Al confirmar, saldrá del sistema y deberá iniciar sesión
                        nuevamente para acceder.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmarCerrarSesion()">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Cerrar sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar PDF -->
    <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewerModalLabel">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        Resolución de Homologación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height: 80vh;">
                    <div id="pdfLoadingIndicator" class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando PDF...</span>
                            </div>
                            <p class="mt-3 text-muted">Cargando documento...</p>
                        </div>
                    </div>
                    <iframe id="pdfViewer" class="w-100 h-100 border-0" style="display: none;"
                        title="Visualizador de PDF">
                    </iframe>
                    <div id="pdfError" class="alert alert-danger m-3" style="display: none;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No se pudo cargar el documento.
                        <a href="#" id="pdfDirectLink" target="_blank" class="alert-link">
                            Hacer clic aquí para abrir en nueva ventana
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" id="downloadPdfBtn">
                        <i class="bi bi-download me-2"></i>
                        Descargar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cargar los scripts -->
    <script src="{{ asset('js/authService.js') }}"></script>
    <script src="{{ asset('js/authMiddleware.js') }}"></script>
    <script src="{{ asset('js/login-script.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>

    <!-- Scripts adicionales para manejo de PDFs -->
    <script>
        // Función para ver PDF en nueva ventana/pestaña
        function verPDFEnNuevaVentana(urlPdf) {
            if (!urlPdf) {
                mostrarNotificacion('URL del documento no disponible', 'error');
                return;
            }

            // Validar URL antes de abrir
            if (!validarUrlPdf(urlPdf)) {
                mostrarNotificacion('El enlace del documento no es válido', 'error');
                return;
            }

            try {
                // Mostrar notificación
                mostrarNotificacion('Abriendo documento en nueva pestaña...', 'info');

                // Abrir en nueva ventana/pestaña
                const nuevaVentana = window.open(urlPdf, '_blank');

                // Verificar si se bloqueó el popup
                if (!nuevaVentana || nuevaVentana.closed || typeof nuevaVentana.closed == 'undefined') {
                    mostrarNotificacion(
                        'Se bloqueó la ventana emergente. Por favor, permita ventanas emergentes y vuelva a intentar.',
                        'warning');

                    // Como alternativa, intentar usar location.href
                    setTimeout(() => {
                        if (confirm('¿Desea abrir el documento en esta misma pestaña?')) {
                            window.location.href = urlPdf;
                        }
                    }, 2000);
                } else {
                    // Éxito al abrir
                    setTimeout(() => {
                        mostrarNotificacion('Documento abierto exitosamente', 'success');
                    }, 1000);
                }
            } catch (error) {
                console.error('Error al abrir PDF:', error);
                mostrarNotificacion('Error al abrir el documento. Intente descargar el archivo.', 'error');
            }
        }

        // Función mejorada para descargar PDF
        function descargarPDFResolucion(urlPdf, numeroRadicado) {
            if (!urlPdf) {
                mostrarNotificacion('URL del documento no disponible', 'error');
                return;
            }

            // Mostrar notificación de inicio de descarga
            mostrarNotificacion('Iniciando descarga del documento...', 'info');

            try {
                // Crear elemento link temporal para descarga
                const link = document.createElement('a');
                link.href = urlPdf;
                link.download = `Resolucion_Homologacion_${numeroRadicado || 'documento'}.pdf`;
                link.target = '_blank';

                // Agregar atributos adicionales para mejor compatibilidad
                link.rel = 'noopener noreferrer';

                // Agregar al DOM, hacer click y remover
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Mostrar confirmación
                setTimeout(() => {
                    mostrarNotificacion('Descarga iniciada correctamente', 'success');
                }, 1000);
            } catch (error) {
                console.error('Error en descarga:', error);
                mostrarNotificacion('Error al iniciar la descarga. Abriendo en nueva ventana como alternativa...',
                    'warning');

                // Como fallback, abrir en nueva ventana
                setTimeout(() => {
                    window.open(urlPdf, '_blank');
                }, 1000);
            }
        }

        // Función para validar URLs de PDF (mantener esta función)
        function validarUrlPdf(url) {
            if (!url) return false;

            // Verificar que la URL termine en .pdf o contenga parámetros de PDF
            const esPdf = url.toLowerCase().includes('.pdf') ||
                url.toLowerCase().includes('application/pdf') ||
                url.toLowerCase().includes('pdf');

            // Verificar que sea una URL válida
            try {
                new URL(url);
                return esPdf;
            } catch {
                return false;
            }
        }

        // Función mejorada para descargar PDF
        function descargarPDFResolucion(urlPdf, numeroRadicado) {
            if (!urlPdf) {
                mostrarNotificacion('URL del documento no disponible', 'error');
                return;
            }

            // Mostrar notificación de inicio de descarga
            mostrarNotificacion('Iniciando descarga del documento...', 'info');

            try {
                // Crear elemento link temporal para descarga
                const link = document.createElement('a');
                link.href = urlPdf;
                link.download = `Resolucion_Homologacion_${numeroRadicado || 'documento'}.pdf`;
                link.target = '_blank';

                // Agregar al DOM, hacer click y remover
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Mostrar confirmación
                setTimeout(() => {
                    mostrarNotificacion('Descarga iniciada correctamente', 'success');
                }, 1000);
            } catch (error) {
                console.error('Error en descarga:', error);
                mostrarNotificacion('Error al iniciar la descarga. Intente abrir el documento directamente.', 'error');

                // Como fallback, abrir en nueva ventana
                window.open(urlPdf, '_blank');
            }
        }

        // Limpiar modal al cerrarlo
        document.getElementById('pdfViewerModal').addEventListener('hidden.bs.modal', function() {
            const pdfViewer = document.getElementById('pdfViewer');
            pdfViewer.src = '';
        });

        // Función para validar URLs de PDF
        function validarUrlPdf(url) {
            if (!url) return false;

            // Verificar que la URL termine en .pdf o contenga parámetros de PDF
            const esPdf = url.toLowerCase().includes('.pdf') ||
                url.toLowerCase().includes('application/pdf') ||
                url.toLowerCase().includes('pdf');

            // Verificar que sea una URL válida
            try {
                new URL(url);
                return esPdf;
            } catch {
                return false;
            }
        }

        // Función de utilidad para formatear nombres de archivos
        function formatearNombreArchivo(numeroRadicado, tipoDocumento = 'resolucion') {
            const fecha = new Date().toISOString().split('T')[0]; // YYYY-MM-DD
            const nombre = `${tipoDocumento}_homologacion_${numeroRadicado || 'sin_radicado'}_${fecha}.pdf`;
            return nombre.replace(/[^a-zA-Z0-9.-]/g, '_'); // Limpiar caracteres especiales
        }

        // Función para manejar errores de carga de documentos
        function manejarErrorDocumento(error, urlPdf) {
            console.error('Error al cargar documento:', error);

            // Intentar diferentes estrategias según el tipo de error
            if (error.name === 'NetworkError' || error.message.includes('network')) {
                mostrarNotificacion('Error de conexión. Verificando estado del servidor...', 'warning');

                // Reintentar después de un momento
                setTimeout(() => {
                    window.open(urlPdf, '_blank');
                }, 2000);
            } else {
                mostrarNotificacion('No se pudo cargar el documento en el visor interno. Abriendo en nueva ventana...',
                    'info');
                window.open(urlPdf, '_blank');
            }
        }

        // Event listeners adicionales para mejorar la experiencia
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar tooltips para botones de documentos
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl));

            // Precargar el modal de PDF para mejor rendimiento
            const pdfModal = document.getElementById('pdfViewerModal');
            if (pdfModal) {
                // Pre-inicializar el modal sin mostrarlo
                new bootstrap.Modal(pdfModal, {
                    show: false
                });
            }
        });

        // Función para verificar el estado del servidor de archivos
        function verificarEstadoServidor() {
            fetch('/storage/test.txt')
                .then(response => {
                    if (response.ok) {
                        console.log('Servidor de archivos disponible');
                    } else {
                        console.warn('Posibles problemas con el servidor de archivos');
                    }
                })
                .catch(error => {
                    console.error('Servidor de archivos no disponible:', error);
                });
        }

        // Verificar estado del servidor al cargar la página
        setTimeout(verificarEstadoServidor, 2000);
    </script>
</body>

</html>

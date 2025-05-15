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
            // Si no hay token o datos de usuario, redirige al login
            window.location.href = `${baseRoute}/auth/login`;
        } else {
            const user = JSON.parse(userData);

            if (user.rol_id !== 1) {
                // Si NO es aspirante, redirige según su rol
                let redirectUrl;

                switch (user.rol_id) {
                    case 2:
                        redirectUrl = `${baseRoute}/coordinador/inicio`;
                        break;
                    case 3:
                        redirectUrl = `${baseRoute}/administrador`;
                        break;
                    default:
                        redirectUrl = `${baseRoute}/auth/login`;
                }

                window.location.href = redirectUrl;
            }
        }
    </script>


    <link rel="stylesheet" href="{{ asset('css/dashboard_aspirante.css') }}">
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

    <!-- Sidebar Menu (Ahora a la derecha) -->
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
    <!-- Carousel -->
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

    <!-- Carousel mejorado -->
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
                                    <div class="radicado-number" id="radicado-number">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        No. Radicado: Cargando...
                                    </div>
                                    <p><strong>Estudiante:</strong> <span
                                            id="homologacion-estudiante">Cargando...</span></p>
                                    <p><strong>Programa:</strong> <span id="homologacion-programa">Cargando...</span>
                                    </p>
                                    <p><strong>Fecha de solicitud:</strong> <span
                                            id="homologacion-fecha">Cargando...</span></p>
                                    <p><strong>Última actualización:</strong> <span
                                            id="homologacion-actualizacion">Cargando...</span></p>
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
                                <!-- Aquí se cargarán dinámicamente los eventos del timeline -->
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
                                <p class="text-center text-muted">Cargando documentos...</p>
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
                            <p><strong>Institución de Origen:</strong> <span id="info-institucion">Cargando...</span></p>
                            <p><strong>Departamento de Origen:</strong> <span id="info-departamento">Cargando...</span></p>
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
                                    <th>Créditos</th>
                                </tr>
                            </thead>
                            <tbody id="primera-homologacion-asignaturas">
                                <tr>
                                    <td colspan="7" class="text-center">Cargando asignaturas...</td>
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

    <!-- Cargar los scripts PRIMERO -->
    <script src="{{ asset('js/authService.js') }}"></script>
    <script src="{{ asset('js/authMiddleware.js') }}"></script>
    <script src="{{ asset('js/login-script.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>

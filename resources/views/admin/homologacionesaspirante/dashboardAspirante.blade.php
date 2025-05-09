<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Homologación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
     <!-- Protección ANTES de cargar el authService -->
    <script>
        // Verificar autenticación ANTES de cargar la página
        if (!localStorage.getItem('auth_token')) {
            window.location.href = '{{ route('login') }}';
        }
    </script>
    <style>
        :root {
            --primary-color: #19407B;
            --secondary-color: #f8f9fa;
            --accent-color: #17a2b8;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Roboto', sans-serif;
        }

        .navbar {
    background-color: var(--primary-color);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
        .navbar-brand, .nav-link, .nav-icon {
            color: white !important;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
        }

        .nav-icon {
            font-size: 1.5rem;
            transition: transform 0.3s;
            margin: 0 10px;
        }

        .nav-icon:hover {
            transform: scale(1.1);
        }

        .nav-icon-text {
        font-size: 0.9rem;
        color:rgb(241, 237, 243);
        display: block;
        text-align: center;
        margin-top: -5px;
}

        .container {
            margin-top: 20px;
        }

        .carousel {
            max-width: 1100px;
            margin: 30px auto;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .carousel-inner img {
            height: 350px;
            object-fit: cover;
        }

        .info-section {
            text-align: center;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            margin: 30px auto;
            max-width: 900px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .info-section h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
        }

        .offcanvas {
            background-color: white;
            padding: 20px;
            width: 300px;
        }

        .offcanvas-header {
            border-bottom: 1px solid #eee;
        }

        .offcanvas-title {
            color: var(--primary-color);
            font-weight: 600;
        }

        .menu-buttons button {
            width: 100%;
            margin-bottom: 15px;
            font-size: 16px;
            padding: 12px;
            border-radius: 8px;
            color: white;
            transition: all 0.3s;
            border: none;
        }

        .menu-buttons button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .menu-buttons .btn-primary {
            background-color: var(--primary-color);
        }

        .menu-buttons .btn-info {
            background-color: var(--accent-color);
        }

        .menu-buttons .btn-danger {
            background-color: var(--danger-color);
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }

        .toast {
            opacity: 0;
            transition: opacity 0.3s;
            animation: slideIn 0.5s forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .step-progress {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }

        .step {
            text-align: center;
            flex-grow: 1;
            padding: 15px;
            margin: 0 5px;
            border-radius: 10px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(248, 246, 246, 0.05);
            position: relative;
            transition: all 0.3s;
        }

        .step:hover {
            transform: translateY(-3px);
        }

        .step:not(:last-child):after {
            content: "";
            position: absolute;
            top: 50%;
            right: -15px;
            width: 20px;
            height: 2px;
            background-color: #ccc;
        }

        .step.pending {
            background-color: #f0f0f0;
            color: #777;
        }

        .step.review {
            background-color: var(--warning-color);
            color: white;
        }

        .step.accepted {
            background-color: var(--success-color);
            color: white;
        }

        .step.rejected {
            background-color: var(--danger-color);
            color: white;
        }

        .profile-info {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(252, 249, 249, 0.05);
        }

        .profile-info h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .profile-info .row {
            margin-bottom: 10px;
        }

        .profile-info .label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }

        .modal-content {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
        }

        .modal-title {
            font-weight: 600;
        }

        .btn-close {
            background-color: white;
        }

        .custom-badge {
            font-size: 0.9rem;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        /* Estilos para el icono de usuario */
        .user-icon {
            color: white;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .user-icon:hover {
            transform: scale(1.1);
        }

        /* Estilos para el modal de perfil */
        .user-credentials {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }

        .credential-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .credential-value {
            font-family: monospace;
            background-color: white;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 20px auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: var(--primary-color);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        .timeline-container {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
        }

        .timeline-container::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -13px;
            background-color: white;
            border: 4px solid var(--primary-color);
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .left {
            left: 0;
        }

        .right {
            left: 50%;
        }

        .right::after {
            left: -12px;
        }

        .timeline-content {
            padding: 20px 30px;
            background-color: white;
            position: relative;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .radicado-number {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .feature-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        /* Nuevos estilos para elementos interactivos */
        .interactive-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }

        .student-info-editable {
            position: relative;
        }

        .edit-icon {
            cursor: pointer;
            color: var(--primary-color);
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .edit-form-group {
            margin-bottom: 15px;
        }

        .document-preview {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            background-color: #f9f9f9;
        }

        .document-preview h6 {
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .document-preview p {
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .document-actions {
            margin-top: 10px;
        }
        .uac-header {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        padding: 1.5rem 0;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .uac-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(25, 64, 123, 0.9) 0%, rgba(23, 162, 184, 0.7) 100%);
        z-index: 1;
    }

    .uac-header .container {
        position: relative;
        z-index: 2;
    }

    .uac-slogan {
        font-style: italic;
        letter-spacing: 1px;
        margin-top: 0.5rem;
    }

    .uac-header .accent-text {
        color: var(--accent-color);
        font-weight: bold;
    }

    .carousel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(25, 64, 123, 0.3), rgba(25, 64, 123, 0.1));
        z-index: 1;
    }

    .carousel-caption {
        z-index: 2;
        bottom: 20%;
    }

    .caption-box {
        background-color: rgba(25, 64, 123, 0.8);
        border-left: 5px solid var(--accent-color);
        padding: 1.5rem;
        border-radius: 4px;
        max-width: 80%;
        margin: 0 auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .carousel-caption h3 {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }

    .carousel-indicators {
        bottom: 30px;
    }

    .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 6px;
        background-color: var(--secondary-color);
        opacity: 0.6;
    }

    .carousel-indicators button.active {
        background-color: var(--accent-color);
        opacity: 1;
    }

    .carousel-control-prev, .carousel-control-next {
        width: 5%;
        opacity: 0.8;
    }

    .carousel-item {
        height: 500px;
    }

    .carousel-item img {
        object-fit: cover;
        height: 100%;
    }


    </style>
</head>
<body>
     <!-- Cargar los scripts PRIMERO -->
    <script src="{{ asset('js/authService.js') }}"></script>
    <script src="{{ asset('js/authMiddleware.js') }}"></script>
    <script src="{{ asset('js/login-script.js') }}"></script>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="bi bi-building-check me-2"></i>
                Homologaciones Uniautónoma
            </a>
            <div class="d-flex align-items-center">
                <div class="text-center mx-2">
                    <button class="btn p-0" onclick="showNotification()" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notificaciones">
                        <i class="bi bi-bell-fill nav-icon"></i>
                        <span class="nav-icon-text">Notificaciones</span>
                    </button>
                </div>
                <div class="text-center mx-2">
                    <button class="btn p-0" data-bs-toggle="modal" data-bs-target="#userProfileModal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Perfil de Usuario">
                        <i class="bi bi-person-circle nav-icon"></i>
                        <span class="nav-icon-text">Perfil</span>
                    </button>
                </div>
                <div class="text-center mx-2">
                    <button class="btn p-0" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Menú">
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
                            <h5 class="mb-0">Carlos Andrés Pérez Rodríguez</h5>
                            <p class="text-muted mb-0">Postulante</p>
                        </div>
                    </div>

                    <div class="user-credentials">
                        <h6 class="mb-3">Credenciales de acceso</h6>
                        <div class="credential-item">
                            <span><i class="bi bi-envelope-fill me-2"></i>Correo:</span>
                            <span class="credential-value">caperez@uniautonoma.edu.co</span>
                        </div>
                        <div class="credential-item">
                            <span><i class="bi bi-key-fill me-2"></i>Contraseña:</span>
                            <span class="credential-value">87654321</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
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
                            <input type="text" class="form-control" value="Carlos Andrés Pérez Rodríguez">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Correo electrónico:</label>
                            <input type="email" class="form-control" value="caperez@uniautonoma.edu.co">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Cédula:</label>
                            <input type="text" class="form-control" value="1087654321">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Teléfono:</label>
                            <input type="tel" class="form-control" value="3201234567">
                        </div>
                        <div class="edit-form-group">
                            <label class="form-label">Dirección:</label>
                            <input type="text" class="form-control" value="Calle 5 #15-45, Popayán">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="saveProfileChanges()">
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
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#infoModal">
                <i class="bi bi-person-vcard me-2"></i>
                Información general
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#primeraHomologacionModal">
                <i class="bi bi-eye me-2"></i>
                Ver primera homologación
            </button>
            <button class="btn btn-danger" onclick="logout()">
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
                <p class="lead uac-slogan">Formamos líderes con <span class="accent-text">visión</span>, <span class="accent-text">compromiso</span> y <span class="accent-text">excelencia</span></p>
                <p class="d-none d-md-block">30 años de trayectoria educativa | Acreditación de Alta Calidad | Popayán, Cauca</p>
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
            <img src="https://www.uniautonoma.edu.co/sites/default/files/noticia/dsc_0071_0.jpg" class="d-block w-100" alt="Campus Universitario">
            <div class="carousel-caption">
                <div class="caption-box">
                    <h3>Formación integral y humanista</h3>
                    <p>Desarrollamos profesionales competentes que transforman la realidad regional con responsabilidad social e innovación</p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="carousel-overlay"></div>
            <img src="https://static1.educaedu-colombia.com/adjuntos/12/00/06/corporaci-n-universitaria-aut-noma-del-cauca-000682_large.jpg" class="d-block w-100" alt="Carreras de Ingeniería">
            <div class="carousel-caption">
                <div class="caption-box">
                    <h3>Excelencia académica y tecnológica</h3>
                    <p>Programas de vanguardia diseñados para los retos del siglo XXI con proyección internacional</p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="carousel-overlay"></div>
            <img src="https://www.uniautonoma.edu.co/sites/default/files/noticia/dsc_0093_0.jpg" class="d-block w-100" alt="Vida Universitaria">
            <div class="carousel-caption">
                <div class="caption-box">
                    <h3>Comunidad universitaria vibrante</h3>
                    <p>Un espacio donde el conocimiento, la cultura y el desarrollo personal confluyen para crear experiencias transformadoras</p>
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
                        <span class="badge bg-success">Estado activo</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center" data-bs-toggle="modal" data-bs-target="#miInformacionModal">
                        <i class="bi bi-person-vcard feature-icon"></i>
                        <h5>Mi información</h5>
                        <p>Revisa y actualiza tus datos personales y académicos</p>
                        <span class="badge bg-primary">Actualizada</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center" data-bs-toggle="modal" data-bs-target="#primeraHomologacionModal">
                        <i class="bi bi-eye feature-icon"></i>
                        <h5>Primera homologación</h5>
                        <p>Visualiza los detalles de tu primera solicitud de homologación</p>
                        <span class="badge bg-info">Disponible</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <h2>Corporación Universitaria Autónoma del Cauca</h2>
        <p class="lead">Es una institución de educación superior, sin ánimo de lucro, fundada para responder a las necesidades educativas de la región y del país.</p>
        <p>Forma profesionales altamente competitivos por su proyección científica, sensibilidad social y liderazgo cívico y empresarial.</p>
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
                                    <div class="radicado-number">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        No. Radicado: HOM-2025-7733
                                    </div>
                                    <p><strong>Estudiante:</strong> Carlos Andrés Pérez Rodríguez</p>
                                    <p><strong>Programa:</strong> Ingeniería de Software</p>
                                    <p><strong>Fecha de solicitud:</strong> 15/02/2025</p>
                                    <p><strong>Última actualización:</strong> 28/02/2025</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Estado actual</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Su solicitud de homologación ha sido aprobada. A continuación puede ver el detalle del proceso.
                                    </div>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100% Completado</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-progress mb-4">
                        <div class="step accepted">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Radicación
                        </div>
                        <div class="step accepted">
                            <i class="bi bi-search me-1"></i>
                            Revisión
                        </div>
                        <div class="step accepted">
                            <i class="bi bi-clipboard-check me-1"></i>
                            Evaluación
                        </div>
                        <div class="step accepted">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Aprobación
                        </div>
                    </div>

                    <ul class="nav nav-tabs" id="homologacionTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="true">
                                <i class="bi bi-clock-history me-1"></i>
                                Historial del proceso
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">
                                <i class="bi bi-journal-check me-1"></i>
                                Asignaturas homologadas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                Documentos
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="homologacionTabContent">
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            <div class="timeline">
                                <div class="timeline-container left">
                                    <div class="timeline-content">
                                        <h5>Radicación de solicitud</h5>
                                        <p class="text-muted">15/02/2025 - 10:30 AM</p>
                                        <p>Se ha registrado correctamente su solicitud de homologación para el programa de Ingeniería de Software.</p>
                                    </div>
                                </div>
                                <div class="timeline-container right">
                                    <div class="timeline-content">
                                        <h5>Verificación de documentos</h5>
                                        <p class="text-muted">18/02/2025 - 09:15 AM</p>
                                        <p>Los documentos presentados han sido verificados y cumplen con los requisitos establecidos.</p>
                                    </div>
                                </div>
                                <div class="timeline-container left">
                                    <div class="timeline-content">
                                        <h5>Evaluación académica</h5>
                                        <p class="text-muted">22/02/2025 - 03:40 PM</p>
                                        <p>El comité académico ha evaluado las asignaturas solicitadas para homologación.</p>
                                    </div>
                                </div>
                                <div class="timeline-container right">
                                    <div class="timeline-content">
                                        <h5>Aprobación de homologación</h5>
                                        <p class="text-muted">28/02/2025 - 11:20 AM</p>
                                        <p>Su solicitud de homologación ha sido aprobada. En la sección de asignaturas homologadas puede ver el detalle.</p>
                                    </div>
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
    <div class="modal fade" id="miInformacionModal" tabindex="-1" aria-labelledby="miInformacionModalLabel" aria-hidden="true">
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
                            <p><strong>Nombres y apellidos:</strong> Carlos Andrés Pérez Rodríguez</p>
                            <p><strong>Cédula:</strong> 1087654321</p>
                            <p><strong>Teléfono:</strong> 3201234567</p>
                            <p><strong>Correo:</strong> caperez@uniautonoma.edu.co</p>
                            <p><strong>Dirección:</strong> Calle 5 #15-45, Popayán</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Programa:</strong> Ingeniería de Software</p>
                            <p><strong>Semestre:</strong> 3</p>
                            <p><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                            <p><strong>Promedio:</strong> 4.2</p>
                            <p><strong>Créditos aprobados:</strong> 52 de 160</p>
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
    <div class="modal fade" id="primeraHomologacionModal" tabindex="-1" aria-labelledby="primeraHomologacionModalLabel" aria-hidden="true">
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
                        La primera homologación fue realizada el 15/01/2024 para su ingreso al programa de Ingeniería de Software.
                    </div>
                    <div class="radicado-number mb-3">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        No. Radicado: HOM-2024-3452
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
                            <tbody>
                                <tr>
                                    <td>COMP101</td>
                                    <td>Fundamentos de Programación</td>
                                    <td>Instituto Tecnológico</td>
                                    <td>4.5</td>
                                    <td>PROG101</td>
                                    <td>Introducción a la Programación</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>MATE110</td>
                                    <td>Matemáticas Básicas</td>
                                    <td>Instituto Tecnológico</td>
                                    <td>4.0</td>
                                    <td>MAT100</td>
                                    <td>Matemáticas Fundamentales</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>ING101</td>
                                    <td>Inglés I</td>
                                    <td>Instituto Tecnológico</td>
                                    <td>3.8</td>
                                    <td>FLEN110</td>
                                    <td>Lengua Extranjera I</td>
                                    <td>2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-success mt-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Total de créditos homologados: <strong>9</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Script -->
    <script>
        // Función para mostrar notificaciones
        function showNotification() {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.innerHTML = `
                <div class="toast-header">
                    <strong class="me-auto"><i class="bi bi-bell-fill me-2"></i>Notificación</strong>
                    <small>Ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <p>Su proceso de homologación ha sido completado con éxito.</p>
                    <p class="mb-0">Puede consultar los detalles en la sección de homologaciones.</p>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Eliminar el toast después de 5 segundos
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Función para cerrar sesión
        function logout() {
            if (confirm('¿Está seguro que desea cerrar sesión?')) {
                // Aquí iría la lógica de cerrar sesión
                alert('Sesión cerrada exitosamente');
                // Redirigir a la página de inicio de sesión
                 window.location.href = 'index.html';
            }
        }

        // Función para guardar cambios en el perfil
        function saveProfileChanges() {
            // Aquí iría la lógica para guardar los cambios
            $('#editProfileModal').modal('hide');

            // Mostrar notificación
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast show';
            toast.innerHTML = `
                <div class="toast-header">
                    <strong class="me-auto"><i class="bi bi-check-circle-fill me-2 text-success"></i>Éxito</strong>
                    <small>Ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    Los datos del perfil han sido actualizados correctamente.
                </div>
            `;

            toastContainer.appendChild(toast);

            // Eliminar el toast después de 5 segundos
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
    </html>

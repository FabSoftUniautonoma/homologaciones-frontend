@extends('admin.layouts.appadmin')

@section('content')
<div class="container-fluid p-0">
    <!-- Header institucional -->
    <div class="header-banner" style="background-color: #003366">
        <div class="container-fluid">
            <div class="row align-items-center py-3">
                <div class="col-md-2 text-center text-md-start">
                    <!-- Espacio para logo -->
                </div>
                <div class="col-md-8 text-center">
                    <h1 class="header-title">Sistema de homologaciones</h1>
                    <p class="header-subtitle">Gestión de Instituciones, Programas y Asignaturas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor principal con tres columnas -->
    <div class="row g-0">
        <!-- Panel de instituciones -->
        <div class="col-md-4 panel-column">
            <div class="card panel-card h-100">
                <div class="card-header panel-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="panel-title">
                            <i class="fas fa-university me-2"></i>Instituciones
                        </h5>
                        <div>
                            <span id="contador-instituciones" class="badge counter-badge me-2">0</span>
                           <a href="{{ route('crearinsti') }}" class="btn btn-sm btn-success" id="btn-nueva-institucion">
    <i class="fas fa-plus-circle"></i>
</a>

                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text search-icon">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control search-input" id="buscadorInstituciones" placeholder="Buscar institución...">
                        </div>
                    </div>

                    <div id="estado-carga-instituciones" class="loading-container">
                        <div class="spinner-container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="loading-text">Cargando instituciones...</p>
                        </div>
                    </div>

                    <div class="list-container" id="instituciones-container">
                        <div class="list-group list-group-flush" id="listaInstituciones">
                            <!-- Aquí se cargarán las instituciones -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de programas -->
        <div class="col-md-4 panel-column">
            <div class="card panel-card h-100">
                <div class="card-header panel-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="panel-title">
                            <i class="fas fa-graduation-cap me-2"></i>Programas
                        </h5>
                        <div>
                            <span id="contador-programas" class="badge counter-badge me-2">0</span>
                           <a href="{{ route('crearinsti') }}" class="btn btn-sm btn-success" id="btn-nuevo-programa">
                             <i class="fas fa-plus-circle"></i>
                           </a>

                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text search-icon">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control search-input" id="buscadorProgramas" placeholder="Buscar programa...">
                        </div>
                    </div>

                    <div id="estado-carga-programas" class="loading-container" style="display: none;">
                        <div class="spinner-container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="loading-text">Cargando programas...</p>
                        </div>
                    </div>

                    <div class="list-container" id="programas-container">
                        <div class="list-group list-group-flush" id="listaProgramas">
                            <div class="empty-state">
                                <i class="fas fa-university fa-3x empty-icon"></i>
                                <p class="empty-text">Seleccione una institución para ver sus programas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de asignaturas -->
        <div class="col-md-4 panel-column">
            <div class="card panel-card h-100">
                <div class="card-header panel-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="panel-title">
                            <i class="fas fa-book me-2"></i>Asignaturas
                        </h5>
                        <div>
                            <span id="contador-asignaturas" class="badge counter-badge me-2">0</span>
                            <a href="{{ route('crearasignatura') }}" class="btn btn-sm btn-success" id="btn-nueva-asignatura">
                             <i class="fas fa-plus-circle"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text search-icon">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control search-input" id="buscadorAsignaturas" placeholder="Buscar asignatura...">
                        </div>
                    </div>

                    <div id="estado-carga-asignaturas" class="loading-container" style="display: none;">
                        <div class="spinner-container">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="loading-text">Cargando asignaturas...</p>
                        </div>
                    </div>

                    <div class="list-container" id="asignaturas-container">
                        <div class="list-group list-group-flush" id="listaAsignaturas">
                            <div class="empty-state">
                                <i class="fas fa-book fa-3x empty-icon"></i>
                                <p class="empty-text">Seleccione un programa para ver sus asignaturas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar información completa de la asignatura -->
<div class="modal fade" id="modalAsignatura" tabindex="-1" aria-labelledby="modalAsignaturaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsignaturaLabel">
                    <i class="fas fa-book-open me-2"></i>
                    <span id="modalTituloAsignatura">Información de Asignatura</span>
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs de navegación -->
                <ul class="nav nav-tabs" id="asignaturaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="informacion-tab" data-toggle="tab" href="#informacion" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Información
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contenido-tab" data-toggle="tab" href="#contenido" role="tab">
                            <i class="fas fa-list-alt me-2"></i>Contenido
                        </a>
                    </li>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content mt-4" id="asignaturaTabsContent">
                    <!-- Pestaña de información general -->
                    <div class="tab-pane fade show active" id="informacion" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-card-body">
                                        <div class="info-card-content">
                                            <div class="info-card-title">Código</div>
                                            <div class="info-card-value" id="codigoAsignatura">-</div>
                                        </div>
                                        <div class="info-card-icon">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-card success">
                                    <div class="info-card-body">
                                        <div class="info-card-content">
                                            <div class="info-card-title">Créditos</div>
                                            <div class="info-card-value" id="creditosAsignatura">-</div>
                                        </div>
                                        <div class="info-card-icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="info-card info">
                                    <div class="info-card-body">
                                        <div class="info-card-content">
                                            <div class="info-card-title">Semestre</div>
                                            <div class="info-card-value" id="semestreAsignatura">-</div>
                                        </div>
                                        <div class="info-card-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="details-card">
                                    <div class="details-card-header">
                                        <h6 class="details-card-title">Detalles de la Asignatura</h6>
                                    </div>
                                    <div class="details-card-body">
                                        <table class="details-table">
                                            <tbody>
                                                <tr>
                                                    <td><i class="fas fa-bookmark text-primary me-2"></i>Tipo:</td>
                                                    <td id="tipoAsignatura">-</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-chalkboard text-primary me-2"></i>Modalidad:</td>
                                                    <td id="modalidadAsignatura">-</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-clock text-primary me-2"></i>Horas SENA:</td>
                                                    <td id="horasSenaAsignatura">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="details-card">
                                    <div class="details-card-header">
                                        <h6 class="details-card-title">Información Académica</h6>
                                    </div>
                                    <div class="details-card-body">
                                        <table class="details-table">
                                            <tbody>
                                                <tr>
                                                    <td><i class="fas fa-tasks text-primary me-2"></i>Metodología:</td>
                                                    <td id="metodologiaAsignatura">-</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-university text-primary me-2"></i>Institución:</td>
                                                    <td id="institucionAsignatura">-</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fas fa-graduation-cap text-primary me-2"></i>Programa:</td>
                                                    <td id="programaAsignatura">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña de contenido programático -->
                    <div class="tab-pane fade" id="contenido" role="tabpanel">
                        <div id="cargandoContenido" class="loading-container-centered">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando contenidos...</span>
                            </div>
                            <p class="mt-3 text-primary">Cargando contenido programático...</p>
                        </div>

                        <div id="contenidosProgramaticos">
                            <!-- Aquí se cargarán los contenidos programáticos -->
                        </div>

                        <div id="mensajeSinContenido" class="alert alert-info alert-modern" style="display: none;">
                            <div class="alert-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h5 class="alert-heading">Sin contenidos programáticos</h5>
                                <p class="mb-0">Esta asignatura no tiene contenidos programáticos registrados.</p>
                                <button class="btn btn-secondary btn-sm mt-3 btn-reintentar">
                                    <i class="fas fa-sync-alt me-2"></i> Reintentar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para contenido programático directo -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<div class="modal fade" id="modalContenidoDirecto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg animate__animated animate__fadeInUp">
            <div class="modal-header bg-info text-white align-items-center">
                <h5 class="modal-title d-flex align-items-center" id="tituloContenidoDirecto">
                    <i class="fas fa-list-alt me-2"></i> Contenido Programático
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4" id="contenidoDirectoBody">
                <!-- Aquí se cargan los contenidos -->
            </div>
        </div>
    </div>
</div>
<style>
#contenidoDirectoBody .contenido-card {
    border-left: 4px solid #2c8ab6;
    background: #f7fbfc;
    margin-bottom: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 4px rgba(44,62,80,0.07);
    transition: box-shadow 0.2s;
}
#contenidoDirectoBody .contenido-card:hover {
    box-shadow: 0 4px 16px rgba(44,62,80,0.13);
}
#contenidoDirectoBody .card-header {
    background: #e3f2fd;
    border-bottom: 1px solid #b3e5fc;
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    color: #2c8ab6;
}
#contenidoDirectoBody .card-body {
    background: #fff;
    border-radius: 0 0 0.5rem 0.5rem;
}
</style>

<!-- Notificaciones Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container"></div>

<!-- Estilos mejorados con colores neutros -->
<style>
:root {
    --primary: #607d8b;
    --primary-hover: #546e7a;
    --primary-light: #eceff1;
    --secondary: #78909c;
    --success: #66bb6a;
    --info: #3f58aa;
    --warning: #ffa726;
    --danger: #ef5350;
    --light: #f5f5f5;
    --dark: #37474f;
    --white: #ffffff;
    --border-color: #e0e0e0;
    --sidebar-bg: #455a64;
    --sidebar-hover: #37474f;
    --card-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s ease;
    --border-radius: 0.375rem;
}

/* Reset y estilos generales */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background-color: #f9f9f9;
    color: var(--dark);
    line-height: 1.5;
}

/* Header */
.header-banner {
    padding: 1.25rem 0;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-title {
    color: var(--white);
    font-weight: 600;
    font-size: 1.75rem;
    margin: 0;
}

.header-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    margin: 0.5rem 0 0;
}

/* Layout de paneles */
.container-fluid {
    padding: 0;
}

.row {
    --bs-gutter-x: 0.75rem;
}

.panel-column {
    padding: 0.5rem;
}

.panel-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    background-color: var(--white);
    height: calc(100vh - 130px);
    display: flex;
    flex-direction: column;
}

.panel-header {
    background-color: var(--white);
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.panel-title {
    color: var(--primary);
    font-weight: 500;
    font-size: 1.1rem;
    margin: 0;
}

.counter-badge {
    background-color: var(--primary);
    color: var(--white);
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.3em 0.6em;
    border-radius: 9999px;
}

/* Búsqueda */
.search-container {
    padding: 0.85rem;
    border-bottom: 1px solid var(--border-color);
}

.search-icon {
    background-color: var(--light);
    border: none;
    color: var(--primary);
}

.search-input {
    border: 1px solid var(--border-color);
    padding: 0.6rem;
    font-size: 0.9rem;
    border-radius: 0.25rem;
    background-color: var(--light);
}

.search-input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(96, 125, 139, 0.1);
}

/* Contenedor de listas */
.list-container {
    overflow-y: auto;
    height: 100%;
    padding: 0.25rem 0;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 0.85rem 1rem;
    transition: var(--transition);
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.list-group-item:hover {
    background-color: var(--primary-light);
}

.list-group-item.active {
    background-color: var(--primary-light);
    border-color: var(--border-color);
    color: var(--dark);
}

.list-group-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background-color: var(--primary);
}

/* Botones dentro de los ítems de lista */
.item-actions {
    margin-top: 0.75rem;
    display: flex;
    justify-content: flex-end;
}

.btn-item-action {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
    margin-left: 0.5rem;
}

/* Estados vacíos */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    text-align: center;
}

.empty-icon {
    color: #bdbdbd;
    margin-bottom: 1rem;
}

.empty-text {
    color: #757575;
    font-size: 0.95rem;
}

/* Loaders */
.loading-container {
    padding: 2rem;
    text-align: center;
}

.loading-container-centered {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

.spinner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.loading-text {
    margin-top: 1rem;
    color: var(--primary);
    font-size: 0.9rem;
}

/* Tarjetas informativas */
.info-card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-left: 4px solid var(--primary);
    transition: var(--transition);
    height: 100%;
}

.info-card.success {
    border-left-color: var(--success);
}

.info-card.info {
    border-left-color: var(--info);
}

.info-card-body {
    padding: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
}

.info-card-content {
    flex-grow: 1;
}

.info-card-title {
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.info-card-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
}

.info-card-icon {
    font-size: 1.5rem;
    color: #bdbdbd;
    padding-left: 1rem;
}

/* Tarjetas de detalles */
.details-card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
    height: 100%;
}

.details-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color);
    background-color: rgba(96, 125, 139, 0.05);
}

.details-card-title {
    color: var(--primary);
    font-weight: 500;
    margin: 0;
    font-size: 1rem;
}

.details-card-body {
    padding: 1rem 1.25rem;
}

.details-table {
    width: 100%;
}

.details-table td {
    padding: 0.5rem 0;
    vertical-align: top;
}

.details-table td:first-child {
    width: 40%;
    font-weight: 500;
    color: var(--secondary);
}

.details-table td:last-child {
    font-weight: 500;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background-color: var(--primary);
    color: var(--white);
    border-bottom: none;
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
    padding: 1rem 1.5rem;
}

.modal-title {
    font-weight: 500;
    font-size: 1.2rem;
}

.modal-body {
    padding: 1.5rem;
    max-height: 75vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.btn-close {
    color: var(--white);
    opacity: 0.8;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

/* Pestañas */
.nav-tabs {
    border-bottom: 1px solid var(--border-color);
}

.nav-tabs .nav-link {
    color: var(--secondary);
    border: none;
    padding: 0.75rem 1rem;
    font-weight: 500;
    transition: var(--transition);
    margin-right: 0.5rem;
}

.nav-tabs .nav-link:hover {
    color: var(--primary);
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    background-color: transparent;
    border-bottom: 3px solid var(--primary);
}

/* Contenido programático */
.contenido-card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.75rem;
    border-left: 4px solid var(--info);
    transition: var(--transition);
    overflow: hidden;
}

.contenido-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.contenido-card .card-header {
    background-color: rgba(79, 195, 247, 0.08);
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(79, 195, 247, 0.15);
}

.contenido-card .card-body {
    padding: 1.25rem;
}

/* Alertas modernas */
.alert-modern {
    display: flex;
    align-items: flex-start;
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    padding: 1.25rem;
}

.alert-icon {
    font-size: 1.5rem;
    margin-right: 1.25rem;
    color: var(--info);
}

.alert-content {
    flex-grow: 1;
}

.alert-heading {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

/* Botones */
.btn {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    transition: var(--transition);
}

.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
}

.btn-secondary {
    background-color: var(--secondary);
    border-color: var(--secondary);
}

.btn-secondary:hover {
    background-color: #546e7a;
    border-color: #546e7a;
}

.btn-success {
    background-color: var(--success);
    border-color: var(--success);
}

.btn-success:hover {
    background-color: #4caf50;
    border-color: #4caf50;
}

.btn-info {
    background-color: var(--info);
    border-color: var(--info);
    color: white;
}

.btn-info:hover {
    background-color: #29b6f6;
    border-color: #29b6f6;
    color: white;
}

/* Toast notifications */
.toast-container {
    z-index: 1060;
}

.toast {
    background-color: var(--white);
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    margin-bottom: 0.75rem;
}

.toast-header {
    background-color: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 0.75rem 1rem;
}

.toast-body {
    padding: 1rem;
    font-size: 0.9rem;
}

/* Scrollbars personalizados */
.list-container::-webkit-scrollbar {
    width: 8px;
}

.list-container::-webkit-scrollbar-track {
    background: transparent;
}

.list-container::-webkit-scrollbar-thumb {
    background-color: rgba(120, 144, 156, 0.3);
    border-radius: 4px;
}

.list-container::-webkit-scrollbar-thumb:hover {
    background-color: rgba(120, 144, 156, 0.5);
}

/* Adaptaciones responsive */
@media (max-width: 992px) {
    .panel-column {
        margin-bottom: 1rem;
    }

    .panel-card {
        height: 450px;
    }

    .modal-dialog {
        margin: 0.75rem;
    }
}

@media (max-width: 768px) {
    .panel-card {
        height: 400px;
    }

    .header-title {
        font-size: 1.5rem;
    }

    .header-subtitle {
        font-size: 0.9rem;
    }

    .row {
        --bs-gutter-x: 0.5rem;
    }
}

/* CSS personalizado para botones laterales y botón de contenido */
<style>
.btn-lateral {
    padding: 0.18rem 0.55rem;
    font-size: 0.85rem;
    border-radius: 0.25rem;
    background-color: #90a4ae !important; /* azul suave */
    color: #fff !important;
    border: none;
    margin-left: 0.5rem;
    transition: background 0.2s;
    box-shadow: 0 1px 4px rgba(44,62,80,0.07);
}
.btn-lateral:hover {
    background-color: #78909c !important;
}
.btn-contenido-suave {
    padding: 0.15rem 0.7rem;
    font-size: 0.82rem;
    border-radius: 0.25rem;
    background-color: #b0bec5 !important; /* azul aún más suave */
    color: #263238 !important;
    border: none;
    transition: background 0.2s;
    box-shadow: 0 1px 4px rgba(44,62,80,0.07);
}
.btn-contenido-suave:hover {
    background-color: #90a4ae !important;
    color: #fff !important;
}
</style>
<!-- MODIFICAR RENDER DE BOTONES EN JS:
//
// Para el botón lateral de institución:
// <button type="button" class="btn btn-lateral btn-item-action btn-programas-institucion">
//   <i class="fas fa-graduation-cap me-1"></i> Programas
// </button>
//
// Para el botón lateral de programa:
// <button type="button" class="btn btn-lateral btn-item-action btn-asignaturas-programa">
//   <i class="fas fa-book me-1"></i> Asignaturas
// </button>
//
// Para el botón de contenido:
// <button type="button" class="btn btn-contenido-suave btn-item-action btn-contenido-asignatura">
//   <i class="fas fa-book-open me-1"></i> Contenido
// </button>
-->

<!-- MODAL DINÁMICO Y MODERNO PARA CONTENIDO PROGRAMÁTICO -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<div class="modal fade" id="modalContenidoDirecto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg animate__animated animate__fadeInUp">
            <div class="modal-header bg-info text-white align-items-center">
                <h5 class="modal-title d-flex align-items-center" id="tituloContenidoDirecto">
                    <i class="fas fa-list-alt me-2"></i> Contenido Programático
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4" id="contenidoDirectoBody">
                <!-- Aquí se cargan los contenidos -->
            </div>
        </div>
    </div>
</div>
<style>
#contenidoDirectoBody .contenido-card {
    border-left: 4px solid #4fc3f7;
    background: #f7fbfc;
    margin-bottom: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 4px rgba(44,62,80,0.07);
    transition: box-shadow 0.2s;
}
#contenidoDirectoBody .contenido-card:hover {
    box-shadow: 0 4px 16px rgba(44,62,80,0.13);
}
#contenidoDirectoBody .card-header {
    background: #e3f2fd;
    border-bottom: 1px solid #b3e5fc;
    border-radius: 0.5rem 0.5rem 0 0;
    font-weight: 600;
    color: #1976d2;
}
#contenidoDirectoBody .card-body {
    background: #fff;
    border-radius: 0 0 0.5rem 0.5rem;
}
</style>

<!-- Notificaciones Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container"></div>

<!-- Estilos mejorados con colores neutros -->
<style>
:root {
    --primary: #607d8b;
    --primary-hover: #546e7a;
    --primary-light: #eceff1;
    --secondary: #78909c;
    --success: #66bb6a;
    --info: #4fc3f7;
    --warning: #ffa726;
    --danger: #ef5350;
    --light: #f5f5f5;
    --dark: #37474f;
    --white: #ffffff;
    --border-color: #e0e0e0;
    --sidebar-bg: #455a64;
    --sidebar-hover: #37474f;
    --card-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    --transition: all 0.2s ease;
    --border-radius: 0.375rem;
}

/* Reset y estilos generales */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background-color: #f9f9f9;
    color: var(--dark);
    line-height: 1.5;
}

/* Header */
.header-banner {
    padding: 1.25rem 0;
    margin-bottom: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-title {
    color: var(--white);
    font-weight: 600;
    font-size: 1.75rem;
    margin: 0;
}

.header-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    margin: 0.5rem 0 0;
}

/* Layout de paneles */
.container-fluid {
    padding: 0;
}

.row {
    --bs-gutter-x: 0.75rem;
}

.panel-column {
    padding: 0.5rem;
}

.panel-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
    background-color: var(--white);
    height: calc(100vh - 130px);
    display: flex;
    flex-direction: column;
}

.panel-header {
    background-color: var(--white);
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.panel-title {
    color: var(--primary);
    font-weight: 500;
    font-size: 1.1rem;
    margin: 0;
}

.counter-badge {
    background-color: var(--primary);
    color: var(--white);
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0.3em 0.6em;
    border-radius: 9999px;
}

/* Búsqueda */
.search-container {
    padding: 0.85rem;
    border-bottom: 1px solid var(--border-color);
}

.search-icon {
    background-color: var(--light);
    border: none;
    color: var(--primary);
}

.search-input {
    border: 1px solid var(--border-color);
    padding: 0.6rem;
    font-size: 0.9rem;
    border-radius: 0.25rem;
    background-color: var(--light);
}

.search-input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(96, 125, 139, 0.1);
}

/* Contenedor de listas */
.list-container {
    overflow-y: auto;
    height: 100%;
    padding: 0.25rem 0;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 0.85rem 1rem;
    transition: var(--transition);
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.list-group-item:hover {
    background-color: var(--primary-light);
}

.list-group-item.active {
    background-color: var(--primary-light);
    border-color: var(--border-color);
    color: var(--dark);
}

.list-group-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background-color: var(--primary);
}

/* Botones dentro de los ítems de lista */
.item-actions {
    margin-top: 0.75rem;
    display: flex;
    justify-content: flex-end;
}

.btn-item-action {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
    margin-left: 0.5rem;
}

/* Estados vacíos */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    text-align: center;
}

.empty-icon {
    color: #bdbdbd;
    margin-bottom: 1rem;
}

.empty-text {
    color: #757575;
    font-size: 0.95rem;
}

/* Loaders */
.loading-container {
    padding: 2rem;
    text-align: center;
}

.loading-container-centered {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

.spinner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.loading-text {
    margin-top: 1rem;
    color: var(--primary);
    font-size: 0.9rem;
}

/* Tarjetas informativas */
.info-card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-left: 4px solid var(--primary);
    transition: var(--transition);
    height: 100%;
}

.info-card.success {
    border-left-color: var(--success);
}

.info-card.info {
    border-left-color: var(--info);
}

.info-card-body {
    padding: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
}

.info-card-content {
    flex-grow: 1;
}

.info-card-title {
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.info-card-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark);
}

.info-card-icon {
    font-size: 1.5rem;
    color: #bdbdbd;
    padding-left: 1rem;
}

/* Tarjetas de detalles */
.details-card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
    height: 100%;
}

.details-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color);
    background-color: rgba(96, 125, 139, 0.05);
}

.details-card-title {
    color: var(--primary);
    font-weight: 500;
    margin: 0;
    font-size: 1rem;
}

.details-card-body {
    padding: 1rem 1.25rem;
}

.details-table {
    width: 100%;
}

.details-table td {
    padding: 0.5rem 0;
    vertical-align: top;
}

.details-table td:first-child {
    width: 40%;
    font-weight: 500;
    color: var(--secondary);
}

.details-table td:last-child {
    font-weight: 500;
}

/* Modal */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background-color: var(--primary);
    color: var(--white);
    border-bottom: none;
    border-top-left-radius: var(--border-radius);
    border-top-right-radius: var(--border-radius);
    padding: 1rem 1.5rem;
}

.modal-title {
    font-weight: 500;
    font-size: 1.2rem;
}

.modal-body {
    padding: 1.5rem;
    max-height: 75vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.btn-close {
    color: var(--white);
    opacity: 0.8;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

/* Pestañas */
.nav-tabs {
    border-bottom: 1px solid var(--border-color);
}

.nav-tabs .nav-link {
    color: var(--secondary);
    border: none;
    padding: 0.75rem 1rem;
    font-weight: 500;
    transition: var(--transition);
    margin-right: 0.5rem;
}

.nav-tabs .nav-link:hover {
    color: var(--primary);
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    color: var(--primary);
    background-color: transparent;
    border-bottom: 3px solid var(--primary);
}

/* Contenido programático */
.contenido-card {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.75rem;
    border-left: 4px solid var(--info);
    transition: var(--transition);
    overflow: hidden;
}

.contenido-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.contenido-card .card-header {
    background-color: rgba(79, 195, 247, 0.08);
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(79, 195, 247, 0.15);
}

.contenido-card .card-body {
    padding: 1.25rem;
}

/* Alertas modernas */
.alert-modern {
    display: flex;
    align-items: flex-start;
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    padding: 1.25rem;
}

.alert-icon {
    font-size: 1.5rem;
    margin-right: 1.25rem;
    color: var(--info);
}

.alert-content {
    flex-grow: 1;
}

.alert-heading {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

/* Botones */
.btn {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    transition: var(--transition);
}

.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
}

.btn-secondary {
    background-color: var(--secondary);
    border-color: var(--secondary);
}

.btn-secondary:hover {
    background-color: #546e7a;
    border-color: #546e7a;
}

.btn-success {
    background-color: var(--success);
    border-color: var(--success);
}

.btn-success:hover {
    background-color: #4caf50;
    border-color: #4caf50;
}

.btn-info {
    background-color: var(--info);
    border-color: var(--info);
    color: white;
}

.btn-info:hover {
    background-color: #29b6f6;
    border-color: #29b6f6;
    color: white;
}

/* Toast notifications */
.toast-container {
    z-index: 1060;
}

.toast {
    background-color: var(--white);
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    margin-bottom: 0.75rem;
}

.toast-header {
    background-color: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 0.75rem 1rem;
}

.toast-body {
    padding: 1rem;
    font-size: 0.9rem;
}

/* Scrollbars personalizados */
.list-container::-webkit-scrollbar {
    width: 8px;
}

.list-container::-webkit-scrollbar-track {
    background: transparent;
}

.list-container::-webkit-scrollbar-thumb {
    background-color: rgba(120, 144, 156, 0.3);
    border-radius: 4px;
}

.list-container::-webkit-scrollbar-thumb:hover {
    background-color: rgba(120, 144, 156, 0.5);
}

/* Adaptaciones responsive */
@media (max-width: 992px) {
    .panel-column {
        margin-bottom: 1rem;
    }

    .panel-card {
        height: 450px;
    }

    .modal-dialog {
        margin: 0.75rem;
    }
}

@media (max-width: 768px) {
    .panel-card {
        height: 400px;
    }

    .header-title {
        font-size: 1.5rem;
    }

    .header-subtitle {
        font-size: 0.9rem;
    }

    .row {
        --bs-gutter-x: 0.5rem;
    }
}
</style>

<!-- Scripts para manejo de datos -->
<script>
// Variables globales
const API_BASE_URL = 'http://127.0.0.1:8000/api';
let instituciones = [];
let programas = [];
let asignaturas = [];
let institucionSeleccionadaId = null;
let programaSeleccionadoId = null;
let asignaturaSeleccionadaId = null;
let institucionSeleccionada = null;
let programaSeleccionado = null;
let asignaturaSeleccionada = null;

// Función para realizar solicitudes con reintentos
async function fetchWithRetry(url, options = {}, maxRetries = 3) {
    let retries = 0;

    const fetchOptions = {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Cache-Control': 'no-cache'
        },
        timeout: 30000,
        ...options
    };

    while (retries <= maxRetries) {
        try {
            console.log(`Intento #${retries + 1} para ${url}`);
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000);

            const response = await fetch(url, {
                ...fetchOptions,
                signal: controller.signal
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
            }

            return await response.json();
        } catch (error) {
            console.warn(`Intento ${retries + 1}/${maxRetries + 1} fallido para ${url}:`, error);
            retries++;

            if (retries > maxRetries) {
                throw error;
            }

            // Esperar un tiempo incremental antes de reintentar
            const delay = 1000 * retries * (0.8 + Math.random() * 0.4);
            console.log(`Esperando ${delay}ms antes del siguiente intento...`);
            await new Promise(resolve => setTimeout(resolve, delay));
        }
    }
}

// Función para mostrar o ocultar elementos
function mostrarOcultarElemento(id, visible) {
    const elemento = document.getElementById(id);
    if (elemento) {
        elemento.style.display = visible ? 'block' : 'none';
    } else {
        console.warn(`Elemento con ID '${id}' no encontrado`);
    }
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
    // Crear contenedor si no existe
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    // Crear ID único para este toast
    const toastId = 'toast-' + Date.now();

    // Crear elemento toast
    const toastHTML = `
        <div id="${toastId}" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2 text-${tipo}"></i>
                <strong class="me-auto">Notificación</strong>
                <button type="button" class="btn-close" data-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${mensaje}
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHTML);

    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.remove();
        }
    }, 5000);
}

// Función para cargar instituciones
async function cargarInstituciones() {
    mostrarOcultarElemento('estado-carga-instituciones', true);

    const listaInstituciones = document.getElementById('listaInstituciones');
    if (listaInstituciones) {
        listaInstituciones.innerHTML = '';
    }

    try {
        const data = await fetchWithRetry(`${API_BASE_URL}/instituciones`);

        if (Array.isArray(data)) {
            instituciones = data;
        } else if (data && typeof data === 'object') {
            // Intentar encontrar el array en las propiedades del objeto
            const posiblesPropiedades = ['datos', 'data', 'instituciones', 'results'];
            for (const prop of posiblesPropiedades) {
                if (Array.isArray(data[prop])) {
                    instituciones = data[prop];
                    break;
                }
            }
        }

        // Actualizar contador
        const contadorInstituciones = document.getElementById('contador-instituciones');
        if (contadorInstituciones) {
            contadorInstituciones.textContent = instituciones.length;
        }

        renderizarInstituciones();
        mostrarNotificacion('Instituciones cargadas correctamente');
    } catch (error) {
        console.error('Error al cargar instituciones:', error);
        if (listaInstituciones) {
            listaInstituciones.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No se pudieron cargar las instituciones. Por favor, intente nuevamente más tarde.
                </div>
            `;
        }
        mostrarNotificacion('No se pudieron cargar las instituciones', 'danger');
    } finally {
        mostrarOcultarElemento('estado-carga-instituciones', false);
    }
}

// Función para renderizar instituciones
function renderizarInstituciones() {
    const listaInstituciones = document.getElementById('listaInstituciones');

    if (instituciones.length === 0) {
        listaInstituciones.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-university fa-3x empty-icon"></i>
                <p class="empty-text">No se encontraron instituciones registradas.</p>
            </div>
        `;
        return;
    }

    // Ordenar alfabéticamente por nombre
    instituciones.sort((a, b) => {
        return (a.nombre || '').localeCompare(b.nombre || '');
    });

    listaInstituciones.innerHTML = '';

    // Crear elementos para cada institución
    instituciones.forEach(institucion => {
        // Identificar el ID de la institución
        const institucionId = institucion.id_institucion || institucion.id;

        // Verificar si tenemos un ID válido
        if (!institucionId) {
            console.warn('Institución sin ID:', institucion);
            return; // Saltar esta institución
        }

        // Crear elemento para la institución
        const institucionElement = document.createElement('a');
        institucionElement.href = '#';
        institucionElement.className = 'list-group-item list-group-item-action';
        institucionElement.dataset.id = institucionId;

        // Determinar valores con fallbacks
        const nombre = institucion.nombre || 'Sin nombre';
        const tipo = institucion.tipo || 'Universidad';
        const municipio = institucion.municipio || 'No especificado';
        const codigoIes = institucion.codigo_ies || 'N/A';

        // Crear contenido del elemento
        institucionElement.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 fw-bold">${nombre}</h6>
                    <div class="small">
                        <span class="text-primary">${tipo}</span>
                        ${municipio ? `<span class="text-muted ms-2"><i class="fas fa-map-marker-alt"></i> ${municipio}</span>` : ''}
                    </div>
                </div>
                <span class="badge bg-secondary rounded-pill">${codigoIes}</span>
            </div>
            <div class="item-actions">
                <button type="button" class="btn btn-lateral btn-item-action btn-programas-institucion">
                    <i class="fas fa-graduation-cap me-1"></i> Programas
                </button>
            </div>
        `;

        // Configurar evento click para el botón de programas
        institucionElement.querySelector('.btn-programas-institucion').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Quitar selección anterior
            document.querySelectorAll('#listaInstituciones .active').forEach(item => {
                item.classList.remove('active');
            });

            // Marcar como seleccionada
            institucionElement.classList.add('active');

            // Guardar referencia a la institución seleccionada
            institucionSeleccionada = institucion;

            // Habilitar botón de agregar programa
            document.getElementById('btn-nuevo-programa').disabled = false;

            // Cargar programas de esta institución
            seleccionarInstitucion(institucionId);
        });

        // Configurar evento click para el elemento completo
        institucionElement.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
                e.preventDefault();

                // Quitar selección anterior
                document.querySelectorAll('#listaInstituciones .active').forEach(item => {
                    item.classList.remove('active');
                });

                // Marcar como seleccionada
                this.classList.add('active');

                // Guardar referencia a la institución seleccionada
                institucionSeleccionada = institucion;

                // Habilitar botón de agregar programa
                document.getElementById('btn-nuevo-programa').disabled = false;

                // Cargar programas de esta institución
                seleccionarInstitucion(institucionId);
            }
        });

        listaInstituciones.appendChild(institucionElement);
    });
}

// Función para seleccionar una institución y cargar sus programas
function seleccionarInstitucion(id) {
    institucionSeleccionadaId = id;
    programaSeleccionadoId = null;
    asignaturaSeleccionadaId = null;

    // Limpiar selecciones anteriores
    document.querySelectorAll('#listaProgramas .active').forEach(item => {
        item.classList.remove('active');
    });

    document.querySelectorAll('#listaAsignaturas .active').forEach(item => {
        item.classList.remove('active');
    });

    // Resetear asignaturas
    document.getElementById('listaAsignaturas').innerHTML = `
        <div class="empty-state">
            <i class="fas fa-book fa-3x empty-icon"></i>
            <p class="empty-text">Seleccione un programa para ver sus asignaturas</p>
        </div>
    `;

    document.getElementById('contador-asignaturas').textContent = '0';

    // Deshabilitar botón de nueva asignatura
    document.getElementById('btn-nueva-asignatura').disabled = true;

    // Cargar programas para esta institución
    cargarProgramas(id);
}

// Función para cargar programas por institución
async function cargarProgramas(institucionId) {
    mostrarOcultarElemento('estado-carga-programas', true);

    const listaProgramas = document.getElementById('listaProgramas');
    listaProgramas.innerHTML = '';

    try {
        // Intentar cargar programas desde el backend
        const data = await fetchWithRetry(`${API_BASE_URL}/programas`);

        // Procesar datos
        let allProgramas = [];
        if (Array.isArray(data)) {
            allProgramas = data;
        } else if (data && typeof data === 'object') {
            // Buscar en propiedades comunes
            const posiblesPropiedades = ['datos', 'data', 'programas', 'results'];
            for (const prop of posiblesPropiedades) {
                if (Array.isArray(data[prop])) {
                    allProgramas = data[prop];
                    break;
                }
            }
        }

        // Filtrar programas por institución
        const institucion = instituciones.find(i => i.id_institucion == institucionId || i.id == institucionId);
        programas = allProgramas.filter(p =>
            p.id_institucion == institucionId ||
            (institucion && p.institucion === institucion.nombre)
        );

        // Actualizar contador
        document.getElementById('contador-programas').textContent = programas.length;

        renderizarProgramas();
    } catch (error) {
        console.error('Error al cargar programas:', error);
        listaProgramas.innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No se pudieron cargar los programas. Por favor, intente nuevamente más tarde.
            </div>
        `;
        mostrarNotificacion('No se pudieron cargar los programas', 'danger');
    } finally {
        mostrarOcultarElemento('estado-carga-programas', false);
    }
}

// Función para renderizar programas
function renderizarProgramas() {
    const listaProgramas = document.getElementById('listaProgramas');

    if (programas.length === 0) {
        listaProgramas.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-graduation-cap fa-3x empty-icon"></i>
                <p class="empty-text">No se encontraron programas para esta institución.</p>
            </div>
        `;
        return;
    }

    // Ordenar alfabéticamente
    programas.sort((a, b) => {
        return (a.programa || a.nombre || '').localeCompare(b.programa || b.nombre || '');
    });

    listaProgramas.innerHTML = '';

    // Crear elementos para cada programa
    programas.forEach(programa => {
        // Identificar el ID del programa
        const programaId = programa.id_programa || programa.id;

        // Verificar si tenemos un ID válido
        if (!programaId) {
            console.warn('Programa sin ID:', programa);
            return; // Saltar este programa
        }

        // Crear elemento para el programa
        const programaElement = document.createElement('a');
        programaElement.href = '#';
        programaElement.className = 'list-group-item list-group-item-action';
        programaElement.dataset.id = programaId;

        // Determinar valores con fallbacks
        const nombre = programa.programa || programa.nombre || 'Sin nombre';
        const nivel = programa.nivel_formacion || programa.tipo_formacion || 'No especificado';
        const metodologia = programa.metodologia || 'No especificada';
        const snies = programa.codigo_snies || programa.snies || 'N/A';

        // Crear contenido del elemento
        programaElement.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 fw-bold">${nombre}</h6>
                    <div class="small">
                        <span class="text-primary">${nivel}</span>
                        <span class="text-muted ms-2"><i class="fas fa-chalkboard"></i> ${metodologia}</span>
                    </div>
                </div>
                <span class="badge bg-secondary rounded-pill">SNIES: ${snies}</span>
            </div>
            <div class="item-actions">
                <button type="button" class="btn btn-lateral btn-item-action btn-asignaturas-programa">
                    <i class="fas fa-book me-1"></i> Asignaturas
                </button>
            </div>
        `;

        // Configurar evento click para el botón de asignaturas
        programaElement.querySelector('.btn-asignaturas-programa').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Quitar selección anterior
            document.querySelectorAll('#listaProgramas .active').forEach(item => {
                item.classList.remove('active');
            });

            // Marcar como seleccionada
            programaElement.classList.add('active');

            // Guardar referencia al programa seleccionado
            programaSeleccionado = programa;

            // Habilitar botón de nueva asignatura
            document.getElementById('btn-nueva-asignatura').disabled = false;

            // Cargar asignaturas de este programa
            seleccionarPrograma(programaId);
        });

        // Configurar evento click para el elemento completo
        programaElement.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
                e.preventDefault();

                // Quitar selección anterior
                document.querySelectorAll('#listaProgramas .active').forEach(item => {
                    item.classList.remove('active');
                });

                // Marcar como seleccionada
                this.classList.add('active');

                // Guardar referencia al programa seleccionado
                programaSeleccionado = programa;

                // Habilitar botón de nueva asignatura
                document.getElementById('btn-nueva-asignatura').disabled = false;

                // Cargar asignaturas de este programa
                seleccionarPrograma(programaId);
            }
        });

        listaProgramas.appendChild(programaElement);
    });
}

// Función para seleccionar un programa y cargar sus asignaturas
function seleccionarPrograma(id) {
    programaSeleccionadoId = id;
    asignaturaSeleccionadaId = null;

    // Resetear selección de asignaturas
    document.querySelectorAll('#listaAsignaturas .active').forEach(item => {
        item.classList.remove('active');
    });

    // Cargar asignaturas para este programa
    cargarAsignaturas(id);
}

// Función para cargar asignaturas por programa
async function cargarAsignaturas(programaId) {
    mostrarOcultarElemento('estado-carga-asignaturas', true);

    const listaAsignaturas = document.getElementById('listaAsignaturas');
    listaAsignaturas.innerHTML = '';

    try {
        // Intentar cargar asignaturas desde el backend
        const data = await fetchWithRetry(`${API_BASE_URL}/asignaturas`);

        // Procesar datos
        let allAsignaturas = [];
        if (Array.isArray(data)) {
            allAsignaturas = data;
        } else if (data && typeof data === 'object') {
            // Buscar en propiedades comunes
            const posiblesPropiedades = ['datos', 'data', 'asignaturas', 'results'];
            for (const prop of posiblesPropiedades) {
                if (Array.isArray(data[prop])) {
                    allAsignaturas = data[prop];
                    break;
                }
            }
        }

        // Filtrar asignaturas por programa
        const programa = programas.find(p => p.id_programa == programaId || p.id == programaId);
        asignaturas = allAsignaturas.filter(a =>
            a.id_programa == programaId ||
            (programa && a.programa === programa.programa)
        );

        // Actualizar contador
        document.getElementById('contador-asignaturas').textContent = asignaturas.length;

        renderizarAsignaturas();
    } catch (error) {
        console.error('Error al cargar asignaturas:', error);
        listaAsignaturas.innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No se pudieron cargar las asignaturas. Por favor, intente nuevamente más tarde.
            </div>
        `;
        mostrarNotificacion('No se pudieron cargar las asignaturas', 'danger');
    } finally {
        mostrarOcultarElemento('estado-carga-asignaturas', false);
    }
}

// Función para renderizar asignaturas
function renderizarAsignaturas() {
    const listaAsignaturas = document.getElementById('listaAsignaturas');

    if (asignaturas.length === 0) {
        listaAsignaturas.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-book fa-3x empty-icon"></i>
                <p class="empty-text">No se encontraron asignaturas para este programa.</p>
            </div>
        `;
        return;
    }

    // Ordenar por semestre y nombre
    asignaturas.sort((a, b) => {
        const semestreA = parseInt(a.semestre || '0');
        const semestreB = parseInt(b.semestre || '0');

        if (semestreA !== semestreB) {
            return semestreA - semestreB;
        }

        return (a.nombre || '').localeCompare(b.nombre || '');
    });

    // Agrupar por semestre
    const asignaturasPorSemestre = {};
    asignaturas.forEach(asignatura => {
        const semestre = asignatura.semestre || 'No especificado';
        if (!asignaturasPorSemestre[semestre]) {
            asignaturasPorSemestre[semestre] = [];
        }
        asignaturasPorSemestre[semestre].push(asignatura);
    });

    listaAsignaturas.innerHTML = '';

    // Crear elementos para cada semestre y sus asignaturas
    Object.keys(asignaturasPorSemestre).sort((a, b) => {
        // Ordenar semestres numéricamente
        if (a === 'No especificado') return 1;
        if (b === 'No especificado') return -1;
        return parseInt(a) - parseInt(b);
    }).forEach(semestre => {
        // Crear encabezado de semestre
        const semestreHeader = document.createElement('div');
        semestreHeader.className = 'list-group-item bg-light text-primary fw-bold semester-header';
        semestreHeader.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-calendar-alt me-2"></i>
                    Semestre ${semestre}
                </div>
                <span class="badge bg-secondary rounded-pill">${asignaturasPorSemestre[semestre].length}</span>
            </div>
        `;
        listaAsignaturas.appendChild(semestreHeader);

        // Crear elementos para cada asignatura de este semestre
        asignaturasPorSemestre[semestre].forEach(asignatura => {
            // Identificar el ID de la asignatura
            const asignaturaId = asignatura.id_asignatura || asignatura.id;

            // Verificar si tenemos un ID válido
            if (!asignaturaId) {
                console.warn('Asignatura sin ID:', asignatura);
                return; // Saltar esta asignatura
            }

            // Crear elemento para la asignatura
            const asignaturaElement = document.createElement('a');
            asignaturaElement.href = '#';
            asignaturaElement.className = 'list-group-item list-group-item-action';
            asignaturaElement.dataset.id = asignaturaId;

            // Determinar valores con fallbacks
            const nombre = asignatura.nombre || 'Sin nombre';
            const codigo = asignatura.codigo_asignatura || asignatura.codigo || 'No especificado';
            const tipo = asignatura.tipo || 'Obligatoria';
            const creditos = asignatura.creditos || '0';

            // Crear contenido del elemento
            asignaturaElement.innerHTML = `
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1 fw-bold">${nombre}</h6>
                        <div class="small">
                            <span class="text-primary"><i class="fas fa-hashtag"></i> ${codigo}</span>
                            <span class="text-muted ms-2"><i class="fas fa-bookmark"></i> ${tipo}</span>
                        </div>
                    </div>
                    <span class="badge bg-info rounded-pill">${creditos} créditos</span>
                </div>
                <div class="item-actions">
                    <button type="button" class="btn btn-contenido-suave btn-item-action btn-contenido-asignatura">
                        <i class="fas fa-book-open me-1"></i> Contenido
                    </button>
                </div>
            `;

            // Configurar evento click para el botón de ver contenido
            asignaturaElement.querySelector('.btn-contenido-asignatura').addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Quitar selección anterior
                document.querySelectorAll('#listaAsignaturas .active').forEach(item => {
                    item.classList.remove('active');
                });

                // Marcar como seleccionada
                asignaturaElement.classList.add('active');

                // Guardar referencia a la asignatura seleccionada
                asignaturaSeleccionada = asignatura;
                asignaturaSeleccionadaId = asignaturaId;

                // Cargar directamente el contenido programático sin abrir modal
                cargarContenidoProgramatico(asignaturaId, true);
            });

            // Configurar evento click para el elemento completo
            asignaturaElement.addEventListener('click', function(e) {
                if (!e.target.closest('.btn')) {
                    e.preventDefault();

                    // Quitar selección anterior
                    document.querySelectorAll('#listaAsignaturas .active').forEach(item => {
                        item.classList.remove('active');
                    });

                    // Marcar como seleccionada
                    this.classList.add('active');

                    // Guardar referencia a la asignatura seleccionada
                    asignaturaSeleccionada = asignatura;
                    asignaturaSeleccionadaId = asignaturaId;

                    // Abrir modal con información detallada
                    mostrarDetallesAsignatura(asignaturaId, asignatura);
                }
            });

            listaAsignaturas.appendChild(asignaturaElement);
        });
    });
}

// Función para mostrar los detalles de la asignatura en el modal
function mostrarDetallesAsignatura(id, asignatura) {
    // Actualizar título del modal
    document.getElementById('modalTituloAsignatura').textContent = asignatura.nombre || 'Información de Asignatura';

    // Cargar información general
    document.getElementById('codigoAsignatura').textContent = asignatura.codigo_asignatura || asignatura.codigo || 'No especificado';
    document.getElementById('creditosAsignatura').textContent = asignatura.creditos || 'No especificado';
    document.getElementById('semestreAsignatura').textContent = asignatura.semestre || 'No especificado';
    document.getElementById('tipoAsignatura').textContent = asignatura.tipo || 'No especificado';
    document.getElementById('modalidadAsignatura').textContent = asignatura.modalidad || 'Presencial';
    document.getElementById('horasSenaAsignatura').textContent = asignatura.horas_sena || 'No especificado';
    document.getElementById('metodologiaAsignatura').textContent = asignatura.metodologia || 'No especificado';

    // Información adicional
    document.getElementById('institucionAsignatura').textContent = institucionSeleccionada ? (institucionSeleccionada.nombre || 'No especificado') : 'No especificado';
    document.getElementById('programaAsignatura').textContent = programaSeleccionado ? (programaSeleccionado.programa || programaSeleccionado.nombre || 'No especificado') : 'No especificado';

    // Preparar la pestaña de contenido programático
    document.getElementById('contenidosProgramaticos').innerHTML = '';
    mostrarOcultarElemento('cargandoContenido', true);
    mostrarOcultarElemento('mensajeSinContenido', false);

    // Mostrar el modal usando jQuery
    $('#modalAsignatura').modal('show');

    // Ir a la pestaña de información general primero usando jQuery
    $('#asignaturaTabs a[href="#informacion"]').tab('show');
}

// Función para cargar el contenido programático de una asignatura
async function cargarContenidoProgramatico(asignaturaId, cargaDirecta = false) {
    console.log(`Cargando contenido programático para asignatura ID: ${asignaturaId}`);

    // Si es carga directa, mostramos en un modal aparte o en una alerta
    if (cargaDirecta) {
        mostrarNotificacion('Cargando contenido programático...', 'info');
    } else {
        // Mostrar estado de carga en el modal
        mostrarOcultarElemento('cargandoContenido', true);
        mostrarOcultarElemento('mensajeSinContenido', false);
        document.getElementById('contenidosProgramaticos').innerHTML = '';
    }

    try {
        // Realizar solicitud fetch con la URL correcta
        const url = `${API_BASE_URL}/contenidos-programaticos/asignatura/${asignaturaId}`;
        console.log(`Solicitando datos a: ${url}`);

        const data = await fetchWithRetry(url);
        console.log("Respuesta de API:", data);

        // Extraer contenidos
        let contenidos = [];
        if (data && data.datos && Array.isArray(data.datos)) {
            contenidos = data.datos;
        } else if (Array.isArray(data)) {
            contenidos = data;
        }

        console.log(`Contenidos encontrados: ${contenidos.length}`);

        // Si es carga directa, mostramos en un modal aparte
        if (cargaDirecta) {
            mostrarModalContenidoDirecto(contenidos, asignaturaSeleccionada);
            return;
        }

        // Ocultar cargador
        mostrarOcultarElemento('cargandoContenido', false);

        // Si no hay contenidos
        if (!contenidos || contenidos.length === 0) {
            mostrarOcultarElemento('mensajeSinContenido', true);
            return;
        }

        // Crear HTML para contenidos
        let html = '<div class="row g-3">';

        // Crear elementos para cada contenido
        contenidos.forEach((contenido, index) => {
            const tema = contenido.tema || contenido.nombre || contenido.titulo || 'Sin título';
            const resultados = contenido.resultados_aprendizaje || contenido.resultados || 'No especificado';
            const descripcion = contenido.descripcion || contenido.detalle || contenido.contenidos || 'No especificado';

            html += `
                <div class="col-md-12">
                    <div class="contenido-card">
                        <div class="card-header">
                            <h5 class="m-0 fw-bold">
                             <i class="fas fa-book-open me-2"></i>${tema}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-check-circle me-2"></i>Resultados de Aprendizaje
                                </h6>
                                <div class="ps-3 border-start border-primary py-2">
                                    ${resultados}
                                </div>
                            </div>

                            <div>
                                <h6 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-info-circle me-2"></i>Descripción del Contenido
                                </h6>
                                <div class="ps-3 border-start border-info py-2">
                                    ${descripcion}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        document.getElementById('contenidosProgramaticos').innerHTML = html;
        console.log("Contenidos programáticos renderizados correctamente");

    } catch (error) {
        console.error("Error al cargar contenidos:", error);

        // Si es carga directa, mostrar notificación de error
        if (cargaDirecta) {
            mostrarNotificacion(`Error al cargar el contenido programático: ${error.message}`, 'danger');
            return;
        }

        // Ocultar cargador
        mostrarOcultarElemento('cargandoContenido', false);

        // Mostrar mensaje de error
        document.getElementById('contenidosProgramaticos').innerHTML = `
            <div class="alert alert-danger alert-modern">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h5 class="alert-heading">Error al cargar contenidos</h5>
                    <p class="mb-0">${error.message}</p>
                    <button class="btn btn-secondary btn-sm mt-3 btn-reintentar" data-id="${asignaturaId}">
                        <i class="fas fa-sync-alt me-2"></i> Reintentar
                    </button>
                </div>
            </div>
        `;
    }
}

// Función para mostrar contenido programático directo en un modal
function mostrarModalContenidoDirecto(contenidos, asignatura) {
    // Actualizar título del modal
    const modalTitle = document.getElementById('tituloContenidoDirecto');
    if (modalTitle) {
        modalTitle.textContent = `Contenido Programático: ${asignatura.nombre || 'Asignatura'}`;
    }

    // Obtener el contenedor del cuerpo del modal
    const modalBody = document.getElementById('contenidoDirectoBody');

    if (!modalBody) {
        console.error('No se encontró el contenedor del cuerpo del modal');
        return;
    }

    // Limpiar contenido anterior
    modalBody.innerHTML = '';

    // Si no hay contenidos
    if (!contenidos || contenidos.length === 0) {
        modalBody.innerHTML = `
            <div class="alert alert-info alert-modern">
                <div class="alert-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="alert-content">
                    <h5 class="alert-heading">Sin contenidos programáticos</h5>
                    <p class="mb-0">Esta asignatura no tiene contenidos programáticos registrados.</p>
                </div>
            </div>
        `;
    } else {
        // Añadir contenidos al modal
        contenidos.forEach((contenido, index) => {
            const tema = contenido.tema || contenido.nombre || contenido.titulo || 'Sin título';
            const resultados = contenido.resultados_aprendizaje || contenido.resultados || 'No especificado';
            const descripcion = contenido.descripcion || contenido.detalle || contenido.contenidos || 'No especificado';

            const contenidoElement = document.createElement('div');
            contenidoElement.className = 'contenido-card mb-4';
            contenidoElement.innerHTML = `
                <div class="card-header">
                    <h5 class="m-0 fw-bold">
                        <i class="fas fa-book-open me-2"></i>${tema}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-check-circle me-2"></i>Resultados de Aprendizaje
                        </h6>
                        <div class="ps-3 border-start border-primary py-3">
                            ${resultados}
                        </div>
                    </div>

                    <div>
                        <h6 class="text-primary fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i>Descripción del Contenido
                        </h6>
                        <div class="ps-3 border-start border-info py-3">
                            ${descripcion}
                        </div>
                    </div>
                </div>
            `;

            modalBody.appendChild(contenidoElement);
        });
    }

    // Mostrar el modal
    $('#modalContenidoDirecto').modal('show');
}

// Función para filtrar instituciones según texto de búsqueda
function filtrarInstituciones(texto) {
    const termino = texto.toLowerCase().trim();
    let contadorVisibles = 0;

    document.querySelectorAll('#listaInstituciones a').forEach(item => {
        const contenido = item.textContent.toLowerCase();
        if (contenido.includes(termino)) {
            item.style.display = 'block';
            contadorVisibles++;
        } else {
            item.style.display = 'none';
        }
    });

    // Actualizar contador con resultados filtrados
    document.getElementById('contador-instituciones').textContent = contadorVisibles;
}

// Función para filtrar programas según texto de búsqueda
function filtrarProgramas(texto) {
    const termino = texto.toLowerCase().trim();
    let contadorVisibles = 0;

    document.querySelectorAll('#listaProgramas a').forEach(item => {
        const contenido = item.textContent.toLowerCase();
        if (contenido.includes(termino)) {
            item.style.display = 'block';
            contadorVisibles++;
        } else {
            item.style.display = 'none';
        }
    });

    // Actualizar contador con resultados filtrados
    document.getElementById('contador-programas').textContent = contadorVisibles;
}

// Función para filtrar asignaturas según texto de búsqueda
function filtrarAsignaturas(texto) {
    const termino = texto.toLowerCase().trim();
    let contadorVisibles = 0;
    const semestresVisibles = new Set();

    // Primero ocultamos todos los semestres
    document.querySelectorAll('#listaAsignaturas .semester-header').forEach(header => {
        header.style.display = 'none';
    });

    // Filtramos asignaturas
    document.querySelectorAll('#listaAsignaturas a:not(.semester-header)').forEach(item => {
        const contenido = item.textContent.toLowerCase();
        if (contenido.includes(termino)) {
            item.style.display = 'block';
            contadorVisibles++;

            // Encontrar el header del semestre anterior
            let prevEl = item.previousElementSibling;
            while (prevEl && !prevEl.classList.contains('semester-header')) {
                prevEl = prevEl.previousElementSibling;
            }

            if (prevEl) {
                prevEl.style.display = 'block';
            }
        } else {
            item.style.display = 'none';
        }
    });

    // Actualizar contador con resultados filtrados
    document.getElementById('contador-asignaturas').textContent = contadorVisibles;
}

// Función para gestionar los botones de creación
function configurarBotonesCreacion() {
    // Botón de nueva institución
    document.getElementById('btn-nueva-institucion').addEventListener('click', function() {
        mostrarNotificacion('Funcionalidad de crear nueva institución en desarrollo', 'info');
    });

    // Botón de nuevo programa
    document.getElementById('btn-nuevo-programa').addEventListener('click', function() {
        if (!institucionSeleccionadaId) {
            mostrarNotificacion('Seleccione una institución primero', 'warning');
            return;
        }
        mostrarNotificacion('Funcionalidad de crear nuevo programa en desarrollo', 'info');
    });

    // Botón de nueva asignatura
    document.getElementById('btn-nueva-asignatura').addEventListener('click', function() {
        if (!programaSeleccionadoId) {
            mostrarNotificacion('Seleccione un programa primero', 'warning');
            return;
        }
        mostrarNotificacion('Funcionalidad de crear nueva asignatura en desarrollo', 'info');
    });
}

// Inicialización al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página cargada, iniciando explorador de contenidos programáticos...');

    // Configurar botones de creación
    configurarBotonesCreacion();

    // Cargar instituciones inmediatamente
    cargarInstituciones();

    // Configurar buscadores con debounce
    const buscadorInstituciones = document.getElementById('buscadorInstituciones');
    if (buscadorInstituciones) {
        let timeout = null;
        buscadorInstituciones.addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                filtrarInstituciones(this.value);
            }, 300);
        });
    }

    const buscadorProgramas = document.getElementById('buscadorProgramas');
    if (buscadorProgramas) {
        let timeout = null;
        buscadorProgramas.addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                filtrarProgramas(this.value);
            }, 300);
        });
    }

    const buscadorAsignaturas = document.getElementById('buscadorAsignaturas');
    if (buscadorAsignaturas) {
        let timeout = null;
        buscadorAsignaturas.addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                filtrarAsignaturas(this.value);
            }, 300);
        });
    }

    // Configurar eventos para los botones de reintentar
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-reintentar') || e.target.closest('.btn-reintentar')) {
            if (asignaturaSeleccionadaId) {
                cargarContenidoProgramatico(asignaturaSeleccionadaId);
            }
        }
    });

    // Monitorear estado de conexión
    window.addEventListener('online', () => {
        console.log('Conexión restablecida');
        mostrarNotificacion('Conexión a internet restablecida', 'success');
    });

    window.addEventListener('offline', () => {
        console.log('Conexión perdida');
        mostrarNotificacion('Se ha perdido la conexión a internet', 'warning');
    });

    // Función global para diagnóstico
    window.fijarProblema = function() {
        if (asignaturaSeleccionadaId) {
            cargarContenidoProgramatico(asignaturaSeleccionadaId);
            return "Intentando recargar contenidos programáticos...";
        } else if (programaSeleccionadoId) {
            cargarAsignaturas(programaSeleccionadoId);
            return "Intentando recargar asignaturas...";
        } else if (institucionSeleccionadaId) {
            cargarProgramas(institucionSeleccionadaId);
            return "Intentando recargar programas...";
        } else {
            cargarInstituciones();
            return "Recargando todas las instituciones...";
        }
    };
});
</script>
@endsection

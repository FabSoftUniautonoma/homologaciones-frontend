@extends('admin.layouts.appadmin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Mejorado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg bg-gradient-primary border-0 animate__animated animate__fadeIn">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-white text-primary rounded-circle shadow me-3">
                            <i class="fas fa-graduation-cap fa-2x p-2"></i>
                        </div>
                        <div>
                            <h2 class="text-white mb-0 fw-bold">Gestión Académica</h2>
                            <p class="text-white-50 mb-0">Administración de instituciones, programas y asignaturas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal con Estructura Mejorada -->
    <div class="row g-4">
        <!-- Panel de Instituciones -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInLeft">
                <div class="card-header bg-gradient-primary text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-university me-2"></i>
                            <h5 class="mb-0">Instituciones</h5>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill px-3" id="contador-instituciones">0</span>
                    </div>
                </div>
                <div class="input-group p-2 bg-light">
                    <span class="input-group-text border-0 bg-transparent">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-transparent" id="buscar-institucion" placeholder="Buscar institución...">
                </div>
                <div class="list-group list-group-flush custom-scrollbar" id="instituciones-lista" style="max-height: 450px; overflow-y: auto;">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando instituciones...</span>
                        </div>
                        <p class="mt-2 text-muted">Cargando instituciones...</p>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <button class="btn btn-primary w-100 rounded-pill" onclick="mostrarModalNuevaInstitucion()">
                        <i class="fas fa-plus-circle me-2"></i> Nueva Institución
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel de Programas -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeIn" style="animation-delay: 0.2s">
                <div class="card-header bg-gradient-success text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book me-2"></i>
                            <h5 class="mb-0">Programas</h5>
                        </div>
                        <span class="badge bg-white text-success rounded-pill px-3" id="contador-programas">0</span>
                    </div>
                    <p id="institucion-seleccionada" class="text-white-50 mb-0 small mt-1 text-truncate"></p>
                </div>
                <div class="input-group p-2 bg-light" id="buscar-programa-container" style="display: none;">
                    <span class="input-group-text border-0 bg-transparent">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-transparent" id="buscar-programa" placeholder="Buscar programa...">
                </div>
                <div class="list-group list-group-flush custom-scrollbar" id="programas-lista" style="max-height: 450px; overflow-y: auto;">
                    <div class="text-center p-4">
                        <p class="text-muted">Seleccione una institución para ver sus programas</p>
                        <i class="fas fa-hand-point-left fa-2x text-muted"></i>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <button class="btn btn-success w-100 rounded-pill" id="btn-nuevo-programa" onclick="mostrarModalNuevoPrograma()" disabled>
                        <i class="fas fa-plus-circle me-2"></i> Nuevo Programa
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel de Asignaturas -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 animate__animated animate__fadeInRight" style="animation-delay: 0.4s">
                <div class="card-header bg-gradient-info text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <h5 class="mb-0">Asignaturas</h5>
                        </div>
                        <span class="badge bg-white text-info rounded-pill px-3" id="contador-asignaturas">0</span>
                    </div>
                    <p id="programa-seleccionado" class="text-white-50 mb-0 small mt-1 text-truncate"></p>
                </div>
                <div class="input-group p-2 bg-light" id="buscar-asignatura-container" style="display: none;">
                    <span class="input-group-text border-0 bg-transparent">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-transparent" id="buscar-asignatura" placeholder="Buscar asignatura...">
                </div>
                <div class="list-group list-group-flush custom-scrollbar" id="asignaturas-lista" style="max-height: 450px; overflow-y: auto;">
                    <div class="text-center p-4">
                        <p class="text-muted">Seleccione un programa para ver sus asignaturas</p>
                        <i class="fas fa-hand-point-left fa-2x text-muted"></i>
                    </div>
                </div>
                <div class="card-footer bg-light p-3">
                    <button class="btn btn-info text-white w-100 rounded-pill" id="btn-nueva-asignatura" onclick="mostrarModalNuevaAsignatura()" disabled>
                        <i class="fas fa-plus-circle me-2"></i> Nueva Asignatura
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles de Asignatura Mejorados -->
    <div class="row mt-4" id="detalles-asignatura-container" style="display: none;">
        <div class="col-12">
            <div class="card shadow-lg border-0 animate__animated animate__fadeInUp">
                <div class="card-header bg-gradient-warning p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-white text-warning rounded-circle shadow me-3">
                                <i class="fas fa-book-open fa-lg p-2"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-white">Detalles de la Asignatura</h5>
                                <p id="asignatura-seleccionada" class="mb-0 text-white-50 small"></p>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-dark me-2" id="btn-editar-asignatura">
                                <i class="fas fa-edit me-1"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-light" onclick="cerrarDetallesAsignatura()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pestañas mejoradas -->
                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-fill p-3" id="asignaturaTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center justify-content-center" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i> Información General
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center justify-content-center" id="contenido-tab" data-bs-toggle="tab" data-bs-target="#contenido" type="button" role="tab">
                                <i class="fas fa-list-alt me-2"></i> Contenido Programático
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center justify-content-center" id="competencias-tab" data-bs-toggle="tab" data-bs-target="#competencias" type="button" role="tab">
                                <i class="fas fa-award me-2"></i> Competencias
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="asignaturaTabsContent">
                        <!-- Pestaña de Información General -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <!-- Contenido dinámico -->
                        </div>

                        <!-- Pestaña de Contenido Programático Mejorada -->
                        <div class="tab-pane fade" id="contenido" role="tabpanel" aria-labelledby="contenido-tab">
                            <div id="contenido-programatico-container">
                                <!-- Loader animado mejorado -->
                                <div id="contenido-loader" class="text-center my-5">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-3">Cargando contenido programático...</p>
                                </div>

                                <!-- Contenido dinámico -->
                                <div id="contenido-programatico-lista" class="row">
                                    <!-- Aquí se cargarán las tarjetas de contenidos -->
                                </div>

                                <!-- Botón para añadir nuevo contenido -->
                                <div class="text-center mt-4">
                                    <button class="btn btn-primary rounded-pill" onclick="mostrarModalNuevoContenido()">
                                        <i class="fas fa-plus-circle me-2"></i> Añadir Nuevo Contenido
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Competencias -->
                        <div class="tab-pane fade" id="competencias" role="tabpanel" aria-labelledby="competencias-tab">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Esta sección mostrará las competencias asociadas a la asignatura.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Contenido Programático Mejorado -->
<div class="modal fade" id="contenidoProgramaticoModal" tabindex="-1" aria-labelledby="contenidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="contenidoModalLabel">
                    <i class="fas fa-book me-2"></i>
                    Detalles del Contenido Programático
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="contenido-programatico-detalle">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-editar-contenido">
                    <i class="fas fa-edit me-1"></i> Editar Contenido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Formulario Modal para Nuevo/Editar Contenido -->
<div class="modal fade" id="formContenidoModal" tabindex="-1" aria-labelledby="formContenidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title" id="formContenidoModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nuevo Contenido Programático
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="contenidoForm">
                    <input type="hidden" id="contenido_id" name="contenido_id">
                    <input type="hidden" id="asignatura_id" name="asignatura_id">

                    <div class="mb-3">
                        <label for="tema" class="form-label">Tema</label>
                        <input type="text" class="form-control" id="tema" name="tema" required>
                    </div>

                    <div class="mb-3">
                        <label for="resultados_aprendizaje" class="form-label">Resultados de Aprendizaje</label>
                        <textarea class="form-control" id="resultados_aprendizaje" name="resultados_aprendizaje" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="guardarContenido">
                    <i class="fas fa-save me-1"></i> Guardar Contenido
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notificación Toast Mejorada -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div id="notificacionToast" class="toast shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <div class="rounded me-2 bg-primary" style="width: 20px; height: 20px;"></div>
            <strong class="me-auto" id="toast-titulo">Notificación</strong>
            <small>Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-mensaje"></div>
    </div>
</div>

<!-- Estilos CSS mejorados -->
<style>
    /* Estilos para la barra de desplazamiento personalizada */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Estilos para tarjetas con efecto hover */
    .list-group-item-action {
        transition: all 0.2s ease;
    }

    .list-group-item-action:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        z-index: 1;
    }

    /* Estilos para los gradientes */
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #6610f2);
    }

    .bg-gradient-success {
        background: linear-gradient(45deg, #1cc88a, #20c997);
    }

    .bg-gradient-info {
        background: linear-gradient(45deg, #36b9cc, #17a2b8);
    }

    .bg-gradient-warning {
        background: linear-gradient(45deg, #f6c23e, #fd7e14);
    }

    /* Estilos para las tarjetas de contenido programático */
    .contenido-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
    }

    .contenido-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    /* Animaciones para pestañas */
    .tab-pane {
        animation: fadeEffect 0.5s;
    }

    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    /* Iconos en las pestañas */
    .nav-pills .nav-link {
        border-radius: 50rem;
        margin: 0 5px;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }

    .nav-pills .nav-link.active {
        background-color: #4e73df;
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.25);
        transform: translateY(-2px);
    }
</style>

<!-- Scripts JS mejorados -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<script>
// Variables para almacenar datos
let instituciones = [];
let programas = [];
let asignaturas = [];
let contenidosProgramaticos = [];
let institucionSeleccionadaId = null;
let programaSeleccionadoId = null;
let asignaturaSeleccionadaId = null;
let contenidoSeleccionadoId = null;

// Configuración base para peticiones
const apiConfig = {
    baseUrl: 'https://homologacionesback.educarenemociones.com/api',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
};

// Inicialización cuando el documento está listo
document.addEventListener('DOMContentLoaded', function() {
    // Añadir animaciones a los contenedores principales
    document.querySelectorAll('.animate__animated').forEach(element => {
        element.classList.add('animate__fadeIn');
    });

    // Cargar todas las instituciones al inicio
    cargarInstituciones();

    // Configurar buscadores
    configurarBuscadores();

    // Configurar evento para cargar contenido programático al cambiar a la pestaña
    document.getElementById('contenido-tab').addEventListener('click', function() {
        if (asignaturaSeleccionadaId) {
            cargarContenidoProgramatico(asignaturaSeleccionadaId);
        }
    });

    // Configurar eventos para modales de contenido
    document.getElementById('guardarContenido')?.addEventListener('click', guardarContenidoProgramatico);
    document.getElementById('btn-editar-contenido')?.addEventListener('click', editarContenidoSeleccionado);
});

// Configurar buscadores
function configurarBuscadores() {
    // Buscador de instituciones
    document.getElementById('buscar-institucion').addEventListener('keyup', function() {
        const busqueda = this.value.toLowerCase();
        filtrarLista('instituciones-lista', busqueda);
    });

    // Buscador de programas
    document.getElementById('buscar-programa').addEventListener('keyup', function() {
        const busqueda = this.value.toLowerCase();
        filtrarLista('programas-lista', busqueda);
    });

    // Buscador de asignaturas
    document.getElementById('buscar-asignatura').addEventListener('keyup', function() {
        const busqueda = this.value.toLowerCase();
        filtrarLista('asignaturas-lista', busqueda);
    });
}

// Función para filtrar listas
function filtrarLista(idLista, texto) {
    const items = document.querySelectorAll(`#${idLista} .list-group-item-action`);
    let coincidencias = 0;

    items.forEach(item => {
        const contenido = item.textContent.toLowerCase();
        if (contenido.includes(texto)) {
            item.style.display = '';
            coincidencias++;
        } else {
            item.style.display = 'none';
        }
    });

    // Mostrar mensaje si no hay coincidencias
    const listaElement = document.getElementById(idLista);
    let mensajeNoResultados = listaElement.querySelector('.no-resultados');

    if (coincidencias === 0 && texto !== '') {
        if (!mensajeNoResultados) {
            mensajeNoResultados = document.createElement('div');
            mensajeNoResultados.className = 'no-resultados text-center p-3';
            mensajeNoResultados.innerHTML = `
                <div class="alert alert-light border">
                    <i class="fas fa-search-minus me-2"></i>
                    No se encontraron resultados para "<strong>${texto}</strong>"
                </div>
            `;
            listaElement.appendChild(mensajeNoResultados);
        }
    } else if (mensajeNoResultados) {
        mensajeNoResultados.remove();
    }
}

// Función para mostrar notificaciones mejorada
function mostrarNotificacion(titulo, mensaje, tipo = 'info') {
    const toast = document.getElementById('notificacionToast');
    const toastTitulo = document.getElementById('toast-titulo');
    const toastMensaje = document.getElementById('toast-mensaje');
    const indicador = toast.querySelector('.rounded');

    // Configurar el toast
    toastTitulo.textContent = titulo;
    toastMensaje.textContent = mensaje;

    // Aplicar clase según el tipo
    toast.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'text-white');
    indicador.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-primary');

    switch(tipo) {
        case 'success':
            indicador.classList.add('bg-success');
            break;
        case 'error':
            indicador.classList.add('bg-danger');
            break;
        case 'warning':
            indicador.classList.add('bg-warning');
            break;
        default:
            indicador.classList.add('bg-primary');
    }

    // Mostrar el toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

// Función para cargar instituciones desde el backend
function cargarInstituciones() {
    const institucionesLista = document.getElementById('instituciones-lista');
    institucionesLista.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando instituciones...</span>
            </div>
            <p class="mt-2 text-muted">Cargando instituciones...</p>
        </div>
    `;

    // Petición al backend
    fetch(`${apiConfig.baseUrl}/instituciones`, {
        method: 'GET',
        headers: apiConfig.headers
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar instituciones');
        }
        return response.json();
    })
    .then(data => {
        instituciones = data;
        document.getElementById('contador-instituciones').textContent = instituciones.length;
        renderizarInstituciones();
    })
    .catch(error => {
        console.error('Error:', error);
        institucionesLista.innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error al cargar instituciones: ${error.message}
            </div>
        `;
        mostrarNotificacion('Error', `No se pudieron cargar las instituciones: ${error.message}`, 'error');
    });
}

// Función para renderizar instituciones en el panel
function renderizarInstituciones() {
    const listaInstituciones = document.getElementById('instituciones-lista');

    if (instituciones.length === 0) {
        listaInstituciones.innerHTML = `
            <div class="text-center p-4">
                <div class="empty-state">
                    <i class="fas fa-university fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay instituciones disponibles</p>
                    <button class="btn btn-primary btn-sm mt-2" onclick="mostrarModalNuevaInstitucion()">
                        <i class="fas fa-plus-circle me-1"></i> Agregar institución
                    </button>
                </div>
            </div>
        `;
        return;
    }

    let html = '';
    instituciones.forEach(institucion => {
        html += `
            <a href="#" class="list-group-item list-group-item-action border-0 mb-1 ${institucionSeleccionadaId === institucion.id_institucion ? 'active' : ''}"
               onclick="seleccionarInstitucion(${institucion.id_institucion})">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${institucion.nombre}</h6>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <span>${institucion.municipio || 'Sin ubicación'}</span>
                            <i class="fas fa-building ms-2 me-1"></i>
                            <span>${institucion.tipo || 'Universidad'}</span>
                        </div>
                    </div>
                    <span class="badge bg-primary rounded-pill px-2">${institucion.codigo_ies}</span>
                </div>
            </a>
        `;
    });

    listaInstituciones.innerHTML = html;
}

// Función para seleccionar una institución y cargar sus programas
function seleccionarInstitucion(id) {
    // Si ya está seleccionada, no hacer nada
    if (institucionSeleccionadaId === id) return;

    institucionSeleccionadaId = id;
    programaSeleccionadoId = null;
    asignaturaSeleccionadaId = null;

    // Actualizar UI para mostrar la institución seleccionada
    const institucionSeleccionada = instituciones.find(i => i.id_institucion === id);
    document.getElementById('institucion-seleccionada').textContent = `${institucionSeleccionada.nombre} (${institucionSeleccionada.codigo_ies})`;

    // Resaltar el elemento seleccionado
    document.querySelectorAll('#instituciones-lista .list-group-item').forEach(el => {
        el.classList.remove('active');
    });
    document.querySelector(`#instituciones-lista .list-group-item[onclick="seleccionarInstitucion(${id})"]`).classList.add('active');

   // Mostrar el buscador de programas con animación
    const buscarProgramaContainer = document.getElementById('buscar-programa-container');
    buscarProgramaContainer.style.display = 'flex';
    buscarProgramaContainer.classList.add('animate__animated', 'animate__fadeIn');

    // Habilitar el botón para crear nuevo programa
    document.getElementById('btn-nuevo-programa').disabled = false;

    // Limpiar y mostrar loading en la lista de programas
    document.getElementById('programas-lista').innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Cargando programas...</span>
            </div>
            <p class="mt-2 text-muted">Cargando programas...</p>
        </div>
    `;
    document.getElementById('contador-programas').textContent = '0';

    // Limpiar la lista de asignaturas
    document.getElementById('asignaturas-lista').innerHTML = `
        <div class="text-center p-4">
            <div class="empty-state">
                <i class="fas fa-hand-point-left fa-3x text-muted mb-3"></i>
                <p class="text-muted">Seleccione un programa para ver sus asignaturas</p>
            </div>
        </div>
    `;
    document.getElementById('contador-asignaturas').textContent = '0';

    // Ocultar detalles de asignatura si están visibles
    document.getElementById('detalles-asignatura-container').style.display = 'none';

    // Cargar programas de esta institución
    cargarProgramasPorInstitucion(id);
}

// Función para cargar programas
function cargarProgramasPorInstitucion(institucionId) {
    fetch(`${apiConfig.baseUrl}/programas`, {
        method: 'GET',
        headers: apiConfig.headers
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar programas');
        }
        return response.json();
    })
    .then(data => {
        // Filtrar programas por institución
        programas = data.filter(p => p.institucion === instituciones.find(i => i.id_institucion === institucionId).nombre);
        document.getElementById('contador-programas').textContent = programas.length;
        renderizarProgramas();
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('programas-lista').innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error al cargar programas: ${error.message}
            </div>
        `;
        mostrarNotificacion('Error', `No se pudieron cargar los programas: ${error.message}`, 'error');
    });
}

// Función para renderizar programas en el panel
function renderizarProgramas() {
    const listaProgramas = document.getElementById('programas-lista');

    if (programas.length === 0) {
        listaProgramas.innerHTML = `
            <div class="text-center p-4">
                <div class="empty-state">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay programas disponibles para esta institución</p>
                    <button class="btn btn-success btn-sm mt-2" onclick="mostrarModalNuevoPrograma()">
                        <i class="fas fa-plus-circle me-1"></i> Agregar programa
                    </button>
                </div>
            </div>
        `;
        return;
    }

    let html = '';
    programas.forEach(programa => {
        html += `
            <a href="#" class="list-group-item list-group-item-action border-0 mb-1 ${programaSeleccionadoId === programa.id_programa ? 'active' : ''}"
               onclick="seleccionarPrograma(${programa.id_programa})">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${programa.programa}</h6>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-graduation-cap me-1"></i>
                            <span>${programa.tipo_formacion || 'Universitaria'}</span>
                            <i class="fas fa-chalkboard ms-2 me-1"></i>
                            <span>${programa.metodologia || 'Presencial'}</span>
                        </div>
                    </div>
                    <span class="badge bg-success rounded-pill px-2">${programa.codigo_snies}</span>
                </div>
            </a>
        `;
    });

    listaProgramas.innerHTML = html;
}

// Función para seleccionar un programa y cargar sus asignaturas
function seleccionarPrograma(id) {
    // Si ya está seleccionado, no hacer nada
    if (programaSeleccionadoId === id) return;

    programaSeleccionadoId = id;
    asignaturaSeleccionadaId = null;

    // Actualizar UI para mostrar el programa seleccionado
    const programaSeleccionado = programas.find(p => p.id_programa === id);
    document.getElementById('programa-seleccionado').textContent = `${programaSeleccionado.programa} (${programaSeleccionado.codigo_snies})`;

    // Resaltar el elemento seleccionado
    document.querySelectorAll('#programas-lista .list-group-item').forEach(el => {
        el.classList.remove('active');
    });
    document.querySelector(`#programas-lista .list-group-item[onclick="seleccionarPrograma(${id})"]`).classList.add('active');

    // Mostrar el buscador de asignaturas con animación
    const buscarAsignaturaContainer = document.getElementById('buscar-asignatura-container');
    buscarAsignaturaContainer.style.display = 'flex';
    buscarAsignaturaContainer.classList.add('animate__animated', 'animate__fadeIn');

    // Habilitar el botón para crear nueva asignatura
    document.getElementById('btn-nueva-asignatura').disabled = false;

    // Limpiar y mostrar loading en la lista de asignaturas
    document.getElementById('asignaturas-lista').innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-info" role="status">
                <span class="visually-hidden">Cargando asignaturas...</span>
            </div>
            <p class="mt-2 text-muted">Cargando asignaturas...</p>
        </div>
    `;

    // Ocultar detalles de asignatura si están visibles
    document.getElementById('detalles-asignatura-container').style.display = 'none';

    // Cargar asignaturas de este programa
    cargarAsignaturasPorPrograma(id);
}

// Función para cargar asignaturas
function cargarAsignaturasPorPrograma(programaId) {
    fetch(`${apiConfig.baseUrl}/asignaturas`, {
        method: 'GET',
        headers: apiConfig.headers
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar asignaturas');
        }
        return response.json();
    })
    .then(data => {
        // Filtrar asignaturas por programa
        asignaturas = data.filter(a => a.programa === programas.find(p => p.id_programa === programaId).programa);
        document.getElementById('contador-asignaturas').textContent = asignaturas.length;
        renderizarAsignaturas();
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('asignaturas-lista').innerHTML = `
            <div class="alert alert-danger m-3">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Error al cargar asignaturas: ${error.message}
            </div>
        `;
        mostrarNotificacion('Error', `No se pudieron cargar las asignaturas: ${error.message}`, 'error');
    });
}

// Función para renderizar asignaturas en el panel
function renderizarAsignaturas() {
    const listaAsignaturas = document.getElementById('asignaturas-lista');

    if (asignaturas.length === 0) {
        listaAsignaturas.innerHTML = `
            <div class="text-center p-4">
                <div class="empty-state">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay asignaturas disponibles para este programa</p>
                    <button class="btn btn-info text-white btn-sm mt-2" onclick="mostrarModalNuevaAsignatura()">
                        <i class="fas fa-plus-circle me-1"></i> Agregar asignatura
                    </button>
                </div>
            </div>
        `;
        return;
    }

    // Agrupar asignaturas por semestre
    const asignaturasPorSemestre = asignaturas.reduce((acc, asignatura) => {
        const semestre = asignatura.semestre || '1';
        if (!acc[semestre]) {
            acc[semestre] = [];
        }
        acc[semestre].push(asignatura);
        return acc;
    }, {});

    // Ordenar semestres
    const semestres = Object.keys(asignaturasPorSemestre).sort((a, b) => a - b);

    let html = '';

    semestres.forEach(semestre => {
        // Añadir encabezado del semestre
        html += `
            <div class="list-group-item list-group-item-secondary border-0 mb-1 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-layer-group me-2"></i>
                        <span>Semestre ${semestre}</span>
                    </div>
                    <span class="badge bg-secondary rounded-pill">${asignaturasPorSemestre[semestre].length}</span>
                </div>
            </div>
        `;

        // Añadir las asignaturas de este semestre
        asignaturasPorSemestre[semestre].sort((a, b) => a.nombre.localeCompare(b.nombre)).forEach(asignatura => {
            html += `
                <a href="#" class="list-group-item list-group-item-action border-0 mb-1 ps-4 ${asignaturaSeleccionadaId === asignatura.id_asignatura ? 'active' : ''}"
                   onclick="mostrarDetallesAsignatura(${asignatura.id_asignatura})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${asignatura.nombre}</h6>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fas fa-hashtag me-1"></i>
                                <span>${asignatura.codigo_asignatura || 'Sin código'}</span>
                                <i class="fas fa-tag ms-2 me-1"></i>
                                <span>${asignatura.tipo || 'Obligatoria'}</span>
                            </div>
                        </div>
                        <span class="badge bg-info rounded-pill">${asignatura.creditos} créd.</span>
                    </div>
                </a>
            `;
        });
    });

    listaAsignaturas.innerHTML = html;
}

// Función para mostrar detalles de una asignatura
function mostrarDetallesAsignatura(id) {
    asignaturaSeleccionadaId = id;

    // Resaltar el elemento seleccionado
    document.querySelectorAll('#asignaturas-lista .list-group-item').forEach(el => {
        el.classList.remove('active');
    });
    const elementoSeleccionado = document.querySelector(`#asignaturas-lista .list-group-item[onclick="mostrarDetallesAsignatura(${id})"]`);
    if (elementoSeleccionado) {
        elementoSeleccionado.classList.add('active');
    }

    // Obtener la asignatura de la lista
    const asignatura = asignaturas.find(a => a.id_asignatura === id);
    if (!asignatura) {
        mostrarNotificacion('Error', 'No se encontró la asignatura seleccionada', 'error');
        return;
    }

    // Actualizar título de la sección de detalles
    document.getElementById('asignatura-seleccionada').textContent = `${asignatura.nombre} - ${asignatura.codigo_asignatura || 'Sin código'}`;

    // Mostrar información básica de la asignatura en la pestaña "Información General"
    const infoTab = document.getElementById('info');
    infoTab.innerHTML = `
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <h5 class="mb-0">Información Básica</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Nombre</div>
                                <div class="fw-bold text-end">${asignatura.nombre}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Código</div>
                                <div class="fw-bold text-end">${asignatura.codigo_asignatura || 'N/A'}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Créditos</div>
                                <div class="fw-bold text-end">${asignatura.creditos}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Semestre</div>
                                <div class="fw-bold text-end">${asignatura.semestre || 'N/A'}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Tipo</div>
                                <div class="fw-bold text-end">${asignatura.tipo || 'Obligatoria'}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <h5 class="mb-0">Información Académica</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Programa</div>
                                <div class="fw-bold text-end">${asignatura.programa}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Intensidad Horaria</div>
                                <div class="fw-bold text-end">${asignatura.intensidad_horaria || 'N/A'}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Modalidad</div>
                                <div class="fw-bold text-end">${asignatura.modalidad || 'Presencial'}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Prerrequisitos</div>
                                <div class="fw-bold text-end">${asignatura.prerequisitos || 'N/A'}</div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                                <div class="text-muted">Correquisitos</div>
                                <div class="fw-bold text-end">${asignatura.corequisitos || 'N/A'}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Preparar pestaña de contenido programático (se cargará después)
    document.getElementById('contenido-programatico-lista').innerHTML = '';
    document.getElementById('contenido-loader').style.display = 'block';

    // Mostrar el contenedor de detalles con animación
    const detallesContainer = document.getElementById('detalles-asignatura-container');
    detallesContainer.style.display = 'block';
    detallesContainer.classList.add('animate__animated', 'animate__fadeIn');

    // Mostrar la primera pestaña por defecto
    const firstTabEl = document.querySelector('#asignaturaTabs li:first-child button');
    const firstTab = new bootstrap.Tab(firstTabEl);
    firstTab.show();
}

// Función para cerrar detalles de asignatura
function cerrarDetallesAsignatura() {
    // Ocultar el panel con animación
    const detallesContainer = document.getElementById('detalles-asignatura-container');
    detallesContainer.classList.remove('animate__fadeIn');
    detallesContainer.classList.add('animate__fadeOut');

    // Esperar a que termine la animación para ocultarlo
    setTimeout(() => {
        detallesContainer.style.display = 'none';
        detallesContainer.classList.remove('animate__fadeOut');
    }, 500);

    // Quitar selección de asignatura
    asignaturaSeleccionadaId = null;
    document.querySelectorAll('#asignaturas-lista .list-group-item').forEach(el => {
        el.classList.remove('active');
    });
}

// Función mejorada para cargar el contenido programático de una asignatura
function cargarContenidoProgramatico(asignaturaId) {
    // Elementos DOM
    const loader = document.getElementById('contenido-loader');
    const listaContainer = document.getElementById('contenido-programatico-lista');

    // Mostrar loader y limpiar contenido anterior
    loader.style.display = 'block';
    listaContainer.innerHTML = '';

    // Establecer el ID de asignatura en el formulario oculto
    document.getElementById('asignatura_id').value = asignaturaId;

    // URL de la API
    const url = `${apiConfig.baseUrl}/contenidos-programaticos/asignatura/${asignaturaId}`;

    // Hacer la petición
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            // Ocultar loader
            loader.style.display = 'none';

            // Procesamos los datos
            // Si datos es un objeto con propiedad 'datos', usamos esa propiedad
            const contenidos = data.datos ? data.datos : (Array.isArray(data) ? data : [data]);
            contenidosProgramaticos = contenidos;

            if (contenidos.length === 0) {
                listaContainer.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h5>No hay contenidos programáticos</h5>
                                    <p class="mb-0">Esta asignatura aún no tiene contenidos programáticos definidos. Puedes agregar uno nuevo usando el botón de abajo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }

            // Renderizar tarjetas de contenido
            let html = '';
            contenidos.forEach(contenido => {
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card contenido-card shadow-sm border-0 h-100">
                            <div class="card-header bg-gradient-info text-white">
                                <h5 class="mb-0 text-truncate">${contenido.tema}</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">${contenido.descripcion.substring(0, 120)}${contenido.descripcion.length > 120 ? '...' : ''}</p>
                            </div>
                            <div class="card-footer bg-light border-top-0">
                                <button class="btn btn-sm btn-primary w-100" onclick="mostrarDetalleContenido(${contenido.id_contenido})">
                                    <i class="fas fa-eye me-1"></i> Ver detalles
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            listaContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error al cargar contenido programático:', error);
            loader.style.display = 'none';
            listaContainer.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <h5>Error al cargar contenidos</h5>
                                <p class="mb-0">${error.message}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
}

// Función para mostrar los detalles de un contenido programático
function mostrarDetalleContenido(contenidoId) {
    contenidoSeleccionadoId = contenidoId;
    const contenido = contenidosProgramaticos.find(c => c.id_contenido == contenidoId);

    if (!contenido) {
        mostrarNotificacion('Error', 'No se encontró el contenido programático seleccionado', 'error');
        return;
    }

    // Rellenar el modal con los detalles
    const detalleContainer = document.getElementById('contenido-programatico-detalle');

    detalleContainer.innerHTML = `
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex align-items-center">
                    <div class="icon-shape bg-primary text-white rounded-circle me-3">
                        <i class="fas fa-bookmark p-2"></i>
                    </div>
                    <h4 class="mb-0">${contenido.tema}</h4>
                </div>
                <hr>
            </div>

            <div class="col-12 mb-4">
                <h5 class="text-primary">
                    <i class="fas fa-bullseye me-2"></i>
                    Resultados de Aprendizaje
                </h5>
                <p class="mb-0 bg-light p-3 rounded">${contenido.resultados_aprendizaje || 'No se han definido resultados de aprendizaje.'}</p>
            </div>

            <div class="col-12">
                <h5 class="text-primary">
                    <i class="fas fa-align-left me-2"></i>
                    Descripción del Contenido
                </h5>
                <p class="mb-0 bg-light p-3 rounded">${contenido.descripcion}</p>
            </div>

            <div class="col-12 mt-4">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center text-muted">
                            <small>
                                <i class="far fa-calendar-alt me-1"></i>
                                Actualizado: ${new Date(contenido.updated_at).toLocaleDateString()}
                            </small>
                            <div class="ms-auto">
                                <small>ID: ${contenido.id_contenido}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('contenidoProgramaticoModal'));
    modal.show();
}

// Función para mostrar el modal de nuevo contenido programático
function mostrarModalNuevoContenido() {
    // Limpiar formulario
    document.getElementById('contenidoForm').reset();
    document.getElementById('contenido_id').value = '';
    document.getElementById('asignatura_id').value = asignaturaSeleccionadaId;

    // Cambiar título del modal
    document.getElementById('formContenidoModalLabel').innerHTML = '<i class="fas fa-plus-circle me-2"></i> Nuevo Contenido Programático';

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('formContenidoModal'));
    modal.show();
}

// Función para editar un contenido seleccionado
function editarContenidoSeleccionado() {
    // Cerrar el modal de detalles
    bootstrap.Modal.getInstance(document.getElementById('contenidoProgramaticoModal')).hide();

    const contenido = contenidosProgramaticos.find(c => c.id_contenido == contenidoSeleccionadoId);

    if (!contenido) {
        mostrarNotificacion('Error', 'No se encontró el contenido programático seleccionado', 'error');
        return;
    }

    // Llenar el formulario con los datos del contenido
    document.getElementById('contenido_id').value = contenido.id_contenido;
    document.getElementById('asignatura_id').value = asignaturaSeleccionadaId;
    document.getElementById('tema').value = contenido.tema;
    document.getElementById('resultados_aprendizaje').value = contenido.resultados_aprendizaje || '';
    document.getElementById('descripcion').value = contenido.descripcion;

    // Cambiar título del modal
    document.getElementById('formContenidoModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i> Editar Contenido Programático';

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('formContenidoModal'));
    modal.show();
}

// Función para guardar un contenido programático (nuevo o edición)
function guardarContenidoProgramatico() {
    // Obtener datos del formulario
    const contenidoId = document.getElementById('contenido_id').value;
    const asignaturaId = document.getElementById('asignatura_id').value;
    const tema = document.getElementById('tema').value;
    const resultadosAprendizaje = document.getElementById('resultados_aprendizaje').value;
    const descripcion = document.getElementById('descripcion').value;

    // Validar campos obligatorios
    if (!tema || !descripcion) {
        mostrarNotificacion('Error', 'Debe completar todos los campos obligatorios', 'error');
        return;
    }

    // Datos para enviar
    const data = {
        asignatura_id: asignaturaId,
        tema: tema,
        resultados_aprendizaje: resultadosAprendizaje,
        descripcion: descripcion
    };

    // URL y método según sea nuevo o edición
    const isNew = !contenidoId;
    const url = isNew
        ? `${apiConfig.baseUrl}/contenidos-programaticos`
        : `${apiConfig.baseUrl}/contenidos-programaticos/${contenidoId}`;
    const method = isNew ? 'POST' : 'PUT';

    // Mostrar indicador de carga en el botón
    const btnGuardar = document.getElementById('guardarContenido');
    const btnTextoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...`;
    btnGuardar.disabled = true;

    // Enviar petición
    fetch(url, {
        method: method,
        headers: apiConfig.headers,
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(response => {
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('formContenidoModal')).hide();

        // Mostrar notificación
        mostrarNotificacion(
            'Éxito',
            isNew ? 'Contenido programático creado correctamente' : 'Contenido programático actualizado correctamente',
            'success'
        );

        // Recargar contenidos
        cargarContenidoProgramatico(asignaturaId);
    })
    .catch(error => {
        console.error('Error al guardar contenido programático:', error);
        mostrarNotificacion('Error', `No se pudo guardar el contenido programático: ${error.message}`, 'error');
    })
    .finally(() => {
        // Restaurar estado del botón
        btnGuardar.innerHTML = btnTextoOriginal;
        btnGuardar.disabled = false;
    });
}

// Función para mostrar el modal de nueva institución
function mostrarModalNuevaInstitucion() {
    // Limpiar formulario
    document.getElementById('institucionForm').reset();

    // Cambiar título del modal
    document.getElementById('institucion-modal-title').innerHTML = 'Nueva Institución';

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('institucionModal'));
    modal.show();

    // Configurar guardar
    document.getElementById('guardarInstitucion').onclick = guardarInstitucion;
}

// Función para guardar una institución
function guardarInstitucion() {
    // Obtener datos del formulario
    const nombre = document.getElementById('nombre').value;
    const codigoIes = document.getElementById('codigo_ies').value;
    const tipo = document.getElementById('tipo').value;
    const municipio = document.getElementById('municipio').value;

    // Validar campos obligatorios
    if (!nombre || !codigoIes) {
        mostrarNotificacion('Error', 'Debe completar el nombre y código IES', 'error');
        return;
    }

    // Datos para enviar
    const data = {
        nombre: nombre,
        codigo_ies: codigoIes,
        tipo: tipo,
        municipio: municipio
    };

    // Mostrar indicador de carga en el botón
    const btnGuardar = document.getElementById('guardarInstitucion');
    const btnTextoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...`;
    btnGuardar.disabled = true;

    // Enviar petición
    fetch(`${apiConfig.baseUrl}/instituciones`, {
        method: 'POST',
        headers: apiConfig.headers,
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(response => {
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('institucionModal')).hide();

        // Mostrar notificación
        mostrarNotificacion('Éxito', 'Institución creada correctamente', 'success');

        // Recargar instituciones
        cargarInstituciones();
    })
    .catch(error => {
        console.error('Error al guardar institución:', error);
        mostrarNotificacion('Error', `No se pudo guardar la institución: ${error.message}`, 'error');
    })
    .finally(() => {
        // Restaurar estado del botón
        btnGuardar.innerHTML = btnTextoOriginal;
        btnGuardar.disabled = false;
    });
}

// Función para mostrar el modal de nuevo programa
function mostrarModalNuevoPrograma() {
    // Verificar si hay una institución seleccionada
    if (!institucionSeleccionadaId) {
        mostrarNotificacion('Error', 'Debe seleccionar una institución', 'error');
        return;
    }

    // En implementación real, aquí iría la lógica para mostrar el modal de nuevo programa
    mostrarNotificacion('Información', 'Funcionalidad en desarrollo', 'info');
}

// Función para mostrar el modal de nueva asignatura
function mostrarModalNuevaAsignatura() {
    // Verificar si hay un programa seleccionado
    if (!programaSeleccionadoId) {
        mostrarNotificacion('Error', 'Debe seleccionar un programa', 'error');
        return;
    }

    // En implementación real, aquí iría la lógica para mostrar el modal de nueva asignatura
    mostrarNotificacion('Información', 'Funcionalidad en desarrollo', 'info');
}

// Mejora de la experiencia de usuario: mostrar tooltips Bootstrap
function habilitarTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Función para añadir animaciones a elementos que entran en el viewport
function habilitarAnimacionesScroll() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeIn');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

// Función para generar cartas más atractivas para contenidos programáticos
function renderizarContenidoProgramaticoEnriquecido(contenido) {
    // Extraer información relevante
    const titulo = contenido.tema;
    const descripcion = contenido.descripcion;
    const resultados = contenido.resultados_aprendizaje || 'No se han definido resultados de aprendizaje.';

    // Generar colores aleatorios para las tarjetas (para variedad visual)
    const colores = [
        'primary', 'success', 'info', 'warning', 'danger', 'dark'
    ];
    const colorRandom = colores[Math.floor(Math.random() * colores.length)];

    // Crear un icono adecuado según el título
    let icono = 'fas fa-book';
    if (titulo.toLowerCase().includes('algoritmo')) icono = 'fas fa-code';
    else if (titulo.toLowerCase().includes('datos')) icono = 'fas fa-database';
    else if (titulo.toLowerCase().includes('programa')) icono = 'fas fa-laptop-code';
    else if (titulo.toLowerCase().includes('diseño')) icono = 'fas fa-paint-brush';
    else if (titulo.toLowerCase().includes('estruct')) icono = 'fas fa-sitemap';

    return `
        <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
            <div class="card contenido-card shadow-sm border-0 h-100">
                <div class="card-header bg-gradient-${colorRandom} text-white">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-white text-${colorRandom} rounded-circle me-2">
                            <i class="${icono}"></i>
                        </div>
                        <h5 class="mb-0 text-truncate">${titulo}</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-${colorRandom} mb-2">
                            <i class="fas fa-bullseye me-1"></i> Resultados de Aprendizaje
                        </h6>
                        <p class="small mb-0 bg-light p-2 rounded">${resultados.substring(0, 100)}${resultados.length > 100 ? '...' : ''}</p>
                    </div>
                    <div>
                        <h6 class="text-${colorRandom} mb-2">
                            <i class="fas fa-align-left me-1"></i> Descripción
                        </h6>
                        <p class="mb-0">${descripcion.substring(0, 120)}${descripcion.length > 120 ? '...' : ''}</p>
                    </div>
                </div>
                <div class="card-footer bg-light border-top-0 d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-${colorRandom}" onclick="editarContenidoDirecto(${contenido.id_contenido})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-${colorRandom}" onclick="mostrarDetalleContenido(${contenido.id_contenido})">
                        <i class="fas fa-eye me-1"></i> Ver detalles
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Función para editar contenido directamente desde la lista
function editarContenidoDirecto(contenidoId) {
    contenidoSeleccionadoId = contenidoId;
    editarContenidoSeleccionado();
}

// Mejora visual: función para alternar modo oscuro
function toggleModoOscuro() {
    document.body.classList.toggle('modo-oscuro');

    // Guardar preferencia en localStorage
    const modoOscuro = document.body.classList.contains('modo-oscuro');
    localStorage.setItem('modo-oscuro', modoOscuro);

    // Actualizar icono del botón
    const iconoModo = document.getElementById('icono-modo');
    if (iconoModo) {
        iconoModo.className = modoOscuro ? 'fas fa-sun' : 'fas fa-moon';
    }
}

// Función para aplicar preferencia de modo oscuro al cargar la página
function aplicarModoOscuro() {
    const modoOscuro = localStorage.getItem('modo-oscuro') === 'true';
    if (modoOscuro) {
        document.body.classList.add('modo-oscuro');

        // Actualizar icono del botón si existe
        const iconoModo = document.getElementById('icono-modo');
        if (iconoModo) {
            iconoModo.className = 'fas fa-sun';
        }
    }
}

// Función para aplicar efectos visuales mejorados al hacer clic en elementos
function aplicarEfectosClick() {
    document.addEventListener('click', function(e) {
        // Solo aplicar a elementos con la clase .btn
        if (e.target.closest('.btn')) {
            // Crear elemento de efecto ripple
            const button = e.target.closest('.btn');
            const ripple = document.createElement('span');
            ripple.className = 'ripple-effect';

            // Calcular posición
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const posX = e.clientX - rect.left - size / 2;
            const posY = e.clientY - rect.top - size / 2;

            // Aplicar posición y tamaño
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${posX}px`;
            ripple.style.top = `${posY}px`;

            // Añadir y luego eliminar después de la animación
            button.appendChild(ripple);
            setTimeout(() => {
                ripple.remove();
            }, 600); // Duración de la animación + pequeño margen
        }
    });
}

// Inicializar funciones adicionales cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Habilitar funciones adicionales de UI
    habilitarTooltips();
    habilitarAnimacionesScroll();
    aplicarModoOscuro();
    aplicarEfectosClick();

    // Añadir botón para modo oscuro en la barra superior si no existe
    const header = document.querySelector('.card.bg-gradient-primary');
    if (header && !document.getElementById('btn-modo-oscuro')) {
        const btnModoOscuro = document.createElement('button');
        btnModoOscuro.id = 'btn-modo-oscuro';
        btnModoOscuro.className = 'btn btn-sm btn-light position-absolute end-0 me-3';
        btnModoOscuro.innerHTML = `<i id="icono-modo" class="${localStorage.getItem('modo-oscuro') === 'true' ? 'fas fa-sun' : 'fas fa-moon'}"></i>`;
        btnModoOscuro.onclick = toggleModoOscuro;
        btnModoOscuro.setAttribute('data-bs-toggle', 'tooltip');
        btnModoOscuro.setAttribute('data-bs-placement', 'bottom');
        btnModoOscuro.setAttribute('title', 'Cambiar modo claro/oscuro');

        header.style.position = 'relative';
        header.appendChild(btnModoOscuro);
    }
});

// Estilos adicionales para modo oscuro
const estilosModoOscuro = document.createElement('style');
estilosModoOscuro.textContent = `
    .modo-oscuro {
        background-color: #121212;
        color: #e0e0e0;
    }

    .modo-oscuro .card {
        background-color: #1e1e1e;
        color: #e0e0e0;
    }

    .modo-oscuro .list-group-item {
        background-color: #2a2a2a;
        color: #e0e0e0;
        border-color: #333;
    }

    .modo-oscuro .list-group-item-action:hover {
        background-color: #333;
    }

    .modo-oscuro .list-group-item.active {
        background-color: #4e73df;
        color: white;
    }

    .modo-oscuro .card-header:not(.bg-gradient-primary):not(.bg-gradient-success):not(.bg-gradient-info):not(.bg-gradient-warning) {
        background-color: #252525 !important;
        color: #e0e0e0;
    }

    .modo-oscuro .card-footer, .modo-oscuro .bg-light {
        background-color: #252525 !important;
        color: #e0e0e0;
    }

    .modo-oscuro .text-muted {
        color: #aaa !important;
    }

    .modo-oscuro .border-0 {
        border-color: #333 !important;
    }

    .modo-oscuro .form-control, .modo-oscuro .input-group-text {
        background-color: #333;
        border-color: #444;
        color: #e0e0e0;
    }

    .modo-oscuro .form-control::placeholder {
        color: #888;
    }

    .modo-oscuro .modal-content {
        background-color: #1e1e1e;
        color: #e0e0e0;
    }

    .modo-oscuro .nav-pills .nav-link:not(.active) {
        color: #e0e0e0;
    }

    .modo-oscuro .table {
        color: #e0e0e0;
    }

    /* Efecto ripple para botones */
    .btn {
        position: relative;
        overflow: hidden;
    }

    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.5);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
`;

// Añadir estilos al documento
document.head.appendChild(estilosModoOscuro);
</script>
@endsection

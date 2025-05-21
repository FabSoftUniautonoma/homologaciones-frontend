// dashboard.js - Versión completa optimizada
const apiBaseUrl = 'https://homologacionesback.educarenemociones.com/api'; // Usamos rutas relativas para evitar problemas con CORS
let usuarioActual = null;
let userPassword = ""; // Almacenará la contraseña para mostrar/ocultar

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM cargado correctamente');

    // Inicializar tooltips de Bootstrap
    try {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltipTriggerList.length > 0) {
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            console.log('Tooltips inicializados');
        }
    } catch (e) {
        console.error('Error al inicializar tooltips:', e);
    }

    // Cargar datos del usuario desde localStorage
    const userData = localStorage.getItem('user_data');
    console.log('Datos del usuario en localStorage:', userData ? 'Encontrados' : 'No encontrados');

    if (userData) {
        try {
            usuarioActual = JSON.parse(userData);
            console.log('Usuario actual:', usuarioActual);

            if (usuarioActual && usuarioActual.id_usuario) {
                cargarDatosUsuario(usuarioActual.id_usuario);
                cargarSolicitudesUsuario(usuarioActual.id_usuario);
            } else {
                mostrarNotificacion('Error: ID de usuario no encontrado', 'error');
                console.error('ID de usuario no encontrado en los datos', usuarioActual);
            }
        } catch (e) {
            console.error('Error al parsear datos de usuario:', e);
            mostrarNotificacion('Error al cargar los datos de usuario', 'error');
        }
    } else {
        console.log('Redirigiendo al login por falta de datos de usuario');
        window.location.href = `/auth/login`;
    }

    // Configurar listeners de eventos
    configurarEventListeners();
});

function configurarEventListeners() {
    const btnNotificaciones = document.getElementById('btnNotificaciones');
    if (btnNotificaciones) {
        btnNotificaciones.addEventListener('click', function () {
            mostrarNotificacion('Verificando actualizaciones...', 'info');

            if (usuarioActual && usuarioActual.id_usuario) {
                cargarSolicitudesUsuario(usuarioActual.id_usuario);
            }
        });
    }

    const btnLogout = document.getElementById('btnLogout');
    if (btnLogout) {
        btnLogout.addEventListener('click', cerrarSesion);
    }

    const btnSaveProfile = document.getElementById('btnSaveProfile');
    if (btnSaveProfile) {
        btnSaveProfile.addEventListener('click', guardarCambiosPerfil);
    }

    // Configurar toggle de contraseña
    configurePasswordToggle();
}

function configurePasswordToggle() {
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function () {
            const passwordDisplay = document.getElementById('passwordDisplay');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordDisplay.textContent === '********') {
                // Si hay una contraseña guardada, mostrarla
                if (userPassword) {
                    passwordDisplay.textContent = userPassword;
                    toggleIcon.className = 'bi bi-eye';
                } else {
                    // Si no hay contraseña, intentar obtenerla
                    const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
                    if (userData.password_visible) {
                        passwordDisplay.textContent = userData.password_visible;
                        userPassword = userData.password_visible;
                        toggleIcon.className = 'bi bi-eye';
                    } else {
                        mostrarNotificacion('La contraseña no está disponible para mostrar', 'info');
                    }
                }
            } else {
                // Ocultar contraseña
                passwordDisplay.textContent = '********';
                toggleIcon.className = 'bi bi-eye-slash';
            }
        });
    }
}

function cargarDatosUsuario(usuarioId) {
    console.log('Intentando cargar datos del usuario:', usuarioId);

    if (!usuarioId) {
        console.error('ID de usuario inválido');
        return;
    }

    fetch(`${apiBaseUrl}/usuarios/${usuarioId}`)
        .then(response => {
            console.log('Respuesta recibida:', response.status);
            if (!response.ok) {
                throw new Error(`Error al obtener datos del usuario. Estado: ${response.status}`);
            }
            return response.json();
        })
        .then(response => {
            console.log('Datos recibidos:', response);

            // Verificar la estructura de la respuesta
            if (response && response.datos) {
                // Guardar la contraseña si está disponible
                if (response.datos.password_visible) {
                    userPassword = response.datos.password_visible;

                    // También actualizar en localStorage para futuras sesiones
                    const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
                    userData.password_visible = response.datos.password_visible;
                    localStorage.setItem('user_data', JSON.stringify(userData));
                }

                mostrarDatosUsuario(response.datos);
            } else {
                console.error('Estructura de respuesta inválida:', response);
                mostrarNotificacion('Error en formato de datos del servidor', 'error');
            }
        })
        .catch(error => {
            console.error('Error detallado:', error);
            mostrarNotificacion(`Error: ${error.message}`, 'error');
        });
}

function mostrarDatosUsuario(usuario) {
    console.log('Mostrando datos del usuario:', usuario);

    if (!usuario || typeof usuario !== 'object') {
        console.error('Datos de usuario inválidos:', usuario);
        return;
    }

    // Función auxiliar para actualizar elementos de forma segura
    const updateElement = (id, value, property = 'textContent') => {
        const element = document.getElementById(id);
        if (element) element[property] = value || 'No especificado';
    };

    // Extraer datos con manejo seguro
    const primerNombre = usuario.primer_nombre || '';
    const segundoNombre = usuario.segundo_nombre || '';
    const primerApellido = usuario.primer_apellido || '';
    const segundoApellido = usuario.segundo_apellido || '';

    // Construir nombre completo
    const nombreCompleto = `${primerNombre} ${segundoNombre} ${primerApellido} ${segundoApellido}`.trim().replace(/\s+/g, ' ');

    // Actualizar datos en el modal de perfil
    updateElement('userFullName', nombreCompleto);
    updateElement('userRole', usuario.rol?.nombre || 'Postulante');
    updateElement('userEmail', usuario.email || '');

    // Actualizar datos en el formulario de edición
    const updateFormInput = (name, value) => {
        const input = document.querySelector(`#editProfileForm input[name="${name}"]`);
        if (input) input.value = value || '';
    };

    updateFormInput('nombreCompleto', nombreCompleto);
    updateFormInput('email', usuario.email || '');
    updateFormInput('identificacion', usuario.numero_identificacion || '');
    updateFormInput('telefono', usuario.telefono || '');
    updateFormInput('direccion', usuario.direccion || '');

    // Actualizar información en el modal de Mi Información
    updateElement('info-nombre', nombreCompleto);
    updateElement('info-cedula', usuario.numero_identificacion || 'No especificado');
    updateElement('info-telefono', usuario.telefono || 'No especificado');
    updateElement('info-correo', usuario.email || 'No especificado');
    updateElement('info-direccion', usuario.direccion || 'No especificado');

    // Asegurarse que estos valores nunca sean 'null' o undefined
    updateElement('info-institucion', usuario.institucion_origen || 'No especificado');
    updateElement('info-departamento', usuario.departamento || 'No especificado');
    updateElement('info-municipio', usuario.municipio || 'No especificado');

    // Ahora necesitamos cargar el programa desde las solicitudes
    if (usuarioActual && usuarioActual.id_usuario) {
        fetch(`${apiBaseUrl}/solicitudes/usuario/${usuarioActual.id_usuario}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error al obtener solicitudes. Estado: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Verificar estructura correcta de la respuesta
                const solicitudes = data.datos ? data.datos : data;

                // Si hay solicitudes, tomar la información de programa de la más reciente
                if (solicitudes && solicitudes.length > 0) {
                    // Ordenar solicitudes por fecha (más reciente primero)
                    solicitudes.sort((a, b) => new Date(b.fecha_solicitud) - new Date(a.fecha_solicitud));

                    // Obtener la solicitud más reciente
                    const ultimaSolicitud = solicitudes[0];

                    // Actualizar información del programa
                    updateElement('info-programa',
                        ultimaSolicitud.programa_destino_nombre || 'No especificado');
                } else {
                    updateElement('info-programa', 'No especificado');
                }
            })
            .catch(error => {
                console.error('Error al cargar programa:', error);
                updateElement('info-programa', 'No especificado');
            });
    } else {
        updateElement('info-programa', 'No especificado');
    }

    // Actualizar estado académico
    const statusInfoEl = document.getElementById('info-estado');
    if (statusInfoEl) {
        statusInfoEl.textContent = usuario.estado_academico || 'Activo';
        statusInfoEl.className = 'badge bg-success';
    }

    const promedioEl = document.getElementById('info-promedio');
    if (promedioEl) {
        promedioEl.textContent = usuario.promedio || 'N/A';
    }

    // Actualizar badge
    const statusInfoBadge = document.getElementById('statusInfo');
    if (statusInfoBadge) {
        statusInfoBadge.textContent = 'Actualizada';
        statusInfoBadge.className = 'badge bg-primary';
    }

    console.log('Datos del usuario mostrados correctamente');
}

function cargarSolicitudesUsuario(usuarioId) {
    if (!usuarioId) {
        console.error('ID de usuario inválido para cargar solicitudes');
        return;
    }

    // Ruta corregida para evitar conflictos
    fetch(`${apiBaseUrl}/solicitudes/usuario/${usuarioId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al obtener solicitudes. Estado: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Solicitudes recibidas:', data);

            // Verificar estructura correcta de la respuesta
            const solicitudes = data.datos ? data.datos : data;

            // Procesar los datos recibidos
            if (solicitudes && solicitudes.length > 0) {
                mostrarSolicitudesUsuario(solicitudes);
                cargarPrimeraHomologacion(solicitudes);
            } else {
                console.log('No se encontraron solicitudes');
                // Actualizar UI para mostrar que no hay solicitudes
                const statusElements = ['statusHomologacion', 'statusPrimeraHomologacion'];
                statusElements.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = 'Sin solicitudes';
                        element.className = 'badge bg-secondary';
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar solicitudes:', error);
            mostrarNotificacion(`Error: ${error.message}`, 'error');

            // Actualizar UI para mostrar el error
            const statusElements = ['statusHomologacion', 'statusPrimeraHomologacion'];
            statusElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = 'Error';
                    element.className = 'badge bg-danger';
                }
            });
        });
}

function mostrarSolicitudesUsuario(solicitudes) {
    if (!solicitudes || solicitudes.length === 0) {
        console.log('No hay solicitudes para mostrar');
        return;
    }

    // Ordenar solicitudes por fecha (más reciente primero)
    solicitudes.sort((a, b) => new Date(b.fecha_solicitud) - new Date(a.fecha_solicitud));

    // Obtener la solicitud más reciente
    const ultimaSolicitud = solicitudes[0];
    console.log('Mostrando solicitud más reciente:', ultimaSolicitud);

    // Actualizar badge de estado
    const estadoBadge = document.getElementById('statusHomologacion');
    if (estadoBadge) {
        const estado = ultimaSolicitud.estado || 'Pendiente';
        estadoBadge.textContent = estado;
        estadoBadge.className = 'badge';

        switch (estado.toLowerCase()) {
            case 'radicado': estadoBadge.classList.add('bg-primary'); break;
            case 'en revisión': estadoBadge.classList.add('bg-warning'); break;
            case 'aprobado': estadoBadge.classList.add('bg-success'); break;
            case 'rechazado': estadoBadge.classList.add('bg-danger'); break;
            default: estadoBadge.classList.add('bg-secondary');
        }
    }

    // Actualizar información en el modal de homologación
    const updateElement = (id, content) => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = content;
    };

    updateElement('radicado-number',
        `<i class="bi bi-file-earmark-text me-2"></i>No. Radicado: ${ultimaSolicitud.numero_radicado || 'No asignado'}`);

    // Nombre del estudiante
    let nombreCompleto = 'No disponible';
    if (usuarioActual) {
        nombreCompleto = [
            usuarioActual.primer_nombre,
            usuarioActual.segundo_nombre,
            usuarioActual.primer_apellido,
            usuarioActual.segundo_apellido
        ].filter(Boolean).join(' ');
    }
    updateElement('homologacion-estudiante', nombreCompleto);

    // Programa destino
    const nombrePrograma = ultimaSolicitud.programa_destino_nombre || 'No especificado';
    updateElement('homologacion-programa', nombrePrograma);

    // Fechas
    updateElement('homologacion-fecha', formatearFecha(ultimaSolicitud.fecha_solicitud) || 'No disponible');
    updateElement('homologacion-actualizacion', formatearFecha(ultimaSolicitud.updated_at) || 'No disponible');

    // Actualizar estado
    actualizarEstadoSolicitud(ultimaSolicitud.estado);

    // Generar timeline
    generarTimeline(ultimaSolicitud);

    // Si hay asignaturas homologadas, cargarlas
    if (ultimaSolicitud.id_solicitud) {
        cargarAsignaturasHomologadas(ultimaSolicitud.id_solicitud);
    }
}

function actualizarEstadoSolicitud(estado) {
    if (!estado) estado = 'Pendiente';

    // Función auxiliar para actualizar elementos de forma segura
    const updateElement = (id, content, className = null) => {
        const el = document.getElementById(id);
        if (!el) return;

        if (content) {
            if (typeof content === 'string' && content.includes('<')) {
                el.innerHTML = content;
            } else {
                el.textContent = content;
            }
        }

        if (className) {
            el.className = className;
        }
    };

    // Actualizar header de estado
    const headerEstado = document.getElementById('estado-header');
    const alertEstado = document.getElementById('estado-alert');
    const progressBar = document.getElementById('progress-bar');

    if (!headerEstado || !alertEstado || !progressBar) {
        console.warn('Elementos de estado no encontrados en el DOM');
        return;
    }

    // Resetear clases
    headerEstado.className = 'card-header text-white';
    alertEstado.className = 'alert';
    progressBar.className = 'progress-bar';

    // Mensaje y clases según estado
    let mensaje = '';
    let porcentaje = 0;

    switch (estado.toLowerCase()) {
        case 'radicado':
            headerEstado.classList.add('bg-primary');
            alertEstado.classList.add('alert-primary');
            progressBar.classList.add('bg-primary');
            mensaje = 'Su solicitud ha sido radicada y está pendiente de revisión.';
            porcentaje = 25;
            break;
        case 'en revisión':
            headerEstado.classList.add('bg-warning');
            alertEstado.classList.add('alert-warning');
            progressBar.classList.add('bg-warning');
            mensaje = 'Su solicitud está siendo revisada por el comité académico.';
            porcentaje = 50;
            break;
        case 'aprobado':
            headerEstado.classList.add('bg-success');
            alertEstado.classList.add('alert-success');
            progressBar.classList.add('bg-success');
            mensaje = 'Su solicitud de homologación ha sido aprobada.';
            porcentaje = 100;
            break;
        case 'rechazado':
            headerEstado.classList.add('bg-danger');
            alertEstado.classList.add('alert-danger');
            progressBar.classList.add('bg-danger');
            mensaje = 'Su solicitud de homologación ha sido rechazada.';
            porcentaje = 100;
            break;
        default:
            headerEstado.classList.add('bg-secondary');
            alertEstado.classList.add('alert-secondary');
            progressBar.classList.add('bg-secondary');
            mensaje = `Estado actual: ${estado}`;
            porcentaje = 0;
    }

    // Actualizar texto y barra de progreso
    alertEstado.innerHTML = `<i class="bi bi-info-circle-fill me-2"></i>${mensaje}`;
    progressBar.style.width = `${porcentaje}%`;
    progressBar.setAttribute('aria-valuenow', porcentaje);
    progressBar.textContent = `${porcentaje}% Completado`;

    // Actualizar pasos de progreso
    actualizarPasosHomologacion(estado);
}

function actualizarPasosHomologacion(estado) {
    const steps = ['step-radicacion', 'step-revision', 'step-evaluacion', 'step-aprobacion'];

    // Verificar y obtener elementos que existen en el DOM
    const validSteps = steps.map(id => document.getElementById(id)).filter(el => el !== null);

    if (validSteps.length === 0) {
        console.warn('Elementos de pasos no encontrados en el DOM');
        return;
    }

    // Resetear todos los pasos
    validSteps.forEach(step => step.className = 'step');

    // Marcar pasos según el estado actual
    switch (estado.toLowerCase()) {
        case 'radicado':
            if (validSteps[0]) validSteps[0].classList.add('accepted');
            break;
        case 'en revisión':
            if (validSteps[0]) validSteps[0].classList.add('accepted');
            if (validSteps[1]) validSteps[1].classList.add('accepted');
            break;
        case 'evaluación':
            if (validSteps[0]) validSteps[0].classList.add('accepted');
            if (validSteps[1]) validSteps[1].classList.add('accepted');
            if (validSteps[2]) validSteps[2].classList.add('accepted');
            break;
        case 'aprobado':
            validSteps.forEach(step => step.classList.add('accepted'));
            break;
        case 'rechazado':
            if (validSteps[0]) validSteps[0].classList.add('accepted');
            if (validSteps[1]) validSteps[1].classList.add('accepted');
            if (validSteps[2]) validSteps[2].classList.add('accepted');
            if (validSteps[3]) validSteps[3].classList.add('rejected');
            break;
    }
}

function generarTimeline(solicitud) {
    const timelineContainer = document.getElementById('proceso-timeline');
    if (!timelineContainer) {
        console.warn('Contenedor de timeline no encontrado en el DOM');
        return;
    }

    timelineContainer.innerHTML = ''; // Limpiar contenedor

    // Si no hay solicitud, mostrar mensaje
    if (!solicitud) {
        timelineContainer.innerHTML = '<p class="text-center text-muted">No hay información disponible</p>';
        return;
    }

    // Crear evento de radicación (siempre presente)
    let eventoHTML = `
        <div class="timeline-container left">
            <div class="timeline-content">
                <h5>Radicación de solicitud</h5>
                <p class="text-muted">${formatearFecha(solicitud.fecha_solicitud, true)}</p>
                <p>Se ha registrado correctamente su solicitud de homologación para el programa de ${solicitud.programa_destino_nombre || 'homologación'}.</p>
            </div>
        </div>
    `;
    timelineContainer.innerHTML += eventoHTML;

    // Si el estado es "Radicado", no agregamos más eventos
    if (!solicitud.estado || solicitud.estado.toLowerCase() === 'radicado') return;

    // Evento de revisión
    eventoHTML = `
        <div class="timeline-container right">
            <div class="timeline-content">
                <h5>Verificación de documentos</h5>
                <p class="text-muted">${formatearFecha(solicitud.updated_at, true)}</p>
                <p>Los documentos presentados han sido verificados y están en proceso de evaluación académica.</p>
            </div>
        </div>
    `;
    timelineContainer.innerHTML += eventoHTML;

    // Si el estado es "En revisión", no agregamos más eventos
    if (solicitud.estado.toLowerCase() === 'en revisión') return;

    // Evento de evaluación
    eventoHTML = `
        <div class="timeline-container left">
            <div class="timeline-content">
                <h5>Evaluación académica</h5>
                <p class="text-muted">${formatearFecha(solicitud.updated_at, true)}</p>
                <p>El comité académico ha evaluado las asignaturas solicitadas para homologación.</p>
            </div>
        </div>
    `;
    timelineContainer.innerHTML += eventoHTML;

    // Evento final (aprobado o rechazado)
    const tituloEvento = solicitud.estado.toLowerCase() === 'aprobado' ? 'Aprobación de homologación' : 'Rechazo de homologación';
    const mensajeEvento = solicitud.estado.toLowerCase() === 'aprobado'
        ? 'Su solicitud de homologación ha sido aprobada. En la sección de asignaturas homologadas puede ver el detalle.'
        : 'Su solicitud de homologación ha sido rechazada. Para más información, contacte a la coordinación académica.';

    eventoHTML = `
        <div class="timeline-container right">
            <div class="timeline-content">
                <h5>${tituloEvento}</h5>
                <p class="text-muted">${formatearFecha(solicitud.updated_at, true)}</p>
                <p>${mensajeEvento}</p>
            </div>
        </div>
    `;
    timelineContainer.innerHTML += eventoHTML;
}

function cargarPrimeraHomologacion(solicitudes) {
    if (!solicitudes || solicitudes.length === 0) {
        console.log('No hay solicitudes para mostrar en primera homologación');
        return;
    }

    // Ordenar solicitudes por fecha (más antigua primero)
    const solicitudesOrdenadas = [...solicitudes].sort((a, b) => new Date(a.fecha_solicitud) - new Date(b.fecha_solicitud));

    // Primera solicitud de homologación
    const primeraHomologacion = solicitudesOrdenadas[0];
    console.log('Mostrando primera homologación:', primeraHomologacion);

    // Función auxiliar para actualizar elementos de forma segura
    const updateElement = (id, content) => {
        const el = document.getElementById(id);
        if (el) {
            if (typeof content === 'string' && content.includes('<')) {
                el.innerHTML = content;
            } else {
                el.textContent = content;
            }
        }
    };

    // Actualizar badge
    const statusBadge = document.getElementById('statusPrimeraHomologacion');
    if (statusBadge) {
        statusBadge.textContent = 'Disponible';
        statusBadge.className = 'badge bg-info';
    }

    // Actualizar información en el modal
    updateElement('primera-homologacion-info',
        `La primera homologación fue realizada el ${formatearFecha(primeraHomologacion.fecha_solicitud)} para su ingreso al programa de ${primeraHomologacion.programa_destino_nombre || 'homologación'}.`);

    updateElement('primera-homologacion-radicado',
        `<i class="bi bi-file-earmark-text me-2"></i>No. Radicado: ${primeraHomologacion.numero_radicado || 'No asignado'}`);

    // Cargar datos de asignaturas homologadas
    cargarAsignaturasHomologadas(primeraHomologacion.id_solicitud);
}

function cargarAsignaturasHomologadas(solicitudId) {
    const tablaCuerpo = document.getElementById('primera-homologacion-asignaturas');
    const creditosEl = document.getElementById('primera-homologacion-creditos');

    if (!tablaCuerpo) {
        console.warn('Tabla de asignaturas no encontrada en el DOM');
        return;
    }

    if (!solicitudId) {
        tablaCuerpo.innerHTML = '<tr><td colspan="7" class="text-center">No hay asignaturas homologadas disponibles</td></tr>';
        if (creditosEl) creditosEl.textContent = '0';
        return;
    }

    // Mostrar estado de carga
    tablaCuerpo.innerHTML = '<tr><td colspan="7" class="text-center"><i class="bi bi-hourglass-split me-2"></i>Cargando asignaturas homologadas...</td></tr>';

    // Llamar a la API de homologacion-asignaturas
    fetch(`${apiBaseUrl}/homologacion-asignaturas/${solicitudId}`)
        .then(response => {
            if (!response.ok) {
                // Si la API de homologación no está disponible, intentar con solicitud-asignaturas como fallback
                console.log('No se encontraron homologaciones, buscando datos en solicitud-asignaturas como alternativa');
                return fetch(`${apiBaseUrl}/solicitud-asignaturas/${solicitudId}`);
            }
            return response;
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al obtener asignaturas. Estado: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos de asignaturas recibidos:', data);

            // Verificar el formato de la respuesta para determinar si proviene de homologacion-asignaturas
            // o de solicitud-asignaturas
            const esHomologacion = data.datos && (data.datos.asignaturas_origen || data.asignaturas_origen);

            if (esHomologacion) {
                // Datos desde homologacion-asignaturas
                const homologacionData = data.datos || data;
                mostrarAsignaturasHomologadas(homologacionData);
            } else {
                // Datos desde solicitud-asignaturas (formato anterior)
                const solicitudData = data.datos || data;
                mostrarAsignaturasDesdeFormatoAntiguo(solicitudData);
            }
        })
        .catch(error => {
            console.error('Error al cargar asignaturas:', error);
            if (tablaCuerpo) {
                tablaCuerpo.innerHTML = `<tr><td colspan="7" class="text-center text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error: ${error.message}</td></tr>`;
            }
        });
}

function mostrarAsignaturasHomologadas(datosHomologacion) {
    const tablaCuerpo = document.getElementById('primera-homologacion-asignaturas');
    const creditosEl = document.getElementById('primera-homologacion-creditos');

    if (!tablaCuerpo) return;

    // Limpiar tabla
    tablaCuerpo.innerHTML = '';

    // Total de créditos
    let totalCreditos = 0;

    // Verificar que tengamos las asignaturas de origen
    const asignaturasOrigen = datosHomologacion.asignaturas_origen || [];
    const asignaturasDestino = datosHomologacion.asignaturas_destino || [];

    if (asignaturasOrigen.length === 0) {
        tablaCuerpo.innerHTML = '<tr><td colspan="8" class="text-center">No se encontraron asignaturas homologadas</td></tr>';
        if (creditosEl) creditosEl.textContent = '0';
        return;
    }

    // Recorrer asignaturas de origen y sus correspondientes destinos
    asignaturasOrigen.forEach((asignaturaOrigen, index) => {
        // Obtener la asignatura destino correspondiente o crear un objeto vacío si no existe
        const asignaturaDestino = index < asignaturasDestino.length ? asignaturasDestino[index] : {};

        const fila = document.createElement('tr');

        // COLUMNAS PARA LA ASIGNATURA DE ORIGEN

        // Código asignatura origen
        const tdCodigoOrigen = document.createElement('td');
        tdCodigoOrigen.textContent = asignaturaOrigen.codigo || 'N/A';
        fila.appendChild(tdCodigoOrigen);

        // Nombre asignatura origen
        const tdNombreOrigen = document.createElement('td');
        tdNombreOrigen.textContent = asignaturaOrigen.nombre || 'No disponible';
        fila.appendChild(tdNombreOrigen);

        // Institución origen
        const tdInstitucionOrigen = document.createElement('td');
        tdInstitucionOrigen.textContent = asignaturaOrigen.institucion || 'No disponible';
        fila.appendChild(tdInstitucionOrigen);

        // Nota origen
        const tdNotaOrigen = document.createElement('td');
        tdNotaOrigen.textContent = asignaturaOrigen.nota_origen !== null && asignaturaOrigen.nota_origen !== undefined
            ? parseFloat(asignaturaOrigen.nota_origen).toFixed(1)
            : 'N/A';
        fila.appendChild(tdNotaOrigen);

        // COLUMNAS PARA LA ASIGNATURA DESTINO

        // Código asignatura destino
        const tdCodigoDestino = document.createElement('td');
        if (asignaturaDestino && asignaturaDestino.codigo) {
            tdCodigoDestino.textContent = asignaturaDestino.codigo;
        } else {
            tdCodigoDestino.innerHTML = '<span class="text-muted">En proceso</span>';
        }
        fila.appendChild(tdCodigoDestino);

        // Nombre asignatura homologada
        const tdNombreDestino = document.createElement('td');
        if (asignaturaDestino && asignaturaDestino.nombre) {
            tdNombreDestino.textContent = asignaturaDestino.nombre;
        } else {
            tdNombreDestino.innerHTML = '<span class="text-muted">En proceso</span>';
        }
        fila.appendChild(tdNombreDestino);

        // Nota destino (NUEVA COLUMNA)
        const tdNotaDestino = document.createElement('td');
        if (asignaturaDestino && asignaturaDestino.nota_destino) {
            tdNotaDestino.textContent = parseFloat(asignaturaDestino.nota_destino).toFixed(1);
        } else {
            tdNotaDestino.innerHTML = '<span class="text-muted">En proceso</span>';
        }
        fila.appendChild(tdNotaDestino);

        // Créditos
        const tdCreditos = document.createElement('td');
        let creditos;
        if (asignaturaDestino && asignaturaDestino.creditos) {
            creditos = asignaturaDestino.creditos;
        } else if (asignaturaOrigen.creditos) {
            creditos = asignaturaOrigen.creditos;
        } else {
            creditos = Math.min(asignaturaOrigen.semestre || 3, 4);
        }

        tdCreditos.textContent = creditos;
        fila.appendChild(tdCreditos);

        // Sumar créditos al total
        totalCreditos += Number(creditos);

        // Añadir fila a la tabla
        tablaCuerpo.appendChild(fila);
    });

    // Actualizar total de créditos
    if (creditosEl) {
        creditosEl.textContent = totalCreditos;
    }

    // Actualizar tablas en otros contenedores
    actualizarTablaEnOtrosContenedores(tablaCuerpo.innerHTML, totalCreditos);
}

// También actualizar la función para el formato antiguo
function mostrarAsignaturasDesdeFormatoAntiguo(datosSolicitud) {
    const tablaCuerpo = document.getElementById('primera-homologacion-asignaturas');
    const creditosEl = document.getElementById('primera-homologacion-creditos');

    if (!tablaCuerpo) return;

    // Limpiar tabla
    tablaCuerpo.innerHTML = '';

    // Total de créditos
    let totalCreditos = 0;

    // Verificar si hay asignaturas
    const asignaturas = datosSolicitud.asignaturas || [];

    if (asignaturas.length === 0) {
        tablaCuerpo.innerHTML = '<tr><td colspan="8" class="text-center">No hay asignaturas homologadas para esta solicitud</td></tr>';
        if (creditosEl) creditosEl.textContent = '0';
        return;
    }

    // Recorrer asignaturas
    asignaturas.forEach(asignatura => {
        const fila = document.createElement('tr');

        // Código asignatura origen
        const tdCodigoOrigen = document.createElement('td');
        tdCodigoOrigen.textContent = asignatura.codigo || 'N/A';
        fila.appendChild(tdCodigoOrigen);

        // Nombre asignatura origen
        const tdNombreOrigen = document.createElement('td');
        tdNombreOrigen.textContent = asignatura.nombre || 'No disponible';
        fila.appendChild(tdNombreOrigen);

        // Institución origen
        const tdInstitucionOrigen = document.createElement('td');
        tdInstitucionOrigen.textContent = asignatura.institucion || 'No disponible';
        fila.appendChild(tdInstitucionOrigen);

        // Nota origen
        const tdNotaOrigen = document.createElement('td');
        tdNotaOrigen.textContent = asignatura.nota_origen !== null ? asignatura.nota_origen.toFixed(1) : 'N/A';
        fila.appendChild(tdNotaOrigen);

        // Código asignatura destino
        const tdCodigoDestino = document.createElement('td');
        tdCodigoDestino.innerHTML = '<span class="text-muted">En proceso</span>';
        fila.appendChild(tdCodigoDestino);

        // Nombre asignatura homologada
        const tdNombreDestino = document.createElement('td');
        tdNombreDestino.innerHTML = '<span class="text-muted">En proceso</span>';
        fila.appendChild(tdNombreDestino);

        // Nota destino (NUEVA COLUMNA)
        const tdNotaDestino = document.createElement('td');
        tdNotaDestino.innerHTML = '<span class="text-muted">En proceso</span>';
        fila.appendChild(tdNotaDestino);

        // Créditos
        const tdCreditos = document.createElement('td');
        const creditos = asignatura.creditos || (asignatura.semestre ? Math.min(asignatura.semestre, 4) : 3);
        tdCreditos.textContent = creditos;
        fila.appendChild(tdCreditos);

        // Sumar créditos al total
        totalCreditos += Number(creditos);

        // Añadir fila a la tabla
        tablaCuerpo.appendChild(fila);
    });

    // Actualizar total de créditos
    if (creditosEl) {
        creditosEl.textContent = totalCreditos;
    }

    // Actualizar tablas en otros contenedores
    actualizarTablaEnOtrosContenedores(tablaCuerpo.innerHTML, totalCreditos);
}

// Función auxiliar para actualizar las tablas en otros contenedores
function actualizarTablaEnOtrosContenedores(contenidoTabla, totalCreditos) {
    const asignaturasContainer = document.getElementById('asignaturas-container');
    if (asignaturasContainer) {
        // Clonar la tabla para mostrarla también en la pestaña de asignaturas
        const tablaAsignaturas = document.createElement('div');
        tablaAsignaturas.className = 'table-responsive';
        tablaAsignaturas.innerHTML = `
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Código</th>
                        <th>Asignatura origen</th>
                        <th>Institución origen</th>
                        <th>Nota</th>
                        <th>Código</th>
                        <th>Asignatura homologada</th>
                        <th>Nota</th>
                        <th>Créditos</th>
                    </tr>
                </thead>
                <tbody>
                    ${contenidoTabla}
                </tbody>
            </table>
            <div class="alert alert-success mt-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                Total de créditos homologados: <strong>${totalCreditos}</strong>
            </div>
        `;
        asignaturasContainer.innerHTML = '';
        asignaturasContainer.appendChild(tablaAsignaturas);
    }

    // Actualizar documentos asociados a la solicitud
    const documentosContainer = document.getElementById('documentos-container');
    if (documentosContainer) {
        documentosContainer.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                Los siguientes documentos fueron presentados para esta solicitud:
            </div>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        Certificado de notas
                    </div>
                    <span class="badge bg-success">Aprobado</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Contenidos programáticos
                    </div>
                    <span class="badge bg-success">Aprobado</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-file-earmark me-2"></i>
                        Solicitud firmada
                    </div>
                    <span class="badge bg-success">Aprobado</span>
                </li>
            </ul>
        `;
    }
}

function guardarCambiosPerfil() {
    if (!usuarioActual || !usuarioActual.id_usuario) {
        mostrarNotificacion('Error: No se pudo identificar al usuario', 'error');
        return;
    }

    const form = document.getElementById('editProfileForm');
    if (!form) {
        mostrarNotificacion('No se encontró el formulario de perfil', 'error');
        return;
    }

    // Obtener valores del formulario
    const getFormValue = (name) => {
        const input = form.querySelector(`input[name="${name}"]`);
        return input ? input.value.trim() : '';
    };

    const nombreCompleto = getFormValue('nombreCompleto');
    const email = getFormValue('email');
    const identificacion = getFormValue('identificacion');
    const telefono = getFormValue('telefono');
    const direccion = getFormValue('direccion');

    // Validaciones básicas
    const errores = [];

    // Validar nombre completo
    if (!nombreCompleto) {
        errores.push('El nombre completo es obligatorio');
    } else if (nombreCompleto.length < 5) {
        errores.push('El nombre completo debe tener al menos 5 caracteres');
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        errores.push('El correo electrónico es obligatorio');
    } else if (!emailRegex.test(email)) {
        errores.push('El formato del correo electrónico no es válido');
    }

    // Validar identificación
    if (!identificacion) {
        errores.push('El número de identificación es obligatorio');
    } else if (!/^\d{4,20}$/.test(identificacion)) {
        errores.push('El número de identificación debe tener entre 4 y 20 dígitos');
    }

    // Validar teléfono (opcional pero con formato si se proporciona)
    if (telefono && !/^\d{7,15}$/.test(telefono)) {
        errores.push('El número de teléfono debe tener entre 7 y 15 dígitos');
    }

    // Validar dirección (opcional pero con longitud mínima)
    if (direccion && direccion.length < 5) {
        errores.push('La dirección debe tener al menos 5 caracteres');
    }

    // Si hay errores, mostrarlos y detener el proceso
    if (errores.length > 0) {
        // Crear mensaje con todos los errores
        const mensajeError = `Por favor, corrija los siguientes errores:\n• ${errores.join('\n• ')}`;
        mostrarNotificacion(mensajeError, 'error');
        console.error('Errores de validación:', errores);
        return;
    }

    // Dividir nombre completo en componentes
    let nombres = nombreCompleto.split(' ');
    let primer_nombre, segundo_nombre, primer_apellido, segundo_apellido;

    // Asignar nombres según cantidad de palabras
    if (nombres.length >= 4) {
        primer_nombre = nombres[0];
        segundo_nombre = nombres[1];
        primer_apellido = nombres[2];
        segundo_apellido = nombres.slice(3).join(' ');
    } else if (nombres.length === 3) {
        primer_nombre = nombres[0];
        segundo_nombre = '';
        primer_apellido = nombres[1];
        segundo_apellido = nombres[2];
    } else if (nombres.length === 2) {
        primer_nombre = nombres[0];
        segundo_nombre = '';
        primer_apellido = nombres[1];
        segundo_apellido = '';
    } else if (nombres.length === 1) {
        primer_nombre = nombres[0];
        segundo_nombre = '';
        primer_apellido = '';
        segundo_apellido = '';
    }

    // Validar que al menos haya un nombre y un apellido
    if (!primer_nombre || !primer_apellido) {
        mostrarNotificacion('Debe ingresar al menos un nombre y un apellido', 'error');
        return;
    }

    // Mostrar indicador de carga
    mostrarNotificacion('Preparando actualización de perfil...', 'info');

    // Aquí vamos a hacer dos solicitudes para asegurarnos de tener los IDs correctos
    // Primero obtendremos los datos completos del usuario desde authService (que tiene los IDs)
    fetch(`${apiBaseUrl}/auth/user-profile`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('auth_token') || ''}`
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error al obtener perfil completo. Estado: ${response.status}`);
        }
        return response.json();
    })
    .then(userData => {
        // Ahora tenemos los datos con los IDs correctos
        console.log('Obtenidos datos completos con IDs:', userData);

        // Verificar que los datos necesarios estén presentes
        if (!userData || typeof userData !== 'object') {
            throw new Error('Los datos del perfil no son válidos');
        }

        // Crear objeto con campos actualizables y preservando los IDs existentes
        const datosActualizar = {
            email: email,
            numero_identificacion: identificacion,
            telefono: telefono || null,  // Permitir null si está vacío
            direccion: direccion || null, // Permitir null si está vacío
            primer_nombre: primer_nombre,
            segundo_nombre: segundo_nombre || null, // Permitir null si está vacío
            primer_apellido: primer_apellido,
            segundo_apellido: segundo_apellido || null, // Permitir null si está vacío
            tipo_identificacion: userData.tipo_identificacion || 'Cédula de Ciudadanía',

            // Usar los IDs correctos del perfil completo, con validación para casos undefined
            institucion_origen_id: userData.institucion_origen_id || null,
            departamento_id: userData.departamento_id || null,
            municipio_id: userData.municipio_id || null,
            pais_id: userData.pais_id || null,
            facultad_id: userData.facultad_id || null,
            rol_id: userData.rol_id || 1, // Valor predeterminado: Aspirante (1)
            activo: userData.activo !== undefined ? userData.activo : true // Valor predeterminado: true
        };

        console.log('Datos a enviar (con IDs preservados):', datosActualizar);

        // Obtener CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const authToken = localStorage.getItem('auth_token') || '';

        // Verificar que tenemos token de autenticación
        if (!authToken) {
            throw new Error('No se encontró el token de autenticación. Inicie sesión nuevamente.');
        }

        // Enviar datos a la API
        return fetch(`${apiBaseUrl}/usuarios/${usuarioActual.id_usuario}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Authorization': `Bearer ${authToken}`
            },
            body: JSON.stringify(datosActualizar)
        });
    })
    .then(response => {
        console.log('Respuesta de actualización:', response.status);
        if (!response.ok) {
            return response.text().then(text => {
                // Intentar parsear como JSON si es posible
                try {
                    const errorJson = JSON.parse(text);
                    throw new Error(errorJson.mensaje || errorJson.error || `Error al actualizar el perfil. Estado: ${response.status}`);
                } catch (e) {
                    throw new Error(`Error al actualizar el perfil. Estado: ${response.status}. Detalle: ${text}`);
                }
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Perfil actualizado:', data);

        // Verificar que la respuesta contenga un mensaje de éxito
        if (!data || !data.mensaje) {
            throw new Error('Respuesta del servidor incompleta');
        }

        // Cerrar modal
        const modalEl = document.getElementById('editProfileModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

        // Actualizar datos localmente
        if (usuarioActual && usuarioActual.id_usuario) {
            // Actualizar también los datos en localStorage
            const userData = JSON.parse(localStorage.getItem('user_data') || '{}');

            // Actualizar solo los campos modificados
            if (primer_nombre) userData.primer_nombre = primer_nombre;
            if (segundo_nombre !== undefined) userData.segundo_nombre = segundo_nombre;
            if (primer_apellido) userData.primer_apellido = primer_apellido;
            if (segundo_apellido !== undefined) userData.segundo_apellido = segundo_apellido;
            if (email) userData.email = email;
            if (identificacion) userData.numero_identificacion = identificacion;
            if (telefono) userData.telefono = telefono;
            if (direccion) userData.direccion = direccion;

            localStorage.setItem('user_data', JSON.stringify(userData));

            // Recargar datos del usuario
            setTimeout(() => {
                cargarDatosUsuario(usuarioActual.id_usuario);
            }, 500);
        }

        // Mostrar notificación
        mostrarNotificacion('Perfil actualizado correctamente', 'success');
    })
    .catch(error => {
        console.error('Error detallado:', error);

        // Mensaje de error más amigable
        let mensajeError = 'Error al actualizar el perfil';

        if (error.message) {
            // Limpiar mensajes técnicos para mostrar solo la información relevante
            let errorMsg = error.message;

            // Si contiene errores técnicos de SQL, simplificar el mensaje
            if (errorMsg.includes('SQLSTATE') || errorMsg.includes('Integrity constraint')) {
                errorMsg = 'Error en la base de datos. El correo o número de identificación ya podría estar en uso.';
            }

            mensajeError = `${mensajeError}: ${errorMsg}`;
        }

        mostrarNotificacion(mensajeError, 'error');

        // Si es un error de autenticación, redirigir al login
        if (error.message && (error.message.includes('token') || error.message.includes('autenticación'))) {
            setTimeout(() => {
                window.location.href = `${baseRoute}/auth/login`;
            }, 2000);
        }
    });
}

function cerrarSesion() {
    // En lugar de usar confirm, mostrar un modal de Bootstrap
    const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
    logoutModal.show();

    // El cierre de sesión actual se realizará cuando el usuario confirme en el modal
    // (Ver código HTML del modal más abajo)
}

// Esta función será llamada cuando el usuario confirme en el modal
function confirmarCerrarSesion() {
    // Eliminar datos de autenticación
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_data');

    // Mostrar mensaje de cierre de sesión
    mostrarNotificacion('Cerrando sesión...', 'info');

    // Agregar un pequeño retraso para permitir que se muestre la notificación
    setTimeout(() => {
        // Redireccionar al login con ruta absoluta
        const baseRoute = '/homologaciones-frontend/public';
        window.location.href = `${baseRoute}/auth/login`;
    }, 1000);
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    const toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        console.warn('Contenedor de notificaciones no encontrado');
        return;
    }

    const toast = document.createElement('div');
    toast.className = 'toast show';

    // Definir icono y clase según el tipo
    let icono = 'bi-info-circle-fill';
    let colorClass = 'text-primary';

    switch (tipo) {
        case 'success':
            icono = 'bi-check-circle-fill';
            colorClass = 'text-success';
            break;
        case 'error':
            icono = 'bi-exclamation-circle-fill';
            colorClass = 'text-danger';
            break;
        case 'warning':
            icono = 'bi-exclamation-triangle-fill';
            colorClass = 'text-warning';
            break;
    }

    toast.innerHTML = `
        <div class="toast-header">
            <strong class="me-auto"><i class="bi ${icono} me-2 ${colorClass}"></i>Notificación</strong>
            <small>Ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            ${mensaje}
        </div>
    `;

    // Agregar al contenedor
    toastContainer.appendChild(toast);

    // Eliminar el toast después de 5 segundos
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

function formatearFecha(fechaStr, incluirHora = false) {
    if (!fechaStr) return 'No disponible';

    try {
        const fecha = new Date(fechaStr);
        if (isNaN(fecha.getTime())) return fechaStr; // Si no es válida, devolver la original

        const opciones = {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        };

        if (incluirHora) {
            opciones.hour = '2-digit';
            opciones.minute = '2-digit';
        }

        return fecha.toLocaleDateString('es-ES', opciones) +
            (incluirHora ? ' - ' + fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' }) : '');
    } catch (e) {
        console.error('Error al formatear fecha:', e);
        return fechaStr || 'No disponible';
    }
}

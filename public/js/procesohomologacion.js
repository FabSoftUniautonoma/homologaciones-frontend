// Base URL para la API
const API_BASE_URL = 'http://127.0.0.1:8000/api';

// Variables globales
let asignaturasOrigen = [];
let asignaturasDestino = [];
let homologaciones = [];
let solicitudId = null;
let homologacionId = null;
let asignaturaSeleccionadaOrigen = null;
let asignaturaSeleccionadaDestino = null;
let firmaUploadData = null;

document.addEventListener('DOMContentLoaded', function () {
    // Obtener ID de solicitud
    solicitudId = document.getElementById('solicitud_id').value;

    // Extraer datos que vienen del controlador (disponibles en las variables Blade)
    if (typeof window._asignaturasOrigen !== 'undefined') {
        asignaturasOrigen = window._asignaturasOrigen || [];
    }

    if (typeof window._asignaturasDestino !== 'undefined') {
        asignaturasDestino = window._asignaturasDestino || [];
    }

    if (typeof window._homologacionesExistentes !== 'undefined') {
        homologaciones = window._homologacionesExistentes || [];
    }

    // Inicializar
    inicializarEventos();
    cargarDatos();
});

function inicializarEventos() {
    // ==== BOTÓN PRINCIPAL DE AGREGAR HOMOLOGACIÓN ====
    document.getElementById('btn-agregar-homologacion').addEventListener('click', function () {
        // Verificar si hay asignaturas seleccionadas
        if (!asignaturaSeleccionadaOrigen || !asignaturaSeleccionadaDestino) {
            mostrarAlerta('Por favor selecciona una asignatura de origen y una de destino', 'warning');
            return;
        }

        abrirModalAgregarHomologacion();
    });

    // ==== BOTONES DE LA TABLA DE HOMOLOGACIONES ====
    document.getElementById('btn-guardar-homologaciones').addEventListener('click', () => guardarHomologaciones());
    document.getElementById('btn-limpiar-homologaciones').addEventListener('click', limpiarHomologaciones);
    document.getElementById('btn-confirmar-homologacion').addEventListener('click', confirmarHomologacion);
    document.getElementById('btn-cerrar-homologacion').addEventListener('click', cerrarHomologacion);
    document.getElementById('btn-generar-pdf').addEventListener('click', generarPDF);

    // ==== EVENT DELEGATION PARA TODOS LOS CLICKEABLES ====
    document.addEventListener('click', function (e) {
        // === SELECCIÓN DE ASIGNATURAS ===
        if (e.target.closest('.seleccionar-asignatura')) {
            const button = e.target.closest('.seleccionar-asignatura');
            const tipo = button.dataset.tipo; // 'origen' o 'destino'
            const asignatura = JSON.parse(button.dataset.asignatura);
            seleccionarAsignatura(asignatura, tipo);
        }

        // === VER INFORMACIÓN DE ASIGNATURA ===
        if (e.target.closest('.ver-info')) {
            e.preventDefault();
            const link = e.target.closest('.ver-info');
            const tipo = link.dataset.tipo;
            const id = link.dataset.id;
            obtenerInfoAsignatura(tipo, id);
        }

        // === EDITAR HOMOLOGACIÓN ===
        if (e.target.closest('.btn-editar-homologacion')) {
            const index = e.target.closest('.btn-editar-homologacion').dataset.index;
            abrirModalEditarHomologacion(parseInt(index));
        }

        // === ELIMINAR HOMOLOGACIÓN ===
        if (e.target.closest('.btn-eliminar-homologacion')) {
            const index = e.target.closest('.btn-eliminar-homologacion').dataset.index;
            eliminarHomologacion(parseInt(index));
        }
    });

    // ==== SINCRONIZACIÓN DE TABS ENTRE ORIGEN Y DESTINO ====
    document.querySelectorAll('#semestres-origen-tab .nav-link').forEach(tab => {
        tab.addEventListener('click', function () {
            const semestre = this.id.match(/\d+/)[0];
            const tabDestino = document.querySelector(`#semestre-destino-${semestre}-tab`);
            if (tabDestino) {
                tabDestino.click();
            }
        });
    });

    document.querySelectorAll('#semestres-destino-tab .nav-link').forEach(tab => {
        tab.addEventListener('click', function () {
            const semestre = this.id.match(/\d+/)[0];
            const tabOrigen = document.querySelector(`#semestre-origen-${semestre}-tab`);
            if (tabOrigen) {
                tabOrigen.click();
            }
        });
    });

    // ==== MANEJO DE FIRMA ====
    const firmaInput = document.getElementById('firma');
    if (firmaInput) {
        firmaInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid';
                    img.style.maxHeight = '150px';

                    const preview = document.getElementById('firma-preview');
                    preview.innerHTML = '';
                    preview.appendChild(img);

                    firmaUploadData = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            // Actualizar el label del file input
            const label = firmaInput.nextElementSibling;
            if (label) {
                label.textContent = file.name;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Intentar obtener el ID de solicitud de múltiples fuentes
    solicitudId = document.getElementById('solicitud_id')?.value;

    // Si no está disponible en el campo oculto, verificar en las variables JavaScript globales
    if (!solicitudId && window._solicitudId) {
        solicitudId = window._solicitudId;
    }

    // También obtener el homologacionId
    homologacionId = document.getElementById('homologacion_id')?.value;
    if (!homologacionId && window._homologacionId) {
        homologacionId = window._homologacionId;
    }

    console.log('Valores iniciales:', {
        solicitudId: solicitudId,
        homologacionId: homologacionId
    });

    // Extraer datos que vienen del controlador (disponibles en las variables Blade)
    if (typeof window._asignaturasOrigen !== 'undefined') {
        asignaturasOrigen = window._asignaturasOrigen || [];
    }

    if (typeof window._asignaturasDestino !== 'undefined') {
        asignaturasDestino = window._asignaturasDestino || [];
    }

    if (typeof window._homologacionesExistentes !== 'undefined') {
        homologaciones = window._homologacionesExistentes || [];

        // Si tenemos homologaciones y no tenemos solicitudId, intentar extraerlo
        if (homologaciones.length > 0 && !solicitudId) {
            // Buscar el solicitudId en la primera homologación o en cualquier estructura anidada
            if (homologaciones[0].solicitud_id) {
                solicitudId = homologaciones[0].solicitud_id;
                console.log('solicitudId extraído de homologaciones:', solicitudId);
            } else if (homologaciones[0].solicitud && homologaciones[0].solicitud.id) {
                solicitudId = homologaciones[0].solicitud.id;
                console.log('solicitudId extraído de estructura anidada en homologaciones:', solicitudId);
            }
        }
    }

    // Si hay solicitudId extraído de homologaciones, guardarlo en el campo hidden
    if (solicitudId) {
        const inputSolicitudId = document.getElementById('solicitud_id');
        if (inputSolicitudId) {
            inputSolicitudId.value = solicitudId;
        } else {
            // Si el campo no existe, crearlo
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.id = 'solicitud_id';
            hiddenField.value = solicitudId;
            document.body.appendChild(hiddenField);
            console.log('Campo oculto de solicitudId creado con valor:', solicitudId);
        }
    }

    // Inicializar
    inicializarEventos();
    cargarDatos();
});

/**
 * Carga los datos de homologaciones con estrategia de múltiples fuentes
 * @returns {Promise} Promesa que se resuelve cuando termina la carga
 */
function cargarDatos() {
    console.log('Iniciando cargarDatos...');

    // Estrategia exhaustiva para recuperar los IDs
    solicitudId = solicitudId || extraerSolicitudId();
    homologacionId = homologacionId || cargarHomologacionId();

    console.log('IDs recuperados:', {
        solicitudId: solicitudId,
        homologacionId: homologacionId
    });

    // Si ya tenemos homologaciones desde el controlador, cargarlas directamente
    if (homologaciones.length > 0) {
        console.log('Usando homologaciones precargadas desde controlador:', homologaciones.length);
        renderizarTablaHomologaciones();
        actualizarEstadoUI();

        // Guardar en localStorage como respaldo
        if (solicitudId) {
            try {
                localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologaciones));
                localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId || '');
                localStorage.setItem('ultimaSolicitudId', solicitudId);
            } catch (e) {
                console.warn('Error al guardar en localStorage:', e);
            }
        }

        return Promise.resolve(homologaciones);
    }

    // Si tenemos solicitudId, intentar cargar desde localStorage primero
    if (solicitudId) {
        try {
            const datosGuardados = localStorage.getItem(`homologaciones_${solicitudId}`);
            const idGuardado = localStorage.getItem(`homologacionId_${solicitudId}`);

            if (datosGuardados) {
                console.log('Encontrados datos en localStorage para solicitud:', solicitudId);
                homologaciones = JSON.parse(datosGuardados) || [];

                if (idGuardado && !homologacionId) {
                    homologacionId = idGuardado;
                    console.log('ID de homologación recuperado de localStorage:', homologacionId);

                    // Actualizar el campo oculto
                    const inputHomologacionId = document.getElementById('homologacion_id');
                    if (inputHomologacionId) {
                        inputHomologacionId.value = homologacionId;
                    }
                }

                if (homologaciones.length > 0) {
                    console.log('Cargadas homologaciones desde localStorage:', homologaciones.length);
                    renderizarTablaHomologaciones();
                    actualizarEstadoUI();

                    // Intentar sincronizar con el servidor en segundo plano
                    sincronizarConServidor();

                    return Promise.resolve(homologaciones);
                }
            }
        } catch (e) {
            console.warn('Error al cargar desde localStorage:', e);
        }
    }

    // Si tenemos homologacionId, cargar del servidor
    if (homologacionId) {
        console.log('Cargando homologación del servidor con ID:', homologacionId);

        // Mostrar indicador de carga
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'text-center my-4';
        loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Cargando homologaciones...</p>';
        document.querySelector('.container').appendChild(loadingIndicator);

        return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos del servidor:', data);

                if (data.datos) {
                    // Actualizar datos globales
                    asignaturasOrigen = data.datos.asignaturas_origen || [];
                    asignaturasDestino = data.datos.asignaturas_destino || [];
                    homologaciones = data.datos.homologaciones || [];

                    // Actualizar el solicitudId si no lo teníamos todavía
                    if (!solicitudId && data.datos.solicitud_id) {
                        solicitudId = data.datos.solicitud_id;
                        console.log('solicitudId obtenido de la API:', solicitudId);

                        // Actualizar el campo oculto
                        const inputSolicitudId = document.getElementById('solicitud_id');
                        if (inputSolicitudId) {
                            inputSolicitudId.value = solicitudId;
                        }
                    }

                    homologacionId = data.datos.id || homologacionId;

                    // Guardar en localStorage como respaldo
                    if (solicitudId) {
                        try {
                            localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologaciones));
                            localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId || '');
                            localStorage.setItem('ultimaSolicitudId', solicitudId);
                        } catch (e) {
                            console.warn('Error al guardar en localStorage:', e);
                        }
                    }

                    renderizarTablaHomologaciones();
                    actualizarEstadoUI();

                    return homologaciones;
                } else {
                    console.warn('El servidor no devolvió datos válidos');
                    throw new Error('No se encontraron datos de homologación');
                }
            })
            .catch(error => {
                console.error('Error al cargar datos de homologación:', error);
                mostrarAlerta('Error al cargar datos. Intentando usar datos locales.', 'warning');

                // Intentar cargar desde localStorage como último recurso
                return cargarDesdeLocalStorage();
            })
            .finally(() => {
                // Eliminar indicador de carga
                if (loadingIndicator.parentNode) {
                    loadingIndicator.parentNode.removeChild(loadingIndicator);
                }
            });
    }
    // Si no tenemos homologacionId pero sí solicitudId, intentar obtener datos de la solicitud
    else if (solicitudId) {
        console.log('Buscando homologaciones para solicitud:', solicitudId);

        return fetch(`${API_BASE_URL}/solicitudes/${solicitudId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos de solicitud recibidos:', data);

                if (data.datos || data.data) {
                    const solicitudData = data.datos || data.data;

                    // Verificar si hay un ID de homologación en los datos
                    if (solicitudData.homologacion_id || solicitudData.id_homologacion) {
                        homologacionId = solicitudData.homologacion_id || solicitudData.id_homologacion;
                        console.log('homologacionId obtenido de la solicitud:', homologacionId);

                        // Actualizar el campo oculto
                        const inputHomologacionId = document.getElementById('homologacion_id');
                        if (inputHomologacionId) {
                            inputHomologacionId.value = homologacionId;
                        }

                        // Guardar en localStorage
                        localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);

                        // Ahora que tenemos el homologacionId, cargar los datos de homologación
                        return cargarDatos();
                    } else {
                        console.warn('La solicitud no tiene homologación asociada');
                        mostrarAlerta('La solicitud no tiene homologación asociada. Se creará una nueva al guardar.', 'info');

                        // Intentar cargar desde localStorage como último recurso
                        return cargarDesdeLocalStorage();
                    }
                } else {
                    console.warn('El servidor no devolvió datos válidos de solicitud');
                    throw new Error('No se encontraron datos de solicitud');
                }
            })
            .catch(error => {
                console.error('Error al cargar datos de solicitud:', error);
                mostrarAlerta('Error al cargar datos de solicitud', 'danger');

                // Intentar cargar desde localStorage como último recurso
                return cargarDesdeLocalStorage();
            });
    } else {
        mostrarAlerta('Error: No se pudo obtener el ID de solicitud o de homologación', 'danger');
        return Promise.reject('No se pudieron obtener los IDs necesarios');
    }
}
/**
 * Función auxiliar para extraer el ID de solicitud de múltiples fuentes
 * @returns {string|null} El ID de solicitud o null si no se encuentra
 */
function extraerSolicitudId() {
    // Verificar todas las posibles fuentes del ID de solicitud
    let id = null;

    // 1. Buscar en campos ocultos
    id = document.getElementById('solicitud_id')?.value;
    if (id) return id;

    // 2. Buscar en variables globales
    if (window._solicitudId) return window._solicitudId;

    // 3. Buscar en homologaciones existentes
    if (homologaciones.length > 0) {
        for (let h of homologaciones) {
            if (h.solicitud_id) return h.solicitud_id;
            if (h.solicitud && h.solicitud.id) return h.solicitud.id;
        }
    }

    // 4. Buscar en URL
    const urlParams = new URLSearchParams(window.location.search);
    id = urlParams.get('solicitud_id');
    if (id) return id;

    // 5. Buscar en datos de solicitud
    if (typeof window.solicitud !== 'undefined') {
        const s = window.solicitud;
        if (s.id_solicitud) return s.id_solicitud;
        if (s.id) return s.id;
        if (s.solicitud && s.solicitud.id) return s.solicitud.id;
    }

    // Si llegamos aquí, no pudimos encontrar el ID
    return null;
}
function seleccionarAsignatura(asignatura, tipo) {
    // Limpiar selecciones previas del mismo tipo
    document.querySelectorAll('.asignatura-row').forEach(row => {
        if (row.closest(`#semestre-${tipo}-`) ||
            (tipo === 'origen' && row.closest('.bg-primary')) ||
            (tipo === 'destino' && row.closest('.bg-success'))) {
            row.classList.remove('table-primary', 'table-success', 'table-active');
        }
    });

    // Marcar la fila actual como seleccionada
    const fila = event.target.closest('.asignatura-row');
    if (tipo === 'origen') {
        fila.classList.add('table-primary');
    } else {
        fila.classList.add('table-success');
    }

    // Guardar la asignatura seleccionada
    if (tipo === 'origen') {
        asignaturaSeleccionadaOrigen = asignatura;
        console.log('✓ Asignatura origen seleccionada:', asignatura.nombre);
    } else {
        asignaturaSeleccionadaDestino = asignatura;
        console.log('✓ Asignatura destino seleccionada:', asignatura.nombre);
    }

    // Actualizar la UI con el estado actual
    actualizarResumenSeleccion();

    // Mostrar feedback
    mostrarAlerta(`✓ Asignatura ${tipo} seleccionada: ${asignatura.nombre}`, 'info');
}

function actualizarResumenSeleccion() {
    // Crear o actualizar panel de resumen
    let panelResumen = document.getElementById('panel-resumen-seleccion');
    if (!panelResumen) {
        panelResumen = document.createElement('div');
        panelResumen.id = 'panel-resumen-seleccion';
        panelResumen.classList.add('card', 'mb-4', 'shadow-sm');

        // Insertar antes de los botones de acción
        const botonesAccion = document.querySelector('.btn-group-lg');
        botonesAccion?.parentNode.insertBefore(panelResumen, botonesAccion);
    }

    // Construir contenido del panel
    let contenido = `
        <div class="card-header bg-light">
            <h5 class="m-0">
                <i class="fas fa-check-circle mr-2"></i>
                Asignaturas Seleccionadas para Homologar
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
    `;

    // Mostrar asignatura origen seleccionada
    if (asignaturaSeleccionadaOrigen) {
        contenido += `
            <div class="col-md-6">
                <div class="alert alert-primary mb-0">
                    <h6 class="alert-heading mb-1">
                        <i class="fas fa-university mr-1"></i>
                        Asignatura de Origen
                    </h6>
                    <p class="mb-1"><strong>${asignaturaSeleccionadaOrigen.nombre}</strong></p>
                    <small class="d-block">Código: ${asignaturaSeleccionadaOrigen.codigo || 'N/A'}</small>
                    <small class="d-block">Nota: ${asignaturaSeleccionadaOrigen.nota_origen || asignaturaSeleccionadaOrigen.nota || '—'}</small>
                    <small class="d-block">Créditos: ${asignaturaSeleccionadaOrigen.creditos || '—'}</small>
                </div>
            </div>
        `;
    } else {
        contenido += `
            <div class="col-md-6">
                <div class="alert alert-light mb-0">
                    <h6 class="alert-heading mb-1">
                        <i class="fas fa-university mr-1"></i>
                        Asignatura de Origen
                    </h6>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Selecciona una asignatura de origen
                    </p>
                </div>
            </div>
        `;
    }

    // Mostrar asignatura destino seleccionada
    if (asignaturaSeleccionadaDestino) {
        contenido += `
            <div class="col-md-6">
                <div class="alert alert-success mb-0">
                    <h6 class="alert-heading mb-1">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        Asignatura de Destino
                    </h6>
                    <p class="mb-1"><strong>${asignaturaSeleccionadaDestino.nombre}</strong></p>
                    <small class="d-block">Código: ${asignaturaSeleccionadaDestino.codigo || 'N/A'}</small>
                    <small class="d-block">Créditos: ${asignaturaSeleccionadaDestino.creditos || '—'}</small>
                </div>
            </div>
        `;
    } else {
        contenido += `
            <div class="col-md-6">
                <div class="alert alert-light mb-0">
                    <h6 class="alert-heading mb-1">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        Asignatura de Destino
                    </h6>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Selecciona una asignatura de destino
                    </p>
                </div>
            </div>
        `;
    }

    contenido += '</div></div>';
    panelResumen.innerHTML = contenido;

    // Habilitar/deshabilitar botón de agregar
    const btnAgregar = document.getElementById('btn-agregar-homologacion');
    if (btnAgregar) {
        btnAgregar.disabled = !asignaturaSeleccionadaOrigen || !asignaturaSeleccionadaDestino;
    }
}

function abrirModalAgregarHomologacion() {
    const modal = document.getElementById('modal-agregar-homologacion');
    modal.querySelector('#modal-titulo').textContent = 'Agregar Homologación';
    modal.querySelector('#homologacion-index').value = '';

    // Limpiar campos
    const inputs = ['asignatura-origen', 'asignatura-destino', 'nota-origen', 'nota-homologada', 'creditos-homologados', 'observacion'];
    inputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) element.value = '';
    });

    // Pre-llenar con asignaturas seleccionadas
    if (asignaturaSeleccionadaOrigen) {
        const origenSelect = document.getElementById('asignatura-origen');
        if (origenSelect) {
            origenSelect.value = asignaturaSeleccionadaOrigen.id_asignatura || asignaturaSeleccionadaOrigen.id;
            document.getElementById('nota-origen').value = asignaturaSeleccionadaOrigen.nota_origen || asignaturaSeleccionadaOrigen.nota || '';
        }
    }

    if (asignaturaSeleccionadaDestino) {
        const destinoSelect = document.getElementById('asignatura-destino');
        if (destinoSelect) {
            destinoSelect.value = asignaturaSeleccionadaDestino.id_asignatura || asignaturaSeleccionadaDestino.id;
            document.getElementById('creditos-homologados').value = asignaturaSeleccionadaDestino.creditos || '';
        }
    }

    $('#modal-agregar-homologacion').modal('show');
}
// Modificar la función confirmarHomologacion para asegurar que se guarden los datos correctos

// Función para validar que una nota esté entre 3.0 y 5.0
function validarNota(valor) {
    // Convertir a número
    let nota = parseFloat(valor);

    // Verificar si es un número válido
    if (isNaN(nota)) {
        return 3.0; // Valor por defecto si no es un número
    }

    // Limitar al rango 3.0 - 5.0
    if (nota < 3.0) {
        return 3.0;
    } else if (nota > 5.0) {
        return 5.0;
    }

    // Redondear a un decimal
    return Math.round(nota * 10) / 10;
}
/**
 * Función principal para confirmar una homologación
 */
function confirmarHomologacion() {
    const form = document.getElementById('form-homologacion');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const index = document.getElementById('homologacion-index').value;

    // Obtener y validar la nota destino
    const notaDestinoInput = document.getElementById('nota-homologada');
    const notaDestino = validarNota(notaDestinoInput.value);
    notaDestinoInput.value = notaDestino; // Actualizar el campo con el valor validado

    const homologacion = {
        asignatura_origen_id: document.getElementById('asignatura-origen').value,
        asignatura_destino_id: document.getElementById('asignatura-destino').value,
        nota_origen: document.getElementById('nota-origen').value,
        nota_destino: notaDestino, // Usar el valor validado
        creditos: document.getElementById('creditos-homologados').value,
        comentarios: document.getElementById('observacion').value
    };

    // Debug: Verificar datos
    console.log('Datos de homologación a guardar:', homologacion);

    // Verificar que los campos requeridos estén presentes
    if (!homologacion.asignatura_origen_id || !homologacion.asignatura_destino_id) {
        mostrarAlerta('Debes seleccionar asignaturas de origen y destino', 'danger');
        return;
    }

    // Obtener nombres de asignaturas
    const origenOption = document.querySelector(`#asignatura-origen option[value="${homologacion.asignatura_origen_id}"]`);
    const destinoOption = document.querySelector(`#asignatura-destino option[value="${homologacion.asignatura_destino_id}"]`);

    homologacion.asignatura_origen_nombre = origenOption ? origenOption.text : 'Sin nombre';
    homologacion.asignatura_destino_nombre = destinoOption ? destinoOption.text : 'Sin nombre';

    if (index !== '') {
        // Editar existente
        homologaciones[index] = homologacion;
    } else {
        // Agregar nueva
        homologaciones.push(homologacion);
    }

    renderizarTablaHomologaciones();
    $('#modal-agregar-homologacion').modal('hide');

    // Limpiar selección
    asignaturaSeleccionadaOrigen = null;
    asignaturaSeleccionadaDestino = null;
    actualizarResumenSeleccion();

    // Mostrar notificación sin guardar automáticamente
    //mostrarAlerta('Homologación agregada. Recuerda guardar los cambios', 'info');
}
// Función separada para guardar homologaciones que puedes llamar desde un botón específico
function guardarCambiosHomologaciones() {
    guardarHomologaciones(false, (exito) => {
        if (exito) {
            //   mostrarAlerta('Homologación guardada exitosamente', 'success');
        } else {
            mostrarAlerta('Hubo un problema al guardar, pero los datos están respaldados localmente', 'warning');
        }
    });
}
/**
 * Renderiza la tabla de homologaciones con los datos actuales
 */
function renderizarTablaHomologaciones() {
    const tbody = document.getElementById('homologaciones-body');

    if (!tbody) {
        console.error('No se encontró el elemento homologaciones-body');
        return;
    }

    if (homologaciones.length === 0) {
        tbody.innerHTML = `
            <tr id="no-homologaciones">
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-info-circle text-muted mr-2"></i>
                    No hay asignaturas homologadas
                </td>
            </tr>
        `;
        const totalCreditosElement = document.getElementById('total-creditos');
        if (totalCreditosElement) {
            totalCreditosElement.textContent = '0';
        }
        return;
    }

    tbody.innerHTML = '';
    let totalCreditos = 0;

    homologaciones.forEach((h, index) => {
        const row = document.createElement('tr');
        totalCreditos += parseInt(h.creditos || 0);

        // Asegurar que la nota_destino esté en el rango válido
        if (h.nota_destino) {
            h.nota_destino = validarNota(h.nota_destino);
        }

        row.innerHTML = `
            <td>${h.asignatura_origen_nombre || 'Sin nombre'}</td>
            <td>${h.asignatura_destino_nombre || 'Sin nombre'}</td>
            <td>${h.nota_origen || '—'}</td>
            <td>
                <input type="number"
                       class="form-control form-control-sm nota-input"
                       value="${h.nota_destino || ''}"
                       min="3.0" max="5.0" step="0.1"
                       data-index="${index}">
            </td>
            <td>${h.creditos || '—'}</td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary btn-sm btn-editar-homologacion"
                            data-index="${index}" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm btn-eliminar-homologacion"
                            data-index="${index}" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;

        tbody.appendChild(row);
    });

    const totalCreditosElement = document.getElementById('total-creditos');
    if (totalCreditosElement) {
        totalCreditosElement.textContent = totalCreditos;
    }

    // Agregar evento para actualizar notas inline
    document.querySelectorAll('.nota-input').forEach(input => {
        // Establecer atributos min y max
        input.min = 3.0;
        input.max = 5.0;

        // Validar el valor actual
        if (input.value) {
            input.value = validarNota(input.value);
        }

        // Evento para validar durante la entrada
        input.addEventListener('input', function () {
            let valor = parseFloat(this.value);
            if (!isNaN(valor)) {
                // Solo aplicar restricciones si es un número válido
                if (valor < 3.0) {
                    this.value = 3.0;
                } else if (valor > 5.0) {
                    this.value = 5.0;
                }
            }
        });

        // Evento cuando cambia el valor (al perder el foco o cambio)
        input.addEventListener('change', function () {
            const index = this.dataset.index;
            const notaValidada = validarNota(this.value);
            this.value = notaValidada; // Actualizar el campo con el valor validado
            homologaciones[index].nota_destino = notaValidada;

            // Auto-guardar cuando se cambia una nota
            guardarSilencioso();
        });

        // Validar también cuando el usuario pierde el foco
        input.addEventListener('blur', function () {
            if (this.value === '' || isNaN(parseFloat(this.value))) {
                this.value = 3.0;
                const index = this.dataset.index;
                homologaciones[index].nota_destino = 3.0;
                guardarSilencioso();
            }
        });
    });
}

// Inicializar validación para el campo nota-homologada
function inicializarValidacionNotas() {
    // Obtener el elemento input
    const notaHomologadaInput = document.getElementById('nota-homologada');

    if (notaHomologadaInput) {
        // Establecer los valores mínimo y máximo permitidos
        notaHomologadaInput.min = 3.0;
        notaHomologadaInput.max = 5.0;

        // Agregar validación cuando el usuario cambia el valor
        notaHomologadaInput.addEventListener('input', function () {
            let valor = parseFloat(this.value);
            if (!isNaN(valor)) {
                // Solo aplicar restricciones si es un número válido
                if (valor < 3.0) {
                    this.value = 3.0;
                } else if (valor > 5.0) {
                    this.value = 5.0;
                }
            }
        });

        // Validar también cuando el usuario pierde el foco del campo
        notaHomologadaInput.addEventListener('blur', function () {
            if (this.value === '' || isNaN(parseFloat(this.value))) {
                this.value = 3.0;
            } else {
                this.value = validarNota(this.value);
            }
        });
    }
}

// Resto del código sin cambios
/**
 * Carga el ID de homologación desde el input hidden o la URL
 * @returns {string|null} El ID de homologación o null si no se encuentra
 */
function cargarHomologacionId() {
    // Intenta obtener el ID desde el elemento hidden en el DOM
    let id = document.getElementById('homologacion_id')?.value;

    // Si no existe en el DOM, intenta obtenerlo de una variable global
    if (!id && typeof homologacionId !== 'undefined') {
        id = homologacionId;
    }

    // Si aún no se encuentra, intenta extraerlo de la URL
    if (!id) {
        const urlParams = new URLSearchParams(window.location.search);
        id = urlParams.get('homologacion_id') || urlParams.get('id');

        // Buscar en la ruta de la URL si tiene formato /homologacion/{id}
        if (!id) {
            const pathParts = window.location.pathname.split('/');
            const homIndex = pathParts.findIndex(part => part === 'homologacion' || part === 'homologaciones');
            if (homIndex !== -1 && pathParts[homIndex + 1]) {
                id = pathParts[homIndex + 1];
            }
        }
    }

    // Normalizar el ID: eliminar prefijo HOM-YYYY- si existe
    if (id && id.includes('HOM-')) {
        // Extraer solo el número después del último guión
        const parts = id.split('-');
        if (parts.length > 0) {
            id = parts[parts.length - 1]; // Obtener la última parte (el número)
        }
    }

    console.log('ID de homologación cargado y normalizado:', id);
    return id || null;
}

// Llamar a inicializarValidacionNotas cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', inicializarValidacionNotas);

/**
 * Guarda las homologaciones en el servidor y localStorage para asegurar persistencia
 * @param {boolean} confirmado Si es una confirmación final de homologación
 * @param {function} callback Función a ejecutar al terminar
 * @returns {Promise} Promesa que se resuelve cuando termina la operación
 */
function guardarHomologaciones(confirmado = false, callback = () => { }) {
    console.log('Iniciando guardarHomologaciones...');

    // Asegurarnos de tener IDs necesarios
    homologacionId = homologacionId || cargarHomologacionId();
    solicitudId = solicitudId || extraerSolicitudId();

    console.log('IDs para guardar:', {
        homologacionId: homologacionId,
        solicitudId: solicitudId
    });

    // Actualizar notas desde los inputs antes de guardar
    actualizarNotasDesdeInputs();

    // Validar datos antes de guardar
    const errores = validarDatosHomologacion();
    if (errores.length > 0) {
        console.error('Errores de validación:', errores);
        mostrarAlerta(`Errores al guardar: ${errores.join(', ')}`, 'danger');
        if (callback) callback(false);
        return Promise.reject(errores);
    }

    // Si no hay homologaciones, no hay nada que guardar
    if (homologaciones.length === 0) {
        mostrarAlerta('No hay homologaciones para guardar', 'warning');
        if (callback) callback(false);
        return Promise.resolve(false);
    }

    // Verificar ID de solicitud
    if (!solicitudId) {
        mostrarAlerta('Error: No se encontró el ID de solicitud', 'danger');
        if (callback) callback(false);
        return Promise.reject('ID de solicitud no encontrado');
    }

    // Mostrar indicador de carga
    const btnGuardar = document.getElementById('btn-guardar-homologaciones');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    btnGuardar.disabled = true;

    // Guardar en localStorage como respaldo, incluso antes de intentar el servidor
    try {
        localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologaciones));
        localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId || '');
        localStorage.setItem('ultimaSolicitudId', solicitudId);
        console.log('Datos guardados en localStorage como respaldo');
    } catch (e) {
        console.warn('Error al guardar en localStorage:', e);
    }

    // Preparar datos para enviar al servidor
    const datosHomologacion = {
        solicitud_id: solicitudId,
        homologacion_id: homologacionId || null,
        asignaturas_origen: homologaciones.map(h => h.asignatura_origen_id),
        asignaturas_destino: homologaciones.map(h => h.asignatura_destino_id),
        notas_destino: homologaciones.map(h => h.nota_destino),
        comentarios_asignaturas: homologaciones.map(h => h.comentarios || ''),
        comentarios: document.getElementById('comentarios_generales')?.value || '',
        homologaciones: homologaciones.map(h => ({
            asignatura_origen_id: h.asignatura_origen_id,
            asignatura_destino_id: h.asignatura_destino_id,
            nota_destino: h.nota_destino,
            nota_origen: h.nota_origen,
            creditos: h.creditos,
            comentarios: h.comentarios || '',
            solicitud_id: solicitudId
        })),
        confirmado: confirmado
    };

    console.log('Datos a enviar al servidor:', datosHomologacion);

    // Determinar URL y método basado en si tenemos homologacionId
    const esActualizacion = !!homologacionId;

    // Normalizar el ID para la API: eliminar prefijo si existe
    let apiHomologacionId = homologacionId;
    if (apiHomologacionId && apiHomologacionId.includes('HOM-')) {
        const parts = apiHomologacionId.split('-');
        if (parts.length > 0) {
            apiHomologacionId = parts[parts.length - 1];
        }
    }

    const url = esActualizacion
        ? `${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`
        : `${API_BASE_URL}/homologacion-asignaturas`;

    const metodo = esActualizacion ? 'PUT' : 'POST';

    console.log(`Enviando ${metodo} a ${url}`);

    // Realizar solicitud al servidor
    return fetch(url, {
        method: metodo,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(datosHomologacion)
    })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error del servidor: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data);

            // Actualizar el ID de homologación si es una nueva
            if (data.datos && data.datos.id) {
                homologacionId = data.datos.id;

                // Actualizar el campo oculto
                const inputHomologacionId = document.getElementById('homologacion_id');
                if (inputHomologacionId) {
                    inputHomologacionId.value = homologacionId;
                } else {
                    // Crear el campo si no existe
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.id = 'homologacion_id';
                    hiddenField.value = homologacionId;
                    document.body.appendChild(hiddenField);
                }

                // Actualizar también en localStorage
                localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);
            }

            // Si recibimos homologaciones actualizadas, actualizar nuestro array
            if (data.datos && Array.isArray(data.datos.homologaciones)) {
                homologaciones = data.datos.homologaciones;
                renderizarTablaHomologaciones();

                // Actualizar en localStorage
                localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologaciones));
            }

            // mostrarAlerta(data.message || 'Homologaciones guardadas exitosamente', 'success');
            actualizarEstadoUI();

            if (callback) callback(true);
            return data;
        })
        .catch(error => {
            console.error('Error al guardar homologaciones:', error);
            mostrarAlerta(`Error al guardar: ${error.message}. Los datos se han guardado localmente.`, 'warning');

            if (callback) callback(false);
        })
        .finally(() => {
            // Restaurar botón
            btnGuardar.innerHTML = textoOriginal;
            btnGuardar.disabled = false;
        });
}

// Función auxiliar para verificar los datos antes de enviar
function verificarDatosHomologacion() {
    console.group('Verificación de Datos de Homologación');
    console.log('solicitudId:', solicitudId);
    console.log('homologacionId:', homologacionId);
    console.log('Total de homologaciones:', homologaciones.length);

    homologaciones.forEach((h, index) => {
        console.log(`Homologación ${index}:`, {
            asignatura_origen_id: h.asignatura_origen_id,
            asignatura_destino_id: h.asignatura_destino_id,
            nota_destino: h.nota_destino,
            comentarios: h.comentarios
        });
    });
    console.groupEnd();
}
/**
 * Valida los datos de homologación antes de guardar
 * @returns {Array} Lista de errores encontrados
 */
function validarDatosHomologacion() {
    let errores = [];

    // Validar IDs necesarios
    if (!solicitudId) {
        errores.push('No se encontró el ID de solicitud');
    }

    // Validar contenido de homologaciones
    homologaciones.forEach((h, index) => {
        // Validar campos obligatorios
        if (!h.asignatura_origen_id) {
            errores.push(`Homologación ${index + 1}: Falta asignatura origen`);
        }

        if (!h.asignatura_destino_id) {
            errores.push(`Homologación ${index + 1}: Falta asignatura destino`);
        }

        // Validar nota destino
        if (h.nota_destino === undefined || h.nota_destino === null || h.nota_destino === '') {
            // Si no hay nota, asignar valor por defecto
            h.nota_destino = '3.0';
        } else {
            // Validar que la nota sea un número en el rango permitido
            const nota = parseFloat(h.nota_destino);
            if (isNaN(nota)) {
                h.nota_destino = '3.0';
            } else if (nota < 3.0) {
                h.nota_destino = '3.0';
            } else if (nota > 5.0) {
                h.nota_destino = '5.0';
            } else {
                // Formato con un decimal
                h.nota_destino = nota.toFixed(1);
            }
        }
    });

    return errores;
}

// Variables globales para almacenar las firmas
window.firmaCoordinadorData = null;
window.firmaVicerrectorData = null;

// Función para recuperar firma del coordinador desde localStorage si está disponible
function cargarFirmaCoordinador() {
    try {
        const firmaGuardada = localStorage.getItem('firmaCoordinadorData');
        if (firmaGuardada) {
            window.firmaCoordinadorData = firmaGuardada;

            // Actualizar vista previa si estamos en la vista del coordinador
            const firmaPreview = document.getElementById('firma-preview');
            if (firmaPreview) {
                firmaPreview.innerHTML = '';

                const img = document.createElement('img');
                img.src = firmaGuardada;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '140px';
                firmaPreview.appendChild(img);
            }

            // Mostrar firma coordinador en vista vicerrector
            const firmaCoordinadorPreviewVice = document.getElementById('firma-coordinador-preview-vice');
            if (firmaCoordinadorPreviewVice) {
                firmaCoordinadorPreviewVice.innerHTML = '';

                const img = document.createElement('img');
                img.src = firmaGuardada;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '140px';
                firmaCoordinadorPreviewVice.appendChild(img);

                // Habilitar la sección del vicerrector si estamos en su vista
                habilitarSeccionVicerrector();
            }

            return true;
        }
    } catch (error) {
        console.error('Error al cargar firma del coordinador:', error);
    }
    return false;
}

// Función para manejar la subida de la firma del coordinador
function handleFirmaCoordinadorUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Verificar que sea una imagen
    if (!file.type.match('image.*')) {
        mostrarAlerta('Por favor seleccione un archivo de imagen válido (JPG, PNG, GIF)', 'danger');
        return;
    }

    // Actualizar la etiqueta del input con el nombre del archivo
    const fileName = file.name;
    const label = document.querySelector('label[for="firma"]');
    if (label) {
        label.textContent = fileName;
    }

    // Leer y mostrar la vista previa
    const reader = new FileReader();
    reader.onload = function (e) {
        const firmaPreview = document.getElementById('firma-preview');
        if (firmaPreview) {
            firmaPreview.innerHTML = '';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '140px';
            firmaPreview.appendChild(img);
        }

        // Almacenar los datos de la imagen para usar en el PDF
        window.firmaCoordinadorData = e.target.result;

        // Guardar en localStorage para compartir con la vista del vicerrector
        try {
            localStorage.setItem('firmaCoordinadorData', e.target.result);
        } catch (error) {
            console.error('Error al guardar firma en localStorage (puede ser demasiado grande):', error);
            mostrarAlerta('Advertencia: No se pudo guardar la firma para compartir (imagen demasiado grande)', 'warning');
        }

        // Habilitar botón de generar PDF en vista coordinador
        const btnGenerarPDFCoord = document.getElementById('btn-generar-pdf-coordinador');
        if (btnGenerarPDFCoord) {
            btnGenerarPDFCoord.disabled = false;
        }
    };

    reader.readAsDataURL(file);
}

// Función para manejar la subida de la firma del vicerrector
function handleFirmaVicerrectorUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Verificar que sea una imagen
    if (!file.type.match('image.*')) {
        mostrarAlerta('Por favor seleccione un archivo de imagen válido (JPG, PNG, GIF)', 'danger');
        return;
    }

    // Actualizar la etiqueta del input con el nombre del archivo
    const fileName = file.name;
    const label = document.querySelector('label[for="firma-vicerrector"]');
    if (label) {
        label.textContent = fileName;
    }

    // Leer y mostrar la vista previa
    const reader = new FileReader();
    reader.onload = function (e) {
        const firmaPreview = document.getElementById('firma-vicerrector-preview');
        if (firmaPreview) {
            firmaPreview.innerHTML = '';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            img.style.maxHeight = '140px';
            firmaPreview.appendChild(img);
        }

        // Almacenar los datos de la imagen para usar en el PDF
        window.firmaVicerrectorData = e.target.result;

        // Habilitar botón de generar PDF final si tenemos ambas firmas
        if (window.firmaCoordinadorData) {
            const btnGenerarPDFFinal = document.getElementById('btn-generar-pdf-final');
            if (btnGenerarPDFFinal) {
                btnGenerarPDFFinal.disabled = false;
            }
        }
    };

    reader.readAsDataURL(file);
}

// Función para inicializar los event listeners
function initSignatureHandlers() {
    // Cargar firma del coordinador si está disponible
    const firmaExiste = cargarFirmaCoordinador();

    // Event listener para la firma del coordinador (solo en vista coordinador)
    const firmaCoordinadorInput = document.getElementById('firma');
    if (firmaCoordinadorInput) {
        firmaCoordinadorInput.addEventListener('change', handleFirmaCoordinadorUpload);

        // Habilitar botón si ya hay firma guardada
        if (firmaExiste) {
            const btnGenerarPDFCoord = document.getElementById('btn-generar-pdf-coordinador');
            if (btnGenerarPDFCoord) {
                btnGenerarPDFCoord.disabled = false;
            }
        }
    }

    // Event listener para la firma del vicerrector (solo en vista vicerrector)
    const firmaVicerrectorInput = document.getElementById('firma-vicerrector');
    if (firmaVicerrectorInput) {
        firmaVicerrectorInput.addEventListener('change', handleFirmaVicerrectorUpload);
    }

    // Configurar botones específicos de cada vista
    configurarBotones();
}


// Detectar en qué vista estamos
function esVistaCoordinador() {
    // Verificar si estamos en la vista del coordinador
    return window.location.href.includes('procesohomologacion') &&
        !window.location.href.includes('procesohomologacionvice');
}

function esVistaVicerrector() {
    // Verificar si estamos en la vista del vicerrector
    return window.location.href.includes('procesohomologacionvice');
}

// Crear sección de visualización de firma coordinador en vista vicerrector
function crearSeccionVisualizacionFirmaCoordinador() {
    if (!esVistaVicerrector()) return; // Solo crear en vista vicerrector

    const seccionCoordinador = document.createElement('div');
    seccionCoordinador.className = 'card mb-4 border-left-info';
    seccionCoordinador.style.borderLeftColor = '#0277bd';

    seccionCoordinador.innerHTML = `
        <div class="card-header py-3 text-white" style="background-color: #0277bd;">
            <h4 class="m-0 font-weight-bold">
                <i class="fas fa-signature mr-2"></i>Firma del Coordinador
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="firma-coordinador-status" class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span id="firma-coordinador-mensaje">Verificando si existe firma del coordinador...</span>
                    </div>
                    <div id="firma-coordinador-preview-vice"
                        class="border rounded p-3 text-center d-flex align-items-center justify-content-center"
                        style="height: 150px; background-color: #e1f5fe; border-color: #6c8ebf;">
                        <p style="color: #19407b;" class="mb-0">Firma del coordinador</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insertar al principio del contenedor
    const container = document.querySelector('.container-fluid');
    if (container.firstChild) {
        container.insertBefore(seccionCoordinador, container.firstChild);
    } else {
        container.appendChild(seccionCoordinador);
    }
}

// Crear sección de firma vicerrector
function crearSeccionFirmaVicerrector() {
    if (!esVistaVicerrector()) return; // Solo crear en vista vicerrector

    const vicerrectorSection = document.createElement('div');
    vicerrectorSection.id = 'seccion-firma-vicerrector';
    vicerrectorSection.className = 'card mb-4 border-left-warning';
    vicerrectorSection.style.borderLeftColor = '#00695c';

    vicerrectorSection.innerHTML = `
        <div class="card-header py-3 text-white" style="background-color: #00695c;">
            <h4 class="m-0 font-weight-bold">
                <i class="fas fa-signature mr-2"></i>Firma del Vicerrector
            </h4>
        </div>
        <div class="card-body">
            <div id="seccion-firma-vicerrector-contenido">
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Se requiere la firma del coordinador antes de poder continuar.
                </div>
            </div>
        </div>
    `;

    // Buscar dónde insertar la sección
    const contenedor = document.querySelector('.container-fluid');
    contenedor.appendChild(vicerrectorSection);
}

// Habilitar sección del vicerrector cuando existe firma del coordinador
function habilitarSeccionVicerrector() {
    if (!esVistaVicerrector()) return;

    const seccionVicerrector = document.getElementById('seccion-firma-vicerrector');
    if (!seccionVicerrector) return;

    const contenidoSeccion = document.getElementById('seccion-firma-vicerrector-contenido');
    const statusCoordinador = document.getElementById('firma-coordinador-status');
    const mensajeCoordinador = document.getElementById('firma-coordinador-mensaje');

    if (window.firmaCoordinadorData) {
        // Actualizar estado de firma coordinador
        if (statusCoordinador) {
            statusCoordinador.className = 'alert alert-success';
            mensajeCoordinador.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Firma del coordinador verificada correctamente.';
        }

        // Habilitar sección vicerrector
        contenidoSeccion.innerHTML = `
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="firma-vicerrector" class="font-weight-bold" style="color: #19407b;">
                    <i class="fas fa-file-upload mr-1"></i> Subir Firma:
                </label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="firma-vicerrector" accept="image/*">
                    <label class="custom-file-label" for="firma-vicerrector" style="color: #19407b;">
                        Seleccionar archivo...
                    </label>
                </div>
                <small class="form-text" style="color: #4b6584;">
                    Formatos aceptados: JPG, PNG, GIF
                </small>
            </div>
        </div/>
        <div class="col-md-6">
            <div id="firma-vicerrector-preview"
                class="border rounded p-3 text-center d-flex align-items-center justify-content-center"
                style="height: 150px; background-color: #e3f2fd; border-color: #1976d2;">
                <p style="color: #1565c0;" class="mb-0">Vista previa de la firma</p>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 text-center">
            <button id="btn-generar-pdf-final" class="btn btn-primary" style="background-color: #1565c0; border-color: #1565c0;" disabled>
                <i class="fas fa-file-pdf mr-1"></i> Generar PDF Final
            </button>
        </div>
    </div>
`;

        // Inicializar event listeners
        const firmaVicerrectorInput = document.getElementById('firma-vicerrector');
        if (firmaVicerrectorInput) {
            firmaVicerrectorInput.addEventListener('change', handleFirmaVicerrectorUpload);
        }

        const btnGenerarPDFFinal = document.getElementById('btn-generar-pdf-final');
        if (btnGenerarPDFFinal) {
            btnGenerarPDFFinal.addEventListener('click', function () {
                generarPDF(true); // true = versión vicerrector (con ambas firmas)
            });
        }
    } else {
        // Actualizar estado de firma coordinador
        if (statusCoordinador) {
            statusCoordinador.className = 'alert alert-danger';
            mensajeCoordinador.innerHTML = '<i class="fas fa-times-circle mr-1"></i> No se ha encontrado la firma del coordinador. No se puede continuar.';
        }

        // Mensaje de error en sección vicerrector
        contenidoSeccion.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-circle mr-1"></i>
                No se puede generar el PDF final sin la firma del coordinador.
                Por favor, asegúrese de que el coordinador haya completado su parte del proceso.
            </div>
        `;
    }
}

// Configurar los botones específicos de cada vista
function configurarBotones() {
    if (esVistaCoordinador()) {
        // En vista coordinador, crear botón de generar PDF
        const contenedor = document.querySelector('.card-body');
        if (contenedor) {
            const btnRow = document.createElement('div');
            btnRow.className = 'row mt-3';
            btnRow.innerHTML = `
                <div class="col-12 text-center">
                    <button id="btn-generar-pdf-coordinador" class="btn btn-primary" disabled>
                        <i class="fas fa-file-pdf mr-1"></i> Previsualizar PDF y Enviar a Vicerrector
                    </button>
                </div>
            `;
            contenedor.appendChild(btnRow);

            // Añadir event listener
            const btnGenerarPDFCoord = document.getElementById('btn-generar-pdf-coordinador');
            if (btnGenerarPDFCoord) {
                btnGenerarPDFCoord.addEventListener('click', function () {
                    generarPDF(false); // false = versión coordinador (solo su firma)
                });

                // Habilitar si ya existe firma
                if (window.firmaCoordinadorData) {
                    btnGenerarPDFCoord.disabled = false;
                }
            }
        }
    } else if (esVistaVicerrector()) {
        // Crear secciones específicas de vicerrector
        crearSeccionVisualizacionFirmaCoordinador();
        crearSeccionFirmaVicerrector();
        habilitarSeccionVicerrector(); // Verificar estado inicial
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    initSignatureHandlers();
});
function generarPDF(esVersionFinal = false) {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'letter',
            compress: true // Mejora la calidad
        });

        // Obtener datos del estudiante
        let estudiante, identificacion, universidad, programa;

        try {
            const datosEstudiante = document.querySelector('.col-md-6');
            if (datosEstudiante) {
                estudiante = datosEstudiante.querySelector('p:nth-child(1)')?.textContent.replace('Nombre:', '').trim() || 'N/A';
                identificacion = datosEstudiante.querySelector('p:nth-child(2)')?.textContent.replace('Identificación:', '').trim() || 'N/A';
                universidad = datosEstudiante.querySelector('p:nth-child(3)')?.textContent.replace('Universidad de Origen:', '').trim() || 'N/A';
                programa = datosEstudiante.querySelector('p:nth-child(4)')?.textContent.replace('Programa de interes:', '').trim() || 'N/A';
            }
        } catch (e) {
            console.error('Error al obtener datos del estudiante:', e);
            estudiante = 'N/A';
            identificacion = 'N/A';
            universidad = 'N/A';
            programa = 'N/A';
        }

        // Obtener la fecha actual
        const fechaActual = new Date();
        const dia = fechaActual.getDate();
        const mes = fechaActual.toLocaleString('es-ES', { month: 'long' });
        const año = fechaActual.getFullYear();
        const fechaFormateada = `Popayán, ${dia} de ${mes} de ${año}`;

        // Número de resolución (generado automáticamente)
        const numeroResolucion = `${año}-${Math.floor(Math.random() * 900) + 100}`;

        // Configurar fuentes y estilos
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(10);

        // Configurar colores institucionales
        const colorAzulInstitucional = [0, 51, 153]; // RGB para azul institucional
        const colorGris = [100, 100, 100]; // RGB para texto gris

        // Primera página
        // --------------
        doc.setFillColor(255, 255, 255);

        // Agregar logo de la institución
        try {
            // Verificar si hay un logo disponible
            if (window.logoUploadData) {
                doc.addImage(window.logoUploadData, 'PNG', 85, 15, 40, 20, undefined, 'FAST');
            } else {
                // Si no hay logo cargado, usar un texto como logo provisional
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, 15, { align: 'center' });
                doc.text('Líderes, visionarios y emprendedores', 105, 22, { align: 'center' });

                // Dibuja una línea decorativa bajo el nombre
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.5);
                doc.line(20, 25, 190, 25);
            }
        } catch (error) {
            console.error('Error al agregar logo:', error);
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
            doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, 15, { align: 'center' });
            doc.text('Líderes, visionarios y emprendedores', 105, 22, { align: 'center' });

            // Dibuja una línea decorativa bajo el nombre
            doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
            doc.setLineWidth(0.5);
            doc.line(20, 25, 190, 25);
        }

        // Número de resolución en la parte superior
        let yPos = 40;
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text(`RESOLUCIÓN No. ${numeroResolucion}`, 105, yPos, { align: 'center' });

        yPos += 10;

        // Texto de la fecha
        doc.text('Del', 105, yPos, { align: 'center' });

        yPos += 7;
        doc.text(`(${dia} ${mes.toUpperCase().substring(0, 3)}. ${año})`, 105, yPos, { align: 'center' });

        yPos += 15;

        // Título principal del documento
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        const tituloPrincipal = `Por la cual se aprueba el estudio de homologación de los cursos aprobados en ${universidad.toUpperCase()}, Programa de ${programa.toUpperCase()}, por ${estudiante.toUpperCase()} identificado con ${identificacion}.`;

        const lineasTituloPrincipal = doc.splitTextToSize(tituloPrincipal, 170);
        doc.text(lineasTituloPrincipal, 20, yPos);

        yPos += lineasTituloPrincipal.length * 6 + 10;

        // Texto de vicerrectoría
        doc.setFont('helvetica', 'bold');
        const textoVicerrectoria = 'La suscrita Vicerrectora Académica de la CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA, en uso de sus atribuciones reglamentarias y en especial las conferidas en el Acuerdo 010 de 2005 expedida por la ASAMBLEA DE FUNDADORES y el Reglamento Estudiantil Acuerdo 011 del 15 febrero de 2017. Artículo 32 y';

        const lineasVicerrectoria = doc.splitTextToSize(textoVicerrectoria, 170);
        doc.text(lineasVicerrectoria, 20, yPos);

        yPos += lineasVicerrectoria.length * 5 + 10;

        // Considerando
        doc.setFont('helvetica', 'bold');
        doc.text('CONSIDERANDO', 105, yPos, { align: 'center' });

        yPos += 10;

        // Texto formal considerando
        doc.setFont('helvetica', 'normal');
        let considerandos = [
            `Que el Decano de la Facultad de ${programa}, realizó el estudio de homologación de los cursos aprobados en el Programa de ${programa.toUpperCase()}, de ${universidad.toUpperCase()}, solicitado por ${estudiante.toUpperCase()} identificado con ${identificacion}.`,

            `Que la Vicerrectora Académica revisó los procedimientos aplicados y los anexos allegados por ${estudiante.toUpperCase()} para el estudio y análisis de la homologación realizada por el Decano de la Facultad correspondiente, con el correspondiente pensum vigente del Programa de ${programa} y por lo anterior.`,

            "Que de conformidad con el Reglamento Estudiantil vigente, se establecen los procedimientos y criterios para la homologación de asignaturas.",

            `Que existe correspondencia entre los contenidos programáticos, intensidad horaria, créditos académicos y nivel de competencias de las asignaturas a homologar.`,

            `Que en sesión del ${dia} de ${mes} de ${año}, el Comité de Homologaciones recomendó la aprobación de las asignaturas que se detallan en la presente resolución.`
        ];

        // Agregar considerandos
        considerandos.forEach((texto, index) => {
            // Asegurar que hay espacio para el considerando
            if (yPos > 240) {
                doc.addPage();
                yPos = 20;

                // Opcional: agregar encabezado en la nueva página
                doc.setFontSize(8);
                doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
                doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 10, { align: 'center' });
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);
            }

            const lineas = doc.splitTextToSize(texto, 165);
            doc.setFont('helvetica', 'bold');
            doc.text(`${index + 1}.`, 20, yPos);
            doc.setFont('helvetica', 'normal');
            doc.text(lineas, 30, yPos);
            yPos += lineas.length * 5 + 3;
        });

        yPos += 5;

        // Resuelve
        doc.setFont('helvetica', 'bold');
        doc.text('RESUELVE:', 105, yPos, { align: 'center' });

        yPos += 10;

        // Artículo Primero - Título e introducción
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 1°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 6;

        // Texto del artículo primero
        const textoArticuloPrimero = `Aprobar el estudio de homologación de ${estudiante.toUpperCase()} identificado con ${identificacion}, de la siguiente manera:`;
        const lineasArticulo1 = doc.splitTextToSize(textoArticuloPrimero, 175);
        doc.text(lineasArticulo1, 20, yPos);

        yPos += lineasArticulo1.length * 5 + 5;

        // Establecer posición inicial para la tabla
        const inicioTabla = yPos;

        // Asegurar que hay espacio para la tabla (o añadir nueva página)
        if (yPos > 180) {
            doc.addPage();
            yPos = 20;

            // Agregar encabezado en la nueva página
            doc.setFontSize(8);
            doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
            doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 10, { align: 'center' });
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(10);
        }

        // Título de la tabla
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.text('CURSOS ACADÉMICOS HOMOLOGADOS', 105, yPos, { align: 'center' });

        yPos += 8;

        // Crear la tabla de cursos homologados
        const headers = ['CURSO INSTITUCIÓN DE ORIGEN', 'CÓDIGO', 'CURSO ACADÉMICO AUTÓNOMA', 'SEM', 'CRED', 'CALIF'];
        const data = homologaciones.map(h => [
            h.asignatura_origen_nombre,
            h.codigo_destino || '',
            h.asignatura_destino_nombre,
            h.semestre || '',
            h.creditos,
            h.nota_destino
        ]);

        // Configuración de la tabla
        doc.autoTable({
            startY: yPos,
            head: [headers],
            body: data,
            margin: { left: 15, right: 15 },
            styles: {
                fontSize: 8,
                font: 'helvetica',
                cellPadding: 2,
                lineWidth: 0.1,
                lineColor: [80, 80, 80],
                textColor: [0, 0, 0],
                halign: 'left'
            },
            headStyles: {
                fillColor: colorAzulInstitucional,
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                halign: 'center',
                valign: 'middle',
                fontSize: 8
            },
            columnStyles: {
                0: { cellWidth: 42, overflow: 'linebreak' },
                1: { cellWidth: 15, halign: 'center' },
                2: { cellWidth: 42, overflow: 'linebreak' },
                3: { cellWidth: 12, halign: 'center' },
                4: { cellWidth: 12, halign: 'center' },
                5: { cellWidth: 12, halign: 'center' }
            },
            alternateRowStyles: {
                fillColor: [240, 240, 255],
            },
            tableLineColor: [0, 51, 153],
            tableLineWidth: 0.2,
            theme: 'grid',
            didDrawCell: function (data) {
                // Mejora visual de la tabla
                if (data.row.index === 0 && data.column.index === 0) {
                    doc.setLineWidth(0.3);
                    doc.setDrawColor(0, 51, 153);
                }
            }
        });

        // Actualizar posición después de la tabla
        yPos = doc.lastAutoTable.finalY + 10;

        // Total de cursos y créditos
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.text('TOTAL CURSOS HOMOLOGADOS:', 110, yPos);
        doc.text(homologaciones.length.toString(), 170, yPos);

        yPos += 6;

        doc.text('TOTAL CRÉDITOS HOMOLOGADOS:', 110, yPos);
        doc.text(document.getElementById('total-creditos').textContent, 170, yPos);

        yPos += 15;

        // Artículo Segundo
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 2°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 6;

        const textoArticuloSegundo = "Definir los cursos pendientes por cursar y aprobar en la Corporación Universitaria Autónoma del Cauca.";
        const lineasArticulo2 = doc.splitTextToSize(textoArticuloSegundo, 175);
        doc.text(lineasArticulo2, 20, yPos);

        yPos += lineasArticulo2.length * 5 + 10;

        // Si los párrafos no caben, añadir nueva página
        if (yPos > 220) {
            doc.addPage();
            yPos = 20;
        }

        // Artículo Tercero
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 3°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 6;

        const textoArticuloTercero = `Autorizar matrícula para el segundo periodo académico de ${año}.`;
        const lineasArticulo3 = doc.splitTextToSize(textoArticuloTercero, 175);
        doc.text(lineasArticulo3, 20, yPos);

        yPos += lineasArticulo3.length * 5 + 15;

        // Párrafo legal 1
        doc.setFont('helvetica', 'bold');
        doc.text('Parágrafo 1.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        const textoParrafo1 = `Para legalizar el proceso de matrícula tanto académica como financiera, deberá cancelar los derechos pecuniarios correspondientes antes del 28 de ${mes} de ${año}.`;
        const lineasParrafo1 = doc.splitTextToSize(textoParrafo1, 160);
        doc.text(lineasParrafo1, 45, yPos);

        yPos += lineasParrafo1.length * 5 + 5;

        // Párrafo legal 2
        doc.setFont('helvetica', 'bold');
        doc.text('Parágrafo 2.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        const textoParrafo2 = `El aspirante/estudiante tendrá derecho a solicitar la revisión del estudio, para lo cual tendrá un plazo máximo de ocho días siguientes a su notificación, siempre y cuando esta revisión se refiera a la documentación entregada inicialmente. Cuando el aspirante/estudiante desee incorporar nuevos contenidos, se debe proceder a solicitar y realizar un nuevo estudio de homologación.`;
        const lineasParrafo2 = doc.splitTextToSize(textoParrafo2, 160);
        doc.text(lineasParrafo2, 45, yPos);

        yPos += lineasParrafo2.length * 5 + 10;

        // Artículo Cuarto
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 4°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        const textoArticuloCuarto = "La presente resolución rige a partir de la fecha de su expedición.";
        doc.text(textoArticuloCuarto, 45, yPos);

        yPos += 15;

        // Si las firmas no caben, añadir nueva página
        if (yPos > 220) {
            doc.addPage();
            yPos = 20;
        }

        // Comuníquese y cúmplase
        doc.setFont('helvetica', 'bold');
        doc.text('NOTIFÍQUESE Y CÚMPLASE', 105, yPos, { align: 'center' });

        yPos += 10;

        doc.setFont('helvetica', 'normal');
        doc.text(`Popayán, ${dia} ${mes.toUpperCase().substring(0, 3)}. ${año}`, 105, yPos, { align: 'center' });

        yPos += 25;

        // Sección de firmas - posiciones fijas
        const yPosFirmas = yPos;
        const espacioFirma = 70; // Espacio horizontal entre firmas

        // Asegurarse de que las firmas queden en la misma página
        if (yPos > 220) {
            doc.addPage();
            yPos = 40;
        }

        // Firmas - siempre en posiciones fijas
        if (window.firmaVicerrectorData && esVersionFinal) {
            try {
                doc.addImage(window.firmaVicerrectorData, 'PNG', (105 + espacioFirma / 2) - 25, yPos - 15, 50, 20);
            } catch (error) {
                console.log('Error al agregar firma del vicerrector al PDF:', error);
            }
        }

        if (window.firmaCoordinadorData) {
            try {
                doc.addImage(window.firmaCoordinadorData, 'PNG', (105 - espacioFirma / 2) - 25, yPos - 15, 50, 20);
            } catch (error) {
                console.log('Error al agregar firma del coordinador al PDF:', error);
            }
        }

        // Líneas para firmas
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.3);
        doc.line(105 - espacioFirma / 2 - 35, yPos + 10, 105 - espacioFirma / 2 + 35, yPos + 10); // Línea izquierda
        doc.line(105 + espacioFirma / 2 - 35, yPos + 10, 105 + espacioFirma / 2 + 35, yPos + 10); // Línea derecha

        // Nombres y cargos
        doc.setFont('helvetica', 'bold');
        doc.text('JUAN PABLO DIAGO RODRÍGUEZ', 105 - espacioFirma / 2, yPos + 20, { align: 'center' });
        doc.text('ISABEL RAMIREZ MEJIA', 105 + espacioFirma / 2, yPos + 20, { align: 'center' });

        yPos += 25;

        doc.setFont('helvetica', 'normal');
        doc.text(`Decano Facultad ${programa}`, 105 - espacioFirma / 2, yPos, { align: 'center' });
        doc.text('Vicerrectora Académica', 105 + espacioFirma / 2, yPos, { align: 'center' });

        yPos += 15;

        // Sección de notificación
        doc.text(`Notificado (a):`, 20, yPos);
        yPos += 7;

        doc.setFont('helvetica', 'bold');
        doc.text(`${estudiante.toUpperCase()}`, 20, yPos);
        yPos += 7;

        doc.setFont('helvetica', 'normal');
        doc.text(`${identificacion}`, 20, yPos);
        yPos += 7;

        doc.text(`Fecha de notificación: ${dia}-${mes.substring(0, 3)}-${año}`, 20, yPos);

        // Sección de copias
        yPos += 15;
        doc.setFontSize(8);
        doc.text('Copia:', 20, yPos);
        yPos += 5;
        doc.text('Vicerrectoría Académica', 30, yPos);
        yPos += 5;
        doc.text('Oficina de Admisiones', 30, yPos);
        yPos += 5;
        doc.text('Oficina de Control y Registro (Hoja de Vida estudiante)', 30, yPos);
        yPos += 5;
        doc.text('Oficina de Archivo', 30, yPos);

        // Pie de página para todas las páginas
        const totalPages = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);

            // Línea de separación para pie de página
            doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
            doc.setLineWidth(0.5);
            doc.line(20, 260, 190, 260);

            doc.setFontSize(7); // Reducir tamaño para el pie de página
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(50, 50, 50); // Gris más oscuro para mejor legibilidad

            // Datos de contacto
            doc.text('Lic. De Funcionamiento: 12321/79. Resolución MEN Nº. 677 de 2023. Código SNIES: 2849', 105, 265, { align: 'center' });
            doc.text('Sede principal – Calle 5 Nº 3 – 85 Centro.', 105, 269, { align: 'center' });
            doc.text('PBX: 602 8222295 – WhatsApp 314 639 54 95 – 320 675 04 64 A.A. 043 Popayán - Cauca - Colombia.', 105, 273, { align: 'center' });
            doc.text('www.uniautonoma.edu.co - Email: recepcion@uniautonoma.edu.co', 105, 277, { align: 'center' });

            // Número de página
            doc.setFontSize(8);
            doc.setFont('helvetica', 'bold');
            doc.text(`Página ${i} de ${totalPages}`, 185, 277, { align: 'right' });

            // Restaurar color
            doc.setTextColor(0, 0, 0);
        }

        // Mostrar preview con mejor tamaño
        const pdfPreview = document.getElementById('pdf-preview-content');
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '600px'; // Mayor altura para mejor visualización
        iframe.style.border = '1px solid #ddd';
        iframe.style.borderRadius = '4px';
        iframe.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
        iframe.src = doc.output('datauristring');
        pdfPreview.innerHTML = '';
        pdfPreview.appendChild(iframe);

        // Mostrar modal
        $('#pdf-preview-modal').modal('show');

        // Configurar botón de confirmar
        document.getElementById('btn-confirmar-pdf').onclick = function () {
            // Nombre de archivo con identificador de version final o coordinador
            const prefijo = esVersionFinal ? 'Homologacion_Final' : 'Homologacion_Coordinador';
            const nombreArchivo = `${prefijo}_${estudiante.replace(/\s+/g, '_')}_${identificacion}.pdf`;
            doc.save(nombreArchivo);
            $('#pdf-preview-modal').modal('hide');

            // Si es la versión del coordinador, mostrar mensaje de éxito
            if (!esVersionFinal) {
                mostrarAlerta('Documento generado y listo para revisión del Vicerrector', 'success');
            } else {
                mostrarAlerta('Documento final generado correctamente', 'success');
            }
        };
    } catch (error) {
        console.error('Error al generar PDF:', error);
        mostrarAlerta('Error al generar PDF: ' + error.message, 'danger');
    }
}
// Función auxiliar para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) return;

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    alertContainer.innerHTML = '';
    alertContainer.appendChild(alertDiv);

    // Auto cerrar después de 5 segundos
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertContainer.innerHTML = '', 150);
    }, 5000);
}

function abrirModalEditarHomologacion(index) {
    const homologacion = homologaciones[index];
    abrirModalAgregarHomologacion();
    document.querySelector('#modal-titulo').textContent = 'Editar Homologación';
    document.querySelector('#homologacion-index').value = index;

    // Llenar campos con datos de la homologación
    document.getElementById('asignatura-origen').value = homologacion.asignatura_origen_id;
    document.getElementById('asignatura-destino').value = homologacion.asignatura_destino_id;
    document.getElementById('nota-origen').value = homologacion.nota_origen;
    document.getElementById('nota-homologada').value = homologacion.nota_destino;
    document.getElementById('creditos-homologados').value = homologacion.creditos;
    document.getElementById('observacion').value = homologacion.comentarios || '';
}

function limpiarHomologaciones() {
    if (confirm('¿Está seguro de limpiar todas las homologaciones?')) {
        homologaciones = [];
        renderizarTablaHomologaciones();
        guardarHomologaciones();
    }
}

function desactivarControles() {
    document.querySelectorAll('input, button, select').forEach(element => {
        element.disabled = true;
    });
    document.getElementById('btn-generar-pdf').disabled = false;
}

function actualizarEstadoUI() {
    const estadoBadge = document.getElementById('estado-solicitud');
    if (estadoBadge) {
        const estado = estadoBadge.textContent.trim();
        if (estado === 'Aprobada' || estado === 'Cerrada' || estado === 'Finalizada') {
            desactivarControles();
        }
    }
}

function actualizarNotasDesdeInputs() {
    document.querySelectorAll('.nota-input').forEach(input => {
        const id = input.dataset.index;
        const valor = parseFloat(input.value);

        if (!isNaN(valor)) {
            homologaciones[id].nota_destino = valor.toFixed(1);
        }
    });
}

function obtenerInfoAsignatura(tipo, id) {
    // Buscar la asignatura en los arreglos
    let asignatura = null;
    if (tipo === 'origen') {
        asignatura = asignaturasOrigen.find(a => a.id_asignatura === id || a.id === id);
    } else {
        asignatura = asignaturasDestino.find(a => a.id_asignatura === id || a.id === id);
    }

    if (asignatura) {
        mostrarInfoAsignatura(asignatura, tipo);
    }
}

function mostrarInfoAsignatura(asignatura, tipo) {
    // Llenar el modal con información
    document.getElementById('modalInfoAsignaturaTitle').textContent =
        `Información de Asignatura (${tipo === 'origen' ? 'Origen' : 'Destino'})`;

    document.getElementById('infoNombre').textContent = asignatura.nombre || 'N/A';
    document.getElementById('infoCodigo').textContent = asignatura.codigo || 'N/A';
    document.getElementById('infoSemestre').textContent = asignatura.semestre || 'N/A';
    document.getElementById('infoCreditos').textContent = asignatura.creditos || 'N/A';

    // Mostrar nota solo si es de origen
    const infoNota = document.getElementById('infoNota');
    if (tipo === 'origen' && asignatura.nota_origen) {
        infoNota.style.display = 'block';
        document.getElementById('infoNotaValue').textContent = asignatura.nota_origen;
    } else {
        infoNota.style.display = 'none';
    }

    $('#modalInfoAsignatura').modal('show');
}

function mostrarAlerta(mensaje, tipo = 'info') {
    const modalBody = document.getElementById('alertModalMessage');
    const modal = new bootstrap.Modal(document.getElementById('alertModal'));

    // Aplica color de fondo según el tipo
    let bgClass = 'bg-light text-dark';
    if (tipo === 'success') bgClass = 'bg-success text-white';
    else if (tipo === 'danger') bgClass = 'bg-danger text-white';
    else if (tipo === 'warning') bgClass = 'bg-warning text-dark';
    else if (tipo === 'info') bgClass = 'bg-info text-white';

    // Establece el contenido y estilos
    modalBody.innerHTML = mensaje;
    modalBody.className = `modal-body ${bgClass}`;

    // Muestra el modal
    modal.show();

    // Cierra automáticamente después de 5 segundos
    setTimeout(() => {
        modal.hide();
    }, 5000);
}


function cerrarHomologacion() {
    if (homologaciones.length === 0) {
        mostrarAlerta('No hay homologaciones para cerrar', 'warning');
        return;
    }

    if (!confirm('¿Está seguro de cerrar este proceso de homologación? Esta acción no se puede deshacer.')) {
        return;
    }

    guardarHomologaciones(true, function (exitoGuardado) {
        if (exitoGuardado) {
            // Actualizar estado de la solicitud
            fetch(`${API_BASE_URL}/solicitudes/${solicitudId}/estado`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    estado: 'Aprobada',
                    homologaciones: homologaciones
                })
            })
                .then(response => response.json())
                .then(data => {
                    mostrarAlerta('Homologación cerrada exitosamente', 'success');
                    desactivarControles();

                    // Actualizar badge de estado
                    const estadoBadge = document.getElementById('estado-solicitud');
                    if (estadoBadge) {
                        estadoBadge.className = 'badge badge-success';
                        estadoBadge.textContent = 'Aprobada';
                    }
                })
                .catch(error => {
                    console.error('Error al cerrar:', error);
                    mostrarAlerta('Error al cerrar homologación', 'danger');
                });
        }
    });
}

// Función utilitaria para debug
function mostrarEstadoActual() {
    console.log('Estado actual del sistema:');
    console.log('Asignaturas Origen:', asignaturasOrigen);
    console.log('Asignaturas Destino:', asignaturasDestino);
    console.log('Asignatura Origen Seleccionada:', asignaturaSeleccionadaOrigen);
    console.log('Asignatura Destino Seleccionada:', asignaturaSeleccionadaDestino);
    console.log('Homologaciones:', homologaciones);
    console.log('Homologacion ID:', homologacionId);
    console.log('Solicitud ID:', solicitudId);
}
// Evento para guardar homologaciones
document.getElementById('btn-guardar-homologaciones').addEventListener('click', function () {
    const btnGuardar = this;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    guardarHomologaciones(false, function (exito) {
        if (exito) {
            //mostrarAlerta('Homologaciones guardadas exitosamente', 'success');
        } else {
            mostrarAlerta('Error al guardar las homologaciones', 'danger');
        }

        // Restaurar botón
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = '<i class="fas fa-save"></i> Guardar';
    });
});

// Evento para limpiar homologaciones
document.getElementById('btn-limpiar-homologaciones').addEventListener('click', function () {
    if (homologaciones.length === 0) {
        mostrarAlerta('No hay homologaciones para limpiar', 'info');
        return;
    }

    if (confirm('¿Está seguro de que desea eliminar todas las homologaciones?')) {
        homologaciones = [];
        renderizarTablaHomologaciones();
        guardarHomologaciones();
        mostrarAlerta('Todas las homologaciones han sido eliminadas', 'success');
    }
});

// Evento para guardar desde el botón principal
document.getElementById('btn-guardar').addEventListener('click', function () {
    const btnGuardar = this;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    guardarHomologaciones(false, function (exito) {
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar Cambios';

        if (exito) {
            mostrarAlerta('Homologaciones guardadas exitosamente', 'success');
        } else {
            mostrarAlerta('Error al guardar las homologaciones', 'danger');
        }
    });
});

// Evento para confirmar PDF
document.getElementById('btn-confirmar-pdf').addEventListener('click', function () {
    const btnConfirmarPDF = this;
    btnConfirmarPDF.disabled = true;
    btnConfirmarPDF.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Descargando...';

    // Primero guardamos los cambios
    guardarHomologaciones(true, function (exitoGuardado) {
        if (exitoGuardado) {
            // Generamos y descargamos el PDF
            try {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Obtener datos del estudiante
                let estudiante, identificacion, universidad, programa;

                try {
                    const datosEstudiante = document.querySelector('.col-md-6');
                    if (datosEstudiante) {
                        estudiante = datosEstudiante.querySelector('p:nth-child(1)')?.textContent.replace('Nombre:', '').trim() || 'N/A';
                        identificacion = datosEstudiante.querySelector('p:nth-child(2)')?.textContent.replace('Identificación:', '').trim() || 'N/A';
                        universidad = datosEstudiante.querySelector('p:nth-child(3)')?.textContent.replace('Universidad de Origen:', '').trim() || 'N/A';
                        programa = datosEstudiante.querySelector('p:nth-child(4)')?.textContent.replace('Programa de interes:', '').trim() || 'N/A';
                    }
                } catch (e) {
                    console.error('Error al obtener datos del estudiante:', e);
                    estudiante = 'N/A';
                    identificacion = 'N/A';
                    universidad = 'N/A';
                    programa = 'N/A';
                }

                // Configurar PDF (mismo código de generarPDF)
                let yPos = 20;

                // Título
                doc.setFontSize(16);
                doc.setFont(undefined, 'bold');
                doc.text('RESOLUCIÓN DE HOMOLOGACIÓN', 105, yPos, { align: 'center' });

                yPos += 20;

                // Datos del estudiante
                doc.setFontSize(12);
                doc.setFont(undefined, 'normal');
                doc.text(`Estudiante: ${estudiante}`, 20, yPos);
                yPos += 10;
                doc.text(`Identificación: ${identificacion}`, 20, yPos);
                yPos += 10;
                doc.text(`Universidad de Origen: ${universidad}`, 20, yPos);
                yPos += 10;
                doc.text(`Programa de Interés: ${programa}`, 20, yPos);
                yPos += 20;

                // Tabla de homologaciones
                doc.setFont(undefined, 'bold');
                doc.text('ASIGNATURAS HOMOLOGADAS', 20, yPos);
                yPos += 10;

                const headers = ['Asignatura Origen', 'Asignatura Destino', 'Nota', 'Créditos'];
                const data = homologaciones.map(h => [
                    h.asignatura_origen_nombre,
                    h.asignatura_destino_nombre,
                    h.nota_destino,
                    h.creditos
                ]);

                doc.autoTable({
                    startY: yPos,
                    head: [headers],
                    body: data,
                    margin: { left: 20, right: 20 },
                    styles: { fontSize: 10 }
                });

                yPos = doc.lastAutoTable.finalY + 10;

                // Total de créditos
                doc.setFont(undefined, 'bold');
                doc.text('Total de Créditos:', 130, yPos);
                doc.text(document.getElementById('total-creditos').textContent, 160, yPos);

                // Firma (si existe)
                if (firmaUploadData) {
                    yPos += 40;
                    doc.text('Coordinador', 105, yPos + 20, { align: 'center' });
                    try {
                        doc.addImage(firmaUploadData, 'JPEG', 80, yPos - 20, 50, 20);
                    } catch (error) {
                        console.log('Error al agregar firma al PDF:', error);
                    }
                }

                // Descargar PDF
                doc.save('homologacion.pdf');
                $('#pdf-preview-modal').modal('hide');
                mostrarAlerta('PDF generado y descargado exitosamente', 'success');

            } catch (error) {
                console.error('Error al generar PDF:', error);
                mostrarAlerta('Error al generar PDF: ' + error.message, 'danger');
            }
        } else {
            mostrarAlerta('Error al guardar los datos. El PDF no se ha podido generar.', 'danger');
        }

        // Restaurar botón
        btnConfirmarPDF.disabled = false;
        btnConfirmarPDF.innerHTML = '<i class="fas fa-check mr-1"></i> Confirmar y Descargar';
    });
});

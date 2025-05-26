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
    solicitudId = document.getElementById('solicitud_id')?.value;

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
    inicializarValidacionNotas();
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

    // Asegurarse de que el botón de limpiar destinos existe y agregar el evento
    const btnLimpiarDestino = document.getElementById('btn-limpiar-destino');
    if (btnLimpiarDestino) {
        btnLimpiarDestino.addEventListener('click', limpiarAsignaturasDestino);
    }

    document.getElementById('btn-confirmar-homologacion').addEventListener('click', confirmarHomologacion);
    document.getElementById('btn-cerrar-homologacion')?.addEventListener('click', cerrarHomologacion);
    document.getElementById('btn-generar-pdf')?.addEventListener('click', generarPDF);

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

/**
 * Función mejorada para cargar datos iniciales
 */
function cargarDatos() {
    console.log('Iniciando cargarDatos con estrategia mejorada...');

    // Iniciar diagnóstico de localStorage
    diagnosticoLocalStorage();

    // Estrategia exhaustiva para recuperar los IDs
    if (!solicitudId) {
        solicitudId = extraerSolicitudId();
    }

    if (!homologacionId) {
        homologacionId = cargarHomologacionId();
    }

    console.log('IDs recuperados:', {
        solicitudId: solicitudId,
        homologacionId: homologacionId
    });

    // Intentar cargar desde localStorage primero SIEMPRE
    if (solicitudId) {
        const datosLocalStorage = cargarDesdeLocalStorage();
        if (datosLocalStorage && datosLocalStorage.length > 0) {
            console.log('Usando datos de localStorage:', datosLocalStorage.length, 'homologaciones');
            homologaciones = datosLocalStorage;
            renderizarTablaHomologaciones();
            actualizarEstadoUI();

            // Si tenemos homologacionId, verificar con el servidor para mantener sincronización
            if (homologacionId) {
                sincronizarConServidor().then(actualizado => {
                    if (actualizado) {
                        console.log('Datos actualizados desde el servidor');
                    }
                });
            }

            return Promise.resolve(homologaciones);
        }
    }

    // Si ya tenemos homologaciones desde el controlador, cargarlas después de intentar localStorage
    if (Array.isArray(homologaciones) && homologaciones.length > 0) {
        console.log('Usando homologaciones precargadas desde controlador:', homologaciones.length);
        renderizarTablaHomologaciones();
        actualizarEstadoUI();

        // Guardar en localStorage como respaldo
        if (solicitudId) {
            try {
                // Crear una copia limpia para localStorage
                const homologacionesLimpio = homologaciones.map(h => ({
                    asignatura_origen_id: h.asignatura_origen_id,
                    asignatura_destino_id: h.asignatura_destino_id,
                    asignatura_origen_nombre: h.asignatura_origen_nombre,
                    asignatura_destino_nombre: h.asignatura_destino_nombre,
                    nota_origen: h.nota_origen,
                    nota_destino: h.nota_destino,
                    creditos: h.creditos,
                    comentarios: h.comentarios || ''
                }));

                localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologacionesLimpio));
                localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId || '');
                localStorage.setItem('ultimaSolicitudId', solicitudId);
            } catch (e) {
                console.error('Error al guardar en localStorage:', e);
            }
        }

        return Promise.resolve(homologaciones);
    }

    // Si tenemos homologacionId, cargar del servidor
    if (homologacionId) {
        console.log('Cargando homologación del servidor con ID:', homologacionId);

        // Mostrar indicador de carga
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'text-center my-4';
        loadingIndicator.id = 'loading-indicator';
        loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Cargando homologaciones...</p>';
        document.querySelector('.container')?.appendChild(loadingIndicator);

        // Normalizar el ID para la API
        const apiHomologacionId = normalizarHomologacionId(homologacionId);

        return fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`)
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
                    if (Array.isArray(data.datos.asignaturas_origen)) {
                        asignaturasOrigen = data.datos.asignaturas_origen;
                    }

                    if (Array.isArray(data.datos.asignaturas_destino)) {
                        asignaturasDestino = data.datos.asignaturas_destino;
                    }

                    if (Array.isArray(data.datos.homologaciones)) {
                        homologaciones = data.datos.homologaciones;
                    } else {
                        // Si no es array, inicializarlo como vacío
                        homologaciones = [];
                    }

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
                            // Crear una copia limpia para localStorage
                            const homologacionesLimpio = homologaciones.map(h => ({
                                asignatura_origen_id: h.asignatura_origen_id,
                                asignatura_destino_id: h.asignatura_destino_id,
                                asignatura_origen_nombre: h.asignatura_origen_nombre,
                                asignatura_destino_nombre: h.asignatura_destino_nombre,
                                nota_origen: h.nota_origen,
                                nota_destino: h.nota_destino,
                                creditos: h.creditos,
                                comentarios: h.comentarios || ''
                            }));

                            localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologacionesLimpio));
                            localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId || '');
                            localStorage.setItem('ultimaSolicitudId', solicitudId);

                            // Verificar almacenamiento
                            const guardado = localStorage.getItem(`homologaciones_${solicitudId}`);
                            if (!guardado) {
                                console.error('Verificación de almacenamiento fallida después de cargar del servidor');
                            }
                        } catch (e) {
                            console.error('Error al guardar en localStorage:', e);
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

                // Intentar cargar desde localStorage nuevamente como respaldo
                const datosLocales = cargarDesdeLocalStorage();
                if (datosLocales && datosLocales.length > 0) {
                    homologaciones = datosLocales;
                    renderizarTablaHomologaciones();
                    actualizarEstadoUI();
                    return homologaciones;
                } else {
                    // Si no hay datos locales, inicializar vacío
                    homologaciones = [];
                    renderizarTablaHomologaciones();
                    actualizarEstadoUI();
                    return homologaciones;
                }
            })
            .finally(() => {
                // Eliminar indicador de carga
                const indicator = document.getElementById('loading-indicator');
                if (indicator && indicator.parentNode) {
                    indicator.parentNode.removeChild(indicator);
                }
            });
    }

    // Si tenemos solicitudId pero no homologacionId
    if (solicitudId) {
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
                        try {
                            localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);
                        } catch (e) {
                            console.error('Error al guardar homologacionId en localStorage:', e);
                        }

                        // Ahora que tenemos el homologacionId, cargar los datos de homologación
                        return cargarDatos();
                    } else {
                        console.warn('La solicitud no tiene homologación asociada');
                        mostrarAlerta('La solicitud no tiene homologación asociada. Se creará una nueva al guardar.', 'info');
                        // Inicializar homologaciones como array vacío
                        homologaciones = [];
                        renderizarTablaHomologaciones();
                        actualizarEstadoUI();
                        return homologaciones;
                    }
                } else {
                    console.warn('El servidor no devolvió datos válidos de solicitud');
                    throw new Error('No se encontraron datos de solicitud');
                }
            })
            .catch(error => {
                console.error('Error al cargar datos de solicitud:', error);
                mostrarAlerta('Error al cargar datos de solicitud', 'danger');
                // Inicializar homologaciones como array vacío
                homologaciones = [];
                renderizarTablaHomologaciones();
                actualizarEstadoUI();
                return homologaciones;
            });
    }

    // Si no tenemos ni solicitudId ni homologacionId, no podemos hacer nada
    mostrarAlerta('Error: No se pudo obtener el ID de solicitud o de homologación', 'danger');
    homologaciones = [];
    renderizarTablaHomologaciones();
    actualizarEstadoUI();
    return Promise.reject('No se pudieron obtener los IDs necesarios');
}

/**
 * Función mejorada para cargar datos desde localStorage
 */
function cargarDesdeLocalStorage() {
    if (!solicitudId) {
        // Intentar obtener la última solicitud utilizada
        solicitudId = localStorage.getItem('ultimaSolicitudId');
        if (!solicitudId) {
            console.warn('No se encontró ID de solicitud para cargar desde localStorage');
            return [];
        }
    }

    try {
        const datosGuardados = localStorage.getItem(`homologaciones_${solicitudId}`);
        const idGuardado = localStorage.getItem(`homologacionId_${solicitudId}`);

        console.log('Intentando cargar datos para solicitud ID:', solicitudId);
        console.log('Datos encontrados en localStorage:', datosGuardados ? 'Sí' : 'No');

        if (datosGuardados) {
            try {
                const datosParseados = JSON.parse(datosGuardados);
                if (Array.isArray(datosParseados)) {
                    console.log('Cargadas homologaciones desde localStorage:', datosParseados.length);

                    if (idGuardado && !homologacionId) {
                        homologacionId = idGuardado;
                        console.log('ID de homologación recuperado de localStorage:', homologacionId);

                        // Actualizar el campo oculto
                        const inputHomologacionId = document.getElementById('homologacion_id');
                        if (inputHomologacionId) {
                            inputHomologacionId.value = homologacionId;
                        }
                    }

                    return datosParseados;
                } else {
                    console.warn('Los datos recuperados no son un array:', datosParseados);
                    return [];
                }
            } catch (error) {
                console.error('Error al parsear datos de localStorage:', error);
                // Si hay error de parseo, intentar limpiar los datos corruptos
                localStorage.removeItem(`homologaciones_${solicitudId}`);
                return [];
            }
        } else {
            console.log('No se encontraron datos en localStorage para solicitudId:', solicitudId);
        }
    } catch (e) {
        console.error('Error al cargar desde localStorage:', e);
    }

    return [];
}

/**
 * Función para verificar si hay suficiente espacio de almacenamiento
 */
function verificarAlmacenamientoDisponible() {
    try {
        // Intentar almacenar un valor de prueba para verificar si localStorage funciona
        localStorage.setItem('test_storage', '1');
        localStorage.removeItem('test_storage');

        // Verificar espacio disponible si el navegador lo soporta
        if (navigator.storage && navigator.storage.estimate) {
            navigator.storage.estimate().then(estimate => {
                const percentUsed = (estimate.usage / estimate.quota) * 100;
                console.log(`Almacenamiento utilizado: ${percentUsed.toFixed(2)}%`);

                // Advertir si queda poco espacio
                if (percentUsed > 90) {
                    console.warn('Queda poco espacio de almacenamiento');
                    mostrarAlerta('Advertencia: Queda poco espacio de almacenamiento local. Podría afectar al guardado automático.', 'warning');
                }
            });
        }
    } catch (e) {
        console.error('Error al verificar almacenamiento:', e);
        mostrarAlerta('Error: No se puede acceder al almacenamiento local. El guardado automático podría no funcionar.', 'danger');
    }
}

/**
 * Función mejorada para diagnosticar el estado del localStorage
 */
function diagnosticoLocalStorage() {
    console.group('Diagnóstico de localStorage');

    try {
        // Verificar disponibilidad de localStorage
        const testKey = 'test_' + Math.random();
        localStorage.setItem(testKey, 'test');
        localStorage.removeItem(testKey);
        console.log('✓ localStorage está disponible y funcionando');

        const ultimaSolicitudId = localStorage.getItem('ultimaSolicitudId');
        console.log('Última solicitud ID:', ultimaSolicitudId);

        // Mostrar todas las claves de homologaciones
        const homologacionesKeys = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith('homologaciones_')) {
                homologacionesKeys.push(key);
            }
        }

        console.log('Claves de homologaciones:', homologacionesKeys);

        // Mostrar información de la solicitud actual
        if (solicitudId) {
            const homologacionesKey = `homologaciones_${solicitudId}`;
            const homologacionIdKey = `homologacionId_${solicitudId}`;

            console.log('Clave actual:', homologacionesKey);
            console.log('Datos encontrados:', localStorage.getItem(homologacionesKey) ? 'Sí' : 'No');
            console.log('ID de homologación guardado:', localStorage.getItem(homologacionIdKey));

            try {
                const datos = JSON.parse(localStorage.getItem(homologacionesKey) || '[]');
                console.log('Cantidad de homologaciones en localStorage:', datos.length);

                // Verificar integridad de los datos
                const datosValidos = datos.every(h =>
                    h && typeof h === 'object' &&
                    (h.asignatura_origen_id || h.asignatura_origen_id === null)
                );

                console.log('Integridad de datos:', datosValidos ? '✓ OK' : '❌ Problemas detectados');

                if (!datosValidos) {
                    console.warn('Se detectaron problemas en los datos almacenados. Considere limpiar el caché.');
                }
            } catch (e) {
                console.error('Error al parsear datos:', e);
            }
        } else {
            console.warn('No hay solicitudId definido para buscar en localStorage');
        }

        // Comprobar tamaño total aproximado
        let totalSize = 0;
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key) {
                const value = localStorage.getItem(key) || '';
                totalSize += (key.length + value.length) * 2; // Aproximación en bytes para strings UTF-16
            }
        }

        console.log(`Tamaño total aproximado: ${(totalSize / 1024).toFixed(2)} KB`);

    } catch (e) {
        console.error('Error durante el diagnóstico de localStorage:', e);
    }

    console.groupEnd();
}
/**
 * Actualiza la interfaz según el estado actual de los datos
 */
function actualizarEstadoUI() {
    // Esta función podría cambiar estado de botones, mostrar/ocultar elementos, etc.
    // según el estado actual de homologaciones
    const hayHomologaciones = homologaciones.length > 0;

    // Actualizar botones según corresponda
    const btnLimpiarHomologaciones = document.getElementById('btn-limpiar-homologaciones');
    const btnLimpiarDestino = document.getElementById('btn-limpiar-destino');
    const btnGuardarHomologaciones = document.getElementById('btn-guardar-homologaciones');

    if (btnLimpiarHomologaciones) {
        btnLimpiarHomologaciones.disabled = !hayHomologaciones;
    }

    if (btnLimpiarDestino) {
        btnLimpiarDestino.disabled = !hayHomologaciones;
    }

    if (btnGuardarHomologaciones) {
        btnGuardarHomologaciones.disabled = !hayHomologaciones && !homologacionId;
    }

    // Actualizar el ID de homologación visible en la interfaz si existe
    const idHomologacionElement = document.getElementById('id-homologacion');
    if (idHomologacionElement && homologacionId) {
        idHomologacionElement.textContent = homologacionId;
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

/**
 * Carga el ID de homologación desde el input hidden o la URL
 * @returns {string|null} El ID de homologación o null si no se encuentra
 */
function cargarHomologacionId() {
    // Intenta obtener el ID desde el elemento hidden en el DOM
    let id = document.getElementById('homologacion_id')?.value;

    // Si no existe en el DOM, intenta obtenerlo de una variable global
    if (!id && typeof homologacionId !== 'undefined' && homologacionId) {
        id = homologacionId;
    }

    // Verificar variable global de Blade
    if (!id && window._homologacionId) {
        id = window._homologacionId;
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
    if (fila) {
        if (tipo === 'origen') {
            fila.classList.add('table-primary');
        } else {
            fila.classList.add('table-success');
        }
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
        if (botonesAccion) {
            botonesAccion.parentNode.insertBefore(panelResumen, botonesAccion);
        } else {
            // Si no encontramos los botones, intentar con otra ubicación
            const cardBody = document.querySelector('.card-body');
            if (cardBody) {
                cardBody.appendChild(panelResumen);
            }
        }
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
                    <small class="d-block">Código: ${asignaturaSeleccionadaDestino.codigo || asignaturaSeleccionadaDestino.codigo_asignatura || 'N/A'}</small>
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
    if (!modal) {
        console.error('No se encontró el modal para agregar homologación');
        return;
    }

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
            const notaOrigenInput = document.getElementById('nota-origen');
            if (notaOrigenInput) {
                notaOrigenInput.value = asignaturaSeleccionadaOrigen.nota_origen || asignaturaSeleccionadaOrigen.nota || '';
            }
        }
    }

    if (asignaturaSeleccionadaDestino) {
        const destinoSelect = document.getElementById('asignatura-destino');
        if (destinoSelect) {
            destinoSelect.value = asignaturaSeleccionadaDestino.id_asignatura || asignaturaSeleccionadaDestino.id;
            const creditosInput = document.getElementById('creditos-homologados');
            if (creditosInput) {
                creditosInput.value = asignaturaSeleccionadaDestino.creditos || '';
            }
        }
    }

    // Inicializar nota homologada por defecto si está vacía
    const notaHomologadaInput = document.getElementById('nota-homologada');
    if (notaHomologadaInput && notaHomologadaInput.value === '') {
        notaHomologadaInput.value = '3.0';
    }

    $('#modal-agregar-homologacion').modal('show');
}

function abrirModalEditarHomologacion(index) {
    const homologacion = homologaciones[index];
    if (!homologacion) {
        console.error('No se encontró la homologación con índice', index);
        return;
    }

    abrirModalAgregarHomologacion();
    document.querySelector('#modal-titulo').textContent = 'Editar Homologación';
    document.querySelector('#homologacion-index').value = index;

    // Llenar campos con datos de la homologación
    const fields = {
        'asignatura-origen': homologacion.asignatura_origen_id,
        'asignatura-destino': homologacion.asignatura_destino_id,
        'nota-origen': homologacion.nota_origen,
        'nota-homologada': homologacion.nota_destino || '3.0',
        'creditos-homologados': homologacion.creditos,
        'observacion': homologacion.comentarios || ''
    };

    for (const [id, value] of Object.entries(fields)) {
        const element = document.getElementById(id);
        if (element) element.value = value;
    }
}

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
    if (!form || !form.checkValidity()) {
        form?.reportValidity();
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
    mostrarAlerta('Homologación agregada. Recuerda guardar los cambios', 'info');
}
/**
 * Función para renderizar la tabla de homologaciones con mejor manejo de errores
 */
/**
 * Función para renderizar la tabla de homologaciones con mejor manejo de errores
 */
function renderizarTablaHomologaciones() {
    const tbody = document.getElementById('homologaciones-body');

    if (!tbody) {
        console.error('No se encontró el elemento homologaciones-body');
        return;
    }

    // Verificar que homologaciones sea un array
    if (!Array.isArray(homologaciones)) {
        console.error('Error: homologaciones no es un array', homologaciones);
        homologaciones = [];
    }

    console.log('Renderizando tabla con', homologaciones.length, 'homologaciones');

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

    // Limpiar tabla existente para evitar duplicados
    tbody.innerHTML = '';
    let totalCreditos = 0;

    homologaciones.forEach((h, index) => {
        // Verificar que la homologación es un objeto válido
        if (!h || typeof h !== 'object') {
            console.warn('Homologación inválida en índice', index, h);
            return;
        }

        const row = document.createElement('tr');

        // Asegurar que tenemos valores predeterminados para evitar errores
        const creditos = parseInt(h.creditos || 0);
        totalCreditos += isNaN(creditos) ? 0 : creditos;

        // Asegurar que la nota_destino esté en el rango válido
        if (h.nota_destino) {
            h.nota_destino = validarNota(h.nota_destino);
        } else {
            h.nota_destino = '3.0'; // Valor por defecto
        }

        row.innerHTML = `
           <td>${h.asignatura_origen_nombre || 'Sin nombre'}</td>
           <td>${h.asignatura_destino_nombre || 'Sin nombre'}</td>
           <td class="text-center">${h.nota_origen || '—'}</td>
           <td>
               <input type="number"
                      class="form-control form-control-sm nota-input"
                      value="${h.nota_destino}"
                      min="3.0" max="5.0" step="0.1"
                      data-index="${index}">
           </td>
           <td class="text-center">${h.creditos || '—'}</td>
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
            const index = parseInt(this.dataset.index);
            if (isNaN(index) || index < 0 || index >= homologaciones.length) return;

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
                const index = parseInt(this.dataset.index);
                if (!isNaN(index) && index >= 0 && index < homologaciones.length) {
                    homologaciones[index].nota_destino = 3.0;
                    guardarSilencioso();
                }
            }
        });
    });

    // **AGREGAR EVENT LISTENERS PARA LOS BOTONES DE EDITAR Y ELIMINAR**
    // Event listener para botones de editar
    document.querySelectorAll('.btn-editar-homologacion').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            if (!isNaN(index) && index >= 0 && index < homologaciones.length) {
                editarHomologacion(index);
            }
        });
    });

    // Event listener para botones de eliminar
    document.querySelectorAll('.btn-eliminar-homologacion').forEach(button => {
        button.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            if (!isNaN(index) && index >= 0 && index < homologaciones.length) {
                eliminarHomologacion(index);
            }
        });
    });

    // Actualizar el estado de la UI después de renderizar
    actualizarEstadoUI();
}

// Función para eliminar una homologación individual
function eliminarHomologacion(index) {
    if (index < 0 || index >= homologaciones.length) {
        console.error('Índice de homologación inválido:', index);
        return;
    }

    const homologacion = homologaciones[index];
    const nombreAsignatura = homologacion.asignatura_origen_nombre || 'Sin nombre';

    // Mostrar confirmación
    if (confirm(`¿Está seguro de que desea eliminar la homologación de "${nombreAsignatura}"?`)) {
        // Eliminar la homologación del array
        homologaciones.splice(index, 1);

        // Volver a renderizar la tabla
        renderizarTablaHomologaciones();

        // Guardar cambios automáticamente
        guardarSilencioso();

        console.log('Homologación eliminada exitosamente');
    }
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
function actualizarNotasDesdeInputs() {
    document.querySelectorAll('.nota-input').forEach(input => {
        const index = parseInt(input.dataset.index);
        if (isNaN(index) || index < 0 || index >= homologaciones.length) return;

        // Obtener y validar el valor
        let valor = input.value.trim();
        if (valor === '') valor = '3.0'; // Valor por defecto

        // Convertir a número y validar rango
        let notaNumero = parseFloat(valor);
        if (isNaN(notaNumero)) notaNumero = 3.0;
        if (notaNumero < 3.0) notaNumero = 3.0;
        if (notaNumero > 5.0) notaNumero = 5.0;

        // Formatear con un decimal
        const notaFormateada = notaNumero.toFixed(1);

        // Actualizar tanto el input como el objeto
        input.value = notaFormateada;
        homologaciones[index].nota_destino = notaFormateada;
    });
}

/**
 * Función para limpiar las asignaturas destino de la homologación actual
 * Mantiene las asignaturas origen en la API pero elimina destinos, notas y comentarios
 */
function limpiarAsignaturasDestino() {
    // Verificar que tenemos datos para limpiar
    if (!homologaciones || homologaciones.length === 0) {
        mostrarAlerta('No hay asignaturas para limpiar', 'info');
        return Promise.resolve(false);
    }

    // Mostrar confirmación con mensaje claro
    if (!confirm('¿Está seguro de eliminar todas las homologaciones de destino? Esta acción no se puede deshacer.')) {
        return Promise.resolve(false);
    }

    // Mostrar indicador de carga
    const btnLimpiar = document.getElementById('btn-limpiar-destino');
    const textoOriginal = btnLimpiar ? btnLimpiar.innerHTML : '';
    if (btnLimpiar) {
        btnLimpiar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Limpiando...';
        btnLimpiar.disabled = true;
    }

    // Guardar solo las asignaturas origen para mantenerlas
    const asignaturasOrigenOriginal = [];

    // Extraer solo las asignaturas origen antes de vaciar el array de homologaciones
    if (Array.isArray(homologaciones)) {
        homologaciones.forEach(h => {
            if (h && h.asignatura_origen_id && h.asignatura_origen_nombre) {
                asignaturasOrigenOriginal.push({
                    id: h.asignatura_origen_id,
                    nombre: h.asignatura_origen_nombre,
                    nota: h.nota_origen
                });
            }
        });
    }

    // Vaciar el array de homologaciones local
    homologaciones = [];

    // Actualizar la visualización inmediatamente
    renderizarTablaHomologaciones();

    // Si no tenemos ID de homologación, solo limpiar localmente
    if (!homologacionId || !solicitudId) {
        if (btnLimpiar) {
            btnLimpiar.innerHTML = textoOriginal;
            btnLimpiar.disabled = false;
        }

        // Guardar el array vacío en localStorage
        if (solicitudId) {
            try {
                localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify([]));
                console.log('Homologaciones vacías guardadas en localStorage');
            } catch (e) {
                console.error('Error al guardar en localStorage:', e);
            }
        }

        mostrarAlerta('Homologaciones de destino eliminadas. Recuerda guardar para sincronizar con el servidor.', 'warning');
        return Promise.resolve(true);
    }

    // Normalizar el ID para la API
    const apiHomologacionId = normalizarHomologacionId(homologacionId);

    // Comprobar si el ID es válido
    if (!apiHomologacionId) {
        console.error('ID de homologación no válido después de normalizar:', homologacionId);
        if (btnLimpiar) {
            btnLimpiar.innerHTML = textoOriginal;
            btnLimpiar.disabled = false;
        }
        mostrarAlerta('Error: ID de homologación no válido.', 'danger');
        return Promise.reject('ID de homologación no válido');
    }

    console.log('ID de homologación normalizado:', apiHomologacionId);
    console.log('Asignaturas origen preservadas:', asignaturasOrigenOriginal);

    // Preparar los datos para enviar a la API
    // IMPORTANTE: La API espera un array de homologaciones donde solo se conservan los datos de origen
    const homologacionesModificadas = asignaturasOrigenOriginal.map(origen => {
        return {
            asignatura_origen_id: origen.id,
            nota_origen: origen.nota,
            // Campos destino que serán nulos
            asignatura_destino_id: null,
            nota_destino: null,
            creditos: null,
            comentarios: null
        };
    });

    const datosHomologacion = {
        homologaciones: homologacionesModificadas // El backend espera exactamente este campo
    };

    console.log('Enviando solicitud para eliminar homologaciones de destino:', datosHomologacion);

    // Verificar la URL antes de enviar
    const url = `${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`;
    console.log('URL para actualizar homologaciones:', url);

    return fetch(url, {
        method: 'PUT',
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
                    console.error('Respuesta de error completa:', text);
                    throw new Error(`Error del servidor: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor tras eliminar homologaciones de destino:', data);

            // Verificar la respuesta del servidor
            if (data.datos && Array.isArray(data.datos.homologaciones)) {
                // Actualizar homologaciones con lo que devuelve el servidor
                homologaciones = data.datos.homologaciones;
                renderizarTablaHomologaciones();

                // Guardar en localStorage
                if (solicitudId) {
                    try {
                        localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologaciones));
                        console.log('Homologaciones actualizadas guardadas en localStorage');
                    } catch (e) {
                        console.error('Error al guardar en localStorage:', e);
                    }
                }

                mostrarAlerta('Homologaciones de destino eliminadas correctamente', 'success');
            } else {
                mostrarAlerta('Homologaciones de destino eliminadas. La respuesta del servidor no incluyó los datos actualizados.', 'info');

                // Mantener el array vacío localmente
                homologaciones = [];
                renderizarTablaHomologaciones();

                if (solicitudId) {
                    try {
                        localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify([]));
                        console.log('Array vacío guardado en localStorage después de la eliminación');
                    } catch (e) {
                        console.error('Error al guardar en localStorage:', e);
                    }
                }
            }

            return true;
        })
        .catch(error => {
            console.error('Error al eliminar homologaciones de destino:', error);
            mostrarAlerta(`Error: ${error.message}. Las homologaciones se han eliminado localmente.`, 'warning');

            // Mantener el array vacío localmente a pesar del error
            homologaciones = [];
            renderizarTablaHomologaciones();

            if (solicitudId) {
                try {
                    localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify([]));
                    console.log('Array vacío guardado en localStorage después del error');
                } catch (e) {
                    console.error('Error al guardar en localStorage:', e);
                }
            }

            return false;
        })
        .finally(() => {
            // Restaurar botón
            if (btnLimpiar) {
                btnLimpiar.innerHTML = textoOriginal;
                btnLimpiar.disabled = false;
            }
        });
}

/**
 * Normaliza el ID de homologación para llamadas a la API
 * Corregido para manejar correctamente formatos de ID
 */
function normalizarHomologacionId(id) {
    if (!id) return null;

    // Si tiene formato HOM-XXXX, extraer solo la parte numérica
    if (typeof id === 'string' && id.includes('HOM-')) {
        const parts = id.split('-');
        if (parts.length > 1) {
            return parts[parts.length - 1];
        }
    }

    // Si el ID es numérico pero tiene ceros al inicio (como '0001'), convertirlo a número y luego a string
    if (typeof id === 'string' && /^0+\d+$/.test(id)) {
        return parseInt(id, 10).toString();
    }

    return id;
}
/**
 * Función mejorada para verificar si los cambios fueron aplicados correctamente
 */
function verificarCambiosAplicados() {
    if (!homologacionId || !solicitudId) return Promise.resolve(false);

    const apiHomologacionId = normalizarHomologacionId(homologacionId);

    console.log('Verificando cambios aplicados para homologación:', apiHomologacionId);

    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al verificar cambios: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Estado actual en servidor:', data);

            if (data.datos && Array.isArray(data.datos.homologaciones)) {
                const serverState = data.datos.homologaciones;

                // Verificar si alguna asignatura destino sigue teniendo valores
                const destinosNoLimpios = serverState.filter(h => h.asignatura_destino_id !== null);

                if (destinosNoLimpios.length > 0) {
                    console.warn('¡Atención! Algunas asignaturas destino no fueron eliminadas:', destinosNoLimpios);

                    // Intentar corregir la discrepancia
                    if (confirm('Se detectaron inconsistencias en la eliminación. ¿Desea intentar sincronizar nuevamente?')) {
                        // Forzar una nueva sincronización
                        return limpiarAsignaturasDestino();
                    }
                } else {
                    console.log('Verificación exitosa: Todas las asignaturas destino fueron eliminadas correctamente');
                }
            }
            return true;
        })
        .catch(error => {
            console.error('Error al verificar cambios:', error);
            return false;
        });
}
/**
 * Función para limpiar homologaciones con mejor manejo de localStorage
 */
function limpiarHomologaciones() {
    if (!confirm('¿Está seguro de eliminar todas las homologaciones? Esta acción no se puede deshacer.')) {
        return Promise.resolve(false);
    }

    // Mostrar indicador de carga
    const btnLimpiar = document.getElementById('btn-limpiar-homologaciones');
    const textoOriginal = btnLimpiar ? btnLimpiar.innerHTML : '';

    if (btnLimpiar) {
        btnLimpiar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Limpiando...';
        btnLimpiar.disabled = true;
    }

    // Limpiar array de homologaciones
    homologaciones = [];
    renderizarTablaHomologaciones();

    // Limpiar en localStorage
    try {
        if (solicitudId) {
            // Guardar array vacío en localStorage
            localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify([]));
        }
    } catch (e) {
        console.error('Error al limpiar localStorage:', e);
    }

    // Si no hay ID de homologación, no necesitamos contactar al servidor
    if (!homologacionId) {
        if (btnLimpiar) {
            btnLimpiar.innerHTML = textoOriginal;
            btnLimpiar.disabled = false;
        }
        mostrarAlerta('Todas las homologaciones han sido eliminadas localmente.', 'success');
        return Promise.resolve(true);
    }

    // Normalizar ID para la API
    const apiHomologacionId = normalizarHomologacionId(homologacionId);

    // Llamar a la API para eliminar en el servidor
    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
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
            console.log('Homologaciones eliminadas en el servidor:', data);
            mostrarAlerta('Todas las homologaciones han sido eliminadas correctamente', 'success');

            // Opcionalmente, limpiar también el homologacionId
            homologacionId = null;

            // Actualizar elemento en el DOM si existe
            const inputHomologacionId = document.getElementById('homologacion_id');
            if (inputHomologacionId) {
                inputHomologacionId.value = '';
            }

            // Limpiar en localStorage
            try {
                if (solicitudId) {
                    localStorage.removeItem(`homologacionId_${solicitudId}`);
                }
            } catch (e) {
                console.error('Error al limpiar homologacionId de localStorage:', e);
            }

            return true;
        })
        .catch(error => {
            console.error('Error al eliminar homologaciones en el servidor:', error);
            mostrarAlerta(`Error al eliminar en el servidor: ${error.message}. Las homologaciones se han eliminado localmente.`, 'warning');
            return false;
        })
        .finally(() => {
            // Restaurar botón
            if (btnLimpiar) {
                btnLimpiar.innerHTML = textoOriginal;
                btnLimpiar.disabled = false;
            }
        });
}
/**
 * Versión corregida de guardarHomologaciones que asegura la correcta sincronización con localStorage
 */
function guardarHomologaciones(confirmado = false, callback = () => { }) {
    console.log('Iniciando guardarHomologaciones...');

    // Asegurarnos de tener IDs necesarios y que sean válidos
    if (!solicitudId) {
        solicitudId = extraerSolicitudId();
    }

    if (!homologacionId) {
        homologacionId = cargarHomologacionId();
    }

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

    // Verificar ID de solicitud
    if (!solicitudId) {
        mostrarAlerta('Error: No se encontró el ID de solicitud', 'danger');
        if (callback) callback(false);
        return Promise.reject('ID de solicitud no encontrado');
    }

    // Mostrar indicador de carga
    const btnGuardar = document.getElementById('btn-guardar-homologaciones') || document.getElementById('btn-guardar');
    if (!btnGuardar) {
        console.error('No se encontró el botón de guardar');
        if (callback) callback(false);
        return Promise.reject('No se encontró el botón de guardar');
    }

    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    btnGuardar.disabled = true;

    // CRUCIAL: Guardar en localStorage de manera confiable, con manejo de errores
    try {
        // Verificar que tenemos datos válidos antes de guardar
        if (Array.isArray(homologaciones)) {
            console.log('Guardando en localStorage:', homologaciones.length, 'homologaciones');

            // Crear una copia limpia de las homologaciones para evitar problemas de serialización
            const homologacionesLimpio = homologaciones.map(h => ({
                asignatura_origen_id: h.asignatura_origen_id,
                asignatura_destino_id: h.asignatura_destino_id,
                asignatura_origen_nombre: h.asignatura_origen_nombre,
                asignatura_destino_nombre: h.asignatura_destino_nombre,
                nota_origen: h.nota_origen,
                nota_destino: h.nota_destino,
                creditos: h.creditos,
                comentarios: h.comentarios || ''
            }));

            localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologacionesLimpio));

            if (homologacionId) {
                localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);
            }

            localStorage.setItem('ultimaSolicitudId', solicitudId);

            // Verificar que se haya guardado correctamente
            const guardado = localStorage.getItem(`homologaciones_${solicitudId}`);
            if (!guardado) {
                console.error('No se pudo guardar en localStorage - verificación fallida');
            }
        } else {
            console.error('Error: homologaciones no es un array válido', homologaciones);
        }
    } catch (e) {
        console.error('Error crítico al guardar en localStorage:', e);
        mostrarAlerta('Error al guardar datos localmente. El navegador puede estar en modo privado o sin espacio.', 'warning');
    }

    // Si no hay homologaciones y tenemos un ID existente, confirmar eliminar todo
    if (homologaciones.length === 0 && homologacionId && !confirmado) {
        // Restaurar botón
        btnGuardar.innerHTML = textoOriginal;
        btnGuardar.disabled = false;

        if (confirm('No hay asignaturas homologadas. ¿Deseas eliminar todas las homologaciones existentes?')) {
            return limpiarHomologaciones();
        } else {
            // El usuario canceló la operación
            if (callback) callback(false);
            return Promise.reject('Operación cancelada por el usuario');
        }
    }

    // Normalizar el ID para la API
    const apiHomologacionId = normalizarHomologacionId(homologacionId);

    // Preparar datos para enviar al servidor - asegurarse de que todas las propiedades estén correctamente formateadas
    const datosHomologacion = {
        solicitud_id: solicitudId,
        homologacion_id: homologacionId || null,
        asignaturas_origen: homologaciones.map(h => h.asignatura_origen_id),
        asignaturas_destino: homologaciones.map(h => h.asignatura_destino_id || null),
        notas_destino: homologaciones.map(h => h.nota_destino || null),
        comentarios_asignaturas: homologaciones.map(h => h.comentarios || ''),
        comentarios: document.getElementById('comentarios_generales')?.value || '',
        homologaciones: homologaciones,
        confirmado: confirmado === true
    };

    console.log('Datos a enviar al servidor:', datosHomologacion);
    console.log('Estado de asignaturas destino:', homologaciones.map(h => ({
        origen: h.asignatura_origen_id,
        destino: h.asignatura_destino_id
    })));

    // Determinar URL y método basado en si tenemos homologacionId
    const esActualizacion = !!homologacionId;
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

            // Verificar específicamente si las asignaturas destino han sido procesadas correctamente
            if (data.datos && Array.isArray(data.datos.homologaciones)) {
                console.log('Verificando asignaturas destino recibidas del servidor:',
                    data.datos.homologaciones.map(h => ({
                        origen: h.asignatura_origen_id,
                        destino: h.asignatura_destino_id
                    }))
                );

                // Actualizar con los datos del servidor para garantizar consistencia
                homologaciones = data.datos.homologaciones;
                renderizarTablaHomologaciones();

                // Actualizar en localStorage con los datos más recientes del servidor
                try {
                    const homologacionesLimpio = homologaciones.map(h => ({
                        asignatura_origen_id: h.asignatura_origen_id,
                        asignatura_destino_id: h.asignatura_destino_id,
                        asignatura_origen_nombre: h.asignatura_origen_nombre,
                        asignatura_destino_nombre: h.asignatura_destino_nombre,
                        nota_origen: h.nota_origen,
                        nota_destino: h.nota_destino,
                        creditos: h.creditos,
                        comentarios: h.comentarios || ''
                    }));

                    localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologacionesLimpio));
                } catch (e) {
                    console.error('Error al actualizar localStorage después de servidor:', e);
                }
            }

            // Actualizar el ID de homologación si es una nueva
            if (data.datos && data.datos.id) {
                homologacionId = data.datos.id;
                try {
                    localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);
                } catch (e) {
                    console.error('Error al guardar homologacionId en localStorage:', e);
                }

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
            }

            // Verificar si los cambios se aplicaron correctamente
            verificarCambiosAplicados();

            actualizarEstadoUI();
            mostrarAlerta('Homologaciones guardadas correctamente', 'success');

            if (callback) callback(true);
            return data;
        })
        .catch(error => {
            console.error('Error al guardar homologaciones:', error);
            mostrarAlerta(`Error al guardar: ${error.message}. Los datos se han guardado localmente.`, 'warning');

            if (callback) callback(false);
            return null;
        })
        .finally(() => {
            // Restaurar botón
            if (btnGuardar) {
                btnGuardar.innerHTML = textoOriginal;
                btnGuardar.disabled = false;
            }
        });
}
/**
 * Función para agregar un evento de cierre de ventana que guarde los datos
 */
function inicializarGuardadoAlCerrar() {
    window.addEventListener('beforeunload', function (e) {
        // Guardar datos antes de cerrar/recargar la página
        if (solicitudId && Array.isArray(homologaciones)) {
            try {
                console.log('Guardando datos antes de cerrar/recargar la página...');

                // Crear una copia limpia para localStorage
                const homologacionesLimpio = homologaciones.map(h => ({
                    asignatura_origen_id: h.asignatura_origen_id,
                    asignatura_destino_id: h.asignatura_destino_id,
                    asignatura_origen_nombre: h.asignatura_origen_nombre,
                    asignatura_destino_nombre: h.asignatura_destino_nombre,
                    nota_origen: h.nota_origen,
                    nota_destino: h.nota_destino,
                    creditos: h.creditos,
                    comentarios: h.comentarios || ''
                }));

                localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologacionesLimpio));

                if (homologacionId) {
                    localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);
                }

                localStorage.setItem('ultimaSolicitudId', solicitudId);

                // Verificar que se guardó correctamente
                const verificacion = localStorage.getItem(`homologaciones_${solicitudId}`);
                if (!verificacion) {
                    console.error('Fallo en la verificación del guardado al cerrar');
                }
            } catch (error) {
                console.error('Error al guardar datos antes de cerrar:', error);
            }
        }
    });
}
/**
 * Versión corregida de guardarSilencioso con mejor manejo de datos locales
 */
function guardarSilencioso() {
    // Solo sincronizar si tenemos los IDs necesarios
    if (!solicitudId) {
        solicitudId = extraerSolicitudId();
        if (!solicitudId) {
            console.warn('No se pudo determinar el ID de solicitud para guardado silencioso');
            return;
        }
    }

    console.log('Sincronizando cambios silenciosamente...');

    // Actualizar notas desde los inputs
    actualizarNotasDesdeInputs();

    // Actualizar UI para reflejar los cambios actuales
    renderizarTablaHomologaciones();

    // CRÍTICO: Guardar en localStorage siempre, independientemente del ID de homologación
    try {
        // Verificar que homologaciones sea un array válido
        if (!Array.isArray(homologaciones)) {
            console.error('Error en guardarSilencioso: homologaciones no es un array', homologaciones);
            homologaciones = [];
            return;
        }

        console.log('Guardado silencioso en localStorage:', homologaciones.length, 'homologaciones');

        // Crear una copia limpia para evitar problemas de circular references
        const homologacionesLimpio = homologaciones.map(h => ({
            asignatura_origen_id: h.asignatura_origen_id,
            asignatura_destino_id: h.asignatura_destino_id,
            asignatura_origen_nombre: h.asignatura_origen_nombre,
            asignatura_destino_nombre: h.asignatura_destino_nombre,
            nota_origen: h.nota_origen,
            nota_destino: h.nota_destino,
            creditos: h.creditos,
            comentarios: h.comentarios || ''
        }));

        localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologacionesLimpio));

        if (homologacionId) {
            localStorage.setItem(`homologacionId_${solicitudId}`, homologacionId);
        }

        localStorage.setItem('ultimaSolicitudId', solicitudId);

        // Verificar almacenamiento
        const guardado = localStorage.getItem(`homologaciones_${solicitudId}`);
        if (!guardado) {
            console.error('Verificación de guardado silencioso fallida');
        }
    } catch (e) {
        console.error('Error crítico al guardar silenciosamente en localStorage:', e);
    }

    // Si no tenemos ID de homologación, no podemos sincronizar con el servidor
    if (!homologacionId) {
        console.log('No hay ID de homologación, guardado sólo local');
        return;
    }

    // Preparar datos para enviar al servidor
    const datosHomologacion = {
        solicitud_id: solicitudId,
        homologacion_id: homologacionId,
        // Incluir todos los campos necesarios
        asignaturas_origen: homologaciones.map(h => h.asignatura_origen_id),
        asignaturas_destino: homologaciones.map(h => h.asignatura_destino_id || null),
        notas_destino: homologaciones.map(h => h.nota_destino || null),
        comentarios_asignaturas: homologaciones.map(h => h.comentarios || ''),
        homologaciones: homologaciones,
        confirmado: false
    };

    // Normalizar el ID para la API
    let apiHomologacionId = normalizarHomologacionId(homologacionId);

    // Enviar al servidor sin bloquear la interfaz
    fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(datosHomologacion)
    })
        .then(response => {
            if (!response.ok) throw new Error(`Error ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('Sincronización silenciosa completada:', data);
            // No actualizar homologaciones desde la respuesta para evitar sobreescribir cambios locales
        })
        .catch(error => {
            console.warn('Error en sincronización silenciosa:', error);
        });
}
function sincronizarConServidor() {
    if (!homologacionId || !solicitudId) return Promise.resolve(false);

    console.log('Sincronizando con el servidor...');

    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`)
        .then(response => {
            if (!response.ok) throw new Error(`Error ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('Datos del servidor recibidos:', data);

            if (data.datos && Array.isArray(data.datos.homologaciones)) {
                // Comparar homologaciones del servidor con las locales
                const serverHomologaciones = data.datos.homologaciones;

                // Si las homologaciones locales están vacías pero el servidor tiene datos,
                // usar los datos del servidor
                if (homologaciones.length === 0 && serverHomologaciones.length > 0) {
                    homologaciones = serverHomologaciones;
                    renderizarTablaHomologaciones();

                    // Actualizar localStorage
                    try {
                        localStorage.setItem(`homologaciones_${solicitudId}`, JSON.stringify(homologaciones));
                    } catch (e) {
                        console.warn('Error al guardar en localStorage:', e);
                    }

                    console.log('Homologaciones restauradas desde el servidor');
                    return true;
                }
                // Si las homologaciones locales tienen datos pero el servidor está vacío,
                // sincronizar los datos locales al servidor
                else if (homologaciones.length > 0 && serverHomologaciones.length === 0) {
                    guardarSilencioso();
                    return true;
                }
            }

            return false;
        })
        .catch(error => {
            console.warn('Error al sincronizar con el servidor:', error);
            return false;
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

// Función de ayuda para obtener datos informativos sin usar :contains (que no es estándar)
function obtenerDatoInformativo(etiqueta) {
    // Buscar entre todos los elementos info-item
    const infoItems = document.querySelectorAll('.info-item');
    for (let i = 0; i < infoItems.length; i++) {
        // Buscar si el texto del primer hijo contiene la etiqueta
        const labelElem = infoItems[i].querySelector('strong, label, h4, h5, h6, span');
        if (labelElem && labelElem.textContent.includes(etiqueta)) {
            // Si encontramos la etiqueta, devolver el contenido del párrafo
            const valueElem = infoItems[i].querySelector('p');
            if (valueElem) {
                return valueElem.textContent.trim();
            }
        }
    }
    // Alternativa: buscar directamente en un elemento con ID específico
    const elemConId = document.getElementById('dato-' + etiqueta.toLowerCase().replace(/\s+/g, '-'));
    if (elemConId) {
        return elemConId.textContent.trim();
    }

    return null;
}

// Función corregida para mostrar alertas (previene recursión infinita)
function mostrarAlerta(mensaje, tipo) {
    console.log(`Alerta: ${mensaje} (${tipo})`);

    // Verificar si ya existe un contenedor de alertas
    let alertContainer = document.getElementById('alert-container');

    // Si no existe, crear uno
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '20px';
        alertContainer.style.right = '20px';
        alertContainer.style.zIndex = '9999';
        alertContainer.style.maxWidth = '350px';
        document.body.appendChild(alertContainer);
    }

    // Crear la alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.role = 'alert';

    // Configurar el HTML de manera segura para evitar llamados recursivos
    alertDiv.textContent = mensaje;

    // Añadir botón de cierre
    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'close';
    closeButton.setAttribute('data-dismiss', 'alert');
    closeButton.setAttribute('aria-label', 'Close');

    const closeSpan = document.createElement('span');
    closeSpan.setAttribute('aria-hidden', 'true');
    closeSpan.innerHTML = '&times;';

    closeButton.appendChild(closeSpan);
    alertDiv.appendChild(closeButton);

    // Agregar la alerta al contenedor
    alertContainer.appendChild(alertDiv);

    // Configurar auto-eliminación después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.classList.remove('show');
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 300);
        }
    }, 5000);
}

// Función para confirmar y enviar la firma al vicerrector
function confirmarYEnviarAVicerrector(firmaData) {
    // Ya no necesitamos confirmar aquí porque ya estamos en el modal de confirmación
    console.log("Enviando firma a vista de vicerrector...");

    try {
        // Codificar la firma optimizada para URL
        const firmaEncoded = encodeURIComponent(firmaData);

        // Verificar si la URL no es demasiado larga
        if (firmaEncoded.length > 1500) {
            console.warn("La firma es muy grande para URL, intentando comprimir más");

            // Si es demasiado grande, volvemos a optimizar con mayor compresión
            optimizarImagen(firmaData, 300, 150, 0.5).then(firmaComprimida => {
                // Usar URL relativa predeterminada si no hay un botón continuar con data-url
                const urlBase = 'vista-vicerrector.html';
                const urlCompleta = `${urlBase}?firma=${encodeURIComponent(firmaComprimida)}`;

                // Guardar también en localStorage como respaldo
                try {
                    localStorage.setItem('firmaCoordinadorData', firmaComprimida);
                    console.log("Firma comprimida guardada en localStorage");
                } catch (error) {
                    console.warn("No se pudo guardar en localStorage, continuando de todos modos");
                }

                // Mostrar mensaje de redirección
                mostrarAlerta('Redirigiendo a la vista de vicerrector...', 'info');

                // Esperar un momento para que se vea la alerta
                setTimeout(() => {
                    // Redirigir a la vista del vicerrector
                    window.location.href = urlCompleta;
                }, 1000);
            }).catch(error => {
                console.error("Error al comprimir imagen:", error);
                mostrarAlerta('Error al comprimir la imagen. Intente con una firma más pequeña.', 'danger');
            });
        } else {
            // La URL no es demasiado larga, podemos usarla directamente
            const urlBase = 'vista-vicerrector.html';
            const urlCompleta = `${urlBase}?firma=${firmaEncoded}`;

            // Mostrar mensaje de redirección
            mostrarAlerta('Redirigiendo a la vista de vicerrector...', 'info');

            // Esperar un momento para que se vea la alerta
            setTimeout(() => {
                // Redirigir a la vista del vicerrector
                window.location.href = urlCompleta;
            }, 1000);
        }
    } catch (error) {
        console.error("Error al enviar firma:", error);
        mostrarAlerta('Error al enviar la firma. Intente de nuevo.', 'danger');
    }
}
// Función corregida para generar PDF
function generarPDF(esVistaVicerrector) {
    console.log("Función generarPDF llamada, es vista vicerrector:", esVistaVicerrector);

    try {
        // Verificar si jsPDF está disponible
        if (typeof jspdf === 'undefined' || typeof jspdf.jsPDF === 'undefined') {
            console.error('jsPDF no está disponible');
            mostrarAlerta('Error: Librería jsPDF no disponible. Verifique que todas las librerías necesarias están cargadas.', 'danger');
            return;
        }

        // Verificar las firmas necesarias para cada vista
        if (!window.firmaCoordinadorData) {
            // Intentar cargar de localStorage una última vez
            const firmaCoordGuardada = localStorage.getItem('firmaCoordinadorData');
            if (firmaCoordGuardada) {
                window.firmaCoordinadorData = firmaCoordGuardada;
                console.log("Firma del coordinador cargada desde localStorage justo antes de generar PDF");
            } else {
                mostrarAlerta('Error: No se ha cargado la firma del coordinador. Por favor, cargue la firma antes de continuar.', 'danger');
                return;
            }
        }

        if (esVistaVicerrector && !window.firmaVicerrectorData) {
            mostrarAlerta('Error: No se ha cargado la firma del vicerrector. Por favor, cargue la firma antes de continuar.', 'danger');
            return;
        }

        // Mostrar indicador de carga
        const btnGenerar = document.getElementById('btn-generar-pdf');
        if (btnGenerar) {
            const textoOriginal = btnGenerar.innerHTML;
            btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
            btnGenerar.disabled = true;

            // Restaurar botón si hay error después de 10 segundos
            setTimeout(() => {
                if (btnGenerar.innerHTML.includes('fa-spinner')) {
                    btnGenerar.innerHTML = textoOriginal;
                    btnGenerar.disabled = false;
                }
            }, 10000);
        }

        // Obtener datos del DOM para el PDF - CORREGIDO para no usar :contains que no es estándar
        const estudiante = obtenerDatoInformativo('Nombre') || 'Estudiante';
        const identificacion = obtenerDatoInformativo('Identificación') || 'No disponible';
        const universidad = obtenerDatoInformativo('Universidad de Origen') || 'Universidad Externa';
        const programa = obtenerDatoInformativo('Programa') || 'Programa Actual';

        // Obtener homologaciones de la tabla
        const homologaciones = [];
        document.querySelectorAll('#tabla-homologaciones tbody tr:not(#no-homologaciones)').forEach(row => {
            const celdas = row.querySelectorAll('td');
            if (celdas.length >= 5) {
                homologaciones.push({
                    asignatura_origen_nombre: celdas[0].textContent.trim(),
                    asignatura_destino_nombre: celdas[1].textContent.trim(),
                    nota_origen: celdas[2].textContent.trim(),
                    nota_destino: celdas[3].textContent.trim(),
                    creditos: celdas[4].textContent.trim()
                });
            }
        });

        // Crear objeto con los datos recopilados
        const datosEstudiante = {
            nombre: estudiante,
            identificacion: identificacion
        };

        const datosSolicitud = {
            universidad_origen: universidad,
            programa_destino: programa
        };

        const datosHomologacion = {
            homologaciones: homologaciones
        };

        // Crear el PDF con los datos recopilados
        generarPDFConDatos(datosHomologacion, datosEstudiante, datosSolicitud, esVistaVicerrector);

    } catch (error) {
        console.error('Error general al iniciar generación de PDF:', error);
        mostrarAlerta(`Error al generar PDF: ${error.message}`, 'danger');

        // Restaurar botón
        const btnGenerar = document.getElementById('btn-generar-pdf');
        if (btnGenerar) {
            btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar Resolución PDF';
            btnGenerar.disabled = false;
        }
    }
}
// Función corregida para generar el PDF con los datos
function generarPDFConDatos(datosHomologacion, datosEstudiante, datosSolicitud, esVistaVicerrector) {
    try {
        // Crear instancia de jsPDF
        const { jsPDF } = jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'letter',
            compress: true
        });

        // Verificar que tenemos las firmas necesarias antes de continuar
        if (!window.firmaCoordinadorData) {
            mostrarAlerta('Error: No se ha cargado la firma del coordinador', 'danger');
            return;
        }

        if (esVistaVicerrector && !window.firmaVicerrectorData) {
            mostrarAlerta('Error: No se ha cargado la firma del vicerrector', 'danger');
            return;
        }

        // Obtener datos del estudiante
        let estudiante = datosEstudiante.nombre || 'N/A';
        let identificacion = datosEstudiante.identificacion || 'N/A';
        let universidad = datosSolicitud.universidad_origen || 'N/A';
        let programa = datosSolicitud.programa_destino || 'N/A';

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
        const colorAzulClaro = [230, 236, 250]; // Azul muy claro para fondos
        const colorGris = [100, 100, 100]; // RGB para texto gris

        // Primera página con marco decorativo
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.3);
        doc.roundedRect(10, 10, 195, 260, 2, 2); // Marco exterior con bordes redondeados

        // Título y logo institucional
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, 25, { align: 'center' });

        doc.setFontSize(11);
        doc.setFont('helvetica', 'italic');
        doc.text('Líderes, visionarios y emprendedores', 105, 35, { align: 'center' });

        // Línea decorativa
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.7);
        doc.line(30, 40, 180, 40);

        // Número de resolución
        let yPos = 55;
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

        // Fondo para el título de resolución
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(50, yPos - 6, 110, 10, 1, 1, 'F');

        doc.text(`RESOLUCIÓN No. ${numeroResolucion}`, 105, yPos, { align: 'center' });

        yPos += 15;
        doc.text('Del', 105, yPos, { align: 'center' });

        yPos += 10;
        doc.text(`(${dia} ${mes.toUpperCase().substring(0, 3)}. ${año})`, 105, yPos, { align: 'center' });

        yPos += 25;

        // Título principal del documento
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        const tituloPrincipal = `Por la cual se aprueba el estudio de homologación de los cursos aprobados en ${universidad.toUpperCase()}, Programa de ${programa.toUpperCase()}, por ${estudiante.toUpperCase()} identificado con ${identificacion}.`;

        const lineasTituloPrincipal = doc.splitTextToSize(tituloPrincipal, 170);
        doc.text(lineasTituloPrincipal, 20, yPos);

        yPos += lineasTituloPrincipal.length * 7 + 15;

        // Texto de vicerrectoría
        doc.setFont('helvetica', 'bold');
        const textoVicerrectoria = 'La suscrita Vicerrectora Académica de la CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA, en uso de sus atribuciones reglamentarias y en especial las conferidas en el Acuerdo 010 de 2005 expedida por la ASAMBLEA DE FUNDADORES y el Reglamento Estudiantil Acuerdo 011 del 15 febrero de 2017. Artículo 32 y';

        const lineasVicerrectoria = doc.splitTextToSize(textoVicerrectoria, 170);
        doc.text(lineasVicerrectoria, 20, yPos);

        yPos += lineasVicerrectoria.length * 7 + 15;

        // Considerando
        doc.setFont('helvetica', 'bold');
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(65, yPos - 6, 80, 10, 2, 2, 'F');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text('CONSIDERANDO', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0);

        yPos += 15;

        // Texto considerando
        doc.setFont('helvetica', 'normal');
        let considerandos = [
            `Que el Decano de la Facssssssssssssssssssssssssssssssssssssssssssssssssultad de ${programa}, realizó el estudio de homologación de los cursos aprobados en el Programa de ${programa.toUpperCase()}, de ${universidad.toUpperCase()}, solicitado por ${estudiante.toUpperCase()} identificado con ${identificacion}.`,

            `Que la Vicerrectora Académica revisó los procedimientos aplicados y los anexos allegados por ${estudiante.toUpperCase()} para el estudio y análisis de la homologación realizada por el Decano de la Facultad correspondiente, con el correspondiente pensum vigente del Programa de ${programa} y por lo anterior.`,

            "Que de conformidad con el Reglamento Estudiantil vigente, se establecen los procedimientos y criterios para la homologación de asignaturas.",

            `Que existe correspondencia entre los contenidos programáticos, intensidad horaria, créditos académicos y nivel de competencias de las asignaturas a homologar.`,

            `Que en sesión del ${dia} de ${mes} de ${año}, el Comité de Homologaciones recomendó la aprobación de las asignaturas que se detallan en la presente resolución.`
        ];

        // Agregar considerandos con viñetas
        considerandos.forEach((texto, index) => {
            // Nueva página si es necesario
            if (yPos > 220) {
                doc.addPage();
                yPos = 30;

                // Marco decorativo
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.3);
                doc.roundedRect(10, 10, 195, 260, 2, 2);

                // Encabezado en la nueva página
                doc.setFontSize(9);
                doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
                doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, { align: 'center' });
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);
            }

            const lineas = doc.splitTextToSize(texto, 165);

            // Viñeta
            doc.setFillColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
            doc.circle(23, yPos, 1.2, 'F');

            doc.setFont('helvetica', 'normal');
            doc.text(lineas, 30, yPos);
            yPos += lineas.length * 6 + 8; // Espacio entre párrafos
        });

        yPos += 10;

        // Resuelve
        doc.setFont('helvetica', 'bold');
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(70, yPos - 6, 70, 10, 2, 2, 'F');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text('RESUELVE:', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0);

        yPos += 15;

        // Artículo Primero
        doc.setFillColor(240, 240, 240);
        doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 1°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 10;

        // Texto del artículo primero
        const textoArticuloPrimero = `Aprobar el estudio de homologación de ${estudiante.toUpperCase()} identificado con ${identificacion}, de la siguiente manera:`;
        const lineasArticulo1 = doc.splitTextToSize(textoArticuloPrimero, 175);
        doc.text(lineasArticulo1, 20, yPos);

        yPos += lineasArticulo1.length * 6 + 12;

        // Siempre crear una nueva página para la tabla para tener espacio suficiente
        doc.addPage();
        yPos = 30;

        // Marco decorativo para la nueva página
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.3);
        doc.roundedRect(10, 10, 195, 260, 2, 2);

        // Encabezado en la nueva página
        doc.setFontSize(9);
        doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
        doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, { align: 'center' });
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(10);

        // Título de la tabla
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

        // Fondo para el título de la tabla
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(45, yPos - 6, 120, 10, 2, 2, 'F');

        doc.text('CURSOS ACADÉMICOS HOMOLOGADOS', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0);

        yPos += 15;

        // Preparar datos para la tabla
        let asignaturasFiltradas = [];

        // Procesar datos de homologación
        if (datosHomologacion.homologaciones && datosHomologacion.homologaciones.length > 0) {
            asignaturasFiltradas = datosHomologacion.homologaciones.map(h => ({
                asignatura_origen_nombre: h.asignatura_origen_nombre || 'N/A',
                codigo_destino: h.codigo_destino || 'N/A',
                asignatura_destino_nombre: h.asignatura_destino_nombre || 'N/A',
                semestre: h.semestre || 'N/A',
                creditos: h.creditos || 'N/A',
                nota_destino: h.nota_destino || h.nota_homologada || 'N/A'
            }));
        }

        // Si no hay datos
        if (asignaturasFiltradas.length === 0) {
            asignaturasFiltradas.push({
                asignatura_origen_nombre: "No se encontraron asignaturas para homologar",
                codigo_destino: "-",
                asignatura_destino_nombre: "-",
                semestre: "-",
                creditos: "-",
                nota_destino: "-"
            });
        }

        // Crear tabla
        const headers = ['CURSO INSTITUCIÓN DE ORIGEN', 'CÓDIGO', 'CURSO ACADÉMICO AUTÓNOMA', 'SEM', 'CRED', 'CALIF'];
        const data = asignaturasFiltradas.map(h => [
            h.asignatura_origen_nombre,
            h.codigo_destino,
            h.asignatura_destino_nombre,
            h.semestre,
            h.creditos,
            h.nota_destino
        ]);

        // Usar autoTable si está disponible
        if (typeof doc.autoTable === 'function') {
            doc.autoTable({
                startY: yPos,
                head: [headers],
                body: data,
                margin: { left: 15, right: 15 },
                styles: {
                    fontSize: 9,  // Aumentado de 8 a 9
                    cellPadding: 6,  // Aumentado de 5 a 6
                    lineWidth: 0.1,
                    valign: 'middle',
                    overflow: 'linebreak',
                    lineColor: [200, 200, 200]
                },
                headStyles: {
                    fillColor: colorAzulInstitucional,
                    textColor: [255, 255, 255],
                    fontStyle: 'bold',
                    halign: 'center',
                    fontSize: 9  // Asegurar que el encabezado sea legible
                },
                columnStyles: {
                    0: { cellWidth: 50 },  // Aumentado espacio para curso de origen
                    1: { cellWidth: 18, halign: 'center' },
                    2: { cellWidth: 50 },  // Aumentado espacio para curso destino
                    3: { cellWidth: 15, halign: 'center' },
                    4: { cellWidth: 15, halign: 'center' },
                    5: { cellWidth: 15, halign: 'center' }
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                didDrawCell: function (data) {
                    // Agregar bordes más visibles a las celdas
                    if (data.section === 'body' || data.section === 'head') {
                        doc.setDrawColor(150, 150, 150);
                        doc.setLineWidth(0.1);
                        doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'S');
                    }
                }
            });

            // Actualizar posición después de la tabla
            yPos = doc.lastAutoTable.finalY + 18;
        } else {
            // Implementación alternativa simple si autoTable no está disponible
            console.warn('autoTable no está disponible, usando implementación básica');

            // Esta parte es para situaciones donde autoTable no esté disponible
            // pero sería mejor asegurarse de que autoTable siempre esté incluido
            yPos = 150;  // Simplemente avanzamos a una posición posterior en la página
        }

        // Calcular total de créditos
        const totalCreditos = asignaturasFiltradas.reduce((sum, item) => {
            return sum + (parseFloat(item.creditos) || 0);
        }, 0);

        // Resumen de totales
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(95, yPos - 5, 90, 25, 2, 2, 'F');  // Aumentado altura del recuadro

        // Total de cursos y créditos
        doc.setFontSize(10);  // Aumentado tamaño de fuente
        doc.setFont('helvetica', 'bold');
        doc.text('TOTAL CURSOS HOMOLOGADOS:', 110, yPos + 5);
        doc.text(asignaturasFiltradas.length.toString(), 175, yPos + 5);

        yPos += 12;  // Aumentado espacio entre líneas

        doc.text('TOTAL CRÉDITOS HOMOLOGADOS:', 110, yPos + 5);
        doc.text(totalCreditos.toString(), 175, yPos + 5);

        // Nueva página para firmas
        doc.addPage();
        yPos = 50;

        // Marco decorativo para la página de firmas
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.3);
        doc.roundedRect(10, 10, 195, 260, 2, 2);

        // Encabezado en la página de firmas
        doc.setFontSize(9);
        doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
        doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, { align: 'center' });
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(10);

        // Artículo Segundo
        doc.setFillColor(240, 240, 240);
        doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 2°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 10;

        // Texto del artículo segundo
        const textoArticuloSegundo = `Ordenar al Departamento de Admisiones, Registro y Control Académico, registrar en el sistema la homologación de los cursos académicos relacionados.`;
        const lineasArticulo2 = doc.splitTextToSize(textoArticuloSegundo, 175);
        doc.text(lineasArticulo2, 20, yPos);

        yPos += lineasArticulo2.length * 6 + 10;

        // Artículo Tercero
        doc.setFillColor(240, 240, 240);
        doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 3°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 10;

        // Texto del artículo tercero
        const textoArticuloTercero = `La presente Resolución rige a partir de la fecha de su expedición.`;
        doc.text(textoArticuloTercero, 20, yPos);

        yPos += 20;

        // Comuníquese y cúmplase
        doc.setFont('helvetica', 'bold');
        doc.text('COMUNÍQUESE Y CÚMPLASE', 105, yPos, { align: 'center' });

        yPos += 10;

        // Fecha de expedición
        doc.setFont('helvetica', 'normal');
        doc.text(`Dada en Popayán, a los ${dia} días del mes de ${mes} de ${año}.`, 105, yPos, { align: 'center' });

        yPos += 30;

        const espacioFirma = 90;

        // Rectángulos de fondo para área de firmas - mejorados
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2], 0.3);
        doc.roundedRect(105 - espacioFirma / 2 - 40, yPos - 20, 80, 70, 3, 3, 'F');  // Aumentado altura
        doc.roundedRect(105 + espacioFirma / 2 - 40, yPos - 20, 80, 70, 3, 3, 'F');  // Aumentado altura

        // Agregar firmas con mejor posicionamiento
        try {
            // Firma del coordinador
            if (window.firmaCoordinadorData) {
                doc.addImage(
                    window.firmaCoordinadorData,
                    'PNG',
                    (105 - espacioFirma / 2) - 25,
                    yPos - 10,  // Ajustado
                    50,
                    25  // Aumentado tamaño
                );
            }

            // Firma del vicerrector si estamos en esa vista
            if (esVistaVicerrector && window.firmaVicerrectorData) {
                doc.addImage(
                    window.firmaVicerrectorData,
                    'PNG',
                    (105 + espacioFirma / 2) - 25,
                    yPos - 10,  // Ajustado
                    50,
                    25  // Aumentado tamaño
                );
            }
        } catch (error) {
            console.error('Error al agregar firmas:', error);
        }

        // Líneas para firmas
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.5);
        doc.line(105 - espacioFirma / 2 - 35, yPos + 20, 105 - espacioFirma / 2 + 35, yPos + 20);  // Ajustado
        doc.line(105 + espacioFirma / 2 - 35, yPos + 20, 105 + espacioFirma / 2 + 35, yPos + 20);  // Ajustado

        // Nombres y cargos
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(9);
        doc.text('JUAN PABLO DIAGO RODRÍGUEZ', 105 - espacioFirma / 2, yPos + 30, { align: 'center' });  // Ajustado
        doc.text('ISABEL RAMIREZ MEJIA', 105 + espacioFirma / 2, yPos + 30, { align: 'center' });  // Ajustado

        yPos += 40;  // Ajustado
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.text(`Decano Facultad ${programa}`, 105 - espacioFirma / 2, yPos, { align: 'center' });
        doc.text('Vicerrectora Académica', 105 + espacioFirma / 2, yPos, { align: 'center' });

        // Pie de página para todas las páginas
        const totalPages = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);

            // Pie de página
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(100, 100, 100);

            const footerY = 265;
            doc.text('Lic. De Funcionamiento: 12321/79. Resolución MEN Nº. 677 de 2023. Código SNIES: 2849', 105, footerY, { align: 'center' });
            doc.text('Sede principal – Calle 5 Nº 3 – 85 Centro. Popayán - Cauca - Colombia.', 105, footerY + 5, { align: 'center' });

            // Número de página
            doc.setFontSize(7);
            doc.setFont('helvetica', 'bold');
            doc.text(`Página ${i} de ${totalPages}`, 180, footerY + 10, { align: 'right' });
        }

        // Mostrar PDF en el modal
        mostrarPDFEnModal(doc, datosEstudiante, esVistaVicerrector);

        return true;

    } catch (error) {
        console.error('Error al generar PDF con datos:', error);
        mostrarAlerta(`Error al generar PDF: ${error.message}`, 'danger');

        // Restaurar botón
        const btnGenerar = document.getElementById('btn-generar-pdf');
        if (btnGenerar) {
            btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar Resolución PDF';
            btnGenerar.disabled = false;
        }

        return false;
    }
}

// Función para mostrar el PDF en el modal y guardar SOLO la versión final
function mostrarPDFEnModal(doc, datosEstudiante, esVistaVicerrector) {
    try {
        // Crear base64 del PDF
        const pdfData = doc.output('datauristring');

        // Mostrar preview
        const pdfPreview = document.getElementById('pdf-preview-content');
        if (pdfPreview) {
            pdfPreview.innerHTML = '';

            const iframe = document.createElement('iframe');
            iframe.style.width = '100%';
            iframe.style.height = '600px';
            iframe.style.border = '1px solid #ddd';
            iframe.src = pdfData;

            pdfPreview.appendChild(iframe);

            // Mostrar modal
            $('#pdf-preview-modal').modal('show');
        } else {
            throw new Error('No se encontró el elemento pdf-preview-content');
        }

        // IMPORTANTE: Subir el PDF final con todas las firmas
        // Solo si es la vista del vicerrector (donde tenemos ambas firmas)
        if (esVistaVicerrector) {
            // Obtener el ID de homologación
            let id = null;
            if (typeof homologacionId !== 'undefined' && homologacionId) {
                id = homologacionId;
            } else if (typeof cargarHomologacionId === 'function') {
                id = cargarHomologacionId();
            } else {
                // Define la función si no existe
                window.cargarHomologacionId = function () {
                    return new URLSearchParams(window.location.search).get('id') ||
                        document.querySelector('[data-homologacion-id]')?.dataset.homologacionId;
                };
                id = cargarHomologacionId();
            }

            if (!id) {
                console.error('No se pudo obtener el ID de homologación para guardar el PDF');
                mostrarAlerta('No se pudo obtener el ID de homologación para guardar el PDF', 'warning');
            } else {
                // Normalizar el ID si es necesario
                let apiHomologacionId = id;
                if (typeof normalizarHomologacionId === 'function') {
                    apiHomologacionId = normalizarHomologacionId(id);
                } else if (typeof window.normalizarHomologacionId === 'function') {
                    apiHomologacionId = window.normalizarHomologacionId(id);
                }

                // Crear archivo PDF para guardar - Asegurarnos que sea el PDF completo con todas las firmas
                const pdfBlob = doc.output('blob');
                const fecha = new Date().toISOString().split('T')[0];
                const nombreArchivo = `resolucion_homologacion_FINAL_${apiHomologacionId}_${fecha}.pdf`;
                const pdfFile = new File([pdfBlob], nombreArchivo, { type: 'application/pdf' });

                // Verificar que la función para subir existe
                if (typeof subirSoloPDFResolucion !== 'function') {
                    console.error('La función subirSoloPDFResolucion no está disponible');
                    mostrarAlerta('No se pudo guardar el PDF final. La función de subida no está disponible.', 'danger');
                } else {
                    // Subir el PDF FINAL con TODAS LAS FIRMAS al servidor
                    subirSoloPDFResolucion(apiHomologacionId, pdfFile)
                        .then(data => {
                            console.log('PDF FINAL con TODAS LAS FIRMAS subido exitosamente:', data);
                            if (typeof actualizarInterfazConPDF === 'function') {
                                actualizarInterfazConPDF(data);
                            }
                            mostrarAlerta('PDF con TODAS las firmas guardado correctamente en el sistema', 'success');
                        })
                        .catch(error => {
                            console.error('Error al subir PDF FINAL:', error);
                            mostrarAlerta(`Error al guardar el PDF en el sistema: ${error.message}`, 'danger');
                        });
                }
            }
        } else {
            console.log('Vista de coordinador: PDF mostrado pero NO guardado en el backend (se guardará en la fase final con vicerrector)');
        }

        // Configurar botón de confirmar
        const btnConfirmar = document.getElementById('btn-confirmar-pdf');
        if (btnConfirmar) {
            // Definir la acción según la vista
            if (esVistaVicerrector) {
                btnConfirmar.onclick = function () {
                    // Cerrar el modal
                    $('#pdf-preview-modal').modal('hide');

                    // Descargar PDF final para el usuario
                    const nombreArchivo = `Homologacion_Final_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
                    doc.save(nombreArchivo);

                    // Mostrar mensaje de éxito
                    mostrarAlerta('Resolución de homologación finalizada y guardada correctamente', 'success');
                };
            } else {
                btnConfirmar.onclick = function () {
                    // Cerrar el modal
                    $('#pdf-preview-modal').modal('hide');

                    // Enviar a vicerrector si estamos en la vista de coordinador
                    if (typeof confirmarYEnviarAVicerrector === 'function') {
                        // Si la firma del coordinador existe, pasarla a la función
                        if (window.firmaCoordinadorData) {
                            confirmarYEnviarAVicerrector(window.firmaCoordinadorData);
                        } else {
                            // Intentar generar una firma por defecto
                            const firmaDefault = typeof generarFirmaDefault === 'function' ? generarFirmaDefault('coordinador') : null;
                            confirmarYEnviarAVicerrector(firmaDefault);
                        }
                    } else {
                        console.error('La función confirmarYEnviarAVicerrector no está disponible');
                        mostrarAlerta('No se pudo enviar al vicerrector. La función no está disponible.', 'danger');
                    }

                    // Descargar PDF para el usuario
                    const nombreArchivo = `Homologacion_Coordinador_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
                    doc.save(nombreArchivo);
                };
            }
        }

        // Botón para solo descargar sin confirmar
        const btnDescargar = document.getElementById('btn-descargar-solo-pdf');
        if (btnDescargar) {
            btnDescargar.onclick = function () {
                const prefijo = esVistaVicerrector ? 'Homologacion_Final' : 'Homologacion_Coordinador';
                const nombreArchivo = `${prefijo}_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
                doc.save(nombreArchivo);
                mostrarAlerta('PDF descargado correctamente', 'success');
            };
        }

        // Restaurar botón de generar PDF
        const btnGenerar = document.getElementById('btn-generar-pdf');
        if (btnGenerar) {
            btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar Resolución PDF';
            btnGenerar.disabled = false;
        }

        return true;
    } catch (error) {
        console.error('Error al mostrar el PDF en el modal:', error);
        mostrarAlerta(`Error al mostrar el PDF: ${error.message}`, 'danger');

        // Si hay error, intentar descargar directamente
        try {
            const prefijo = esVistaVicerrector ? 'Homologacion_Final' : 'Homologacion_Coordinador';
            const nombreArchivo = `${prefijo}_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
            doc.save(nombreArchivo);
            mostrarAlerta('PDF generado y descargado directamente', 'warning');
            return true;
        } catch (e) {
            console.error('Error al descargar PDF directamente:', e);
            mostrarAlerta('No se pudo generar el PDF', 'danger');
            return false;
        }
    }
}

/**
 * Configura el botón de descargar PDF para guardar la ruta en la API
 */
document.addEventListener('DOMContentLoaded', function () {
    const btnDescargarPDF = document.getElementById('btn-descargar-pdf');
    if (btnDescargarPDF) {
        btnDescargarPDF.addEventListener('click', descargarPDF);
        console.log('Botón de descargar PDF configurado correctamente');
    } else {
        console.log('Botón de descargar PDF no encontrado en esta página');
    }
});


// Función para manejar la carga de firma del coordinador
function handleFirmaCoordinadorUpload(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('firma-preview');
    const placeholder = document.getElementById('firma-coordinador-placeholder');
    const hiddenInput = document.getElementById('firma_coordinador_data');
    const generateBtn = document.getElementById('btn-generar-pdf-coordinador');
    const fileLabel = event.target.nextElementSibling;

    if (file) {
        // Validar tipo de archivo
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Por favor, selecciona un archivo de imagen válido (JPG, PNG, GIF)');
            event.target.value = '';
            return;
        }

        // Validar tamaño (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('El archivo es demasiado grande. Máximo 5MB permitido.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            // Mostrar preview de la imagen
            preview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;

            // Guardar datos en input hidden
            hiddenInput.value = e.target.result;

            // IMPORTANTE: Guardar también en window.firmaCoordinadorData para que esté disponible globalmente
            window.firmaCoordinadorData = e.target.result;

            // Guardar en localStorage como respaldo
            localStorage.setItem('firmaCoordinadorData', e.target.result);

            // Habilitar botón de generar PDF
            generateBtn.disabled = false;
            generateBtn.classList.remove('btn-secondary');
            generateBtn.style.backgroundColor = '#0277bd';

            console.log('Firma del coordinador cargada y guardada correctamente');
        };
        reader.readAsDataURL(file);

        // Actualizar label del archivo
        fileLabel.textContent = file.name;
    } else {
        // Resetear si no hay archivo
        preview.innerHTML = `<p style="color: #19407b;" class="mb-0">Vista previa de la firma</p>`;
        hiddenInput.value = '';

        // Limpiar también las variables globales
        window.firmaCoordinadorData = null;
        localStorage.removeItem('firmaCoordinadorData');

        generateBtn.disabled = true;
        generateBtn.style.backgroundColor = '#6c757d';
        fileLabel.textContent = 'Seleccionar archivo...';
    }
}

// Función para generar PDF desde el formulario del coordinador
function generarPDFCoordinador() {
    // Verificar que la firma esté cargada
    const firmaData = document.getElementById('firma_coordinador_data').value;

    if (!firmaData) {
        mostrarAlerta('Por favor, carga la firma del coordinador antes de generar el PDF.', 'warning');
        return;
    }

    // Asegurar que la firma esté disponible globalmente
    if (!window.firmaCoordinadorData) {
        window.firmaCoordinadorData = firmaData;
        localStorage.setItem('firmaCoordinadorData', firmaData);
    }

    // Mostrar loading en el botón
    const btn = document.getElementById('btn-generar-pdf-coordinador');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generando PDF...';
    btn.disabled = true;

    try {
        // Llamar a la función principal de generación de PDF
        // El parámetro false indica que es vista de coordinador (no vicerrector)
        const resultado = generarPDF(false);

        if (!resultado) {
            throw new Error('Error en la generación del PDF');
        }

        console.log('PDF generado exitosamente desde formulario del coordinador');

    } catch (error) {
        console.error('Error al generar PDF desde coordinador:', error);
        mostrarAlerta(`Error al generar PDF: ${error.message}`, 'danger');
    } finally {
        // Restaurar botón después de un breve delay
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }
}

// Función auxiliar para obtener datos informativos del DOM
function obtenerDatoInformativo(campo) {
    // Buscar por texto exacto primero
    let elemento = Array.from(document.querySelectorAll('strong, b, .font-weight-bold')).find(el =>
        el.textContent.trim().toLowerCase().includes(campo.toLowerCase())
    );

    if (elemento) {
        // Buscar el valor en el siguiente elemento o en el padre
        let valor = elemento.nextElementSibling?.textContent?.trim() ||
            elemento.parentElement?.nextElementSibling?.textContent?.trim() ||
            elemento.parentElement?.textContent?.replace(elemento.textContent, '').trim();

        if (valor && valor !== '') {
            return valor;
        }
    }

    // Buscar en inputs o spans con data attributes
    elemento = document.querySelector(`[data-campo="${campo.toLowerCase()}"]`) ||
        document.querySelector(`[data-info="${campo.toLowerCase()}"]`);

    if (elemento) {
        return elemento.textContent?.trim() || elemento.value?.trim();
    }

    return null;
}

// Función auxiliar para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    // Si existe una función de alertas personalizada, usarla
    if (typeof window.mostrarAlerta === 'function' && window.mostrarAlerta !== mostrarAlerta) {
        return window.mostrarAlerta(mensaje, tipo);
    }

    // Implementación básica con estilos Bootstrap si está disponible
    const alertContainer = document.getElementById('alert-container') || document.body;

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.maxWidth = '400px';

    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;

    alertContainer.appendChild(alertDiv);

    // Auto-remove después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

// Función corregida para generar PDF
function generarPDF(esVistaVicerrector) {
    console.log("Función generarPDF llamada, es vista vicerrector:", esVistaVicerrector);

    try {
        // Verificar si jsPDF está disponible
        if (typeof jspdf === 'undefined' || typeof jspdf.jsPDF === 'undefined') {
            console.error('jsPDF no está disponible');
            mostrarAlerta('Error: Librería jsPDF no disponible. Verifique que todas las librerías necesarias están cargadas.', 'danger');
            return;
        }

        // Verificar las firmas necesarias para cada vista
        if (!window.firmaCoordinadorData) {
            // Intentar cargar de localStorage una última vez
            const firmaCoordGuardada = localStorage.getItem('firmaCoordinadorData');
            if (firmaCoordGuardada) {
                window.firmaCoordinadorData = firmaCoordGuardada;
                console.log("Firma del coordinador cargada desde localStorage justo antes de generar PDF");
            } else {
                mostrarAlerta('Error: No se ha cargado la firma del coordinador. Por favor, cargue la firma antes de continuar.', 'danger');
                return;
            }
        }

        if (esVistaVicerrector && !window.firmaVicerrectorData) {
            mostrarAlerta('Error: No se ha cargado la firma del vicerrector. Por favor, cargue la firma antes de continuar.', 'danger');
            return;
        }

        // Mostrar indicador de carga
        const btnGenerar = document.getElementById('btn-generar-pdf') || document.getElementById('btn-generar-pdf-coordinador');
        if (btnGenerar) {
            const textoOriginal = btnGenerar.innerHTML;
            btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
            btnGenerar.disabled = true;

            // Restaurar botón si hay error después de 10 segundos
            setTimeout(() => {
                if (btnGenerar.innerHTML.includes('fa-spinner')) {
                    btnGenerar.innerHTML = textoOriginal;
                    btnGenerar.disabled = false;
                }
            }, 10000);
        }

        // Obtener datos del DOM para el PDF
        const estudiante = obtenerDatoInformativo('Nombre') || 'Estudiante';
        const identificacion = obtenerDatoInformativo('Identificación') || 'No disponible';
        const universidad = obtenerDatoInformativo('Universidad de Origen') || 'Universidad Externa';
        const programa = obtenerDatoInformativo('Programa') || 'Programa Actual';

        // Obtener homologaciones de la tabla
        const homologaciones = [];
        document.querySelectorAll('#tabla-homologaciones tbody tr:not(#no-homologaciones)').forEach(row => {
            const celdas = row.querySelectorAll('td');
            if (celdas.length >= 5) {
                homologaciones.push({
                    asignatura_origen_nombre: celdas[0].textContent.trim(),
                    asignatura_destino_nombre: celdas[1].textContent.trim(),
                    nota_origen: celdas[2].textContent.trim(),
                    nota_destino: celdas[3].textContent.trim(),
                    creditos: celdas[4].textContent.trim()
                });
            }
        });

        // Crear objeto con los datos recopilados
        const datosEstudiante = {
            nombre: estudiante,
            identificacion: identificacion
        };

        const datosSolicitud = {
            universidad_origen: universidad,
            programa_destino: programa
        };

        const datosHomologacion = {
            homologaciones: homologaciones
        };

        // Crear el PDF con los datos recopilados
        generarPDFConDatos(datosHomologacion, datosEstudiante, datosSolicitud, esVistaVicerrector);

    } catch (error) {
        console.error('Error general al iniciar generación de PDF:', error);
        mostrarAlerta(`Error al generar PDF: ${error.message}`, 'danger');

        // Restaurar botón
        const btnGenerar = document.getElementById('btn-generar-pdf') || document.getElementById('btn-generar-pdf-coordinador');
        if (btnGenerar) {
            btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar Resolución PDF';
            btnGenerar.disabled = false;
        }
    }
}
// Función corregida para generar el PDF con los datos
async function generarPDFConDatos(datosHomologacion, datosEstudiante, datosSolicitud, esVistaVicerrector) {
    try {
        // Crear instancia de jsPDF
        const { jsPDF } = jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'letter',
            compress: true
        });

        // URLs de firmas predeterminadas
        const FIRMAS_PREDETERMINADAS = {
            coordinador: 'https://i.postimg.cc/W4yX2QG8/firma-coordinador.png',
            vicerrector: 'https://i.postimg.cc/h4pG2K1S/firma-vicerrector.png'
        };

        // Función mejorada para cargar imagen desde URL con fondo blanco
        function cargarImagenDesdeURL(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = function() {
                    try {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        canvas.width = this.width;
                        canvas.height = this.height;

                        // IMPORTANTE: Llenar el canvas con fondo blanco primero
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);

                        // Luego dibujar la imagen encima
                        ctx.drawImage(this, 0, 0);

                        resolve(canvas.toDataURL('image/png'));
                    } catch (error) {
                        reject(error);
                    }
                };
                img.onerror = function() {
                    reject(new Error(`No se pudo cargar la imagen: ${url}`));
                };
                img.src = url;
            });
        }

        // Función mejorada para crear firma de texto con fondo blanco
        function crearFirmaTexto(nombre) {
            const canvas = document.createElement('canvas');
            canvas.width = 300;
            canvas.height = 100;
            const ctx = canvas.getContext('2d');

            // IMPORTANTE: Fondo blanco explícito
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Configurar texto
            ctx.font = 'italic 24px serif';
            ctx.fillStyle = '#003399';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            // Dibujar el texto
            ctx.fillText(nombre, canvas.width / 2, canvas.height / 2);

            // Agregar una línea decorativa debajo
            ctx.strokeStyle = '#003399';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(50, canvas.height - 20);
            ctx.lineTo(canvas.width - 50, canvas.height - 20);
            ctx.stroke();

            return canvas.toDataURL('image/png');
        }

        // NUEVA LÓGICA: Preparar firmas automáticamente
        async function prepararFirmas() {
            console.log('Preparando firmas...');

            try {
                // Cargar firma del coordinador si no existe
                if (!window.firmaCoordinadorData) {
                    console.log('Cargando firma predeterminada del coordinador...');
                    try {
                        window.firmaCoordinadorData = await cargarImagenDesdeURL(FIRMAS_PREDETERMINADAS.coordinador);
                        console.log('Firma del coordinador cargada exitosamente');
                    } catch (error) {
                        console.warn('Error al cargar firma del coordinador:', error);
                        // Crear una firma de texto simple como fallback
                        window.firmaCoordinadorData = crearFirmaTexto('Juan Pablo Diago R.');
                    }
                }

                // Cargar firma del vicerrector si es necesaria
                if (esVistaVicerrector && !window.firmaVicerrectorData) {
                    console.log('Cargando firma predeterminada del vicerrector...');
                    try {
                        window.firmaVicerrectorData = await cargarImagenDesdeURL(FIRMAS_PREDETERMINADAS.vicerrector);
                        console.log('Firma del vicerrector cargada exitosamente');
                    } catch (error) {
                        console.warn('Error al cargar firma del vicerrector:', error);
                        // Crear una firma de texto simple como fallback
                        window.firmaVicerrectorData = crearFirmaTexto('Sebastian Toro');
                    }
                }
            } catch (error) {
                console.error('Error general al preparar firmas:', error);
            }
        }

        // EJECUTAR preparación de firmas ANTES de continuar
        await prepararFirmas();

        // Obtener datos del estudiante
        let estudiante = datosEstudiante.nombre || 'N/A';
        let identificacion = datosEstudiante.identificacion || 'N/A';
        let universidad = datosSolicitud.universidad_origen || 'N/A';
        let programa = datosSolicitud.programa_destino || 'N/A';

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
        const colorAzulClaro = [230, 236, 250]; // Azul muy claro para fondos
        const colorGris = [100, 100, 100]; // RGB para texto gris

        // Primera página con marco decorativo
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.3);
        doc.roundedRect(10, 10, 195, 260, 2, 2); // Marco exterior con bordes redondeados

        // Título y logo institucional
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, 25, { align: 'center' });

        doc.setFontSize(11);
        doc.setFont('helvetica', 'italic');
        doc.text('Líderes, visionarios y emprendedores', 105, 35, { align: 'center' });

        // Línea decorativa
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.7);
        doc.line(30, 40, 180, 40);

        // Número de resolución
        let yPos = 55;
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

        // Fondo para el título de resolución
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(50, yPos - 6, 110, 10, 1, 1, 'F');

        doc.text(`RESOLUCIÓN No. ${numeroResolucion}`, 105, yPos, { align: 'center' });

        yPos += 15;
        doc.text('Del', 105, yPos, { align: 'center' });

        yPos += 10;
        doc.text(`(${dia} ${mes.toUpperCase().substring(0, 3)}. ${año})`, 105, yPos, { align: 'center' });

        yPos += 25;

        // Título principal del documento
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(0, 0, 0);
        const tituloPrincipal = `Por la cual se aprueba el estudio de homologación de los cursos aprobados en ${universidad.toUpperCase()}, Programa de ${programa.toUpperCase()}, por ${estudiante.toUpperCase()} identificado con ${identificacion}.`;

        const lineasTituloPrincipal = doc.splitTextToSize(tituloPrincipal, 170);
        doc.text(lineasTituloPrincipal, 20, yPos);

        yPos += lineasTituloPrincipal.length * 7 + 15;

        // Texto de vicerrectoría
        doc.setFont('helvetica', 'bold');
        const textoVicerrectoria = 'La suscrita Vicerrectora Académica de la CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA, en uso de sus atribuciones reglamentarias y en especial las conferidas en el Acuerdo 010 de 2005 expedida por la ASAMBLEA DE FUNDADORES y el Reglamento Estudiantil Acuerdo 011 del 15 febrero de 2017. Artículo 32 y';

        const lineasVicerrectoria = doc.splitTextToSize(textoVicerrectoria, 170);
        doc.text(lineasVicerrectoria, 20, yPos);

        yPos += lineasVicerrectoria.length * 7 + 15;

        // Considerando
        doc.setFont('helvetica', 'bold');
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(65, yPos - 6, 80, 10, 2, 2, 'F');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text('CONSIDERANDO', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0);

        yPos += 15;

        // Texto considerando
        doc.setFont('helvetica', 'normal');
        let considerandos = [
            `Que el Decano de la Facultad de ${programa}, realizó el estudio de homologación de los cursos aprobados en el Programa de ${programa.toUpperCase()}, de ${universidad.toUpperCase()}, solicitado por ${estudiante.toUpperCase()} identificado con ${identificacion}.`,

            `Que la Vicerrectora Académica revisó los procedimientos aplicados y los anexos allegados por ${estudiante.toUpperCase()} para el estudio y análisis de la homologación realizada por el Decano de la Facultad correspondiente, con el correspondiente pensum vigente del Programa de ${programa} y por lo anterior.`,

            "Que de conformidad con el Reglamento Estudiantil vigente, se establecen los procedimientos y criterios para la homologación de asignaturas.",

            `Que existe correspondencia entre los contenidos programáticos, intensidad horaria, créditos académicos y nivel de competencias de las asignaturas a homologar.`,

            `Que en sesión del ${dia} de ${mes} de ${año}, el Comité de Homologaciones recomendó la aprobación de las asignaturas que se detallan en la presente resolución.`
        ];

        // Agregar considerandos con viñetas
        considerandos.forEach((texto, index) => {
            // Nueva página si es necesario
            if (yPos > 220) {
                doc.addPage();
                yPos = 30;

                // Marco decorativo
                doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
                doc.setLineWidth(0.3);
                doc.roundedRect(10, 10, 195, 260, 2, 2);

                // Encabezado en la nueva página
                doc.setFontSize(9);
                doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
                doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, { align: 'center' });
                doc.setTextColor(0, 0, 0);
                doc.setFontSize(10);
            }

            const lineas = doc.splitTextToSize(texto, 165);

            // Viñeta
            doc.setFillColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
            doc.circle(23, yPos, 1.2, 'F');

            doc.setFont('helvetica', 'normal');
            doc.text(lineas, 30, yPos);
            yPos += lineas.length * 6 + 8; // Espacio entre párrafos
        });

        yPos += 10;

        // Resuelve
        doc.setFont('helvetica', 'bold');
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(70, yPos - 6, 70, 10, 2, 2, 'F');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.text('RESUELVE:', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0);

        yPos += 15;

        // Artículo Primero
        doc.setFillColor(240, 240, 240);
        doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 1°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 10;

        // Texto del artículo primero
        const textoArticuloPrimero = `Aprobar el estudio de homologación de ${estudiante.toUpperCase()} identificado con ${identificacion}, de la siguiente manera:`;
        const lineasArticulo1 = doc.splitTextToSize(textoArticuloPrimero, 175);
        doc.text(lineasArticulo1, 20, yPos);

        yPos += lineasArticulo1.length * 6 + 12;

        // Siempre crear una nueva página para la tabla para tener espacio suficiente
        doc.addPage();
        yPos = 30;

        // Marco decorativo para la nueva página
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.3);
        doc.roundedRect(10, 10, 195, 260, 2, 2);

        // Encabezado en la nueva página
        doc.setFontSize(9);
        doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
        doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, { align: 'center' });
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(10);

        // Título de la tabla
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);

        // Fondo para el título de la tabla
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(45, yPos - 6, 120, 10, 2, 2, 'F');

        doc.text('CURSOS ACADÉMICOS HOMOLOGADOS', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0);

        yPos += 15;

        // Preparar datos para la tabla
        let asignaturasFiltradas = [];

        // Procesar datos de homologación
        if (datosHomologacion.homologaciones && datosHomologacion.homologaciones.length > 0) {
            asignaturasFiltradas = datosHomologacion.homologaciones.map(h => ({
                asignatura_origen_nombre: h.asignatura_origen_nombre || 'N/A',
                codigo_destino: h.codigo_destino || 'N/A',
                asignatura_destino_nombre: h.asignatura_destino_nombre || 'N/A',
                semestre: h.semestre || 'N/A',
                creditos: h.creditos || 'N/A',
                nota_destino: h.nota_destino || h.nota_homologada || 'N/A'
            }));
        }

        // Si no hay datos
        if (asignaturasFiltradas.length === 0) {
            asignaturasFiltradas.push({
                asignatura_origen_nombre: "No se encontraron asignaturas para homologar",
                codigo_destino: "-",
                asignatura_destino_nombre: "-",
                semestre: "-",
                creditos: "-",
                nota_destino: "-"
            });
        }

        // Crear tabla
        const headers = ['CURSO INSTITUCIÓN DE ORIGEN', 'CÓDIGO', 'CURSO ACADÉMICO AUTÓNOMA', 'SEM', 'CRED', 'CALIF'];
        const data = asignaturasFiltradas.map(h => [
            h.asignatura_origen_nombre,
            h.codigo_destino,
            h.asignatura_destino_nombre,
            h.semestre,
            h.creditos,
            h.nota_destino
        ]);

        // Usar autoTable si está disponible
        if (typeof doc.autoTable === 'function') {
            doc.autoTable({
                startY: yPos,
                head: [headers],
                body: data,
                margin: { left: 15, right: 15 },
                styles: {
                    fontSize: 9,
                    cellPadding: 6,
                    lineWidth: 0.1,
                    valign: 'middle',
                    overflow: 'linebreak',
                    lineColor: [200, 200, 200]
                },
                headStyles: {
                    fillColor: colorAzulInstitucional,
                    textColor: [255, 255, 255],
                    fontStyle: 'bold',
                    halign: 'center',
                    fontSize: 9
                },
                columnStyles: {
                    0: { cellWidth: 50 },
                    1: { cellWidth: 18, halign: 'center' },
                    2: { cellWidth: 50 },
                    3: { cellWidth: 15, halign: 'center' },
                    4: { cellWidth: 15, halign: 'center' },
                    5: { cellWidth: 15, halign: 'center' }
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                didDrawCell: function (data) {
                    if (data.section === 'body' || data.section === 'head') {
                        doc.setDrawColor(150, 150, 150);
                        doc.setLineWidth(0.1);
                        doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'S');
                    }
                }
            });

            // Actualizar posición después de la tabla
            yPos = doc.lastAutoTable.finalY + 18;
        } else {
            console.warn('autoTable no está disponible, usando implementación básica');
            yPos = 150;
        }

        // Calcular total de créditos
        const totalCreditos = asignaturasFiltradas.reduce((sum, item) => {
            return sum + (parseFloat(item.creditos) || 0);
        }, 0);

        // Resumen de totales
        doc.setFillColor(colorAzulClaro[0], colorAzulClaro[1], colorAzulClaro[2]);
        doc.roundedRect(95, yPos - 5, 90, 25, 2, 2, 'F');

        // Total de cursos y créditos
        doc.setFontSize(10);
        doc.setFont('helvetica', 'bold');
        doc.text('TOTAL CURSOS HOMOLOGADOS:', 110, yPos + 5);
        doc.text(asignaturasFiltradas.length.toString(), 175, yPos + 5);

        yPos += 12;

        doc.text('TOTAL CRÉDITOS HOMOLOGADOS:', 110, yPos + 5);
        doc.text(totalCreditos.toString(), 175, yPos + 5);

        // Nueva página para firmas
        doc.addPage();
        yPos = 50;

        // Marco decorativo para la página de firmas
        doc.setDrawColor(colorAzulInstitucional[0], colorAzulInstitucional[1], colorAzulInstitucional[2]);
        doc.setLineWidth(0.3);
        doc.roundedRect(10, 10, 195, 260, 2, 2);

        // Encabezado en la página de firmas
        doc.setFontSize(9);
        doc.setTextColor(colorGris[0], colorGris[1], colorGris[2]);
        doc.text('RESOLUCIÓN No. ' + numeroResolucion, 105, 20, { align: 'center' });
        doc.setTextColor(0, 0, 0);
        doc.setFontSize(10);

        // Artículo Segundo
        doc.setFillColor(240, 240, 240);
        doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 2°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 10;

        // Texto del artículo segundo
        const textoArticuloSegundo = `Ordenar al Departamento de Admisiones, Registro y Control Académico, registrar en el sistema la homologación de los cursos académicos relacionados.`;
        const lineasArticulo2 = doc.splitTextToSize(textoArticuloSegundo, 175);
        doc.text(lineasArticulo2, 20, yPos);

        yPos += lineasArticulo2.length * 6 + 10;

        // Artículo Tercero
        doc.setFillColor(240, 240, 240);
        doc.roundedRect(20, yPos - 5, 35, 8, 1, 1, 'F');
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO 3°.', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 10;

        // Texto del artículo tercero
        const textoArticuloTercero = `La presente Resolución rige a partir de la fecha de su expedición.`;
        doc.text(textoArticuloTercero, 20, yPos);

        yPos += 20;

        // Comuníquese y cúmplase
        doc.setFont('helvetica', 'bold');
        doc.text('COMUNÍQUESE Y CÚMPLASE', 105, yPos, { align: 'center' });

        yPos += 10;

        // Fecha de expedición
        doc.setFont('helvetica', 'normal');
        doc.text(`Dada en Popayán, a los ${dia} días del mes de ${mes} de ${año}.`, 105, yPos, { align: 'center' });

        yPos += 30;

        const espacioFirma = 90;

        // =================== SECCIÓN DE FIRMAS MEJORADA ===================

        // Rectángulos de fondo BLANCOS para área de firmas
        doc.setFillColor(255, 255, 255); // Blanco puro
        doc.setDrawColor(220, 220, 220); // Borde gris muy claro
        doc.setLineWidth(0.5);

        // Área del coordinador (siempre presente)
        doc.roundedRect(105 - espacioFirma / 2 - 40, yPos - 25, 80, 75, 3, 3, 'FD');

        // Área del vicerrector (solo si es necesario)
        if (esVistaVicerrector) {
            doc.roundedRect(105 + espacioFirma / 2 - 40, yPos - 25, 80, 75, 3, 3, 'FD');
        }

        // AGREGAR FIRMAS con posicionamiento mejorado
        try {
            // Firma del coordinador (SIEMPRE presente)
            console.log('Agregando firma del coordinador al PDF');
            doc.addImage(
                window.firmaCoordinadorData,
                'PNG',
                (105 - espacioFirma / 2) - 30, // Posición X centrada
                yPos - 18,  // Posición Y ajustada
                60,  // Ancho
                35   // Alto
            );

            // Firma del vicerrector (solo si es vista vicerrector)
            if (esVistaVicerrector) {
                console.log('Agregando firma del vicerrector al PDF');
                doc.addImage(
                    window.firmaVicerrectorData,
                    'PNG',
                    (105 + espacioFirma / 2) - 30, // Posición X centrada
                    yPos - 18,  // Posición Y ajustada
                    60,  // Ancho
                    35   // Alto
                );
            }
        } catch (error) {
            console.error('Error al agregar firmas:', error);
            mostrarAlerta('Advertencia: Error al agregar las firmas al PDF', 'warning');
        }

        // Líneas decorativas bajo las firmas
        doc.setDrawColor(150, 150, 150);
        doc.setLineWidth(0.8);

        // Línea bajo firma del coordinador
        doc.line(
            (105 - espacioFirma / 2) - 35,
            yPos + 25,
            (105 - espacioFirma / 2) + 35,
            yPos + 25
        );

        // Línea bajo firma del vicerrector (si aplica)
        if (esVistaVicerrector) {
            doc.line(
                (105 + espacioFirma / 2) - 35,
                yPos + 25,
                (105 + espacioFirma / 2) + 35,
                yPos + 25
            );
        }

        // Nombres y cargos con mejor espaciado
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);

        // Nombre del coordinador
        doc.text('JUAN PABLO DIAGO RODRÍGUEZ', 105 - espacioFirma / 2, yPos + 35, { align: 'center' });

        // Nombre del vicerrector (si aplica)
        if (esVistaVicerrector) {
            doc.text('SEBASTIAN TORO', 105 + espacioFirma / 2, yPos + 35, { align: 'center' });
        }

        // Cargos
        yPos += 42;
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(9);
        doc.setTextColor(80, 80, 80);

        // Cargo del coordinador
        doc.text(`Decano Facultad ${programa}`, 105 - espacioFirma / 2, yPos, { align: 'center' });

        // Cargo del vicerrector (si aplica)
        if (esVistaVicerrector) {
            doc.text('Vicerrectora Académica', 105 + espacioFirma / 2, yPos, { align: 'center' });
        }

        // =================== FIN SECCIÓN DE FIRMAS ===================

        // Pie de página para todas las páginas
        const totalPages = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);

            // Pie de página
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(100, 100, 100);

            const footerY = 265;
            doc.text('Lic. De Funcionamiento: 12321/79. Resolución MEN Nº. 677 de 2023. Código SNIES: 2849', 105, footerY, { align: 'center' });
            doc.text('Sede principal – Calle 5 Nº 3 – 85 Centro. Popayán - Cauca - Colombia.', 105, footerY + 5, { align: 'center' });

            // Número de página
            doc.setFontSize(7);
            doc.setFont('helvetica', 'bold');
            doc.text(`Página ${i} de ${totalPages}`, 180, footerY + 10, { align: 'right' });
        }

        // Mostrar PDF en el modal
        mostrarPDFEnModal(doc, datosEstudiante, esVistaVicerrector);

        return true;

    } catch (error) {
        console.error('Error al generar PDF con datos:', error);
        mostrarAlerta(`Error al generar PDF: ${error.message}`, 'danger');

        // Restaurar bot
        // Restaurar botón
       const btnGenerar = document.getElementById('btn-generar-pdf') || document.getElementById('btn-generar-pdf-coordinador');
       if (btnGenerar) {
           btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar Resolución PDF';
           btnGenerar.disabled = false;
       }

       return false;
   }
}

// Función auxiliar para mostrar el PDF en modal (si no existe)
function mostrarPDFEnModal(doc, datosEstudiante, esVistaVicerrector) {
   try {
       // Generar el blob del PDF
       const pdfBlob = doc.output('blob');
       const pdfUrl = URL.createObjectURL(pdfBlob);

       // Crear modal si no existe
       let modal = document.getElementById('pdfModal');
       if (!modal) {
           modal = document.createElement('div');
           modal.id = 'pdfModal';
           modal.className = 'modal fade';
           modal.tabIndex = -1;
           modal.setAttribute('role', 'dialog');
           modal.innerHTML = `
               <div class="modal-dialog modal-xl" role="document">
                   <div class="modal-content">
                       <div class="modal-header bg-primary text-white">
                           <h5 class="modal-title">
                               <i class="fas fa-file-pdf mr-2"></i>
                               Resolución de Homologación - ${datosEstudiante.nombre || 'Estudiante'}
                           </h5>
                           <button type="button" class="close text-white" data-dismiss="modal">
                               <span>&times;</span>
                           </button>
                       </div>
                       <div class="modal-body p-0">
                           <div class="d-flex justify-content-between align-items-center p-3 bg-light">
                               <div class="btn-group" role="group">
                                   <button type="button" class="btn btn-success" onclick="descargarPDF()">
                                       <i class="fas fa-download mr-2"></i>Descargar PDF
                                   </button>
                                   <button type="button" class="btn btn-info" onclick="imprimirPDF()">
                                       <i class="fas fa-print mr-2"></i>Imprimir
                                   </button>
                               </div>
                               <small class="text-muted">
                                   Resolución generada el ${new Date().toLocaleDateString('es-ES')}
                               </small>
                           </div>
                           <iframe id="pdfViewer"
                                   style="width: 100%; height: 70vh; border: none;"
                                   src="${pdfUrl}">
                           </iframe>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">
                               <i class="fas fa-times mr-2"></i>Cerrar
                           </button>
                       </div>
                   </div>
               </div>
           `;
           document.body.appendChild(modal);
       } else {
           // Actualizar el contenido del modal existente
           const iframe = modal.querySelector('#pdfViewer');
           const title = modal.querySelector('.modal-title');
           if (iframe) iframe.src = pdfUrl;
           if (title) {
               title.innerHTML = `
                   <i class="fas fa-file-pdf mr-2"></i>
                   Resolución de Homologación - ${datosEstudiante.nombre || 'Estudiante'}
               `;
           }
       }

       // Guardar referencia global para funciones de descarga/impresión
       window.currentPDFDoc = doc;
       window.currentPDFUrl = pdfUrl;

       // Mostrar modal
       $(modal).modal('show');

       // Limpiar URL cuando se cierre el modal
       $(modal).on('hidden.bs.modal', function() {
           if (window.currentPDFUrl) {
               URL.revokeObjectURL(window.currentPDFUrl);
               window.currentPDFUrl = null;
           }
       });

       console.log('PDF generado y mostrado en modal exitosamente');

   } catch (error) {
       console.error('Error al mostrar PDF en modal:', error);
       mostrarAlerta('Error al mostrar el PDF', 'danger');
   }
}

// Función para descargar PDF
function descargarPDF() {
   try {
       if (window.currentPDFDoc) {
           const fechaActual = new Date();
           const timestamp = fechaActual.toISOString().slice(0, 10);
           const nombreArchivo = `Resolucion_Homologacion_${timestamp}.pdf`;

           window.currentPDFDoc.save(nombreArchivo);

           mostrarAlerta('PDF descargado exitosamente', 'success');
       } else {
           mostrarAlerta('Error: No hay PDF disponible para descargar', 'danger');
       }
   } catch (error) {
       console.error('Error al descargar PDF:', error);
       mostrarAlerta('Error al descargar el PDF', 'danger');
   }
}

// Función para imprimir PDF
function imprimirPDF() {
   try {
       if (window.currentPDFUrl) {
           // Abrir en nueva ventana para imprimir
           const printWindow = window.open(window.currentPDFUrl, '_blank');

           if (printWindow) {
               printWindow.onload = function() {
                   printWindow.print();
               };
           } else {
               // Fallback si se bloquean popups
               mostrarAlerta('Por favor, permita ventanas emergentes para imprimir', 'warning');
           }
       } else {
           mostrarAlerta('Error: No hay PDF disponible para imprimir', 'danger');
       }
   } catch (error) {
       console.error('Error al imprimir PDF:', error);
       mostrarAlerta('Error al imprimir el PDF', 'danger');
   }
}



// Función para validar que las dependencias estén cargadas
function validarDependencias() {
   const dependencias = {
       'jsPDF': typeof jspdf !== 'undefined',
       'autoTable': typeof jspdf !== 'undefined' && typeof jspdf.jsPDF.prototype.autoTable === 'function',
       'jQuery': typeof $ !== 'undefined'
   };

   const faltantes = Object.keys(dependencias).filter(dep => !dependencias[dep]);

   if (faltantes.length > 0) {
       console.warn('Dependencias faltantes:', faltantes);
       mostrarAlerta(`Advertencia: Faltan dependencias - ${faltantes.join(', ')}`, 'warning');
       return false;
   }

   return true;
}

// Función de inicialización (llamar cuando se cargue la página)
function inicializarGeneradorPDF() {
   // Validar dependencias
   if (!validarDependencias()) {
       console.error('No se pueden inicializar las funciones PDF debido a dependencias faltantes');
       return false;
   }

   // Verificar si los botones existen y agregar eventos
   const btnGenerarCoordinador = document.getElementById('btn-generar-pdf-coordinador');
   const btnGenerarVicerrector = document.getElementById('btn-generar-pdf');

   if (btnGenerarCoordinador) {
       btnGenerarCoordinador.addEventListener('click', function() {
           // Aquí deberías obtener los datos reales de tu aplicación
           const datosEjemplo = obtenerDatosParaPDF();
           generarPDFConDatos(
               datosEjemplo.homologaciones,
               datosEjemplo.estudiante,
               datosEjemplo.solicitud,
               false // Vista coordinador
           );
       });
   }

   if (btnGenerarVicerrector) {
       btnGenerarVicerrector.addEventListener('click', function() {
           // Aquí deberías obtener los datos reales de tu aplicación
           const datosEjemplo = obtenerDatosParaPDF();
           generarPDFConDatos(
               datosEjemplo.homologaciones,
               datosEjemplo.estudiante,
               datosEjemplo.solicitud,
               true // Vista vicerrector
           );
       });
   }

   console.log('Generador PDF inicializado correctamente');
   return true;
}

// Función para obtener datos de ejemplo (reemplazar con datos reales)
function obtenerDatosParaPDF() {
   // Esta función debe ser reemplazada con la lógica real de tu aplicación
   return {
       homologaciones: {
           homologaciones: [
               {
                   asignatura_origen_nombre: "Cálculo Diferencial",
                   codigo_destino: "MAT101",
                   asignatura_destino_nombre: "Matemáticas I",
                   semestre: "1",
                   creditos: "4",
                   nota_destino: "4.2"
               },
               {
                   asignatura_origen_nombre: "Programación I",
                   codigo_destino: "SIS201",
                   asignatura_destino_nombre: "Fundamentos de Programación",
                   semestre: "2",
                   creditos: "3",
                   nota_destino: "4.5"
               }
           ]
       },
       estudiante: {
           nombre: "Juan Carlos Pérez García",
           identificacion: "1234567890"
       },
       solicitud: {
           universidad_origen: "Universidad Nacional de Colombia",
           programa_destino: "Ingeniería de Sistemas"
       }
   };
}

// Auto-inicializar cuando se cargue el DOM
document.addEventListener('DOMContentLoaded', function() {
   // Esperar un poco para asegurar que todas las dependencias estén cargadas
   setTimeout(inicializarGeneradorPDF, 500);
});

// Exponer funciones globalmente para uso externo
window.generarPDFConDatos = generarPDFConDatos;
window.mostrarPDFEnModal = mostrarPDFEnModal;
window.descargarPDF = descargarPDF;
window.imprimirPDF = imprimirPDF;
window.inicializarGeneradorPDF = inicializarGeneradorPDF;
// Función para mostrar el PDF en el modal y guardar SOLO la versión final
function mostrarPDFEnModal(doc, datosEstudiante, esVistaVicerrector) {
    try {
        // Crear base64 del PDF
        const pdfData = doc.output('datauristring');

        // Mostrar preview
        const pdfPreview = document.getElementById('pdf-preview-content');
        if (pdfPreview) {
            pdfPreview.innerHTML = '';

            const iframe = document.createElement('iframe');
            iframe.style.width = '100%';
            iframe.style.height = '600px';
            iframe.style.border = '1px solid #ddd';
            iframe.src = pdfData;

            pdfPreview.appendChild(iframe);

            // Mostrar modal
            $('#pdf-preview-modal').modal('show');
        } else {
            throw new Error('No se encontró el elemento pdf-preview-content');
        }

        // IMPORTANTE: Subir el PDF final con todas las firmas
        // Solo si es la vista del vicerrector (donde tenemos ambas firmas)
        if (esVistaVicerrector) {
            // Obtener el ID de homologación
            let id = null;
            if (typeof homologacionId !== 'undefined' && homologacionId) {
                id = homologacionId;
            } else if (typeof cargarHomologacionId === 'function') {
                id = cargarHomologacionId();
            } else {
                // Define la función si no existe
                window.cargarHomologacionId = function () {
                    return new URLSearchParams(window.location.search).get('id') ||
                        document.querySelector('[data-homologacion-id]')?.dataset.homologacionId;
                };
                id = cargarHomologacionId();
            }

            if (!id) {
                console.error('No se pudo obtener el ID de homologación para guardar el PDF');
                mostrarAlerta('No se pudo obtener el ID de homologación para guardar el PDF', 'warning');
            } else {
                // Normalizar el ID si es necesario
                let apiHomologacionId = id;
                if (typeof normalizarHomologacionId === 'function') {
                    apiHomologacionId = normalizarHomologacionId(id);
                } else if (typeof window.normalizarHomologacionId === 'function') {
                    apiHomologacionId = window.normalizarHomologacionId(id);
                }

                // Crear archivo PDF para guardar - Asegurarnos que sea el PDF completo con todas las firmas
                const pdfBlob = doc.output('blob');
                const fecha = new Date().toISOString().split('T')[0];
                const nombreArchivo = `resolucion_homologacion_FINAL_${apiHomologacionId}_${fecha}.pdf`;
                const pdfFile = new File([pdfBlob], nombreArchivo, { type: 'application/pdf' });

                // Verificar que la función para subir existe
                if (typeof subirSoloPDFResolucion !== 'function') {
                    console.error('La función subirSoloPDFResolucion no está disponible');
                    mostrarAlerta('No se pudo guardar el PDF final. La función de subida no está disponible.', 'danger');
                } else {
                    // Subir el PDF FINAL con TODAS LAS FIRMAS al servidor
                    subirSoloPDFResolucion(apiHomologacionId, pdfFile)
                        .then(data => {
                            console.log('PDF FINAL con TODAS LAS FIRMAS subido exitosamente:', data);
                            if (typeof actualizarInterfazConPDF === 'function') {
                                actualizarInterfazConPDF(data);
                            }
                            mostrarAlerta('PDF con TODAS las firmas guardado correctamente en el sistema', 'success');
                        })
                        .catch(error => {
                            console.error('Error al subir PDF FINAL:', error);
                            mostrarAlerta(`Error al guardar el PDF en el sistema: ${error.message}`, 'danger');
                        });
                }
            }
        } else {
            console.log('Vista de coordinador: PDF mostrado pero NO guardado en el backend (se guardará en la fase final con vicerrector)');
        }

        // Configurar botón de confirmar
        const btnConfirmar = document.getElementById('btn-confirmar-pdf');
        if (btnConfirmar) {
            // Definir la acción según la vista
            if (esVistaVicerrector) {
                btnConfirmar.onclick = function () {
                    // Cerrar el modal
                    $('#pdf-preview-modal').modal('hide');

                    // Descargar PDF final para el usuario
                    const nombreArchivo = `Homologacion_Final_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
                    doc.save(nombreArchivo);

                    // Mostrar mensaje de éxito
                    mostrarAlerta('Resolución de homologación finalizada y guardada correctamente', 'success');
                };
            } else {
                btnConfirmar.onclick = function () {
                    // Cerrar el modal
                    $('#pdf-preview-modal').modal('hide');

                    // Enviar a vicerrector si estamos en la vista de coordinador
                    if (typeof confirmarYEnviarAVicerrector === 'function') {
                        // Si la firma del coordinador existe, pasarla a la función
                        if (window.firmaCoordinadorData) {
                            confirmarYEnviarAVicerrector(window.firmaCoordinadorData);
                        } else {
                            // Intentar generar una firma por defecto
                            const firmaDefault = typeof generarFirmaDefault === 'function' ? generarFirmaDefault('coordinador') : null;
                            confirmarYEnviarAVicerrector(firmaDefault);
                        }
                    } else {
                        console.error('La función confirmarYEnviarAVicerrector no está disponible');
                        mostrarAlerta('No se pudo enviar al vicerrector. La función no está disponible.', 'danger');
                    }

                    // Descargar PDF para el usuario
                    const nombreArchivo = `Homologacion_Coordinador_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
                    doc.save(nombreArchivo);
                };
            }
        }

        // Botón para solo descargar sin confirmar
        const btnDescargar = document.getElementById('btn-descargar-solo-pdf');
        if (btnDescargar) {
            btnDescargar.onclick = function () {
                const prefijo = esVistaVicerrector ? 'Homologacion_Final' : 'Homologacion_Coordinador';
                const nombreArchivo = `${prefijo}_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
                doc.save(nombreArchivo);
                mostrarAlerta('PDF descargado correctamente', 'success');
            };
        }

        // Restaurar botón de generar PDF
        const btnGenerar = document.getElementById('btn-generar-pdf') || document.getElementById('btn-generar-pdf-coordinador');
        if (btnGenerar) {
            btnGenerar.innerHTML = '<i class="fas fa-file-pdf mr-2"></i> Generar Resolución PDF';
            btnGenerar.disabled = false;
        }

        return true;
    } catch (error) {
        console.error('Error al mostrar el PDF en el modal:', error);
        mostrarAlerta(`Error al mostrar el PDF: ${error.message}`, 'danger');

        // Si hay error, intentar descargar directamente
        try {
            const prefijo = esVistaVicerrector ? 'Homologacion_Final' : 'Homologacion_Coordinador';
            const nombreArchivo = `${prefijo}_${datosEstudiante.nombre.replace(/\s+/g, '_')}_${datosEstudiante.identificacion}.pdf`;
            doc.save(nombreArchivo);
            mostrarAlerta('PDF generado y descargado directamente', 'warning');
            return true;
        } catch (e) {
            console.error('Error al descargar PDF directamente:', e);
            mostrarAlerta('No se pudo generar el PDF', 'danger');
            return false;
        }
    }
}



// Función auxiliar para verificar si la firma está cargada
function verificarFirmaCoordinador() {
    const firmaData = window.firmaCoordinadorData ||
        document.getElementById('firma_coordinador_data')?.value ||
        localStorage.getItem('firmaCoordinadorData');

    if (!firmaData) {
        mostrarAlerta('Por favor, carga la firma del coordinador antes de continuar.', 'warning');
        return false;
    }

    // Asegurar que esté disponible globalmente
    if (!window.firmaCoordinadorData) {
        window.firmaCoordinadorData = firmaData;
    }

    return true;
}

// Función para limpiar datos de firma (útil para testing o reset)
function limpiarFirmaCoordinador() {
    // Limpiar todas las referencias a la firma
    window.firmaCoordinadorData = null;
    localStorage.removeItem('firmaCoordinadorData');

    const hiddenInput = document.getElementById('firma_coordinador_data');
    const preview = document.getElementById('firma-preview');
    const generateBtn = document.getElementById('btn-generar-pdf-coordinador');
    const inputFirma = document.getElementById('firma');
    const fileLabel = document.querySelector('label[for="firma"]');

    if (hiddenInput) hiddenInput.value = '';
    if (preview) preview.innerHTML = '<p style="color: #19407b;" class="mb-0">Vista previa de la firma</p>';
    if (generateBtn) {
        generateBtn.disabled = true;
        generateBtn.style.backgroundColor = '#6c757d';
    }
    if (inputFirma) inputFirma.value = '';
    if (fileLabel) fileLabel.textContent = 'Seleccionar archivo...';

    console.log('Datos de firma del coordinador limpiados');
}

// Event listeners principales
document.addEventListener('DOMContentLoaded', function () {
    console.log('Inicializando sistema de firmas del coordinador...');

    // Event listener para el botón de generar PDF del coordinador
    const btnGenerar = document.getElementById('btn-generar-pdf-coordinador');
    if (btnGenerar) {
        btnGenerar.addEventListener('click', generarPDFCoordinador);
        console.log('Event listener para btn-generar-pdf-coordinador configurado');
    }

    // Event listener para el input de archivo de firma
    const inputFirma = document.getElementById('firma');
    if (inputFirma) {
        inputFirma.addEventListener('change', handleFirmaCoordinadorUpload);
        console.log('Event listener para input de firma configurado');
    }

    // Verificar si hay una firma guardada al cargar la página
    const firmaGuardada = localStorage.getItem('firmaCoordinadorData');
    if (firmaGuardada) {
        window.firmaCoordinadorData = firmaGuardada;

        // Restaurar preview si los elementos existen
        const preview = document.getElementById('firma-preview');
        const generateBtn = document.getElementById('btn-generar-pdf-coordinador');
        const hiddenInput = document.getElementById('firma_coordinador_data');

        if (preview && generateBtn && hiddenInput) {
            preview.innerHTML = `<img src="${firmaGuardada}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
            hiddenInput.value = firmaGuardada;
            generateBtn.disabled = false;
            generateBtn.style.backgroundColor = '#0277bd';

            // Actualizar también el label del archivo
            const fileLabel = document.querySelector('label[for="firma"]');
            if (fileLabel) {
                fileLabel.textContent = 'Firma cargada anteriormente';
            }
        }

        console.log('Firma del coordinador restaurada desde localStorage');
    }

    // Event listener adicional para el botón principal de PDF (si existe)
    const btnGenerarPrincipal = document.getElementById('btn-generar-pdf');
    if (btnGenerarPrincipal) {
        btnGenerarPrincipal.addEventListener('click', function () {
            // Verificar si estamos en vista de coordinador o vicerrector
            const esVistaVicerrector = window.location.pathname.includes('vicerrector') ||
                document.body.classList.contains('vista-vicerrector') ||
                document.querySelector('[data-vista="vicerrector"]') !== null;

            generarPDF(esVistaVicerrector);
        });
        console.log('Event listener para btn-generar-pdf principal configurado');
    }

    console.log('Sistema de firmas del coordinador inicializado correctamente');
});

// Funciones auxiliares adicionales
window.firmaCoordinadorUtils = {
    verificar: verificarFirmaCoordinador,
    limpiar: limpiarFirmaCoordinador,
    obtenerFirma: function () {
        return window.firmaCoordinadorData || localStorage.getItem('firmaCoordinadorData');
    },
    establecerFirma: function (firmaData) {
        window.firmaCoordinadorData = firmaData;
        localStorage.setItem('firmaCoordinadorData', firmaData);

        // Actualizar elementos del DOM si existen
        const hiddenInput = document.getElementById('firma_coordinador_data');
        const preview = document.getElementById('firma-preview');
        const generateBtn = document.getElementById('btn-generar-pdf-coordinador');

        if (hiddenInput) hiddenInput.value = firmaData;
        if (preview) preview.innerHTML = `<img src="${firmaData}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
        if (generateBtn) {
            generateBtn.disabled = false;
            generateBtn.style.backgroundColor = '#0277bd';
        }

        console.log('Firma del coordinador establecida programáticamente');
    }
};

// Exportar funciones principales para uso externo
window.generarPDFCoordinador = generarPDFCoordinador;
window.handleFirmaCoordinadorUpload = handleFirmaCoordinadorUpload;
window.verificarFirmaCoordinador = verificarFirmaCoordinador;
window.limpiarFirmaCoordinador = limpiarFirmaCoordinador;

console.log('Script de firma del coordinador cargado completamente');


// Manejo de la carga de firma del vicerrector
document.getElementById('firma-vicerrector').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('firma-vicerrector-preview');
    const placeholder = document.getElementById('firma-vicerrector-placeholder');
    const img = document.getElementById('img-firma-vicerrector');
    const hiddenInput = document.getElementById('firma_vicerrector_data');
    const btnGenerar = document.getElementById('btn-generar-pdf');
    const fileLabel = document.querySelector('label[for="firma-vicerrector"]');

    if (file) {
        // Validar tipo de archivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Por favor, selecciona un archivo de imagen válido (JPG, PNG, GIF)');
            this.value = '';
            return;
        }

        // Validar tamaño del archivo (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('El archivo es demasiado grande. Máximo 5MB permitido.');
            this.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event) {
            // Mostrar vista previa
            placeholder.style.display = 'none';
            img.src = event.target.result;
            img.style.display = 'block';

            // Guardar datos en campo oculto
            hiddenInput.value = event.target.result;

            // Habilitar botón de generar PDF
            btnGenerar.disabled = false;

            // Actualizar etiqueta del archivo
            fileLabel.textContent = file.name;
            fileLabel.style.color = '#28a745';
        };

        reader.onerror = function() {
            alert('Error al cargar el archivo. Inténtalo de nuevo.');
        };

        reader.readAsDataURL(file);
    } else {
        // Resetear si no hay archivo
        placeholder.style.display = 'block';
        img.style.display = 'none';
        img.src = '';
        hiddenInput.value = '';
        btnGenerar.disabled = true;
        fileLabel.textContent = 'Seleccionar archivo...';
        fileLabel.style.color = '#0277bd';
    }
});

// Función para generar PDF con las firmas
document.getElementById('btn-generar-pdf').addEventListener('click', function() {
    const firmaVicerrector = document.getElementById('firma_vicerrector_data').value;
    const firmaCoordinador = document.getElementById('firma_coordinador_data').value;

    if (!firmaVicerrector) {
        alert('Por favor, sube la firma del vicerrector primero.');
        return;
    }

    // Mostrar indicador de carga
    const btnOriginalText = this.innerHTML;
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generando PDF...';
    this.disabled = true;

    // Crear el PDF usando jsPDF
    generarPDFConFirmas(firmaVicerrector, firmaCoordinador)
        .then(() => {
            // Restaurar botón
            this.innerHTML = btnOriginalText;
            this.disabled = false;
        })
        .catch((error) => {
            console.error('Error al generar PDF:', error);
            alert('Error al generar el PDF. Inténtalo de nuevo.');
            this.innerHTML = btnOriginalText;
            this.disabled = false;
        });
});

// Función para generar el PDF con las firmas
async function generarPDFConFirmas(firmaVicerrector, firmaCoordinador) {
    try {
        // Verificar que jsPDF esté cargado
        if (typeof window.jsPDF === 'undefined') {
            throw new Error('jsPDF no está cargado');
        }

        const { jsPDF } = window.jsPDF;
        const doc = new jsPDF();

        // Configuración del documento
        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();

        // Título del documento
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text('DOCUMENTO OFICIAL', pageWidth / 2, 30, { align: 'center' });

        // Línea separadora
        doc.setLineWidth(0.5);
        doc.line(20, 40, pageWidth - 20, 40);

        // Contenido del documento (aquí puedes agregar el contenido que necesites)
        doc.setFontSize(12);
        doc.setFont('helvetica', 'normal');
        doc.text('Contenido del documento...', 20, 60);

        // Sección de firmas
        const firmaY = pageHeight - 80;

        // Firma del Coordinador (si existe)
        if (firmaCoordinador) {
            doc.text('Coordinador:', 30, firmaY - 10);
            await agregarFirmaAlPDF(doc, firmaCoordinador, 30, firmaY, 60, 30);
            doc.line(20, firmaY + 35, 80, firmaY + 35);
            doc.setFontSize(10);
            doc.text('Firma del Coordinador', 35, firmaY + 40);
        }

        // Firma del Vicerrector
        doc.setFontSize(12);
        doc.text('Vicerrector:', 130, firmaY - 10);
        await agregarFirmaAlPDF(doc, firmaVicerrector, 130, firmaY, 60, 30);
        doc.line(120, firmaY + 35, 180, firmaY + 35);
        doc.setFontSize(10);
        doc.text('Firma del Vicerrector', 135, firmaY + 40);

        // Fecha y hora
        const now = new Date();
        const fechaHora = now.toLocaleString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        doc.setFontSize(8);
        doc.text(`Generado el: ${fechaHora}`, pageWidth - 20, pageHeight - 10, { align: 'right' });

        // Descargar el PDF
        const filename = `documento_firmado_${now.getFullYear()}${(now.getMonth()+1).toString().padStart(2,'0')}${now.getDate().toString().padStart(2,'0')}.pdf`;
        doc.save(filename);

        // Mostrar mensaje de éxito
        mostrarMensajeExito('PDF generado exitosamente');

    } catch (error) {
        console.error('Error en generarPDFConFirmas:', error);
        throw error;
    }
}

// Función auxiliar para agregar firma al PDF
function agregarFirmaAlPDF(doc, firmaBase64, x, y, width, height) {
    return new Promise((resolve, reject) => {
        try {
            // Agregar la imagen de la firma al PDF
            doc.addImage(firmaBase64, 'PNG', x, y, width, height);
            resolve();
        } catch (error) {
            console.error('Error al agregar firma al PDF:', error);
            reject(error);
        }
    });
}

// Función para mostrar mensajes de éxito
function mostrarMensajeExito(mensaje) {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        z-index: 9999;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        font-weight: bold;
    `;
    notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${mensaje}`;

    document.body.appendChild(notification);

    // Remover después de 3 segundos
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 3000);
}

// Función para limpiar formulario (opcional)
function limpiarFormularioFirmas() {
    document.getElementById('firma-vicerrector').value = '';
    document.getElementById('firma_vicerrector_data').value = '';
    document.getElementById('firma_coordinador_data').value = '';

    // Resetear vista previa
    const placeholder = document.getElementById('firma-vicerrector-placeholder');
    const img = document.getElementById('img-firma-vicerrector');
    const btnGenerar = document.getElementById('btn-generar-pdf');
    const fileLabel = document.querySelector('label[for="firma-vicerrector"]');

    placeholder.style.display = 'block';
    img.style.display = 'none';
    img.src = '';
    btnGenerar.disabled = true;
    fileLabel.textContent = 'Seleccionar archivo...';
    fileLabel.style.color = '#0277bd';
}

/**
 * Sube solo el PDF de resolución al endpoint específico
 * @param {string} homologacionId - ID de la homologación
 * @param {File} pdfFile - El archivo PDF
 * @returns {Promise} - Promesa con el resultado de la operación
 */
function subirSoloPDFResolucion(homologacionId, pdfFile) {
    console.log('Iniciando subida de PDF de resolución...');

    // Verificar que tengamos un ID válido
    if (!homologacionId) {
        // Define la función si no existe
        if (typeof cargarHomologacionId !== 'function') {
            window.cargarHomologacionId = function () {
                return new URLSearchParams(window.location.search).get('id') ||
                    document.querySelector('[data-homologacion-id]')?.dataset.homologacionId;
            };
        }
        homologacionId = cargarHomologacionId();
        console.log('ID cargado con cargarHomologacionId:', homologacionId);
    }

    if (!homologacionId) {
        return Promise.reject(new Error('ID de homologación no válido. Debe guardar la homologación antes de generar el PDF.'));
    }

    // Define API_BASE_URL si no existe
    if (typeof API_BASE_URL === 'undefined') {
        window.API_BASE_URL = 'http://127.0.0.1:8000/api';
        console.log('API_BASE_URL definido:', API_BASE_URL);
    }

    // Normalizar el ID para la API (si es necesario)
    if (typeof normalizarHomologacionId !== 'function') {
        window.normalizarHomologacionId = id => id;
    }
    const apiHomologacionId = normalizarHomologacionId(homologacionId);
    console.log('ID normalizado para API:', apiHomologacionId);

    // Verificar que tengamos un archivo válido
    if (!pdfFile || !(pdfFile instanceof File)) {
        return Promise.reject(new Error('Archivo PDF no válido'));
    }

    console.log('Subiendo PDF al servidor:', {
        endpoint: `${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}/pdf`,
        fileName: pdfFile.name,
        fileSize: pdfFile.size
    });

    // Crear FormData para el archivo
    const formData = new FormData();
    formData.append('ruta_pdf_resolucion', pdfFile);

    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.warn('No se encontró token CSRF en el documento');
    }

    // Verificar conectividad con el servidor antes de la subida
    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken || ''
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al verificar conectividad: ${response.status}`);
            }
            console.log('Conectividad con el servidor verificada, procediendo con la subida');

            // Ahora intentar con el endpoint específico para PDF
            return fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}/pdf`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: formData
            });
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Respuesta del servidor:', text);
                    throw new Error(`Error en la respuesta del servidor: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta exitosa:', data);
            actualizarInterfazConPDF(data);
            return data;
        })
        .catch(error => {
            console.error('Error en la operación de subida:', error);
            // Si el error es por conectividad, intentar el método completo
            if (error.message.includes('conectividad') || error.message.includes('Failed to fetch')) {
                console.log('Intentando método alternativo debido a problemas de conexión...');
                return intentarMetodoCompleto(apiHomologacionId, pdfFile);
            }
            throw error;
        });
}
/**
 * Método que implementa la misma lógica de guardarHomologaciones para subir el PDF
 * @param {string} homologacionId - ID de homologación
 * @param {File} pdfFile - Archivo PDF
 * @returns {Promise} - Promesa con el resultado
 */
function intentarMetodoCompleto(homologacionId, pdfFile) {
    console.log('Utilizando método completo para subir PDF...');

    // Obtener datos actuales de homologaciones
    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error al obtener datos: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (!data || !data.datos) {
                throw new Error('No se pudieron obtener los datos de la homologación');
            }

            console.log('Datos obtenidos del servidor:', data.datos);

            // Preparar un array válido de homologaciones basado en los datos existentes
            let homologacionesArray = [];

            if (data.datos.homologaciones && Array.isArray(data.datos.homologaciones)) {
                homologacionesArray = data.datos.homologaciones.map(h => ({
                    asignatura_origen_id: h.asignatura_origen_id,
                    asignatura_destino_id: h.asignatura_destino_id || 1, // Asegurar que sea entero
                    nota_destino: h.nota_destino || "0",
                    comentarios: h.comentarios || ''
                }));
            } else if (data.datos.asignaturas_origen && data.datos.asignaturas_destino) {
                homologacionesArray = data.datos.asignaturas_origen.map((asignatura, index) => {
                    const destino = data.datos.asignaturas_destino[index] || {};
                    return {
                        asignatura_origen_id: asignatura.id,
                        asignatura_destino_id: destino.id || 1, // Asegurar que sea entero
                        nota_destino: destino.nota_destino || "0",
                        comentarios: destino.comentarios || ''
                    };
                });
            }

            // Si aún no tenemos homologaciones, crear una entrada mínima válida
            if (homologacionesArray.length === 0) {
                homologacionesArray = [{
                    asignatura_origen_id: 1,
                    asignatura_destino_id: 1, // Entero válido
                    nota_destino: "0",
                    comentarios: ''
                }];
            }

            console.log('Homologaciones preparadas para enviar:', homologacionesArray);

            // Crear FormData con los datos necesarios
            const formData = new FormData();
            formData.append('_method', 'PUT'); // Simular PUT para envío de archivos
            formData.append('ruta_pdf_resolucion', pdfFile);

            // Asegurar que homologaciones se envía correctamente como array
            homologacionesArray.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                    formData.append(`homologaciones[${index}][${key}]`, item[key]);
                });
            });

            // Verificar los datos del FormData (solo para debug)
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Enviar la solicitud con el archivo PDF y los datos existentes
            return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`, {
                method: 'POST', // Usando POST con _method=PUT
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: formData
            });
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error al subir PDF: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // Crear un objeto de respuesta estandarizado
            return {
                mensaje: data.mensaje || 'PDF actualizado correctamente',
                ruta_pdf_resolucion: data.ruta_pdf_resolucion || (data.datos && data.datos.ruta_pdf_resolucion) || '',
                url_pdf_resolucion: data.url_pdf_resolucion || (data.ruta_pdf_resolucion ? `/storage/${data.ruta_pdf_resolucion}` : '')
            };
        });
}

function descargarPDF() {
    console.log('Iniciando generación de PDF de resolución...');

    // Asegurarnos de tener el ID necesario
    let id = null;

    // Intentar obtener el ID de diferentes fuentes
    if (typeof homologacionId !== 'undefined' && homologacionId) {
        id = homologacionId;
        console.log('Usando homologacionId global:', id);
    } else {
        id = cargarHomologacionId();
        console.log('ID cargado con cargarHomologacionId:', id);
    }

    if (!id) {
        console.error('No se pudo obtener el ID de homologación.');
        alert('Error: Debe guardar la homologación antes de generar el PDF');
        return;
    }

    // Normalizar el ID para la API (si es necesario)
    const apiHomologacionId = normalizarHomologacionId ? normalizarHomologacionId(id) : id;
    console.log('ID normalizado para API:', apiHomologacionId);

    // Mostrar indicador de carga
    const btnDescargar = document.getElementById('btn-descargar-pdf');
    if (!btnDescargar) {
        console.error('No se encontró el botón de descargar PDF');
        return;
    }

    const textoOriginal = btnDescargar.innerHTML;
    btnDescargar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
    btnDescargar.disabled = true;

    try {
        // Obtener datos de homologación para generar el PDF
        fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Error al obtener datos: ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos para generación de PDF obtenidos:', data);

                if (!data || !data.datos) {
                    throw new Error('No se pudieron obtener los datos de la homologación');
                }

                const homologacionData = data.datos;

                // Preparar datos para la generación del PDF
                const datosEstudiante = {
                    nombre: homologacionData.estudiante || 'Estudiante',
                    identificacion: homologacionData.numero_identificacion || 'No disponible'
                };

                const datosSolicitud = {
                    universidad_origen: homologacionData.universidad_origen || 'Universidad Externa',
                    programa_destino: homologacionData.programa_destino || 'Programa Actual'
                };

                // Preparar datos de homologación
                const homologaciones = [];
                if (homologacionData.asignaturas_origen && homologacionData.asignaturas_destino) {
                    homologacionData.asignaturas_origen.forEach((asignatura, index) => {
                        const destino = homologacionData.asignaturas_destino[index] || {};

                        homologaciones.push({
                            asignatura_origen_nombre: asignatura.nombre || 'No disponible',
                            codigo_destino: destino.codigo || 'N/A',
                            asignatura_destino_nombre: destino.nombre || 'No disponible',
                            semestre: destino.semestre || 'N/A',
                            creditos: asignatura.creditos || destino.creditos || 'N/A',
                            nota_destino: destino.nota_destino || 'N/A'
                        });
                    });
                }

                const datosHomologacion = {
                    homologaciones: homologaciones
                };

                // Crear las firmas si no existen
                if (!window.firmaCoordinadorData) {
                    window.firmaCoordinadorData = generarFirmaDefault('coordinador');
                    console.log('Se generó una firma por defecto para el coordinador');
                }

                // Verificar si generarPDFConDatos está disponible
                if (typeof generarPDFConDatos === 'function') {
                    // Generar el PDF con los datos - NO se sobrescribe mostrarPDFEnModal aquí
                    console.log('Llamando a generarPDFConDatos...');
                    const resultadoGeneracion = generarPDFConDatos(datosHomologacion, datosEstudiante, datosSolicitud, false);

                    if (!resultadoGeneracion) {
                        throw new Error('No se pudo generar el PDF con datos');
                    }
                } else {
                    throw new Error('Función generarPDFConDatos no disponible');
                }
            })
            .catch(error => {
                console.error('Error al generar o procesar el PDF:', error);
                alert(`Error: ${error.message}`);

                // Restaurar botón
                if (btnDescargar) {
                    btnDescargar.innerHTML = textoOriginal;
                    btnDescargar.disabled = false;
                }
            });
    } catch (error) {
        console.error('Error en la función descargarPDF:', error);
        alert(`Error al generar el PDF: ${error.message}`);

        // Restaurar botón
        if (btnDescargar) {
            btnDescargar.innerHTML = textoOriginal;
            btnDescargar.disabled = false;
        }
    }
}

/**
 * Actualiza la interfaz de usuario con la información del PDF
 * @param {Object} data - Datos de respuesta del servidor
 */
function actualizarInterfazConPDF(data) {
    try {
        console.log('Actualizando interfaz con datos del PDF:', data);

        // Actualizar link del PDF si existe en la interfaz
        const pdfLink = document.getElementById('link-pdf-resolucion');
        if (pdfLink) {
            let rutaPDF = '';

            if (data.url_pdf_resolucion) {
                rutaPDF = data.url_pdf_resolucion;
            } else if (data.ruta_pdf_resolucion) {
                rutaPDF = `/storage/${data.ruta_pdf_resolucion}`;
            } else if (data.datos && data.datos.ruta_pdf_resolucion) {
                rutaPDF = `/storage/${data.datos.ruta_pdf_resolucion}`;
            } else if (data.datos && data.datos.url_pdf_resolucion) {
                rutaPDF = data.datos.url_pdf_resolucion;
            }

            if (rutaPDF) {
                pdfLink.href = rutaPDF;
                pdfLink.style.display = 'inline';

                // Actualizar texto del enlace si tiene un span
                const pdfLinkText = pdfLink.querySelector('span');
                if (pdfLinkText) {
                    pdfLinkText.textContent = 'Ver PDF de resolución';
                }
            }
        }

        // Actualizar campo oculto si existe
        const pdfPathField = document.getElementById('ruta_pdf_resolucion');
        const rutaPDF = data.ruta_pdf_resolucion || (data.datos && data.datos.ruta_pdf_resolucion);
        if (pdfPathField && rutaPDF) {
            pdfPathField.value = rutaPDF;
        }

        // Actualizar cualquier elemento que muestre el nombre del archivo
        const pdfFileName = document.getElementById('pdf-file-name');
        if (pdfFileName && rutaPDF) {
            const nombreArchivo = rutaPDF.split('/').pop();
            pdfFileName.textContent = nombreArchivo;
        }

        // Actualizar estado visual en la interfaz
        const estadoPDF = document.getElementById('estado-pdf');
        if (estadoPDF) {
            estadoPDF.innerHTML = '<span class="badge badge-success">PDF Cargado</span>';
        }

        // Mostrar contenedor de PDF si existe
        const pdfContainer = document.getElementById('pdf-container');
        if (pdfContainer) {
            pdfContainer.style.display = 'block';
        }
    } catch (e) {
        console.error('Error al actualizar interfaz con datos del PDF:', e);
    }
}

/**
 * Función para definir alertaEnProceso si no existe
 */
if (typeof alertaEnProceso !== 'function') {
    function alertaEnProceso(mensaje) {
        console.log('Alerta en proceso:', mensaje);
        alert(mensaje);
    }
}

/**
 * Configura el botón de descargar PDF para guardar la ruta en la API
 */
document.addEventListener('DOMContentLoaded', function () {
    const btnDescargarPDF = document.getElementById('btn-descargar-pdf');
    if (btnDescargarPDF) {
        btnDescargarPDF.addEventListener('click', descargarPDF);
        console.log('Botón de descargar PDF configurado correctamente');
    } else {
        console.log('Botón de descargar PDF no encontrado en esta página');
    }
});




/**
 * Sube solo el PDF de resolución al endpoint específico
 * @param {string} homologacionId - ID de la homologación
 * @param {File} pdfFile - El archivo PDF
 * @returns {Promise} - Promesa con el resultado de la operación
 */
function subirSoloPDFResolucion(homologacionId, pdfFile) {
    console.log('Iniciando subida de PDF de resolución...');

    // Verificar que tengamos un ID válido
    if (!homologacionId) {
        homologacionId = cargarHomologacionId();
        console.log('ID cargado con cargarHomologacionId:', homologacionId);
    }

    if (!homologacionId) {
        return Promise.reject(new Error('ID de homologación no válido. Debe guardar la homologación antes de generar el PDF.'));
    }

    // Normalizar el ID para la API (si es necesario)
    const apiHomologacionId = normalizarHomologacionId ? normalizarHomologacionId(homologacionId) : homologacionId;
    console.log('ID normalizado para API:', apiHomologacionId);

    // Verificar que tengamos un archivo válido
    if (!pdfFile || !(pdfFile instanceof File)) {
        return Promise.reject(new Error('Archivo PDF no válido'));
    }

    console.log('Subiendo PDF al servidor:', {
        endpoint: `${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}/pdf`,
        fileName: pdfFile.name,
        fileSize: pdfFile.size
    });

    // Crear FormData para el archivo
    const formData = new FormData();
    formData.append('ruta_pdf_resolucion', pdfFile);

    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Intentar con el endpoint específico para PDF primero
    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}/pdf`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                console.warn(`Endpoint específico falló: ${response.status}. Intentando método alternativo...`);
                return response.text().then(text => {
                    console.error('Respuesta del servidor:', text);
                    // Intentar método alternativo
                    return intentarMetodoCompleto(apiHomologacionId, pdfFile);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta exitosa:', data);
            return data;
        });
}

/**
 * Método que implementa la misma lógica de guardarHomologaciones para subir el PDF
 * @param {string} homologacionId - ID de homologación
 * @param {File} pdfFile - Archivo PDF
 * @returns {Promise} - Promesa con el resultado
 */
function intentarMetodoCompleto(homologacionId, pdfFile) {
    console.log('Utilizando método completo para subir PDF...');

    // Obtener datos actuales de homologaciones
    return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error al obtener datos: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (!data || !data.datos) {
                throw new Error('No se pudieron obtener los datos de la homologación');
            }

            console.log('Datos obtenidos del servidor:', data.datos);

            // Preparar un array válido de homologaciones basado en los datos existentes
            let homologacionesArray = [];

            if (data.datos.homologaciones && Array.isArray(data.datos.homologaciones)) {
                homologacionesArray = data.datos.homologaciones.map(h => ({
                    asignatura_origen_id: h.asignatura_origen_id,
                    asignatura_destino_id: h.asignatura_destino_id || 1, // Asegurar que sea entero
                    nota_destino: h.nota_destino || "0",
                    comentarios: h.comentarios || ''
                }));
            } else if (data.datos.asignaturas_origen && data.datos.asignaturas_destino) {
                homologacionesArray = data.datos.asignaturas_origen.map((asignatura, index) => {
                    const destino = data.datos.asignaturas_destino[index] || {};
                    return {
                        asignatura_origen_id: asignatura.id,
                        asignatura_destino_id: destino.id || 1, // Asegurar que sea entero
                        nota_destino: destino.nota_destino || "0",
                        comentarios: destino.comentarios || ''
                    };
                });
            }

            // Si aún no tenemos homologaciones, crear una entrada mínima válida
            if (homologacionesArray.length === 0) {
                homologacionesArray = [{
                    asignatura_origen_id: 1,
                    asignatura_destino_id: 1, // Entero válido
                    nota_destino: "0",
                    comentarios: ''
                }];
            }

            console.log('Homologaciones preparadas para enviar:', homologacionesArray);

            // Crear FormData con los datos necesarios
            const formData = new FormData();
            formData.append('_method', 'PUT'); // Simular PUT para envío de archivos
            formData.append('ruta_pdf_resolucion', pdfFile);

            // Asegurar que homologaciones se envía correctamente como array
            homologacionesArray.forEach((item, index) => {
                Object.keys(item).forEach(key => {
                    formData.append(`homologaciones[${index}][${key}]`, item[key]);
                });
            });

            // Verificar los datos del FormData (solo para debug)
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // Enviar la solicitud con el archivo PDF y los datos existentes
            return fetch(`${API_BASE_URL}/homologacion-asignaturas/${homologacionId}`, {
                method: 'POST', // Usando POST con _method=PUT
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: formData
            });
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error al subir PDF: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            // Crear un objeto de respuesta estandarizado
            return {
                mensaje: data.mensaje || 'PDF actualizado correctamente',
                ruta_pdf_resolucion: data.ruta_pdf_resolucion || (data.datos && data.datos.ruta_pdf_resolucion) || '',
                url_pdf_resolucion: data.url_pdf_resolucion || (data.ruta_pdf_resolucion ? `/storage/${data.ruta_pdf_resolucion}` : '')
            };
        });
}

function descargarPDF() {
    console.log('Iniciando generación de PDF de resolución...');

    // Asegurarnos de tener el ID necesario
    let id = null;

    // Intentar obtener el ID de diferentes fuentes
    if (typeof homologacionId !== 'undefined' && homologacionId) {
        id = homologacionId;
        console.log('Usando homologacionId global:', id);
    } else {
        id = cargarHomologacionId();
        console.log('ID cargado con cargarHomologacionId:', id);
    }

    if (!id) {
        console.error('No se pudo obtener el ID de homologación.');
        alert('Error: Debe guardar la homologación antes de generar el PDF');
        return;
    }

    // Normalizar el ID para la API (si es necesario)
    const apiHomologacionId = normalizarHomologacionId ? normalizarHomologacionId(id) : id;
    console.log('ID normalizado para API:', apiHomologacionId);

    // Mostrar indicador de carga
    const btnDescargar = document.getElementById('btn-descargar-pdf');
    if (!btnDescargar) {
        console.error('No se encontró el botón de descargar PDF');
        return;
    }

    const textoOriginal = btnDescargar.innerHTML;
    btnDescargar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
    btnDescargar.disabled = true;

    try {
        // Obtener datos de homologación para generar el PDF
        fetch(`${API_BASE_URL}/homologacion-asignaturas/${apiHomologacionId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Error al obtener datos: ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos para generación de PDF obtenidos:', data);

                if (!data || !data.datos) {
                    throw new Error('No se pudieron obtener los datos de la homologación');
                }

                const homologacionData = data.datos;

                // Preparar datos para la generación del PDF
                const datosEstudiante = {
                    nombre: homologacionData.estudiante || 'Estudiante',
                    identificacion: homologacionData.numero_identificacion || 'No disponible'
                };

                const datosSolicitud = {
                    universidad_origen: homologacionData.universidad_origen || 'Universidad Externa',
                    programa_destino: homologacionData.programa_destino || 'Programa Actual'
                };

                // Preparar datos de homologación
                const homologaciones = [];
                if (homologacionData.asignaturas_origen && homologacionData.asignaturas_destino) {
                    homologacionData.asignaturas_origen.forEach((asignatura, index) => {
                        const destino = homologacionData.asignaturas_destino[index] || {};

                        homologaciones.push({
                            asignatura_origen_nombre: asignatura.nombre || 'No disponible',
                            codigo_destino: destino.codigo || 'N/A',
                            asignatura_destino_nombre: destino.nombre || 'No disponible',
                            semestre: destino.semestre || 'N/A',
                            creditos: asignatura.creditos || destino.creditos || 'N/A',
                            nota_destino: destino.nota_destino || 'N/A'
                        });
                    });
                }

                const datosHomologacion = {
                    homologaciones: homologaciones
                };

                // Crear las firmas si no existen
                if (!window.firmaCoordinadorData) {
                    window.firmaCoordinadorData = generarFirmaDefault('coordinador');
                    console.log('Se generó una firma por defecto para el coordinador');
                }

                // Verificar si generarPDFConDatos está disponible
                if (typeof generarPDFConDatos === 'function') {
                    // Generar el PDF con los datos - NO se sobrescribe mostrarPDFEnModal aquí
                    console.log('Llamando a generarPDFConDatos...');
                    const resultadoGeneracion = generarPDFConDatos(datosHomologacion, datosEstudiante, datosSolicitud, false);

                    if (!resultadoGeneracion) {
                        throw new Error('No se pudo generar el PDF con datos');
                    }
                } else {
                    throw new Error('Función generarPDFConDatos no disponible');
                }
            })
            .catch(error => {
                console.error('Error al generar o procesar el PDF:', error);
                alert(`Error: ${error.message}`);

                // Restaurar botón
                if (btnDescargar) {
                    btnDescargar.innerHTML = textoOriginal;
                    btnDescargar.disabled = false;
                }
            });
    } catch (error) {
        console.error('Error en la función descargarPDF:', error);
        alert(`Error al generar el PDF: ${error.message}`);

        // Restaurar botón
        if (btnDescargar) {
            btnDescargar.innerHTML = textoOriginal;
            btnDescargar.disabled = false;
        }
    }
}

/**
 * Actualiza la interfaz de usuario con la información del PDF
 * @param {Object} data - Datos de respuesta del servidor
 */
function actualizarInterfazConPDF(data) {
    try {
        console.log('Actualizando interfaz con datos del PDF:', data);

        // Actualizar link del PDF si existe en la interfaz
        const pdfLink = document.getElementById('link-pdf-resolucion');
        if (pdfLink) {
            let rutaPDF = '';

            if (data.url_pdf_resolucion) {
                rutaPDF = data.url_pdf_resolucion;
            } else if (data.ruta_pdf_resolucion) {
                rutaPDF = `/storage/${data.ruta_pdf_resolucion}`;
            } else if (data.datos && data.datos.ruta_pdf_resolucion) {
                rutaPDF = `/storage/${data.datos.ruta_pdf_resolucion}`;
            } else if (data.datos && data.datos.url_pdf_resolucion) {
                rutaPDF = data.datos.url_pdf_resolucion;
            }

            if (rutaPDF) {
                pdfLink.href = rutaPDF;
                pdfLink.style.display = 'inline';

                // Actualizar texto del enlace si tiene un span
                const pdfLinkText = pdfLink.querySelector('span');
                if (pdfLinkText) {
                    pdfLinkText.textContent = 'Ver PDF de resolución';
                }
            }
        }

        // Actualizar campo oculto si existe
        const pdfPathField = document.getElementById('ruta_pdf_resolucion');
        const rutaPDF = data.ruta_pdf_resolucion || (data.datos && data.datos.ruta_pdf_resolucion);
        if (pdfPathField && rutaPDF) {
            pdfPathField.value = rutaPDF;
        }

        // Actualizar cualquier elemento que muestre el nombre del archivo
        const pdfFileName = document.getElementById('pdf-file-name');
        if (pdfFileName && rutaPDF) {
            const nombreArchivo = rutaPDF.split('/').pop();
            pdfFileName.textContent = nombreArchivo;
        }

        // Actualizar estado visual en la interfaz
        const estadoPDF = document.getElementById('estado-pdf');
        if (estadoPDF) {
            estadoPDF.innerHTML = '<span class="badge badge-success">PDF Cargado</span>';
        }

        // Mostrar contenedor de PDF si existe
        const pdfContainer = document.getElementById('pdf-container');
        if (pdfContainer) {
            pdfContainer.style.display = 'block';
        }
    } catch (e) {
        console.error('Error al actualizar interfaz con datos del PDF:', e);
    }
}

/**
 * Función para definir alertaEnProceso si no existe
 */
if (typeof alertaEnProceso !== 'function') {
    function alertaEnProceso(mensaje) {
        console.log('Alerta en proceso:', mensaje);
        alert(mensaje);
    }
}

/**
 * Función auxiliar para mostrar alertas
 */
function mostrarAlerta(mensaje, tipo) {
    console.log(`Alerta [${tipo}]: ${mensaje}`);

    // Usar alertaEnProceso si está disponible
    if (typeof alertaEnProceso === 'function') {
        alertaEnProceso(mensaje);
    } else {
        alert(mensaje);
    }

    // Si existe un contenedor de alertas en el DOM, usarlo también
    const alertContainer = document.getElementById('alert-container');
    if (alertContainer) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        alertContainer.appendChild(alertDiv);

        // Auto-eliminar después de 5 segundos
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }, 5000);
    }
}



/**
 * Función para configurar el botón de PDF cuando el DOM está listo
 */
function configurarBotonPDF() {
    const btnDescargarPDF = document.getElementById('btn-descargar-pdf');
    if (btnDescargarPDF) {
        btnDescargarPDF.addEventListener('click', descargarPDF);
        console.log('Botón de descargar PDF configurado correctamente');
    } else {
        console.log('Botón de descargar PDF no encontrado en esta página');
    }
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', configurarBotonPDF);
} else {
    configurarBotonPDF();
}



// Reemplazar el event listener existente con esta versión actualizada


document.getElementById('btn-limpiar-homologaciones').addEventListener('click', limpiarHomologaciones);

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


function mostrarAlerta(mensaje, tipo) {
    // Prevenir recursión
    if (alertaEnProceso) {
        console.error("Prevención de recursión en mostrarAlerta");
        return;
    }

    alertaEnProceso = true;

    try {
        console.log("Mostrando alerta:", mensaje, tipo);

        // Método 1: Usar Bootstrap nativo si está disponible
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            // Crear un toast de Bootstrap 5
            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-white bg-${tipo} border-0`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');

            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${mensaje}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            document.body.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            // Eliminar después de que se oculte
            toastEl.addEventListener('hidden.bs.toast', () => {
                document.body.removeChild(toastEl);
            });
        }
        // Método 2: Enfoque simple, crea un div de alerta básico
        else {
            // Usa un ID único para el contenedor de alertas
            let contenedor = document.getElementById('sistema-alertas');
            if (!contenedor) {
                contenedor = document.createElement('div');
                contenedor.id = 'sistema-alertas';
                contenedor.style.position = 'fixed';
                contenedor.style.top = '20px';
                contenedor.style.right = '20px';
                contenedor.style.zIndex = '9999';
                contenedor.style.maxWidth = '300px';
                document.body.appendChild(contenedor);
            }

            // Crear elemento de alerta con estilo inline para evitar dependencias
            const alertaEl = document.createElement('div');
            alertaEl.style.padding = '15px';
            alertaEl.style.marginBottom = '10px';
            alertaEl.style.border = '1px solid transparent';
            alertaEl.style.borderRadius = '4px';
            alertaEl.style.opacity = '0';
            alertaEl.style.transition = 'opacity 0.3s ease-in-out';

            // Establecer colores según el tipo
            switch (tipo) {
                case 'success':
                    alertaEl.style.backgroundColor = '#d4edda';
                    alertaEl.style.borderColor = '#c3e6cb';
                    alertaEl.style.color = '#155724';
                    break;
                case 'danger':
                    alertaEl.style.backgroundColor = '#f8d7da';
                    alertaEl.style.borderColor = '#f5c6cb';
                    alertaEl.style.color = '#721c24';
                    break;
                case 'warning':
                    alertaEl.style.backgroundColor = '#fff3cd';
                    alertaEl.style.borderColor = '#ffeeba';
                    alertaEl.style.color = '#856404';
                    break;
                case 'info':
                default:
                    alertaEl.style.backgroundColor = '#d1ecf1';
                    alertaEl.style.borderColor = '#bee5eb';
                    alertaEl.style.color = '#0c5460';
                    break;
            }

            alertaEl.textContent = mensaje;
            contenedor.appendChild(alertaEl);

            // Hacer visible con un pequeño retraso para que la transición funcione
            setTimeout(() => {
                alertaEl.style.opacity = '1';
            }, 10);

            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                alertaEl.style.opacity = '0';
                setTimeout(() => {
                    if (alertaEl.parentNode) {
                        alertaEl.parentNode.removeChild(alertaEl);
                    }
                }, 300);
            }, 5000);
        }
    } catch (e) {
        // Capturar cualquier error sin llamar a mostrarAlerta para evitar recursión
        console.error('Error en mostrarAlerta:', e);
    } finally {
        // Siempre restablecer la bandera
        setTimeout(() => {
            alertaEnProceso = false;
        }, 100);
    }
}
// DESDE AQUI INICIA

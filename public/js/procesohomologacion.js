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

function cargarDatos() {
    // Si ya tenemos homologaciones desde el controlador, cargarlas directamente
    if (homologaciones.length > 0) {
        renderizarTablaHomologaciones();
        actualizarEstadoUI();
    } else {
        // Cargar homologaciones mediante API
        fetch(`${API_BASE_URL}/homologacion-asignaturas/${solicitudId}`)
            .then(response => response.json())
            .then(data => {
                if (data.datos) {
                    asignaturasOrigen = data.datos.asignaturas_origen || [];
                    asignaturasDestino = data.datos.asignaturas_destino || [];
                    homologaciones = data.datos.homologaciones || [];
                    homologacionId = data.datos.id || null;

                    renderizarTablaHomologaciones();
                    actualizarEstadoUI();
                }
            })
            .catch(error => {
                console.error('Error al cargar datos:', error);
                mostrarAlerta('Error al cargar datos', 'danger');
            });
    }
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

function confirmarHomologacion() {
    const form = document.getElementById('form-homologacion');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const index = document.getElementById('homologacion-index').value;
    const homologacion = {
        asignatura_origen_id: document.getElementById('asignatura-origen').value,
        asignatura_destino_id: document.getElementById('asignatura-destino').value,
        nota_origen: document.getElementById('nota-origen').value,
        nota_destino: document.getElementById('nota-homologada').value,
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

    // Guardar automáticamente
    guardarHomologaciones();
}
function renderizarTablaHomologaciones() {
    const tbody = document.getElementById('homologaciones-body');

    if (homologaciones.length === 0) {
        tbody.innerHTML = `
            <tr id="no-homologaciones">
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-info-circle text-muted mr-2"></i>
                    No hay asignaturas homologadas
                </td>
            </tr>
        `;
        document.getElementById('total-creditos').textContent = '0';
        return;
    }

    tbody.innerHTML = '';
    let totalCreditos = 0;

    homologaciones.forEach((h, index) => {
        const row = document.createElement('tr');
        totalCreditos += parseInt(h.creditos || 0);

        row.innerHTML = `
            <td>${h.asignatura_origen_nombre || 'Sin nombre'}</td>
            <td>${h.asignatura_destino_nombre || 'Sin nombre'}</td>
            <td>${h.nota_origen || '—'}</td>
            <td>
                <input type="number"
                       class="form-control form-control-sm nota-input"
                       value="${h.nota_destino || ''}"
                       min="0" max="5" step="0.1"
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

    document.getElementById('total-creditos').textContent = totalCreditos;

    // Agregar evento para actualizar notas inline
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('change', function () {
            const index = this.dataset.index;
            homologaciones[index].nota_destino = this.value;
            guardarHomologaciones();
        });
    });
}
function guardarHomologaciones(confirmado = false, callback = () => { }) {
    console.log('Iniciando guardarHomologaciones...');
    console.log('homologacionId:', homologacionId);
    console.log('solicitudId:', solicitudId);
    console.log('homologaciones:', homologaciones);

    actualizarNotasDesdeInputs();

    try {
        verificarDatosHomologacion();
    } catch (e) {
        console.error(e.message);
        callback(false);
        return;
    }

    if (homologaciones.length === 0) {
        mostrarAlerta('No hay homologaciones para guardar', 'warning');
        callback(false);
        return;
    }

    if (!solicitudId) {
        mostrarAlerta('Error: No se encontró el ID de solicitud', 'danger');
        callback(false);
        return;
    }

    // Forzar el uso de PUT para actualizar, ya que la homologación ya existe
    // ID 1 es el que viene en los datos de ejemplo
    const existingHomologacionId = homologacionId || 1;

    // Estructurar los datos para actualizar las asignaturas destino y notas
    const bodyData = {
        solicitud_id: solicitudId,
        asignaturas_origen: homologaciones.map(h => h.asignatura_origen_id),
        asignaturas_destino: homologaciones.map(h => h.asignatura_destino_id),
        notas_destino: homologaciones.map(h => h.nota_destino),
        comentarios_asignaturas: homologaciones.map(h => h.comentarios || ''),
        comentarios: document.getElementById('comentarios_generales')?.value || '',
        // Añadir el campo homologaciones que es requerido por el backend
        homologaciones: homologaciones.map(h => ({
            asignatura_origen_id: h.asignatura_origen_id,
            asignatura_destino_id: h.asignatura_destino_id,
            nota_destino: h.nota_destino,
            comentarios: h.comentarios || ''
        }))
    };

    // Siempre usar el método PUT para actualizar
    const url = `${API_BASE_URL}/homologacion-asignaturas/${existingHomologacionId}`;
    const method = 'PUT';

    // Log the data being sent for debugging
    console.log('Sending data to API:', bodyData);
    console.log('Using method:', method);
    console.log('URL:', url);

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(bodyData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        mostrarAlerta(data.message || 'Homologaciones actualizadas correctamente.', 'success');
        if (!homologacionId && data.id) {
            homologacionId = data.id;
            console.log('Homologación actualizada con ID:', homologacionId);
        }
        callback(true);
    })
    .catch(error => {
        console.error('Error en fetch:', error);
        mostrarAlerta(`Error al actualizar homologaciones: ${error.message}`, 'danger');
        callback(false);
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
// Agregar validaciones antes de guardar
function validarDatosHomologacion() {
    let errores = [];

    if (!solicitudId) {
        errores.push('No se encontró el ID de solicitud');
    }

    if (homologaciones.length === 0) {
        errores.push('No hay homologaciones para guardar');
    }

    homologaciones.forEach((h, index) => {
        if (!h.asignatura_origen_id) {
            errores.push(`Homologación ${index}: Falta asignatura origen`);
        }

        if (!h.asignatura_destino_id) {
            errores.push(`Homologación ${index}: Falta asignatura destino`);
        }

        if (h.nota_destino === undefined || h.nota_destino === null) {
            errores.push(`Homologación ${index}: Falta nota destino`);
        } else {
            const nota = parseFloat(h.nota_destino);
            if (isNaN(nota) || nota < 0 || nota > 5) {
                errores.push(`Homologación ${index}: Nota inválida (${h.nota_destino})`);
            }
        }
    });

    return errores;
}


function generarPDF() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'letter'
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

        // Agregar logo de la institución
        try {
            // Verificar si hay un logo disponible
            if (window.logoUploadData) {
                doc.addImage(window.logoUploadData, 'PNG', 10, 10, 40, 20);
            } else {
                // Si no hay logo cargado, usar un texto como logo provisional
                doc.setFontSize(16);
                doc.setFont('helvetica', 'bold');
                doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, 15, { align: 'center' });

                // Dibuja una línea decorativa bajo el nombre
                doc.setDrawColor(0, 51, 153); // Azul institucional
                doc.setLineWidth(0.5);
                doc.line(20, 20, 190, 20);
            }
        } catch (error) {
            console.error('Error al agregar logo:', error);
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, 15, { align: 'center' });
        }

        // Configurar el PDF
        let yPos = 40;

        // Título
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(0, 51, 153); // Azul institucional
        doc.text('RESOLUCIÓN DE HOMOLOGACIÓN DE ASIGNATURAS', 105, yPos, { align: 'center' });
        doc.setTextColor(0, 0, 0); // Volver al color negro

        yPos += 8;

        // Número de resolución
        doc.setFontSize(12);
        doc.text(`Resolución N° ${numeroResolucion}`, 105, yPos, { align: 'center' });

        yPos += 15;

        // Fecha
        doc.setFontSize(11);
        doc.setFont('helvetica', 'normal');
        doc.text(fechaFormateada, 170, yPos, { align: 'right' });

        yPos += 15;

        // Introducción formal
        doc.setFont('helvetica', 'bold');
        doc.text('EL CONSEJO ACADÉMICO DE LA CORPORACIÓN UNIVERSITARIA AUTÓNOMA DEL CAUCA', 105, yPos, { align: 'center' });

        yPos += 10;

        doc.text('CONSIDERANDO:', 105, yPos, { align: 'center' });

        yPos += 10;

        // Texto formal considerando
        doc.setFont('helvetica', 'normal');
        let considerandos = [
            "Que de conformidad con el Reglamento Estudiantil vigente, en su Capítulo IV, Artículos 28 al 32, se establecen los procedimientos y criterios para la homologación de asignaturas.",

            "Que el estudiante " + estudiante.toUpperCase() + ", identificado con documento " + identificacion + ", proveniente de " + universidad + ", ha solicitado homologación de asignaturas para el programa de " + programa + ".",

            "Que el Comité de Homologaciones ha analizado la documentación presentada, incluyendo certificados de calificaciones y contenidos programáticos de las asignaturas cursadas en la institución de origen.",

            "Que existe correspondencia entre los contenidos programáticos, intensidad horaria, créditos académicos y nivel de competencias de las asignaturas a homologar.",

            "Que en sesión del " + dia + " de " + mes + " de " + año + ", el Comité de Homologaciones recomendó la aprobación de las asignaturas que se detallan en la presente resolución."
        ];

        // Agregar considerandos
        let numConsiderando = 1;
        considerandos.forEach(texto => {
            const lineas = doc.splitTextToSize("Que " + texto.replace(/^Que /, ""), 165);
            doc.text(`${numConsiderando}. `, 20, yPos);
            doc.text(lineas, 30, yPos);
            yPos += lineas.length * 7 + 3;
            numConsiderando++;
        });

        yPos += 5;

        // Resuelve
        doc.setFont('helvetica', 'bold');
        doc.text('RESUELVE:', 105, yPos, { align: 'center' });

        yPos += 10;

        // Artículo Primero
        doc.text('ARTÍCULO PRIMERO:', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 7;

        const textoArticuloPrimero = "Aprobar la homologación de las siguientes asignaturas cursadas por el estudiante " +
                                      estudiante.toUpperCase() + ", identificado con documento " + identificacion +
                                      ", para el programa académico de " + programa + ":";

        const lineasArticulo1 = doc.splitTextToSize(textoArticuloPrimero, 175);
        doc.text(lineasArticulo1, 20, yPos);

        yPos += lineasArticulo1.length * 7 + 5;

        // Tabla de homologaciones (manteniendo la estructura original)
        const headers = ['Asignatura Origen', 'Asignatura Destino', 'Nota Origen', 'Nota Homologada', 'Créditos'];
        const data = homologaciones.map(h => [
            h.asignatura_origen_nombre,
            h.asignatura_destino_nombre,
            h.nota_origen,
            h.nota_destino,
            h.creditos
        ]);

        doc.autoTable({
            startY: yPos,
            head: [headers],
            body: data,
            margin: { left: 20, right: 20 },
            styles: { fontSize: 10, font: 'helvetica' },
            headStyles: { fillColor: [0, 51, 153], textColor: [255, 255, 255], fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [240, 240, 250] },
            tableLineColor: [100, 100, 100],
            tableLineWidth: 0.1
        });

        yPos = doc.lastAutoTable.finalY + 10;

        // Total de créditos
        doc.setFont('helvetica', 'bold');
        doc.text('Total de Créditos Homologados:', 120, yPos);
        doc.text(document.getElementById('total-creditos').textContent, 180, yPos);

        yPos += 15;

        // Artículo Segundo
        doc.text('ARTÍCULO SEGUNDO:', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 7;

        const textoArticuloSegundo = "Las asignaturas homologadas se registrarán en el historial académico del estudiante " +
                                      "con las calificaciones aquí establecidas y serán válidas para todos los efectos académicos " +
                                      "dentro del plan de estudios vigente del programa de " + programa + ".";

        const lineasArticulo2 = doc.splitTextToSize(textoArticuloSegundo, 175);
        doc.text(lineasArticulo2, 20, yPos);

        yPos += lineasArticulo2.length * 7 + 5;

        // Artículo Tercero
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO TERCERO:', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 7;

        const textoArticuloTercero = "El estudiante deberá cursar y aprobar las demás asignaturas contempladas en " +
                                       "el plan de estudios que no han sido objeto de homologación, para obtener " +
                                       "el título correspondiente.";

        const lineasArticulo3 = doc.splitTextToSize(textoArticuloTercero, 175);
        doc.text(lineasArticulo3, 20, yPos);

        yPos += lineasArticulo3.length * 7 + 5;

        // Artículo Cuarto
        doc.setFont('helvetica', 'bold');
        doc.text('ARTÍCULO CUARTO:', 20, yPos);
        doc.setFont('helvetica', 'normal');

        yPos += 7;

        const textoArticuloCuarto = "La presente resolución rige a partir de la fecha de su expedición y contra " +
                                     "ella procede el recurso de reposición ante el Consejo Académico, dentro de " +
                                     "los cinco (5) días hábiles siguientes a su notificación.";

        const lineasArticulo4 = doc.splitTextToSize(textoArticuloCuarto, 175);
        doc.text(lineasArticulo4, 20, yPos);

        yPos += lineasArticulo4.length * 7 + 15;

        // Comuníquese y cúmplase
        doc.setFont('helvetica', 'bold');
        doc.text('COMUNÍQUESE Y CÚMPLASE', 105, yPos, { align: 'center' });

        yPos += 7;

        doc.setFont('helvetica', 'normal');
        doc.text(`Dada en Popayán, a los ${dia} días del mes de ${mes} de ${año}.`, 105, yPos, { align: 'center' });

        yPos += 20;

        // Firmas
        // Firma del coordinador (si existe)
        if (window.firmaUploadData) {
            try {
                doc.addImage(window.firmaUploadData, 'PNG', 40, yPos - 20, 50, 20);
            } catch (error) {
                console.log('Error al agregar firma al PDF:', error);
            }
        }

        // Líneas para firmas
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.3);

        // Línea coordinador
        doc.line(20, yPos + 10, 80, yPos + 10);

        // Línea vicerrector
        doc.line(120, yPos + 10, 180, yPos + 10);

        // Nombre del coordinador
        doc.setFont('helvetica', 'bold');
        doc.text('__________________________', 50, yPos + 10, { align: 'center' });
        yPos += 15;
        doc.text('Coordinador del Programa', 50, yPos, { align: 'center' });
        doc.setFont('helvetica', 'normal');
        doc.text(programa, 50, yPos + 7, { align: 'center' });

        // Nombre del vicerrector
        doc.setFont('helvetica', 'bold');
        doc.text('__________________________', 150, yPos - 15, { align: 'center' });
        doc.text('Vicerrector Académico', 150, yPos, { align: 'center' });
        doc.setFont('helvetica', 'normal');
        doc.text('Corporación Universitaria Autónoma del Cauca', 150, yPos + 7, { align: 'center' });

        // Pie de página para todas las páginas
        const totalPages = doc.internal.getNumberOfPages();
        for (let i = 1; i <= totalPages; i++) {
            doc.setPage(i);

            // Línea de separación para pie de página
            doc.setDrawColor(0, 51, 153); // Azul institucional
            doc.setLineWidth(0.5);
            doc.line(20, 260, 190, 260);

            doc.setFontSize(8);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(100, 100, 100); // Gris para el pie de página

            // Datos de contacto
            doc.text('Corporación Universitaria Autónoma del Cauca', 105, 265, { align: 'center' });
            doc.text('Calle 5 No. 3-85 - Popayán, Colombia', 105, 269, { align: 'center' });
            doc.text('Tel: (602) 8213000 - www.uniautonoma.edu.co', 105, 273, { align: 'center' });

            // Número de página
            doc.text(`Página ${i} de ${totalPages} - Resolución N° ${numeroResolucion}`, 190, 273, { align: 'right' });

            // Restaurar color
            doc.setTextColor(0, 0, 0);
        }

        // Mostrar preview
        const pdfPreview = document.getElementById('pdf-preview-content');
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '500px';
        iframe.src = doc.output('datauristring');
        pdfPreview.innerHTML = '';
        pdfPreview.appendChild(iframe);

        // Mostrar modal
        $('#pdf-preview-modal').modal('show');

        // Configurar botón de confirmar
        document.getElementById('btn-confirmar-pdf').onclick = function () {
            const nombreArchivo = `Homologacion_${estudiante.replace(/\s+/g, '_')}_${identificacion}.pdf`;
            doc.save(nombreArchivo);
            $('#pdf-preview-modal').modal('hide');
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

function eliminarHomologacion(index) {
    if (confirm('¿Está seguro de eliminar esta homologación?')) {
        homologaciones.splice(index, 1);
        renderizarTablaHomologaciones();
        guardarHomologaciones();
    }
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
            mostrarAlerta('Homologaciones guardadas exitosamente', 'success');
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

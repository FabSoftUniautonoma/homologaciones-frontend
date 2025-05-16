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

function desactivarControles() {
    document.querySelectorAll('input, button, select').forEach(element => {
        element.disabled = true;
    });
    document.getElementById('btn-generar-pdf').disabled = false;
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


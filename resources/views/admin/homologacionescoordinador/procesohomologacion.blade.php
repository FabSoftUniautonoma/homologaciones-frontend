{{-- resources/views/homologacionescoordinador/coordinador.blade.php --}}
@extends('admin.layouts.appcoordinacion')

@section('title', 'Proceso de Homologación')

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Variables para el manejo de datos
                let homologaciones = [];
                const solicitudId = '{{ $solicitud['id_solicitud'] ?? ($solicitud['id'] ?? '') }}';
                const estadoActual = '{{ $estadoActual }}';

                // Inicializar la tabla de homologaciones con los datos existentes
                inicializarTablaHomologaciones();
                calcularTotalCreditos();

                // Inicializar el pad de firma
                const canvas = document.getElementById('signatureCanvas');
                if (canvas) {
                    const signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)'
                    });

                    // Botón para limpiar firma
                    document.getElementById('btnLimpiarFirma')?.addEventListener('click', function() {
                        signaturePad.clear();
                    });

                    // Botón para guardar firma
                    document.getElementById('btnGuardarFirma')?.addEventListener('click', function() {
                        if (signaturePad.isEmpty()) {
                            Swal.fire({
                                title: 'Firma requerida',
                                text: 'Por favor, firme antes de guardar',
                                icon: 'warning'
                            });
                            return;
                        }

                        const firma = signaturePad.toDataURL();
                        // Guardar en localStorage
                        localStorage.setItem('firma_coordinador_' + solicitudId, firma);

                        Swal.fire({
                            title: 'Firma guardada',
                            text: 'La firma ha sido guardada correctamente',
                            icon: 'success'
                        });
                    });

                    // Cargar firma guardada si existe
                    const firmaGuardada = localStorage.getItem('firma_coordinador_' + solicitudId);
                    if (firmaGuardada) {
                        const image = new Image();
                        image.onload = function() {
                            const context = canvas.getContext('2d');
                            context.drawImage(image, 0, 0);
                        };
                        image.src = firmaGuardada;
                    }
                }

                // Botón para emparejar materias
                document.getElementById('btnEmparejar')?.addEventListener('click', function() {
                    const materiasOrigen = document.querySelectorAll('.asignatura-origen:checked');
                    const materiasDestino = document.querySelectorAll('.asignatura-destino:checked');

                    if (materiasOrigen.length === 0 || materiasDestino.length === 0) {
                        Swal.fire({
                            title: 'Selección requerida',
                            text: 'Debe seleccionar al menos una asignatura de origen y una de destino',
                            icon: 'warning'
                        });
                        return;
                    }

                    // Verificar si alguna materia tiene nota menor a 3
                    for (const materiaOrigen of materiasOrigen) {
                        const nota = parseFloat(materiaOrigen.dataset.nota);
                        if (nota < 3) {
                            Swal.fire({
                                title: 'Nota insuficiente',
                                text: `La asignatura "${materiaOrigen.dataset.nombre}" tiene una nota de ${nota}, inferior a 3.0`,
                                icon: 'error'
                            });
                            return;
                        }
                    }

                    // Crear homologación para cada par de materias seleccionadas
                    for (const materiaOrigen of materiasOrigen) {
                        for (const materiaDestino of materiasDestino) {
                            agregarHomologacion(materiaOrigen, materiaDestino);
                        }
                    }

                    // Limpiar selecciones
                    materiasOrigen.forEach(checkbox => checkbox.checked = false);
                    materiasDestino.forEach(checkbox => checkbox.checked = false);

                    // Actualizar tabla y totales
                    actualizarTablaHomologaciones();
                    calcularTotalCreditos();
                    guardarEnLocalStorage();
                });

                // Botón para guardar cambios
                document.getElementById('btnGuardarCambios')?.addEventListener('click', function() {
                    if (homologaciones.length === 0) {
                        Swal.fire({
                            title: 'Sin homologaciones',
                            text: 'No hay asignaturas homologadas para guardar',
                            icon: 'warning'
                        });
                        return;
                    }

                    // Simulamos la llamada a la API para guardar
                    Swal.fire({
                        title: 'Guardando cambios',
                        text: 'Espere un momento...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            setTimeout(() => {
                                guardarEnLocalStorage();
                                Swal.fire({
                                    title: 'Cambios guardados',
                                    text: 'Las homologaciones se han guardado correctamente',
                                    icon: 'success'
                                });
                            }, 1000);
                        }
                    });
                });

                // Botón para generar PDF
                document.getElementById('btnGenerarPDF')?.addEventListener('click', function() {
                    if (homologaciones.length === 0) {
                        Swal.fire({
                            title: 'Sin homologaciones',
                            text: 'No hay asignaturas homologadas para generar el PDF',
                            icon: 'warning'
                        });
                        return;
                    }

                    const firma = localStorage.getItem('firma_coordinador_' + solicitudId);
                    if (!firma && estadoActual !== 'cerrado') {
                        Swal.fire({
                            title: 'Firma requerida',
                            text: 'Por favor, firme el documento antes de generar el PDF',
                            icon: 'warning'
                        });
                        return;
                    }

                    // Simulamos la generación y visualización del PDF
                    Swal.fire({
                        title: 'Generando PDF',
                        text: 'Espere un momento...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                            setTimeout(() => {
                                // Aquí normalmente se llamaría a una API para generar el PDF
                                // Simulamos mostrando un iframe en el modal
                                const modal = new bootstrap.Modal(document.getElementById(
                                    'pdfPreviewModal'));
                                // Preparar el iframe para mostrar el PDF (en una implementación real)
                                const iframe = document.getElementById('pdfPreviewFrame');
                                if (iframe) {
                                    // En una implementación real, aquí se establecería la URL del PDF
                                    // Por ahora, usamos un placeholder
                                    iframe.src = "about:blank"; // O URL real del PDF
                                }
                                modal.show();
                                Swal.close();
                            }, 1500);
                        }
                    });
                });

                // Botón para cerrar homologación
                document.getElementById('btnCerrarHomologacion')?.addEventListener('click', function() {
                    if (homologaciones.length === 0) {
                        Swal.fire({
                            title: 'Sin homologaciones',
                            text: 'No hay asignaturas homologadas para cerrar el proceso',
                            icon: 'warning'
                        });
                        return;
                    }

                    Swal.fire({
                        title: '¿Está seguro?',
                        text: 'Una vez cerrada la homologación, no podrá realizar más cambios',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, cerrar homologación',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Simulamos llamada a la API para cerrar la homologación
                            Swal.fire({
                                title: 'Cerrando homologación',
                                text: 'Espere un momento...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                    setTimeout(() => {
                                        // Aquí normalmente se llamaría a la API para cambiar el estado
                                        localStorage.setItem(
                                            'estado_homologacion_' +
                                            solicitudId, 'cerrado');

                                        Swal.fire({
                                            title: 'Homologación cerrada',
                                            text: 'El proceso de homologación ha sido cerrado correctamente',
                                            icon: 'success'
                                        }).then(() => {
                                            // Recargar la página para reflejar el nuevo estado
                                            location.reload();
                                        });
                                    }, 1500);
                                }
                            });
                        }
                    });
                });

                // Botón para confirmar PDF
                document.getElementById('btnConfirmarPDF')?.addEventListener('click', function() {
                    // En un caso real, aquí se enviaría el PDF al servidor
                    Swal.fire({
                        title: 'PDF guardado',
                        text: 'El documento PDF ha sido guardado correctamente',
                        icon: 'success'
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'pdfPreviewModal'));
                        modal.hide();
                    });
                });

                // Eliminar homologación
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('btn-eliminar-homologacion') ||
                        e.target.parentElement.classList.contains('btn-eliminar-homologacion')) {

                        const fila = e.target.closest('tr');
                        if (fila) {
                            const origenId = fila.dataset.origenId;
                            const destinoId = fila.dataset.destinoId;

                            // Eliminar de la lista de homologaciones
                            homologaciones = homologaciones.filter(h =>
                                h.asignatura_origen_id !== origenId || h.asignatura_destino_id !== destinoId
                            );

                            // Actualizar tabla y localStorage
                            fila.remove();
                            calcularTotalCreditos();
                            guardarEnLocalStorage();
                        }
                    }
                });

                // Actualizar datos al cambiar créditos o nota
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('creditos-homologados') ||
                        e.target.classList.contains('nota-homologada')) {

                        const fila = e.target.closest('tr');
                        if (fila) {
                            const origenId = fila.dataset.origenId;
                            const destinoId = fila.dataset.destinoId;

                            // Actualizar valores en el array de homologaciones
                            for (const homologacion of homologaciones) {
                                if (homologacion.asignatura_origen_id === origenId &&
                                    homologacion.asignatura_destino_id === destinoId) {

                                    if (e.target.classList.contains('creditos-homologados')) {
                                        homologacion.creditos = parseInt(e.target.value) || 0;
                                    } else if (e.target.classList.contains('nota-homologada')) {
                                        const nota = parseFloat(e.target.value);
                                        if (nota < 3) {
                                            Swal.fire({
                                                title: 'Nota inválida',
                                                text: 'La nota mínima para homologar es 3.0',
                                                icon: 'warning'
                                            });
                                            e.target.value = 3;
                                            homologacion.nota_destino = 3;
                                        } else {
                                            homologacion.nota_destino = nota;
                                        }
                                    }
                                    break;
                                }
                            }

                            calcularTotalCreditos();
                            guardarEnLocalStorage();
                        }
                    }
                });

                // Función para inicializar la tabla con homologaciones existentes
                function inicializarTablaHomologaciones() {
                    // Intentar recuperar del localStorage primero
                    const savedHomologaciones = localStorage.getItem('homologaciones_' + solicitudId);
                    if (savedHomologaciones) {
                        try {
                            homologaciones = JSON.parse(savedHomologaciones);
                            actualizarTablaHomologaciones();
                            return;
                        } catch (e) {
                            console.error("Error parsing saved homologaciones", e);
                            // Continue to use data from the server if localStorage parsing fails
                        }
                    }

                    // Si no hay datos en localStorage o hubo un error, usar los de la API
                    // Esta parte permanece igual, pero incluida en un bloque try-catch por seguridad
                    try {
                        // Esta sección depende de datos del servidor
                        // Aquí normalmente habría una carga de datos iniciales desde el servidor
                        // El código Blade permanece igual
                    } catch (e) {
                        console.error("Error loading initial homologaciones", e);
                    }
                }

                // Función para agregar una nueva homologación
                function agregarHomologacion(materiaOrigen, materiaDestino) {
                    const origenId = materiaOrigen.value;
                    const destinoId = materiaDestino.value;

                    // Verificar si ya existe esta homologación
                    const homologacionExistente = homologaciones.find(h =>
                        h.asignatura_origen_id === origenId && h.asignatura_destino_id === destinoId
                    );

                    if (homologacionExistente) {
                        Swal.fire({
                            title: 'Homologación duplicada',
                            text: `La asignatura "${materiaOrigen.dataset.nombre}" ya está homologada con "${materiaDestino.dataset.nombre}"`,
                            icon: 'warning'
                        });
                        return;
                    }

                    // Crear nueva homologación
                    const nuevaHomologacion = {
                        asignatura_origen_id: origenId,
                        asignatura_origen: materiaOrigen.dataset.nombre,
                        asignatura_destino_id: destinoId,
                        asignatura_destino: materiaDestino.dataset.nombre,
                        creditos: parseInt(materiaDestino.dataset.creditos) || 0,
                        nota_destino: parseFloat(materiaOrigen.dataset.nota) || 3
                    };

                    homologaciones.push(nuevaHomologacion);
                }

                // Función para actualizar la tabla de homologaciones
                function actualizarTablaHomologaciones() {
                    const tabla = document.getElementById('tablaHomologaciones');
                    if (!tabla) return;

                    const tbody = tabla.querySelector('tbody');
                    if (!tbody) return;

                    // Limpiar tabla
                    tbody.innerHTML = '';

                    // Añadir filas nuevas
                    homologaciones.forEach(homologacion => {
                        const fila = document.createElement('tr');
                        fila.dataset.origenId = homologacion.asignatura_origen_id;
                        fila.dataset.destinoId = homologacion.asignatura_destino_id;

                        fila.innerHTML = `
                <td>${homologacion.asignatura_origen}</td>
                <td>${homologacion.asignatura_destino}</td>
                <td>
                    <input type="number" class="form-control creditos-homologados" value="${homologacion.creditos}"
                        min="0" step="1" ${estadoActual === 'cerrado' ? 'disabled' : ''}>
                </td>
                <td>
                    <input type="number" class="form-control nota-homologada" value="${homologacion.nota_destino}"
                        min="3" max="5" step="0.1" ${estadoActual === 'cerrado' ? 'disabled' : ''}>
                </td>
                <td>
                    ${estadoActual !== 'cerrado' ?
                        `<button type="button" class="btn btn-danger btn-sm btn-eliminar-homologacion">
                                            <i class="fas fa-trash"></i>
                                        </button>` :
                        `<span class="badge bg-secondary">Cerrado</span>`
                    }
                </td>
            `;

                        tbody.appendChild(fila);
                    });
                }

                // Función para calcular el total de créditos homologados
                function calcularTotalCreditos() {
                    const totalCreditos = homologaciones.reduce((sum, h) => sum + (parseInt(h.creditos) || 0), 0);
                    const totalCreditosElement = document.getElementById('totalCreditos');
                    if (totalCreditosElement) {
                        totalCreditosElement.textContent = totalCreditos;
                    }
                }

                // Función para guardar en localStorage
                function guardarEnLocalStorage() {
                    localStorage.setItem('homologaciones_' + solicitudId, JSON.stringify(homologaciones));
                }

                // Funciones para ver y descargar documentos (simuladas)
                window.verDocumento = function(nombreDocumento) {
                    Swal.fire({
                        title: 'Visualizando documento',
                        text: `Abriendo "${nombreDocumento}"...`,
                        icon: 'info',
                        timer: 1500,
                        showConfirmButton: false
                    });
                };

                window.descargarDocumento = function(nombreDocumento) {
                    Swal.fire({
                        title: 'Descargando documento',
                        text: `Descargando "${nombreDocumento}"...`,
                        icon: 'info',
                        timer: 1500,
                        showConfirmButton: false
                    });
                };

                // Verificar el estado al cargar la página
                if (estadoActual === 'cerrado' || localStorage.getItem('estado_homologacion_' + solicitudId) ===
                    'cerrado') {
                    // Deshabilitar todos los controles de edición
                    document.querySelectorAll('input, button').forEach(element => {
                        if (!element.classList.contains('btn-secondary')) {
                            element.disabled = true;
                        }
                    });

                    // Mostrar alerta de proceso cerrado si no existe ya
                    if (!document.querySelector('.alert-warning')) {
                        const cardBody = document.querySelector('.card-body');
                        if (cardBody) {
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-warning mt-3';
                            alertDiv.innerHTML =
                                '<i class="fas fa-lock"></i> Esta homologación ha sido cerrada. No se permiten más cambios.';
                            cardBody.prepend(alertDiv);
                        }
                    }
                }
            });
        </script>
    @endsection


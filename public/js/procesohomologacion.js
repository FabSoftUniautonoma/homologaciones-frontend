document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let homologaciones = [];
    const solicitudId = document.getElementById('solicitud_id').value;

    // Evento para agregar homologación
    document.getElementById('btn-agregar-homologacion').addEventListener('click', function() {
        $('#modal-agregar-homologacion').modal('show');
    });

    // Actualizar nota origen al seleccionar asignatura de origen
    document.getElementById('asignatura-origen').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nota = selectedOption.getAttribute('data-nota');
        document.getElementById('nota-origen').value = nota;
        document.getElementById('nota-homologada').value = nota;
    });

    // Confirmar homologación
    document.getElementById('btn-confirmar-homologacion').addEventListener('click', function() {
        const origenSelect = document.getElementById('asignatura-origen');
        const destinoSelect = document.getElementById('asignatura-destino');
        const notaOrigen = document.getElementById('nota-origen').value;
        const notaHomologada = document.getElementById('nota-homologada').value;
        const observacion = document.getElementById('observacion').value;

        // Validación
        if (!origenSelect.value || !destinoSelect.value || !notaHomologada) {
            alert('Por favor complete todos los campos obligatorios');
            return;
        }

        // Obtener datos completos
        const origenOption = origenSelect.options[origenSelect.selectedIndex];
        const destinoOption = destinoSelect.options[destinoSelect.selectedIndex];

        const homologacion = {
            id: Date.now(), // Temporal ID para manejo en frontend
            asignatura_origen_id: origenSelect.value,
            asignatura_destino_id: destinoSelect.value,
            asignatura_origen: origenOption.getAttribute('data-nombre'),
            asignatura_destino: destinoOption.getAttribute('data-nombre'),
            nota_origen: notaOrigen,
            nota_homologada: notaHomologada,
            creditos: destinoOption.getAttribute('data-creditos'),
            observacion: observacion
        };

        // Agregar a la lista y actualizar tabla
        homologaciones.push(homologacion);
        actualizarTablaHomologaciones();

        // Cerrar modal y limpiar formulario
        $('#modal-agregar-homologacion').modal('hide');
        document.getElementById('form-homologacion').reset();
    });

    // Función para actualizar la tabla de homologaciones
    function actualizarTablaHomologaciones() {
        const tbody = document.getElementById('homologaciones-body');

        // Limpiar tabla
        tbody.innerHTML = '';

        if (homologaciones.length === 0) {
            tbody.innerHTML = '<tr id="no-homologaciones"><td colspan="6" class="text-center">No hay asignaturas homologadas</td></tr>';
            document.getElementById('total-creditos').textContent = '0';
            return;
        }

        let totalCreditos = 0;

        // Agregar filas
        homologaciones.forEach(homologacion => {
            const creditos = parseFloat(homologacion.creditos) || 0;
            totalCreditos += creditos;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${homologacion.asignatura_origen}</td>
                <td>${homologacion.asignatura_destino}</td>
                <td>${homologacion.nota_origen}</td>
                <td>${homologacion.nota_homologada}</td>
                <td>${homologacion.creditos}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger eliminar-homologacion" data-id="${homologacion.id}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        // Actualizar total de créditos
        document.getElementById('total-creditos').textContent = totalCreditos.toFixed(1);

        // Agregar evento a botones de eliminar
        document.querySelectorAll('.eliminar-homologacion').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                eliminarHomologacion(id);
            });
        });
    }

    // Función para eliminar homologación
    function eliminarHomologacion(id) {
        if (confirm('¿Está seguro de eliminar esta homologación?')) {
            homologaciones = homologaciones.filter(item => item.id != id);
            actualizarTablaHomologaciones();
        }
    }

    // Eventos para ver información de asignaturas
    document.querySelectorAll('.ver-asignatura-origen').forEach(item => {
        item.addEventListener('click', function() {
            document.getElementById('infoNombreOrigen').textContent = this.getAttribute('data-nombre');
            document.getElementById('infoSemestreOrigen').textContent = this.getAttribute('data-semestre');
            document.getElementById('infoCreditosOrigen').textContent = this.getAttribute('data-creditos');
            document.getElementById('infoModalidadOrigen').textContent = this.getAttribute('data-modalidad') || 'No especificada';
        });
    });

    document.querySelectorAll('.ver-asignatura-destino').forEach(item => {
        item.addEventListener('click', function() {
            document.getElementById('infoNombre').textContent = this.getAttribute('data-nombre');
            document.getElementById('infoSemestre').textContent = this.getAttribute('data-semestre');
            document.getElementById('infoCreditos').textContent = this.getAttribute('data-creditos');
            document.getElementById('infoModalidad').textContent = this.getAttribute('data-modalidad') || 'No especificada';
        });
    });

    // Evento para guardar cambios
    document.getElementById('btn-guardar').addEventListener('click', function() {
        if (homologaciones.length === 0) {
            alert('Debe agregar al menos una homologación');
            return;
        }

        // Datos a enviar
        const datos = {
            id_solicitud: solicitudId,
            homologaciones: homologaciones
        };

        // Simular guardado (reemplazar con llamada AJAX real)
        console.log('Datos a guardar:', datos);
        alert('Cambios guardados correctamente');
    });

    // Evento para generar PDF
    document.getElementById('btn-generar-pdf').addEventListener('click', function() {
        if (homologaciones.length === 0) {
            alert('Debe agregar al menos una homologación');
            return;
        }

        // Simular vista previa (reemplazar con implementación real)
        const pdfContent = document.getElementById('pdf-preview-content');

        // Datos del estudiante
        let contenidoHTML = `
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Datos del Estudiante</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> ${document.querySelector('.card-body p:nth-child(1)').textContent.split(':')[1].trim()}</p>
                    <p><strong>Identificación:</strong> ${document.querySelector('.card-body p:nth-child(2)').textContent.split(':')[1].trim()}</p>
                    <p><strong>Universidad de Origen:</strong> ${document.querySelector('.card-body p:nth-child(3)').textContent.split(':')[1].trim()}</p>
                    <p><strong>Programa de Destino:</strong> ${document.querySelector('.card-body p:nth-child(4)').textContent.split(':')[1].trim()}</p>
                </div>
            </div>
        `;

        // Tabla de homologaciones
        contenidoHTML += `
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Asignaturas Homologadas</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Asignatura Origen</th>
                                <th>Asignatura Destino</th>
                                <th>Nota Origen</th>
                                <th>Nota Homologada</th>
                                <th>Créditos</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        homologaciones.forEach(homologacion => {
            contenidoHTML += `
                <tr>
                    <td>${homologacion.asignatura_origen}</td>
                    <td>${homologacion.asignatura_destino}</td>
                    <td>${homologacion.nota_origen}</td>
                    <td>${homologacion.nota_homologada}</td>
                    <td>${homologacion.creditos}</td>
                </tr>
            `;
        });

        contenidoHTML += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total Créditos:</strong></td>
                                <td>${document.getElementById('total-creditos').textContent}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        `;

        // Firma
        contenidoHTML += `
            <div class="row mt-5">
                <div class="col-md-6 offset-md-3 text-center">
                    <hr>
                    <p>Firma del Coordinador</p>
                    <div id="firma-pdf">
                        <img src="${document.querySelector('#firma-preview img')?.src || ''}" alt="Firma" style="max-height: 100px; max-width: 200px;">
                    </div>
                    <p class="mt-2">Fecha: ${new Date().toLocaleDateString()}</p>
                </div>
            </div>
        `;

        pdfContent.innerHTML = contenidoHTML;
        $('#pdf-preview-modal').modal('show');
    });

    // Confirmar PDF
    document.getElementById('btn-confirmar-pdf').addEventListener('click', function() {
        alert('PDF generado correctamente');
        $('#pdf-preview-modal').modal('hide');
    });

    // Cerrar homologación
    document.getElementById('btn-cerrar-homologacion').addEventListener('click', function() {
        if (confirm('¿Está seguro de cerrar esta homologación? Esta acción no se puede deshacer.')) {
            alert('Homologación cerrada correctamente');
            // Aquí redireccionar o realizar acción de cierre
        }
    });

    // Vista previa de firma
    document.getElementById('firma').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const firmaPreview = document.getElementById('firma-preview');
                firmaPreview.innerHTML = `<img src="${event.target.result}" alt="Firma" class="img-fluid" style="max-height: 100px;">`;
            };
            reader.readAsDataURL(file);
            // Actualizar texto del input file
            const label = document.querySelector('.custom-file-label');
            label.textContent = file.name;
        }
    });
});

/**
 * JavaScript para el Proceso de Homologación
 * Sistema de Homologaciones - Universidad Autónoma del Cauca
 */

$(document).ready(function() {
    // Variables globales
    let homologacionesSeleccionadas = [];
    let totalCreditos = 0;

    // ===== EVENTOS DE INICIALIZACIÓN =====

    // Inicializar tooltips y popovers de Bootstrap
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    // Evento para mostrar información de asignatura de origen
    $(document).on('click', '.ver-asignatura-origen', function(e) {
        e.preventDefault();
        $('#infoNombreOrigen').text($(this).data('nombre'));
        $('#infoSemestreOrigen').text($(this).data('semestre'));
        $('#infoCreditosOrigen').text($(this).data('creditos'));
        $('#infoModalidadOrigen').text($(this).data('modalidad'));
    });

    // Evento para mostrar información de asignatura de destino
    $(document).on('click', '.ver-asignatura-destino', function(e) {
        e.preventDefault();
        $('#infoNombre').text($(this).data('nombre'));
        $('#infoSemestre').text($(this).data('semestre'));
        $('#infoCreditos').text($(this).data('creditos'));
        $('#infoModalidad').text($(this).data('modalidad'));

        // Mostrar contenido programático si existe
        if($(this).data('tema') || $(this).data('resultados') || $(this).data('descripcion')) {
            $('#infoContenidoProgramatico').show();
            $('#infoTema').text($(this).data('tema') || 'No disponible');
            $('#infoResultados').text($(this).data('resultados') || 'No disponible');
            $('#infoDescripcion').text($(this).data('descripcion') || 'No disponible');
        } else {
            $('#infoContenidoProgramatico').hide();
        }
    });

    // ===== SELECCIÓN DE MATERIAS =====

    // Evento para seleccionar materias de origen
    $(document).on('change', 'input[name="seleccionar[]"]', function() {
        const checkboxOrigen = $(this);
        const filaOrigen = checkboxOrigen.closest('tr');
        const semestreOrigen = checkboxOrigen.closest('tbody').data('semestre');

        // Si se está seleccionando (no deseleccionando)
        if(checkboxOrigen.is(':checked')) {
            // Desactivar otras selecciones en origen
            $('input[name="seleccionar[]"]').not(this).prop('checked', false);

            // Obtener datos de la asignatura seleccionada
            const nombreOrigen = filaOrigen.find('td:first-child a').text().trim();
            const notaOrigen = filaOrigen.find('td:nth-child(2)').text().trim();
            const creditosOrigen = filaOrigen.find('td:nth-child(3)').text().trim();
            const idAsignaturaOrigen = checkboxOrigen.val();
            const asignaturaData = JSON.parse(checkboxOrigen.attr('data-asignatura') || '{}');

            // Resaltar la fila seleccionada
            $('tbody.asignaturas-origen tr').removeClass('table-primary');
            filaOrigen.addClass('table-primary');

            // Mostrar mensaje de instrucción
            mostrarMensaje('Ahora seleccione una asignatura de destino para homologar', 'info');

            // Guardar datos en un atributo data para su uso posterior
            $(this).data('info', {
                nombreOrigen: nombreOrigen,
                notaOrigen: notaOrigen,
                creditosOrigen: creditosOrigen,
                idAsignaturaOrigen: idAsignaturaOrigen,
                semestreOrigen: semestreOrigen,
                asignaturaCompleta: asignaturaData
            });

            // Activar pestañas de destino
            $('#semestres-tab a[href="#semestre-' + semestreOrigen + '"]').tab('show');
        } else {
            // Si se está deseleccionando
            filaOrigen.removeClass('table-primary');
            $('input[name="seleccionar_destino[]"]').prop('checked', false);
            $('.asignaturas-destino tr').removeClass('table-success');
        }
    });

    // Evento para seleccionar materias de destino
    $(document).on('change', 'input[name="seleccionar_destino[]"]', function() {
        const checkboxDestino = $(this);
        const filaDestino = checkboxDestino.closest('tr');

        // Verificar si hay una materia de origen seleccionada
        const origenSeleccionado = $('input[name="seleccionar[]"]:checked');

        if(origenSeleccionado.length === 0) {
            checkboxDestino.prop('checked', false);
            mostrarMensaje('Primero debe seleccionar una asignatura de origen', 'warning');
            return;
        }

        // Si se está seleccionando (no deseleccionando)
        if(checkboxDestino.is(':checked')) {
            // Desactivar otras selecciones en destino
            $('input[name="seleccionar_destino[]"]').not(this).prop('checked', false);

            // Resaltar la fila seleccionada
            $('.asignaturas-destino tr').removeClass('table-success');
            filaDestino.addClass('table-success');

            // Obtener datos de la asignatura de destino
            const nombreDestino = filaDestino.find('td:first-child a').text().trim();
            const creditosDestino = filaDestino.find('td:nth-child(2)').text().trim();
            const idAsignaturaDestino = checkboxDestino.val();
            const asignaturaData = JSON.parse(checkboxDestino.attr('data-asignatura') || '{}');

            // Abrir modal para ingresar nota homologada
            $('#modal-agregar-homologacion').modal('show');

            // Obtener datos de la asignatura de origen
            const infoOrigen = origenSeleccionado.data('info');

            // Llenar los campos del modal
            $('#asignatura-origen').val(infoOrigen.idAsignaturaOrigen);
            $('#asignatura-destino').val(idAsignaturaDestino);
            $('#nota-origen').val(infoOrigen.notaOrigen);
            $('#nota-homologada').val(infoOrigen.notaOrigen);

            // Guardar temporalmente la información para el botón de confirmar
            $('#btn-confirmar-homologacion').data('homologacion', {
                solicitud_id: $('#solicitud_id').val(),
                asignatura_origen_id: infoOrigen.idAsignaturaOrigen,
                asignatura_destino_id: idAsignaturaDestino,
                nombre_origen: infoOrigen.nombreOrigen,
                nombre_destino: nombreDestino,
                nota_origen: infoOrigen.notaOrigen,
                creditos: creditosDestino,
                asignatura_origen_data: infoOrigen.asignaturaCompleta,
                asignatura_destino_data: asignaturaData
            });
        }
    });

    // Evento para confirmar homologación desde el modal
    $('#btn-confirmar-homologacion').on('click', function() {
        const homologacion = $(this).data('homologacion');
        const notaHomologada = $('#nota-homologada').val();
        const observacion = $('#observacion').val();

        // Validar nota
        if (!notaHomologada || parseFloat(notaHomologada) < 3.0 || parseFloat(notaHomologada) > 5.0) {
            mostrarMensaje('La nota debe estar entre 3.0 y 5.0', 'warning');
            return;
        }

        // Completar el objeto de homologación
        homologacion.nota_destino = notaHomologada;
        homologacion.observaciones = observacion;

        // Agregar a la tabla
        agregarHomologacionATabla(homologacion);

        // Cerrar el modal
        $('#modal-agregar-homologacion').modal('hide');

        // Limpiar selecciones
        $('input[name="seleccionar[]"]').prop('checked', false);
        $('input[name="seleccionar_destino[]"]').prop('checked', false);
        $('.asignaturas-origen tr').removeClass('table-primary');
        $('.asignaturas-destino tr').removeClass('table-success');

        mostrarMensaje('Homologación agregada correctamente', 'success');
    });

    // Agregar botón "No aplica" para las asignaturas sin homologación
    $('.tab-pane .col-md-6:nth-child(2) .card-body').each(function() {
        if (!$(this).find('.btn-no-aplica').length) {
            $(this).append(`
                <div class="mt-3">
                    <button class="btn btn-sm btn-outline-danger btn-no-aplica">
                        <i class="fas fa-times-circle mr-1"></i> Marcar como No Aplica
                    </button>
                </div>
            `);
        }
    });

    // Evento para opción "No aplica" en destino
    $(document).on('click', '.btn-no-aplica', function() {
        const origenSeleccionado = $('input[name="seleccionar[]"]:checked');

        if(origenSeleccionado.length === 0) {
            mostrarMensaje('Primero debe seleccionar una asignatura de origen', 'warning');
            return;
        }

        // Obtener datos de la asignatura de origen
        const infoOrigen = origenSeleccionado.data('info');

        // Crear objeto de homologación sin destino
        const homologacion = {
            id_homologacion: null,
            solicitud_id: $('#solicitud_id').val(),
            asignatura_origen_id: infoOrigen.idAsignaturaOrigen,
            asignatura_destino_id: null, // No aplica
            nombre_origen: infoOrigen.nombreOrigen,
            nombre_destino: 'No aplica',
            nota_origen: infoOrigen.notaOrigen,
            nota_destino: '0.0', // No aplica
            creditos: '0',
            observaciones: 'No homologable',
            asignatura_origen_data: infoOrigen.asignaturaCompleta
        };

        // Guardar homologación en la tabla
        agregarHomologacionATabla(homologacion);

        // Limpiar selecciones
        $('input[name="seleccionar[]"]').prop('checked', false);
        $('.asignaturas-origen tr').removeClass('table-primary');

        mostrarMensaje('Asignatura marcada como no homologable', 'info');
    });

    // ===== GESTIÓN DE LA TABLA DE HOMOLOGACIONES =====

    // Función para agregar una homologación a la tabla
    function agregarHomologacionATabla(homologacion) {
        // Comprobar si la asignatura ya está en la tabla
        const existente = homologacionesSeleccionadas.findIndex(h =>
            h.asignatura_origen_id === homologacion.asignatura_origen_id);

        if(existente !== -1) {
            // Actualizar homologación existente
            homologacionesSeleccionadas[existente] = homologacion;
            actualizarFilaHomologacion(homologacion, existente);
        } else {
            // Agregar nueva homologación
            homologacionesSeleccionadas.push(homologacion);

            // Crear fila en la tabla
            const fila = `
                <tr data-index="${homologacionesSeleccionadas.length - 1}">
                    <td>${homologacion.nombre_origen}</td>
                    <td>${homologacion.nombre_destino}</td>
                    <td>${homologacion.nota_origen}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm nota-homologada"
                            value="${homologacion.nota_destino}" min="3.0" max="5.0" step="0.1"
                            ${homologacion.nombre_destino === 'No aplica' ? 'disabled' : ''}>
                    </td>
                    <td>${homologacion.creditos}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning editar-homologacion">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger eliminar-homologacion">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            // Eliminar fila de "No hay asignaturas homologadas" si existe
            $('#no-homologaciones').remove();

            // Agregar fila a la tabla
            $('#homologaciones-body').append(fila);
        }

        // Recalcular total de créditos
        calcularTotalCreditos();
    }

    // Función para actualizar una fila existente
    function actualizarFilaHomologacion(homologacion, index) {
        const fila = $(`#homologaciones-body tr[data-index="${index}"]`);

        fila.find('td:nth-child(1)').text(homologacion.nombre_origen);
        fila.find('td:nth-child(2)').text(homologacion.nombre_destino);
        fila.find('td:nth-child(3)').text(homologacion.nota_origen);
        fila.find('td:nth-child(4) input').val(homologacion.nota_destino);
        fila.find('td:nth-child(5)').text(homologacion.creditos);

        if(homologacion.nombre_destino === 'No aplica') {
            fila.find('td:nth-child(4) input').prop('disabled', true);
        } else {
            fila.find('td:nth-child(4) input').prop('disabled', false);
        }
    }

    // Evento para cambiar la nota en la tabla
    $(document).on('change', '.nota-homologada', function() {
        const fila = $(this).closest('tr');
        const index = fila.data('index');
        const nuevaNota = $(this).val();

        // Validar nota
        if(nuevaNota < 3.0 || nuevaNota > 5.0) {
            mostrarMensaje('La nota debe estar entre 3.0 y 5.0', 'warning');
            $(this).val(homologacionesSeleccionadas[index].nota_destino);
            return;
        }

        // Actualizar el valor en el array
        homologacionesSeleccionadas[index].nota_destino = nuevaNota;
    });

    // Evento para editar homologación
    $(document).on('click', '.editar-homologacion', function() {
        const fila = $(this).closest('tr');
        const index = fila.data('index');
        const homologacion = homologacionesSeleccionadas[index];

        // Llenar el modal con los datos actuales
        $('#asignatura-origen').val(homologacion.asignatura_origen_id);
        $('#asignatura-destino').val(homologacion.asignatura_destino_id);
        $('#nota-origen').val(homologacion.nota_origen);
        $('#nota-homologada').val(homologacion.nota_destino);
        $('#observacion').val(homologacion.observaciones);

        // Guardar el índice para saber qué registro estamos editando
        $('#btn-confirmar-homologacion').data('edit-index', index);

        // Mostrar el modal
        $('#modal-agregar-homologacion').modal('show');
    });

    // Evento para eliminar homologación
    $(document).on('click', '.eliminar-homologacion', function() {
        const fila = $(this).closest('tr');
        const index = fila.data('index');

        // Confirmar eliminación
        if(confirm('¿Está seguro de eliminar esta homologación?')) {
            homologacionesSeleccionadas.splice(index, 1);
            fila.remove();

            // Reindexar las filas restantes
            $('#homologaciones-body tr').each(function(i) {
                $(this).attr('data-index', i);
            });

            // Recalcular créditos
            calcularTotalCreditos();

            mostrarMensaje('Homologación eliminada correctamente', 'success');
        }
    });

    // Función para calcular el total de créditos
    function calcularTotalCreditos() {
        totalCreditos = 0;

        homologacionesSeleccionadas.forEach(h => {
            // Solo contar las que tienen destino válido
            if(h.nombre_destino !== 'No aplica') {
                totalCreditos += parseFloat(h.creditos) || 0;
            }
        });

        $('#total-creditos').text(totalCreditos.toFixed(1));

        // Mostrar mensaje si no hay homologaciones
        if(homologacionesSeleccionadas.length === 0) {
            $('#homologaciones-body').html('<tr id="no-homologaciones"><td colspan="6" class="text-center">No hay asignaturas homologadas</td></tr>');
        }
    }

    // ===== GUARDAR Y GENERAR PDF =====

    // Evento para guardar cambios
    $('#btn-guardar').on('click', function() {
        if(homologacionesSeleccionadas.length === 0) {
            mostrarMensaje('No hay homologaciones para guardar', 'warning');
            return;
        }

        // Mostrar spinner de carga
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        $(this).prop('disabled', true);

        // Preparar datos para enviar
        const datos = {
            solicitud_id: $('#solicitud_id').val(),
            homologaciones: homologacionesSeleccionadas
        };

        // Enviar al servidor mediante AJAX
        $.ajax({
            url: '/admin/guardar-homologaciones',
            type: 'POST',
            data: JSON.stringify(datos),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    mostrarMensaje(response.message || 'Homologaciones guardadas correctamente', 'success');

                    // Actualizar IDs de homologación si se devolvieron
                    if(response.homologaciones) {
                        response.homologaciones.forEach((h, index) => {
                            if(homologacionesSeleccionadas[index]) {
                                homologacionesSeleccionadas[index].id_homologacion = h.id_homologacion;
                            }
                        });
                    }
                } else {
                    mostrarMensaje(response.message || 'Error al guardar homologaciones', 'error');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al guardar homologaciones';
                try {
                    const respuesta = JSON.parse(xhr.responseText);
                    errorMsg = respuesta.message || errorMsg;
                } catch(e) {
                    errorMsg += ': ' + xhr.statusText;
                }
                mostrarMensaje(errorMsg, 'error');
            },
            complete: function() {
                // Restaurar botón
                $('#btn-guardar').html('<i class="fas fa-save mr-1"></i> Guardar Cambios');
                $('#btn-guardar').prop('disabled', false);
            }
        });
    });

    // Evento para generar PDF
    $('#btn-generar-pdf').on('click', function() {
        if(homologacionesSeleccionadas.length === 0) {
            mostrarMensaje('No hay homologaciones para generar PDF', 'warning');
            return;
        }

        // Mostrar spinner de carga
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
        $(this).prop('disabled', true);

        // Preparar vista previa del PDF
        const imagenFirma = $('#firma-preview img').attr('src') || '';

        // Construir contenido HTML para la vista previa
        let contenidoPDF = `
            <div class="pdf-preview">
                <div class="header text-center mb-4">
                    <h3>Universidad Autónoma del Cauca</h3>
                    <h4>Formato de Homologación de Asignaturas</h4>
                </div>

                <div class="student-info mb-4">
                    <p><strong>Estudiante:</strong> ${$('.card-body p:contains("Nombre")').text().replace(/Nombre:|\s+/g, ' ').trim()}</p>
                    <p><strong>Identificación:</strong> ${$('.card-body p:contains("Identificación")').text().replace(/Identificación:|\s+/g, ' ').trim()}</p>
                    <p><strong>Universidad de Origen:</strong> ${$('.card-body p:contains("Universidad de Origen")').text().replace(/Universidad de Origen:|\s+/g, ' ').trim()}</p>
                    <p><strong>Programa de Destino:</strong> ${$('.card-body p:contains("Programa de Destino")').text().replace(/Programa de Destino:|\s+/g, ' ').trim()}</p>
                    <p><strong>Fecha:</strong> ${new Date().toLocaleDateString()}</p>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Asignatura Origen</th>
                                <th>Asignatura Destino</th>
                                <th>Nota Origen</th>
                                <th>Nota Homologada</th>
                                <th>Créditos</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        // Agregar filas de homologaciones
        homologacionesSeleccionadas.forEach(h => {
            contenidoPDF += `
                <tr>
                    <td>${h.nombre_origen}</td>
                    <td>${h.nombre_destino}</td>
                    <td>${h.nota_origen}</td>
                    <td>${h.nota_destino}</td>
                    <td>${h.creditos}</td>
                </tr>
            `;
        });

        // Cerrar tabla y agregar total de créditos
        contenidoPDF += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total de Créditos:</strong></td>
                                <td>${totalCreditos.toFixed(1)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="signatures mt-5">
                    <div class="row">
                        <div class="col-6">
                            <div class="signature-line">
                                <div class="signature-image mb-2">
                                    ${imagenFirma ? `<img src="${imagenFirma}" alt="Firma del Coordinador" height="100">` : '<p class="text-muted">No se ha cargado firma</p>'}
                                </div>
                                <hr>
                                <p class="text-center">Firma del Coordinador</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="signature-line">
                                <div class="signature-image mb-2">
                                    <p class="text-muted">Firma del Estudiante</p>
                                </div>
                                <hr>
                                <p class="text-center">Firma del Estudiante</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Mostrar vista previa en el modal
        $('#pdf-preview-content').html(contenidoPDF);
        $('#pdf-preview-modal').modal('show');

        // Restaurar botón
        $('#btn-generar-pdf').html('<i class="fas fa-file-pdf mr-1"></i> Generar PDF');
        $('#btn-generar-pdf').prop('disabled', false);
    });

    // Evento para confirmar y descargar PDF
    $('#btn-confirmar-pdf').on('click', function() {
        // Mostrar spinner de carga
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Descargando...');
        $(this).prop('disabled', true);

        // Preparar datos para enviar
        const datos = {
            solicitud_id: $('#solicitud_id').val(),
            homologaciones: homologacionesSeleccionadas,
            firma: $('#firma-preview img').attr('src') || ''
        };

        // Enviar solicitud al servidor para generar el PDF
        $.ajax({
            url: '/admin/generar-pdf-homologacion',
            type: 'POST',
            data: JSON.stringify(datos),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success && response.url) {
                    // Crear un enlace invisible y hacer clic en él para descargar
                    const link = document.createElement('a');
                    link.href = response.url;
                    link.download = response.filename || 'homologacion.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    mostrarMensaje('PDF generado y descargado correctamente', 'success');
                } else {
                    mostrarMensaje(response.message || 'Error al generar el PDF', 'error');
                }
            },
            error: function(xhr) {
                mostrarMensaje('Error al generar el PDF: ' + xhr.statusText, 'error');
            },
            complete: function() {
                // Cerrar modal y restaurar botón
                $('#pdf-preview-modal').modal('hide');
                $('#btn-confirmar-pdf').html('<i class="fas fa-check mr-1"></i> Confirmar y Descargar');
                $('#btn-confirmar-pdf').prop('disabled', false);
            }
        });
    });

    // Evento para cerrar homologación
    $('#btn-cerrar-homologacion').on('click', function() {
        if(homologacionesSeleccionadas.length === 0) {
            mostrarMensaje('No hay homologaciones para cerrar', 'warning');
            return;
        }

        if(!confirm('¿Está seguro de cerrar este proceso de homologación? Esta acción no se puede revertir.')) {
            return;
        }

        // Mostrar spinner de carga
        $(this).html('<i class="fas fa-spinner fa-spin"></i> Cerrando...');
        $(this).prop('disabled', true);

        // Preparar datos para enviar
        const datos = {
            solicitud_id: $('#solicitud_id').val(),
            homologaciones: homologacionesSeleccionadas,
            observaciones: 'Proceso de homologación completado'
        };

        // Enviar al servidor
        $.ajax({
            url: '/admin/cerrar-homologacion',
            type: 'POST',
            data: JSON.stringify(datos),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    mostrarMensaje(response.message || 'Homologación cerrada correctamente', 'success');

                    // Actualizar estado en la interfaz
                    $('#estado-solicitud').removeClass('badge-warning').addClass('badge-success');
                    $('#estado-solicitud').text('Aprobada');

                    // Desactivar controles
                    $('input[type="checkbox"]').prop('disabled', true);
                    $('.nota-homologada').prop('disabled', true);
                    $('.editar-homologacion, .eliminar-homologacion').prop('disabled', true);
                    $('#btn-guardar, #btn-agregar-homologacion').prop('disabled', true);
                } else {
                    mostrarMensaje(response.message || 'Error al cerrar homologación', 'error');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error al cerrar homologación';
                try {
                    const respuesta = JSON.parse(xhr.responseText);
                    errorMsg = respuesta.message || errorMsg;
                } catch(e) {
                    errorMsg += ': ' + xhr.statusText;
                }
                mostrarMensaje(errorMsg, 'error');
            },
            complete: function() {
                // Restaurar botón
                $('#btn-cerrar-homologacion').html('<i class="fas fa-times-circle mr-1"></i> Cerrar Homologación');
                $('#btn-cerrar-homologacion').prop('disabled', false);
            }
        });
    });

    // ===== GESTIÓN DE FIRMA =====

    // Evento para subir firma
    $('#firma').on('change', function(e) {
        const archivo = e.target.files[0];

        if(!archivo) return;

        if(!archivo.type.match('image.*')) {
            mostrarMensaje('El archivo debe ser una imagen (JPG, PNG, GIF)', 'warning');
            return;
        }

        // Actualizar label con el nombre del archivo
        $('.custom-file-label').text(archivo.name);

        // Mostrar vista previa
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#firma-preview').html(`<img src="${e.target.result}" alt="Vista previa de firma" class="img-fluid" style="max-height: 150px;">`);
        }
        reader.readAsDataURL(archivo);
    });

    // ===== UTILIDADES =====

    // Función para mostrar mensajes
    function mostrarMensaje(mensaje, tipo) {
        // Convertir 'error' a 'danger' para Bootstrap
        if (tipo === 'error') tipo = 'danger';

        // Crear div de alerta
        const alertDiv = $(`
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `);

        // Insertar al principio de la tarjeta principal
        $('.card-body:first').prepend(alertDiv);

        // Desaparecer después de 5 segundos
        setTimeout(function() {
            alertDiv.alert('close');
        }, 5000);
    }

    // Cargar homologaciones existentes si las hay
    function cargarHomologacionesExistentes() {
        const solicitudId = $('#solicitud_id').val();

        if(!solicitudId) return;

        // Comprobar si hay datos locales (simulación para fines de demostración)
        // En una implementación real, esto debería venir del servidor
        if (typeof homologacionesLocales !== 'undefined' && homologacionesLocales.length > 0) {
            homologacionesLocales.forEach(h => {
                agregarHomologacionATabla(h);
            });
            mostrarMensaje('Homologaciones cargadas correctamente', 'success');
            return;
        }

        // Si no hay datos locales, intentar cargar desde el servidor
        $.ajax({
            url: `/admin/obtener-homologaciones/${solicitudId}`,
            type: 'GET',
            success: function(response) {
                if(response.success && response.homologaciones && response.homologaciones.length > 0) {
                    response.homologaciones.forEach(h => {
                        agregarHomologacionATabla({
                            id_homologacion: h.id_homologacion,
                            solicitud_id: h.solicitud_id,
                            asignatura_origen_id: h.asignatura_origen_id,
                            asignatura_destino_id: h.asignatura_destino_id,
                            nombre_origen: h.nombre_origen,
                            nombre_destino: h.nombre_destino,
                            nota_origen: h.nota_origen,
                            nota_destino: h.nota_destino || h.nota_homologada,
                            creditos: h.creditos,
                            observaciones: h.observaciones
                        });
                    });

                    mostrarMensaje('Homologaciones cargadas correctamente', 'success');
                }
            },
            error: function(xhr) {
                console.error('Error al cargar homologaciones:', xhr);
                // No mostrar error al usuario, simplemente iniciar con tabla vacía
            }
        });
    }

    // Validar entrada numérica
    $(document).on('input', 'input[type="number"]', function() {
        const valor = parseFloat($(this).val());
        const min = parseFloat($(this).attr('min')) || 0;
        const max = parseFloat($(this).attr('max')) || 5;

        if (valor < min) {
            $(this).val(min);
        } else if (valor > max) {
            $(this).val(max);
        }
    });

    // Inicializar tooltips y popovers (repetido para elementos dinámicos)
    function inicializarComponentes() {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    }

    // ===== INICIALIZACIÓN DE LA PÁGINA =====

    // Verificar si la homologación ya está cerrada
    function verificarEstadoHomologacion() {
        const estado = $('#estado-solicitud').text().trim();

        if (estado === 'Aprobada' || estado === 'Cerrada' || estado === 'Finalizada') {
            // Desactivar controles
            $('input[type="checkbox"]').prop('disabled', true);
            $('.nota-homologada').prop('disabled', true);
            $('.editar-homologacion, .eliminar-homologacion').prop('disabled', true);
            $('#btn-guardar, #btn-agregar-homologacion').prop('disabled', true);

            // Mostrar mensaje informativo
            mostrarMensaje('Esta homologación ya está cerrada. No se pueden realizar cambios.', 'info');
        }
    }

    // Comprobar si hay una firma ya subida
    function comprobarFirmaExistente() {
        const solicitudId = $('#solicitud_id').val();

        if(!solicitudId) return;

        // Verificar si hay una firma guardada
        $.ajax({
            url: `/admin/obtener-firma/${solicitudId}`,
            type: 'GET',
            success: function(response) {
                if(response.success && response.firma) {
                    $('#firma-preview').html(`<img src="${response.firma}" alt="Firma del Coordinador" class="img-fluid" style="max-height: 150px;">`);
                    $('.custom-file-label').text('Firma cargada');
                }
            },
            error: function() {
                // No hacer nada si no se encuentra firma
            }
        });
    }

    // Iniciar carga de datos
    inicializarComponentes();
    verificarEstadoHomologacion();
    comprobarFirmaExistente();
    cargarHomologacionesExistentes();
});

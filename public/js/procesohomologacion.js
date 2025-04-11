$(document).on('click', '.btn-homologar', function() {
    var materiaId = $(this).data('materia-id');
    var materiaSeleccionada = $(this).closest('tr').find('select').val();
    var notaAsignada = $(this).closest('tr').find('.nota-homologada').val(); // Obtener la nota de la materia homologada

    if (materiaSeleccionada && notaAsignada) {
        // Enviar datos al servidor (AJAX)
        $.ajax({
            url: '/homologaciones/guardar-homologacion',  // Ruta para guardar la homologación
            type: 'POST',
            data: {
                materia_id: materiaId,
                materia_homologada: materiaSeleccionada,
                nota_homologada: notaAsignada, // Enviar la nota
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Si la homologación es exitosa, marcar la fila en verde
                $(this).closest('tr').css('background-color', '#d4edda');  // Verde claro
                $(this).prop('disabled', true); // Deshabilitar el botón
                alert('Materia homologada correctamente con la nota: ' + notaAsignada);
            }
        });
    } else {
        alert("Por favor, seleccione una materia y asigna una nota.");
    }
});

$(document).on('change', '.materia-seleccionada', function() {
    // Mostrar el modal para mostrar una materia a la vez (opcional)
    var materia = $(this).closest('tr');
    var materiaNombre = materia.find('td:first').text();
    var materiaNota = materia.find('td:nth-child(2)').text();
    var select = materia.find('select');

    // Aquí podrías abrir un modal o un área emergente que permita al usuario modificar los datos de la materia.
    $('#materiaModal .materia-nombre').text(materiaNombre);
    $('#materiaModal .materia-nota').text(materiaNota);
    $('#materiaModal').modal('show');
});
$(document).on('change', '.materia-seleccionada', function() {
    var selectedOption = $(this).find('option:selected');
    var pensumData = selectedOption.data('pensum');  // Datos del pensum

    if (pensumData) {
        var pensumList = JSON.parse(pensumData);  // Parseamos el JSON de pensum

        // Mostrar el pensum en la interfaz
        var pensumContainer = $(this).closest('tr').find('.pensum-info');
        var pensumListElement = pensumContainer.find('.pensum-list');

        pensumListElement.empty();  // Limpiar el contenido anterior

        // Añadir los elementos del pensum
        pensumList.forEach(function(item) {
            pensumListElement.append('<li class="list-group-item">' + item + '</li>');
        });

        pensumContainer.show();  // Mostrar el contenedor del pensum
    } else {
        $(this).closest('tr').find('.pensum-info').hide();  // Ocultar si no hay datos
    }
});

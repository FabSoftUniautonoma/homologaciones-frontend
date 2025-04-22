// Variables globales
let homologaciones = [];
let materiasCursadasUsadas = [];

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const materiaSelect = document.getElementById('materia-select');
    const materiaPensumSelect = document.getElementById('materia-pensum-select');
    const notaHomologacionInput = document.getElementById('nota_homologacion');
    const guardarMateriaBtn = document.getElementById('guardar-materia-btn');
    const guardarBtn = document.getElementById('guardar-btn');
    const homologacionesData = document.getElementById('homologaciones-data');

    // Eventos
    materiaSelect.addEventListener('change', handleMateriaChange);
    materiaPensumSelect.addEventListener('change', handleMateriaPensumChange);
    guardarMateriaBtn.addEventListener('click', guardarMateria);
    guardarBtn.addEventListener('click', guardarHomologacion);

    // Actualizar contador inicial
    actualizarContador();
});

// Manejar cambio en selección de materia cursada
function handleMateriaChange() {
    const materiaSelect = document.getElementById('materia-select');
    const materiaPensumSelect = document.getElementById('materia-pensum-select');
    const detallesMateria = document.getElementById('materia-details');
    const tituloPensum = document.getElementById('titulo-pensum');

    if (materiaSelect.value) {
        // Mostrar detalles de la materia seleccionada
        const selectedOption = materiaSelect.options[materiaSelect.selectedIndex];
        document.getElementById('materia-nombre').textContent = selectedOption.dataset.nombre;
        document.getElementById('materia-nota').textContent = selectedOption.dataset.nota;
        document.getElementById('materia-creditos').textContent = selectedOption.dataset.creditos;
        document.getElementById('materia-horas').textContent = selectedOption.dataset.horas;
        document.getElementById('materia-descripcion').textContent = selectedOption.dataset.descripcion;
        document.getElementById('materia-temas').textContent = selectedOption.dataset.temas;

        detallesMateria.style.display = 'block';
        materiaPensumSelect.style.display = 'block';
        tituloPensum.style.display = 'block';

        // Verificar si esta materia ya fue utilizada
        if (materiasCursadasUsadas.includes(materiaSelect.value)) {
            mostrarAlerta('Esta materia ya ha sido homologada.', 'warning');
            materiaPensumSelect.value = '';
            document.getElementById('pensum-details').style.display = 'none';
            document.getElementById('label-nota').style.display = 'none';
            document.getElementById('input-nota-container').style.display = 'none';
            document.getElementById('guardar-materia-btn').style.display = 'none';
        }
    } else {
        detallesMateria.style.display = 'none';
        materiaPensumSelect.style.display = 'none';
        tituloPensum.style.display = 'none';
        document.getElementById('pensum-details').style.display = 'none';
        document.getElementById('label-nota').style.display = 'none';
        document.getElementById('input-nota-container').style.display = 'none';
        document.getElementById('guardar-materia-btn').style.display = 'none';
    }
}

// Manejar cambio en selección de materia del pensum
function handleMateriaPensumChange() {
    const materiaPensumSelect = document.getElementById('materia-pensum-select');
    const detallesPensum = document.getElementById('pensum-details');
    const labelNota = document.getElementById('label-nota');
    const inputNotaContainer = document.getElementById('input-nota-container');
    const guardarMateriaBtn = document.getElementById('guardar-materia-btn');
    const notaHomologacionInput = document.getElementById('nota_homologacion');

    if (materiaPensumSelect.value) {
        // Mostrar detalles de la materia del pensum seleccionada
        const selectedOption = materiaPensumSelect.options[materiaPensumSelect.selectedIndex];
        document.getElementById('pensum-nombre').textContent = selectedOption.dataset.nombre;
        document.getElementById('pensum-creditos').textContent = selectedOption.dataset.creditos;
        document.getElementById('pensum-horas').textContent = selectedOption.dataset.horas;
        document.getElementById('pensum-descripcion').textContent = selectedOption.dataset.descripcion;
        document.getElementById('pensum-temas').textContent = selectedOption.dataset.temas;

        detallesPensum.style.display = 'block';
        labelNota.style.display = 'block';
        inputNotaContainer.style.display = 'block';
        guardarMateriaBtn.style.display = 'block';

        // Autocompletar nota de homologación con la nota original
        const materiaSelect = document.getElementById('materia-select');
        if (materiaSelect.value) {
            const selectedMateria = materiaSelect.options[materiaSelect.selectedIndex];
            notaHomologacionInput.value = selectedMateria.dataset.nota;
        }
    } else {
        detallesPensum.style.display = 'none';
        labelNota.style.display = 'none';
        inputNotaContainer.style.display = 'none';
        guardarMateriaBtn.style.display = 'none';
    }
}

// Guardar una materia homologada
function guardarMateria() {
    const materiaSelect = document.getElementById('materia-select');
    const materiaPensumSelect = document.getElementById('materia-pensum-select');
    const notaHomologacionInput = document.getElementById('nota_homologacion');

    // Validaciones
    if (!materiaSelect.value) {
        mostrarAlerta('Seleccione una materia cursada.', 'danger');
        return;
    }

    if (!materiaPensumSelect.value) {
        mostrarAlerta('Seleccione una materia del pensum.', 'danger');
        return;
    }

    const nota = parseFloat(notaHomologacionInput.value);
    if (isNaN(nota) || nota < 0 || nota > 5) {
        mostrarAlerta('Ingrese una nota válida entre 0 y 5.', 'danger');
        return;
    }

    // Obtener detalles
    const materiaCursadaOption = materiaSelect.options[materiaSelect.selectedIndex];
    const materiaPensumOption = materiaPensumSelect.options[materiaPensumSelect.selectedIndex];

    // Crear objeto de homologación
    const homologacion = {
        materia_cursada: materiaCursadaOption.dataset.nombre,
        materia_pensum: materiaPensumOption.dataset.nombre,
        nota_original: materiaCursadaOption.dataset.nota,
        nota: nota.toFixed(1),
        creditos: materiaPensumOption.dataset.creditos
    };

    // Añadir a la lista
    homologaciones.push(homologacion);
    materiasCursadasUsadas.push(materiaSelect.value);

    // Actualizar tabla
    actualizarTablaHomologadas();

    // Limpiar selecciones
    materiaSelect.value = '';
    materiaPensumSelect.value = '';
    notaHomologacionInput.value = '';

    // Ocultar detalles
    document.getElementById('materia-details').style.display = 'none';
    document.getElementById('pensum-details').style.display = 'none';
    document.getElementById('label-nota').style.display = 'none';
    document.getElementById('input-nota-container').style.display = 'none';
    document.getElementById('guardar-materia-btn').style.display = 'none';
    document.getElementById('materia-pensum-select').style.display = 'none';
    document.getElementById('titulo-pensum').style.display = 'none';

    // Actualizar contador y progreso
    actualizarContador();

    // Habilitar botón de guardar homologación
    document.getElementById('guardar-btn').disabled = homologaciones.length === 0;

    mostrarAlerta('Materia homologada correctamente.', 'success');
}

// Actualizar tabla de materias homologadas
function actualizarTablaHomologadas() {
    const tabla = document.getElementById('tabla-homologadas');
    const tbody = tabla.querySelector('tbody');
    const noMateriasRow = document.getElementById('no-materias-row');

    // Limpiar tabla
    tbody.innerHTML = '';

    if (homologaciones.length === 0) {
        tbody.appendChild(noMateriasRow);
        return;
    }

    // Añadir cada homologación
    homologaciones.forEach((h, index) => {
        const tr = document.createElement('tr');

        // Materia cursada
        const tdMateriaCursada = document.createElement('td');
        tdMateriaCursada.textContent = h.materia_cursada;
        tr.appendChild(tdMateriaCursada);

        // Materia pensum / Estado
        const tdMateriaPensum = document.createElement('td');
        if (h.materia_pensum === 'No Aplica') {
            tdMateriaPensum.innerHTML = `<span class="badge bg-warning">No Homologada</span>`;
        } else {
            tdMateriaPensum.innerHTML = `${h.materia_pensum} <span class="badge bg-success">Homologada</span>`;
        }
        tr.appendChild(tdMateriaPensum);

        // Nota
        const tdNota = document.createElement('td');
        tdNota.innerHTML = `<span class="badge bg-info text-dark">${h.nota}</span>`;
        tr.appendChild(tdNota);

        // Créditos
        const tdCreditos = document.createElement('td');
        tdCreditos.textContent = h.creditos;
        tr.appendChild(tdCreditos);

        tbody.appendChild(tr);
    });

    // Guardar datos en input hidden
    document.getElementById('homologaciones-data').value = JSON.stringify(homologaciones);
}

// Actualizar contador y barra de progreso
function actualizarContador() {
    const contadorHomologadas = document.getElementById('contador-homologadas');
    const contadorTotal = document.getElementById('contador-total');
    const progressBar = document.getElementById('progress-bar');

    // Obtener total de materias disponibles
    const materiaSelect = document.getElementById('materia-select');
    const totalMaterias = materiaSelect.options.length - 1; // Restar la opción por defecto

    contadorHomologadas.textContent = homologaciones.length;
    contadorTotal.textContent = totalMaterias;

    // Calcular porcentaje
    let porcentaje = 0;
    if (totalMaterias > 0) {
        porcentaje = Math.round((homologaciones.length / totalMaterias) * 100);
    }

    progressBar.style.width = `${porcentaje}%`;
    progressBar.textContent = `${porcentaje}%`;
}

// Guardar toda la homologación
function guardarHomologacion() {
    if (homologaciones.length === 0) {
        mostrarAlerta('No hay materias homologadas para guardar.', 'warning');
        return;
    }

    // Submit form
    document.getElementById('homologacion-form').submit();
}

// Mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const alertsContainer = document.getElementById('alerts-container');

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.role = 'alert';

    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    alertsContainer.appendChild(alertDiv);

    // Auto cerrar después de 5 segundos
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

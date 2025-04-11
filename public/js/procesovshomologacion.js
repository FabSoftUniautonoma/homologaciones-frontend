document.addEventListener('DOMContentLoaded', () => {
    // Elementos del DOM
    const materiaSelect = document.getElementById('materia-select');
    const pensumSelect = document.getElementById('materia-pensum-select');
    const notaInput = document.getElementById('nota_homologacion');
    const notaContainer = document.getElementById('input-nota-container') || document.getElementById('nota_homologacion').parentNode;
    const guardarMateriaBtn = document.getElementById('guardar-materia-btn');
    const guardarBtn = document.getElementById('guardar-btn');
    const formHomologacion = document.getElementById('homologacion-form');
    const homologacionesDataInput = document.getElementById('homologaciones-data');

    const detalleMateria = document.getElementById('materia-details');
    const detallePensum = document.getElementById('pensum-details');
    const labelNota = document.getElementById('label-nota');
    const tituloPensum = document.getElementById('titulo-pensum');
    const tablaHomologadas = document.getElementById('tabla-homologadas');
    const progressBar = document.getElementById('progress-bar');
    const contadorHomologadas = document.getElementById('contador-homologadas');
    const contadorTotal = document.getElementById('contador-total');

    // Estructuras de datos para almacenar homologaciones
    const homologaciones = new Map();
    const homologadas = new Set();

    // Funciones de inicialización
    function initializeTotalCounter() {
        const options = materiaSelect.querySelectorAll('option');
        let total = 0;

        options.forEach(option => {
            if (option.value) total++;
        });

        if (contadorTotal) contadorTotal.textContent = total;
        return total;
    }

    // Funciones para actualizar la interfaz de usuario
    function updateMateriaDetails() {
        const selected = materiaSelect.options[materiaSelect.selectedIndex];

        if (selected && selected.value) {
            document.getElementById('materia-nombre').textContent = selected.value;
            document.getElementById('materia-nota').textContent = selected.dataset.nota;
            document.getElementById('materia-descripcion').textContent = selected.dataset.descripcion;
            document.getElementById('materia-creditos').textContent = selected.dataset.creditos;
            document.getElementById('materia-horas').textContent = selected.dataset.horas;
            document.getElementById('materia-temas').textContent = selected.dataset.temas;
            detalleMateria.style.display = 'block';

            // Mostrar el selector de materias del pensum
            pensumSelect.style.display = 'block';

            // Si hay elementos label para el pensum, mostrarlos
            const pensumLabel = document.querySelector('label[for="materia-pensum-select"]');
            if (pensumLabel) pensumLabel.style.display = 'block';

            if (tituloPensum) tituloPensum.style.display = 'block';
        } else {
            detalleMateria.style.display = 'none';

            // Ocultar elementos relacionados con el pensum
            pensumSelect.style.display = 'none';

            const pensumLabel = document.querySelector('label[for="materia-pensum-select"]');
            if (pensumLabel) pensumLabel.style.display = 'none';

            if (detallePensum) detallePensum.style.display = 'none';
            if (notaContainer) notaContainer.style.display = 'none';
            if (labelNota) labelNota.style.display = 'none';
            if (tituloPensum) tituloPensum.style.display = 'none';
            guardarMateriaBtn.style.display = 'none';
        }

        checkMateriaValidity();
    }

    function updatePensumDetails() {
        const selected = pensumSelect.options[pensumSelect.selectedIndex];

        // Si no hay selección de pensum, ocultar todo lo relacionado
        if (!selected || !selected.value) {
            if (detallePensum) detallePensum.style.display = 'none';
            if (notaContainer) notaContainer.style.display = 'none';
            if (labelNota) labelNota.style.display = 'none';
            guardarMateriaBtn.style.display = 'none';
            return;
        }

        if (selected.value === 'no_aplica') {
            // Para "No Aplica", ocultar detalles del pensum pero mostrar botón
            if (detallePensum) detallePensum.style.display = 'none';
            if (notaContainer) notaContainer.style.display = 'none';
            if (labelNota) labelNota.style.display = 'none';

            guardarMateriaBtn.style.display = 'block';
            guardarMateriaBtn.disabled = false;
            guardarMateriaBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Materia (No Aplica)';
            guardarMateriaBtn.classList.remove('btn-success');
            guardarMateriaBtn.classList.add('btn-secondary');
            notaInput.disabled = true;
            notaInput.value = 0;

            showAlert('Esta materia será registrada como "No Aplica". Por favor, haga clic en guardar.', 'success');
        } else {
            // Para materias regulares del pensum, mostrar todos los detalles
            if (document.getElementById('pensum-nombre')) {
                document.getElementById('pensum-nombre').textContent = selected.value;
                document.getElementById('pensum-descripcion').textContent = selected.dataset.descripcion || 'Sin descripción';
                document.getElementById('pensum-creditos').textContent = selected.dataset.creditos || 'N/A';
                document.getElementById('pensum-horas').textContent = selected.dataset.horas || 'N/A';
                document.getElementById('pensum-temas').textContent = selected.dataset.temas || 'N/A';
            }

            if (detallePensum) detallePensum.style.display = 'block';
            if (notaContainer) notaContainer.style.display = 'block';
            if (labelNota) labelNota.style.display = 'block';

            guardarMateriaBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Materia';
            guardarMateriaBtn.classList.remove('btn-secondary');
            guardarMateriaBtn.classList.add('btn-success');
            notaInput.disabled = false;
            notaInput.value = ''; // Limpiar el campo de nota
            guardarMateriaBtn.style.display = 'block';
        }

        checkMateriaValidity();
    }

    function checkMateriaValidity() {
        const materia = materiaSelect.value;
        const pensum = pensumSelect.value;
        const nota = parseFloat(notaInput.value);

        guardarMateriaBtn.disabled = true;

        if (!materia || !pensum) {
            guardarMateriaBtn.style.display = 'none';
            return;
        }

        guardarMateriaBtn.style.display = 'block';

        if (pensum === 'no_aplica') {
            guardarMateriaBtn.disabled = false;
            return;
        }

        if (!isNaN(nota) && nota >= 3.0 && nota <= 5) {
            guardarMateriaBtn.disabled = false;
        } else if (notaInput.value !== '' && (!isNaN(nota) && nota < 3.0)) {
            showAlert('No se puede homologar esta materia porque la nota es inferior a 3.0', 'danger');
        }
    }

    function showAlert(message, type) {
        // Buscar contenedor de alertas o crearlo si no existe
        const alertsContainer = document.getElementById('alerts-container') || createAlertsContainer();

        const alert = document.createElement('div');
        alert.classList.add('alert', type === 'success' ? 'alert-success' : 'alert-danger');
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
        `;

        alertsContainer.appendChild(alert);

        setTimeout(() => alert.remove(), 3500);
    }

    function createAlertsContainer() {
        const container = document.createElement('div');
        container.id = 'alerts-container';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '1050';
        document.body.appendChild(container);
        return container;
    }

    // Funciones para gestionar las materias
    function saveMateria() {
        const cursada = materiaSelect.value;
        const pensum = pensumSelect.value;
        const nota = parseFloat(notaInput.value.trim() === '' ? 0 : notaInput.value);

        if (!cursada || !pensum) {
            showAlert('Seleccione una materia y una opción del pensum', 'danger');
            return;
        }

        if (homologadas.has(cursada)) {
            showAlert(`La materia "${cursada}" ya ha sido homologada.`, 'danger');
            return;
        }

        if (pensum !== 'no_aplica' && (isNaN(nota) || nota < 3.0 || nota > 5)) {
            showAlert('La nota debe estar entre 3.0 y 5.0 para homologar una materia', 'danger');
            return;
        }

        const optionCursada = materiaSelect.querySelector(`option[value="${cursada}"]`);
        const creditos = parseInt(optionCursada.getAttribute('data-creditos')) || 0;
        const horas = parseInt(optionCursada.getAttribute('data-horas')) || 0;

        homologadas.add(cursada);
        homologaciones.set(cursada, {
            materia_pensum: pensum,
            nota: pensum === 'no_aplica' ? 0 : nota,
            creditos: creditos,
            horas: horas
        });

        // Marcar visualmente la opción como seleccionada
        optionCursada.style.backgroundColor = '#28a745';
        optionCursada.style.color = 'white';
        optionCursada.disabled = true;

        // Actualizar tabla de materias homologadas
        updateHomologadasTable();

        // Actualizar barra de progreso
        updateProgressBar();

        showAlert(
            pensum === 'no_aplica'
                ? `La materia "${cursada}" fue marcada como "No Aplica".`
                : `La materia "${cursada}" fue homologada con nota ${nota}.`,
            'success'
        );

        // Reset
        materiaSelect.value = '';
        pensumSelect.value = '';
        notaInput.value = '';
        resetInterface();

        checkHomologacionValidity();
    }

    function resetInterface() {
        // Resetear todos los elementos de la interfaz
        detalleMateria.style.display = 'none';
        if (detallePensum) detallePensum.style.display = 'none';

        pensumSelect.style.display = 'none';
        if (notaContainer) notaContainer.style.display = 'none';
        if (labelNota) labelNota.style.display = 'none';
        if (tituloPensum) tituloPensum.style.display = 'none';

        notaInput.disabled = true;
        guardarMateriaBtn.disabled = true;
        guardarMateriaBtn.style.display = 'none';

        const pensumLabel = document.querySelector('label[for="materia-pensum-select"]');
        if (pensumLabel) pensumLabel.style.display = 'none';
    }

    function updateHomologadasTable() {
        const tbody = tablaHomologadas.querySelector('tbody');
        const noMateriasRow = document.getElementById('no-materias-row');

        if (noMateriasRow) {
            noMateriasRow.remove();
        }

        // Limpiar contenido anterior y agregar filas nuevas
        homologaciones.forEach((data, materia) => {
            // Solo agregar filas nuevas
            const rowId = `row-${materia.replace(/\s+/g, '-')}`;
            if (!document.getElementById(rowId)) {
                const newRow = document.createElement('tr');
                newRow.id = rowId;

                const estado = data.materia_pensum === 'no_aplica' ?
                    '<span class="badge bg-secondary">No Aplica</span>' :
                    data.materia_pensum;

                const notaDisplay = data.materia_pensum === 'no_aplica' ?
                    '<span class="badge bg-secondary">N/A</span>' :
                    `<span class="badge bg-success">${data.nota}</span>`;

                newRow.innerHTML = `
                    <td>${materia}</td>
                    <td>${estado}</td>
                    <td>${notaDisplay}</td>
                    <td>${data.creditos}</td>
                `;

                tbody.appendChild(newRow);
            }
        });
    }

    function updateProgressBar() {
        const totalMaterias = initializeTotalCounter();
        const materiasHomologadas = homologadas.size;
        const porcentaje = totalMaterias > 0 ? (materiasHomologadas / totalMaterias) * 100 : 0;

        if (progressBar) {
            progressBar.style.width = `${porcentaje}%`;
            progressBar.textContent = `${Math.round(porcentaje)}%`;
        }

        if (contadorHomologadas) {
            contadorHomologadas.textContent = materiasHomologadas;
        }
    }

    function checkHomologacionValidity() {
        const options = materiaSelect.querySelectorAll('option[value]');
        let totalValid = 0;
        let totalHomologadas = 0;
        const faltantes = [];

        options.forEach(option => {
            const nombre = option.value;
            if (!nombre) return;

            totalValid++;
            if (homologadas.has(nombre)) {
                const data = homologaciones.get(nombre);
                const notaValida = data.materia_pensum === 'no_aplica' ||
                    (typeof data.nota === 'number' && data.nota >= 3.0 && data.nota <= 5);

                if (notaValida) {
                    totalHomologadas++;
                } else {
                    faltantes.push(nombre);
                }
            } else {
                faltantes.push(nombre);
            }
        });

        if (totalHomologadas === totalValid && totalValid > 0) {
            guardarBtn.disabled = false;
            showAlert('Todas las materias han sido homologadas correctamente', 'success');
        } else {
            guardarBtn.disabled = true;
            if (faltantes.length > 0) {
                showAlert(`Faltan homologaciones válidas en: ${faltantes.join(', ')}`, 'danger');
            }
        }

        return totalHomologadas === totalValid && totalValid > 0;
    }

    // Funciones para generar PDF
    function generateAndDownloadPDF() {
        // Verificar que todas las materias estén homologadas
        if (!checkHomologacionValidity()) {
            showAlert('No se puede generar el PDF. Hay materias pendientes por homologar.', 'danger');
            return false;
        }

        // Función auxiliar para obtener texto desde un <p> que contiene cierto label
        function obtenerTextoPorLabel(label) {
            const parrafos = document.querySelectorAll('.card-body p');
            for (let p of parrafos) {
                if (p.textContent.includes(label)) {
                    return p.textContent.split(':')[1]?.trim() || '';
                }
            }
            return '';
        }

        // Datos para el encabezado del PDF
        const nombreEstudiante = obtenerTextoPorLabel('Nombre') || 'Estudiante';
        const instituto = obtenerTextoPorLabel('Instituto') || 'Instituto';
        const carrera = obtenerTextoPorLabel('Carrera de Interés') || 'Carrera';
        const codigoSolicitud = document.querySelector('.card-header h3')?.textContent.split(':')[1]?.trim() || 'No disponible';

        // Construcción del HTML del PDF
        let htmlContent = `
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Homologación - ${nombreEstudiante}</title>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        line-height: 1.6;
                        color: #333;
                        margin: 0;
                        padding: 20px;
                    }
                    .header { text-align: center; border-bottom: 2px solid #19407b; margin-bottom: 30px; }
                    h1 { color: #19407b; font-size: 24px; margin-bottom: 10px; }
                    h2 { color: #0075bf; font-size: 18px; margin: 15px 0; }
                    .info-container {
                        display: flex;
                        justify-content: space-between;
                        background-color: #f4f4f4;
                        padding: 15px;
                        border-radius: 5px;
                        margin-bottom: 20px;
                    }
                    .info-label { font-weight: bold; color: #19407b; }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    th, td {
                        padding: 12px 15px;
                        border-bottom: 1px solid #ddd;
                        text-align: left;
                    }
                    th {
                        background-color: #19407b;
                        color: white;
                    }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    .aprobada { color: #28a745; font-weight: bold; }
                    .no-aplica { color: #6c757d; font-style: italic; }
                    .summary {
                        background-color: #e6f7ff;
                        border-left: 5px solid #0075bf;
                        border-radius: 3px;
                        padding: 15px;
                        margin-top: 20px;
                    }
                    .creditos-total { font-weight: bold; color: #19407b; }
                    .firma-container {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 50px;
                    }
                    .firma {
                        width: 45%;
                        text-align: center;
                        border-top: 1px solid #333;
                        padding-top: 10px;
                    }
                    .footer {
                        text-align: center;
                        margin-top: 30px;
                        font-size: 12px;
                        color: #6c757d;
                        border-top: 1px solid #ddd;
                        padding-top: 15px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>PROCESO DE HOMOLOGACIÓN DE MATERIAS</h1>
                </div>

                <div class="info-container">
                    <div>
                        <div><span class="info-label">Estudiante:</span> ${nombreEstudiante}</div>
                        <div><span class="info-label">Instituto de Origen:</span> ${instituto}</div>
                    </div>
                    <div>
                        <div><span class="info-label">Código de Solicitud:</span> ${codigoSolicitud}</div>
                        <div><span class="info-label">Carrera de Interés:</span> ${carrera}</div>
                    </div>
                </div>

                <h2>Materias Homologadas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Materia Cursada</th>
                            <th>Materia del Pensum</th>
                            <th>Nota</th>
                            <th>Créditos</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        let totalCreditos = 0;
        let totalHoras = 0;

        homologaciones.forEach((data, materia) => {
            const esNoAplica = data.materia_pensum === 'no_aplica';
            totalCreditos += esNoAplica ? 0 : (data.creditos || 0);
            totalHoras += esNoAplica ? 0 : (data.horas || 0);

            htmlContent += `
                <tr>
                    <td>${materia}</td>
                    <td>${esNoAplica ? '<span class="no-aplica">No Aplica</span>' : data.materia_pensum}</td>
                    <td>${esNoAplica ? 'N/A' : `<span class="aprobada">${data.nota}</span>`}</td>
                    <td>${esNoAplica ? 'N/A' : data.creditos}</td>
                </tr>
            `;
        });

        htmlContent += `
                    </tbody>
                </table>

                <div class="summary">
                    <div><span class="info-label">Total Materias Homologadas:</span> ${homologaciones.size}</div>
                    <div><span class="info-label">Total Créditos:</span> <span class="creditos-total">${totalCreditos}</span></div>
                    <div><span class="info-label">Total Horas Académicas:</span> ${totalHoras}</div>
                </div>

                <div class="firma-container">
                    <div class="firma">
                        <div>Coordinador Académico</div>
                        <div>Nombre: ______________________</div>
                    </div>
                    <div class="firma">
                        <div>Estudiante</div>
                        <div>Nombre: ${nombreEstudiante}</div>
                    </div>
                </div>

                <div class="footer">
                    <p>Este documento es válido como constancia del proceso de homologación. Fecha de emisión: ${new Date().toLocaleDateString()}</p>
                </div>
            </body>
            </html>
        `;

        // Crear Blob y ventana para impresión
        const blob = new Blob([htmlContent], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const printWindow = window.open(url, '_blank');

        printWindow.onload = function () {
            printWindow.print();
            setTimeout(() => {
                printWindow.close();
                URL.revokeObjectURL(url);
            }, 1000);
        };

        // Enviar los datos al formulario oculto si es necesario
        prepareHomologacionesData();

        return true;
    }


    function prepareHomologacionesData() {
        // Convertir el Map de homologaciones a un objeto para el envío en JSON
        const homologacionesData = {};

        homologaciones.forEach((data, materia) => {
            homologacionesData[materia] = {
                materia_pensum: data.materia_pensum,
                nota: data.nota,
                creditos: data.creditos,
                horas: data.horas
            };
        });

        // Asignar los datos al campo oculto
        if (homologacionesDataInput) {
            homologacionesDataInput.value = JSON.stringify(homologacionesData);
        }
    }

    // Función para guardar la homologación
    function saveHomologacion(event) {
        event.preventDefault();

        if (!checkHomologacionValidity()) {
            showAlert('No se puede guardar la homologación. Hay materias pendientes.', 'danger');
            return false;
        }

        // Preparar los datos para el envío
        prepareHomologacionesData();

        // Si todo está correcto, proceder con la impresión del PDF
        const pdfGenerated = generateAndDownloadPDF();

        // Si el PDF se generó correctamente, enviar el formulario
        if (pdfGenerated && formHomologacion) {
            showAlert('Guardando homologación...', 'success');
            formHomologacion.submit();
        }
    }

    // Event listeners
    materiaSelect.addEventListener('change', () => {
        updateMateriaDetails();

        // Reiniciar selector de pensum y nota
        pensumSelect.value = '';
        notaInput.value = '';
        if (detallePensum) detallePensum.style.display = 'none';
        if (notaContainer) notaContainer.style.display = 'none';
        if (labelNota) labelNota.style.display = 'none';
        guardarMateriaBtn.style.display = 'none';
    });

    pensumSelect.addEventListener('change', updatePensumDetails);
    notaInput.addEventListener('input', checkMateriaValidity);
    guardarMateriaBtn.addEventListener('click', saveMateria);

    if (guardarBtn) {
        guardarBtn.addEventListener('click', saveHomologacion);
    }

    // Inicializar contadores
    initializeTotalCounter();
    resetInterface();

    // Verificar si hay homologaciones previas
    const prevHomologaciones = homologacionesDataInput && homologacionesDataInput.value ?
                              JSON.parse(homologacionesDataInput.value) : null;

    if (prevHomologaciones) {
        // Importar homologaciones previas
        for (const materia in prevHomologaciones) {
            if (prevHomologaciones.hasOwnProperty(materia)) {
                const data = prevHomologaciones[materia];
                homologaciones.set(materia, data);
                homologadas.add(materia);

                // Marcar visualmente la opción como seleccionada
                const option = materiaSelect.querySelector(`option[value="${materia}"]`);
                if (option) {
                    option.style.backgroundColor = '#28a745';
                    option.style.color = 'white';
                    option.disabled = true;
                }
            }
        }

        // Actualizar la interfaz si hay datos previos
        if (homologadas.size > 0) {
            updateHomologadasTable();
            updateProgressBar();
            checkHomologacionValidity();
        }
    }

    // Añadir funcionalidad para filtrar materias
    const searchInput = document.getElementById('search-materia');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const options = materiaSelect.querySelectorAll('option');

            options.forEach(option => {
                const value = option.value.toLowerCase();
                const shouldShow = value.includes(filter) || option.value === '';
                option.style.display = shouldShow ? '' : 'none';
            });
        });
    }

    // Añadir validación para nota decimal (máximo dos decimales)
    if (notaInput) {
        notaInput.addEventListener('blur', function() {
            if (this.value !== '') {
                const nota = parseFloat(this.value);
                if (!isNaN(nota)) {
                    this.value = nota.toFixed(2);
                }
            }
        });
    }

    // Añadir confirmación antes de salir si hay cambios sin guardar
    window.addEventListener('beforeunload', function(e) {
        if (homologadas.size > 0 && !document.querySelector('form').getAttribute('data-submitted')) {
            const message = '¿Seguro que desea salir? Los cambios no guardados se perderán.';
            e.returnValue = message;
            return message;
        }
    });

    // Marcar formulario como enviado cuando se envía correctamente
    if (formHomologacion) {
        formHomologacion.addEventListener('submit', function() {
            this.setAttribute('data-submitted', 'true');
        });
    }

    // Función para marcar todas las materias como "No Aplica" (opcional)
    const marcarTodasBtn = document.getElementById('marcar-todas-btn');
    if (marcarTodasBtn) {
        marcarTodasBtn.addEventListener('click', function() {
            const confirmar = confirm('¿Está seguro de que desea marcar todas las materias restantes como "No Aplica"?');
            if (confirmar) {
                const options = materiaSelect.querySelectorAll('option:not([disabled])');
                options.forEach(option => {
                    if (option.value && !homologadas.has(option.value)) {
                        materiaSelect.value = option.value;
                        updateMateriaDetails();
                        pensumSelect.value = 'no_aplica';
                        updatePensumDetails();
                        saveMateria();
                    }
                });
                showAlert('Todas las materias han sido marcadas como "No Aplica"', 'success');
            }
        });
    }
});

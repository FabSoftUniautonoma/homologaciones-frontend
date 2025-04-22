/**
 * Script para gestionar las funcionalidades de filtrado y acciones
 * en la página de coordinación de homologaciones
 */

document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del DOM
    const filtroForm = document.getElementById('filtroForm');
    const estadoSelect = document.getElementById('estado');
    const fechaInput = document.getElementById('fecha');
    const carreraSelect = document.getElementById('carrera');
    const estudianteInput = document.getElementById('estudiante');
    const buscarBtn = document.getElementById('buscarBtn');
    const limpiarBtn = document.getElementById('limpiarBtn');
    const tablaSolicitudes = document.getElementById('solicitudes-table');

    // Evento principal de búsqueda
    buscarBtn.addEventListener('click', filtrarSolicitudes);

    // Filtrado automático cuando cambian los valores de los filtros
    estadoSelect.addEventListener('change', filtrarSolicitudes);
    fechaInput.addEventListener('change', filtrarSolicitudes);
    carreraSelect.addEventListener('change', filtrarSolicitudes);
    estudianteInput.addEventListener('input', debounce(filtrarSolicitudes, 500));

    // Limpiar filtros
    limpiarBtn.addEventListener('click', limpiarFiltros);

    /**
     * Función principal para filtrar solicitudes
     */
    function filtrarSolicitudes() {
        const estado = estadoSelect.value.toLowerCase();
        const fecha = fechaInput.value;
        const carrera = carreraSelect.value.toLowerCase();
        const estudiante = estudianteInput.value.toLowerCase();

        // Obtener todas las filas de la tabla (excepto el encabezado)
        const filas = tablaSolicitudes.querySelectorAll('tbody tr');

        filas.forEach(fila => {
            // Verificar si la fila cumple con todos los criterios de filtrado
            const cumpleFiltros = verificarFiltros(fila, estado, fecha, carrera, estudiante);

            // Mostrar u ocultar la fila según corresponda
            fila.style.display = cumpleFiltros ? '' : 'none';
        });

        // Mostrar mensaje si no hay resultados
        verificarResultados(filas);
    }

    /**
     * Verificar si una fila cumple con los criterios de filtrado
     */
    function verificarFiltros(fila, estado, fecha, carrera, estudiante) {
        // Columnas: [0]ID, [1]Estudiante, [2]Programa, [3]Correo, [4]Fecha, [5]Institución, [6]Estado

        // Filtro por estado
        if (estado && !fila.cells[6].textContent.toLowerCase().includes(estado)) {
            return false;
        }

        // Filtro por fecha
        if (fecha) {
            const fechaFila = fila.cells[4].textContent.trim();
            if (fechaFila === 'Sin fecha') {
                return false;
            }

            // Convertir formato dd/mm/yyyy a yyyy-mm-dd para comparación
            const partesF = fechaFila.split('/');
            if (partesF.length !== 3) return false;

            const fechaFilaFormato = `${partesF[2]}-${partesF[1]}-${partesF[0]}`;
            if (fechaFilaFormato !== fecha) {
                return false;
            }
        }

        // Filtro por carrera/programa
        if (carrera && !fila.cells[2].textContent.toLowerCase().includes(carrera)) {
            return false;
        }

        // Filtro por estudiante (nombre o ID)
        if (estudiante) {
            const cumpleEstudiante =
                fila.cells[0].textContent.toLowerCase().includes(estudiante) || // ID
                fila.cells[1].textContent.toLowerCase().includes(estudiante);   // Nombre

            if (!cumpleEstudiante) {
                return false;
            }
        }

        // Si pasa todos los filtros
        return true;
    }

    /**
     * Verificar si hay resultados después del filtrado
     */
    function verificarResultados(filas) {
        // Contar filas visibles
        let filasVisibles = 0;
        filas.forEach(fila => {
            if (fila.style.display !== 'none') {
                filasVisibles++;
            }
        });

        // Verificar si existe un mensaje de "no hay resultados"
        let noResultadosRow = document.getElementById('no-resultados');

        // Si no hay filas visibles, mostrar mensaje
        if (filasVisibles === 0) {
            if (!noResultadosRow) {
                const tbody = tablaSolicitudes.querySelector('tbody');
                noResultadosRow = document.createElement('tr');
                noResultadosRow.id = 'no-resultados';
                noResultadosRow.innerHTML = `
                    <td colspan="8" class="text-center py-3">
                        <div class="alert alert-info mb-0">
                            No se encontraron solicitudes con los filtros aplicados
                        </div>
                    </td>
                `;
                tbody.appendChild(noResultadosRow);
            }
        } else {
            // Si hay resultados, ocultar mensaje si existe
            if (noResultadosRow) {
                noResultadosRow.remove();
            }
        }
    }

    /**
     * Limpiar todos los filtros
     */
    function limpiarFiltros() {
        estadoSelect.value = '';
        fechaInput.value = '';
        carreraSelect.value = '';
        estudianteInput.value = '';

        // Filtrar para mostrar todas las solicitudes
        filtrarSolicitudes();
    }

    /**
     * Función debounce para retrasar las búsquedas al escribir
     */
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(this, args);
            }, wait);
        };
    }

    /**
     * Inicialización: cargar datos desde sessionStorage si existen
     * (permite mantener filtros al navegar entre páginas)
     */
    function cargarFiltrosGuardados() {
        if (sessionStorage.getItem('filtrosHomologacion')) {
            try {
                const filtros = JSON.parse(sessionStorage.getItem('filtrosHomologacion'));
                estadoSelect.value = filtros.estado || '';
                fechaInput.value = filtros.fecha || '';
                carreraSelect.value = filtros.carrera || '';
                estudianteInput.value = filtros.estudiante || '';

                // Aplicar filtros guardados
                filtrarSolicitudes();
            } catch (e) {
                console.error('Error al cargar filtros guardados:', e);
                sessionStorage.removeItem('filtrosHomologacion');
            }
        }
    }

    /**
     * Guardar filtros en sessionStorage cuando se modifican
     */
    function guardarFiltros() {
        const filtros = {
            estado: estadoSelect.value,
            fecha: fechaInput.value,
            carrera: carreraSelect.value,
            estudiante: estudianteInput.value
        };

        sessionStorage.setItem('filtrosHomologacion', JSON.stringify(filtros));
    }

    // Agregar eventos para guardar filtros
    filtroForm.addEventListener('change', guardarFiltros);
    estudianteInput.addEventListener('blur', guardarFiltros);

    // Cargar filtros guardados al iniciar
    cargarFiltrosGuardados();

    // Mejorar la experiencia de usuario agregando eventos adicionales

    // Enter en el campo de búsqueda ejecuta la búsqueda
    filtroForm.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filtrarSolicitudes();
        }
    });

    // Ajuste adicional para la responsividad de la tabla
    window.addEventListener('resize', function() {
        const tableContainer = tablaSolicitudes.closest('.table-responsive');
        if (tableContainer) {
            if (window.innerWidth < 768) {
                tableContainer.classList.add('overflow-auto');
            } else {
                tableContainer.classList.remove('overflow-auto');
            }
        }
    });

    // Iniciar con la función de redimensionamiento
    window.dispatchEvent(new Event('resize'));
});

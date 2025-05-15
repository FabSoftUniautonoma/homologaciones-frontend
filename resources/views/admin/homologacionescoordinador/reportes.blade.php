@extends('admin.layouts.appcoordinacion')

@section('content')
    <div class="container mt-5">
        <h2 class="text-primary mb-4">Reportes de Homologaciones</h2>
        <!-- Alertas para notificaciones -->
        <div id="alertas-container" class="mb-4"></div>
        <!-- Filtros y botones de exportación -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filtro-fecha">Filtrar por período:</label>
                            <select id="filtro-fecha" class="form-control">
                                <option value="all">Todos los períodos</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="filtro-estado">Filtrar por estado:</label>
                            <select id="filtro-estado" class="form-control">
                                <option value="all">Todos los estados</option>
                                <option value="Radicado">Radicado</option>
                                <option value="En revisión">En revisión</option>
                                <option value="Aprobado">Aprobado</option>
                                <option value="Rechazado">Rechazado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-right">
                        <button id="btn-actualizar" class="btn btn-primary">Actualizar datos</button>
                        <button id="btn-exportar-excel" class="btn btn-success ml-2">Exportar a Excel</button>
                        <button id="btn-exportar-pdf" class="btn btn-danger ml-2">Exportar a PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Materias más homologadas -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">Materias más Homologadas</div>
                    <div class="card-body">
                        <div class="chart-loader" id="loader-materias">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                        </div>
                        <canvas id="materiasChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Estudiante con más homologaciones -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">Top Estudiantes con más Homologaciones</div>
                    <div class="card-body">
                        <div class="chart-loader" id="loader-estudiantes">
                            <div class="spinner-border text-success" role="status">
                                <span class="sr-only">Cargando...</span>
                            </div>
                        </div>
                        <canvas id="estudiantesChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Instituciones de origen -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-info text-white">Instituciones de Origen con más Solicitudes</div>
                        <div class="card-body">
                            <div class="chart-loader" id="loader-instituciones">
                                <div class="spinner-border text-info" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                            </div>
                            <canvas id="institucionesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Distribución de solicitudes por estado -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-warning text-white">Distribución de Estados</div>
                        <div class="card-body">
                            <div class="chart-loader" id="loader-estados">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                            </div>
                            <canvas id="estadosChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tendencia temporal de solicitudes -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-secondary text-white">Tendencia de Solicitudes</div>
                        <div class="card-body">
                            <div class="chart-loader" id="loader-tendencia">
                                <div class="spinner-border text-secondary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                            </div>
                            <canvas id="tendenciaChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tabla de datos -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Datos de Solicitudes</h5>
                    <input type="text" id="tabla-buscar" class="form-control form-control-sm w-25"
                        placeholder="Buscar...">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tabla-solicitudes">
                        <thead>
                            <tr>
                                <th>Radicado</th>
                                <th>Estudiante</th>
                                <th>Institución Origen</th>
                                <th>Estado</th>
                                <th>Fecha Solicitud</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-solicitudes-body">
                            <tr>
                                <td colspan="5" class="text-center">Cargando datos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <select id="tabla-registros" class="form-control form-control-sm">
                            <option value="5">5 registros</option>
                            <option value="10" selected>10 registros</option>
                            <option value="25">25 registros</option>
                            <option value="50">50 registros</option>
                        </select>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm" id="tabla-paginacion">
                            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#">Siguiente</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de exportación -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Generando Reporte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="sr-only">Generando...</span>
                    </div>
                    <p>Preparando su reporte, por favor espere...</p>
                    <div class="progress">
                        <div id="export-progress" class="progress-bar progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chart-loader {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 10;
        }

        .card-body {
            position: relative;
            min-height: 250px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.075);
            cursor: pointer;
        }
    </style>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SheetJS (XLSX) para exportación a Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <!-- html2pdf para exportación a PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        // Variables globales
        let allSolicitudes = [];
        let chartMaterias, chartEstudiantes, chartInstituciones, chartEstados, chartTendencia;
        let currentPage = 1;
        let recordsPerPage = 10;
        let filteredData = [];

        // Función para mostrar alertas
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertas-container');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
            alertContainer.appendChild(alertDiv);

            // Auto-eliminar la alerta después de 5 segundos
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 150);
            }, 5000);
        }

        // Función para cargar datos desde la API
        async function cargarDatos() {
            // Mostrar loaders
            document.querySelectorAll('.chart-loader').forEach(loader => {
                loader.style.display = 'flex';
            });

            try {
                const response = await fetch('http://127.0.0.1:8000/api/solicitudes');

                if (!response.ok) {
                    throw new Error('Error al cargar los datos');
                }

                const data = await response.json();
                allSolicitudes = data;
                filteredData = [...data];

                // Aplicar filtros iniciales si hay
                aplicarFiltros();

                // Actualizar visualizaciones
                updateCharts(filteredData);
                actualizarTabla(filteredData);

                // Ocultar loaders
                document.querySelectorAll('.chart-loader').forEach(loader => {
                    loader.style.display = 'none';
                });

                showAlert('Datos cargados correctamente', 'success');

            } catch (error) {
                console.error('Error:', error);
                showAlert('Error al cargar los datos: ' + error.message, 'danger');

                // Ocultar loaders en caso de error
                document.querySelectorAll('.chart-loader').forEach(loader => {
                    loader.style.display = 'none';
                });
            }
        }

        // Función para actualizar todos los gráficos
        function updateCharts(solicitudes) {
            actualizarGraficoMaterias(solicitudes);
            actualizarGraficoEstudiantes(solicitudes);
            actualizarGraficoInstituciones(solicitudes);
            actualizarGraficoEstados(solicitudes);
            actualizarGraficoTendencia(solicitudes);
        }

        // Función para actualizar gráfico de materias homologadas
        // Nota: Como los datos de la API no incluyen materias específicas,
        // este gráfico mostrará datos simulados basados en los datos reales disponibles
        function actualizarGraficoMaterias(solicitudes) {
            // Crear datos simulados basados en la cantidad real de solicitudes
            const totalSolicitudes = solicitudes.length;
            const materiasSimuladas = [{
                    nombre: 'Programación I',
                    count: Math.floor(totalSolicitudes * 0.35)
                },
                {
                    nombre: 'Matemáticas I',
                    count: Math.floor(totalSolicitudes * 0.25)
                },
                {
                    nombre: 'Bases de Datos',
                    count: Math.floor(totalSolicitudes * 0.20)
                },
                {
                    nombre: 'Álgebra',
                    count: Math.floor(totalSolicitudes * 0.15)
                },
                {
                    nombre: 'Sistemas Operativos',
                    count: Math.floor(totalSolicitudes * 0.05)
                }
            ];

            // Ordenar de mayor a menor
            materiasSimuladas.sort((a, b) => b.count - a.count);

            const labels = materiasSimuladas.map(item => item.nombre);
            const data = materiasSimuladas.map(item => item.count);

            // Colores
            const backgroundColors = [
                '#007bff', '#0056b3', '#3399ff', '#66b3ff', '#99ccff'
            ];

            // Actualizar o crear el gráfico
            const ctx = document.getElementById('materiasChart').getContext('2d');

            if (chartMaterias) {
                chartMaterias.data.labels = labels;
                chartMaterias.data.datasets[0].data = data;
                chartMaterias.update();
            } else {
                chartMaterias = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Número de Homologaciones',
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Materias Más Homologadas'
                            }
                        }
                    }
                });
            }
        }

        // Función para actualizar gráfico de estudiantes
        function actualizarGraficoEstudiantes(solicitudes) {
            // Agrupar por estudiante (utilizando nombre y apellido)
            const estudiantesCounts = {};

            solicitudes.forEach(solicitud => {
                const nombreCompleto = `${solicitud.primer_nombre} ${solicitud.primer_apellido}`;
                if (estudiantesCounts[nombreCompleto]) {
                    estudiantesCounts[nombreCompleto]++;
                } else {
                    estudiantesCounts[nombreCompleto] = 1;
                }
            });

            // Convertir a array y ordenar
            const estudiantesArray = Object.entries(estudiantesCounts).map(([nombre, count]) => ({
                nombre,
                count
            }));

            estudiantesArray.sort((a, b) => b.count - a.count);

            // Tomar los top 5
            const top5 = estudiantesArray.slice(0, 5);

            const labels = top5.map(item => item.nombre);
            const data = top5.map(item => item.count);

            // Colores
            const backgroundColors = [
                '#28a745', '#218838', '#34d058', '#5ec98d', '#a8e6cf'
            ];

            // Actualizar o crear el gráfico
            const ctx = document.getElementById('estudiantesChart').getContext('2d');

            if (chartEstudiantes) {
                chartEstudiantes.data.labels = labels;
                chartEstudiantes.data.datasets[0].data = data;
                chartEstudiantes.update();
            } else {
                chartEstudiantes = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Homologaciones',
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Estudiantes con Más Homologaciones'
                            }
                        }
                    }
                });
            }
        }

        // Función para actualizar gráfico de instituciones
        function actualizarGraficoInstituciones(solicitudes) {
            // Agrupar por institución
            const institucionesCounts = {};

            solicitudes.forEach(solicitud => {
                const institucion = solicitud.institucion_origen_nombre;
                if (institucionesCounts[institucion]) {
                    institucionesCounts[institucion]++;
                } else {
                    institucionesCounts[institucion] = 1;
                }
            });

            // Convertir a array y ordenar
            const institucionesArray = Object.entries(institucionesCounts).map(([nombre, count]) => ({
                nombre,
                count
            }));

            institucionesArray.sort((a, b) => b.count - a.count);

            const labels = institucionesArray.map(item => item.nombre);
            const data = institucionesArray.map(item => item.count);

            // Colores
            const backgroundColors = [
                '#17a2b8', '#138496', '#5bc0de', '#7fd5f5', '#c6eefd',
                '#5DADE2', '#3498DB', '#2E86C1', '#2874A6', '#1F618D'
            ];

            // Actualizar o crear el gráfico
            const ctx = document.getElementById('institucionesChart').getContext('2d');

            if (chartInstituciones) {
                chartInstituciones.data.labels = labels;
                chartInstituciones.data.datasets[0].data = data;
                chartInstituciones.update();
            } else {
                chartInstituciones = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Solicitudes de Homologación',
                            data: data,
                            backgroundColor: backgroundColors.slice(0, labels.length)
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Instituciones de Origen con Más Solicitudes'
                            }
                        }
                    }
                });
            }
        }

        // Función para actualizar gráfico de estados
        function actualizarGraficoEstados(solicitudes) {
            // Contar por estado
            const estadosCounts = {
                'Radicado': 0,
                'En revisión': 0,
                'Aprobado': 0,
                'Rechazado': 0
            };

            solicitudes.forEach(solicitud => {
                if (estadosCounts[solicitud.estado] !== undefined) {
                    estadosCounts[solicitud.estado]++;
                }
            });

            const labels = Object.keys(estadosCounts);
            const data = Object.values(estadosCounts);

            // Colores por estado
            const backgroundColors = [
                '#007bff', // Radicado (primary)
                '#ffc107', // En revisión (warning)
                '#28a745', // Aprobado (success)
                '#dc3545' // Rechazado (danger)
            ];

            // Actualizar o crear el gráfico
            const ctx = document.getElementById('estadosChart').getContext('2d');

            if (chartEstados) {
                chartEstados.data.labels = labels;
                chartEstados.data.datasets[0].data = data;
                chartEstados.update();
            } else {
                chartEstados = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            title: {
                                display: true,
                                text: 'Distribución por Estado'
                            }
                        }
                    }
                });
            }
        }

        // Función para actualizar gráfico de tendencia temporal
        function actualizarGraficoTendencia(solicitudes) {
            // Agrupar por mes
            const solicitudesPorMes = {};

            solicitudes.forEach(solicitud => {
                const fecha = new Date(solicitud.fecha_solicitud);
                const mes = fecha.getMonth() + 1;
                const año = fecha.getFullYear();
                const periodo = `${año}-${mes.toString().padStart(2, '0')}`;

                if (solicitudesPorMes[periodo]) {
                    solicitudesPorMes[periodo]++;
                } else {
                    solicitudesPorMes[periodo] = 1;
                }
            });

            // Convertir a array y ordenar por fecha
            const solicitudesMesArray = Object.entries(solicitudesPorMes).map(([periodo, count]) => ({
                periodo,
                count
            }));

            solicitudesMesArray.sort((a, b) => a.periodo.localeCompare(b.periodo));

            const labels = solicitudesMesArray.map(item => {
                const [año, mes] = item.periodo.split('-');
                const nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov',
                    'Dic'
                ];
                return `${nombresMeses[parseInt(mes) - 1]} ${año}`;
            });

            const data = solicitudesMesArray.map(item => item.count);

            // Actualizar o crear el gráfico
            const ctx = document.getElementById('tendenciaChart').getContext('2d');

            if (chartTendencia) {
                chartTendencia.data.labels = labels;
                chartTendencia.data.datasets[0].data = data;
                chartTendencia.update();
            } else {
                chartTendencia = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Solicitudes por Mes',
                            data: data,
                            borderColor: '#6c757d',
                            backgroundColor: 'rgba(108, 117, 125, 0.2)',
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Tendencia de Solicitudes por Mes'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        }

        // Función para aplicar filtros
        function aplicarFiltros() {
            const filtroFecha = document.getElementById('filtro-fecha').value;
            const filtroEstado = document.getElementById('filtro-estado').value;

            filteredData = allSolicitudes.filter(solicitud => {
                // Filtrar por año
                let pasaFiltroFecha = true;
                if (filtroFecha !== 'all') {
                    const año = new Date(solicitud.fecha_solicitud).getFullYear().toString();
                    pasaFiltroFecha = año === filtroFecha;
                }

                // Filtrar por estado
                let pasaFiltroEstado = true;
                if (filtroEstado !== 'all') {
                    pasaFiltroEstado = solicitud.estado === filtroEstado;
                }

                return pasaFiltroFecha && pasaFiltroEstado;
            });

            // Actualizar visualizaciones con los datos filtrados
            updateCharts(filteredData);

            // Resetear a la primera página cuando se filtran los datos
            currentPage = 1;
            actualizarTabla(filteredData);
        }

        // Función para actualizar la tabla de datos
        function actualizarTabla(solicitudes) {
            const tabla = document.getElementById('tabla-solicitudes-body');
            const busqueda = document.getElementById('tabla-buscar').value.toLowerCase();

            // Filtrar por búsqueda si hay texto
            let solicitudesFiltradas = solicitudes;
            if (busqueda) {
                solicitudesFiltradas = solicitudes.filter(s =>
                    s.numero_radicado.toLowerCase().includes(busqueda) ||
                    (s.primer_nombre + ' ' + s.primer_apellido).toLowerCase().includes(busqueda) ||
                    s.institucion_origen_nombre.toLowerCase().includes(busqueda) ||
                    s.estado.toLowerCase().includes(busqueda)
                );
            }

            // Calcular paginación
            const startIndex = (currentPage - 1) * recordsPerPage;
            const endIndex = startIndex + recordsPerPage;
            const paginatedData = solicitudesFiltradas.slice(startIndex, endIndex);

            // Actualizar datos de la tabla
            if (paginatedData.length === 0) {
                tabla.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron registros</td></tr>';
            } else {
                tabla.innerHTML = '';
                paginatedData.forEach(solicitud => {
                    const row = document.createElement('tr');

                    // Formatear fecha
                    const fecha = new Date(solicitud.fecha_solicitud);
                    const fechaFormateada = fecha.toLocaleDateString('es-CO', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });

                    // Determinar clase de estado
                    let claseEstado = '';
                    switch (solicitud.estado) {
                        case 'Aprobado':
                            claseEstado = 'success';
                            break;
                        case 'Rechazado':
                            claseEstado = 'danger';
                            break;
                        case 'En revisión':
                            claseEstado = 'warning';
                            break;
                        default:
                            claseEstado = 'primary';
                    }

                    row.innerHTML = `
                    <td>${solicitud.numero_radicado}</td>
                    <td>${solicitud.primer_nombre} ${solicitud.segundo_nombre || ''} ${solicitud.primer_apellido} ${solicitud.segundo_apellido || ''}</td>
                    <td>${solicitud.institucion_origen_nombre}</td>
                    <td><span class="badge badge-${claseEstado}">${solicitud.estado}</span></td>
                    <td>${fechaFormateada}</td>
                `;

                    tabla.appendChild(row);
                });
            }

            // Actualizar paginación
            actualizarPaginacion(solicitudesFiltradas.length);
        }

        // Función para actualizar los controles de paginación
        function actualizarPaginacion(totalRegistros) {
            const totalPaginas = Math.ceil(totalRegistros / recordsPerPage);
            const paginacion = document.getElementById('tabla-paginacion');

            paginacion.innerHTML = '';

            // Botón anterior
            const btnAnterior = document.createElement('li');
            btnAnterior.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            btnAnterior.innerHTML = '<a class="page-link" href="#">Anterior</a>';
            btnAnterior.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    actualizarTabla(filteredData);
                }
            });
            paginacion.appendChild(btnAnterior);

            // Páginas numeradas
            const maxPagesToShow = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(totalPaginas, startPage + maxPagesToShow - 1);

            if (endPage - startPage + 1 < maxPagesToShow) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const btnPagina = document.createElement('li');
                btnPagina.className = `page-item ${i === currentPage ? 'active' : ''}`;
                btnPagina.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                btnPagina.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = i;
                    actualizarTabla(filteredData);
                });
                paginacion.appendChild(btnPagina);
            }

            // Botón siguiente
            const btnSiguiente = document.createElement('li');
            btnSiguiente.className = `page-item ${currentPage === totalPaginas ? 'disabled' : ''}`;
            btnSiguiente.innerHTML = '<a class="page-link" href="#">Siguiente</a>';
            btnSiguiente.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage < totalPaginas) {
                    currentPage++;
                    actualizarTabla(filteredData);
                }
            });
            paginacion.appendChild(btnSiguiente);
        }

        // Función para exportar a Excel
        function exportarExcel() {
            // Mostrar modal de progreso
            $('#exportModal').modal('show');

            // Simular progreso
            let progress = 0;
            const progressBar = document.getElementById('export-progress');
            const interval = setInterval(() => {
                progress += 10;
                progressBar.style.width = `${progress}%`;
                progressBar.setAttribute('aria-valuenow', progress);

                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        $('#exportModal').modal('hide');
                        generarReporteExcel();
                    }, 500);
                }
            }, 200);
        }

        // Generar el reporte de Excel
        function generarReporteExcel() {
            // Crear una hoja de trabajo
            const ws = XLSX.utils.json_to_sheet(filteredData.map(s => ({
                'Radicado': s.numero_radicado,
                'Nombres': `${s.primer_nombre} ${s.segundo_nombre || ''}`,
                'Apellidos': `${s.primer_apellido} ${s.segundo_apellido || ''}`,
                'Institución Origen': s.institucion_origen_nombre,
                'Estado': s.estado,
                'Fecha Solicitud': new Date(s.fecha_solicitud).toLocaleDateString('es-CO')
            })));

            // Crear libro de trabajo y añadir la hoja
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Homologaciones");

            // Generar el archivo
            const fechaActual = new Date().toISOString().slice(0, 10);
            XLSX.writeFile(wb, `Reporte_Homologaciones_${fechaActual}.xlsx`);

            showAlert('Reporte exportado exitosamente', 'success');
        }

        // Función para exportar a PDF
        function exportarPDF() {
            // Mostrar modal de progreso
            $('#exportModal').modal('show');

            // Simular progreso
            let progress = 0;
            const progressBar = document.getElementById('export-progress');
            const interval = setInterval(() => {
                progress += 10;
                progressBar.style.width = `${progress}%`;
                progressBar.setAttribute('aria-valuenow', progress);

                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        $('#exportModal').modal('hide');
                        generarReportePDF();
                    }, 500);
                }
            }, 200);
        }

        // Generar el reporte PDF
        function generarReportePDF() {
            // Crear un contenedor para el PDF
            const contenedor = document.createElement('div');
            contenedor.style.padding = '20px';
            contenedor.style.fontFamily = 'Arial, sans-serif';

            // Añadir título
            const titulo = document.createElement('h2');
            titulo.textContent = 'Reporte de Homologaciones';
            titulo.style.textAlign = 'center';
            titulo.style.color = '#007bff';
            contenedor.appendChild(titulo);

            // Añadir fecha
            const fecha = document.createElement('p');
            fecha.textContent = `Fecha de generación: ${new Date().toLocaleDateString('es-CO')}`;
            fecha.style.textAlign = 'right';
            contenedor.appendChild(fecha);

            // Añadir filtros aplicados
            const filtros = document.createElement('div');
            filtros.innerHTML = `
            <p><strong>Filtros aplicados:</strong></p>
            <ul>
                <li>Período: ${document.getElementById('filtro-fecha').options[document.getElementById('filtro-fecha').selectedIndex].text}</li>
                <li>Estado: ${document.getElementById('filtro-estado').options[document.getElementById('filtro-estado').selectedIndex].text}</li>
            </ul>
        `;
            contenedor.appendChild(filtros);

            // Añadir tabla
            const tabla = document.createElement('table');
            tabla.style.width = '100%';
            tabla.style.borderCollapse = 'collapse';
            tabla.style.marginTop = '20px';

            // Cabecera de la tabla
            const cabecera = document.createElement('thead');
            cabecera.innerHTML = `
            <tr style="background-color: #007bff; color: white;">
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Radicado</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Estudiante</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Institución Origen</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Estado</th>
                <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Fecha Solicitud</th>
            </tr>
        `;
            tabla.appendChild(cabecera);

            // Cuerpo de la tabla
            const cuerpo = document.createElement('tbody');
            filteredData.forEach((s, index) => {
                const fila = document.createElement('tr');
                fila.style.backgroundColor = index % 2 === 0 ? '#f2f2f2' : 'white';

                // Formatear fecha
                const fecha = new Date(s.fecha_solicitud);
                const fechaFormateada = fecha.toLocaleDateString('es-CO', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                fila.innerHTML = `
                <td style="padding: 8px; border: 1px solid #ddd;">${s.numero_radicado}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">${s.primer_nombre} ${s.segundo_nombre || ''} ${s.primer_apellido} ${s.segundo_apellido || ''}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">${s.institucion_origen_nombre}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">${s.estado}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">${fechaFormateada}</td>
            `;
                cuerpo.appendChild(fila);
            });
            tabla.appendChild(cuerpo);
            contenedor.appendChild(tabla);

            // Añadir resumen
            const resumen = document.createElement('div');
            resumen.style.marginTop = '20px';
            resumen.innerHTML = `
            <p><strong>Resumen:</strong></p>
            <p>Total de solicitudes: ${filteredData.length}</p>
        `;
            contenedor.appendChild(resumen);

            // Configuración del PDF
            const opt = {
                margin: 10,
                filename: `Reporte_Homologaciones_${new Date().toISOString().slice(0, 10)}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            // Generar PDF
            html2pdf().from(contenedor).set(opt).save().then(() => {
                showAlert('Reporte PDF generado exitosamente', 'success');
            });
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar datos iniciales
            cargarDatos();

            // Event listener para filtros
            document.getElementById('filtro-fecha').addEventListener('change', aplicarFiltros);
            document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);

            // Event listener para botón actualizar
            document.getElementById('btn-actualizar').addEventListener('click', cargarDatos);

            // Event listeners para exportación
            document.getElementById('btn-exportar-excel').addEventListener('click', exportarExcel);
            document.getElementById('btn-exportar-pdf').addEventListener('click', exportarPDF);

            // Event listener para búsqueda en tabla
            document.getElementById('tabla-buscar').addEventListener('input', function() {
                currentPage = 1; // Resetear a primera página
                actualizarTabla(filteredData);
            });

            // Event listener para cambio en cantidad de registros por página
            document.getElementById('tabla-registros').addEventListener('change', function() {
                recordsPerPage = parseInt(this.value);
                currentPage = 1; // Resetear a primera página
                actualizarTabla(filteredData);
            });
        });
    </script>
@endsection

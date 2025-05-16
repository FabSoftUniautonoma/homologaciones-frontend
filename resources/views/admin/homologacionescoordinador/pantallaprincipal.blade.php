@extends('admin.layouts.appcoordinacion')

@section('content')
    <style>
        .card-hover:hover {
            transform: scale(1.03);
            transition: transform 0.3s ease;
            box-shadow: 0 0.5rem 1.2rem rgba(0, 0, 0, 0.1);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease;
        }

        .card-body {
            transition: background-color 0.3s ease;
        }

        .card-stats h3 {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .card-stats small {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .icon-emoji {
            font-size: 1.7rem;
        }

        .notifications-container {
            max-height: 350px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .notifications-container::-webkit-scrollbar {
            width: 6px;
        }

        .notifications-container::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .notification-item {
            border-left: 4px solid transparent;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            border-radius: 4px;
        }

        .notification-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
            transform: translateX(3px);
        }

        .notification-item.new {
            border-left-color: #007bff;
        }

        .notification-item.warning {
            border-left-color: #ffc107;
        }

        .notification-item.success {
            border-left-color: #28a745;
        }

        .notification-item.danger {
            border-left-color: #dc3545;
        }

        .skeleton-loader {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
            height: 24px;
            margin-bottom: 8px;
        }

        .chart-container {
            position: relative;
            height: 100%;
            min-height: 250px;
        }

        .page-title {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .page-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 60px;
            height: 4px;
            background-color: #007bff;
            border-radius: 2px;
        }

        .stats-icon {
            transition: transform 0.3s ease;
        }

        .card:hover .stats-icon {
            transform: scale(1.1);
        }

        .card-stats.border-left-primary {
            border-left: 4px solid #007bff;
        }

        .card-stats.border-left-warning {
            border-left: 4px solid #ffc107;
        }

        .card-stats.border-left-success {
            border-left: 4px solid #28a745;
        }

        .card-stats.border-left-danger {
            border-left: 4px solid #dc3545;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h2 class="page-title text-primary font-weight-bold">Panel de Homologación</h2>

                    <!-- Alertas de Notificaciones -->
                    <div id="system-alerts" class="mb-4"></div>

                    <!-- Estadísticas de solicitudes -->
                    <div class="row">
                        <!-- Total de solicitudes -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card card-stats card-round shadow-sm card-hover border-0 border-left-primary">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="icon-circle bg-primary-light text-primary mr-4 d-flex justify-content-center align-items-center stats-icon"
                                        style="width: 50px; height: 50px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-file-text" width="24"
                                            height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                            <line x1="16" x2="8" y1="13" y2="13" />
                                            <line x1="16" x2="8" y1="17" y2="17" />
                                            <line x1="10" x2="8" y1="9" y2="9" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 id="total-solicitudes" class="mb-0">
                                            <div class="skeleton-loader"></div>
                                        </h3>
                                        <small class="text-muted">Total de solicitudes</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pendientes -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card card-stats card-round shadow-sm card-hover border-0 border-left-warning">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="icon-circle bg-warning-light text-warning mr-4 d-flex justify-content-center align-items-center stats-icon"
                                        style="width: 50px; height: 50px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-clock" width="24"
                                            height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 id="pendientes" class="mb-0">
                                            <div class="skeleton-loader"></div>
                                        </h3>
                                        <small class="text-muted">Pendientes por revisar</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Aprobadas -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card card-stats card-round shadow-sm card-hover border-0 border-left-success">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="icon-circle bg-success-light text-success mr-4 d-flex justify-content-center align-items-center stats-icon"
                                        style="width: 50px; height: 50px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-check-circle" width="24"
                                            height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M9 12l2 2l4 -4" />
                                            <circle cx="12" cy="12" r="10" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 id="aprobadas" class="mb-0">
                                            <div class="skeleton-loader"></div>
                                        </h3>
                                        <small class="text-muted">Solicitudes aprobadas</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rechazadas -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card card-stats card-round shadow-sm card-hover border-0 border-left-danger">
                                <div class="card-body d-flex align-items-center p-3">
                                    <div class="icon-circle bg-danger-light text-danger mr-4 d-flex justify-content-center align-items-center stats-icon"
                                        style="width: 50px; height: 50px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-x-circle" width="24"
                                            height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="15" y1="9" x2="9" y2="15" />
                                            <line x1="9" y1="9" x2="15" y2="15" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 id="rechazadas" class="mb-0">
                                            <div class="skeleton-loader"></div>
                                        </h3>
                                        <small class="text-muted">Solicitudes rechazadas</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Últimas Solicitudes -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm h-100 d-flex flex-column">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Últimas Solicitudes</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </div>
                    <div class="card-body notifications-container flex-grow-1" id="ultimas-solicitudes">
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                    </div>
                </div>
            </div>

            <!-- Notificaciones del Sistema -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100 d-flex flex-column">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Notificaciones</h5>
                        <span class="badge badge-pill badge-primary" id="notification-count">0</span>
                    </div>
                    <div class="card-body notifications-container flex-grow-1" id="notificaciones">
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                        <div class="skeleton-loader"></div>
                    </div>
                    <div class="card-footer bg-white border-0 text-center">
                        <a href="#" class="btn btn-sm btn-outline-secondary">Marcar todas como leídas</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Función para mostrar alertas
            function showAlert(message, type = 'info') {
                const alertContainer = document.getElementById('system-alerts');
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
                try {
                    const response = await fetch('http://127.0.0.1:8000/api/solicitudes');

                    if (!response.ok) {
                        throw new Error('Error al cargar los datos');
                    }

                    const solicitudes = await response.json();

                    // Procesar datos para estadísticas
                    const totalSolicitudes = solicitudes.length;
                    const pendientes = solicitudes.filter(s => s.estado === 'Radicado' || s.estado ===
                        'En revisión').length;
                    const aprobadas = solicitudes.filter(s => s.estado === 'Aprobado').length;
                    const rechazadas = solicitudes.filter(s => s.estado === 'Rechazado').length;

                    // Actualizar contadores
                    document.getElementById('total-solicitudes').textContent = totalSolicitudes;
                    document.getElementById('pendientes').textContent = pendientes;
                    document.getElementById('aprobadas').textContent = aprobadas;
                    document.getElementById('rechazadas').textContent = rechazadas;

                    // Actualizar notificaciones
                    actualizarNotificaciones(solicitudes);

                    // Actualizar últimas solicitudes
                    actualizarUltimasSolicitudes(solicitudes);

                    // Crear gráficos
                    crearGraficoInstituciones(solicitudes);
                    crearGraficoEstados(solicitudes);

                    // Mostrar alerta de éxito
                    showAlert('Datos actualizados correctamente', 'success');

                    return solicitudes;
                } catch (error) {
                    console.error('Error:', error);
                    showAlert('Error al cargar los datos. Intente más tarde.', 'danger');
                    return [];
                }
            }

            // Función para actualizar notificaciones
            function actualizarNotificaciones(solicitudes) {
                const notificacionesContainer = document.getElementById('notificaciones');
                notificacionesContainer.innerHTML = '';

                // Ordenar solicitudes por fecha (las más recientes primero)
                const solicitudesOrdenadas = [...solicitudes].sort((a, b) => {
                    return new Date(b.fecha_solicitud) - new Date(a.fecha_solicitud);
                });

                // Crear notificaciones
                let notificaciones = [];

                // Solicitudes recientes (últimas 24 horas)
                const ultimasDia = solicitudesOrdenadas.filter(s => {
                    const fechaSolicitud = new Date(s.fecha_solicitud);
                    const ahora = new Date();
                    const diff = ahora - fechaSolicitud;
                    return diff < 24 * 60 * 60 * 1000; // 24 horas en milisegundos
                });

                if (ultimasDia.length > 0) {
                    notificaciones.push({
                        mensaje: `${ultimasDia.length} nueva${ultimasDia.length > 1 ? 's' : ''} solicitud${ultimasDia.length > 1 ? 'es' : ''} en las últimas 24 horas`,
                        tipo: 'new',
                        fecha: new Date()
                    });
                }

                // Solicitudes pendientes por más de 7 días
                const pendientesDemoradas = solicitudesOrdenadas.filter(s => {
                    if (s.estado === 'Radicado' || s.estado === 'En revisión') {
                        const fechaSolicitud = new Date(s.fecha_solicitud);
                        const ahora = new Date();
                        const diff = ahora - fechaSolicitud;
                        return diff > 7 * 24 * 60 * 60 * 1000; // 7 días en milisegundos
                    }
                    return false;
                });

                if (pendientesDemoradas.length > 0) {
                    notificaciones.push({
                        mensaje: `${pendientesDemoradas.length} solicitud${pendientesDemoradas.length > 1 ? 'es' : ''} pendiente${pendientesDemoradas.length > 1 ? 's' : ''} por más de 7 días`,
                        tipo: 'warning',
                        fecha: new Date()
                    });
                }

                // Solicitudes aprobadas recientemente
                const aprobadasRecientes = solicitudesOrdenadas.filter(s => {
                    return s.estado === 'Aprobado';
                }).slice(0, 3);

                aprobadasRecientes.forEach(solicitud => {
                    notificaciones.push({
                        mensaje: `Solicitud ${solicitud.numero_radicado} de ${solicitud.primer_nombre} ${solicitud.primer_apellido} aprobada`,
                        tipo: 'success',
                        fecha: new Date(solicitud.fecha_solicitud)
                    });
                });

                // Solicitudes rechazadas recientemente
                const rechazadasRecientes = solicitudesOrdenadas.filter(s => {
                    return s.estado === 'Rechazado';
                }).slice(0, 3);

                rechazadasRecientes.forEach(solicitud => {
                    notificaciones.push({
                        mensaje: `Solicitud ${solicitud.numero_radicado} de ${solicitud.primer_nombre} ${solicitud.primer_apellido} rechazada`,
                        tipo: 'danger',
                        fecha: new Date(solicitud.fecha_solicitud)
                    });
                });

                // Mostrar notificaciones
                if (notificaciones.length === 0) {
                    notificacionesContainer.innerHTML = '<p class="text-muted text-center">No hay notificaciones nuevas</p>';
                } else {
                    notificaciones.forEach(notificacion => {
                        const notificacionElement = document.createElement('div');
                        notificacionElement.className = `notification-item p-3 ${notificacion.tipo}`;

                        const fechaFormateada = notificacion.fecha.toLocaleString('es-CO', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        notificacionElement.innerHTML = `
                        <div class="d-flex justify-content-between">
                            <strong>${notificacion.mensaje}</strong>
                            <small class="text-muted">${fechaFormateada}</small>
                        </div>
                    `;

                        notificacionesContainer.appendChild(notificacionElement);
                    });
                }

                // Actualizar contador de notificaciones
                document.getElementById('notification-count').textContent = notificaciones.length;
            }

            // Función para actualizar últimas solicitudes
            function actualizarUltimasSolicitudes(solicitudes) {
                const ultimasSolicitudesContainer = document.getElementById('ultimas-solicitudes');
                ultimasSolicitudesContainer.innerHTML = '';

                // Ordenar solicitudes por fecha (las más recientes primero)
                const solicitudesOrdenadas = [...solicitudes].sort((a, b) => {
                    return new Date(b.fecha_solicitud) - new Date(a.fecha_solicitud);
                }).slice(0, 8); // Mostrar las 8 más recientes

                if (solicitudesOrdenadas.length === 0) {
                    ultimasSolicitudesContainer.innerHTML =
                    '<p class="text-muted text-center">No hay solicitudes recientes</p>';
                } else {
                    solicitudesOrdenadas.forEach(solicitud => {
                        const solicitudElement = document.createElement('div');

                        // Determinar el color de fondo según el estado
                        let colorEstado = '';
                        switch (solicitud.estado) {
                            case 'Aprobado':
                                colorEstado = 'success';
                                break;
                            case 'Rechazado':
                                colorEstado = 'danger';
                                break;
                            case 'En revisión':
                                colorEstado = 'warning';
                                break;
                            default:
                                colorEstado = 'primary';
                        }

                        solicitudElement.className = `notification-item p-3`;

                        const fechaFormateada = new Date(solicitud.fecha_solicitud).toLocaleString(
                        'es-CO', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        solicitudElement.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${solicitud.primer_nombre} ${solicitud.primer_apellido}</strong>
                                <div>
                                    <span class="badge badge-${colorEstado}">${solicitud.estado}</span>
                                    <small class="text-muted">${solicitud.numero_radicado}</small>
                                </div>
                                <small>${solicitud.institucion_origen_nombre}</small>
                            </div>
                            <small class="text-muted">${fechaFormateada}</small>
                        </div>
                    `;

                        ultimasSolicitudesContainer.appendChild(solicitudElement);
                    });
                }
            }

            // Función para crear gráfico de instituciones
            function crearGraficoInstituciones(solicitudes) {
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

                const labels = Object.keys(institucionesCounts);
                const data = Object.values(institucionesCounts);

                // Colores predefinidos
                const backgroundColors = [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ];

                // Crear gráfico
                const ctx = document.getElementById('institucionesChart').getContext('2d');
                if (window.institucionesChart) {
                    window.institucionesChart.destroy();
                }

                window.institucionesChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors.slice(0, labels.length),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 15,
                                    padding: 15
                                }
                            },
                            title: {
                                display: true,
                                text: 'Distribución por Institución de Origen',
                                padding: {
                                    top: 10,
                                    bottom: 20
                                }
                            }
                        }
                    }
                });
            }

            // Función para crear gráfico de estados
            function crearGraficoEstados(solicitudes) {
                // Contar solicitudes por estado
                const estadosCount = {
                    'Radicado': 0,
                    'En revisión': 0,
                    'Aprobado': 0,
                    'Rechazado': 0
                };

                solicitudes.forEach(solicitud => {
                    estadosCount[solicitud.estado]++;
                });

                const labels = Object.keys(estadosCount);
                const data = Object.values(estadosCount);

                // Colores por estado
                const backgroundColors = [
                    'rgba(0, 123, 255, 0.7)', // Radicado (primary)
                    'rgba(255, 193, 7, 0.7)', // En revisión (warning)
                    'rgba(40, 167, 69, 0.7)', // Aprobado (success)
                    'rgba(220, 53, 69, 0.7)' // Rechazado (danger)
                ];

                // Crear gráfico
                const ctx = document.getElementById('estadosChart').getContext('2d');
                if (window.estadosChart) {
                    window.estadosChart.destroy();
                }

                window.estadosChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cantidad de Solicitudes',
                            data: data,
                            backgroundColor: backgroundColors,
                            borderWidth: 1,
                            borderRadius: 6,
                            maxBarThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Solicitudes por Estado',
                                padding: {
                                    top: 10,
                                    bottom: 20
                                }
                            }
                        }
                    }
                });
            }

            // Cargar datos iniciales
            cargarDatos();

            // Actualizar datos cada 5 minutos
            setInterval(cargarDatos, 5 * 60 * 1000);
        });
    </script>
@endsection

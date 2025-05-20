@extends('admin.layouts.appadmin')

@section('content')
<div class="container-fluid py-4">
    <!-- Título principal con animación sutil -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg bg-gradient-primary border-0 animate__animated animate__fadeIn">
                <div class="card-body text-center p-4">
                    <h1 class="text-white font-weight-bold">Corporación Universitaria Autónoma del Cauca</h1>
                    <p class="text-white opacity-8 mb-0">Panel de Administración</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de resumen con contador animado -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card hover-card animate__animated animate__fadeInUp">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Usuarios</p>
                                <h5 class="font-weight-bolder mb-0 counter-value" id="total-users" data-target="0">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow pulse">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card hover-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Instituciones</p>
                                <h5 class="font-weight-bolder mb-0 counter-value" id="total-institutions" data-target="0">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow pulse">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card hover-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Usuarios Activos</p>
                                <h5 class="font-weight-bolder mb-0 counter-value" id="active-users" data-target="0">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow pulse">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card hover-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Instituciones Activas</p>
                                <h5 class="font-weight-bolder mb-0 counter-value" id="active-institutions" data-target="0">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow pulse">
                                <i class="fas fa-certificate"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de gestión con diseño mejorado -->
    <div class="row">
        <!-- Tarjeta de Usuarios -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm hover-card h-100 animate__animated animate__fadeInLeft">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-primary text-white rounded-circle shadow-sm me-3 pulse">
                            <i class="fas fa-users fa-2x p-3"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">Gestión de Usuarios</h3>
                            <p class="text-muted mb-0">Administración completa de usuarios del sistema</p>
                        </div>
                    </div>
                    <div class="mt-auto pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Último acceso: <span id="last-user-access">Cargando...</span></h6>
                            </div>
                            <a href="{{ url('administrador/usuarios') }}" class="btn btn-primary btn-lg rounded-pill">
                                Gestionar usuarios <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Instituciones -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm hover-card h-100 animate__animated animate__fadeInRight">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-gradient-success text-white rounded-circle shadow-sm me-3 pulse">
                            <i class="fas fa-university fa-2x p-3"></i>
                        </div>
                        <div>
                            <h3 class="mb-1">Gestión de Instituciones</h3>
                            <p class="text-muted mb-0">Administración completa de instituciones educativas</p>
                        </div>
                    </div>
                    <div class="mt-auto pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Instituciones registradas hoy: <span id="institutions-today">Cargando...</span></h6>
                            </div>
                            <a href="{{ url('sinstituciones') }}" class="btn btn-success btn-lg rounded-pill">
                                Gestionar instituciones <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
<!-- Estilos adicionales mejorados -->
<style>
    .hover-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15), 0 10px 10px rgba(0, 0, 0, 0.12);
    }

    .icon-shape {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        width: 3rem;
        height: 3rem;
        transition: all 0.3s ease;
    }

    .bg-gradient-primary {
        background: linear-gradient(150deg, #00205B 0%, #2c5ecc 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(150deg, #006400 0%, #00b300 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(150deg, #0077b6 0%, #00b4d8 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(150deg, #e85d04 0%, #faa307 100%);
    }

    /* Animación de pulso */
    .pulse {
        animation: pulse-animation 2s infinite;
    }

    @keyframes pulse-animation {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }

    /* Animación para contador */
    .counter-value {
        transition: all 0.5s ease;
    }
</style>
@endsection

@push('scripts')
<!-- Scripts mejorados -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // URL base de la API
        const API_BASE_URL = 'https://homologacionesback.educarenemociones.com/api';

        // Configuración para fetch
        const fetchConfig = {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            mode: 'cors',
            credentials: 'include'
        };

        // Función para animar contadores
        function animateCounter(element, targetValue) {
            if (!element) return;

            const duration = 1500;
            const startValue = 0;
            const startTime = Date.now();

            // Quitar spinner si existe
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }

            const updateCounter = () => {
                const currentTime = Date.now();
                const elapsedTime = currentTime - startTime;

                if (elapsedTime < duration) {
                    const progress = elapsedTime / duration;
                    const currentValue = Math.floor(startValue + progress * (targetValue - startValue));
                    element.textContent = currentValue.toLocaleString();
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = targetValue.toLocaleString();
                }
            };

            updateCounter();
        }

        // Función para cargar datos del dashboard
        function loadDashboardData() {
            // Cargar contadores de usuarios
            fetch(`${API_BASE_URL}/usuarios`, fetchConfig)
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = text ? JSON.parse(text) : { total: 0, active: 0 };

                        // Animar contadores
                        animateCounter(document.getElementById('total-users'), data.total || 0);
                        animateCounter(document.getElementById('active-users'), data.active || 0);

                        // Actualizar último acceso
                        document.getElementById('last-user-access').textContent =
                            new Date().toLocaleString('es-ES', {
                                day: '2-digit',
                                month: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                        // Cargar gráfico de usuarios
                        loadUsersChart(data);
                    } catch (e) {
                        console.error('Error procesando datos de usuarios:', e);
                        document.getElementById('total-users').textContent = 'Error';
                        document.getElementById('active-users').textContent = 'Error';
                    }
                })
                .catch(error => {
                    console.error('Error cargando estadísticas de usuarios:', error);
                    document.getElementById('total-users').textContent = 'Error';
                    document.getElementById('active-users').textContent = 'Error';
                });

            // Cargar estadísticas de instituciones
            fetch(`${API_BASE_URL}/instituciones`, fetchConfig)
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = text ? JSON.parse(text) : { total: 0, active: 0, inactive: 0, pending: 0 };

                        // Animar contadores
                        animateCounter(document.getElementById('total-institutions'), data.total || 0);
                        animateCounter(document.getElementById('active-institutions'), data.active || 0);

                        // Actualizar instituciones registradas hoy (valor aleatorio para demo)
                        document.getElementById('institutions-today').textContent = Math.floor(Math.random() * 5);

                        // Crear gráfico circular con los datos
                        createInstitutionsChart(data);
                    } catch (e) {
                        console.error('Error procesando datos de instituciones:', e);
                        document.getElementById('total-institutions').textContent = 'Error';
                        document.getElementById('active-institutions').textContent = 'Error';
                    }
                })
                .catch(error => {
                    console.error('Error cargando estadísticas de instituciones:', error);
                    document.getElementById('total-institutions').textContent = 'Error';
                    document.getElementById('active-institutions').textContent = 'Error';
                });
        }

        // Función para crear gráfico de actividad de usuarios
        function loadUsersChart(data) {
            // Generar datos de ejemplo para el gráfico de líneas
            const dates = [];
            const activeUsers = [];
            const newUsers = [];

            // Generar datos para últimos 7 días
            const today = new Date();
            for (let i = 6; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(today.getDate() - i);
                dates.push(date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' }));

                // Valores simulados basados en datos reales
                const baseActive = data.active ? Math.floor(data.active * 0.8) : 100;
                const variance = Math.floor(Math.random() * 20) - 10;
                activeUsers.push(Math.max(0, baseActive + variance));

                // Nuevos usuarios (valores aleatorios)
                newUsers.push(Math.floor(Math.random() * 10));
            }

            const options = {
                series: [{
                    name: 'Usuarios Activos',
                    data: activeUsers
                }, {
                    name: 'Nuevos Usuarios',
                    data: newUsers
                }],
                chart: {
                    height: 300,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                colors: ['#2c5ecc', '#00b300'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 5
                },
                xaxis: {
                    categories: dates
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return val + " usuarios"
                        }
                    }
                }
            };

            // Limpiar gráfico anterior si existe
            document.getElementById('users-chart').innerHTML = '';

            // Crear nuevo gráfico
            const chart = new ApexCharts(document.getElementById('users-chart'), options);
            chart.render();

            // Guardar referencia para actualizar
            window.usersChart = chart;
        }

        // Función para crear el gráfico circular de instituciones
        function createInstitutionsChart(data) {
            const options = {
                series: [data.active || 0, data.inactive || 0, data.pending || 0],
                labels: ['Activas', 'Inactivas', 'Pendientes'],
                chart: {
                    type: 'donut',
                    height: 300,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }
                },
                colors: ['#00b300', '#ff4d4d', '#ffcc00'],
                legend: {
                    position: 'bottom'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function(value, { series, seriesIndex, w }) {
                            const total = series.reduce((a, b) => a + b, 0);
                            const percentage = ((value * 100) / total).toFixed(1);
                            return `${value} (${percentage}%)`;
                        }
                    }
                }
            };

            // Limpiar gráfico anterior si existe
            document.getElementById('chart-instituciones').innerHTML = '';

            // Crear nuevo gráfico
            const chart = new ApexCharts(document.getElementById('chart-instituciones'), options);
            chart.render();

            // Guardar referencia para actualizar
            window.institutionsChart = chart;
        }

        // Cargar datos iniciales
        loadDashboardData();

        // Configurar botones de actualización
        document.getElementById('refresh-users-chart')?.addEventListener('click', function(e) {
            e.preventDefault();
            loadDashboardData();
        });

        document.getElementById('refresh-institutions-chart')?.addEventListener('click', function(e) {
            e.preventDefault();
            loadDashboardData();
        });

        // Actualizar datos periódicamente
        setInterval(loadDashboardData, 300000); // Cada 5 minutos
    });
</script>
@endpush

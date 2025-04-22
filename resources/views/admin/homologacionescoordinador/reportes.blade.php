@extends('admin.layouts.appcoordinacion')

@section('content')
<div class="container mt-5">
    <h2 class="text-primary mb-4">📊 Reportes de Homologaciones</h2>

    <div class="row">
        <!-- Materias más homologadas -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">📚 Materias más Homologadas</div>
                <div class="card-body">
                    <canvas id="materiasChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Estudiante con más homologaciones -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">👨‍🎓 Top Estudiantes con más Homologaciones</div>
                <div class="card-body">
                    <canvas id="estudiantesChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Instituciones de origen -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">🏫 Instituciones de Origen con más Solicitudes</div>
                <div class="card-body">
                    <canvas id="institucionesChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
window.onload = function () {
    const materiasData = {
        labels: ['Matemáticas I', 'Programación I', 'Bases de Datos', 'Álgebra', 'Sistemas Operativos'],
        datasets: [{
            label: 'Número de Homologaciones',
            data: [45, 38, 33, 29, 25],
            backgroundColor: ['#007bff', '#0056b3', '#3399ff', '#66b3ff', '#99ccff']
        }]
    };

    const estudiantesData = {
        labels: ['Juan Pérez', 'Ana Gómez', 'Carlos Ruiz', 'María López', 'David Martínez'],
        datasets: [{
            label: 'Total Homologaciones',
            data: [12, 10, 9, 8, 7],
            backgroundColor: ['#28a745', '#218838', '#34d058', '#5ec98d', '#a8e6cf']
        }]
    };

    const institucionesData = {
        labels: ['Universidad del Valle', 'SENA', 'Universidad Nacional', 'Icesi', 'Politécnico Jaime Isaza'],
        datasets: [{
            label: 'Solicitudes de Homologación',
            data: [30, 25, 20, 18, 15],
            backgroundColor: ['#17a2b8', '#138496', '#5bc0de', '#7fd5f5', '#c6eefd']
        }]
    };

    new Chart(document.getElementById('materiasChart'), {
        type: 'bar',
        data: materiasData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Materias Más Homologadas' }
            }
        }
    });

    new Chart(document.getElementById('estudiantesChart'), {
        type: 'bar',
        data: estudiantesData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Estudiantes con Más Homologaciones' }
            }
        }
    });

    new Chart(document.getElementById('institucionesChart'), {
        type: 'pie',
        data: institucionesData,
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Instituciones de Origen con Más Solicitudes' }
            }
        }
    });
};
</script>
@endsection

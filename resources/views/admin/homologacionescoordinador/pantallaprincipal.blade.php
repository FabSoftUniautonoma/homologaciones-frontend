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
</style>

<div class="container mt-5 bg-white rounded p-4 shadow-sm">
    <h2 class="mb-5 text-center font-weight-bold text-primary">📊 Panel de Homologación - Coordinador</h2>

    <div class="row">
        <!-- Total de solicitudes -->
        <div class="col-md-3 mb-4">
            <div class="card card-stats card-round shadow-sm card-hover border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-primary text-white mr-4 icon-emoji">✅</div>
                    <div>
                        <h3 id="total-solicitudes">0</h3>
                        <small>Total de solicitudes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pendientes -->
        <div class="col-md-3 mb-4">
            <div class="card card-stats card-round shadow-sm card-hover border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-warning text-white mr-4 icon-emoji">⏳</div>
                    <div>
                        <h3 id="pendientes">0</h3>
                        <small>Pendientes por revisar</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aprobadas -->
        <div class="col-md-3 mb-4">
            <div class="card card-stats card-round shadow-sm card-hover border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-success text-white mr-4 icon-emoji">✔️</div>
                    <div>
                        <h3 id="aprobadas">0</h3>
                        <small>Solicitudes aprobadas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rechazadas -->
        <div class="col-md-3 mb-4">
            <div class="card card-stats card-round shadow-sm card-hover border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-danger text-white mr-4 icon-emoji">❌</div>
                    <div>
                        <h3 id="rechazadas">0</h3>
                        <small>Solicitudes rechazadas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materias registradas -->
        <div class="col-md-12 mt-3">
            <div class="card card-stats card-round shadow-sm card-hover border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-info text-white mr-4 icon-emoji">📚</div>
                    <div>
                        <h3 id="materias-pensum">0</h3>
                        <small>Materias registradas en el pensum</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Simulado -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Datos simulados (puedes reemplazarlos por AJAX más adelante)
        const datos = {
            totalSolicitudes: 128,
            pendientes: 42,
            aprobadas: 70,
            rechazadas: 16,
            materiasPensum: 132
        };

        // Asignación de datos
        document.getElementById('total-solicitudes').textContent = datos.totalSolicitudes;
        document.getElementById('pendientes').textContent = datos.pendientes;
        document.getElementById('aprobadas').textContent = datos.aprobadas;
        document.getElementById('rechazadas').textContent = datos.rechazadas;
        document.getElementById('materias-pensum').textContent = datos.materiasPensum;
    });
</script>
@endsection

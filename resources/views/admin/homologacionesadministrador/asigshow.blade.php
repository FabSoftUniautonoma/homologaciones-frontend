<!-- resources/views/asignaturas/show.blade.php -->
@extends('layouts.app')

@section('title', $asignatura['nombre'] . ' - Sistema de Gestión Académica')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instituciones.index') }}">Instituciones</a></li>
                <li class="breadcrumb-item"><a href="{{ route('programas.show', $asignatura['programa_id']) }}">{{ $asignatura['programa'] }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $asignatura['nombre'] }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="card-title mb-0">{{ $asignatura['nombre'] }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">Información Básica</h5>
                        <div class="mb-3">
                            <p><strong>Programa:</strong> {{ $asignatura['programa'] }}</p>
                            <p><strong>Código:</strong> {{ $asignatura['codigo_asignatura'] }}</p>
                            <p><strong>Tipo:</strong> {{ $asignatura['tipo'] }}</p>
                            <p><strong>Créditos:</strong> {{ $asignatura['creditos'] }}</p>
                            <p><strong>Semestre:</strong> {{ $asignatura['semestre'] }}</p>
                            <p><strong>Modalidad:</strong> {{ $asignatura['modalidad'] }}</p>
                            <p><strong>Metodología:</strong> {{ $asignatura['metodologia'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="border-bottom pb-2">Distribución de Horas</h5>
                        <div class="mb-3">
                            <p><strong>Horas SENA:</strong> {{ $asignatura['horas_sena'] }}</p>
                            <p><strong>Tiempo Presencial:</strong> {{ $asignatura['tiempo_presencial'] }} horas</p>
                            <p><strong>Tiempo Independiente:</strong> {{ $asignatura['tiempo_independiente'] }} horas</p>
                            <p><strong>Horas Totales Semanales:</strong> {{ $asignatura['horas_totales_semanales'] }} horas</p>
                        </div>

                        <h5 class="border-bottom pb-2">Información Adicional</h5>
                        <div>
                            <p><strong>Creado:</strong> {{ date('d/m/Y H:i', strtotime($asignatura['created_at'])) }}</p>
                            <p><strong>Actualizado:</strong> {{ date('d/m/Y H:i', strtotime($asignatura['updated_at'])) }}</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('programas.show', $asignatura['programa_id']) }}" class="btn btn-primary">
                        Volver al Programa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

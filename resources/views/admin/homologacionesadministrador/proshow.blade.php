<!-- resources/views/instituciones/show.blade.php -->
@extends('layouts.app')

@section('title', $institucion['nombre'] . ' - Sistema de Gestión Académica')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('instituciones.index') }}">Instituciones</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $institucion['nombre'] }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="card-title mb-0">Detalles de la Institución</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $institucion['nombre'] }}</h5>
                <p class="card-text"><strong>Código IES:</strong> {{ $institucion['codigo_ies'] }}</p>
                <p class="card-text"><strong>Municipio:</strong> {{ $institucion['municipio'] }}</p>
                <p class="card-text"><strong>Departamento:</strong> {{ $institucion['departamento'] }}</p>
                <p class="card-text"><strong>Tipo:</strong> {{ $institucion['tipo'] }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Programas Académicos</h4>
            </div>
            <div class="card-body">
                @if(isset($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                @if(empty($programas))
                    <div class="alert alert-warning">Esta institución no tiene programas registrados.</div>
                @else
                    <div class="list-group">
                        @foreach($programas as $programa)
                            <a href="{{ route('programas.show', $programa['id_programa']) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $programa['programa'] }}</h5>
                                    <small>Código SNIES: {{ $programa['codigo_snies'] }}</small>
                                </div>
                                <p class="mb-1">Tipo de formación: {{ $programa['tipo_formacion'] }}</p>
                                <small>Metodología: {{ $programa['metodologia'] }}</small>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<!-- resources/views/instituciones/index.blade.php -->
@extends('layouts.app')

@section('title', 'Instituciones - Sistema de Gestión Académica')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Instituciones Educativas</h3>
            </div>
            <div class="card-body">
                @if(isset($error))
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif

                @if(empty($instituciones))
                    <div class="alert alert-warning">No hay instituciones registradas.</div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($instituciones as $institucion)
                            <div class="col">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">{{ $institucion['nombre'] }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><strong>Código IES:</strong> {{ $institucion['codigo_ies'] }}</p>
                                        <p class="card-text"><strong>Ubicación:</strong> {{ $institucion['municipio'] }}, {{ $institucion['departamento'] }}</p>
                                        <p class="card-text"><strong>Tipo:</strong> {{ $institucion['tipo'] }}</p>
                                    </div>
                                    <div class="card-footer bg-transparent text-center">
                                        <a href="{{ route('instituciones.show', $institucion['id_institucion']) }}" class="btn btn-primary">
                                            Ver Programas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

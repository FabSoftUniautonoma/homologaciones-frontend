@extends('layouts.atlantis')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-file-pdf mr-2"></i> Verificación de Documentos</h3>

    <div class="card">
        <div class="card-body">
            <ul class="list-group">
                @foreach ($documentos as $titulo => $ruta)
                    @if ($ruta)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $titulo }}
                            <a href="{{ asset('storage/' . $ruta) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                            </a>
                        </li>
                    @else
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $titulo }}
                            <span class="text-muted">No disponible</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.homologaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
</div>
@endsection

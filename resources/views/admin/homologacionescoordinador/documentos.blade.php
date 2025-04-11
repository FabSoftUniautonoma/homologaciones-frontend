@extends('admin.layouts.appcoordinacion')

@section('content')
<div class="container mt-5 position-relative">
    {{-- Botón de Cerrar (ubicado arriba a la derecha del contenedor) --}}
    <a href="{{ route('homologacion.index', ['id' => $id]) }}"
        id="btn-cerrar"
        class="btn btn-danger shadow position-absolute"
        style="top: 0; right: 0; border-radius: 50%; width: 45px; height: 45px; display: flex; justify-content: center; align-items: center;">
        <i class="fas fa-times"></i>
    </a>

    <h2 class="mb-4 fw-bold text-primary" style="font-size: 1.8rem;">
        Documentos de {{ $nombreEstudiante }}
        <span class="text-muted" style="font-size: 1.1rem;">({{ $id }})</span>
    </h2>



    @foreach ($documentos as $nombre => $ruta)
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <span style="font-size: 1.2rem; font-weight: 600; color: #003366;">
                    {{ $nombre }}
                </span>

                @if ($ruta)
                    <a href="{{ asset($ruta) }}" target="_blank" class="btn btn-sm btn-primary btn-animado">
                        Abrir en nueva pestaña
                    </a>
                @else
                    <span class="badge bg-danger text-light px-3 py-2" style="font-size: 0.85rem; border-radius: 0.5rem;">
                        No disponible
                    </span>
                @endif
            </div>

            @if ($ruta)
                <div class="card-body p-0">
                    <iframe src="{{ asset($ruta) }}" width="100%" height="500px" style="border: none;"></iframe>
                </div>
            @endif
        </div>
    @endforeach
</div>

{{-- Script para tecla ESC --}}
<script>
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            window.location.href = "{{ route('homologacion.index', ['id' => $id]) }}";
        }
    });
</script>
@endsection

@extends('admin.layouts.appvice')

@section('content')
    <div class="container mt-5 position-relative">
        @php
            // Obtener datos del estudiante del primer documento
            $estudiante = null;
            if (isset($documentos['datos']) && count($documentos['datos']) > 0) {
                $estudiante = $documentos['datos'][0];
            }
        @endphp

        {{-- Información del estudiante --}}
        @if ($estudiante)
            <div class="card mb-4 shadow-lg border-0 position-relative" style="border-radius: 15px; overflow: hidden;">
                {{-- Botón de Cerrar (dentro del contenedor) --}}
                <a href="{{ route('homologacion.documentos', ['id' => $id]) }}" id="btn-cerrar"
                    class="btn btn-danger shadow position-absolute"
                    style="top: 10px; right: 10px; border-radius: 50%; width: 45px; height: 45px; display: flex; justify-content: center; align-items: center; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i>
                </a>

                <div class="card-header bg-primary text-white p-3" style="border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0 fw-bold text-white" style="font-size: 1.8rem;">
                        Documentos de {{ $nombreEstudiante }}
                        <span style="font-size: 1.1rem; opacity: 0.8;">({{ $id }})</span>
                    </h2>
                </div>

                <div class="card-body bg-light p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user me-2 text-primary"></i>
                                <span>{{ $estudiante['primer_nombre'] }} {{ $estudiante['segundo_nombre'] }}
                                    {{ $estudiante['primer_apellido'] }} {{ $estudiante['segundo_apellido'] }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-id-card me-2 text-primary"></i>
                                <span>{{ $estudiante['tipo_identificacion'] }}:
                                    {{ $estudiante['numero_identificacion'] }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <span>{{ $estudiante['email'] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                <span>{{ $estudiante['telefono'] }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                <span>{{ $estudiante['direccion'] }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                <span>Radicado: <span class="badge bg-info">{{ $estudiante['numero_radicado'] }}</span>
                                    Estado: <span
                                        class="badge bg-{{ $estudiante['estado'] == 'Radicado' ? 'warning' : 'success' }}">{{ $estudiante['estado'] }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @php
            // Determinar qué estructura de datos estamos recibiendo
            $documentosList = isset($documentos['datos'])
                ? $documentos['datos']
                : (is_array($documentos)
                    ? $documentos
                    : []);
        @endphp

        {{-- Navegación por pestañas --}}
        <ul class="nav nav-tabs mb-3" id="documentsTabs" role="tablist">
            @foreach ($documentosList as $index => $documento)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="doc-{{ $index }}-tab"
                        data-bs-toggle="tab" data-bs-target="#doc-{{ $index }}" type="button" role="tab"
                        aria-controls="doc-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                        style="font-weight: 500; transition: all 0.3s ease;">
                        <i class="fas fa-file-alt me-1"></i> {{ $documento['tipo'] }}
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- Contenido de pestañas --}}
        <div class="tab-content" id="documentsTabsContent">
            @if (count($documentosList) > 0)
                @foreach ($documentosList as $index => $documento)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="doc-{{ $index }}"
                        role="tabpanel" aria-labelledby="doc-{{ $index }}-tab">
                        <div class="card shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light"
                                style="border-bottom: 2px solid #e9ecef;">
                                <div>
                                    <h4 style="font-size: 1.2rem; font-weight: 600; color: #003366; margin-bottom: 0;">
                                        {{ $documento['tipo'] }}
                                    </h4>
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i> Subido:
                                        {{ \Carbon\Carbon::parse($documento['fecha_subida'])->format('d/m/Y H:i') }}
                                    </small>
                                </div>

                                @if (isset($documento['ruta']) && $documento['ruta'])
                                    <div>
                                        <a href="{{ $documento['ruta'] }}" download
                                            class="btn btn-sm btn-outline-primary me-2" style="transition: all 0.3s ease;">
                                            <i class="fas fa-download me-1"></i> Descargar
                                        </a>
                                        <a href="{{ $documento['ruta'] }}" target="_blank" class="btn btn-sm btn-primary"
                                            style="transition: all 0.3s ease;">
                                            <i class="fas fa-external-link-alt me-1"></i> Abrir en nueva pestaña
                                        </a>
                                    </div>
                                @else
                                    <span class="badge bg-danger text-light px-3 py-2"
                                        style="font-size: 0.85rem; border-radius: 0.5rem;">
                                        <i class="fas fa-exclamation-triangle me-1"></i> No disponible
                                    </span>
                                @endif
                            </div>

                            @if (isset($documento['ruta']) && $documento['ruta'])
                                <div class="card-body p-0 position-relative">
                                    {{-- Indicador de carga --}}
                                    <div id="loading-{{ $index }}"
                                        class="position-absolute w-100 h-100 d-flex flex-column justify-content-center align-items-center"
                                        style="background: rgba(255,255,255,0.9); z-index: 10;">
                                        <div class="spinner-border text-primary mb-2" role="status">
                                            <span class="visually-hidden">Cargando...</span>
                                        </div>
                                        <p class="mb-0">Cargando documento...</p>
                                    </div>
                                    <iframe src="{{ $documento['ruta'] }}" width="100%" height="600px"
                                        style="border: none;"
                                        onload="document.getElementById('loading-{{ $index }}').style.display='none'">
                                    </iframe>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No se encontraron documentos para esta solicitud.
                </div>
            @endif
        </div>
    </div>

    {{-- CSS adicional --}}
    <style>
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link {
            border-radius: 10px 10px 0 0;
            padding: 10px 15px;
            color: #495057;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #f8f9fa;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
        }

        #btn-cerrar:hover {
            transform: rotate(90deg);
        }

        /* Animación para cargar los documentos */
        .tab-pane {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    {{-- Script para tecla ESC y otras funcionalidades --}}
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                window.location.href = "{{ route('homologacion.documentos', ['id' => $id]) }}";
            }
        });

        // Inicializar las pestañas con Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar manualmente la funcionalidad de tabs ya que puede que Bootstrap no esté inicializado
            const tabLinks = document.querySelectorAll('#documentsTabs .nav-link');
            const tabContents = document.querySelectorAll('.tab-pane');

            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Eliminar active de todos los tabs
                    tabLinks.forEach(function(link) {
                        link.classList.remove('active');
                        link.setAttribute('aria-selected', 'false');
                    });

                    // Ocultar todos los contenidos
                    tabContents.forEach(function(content) {
                        content.classList.remove('show', 'active');
                    });

                    // Activar el tab seleccionado
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    // Mostrar el contenido correspondiente
                    const target = this.getAttribute('data-bs-target');
                    document.querySelector(target).classList.add('show', 'active');
                });
            });
        });

        // Navegación por teclado entre pestañas
        document.addEventListener('keydown', function(event) {
            if (event.altKey) {
                const numTabs = {{ count($documentosList) }};

                if (event.key === "ArrowRight") {
                    let activeTabIndex = getActiveTabIndex();
                    if (activeTabIndex < numTabs - 1) {
                        document.querySelectorAll('#documentsTabs .nav-link')[activeTabIndex + 1].click();
                    }
                } else if (event.key === "ArrowLeft") {
                    let activeTabIndex = getActiveTabIndex();
                    if (activeTabIndex > 0) {
                        document.querySelectorAll('#documentsTabs .nav-link')[activeTabIndex - 1].click();
                    }
                }
            }
        });

        function getActiveTabIndex() {
            const tabs = document.querySelectorAll('#documentsTabs .nav-link');
            for (let i = 0; i < tabs.length; i++) {
                if (tabs[i].classList.contains('active')) {
                    return i;
                }
            }
            return 0;
        }
    </script>
@endsection

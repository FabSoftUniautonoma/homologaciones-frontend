@extends('admin.layouts.appcoordinacion')

@section('title', 'Homologaciones')

@section('content')
    <div class="container-fluid py-4 position-relative">
        <!-- Número de Homologación y Estado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div
                        class="card-body d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i> Homologación
                            #{{ $solicitud['numero_radicado'] ?? 'No disponible' }}
                        </h4>

                        <a href="{{ route('admin.homologacionescoordinador.index') }}"
                            class="btn btn-danger shadow position-absolute"
                            style="top: 0; right: 0; margin: 10px; border-radius: 50%; width: 45px; height: 45px; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal y Académica -->
            <div class="col-lg-8">
                <!-- Información Personal -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i> Información Personal</h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label><strong>Tipo de identificación:</strong></label>
                            <p>{{ $usuario['tipo_identificacion'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Número de identificación:</strong></label>
                            <p>{{ $usuario['numero_identificacion'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Nombres completos:</strong></label>
                            <p>
                                {{ $usuario['primer_nombre'] ?? '' }}
                                {{ $usuario['segundo_nombre'] ?? '' }}
                                {{ $usuario['primer_apellido'] ?? '' }}
                                {{ $usuario['segundo_apellido'] ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Correo electrónico:</strong></label>
                            <p>{{ $usuario['email'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Teléfono:</strong></label>
                            <p>{{ $solicitud['telefono'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Nacionalidad:</strong></label>
                            <p>{{ $usuario['pais'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Departamento:</strong></label>
                            <p>{{ $usuario['departamento'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Municipio:</strong></label>
                            <p>{{ $usuario['municipio'] ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fas fa-graduation-cap me-2"></i> Información Académica</h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label><strong>Instituto de origen:</strong></label>
                            <p>{{ $usuario['institucion_origen'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Programa de destino:</strong></label>
                            <p>{{ $solicitud['programa_destino_nombre'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Fecha de solicitud:</strong></label>
                            @if (!empty($solicitud['fecha_solicitud']))
                                <p>{{ \Carbon\Carbon::parse($solicitud['fecha_solicitud'])->format('d/m/Y') }}</p>
                            @else
                                <p>No disponible</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Estado de la solicitud:</strong></label>
                            <p>{{ $solicitud['estado'] ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión del Proceso -->
            <div class="col-lg-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i> Gestión del Proceso</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.homologaciones.actualizar', $solicitud['id_solicitud']) }}"
                            method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label class="fw-bold">Estado actual:</label>
                                <div class="alert alert-{{ $solicitud['estado_class'] ?? 'warning' }} text-center">
                                    {{ ucfirst($solicitud['estado']) ?? 'Pendiente' }}
                                </div>
                            </div>

                            <a href="{{ route('admin.homologacionescoordinador.documentos', $solicitud['id_solicitud']) }}"
                                class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-check-circle me-2"></i> Verificar Documentos
                            </a>


                            <a href="{{ route('admin.homologacionescoordinador.procesohomologacion', $solicitud['numero_radicado']) }}"
                                class="btn btn-success w-100 mb-3">
                                <i class="fas fa-play-circle me-2"></i> Iniciar Proceso
                            </a>

                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('js/informacionhomologacion.js') }}"></script>
@endsection

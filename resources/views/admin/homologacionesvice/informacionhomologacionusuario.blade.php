@extends('admin.layouts.appvice')

@section('title', 'Homologaciones')

@section('content')
    <div class="container-fluid py-4">
        <!-- Encabezado con Número de Homologación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body bg-gradient-primary text-white rounded-3 position-relative">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-file-alt me-2"></i>
                            Homologación #{{ $solicitud['numero_radicado'] ?? 'No disponible' }}
                        </h4>
                        <a href="{{ route('admin.homologacionescoordinador.index') }}"
                            class="btn btn-white shadow position-absolute"
                            style="top: 0; right: 0; margin: 10px; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Panel Izquierdo: Información Personal y Académica -->
            <div class="col-lg-8">
                <!-- Información Personal -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="fas fa-user-circle me-2"></i> Información Personal
                        </h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Tipo de identificación</label>
                            <p class="mb-0 fw-bold">{{ $usuario['tipo_identificacion'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Número de identificación</label>
                            <p class="mb-0 fw-bold">{{ $usuario['numero_identificacion'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nombres completos</label>
                            <p class="mb-0 fw-bold">
                                {{ $usuario['primer_nombre'] ?? '' }}
                                {{ $usuario['segundo_nombre'] ?? '' }}
                                {{ $usuario['primer_apellido'] ?? '' }}
                                {{ $usuario['segundo_apellido'] ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Correo electrónico</label>
                            <p class="mb-0 fw-bold">{{ $usuario['email'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Teléfono</label>
                            <p class="mb-0 fw-bold">{{ $solicitud['telefono'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nacionalidad</label>
                            <p class="mb-0 fw-bold">{{ $usuario['pais'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Departamento</label>
                            <p class="mb-0 fw-bold">{{ $usuario['departamento'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Municipio</label>
                            <p class="mb-0 fw-bold">{{ $usuario['municipio'] ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="fas fa-graduation-cap me-2"></i> Información Académica
                        </h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Instituto de origen</label>
                            <p class="mb-0 fw-bold">{{ $usuario['institucion_origen'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Programa de destino</label>
                            <p class="mb-0 fw-bold">{{ $solicitud['programa_destino_nombre'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Fecha de solicitud</label>
                            @if (!empty($solicitud['fecha_solicitud']))
                                <p class="mb-0 fw-bold">
                                    {{ \Carbon\Carbon::parse($solicitud['fecha_solicitud'])->format('d/m/Y') }}</p>
                            @else
                                <p class="mb-0 fw-bold">No disponible</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Estado de la solicitud</label>
                            <p class="mb-0 fw-bold">{{ $solicitud['estado'] ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Gestión del Proceso -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="fas fa-tasks me-2"></i> Gestión del Proceso
                        </h5>
                    </div>
                    <div class="card-body">
                        

                            <div class="form-group mb-4">
                                <label class="text-muted small text-uppercase mb-2">Estado actual</label>

                                @php
                                    $estado = strtolower($solicitud['estado'] ?? 'pendiente');
                                    if ($estado == 'aprobado' || $estado == 'aprobada') {
                                        $estado_class = 'success';
                                        $estado_icon = 'check-circle';
                                    } elseif ($estado == 'rechazado' || $estado == 'rechazada') {
                                        $estado_class = 'danger';
                                        $estado_icon = 'times-circle';
                                    } else {
                                        $estado_class = 'primary';
                                        $estado_icon = 'clock';
                                    }
                                @endphp

                                <div class="alert alert-{{ $estado_class }} text-center mb-4 border-0">
                                    <i class="fas fa-{{ $estado_icon }} me-2"></i>
                                    <span class="fw-bold">{{ ucfirst($solicitud['estado'] ?? 'Pendiente') }}</span>
                                </div>
                            </div>

                            <a href="{{ route('admin.homologaciones.vice.documentos', $solicitud['id_solicitud']) }}"
                                class="btn btn-primary w-100 mb-3 py-3 shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i> Verificar Documentos
                            </a>

                            <a href="{{ route('admin.homologaciones.vice.procesohomologacion.vicerrectoria', $solicitud['numero_radicado']) }}"
                                class="btn btn-success w-100 mb-3 py-3 shadow-sm">
                                <i class="fas fa-play-circle me-2"></i> Proceso
                            </a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('js/informacionhomologacion.js') }}"></script>
@endsection

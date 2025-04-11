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
                            <i class="fas fa-file-alt me-2"></i> Homologación #{{ $homologacion->codigo }}
                        </h4>

                        {{-- Botón de cerrar alineado a la derecha dentro del mismo contenedor --}}
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
                            <p>{{ $homologacion->estudiante->tipo_identificacion }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Número de identificación:</strong></label>
                            <p>{{ $homologacion->estudiante->numero_identificacion }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Nombres completos:</strong></label>
                            <p>{{ $homologacion->estudiante->nombre_completo }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Correo electrónico:</strong></label>
                            <p>{{ $homologacion->estudiante->correo }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Teléfono:</strong></label>
                            <p>{{ $homologacion->estudiante->telefono }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Nacionalidad:</strong></label>
                            <p>{{ $homologacion->estudiante->nacionalidad }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Departamento:</strong></label>
                            <p>{{ $homologacion->estudiante->departamento }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Municipio:</strong></label>
                            <p>{{ $homologacion->estudiante->municipio }}</p>
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
                            <p>{{ $homologacion->instituto_origen }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Programa cursado:</strong></label>
                            <p>{{ $homologacion->programa_origen }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Carrera de interés:</strong></label>
                            <p>{{ $homologacion->carrera_interes }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label><strong>Fecha de solicitud:</strong></label>
                            <p>{{ $homologacion->fecha_solicitud->format('d/m/Y') }}</p>
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
                        <form action="{{ route('admin.homologaciones.actualizar', $homologacion->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-4">
                                <label class="fw-bold">Estado actual:</label>
                                <div class="alert alert-{{ $homologacion->estado_class }} text-center">
                                    {{ ucfirst($homologacion->estado) }}
                                </div>
                            </div>
                            <a href="{{ route('homologacion.documentos', $homologacion->codigo) }}"
                                class="btn btn-primary w-100 mb-3">
                                <i class="fas fa-check-circle me-2"></i> Verificar Documentos
                            </a>


                            <a href="{{ route('admin.homologacionescoordinador.procesohomologacion', $homologacion->codigo) }}"
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

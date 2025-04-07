{{-- Vista: Detalle de Homologación --}}
@extends('admin.layouts.app')

@section('title', ' Homologaciones')

@section('content')
    <div class="container-fluid py-4">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="{{ asset('css/informacionhomologacion.css') }}" rel="stylesheet">


        </head>

        <body>
            <!-- Numero de Homologación -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-file-alt mr-2"></i> Homologación #{{ $homologacion->codigo }}
                                </h4>
                                <span class="badge badge-{{ $homologacion->estado_class }}">
                                    {{ $homologacion->estado }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Información Personal -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-user mr-2"></i> Información Personal</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tipo de identificación:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->tipo_identificacion }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label>Número de identificación:</label>
                                        <p class="form-control-static">
                                            {{ $homologacion->estudiante->numero_identificacion }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nombres completos:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->nombre_completo }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Correo electrónico:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->correo }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->telefono }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nacionalidad:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->nacionalidad }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Departamento:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->departamento }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Municipio:</label>
                                        <p class="form-control-static">{{ $homologacion->estudiante->municipio }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Información Académica -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-graduation-cap mr-2"></i> Información Académica</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Instituto de origen:</label>
                                        <p class="form-control-static">{{ $homologacion->instituto_origen }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Programa cursado:</label>
                                        <p class="form-control-static">{{ $homologacion->programa_origen }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Carrera de interés:</label>
                                        <p class="form-control-static">{{ $homologacion->carrera_interes }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha de solicitud:</label>
                                        <p class="form-control-static">
                                            {{ $homologacion->fecha_solicitud->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestión del Proceso -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-tasks mr-2"></i> Gestión del Proceso</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.homologaciones.actualizar', $homologacion->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="estado">Estado de la homologación:</label>
                                    <p class="form-control-plaintext">{{ ucfirst($homologacion->estado) }}</p>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="button" class="btn btn-primary btn-block" id="verificarDocumentos">
                                        <i class="fas fa-check-circle mr-2"></i> Verificar Documentos
                                    </button>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="button" class="btn btn-success btn-block" id="iniciarProceso">
                                        <i class="fas fa-play-circle mr-2"></i> Iniciar Proceso
                                    </button>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-info btn-block">
                                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Historial de Cambios -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-history mr-2"></i> Historial de Cambios</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        @forelse($homologacion->historial as $historia)
                                            <tr>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <p class="mb-0"><strong>{{ $historia->accion }}</strong></p>
                                                            <small
                                                                class="text-muted">{{ $historia->created_at->format('d/m/Y - h:i A') }}</small>
                                                        </div>
                                                        <span class="badge badge-secondary">{{ $historia->usuario }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center">No hay registros en el historial</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</body>

</html>
<script src="{{ asset('js/informacionhomologacion.js') }}"></script>

@endsection

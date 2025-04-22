@extends('admin.layouts.appadmin')
<link href="{{ asset('css/materia.css') }}" rel="stylesheet">
<script src="{{ asset('js/materia.js') }}"></script>
@section('title', 'Crear nueva materia')

@section('content')
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 materia-card">
                <div class="card-header bg-primary text-white py-4">
                    <h2 class="mb-0 text-center fw-bold header-title">
                        Crear nueva materia
                    </h2>
                </div>
                <div class="card-body p-5">
                    <form id="crearMateriaForm" method="POST" action="{{ url('/admin/materias') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="nombre" class="form-label fs-5 fw-bold">Nombre de la materia</label>
                                <div class="input-group input-group-lg has-validation">
                                    <span class="input-group-text bg-light"><i class="fas fa-graduation-cap"></i></span>
                                    <input type="text" class="form-control form-control-lg" id="nombre" name="nombre"
                                           placeholder="Ingrese el nombre completo de la materia" required>
                                    <div class="invalid-feedback error-nombre">
                                        El nombre de la materia es obligatorio
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="creditos" class="form-label fs-5 fw-bold">Créditos académicos</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light"><i class="fas fa-star"></i></span>
                                    <input type="number" class="form-control form-control-lg" id="creditos" name="creditos"
                                           min="1" max="20" placeholder="Número de créditos" required>
                                    <div class="invalid-feedback error-creditos">
                                        El número de créditos debe ser mayor o igual a 1
                                    </div>
                                </div>
                                <small class="text-muted mt-2">Valor entre 1 y 20 créditos</small>
                            </div>

                            <div class="col-md-6">
                                <label for="semestre" class="form-label fs-5 fw-bold">Semestre</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="number" class="form-control form-control-lg" id="semestre" name="semestre"
                                           min="1" max="15" placeholder="Semestre académico" required>
                                    <div class="invalid-feedback error-semestre">
                                        El semestre debe ser mayor o igual a 1
                                    </div>
                                </div>
                                <small class="text-muted mt-2">Semestre en el que se imparte la materia</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="tipo" class="form-label fs-5 fw-bold">Tipo de materia</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                    <select class="form-select form-select-lg" id="tipo" name="tipo" required>
                                        <option value="" selected disabled>Seleccione un tipo</option>
                                        <option value="obligatoria">Obligatoria</option>
                                        <option value="electiva">Electiva</option>
                                    </select>
                                    <div class="invalid-feedback error-tipo">
                                        Debe seleccionar un tipo de materia
                                    </div>
                                </div>
                                <small class="text-muted mt-2">Indica si la materia es obligatoria o electiva</small>
                            </div>
<!-- Campo de pensum a ancho completo con área de texto más grande -->
<div class="row mb-4">
    <div class="col-12">
        <label for="pensum" class="form-label fs-5 fw-bold">Pensum</label>
        <div class="input-group has-validation">
            <span class="input-group-text bg-light align-self-start"><i class="fas fa-list-alt"></i></span>
            <textarea class="form-control form-control-lg pensum-textarea" id="pensum" name="pensum"
                      rows="6" placeholder="Ingrese la descripción completa del pensum, incluyendo detalles sobre el programa académico, año, características específicas y cualquier información relevante..." required></textarea>
            <div class="invalid-feedback error-pensum">
                Debe ingresar un pensum válido
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">Ingrese información detallada del pensum, incluyendo nombre, año y cualquier detalle adicional</small>
        </div>
    </div>
</div>
                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-between">
                                <a href="{{ url('/admin/materias') }}" class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="fas fa-arrow-left me-2"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                    <i class="fas fa-save me-2"></i> Guardar Materia
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">¡Éxito!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                La materia se ha guardado correctamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="errorDetails">Ha ocurrido un error al intentar guardar la materia.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="{{ asset('js/materia.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/materia.css') }}">
@endpush

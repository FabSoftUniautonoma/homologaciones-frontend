@extends('admin.layouts.appadmin')
@section('content')
<link href="{{ asset('css/programas.css') }}" rel="stylesheet">
<script src="{{ asset('js/programas.js') }}"></script>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-graduation-cap me-2"></i> Registro de Programa Académico
                    </h4>
                </div>

                <div class="card-body px-4 py-4">
                    <!-- Los mensajes de alerta se manejarán con modales -->
                    <form id="programaForm" method="POST" action="#" novalidate>
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre" class="form-control-label">Nombre <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text"
                                        id="nombre" name="nombre" placeholder="Ingrese el nombre del programa" required>
                                    <div class="invalid-feedback">
                                        El nombre no puede contener números ni caracteres especiales.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_snies" class="form-control-label">Código SNIES <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text"
                                        id="codigo_snies" name="codigo_snies" placeholder="Ingrese el código SNIES" required>
                                    <div class="invalid-feedback">
                                        El código SNIES debe contener solo números.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facultad_id" class="form-control-label">Facultad <span class="text-danger">*</span></label>
                                    <select class="form-select" id="facultad_id" name="facultad_id" required>
                                        <option value="" selected disabled>Seleccione una facultad</option>
                                        <option value="1">Ingeniería</option>
                                        <option value="2">Ciencias Económicas</option>
                                        <option value="3">Ciencias de la Salud</option>
                                        <option value="4">Derecho</option>
                                        <option value="5">Educación</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar una facultad.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_formacion" class="form-control-label">Tipo de Formación <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tipo_formacion" name="tipo_formacion" required>
                                        <option value="" selected disabled>Seleccione un tipo</option>
                                        <option value="Pregrado">Pregrado</option>
                                        <option value="Especialización">Especialización</option>
                                        <option value="Maestría">Maestría</option>
                                        <option value="Doctorado">Doctorado</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar un tipo de formación.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_creditos" class="form-control-label">Total de Créditos <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number"
                                        id="total_creditos" name="total_creditos" min="1" placeholder="Ej: 160" required>
                                    <div class="invalid-feedback">
                                        Debe ingresar un número válido mayor a cero.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="horas_presenciales" class="form-control-label">Horas Presenciales <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number"
                                        id="horas_presenciales" name="horas_presenciales" min="0" placeholder="Ej: 120" required>
                                    <div class="invalid-feedback">
                                        Debe ingresar un número válido mayor o igual a cero.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duracion" class="form-control-label">Duración (semestres) <span class="text-danger">*</span></label>
                                    <input class="form-control" type="number"
                                        id="duracion" name="duracion" min="1" placeholder="Ej: 10" required>
                                    <div class="invalid-feedback">
                                        Debe ingresar un número válido mayor a cero.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo" class="form-control-label">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-select" id="tipo" name="tipo" required>
                                        <option value="" selected disabled>Seleccione un tipo</option>
                                        <option value="Teórico">Teórico</option>
                                        <option value="Práctico">Práctico</option>
                                        <option value="Teórico-Práctico">Teórico-Práctico</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar un tipo.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="metodologia" class="form-control-label">Metodología <span class="text-danger">*</span></label>
                                    <select class="form-select" id="metodologia" name="metodologia" required>
                                        <option value="" selected disabled>Seleccione una metodología</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Presencial">Presencial</option>
                                        <option value="Híbrido">Híbrido</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Debe seleccionar una metodología.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12 d-flex justify-content-center">
                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary px-5 py-3 btn-spacing">
                                    <i class="fas fa-save me-2"></i> Guardar Programa
                                </button>

                                <!-- Botón Cancelar -->
                                <button type="button" class="btn btn-warning px-5 py-3"
                                    onclick="window.location.href='{{ url('/admin/programas/crear') }}'">

                                    <i class="fas fa-arrow-left me-2"></i> Cancelar
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
                <h5 class="modal-title" id="successModalLabel">¡Operación Exitosa!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="text-center w-100 py-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Programa guardado correctamente</h4>
                    <p class="text-muted">El programa académico ha sido registrado en el sistema con éxito.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">Error en la Operación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="text-center w-100 py-4">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">No se pudo guardar el programa</h4>
                    <p class="text-muted" id="errorMessage">Verifique la información ingresada e intente nuevamente.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

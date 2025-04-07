@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Crear Programa Académico</h1>
        <a href="{{ url('/admin/programas') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="programaForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_programa" class="form-label">Nombre del Programa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre_programa" name="nombre_programa" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el nombre del programa.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_anis" class="form-label">Código ANIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="codigo_anis" name="codigo_anis" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el código ANIS.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="facultad" class="form-label">Facultad <span class="text-danger">*</span></label>
                        <select class="form-select" id="facultad" name="facultad" required>
                            <option value="">Seleccione una facultad</option>
                            <option value="ingenieria">Facultad de Ingeniería</option>
                            <option value="ciencias_economicas">Facultad de Ciencias Económicas</option>
                            <option value="medicina">Facultad de Medicina</option>
                            <option value="educacion">Facultad de Educación</option>
                            <option value="ciencias">Facultad de Ciencias</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione una facultad.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tipo_formacion" class="form-label">Tipo de Formación <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipo_formacion" name="tipo_formacion" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="pregrado">Pregrado</option>
                            <option value="especializacion">Especialización</option>
                            <option value="maestria">Maestría</option>
                            <option value="doctorado">Doctorado</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un tipo de formación.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="total_creditos" class="form-label">Total de Créditos <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="total_creditos" name="total_creditos" min="1" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el total de créditos.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="horas_presenciales" class="form-label">Total de Horas Presenciales <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="horas_presenciales" name="horas_presenciales" min="0" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el total de horas presenciales.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="duracion" class="form-label">Duración (semestres) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duracion" name="duracion" min="1" required>
                        <div class="invalid-feedback">
                            Por favor ingrese la duración del programa.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tipo_programa" class="form-label">Tipo del Programa <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipo_programa" name="tipo_programa" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="teorico">Teórico</option>
                            <option value="practico">Práctico</option>
                            <option value="teorico-practico">Teórico-Práctico</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un tipo de programa.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="metodologia" class="form-label">Metodología <span class="text-danger">*</span></label>
                        <select class="form-select" id="metodologia" name="metodologia" required>
                            <option value="">Seleccione una metodología</option>
                            <option value="presencial">Presencial</option>
                            <option value="virtual">Virtual</option>
                            <option value="hibrida">Híbrida</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione una metodología.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institucion" class="form-label">Institución <span class="text-danger">*</span></label>
                        <select class="form-select" id="institucion" name="institucion" required>
                            <option value="">Seleccione una institución</option>
                            <option value="1">Universidad Nacional de Colombia</option>
                            <option value="2">Universidad de los Andes</option>
                            <option value="3">Universidad del Valle</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione una institución.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="activo" selected>Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion_programa" class="form-label">Descripción del Programa</label>
                    <textarea class="form-control" id="descripcion_programa" name="descripcion_programa" rows="3"></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Limpiar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarPrograma">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

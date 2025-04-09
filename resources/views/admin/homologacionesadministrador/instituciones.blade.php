@extends('admin.layouts.app')
@section('title', 'Crear Nueva Institución')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Crear Nueva Institución</h1>
        <a href="{{ url('/admin/instituciones') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Información de la Institución</h5>
        </div>
        <div class="card-body">
            <form id="institucionForm" action="{{ url('/admin/instituciones/guardar') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre de la Institución *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required
                               pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                               title="El nombre no debe incluir caracteres numéricos">
                        <div class="invalid-feedback">
                            El nombre no debe incluir caracteres numéricos.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_snies" class="form-label">Código SNIES *</label>
                        <input type="text" class="form-control" id="codigo_snies" name="codigo_snies" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ciudad" class="form-label">Ciudad *</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pais" class="form-label">País *</label>
                        <input type="text" class="form-control" id="pais" name="pais" value="Colombia" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                </div>

                <hr class="my-4">
                <h4>Programa Académico <span class="text-danger">*</span></h4>
                <p class="text-muted">Se requiere crear al menos un programa para registrar la institución.</p>

                <div id="programa-container">
                    <div class="card mb-3 bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Programa #1</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="programa_nombre" class="form-label">Nombre del Programa *</label>
                                    <input type="text" class="form-control" id="programa_nombre" name="programas[0][nombre]" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="programa_codigo" class="form-label">Código SNIES *</label>
                                    <input type="text" class="form-control" id="programa_codigo" name="programas[0][codigo]" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="programa_facultad" class="form-label">Facultad *</label>
                                    <select class="form-select" id="programa_facultad" name="programas[0][facultad]" required>
                                        <option value="">Seleccionar facultad</option>
                                        <option value="Ingeniería">Ingeniería</option>
                                        <option value="Ciencias Sociales">Ciencias Sociales</option>
                                        <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                                        <option value="Educación">Educación</option>
                                        <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                                        <option value="Otros">Otros</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3" id="otra-facultad-container" style="display: none;">
                                    <label for="otra_facultad" class="form-label">Especificar Facultad *</label>
                                    <input type="text" class="form-control" id="otra_facultad" name="programas[0][otra_facultad]">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="programa_creditos" class="form-label">Total de Créditos *</label>
                                    <input type="number" class="form-control" id="programa_creditos" name="programas[0][creditos]" min="1" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="programa_horas" class="form-label">Total de Horas *</label>
                                    <input type="number" class="form-control" id="programa_horas" name="programas[0][horas]" min="1" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="programa_duracion" class="form-label">Duración (semestres) *</label>
                                    <input type="number" class="form-control" id="programa_duracion" name="programas[0][duracion]" min="1" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="programa_metodologia" class="form-label">Metodología *</label>
                                    <select class="form-select" id="programa_metodologia" name="programas[0][metodologia]" required>
                                        <option value="">Seleccionar metodología</option>
                                        <option value="Presencial">Presencial</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Híbrida">Híbrida</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarMateria(0)">
                                        <i class="fas fa-plus-circle"></i> Agregar Materia
                                    </button>
                                </div>
                            </div>

                            <div id="materias-container-0" class="mt-3">
                                <h6>Materias del Programa</h6>
                                <div class="materia-item mb-2">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <input type="text" class="form-control" placeholder="Nombre de la materia"
                                                name="programas[0][materias][0][nombre]" required>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <input type="number" class="form-control" placeholder="Créditos" min="1"
                                                name="programas[0][materias][0][creditos]" required>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <input type="number" class="form-control" placeholder="Semestre" min="1"
                                                name="programas[0][materias][0][semestre]" required>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <select class="form-select" name="programas[0][materias][0][tipo]" required>
                                                <option value="">Tipo</option>
                                                <option value="Obligatoria">Obligatoria</option>
                                                <option value="Electiva">Electiva</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarMateria(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-success" onclick="agregarPrograma()">
                            <i class="fas fa-plus"></i> Agregar Otro Programa
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='{{ url('/admin/instituciones') }}'">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Institución</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="exitoModal" tabindex="-1" aria-labelledby="exitoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="exitoModalLabel">Éxito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <p>Datos guardados con éxito.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.href='{{ url('/admin/instituciones') }}'">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de agregar programa -->
<div class="modal fade" id="agregarProgramaModal" tabindex="-1" aria-labelledby="agregarProgramaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="agregarProgramaModalLabel">Agregar Programa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="programaModalForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_programa_nombre" class="form-label">Nombre del Programa *</label>
                            <input type="text" class="form-control" id="modal_programa_nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_programa_codigo" class="form-label">Código SNIES *</label>
                            <input type="text" class="form-control" id="modal_programa_codigo" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="modal_programa_facultad" class="form-label">Facultad *</label>
                            <select class="form-select" id="modal_programa_facultad" required>
                                <option value="">Seleccionar facultad</option>
                                <option value="Ingeniería">Ingeniería</option>
                                <option value="Ciencias Sociales">Ciencias Sociales</option>
                                <option value="Ciencias de la Salud">Ciencias de la Salud</option>
                                <option value="Educación">Educación</option>
                                <option value="Ciencias Administrativas">Ciencias Administrativas</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="modal-otra-facultad-container" style="display: none;">
                            <label for="modal_otra_facultad" class="form-label">Especificar Facultad *</label>
                            <input type="text" class="form-control" id="modal_otra_facultad">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="modal_programa_creditos" class="form-label">Total de Créditos *</label>
                            <input type="number" class="form-control" id="modal_programa_creditos" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_programa_horas" class="form-label">Total de Horas *</label>
                            <input type="number" class="form-control" id="modal_programa_horas" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_programa_duracion" class="form-label">Duración (semestres) *</label>
                            <input type="number" class="form-control" id="modal_programa_duracion" min="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="modal_programa_metodologia" class="form-label">Metodología *</label>
                            <select class="form-select" id="modal_programa_metodologia" required>
                                <option value="">Seleccionar metodología</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Virtual">Virtual</option>
                                <option value="Híbrida">Híbrida</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarProgramaBtn">Guardar Programa</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let programaCounter = 1;
    let materiaCounters = {'0': 1};

    document.addEventListener('DOMContentLoaded', function() {
        // Validación del formulario
        const form = document.getElementById('institucionForm');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (!validarFormulario()) {
                return;
            }

            // Simulación de envío (aquí iría la lógica de envío al backend)
            setTimeout(function() {
                const modal = new bootstrap.Modal(document.getElementById('exitoModal'));
                modal.show();
            }, 500);
        });

        // Mostrar campo "otra facultad" si se selecciona "Otros"
        document.getElementById('programa_facultad').addEventListener('change', function() {
            const otraFacultadContainer = document.getElementById('otra-facultad-container');
            otraFacultadContainer.style.display = this.value === 'Otros' ? 'block' : 'none';

            const otraFacultadInput = document.getElementById('otra_facultad');
            otraFacultadInput.required = this.value === 'Otros';
        });

        // Similar para el modal
        document.getElementById('modal_programa_facultad').addEventListener('change', function() {
            const otraFacultadContainer = document.getElementById('modal-otra-facultad-container');
            otraFacultadContainer.style.display = this.value === 'Otros' ? 'block' : 'none';

            const otraFacultadInput = document.getElementById('modal_otra_facultad');
            otraFacultadInput.required = this.value === 'Otros';
        });

        // Configurar botón para guardar programa desde modal
        document.getElementById('guardarProgramaBtn').addEventListener('click', function() {
            const modalForm = document.getElementById('programaModalForm');

            if (!modalForm.checkValidity()) {
                modalForm.reportValidity();
                return;
            }

            // Agregar programa desde modal
            const nombre = document.getElementById('modal_programa_nombre').value;
            const codigo = document.getElementById('modal_programa_codigo').value;
            const facultad = document.getElementById('modal_programa_facultad').value;
            const creditos = document.getElementById('modal_programa_creditos').value;
            const horas = document.getElementById('modal_programa_horas').value;
            const duracion = document.getElementById('modal_programa_duracion').value;
            const metodologia = document.getElementById('modal_programa_metodologia').value;

            let otraFacultad = '';
            if (facultad === 'Otros') {
                otraFacultad = document.getElementById('modal_otra_facultad').value;
            }

            // Añadir programa al formulario
            agregarProgramaDesdeModal(nombre, codigo, facultad, otraFacultad, creditos, horas, duracion, metodologia);

            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('agregarProgramaModal'));
            modal.hide();

            // Limpiar formulario del modal
            modalForm.reset();
        });
    });

    function validarFormulario() {
        const form = document.getElementById('institucionForm');

        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }

        // Validar que haya al menos un programa
        const programas = document.querySelectorAll('#programa-container .card');
        if (programas.length === 0) {
            alert('Debe crear al menos un programa para la institución.');
            return false;
        }

        return true;
    }

    function agregarPrograma() {
        // Abrir modal para agregar programa
        const modal = new bootstrap.Modal(document.getElementById('agregarProgramaModal'));
        modal.show();
    }

    function agregarProgramaDesdeModal(nombre, codigo, facultad, otraFacultad, creditos, horas, duracion, metodologia) {
        const programaContainer = document.getElementById('programa-container');

        // Inicializar contador de materias para este programa
        materiaCounters[programaCounter] = 0;

        const programaHTML = `
            <div class="card mb-3 bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Programa #${programaCounter + 1}</h5>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarPrograma(this)">
                            <i class="fas fa-times"></i> Eliminar
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Programa *</label>
                            <input type="text" class="form-control" value="${nombre}" name="programas[${programaCounter}][nombre]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Código SNIES *</label>
                            <input type="text" class="form-control" value="${codigo}" name="programas[${programaCounter}][codigo]" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Facultad *</label>
                            <input type="text" class="form-control" value="${facultad === 'Otros' ? otraFacultad : facultad}" name="programas[${programaCounter}][facultad]" readonly>
                            <input type="hidden" value="${facultad}" name="programas[${programaCounter}][facultad_original]">
                            ${facultad === 'Otros' ? `<input type="hidden" value="${otraFacultad}" name="programas[${programaCounter}][otra_facultad]">` : ''}
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Créditos *</label>
                                    <input type="number" class="form-control" value="${creditos}" name="programas[${programaCounter}][creditos]" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Horas *</label>
                                    <input type="number" class="form-control" value="${horas}" name="programas[${programaCounter}][horas]" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Semestres *</label>
                                    <input type="number" class="form-control" value="${duracion}" name="programas[${programaCounter}][duracion]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Metodología *</label>
                            <input type="text" class="form-control" value="${metodologia}" name="programas[${programaCounter}][metodologia]" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="agregarMateria(${programaCounter})">
                                <i class="fas fa-plus-circle"></i> Agregar Materia
                            </button>
                        </div>
                    </div>

                    <div id="materias-container-${programaCounter}" class="mt-3">
                        <h6>Materias del Programa</h6>
                        <div class="materia-item mb-2">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <input type="text" class="form-control" placeholder="Nombre de la materia"
                                        name="programas[${programaCounter}][materias][0][nombre]" required>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <input type="number" class="form-control" placeholder="Créditos" min="1"
                                        name="programas[${programaCounter}][materias][0][creditos]" required>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <input type="number" class="form-control" placeholder="Semestre" min="1"
                                        name="programas[${programaCounter}][materias][0][semestre]" required>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <select class="form-select" name="programas[${programaCounter}][materias][0][tipo]" required>
                                        <option value="">Tipo</option>
                                        <option value="Obligatoria">Obligatoria</option>
                                        <option value="Electiva">Electiva</option>
                                    </select>
                                </div>
                                <div class="col-md-1 mb-2">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarMateria(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        programaContainer.insertAdjacentHTML('beforeend', programaHTML);
        materiaCounters[programaCounter] = 1;
        programaCounter++;
    }

    function eliminarPrograma(button) {
        const programaCard = button.closest('.card');
        programaCard.remove();
    }

    function agregarMateria(programaIndex) {
        const materiasContainer = document.getElementById(`materias-container-${programaIndex}`);
        const materiaIndex = materiaCounters[programaIndex];

        const materiaHTML = `
            <div class="materia-item mb-2">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" placeholder="Nombre de la materia"
                            name="programas[${programaIndex}][materias][${materiaIndex}][nombre]" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" class="form-control" placeholder="Créditos" min="1"
                            name="programas[${programaIndex}][materias][${materiaIndex}][creditos]" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" class="form-control" placeholder="Semestre" min="1"
                            name="programas[${programaIndex}][materias][${materiaIndex}][semestre]" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <select class="form-select" name="programas[${programaIndex}][materias][${materiaIndex}][tipo]" required>
                            <option value="">Tipo</option>
                            <option value="Obligatoria">Obligatoria</option>
                            <option value="Electiva">Electiva</option>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarMateria(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        materiasContainer.insertAdjacentHTML('beforeend', materiaHTML);
        materiaCounters[programaIndex]++;
    }

    function eliminarMateria(button) {
        const materiaItem = button.closest('.materia-item');
        const programaCard = button.closest('.card');
        const materiasContainer = materiaItem.parentElement;

        // Verificar si es la última materia del programa
        if (materiasContainer.querySelectorAll('.materia-item').length > 1) {
            materiaItem.remove();
        } else {
            alert('Debe haber al menos una materia en el programa.');
        }
    }
</script>
@endsection

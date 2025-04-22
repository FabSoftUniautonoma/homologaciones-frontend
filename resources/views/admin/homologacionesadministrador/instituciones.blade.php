@extends('admin.layouts.appadmin')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Gestión de Instituciones</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addInstitutionModal">
                        <i class="fas fa-plus me-2"></i>Agregar Institución
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Alerta de éxito -->
                    <div class="alert alert-success alert-dismissible fade show mx-4 mt-3 d-none" id="successAlert" role="alert">
                        <span class="alert-text" id="successMessage">Cambios guardados exitosamente</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <!-- Alerta de error -->
                    <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 d-none" id="errorAlert" role="alert">
                        <span class="alert-text" id="errorMessage">Error al guardar cambios</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Listado de instituciones -->
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Institución</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ubicación</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Programas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="institutionsTable">
                                <!-- Datos de ejemplo, esto se llenaría dinámicamente -->
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Universidad Nacional</h6>
                                                <p class="text-xs text-secondary mb-0">unacional.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Bogotá</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">12 programas</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-success">Activa</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="showPrograms(1)">
                                            <i class="fas fa-list text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="editInstitution(1)">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-danger mb-0"
                                                onclick="confirmDeleteInstitution(1)">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Instituto Tecnológico</h6>
                                                <p class="text-xs text-secondary mb-0">institutotec.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Medellín</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">8 programas</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-success">Activa</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="showPrograms(2)">
                                            <i class="fas fa-list text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="editInstitution(2)">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-danger mb-0"
                                                onclick="confirmDeleteInstitution(2)">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de visualización de programas (inicialmente oculto) -->
    <div class="row d-none" id="programsPanel">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Programas de <span id="institutionName">Universidad Nacional</span></h6>
                        <button class="btn btn-link px-0" onclick="hidePrograms()">
                            <i class="fas fa-arrow-left me-2"></i>Volver a instituciones
                        </button>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProgramModal">
                        <i class="fas fa-plus me-2"></i>Agregar Programa
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Programa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nivel</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Materias</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="programsTable">
                                <!-- Datos de ejemplo, esto se llenaría dinámicamente -->
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Ingeniería de Sistemas</h6>
                                                <p class="text-xs text-secondary mb-0">Código: ING-SIS-2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Pregrado</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">24 materias</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-success">Activo</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="showSubjects(1)">
                                            <i class="fas fa-list text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="editProgram(1)">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-danger mb-0"
                                                onclick="confirmDeleteProgram(1)">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Administración de Empresas</h6>
                                                <p class="text-xs text-secondary mb-0">Código: ADM-EMP-2023</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Pregrado</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">20 materias</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-success">Activo</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="showSubjects(2)">
                                            <i class="fas fa-list text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="editProgram(2)">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-danger mb-0"
                                                onclick="confirmDeleteProgram(2)">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de visualización de materias (inicialmente oculto) -->
    <div class="row d-none" id="subjectsPanel">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Materias de <span id="programName">Ingeniería de Sistemas</span></h6>
                        <button class="btn btn-link px-0" onclick="backToPrograms()">
                            <i class="fas fa-arrow-left me-2"></i>Volver a programas
                        </button>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="fas fa-plus me-2"></i>Agregar Materia
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Materia</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Semestre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Créditos</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="subjectsTable">
                                <!-- Datos de ejemplo, esto se llenaría dinámicamente -->
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Algoritmos y Programación</h6>
                                                <p class="text-xs text-secondary mb-0">Código: ALG-001</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">1</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">4</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-success">Activa</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="editSubject(1)">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-danger mb-0"
                                                onclick="confirmDeleteSubject(1)">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Cálculo Diferencial</h6>
                                                <p class="text-xs text-secondary mb-0">Código: CAL-001</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">1</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">4</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-success">Activa</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-secondary mb-0"
                                                onclick="editSubject(2)">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>
                                        <button class="btn btn-link text-danger mb-0"
                                                onclick="confirmDeleteSubject(2)">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar una nueva institución -->
<div class="modal fade" id="addInstitutionModal" tabindex="-1" role="dialog" aria-labelledby="addInstitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInstitutionModalLabel">Agregar Nueva Institución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addInstitutionForm">
                    <div class="form-group mb-3">
                        <label for="institutionName" class="form-control-label">Nombre de la Institución</label>
                        <input type="text" class="form-control" id="institutionNameInput" placeholder="Ingrese el nombre de la institución" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="institutionWeb" class="form-control-label">Sitio Web</label>
                        <input type="url" class="form-control" id="institutionWeb" placeholder="Ingrese la URL del sitio web">
                    </div>
                    <div class="form-group mb-3">
                        <label for="institutionLocation" class="form-control-label">Ubicación</label>
                        <input type="text" class="form-control" id="institutionLocation" placeholder="Ciudad, País">
                    </div>
                    <div class="form-group mb-3">
                        <label for="institutionStatus" class="form-control-label">Estado</label>
                        <select class="form-control" id="institutionStatus">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveInstitution()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar una institución existente -->
<div class="modal fade" id="editInstitutionModal" tabindex="-1" role="dialog" aria-labelledby="editInstitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInstitutionModalLabel">Editar Institución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInstitutionForm">
                    <input type="hidden" id="editInstitutionId">
                    <div class="form-group mb-3">
                        <label for="editInstitutionName" class="form-control-label">Nombre de la Institución</label>
                        <input type="text" class="form-control" id="editInstitutionName" placeholder="Ingrese el nombre de la institución" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editInstitutionWeb" class="form-control-label">Sitio Web</label>
                        <input type="url" class="form-control" id="editInstitutionWeb" placeholder="Ingrese la URL del sitio web">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editInstitutionLocation" class="form-control-label">Ubicación</label>
                        <input type="text" class="form-control" id="editInstitutionLocation" placeholder="Ciudad, País">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editInstitutionStatus" class="form-control-label">Estado</label>
                        <select class="form-control" id="editInstitutionStatus">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="updateInstitution()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar un nuevo programa -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramModalLabel">Agregar Nuevo Programa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProgramForm">
                    <input type="hidden" id="programInstitutionId">
                    <div class="form-group mb-3">
                        <label for="programName" class="form-control-label">Nombre del Programa</label>
                        <input type="text" class="form-control" id="programNameInput" placeholder="Ingrese el nombre del programa" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="programCode" class="form-control-label">Código</label>
                        <input type="text" class="form-control" id="programCode" placeholder="Ingrese el código del programa">
                    </div>
                    <div class="form-group mb-3">
                        <label for="programLevel" class="form-control-label">Nivel</label>
                        <select class="form-control" id="programLevel">
                            <option value="pregrado">Pregrado</option>
                            <option value="posgrado">Posgrado</option>
                            <option value="maestria">Maestría</option>
                            <option value="doctorado">Doctorado</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="programStatus" class="form-control-label">Estado</label>
                        <select class="form-control" id="programStatus">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveProgram()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar un programa existente -->
<div class="modal fade" id="editProgramModal" tabindex="-1" role="dialog" aria-labelledby="editProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProgramModalLabel">Editar Programa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProgramForm">
                    <input type="hidden" id="editProgramId">
                    <div class="form-group mb-3">
                        <label for="editProgramName" class="form-control-label">Nombre del Programa</label>
                        <input type="text" class="form-control" id="editProgramName" placeholder="Ingrese el nombre del programa" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editProgramCode" class="form-control-label">Código</label>
                        <input type="text" class="form-control" id="editProgramCode" placeholder="Ingrese el código del programa">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editProgramLevel" class="form-control-label">Nivel</label>
                        <select class="form-control" id="editProgramLevel">
                            <option value="pregrado">Pregrado</option>
                            <option value="posgrado">Posgrado</option>
                            <option value="maestria">Maestría</option>
                            <option value="doctorado">Doctorado</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editProgramStatus" class="form-control-label">Estado</label>
                        <select class="form-control" id="editProgramStatus">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="updateProgram()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar una nueva materia -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Agregar Nueva Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubjectForm">
                    <input type="hidden" id="subjectProgramId">
                    <div class="form-group mb-3">
                        <label for="subjectName" class="form-control-label">Nombre de la Materia</label>
                        <input type="text" class="form-control" id="subjectNameInput" placeholder="Ingrese el nombre de la materia" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectCode" class="form-control-label">Código</label>
                        <input type="text" class="form-control" id="subjectCode" placeholder="Ingrese el código de la materia">
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectSemester" class="form-control-label">Semestre</label>
                        <input type="number" class="form-control" id="subjectSemester" min="1" max="12" placeholder="Semestre">
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectCredits" class="form-control-label">Créditos</label>
                        <input type="number" class="form-control" id="subjectCredits" min="1" max="10" placeholder="Créditos">
                    </div>
                    <div class="form-group mb-3">
                        <label for="subjectStatus" class="form-control-label">Estado</label>
                        <select class="form-control" id="subjectStatus">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveSubject()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar una materia existente -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubjectModalLabel">Editar Materia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSubjectForm">
                    <input type="hidden" id="editSubjectId">
                    <div class="form-group mb-3">
                        <label for="editSubjectName" class="form-control-label">Nombre de la Materia</label>
                        <input type="text" class="form-control" id="editSubjectName" placeholder="Ingrese el nombre de la materia" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="editSubjectCode" class="form-control-label">Código</label>
                        <input type="text" class="form-control" id="editSubjectCode" placeholder="Ingrese el código de la materia">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editSubjectSemester" class="form-control-label">Semestre</label>
                        <input type="number" class="form-control" id="editSubjectSemester" min="1" max="12" placeholder="Semestre">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editSubjectCredits" class="form-control-label">Créditos</label>
                        <input type="number" class="form-control" id="editSubjectCredits" min="1" max="10" placeholder="Créditos">
                    </div>
                    <div class="form-group mb-3">
                        <label for="editSubjectStatus" class="form-control-label">Estado</label>
                        <select class="form-control" id="editSubjectStatus">
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="updateSubject()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmText">¿Está seguro de que desea eliminar este elemento?</p>
                <input type="hidden" id="deleteItemId">
                <input type="hidden" id="deleteItemType">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="deleteConfirmed()">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales para almacenar referencias
    let currentInstitutionId = null;
    let currentProgramId = null;

    // Funciones para mostrar/ocultar paneles
    function showPrograms(institutionId) {
        // En una aplicación real, aquí se cargarían los programas desde el servidor
        currentInstitutionId = institutionId;

        // Actualizar el nombre de la institución en el panel de programas
        const institutionName = document.querySelector(`#institutionsTable tr[data-id="${institutionId}"] h6`).textContent;
        document.getElementById('institutionName').textContent = institutionName;

        // Ocultar panel de instituciones y mostrar panel de programas
        document.getElementById('programsPanel').classList.remove('d-none');
        document.getElementById('subjectsPanel').classList.add('d-none');

        // Asignar el ID de la institución para el formulario de agregar programa
        document.getElementById('programInstitutionId').value = institutionId;

        // Scroll hacia el panel de programas
        document.getElementById('programsPanel').scrollIntoView({ behavior: 'smooth' });
    }

    function hidePrograms() {
        document.getElementById('programsPanel').classList.add('d-none');
        document.getElementById('subjectsPanel').classList.add('d-none');
    }

    function showSubjects(programId) {
        // En una aplicación real, aquí se cargarían las materias desde el servidor
        currentProgramId = programId;

        // Actualizar el nombre del programa en el panel de materias
        const programName = document.querySelector(`#programsTable tr[data-id="${programId}"] h6`).textContent;
        document.getElementById('programName').textContent = programName;

        // Mostrar panel de materias
        document.getElementById('subjectsPanel').classList.remove('d-none');

        // Asignar el ID del programa para el formulario de agregar materia
        document.getElementById('subjectProgramId').value = programId;

        // Scroll hacia el panel de materias
        document.getElementById('subjectsPanel').scrollIntoView({ behavior: 'smooth' });
    }

    function backToPrograms() {
        document.getElementById('subjectsPanel').classList.add('d-none');
    }

    // Funciones para el manejo de instituciones
    function editInstitution(institutionId) {
        // En una aplicación real, aquí se cargarían los datos de la institución desde el servidor
        // Para la demostración, simularemos con los datos existentes en la tabla

        // Buscar la fila de la institución
        const row = document.querySelector(`#institutionsTable tr[data-id="${institutionId}"]`);
        if (row) {
            const name = row.querySelector('h6').textContent;
            const web = row.querySelector('p').textContent;
            const location = row.querySelector('td:nth-child(2) p').textContent;
            const status = row.querySelector('td:nth-child(4) span').classList.contains('bg-gradient-success') ? 'active' : 'inactive';

            // Llenar el formulario de edición
            document.getElementById('editInstitutionId').value = institutionId;
            document.getElementById('editInstitutionName').value = name;
            document.getElementById('editInstitutionWeb').value = web;
            document.getElementById('editInstitutionLocation').value = location;
            document.getElementById('editInstitutionStatus').value = status;

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('editInstitutionModal'));
            modal.show();
        }
    }

    function saveInstitution() {
        // Validar el formulario
        const form = document.getElementById('addInstitutionForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // En una aplicación real, aquí se enviarían los datos al servidor
        // Para la demostración, simularemos una operación exitosa

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addInstitutionModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showSuccessMessage('Institución agregada exitosamente');

        // Actualizar la tabla (en una aplicación real, esto se haría con los datos devueltos por el servidor)
        const newRow = `
            <tr data-id="${Date.now()}">
                <td>
                    <div class="d-flex px-3 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">${document.getElementById('institutionNameInput').value}</h6>
                            <p class="text-xs text-secondary mb-0">${document.getElementById('institutionWeb').value}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">${document.getElementById('institutionLocation').value}</p>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">0 programas</p>
                </td>
                <td>
                    <span class="badge badge-sm bg-gradient-${document.getElementById('institutionStatus').value === 'active' ? 'success' : 'secondary'}">${document.getElementById('institutionStatus').value === 'active' ? 'Activa' : 'Inactiva'}</span>
                </td>
                <td class="align-middle text-center">
                    <button class="btn btn-link text-secondary mb-0"
                            onclick="showPrograms(${Date.now()})">
                        <i class="fas fa-list text-xs"></i>
                    </button>
                    <button class="btn btn-link text-secondary mb-0"
                            onclick="editInstitution(${Date.now()})">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button class="btn btn-link text-danger mb-0"
                            onclick="confirmDeleteInstitution(${Date.now()})">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('institutionsTable').insertAdjacentHTML('beforeend', newRow);

        // Resetear el formulario
        form.reset();
    }

    function updateInstitution() {
        // Validar el formulario
        const form = document.getElementById('editInstitutionForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // En una aplicación real, aquí se enviarían los datos al servidor
        // Para la demostración, simularemos una operación exitosa
        const institutionId = document.getElementById('editInstitutionId').value;

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editInstitutionModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showSuccessMessage('Institución actualizada exitosamente');

        // Actualizar la tabla (en una aplicación real, esto se haría con los datos devueltos por el servidor)
        const row = document.querySelector(`#institutionsTable tr[data-id="${institutionId}"]`);
        if (row) {
            row.querySelector('h6').textContent = document.getElementById('editInstitutionName').value;
            row.querySelector('p').textContent = document.getElementById('editInstitutionWeb').value;
            row.querySelector('td:nth-child(2) p').textContent = document.getElementById('editInstitutionLocation').value;
            const statusSpan = row.querySelector('td:nth-child(4) span');
            statusSpan.className = `badge badge-sm bg-gradient-${document.getElementById('editInstitutionStatus').value === 'active' ? 'success' : 'secondary'}`;
            statusSpan.textContent = document.getElementById('editInstitutionStatus').value === 'active' ? 'Activa' : 'Inactiva';
        }
    }

    function confirmDeleteInstitution(institutionId) {
        document.getElementById('deleteItemId').value = institutionId;
        document.getElementById('deleteItemType').value = 'institution';
        document.getElementById('deleteConfirmText').textContent = '¿Está seguro de que desea eliminar esta institución? Esta acción eliminará todos los programas y materias asociados.';

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Funciones para el manejo de programas
    function editProgram(programId) {
        // En una aplicación real, aquí se cargarían los datos del programa desde el servidor
        // Para la demostración, simularemos con los datos existentes en la tabla

        // Buscar la fila del programa
        const row = document.querySelector(`#programsTable tr[data-id="${programId}"]`);
        if (row) {
            const name = row.querySelector('h6').textContent;
            const code = row.querySelector('p').textContent.replace('Código: ', '');
            const level = row.querySelector('td:nth-child(2) p').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(4) span').classList.contains('bg-gradient-success') ? 'active' : 'inactive';

            // Llenar el formulario de edición
            document.getElementById('editProgramId').value = programId;
            document.getElementById('editProgramName').value = name;
            document.getElementById('editProgramCode').value = code;
            document.getElementById('editProgramLevel').value = level;
            document.getElementById('editProgramStatus').value = status;

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('editProgramModal'));
            modal.show();
        }
    }

    function saveProgram() {
        // Validar el formulario
        const form = document.getElementById('addProgramForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // En una aplicación real, aquí se enviarían los datos al servidor
        // Para la demostración, simularemos una operación exitosa

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addProgramModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showSuccessMessage('Programa agregado exitosamente');

        // Actualizar la tabla (en una aplicación real, esto se haría con los datos devueltos por el servidor)
        const newRow = `
            <tr data-id="${Date.now()}">
                <td>
                    <div class="d-flex px-3 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">${document.getElementById('programNameInput').value}</h6>
                            <p class="text-xs text-secondary mb-0">Código: ${document.getElementById('programCode').value}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">${document.getElementById('programLevel').options[document.getElementById('programLevel').selectedIndex].text}</p>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">0 materias</p>
                </td>
                <td>
                    <span class="badge badge-sm bg-gradient-${document.getElementById('programStatus').value === 'active' ? 'success' : 'secondary'}">${document.getElementById('programStatus').value === 'active' ? 'Activo' : 'Inactivo'}</span>
                </td>
                <td class="align-middle text-center">
                    <button class="btn btn-link text-secondary mb-0"
                            onclick="showSubjects(${Date.now()})">
                        <i class="fas fa-list text-xs"></i>
                    </button>
                    <button class="btn btn-link text-secondary mb-0"
                            onclick="editProgram(${Date.now()})">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button class="btn btn-link text-danger mb-0"
                            onclick="confirmDeleteProgram(${Date.now()})">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('programsTable').insertAdjacentHTML('beforeend', newRow);

        // Actualizar el contador de programas en la tabla de instituciones
        const institutionRow = document.querySelector(`#institutionsTable tr[data-id="${currentInstitutionId}"]`);
        if (institutionRow) {
            const programsCount = institutionRow.querySelector('td:nth-child(3) p');
            const currentCount = parseInt(programsCount.textContent) || 0;
            programsCount.textContent = `${currentCount + 1} programas`;
        }

        // Resetear el formulario
        form.reset();
    }

    function updateProgram() {
        // Validar el formulario
        const form = document.getElementById('editProgramForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // En una aplicación real, aquí se enviarían los datos al servidor
        // Para la demostración, simularemos una operación exitosa
        const programId = document.getElementById('editProgramId').value;

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editProgramModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showSuccessMessage('Programa actualizado exitosamente');

        // Actualizar la tabla (en una aplicación real, esto se haría con los datos devueltos por el servidor)
        const row = document.querySelector(`#programsTable tr[data-id="${programId}"]`);
        if (row) {
            row.querySelector('h6').textContent = document.getElementById('editProgramName').value;
            row.querySelector('p').textContent = 'Código: ' + document.getElementById('editProgramCode').value;
            row.querySelector('td:nth-child(2) p').textContent = document.getElementById('editProgramLevel').options[document.getElementById('editProgramLevel').selectedIndex].text;
            const statusSpan = row.querySelector('td:nth-child(4) span');
            statusSpan.className = `badge badge-sm bg-gradient-${document.getElementById('editProgramStatus').value === 'active' ? 'success' : 'secondary'}`;
            statusSpan.textContent = document.getElementById('editProgramStatus').value === 'active' ? 'Activo' : 'Inactivo';
        }
    }

    function confirmDeleteProgram(programId) {
        document.getElementById('deleteItemId').value = programId;
        document.getElementById('deleteItemType').value = 'program';
        document.getElementById('deleteConfirmText').textContent = '¿Está seguro de que desea eliminar este programa? Esta acción eliminará todas las materias asociadas.';

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Funciones para el manejo de materias
    function editSubject(subjectId) {
        // En una aplicación real, aquí se cargarían los datos de la materia desde el servidor
        // Para la demostración, simularemos con los datos existentes en la tabla

        // Buscar la fila de la materia
        const row = document.querySelector(`#subjectsTable tr[data-id="${subjectId}"]`);
        if (row) {
            const name = row.querySelector('h6').textContent;
            const code = row.querySelector('p').textContent.replace('Código: ', '');
            const semester = row.querySelector('td:nth-child(2) p').textContent;
            const credits = row.querySelector('td:nth-child(3) p').textContent;
            const status = row.querySelector('td:nth-child(4) span').classList.contains('bg-gradient-success') ? 'active' : 'inactive';

            // Llenar el formulario de edición
            document.getElementById('editSubjectId').value = subjectId;
            document.getElementById('editSubjectName').value = name;
            document.getElementById('editSubjectCode').value = code;
            document.getElementById('editSubjectSemester').value = semester;
            document.getElementById('editSubjectCredits').value = credits;
            document.getElementById('editSubjectStatus').value = status;

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
            modal.show();
        }
    }

    function saveSubject() {
        // Validar el formulario
        const form = document.getElementById('addSubjectForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // En una aplicación real, aquí se enviarían los datos al servidor
        // Para la demostración, simularemos una operación exitosa

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addSubjectModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showSuccessMessage('Materia agregada exitosamente');

        // Actualizar la tabla (en una aplicación real, esto se haría con los datos devueltos por el servidor)
        const newRow = `
            <tr data-id="${Date.now()}">
                <td>
                    <div class="d-flex px-3 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">${document.getElementById('subjectNameInput').value}</h6>
                            <p class="text-xs text-secondary mb-0">Código: ${document.getElementById('subjectCode').value}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">${document.getElementById('subjectSemester').value}</p>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">${document.getElementById('subjectCredits').value}</p>
                </td>
                <td>
                    <span class="badge badge-sm bg-gradient-${document.getElementById('subjectStatus').value === 'active' ? 'success' : 'secondary'}">${document.getElementById('subjectStatus').value === 'active' ? 'Activa' : 'Inactiva'}</span>
                </td>
                <td class="align-middle text-center">
                    <button class="btn btn-link text-secondary mb-0"
                            onclick="editSubject(${Date.now()})">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <button class="btn btn-link text-danger mb-0"
                            onclick="confirmDeleteSubject(${Date.now()})">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('subjectsTable').insertAdjacentHTML('beforeend', newRow);

        // Actualizar el contador de materias en la tabla de programas
        const programRow = document.querySelector(`#programsTable tr[data-id="${currentProgramId}"]`);
        if (programRow) {
            const subjectsCount = programRow.querySelector('td:nth-child(3) p');
            const currentCount = parseInt(subjectsCount.textContent) || 0;
            subjectsCount.textContent = `${currentCount + 1} materias`;
        }

        // Resetear el formulario
        form.reset();
    }

    function updateSubject() {
        // Validar el formulario
        const form = document.getElementById('editSubjectForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // En una aplicación real, aquí se enviarían los datos al servidor
        // Para la demostración, simularemos una operación exitosa
        const subjectId = document.getElementById('editSubjectId').value;

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editSubjectModal'));
        modal.hide();

        // Mostrar mensaje de éxito
        showSuccessMessage('Materia actualizada exitosamente');

        // Actualizar la tabla (en una aplicación real, esto se haría con los datos devueltos por el servidor)
        const row = document.querySelector(`#subjectsTable tr[data-id="${subjectId}"]`);
        if (row) {
            row.querySelector('h6').textContent = document.getElementById('editSubjectName').value;
            row.querySelector('p').textContent = 'Código: ' + document.getElementById('editSubjectCode').value;
            row.querySelector('td:nth-child(2) p').textContent = document.getElementById('editSubjectSemester').value;
            row.querySelector('td:nth-child(3) p').textContent = document.getElementById('editSubjectCredits').value;
            const statusSpan = row.querySelector('td:nth-child(4) span');
            statusSpan.className = `badge badge-sm bg-gradient-${document.getElementById('editSubjectStatus').value === 'active' ? 'success' : 'secondary'}`;
            statusSpan.textContent = document.getElementById('editSubjectStatus').value === 'active' ? 'Activa' : 'Inactiva';
        }
    }

    function confirmDeleteSubject(subjectId) {
        document.getElementById('deleteItemId').value = subjectId;
        document.getElementById('deleteItemType').value = 'subject';
        document.getElementById('deleteConfirmText').textContent = '¿Está seguro de que desea eliminar esta materia?';

        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // Función para confirmar la eliminación de un elemento
    function deleteConfirmed() {
        const itemId = document.getElementById('deleteItemId').value;
        const itemType = document.getElementById('deleteItemType').value;

        // En una aplicación real, aquí se enviaría la solicitud de eliminación al servidor
        // Para la demostración, simularemos una operación exitosa

        // Cerrar el modal de confirmación
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
        modal.hide();

        // Eliminar el elemento según su tipo
        if (itemType === 'institution') {
            const row = document.querySelector(`#institutionsTable tr[data-id="${itemId}"]`);
            if (row) row.remove();

            // Si la institución eliminada es la que se está mostrando, ocultar los paneles
            if (currentInstitutionId === itemId) {
                hidePrograms();
            }

            showSuccessMessage('Institución eliminada exitosamente');
        } else if (itemType === 'program') {
            const row = document.querySelector(`#programsTable tr[data-id="${itemId}"]`);
            if (row) row.remove();

            // Actualizar el contador de programas en la tabla de instituciones
            const institutionRow = document.querySelector(`#institutionsTable tr[data-id="${currentInstitutionId}"]`);
            if (institutionRow) {
                const programsCount = institutionRow.querySelector('td:nth-child(3) p');
                const currentCount = parseInt(programsCount.textContent) || 0;
                if (currentCount > 0) {
                    programsCount.textContent = `${currentCount - 1} programas`;
                }
            }

            // Si el programa eliminado es el que se está mostrando, ocultar el panel de materias
            if (currentProgramId === itemId) {
                document.getElementById('subjectsPanel').classList.add('d-none');
            }

            showSuccessMessage('Programa eliminado exitosamente');
        } else if (itemType === 'subject') {
            const row = document.querySelector(`#subjectsTable tr[data-id="${itemId}"]`);
            if (row) row.remove();

            // Actualizar el contador de materias en la tabla de programas
            const programRow = document.querySelector(`#programsTable tr[data-id="${currentProgramId}"]`);
            if (programRow) {
                const subjectsCount = programRow.querySelector('td:nth-child(3) p');
                const currentCount = parseInt(subjectsCount.textContent) || 0;
                if (currentCount > 0) {
                    subjectsCount.textContent = `${currentCount - 1} materias`;
                }
            }

            showSuccessMessage('Materia eliminada exitosamente');
        }
    }

    // Función para mostrar mensajes de éxito
    function showSuccessMessage(message) {
        const alert = document.getElementById('successAlert');
        document.getElementById('successMessage').textContent = message;
        alert.classList.remove('d-none');

        // Ocultar el mensaje después de unos segundos
        setTimeout(() => {
            alert.classList.add('d-none');
        }, 3000);
    }

    // Función para mostrar mensajes de error
    function showErrorMessage(message) {
        const alert = document.getElementById('errorAlert');
        document.getElementById('errorMessage').textContent = message;
        alert.classList.remove('d-none');

        // Ocultar el mensaje después de unos segundos
        setTimeout(() => {
            alert.classList.add('d-none');
        }, 3000);
    }

    // Inicializar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar atributos data-id a las filas existentes para simular IDs
        document.querySelectorAll('#institutionsTable tr').forEach((row, index) => {
            row.setAttribute('data-id', index + 1);
        });

        document.querySelectorAll('#programsTable tr').forEach((row, index) => {
            row.setAttribute('data-id', index + 1);
        });

        document.querySelectorAll('#subjectsTable tr').forEach((row, index) => {
            row.setAttribute('data-id', index + 1);
        });
    });
</script>
@endsection

@extends('admin.layouts.app')
@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel de administración</h1>
    </div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">
                <i class="fas fa-users"></i> Gestión de usuarios
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="institutions-tab" data-toggle="tab" href="#institutions" role="tab" aria-controls="institutions" aria-selected="false">
                <i class="fas fa-university"></i> Instituciones educativas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="programs-tab" data-toggle="tab" href="#programs" role="tab" aria-controls="programs" aria-selected="false">
                <i class="fas fa-graduation-cap"></i> Programas académicos
            </a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content" id="adminTabContent">
        <!-- User Management -->
        <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Gestión de Permisos</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-plus"></i> Nuevo usuario
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Juan Pérez</td>
                                    <td>juan.perez@ejemplo.com</td>
                                    <td>Coordinador</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>María López</td>
                                    <td>maria.lopez@ejemplo.com</td>
                                    <td>Vicerrector</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institutions Management -->
        <div class="tab-pane fade" id="institutions" role="tabpanel" aria-labelledby="institutions-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Instituciones de educación superior</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addInstitutionModal">
                        <i class="fas fa-plus"></i> Nueva institución
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="institutionsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código SNIES</th>
                                    <th>País</th>
                                    <th>Departamento</th>
                                    <th>Ciudad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Corporacion universitaria Autonóma del Cauca</td>
                                    <td>1101</td>
                                    <td>Colombia</td>
                                    <td>Cauca</td>
                                    <td>Popayán</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Universidad de Antioquia</td>
                                    <td>1201</td>
                                    <td>Colombia</td>
                                    <td>Antioquia</td>
                                    <td>Medellín</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programs Management -->
        <div class="tab-pane fade" id="programs" role="tabpanel" aria-labelledby="programs-tab">
            <div class="card shadow mb-4 mt-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Programas Académicos</h6>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProgramModal">
                        <i class="fas fa-plus"></i> Nuevo Programa
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="programsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código ANIS</th>
                                    <th>Facultad</th>
                                    <th>Tipo de Formación</th>
                                    <th>Créditos</th>
                                    <th>Metodología</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ingeniería de Sistemas</td>
                                    <td>2310</td>
                                    <td>Ingeniería</td>
                                    <td>Profesional</td>
                                    <td>170</td>
                                    <td>Presencial</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Psicología</td>
                                    <td>3150</td>
                                    <td>Ciencias Humanas</td>
                                    <td>Profesional</td>
                                    <td>160</td>
                                    <td>Híbrido</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Nuevo usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    <div class="form-group">
                        <label for="userName">Nombre Completo</label>
                        <input type="text" class="form-control" id="userName" required>
                        <div class="invalid-feedback">Por favor ingrese un nombre completo.</div>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Correo Electrónico</label>
                        <input type="email" class="form-control" id="userEmail" required>
                        <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
                    </div>
                    <div class="form-group">
                        <label for="userPassword">Contraseña</label>
                        <input type="password" class="form-control" id="userPassword" required>
                        <div class="invalid-feedback">Por favor ingrese una contraseña.</div>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Rol</label>
                        <select class="form-control" id="userRole" required>
                            <option value="">Seleccione un rol</option>
                            <option value="admin">Administrador</option>
                            <option value="coordinator">Coordinador</option>
                            <option value="vicerector">Vicerrector</option>
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un rol.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Institution Modal -->
<div class="modal fade" id="addInstitutionModal" tabindex="-1" role="dialog" aria-labelledby="addInstitutionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInstitutionModalLabel">Nueva Institución</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="institutionForm">
                    <div class="form-group">
                        <label for="institutionName">Nombre de la Institución</label>
                        <input type="text" class="form-control" id="institutionName" required>
                        <div class="invalid-feedback">Por favor ingrese el nombre de la institución.</div>
                    </div>
                    <div class="form-group">
                        <label for="institutionCode">Código SNIES</label>
                        <input type="text" class="form-control" id="institutionCode" required>
                        <div class="invalid-feedback">Por favor ingrese el código SNIES.</div>
                    </div>
                    <div class="form-group">
                        <label for="institutionCountry">País</label>
                        <select class="form-control" id="institutionCountry" required>
                            <option value="">Seleccione un país</option>
                            <option value="colombia">Colombia</option>
                            <option value="venezuela">Venezuela</option>
                            <option value="peru">Perú</option>
                            <!-- Add more countries as needed -->
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un país.</div>
                    </div>
                    <div class="form-group">
                        <label for="institutionDepartment">Departamento</label>
                        <select class="form-control" id="institutionDepartment" required>
                            <option value="">Seleccione un departamento</option>
                            <option value="cauca">Cauca</option>
                            <option value="antioquia">Antioquia</option>
                            <option value="valle">Valle del Cauca</option>
                            <!-- Add more departments as needed -->
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un departamento.</div>
                    </div>
                    <div class="form-group">
                        <label for="institutionCity">Ciudad</label>
                        <select class="form-control" id="institutionCity" required>
                            <option value="">Seleccione una ciudad</option>
                            <option value="bogota">Bogotá</option>
                            <option value="popayan">Popayán</option>
                            <option value="cali">Cali</option>
                            <!-- Add more cities as needed -->
                        </select>
                        <div class="invalid-feedback">Por favor seleccione una ciudad.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveInstitutionBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramModalLabel">Nuevo Programa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="programForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="programName">Nombre del Programa</label>
                                <input type="text" class="form-control" id="programName" required>
                                <div class="invalid-feedback">Por favor ingrese el nombre del programa.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="programCode">Código ANIS</label>
                                <input type="text" class="form-control" id="programCode" required>
                                <div class="invalid-feedback">Por favor ingrese el código ANIS.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="programFaculty">Facultad</label>
                                <select class="form-control" id="programFaculty" required>
                                    <option value="">Seleccione una facultad</option>
                                    <option value="ingenieria">Ingeniería</option>
                                    <option value="ciencias">Ciencias</option>
                                    <option value="humanidades">Humanidades</option>
                                    <option value="salud">Ciencias de la Salud</option>
                                    <!-- Add more faculties as needed -->
                                </select>
                                <div class="invalid-feedback">Por favor seleccione una facultad.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="programFormationType">Tipo de Formación</label>
                                <select class="form-control" id="programFormationType" required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="tecnico">Técnico Profesional</option>
                                    <option value="tecnologico">Tecnológico</option>
                                    <option value="profesional">Profesional</option>
                                    <option value="especializacion">Especialización</option>
                                    <option value="maestria">Maestría</option>
                                    <option value="doctorado">Doctorado</option>
                                    <!-- Add more types as needed -->
                                </select>
                                <div class="invalid-feedback">Por favor seleccione un tipo de formación.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="programCredits">Total de Créditos</label>
                                <input type="number" class="form-control" id="programCredits" required>
                                <div class="invalid-feedback">Por favor ingrese el total de créditos.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="programHours">Horas Presenciales</label>
                                <input type="number" class="form-control" id="programHours" required>
                                <div class="invalid-feedback">Por favor ingrese las horas presenciales.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="programDuration">Duración (semestres)</label>
                                <input type="number" class="form-control" id="programDuration" required>
                                <div class="invalid-feedback">Por favor ingrese la duración en semestres.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="programType">Tipo</label>
                                <select class="form-control" id="programType" required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="teorico">Teórico</option>
                                    <option value="practico">Práctico</option>
                                    <option value="teorico-practico">Teórico-Práctico</option>
                                </select>
                                <div class="invalid-feedback">Por favor seleccione un tipo.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="programMethodology">Metodología</label>
                                <select class="form-control" id="programMethodology" required>
                                    <option value="">Seleccione una metodología</option>
                                    <option value="presencial">Presencial</option>
                                    <option value="virtual">Virtual</option>
                                    <option value="hibrido">Híbrido</option>
                                </select>
                                <div class="invalid-feedback">Por favor seleccione una metodología.</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveProgramBtn">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modals -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Error</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Por favor complete todos los campos requeridos antes de continuar.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle"></i> Éxito</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>La información ha sido guardada exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#usersTable').DataTable();
        $('#institutionsTable').DataTable();
        $('#programsTable').DataTable();

        // Activate tab based on hash in URL if present
        let hash = window.location.hash;
        if (hash) {
            $('.nav-tabs a[href="' + hash + '"]').tab('show');
        }

        // Update hash on tab change
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });

        // Validate User Form
        $('#saveUserBtn').click(function() {
            validateForm('userForm');
        });

        // Validate Institution Form
        $('#saveInstitutionBtn').click(function() {
            validateForm('institutionForm');
        });

        // Validate Program Form
        $('#saveProgramBtn').click(function() {
            validateForm('programForm');
        });

        // Function to validate forms
        function validateForm(formId) {
            const form = document.getElementById(formId);
            let isValid = true;

            // Check each required field
            $(form).find('[required]').each(function() {
                if (!this.value) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Check email format for user form
            if (formId === 'userForm' && $('#userEmail').val()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test($('#userEmail').val())) {
                    $('#userEmail').addClass('is-invalid');
                    isValid = false;
                }
            }

            // Show error modal if form is invalid
            if (!isValid) {
                $('#errorModal').modal('show');
                return false;
            }

            // If valid, show success message and reset form
            $('#successModal').modal('show');

            // Reset form and close modal after success
            setTimeout(function() {
                $(form).trigger('reset');
                $(`#add${formId.replace('Form', '')}Modal`).modal('hide');

                // Add the new entry to the table (this would typically be done through AJAX)
                if (formId === 'userForm') {
                    addUserToTable();
                } else if (formId === 'institutionForm') {
                    addInstitutionToTable();
                } else if (formId === 'programForm') {
                    addProgramToTable();
                }

                $('#successModal').modal('hide');
            }, 1500);
        }

        // Demo functions to add new entries to tables
        function addUserToTable() {
            const name = $('#userName').val();
            const email = $('#userEmail').val();
            const role = $('#userRole option:selected').text();

            $('#usersTable tbody').prepend(`
                <tr>
                    <td>${name}</td>
                    <td>${email}</td>
                    <td>${role}</td>
                    <td>
                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        }

        function addInstitutionToTable() {
            const name = $('#institutionName').val();
            const code = $('#institutionCode').val();
            const country = $('#institutionCountry option:selected').text();
            const department = $('#institutionDepartment option:selected').text();
            const city = $('#institutionCity option:selected').text();

            $('#institutionsTable tbody').prepend(`
                <tr>
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>${country}</td>
                    <td>${department}</td>
                    <td>${city}</td>
                    <td>
                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        }

        function addProgramToTable() {
            const name = $('#programName').val();
            const code = $('#programCode').val();
            const faculty = $('#programFaculty option:selected').text();
            const formationType = $('#programFormationType option:selected').text();
            const credits = $('#programCredits').val();
            const methodology = $('#programMethodology option:selected').text();

            $('#programsTable tbody').prepend(`
                <tr>
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>${faculty}</td>
                    <td>${formationType}</td>
                    <td>${credits}</td>
                    <td>${methodology}</td>
                    <td>
                        <button class="btn btn-info btn-sm"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        }

        // Add event listeners to clear validation errors when users start typing
        $('input, select').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
    });
</script>
@endsection

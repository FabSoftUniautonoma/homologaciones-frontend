@extends('admin.layouts.appadmin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Crear Institución, Programa y Asignaturas</h3>
                    </div>
                    <div class="card-body">
                        <!-- Progress Bar -->
                        <div class="progress mb-4">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
                                Paso 1 de 3
                            </div>
                        </div>

                        <!-- Paso 1: Institución -->
                        <div id="step1" class="step active">
                            <h4 class="mb-3">Paso 1: Información de la Institución</h4>
                            <form id="formInstitucion">
                                <div class="form-group mb-3">
                                    <label for="nombre_institucion">Nombre de la Institución</label>
                                    <input type="text" class="form-control" id="nombre_institucion" required>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="pais">País</label>
                                        <select class="form-select custom-select" id="pais" required>
                                            <option value="" disabled>Seleccione un país</option>
                                            <option value="1" selected>Colombia</option>
                                            <option value="2">Otro</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="departamento">Departamento</label>
                                        <select class="form-select custom-select" id="departamento" required>
                                            <option value="" disabled>Seleccione un departamento</option>
                                            <!-- Se cargará dinámicamente -->
                                        </select>
                                        <input type="text" class="form-control mt-2 hidden" id="otro_departamento" placeholder="Ingrese departamento">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="municipio">Municipio</label>
                                        <select class="form-select custom-select" id="municipio" required>
                                            <option value="" disabled>Seleccione un municipio</option>
                                            <!-- Se cargará dinámicamente -->
                                        </select>
                                        <input type="text" class="form-control mt-2 hidden" id="otro_municipio" placeholder="Ingrese municipio">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tipo_institucion">Tipo de Institución</label>
                                    <select class="form-select custom-select" id="tipo_institucion" required>
                                        <option value="">Seleccione el tipo</option>
                                        <option value="Mixta" selected>Mixta</option>
                                        <option value="Universitaria">Universitaria</option>
                                        <option value="SENA">SENA</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="codigo_ies">Código IES</label>
                                    <input type="text" class="form-control" id="codigo_ies">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <button type="button" class="btn btn-primary float-end" onclick="nextStep(1)">
                                    Continuar con programa <i class="fas fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Paso 2: Programa -->
                        <div id="step2" class="step">
                            <h4 class="mb-3">Paso 2: Información del Programa</h4>
                            <form id="formPrograma">
                                <div class="form-group mb-3">
                                    <label for="institucion_seleccionada">Institución</label>
                                    <select class="form-select custom-select" id="institucion_seleccionada" required>
                                        <option value="">Cargando instituciones...</option>
                                        <!-- Se cargarán todas las instituciones desde la API -->
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="nombre_programa">Nombre del Programa</label>
                                    <input type="text" class="form-control" id="nombre_programa" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tipo_formacion">Tipo de Formación</label>
                                    <select class="form-select custom-select" id="tipo_formacion" required>
                                        <option value="">Seleccione el tipo</option>
                                        <option value="Profesional" selected>Profesional</option>
                                        <option value="Técnico">Técnico</option>
                                        <option value="Tecnólogo">Tecnólogo</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="metodologia">Metodología</label>
                                    <select class="form-select custom-select" id="metodologia" required>
                                        <option value="">Seleccione la metodología</option>
                                        <option value="Presencial" selected>Presencial</option>
                                        <option value="Virtual">Virtual</option>
                                        <option value="Híbrido">Híbrido</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="codigo_snies">Código SNIES</label>
                                    <input type="text" class="form-control" id="codigo_snies">
                                </div>

                                <button type="button" class="btn btn-secondary float-start" onclick="previousStep(2)">
                                    <i class="fas fa-arrow-left"></i> Atrás
                                </button>
                                <button type="button" class="btn btn-primary float-end" onclick="nextStep(2)">
                                    Continuar con asignaturas <i class="fas fa-arrow-right"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Paso 3: Asignaturas -->
                        <div id="step3" class="step">
                            <h4 class="mb-3">Paso 3: Asignaturas</h4>

                            <div class="form-group mb-3">
                                <label for="institucion_asignatura">Institución</label>
                                <select class="form-select custom-select" id="institucion_asignatura" required>
                                    <option value="">Seleccione una institución</option>
                                    <!-- Se cargarán todas las instituciones desde la API -->
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="programa_asignatura">Programa</label>
                                <select class="form-select custom-select" id="programa_asignatura" required>
                                    <option value="">Seleccione primero una institución</option>
                                    <!-- Se cargarán los programas según la institución seleccionada -->
                                </select>
                            </div>

                            <div id="asignaturas-container">
                                <div class="asignatura-form mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <form class="formAsignatura">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label>Nombre de la Asignatura</label>
                                                        <input type="text" class="form-control nombre_asignatura"
                                                            required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Tipo de Competencia</label>
                                                        <select class="form-select custom-select tipo_asignatura" required>
                                                            <option value="">Seleccione el tipo</option>
                                                            <option value="Básica" selected>Básica</option>
                                                            <option value="Específica">Específica</option>
                                                            <option value="Optativa">Optativa</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Código Asignatura</label>
                                                        <input type="text" class="form-control codigo_asignatura">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Número de Créditos</label>
                                                        <input type="number" class="form-control creditos" value="3" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Semestre</label>
                                                        <input type="number" class="form-control semestre" value="1" required>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Tiempo Presencial</label>
                                                        <input type="number" class="form-control tiempo_presencial" value="4"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Tiempo Independiente</label>
                                                        <input type="number" class="form-control tiempo_independiente" value="8"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Horas Totales Semanales</label>
                                                        <input type="number" class="form-control horas_totales_semanales" value="12"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label>Contenido Programático de la Asignatura</label>
                                                    <textarea class="form-control pensum" rows="3" required>Contenido programático de la asignatura</textarea>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success mb-3" onclick="agregarAsignatura()">
                                <i class="fas fa-plus"></i> Agregar más asignaturas
                            </button>

                            <button type="button" class="btn btn-secondary float-start" onclick="previousStep(3)">
                                <i class="fas fa-arrow-left"></i> Atrás
                            </button>
                            <button type="button" class="btn btn-primary float-end" onclick="guardarTodo()">
                                Guardar Todo <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* Estilos generales */
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .asignatura-form {
            position: relative;
        }

        .remove-asignatura {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .hidden {
            display: none;
        }

        /* Estilos específicos para selectores */
        .custom-select {
            display: block !important;
            width: 100% !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 1rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
            color: #212529 !important;
            background-color: #fff !important;
            background-clip: padding-box !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.25rem !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
            appearance: auto !important;
            -webkit-appearance: auto !important;
        }

        /* Garantizar que siempre se muestren en negro */
        .form-control,
        .form-select,
        .custom-select,
        .custom-select option {
            color: black !important;
            background-color: white !important;
        }

        /* Sobrescribir cualquier estilo oscuro */
        .dark-mode .form-control,
        .dark-mode .form-select,
        .dark-mode .custom-select,
        .dark-mode .custom-select option {
            color: black !important;
            background-color: white !important;
        }

        /* Notificaciones */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            display: none;
        }

        .notification.success {
            background-color: #4CAF50;
        }

        .notification.error {
            background-color: #f44336;
        }
    </style>

    <div id="notification" class="notification"></div>

    <script>
        // Variables para almacenar los IDs creados
        let institucionId = null;
        let programaId = null;

        // Datos precargados para Colombia
        const departamentosColombia = [
            { id: 1, nombre: "Antioquia" },
            { id: 2, nombre: "Atlántico" },
            { id: 3, nombre: "Bogotá D.C." },
            { id: 4, nombre: "Bolívar" },
            { id: 5, nombre: "Boyacá" },
            { id: 6, nombre: "Caldas" },
            { id: 7, nombre: "Caquetá" },
            { id: 8, nombre: "Cauca" },
            { id: 9, nombre: "Cesar" },
            { id: 10, nombre: "Córdoba" },
            { id: 11, nombre: "Cundinamarca" },
            { id: 12, nombre: "Chocó" },
            { id: 13, nombre: "Huila" },
            { id: 14, nombre: "La Guajira" },
            { id: 15, nombre: "Magdalena" },
            { id: 16, nombre: "Meta" },
            { id: 17, nombre: "Nariño" },
            { id: 18, nombre: "Norte de Santander" },
            { id: 19, nombre: "Quindío" },
            { id: 20, nombre: "Risaralda" },
            { id: 21, nombre: "Santander" },
            { id: 22, nombre: "Sucre" },
            { id: 23, nombre: "Tolima" },
            { id: 24, nombre: "Valle del Cauca" },
            { id: 25, nombre: "Arauca" },
            { id: 26, nombre: "Casanare" },
            { id: 27, nombre: "Putumayo" },
            { id: 28, nombre: "San Andrés y Providencia" },
            { id: 29, nombre: "Amazonas" },
            { id: 30, nombre: "Guainía" },
            { id: 31, nombre: "Guaviare" },
            { id: 32, nombre: "Vaupés" },
            { id: 33, nombre: "Vichada" }
        ];

        // Mapa de municipios por departamento (algunos ejemplos por departamento)
        const municipiosPorDepartamento = {
            1: [ // Antioquia
                { id: 1, nombre: "Medellín" },
                { id: 2, nombre: "Bello" },
                { id: 3, nombre: "Envigado" },
                { id: 4, nombre: "Itagüí" },
                { id: 5, nombre: "Rionegro" }
            ],
            2: [ // Atlántico
                { id: 6, nombre: "Barranquilla" },
                { id: 7, nombre: "Soledad" },
                { id: 8, nombre: "Malambo" }
            ],
            3: [ // Bogotá D.C.
                { id: 9, nombre: "Bogotá" }
            ],
            8: [ // Cauca
                { id: 10, nombre: "Popayán" },
                { id: 11, nombre: "Santander de Quilichao" },
                { id: 12, nombre: "Puerto Tejada" }
            ]
            // Agregar más departamentos según sea necesario
        };

        // Map para convertir valores de tipo de asignatura
        const tipoAsignaturaMap = {
            'Básica': 'B',
            'Específica': 'E',
            'Optativa': 'O'
        };

        // Función para obtener el token CSRF
        function getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        }

        // Función para mostrar notificaciones
        function mostrarNotificacion(mensaje, tipo) {
            const notificacion = document.getElementById('notification');
            notificacion.textContent = mensaje;
            notificacion.className = 'notification ' + tipo;
            notificacion.style.display = 'block';

            setTimeout(() => {
                notificacion.style.display = 'none';
            }, 3000);
        }

        // Función para cambiar de paso
        function changeStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + step).classList.add('active');

            const progress = step === 1 ? 33 : step === 2 ? 66 : 100;
            document.querySelector('.progress-bar').style.width = progress + '%';
            document.querySelector('.progress-bar').textContent = `Paso ${step} de 3`;

            // Si avanzamos al paso 2, cargar las instituciones
            if (step === 2) {
                cargarInstituciones();
            }

            // Si avanzamos al paso 3, cargar las instituciones para asignaturas
            if (step === 3) {
                cargarInstitucionesParaAsignaturas();
            }
        }

        // Función para cargar todas las instituciones desde la API
        function cargarInstituciones() {
            const selectInstitucion = document.getElementById('institucion_seleccionada');
            selectInstitucion.innerHTML = '<option value="">Cargando instituciones...</option>';

            fetch('https://homologacionesback.educarenemociones.com/api/instituciones')
                .then(response => response.json())
                .then(instituciones => {
                    selectInstitucion.innerHTML = '<option value="">Seleccione una institución</option>';

                    instituciones.forEach(institucion => {
                        const option = document.createElement('option');
                        option.value = institucion.id_institucion;
                        option.textContent = institucion.nombre;
                        selectInstitucion.appendChild(option);
                    });

                    // Si ya tenemos una institucion seleccionada, seleccionarla
                    if (institucionId) {
                        selectInstitucion.value = institucionId;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar instituciones:', error);
                    selectInstitucion.innerHTML = '<option value="">Error al cargar instituciones</option>';
                    mostrarNotificacion('Error al cargar instituciones. Intente nuevamente.', 'error');
                });
        }

        // Función para cargar instituciones en el paso 3
        function cargarInstitucionesParaAsignaturas() {
            const selectInstitucion = document.getElementById('institucion_asignatura');
            selectInstitucion.innerHTML = '<option value="">Cargando instituciones...</option>';

            fetch('https://homologacionesback.educarenemociones.com/api/instituciones')
                .then(response => response.json())
                .then(instituciones => {
                    selectInstitucion.innerHTML = '<option value="">Seleccione una institución</option>';

                    instituciones.forEach(institucion => {
                        const option = document.createElement('option');
                        option.value = institucion.id_institucion;
                        option.textContent = institucion.nombre;
                        selectInstitucion.appendChild(option);
                    });

                    // Si ya tenemos una institucion seleccionada, seleccionarla
                    if (institucionId) {
                        selectInstitucion.value = institucionId;
                        // Cargar los programas asociados a esta institución
                        cargarProgramasPorInstitucion(institucionId);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar instituciones:', error);
                    selectInstitucion.innerHTML = '<option value="">Error al cargar instituciones</option>';
                });
        }

        // Función para cargar programas por institución
        function cargarProgramasPorInstitucion(institucionId) {
            if (!institucionId) return;

            const selectPrograma = document.getElementById('programa_asignatura');
            selectPrograma.innerHTML = '<option value="">Cargando programas...</option>';

            fetch(`https://homologacionesback.educarenemociones.com/api/programas/institucion/${institucionId}`)
                .then(response => response.json())
                .then(programas => {
                    selectPrograma.innerHTML = '<option value="">Seleccione un programa</option>';

                    programas.forEach(programa => {
                        const option = document.createElement('option');
                        option.value = programa.id_programa;
                        option.textContent = programa.nombre;
                        selectPrograma.appendChild(option);
                    });

                    // Si ya tenemos un programa seleccionado, seleccionarlo
                    if (programaId) {
                        selectPrograma.value = programaId;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar programas:', error);
                    selectPrograma.innerHTML = '<option value="">Error al cargar programas</option>';
                });
        }

        // Cargar departamentos para Colombia
        function cargarDepartamentosColombia() {
            const selectDepartamento = document.getElementById('departamento');
            selectDepartamento.innerHTML = '<option value="" disabled>Seleccione un departamento</option>';

            departamentosColombia.forEach(depto => {
                const option = document.createElement('option');
                option.value = depto.id;
                option.textContent = depto.nombre;
                selectDepartamento.appendChild(option);
            });

            // Seleccionar Cauca (id: 8) por defecto, ya que estamos en Popayán
            selectDepartamento.value = 8;

            // Cargar municipios del departamento seleccionado
            cargarMunicipiosPorDepartamento(8);
        }

        // Cargar municipios por departamento
        function cargarMunicipiosPorDepartamento(departamentoId) {
            const selectMunicipio = document.getElementById('municipio');
            selectMunicipio.innerHTML = '<option value="" disabled>Seleccione un municipio</option>';

            if (municipiosPorDepartamento[departamentoId]) {
                municipiosPorDepartamento[departamentoId].forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio.id;
                    option.textContent = municipio.nombre;
                    selectMunicipio.appendChild(option);
                });

                // Si es Cauca (id: 8), seleccionar Popayán por defecto
                if (departamentoId == 8) {
                    selectMunicipio.value = 10; // ID de Popayán
                } else if (municipiosPorDepartamento[departamentoId].length > 0) {
                    selectMunicipio.value = municipiosPorDepartamento[departamentoId][0].id;
                }
            }
        }

        // Función para validar formulario
        function validateForm(form) {
            let valid = true;
            form.querySelectorAll('[required]').forEach(field => {
                if (!field.value) {
                    field.classList.add('is-invalid');
                    valid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            return valid;
        }

        // Función para siguiente paso - MODIFICADA para permitir avanzar sin completar todos los campos
        function nextStep(currentStep) {
            // Nueva política: avanzamos al siguiente paso sin validar todos los campos

            if (currentStep === 1) {
                // Si hay campos completados, intentar guardar la institución
                const nombre = document.getElementById('nombre_institucion').value.trim();
                if (nombre) {
                    guardarInstitucionParcial();
                }
                // Avanzar al siguiente paso de todas formas
                changeStep(2);
            } else if (currentStep === 2) {
                // Si hay campos completados, intentar guardar el programa
                const nombrePrograma = document.getElementById('nombre_programa').value.trim();
                if (nombrePrograma) {
                    guardarProgramaParcial();
                }
                // Avanzar al siguiente paso de todas formas
                changeStep(3);
            }
        }

        // Función para paso anterior
        function previousStep(currentStep) {
            changeStep(currentStep - 1);
        }

        // Función para guardar institución de forma parcial
        function guardarInstitucionParcial() {
            const nombre = document.getElementById('nombre_institucion').value.trim();
            const municipioId = document.getElementById('municipio').value;
            const tipo = document.getElementById('tipo_institucion').value;
            const codigoIes = document.getElementById('codigo_ies').value.trim();

            if (!nombre) return; // No guardar si al menos no hay nombre

            const data = {
                nombre: nombre,
                codigo_ies: codigoIes || '',
                municipio_id: municipioId,
                tipo: tipo || 'Mixta'
            };

            // Deshabilitar botón durante la petición
            const nextButton = document.querySelector('#step1 .btn-primary');
            const originalText = nextButton.innerHTML;
            nextButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            nextButton.disabled = true;

            fetch('https://homologacionesback.educarenemociones.com/api/instituciones', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            throw new Error(`Error HTTP: ${response.status}, ${text}`);
                        }
                    });
                }
                return response.json();
            })
            .then(result => {
                nextButton.innerHTML = originalText;
                nextButton.disabled = false;

                if (result.mensaje === 'Institución insertada correctamente') {
                    if (result.id_institucion) {
                        institucionId = result.id_institucion;
                        mostrarNotificacion('Institución guardada correctamente', 'success');
                    }
                } else if (result.error && result.error.includes('already been taken')) {
                    mostrarNotificacion('Este código IES ya está registrado', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                nextButton.innerHTML = originalText;
                nextButton.disabled = false;
                mostrarNotificacion('Error al guardar institución', 'error');
            });
        }

        // Función para guardar programa de forma parcial
        function guardarProgramaParcial() {
            const nombrePrograma = document.getElementById('nombre_programa').value.trim();
            if (!nombrePrograma) return; // No guardar si al menos no hay nombre

            // Obtener institución seleccionada
const institucionSeleccionada = document.getElementById('institucion_seleccionada').value;
// Si no hay institución seleccionada, usar la creada anteriormente o mostrar error
const instId = institucionSeleccionada || institucionId;

if (!instId) {
    mostrarNotificacion('No hay institución seleccionada para el programa', 'error');
    return;
}

const data = {
    institucion_id: parseInt(instId),
    facultad_id: null,
    nombre: nombrePrograma,
    codigo_snies: document.getElementById('codigo_snies').value.trim() || '',
    tipo_formacion: document.getElementById('tipo_formacion').value || 'Profesional',
    metodologia: document.getElementById('metodologia').value || 'Presencial'
};

// Deshabilitar botón durante la petición
const nextButton = document.querySelector('#step2 .btn-primary');
const originalText = nextButton.innerHTML;
nextButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
nextButton.disabled = true;

fetch('https://homologacionesback.educarenemociones.com/api/programas', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
        'Accept': 'application/json'
    },
    body: JSON.stringify(data)
})
.then(response => {
    if (!response.ok) {
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error(`Error HTTP: ${response.status}, ${text}`);
            }
        });
    }
    return response.json();
})
.then(result => {
    nextButton.innerHTML = originalText;
    nextButton.disabled = false;

    if (result.mensaje === 'Programa insertado correctamente') {
        if (result.id_programa) {
            programaId = result.id_programa;
            institucionId = instId; // Actualizar institucionId con la seleccionada
            mostrarNotificacion('Programa guardado correctamente', 'success');
        }
    }
})
.catch(error => {
    console.error('Error:', error);
    nextButton.innerHTML = originalText;
    nextButton.disabled = false;
    mostrarNotificacion('Error al guardar programa', 'error');
});
}

// Función para agregar asignatura
function agregarAsignatura() {
    const container = document.getElementById('asignaturas-container');
    const newAsignatura = document.querySelector('.asignatura-form').cloneNode(true);

    // Limpiar campos
    newAsignatura.querySelectorAll('input, select, textarea').forEach(field => {
        // Mantener valores por defecto para campos numéricos
        if (field.type === 'number') {
            // No limpiar, mantener los valores originales
        } else if (field.tagName === 'SELECT') {
            // Mantener selección por defecto en selects
        } else {
            field.value = '';
        }
    });

    // Agregar botón de eliminar
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-danger btn-sm remove-asignatura';
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.onclick = function() {
        newAsignatura.remove();
    };
    newAsignatura.querySelector('.card-body').appendChild(removeBtn);

    container.appendChild(newAsignatura);
}

// Función para guardar asignaturas con contenido programático
function guardarAsignaturas() {
    // Obtener el programa seleccionado en el paso 3
    const programaSeleccionado = document.getElementById('programa_asignatura').value;
    // Si no hay programa seleccionado, usar el creado anteriormente o mostrar error
    const progId = programaSeleccionado || programaId;

    if (!progId) {
        mostrarNotificacion('No hay programa seleccionado para las asignaturas', 'error');
        return Promise.reject('No hay programa ID');
    }

    const asignaturasFormsCollection = document.querySelectorAll('.formAsignatura');
    let promises = [];

    asignaturasFormsCollection.forEach((form, index) => {
        if (!validateForm(form)) return;

        // Obtener el tipo de asignatura
        const tipoDisplay = form.querySelector('.tipo_asignatura').value;
        // Mapear al valor de una sola letra que espera el backend
        const tipo = tipoAsignaturaMap[tipoDisplay] || 'B';

        // Asegurarnos que los campos numéricos tengan valores adecuados
        const creditos = parseInt(form.querySelector('.creditos').value) || 0;
        const semestre = parseInt(form.querySelector('.semestre').value) || 1;
        const tiempo_presencial = parseInt(form.querySelector('.tiempo_presencial').value) || 0;
        const tiempo_independiente = parseInt(form.querySelector('.tiempo_independiente').value) || 0;
        const horas_totales = parseInt(form.querySelector('.horas_totales_semanales').value) || 0;

        const nombreAsignatura = form.querySelector('.nombre_asignatura').value.trim();
        const pensum = form.querySelector('.pensum').value.trim();
        const codigoAsignatura = form.querySelector('.codigo_asignatura').value.trim() || '';

        // Datos formateados correctamente para la API
        const asignaturaData = {
            programa_id: parseInt(progId),
            nombre: nombreAsignatura,
            tipo: tipo, // Ahora usando el valor mapeado (B, E, O)
            codigo_asignatura: codigoAsignatura,
            creditos: creditos,
            semestre: semestre,
            horas_sena: 0,
            tiempo_presencial: tiempo_presencial,
            tiempo_independiente: tiempo_independiente,
            horas_totales_semanales: horas_totales,
            modalidad: 'Regular',
            metodologia: document.getElementById('metodologia').value || 'Presencial'
        };

        console.log(`Datos formateados para API asignatura ${index + 1}:`, asignaturaData);

        const promise = new Promise((resolve, reject) => {
            // Paso 1: Guardar la asignatura
            fetch('https://homologacionesback.educarenemociones.com/api/asignaturas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(asignaturaData)
            })
            .then(response => {
                console.log(`Status de respuesta asignatura ${index + 1}:`, response.status);

                if (!response.ok) {
                    return response.text().then(text => {
                        console.error(`Error en asignatura ${index + 1}:`, text);
                        throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                    });
                }
                return response.json();
            })
            .then(result => {
                console.log(`Respuesta asignatura ${index + 1}:`, result);

                if (result.mensaje === 'Asignatura insertada correctamente') {
                    // MODIFICACIÓN: En lugar de buscar la asignatura, obtenemos el ID directamente o generamos uno temporal
                    let asignaturaId;

                    if (result.id_asignatura) {
                        // Si la API devuelve el ID directamente (mejor caso)
                        asignaturaId = result.id_asignatura;
                        console.log(`ID obtenido directamente para asignatura ${index + 1}:`, asignaturaId);
                    } else {
                        // Generamos un ID temporal para completar el proceso
                        asignaturaId = new Date().getTime() + index;
                        console.warn(`Usando ID temporal para asignatura ${index + 1}:`, asignaturaId);
                    }

                    console.log(
                        `Guardando contenido programático para asignatura ${index + 1} (ID: ${asignaturaId}):`,
                        pensum);

                    // Datos para el contenido programático - asegurar que todos son strings
                    const contenidoData = {
                        asignatura_id: parseInt(asignaturaId),
                        tema: nombreAsignatura.toString(),
                        resultados_aprendizaje: pensum.toString(),
                        descripcion: pensum.toString()
                    }
                    // Guardar contenido programático
return fetch(
    'https://homologacionesback.educarenemociones.com/api/contenidos-programaticos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json'
        },
        body: JSON.stringify(contenidoData)
    })
.then(response => {
    if (!response.ok) {
        return response.text().then(text => {
            console.error(
                `Error al guardar contenido programático para asignatura ${asignaturaId}:`,
                text);
            throw new Error(
                `Error al guardar contenido programático: ${text}`
            );
        });
    }
    return response.json();
})
.then(contenidoResult => {
    console.log(
        `Contenido programático guardado para asignatura ${asignaturaId}:`,
        contenidoResult);

    // Devolvemos información sobre la asignatura guardada
    resolve({
        nombre: nombreAsignatura,
        id: asignaturaId,
        contenidoGuardado: true
    });
})
.catch(error => {
    console.error("Error al guardar contenido programático:", error);
    // Aún si falla el contenido programático, consideramos la asignatura guardada
    resolve({
        nombre: nombreAsignatura,
        id: asignaturaId,
        contenidoGuardado: false,
        error: error.message
    });
});
                } else {
                    const error = new Error('Error: ' + result.mensaje);
                    console.error(error);
                    reject(error);
                }
            })
            .catch(error => {
                console.error("Error en el proceso de guardar asignatura:", error);
                reject(error);
            });
        });

        promises.push(promise);
    });

    return Promise.all(promises).then(results => {
        console.log("Todas las asignaturas y contenidos programáticos guardados correctamente:", results);
        return results;
    });
}

// Función para guardar todo con mejor manejo de errores
function guardarTodo() {
    console.log('Iniciando proceso de guardado completo...');

    // Obtener institución y programa seleccionados en el paso 3
    const institucionSeleccionada = document.getElementById('institucion_asignatura').value;
    const programaSeleccionado = document.getElementById('programa_asignatura').value;

    // Actualizar IDs con las selecciones más recientes
    if (institucionSeleccionada) {
        institucionId = institucionSeleccionada;
    }

    if (programaSeleccionado) {
        programaId = programaSeleccionado;
    }

    // Verificamos que haya al menos una asignatura
    const asignaturaForms = document.querySelectorAll('.formAsignatura');
    if (asignaturaForms.length === 0) {
        alert('Debe agregar al menos una asignatura');
        return;
    }

    // Validamos cada formulario de asignatura
    let asignaturasValidas = true;
    asignaturaForms.forEach(form => {
        if (!validateForm(form)) {
            asignaturasValidas = false;
        }
    });

    if (!asignaturasValidas) {
        alert('Por favor complete todos los campos obligatorios en las asignaturas');
        return;
    }

    // Mostramos un mensaje de espera
    const saveButton = document.querySelector('#step3 .btn-primary');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    saveButton.disabled = true;

    // Verificar que tenemos IDs para continuar (o usar temporales si no)
    if (!institucionId) {
        console.warn('No hay ID de institución. Usando ID temporal.');
        institucionId = document.getElementById('municipio').value * 100 || new Date().getTime();
    }

    if (!programaId) {
        console.warn('No hay ID de programa. Usando ID temporal.');
        programaId = new Date().getTime();
    }

    console.log(`Guardando asignaturas para Institución ID: ${institucionId}, Programa ID: ${programaId}`);

    // Proceder directamente a guardar asignaturas
    guardarAsignaturas()
        .then((resultados) => {
            console.log('Todas las asignaturas guardadas exitosamente:', resultados);
            saveButton.innerHTML = '<i class="fas fa-check"></i> Guardado!';

            // Mensaje de éxito mostrando ID de institución y programa
            const mensaje = `¡Datos guardados exitosamente!\n\nInstitución ID: ${institucionId}\nPrograma ID: ${programaId}\nAsignaturas: ${resultados.length}`;
            alert(mensaje);
            mostrarNotificacion('Asignaturas guardadas correctamente', 'success');

            // Redirigir después de un breve retraso
            setTimeout(() => {
                window.location.href = '/admin/programas';
            }, 1000);
        })
        .catch(error => {
            console.error('Error completo en guardado:', error);
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
            mostrarNotificacion('Error al guardar asignaturas', 'error');

            // Incluso en caso de error, dar opción de continuar
            if (confirm('Hubo un problema al guardar las asignaturas: ' + error.message + '. ¿Desea intentarlo nuevamente?')) {
                guardarTodo();
            }
        });
}

// Función para obtener una institución por ID
function obtenerInstitucionPorId(id) {
    return fetch(`https://homologacionesback.educarenemociones.com/api/instituciones/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al obtener institución: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.datos) {
                return data.datos;
            } else {
                throw new Error('Formato de respuesta inesperado');
            }
        });
}

// Función para obtener un programa por ID
function obtenerProgramaPorId(id) {
    return fetch(`https://homologacionesback.educarenemociones.com/api/programas/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al obtener programa: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data && data.datos) {
                return data.datos;
            } else {
                throw new Error('Formato de respuesta inesperado');
            }
        });
}

// Función para obtener contenido programático de una asignatura
function obtenerContenidoPorAsignatura(asignaturaId) {
    return fetch(`https://homologacionesback.educarenemociones.com/api/contenidos-programaticos/${asignaturaId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al obtener contenido programático: ${response.status}`);
            }
            return response.json();
        });
}

// Función para calcular automáticamente las horas totales semanales
function calcularHorasTotales(form) {
    const tiempoPresencial = parseInt(form.querySelector('.tiempo_presencial').value) || 0;
    const tiempoIndependiente = parseInt(form.querySelector('.tiempo_independiente').value) || 0;
    form.querySelector('.horas_totales_semanales').value = tiempoPresencial + tiempoIndependiente;
}

// Inicialización al cargar el documento
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando formulario...');

    // Aplicar estilos adicionales a los selects para mejorar su visualización
    document.querySelectorAll('select').forEach(select => {
        select.style.color = 'black';
        select.style.backgroundColor = 'white';
        select.style.border = '1px solid #ced4da';
    });

    // Cargar departamentos de Colombia por defecto
    cargarDepartamentosColombia();

    // Event listener para cambio de país
    document.getElementById('pais').addEventListener('change', function() {
        const paisId = this.value;

        // Mostrar/ocultar campos alternativos según el país seleccionado
        if (paisId == 1) { // Colombia
            document.getElementById('departamento').classList.remove('hidden');
            document.getElementById('otro_departamento').classList.add('hidden');
            document.getElementById('municipio').classList.remove('hidden');
            document.getElementById('otro_municipio').classList.add('hidden');

            cargarDepartamentosColombia();
        } else {
            // Para otros países, mostrar campos de texto
            document.getElementById('departamento').classList.add('hidden');
            document.getElementById('otro_departamento').classList.remove('hidden');
            document.getElementById('municipio').classList.add('hidden');
            document.getElementById('otro_municipio').classList.remove('hidden');
        }
    });


   // Mejora del event listener para cambio de institución en paso 3
document.getElementById('institucion_asignatura').addEventListener('change', function() {
    const institucionId = this.value;
    if (institucionId) {
        cargarProgramasPorInstitucion(institucionId);
    } else {
        // Resetear el selector de programas si no hay institución seleccionada
        const selectPrograma = document.getElementById('programa_asignatura');
        selectPrograma.innerHTML = '<option value="">Seleccione primero una institución</option>';
    }
});
 document.getElementById('institucion_asignatura').addEventListener('change', function() {
        const institucionId = this.value;
        if (institucionId) {
            console.log('Institución seleccionada:', institucionId);
            cargarProgramasPorInstitucion(institucionId);
        } else {
            // Resetear el selector de programas si no hay institución seleccionada
            const selectPrograma = document.getElementById('programa_asignatura');
            selectPrograma.innerHTML = '<option value="">Seleccione primero una institución</option>';
        }
    });

    // Verificar si hay un programa seleccionado al iniciar el paso 3
    document.querySelector('#step2 .btn-primary').addEventListener('click', function() {
        setTimeout(() => {
            const institucionId = document.getElementById('institucion_asignatura').value;
            if (institucionId) {
                cargarProgramasPorInstitucion(institucionId);
            }
        }, 300);
    });

    // Pre-seleccionar institución y programa en paso 3 si tenemos esos valores
    document.querySelector('#step2 .btn-primary').addEventListener('click', function() {
        if (institucionId) {
            setTimeout(() => {
                const selectInstitucion = document.getElementById('institucion_asignatura');
                selectInstitucion.value = institucionId;
                cargarProgramasPorInstitucion(institucionId);

                if (programaId) {
                    setTimeout(() => {
                        const selectPrograma = document.getElementById('programa_asignatura');
                        selectPrograma.value = programaId;
                    }, 500);
                }
            }, 300);
        }
    });
});

// Función para obtener contenido programático por asignatura mejorada
function obtenerContenidoPorAsignatura(asignaturaId) {
    return fetch(`https://homologacionesback.educarenemociones.com/api/contenidos-programaticos/asignatura/${asignaturaId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al obtener contenido programático: ${response.status}`);
            }
            return response.json();
        });
}

// Modificación en la función guardarAsignaturas para asegurar que se usa el programa seleccionado
function guardarAsignaturas() {
    // Obtener el programa seleccionado en el paso 3
    const programaSeleccionado = document.getElementById('programa_asignatura').value;

    if (!programaSeleccionado) {
        mostrarNotificacion('Debe seleccionar un programa para las asignaturas', 'error');
        return Promise.reject('No hay programa seleccionado');
    }

    const progId = programaSeleccionado;
    console.log(`Guardando asignaturas para el programa ID: ${progId}`);

    const asignaturasFormsCollection = document.querySelectorAll('.formAsignatura');
    let promises = [];

    asignaturasFormsCollection.forEach((form, index) => {
        if (!validateForm(form)) {
            mostrarNotificacion('Complete todos los campos obligatorios', 'error');
            return;
        }

        // Obtener el tipo de asignatura
        const tipoDisplay = form.querySelector('.tipo_asignatura').value;
        // Mapear al valor de una sola letra que espera el backend
        const tipo = tipoAsignaturaMap[tipoDisplay] || 'B';

        // Asegurarnos que los campos numéricos tengan valores adecuados
        const creditos = parseInt(form.querySelector('.creditos').value) || 0;
        const semestre = parseInt(form.querySelector('.semestre').value) || 1;
        const tiempo_presencial = parseInt(form.querySelector('.tiempo_presencial').value) || 0;
        const tiempo_independiente = parseInt(form.querySelector('.tiempo_independiente').value) || 0;
        const horas_totales = parseInt(form.querySelector('.horas_totales_semanales').value) || 0;

        const nombreAsignatura = form.querySelector('.nombre_asignatura').value.trim();
        const pensum = form.querySelector('.pensum').value.trim();
        const codigoAsignatura = form.querySelector('.codigo_asignatura').value.trim() || '';

        // Datos formateados correctamente para la API
        const asignaturaData = {
            programa_id: parseInt(progId),
            nombre: nombreAsignatura,
            tipo: tipo, // Valor mapeado (B, E, O)
            codigo_asignatura: codigoAsignatura,
            creditos: creditos,
            semestre: semestre,
            horas_sena: 0,
            tiempo_presencial: tiempo_presencial,
            tiempo_independiente: tiempo_independiente,
            horas_totales_semanales: horas_totales,
            modalidad: 'Regular',
            metodologia: document.getElementById('metodologia').value || 'Presencial'
        };

        console.log(`Datos para asignatura ${index + 1}:`, asignaturaData);

        const promise = new Promise((resolve, reject) => {
            // Paso 1: Guardar la asignatura
            fetch('https://homologacionesback.educarenemociones.com/api/asignaturas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(asignaturaData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error(`Error en asignatura ${index + 1}:`, text);
                        throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
                    });
                }
                return response.json();
            })
            .then(result => {
                console.log(`Respuesta asignatura ${index + 1}:`, result);

                if (result.mensaje === 'Asignatura insertada correctamente') {
                    // Obtener el ID de la asignatura recién creada
                    let asignaturaId;

                    if (result.id_asignatura) {
                        asignaturaId = result.id_asignatura;
                        console.log(`ID obtenido para asignatura ${index + 1}:`, asignaturaId);
                    } else {
                        reject(new Error('No se recibió ID de asignatura'));
                        return;
                    }

                    // Datos para el contenido programático
                    const contenidoData = {
                        asignatura_id: parseInt(asignaturaId),
                        tema: nombreAsignatura.toString(),
                        resultados_aprendizaje: pensum.toString(),
                        descripcion: pensum.toString()
                    }

                    // Guardar contenido programático
                    return fetch('https://homologacionesback.educarenemociones.com/api/contenidos-programaticos', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(contenidoData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error(`Error al guardar contenido programático:`, text);
                                throw new Error(`Error al guardar contenido programático: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(contenidoResult => {
                        console.log(`Contenido programático guardado:`, contenidoResult);

                        resolve({
                            nombre: nombreAsignatura,
                            id: asignaturaId,
                            contenidoGuardado: true
                        });
                    });
                } else {
                    const error = new Error('Error: ' + (result.mensaje || 'Desconocido'));
                    console.error(error);
                    reject(error);
                }
            })
            .catch(error => {
                console.error("Error en el proceso:", error);
                reject(error);
            });
        });

        promises.push(promise);
    });

    return Promise.all(promises).then(results => {
        console.log("Todas las asignaturas guardadas correctamente:", results);
        return results;
    });
}

// Modificación de la función guardarTodo para mejor manejo de errores y UI
function guardarTodo() {
    console.log('Iniciando proceso de guardado...');

    // Verificamos que haya una institución y programa seleccionados
    const institucionSeleccionada = document.getElementById('institucion_asignatura').value;
    const programaSeleccionado = document.getElementById('programa_asignatura').value;

    if (!institucionSeleccionada) {
        mostrarNotificacion('Debe seleccionar una institución', 'error');
        return;
    }

    if (!programaSeleccionado) {
        mostrarNotificacion('Debe seleccionar un programa', 'error');
        return;
    }

    // Verificamos que haya al menos una asignatura
    const asignaturaForms = document.querySelectorAll('.formAsignatura');
    if (asignaturaForms.length === 0) {
        alert('Debe agregar al menos una asignatura');
        return;
    }

    // Validamos cada formulario de asignatura
    let asignaturasValidas = true;
    asignaturaForms.forEach(form => {
        if (!validateForm(form)) {
            asignaturasValidas = false;
        }
    });

    if (!asignaturasValidas) {
        alert('Por favor complete todos los campos obligatorios en las asignaturas');
        return;
    }

    // Mostramos un mensaje de espera
    const saveButton = document.querySelector('#step3 .btn-primary');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    saveButton.disabled = true;

    // Proceder a guardar asignaturas
    guardarAsignaturas()
        .then((resultados) => {
            console.log('Asignaturas guardadas exitosamente:', resultados);
            saveButton.innerHTML = '<i class="fas fa-check"></i> Guardado!';

            // Mensaje de éxito
            mostrarNotificacion(`${resultados.length} asignatura(s) guardada(s) correctamente`, 'success');

            // Redirigir después de un breve retraso
            setTimeout(() => {
                window.location.href = '/admin/programas';
            }, 2000);
        })
        .catch(error => {
            console.error('Error en guardado:', error);
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
            mostrarNotificacion('Error al guardar asignaturas: ' + error.message, 'error');
        });
}

</script>

@endsection

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Homologación - Universidad Autónoma del Cauca</title>
    <link href="{{ asset('css/estiloformularioaspirante.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<<<<<<< HEAD
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
=======
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script>
        // Validación de acceso
        const baseRoute = '/homologaciones-frontend/public';
        const token = localStorage.getItem('auth_token');
        const userData = localStorage.getItem('user_data');

        if (!token || !userData) {
            window.location.href = `${baseRoute}/auth/login`;
        } else {
            const user = JSON.parse(userData);
            if (user.rol_id !== 1) {
                const redirectMap = {
                    2: `${baseRoute}/coordinador/inicio`,
                    3: `${baseRoute}/administrador`,
                    default: `${baseRoute}/auth/login`
                };
                window.location.href = redirectMap[user.rol_id] || redirectMap.default;
            }
        }
    </script>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
</head>

<body>
    <header>
        <h1>Universidad Autónoma del Cauca</h1>
        <p>Proceso de Homologación Académica</p>
    </header>

    <div class="container">
        <!-- Barra de progreso -->
        <div class="progress-container">
            <div class="step active" data-step="1">
                <div class="step-icon"><i class="fa-solid fa-id-badge"></i></div>
                <div class="step-title">Datos personales</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-icon"><i class="fa-solid fa-landmark"></i></div>
                <div class="step-title">Universidad de Origen</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="step-title">Programa académico</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-icon"><i class="fa-solid fa-file-pdf"></i></div>
                <div class="step-title">Documentos</div>
            </div>
            <div class="step" data-step="5">
                <div class="step-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="step-title">Confirmación</div>
            </div>
        </div>

        <form id="homologacion-form">
<<<<<<< HEAD
            <!-- STEP 1: DATOS PERSONALES -->
            <div class="step-content active" id="step-1">
                <h2>Información personal</h2>

                <!-- Tipo de Identificación y Número -->
=======
            <!-- STEP 1: Información Personal -->
            <div class="step-content active" id="step-1">
                <h2>Información personal</h2>

>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                <div class="row">
                    <div class="form-group">
                        <label>Tipo de identificación:</label>
                        <select id="tipo_identificacion" required>
                            <option value="">Seleccione</option>
<<<<<<< HEAD
                            <option value="TI">Tarjeta de Identidad (TI)</option>
                            <option value="CC">Cédula de Ciudadanía (CC)</option>
                            <option value="TE">Tarjeta de Extranjería (TE)</option>
                        </select>
                        <span class="error-message"></span>
                    </div>

=======
                            <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                            <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                            <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                    <div class="form-group">
                        <label>Número de identificación:</label>
                        <input type="text" id="numero_identificacion" required>
                        <span class="error-message"></span>
<<<<<<< HEAD
                    </div>
                </div>

                <!-- Nombres -->
                <div class="row">
                    <div class="form-group">
                        <label>Primer nombre:</label>
                        <input type="text" id="primer_nombre" required>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label>Segundo nombre (Opcional):</label>
                        <input type="text" id="segundo_nombre">
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="row">
                    <div class="form-group">
                        <label>Primer apellido:</label>
                        <input type="text" id="primer_apellido" required>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label>Segundo apellido (Opcional):</label>
                        <input type="text" id="segundo_apellido">
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Correo electrónico:</label>
                    <input type="email" id="email" required>
                    <span class="error-message"></span>
                </div>

                <!-- Teléfono y direccion -->
                <div class="row">
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="tel" id="telefono" required>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" id="direccion" required>
                        <span class="error-message"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>País:</label>
                        <select id="pais" required disabled>
                            <option value="Colombia">Colombia</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Departamento:</label>
                        <select id="departamento" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['departamento'] }}">{{ $departamento['departamento'] }}</option>
                            @endforeach
                        </select>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label>Municipio:</label>
                        <select id="municipio" required disabled>
                            <option value="">Seleccione un Municipio</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="next-button" onclick="changeStep(1, event)">Siguiente</button>
                </div>
            </div>

            <!-- STEP 2: UNIVERSIDAD DE ORIGEN -->
            <div class="step-content" id="step-2">
                <h2>Instituto de educación superior de origen</h2>

                <div class="row">
                    <!-- Selección de País -->
                    <div class="form-group">
                        <label>País:</label>
                        <select id="pais_origen" class="form-control" required disabled>
                            <option value="Colombia">Colombia</option>
=======
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Primer nombre:</label>
                        <input type="text" id="primer_nombre" required>
                        <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label>Segundo nombre (Opcional):</label>
                        <input type="text" id="segundo_nombre">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Primer apellido:</label>
                        <input type="text" id="primer_apellido" required>
                        <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label>Segundo apellido (Opcional):</label>
                        <input type="text" id="segundo_apellido">
                    </div>
                </div>

                <div class="form-group">
                    <label>Correo electrónico:</label>
                    <input type="email" id="email" required>
                    <span class="error-message"></span>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="tel" id="telefono" required>
                        <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" id="direccion" required>
                        <span class="error-message"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>País:</label>
                        <select id="pais" required onchange="handlePaisChange()">
                            <option value="">Seleccione un país</option>
                            @foreach ($paises as $pais)
                                <option value="{{ $pais['id_pais'] }}">{{ $pais['pais'] }}</option>
                            @endforeach
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                        </select>
                        <span class="error-message"></span>
                    </div>
<<<<<<< HEAD

                    <!-- Selección de Departamento -->
                    <div class="form-group">
                        <label>Departamento:</label>
                        <select id="departamento_origen" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['departamento'] }}">{{ $departamento['departamento'] }}</option>
=======
                    <div class="form-group">
                        <label>Departamento:</label>
                        <select id="departamento" required onchange="updateMunicipios()" disabled>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento['departamento'] }}"
                                    data-id="{{ $departamento['id_departamento'] }}">
                                    {{ $departamento['departamento'] }}
                                </option>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                            @endforeach
                        </select>
                        <span class="error-message"></span>
                    </div>
<<<<<<< HEAD

                    <div class="form-group">
                        <label>Municipio:</label>
                        <select id="municipio_origen" required disabled>
=======
                    <div class="form-group">
                        <label>Municipio:</label>
                        <select id="municipio" required disabled>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                            <option value="">Seleccione un Municipio</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
                </div>

<<<<<<< HEAD
                <!-- Parte del formulario a modificar - Institución y Programa -->
                <div class="row">
                    <!-- Institución de origen -->
                    <div class="form-group">
                        <label>Institución de origen:</label>
                        <select id="institucion" class="form-control" required>
                            <option value="">Seleccione una Institución</option>
                            @foreach ($instituciones as $institucion)
                            <option
                                value="{{ $institucion['id_institucion'] }}"
                                data-formacion="{{ $institucion['tipo'] }}">
                                {{ $institucion['nombre'] }}
                            </option>
=======
                <div class="btn-container">
                    <button type="button" class="next-button" onclick="validarFormularioStep1(1)">Siguiente</button>
                </div>
            </div>

            <!-- STEP 2: Universidad de Origen -->
            <div class="step-content" id="step-2">
                <h2>Instituto de educacion superior de origen</h2>

                <div class="row">
                    <div class="form-group">
                        <label>Institución de origen:</label>
                        <select id="institucion" required onchange="updateFormacion()">
                            <option value="">Seleccione una Institución</option>
                            @foreach ($instituciones as $institucion)
                                <option value="{{ $institucion['id_institucion'] }}"
                                    data-formacion="{{ $institucion['tipo'] }}">
                                    {{ $institucion['nombre'] }}
                                </option>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                            @endforeach
                        </select>
                        <span class="error-message"></span>
                    </div>
<<<<<<< HEAD

                    <!-- Tipo de formación -->
                    <div class="form-group">
                        <label>Tipo de formación:</label>
                        <input type="text" id="tipoFormacion" name="tipo" readonly>
                    </div>

                    <!-- Programa -->
                    <div class="form-group">
                        <label>Programa:</label>
                        <select id="programa" name="programa" class="form-control" required disabled>
                            <option value="">Seleccione un Programa</option>
                        </select>
=======
                    <div class="form-group">
                        <label>Tipo de formación:</label>
                        <input type="text" id="tipoFormacion" readonly>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                    </div>
                </div>

                <div class="row">
<<<<<<< HEAD
                    <!-- ¿Finalizó estudios? -->
                    <div class="form-group">
                        <label>¿Finalizó sus estudios?</label>
                        <select id="finalizo_estudios" class="form-control" required>
=======
                    <div class="form-group">
                        <label>Programa:</label>
                        <select id="programa" required disabled onchange="updateSemestres()">
                            <option value="">Seleccione un Programa</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label>Programa de Destino (Universidad Autónoma):</label>
                        <select id="programa_destino" name="programa_destino" required>
                            <option value="">Seleccione un programa de destino</option>
                            @foreach ($programasAutonoma as $programa)
                                <option value="{{ $programa['id_programa'] }}">{{ $programa['programa'] }}</option>
                            @endforeach
                        </select>
                        <span class="error-message"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>¿Finalizó sus estudios?</label>
                        <select id="finalizo_estudios" required onchange="toggleFechaFinalizacion()">
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                            <option value="">Seleccione</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
<<<<<<< HEAD

                    <!-- Fecha de Finalización (Se muestra si selecciona "Sí") -->
=======
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                    <div class="form-group" id="fecha_finalizacion_container" style="display: none;">
                        <label>Fecha de finalización:</label>
                        <input type="date" id="fecha_finalizacion" required>
                        <span class="error-message"></span>
                    </div>
<<<<<<< HEAD

                    <!-- Fecha del Último Semestre Cursado (Se muestra si selecciona "No") -->
                    <div class="form-group" id="fecha_ultimo_semestre_container" style="display: none;">
                        <label>Fecha del último semestre cursado:</label>
                        <input type="date" id="fecha_ultimo_semestre" class="form-control" required>
=======
                    <div class="form-group" id="fecha_ultimo_semestre_container" style="display: none;">
                        <label>Fecha del ultimo semestre cursado:</label>
                        <input type="date" id="fecha_ultimo_semestre" required>
                        <span class="error-message"></span>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                    </div>
                </div>

                <div class="btn-container">
<<<<<<< HEAD
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="button" class="next-button" onclick="changeStep(1, event)">Siguiente</button>
                </div>
            </div>

            <!-- STEP 3: PROGRAMA ACADÉMICO -->
            <div class="step-content" id="step-3">
                <h2>Seleccionar Asignaturas</h2>

                <div class="form-group">
                    <label for="semestre">Semestre:</label>
                    <select id="semestre" class="form-control" disabled>
                        <option value="">Seleccione un semestre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="materia">Asignatura:</label>
                    <select id="materia" class="form-control" disabled>
                        <option value="">Seleccione una asignatura</option>
                    </select>
                </div>

                <button type="button" class="add-button" onclick="agregarMateria()">
=======
                    <button class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button class="next-button" onclick="validarFormularioStep2(1)">Siguiente</button>
                </div>
            </div>

            <!-- STEP 3: Programa Académico -->
            <div class="step-content" id="step-3">
                <h2>Seleccionar Asignaturas</h2>

                <div class="row">
                    <div class="form-group">
                        <label for="semestre">Semestre:</label>
                        <select id="semestre" onchange="updateAsignaturas()">
                            <option value="">Seleccione un semestre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="materia">Asignatura:</label>
                        <select id="materia">
                            <option value="">Seleccione una asignatura</option>
                        </select>
                    </div>
                </div>

                <button class="add-button" onclick="agregarMateria()">
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                    <i class="fa-solid fa-plus"></i> Agregar Materia
                </button>

                <h3>Materias Seleccionadas</h3>
                <div id="materias-container"></div>

                <div class="mensaje-info">
                    <i class="fa-solid fa-info-circle"></i> Debe registrar al menos 6 materias con notas para
                    continuar.
                </div>

                <div class="btn-container">
<<<<<<< HEAD
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="button" class="next-button" onclick="validacionStep3()">Siguiente</button>
                </div>
            </div>

            <!-- STEP 4: DOCUMENTOS -->
=======
                    <button class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button class="next-button"
                        onclick="validacionStep3(); validarFormularioStep4();">Continuar</button>
                </div>
            </div>

            <!-- STEP 4: Documentos -->
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
            <div class="step-content" id="step-4">
                <h2>Documentos requeridos</h2>
                <p>Adjunte los siguientes documentos para completar su solicitud de homologación:</p>

                <div class="form-group">
<<<<<<< HEAD
                    <label>Certificado de notas:</label>
                    <input type="file" id="certificado_notas" accept=".pdf,.jpg,.jpeg,.png">
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label>Contenidos programáticos:</label>
                    <input type="file" id="contenidos_programaticos" accept=".pdf">
                    <span class="error-message"></span>
                </div>

                <div class="form-group">
                    <label>Documento de identidad:</label>
                    <input type="file" id="documento_identidad" accept=".pdf,.jpg,.jpeg,.png">
                    <span class="error-message"></span>
                </div>

                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="button" class="next-button" onclick="changeStep(1, event)">Siguiente</button>
                </div>
            </div>

            <!-- STEP 5: CONFIRMACIÓN -->
            <div class="step-content" id="step-5">
                <h2>Confirmación de solicitud</h2>

                <div class="resumen-container">
                    <h3>Resumen de la solicitud</h3>
                    <div id="resumen-datos"></div>
                </div>

                <div class="form-group">
                    <div class="checkbox-container">
                        <input type="checkbox" id="terminos" required>
                        <label for="terminos">Acepto los términos y condiciones del proceso de homologación</label>
                    </div>
                    <span class="error-message"></span>
                </div>

                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="submit" class="submit-button" onclick="enviarFormulario(event)">Enviar solicitud</button>
=======
                    <label>Documento de identidad:</label>
                    <input type="file" id="documento_id" name="documento_id" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label>Certificado de notas:</label>
                    <input type="file" id="certificado_notas" name="certificado_notas" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label>Contenido programático:</label>
                    <input type="file" id="contenido_programatico" name="contenido_programatico" accept=".pdf"
                        required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label>Carta Solicitud de homologación:</label>
                    <input type="file" id="carta_homologacion" name="carta_homologacion" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>
                <div class="form-group">
                    <label>Certificación de finalización de estudios:</label>
                    <input type="file" id="certificacion_finalizacion" name="certificacion_finalizacion"
                        accept=".pdf">
                    <span class="error-message"></span>
                </div>

                <div id="extra-docs" class="hidden">
                    <h3>Documentos adicionales para extranjeros</h3>
                    <div class="form-group">
                        <label>Copia de la visa o pasaporte:</label>
                        <input type="file" id="visa_pasaporte" name="visa_pasaporte" accept=".pdf">
                        <span class="error-message"></span>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="button" class="next-button" onclick="validarFormularioStep4()">Siguiente</button>
                </div>
            </div>

            <!-- STEP 5: Confirmación -->
            <div class="step-content" id="step-5">
                <h2>Confirmación</h2>
                <p>Por favor, revisa tus datos antes de enviar la solicitud.</p>
                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1)">Anterior</button>
                    <button type="button" class="submit-button" onclick="confirmarDatos()">Enviar</button>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
<<<<<<< HEAD
    <script src="{{ asset('js/homo.js') }}"></script>

    <script>
        // Inicializar variables globales con datos de la API
        window.departamentos = @json($departamentos ?? []);
        window.municipios = @json($municipios ?? []);
        window.instituciones = @json($instituciones ?? []);
        window.programas = @json($programas ?? []);
        window.asignaturas = @json($asignaturas ?? []);

        // Datos disponibles para debug
        console.log("Datos cargados en la vista:");
        console.log("Departamentos:", window.departamentos?.length);
        console.log("Municipios:", window.municipios?.length);
        console.log("Instituciones:", window.instituciones?.length);
        console.log("Programas:", window.programas?.length);
        console.log("Asignaturas:", window.asignaturas?.length);

        // Configurar URL de la API en un atributo data
        document.body.dataset.apiUrl = '{{ $apiUrl ?? "https://homologacionesback.educarenemociones.com/api" }}';

        // Llamar a la función de inicialización cuando el script esté cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar con todos los datos disponibles
            inicializarDatosFormulario({
                departamentos: window.departamentos,
                municipios: window.municipios,
                instituciones: window.instituciones,
                programas: window.programas,
                asignaturas: window.asignaturas
            });

            // Configurar event listeners
            const departamento = document.getElementById('departamento');
            if (departamento) {
                departamento.addEventListener('change', updateMunicipios);
            }

            const departamentoOrigen = document.getElementById('departamento_origen');
            if (departamentoOrigen) {
                departamentoOrigen.addEventListener('change', updateMunicipiosOrigen);
            }

            const institucion = document.getElementById('institucion');
            if (institucion) {
                institucion.addEventListener('change', updateFormacion);
            }

            const programa = document.getElementById('programa');
            if (programa) {
                programa.addEventListener('change', updateSemestres);
            }

            const semestre = document.getElementById('semestre');
            if (semestre) {
                semestre.addEventListener('change', updateAsignaturas);
            }

            const finalizoEstudios = document.getElementById('finalizo_estudios');
            if (finalizoEstudios) {
                finalizoEstudios.addEventListener('change', toggleFechaFinalizacion);
            }

            // Inicializar estados
            toggleFechaFinalizacion();
        });

        // Función para enviar el formulario (implementar según sea necesario)
        function enviarFormulario(event) {
            event.preventDefault();
            // Implementar lógica de envío
            alert('Formulario enviado correctamente');
        }
    </script>
=======
    <script>
        // Variables globales
        const todasLasAsignaturas = @json($asignaturas);
        const programasPorInstitucion = @json($programas);
        const todosLosMunicipios = @json($municipios);
        const todosLosProgramas = @json($programas);

        // Funciones de navegación del formulario
        function changeStep(direction, event) {
            if (event) event.preventDefault();
            // Esta función será implementada en el archivo externo
        }

        // Funciones de actualización de datos en formulario
        function handlePaisChange() {
            const paisSelect = document.getElementById('pais');
            const departamentoSelect = document.getElementById('departamento');
            departamentoSelect.disabled = paisSelect.value !== '1';

            if (paisSelect.value === '1') {
                departamentoSelect.removeAttribute('disabled');
                updateMunicipios();
            } else {
                departamentoSelect.setAttribute('disabled', 'disabled');
                document.getElementById('municipio').setAttribute('disabled', 'disabled');
            }
        }

        function activarInstitucionSelect() {
            const municipioSelect = document.getElementById('municipio_origen');
            const institucionSelect = document.getElementById('institucion');

            if (municipioSelect.value) {
                institucionSelect.disabled = false;

                institucionSelect.onchange = function() {
                    const programaSelect = document.getElementById('programa');
                    if (institucionSelect.value) {
                        updateFormacion();
                        programaSelect.disabled = false;
                    } else {
                        programaSelect.disabled = true;
                    }
                };
            } else {
                institucionSelect.disabled = true;
            }
        }

        function updateMunicipios() {
            const departamentoSelect = document.getElementById('departamento');
            const municipioSelect = document.getElementById('municipio');
            const departamentoSeleccionado = departamentoSelect.value;

            municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';

            if (!departamentoSeleccionado) {
                municipioSelect.disabled = true;
                return;
            }

            const municipiosFiltrados = todosLosMunicipios.filter(m => m.departamento === departamentoSeleccionado);
            municipiosFiltrados.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id_municipio;
                option.textContent = municipio.municipio;
                municipioSelect.appendChild(option);
            });

            municipioSelect.disabled = false;
        }

        function updateMunicipiosOrigen() {
            const departamentoSelect = document.getElementById('departamento_origen');
            const municipioSelect = document.getElementById('municipio_origen');
            const institucionSelect = document.getElementById('institucion');
            const departamentoSeleccionado = departamentoSelect.value;

            municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';
            institucionSelect.disabled = true;

            if (!departamentoSeleccionado) {
                municipioSelect.disabled = true;
                return;
            }

            const municipiosFiltrados = todosLosMunicipios.filter(m => m.departamento === departamentoSeleccionado);
            municipiosFiltrados.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id_municipio;
                option.textContent = municipio.municipio;
                municipioSelect.appendChild(option);
            });

            municipioSelect.disabled = false;

            municipioSelect.onchange = function() {
                institucionSelect.disabled = !municipioSelect.value;
            };
        }

        function updateFormacion() {
            const institucionSelect = document.getElementById('institucion');
            const tipoInput = document.getElementById('tipoFormacion');
            const programaSelect = document.getElementById('programa');
            const selectedOption = institucionSelect.options[institucionSelect.selectedIndex];

            tipoInput.value = selectedOption.dataset.formacion || "";

            programaSelect.innerHTML = '<option value="">Seleccione un Programa</option>';
            document.getElementById('semestre').innerHTML = '<option value="">Seleccione un semestre</option>';
            document.getElementById('materia').innerHTML = '<option value="">Seleccione una materia</option>';
            document.getElementById('semestre').disabled = true;
            document.getElementById('materia').disabled = true;

            const institucionId = institucionSelect.value;
            if (!institucionId) {
                programaSelect.disabled = true;
                return;
            }

            const programasFiltrados = programasPorInstitucion.filter(p => p.id_institucion == institucionId);
            programasFiltrados.forEach(programa => {
                const option = document.createElement('option');
                option.value = programa.id_programa;
                option.textContent = programa.nombre;
                programaSelect.appendChild(option);
            });

            programaSelect.disabled = false;

            // Cargar programas de destino si no están cargados
            cargarProgramasDestino();
        }

        function initializeFechaFinalizacion() {
            const finalizoEstudiosSelect = document.getElementById("finalizo_estudios");
            finalizoEstudiosSelect.value = ""; // Cambiar de "no" a vacío

            // Ocultar ambos contenedores de fecha al inicio
            document.getElementById("fecha_finalizacion_container").style.display = "none";
            document.getElementById("fecha_ultimo_semestre_container").style.display = "none";

            // Quitar el atributo required de ambos campos inicialmente
            document.getElementById("fecha_finalizacion").removeAttribute("required");
            document.getElementById("fecha_ultimo_semestre").removeAttribute("required");
        }

        function toggleFechaFinalizacion() {
            const finalizoEstudios = document.getElementById("finalizo_estudios").value;
            const fechaFinalizacionContainer = document.getElementById("fecha_finalizacion_container");
            const fechaUltimoSemestreContainer = document.getElementById("fecha_ultimo_semestre_container");

            if (finalizoEstudios === "si") {
                fechaFinalizacionContainer.style.display = "block";
                fechaUltimoSemestreContainer.style.display = "none";
                document.getElementById("fecha_ultimo_semestre").removeAttribute("required");
                document.getElementById("fecha_finalizacion").setAttribute("required", "");
            } else if (finalizoEstudios === "no") {
                fechaFinalizacionContainer.style.display = "none";
                fechaUltimoSemestreContainer.style.display = "block";
                document.getElementById("fecha_finalizacion").removeAttribute("required");
                document.getElementById("fecha_ultimo_semestre").setAttribute("required", "");
            } else {
                fechaFinalizacionContainer.style.display = "none";
                fechaUltimoSemestreContainer.style.display = "none";
            }
        }

        function cargarProgramasDestino() {
            const programaDestinoSelect = document.getElementById('programa_destino');
            if (!programaDestinoSelect || programaDestinoSelect.options.length > 1) return;

            fetch('http://localhost/Backend-Laravel/public/api/programas?institucion=1')
                .then(response => response.json())
                .then(data => {
                    if (data && Array.isArray(data)) {
                        const programas = data.filter(p =>
                            p.institucion &&
                            (p.institucion.includes('Autónoma') || p.institucion.includes('Autonoma'))
                        );

                        if (programas.length > 0) {
                            programas.forEach(programa => {
                                const option = document.createElement('option');
                                option.value = programa.id_programa;
                                option.textContent = programa.nombre;
                                programaDestinoSelect.appendChild(option);
                            });
                        }
                    }
                })
                .catch(error => console.error('Error al cargar programas de destino:', error));
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Formulario de homologación iniciado");
            initializeFechaFinalizacion();
            cargarProgramasDestino();
        });
    </script>

    <!-- Cargar scripts externos -->
    <script src="{{ asset('js/authService.js') }}"></script>
    <script src="{{ asset('js/authMiddleware.js') }}"></script>
    <script src="{{ asset('js/solicitudhomologacion.js') }}"></script>
>>>>>>> 6ee72232848682e84178845b68c8a544fd8977eb
</body>
</html>

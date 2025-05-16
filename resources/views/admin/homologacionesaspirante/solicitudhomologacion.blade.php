<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Homologación - Universidad Autónoma del Cauca</title>
    <link href="{{ asset('css/estiloformularioaspirante.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>
        <h1>Universidad Autónoma del Cauca</h1>
        <p>Proceso de Homologación Académica</p>
    </header>

    <div class="container">
        <div class="progress-container">
            <div class="step active" data-step="1">
                <div class="step-icon">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <div class="step-title">Datos personales</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-icon">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div class="step-title">Universidad de Origen</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="step-title">Programa académico</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-icon">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <div class="step-title">Documentos</div>
            </div>
            <div class="step" data-step="5">
                <div class="step-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="step-title">Confirmación</div>
            </div>
        </div>

        <form id="homologacion-form">
            <!-- STEP 1: DATOS PERSONALES -->
            <div class="step-content active" id="step-1">
                <h2>Información personal</h2>

                <!-- Tipo de Identificación y Número -->
                <div class="row">
                    <div class="form-group">
                        <label>Tipo de identificación:</label>
                        <select id="tipo_identificacion" required>
                            <option value="">Seleccione</option>
                            <option value="TI">Tarjeta de Identidad (TI)</option>
                            <option value="CC">Cédula de Ciudadanía (CC)</option>
                            <option value="TE">Tarjeta de Extranjería (TE)</option>
                        </select>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label>Número de identificación:</label>
                        <input type="text" id="numero_identificacion" required>
                        <span class="error-message"></span>
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
                        </select>
                    </div>

                    <!-- Selección de Departamento -->
                    <div class="form-group">
                        <label>Departamento:</label>
                        <select id="departamento_origen" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['departamento'] }}">{{ $departamento['departamento'] }}</option>
                            @endforeach
                        </select>
                        <span class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label>Municipio:</label>
                        <select id="municipio_origen" required disabled>
                            <option value="">Seleccione un Municipio</option>
                        </select>
                        <span class="error-message"></span>
                    </div>
                </div>

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
                            @endforeach
                        </select>
                    </div>

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
                    </div>
                </div>

                <div class="row">
                    <!-- ¿Finalizó estudios? -->
                    <div class="form-group">
                        <label>¿Finalizó sus estudios?</label>
                        <select id="finalizo_estudios" class="form-control" required>
                            <option value="">Seleccione</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <!-- Fecha de Finalización (Se muestra si selecciona "Sí") -->
                    <div class="form-group" id="fecha_finalizacion_container" style="display: none;">
                        <label>Fecha de finalización:</label>
                        <input type="date" id="fecha_finalizacion" class="form-control" required>
                    </div>

                    <!-- Fecha del Último Semestre Cursado (Se muestra si selecciona "No") -->
                    <div class="form-group" id="fecha_ultimo_semestre_container" style="display: none;">
                        <label>Fecha del último semestre cursado:</label>
                        <input type="date" id="fecha_ultimo_semestre" class="form-control" required>
                    </div>
                </div>

                <div class="btn-container">
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
                    <i class="fa-solid fa-plus"></i> Agregar Materia
                </button>

                <h3>Materias Seleccionadas</h3>
                <div id="materias-container"></div>

                <div class="mensaje-info">
                    <i class="fa-solid fa-info-circle"></i> Debe registrar al menos 6 materias con notas para continuar
                    con la homologación.
                </div>

                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="button" class="next-button" onclick="validacionStep3()">Siguiente</button>
                </div>
            </div>

            <!-- STEP 4: DOCUMENTOS -->
            <div class="step-content" id="step-4">
                <h2>Documentos requeridos</h2>
                <p>Adjunte los siguientes documentos para completar su solicitud de homologación:</p>

                <div class="form-group">
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
                </div>
            </div>
        </form>
    </div>

    <!-- Scripts -->
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
</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Homologación - Universidad Autónoma del Cauca</title>
    <link href="{{ asset('css/estiloformularioaspirante.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
    <!-- Cargar los scripts PRIMERO -->
    <!-- <script src="{{ asset('js/authService.js') }}"></script>
<script src="{{ asset('js/authMiddleware.js') }}"></script> -->

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

                <!-- Yo uso el chagestep para moverme en cada uno de los iconos no es neserio para ustedes, los pueden borrar-->

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
            <div class="step-content active" id="step-1">
                <form id="formulario">
                    <h2>Información personal</h2>

                    <!-- Tipo de Identificación y Número -->
                    <div class="row">
                        <div class="form-group">
                            <label>Tipo de identificación:</label>
                            <select id="tipo_identificacion" required>
                                <option value="">Seleccione</option>
                                <option value="Tarjeta de Identidad">Tarjeta de Identidad (TI)</option>
                                <option value="Cédula de Ciudadanía">Cédula de Ciudadanía (CC)</option>
                                <option value="Cédula de Extranjería">Cédula de Extranjería (TE)</option>
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
                            <select id="pais" required disabled onchange="cargarDepartamentos()">
                                <option value="Colombia">Colombia</option>
                            </select><br>
                        </div>
                        <div class="form-group">
                            <label>Departamento:</label>
                            <select id="departamento" required onchange="updateMunicipios()">
                                <option value="">Seleccione un departamento</option>
                                @foreach ($departamentos as $departamento)
                                <option
                                    value="{{ $departamento['departamento'] }}"
                                    data-id="{{ $departamento['id_departamento'] }}">
                                    {{ $departamento['departamento'] }}
                                </option>
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
                        <button type="button" class="next-button"
                            onclick="validarFormularioStep1(1)">Siguiente</button>
                    </div>

                </form>
            </div>
            <!--STEP # 2 DONDE CARGAR LAS UNIVERSIDADES QUE HAY EN EL PAIS-->
            <div class="step-content" id="step-2">
                <h2>Instituto de educacion superior de origen </h2>

                <div class="row">
                    <!-- Selección de País -->
                    <div class="col-md-4 form-group">
                        <label>País:</label>
                        <select id="pais" class="form-control" required disabled onchange="cargarPaises()">
                            <option value="Colombia">Colombia</option>
                        </select>
                    </div>

                    <!-- Selección de Departamento -->
                    <div class="form-group">
                        <label>Departamento:</label>
                        <select id="departamento_origen" required onchange="updateMunicipiosOrigen()">
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
                    <div class="col-md-4 form-group">
                        <label>Institución de origen:</label>
                        <select id="institucion" name="institucion" class="form-control" required onchange="updateFormacion()">
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
                    <div class="col-md-4 form-group">
                        <label>Tipo de formación:</label>
                        <input type="text" id="tipoFormacion" name="tipo" readonly>
                    </div>

                    <!-- Programa -->
                    <div class="col-md-4 form-group">
                        <label>Programa:</label>
                        <select id="programa" name="programa" class="form-control" required disabled onchange="updateSemestres()">
                            <option value="">Seleccione un Programa</option>
                        </select>
                    </div>
                </div>




                <div class="row">
                    <!-- ¿Finalizó estudios? -->
                    <div class="col-md-4 form-group">
                        <label>¿Finalizó sus estudios?</label>
                        <select id="finalizo_estudios" class="form-control" required
                            onchange="toggleFechaFinalizacion()">
                            <option value="">Seleccione</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <!-- Fecha de Finalización (Se muestra si selecciona "Sí") -->
                    <div class="col-md-4 form-group" id="fecha_finalizacion_container" style="display: none;">
                        <label>Fecha de finalización:</label>
                        <input type="date" id="fecha_finalizacion" class="form-control" required>
                    </div>

                    <!-- Fecha del Último Semestre Cursado (Se muestra si selecciona "No") -->
                    <div class="col-md-4 form-group" id="fecha_ultimo_semestre_container" style="display: none;">
                        <label>Fecha del ultimo semestre cursado:</label>
                        <input type="date" id="fecha_ultimo_semestre" class="form-control" required>
                    </div>
                </div>

                <div class="btn-container">
                    <button class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button class="next-button" onclick="validarFormularioStep2(1)">Siguiente</button>
                </div>
            </div>



            <!-- Modificación del STEP 3 para filtrar semestres y asignaturas según el programa -->
            <div class="step-content" id="step-3">
                <h2>Seleccionar Asignaturas</h2>

                <div class="form-group">
                    <label for="semestre">Semestre:</label>
                    <select id="semestre" class="form-control" onchange="updateAsignaturas()">
                        <option value="">Seleccione un semestre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="materia">Asignatura:</label>
                    <select id="materia" class="form-control">
                        <option value="">Seleccione una asignatura</option>
                    </select>
                </div>

                <button class="add-button" onclick="agregarMateria()">
                    <i class="fa-solid fa-plus"></i> Agregar Materia
                </button>

                <h3>Materias Seleccionadas</h3>
                <div id="materias-container"></div>

                <div class="mensaje-info">
                    <i class="fa-solid fa-info-circle"></i> Debe registrar al menos 6 materias con notas para continuar
                    con la homologación.
                </div>

                <div class="btn-container">
                    <button class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button class="next-button" onclick="validacionStep3(); validarFormularioStep4();">Continuar</button>
                </div>
            </div>


            <div class="step-content" id="step-4">
                <h2>Documentos</h2>

                <div>
                    <label>Documento de identidad:</label>
                    <input type="file" id="documento_id" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>

                <div>
                    <label>Certificado de notas:</label>
                    <input type="file" id="certificado_notas" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>

                <div>
                    <label>Contenido programático:</label>
                    <input type="file" id="contenido_programatico" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>

                <div>
                    <label>Carta Solicitud de homologación:</label>
                    <input type="file" id="carta_homologacion" accept=".pdf" required>
                    <span class="error-message"></span>
                </div>

                <div>
                    <label>Certificación de finalización de estudios:</label>
                    <input type="file" id="certificacion_finalizacion" accept=".pdf">
                    <span class="error-message"></span>
                </div>

                <!-- Documentos adicionales para extranjeros -->
                <div id="extra-docs" class="hidden">
                    <h3>Documentos adicionales para extranjeros</h3>

                    <div>
                        <label>Copia de la visa o pasaporte:</label>
                        <input type="file" id="visa_pasaporte" accept=".pdf">
                        <span class="error-message"></span>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="button" class="prev-button" onclick="changeStep(-1, event)">Anterior</button>
                    <button type="button" class="next-button" onclick="confirmarDatos()">Siguiente</button>
                </div>
            </div>



            <div class="step-content" id="step-5">
                <h2>Confirmación</h2>

                <p>Por favor, revisa tus datos antes de enviar la solicitud.</p>
                <div class="btn-container">

                    <button type="button" class="prev-button" onclick="changeStep(-1)">Anterior</button>
                    <button type="button" class="submit-button" onclick="confirmarDatos()">Enviar</button>
                </div>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/solicitudhomologacion.js') }}"></script>




    <!-- Script para manejar el filtrado de semestres y materias -->
    <script>
        const todasLasAsignaturas = @json($asignaturas);
        const programasPorInstitucion = @json($programas);

        function updateFormacion() {
            const institucionSelect = document.getElementById('institucion');
            const tipoInput = document.getElementById('tipoFormacion');
            const programaSelect = document.getElementById('programa');
            const selectedOption = institucionSelect.options[institucionSelect.selectedIndex];

            // Mostrar tipo de formación
            tipoInput.value = selectedOption.dataset.formacion || "";

            // Limpiar programas anteriores
            programaSelect.innerHTML = '<option value="">Seleccione un Programa</option>';
            document.getElementById('semestre').innerHTML = '<option value="">Seleccione un semestre</option>';
            document.getElementById('materia').innerHTML = '<option value="">Seleccione una materia</option>';
            document.getElementById('semestre').disabled = true;
            document.getElementById('materia').disabled = true;

            // Obtener ID de la institución seleccionada
            const institucionId = institucionSelect.value;

            if (!institucionId) {
                programaSelect.disabled = true;
                return;
            }

            // Filtrar programas de la institución seleccionada
            const programasFiltrados = programasPorInstitucion.filter(p => p.id_institucion == institucionId);
            programasFiltrados.forEach(programa => {
                const option = document.createElement('option');
                option.value = programa.id_programa;
                option.textContent = programa.nombre;
                programaSelect.appendChild(option);
            });

            programaSelect.disabled = programasFiltrados.length === 0;
        }
    </script>



    <script>
        // Esta parte me permite definir el tipo de formacion del programa.
        document.addEventListener('DOMContentLoaded', function() {
            const institucionSelect = document.getElementById('institucion');
            const tipoFormacionInput = document.getElementById('tipoFormacion');

            institucionSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const tipoFormacion = selectedOption.getAttribute('data-formacion');

                tipoFormacionInput.value = tipoFormacion || '';
            });
        });
    </script>

    <script>
        const todosLosProgramas = @json($programas); // Pasamos todos los programas al JavaScript


        // Función para actualizar el tipo de formación según la institución seleccionada
        function updateFormacion() {
            const institucionSelect = document.getElementById("institucion");
            const tipoFormacionInput = document.getElementById("tipoFormacion");

            // Validamos que haya una opción seleccionada
            if (institucionSelect.selectedIndex > 0) {
                const selectedOption = institucionSelect.options[institucionSelect.selectedIndex];
                tipoFormacionInput.value = selectedOption.getAttribute('data-formacion') || '';
            } else {
                tipoFormacionInput.value = '';
            }

            // Después de cambiar la institución, también actualizamos la lista de programas
            updateCarreras();
        }

        // Función para actualizar la lista de programas según la institución seleccionada
        function updateCarreras() {
            const institucionSelect = document.getElementById('institucion');
            const programaSelect = document.getElementById('programa');

            // Obtener tanto el ID como el nombre de la institución seleccionada
            let institucionId = '';
            let institucionNombre = '';

            if (institucionSelect.selectedIndex > 0) {
                institucionId = institucionSelect.value;
                institucionNombre = institucionSelect.options[institucionSelect.selectedIndex].text;
            }

            // Limpiamos las opciones actuales del selector de programas
            programaSelect.innerHTML = '<option value="">Seleccione un Programa</option>';

            // Si no hay institución seleccionada, deshabilitamos el selector de programas
            if (!institucionId) {
                programaSelect.disabled = true;
                return;
            }

            // Filtramos los programas que corresponden a la institución seleccionada
            // Verificamos tanto por ID como por nombre completo
            const programasFiltrados = todosLosProgramas.filter(programa => {
                return programa.institucion === institucionNombre ||
                    (programa.id_institucion && programa.id_institucion == institucionId);
            });

            // Comprobamos si obtuvimos programas
            if (programasFiltrados.length > 0) {
                // Agregamos las opciones filtradas al selector
                programasFiltrados.forEach(programa => {
                    const option = document.createElement('option');
                    option.value = programa.id_programa;
                    option.textContent = programa.programa;
                    programaSelect.appendChild(option);
                });

                programaSelect.disabled = false;
            } else {
                // Si no hay programas, mostramos un mensaje en consola para depuración
                console.log(`No se encontraron programas para la institución: ID=${institucionId}, Nombre=${institucionNombre}`);
                programaSelect.disabled = true;
            }
        }

        // Función para mostrar/ocultar campos según si finalizó estudios
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

        // Función adicional para debugging - ayuda a verificar qué datos están cargados
        function verificarDatos() {
            console.log("Instituciones cargadas:", document.getElementById('institucion').options.length);
            if (typeof todosLosProgramas !== 'undefined') {
                console.log("Programas disponibles:", todosLosProgramas.length);
                console.log("Ejemplo de programa:", todosLosProgramas[0]);
            } else {
                console.error("La variable todosLosProgramas no está definida");
            }
        }

        // Ejecutar verificación cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar datos en consola
            verificarDatos();

            // Verificar que los selectores existan
            const elementos = ['institucion', 'programa', 'tipoFormacion', 'finalizo_estudios'];
            elementos.forEach(id => {
                if (!document.getElementById(id)) {
                    console.error(`Elemento con ID '${id}' no encontrado en el DOM`);
                }
            });
        });
    </script>

    <script>
        // Trae los municipios en json y hace cosas con ellos
        const todosLosMunicipios = @json($municipios);

        function updateMunicipios() {
            const departamentoSelect = document.getElementById('departamento');
            const municipioSelect = document.getElementById('municipio');
            const departamentoSeleccionado = departamentoSelect.value;

            municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';

            if (departamentoSeleccionado === '') {
                municipioSelect.disabled = true;
                return;
            }

            const municipiosFiltrados = todosLosMunicipios.filter(
                m => m.departamento === departamentoSeleccionado
            );

            municipiosFiltrados.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id_municipio;
                option.textContent = municipio.municipio;
                municipioSelect.appendChild(option);
            });

            municipioSelect.disabled = false;
        }


        // Step 2 departamentos y Municipios
        function updateMunicipiosOrigen() {

            const departamentoSelect = document.getElementById('departamento_origen');
            const municipioSelect = document.getElementById('municipio_origen');
            const departamentoSeleccionado = departamentoSelect.value;

            municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';

            if (departamentoSeleccionado === '') {
                municipioSelect.disabled = true;
                return;
            }

            const municipiosFiltrados = todosLosMunicipios.filter(
                m => m.departamento === departamentoSeleccionado
            );

            municipiosFiltrados.forEach(municipio => {
                const option = document.createElement('option');
                option.value = municipio.id_municipio;
                option.textContent = municipio.municipio;
                municipioSelect.appendChild(option);
            });

            municipioSelect.disabled = false;
        }
    </script>

</body>

</html>
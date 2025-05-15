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
            <div class="step" data-step="2" onclick="changeStep(1)">
                <div class="step-icon">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div class="step-title">Universidad de Origen</div>
            </div>
            <div class="step" data-step="3" onclick="changeStep(2)">
                <div class="step-icon">
                    <i class="fa-solid fa-graduation-cap" onclick="changeStep(3)"></i>
                </div>
                <div class="step-title">Programa académico</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-icon">
                    <i class="fa-solid fa-file-pdf" onclick="changeStep(3)"></i>
                </div>
                <div class="step-title">Documentos</div>
            </div>
            <div class="step" data-step="5">
                <div class="step-icon">
                    <i class="fa-solid fa-circle-check" onclick="changeStep(3)"></i>
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
                            <select id="pais" required disabled onchange="cargarDepartamentos()">
                                <option value="Colombia">Colombia</option>
                            </select><br>
                        </div>
                        <div class="form-group">
                            <label>Departamento:</label>
                            <select id="departamento" required onchange="updateMunicipios()">
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
                        <button type="button" class="next-button"
                            onclick="changeStep(1)">Siguiente</button>
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
                        <select id="institucion" class="form-control" required onchange="updateFormacion()">
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
                    <button class="next-button" onclick="changeStep(1, event)">Siguiente</button>
                </div>
            </div>



            <!-- Modificación del STEP 3 para filtrar semestres y asignaturas según el programa -->
            <div class="step-content" id="step-3">


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
                    <button class="prev-button" onclick="changeStep(-1)">Anterior</button>
                    <button class="next-button" onclick="validacionStep3();">Siguiente</button>
                </div>
            </div>

            <script>
                function agregarMateria() {
    const semestre = document.getElementById("semestre").value;
    const materiaSeleccionada = document.getElementById("materia").value;

    if (!semestre || !materiaSeleccionada) {
        mostrarMensaje("Debe seleccionar una carrera, un semestre y una materia.", "error");
        return;
    }

    let materiaId = `nota_${semestre}_${materiaSeleccionada.replace(/\s+/g, '_')}`;
    let materiasContainer = document.getElementById("materias-container");

    // Verificar si el contenedor del semestre ya existe
    let contenedorSemestre = document.getElementById(`semestre_${semestre}`);

    if (!contenedorSemestre) {
        // Crear el contenedor de semestre si no existe
        contenedorSemestre = document.createElement("div");
        contenedorSemestre.id = `semestre_${semestre}`;
        contenedorSemestre.classList.add("semestre-container");
        contenedorSemestre.setAttribute("data-semestre", semestre);
        contenedorSemestre.innerHTML = `<h3>${semestre}</h3>`;

        materiasContainer.appendChild(contenedorSemestre);
    }

    // Verificar si la materia ya existe dentro del semestre
    if (document.getElementById(materiaId)) {
        mostrarMensaje("Esta materia ya ha sido seleccionada en este semestre.", "error");
        return;
    }

    // Crear la fila de materia
    let materiaRow = document.createElement("div");
    materiaRow.classList.add("materia-row");
    materiaRow.innerHTML = `
        <label class="materia-label">${materiaSeleccionada}:</label>

        <div class="input-container">
            <input type="text" id="${materiaId}" placeholder="Ingrese nota" class="nota-input"
                oninput="validarNota(this)">
        </div>

        <button class="delete-button" onclick="borrarMateria('${materiaId}')">
            <i class="fa-solid fa-trash"></i> Borrar
        </button>
    `;

    // Agregar la materia dentro del semestre correspondiente
    contenedorSemestre.appendChild(materiaRow);
    mostrarMensaje(`Materia "${materiaSeleccionada}" agregada correctamente.`, "success");

    // Reordenar los semestres en el DOM
    ordenarSemestresAlfabeticamente();
}

function borrarMateria(materiaId) {
    const materiaRow = document.getElementById(materiaId).closest('.materia-row');
    const materiaNombre = materiaRow.querySelector('.materia-label').textContent;
    const semestre = materiaRow.closest('.semestre-container');

    // Mostrar modal de confirmación
    mostrarModalConfirmacion(
        "Eliminar Materia",
        `¿Está seguro que desea eliminar la materia "${materiaNombre}"?`,
        "Eliminar",
        () => {
            if (!materiaRow) return; // Evita errores si no se encuentra la materia

            // Eliminar la nota del localStorage si existe
            let notas = JSON.parse(localStorage.getItem('notas')) || {};
            if (notas[materiaId]) {
                delete notas[materiaId];
                localStorage.setItem('notas', JSON.stringify(notas));
            }

            // Eliminar la fila de la materia
            materiaRow.remove();

            // Verificar si el contenedor del semestre está vacío (sin materias)
            if (semestre && semestre.querySelectorAll('.materia-row').length === 0) {
                semestre.remove();
            }

            // Mostrar mensaje de éxito
            mostrarMensaje(`Materia "${materiaNombre}" ha sido eliminada correctamente.`, "success");
        }
    );

}


function ordenarSemestresAlfabeticamente() {
    let materiasContainer = document.getElementById("materias-container");
    let semestres = Array.from(document.querySelectorAll(".semestre-container"));

    // Ordenar los semestres alfabéticamente por su atributo de semestre
    semestres.sort((a, b) => {
        let nombreA = a.getAttribute("data-semestre").toLowerCase();
        let nombreB = b.getAttribute("data-semestre").toLowerCase();
        return nombreA.localeCompare(nombreB);
    });

    // Crear un fragmento para mejorar el rendimiento
    let fragment = document.createDocumentFragment();
    semestres.forEach(semestre => fragment.appendChild(semestre));

    // Limpiar y volver a agregar los semestres ordenados
    materiasContainer.innerHTML = "";
    materiasContainer.appendChild(fragment);
}
            </script>









            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const programaSelect = document.getElementById("programa");
                    const semestresContainer = document.getElementById("semestres");

                    programaSelect.addEventListener("change", function() {
                        const programaId = this.value;

                        if (programaId) {
                            fetch(`http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`)
                                .then(response => response.json())
                                .then(data => {
                                    console.log("📦 Datos recibidos:", data); // 🔍 Verifica aquí en consola

                                    if (data.success) {
                                        mostrarAsignaturas(data.data);
                                    } else {
                                        alert("No se pudieron obtener las asignaturas.");
                                    }
                                })
                                .catch(error => {
                                    console.error("❌ Error en la petición:", error);
                                });
                        }
                    });

                    function mostrarAsignaturas(asignaturas) {
                        semestresContainer.innerHTML = ""; // Limpia contenido anterior

                        const asignaturasPorSemestre = {};

                        // Agrupa por semestre
                        asignaturas.forEach(asig => {
                            if (!asignaturasPorSemestre[asig.semestre]) {
                                asignaturasPorSemestre[asig.semestre] = [];
                            }
                            asignaturasPorSemestre[asig.semestre].push(asig);
                        });

                        // Recorre cada semestre y muestra asignaturas
                        for (const semestre in asignaturasPorSemestre) {
                            const div = document.createElement("div");
                            div.innerHTML = `
                <h4>Semestre ${semestre}</h4>
                <ul>
                    ${asignaturasPorSemestre[semestre].map(asig => `<li>${asig.nombre} - ${asig.codigo_asignatura}</li>`).join("")}
                </ul>
            `;
                            semestresContainer.appendChild(div);
                        }
                    }
                });
            </script>

            <script>
                function updateSemestres() {
                    const programaId = document.getElementById("programa").value;
                    const semestreSelect = document.getElementById("semestre");
                    const asignaturaSelect = document.getElementById("materia");

                    // Resetear selects
                    semestreSelect.innerHTML = '<option value="">Seleccione un semestre</option>';
                    asignaturaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
                    semestreSelect.disabled = true;
                    asignaturaSelect.disabled = true;

                    if (!programaId) return;

                    // Llamada a la ruta del backend
                    fetch(`http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`)
                        .then(response => response.json())
                        .then(data => {
                            const asignaturas = data.data;

                            // Extraer semestres únicos
                            const semestresUnicos = [...new Set(asignaturas.map(a => a.semestre))].sort((a, b) => a - b);

                            // Llenar el select de semestres
                            semestresUnicos.forEach(sem => {
                                const option = document.createElement("option");
                                option.value = sem;
                                option.textContent = `Semestre ${sem}`;
                                semestreSelect.appendChild(option);
                            });

                            // Guardar asignaturas temporalmente en una variable global
                            window.asignaturasPorPrograma = asignaturas;

                            semestreSelect.disabled = false;
                        })
                        .catch(err => {
                            console.error("Error al cargar asignaturas:", err);
                        });
                }

                function updateAsignaturas() {
                    const semestre = parseInt(document.getElementById("semestre").value);
                    const asignaturaSelect = document.getElementById("materia");

                    asignaturaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
                    asignaturaSelect.disabled = true;

                    if (!semestre || !window.asignaturasPorPrograma) return;

                    const filtradas = window.asignaturasPorPrograma.filter(a => a.semestre === semestre);

                    filtradas.forEach(a => {
                        const option = document.createElement("option");
                        option.value = a.id_asignatura;
                        option.textContent = a.nombre;
                        asignaturaSelect.appendChild(option);
                    });

                    asignaturaSelect.disabled = false;
                }
            </script>



            <!-- Script para manejar el filtrado de semestres y asignaturas -->
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
            </script>

            <!-- Yo uso este script para moverme entre los step no es nesesario para ustedes -->

            <script>
                let currentStep = 1; // Asegurar que currentStep esté definido globalmente
                function changeStep(stepChange) {
                    const steps = document.querySelectorAll(".step");
                    const stepContents = document.querySelectorAll(".step-content");

                    let newStep = currentStep + stepChange;

                    // Evitar que se salga de los límites
                    if (newStep < 1 || newStep > steps.length) return;

                    // Ocultar el paso actual
                    stepContents[currentStep - 1].classList.remove("active");
                    steps[currentStep - 1].classList.remove("active");

                    // Actualizar el paso actual
                    currentStep = newStep;

                    // Mostrar el nuevo paso
                    stepContents[currentStep - 1].classList.add("active");
                    steps[currentStep - 1].classList.add("active");
                }
            </script>

</body>

</html>
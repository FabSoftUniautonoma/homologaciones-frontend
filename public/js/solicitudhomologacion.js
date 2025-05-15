//FUNCION PARA EL BOTON SIGUENTE Y EL ANTERIOR

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

// PARA QUE FUNCIONE LA BARRA DE PROGRESO
document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".step");
    const stepContents = document.querySelectorAll(".step-content");
    const progressBar = document.getElementById("progress-bar");

    function updateStep(stepNumber) {
        // Ocultar todos los contenidos
        stepContents.forEach((content) => content.classList.remove("active"));

        // Activar solo el contenido del paso actual
        document.getElementById(`step-${stepNumber}`).classList.add("active");

        // Actualizar clases en los pasos
        steps.forEach((step, index) => {
            if (index + 1 < stepNumber) {
                step.classList.add("completed");
                step.classList.remove("active");
            } else if (index + 1 === stepNumber) {
                step.classList.add("active");
                step.classList.remove("completed");
            } else {
                step.classList.remove("active", "completed");
            }
        });

        // Ajustar la barra de progreso
        const stepPercentage = ((stepNumber - 1) / (steps.length - 1)) * 100;
        progressBar.style.width = `${stepPercentage}%`;
    }

    // Iniciar en el primer paso
    updateStep(1);
});
function mostrarMensaje(mensaje, tipo) {
    // Verificar si ya existe un mensaje y eliminarlo
    let mensajeExistente = document.querySelector(".mensaje-flash");
    if (mensajeExistente) {
        mensajeExistente.remove();
    }

    // Crear el elemento de mensaje
    let mensajeElement = document.createElement("div");
    mensajeElement.classList.add("mensaje-flash", tipo);
    mensajeElement.innerHTML = `
        <span>${mensaje}</span>
        <button onclick="this.parentNode.remove()">×</button>
    `;

    // Agregar el mensaje al DOM
    document.body.appendChild(mensajeElement);

    // Eliminar automáticamente después de 5 segundos
    setTimeout(() => {
        if (document.body.contains(mensajeElement)) {
            mensajeElement.remove();
        }
    }, 5000);
}
// Validacion de el formulario
// Expresiones regulares

const regexTexto = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios
const regexEmail =
    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/;
const regexTelefono = /^\d{7,10}$/; // Teléfonos de 7 a 10 dígitos
const regexNumeroIdentificacion = /^\d+$/; // Solo números

// Restringir entrada de caracteres no permitidos en Número de Identificación y Teléfono
document
    .getElementById("numero_identificacion")
    .addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, ""); // Elimina cualquier carácter que no sea número
    });

// Función para evitar que se ingresen caracteres no permitidos
function soloLetras(event) {
    let input = event.target;
    let valor = input.value;

    // Reemplaza cualquier carácter que no sea letra o espacio con una cadena vacía
    input.value = valor.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
}

// Aplicar la validación en tiempo real a los campos de texto
document.addEventListener("DOMContentLoaded", function () {
    const campos = [
        "primer_nombre",
        "segundo_nombre",
        "primer_apellido",
        "segundo_apellido",
    ];

    campos.forEach((id) => {
        const input = document.getElementById(id);
        if (input) {
            // Bloquea la entrada de caracteres inválidos en tiempo real
            input.addEventListener("input", soloLetras);

            // Evita pegar texto con caracteres no permitidos
            input.addEventListener("paste", function (event) {
                event.preventDefault();
            });

            // Evita que los números sean ingresados con teclas especiales
            input.addEventListener("keydown", function (event) {
                if (event.key.match(/[0-9]/)) {
                    event.preventDefault();
                }
            });
        }
    });
});

function toggleFechaFinalizacion() {
    const finalizoEstudios = document.getElementById("finalizo_estudios").value;
    const fechaFinalizacionContainer = document.getElementById(
        "fecha_finalizacion_container"
    );
    const fechaUltimoSemestreContainer = document.getElementById(
        "fecha_ultimo_semestre_container"
    );

    if (finalizoEstudios === "si") {
        fechaFinalizacionContainer.style.display = "block";
        fechaUltimoSemestreContainer.style.display = "none";
        document
            .getElementById("fecha_ultimo_semestre")
            .removeAttribute("required");
        document
            .getElementById("fecha_finalizacion")
            .setAttribute("required", "");
    } else if (finalizoEstudios === "no") {
        fechaFinalizacionContainer.style.display = "none";
        fechaUltimoSemestreContainer.style.display = "block";
        document
            .getElementById("fecha_finalizacion")
            .removeAttribute("required");
        document
            .getElementById("fecha_ultimo_semestre")
            .setAttribute("required", "");
    } else {
        fechaFinalizacionContainer.style.display = "none";
        fechaUltimoSemestreContainer.style.display = "none";
    }
    validarSENA(); // Llamar a la validación cada vez que cambie la selección
}

function validarSENA() {
    const institucion = document.getElementById("institucion").value;
    const finalizoEstudios = document.getElementById("finalizo_estudios").value;
    const mensajeSENA = document.getElementById("mensajeSENA");

    if (institucion === "SENA" && finalizoEstudios === "no") {
        if (!mensajeSENA) {
            const mensaje = document.createElement("div");
            mensaje.id = "mensajeSENA";
            mensaje.classList.add("alert", "alert-danger");
            mensaje.textContent =
                "Usuario, recuerde que para poder hacer una homologación con el SENA tuvo que haber finalizado sus estudios. De lo contrario, no podrá seguir con el proceso de homologación.";
            document
                .getElementById("finalizo_estudios")
                .parentElement.appendChild(mensaje);
        }
    } else {
        if (mensajeSENA) {
            mensajeSENA.remove();
        }
    }
}

function validarFormularioStep2() {
    let valido = true;

    // Obtener los campos del formulario
    const institucion = document.getElementById("institucion");
    const tipoFormacion = document.getElementById("tipoFormacion");
    const carrera = document.getElementById("programa");
    const finalizoEstudios = document.getElementById("finalizo_estudios");
    const fechaFinalizacion = document.getElementById("fecha_finalizacion");
    const fechaUltimoSemestre = document.getElementById(
        "fecha_ultimo_semestre"
    );

    // Expresión regular para validar fechas en formato YYYY-MM-DD
    const regexFecha = /^\d{4}-\d{2}-\d{2}$/;

    // Validación extra: Si es del SENA y no finalizó estudios, no permite avanzar
    if (institucion.value === "SENA" && finalizoEstudios.value === "no") {
        alert(
            "No puede continuar con la homologación si no ha finalizado sus estudios en el SENA."
        );
        return false;
    }

    // Función para validar campo obligatorio y mostrar mensajes de error
    function validarCampo(campo, mensaje) {
        let errorMensaje = campo.parentElement.querySelector(".error-message");
        if (!errorMensaje) {
            errorMensaje = document.createElement("span");
            errorMensaje.classList.add("error-message");
            campo.parentElement.appendChild(errorMensaje);
        }

        if (!campo.value.trim()) {
            campo.classList.add("error");
            errorMensaje.textContent = mensaje;
            valido = false;
        } else {
            campo.classList.remove("error");
            errorMensaje.textContent = "";
        }
    }

    // Validar los campos obligatorios
    validarCampo(institucion, "Seleccione su institución de origen");
    validarCampo(tipoFormacion, "Seleccione su tipo de formación");
    validarCampo(carrera, "Seleccione su carrera o programa");
    validarCampo(finalizoEstudios, "Seleccione si finalizó sus estudios");

    // Validar fechas según la selección de finalización de estudios
    if (finalizoEstudios.value === "si") {
        validarCampo(
            fechaFinalizacion,
            "Ingrese la fecha de finalización de sus estudios"
        );
        if (!regexFecha.test(fechaFinalizacion.value)) {
            fechaFinalizacion.classList.add("error");
            fechaFinalizacion.parentElement.querySelector(
                ".error-message"
            ).textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
        fechaUltimoSemestre.classList.remove("error");
        if (fechaUltimoSemestre.parentElement.querySelector(".error-message")) {
            fechaUltimoSemestre.parentElement.querySelector(
                ".error-message"
            ).textContent = "";
        }
    } else if (finalizoEstudios.value === "no") {
        validarCampo(
            fechaUltimoSemestre,
            "Ingrese la fecha del último semestre cursado"
        );
        if (!regexFecha.test(fechaUltimoSemestre.value)) {
            fechaUltimoSemestre.classList.add("error");
            fechaUltimoSemestre.parentElement.querySelector(
                ".error-message"
            ).textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
        fechaFinalizacion.classList.remove("error");
        if (fechaFinalizacion.parentElement.querySelector(".error-message")) {
            fechaFinalizacion.parentElement.querySelector(
                ".error-message"
            ).textContent = "";
        }
    }

    // Si la validación es correcta, avanzar al siguiente paso
    if (valido) {
        mostrarMensaje("Paso 2 finalizado correctamente", "success");
        changeStep(1);
    }
}

// Realice cambio

function updateSemestres() {
    const programaId = document.getElementById("programa").value;
    const semestreSelect = document.getElementById("semestre");
    const asignaturaSelect = document.getElementById("materia");

    // Resetear selects
    semestreSelect.innerHTML =
        '<option value="">Seleccione un semestre</option>';
    asignaturaSelect.innerHTML =
        '<option value="">Seleccione una asignatura</option>';
    semestreSelect.disabled = true;
    asignaturaSelect.disabled = true;

    if (!programaId) return;

    // Llamada a la ruta del backend
    fetch(
        `http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`
    )
        .then((response) => response.json())
        .then((data) => {
            const asignaturas = data.data;

            // Extraer semestres únicos
            const semestresUnicos = [
                ...new Set(asignaturas.map((a) => a.semestre)),
            ].sort((a, b) => a - b);

            // Llenar el select de semestres
            semestresUnicos.forEach((sem) => {
                const option = document.createElement("option");
                option.value = sem;
                option.textContent = `Semestre ${sem}`;
                semestreSelect.appendChild(option);
            });

            // Guardar asignaturas temporalmente en una variable global
            window.asignaturasPorPrograma = asignaturas;

            semestreSelect.disabled = false;
        })
        .catch((err) => {
            console.error("Error al cargar asignaturas:", err);
        });
}

function updateAsignaturas() {
    const semestre = parseInt(document.getElementById("semestre").value);
    const asignaturaSelect = document.getElementById("materia");

    asignaturaSelect.innerHTML =
        '<option value="">Seleccione una materia</option>';
    asignaturaSelect.disabled = true;

    if (!semestre || !window.asignaturasPorPrograma) return;

    const filtradas = window.asignaturasPorPrograma.filter(
        (a) => a.semestre === semestre
    );

    filtradas.forEach((a) => {
        const option = document.createElement("option");
        option.value = a.id_asignatura;
        option.textContent = a.nombre;
        asignaturaSelect.appendChild(option);
    });

    asignaturaSelect.disabled = false;
}

// const todasLasAsignaturas = @json($asignaturas);
// const programasPorInstitucion = @json($programas);
// Reemplaza las líneas anteriores con una asignación válida desde tu HTML o inicialízalas como vacías si no tienes los datos aún:
const todasLasAsignaturas = [];
const programasPorInstitucion = [];

function updateFormacion() {
    const institucionSelect = document.getElementById("institucion");
    const tipoInput = document.getElementById("tipoFormacion");
    const programaSelect = document.getElementById("programa");
    const selectedOption =
        institucionSelect.options[institucionSelect.selectedIndex];

    // Mostrar tipo de formación
    tipoInput.value = selectedOption.dataset.formacion || "";

    // Limpiar programas anteriores
    programaSelect.innerHTML =
        '<option value="">Seleccione un Programa</option>';
    document.getElementById("semestre").innerHTML =
        '<option value="">Seleccione un semestre</option>';
    document.getElementById("materia").innerHTML =
        '<option value="">Seleccione una materia</option>';
    document.getElementById("semestre").disabled = true;
    document.getElementById("materia").disabled = true;

    // Obtener ID de la institución seleccionada
    const institucionId = institucionSelect.value;

    if (!institucionId) {
        programaSelect.disabled = true;
        return;
    }

    // Filtrar programas de la institución seleccionada
    const programasFiltrados = programasPorInstitucion.filter(
        (p) => p.id_institucion == institucionId
    );
    programasFiltrados.forEach((programa) => {
        const option = document.createElement("option");
        option.value = programa.id_programa;
        option.textContent = programa.nombre;
        programaSelect.appendChild(option);
    });

    programaSelect.disabled = programasFiltrados.length === 0;
}

// Realice cambio
function agregarMateria() {
    const semestre = document.getElementById("semestre").value;
    const materiaSeleccionada = document.getElementById("materia").value;

    if (!semestre || !materiaSeleccionada) {
        mostrarMensaje(
            "Debe seleccionar una carrera, un semestre y una materia.",
            "error"
        );
        return;
    }

    let materiaId = `nota_${semestre}_${materiaSeleccionada.replace(
        /\s+/g,
        "_"
    )}`;
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
        mostrarMensaje(
            "Esta materia ya ha sido seleccionada en este semestre.",
            "error"
        );
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
    mostrarMensaje(
        `Materia "${materiaSeleccionada}" agregada correctamente.`,
        "success"
    );

    // Reordenar los semestres en el DOM
    ordenarSemestresAlfabeticamente();
}

function borrarMateria(materiaId) {
    const materiaRow = document
        .getElementById(materiaId)
        .closest(".materia-row");
    const materiaNombre =
        materiaRow.querySelector(".materia-label").textContent;
    const semestre = materiaRow.closest(".semestre-container");

    // Mostrar modal de confirmación
    mostrarModalConfirmacion(
        "Eliminar Materia",
        `¿Está seguro que desea eliminar la materia "${materiaNombre}"?`,
        "Eliminar",
        () => {
            if (!materiaRow) return; // Evita errores si no se encuentra la materia

            // Eliminar la nota del localStorage si existe
            let notas = JSON.parse(localStorage.getItem("notas")) || {};
            if (notas[materiaId]) {
                delete notas[materiaId];
                localStorage.setItem("notas", JSON.stringify(notas));
            }

            // Eliminar la fila de la materia
            materiaRow.remove();

            // Verificar si el contenedor del semestre está vacío (sin materias)
            if (
                semestre &&
                semestre.querySelectorAll(".materia-row").length === 0
            ) {
                semestre.remove();
            }

            // Mostrar mensaje de éxito
            mostrarMensaje(
                `Materia "${materiaNombre}" ha sido eliminada correctamente.`,
                "success"
            );
        }
    );
}

function verificarDatos() {
    console.log(
        "Instituciones cargadas:",
        document.getElementById("institucion").options.length
    );
    if (typeof todosLosProgramas !== "undefined") {
        console.log("Programas disponibles:", todosLosProgramas.length);
        console.log("Ejemplo de programa:", todosLosProgramas[0]);
    } else {
        console.error("La variable todosLosProgramas no está definida");
    }
}

function ordenarSemestresAlfabeticamente() {
    let materiasContainer = document.getElementById("materias-container");
    let semestres = Array.from(
        document.querySelectorAll(".semestre-container")
    );

    // Ordenar los semestres alfabéticamente por su atributo de semestre
    semestres.sort((a, b) => {
        let nombreA = a.getAttribute("data-semestre").toLowerCase();
        let nombreB = b.getAttribute("data-semestre").toLowerCase();
        return nombreA.localeCompare(nombreB);
    });

    // Crear un fragmento para mejorar el rendimiento
    let fragment = document.createDocumentFragment();
    semestres.forEach((semestre) => fragment.appendChild(semestre));

    // Limpiar y volver a agregar los semestres ordenados
    materiasContainer.innerHTML = "";
    materiasContainer.appendChild(fragment);
}

//Obtener materias seleccionadas

function obtenerMaterias() {
    const materiasContainer = document.getElementById("materias-container");
    const semestres = materiasContainer.querySelectorAll(".semestre-container");

    // Crear un objeto simple y limpio
    let resultado = {};

    semestres.forEach((semestreDiv) => {
        const semestre = semestreDiv.getAttribute("data-semestre");
        const materias = semestreDiv.querySelectorAll(".materia-row");

        // Inicializar el semestre como un array vacío
        resultado[semestre] = [];

        materias.forEach((materiaRow) => {
            const label = materiaRow.querySelector(".materia-label");
            const input = materiaRow.querySelector(".nota-input");

            // Sanitizar el nombre de la materia (eliminar caracteres especiales y HTML)
            let materiaNombre = "";
            if (label) {
                // Obtener sólo texto plano
                materiaNombre = label.textContent.replace(":", "").trim();

                // Si el label contiene un ID o código numérico, usarlo directamente
                // Por ejemplo, si el formato es "Matemáticas (158)", extraer solo el 158
                const codigoMatch = materiaNombre.match(/\((\d+)\)/);
                if (codigoMatch && codigoMatch[1]) {
                    materiaNombre = codigoMatch[1]; // Solo el código numérico
                }
            }

            // Sanitizar la nota y asegurarse de que es un formato válido
            let nota = "";
            if (input && input.value) {
                nota = input.value.trim().replace(/[^\d,\.]/g, ""); // Solo permitir números, comas y puntos
            }

            // Solo agregar materias con datos válidos
            if (materiaNombre && nota) {
                resultado[semestre].push({
                    nombre: materiaNombre,
                    nota: nota,
                });
            }
        });

        // Si no hay materias en este semestre, eliminar el array vacío
        if (resultado[semestre].length === 0) {
            delete resultado[semestre];
        }
    });

    // Validar que tenemos un objeto con al menos un semestre
    if (Object.keys(resultado).length === 0) {
        console.warn("No se encontraron materias para enviar");
        return {}; // Objeto vacío, pero válido para JSON
    }

    // Convertir a cadena JSON y verificar que sea válida
    try {
        const jsonString = JSON.stringify(resultado);
        // Verificar que el JSON sea válido convirtiéndolo de vuelta a objeto
        JSON.parse(jsonString);
        console.log("JSON de materias válido:", jsonString);
        return resultado;
    } catch (error) {
        console.error("Error al generar JSON de materias:", error);
        // Retornar un objeto vacío válido en caso de error
        return {};
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const programaSelect = document.getElementById("programa");
    const semestresContainer = document.getElementById("semestres");

    programaSelect.addEventListener("change", function () {
        const programaId = this.value;

        if (programaId) {
            fetch(
                `http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`
            )
                .then((response) => response.json())
                .then((data) => {
                    console.log("📦 Datos recibidos:", data); // 🔍 Verifica aquí en consola

                    if (data.success) {
                        mostrarAsignaturas(data.data);
                    } else {
                        alert("No se pudieron obtener las asignaturas.");
                    }
                })
                .catch((error) => {
                    console.error("❌ Error en la petición:", error);
                });
        }
    });

    function mostrarAsignaturas(asignaturas) {
        semestresContainer.innerHTML = ""; // Limpia contenido anterior

        const asignaturasPorSemestre = {};

        // Agrupa por semestre
        asignaturas.forEach((asig) => {
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
                    ${asignaturasPorSemestre[semestre]
                        .map(
                            (asig) =>
                                `<li>${asig.nombre} - ${asig.codigo_asignatura}</li>`
                        )
                        .join("")}
                </ul>
            `;
            semestresContainer.appendChild(div);
        }
    }
});

function validarNota(input) {
    let valor = input.value;

    // Permitir solo números del 0 al 5, una coma y un decimal de 0 a 9
    valor = valor.replace(/[^0-9,]/g, "");

    // Verificar si hay más de una coma y eliminar las extras
    let partes = valor.split(",");
    if (partes.length > 2) {
        valor = partes[0] + "," + partes[1].slice(0, 1); // Mantener solo un decimal
    }

    // Si el usuario empieza con coma, corregirlo
    if (valor.startsWith(",")) {
        valor = "0" + valor; // Asegurar que "0," sea válido
    }

    // Convertir a número y validar rango
    let numero = parseFloat(valor.replace(",", "."));

    if (!isNaN(numero) && numero >= 0 && numero <= 5) {
        input.value = valor; // Mantener el valor formateado sin afectar la edición
    } else {
        input.value = ""; // Borrar si está fuera de rango
    }
}

function mostrarModalConfirmacion(
    titulo,
    mensaje,
    textoBotonConfirmar = "Aceptar",
    onConfirm = null
) {
    // Crear el modal
    const modal = document.createElement("div");
    modal.id = "modalConfirmacion"; // Add ID for styling
    modal.classList.add("modal", "modal-confirmacion");

    modal.innerHTML = `
        <div class="modal-content">
            <div class="close-icon">&times;</div>
            <h3>${titulo}</h3>
            <p>${mensaje.replace(/\n/g, "<br>")}</p>
            <div>
                <button class="btn-cancelar">Cancelar</button>
                <button class="btn-confirmar">${textoBotonConfirmar}</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Añadir un pequeño retardo para la animación
    requestAnimationFrame(() => {
        modal.classList.add("active");
    });

    // Referencias a botones
    const closeIcon = modal.querySelector(".close-icon");
    const btnCancelar = modal.querySelector(".btn-cancelar");
    const btnConfirmar = modal.querySelector(".btn-confirmar");

    // Función para cerrar el modal
    const cerrarModal = () => {
        modal.classList.remove("active");
        modal.classList.add("closing");

        setTimeout(() => {
            modal.remove();
        }, 300);
    };

    // Eventos de cierre
    closeIcon.addEventListener("click", cerrarModal);
    btnCancelar.addEventListener("click", cerrarModal);

    // Evento de confirmación
    btnConfirmar.addEventListener("click", () => {
        if (onConfirm) {
            onConfirm();
        }
        cerrarModal();
    });

    return modal;
}

function validacionStep3() {
    // Seleccionar todos los inputs de notas
    let materias = document.querySelectorAll(".nota-input");

    // Validar que haya al menos 6 materias
    if (materias.length < 6) {
        mostrarModalConfirmacion(
            "Error",
            "Debe registrar al menos 6 materias antes de continuar.",
            "Cerrar"
        );
        return false;
    }

    // Arrays para almacenar errores y materias guardadas
    let errores = [];
    let materiasGuardadas = [];

    // Iterar sobre cada input de nota
    materias.forEach((input) => {
        // Obtener el label de la materia
        const materiaRow = input.closest(".materia-row");
        if (!materiaRow) {
            errores.push("Error al procesar una materia");
            return;
        }

        const materiaLabel = materiaRow.querySelector(".materia-label");
        if (!materiaLabel) {
            errores.push("No se encontró el label de la materia");
            return;
        }

        const materiaNombre = materiaLabel.textContent;
        const nota = input.value.trim();

        // Validar que la nota no esté vacía
        if (nota === "") {
            errores.push(
                `La materia ${materiaNombre} no tiene nota registrada.`
            );
            return;
        }

        // Convertir la nota, reemplazando coma por punto
        const notaNumero = parseFloat(nota.replace(",", "."));

        // Validar el formato de la nota
        if (isNaN(notaNumero) || notaNumero < 0 || notaNumero > 5) {
            errores.push(`La nota de ${materiaNombre} no es válida: ${nota}`);
            return;
        }

        // Guardar nota en localStorage
        try {
            let notas = JSON.parse(localStorage.getItem("notas")) || {};
            notas[input.id] = notaNumero.toFixed(1);
            localStorage.setItem("notas", JSON.stringify(notas));

            // Agregar a materias guardadas
            materiasGuardadas.push({
                materia: materiaNombre,
                nota: notaNumero.toFixed(1),
            });
        } catch (error) {
            errores.push(
                `Error al guardar la nota de ${materiaNombre}: ${error.message}`
            );
        }
    });

    // Manejar errores si los hay
    if (errores.length > 0) {
        mostrarModalConfirmacion(
            "Errores",
            `Se encontraron los siguientes errores:<br>${errores.join("<br>")}`,
            "Cerrar"
        );
        return false;
    }

    // Guardar materias en localStorage
    try {
        localStorage.setItem(
            "materiasGuardadas",
            JSON.stringify(materiasGuardadas)
        );
    } catch (error) {
        mostrarModalConfirmacion(
            "Error de Almacenamiento",
            "No se pudieron guardar las materias. Inténtelo de nuevo.",
            "Cerrar"
        );
        return false;
    }

    // Mostrar confirmación
    mostrarModalConfirmacion(
        "Materias Guardadas",
        `Materias guardadas correctamente:<br>${materiasGuardadas
            .map((m) => `${m.materia}: ${m.nota}`)
            .join("<br>")}`,
        "Aceptar",
        () => {
            // Verificar que changeStep y mostrarMensaje estén definidas
            if (typeof mostrarMensaje === "function") {
                mostrarMensaje("Paso 3 finalizado correctamente", "success");
            }
            if (typeof changeStep === "function") {
                changeStep(1);
            }
        }
    );

    return true;
}

function validarFormularioStep1() {
    let valido = true;

    // Obtener los campos
    const tipoIdentificacion = document.getElementById("tipo_identificacion");
    const numeroIdentificacion = document.getElementById(
        "numero_identificacion"
    );
    const primerNombre = document.getElementById("primer_nombre");
    const segundoNombre = document.getElementById("segundo_nombre");
    const primerApellido = document.getElementById("primer_apellido");
    const segundoApellido = document.getElementById("segundo_apellido");
    const email = document.getElementById("email");
    const telefono = document.getElementById("telefono");
    const direccion = document.getElementById("direccion");
    const municipio = document.getElementById("municipio");
    const departamento = document.getElementById("departamento");

    // Expresiones regulares

    const regexTexto = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios
    const regexEmail =
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/;
    const regexTelefono = /^\d{7,10}$/; // Teléfonos de 7 a 10 dígitos
    const regexNumeroIdentificacion = /^\d+$/; // Solo números

    // Función para validar campo obligatorio
    function validarCampo(campo, mensaje) {
        const errorMensaje = campo.nextElementSibling;
        if (!campo.value.trim()) {
            campo.classList.add("error");
            if (errorMensaje) errorMensaje.textContent = mensaje;
            valido = false;
        } else {
            campo.classList.remove("error");
            if (errorMensaje) errorMensaje.textContent = "";
        }
    }

    // Validar campos obligatorios
    validarCampo(tipoIdentificacion, "Seleccione un tipo de identificación");
    validarCampo(numeroIdentificacion, "Ingrese su número de identificación");
    validarCampo(primerNombre, "Ingrese su primer nombre");
    validarCampo(primerApellido, "Ingrese su primer apellido");
    validarCampo(email, "Ingrese un correo electrónico");
    validarCampo(telefono, "Ingrese su teléfono");
    validarCampo(direccion, "Ingrese su dirección");
    validarCampo(municipio, "Ingrese su municipio");
    validarCampo(departamento, "Ingrese su departamento");

    // Validar que los nombres y apellidos solo contengan letras
    [primerNombre, segundoNombre, primerApellido, segundoApellido].forEach(
        (campo) => {
            const errorMensaje = campo.nextElementSibling;
            if (campo.value.trim() && !regexTexto.test(campo.value)) {
                campo.classList.add("error");
                if (errorMensaje)
                    errorMensaje.textContent = "Solo se permiten letras";
                valido = false;
            }
        }
    );
    // Validar número de identificación (solo números)
    const errorNumeroIdentificacion = numeroIdentificacion.nextElementSibling;
    if (!regexNumeroIdentificacion.test(numeroIdentificacion.value)) {
        numeroIdentificacion.classList.add("error");
        if (errorNumeroIdentificacion)
            errorNumeroIdentificacion.textContent = "Ingrese solo números";
        valido = false;
    }

    // Validar email y confirmar que coincidan
    const errorEmail = email.nextElementSibling;
    if (!regexEmail.test(email.value)) {
        email.classList.add("error");
        if (errorEmail) errorEmail.textContent = "Ingrese un correo válido";
        valido = false;
    }

    // Validar teléfono (solo números y de 7 a 10 dígitos)
    const errorTelefono = telefono.nextElementSibling;
    if (!regexTelefono.test(telefono.value)) {
        telefono.classList.add("error");
        if (errorTelefono)
            errorTelefono.textContent =
                "Ingrese un número de teléfono válido (7 a 10 dígitos)";
        valido = false;
    }

    // Si todo es válido, mostrar mensaje y avanzar al siguiente paso
    if (valido) {
        mostrarMensaje("Paso 1 finalizado correctamente", "success");
        changeStep(1);
    }
}

// Agregar estilos para el modal
const estilosModal = document.createElement("style");
estilosModal.textContent = `
/* Estilos para el modal de confirmación */
:root {
    --azul-oscuro: #19407b;
    --azul-medio: #0075bf;
    --azul-claro: #08dcff;
    --blanco: #ffffff;
    --gris-claro: #f4f4f4;
    --gris-medio: #e0e0e0;
    --borde: #dddddd;
    --sombra: rgba(0, 0, 0, 0.1);
    --negro-transparente: rgba(0, 0, 0, 0.5);
    --rojo-error: #ff4d4d;
    --rojo-hover: #c0392b;
    --verde-exito: #4CAF50;
    --font-primary: 'Roboto', Arial, sans-serif;
    --transition-default: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Estilos generales de los modales */
.modal,
#modalConfirmacion,
#modalConfirmacionEnvio {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: var(--negro-transparente);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    backdrop-filter: blur(8px);

}

.modal.active,
#modalConfirmacion.active,
#modalConfirmacionEnvio.active {
    opacity: 1;
    visibility: visible;
}

/* Contenedor del modal */
.modal-content,
#modalConfirmacion > div,
#modalConfirmacionEnvio > div {
    background-color: var(--blanco);
    padding: 28px 24px;
    border-radius: 12px;
    text-align: center;
    max-width: 400px;
    width: 80%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: translateY(30px) scale(0.95);
    opacity: 0;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                opacity 0.3s ease;
    border-top: 5px solid var(--azul-medio);
}

.modal.active .modal-content,
#modalConfirmacion.active > div,
#modalConfirmacionEnvio.active > div {
    transform: translateY(0) scale(1);
    opacity: 1;
    animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Animación de entrada */
@keyframes scaleIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Estilos de los títulos */
.modal-content h3,
#modalConfirmacion h3,
#modalConfirmacionEnvio h3 {
    margin-top: 0;
    color: #333;
    font-size: 22px;
    margin-bottom: 16px;
}

/* Estilos de los párrafos */
.modal-content p,
#modalConfirmacion p,
#modalConfirmacionEnvio p {
    color: #666;
    margin-bottom: 16px;
    line-height: 1.5;
}

/* Botones */
.modal-content button,
#modalConfirmacion button,
#modalConfirmacionEnvio button {
    font-size: 16px;
    font-weight: bold;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin: 10px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    background: var(--azul-medio);
    color: var(--blanco);
    box-shadow: 0 4px 8px var(--sombra);
}

.modal-content button:hover,
#modalConfirmacion button:hover,
#modalConfirmacionEnvio button:hover {
    background: var(--azul-oscuro);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.modal-content button:active,
#modalConfirmacion button:active,
#modalConfirmacionEnvio button:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Efecto de onda en botones */
.modal-content button::after,
#modalConfirmacion button::after,
#modalConfirmacionEnvio button::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
    background-image: radial-gradient(circle, rgba(255, 255, 255, 0.3) 10%, transparent 10.01%);
    background-repeat: no-repeat;
    background-position: 50%;
    transform: scale(10, 10);
    opacity: 0;
    transition: transform .5s, opacity 1s;
}

.modal-content button:active::after,
#modalConfirmacion button:active::after,
#modalConfirmacionEnvio button:active::after {
    transform: scale(0, 0);
    opacity: .3;
    transition: 0s;
}

/* Botón de Cancelar */
#modalConfirmacion button:last-of-type {
    background: var(--rojo-error);
    color: var(--blanco);
    box-shadow: 0 2px 5px rgba(231, 76, 60, 0.3);
}

#modalConfirmacion button:last-of-type:hover {
    background: var(--rojo-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.4);
}

/* Animación de cierre */
.modal.closing,
#modalConfirmacionEnvio.closing {
    transform: scale(0.95);
    opacity: 0;
}

/* Botón de cerrar */
.close-icon {
    position: absolute;
    top: 15px;
    right: 15px;
    cursor: pointer;
    font-size: 20px;
    color: #999;
    transition: color 0.2s ease, transform 0.2s ease;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close-icon:hover {
    color: #333;
    transform: rotate(90deg);
    background-color: rgba(0, 0, 0, 0.05);
}

`;
document.head.appendChild(estilosModal);

// Event listeners
document.getElementById("carrera").addEventListener("change", updateSemestres);
document
    .getElementById("semestre")
    .addEventListener("change", updateMateriasPorSemestre);
// STEP 4 //
// Función para verificar si el usuario es extranjero cuando llega al Step 4
document.addEventListener("DOMContentLoaded", function () {
    const tipoIdentificacion = document.getElementById("tipo_identificacion");
    const extraDocsSection = document.getElementById("extra-docs");
    const visaPasaporte = document.getElementById("visa_pasaporte");

    const finalizoEstudios = document.getElementById("finalizo_estudios");
    const fechaFinalizacion = document.getElementById("fecha_finalizacion");
    const certificacionFinalizacion = document.getElementById(
        "certificacion_finalizacion"
    );
    const certificacionFinalizacionContainer =
        certificacionFinalizacion.parentElement;

    // Función para verificar si se deben mostrar los documentos adicionales (Extranjeros)
    function verificarDocumentosExtranjero() {
        if (tipoIdentificacion.value === "Cédula de Extranjería") {
            extraDocsSection.classList.remove("hidden");
            visaPasaporte.setAttribute("required", "true");
        } else {
            extraDocsSection.classList.add("hidden");
            visaPasaporte.removeAttribute("required");

            // Resetear el campo si el usuario cambia de extranjero a nacional
            const nuevoInput = visaPasaporte.cloneNode(true);
            visaPasaporte.parentNode.replaceChild(nuevoInput, visaPasaporte);
        }
    }

    // Ejecutar la función cuando el usuario cambie la selección en Step 1
    tipoIdentificacion.addEventListener(
        "change",
        verificarDocumentosExtranjero
    );

    // Función para verificar si el usuario finalizó estudios
    function verificarFinalizacionEstudios() {
        const regexFecha = /^\d{4}-\d{2}-\d{2}$/;

        if (finalizoEstudios.value === "si") {
            // Mostrar campos requeridos
            fechaFinalizacion.classList.remove("hidden");
            fechaFinalizacion.setAttribute("required", "true");

            certificacionFinalizacionContainer.classList.remove("hidden");
            certificacionFinalizacion.setAttribute("required", "true");

            // Validar fecha de finalización
            if (!regexFecha.test(fechaFinalizacion.value)) {
                fechaFinalizacion.classList.add("error");
                mostrarMensaje(
                    "Ingrese una fecha válida de finalización (YYYY-MM-DD)",
                    "error"
                );
                return false;
            } else {
                fechaFinalizacion.classList.remove("error");
            }
        } else {
            // Ocultar y limpiar los campos si no finalizó estudios
            fechaFinalizacion.classList.add("hidden");
            fechaFinalizacion.removeAttribute("required");
            fechaFinalizacion.value = "";

            certificacionFinalizacionContainer.classList.add("hidden");
            certificacionFinalizacion.removeAttribute("required");
            certificacionFinalizacion.value = "";
        }

        return true;
    }

    // Ejecutar la función cuando el usuario cambie la selección de finalización de estudios
    finalizoEstudios.addEventListener("change", verificarFinalizacionEstudios);

    // Función para validar archivos subidos
    function validarArchivo(input, mensaje) {
        const errorMensaje = input.nextElementSibling;
        if (!input.files.length) {
            input.classList.add("error");
            errorMensaje.textContent = mensaje;
            return false;
        } else {
            input.classList.remove("error");
            errorMensaje.textContent = "";
            return true;
        }
    }

    // Función para validar el formulario en Step 4
    function validarFormularioStep4() {
        let valido = true;

        // Obtener los campos de documentos
        const documentoId = document.getElementById("documento_id");
        const certificadoNotas = document.getElementById("certificado_notas");
        const contenidoProgramatico = document.getElementById(
            "contenido_programatico"
        );
        const cartaHomologacion = document.getElementById("carta_homologacion");

        // Determinar si el usuario es extranjero
        const esExtranjero = tipoIdentificacion.value === "TE";

        // Validar documentos obligatorios
        valido &= validarArchivo(
            documentoId,
            "Debe subir su Documento de Identidad."
        );
        valido &= validarArchivo(
            certificadoNotas,
            "Debe subir su Certificado de Notas."
        );
        valido &= validarArchivo(
            contenidoProgramatico,
            "Debe subir el Contenido Programático."
        );
        valido &= validarArchivo(
            cartaHomologacion,
            "Debe subir la Carta de Solicitud de Homologación."
        );

        // Validar Certificación de Finalización de Estudios si el usuario finalizó estudios
        if (finalizoEstudios.value === "si") {
            valido &= validarArchivo(
                certificacionFinalizacion,
                "Debe subir la Certificación de Finalización de Estudios."
            );
        }

        // Validar documentos adicionales solo si es extranjero
        if (esExtranjero) {
            extraDocsSection.classList.remove("hidden");
            visaPasaporte.setAttribute("required", "true");
            valido &= validarArchivo(
                visaPasaporte,
                "Debe subir una copia de su Visa o Pasaporte."
            );
        } else {
            extraDocsSection.classList.add("hidden");
            visaPasaporte.removeAttribute("required");
            visaPasaporte.value = "";
        }

        // Si todo es válido, avanzar al siguiente paso
        if (valido) {
            mostrarMensaje("Paso 4 finalizado correctamente", "success");
            changeStep(1);
        }
    }

    // Exponer funciones para que puedan ser usadas en HTML
    window.verificarDocumentosExtranjero = verificarDocumentosExtranjero;
    window.verificarFinalizacionEstudios = verificarFinalizacionEstudios;
    window.validarFormularioStep4 = validarFormularioStep4;
});

// Asegurar que solo se acepten archivos PDF
document.querySelectorAll("input[type='file']").forEach((input) => {
    input.addEventListener("change", function () {
        if (this.files.length > 0 && this.files[0].type !== "application/pdf") {
            alert("Solo se permiten archivos en formato PDF.");
            this.value = ""; // Borra el archivo si no es PDF
        }
    });
});

// STEP 5
function confirmarDatos() {
    // Validar campos obligatorios
    const tipoIdentificacion = document.getElementById(
        "tipo_identificacion"
    ).value;
    const numeroIdentificacion = document.getElementById(
        "numero_identificacion"
    ).value;
    const primerNombre = document.getElementById("primer_nombre").value;
    const segundoNombre =
        document.getElementById("segundo_nombre").value || "(No aplica)";
    const primerApellido = document.getElementById("primer_apellido").value;
    const segundoApellido =
        document.getElementById("segundo_apellido").value || "(No aplica)";
    const email = document.getElementById("email").value;
    const telefono = document.getElementById("telefono").value;

    if (
        !tipoIdentificacion ||
        !numeroIdentificacion ||
        !primerNombre ||
        !primerApellido ||
        !email ||
        !telefono
    ) {
        alert(
            "Por favor, completa todos los campos obligatorios antes de enviar."
        );
        return;
    }

    let mensaje = `
        <h3>¿Están correctos estos datos?</h3>
        <p><strong>Tipo de Identificación:</strong> ${tipoIdentificacion}</p>
        <p><strong>Número de Identificación:</strong> ${numeroIdentificacion}</p>
        <p><strong>Nombre Completo:</strong> ${primerNombre} ${segundoNombre} ${primerApellido} ${segundoApellido}</p>
        <p><strong>Correo Electrónico:</strong> ${email}</p>
        <p><strong>Teléfono:</strong> ${telefono}</p>
        <br>
        <button id="confirmarBtn">Confirmar</button>
        <button onclick="cerrarModal()">Rechazar</button>
    `;

    mostrarModal(mensaje, "modalConfirmacion");

    setTimeout(() => {
        document
            .getElementById("confirmarBtn")
            .addEventListener("click", enviarFormulario);
    }, 100);
}

function mostrarModal(contenido, id) {
    let modal = document.createElement("div");
    modal.id = id;
    modal.classList.add("modal");

    let modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");
    modalContent.innerHTML = contenido;

    modal.appendChild(modalContent);
    document.body.appendChild(modal);
    agregarEstilosModalFinal();
}

function cerrarModal() {
    let modal = document.getElementById("modalConfirmacion");
    if (modal) modal.remove();
}

function enviarFormulario() {
    const formData = new FormData();

    try {
        // Usuario
        formData.append(
            "tipo_identificacion",
            document.getElementById("tipo_identificacion").value
        );
        formData.append(
            "numero_identificacion",
            document.getElementById("numero_identificacion").value
        );
        formData.append(
            "primer_nombre",
            document.getElementById("primer_nombre").value
        );
        formData.append(
            "segundo_nombre",
            document.getElementById("segundo_nombre").value || ""
        );
        formData.append(
            "primer_apellido",
            document.getElementById("primer_apellido").value
        );
        formData.append(
            "segundo_apellido",
            document.getElementById("segundo_apellido").value || ""
        );
        formData.append("email", document.getElementById("email").value);
        formData.append("telefono", document.getElementById("telefono").value);
        formData.append(
            "direccion",
            document.getElementById("direccion").value
        );
        formData.append("pais", document.getElementById("pais").value);

        // Capturar el ID del departamento desde data-id
        let departamentoSelect = document.getElementById("departamento");
        let idDepartamentoSeleccionado =
            departamentoSelect.options[
                departamentoSelect.selectedIndex
            ].getAttribute("data-id");
        formData.append("departamento", idDepartamentoSeleccionado);

        formData.append(
            "municipio",
            document.getElementById("municipio").value
        );

        // Solicitud
        formData.append("programa", document.getElementById("programa").value);
        const finalizo = document.getElementById("finalizo_estudios").value;
        formData.append("finalizo_estudios", finalizo);

        if (finalizo === "si") {
            formData.append(
                "fecha_finalizacion",
                document.getElementById("fecha_finalizacion").value
            );
            formData.append("fecha_ultimo_semestre", "");
        } else if (finalizo === "no") {
            formData.append(
                "fecha_ultimo_semestre",
                document.getElementById("fecha_ultimo_semestre").value
            );
            formData.append("fecha_finalizacion", "");
        }

        // Generar el número de radicado
        const año = new Date().getFullYear();
        const contador = Math.floor(Math.random() * 1000) + 1;
        const contadorFormateado = String(contador).padStart(4, "0");
        const numeroRadicado = `HOM-${año}-${contadorFormateado}`;

        formData.append("numero_rad", numeroRadicado);
        formData.append("password", "12345678");

        // Asignaturas - Proceso mejorado
        const materias = obtenerMaterias();

        // Verificar que el objeto de materias es válido y no está vacío
        if (Object.keys(materias).length > 0) {
            // Convertir a JSON y sanitizar
            const materiasJSON = JSON.stringify(materias);

            // Validar el JSON antes de enviarlo
            try {
                // Intenta parsear el JSON para verificar que sea válido
                JSON.parse(materiasJSON);
                formData.append("materias", materiasJSON);
                console.log("JSON de materias válido:", materiasJSON);
            } catch (error) {
                console.error("Error al validar JSON de materias:", error);
                alert(
                    "Error al procesar las materias. Por favor revise los datos ingresados."
                );
                return; // Detener el envío
            }
        } else {
            alert("No se han ingresado materias para homologar.");
            return; // Detener el envío
        }

        // Documentos
        const documentos = [
            { id: "certificado_notas", tipo: "Certificado de Notas" },
            { id: "contenido_programatico", tipo: "Contenido Programático" },
            { id: "carta_homologacion", tipo: "Carta de Solicitud" },
            {
                id: "certificacion_finalizacion",
                tipo: "Certificación de Finalización de Estudios",
            },
            { id: "visa_pasaporte", tipo: "Copia de la Visa" }, // O Copia del Pasaporte, dependiendo de tu lógica
        ];

        documentos.forEach((doc) => {
            const fileInput = document.getElementById(doc.id);
            if (fileInput && fileInput.files.length > 0) {
                formData.append("documentos[]", fileInput.files[0]);
                formData.append("tipos[]", doc.tipo);
            }
        });

        // Mostrar los datos que se enviarán
        console.log("📦 Datos que se enviarán:");
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(`${key}: [Archivo] ${value.name} (${value.type})`);
            } else {
                console.log(`${key}: ${value}`);
            }
        }

        //Comentario ...

        // Enviar la solicitud
        fetch(
            "http://localhost/Backend-Laravel/public/api/solicitud-completa",
            {
                method: "POST",
                body: formData,
            }
        )
            .then((response) => {
                // Verificar si la respuesta es exitosa
                if (!response.ok) {
                    // Intentar obtener el mensaje de error del servidor
                    return response.json().then((errorData) => {
                        throw new Error(
                            errorData.message || "Error en la solicitud"
                        );
                    });
                }
                return response.json();
            })
            .then((data) => {
                console.log("Respuesta exitosa:", data);
                alert("¡Solicitud enviada correctamente!");
                // Opcionalmente, redirigir o limpiar el formulario
                // window.location.href = "confirmacion.html";
            })
            .catch((error) => {
                console.error("Error completo:", error);
                alert(`Error al enviar la solicitud: ${error.message}`);
            });
    } catch (error) {
        console.error("Error al preparar el formulario:", error);
        alert(
            "Ocurrió un error al preparar el formulario. Por favor inténtelo de nuevo."
        );
    }
}

function redirigirAspirante() {
    const modal = document.getElementById("modalConfirmacionEnvio");
    if (modal) {
        modal.remove();
        window.location.href = "DashBoard.html";
    }
}

function agregarEstilosModalFinal() {
    let estilo = document.createElement("style");
    estilo.innerHTML = `
        --azul-oscuro: #19407b;
    --azul-medio: #0075bf;
    --azul-claro: #08dcff;
    --blanco: #ffffff;
    --gris-claro: #f4f4f4;
    --gris-medio: #e0e0e0;
    --borde: #dddddd;
    --sombra: rgba(0, 0, 0, 0.1);
    --negro-transparente:#0075bf;
    --rojo-error: #ff4d4d;
    --rojo-hover: #c0392b;
    --verde-exito: #4CAF50;
    --font-primary: 'Roboto', Arial, sans-serif;
    --transition-default: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}


/* Estilos para ambos modales */
.modal,
#modalConfirmacion,
#modalConfirmacionEnvio {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: var(--negro-transparente);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    backdrop-filter: blur(5px);
}

.modal.active,
#modalConfirmacion,
#modalConfirmacionEnvio {
    opacity: 1;
    visibility: visible;
}

/* Contenedor del modal */
.modal-content,
#modalConfirmacion > div,
#modalConfirmacionEnvio > div {
    background-color: var(--blanco);
    padding: 28px 24px;
    border-radius: 12px;
    text-align: center;
    max-width: 400px;
    width: 80%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: translateY(30px) scale(0.95);
    opacity: 0;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                opacity 0.3s ease;
    border-top: 5px solid var(--azul-medio);
}

.modal.active .modal-content,
#modalConfirmacion > div,
#modalConfirmacionEnvio > div {
    transform: translateY(0) scale(1);
    opacity: 1;
    animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes scaleIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Estilos de los títulos */
.modal-content h3,
#modalConfirmacion h3,
#modalConfirmacionEnvio h3 {
    margin-top: 0;
    color: #333;
    font-size: 22px;
    margin-bottom: 16px;
}

/* Estilos de los párrafos */
.modal-content p,
#modalConfirmacion p,
#modalConfirmacionEnvio p {
    color: #666;
    margin-bottom: 16px;
    line-height: 1.5;
}

/* Estilos de los botones */
.modal-content button,
#modalConfirmacion button,
#modalConfirmacionEnvio button {
    font-size: 16px;
    font-weight: bold;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin: 10px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    background: var(--azul-medio);
    color: var(--blanco);
    box-shadow: 0 4px 8px var(--sombra);
}

.modal-content button:hover,
#modalConfirmacion button:hover,
#modalConfirmacionEnvio button:hover {
    background: var(--azul-oscuro);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.modal-content button:active,
#modalConfirmacion button:active,
#modalConfirmacionEnvio button:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Efecto de onda para botones */
.modal-content button::after,
#modalConfirmacion button::after,
#modalConfirmacionEnvio button::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
    background-image: radial-gradient(circle, rgba(255, 255, 255, 0.3) 10%, transparent 10.01%);
    background-repeat: no-repeat;
    background-position: 50%;
    transform: scale(10, 10);
    opacity: 0;
    transition: transform .5s, opacity 1s;
}

.modal-content button:active::after,
#modalConfirmacion button:active::after,
#modalConfirmacionEnvio button:active::after {
    transform: scale(0, 0);
    opacity: .3;
    transition: 0s;
}

/* Botón de Rechazar */
#modalConfirmacion button:last-of-type {
    background: var(--rojo-error);
    color: var(--blanco);
    box-shadow: 0 2px 5px rgba(231, 76, 60, 0.3);
}

#modalConfirmacion button:last-of-type:hover {
    background: var(--rojo-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.4);
}

/* Animación para cerrar el modal */
.modal.closing .modal-content,
.modal.closing,
#modalConfirmacionEnvio.closing {
    transform: scale(0.95);
    opacity: 0;
}

/* Icono para cerrar */
.close-icon {
    position: absolute;
    top: 15px;
    right: 15px;
    cursor: pointer;
    font-size: 20px;
    color: #999;
    transition: color 0.2s ease, transform 0.2s ease;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.close-icon:hover {
    color: #333;
    transform: rotate(90deg);
    background-color: rgba(0, 0, 0, 0.05);
}
.materia-row {
    display: flex;
    align-items: center;
    padding: 10px;
    margin-bottom: 8px;
    border-radius: 5px;
    background-color: #f0f7ff;
    transition: all 0.3s ease;
}

.nota-guardada {
    background-color: #e8f5e9;
    border-left: 4px solid #4caf50;
}

.materia-label {
    flex: 2;
    font-weight: bold;
}

.input-container {
    flex: 1;
}

.nota-input {
    width: 80px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

.save-button, .delete-button {
    margin-left: 10px;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.save-button {
    background-color: #2196F3;
    color: white;
}

.save-button:hover {
    background-color: #0b7dda;
}

.save-button.saved {
    background-color: #4CAF50;
}

.save-button.ready-to-save {
    animation: pulse 1.5s infinite;
}

.delete-button {
    background-color: #f44336;
    color: white;
}

.delete-button:hover {
    background-color: #d32f2f;
}

.semestre-container {
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    background-color: #fff;
}

.semestre-container h3 {
    margin-top: 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}



.mensaje-flash {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: 300px;
    z-index: 1000;
    animation: slideIn 0.5s;
}

.mensaje-flash.success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.mensaje-flash.error {
    background-color: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.mensaje-flash button {
    background: transparent;
    border: none;
    font-size: 1.2em;
    cursor: pointer;
    color: inherit;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(33, 150, 243, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(33, 150, 243, 0);
    }
}

.next-button {
    opacity: 0.7;
    cursor: not-allowed;
}

.next-button.enabled {
    opacity: 1;
    cursor: pointer;
}
    `;
    document.head.appendChild(estilo);
}

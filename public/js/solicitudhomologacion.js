// Global variables
let currentStep = 1;
let asignaturasPorPrograma = [];

// Validation regex patterns
const regex = {
    texto: /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/,
    email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/,
    telefono: /^\d{7,10}$/,
    numeroId: /^\d+$/,
    fecha: /^\d{4}-\d{2}-\d{2}$/
};

// UI helper functions
function changeStep(stepChange) {
    const steps = document.querySelectorAll(".step");
    const stepContents = document.querySelectorAll(".step-content");
    let newStep = currentStep + stepChange;

    if (newStep < 1 || newStep > steps.length) return;

    stepContents[currentStep - 1].classList.remove("active");
    steps[currentStep - 1].classList.remove("active");
    currentStep = newStep;
    stepContents[currentStep - 1].classList.add("active");
    steps[currentStep - 1].classList.add("active");

    const progressBar = document.getElementById("progress-bar");
    if (progressBar) {
        progressBar.style.width = `${((currentStep - 1) / (steps.length - 1)) * 100}%`;
    }
}

function mostrarMensaje(mensaje, tipo) {
    let mensajeExistente = document.querySelector(".mensaje-flash");
    if (mensajeExistente) mensajeExistente.remove();

    const mensajeElement = document.createElement("div");
    mensajeElement.classList.add("mensaje-flash", tipo);
    mensajeElement.innerHTML = `<span>${mensaje}</span><button onclick="this.parentNode.remove()">×</button>`;

    document.body.appendChild(mensajeElement);
    setTimeout(() => mensajeElement.remove(), 5000);
}

function mostrarModalConfirmacion(titulo, mensaje, textoBotonConfirmar = "Aceptar", onConfirm = null) {
    const modal = document.createElement("div");
    modal.id = "modalConfirmacion";
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
    requestAnimationFrame(() => modal.classList.add("active"));

    const cerrarModal = () => {
        modal.classList.remove("active");
        modal.classList.add("closing");
        setTimeout(() => modal.remove(), 300);
    };

    modal.querySelector(".close-icon").addEventListener("click", cerrarModal);
    modal.querySelector(".btn-cancelar").addEventListener("click", cerrarModal);
    modal.querySelector(".btn-confirmar").addEventListener("click", () => {
        if (onConfirm) onConfirm();
        cerrarModal();
    });

    return modal;
}

// Form validators
function soloLetras(event) {
    event.target.value = event.target.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, "");
}

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
        return false;
    } else {
        campo.classList.remove("error");
        errorMensaje.textContent = "";
        return true;
    }
}

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

// Form data handlers
function handlePaisChange() {
    const paisSelect = document.getElementById('pais');
    const departamentoSelect = document.getElementById('departamento');
    const municipioSelect = document.getElementById('municipio');

    departamentoSelect.disabled = paisSelect.value !== '1';
    municipioSelect.disabled = true;
    municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';
}

function updateMunicipios() {
    const departamentoSelect = document.getElementById('departamento');
    const municipioSelect = document.getElementById('municipio');
    const departamentoSeleccionado = departamentoSelect.value;

    municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';
    municipioSelect.disabled = true;

    if (!departamentoSeleccionado) return;

    // Usar el ID del departamento
    const departamentoId = departamentoSelect.options[departamentoSelect.selectedIndex].getAttribute('data-id');

    const municipiosFiltrados = todosLosMunicipios.filter(m => m.departamento === departamentoSeleccionado);
    municipiosFiltrados.forEach(municipio => {
        const option = document.createElement('option');
        option.value = municipio.id_municipio; // Usar ID numérico
        option.textContent = municipio.municipio;
        municipioSelect.appendChild(option);
    });

    municipioSelect.disabled = false;

    // Guardar el ID en campo oculto
    let departamentoIdField = document.getElementById('departamento_id_hidden');
    if (!departamentoIdField) {
        departamentoIdField = document.createElement('input');
        departamentoIdField.type = 'hidden';
        departamentoIdField.id = 'departamento_id_hidden';
        departamentoSelect.parentNode.appendChild(departamentoIdField);
    }
    departamentoIdField.value = departamentoId || "";
}

function activarInstituciones() {
    const municipioSelect = document.getElementById('municipio');
    const institucionSelect = document.getElementById('institucion');
    institucionSelect.disabled = !municipioSelect.value;
}

function toggleFechaFinalizacion() {
    const finalizoEstudiosSelect = document.getElementById("finalizo_estudios");
    if (!finalizoEstudiosSelect) return;

    const finalizoEstudios = finalizoEstudiosSelect.value;
    const fechaFinalizacionContainer = document.getElementById("fecha_finalizacion_container");
    const fechaUltimoSemestreContainer = document.getElementById("fecha_ultimo_semestre_container");

    fechaFinalizacionContainer.style.display = "none";
    fechaUltimoSemestreContainer.style.display = "none";

    document.getElementById("fecha_finalizacion").removeAttribute("required");
    document.getElementById("fecha_ultimo_semestre").removeAttribute("required");

    if (finalizoEstudios === "si" || finalizoEstudios === "Sí" || finalizoEstudios === "SI") {
        fechaFinalizacionContainer.style.display = "block";
        document.getElementById("fecha_finalizacion").setAttribute("required", "");
    } else if (finalizoEstudios === "no" || finalizoEstudios === "No" || finalizoEstudios === "NO") {
        fechaUltimoSemestreContainer.style.display = "block";
        document.getElementById("fecha_ultimo_semestre").setAttribute("required", "");
    }

    setTimeout(validarSENA, 100);
}

function validarSENA() {
    const institucionSelect = document.getElementById("institucion");
    const institucionValue = institucionSelect.value;
    const institucionText = institucionSelect.options[institucionSelect.selectedIndex]?.textContent || "";
    const finalizoEstudios = document.getElementById("finalizo_estudios").value;
    const nextButton = document.querySelector('.step-content[id="step-2"] .next-button');

    const esSENA = institucionValue === "SENA" ||
        institucionText.toUpperCase().includes("SENA") ||
        institucionText.includes("Servicio Nacional de Aprendizaje - SENA");

    let mensajeSENA = document.getElementById("mensajeSENA");

    if (esSENA && (finalizoEstudios === "No" || finalizoEstudios === "no" || finalizoEstudios === "NO")) {
        if (!mensajeSENA) {
            mensajeSENA = document.createElement("div");
            mensajeSENA.id = "mensajeSENA";
            mensajeSENA.classList.add("alert", "alert-danger");
            mensajeSENA.style.cssText = "color:#721c24;background-color:#f8d7da;border:1px solid #f5c6cb;border-radius:4px;padding:12px;margin-top:10px;";
            mensajeSENA.textContent = "No puede continuar con la homologación si no ha finalizado sus estudios en el SENA.";
            document.getElementById("finalizo_estudios").parentElement.appendChild(mensajeSENA);
        }

        if (nextButton) {
            nextButton.disabled = true;
            nextButton.classList.add("disabled");
            nextButton.style.opacity = "0.5";
            nextButton.style.cursor = "not-allowed";
            nextButton.setAttribute("data-original-onclick", nextButton.getAttribute("onclick"));
            nextButton.setAttribute("onclick", "alertaSENA(); return false;");
        }
    } else {
        if (mensajeSENA) {
            mensajeSENA.remove();
        }

        if (nextButton) {
            nextButton.disabled = false;
            nextButton.classList.remove("disabled");
            nextButton.style.opacity = "";
            nextButton.style.cursor = "";

            const originalOnclick = nextButton.getAttribute("data-original-onclick");
            if (originalOnclick) {
                nextButton.setAttribute("onclick", originalOnclick);
            }
        }
    }

    return !(esSENA && finalizoEstudios === "No");
}

function alertaSENA() {
    mostrarMensaje("No puede continuar con la homologación si no ha finalizado sus estudios en el SENA.", "error");
}

function updateFormacion() {
    const institucionSelect = document.getElementById('institucion');
    const tipoInput = document.getElementById('tipoFormacion');
    const programaSelect = document.getElementById('programa');
    const programaDestinoSelect = document.getElementById('programa_destino');

    const selectedOption = institucionSelect.options[institucionSelect.selectedIndex];

    tipoInput.value = selectedOption?.dataset.formacion || "";

    programaSelect.innerHTML = '<option value="">Seleccione un Programa</option>';
    programaSelect.disabled = true;

    const semestre = document.getElementById('semestre');
    const materia = document.getElementById('materia');
    if (semestre) {
        semestre.innerHTML = '<option value="">Seleccione un semestre</option>';
        semestre.disabled = true;
    }
    if (materia) {
        materia.innerHTML = '<option value="">Seleccione una materia</option>';
        materia.disabled = true;
    }

    const institucionId = institucionSelect.value;
    if (!institucionId) return;

    const institucionNombre = selectedOption.textContent.trim();

    const programasFiltrados = programasPorInstitucion.filter(p =>
        p.institucion && p.institucion.includes(institucionNombre)
    );

    programasFiltrados.forEach(programa => {
        const option = document.createElement('option');
        option.value = programa.id_programa;
        option.textContent = programa.programa;
        programaSelect.appendChild(option);
    });

    programaSelect.disabled = false;

    if (programasFiltrados.length === 0) {
        fetch(`http://localhost/Backend-Laravel/public/api/programas`)
            .then(response => response.json())
            .then(allProgramas => {
                if (Array.isArray(allProgramas)) {
                    const filteredForInstitution = allProgramas.filter(p =>
                        p.institucion && p.institucion.includes(institucionNombre)
                    );

                    filteredForInstitution.forEach(programa => {
                        const option = document.createElement('option');
                        option.value = programa.id_programa;
                        option.textContent = programa.programa;
                        programaSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error("Error al cargar todos los programas:", error));
    }

    // Cargar programas de destino
    if (programaDestinoSelect && programaDestinoSelect.options.length <= 1) {
        cargarProgramasDestino();
    }
}

function updateSemestres() {
    const programaId = document.getElementById("programa").value;
    const semestreSelect = document.getElementById("semestre");
    const asignaturaSelect = document.getElementById("materia");

    semestreSelect.innerHTML = '<option value="">Seleccione un semestre</option>';
    asignaturaSelect.innerHTML = '<option value="">Seleccione una asignatura</option>';
    semestreSelect.disabled = true;
    asignaturaSelect.disabled = true;

    if (!programaId) return;

    fetch(`http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`)
        .then(response => response.json())
        .then(data => {
            const asignaturas = data.data;
            const semestresUnicos = [...new Set(asignaturas.map(a => a.semestre))].sort((a, b) => a - b);

            semestresUnicos.forEach(sem => {
                const option = document.createElement("option");
                option.value = sem;
                option.textContent = `Semestre ${sem}`;
                semestreSelect.appendChild(option);
            });

            window.asignaturasPorPrograma = asignaturas;
            semestreSelect.disabled = false;
        })
        .catch(err => console.error("Error al cargar asignaturas:", err));
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

// Subject management
function agregarMateria() {
    const semestre = document.getElementById("semestre").value;
    const materiaSelect = document.getElementById("materia");
    const materiaValor = materiaSelect.value;
    const materiaNombre = materiaSelect.options[materiaSelect.selectedIndex].textContent;

    if (!semestre || !materiaValor) {
        mostrarMensaje("Debe seleccionar una carrera, un semestre y una materia.", "error");
        return;
    }

    let materiaId = `nota_${semestre}_${materiaValor.replace(/\s+/g, "_")}`;
    let materiasContainer = document.getElementById("materias-container");

    let contenedorSemestre = document.getElementById(`semestre_${semestre}`);
    if (!contenedorSemestre) {
        contenedorSemestre = document.createElement("div");
        contenedorSemestre.id = `semestre_${semestre}`;
        contenedorSemestre.classList.add("semestre-container");
        contenedorSemestre.setAttribute("data-semestre", semestre);
        contenedorSemestre.innerHTML = `<h3>${semestre}</h3>`;
        materiasContainer.appendChild(contenedorSemestre);
    }

    if (document.getElementById(materiaId)) {
        mostrarMensaje("Esta materia ya ha sido seleccionada en este semestre.", "error");
        return;
    }

    let materiaRow = document.createElement("div");
    materiaRow.classList.add("materia-row");
    materiaRow.innerHTML = `
        <label class="materia-label">${materiaNombre}:</label>
        <div class="input-container">
            <input type="text" id="${materiaId}" placeholder="Ingrese nota" class="nota-input" oninput="validarNota(this)">
        </div>
        <button class="delete-button" onclick="borrarMateria('${materiaId}')">
            <i class="fa-solid fa-trash"></i> Borrar
        </button>
    `;

    contenedorSemestre.appendChild(materiaRow);
    mostrarMensaje(`Materia "${materiaNombre}" agregada correctamente.`, "success");
    ordenarSemestresAlfabeticamente();
}

function borrarMateria(materiaId) {
    const materiaRow = document.getElementById(materiaId).closest(".materia-row");
    const materiaNombre = materiaRow.querySelector(".materia-label").textContent;
    const semestre = materiaRow.closest(".semestre-container");

    mostrarModalConfirmacion(
        "Eliminar Materia",
        `¿Está seguro que desea eliminar la materia "${materiaNombre}"?`,
        "Eliminar",
        () => {
            if (!materiaRow) return;

            let notas = JSON.parse(localStorage.getItem("notas")) || {};
            if (notas[materiaId]) {
                delete notas[materiaId];
                localStorage.setItem("notas", JSON.stringify(notas));
            }

            materiaRow.remove();

            if (semestre && semestre.querySelectorAll(".materia-row").length === 0) {
                semestre.remove();
            }

            mostrarMensaje(`Materia "${materiaNombre}" ha sido eliminada correctamente.`, "success");
        }
    );
}

function ordenarSemestresAlfabeticamente() {
    let materiasContainer = document.getElementById("materias-container");
    let semestres = Array.from(document.querySelectorAll(".semestre-container"));

    semestres.sort((a, b) => {
        let nombreA = a.getAttribute("data-semestre").toLowerCase();
        let nombreB = b.getAttribute("data-semestre").toLowerCase();
        return nombreA.localeCompare(nombreB);
    });

    materiasContainer.innerHTML = "";
    semestres.forEach(semestre => materiasContainer.appendChild(semestre));
}

function obtenerMaterias() {
    const materiasContainer = document.getElementById("materias-container");
    const semestres = materiasContainer.querySelectorAll(".semestre-container");
    let resultado = {};

    semestres.forEach(semestreDiv => {
        const semestre = semestreDiv.getAttribute("data-semestre");
        const materias = semestreDiv.querySelectorAll(".materia-row");
        resultado[semestre] = [];

        materias.forEach(materiaRow => {
            const label = materiaRow.querySelector(".materia-label");
            const input = materiaRow.querySelector(".nota-input");

            let materiaNombre = label ? label.textContent.replace(":", "").trim() : "";
            const codigoMatch = materiaNombre.match(/\((\d+)\)/);
            if (codigoMatch && codigoMatch[1]) {
                materiaNombre = codigoMatch[1];
            }

            let nota = input?.value ? input.value.trim().replace(/[^\d,\.]/g, "") : "";

            if (materiaNombre && nota) {
                resultado[semestre].push({ nombre: materiaNombre, nota: nota });
            }
        });

        if (resultado[semestre].length === 0) {
            delete resultado[semestre];
        }
    });

    return Object.keys(resultado).length ? resultado : {};
}

function validarNota(input) {
    let valor = input.value.replace(/[^0-9,]/g, "");

    let partes = valor.split(",");
    if (partes.length > 2) {
        valor = partes[0] + "," + partes[1].slice(0, 1);
    }
    if (valor.startsWith(",")) {
        valor = "0" + valor;
    }

    let numero = parseFloat(valor.replace(",", "."));
    input.value = (!isNaN(numero) && numero >= 0 && numero <= 5) ? valor : "";
}

// User data functions
function cargarDatosUsuario() {
    const token = localStorage.getItem('auth_token');
    const userData = localStorage.getItem('user_data');

    let usuarioId;
    if (userData) {
        try {
            const user = JSON.parse(userData);
            usuarioId = user.id || user.usuario_id;

            if (user.tipo_identificacion || user.numero_identificacion || user.email) {
                rellenarCamposUsuario(user);
                return;
            }
        } catch (error) {
            console.warn("Error al procesar user_data:", error);
        }
    }

    if (!usuarioId) return;

    fetch(`http://localhost/Backend-Laravel/public/api/usuarios/${usuarioId}`, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
        .then(response => response.ok ? response.json() : Promise.reject(`Error: ${response.status}`))
        .then(data => {
            if (data.success && data.data) {
                rellenarCamposUsuario(data.data);
            } else if (data) {
                rellenarCamposUsuario(data);
            }
        })
        .catch(error => console.error("Error al obtener datos del usuario:", error));
}

function rellenarCamposUsuario(usuario) {
    const fieldMappings = {
        'tipo_identificacion,tipo_documento': 'tipo_identificacion',
        'numero_identificacion,numero_documento': 'numero_identificacion',
        'email,correo': 'email',
        'telefono': 'telefono',
        'primer_nombre': 'primer_nombre',
        'primer_apellido': 'primer_apellido',
        'segundo_nombre': 'segundo_nombre',
        'segundo_apellido': 'segundo_apellido'
    };

    for (const [apiFields, formField] of Object.entries(fieldMappings)) {
        const apiFieldList = apiFields.split(',');
        const formElement = document.getElementById(formField);

        if (!formElement) continue;

        for (const apiField of apiFieldList) {
            if (usuario[apiField]) {
                if (formElement.tagName === 'SELECT') {
                    for (let i = 0; i < formElement.options.length; i++) {
                        if (formElement.options[i].value === usuario[apiField]) {
                            formElement.selectedIndex = i;
                            break;
                        }
                    }
                } else {
                    formElement.value = usuario[apiField];
                }
                break;
            }
        }
    }

    if (usuario.nombres && !document.getElementById('primer_nombre').value) {
        const nombres = usuario.nombres.split(' ');
        document.getElementById('primer_nombre').value = nombres[0] || '';
        if (nombres.length > 1) {
            document.getElementById('segundo_nombre').value = nombres[1] || '';
        }
    }

    if (usuario.apellidos && !document.getElementById('primer_apellido').value) {
        const apellidos = usuario.apellidos.split(' ');
        document.getElementById('primer_apellido').value = apellidos[0] || '';
        if (apellidos.length > 1) {
            document.getElementById('segundo_apellido').value = apellidos[1] || '';
        }
    }
}

// Form validation functions
function validarFormularioStep1() {
    let valido = true;

    const campos = {
        tipo_identificacion: "Seleccione un tipo de identificación",
        numero_identificacion: "Ingrese su número de identificación",
        primer_nombre: "Ingrese su primer nombre",
        primer_apellido: "Ingrese su primer apellido",
        email: "Ingrese un correo electrónico",
        telefono: "Ingrese su teléfono",
        direccion: "Ingrese su dirección",
        pais: "Seleccione su país"
    };

    for (const [id, mensaje] of Object.entries(campos)) {
        if (!validarCampo(document.getElementById(id), mensaje)) {
            valido = false;
        }
    }

    const paisSelect = document.getElementById('pais');
    if (paisSelect.value === '1') {
        if (!validarCampo(document.getElementById('departamento'), "Seleccione un departamento")) {
            valido = false;
        }
        if (!validarCampo(document.getElementById('municipio'), "Seleccione un municipio")) {
            valido = false;
        }
    }

    const nombres = ["primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido"];
    nombres.forEach(id => {
        const campo = document.getElementById(id);
        if (campo && campo.value.trim() && !regex.texto.test(campo.value)) {
            campo.classList.add("error");
            const errorMensaje = campo.nextElementSibling;
            if (errorMensaje) errorMensaje.textContent = "Solo se permiten letras";
            valido = false;
        }
    });

    const numId = document.getElementById("numero_identificacion");
    if (!regex.numeroId.test(numId.value)) {
        numId.classList.add("error");
        const errorMsg = numId.nextElementSibling;
        if (errorMsg) errorMsg.textContent = "Ingrese solo números";
        valido = false;
    }

    const email = document.getElementById("email");
    if (!regex.email.test(email.value)) {
        email.classList.add("error");
        const errorEmail = email.nextElementSibling;
        if (errorEmail) errorEmail.textContent = "Ingrese un correo válido";
        valido = false;
    }

    const telefono = document.getElementById("telefono");
    if (!regex.telefono.test(telefono.value)) {
        telefono.classList.add("error");
        const errorTelefono = telefono.nextElementSibling;
        if (errorTelefono) errorTelefono.textContent = "Ingrese un número de teléfono válido (7 a 10 dígitos)";
        valido = false;
    }

    if (valido) {
        mostrarMensaje("Paso 1 finalizado correctamente", "success");
        changeStep(1);
    }

    return valido;
}

function validarFormularioStep2() {
    if (!validarSENA()) {
        return false;
    }

    let valido = true;

    const institucion = document.getElementById("institucion");
    const tipoFormacion = document.getElementById("tipoFormacion");
    const programa = document.getElementById("programa");
    const finalizoEstudios = document.getElementById("finalizo_estudios");
    const fechaFinalizacion = document.getElementById("fecha_finalizacion");
    const fechaUltimoSemestre = document.getElementById("fecha_ultimo_semestre");

    const regexFecha = /^\d{4}-\d{2}-\d{2}$/;

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
            return false;
        } else {
            campo.classList.remove("error");
            errorMensaje.textContent = "";
            return true;
        }
    }

    if (institucion.value === "SENA" && finalizoEstudios.value === "No") {
        mostrarMensaje("No puede continuar con la homologación si no ha finalizado sus estudios en el SENA.", "error");
        return false;
    }

    validarCampo(institucion, "Seleccione su institución de origen");

    if (!tipoFormacion.readOnly) {
        validarCampo(tipoFormacion, "Seleccione su tipo de formación");
    }

    validarCampo(programa, "Seleccione su programa académico");
    validarCampo(finalizoEstudios, "Indique si finalizó sus estudios");

    if (finalizoEstudios.value === "Sí") {
        if (!validarCampo(fechaFinalizacion, "Ingrese la fecha de finalización de sus estudios")) {
            valido = false;
        } else if (!regexFecha.test(fechaFinalizacion.value)) {
            fechaFinalizacion.classList.add("error");
            const errorMsg = fechaFinalizacion.parentElement.querySelector(".error-message");
            if (errorMsg) errorMsg.textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
    } else if (finalizoEstudios.value === "No") {
        if (!validarCampo(fechaUltimoSemestre, "Ingrese la fecha del último semestre cursado")) {
            valido = false;
        } else if (!regexFecha.test(fechaUltimoSemestre.value)) {
            fechaUltimoSemestre.classList.add("error");
            const errorMsg = fechaUltimoSemestre.parentElement.querySelector(".error-message");
            if (errorMsg) errorMsg.textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
    }

    const hoy = new Date();

    if (finalizoEstudios.value === "Sí" && fechaFinalizacion.value) {
        const fechaFin = new Date(fechaFinalizacion.value);
        if (fechaFin > hoy) {
            fechaFinalizacion.classList.add("error");
            const errorMsg = fechaFinalizacion.parentElement.querySelector(".error-message");
            if (errorMsg) errorMsg.textContent = "La fecha no puede ser futura";
            valido = false;
        }
    }

    if (finalizoEstudios.value === "No" && fechaUltimoSemestre.value) {
        const fechaUltimo = new Date(fechaUltimoSemestre.value);
        if (fechaUltimo > hoy) {
            fechaUltimoSemestre.classList.add("error");
            const errorMsg = fechaUltimoSemestre.parentElement.querySelector(".error-message");
            if (errorMsg) errorMsg.textContent = "La fecha no puede ser futura";
            valido = false;
        }
    }

    if (valido) {
        mostrarMensaje("Paso 2 finalizado correctamente", "success");
        changeStep(1);
    }

    if (valido) {
        const institucionSelect = document.getElementById("institucion");
        const institucionValue = institucionSelect.value;
        const institucionText = institucionSelect.options[institucionSelect.selectedIndex]?.textContent || "";

        const esSENA = institucionValue === "SENA" ||
            institucionText.toUpperCase().includes("SENA") ||
            institucionText.includes("Servicio Nacional de Aprendizaje - SENA");

        if (esSENA) {
            autoAsignarNotasSENA();
            setTimeout(() => {
                changeStep(1);
            }, 500);
            return false;
        }
    }

    return valido;
}

function autoAsignarNotasSENA() {
    const programaId = document.getElementById("programa").value;
    const materiasContainer = document.getElementById("materias-container");
    materiasContainer.innerHTML = "";

    fetch(`http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`)
        .then(response => response.json())
        .then(data => {
            const asignaturas = data.data || [];

            const asignaturasPorSemestre = {};
            asignaturas.forEach(asignatura => {
                if (!asignaturasPorSemestre[asignatura.semestre]) {
                    asignaturasPorSemestre[asignatura.semestre] = [];
                }
                asignaturasPorSemestre[asignatura.semestre].push(asignatura);
            });

            Object.keys(asignaturasPorSemestre).sort().forEach(semestre => {
                let contenedorSemestre = document.createElement("div");
                contenedorSemestre.id = `semestre_${semestre}`;
                contenedorSemestre.classList.add("semestre-container");
                contenedorSemestre.setAttribute("data-semestre", semestre);
                contenedorSemestre.innerHTML = `<h3>Semestre ${semestre}</h3>`;
                materiasContainer.appendChild(contenedorSemestre);

                asignaturasPorSemestre[semestre].forEach(asignatura => {
                    const materiaId = `nota_${semestre}_${asignatura.id_asignatura}`;
                    let materiaRow = document.createElement("div");
                    materiaRow.classList.add("materia-row");
                    materiaRow.innerHTML = `
                        <label class="materia-label" data-id="${asignatura.id_asignatura}">${asignatura.nombre}:</label>
                        <div class="input-container">
                            <input type="text" id="${materiaId}" value="4.5" class="nota-input" readonly data-id="${asignatura.id_asignatura}">
                        </div>
                        <button class="delete-button" disabled style="opacity: 0.5">
                            <i class="fa-solid fa-trash"></i> Borrar
                        </button>
                    `;
                    contenedorSemestre.appendChild(materiaRow);

                    let notas = JSON.parse(localStorage.getItem("notas")) || {};
                    notas[materiaId] = "4.5";
                    localStorage.setItem("notas", JSON.stringify(notas));
                });
            });

            // Guardar asignaturas para poder acceder a sus IDs después
            window.asignaturasPorPrograma = asignaturas;

            mostrarMensaje("Se han cargado automáticamente todas las competencias del SENA con nota 4.5", "success");

            let materiasGuardadas = [];
            asignaturas.forEach(asignatura => {
                materiasGuardadas.push({
                    materia: asignatura.nombre,
                    id: asignatura.id_asignatura,
                    nota: "4.5"
                });
            });
            localStorage.setItem("materiasGuardadas", JSON.stringify(materiasGuardadas));
        })
        .catch(error => {
            console.error("Error al cargar asignaturas:", error);
            mostrarMensaje("Error al cargar automáticamente las competencias SENA", "error");
        });
}

function validacionStep3() {
    const materias = document.querySelectorAll(".nota-input");

    if (materias.length < 6) {
        mostrarModalConfirmacion("Error", "Debe registrar al menos 6 materias antes de continuar.", "Cerrar");
        return false;
    }

    const errores = [];
    const materiasGuardadas = [];

    materias.forEach(input => {
        const materiaRow = input.closest(".materia-row");
        if (!materiaRow) return;

        const materiaLabel = materiaRow.querySelector(".materia-label");
        if (!materiaLabel) return;

        const materiaNombre = materiaLabel.textContent;
        const nota = input.value.trim();

        if (nota === "") {
            errores.push(`La materia ${materiaNombre} no tiene nota registrada.`);
            return;
        }

        const notaNumero = parseFloat(nota.replace(",", "."));
        if (isNaN(notaNumero) || notaNumero < 0 || notaNumero > 5) {
            errores.push(`La nota de ${materiaNombre} no es válida: ${nota}`);
            return;
        }

        try {
            let notas = JSON.parse(localStorage.getItem("notas")) || {};
            notas[input.id] = notaNumero.toFixed(1);
            localStorage.setItem("notas", JSON.stringify(notas));

            materiasGuardadas.push({
                materia: materiaNombre,
                nota: notaNumero.toFixed(1)
            });
        } catch (error) {
            errores.push(`Error al guardar la nota de ${materiaNombre}: ${error.message}`);
        }
    });

    if (errores.length > 0) {
        mostrarModalConfirmacion("Errores", `Se encontraron los siguientes errores:<br>${errores.join("<br>")}`, "Cerrar");
        return false;
    }

    try {
        localStorage.setItem("materiasGuardadas", JSON.stringify(materiasGuardadas));
    } catch (error) {
        mostrarModalConfirmacion("Error de Almacenamiento", "No se pudieron guardar las materias. Inténtelo de nuevo.", "Cerrar");
        return false;
    }

    mostrarModalConfirmacion(
        "Materias Guardadas",
        `Materias guardadas correctamente:<br>${materiasGuardadas.map(m => `${m.materia}: ${m.nota}`).join("<br>")}`,
        "Aceptar",
        () => {
            mostrarMensaje("Paso 3 finalizado correctamente", "success");
            changeStep(1);
        }
    );

    return true;
}

function validarFormularioStep4() {
    let valido = true;

    const documentoId = document.getElementById("documento_id");
    const certificadoNotas = document.getElementById("certificado_notas");
    const contenidoProgramatico = document.getElementById("contenido_programatico");
    const cartaHomologacion = document.getElementById("carta_homologacion");

    valido &= validarArchivo(documentoId, "Debe subir su Documento de Identidad.");
    valido &= validarArchivo(certificadoNotas, "Debe subir su Certificado de Notas.");
    valido &= validarArchivo(contenidoProgramatico, "Debe subir el Contenido Programático.");
    valido &= validarArchivo(cartaHomologacion, "Debe subir la Carta de Solicitud de Homologación.");

    const finalizoEstudios = document.getElementById("finalizo_estudios").value;
    if (finalizoEstudios === "si" || finalizoEstudios === "Sí" || finalizoEstudios === "SI") {
        valido &= validarArchivo(
            document.getElementById("certificacion_finalizacion"),
            "Debe subir la Certificación de Finalización de Estudios."
        );
    }

    if (document.getElementById("tipo_identificacion").value === "Cédula de Extranjería") {
        document.getElementById("extra-docs").classList.remove("hidden");
        const visaPasaporte = document.getElementById("visa_pasaporte");
        visaPasaporte.setAttribute("required", "true");
        valido &= validarArchivo(visaPasaporte, "Debe subir una copia de su Visa o Pasaporte.");
    }

    if (valido) {
        mostrarMensaje("Documentos validados correctamente", "success");
        changeStep(1);
    } else {
        mostrarMensaje("Por favor, adjunte todos los documentos requeridos", "error");
    }

    return valido;
}

function cerrarModal() {
    const modal = document.getElementById("modalConfirmacion");
    if (modal) {
        modal.classList.remove("active");
        modal.classList.add("closing");
        setTimeout(() => modal.remove(), 300);
    }
}

function confirmarDatos() {
    const currentStepContent = document.querySelector(".step-content.active");
    const currentStepId = currentStepContent ? currentStepContent.id : "";

    if (currentStepId === "step-4") {
        if (!validarFormularioStep4()) {
            return;
        }

        changeStep(1);
        return;
    }

    const datos = {
        tipoIdentificacion: document.getElementById("tipo_identificacion").value,
        numeroIdentificacion: document.getElementById("numero_identificacion").value,
        primerNombre: document.getElementById("primer_nombre").value,
        segundoNombre: document.getElementById("segundo_nombre").value || "(No aplica)",
        primerApellido: document.getElementById("primer_apellido").value,
        segundoApellido: document.getElementById("segundo_apellido").value || "(No aplica)",
        email: document.getElementById("email").value,
        telefono: document.getElementById("telefono").value
    };

    if (!datos.tipoIdentificacion || !datos.numeroIdentificacion || !datos.primerNombre ||
        !datos.primerApellido || !datos.email || !datos.telefono) {
        mostrarMensaje("Por favor, completa todos los campos obligatorios antes de enviar.", "error");
        return;
    }

    let mensaje = `
    <h3>¿Están correctos estos datos?</h3>
    <p><strong>Tipo de Identificación:</strong> ${datos.tipoIdentificacion}</p>
    <p><strong>Número de Identificación:</strong> ${datos.numeroIdentificacion}</p>
    <p><strong>Nombre Completo:</strong> ${datos.primerNombre} ${datos.segundoNombre} ${datos.primerApellido} ${datos.segundoApellido}</p>
    <p><strong>Correo Electrónico:</strong> ${datos.email}</p>
    <p><strong>Teléfono:</strong> ${datos.telefono}</p>
    <br>
    <button id="confirmarBtn">Confirmar y Enviar</button>
    <button onclick="cerrarModal()">Cancelar</button>
    `;

    const modal = document.createElement("div");
    modal.id = "modalConfirmacion";
    modal.classList.add("modal");
    modal.innerHTML = `<div class="modal-content">${mensaje}</div>`;
    document.body.appendChild(modal);
    modal.classList.add("active");

    setTimeout(() => {
        document.getElementById("confirmarBtn").addEventListener("click", enviarFormulario);
    }, 100);
}

function verificarCamposObligatorios() {
    const campos = {
        "tipo_identificacion": document.getElementById("tipo_identificacion"),
        "numero_identificacion": document.getElementById("numero_identificacion"),
        "primer_nombre": document.getElementById("primer_nombre"),
        "primer_apellido": document.getElementById("primer_apellido"),
        "email": document.getElementById("email"),
        "telefono": document.getElementById("telefono"),
        "direccion": document.getElementById("direccion"),
        "pais": document.getElementById("pais"),
        "institucion": document.getElementById("institucion"),
        "programa": document.getElementById("programa"),
        "programa_destino": document.getElementById("programa_destino")
    };

    let problemasEncontrados = [];

    for (const [nombre, campo] of Object.entries(campos)) {
        if (!campo) {
            problemasEncontrados.push(`Campo ${nombre} no encontrado en el DOM`);
            continue;
        }

        if (!campo.value || campo.value.trim() === "") {
            problemasEncontrados.push(`Campo ${nombre} está vacío`);
        }
    }

    if (campos.municipio && campos.municipio.disabled) {
        console.log("El campo municipio está deshabilitado. Habilitándolo...");
        campos.municipio.disabled = false;
    }

    const documentosObligatorios = ["documento_id", "certificado_notas", "contenido_programatico", "carta_homologacion"];
    documentosObligatorios.forEach(docId => {
        const fileInput = document.getElementById(docId);
        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
            problemasEncontrados.push(`Documento obligatorio ${docId} no adjuntado`);
        }
    });

    const materiasContainer = document.getElementById("materias-container");
    if (!materiasContainer || materiasContainer.querySelectorAll(".materia-row").length < 6) {
        problemasEncontrados.push("Debe agregar al menos 6 materias");
    }

    return problemasEncontrados;
}

function cargarInstituciones() {
    const institucionSelect = document.getElementById('institucion');

    if (institucionSelect && institucionSelect.options.length > 1) {
        return;
    }

    fetch('http://localhost/Backend-Laravel/public/api/instituciones')
        .then(response => response.json())
        .then(data => {
            if (data && Array.isArray(data)) {
                institucionSelect.innerHTML = '<option value="">Seleccione una Institución</option>';

                data.forEach(institucion => {
                    const option = document.createElement('option');
                    option.value = institucion.id_institucion;
                    option.textContent = institucion.nombre;
                    option.setAttribute('data-formacion', institucion.tipo || '');
                    institucionSelect.appendChild(option);
                });

                console.log(`Se cargaron ${data.length} instituciones`);
            } else {
                console.warn('Formato de datos de instituciones no válido');
            }
        })
        .catch(error => {
            console.error('Error al cargar instituciones:', error);
        });
}

function cargarProgramasDestino() {
    const programaDestinoSelect = document.getElementById('programa_destino');
    if (!programaDestinoSelect) {
        console.error('No se encontró el elemento programa_destino');
        return;
    }

    programaDestinoSelect.innerHTML = '<option value="">Seleccione un programa de destino</option>';

    // Utilizar el endpoint correcto y agregar parámetros de depuración
    fetch('http://127.0.0.1:8000/api/programas?institucion=Autonoma')
        .then(response => {
            console.log('Status de respuesta:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);

            // Filtrar programas que corresponden a la Autónoma
            const programasAutonoma = Array.isArray(data) ?
                data.filter(p => p.institucion &&
                    (p.institucion.includes('Autónoma') ||
                        p.institucion.includes('Autonoma'))) : [];

            console.log('Programas filtrados:', programasAutonoma);

            if (programasAutonoma.length > 0) {
                programasAutonoma.forEach(programa => {
                    const option = document.createElement('option');
                    option.value = programa.id_programa;
                    option.textContent = programa.programa;
                    programaDestinoSelect.appendChild(option);
                });
                console.log(`Se cargaron ${programasAutonoma.length} programas de destino`);
            } else {
                console.warn('No se encontraron programas para la Universidad Autónoma');
            }
        })
        .catch(error => {
            console.error('Error al cargar programas de destino:', error);
        });
}

function activarInstitucionSelect() {
    // Esta función ya no depende de departamento/municipio
    const institucionSelect = document.getElementById('institucion');
    if (institucionSelect) {
        institucionSelect.disabled = false;
    }
}

function procesarMateriasConDetalles(materias) {
    const result = [];

    // Si no hay materias o el objeto está vacío
    if (!materias || Object.keys(materias).length === 0) {
        // Aquí obtendríamos las asignaturas del SENA desde la base de datos en lugar de crear datos ficticios
        const programaId = document.getElementById("programa").value;

        if (!programaId) {
            console.error("No se ha seleccionado un programa");
            mostrarMensaje("Debe seleccionar un programa antes de continuar", "error");
            return [];
        }

        // En este caso usamos las asignaturas ya cargadas en asignaturasPorPrograma si están disponibles
        if (window.asignaturasPorPrograma && window.asignaturasPorPrograma.length > 0) {
            window.asignaturasPorPrograma.forEach(asignatura => {
                result.push({
                    asignatura_id: asignatura.id_asignatura, // Usar el ID real de la asignatura
                    nombre: asignatura.nombre,
                    codigo: asignatura.codigo || "SENA_" + asignatura.id_asignatura,
                    semestre: asignatura.semestre,
                    nota: 4.5,
                    programa: asignatura.programa,
                    programa_id: programaId,
                    institucion: document.getElementById("institucion").options[
                        document.getElementById("institucion").selectedIndex
                    ]?.textContent || "",
                    institucion_id: document.getElementById("institucion").value,
                    nota_origen: 4.5,
                    horas_sena: Math.floor(Math.random() * 150) + 100 // Esto lo mantenemos aleatorio solo para SENA
                });
            });
        } else {
            // Si no tenemos asignaturas cargadas, cargarlas de la API
            fetch(`http://localhost/Backend-Laravel/public/api/asignaturas/programa/${programaId}`)
                .then(response => response.json())
                .then(data => {
                    const asignaturas = data.data || [];
                    if (asignaturas.length > 0) {
                        asignaturas.forEach(asignatura => {
                            result.push({
                                asignatura_id: asignatura.id_asignatura,
                                nombre: asignatura.nombre,
                                codigo: asignatura.codigo || "SENA_" + asignatura.id_asignatura,
                                semestre: asignatura.semestre,
                                nota: 4.5,
                                programa: asignatura.programa,
                                programa_id: programaId,
                                institucion: document.getElementById("institucion").options[
                                    document.getElementById("institucion").selectedIndex
                                ]?.textContent || "",
                                institucion_id: document.getElementById("institucion").value,
                                nota_origen: 4.5,
                                horas_sena: Math.floor(Math.random() * 150) + 100
                            });
                        });
                    }
                })
                .catch(err => console.error("Error al cargar asignaturas:", err));
        }

        return result;
    }

    // Procesar materias normalmente si existen
    for (const [semestre, materiasDelSemestre] of Object.entries(materias)) {
        if (Array.isArray(materiasDelSemestre)) {
            materiasDelSemestre.forEach(materia => {
                // Asumimos que el ID está en el formato nota_{semestre}_{id_asignatura}
                const idMateria = document.getElementById(`nota_${semestre}_${materia.nombre}`);
                let asignaturaId = null;

                // Primero intentamos extraer el ID de la asignatura de los datos cargados
                if (window.asignaturasPorPrograma) {
                    const asignaturaEncontrada = window.asignaturasPorPrograma.find(a =>
                        a.nombre === materia.nombre || a.nombre.includes(materia.nombre)
                    );

                    if (asignaturaEncontrada) {
                        asignaturaId = asignaturaEncontrada.id_asignatura;
                    }
                }

                // Si no encontramos el ID, intentamos extraerlo del DOM o del nombre
                if (!asignaturaId) {
                    // Intentar extraer el ID del elemento DOM
                    if (idMateria && idMateria.id) {
                        const match = idMateria.id.match(/nota_\d+_(\d+)/);
                        if (match && match[1]) {
                            asignaturaId = parseInt(match[1]);
                        }
                    }

                    // Si aún no tenemos ID, intentar extraerlo del nombre
                    if (!asignaturaId) {
                        const codigoMatch = materia.nombre.match(/\((\d+)\)/);
                        if (codigoMatch && codigoMatch[1]) {
                            asignaturaId = parseInt(codigoMatch[1]);
                        }
                    }
                }

                // Si aún no tenemos ID, buscar en data-id si existe
                if (!asignaturaId && idMateria && idMateria.getAttribute('data-id')) {
                    asignaturaId = parseInt(idMateria.getAttribute('data-id'));
                }

                // Verificar si tenemos un ID válido
                if (!asignaturaId || isNaN(asignaturaId)) {
                    console.warn(`No se pudo determinar el ID para la asignatura: ${materia.nombre}`);
                    // Intentar buscar por última vez en todas las asignaturas
                    fetch(`http://localhost/Backend-Laravel/public/api/asignaturas?nombre=${encodeURIComponent(materia.nombre)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.data && data.data.length > 0) {
                                asignaturaId = data.data[0].id_asignatura;
                                // Continuar procesamiento...
                            }
                        })
                        .catch(err => console.error("Error al buscar asignatura:", err));

                    return; // Saltamos esta asignatura si no podemos identificarla
                }

                // Get program and institution data from selects
                const programaSelect = document.getElementById("programa");
                const institucionSelect = document.getElementById("institucion");
                const programa = programaSelect.options[programaSelect.selectedIndex]?.textContent || "";
                const institucion = institucionSelect.options[institucionSelect.selectedIndex]?.textContent || "";

                // Determinar si es SENA para asignar horas aleatorias
                const esSENA = institucion.includes("SENA");

                // Create properly structured asignatura object
                result.push({
                    asignatura_id: asignaturaId,
                    nombre: materia.nombre.replace(/\s*\([^)]*\)\s*/, '').trim(),
                    codigo: `CODE_${asignaturaId}`,
                    semestre: parseInt(semestre) || 1,
                    nota: parseFloat(materia.nota.replace(',', '.')) || 0,
                    programa: programa,
                    programa_id: programaSelect.value || null,
                    institucion: institucion,
                    institucion_id: institucionSelect.value || null,
                    nota_origen: parseFloat(materia.nota.replace(',', '.')) || 0,
                    horas_sena: esSENA ? Math.floor(Math.random() * 150) + 100 : null
                });
            });
        }
    }

    return result;
}

function enviarFormulario() {
    const problemas = verificarCamposObligatorios();
    if (problemas.length > 0) {
        console.warn("Problemas encontrados con campos obligatorios:", problemas);
        mostrarMensaje("Faltan campos obligatorios. Por favor, completa el formulario.", "error");
        return;
    }

    cerrarModal();

    const formData = new FormData();

    try {
        // Definir primero todos los elementos DOM que vamos a utilizar
        const paisSelect = document.getElementById("pais");
        const departamentoSelect = document.getElementById("departamento");
        const municipioSelect = document.getElementById("municipio");
        const institucionSelect = document.getElementById("institucion");
        const programaSelect = document.getElementById("programa");
        const programaDestinoSelect = document.getElementById("programa_destino");
        const finalizoEstudiosSelect = document.getElementById("finalizo_estudios");
        const fechaFinalizacion = document.getElementById("fecha_finalizacion");
        const fechaUltimoSemestre = document.getElementById("fecha_ultimo_semestre");

        // Datos personales
        formData.append("tipo_identificacion", document.getElementById("tipo_identificacion").value || "");
        formData.append("numero_identificacion", document.getElementById("numero_identificacion").value || "");
        formData.append("primer_nombre", document.getElementById("primer_nombre").value || "");
        formData.append("segundo_nombre", document.getElementById("segundo_nombre").value || "");
        formData.append("primer_apellido", document.getElementById("primer_apellido").value || "");
        formData.append("segundo_apellido", document.getElementById("segundo_apellido").value || "");
        formData.append("email", document.getElementById("email").value || "");
        formData.append("telefono", document.getElementById("telefono").value || "");
        formData.append("direccion", document.getElementById("direccion").value || "");

        // Datos geográficos - asegurarse de enviar siempre valores numéricos o null explícito
        formData.append("pais", paisSelect.value ? parseInt(paisSelect.value, 10) : null);

        // Si el país no es Colombia (ID 1), establecer departamento y municipio a null
        if (paisSelect.value !== '1') {
            formData.append("departamento", null);
            formData.append("municipio", null);
        } else {
            // Para Colombia, asegurar que enviamos valores numéricos
            if (departamentoSelect && !departamentoSelect.disabled) {
                const departamentoId = departamentoSelect.options[departamentoSelect.selectedIndex]?.getAttribute('data-id');
                formData.append("departamento", departamentoId ? parseInt(departamentoId, 10) : null);
            } else {
                formData.append("departamento", null);
            }

            if (municipioSelect && !municipioSelect.disabled) {
                formData.append("municipio", municipioSelect.value ? parseInt(municipioSelect.value, 10) : null);
            } else {
                formData.append("municipio", null);
            }
        }

        // Datos académicos - asegurarse de guardar tanto IDs como nombres para referencia
        formData.append("institucion_origen", institucionSelect.value ? parseInt(institucionSelect.value, 10) : null);
        formData.append("institucion_origen_nombre",
            institucionSelect.options[institucionSelect.selectedIndex]?.textContent.trim() || "");

        formData.append("tipo_formacion", document.getElementById("tipoFormacion").value || "SENA");
        formData.append("programa_origen", programaSelect.value ? parseInt(programaSelect.value, 10) : null);
        formData.append("programa_destino", programaDestinoSelect.value ? parseInt(programaDestinoSelect.value, 10) : null);

        const finalizo = finalizoEstudiosSelect.value;
        formData.append("finalizo_estudios", finalizo);

        if (finalizo === "Sí" || finalizo === "si" || finalizo === "SI") {
            formData.append("fecha_finalizacion", fechaFinalizacion.value || "");
            formData.append("fecha_ultimo_semestre", "");
        } else {
            formData.append("fecha_ultimo_semestre", fechaUltimoSemestre.value || "");
            formData.append("fecha_finalizacion", "");
        }

        // Generar número de radicado
        const año = new Date().getFullYear();
        const contador = String(Math.floor(Math.random() * 1000) + 1).padStart(4, "0");
        formData.append("numero_rad", `HOM-${año}-${contador}`);
        formData.append("password", "12345678");  // Este debería ser generado de forma segura o pedido al usuario

        // Procesar materias
        const materias = obtenerMaterias();
        if (Object.keys(materias).length === 0) {
            mostrarMensaje("No se han ingresado materias para homologar.", "error");
            return;
        }

        const materiasConDetalles = procesarMateriasConDetalles(materias);

        try {
            // Crear un array de objetos simple
            const materiasSimplificadas = materiasConDetalles.map(m => ({
                asignatura_id: m.asignatura_id,
                nota_origen: m.nota_origen,
                horas_sena: m.horas_sena
            }));

            // Convertir a JSON sin caracteres de escape adicionales
            const materiasJSON = JSON.stringify(materiasSimplificadas);

            // Enviarlo exactamente como es
            formData.append("materias", materiasJSON);
            formData.append("materias_is_json", "true");

            console.log("JSON de materias:", materiasJSON);
        } catch (error) {
            console.error("Error al procesar JSON de materias:", error);
            mostrarMensaje("Error al procesar las materias.", "error");
            return;
        }

        // Procesar documentos
        const documentos = [
          { id: "documento_id", tipo: "Documento de Identidad", dir: "doc_identidad" },
            { id: "certificado_notas", tipo: "Certificado de Notas", dir: "cert_notas" },
            { id: "contenido_programatico", tipo: "Contenido Programático", dir: "cont_programatico" },
            { id: "carta_homologacion", tipo: "Carta de Solicitud", dir: "cart_solicitud" },
            { id: "certificacion_finalizacion", tipo: "Certificación de Finalización de Estudios", dir: "cert_fin_est" },
            { id: "visa_pasaporte", tipo: "Copia de la Visa", dir: "copia_visa" }
        ];

        documentos.forEach(doc => {
            const fileInput = document.getElementById(doc.id);
            if (fileInput && fileInput.files.length > 0) {
                formData.append("documentos[]", fileInput.files[0]);
                formData.append("tipos[]", doc.tipo);
                formData.append("directorios[]", doc.dir);
            }
        });

        // Mostrar todos los datos que se enviarán
        console.log("Enviando datos:");
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
        }

        // Mostrar indicador de carga
        const loadingIndicator = document.createElement("div");
        loadingIndicator.className = "loading-indicator";
        loadingIndicator.innerHTML = '<div class="spinner"></div><p>Enviando solicitud...</p>';
        loadingIndicator.style.position = "fixed";
        loadingIndicator.style.top = "0";
        loadingIndicator.style.left = "0";
        loadingIndicator.style.width = "100%";
        loadingIndicator.style.height = "100%";
        loadingIndicator.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
        loadingIndicator.style.display = "flex";
        loadingIndicator.style.flexDirection = "column";
        loadingIndicator.style.justifyContent = "center";
        loadingIndicator.style.alignItems = "center";
        loadingIndicator.style.zIndex = "9999";
        loadingIndicator.style.color = "white";

        // Estilos para el spinner
        const spinnerStyle = document.createElement('style');
        spinnerStyle.textContent = `
            .spinner {
                border: 5px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top: 5px solid white;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
                margin-bottom: 15px;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(spinnerStyle);
        document.body.appendChild(loadingIndicator);

        // Enviar solicitud
        const endpoint = "http://localhost/Backend-Laravel/public/api/solicitud-actualizar";

        fetch(endpoint, {
            method: "POST",
            body: formData
        })
            .then(response => {
                console.log("Respuesta del servidor:", response.status);
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || "Error en la solicitud");
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log("Datos recibidos:", data);
                // Eliminar el indicador de carga
                if (document.body.contains(loadingIndicator)) {
                    document.body.removeChild(loadingIndicator);
                }

                // Mostrar mensaje de éxito
                mostrarMensaje("¡Solicitud enviada correctamente!", "success");

                // Número de radicado generado
                const numeroRadicado = data.numero_radicado || `HOM-${new Date().getFullYear()}-${String(Math.floor(Math.random() * 9000) + 1000)}`;

                console.log("Mostrando ventana de confirmación final con radicado:", numeroRadicado);

                // Llamar a la función con un pequeño retraso
                setTimeout(() => {
                    abrirModalConfirmacion(numeroRadicado);
                }, 300);
            })
            .catch(error => {
                console.error("Error:", error);
                if (document.body.contains(loadingIndicator)) {
                    document.body.removeChild(loadingIndicator);
                }
                mostrarMensaje(`Error al enviar la solicitud: ${error.message}`, "error");
            });
    } catch (error) {
        console.error("Error al preparar el formulario:", error);
        mostrarMensaje("Ocurrió un error al preparar el formulario.", "error");
    }
}

// Nueva función para mostrar el modal de confirmación final
function abrirModalConfirmacion(numeroRadicado) {
    console.log("Ejecutando abrirModalConfirmacion con número:", numeroRadicado);

    // Eliminar cualquier modal existente
    document.querySelectorAll(".modal-confirmacion, .modal").forEach(modal => {
        document.body.removeChild(modal);
        console.log("Modal existente eliminado");
    });

    // Crear nuevo modal
    const modal = document.createElement("div");
    modal.id = "modalConfirmacionFinal";
    modal.style.position = "fixed";
    modal.style.top = "0";
    modal.style.left = "0";
    modal.style.width = "100%";
    modal.style.height = "100%";
    modal.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";
    modal.style.zIndex = "9999";

    modal.innerHTML = `
        <div style="background-color: white; max-width: 450px; width: 90%; padding: 30px; border-radius: 10px; text-align: center; border-top: 5px solid #28a745;">
            <div style="font-size: 64px; color: #28a745; margin-bottom: 15px;">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <h2 style="color: #333;">¡Solicitud Enviada con Éxito!</h2>
            <p style="color: #555;">Su solicitud de homologación ha sido registrada correctamente.</p>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e5e5;">
                <span style="font-size: 14px; color: #6c757d; display: block; margin-bottom: 5px;">Número de radicado:</span>
                <span style="font-size: 24px; font-weight: bold; color: #212529;">${numeroRadicado || 'N/A'}</span>
            </div>
            <p style="font-size: 14px; color: #6c757d; margin-bottom: 20px;">Utilice este número para realizar seguimiento a su proceso.</p>
            <button onclick="window.location.href='http://localhost/homologaciones-frontend/public/homologaciones/aspirante'"
                    style="background-color: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 4px; font-size: 16px; cursor: pointer; width: 100%; display: flex; justify-content: center; align-items: center;">
                Ir a Seguimiento <i style="margin-left: 8px;" class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    `;

    document.body.appendChild(modal);
    console.log("Modal de confirmación añadido al DOM");

    // Prevenir que se cierre haciendo clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            e.stopPropagation();
            console.log("Intento de cierre bloqueado");
        }
    });
}

// Función para pruebas directas - puedes usar esto en la consola para probar
function testModal() {
    abrirModalConfirmacion("HOM-2025-" + Math.floor(Math.random() * 10000));
    console.log("Función de prueba ejecutada");
}

// Initialization
document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".step");
    const stepContents = document.querySelectorAll(".step-content");

    // Habilitar institucion por defecto
    const institucionSelect = document.getElementById('institucion');
    if (institucionSelect) {
        institucionSelect.disabled = false;
        cargarInstituciones();
    }

    document.getElementById("finalizo_estudios").addEventListener('change', toggleFechaFinalizacion);

    if (institucionSelect) {
        institucionSelect.addEventListener('change', validarSENA);
    }

    setTimeout(validarSENA, 500);

    cargarDatosUsuario();
    handlePaisChange();

    document.getElementById("fecha_finalizacion_container").style.display = "none";
    document.getElementById("fecha_ultimo_semestre_container").style.display = "none";

    console.log("Datos cargados:");
    console.log("Programas:", typeof programasPorInstitucion, Array.isArray(programasPorInstitucion) ? programasPorInstitucion.length : 0);

    if (typeof programasPorInstitucion === 'undefined' || !Array.isArray(programasPorInstitucion) || programasPorInstitucion.length === 0) {
        console.error("Error: La variable programasPorInstitucion no está definida correctamente o está vacía");
    }

    if (institucionSelect) {
        institucionSelect.addEventListener('change', function () {
            const institucionId = this.value;
            if (institucionId && typeof programasPorInstitucion !== 'undefined') {
                const filtrados = programasPorInstitucion.filter(p => p.id_institucion == institucionId);
                console.log(`Programas filtrados para institución ID ${institucionId}:`, filtrados);
            }
        });
    }

    if (steps.length > 0 && stepContents.length > 0) {
        steps[0].classList.add("active");
        stepContents[0].classList.add("active");

        const progressBar = document.getElementById("progress-bar");
        if (progressBar) progressBar.style.width = "0%";
    }

    const numeroIdentificacionInput = document.getElementById("numero_identificacion");
    if (numeroIdentificacionInput) {
        numeroIdentificacionInput.addEventListener("input", function () {
            this.value = this.value.replace(/\D/g, "");
        });
    }

    ["primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido"].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", soloLetras);
            input.addEventListener("paste", e => e.preventDefault());
            input.addEventListener("keydown", e => {
                if (e.key.match(/[0-9]/)) e.preventDefault();
            });
        }
    });

    const selectors = {
        "institucion": updateFormacion,
        "programa": updateSemestres,
        "semestre": updateAsignaturas,
        "finalizo_estudios": toggleFechaFinalizacion
    };

    Object.entries(selectors).forEach(([id, handler]) => {
        const element = document.getElementById(id);
        if (element) element.addEventListener("change", handler);
    });

    document.querySelectorAll("input[type='file']").forEach(input => {
        input.addEventListener("change", function () {
            if (this.files.length > 0 && this.files[0].type !== "application/pdf") {
                mostrarMensaje("Solo se permiten archivos en formato PDF.", "error");
                this.value = "";
            }
        });
    });

    const tipoIdentificacion = document.getElementById("tipo_identificacion");
    if (tipoIdentificacion) {
        tipoIdentificacion.addEventListener("change", function () {
            const extraDocsSection = document.getElementById("extra-docs");
            const visaPasaporte = document.getElementById("visa_pasaporte");

            if (this.value === "Cédula de Extranjería") {
                extraDocsSection.classList.remove("hidden");
                visaPasaporte.setAttribute("required", "true");
            } else {
                extraDocsSection.classList.add("hidden");
                visaPasaporte.removeAttribute("required");

                const nuevoInput = visaPasaporte.cloneNode(true);
                visaPasaporte.parentNode.replaceChild(nuevoInput, visaPasaporte);
            }
        });
    }

    // Agregar la función de prueba al objeto window para poder acceder desde la consola
    window.testModal = testModal;

    console.log("Inicialización completa");
});

// Add styles for UI components
const styles = `
.modal-confirmacion {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease, visibility 0.3s ease;
  backdrop-filter: blur(5px);
}

.modal.active, #modalConfirmacion, #modalConfirmacionEnvio, #modalConfirmacionFinal {
  opacity: 1;
  visibility: visible;
}

.modal-content {
  background-color: #fff;
  padding: 24px;
  border-radius: 12px;
  text-align: center;
  max-width: 400px;
  width: 80%;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  border-top: 5px solid #0075bf;
  position: relative;
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

.error-message {
  color: #dc3545;
  font-size: 0.8rem;
  margin-top: 5px;
  display: block;
}

input.error, select.error {
  border-color: #dc3545;
}

.materia-row {
  display: flex;
  align-items: center;
  padding: 10px;
  margin-bottom: 8px;
  border-radius: 5px;
  background-color: #f0f7ff;
}

.semestre-container {
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 10px;
  background-color: #fff;
}

@keyframes slideIn {
  from { transform: translateX(100%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}

@keyframes bounceIn {
  0% { transform: scale(0.8); opacity: 0; }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); opacity: 1; }
}

.loading-indicator {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 2000;
  color: white;
}

.spinner {
  border: 5px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 5px solid white;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
  margin-bottom: 15px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
`;

// Add styles to document
const styleElement = document.createElement('style');
styleElement.textContent = styles;
document.head.appendChild(styleElement);

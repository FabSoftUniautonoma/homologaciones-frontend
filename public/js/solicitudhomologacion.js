// Global variables
let currentStep = 1;
let asignaturasPorPrograma = [];

// Step navigation and progress bar functions
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

  // Update progress bar
  const progressBar = document.getElementById("progress-bar");
  const stepPercentage = ((currentStep - 1) / (steps.length - 1)) * 100;
  progressBar.style.width = `${stepPercentage}%`;
}

// Flash message system
function mostrarMensaje(mensaje, tipo) {
  let mensajeExistente = document.querySelector(".mensaje-flash");
  if (mensajeExistente) mensajeExistente.remove();

  let mensajeElement = document.createElement("div");
  mensajeElement.classList.add("mensaje-flash", tipo);
  mensajeElement.innerHTML = `
    <span>${mensaje}</span>
    <button onclick="this.parentNode.remove()">×</button>
  `;

  document.body.appendChild(mensajeElement);
  setTimeout(() => mensajeElement.remove(), 5000);
}

// Form validation helpers
const regexTexto = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/;
const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/;
const regexTelefono = /^\d{7,10}$/;
const regexNumeroIdentificacion = /^\d+$/;
const regexFecha = /^\d{4}-\d{2}-\d{2}$/;

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

// Institution & Academic program functions
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
  validarSENA();
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
      mensaje.textContent = "Usuario, recuerde que para poder hacer una homologación con el SENA tuvo que haber finalizado sus estudios.";
      document.getElementById("finalizo_estudios").parentElement.appendChild(mensaje);
    }
  } else if (mensajeSENA) {
    mensajeSENA.remove();
  }
}

// Subject management functions
function updateFormacion() {
    const institucionSelect = document.getElementById('institucion');
    const tipoInput = document.getElementById('tipoFormacion');
    const programaSelect = document.getElementById('programa');
    const selectedOption = institucionSelect.options[institucionSelect.selectedIndex];

    tipoInput.value = selectedOption.dataset.formacion || "";

    programaSelect.innerHTML = '<option value="">Seleccione un Programa</option>';
    document.getElementById("semestre").innerHTML = '<option value="">Seleccione un semestre</option>';
    document.getElementById("materia").innerHTML = '<option value="">Seleccione una materia</option>';
    document.getElementById("semestre").disabled = true;
    document.getElementById("materia").disabled = true;

    const institucionId = institucionSelect.value;
    if (!institucionId) {
        programaSelect.disabled = true;
        return;
    }

    const programasFiltrados = programasPorInstitucion.filter(p => p.id_institucion == institucionId);
    programasFiltrados.forEach(programa => {
        const option = document.createElement("option");
        option.value = programa.id_programa;
        option.textContent = programa.nombre;
        programaSelect.appendChild(option);
    });

    programaSelect.disabled = programasFiltrados.length === 0;
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

function agregarMateria() {
  const semestre = document.getElementById("semestre").value;
  const materiaSeleccionada = document.getElementById("materia").value;

  if (!semestre || !materiaSeleccionada) {
    mostrarMensaje("Debe seleccionar una carrera, un semestre y una materia.", "error");
    return;
  }

  let materiaId = `nota_${semestre}_${materiaSeleccionada.replace(/\s+/g, "_")}`;
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
    <label class="materia-label">${materiaSeleccionada}:</label>
    <div class="input-container">
      <input type="text" id="${materiaId}" placeholder="Ingrese nota" class="nota-input" oninput="validarNota(this)">
    </div>
    <button class="delete-button" onclick="borrarMateria('${materiaId}')">
      <i class="fa-solid fa-trash"></i> Borrar
    </button>
  `;

  contenedorSemestre.appendChild(materiaRow);
  mostrarMensaje(`Materia "${materiaSeleccionada}" agregada correctamente.`, "success");
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

  let fragment = document.createDocumentFragment();
  semestres.forEach(semestre => fragment.appendChild(semestre));

  materiasContainer.innerHTML = "";
  materiasContainer.appendChild(fragment);
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

      let materiaNombre = "";
      if (label) {
        materiaNombre = label.textContent.replace(":", "").trim();
        const codigoMatch = materiaNombre.match(/\((\d+)\)/);
        if (codigoMatch && codigoMatch[1]) {
          materiaNombre = codigoMatch[1];
        }
      }

      let nota = "";
      if (input && input.value) {
        nota = input.value.trim().replace(/[^\d,\.]/g, "");
      }

      if (materiaNombre && nota) {
        resultado[semestre].push({
          nombre: materiaNombre,
          nota: nota
        });
      }
    });

    if (resultado[semestre].length === 0) {
      delete resultado[semestre];
    }
  });

  if (Object.keys(resultado).length === 0) {
    console.warn("No se encontraron materias para enviar");
    return {};
  }

  try {
    const jsonString = JSON.stringify(resultado);
    JSON.parse(jsonString);
    return resultado;
  } catch (error) {
    console.error("Error al generar JSON de materias:", error);
    return {};
  }
}

function validarNota(input) {
  let valor = input.value;
  valor = valor.replace(/[^0-9,]/g, "");

  let partes = valor.split(",");
  if (partes.length > 2) {
    valor = partes[0] + "," + partes[1].slice(0, 1);
  }

  if (valor.startsWith(",")) {
    valor = "0" + valor;
  }

  let numero = parseFloat(valor.replace(",", "."));

  if (!isNaN(numero) && numero >= 0 && numero <= 5) {
    input.value = valor;
  } else {
    input.value = "";
  }
}

// Modal functions
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

  const closeIcon = modal.querySelector(".close-icon");
  const btnCancelar = modal.querySelector(".btn-cancelar");
  const btnConfirmar = modal.querySelector(".btn-confirmar");

  const cerrarModal = () => {
    modal.classList.remove("active");
    modal.classList.add("closing");
    setTimeout(() => modal.remove(), 300);
  };

  closeIcon.addEventListener("click", cerrarModal);
  btnCancelar.addEventListener("click", cerrarModal);
  btnConfirmar.addEventListener("click", () => {
    if (onConfirm) onConfirm();
    cerrarModal();
  });

  return modal;
}

// Agregar esta función para cargar los datos del usuario al iniciar
// Función mejorada para cargar datos del usuario
function cargarDatosUsuario() {
  const token = localStorage.getItem('auth_token');
  const userData = localStorage.getItem('user_data');

  // Intentar obtener el ID del usuario del localStorage
  let usuarioId;
  if (userData) {
    try {
      const user = JSON.parse(userData);
      usuarioId = user.id || user.usuario_id;

      // Si tenemos datos completos en user_data, rellenamos directamente
      if (user.tipo_identificacion || user.numero_identificacion || user.email) {
        rellenarCamposUsuario(user);
        return; // Si ya completamos los campos, terminamos
      }
    } catch (error) {
      console.warn("Error al procesar user_data:", error);
    }
  }

  // Si no tenemos ID, no podemos hacer la petición
  if (!usuarioId) {
    console.warn("No se pudo obtener el ID del usuario para cargar sus datos");
    return;
  }

  // Hacer petición a la API con el ID obtenido
  fetch(`http://localhost/Backend-Laravel/public/api/usuarios/${usuarioId}`, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`Error en la petición: ${response.status}`);
    }
    return response.json();
  })
  .then(data => {
    console.log("Datos recibidos de la API:", data); // Para depuración

    if (data.success && data.data) {
      rellenarCamposUsuario(data.data);
    } else if (data) {
      // Si la API no devuelve en formato {success, data} sino directamente los datos
      rellenarCamposUsuario(data);
    }
  })
  .catch(error => {
    console.error("Error al obtener datos del usuario:", error);
  });
}

// Función auxiliar para rellenar campos
function rellenarCamposUsuario(usuario) {
  console.log("Rellenando campos con:", usuario); // Para depuración

  // Tipo de identificación (select)
  if (usuario.tipo_identificacion || usuario.tipo_documento) {
    const tipoDoc = usuario.tipo_identificacion || usuario.tipo_documento;
    const selectTipo = document.getElementById('tipo_identificacion');

    for (let i = 0; i < selectTipo.options.length; i++) {
      if (selectTipo.options[i].value === tipoDoc) {
        selectTipo.selectedIndex = i;
        break;
      }
    }
  }

  // Número de identificación
  if (usuario.numero_identificacion || usuario.numero_documento) {
    document.getElementById('numero_identificacion').value =
      usuario.numero_identificacion || usuario.numero_documento;
  }

  // Email
  if (usuario.email || usuario.correo) {
    document.getElementById('email').value = usuario.email || usuario.correo;
  }

  // Nombres
  if (usuario.primer_nombre) {
    document.getElementById('primer_nombre').value = usuario.primer_nombre;
  } else if (usuario.nombres) {
    const nombres = usuario.nombres.split(' ');
    document.getElementById('primer_nombre').value = nombres[0] || '';
    if (nombres.length > 1) {
      document.getElementById('segundo_nombre').value = nombres[1] || '';
    }
  }

  // Apellidos
  if (usuario.primer_apellido) {
    document.getElementById('primer_apellido').value = usuario.primer_apellido;
  } else if (usuario.apellidos) {
    const apellidos = usuario.apellidos.split(' ');
    document.getElementById('primer_apellido').value = apellidos[0] || '';
    if (apellidos.length > 1) {
      document.getElementById('segundo_apellido').value = apellidos[1] || '';
    }
  }

  // Segundo nombre y segundo apellido directos si existen
  if (usuario.segundo_nombre) {
    document.getElementById('segundo_nombre').value = usuario.segundo_nombre;
  }

  if (usuario.segundo_apellido) {
    document.getElementById('segundo_apellido').value = usuario.segundo_apellido;
  }

  // Teléfono
  if (usuario.telefono) {
    document.getElementById('telefono').value = usuario.telefono;
  }
}

function handlePaisChange() {
    const paisSelect = document.getElementById('pais');
    const departamentoSelect = document.getElementById('departamento');
    const municipioSelect = document.getElementById('municipio');
    const institucionSelect = document.getElementById('institucion');

    // Obtener el valor seleccionado
    const paisSeleccionado = paisSelect.value;

    // Deshabilitar institución y siguientes campos
    institucionSelect.disabled = true;
    document.getElementById('programa').disabled = true;
    document.getElementById('finalizo_estudios').disabled = true;

    // Si es Colombia (asumiendo que el ID es 1) habilitar campos de departamento
    if (paisSeleccionado === '1') { // Colombia
        departamentoSelect.disabled = false;
        // Resetear el municipio al cambiar país
        municipioSelect.innerHTML = '<option value="">Seleccione un Municipio</option>';
        municipioSelect.disabled = true;
    } else {
        // Si no es Colombia, deshabilitar y limpiar los campos
        departamentoSelect.disabled = true;
        departamentoSelect.value = "";

        municipioSelect.disabled = true;
        municipioSelect.value = "";
    }
}

// Step validation functions
// Modificar la función de validación del Step 1
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

    // Validar campos base
    for (const [id, mensaje] of Object.entries(campos)) {
        if (!validarCampo(document.getElementById(id), mensaje)) {
            valido = false;
        }
    }

    // Si el país seleccionado es Colombia, validar departamento y municipio
    const paisSelect = document.getElementById('pais');
    if (paisSelect.value === '1') { // Colombia
        if (!validarCampo(document.getElementById('departamento'), "Seleccione un departamento")) {
            valido = false;
        }
        if (!validarCampo(document.getElementById('municipio'), "Seleccione un municipio")) {
            valido = false;
        }
    }

    // Validar formato de los campos
    const nombres = ["primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido"];
    nombres.forEach(id => {
        const campo = document.getElementById(id);
        if (campo && campo.value.trim() && !regexTexto.test(campo.value)) {
            campo.classList.add("error");
            const errorMensaje = campo.nextElementSibling;
            if (errorMensaje) errorMensaje.textContent = "Solo se permiten letras";
            valido = false;
        }
    });



    // Validar número de identificación
    const numId = document.getElementById("numero_identificacion");
    if (!regexNumeroIdentificacion.test(numId.value)) {
        numId.classList.add("error");
        const errorMsg = numId.nextElementSibling;
        if (errorMsg) errorMsg.textContent = "Ingrese solo números";
        valido = false;
    }

    // Validar email
    const email = document.getElementById("email");
    if (!regexEmail.test(email.value)) {
        email.classList.add("error");
        const errorEmail = email.nextElementSibling;
        if (errorEmail) errorEmail.textContent = "Ingrese un correo válido";
        valido = false;
    }

    // Validar teléfono
    const telefono = document.getElementById("telefono");
    if (!regexTelefono.test(telefono.value)) {
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
  let valido = true;

  const institucion = document.getElementById("institucion");
  const tipoFormacion = document.getElementById("tipoFormacion");
  const carrera = document.getElementById("programa");
  const finalizoEstudios = document.getElementById("finalizo_estudios");
  const fechaFinalizacion = document.getElementById("fecha_finalizacion");
  const fechaUltimoSemestre = document.getElementById("fecha_ultimo_semestre");

  if (institucion.value === "SENA" && finalizoEstudios.value === "no") {
    alert("No puede continuar con la homologación si no ha finalizado sus estudios en el SENA.");
    return false;
  }

  // Función para activar el selector de instituciones cuando se selecciona un municipio
function activarInstitucionSelect() {
    const municipioSelect = document.getElementById('municipio_origen');
    const institucionSelect = document.getElementById('institucion');

    if (municipioSelect.value) {
        institucionSelect.disabled = false;
    } else {
        institucionSelect.disabled = true;
    }
}

  validarCampo(institucion, "Seleccione su institución de origen");
  validarCampo(tipoFormacion, "Seleccione su tipo de formación");
  validarCampo(carrera, "Seleccione su carrera o programa");
  validarCampo(finalizoEstudios, "Seleccione si finalizó sus estudios");

  if (finalizoEstudios.value === "si") {
    validarCampo(fechaFinalizacion, "Ingrese la fecha de finalización de sus estudios");
    if (!regexFecha.test(fechaFinalizacion.value)) {
      fechaFinalizacion.classList.add("error");
      fechaFinalizacion.parentElement.querySelector(".error-message").textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
      valido = false;
    }
  } else if (finalizoEstudios.value === "no") {
    validarCampo(fechaUltimoSemestre, "Ingrese la fecha del último semestre cursado");
    if (!regexFecha.test(fechaUltimoSemestre.value)) {
      fechaUltimoSemestre.classList.add("error");
      fechaUltimoSemestre.parentElement.querySelector(".error-message").textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
      valido = false;
    }
  }

  if (valido) {
    mostrarMensaje("Paso 2 finalizado correctamente", "success");
    changeStep(1);
  }

  return valido;
}

function validacionStep3() {
  let materias = document.querySelectorAll(".nota-input");

  if (materias.length < 6) {
    mostrarModalConfirmacion("Error", "Debe registrar al menos 6 materias antes de continuar.", "Cerrar");
    return false;
  }

  let errores = [];
  let materiasGuardadas = [];

  materias.forEach(input => {
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

  // Validar documentos obligatorios
  valido &= validarArchivo(documentoId, "Debe subir su Documento de Identidad.");
  valido &= validarArchivo(certificadoNotas, "Debe subir su Certificado de Notas.");
  valido &= validarArchivo(contenidoProgramatico, "Debe subir el Contenido Programático.");
  valido &= validarArchivo(cartaHomologacion, "Debe subir la Carta de Solicitud de Homologación.");

  // Validar Certificación de Finalización si finalizó estudios
  if (document.getElementById("finalizo_estudios").value === "si") {
    valido &= validarArchivo(
      document.getElementById("certificacion_finalizacion"),
      "Debe subir la Certificación de Finalización de Estudios."
    );
  }

  // Validar documentos de extranjería si aplica
  if (document.getElementById("tipo_identificacion").value === "Cédula de Extranjería") {
    document.getElementById("extra-docs").classList.remove("hidden");
    document.getElementById("visa_pasaporte").setAttribute("required", "true");
    valido &= validarArchivo(
      document.getElementById("visa_pasaporte"),
      "Debe subir una copia de su Visa o Pasaporte."
    );
  }

  if (valido) {
    mostrarMensaje("Paso 4 finalizado correctamente", "success");
    changeStep(1);
  }

  return valido;
}

// Form submission functions
function confirmarDatos() {
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
    <button id="confirmarBtn">Confirmar</button>
    <button onclick="cerrarModal()">Rechazar</button>
  `;

  const modal = document.createElement("div");
  modal.id = "modalConfirmacion";
  modal.classList.add("modal");
  modal.innerHTML = `<div class="modal-content">${mensaje}</div>`;
  document.body.appendChild(modal);

  setTimeout(() => {
    document.getElementById("confirmarBtn").addEventListener("click", enviarFormulario);
  }, 100);
}

function updateDepartamentos() {
    const paisId = document.getElementById('pais').value;
    const departamentoSelect = document.getElementById('departamento');
    const municipioSelect = document.getElementById('municipio');

    // Resetear selectores dependientes
    departamentoSelect.innerHTML = '<option value="">Seleccione un departamento</option>';
    departamentoSelect.disabled = true;
    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
    municipioSelect.disabled = true;

    if (!paisId) return;

    // Filtrar departamentos por país seleccionado
    const departamentosFiltrados = json($departamentos).filter(d => d.id_pais == paisId);

    departamentosFiltrados.forEach(departamento => {
        const option = document.createElement('option');
        option.value = departamento.departamento;
        option.setAttribute('data-id', departamento.id_departamento);
        option.textContent = departamento.departamento;
        departamentoSelect.appendChild(option);
    });

    departamentoSelect.disabled = departamentosFiltrados.length === 0;
}


function enviarFormulario() {
  const formData = new FormData();

  try {
    // Añadir datos de usuario
    const campos = [
      "tipo_identificacion", "numero_identificacion", "primer_nombre",
      "segundo_nombre", "primer_apellido", "segundo_apellido",
      "email", "telefono", "direccion", "pais"
    ];

    campos.forEach(campo => {
      formData.append(campo, document.getElementById(campo).value || "");
    });

    // Añadir ID departamento
    const departamentoSelect = document.getElementById("departamento");
    const idDepartamento = departamentoSelect.options[departamentoSelect.selectedIndex].getAttribute("data-id");
    formData.append("departamento", idDepartamento);
    formData.append("municipio", document.getElementById("municipio").value);

    // Añadir datos académicos
    formData.append("programa", document.getElementById("programa").value);
    const finalizo = document.getElementById("finalizo_estudios").value;
    formData.append("finalizo_estudios", finalizo);

    if (finalizo === "si") {
      formData.append("fecha_finalizacion", document.getElementById("fecha_finalizacion").value);
      formData.append("fecha_ultimo_semestre", "");
    } else {
      formData.append("fecha_ultimo_semestre", document.getElementById("fecha_ultimo_semestre").value);
      formData.append("fecha_finalizacion", "");
    }

   // Generar número de radicado
   const año = new Date().getFullYear();
   const contador = String(Math.floor(Math.random() * 1000) + 1).padStart(4, "0");
   formData.append("numero_rad", `HOM-${año}-${contador}`);
   formData.append("password", "12345678");

   // Procesar materias
   const materias = obtenerMaterias();
   if (Object.keys(materias).length === 0) {
     mostrarMensaje("No se han ingresado materias para homologar.", "error");
     return;
   }

   try {
     const materiasJSON = JSON.stringify(materias);
     JSON.parse(materiasJSON); // Validar JSON
     formData.append("materias", materiasJSON);
   } catch (error) {
     console.error("Error al validar JSON de materias:", error);
     mostrarMensaje("Error al procesar las materias.", "error");
     return;
   }

   // Añadir documentos
   const documentos = [
     { id: "certificado_notas", tipo: "Certificado de Notas" },
     { id: "contenido_programatico", tipo: "Contenido Programático" },
     { id: "carta_homologacion", tipo: "Carta de Solicitud" },
     { id: "certificacion_finalizacion", tipo: "Certificación de Finalización de Estudios" },
     { id: "visa_pasaporte", tipo: "Copia de la Visa" }
   ];

   documentos.forEach(doc => {
     const fileInput = document.getElementById(doc.id);
     if (fileInput && fileInput.files.length > 0) {
       formData.append("documentos[]", fileInput.files[0]);
       formData.append("tipos[]", doc.tipo);
     }
   });

   // Enviar solicitud
   fetch("http://localhost/Backend-Laravel/public/api/solicitud-completa", {
     method: "POST",
     body: formData
   })
   .then(response => {
     if (!response.ok) {
       return response.json().then(errorData => {
         throw new Error(errorData.message || "Error en la solicitud");
       });
     }
     return response.json();
   })
   .then(data => {
     console.log("Respuesta exitosa:", data);
     mostrarMensaje("¡Solicitud enviada correctamente!", "success");
     // Opcionalmente redirigir
     // window.location.href = "confirmacion.html";
   })
   .catch(error => {
     console.error("Error:", error);
     mostrarMensaje(`Error al enviar la solicitud: ${error.message}`, "error");
   });
 } catch (error) {
   console.error("Error al preparar el formulario:", error);
   mostrarMensaje("Ocurrió un error al preparar el formulario.", "error");
 }
}


// Initialization code
document.addEventListener("DOMContentLoaded", function() {
 // Setup progress bar and first step
 const steps = document.querySelectorAll(".step");
 const stepContents = document.querySelectorAll(".step-content");
  cargarDatosUsuario();
   handlePaisChange();


 // Initialize first step
 if (steps.length > 0 && stepContents.length > 0) {
   steps[0].classList.add("active");
   stepContents[0].classList.add("active");

   // Initialize progress bar
   const progressBar = document.getElementById("progress-bar");
   if (progressBar) progressBar.style.width = "0%";
 }

 // Restrict input fields
 document.getElementById("numero_identificacion")?.addEventListener("input", function() {
   this.value = this.value.replace(/\D/g, "");
 });

 // Setup text-only fields
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

 // Setup institution change handlers
 const institucionSelect = document.getElementById("institucion");
 if (institucionSelect) {
   institucionSelect.addEventListener("change", updateFormacion);
 }

 // Setup program change handlers
 const programaSelect = document.getElementById("programa");
 if (programaSelect) {
   programaSelect.addEventListener("change", updateSemestres);
 }

 // Setup semester change handlers
 const semestreSelect = document.getElementById("semestre");
 if (semestreSelect) {
   semestreSelect.addEventListener("change", updateAsignaturas);
 }

 // Setup file input validation
 document.querySelectorAll("input[type='file']").forEach(input => {
   input.addEventListener("change", function() {
     if (this.files.length > 0 && this.files[0].type !== "application/pdf") {
       mostrarMensaje("Solo se permiten archivos en formato PDF.", "error");
       this.value = "";
     }
   });
 });

 // Setup SENA validation
 const finalizoEstudios = document.getElementById("finalizo_estudios");
 if (finalizoEstudios) {
   finalizoEstudios.addEventListener("change", toggleFechaFinalizacion);
 }

 // Check for extranjero documents
 const tipoIdentificacion = document.getElementById("tipo_identificacion");
 if (tipoIdentificacion) {
   tipoIdentificacion.addEventListener("change", function() {
     const extraDocsSection = document.getElementById("extra-docs");
     const visaPasaporte = document.getElementById("visa_pasaporte");

     if (this.value === "Cédula de Extranjería") {
       extraDocsSection.classList.remove("hidden");
       visaPasaporte.setAttribute("required", "true");
     } else {
       extraDocsSection.classList.add("hidden");
       visaPasaporte.removeAttribute("required");

       // Reset field if changing from foreigner to national
       const nuevoInput = visaPasaporte.cloneNode(true);
       visaPasaporte.parentNode.replaceChild(nuevoInput, visaPasaporte);
     }
   });
 }


// Función para activar el selector de instituciones
function activarInstituciones() {
    const municipioSelect = document.getElementById('municipio_origen');
    const institucionSelect = document.getElementById('institucion');

    if (municipioSelect.value) {
        institucionSelect.disabled = false;
    } else {
        institucionSelect.disabled = true;
    }
}

 // Añadir función para activar instituciones después de seleccionar municipio
function updateMunicipios() {
    const departamentoSelect = document.getElementById('departamento');
    const municipioSelect = document.getElementById('municipio');
    const institucionSelect = document.getElementById('institucion');
    const departamentoSeleccionado = departamentoSelect.value;

    // Resetear y deshabilitar institución
    institucionSelect.disabled = true;

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

    // Agregar evento change para municipio
    municipioSelect.addEventListener('change', activarInstituciones);
}


// Función para activar el selector de instituciones
function activarInstituciones() {
    const municipioSelect = document.getElementById('municipio');
    const institucionSelect = document.getElementById('institucion');

    if (municipioSelect.value) {
        institucionSelect.disabled = false;
    } else {
        institucionSelect.disabled = true;
    }
}
});

// CSS styles for modals and notifications
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

.modal.active, #modalConfirmacion, #modalConfirmacionEnvio {
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
`;

// Add styles to document
const styleElement = document.createElement('style');
styleElement.textContent = styles;
document.head.appendChild(styleElement);

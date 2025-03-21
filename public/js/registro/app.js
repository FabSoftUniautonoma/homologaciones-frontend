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
        stepContents.forEach(content => content.classList.remove("active"));

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
// Validacion de el formulario
// Expresiones regulares
const regexTexto = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios
const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/;
const regexTelefono = /^\d{7,10}$/; // Teléfonos de 7 a 10 dígitos
const regexNumeroIdentificacion = /^\d+$/; // Solo números

// Restringir entrada de caracteres no permitidos en Número de Identificación y Teléfono
document.getElementById("numero_identificacion").addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, ""); // Elimina cualquier carácter que no sea número
});

document.getElementById("telefono").addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, ""); // Elimina cualquier carácter que no sea número
});
function validarFormularioStep1() {
    let valido = true;

    // Obtener los campos
    const tipoIdentificacion = document.getElementById("tipo_identificacion");
    const numeroIdentificacion = document.getElementById("numero_identificacion");
    const primerNombre = document.getElementById("primer_nombre");
    const segundoNombre = document.getElementById("segundo_nombre");
    const primerApellido = document.getElementById("primer_apellido");
    const segundoApellido = document.getElementById("segundo_apellido");
    const email = document.getElementById("email");
    const telefono = document.getElementById("telefono");
    const direccion = document.getElementById("direccion");

    // Expresiones regulares
    const regexTexto = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/; // Solo letras y espacios
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/;
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


    // Validar que los nombres y apellidos solo contengan letras
    [primerNombre, segundoNombre, primerApellido, segundoApellido].forEach(campo => {
        const errorMensaje = campo.nextElementSibling;
        if (campo.value.trim() && !regexTexto.test(campo.value)) {
            campo.classList.add("error");
            if (errorMensaje) errorMensaje.textContent = "Solo se permiten letras";
            valido = false;
        }
    });

    // Validar número de identificación (solo números)
    const errorNumeroIdentificacion = numeroIdentificacion.nextElementSibling;
    if (!regexNumeroIdentificacion.test(numeroIdentificacion.value)) {
        numeroIdentificacion.classList.add("error");
        if (errorNumeroIdentificacion) errorNumeroIdentificacion.textContent = "Ingrese solo números";
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
        if (errorTelefono) errorTelefono.textContent = "Ingrese un número de teléfono válido (7 a 10 dígitos)";
        valido = false;
    }

    // Si todo es válido, mostrar mensaje y avanzar al siguiente paso
    if (valido) {
        alert("Formulario enviado correctamente");
        changeStep(1);
    }
}
// Datos de departamentos y municipios de Colombia
const departamentosMunicipios = {
    "Antioquia": ["Medellín", "Bello", "Itagüí", "Envigado"],
    "Cundinamarca": ["Bogotá", "Soacha", "Zipaquirá", "Girardot"],
    "Valle del Cauca": ["Cali", "Palmira", "Buenaventura", "Tuluá"],
    "Atlántico": ["Barranquilla", "Soledad", "Malambo", "Puerto Colombia"],
    "Santander": ["Bucaramanga", "Floridablanca", "Girón", "Piedecuesta"],
    "Bolívar": ["Cartagena", "Magangué", "Turbaco", "Arjona"],
    "Nariño": ["Pasto", "Ipiales", "Tumaco", "Túquerres"],
    "Cauca": ["Popayán", "Santander de Quilichao", "Puerto Tejada", "Piendamó"]
};

function cargarDepartamentos() {
    const departamentoSelect = document.getElementById("departamento");
    Object.keys(departamentosMunicipios).forEach(departamento => {
        let option = document.createElement("option");
        option.value = departamento;
        option.textContent = departamento;
        departamentoSelect.appendChild(option);
    });
}

function updateMunicipios() {
    const municipioSelect = document.getElementById("municipio");
    const departamentoSelect = document.getElementById("departamento").value;

    municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
    if (departamentoSelect && departamentosMunicipios[departamentoSelect]) {
        departamentosMunicipios[departamentoSelect].forEach(municipio => {
            let option = document.createElement("option");
            option.value = municipio;
            option.textContent = municipio;
            municipioSelect.appendChild(option);
        });
    }
}

// Cargar departamentos y validar formulario al cargar la página
window.onload = function () {
    cargarDepartamentos();
    validateForm();
};
//AQUI TERMINA LA VALIDACION DE EL PRIMER STEP ( NO GUARDA LA INFROMACION , SOLO VALIDA QUE FUNCIONE CORRECTAMENTE Y VALIDA TODOS LOS CAMPOS)
// INICIA EL STEP 2

// Función para actualizar las opciones de formación según la institución
function updateFormacion() {
    var institucion = document.getElementById("institucion").value;
    var tipoFormacion = document.getElementById("tipo_formacion");
    tipoFormacion.innerHTML = '<option value="">Seleccione</option>';

    var formaciones = {
        "SENA": ["Técnico", "Tecnólogo"],
        "FUP": ["Universitario"],
        "Colegio Mayor": ["Tecnólogo", "Universitario"]
    };

    if (formaciones[institucion]) {
        formaciones[institucion].forEach(function (tipo) {
            var option = document.createElement("option");
            option.value = tipo;
            option.textContent = tipo;
            tipoFormacion.appendChild(option);
        });
    }
}

// Función para actualizar carreras según la institución y el tipo de formación
function updateCarreras() {
    var institucion = document.getElementById("institucion").value;
    var tipoFormacion = document.getElementById("tipo_formacion").value;
    var carrera = document.getElementById("carrera");
    carrera.innerHTML = '<option value="">Seleccione</option>';

    var opciones = {
        "SENA": {
            "Técnico": ["Técnico en Sistemas", "Técnico en Programación de Software"],
            "Tecnólogo": ["Tecnólogo en Análisis y Desarrollo de Sistemas", "Tecnología en Gestión de Redes de Datos", "Tecnología en Desarrollo de Videojuegos"]
        },
        "FUP": {
            "Universitario": ["Ingeniería de Sistemas", "Ingeniería Industrial"]
        },
        "Colegio Mayor": {
            "Tecnólogo": ["Tecnólogo en Diseño Gráfico"],
            "Universitario": ["Ingeniería Informática", "Ingeniería de Sistemas", "Ingeniería Multimedia"]
        }
    };

    if (opciones[institucion] && opciones[institucion][tipoFormacion]) {
        opciones[institucion][tipoFormacion].forEach(function (nombre) {
            var option = document.createElement("option");
            option.value = nombre;
            option.textContent = nombre;
            carrera.appendChild(option);
        });
    }

}
function toggleFechaFinalizacion() {
    const finalizoEstudios = document.getElementById("finalizo_estudios").value;
    document.getElementById("fecha_finalizacion_container").style.display = finalizoEstudios === "si" ? "block" : "none";
    document.getElementById("fecha_ultimo_semestre_container").style.display = finalizoEstudios === "no" ? "block" : "none";
}

function validarFormularioStep2() {
    let valido = true;

    // Obtener los campos del formulario
    const institucion = document.getElementById("institucion");
    const tipoFormacion = document.getElementById("tipo_formacion");
    const carrera = document.getElementById("carrera");
    const finalizoEstudios = document.getElementById("finalizo_estudios");
    const fechaFinalizacion = document.getElementById("fecha_finalizacion");
    const fechaUltimoSemestre = document.getElementById("fecha_ultimo_semestre");

    // Expresión regular para validar fechas en formato YYYY-MM-DD
    const regexFecha = /^\d{4}-\d{2}-\d{2}$/;

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

    // Validar los campos obligatorios
    validarCampo(institucion, "Seleccione su institución de origen");
    validarCampo(tipoFormacion, "Seleccione su tipo de formación");
    validarCampo(carrera, "Seleccione su carrera o programa");
    validarCampo(finalizoEstudios, "Seleccione si finalizó sus estudios");

    // Validar fechas según la selección de finalización de estudios
    if (finalizoEstudios.value === "si") {
        validarCampo(fechaFinalizacion, "Ingrese la fecha de finalización de sus estudios");
        if (!regexFecha.test(fechaFinalizacion.value)) {
            fechaFinalizacion.classList.add("error");
            fechaFinalizacion.nextElementSibling.textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
            alert("Ingrese una fecha válida (YYYY-MM-DD)");

        }
        fechaUltimoSemestre.classList.remove("error");
        fechaUltimoSemestre.nextElementSibling.textContent = "";
    } else if (finalizoEstudios.value === "no") {
        validarCampo(fechaUltimoSemestre, "Ingrese la fecha del último semestre cursado");
        if (!regexFecha.test(fechaUltimoSemestre.value)) {
            fechaUltimoSemestre.classList.add("error");
            fechaUltimoSemestre.nextElementSibling.textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
        fechaFinalizacion.classList.remove("error");
        fechaFinalizacion.nextElementSibling.textContent = "";
    }

    // Si la validación es correcta, avanzar al siguiente paso
    if (valido) {
        alert("Formulario enviado correctamente");

        changeStep(1);
    }
}




// STEP NUMERO 3
// Pensum: Lista de materias según el programa académico
const pensum = {
    "Ingeniería de Sistemas": {
        "Semestre 1": ["Fundamentos de Programación", "Matemáticas I", "Introducción a la Ingeniería de Software", "Comunicación Oral y Escrita", "Electiva Sociohumanística I"],
        "Semestre 2": ["Estructuras de Datos", "Matemáticas II", "Arquitectura de Computadores", "Electiva de Contexto I"],
        "Semestre 3": ["Bases de Datos I", "Matemáticas Discretas", "Sistemas Operativos", "Electiva Sociohumanística II"],
        "Semestre 4": ["Ingeniería de Requisitos", "Bases de Datos II", "Redes de Computadores", "Electiva de Contexto II"],
        "Semestre 5": ["Diseño de Software", "Programación Web", "Gestión de Proyectos de Software", "Electiva Profesional I"],
        "Semestre 6": ["Pruebas y Calidad de Software", "Seguridad Informática", "Electiva Profesional II", "Electiva de Contexto III"],
        "Semestre 7": ["Arquitectura de Software", "Desarrollo de Aplicaciones Móviles", "Electiva Profesional III"],
        "Semestre 8": ["Seminario de Investigación", "Gestión de la Innovación en Software", "Electiva Profesional IV"],
        "Semestre 9": ["Trabajo de Grado", "Práctica Profesional"]
    },
    "Ingeniería Industrial": {
        "Semestre 1": ["Introducción a la Ingeniería Industrial", "Matemáticas I", "Física I", "Química General", "Comunicación Oral y Escrita"],
        "Semestre 2": ["Matemáticas II", "Física II", "Dibujo Técnico", "Fundamentos de Programación", "Electiva de Contexto I"],
        "Semestre 3": ["Estadística Descriptiva", "Mecánica", "Contabilidad General", "Electiva Sociohumanística I"],
        "Semestre 4": ["Estadística Inferencial", "Termodinámica", "Costos y Presupuestos", "Electiva de Contexto II"],
        "Semestre 5": ["Investigación de Operaciones I", "Electiva Profesional I", "Electiva Sociohumanística II"],
        "Semestre 6": ["Investigación de Operaciones II", "Electiva Profesional II", "Electiva de Contexto III"],
        "Semestre 7": ["Gestión de la Producción", "Electiva Profesional III"],
        "Semestre 8": ["Gestión de la Calidad", "Electiva Profesional IV"],
        "Semestre 9": ["Práctica Empresarial"],
        "Semestre 10": ["Trabajo de Grado"]
    },
    "Técnico en Sistemas": {
        "Semestre 1": ["Redes de Computadores", "Sistemas Operativos", "Soporte Técnico"],
        "Semestre 2": ["Bases de Datos", "Programación Web", "Seguridad Informática"],
        "Semestre 3": ["Desarrollo de Aplicaciones", "Virtualización de Servidores", "Gestión de Redes"],
        "Semestre 4": ["Administración de Servidores", "Ciberseguridad Básica", "Configuración de Firewalls"],
        "Semestre 5": ["Mantenimiento de Hardware", "Automatización de Procesos", "Implementación de VPNs"],
        "Semestre 6": ["Monitorización de Sistemas", "Gestión de Incidentes de Seguridad", "Diseño de Redes Empresariales"]
    },
    "Técnico en Programación de Software": {
        "Semestre 1": ["Desarrollo de Aplicaciones de Software", "Manejo de Lenguajes de Programación", "Fundamentos de Algoritmos"],
        "Semestre 2": ["Diseño de Interfaces Gráficas", "Pruebas de Software", "Implementación de Soluciones Informáticas"],
        "Semestre 3": ["Bases de Datos", "Arquitectura de Software", "Desarrollo Web"],
        "Semestre 4": ["Desarrollo de Aplicaciones Móviles", "Seguridad en el Desarrollo de Software", "Gestión de Proyectos de Software"]
    },
    "Tecnología en Desarrollo de Videojuegos": {
        "Semestre 1": ["Fundamentos de Programación en Videojuegos", "Diseño de Videojuegos", "Matemáticas para Gráficos"],
        "Semestre 2": ["Motores Gráficos", "Narrativa Interactiva", "Inteligencia Artificial en Juegos"],
        "Semestre 3": ["Diseño de Niveles", "Multijugador y Redes", "Optimización de Videojuegos"],
        "Semestre 4": ["Realidad Virtual y Aumentada", "Modelado y Animación 3D", "Proyecto Final de Videojuegos"]
    },

    "Tecnólogo en Diseño Gráfico": {
        "Semestre 1": ["Fundamentos del Diseño Gráfico", "Historia del Arte y el Diseño", "Técnicas de Ilustración Digital"],
        "Semestre 2": ["Tipografía y Diagramación", "Fotografía y Edición Digital", "Teoría del Color y Composición"],
        "Semestre 3": ["Diseño de Identidad Visual", "Producción Audiovisual", "Animación 2D y 3D"],
        "Semestre 4": ["Diseño Web y Experiencia de Usuario", "Marketing y Publicidad Digital", "Proyecto Final de Diseño Gráfico"]
    },
    "Ingeniería Multimedia": {
        "Semestre 1": ["Fundamentos de Diseño Multimedia", "Matemáticas y Lógica Computacional", "Programación Básica"],
        "Semestre 2": ["Edición y Producción Audiovisual", "Diseño de Interfaces y Experiencia de Usuario", "Animación Digital 2D"],
        "Semestre 3": ["Modelado y Animación 3D", "Narrativa Digital y Storytelling", "Desarrollo de Aplicaciones Interactivas"],
        "Semestre 4": ["Realidad Virtual y Aumentada", "Inteligencia Artificial en Medios Digitales", "Proyecto Final de Ingeniería Multimedia"]
    },
    "Ingeniería Informática": {
        "Semestre 1": ["Fundamentos de Programación", "Matemáticas Discretas", "Arquitectura de Computadores"],
        "Semestre 2": ["Estructuras de Datos", "Sistemas Operativos", "Bases de Datos I"],
        "Semestre 3": ["Redes de Computadores", "Programación Orientada a Objetos", "Ingeniería de Software"],
        "Semestre 4": ["Bases de Datos II", "Seguridad Informática", "Desarrollo Web"],
        "Semestre 5": ["Gestión de Proyectos de Software", "Inteligencia Artificial", "Computación en la Nube"],
        "Semestre 6": ["Arquitectura de Software", "Ciberseguridad", "Desarrollo de Aplicaciones Móviles"],
        "Semestre 7": ["Big Data y Analítica", "Internet de las Cosas", "Práctica Profesional I"],
        "Semestre 8": ["Computación Gráfica", "Blockchain y Criptografía", "Práctica Profesional II"],
        "Semestre 9": ["Trabajo de Grado I", "Ética Profesional", "Emprendimiento Tecnológico"],
        "Semestre 10": ["Trabajo de Grado II", "Innovación y Transformación Digital", "Electiva Profesional"]
    },
    "Tecnólogo en Análisis y Desarrollo de Sistemas": {
        "Semestre 1": ["Fundamentos de Programación", "Lógica de Programación", "Matemáticas Aplicadas", "Introducción a las Bases de Datos"],
        "Semestre 2": ["Estructuras de Datos", "Desarrollo Web Básico", "Programación Orientada a Objetos", "Modelado de Bases de Datos"],
        "Semestre 3": ["Desarrollo de Aplicaciones Móviles", "Bases de Datos Avanzadas", "Ingeniería de Software", "Redes y Comunicaciones"],
        "Semestre 4": ["Programación en la Nube", "Pruebas de Software", "Metodologías Ágiles", "Desarrollo Seguro de Aplicaciones"]
    },

    "Tecnología en Gestión de Redes de Datos": {
        "Semestre 1": ["Fundamentos de Redes", "Sistemas Operativos", "Matemáticas para TI", "Fundamentos de Programación"],
        "Semestre 2": ["Administración de Redes", "Protocolos de Comunicación", "Bases de Datos", "Seguridad en Redes"],
        "Semestre 3": ["Configuración de Servidores", "Redes Inalámbricas", "Programación en Redes", "Gestión de Proyectos TIC"],
        "Semestre 4": ["Monitoreo y Diagnóstico de Redes", "Infraestructura Cloud", "Seguridad Avanzada en Redes", "Ética y Legislación TIC"],
        "Semestre 5": ["Automatización de Redes", "Gestión de Servicios en la Nube", "Administración de Data Centers", "Trabajo de Grado"]
    }

};
function updateSemestres() {
    const carrera = document.getElementById("carrera").value;
    const semestreSelect = document.getElementById("semestre");
    semestreSelect.innerHTML = `<option value="">Seleccione un semestre</option>`;

    if (!pensum[carrera]) return;

    Object.keys(pensum[carrera]).forEach(semestre => {
        let option = document.createElement("option");
        option.value = semestre;
        option.textContent = `${semestre}`;
        semestreSelect.appendChild(option);
    });
}

function updateMateriasPorSemestre() {
    const carrera = document.getElementById("carrera").value;
    const semestre = document.getElementById("semestre").value;
    const materiaSelect = document.getElementById("materia");

    materiaSelect.innerHTML = `<option value="">Seleccione una materia</option>`;

    if (pensum[carrera] && pensum[carrera][semestre]) {
        pensum[carrera][semestre].forEach(materia => {
            let option = document.createElement("option");
            option.value = materia;
            option.textContent = materia;
            materiaSelect.appendChild(option);
        });
    }
}

function agregarMateria() {
    const semestre = document.getElementById("semestre").value;
    const materiaSeleccionada = document.getElementById("materia").value;

    if (!semestre || !materiaSeleccionada) {
        alert("Debe seleccionar un semestre y una materia.");
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
        alert("Esta materia ya ha sido seleccionada en este semestre.");
        return;
    }

    // Crear la fila de materia
    let materiaRow = document.createElement("div");
    materiaRow.classList.add("materia-row");
    materiaRow.innerHTML = `
        <label class="materia-label">${materiaSeleccionada}:</label>

        <div class="input-container">
            <input type="text" id="${materiaId}" placeholder="Ingrese nota" class="nota-input" disabled
                oninput="validarNota(this)">
        </div>

        <div class="switch-container">
            <span class="switch-label">Cursada</span>
            <label class="switch">
                <input type="checkbox" id="check_${materiaId}" onchange="toggleNota('${materiaId}')">
                <span class="slider"></span>
            </label>
        </div>

        <button class="save-button" onclick="guardarNota('${materiaId}', '${materiaSeleccionada}')">
            <i class="fa-regular fa-floppy-disk"></i> Guardar
        </button>
    `;

    // Agregar la materia dentro del semestre correspondiente
    contenedorSemestre.appendChild(materiaRow);

    // Reordenar los semestres en el DOM
    ordenarSemestresAlfabeticamente();
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




function validarNota(input) {
    let valor = input.value;

    // Permitir solo números del 0 al 5, una coma y un decimal de 0 a 9
    valor = valor.replace(/[^0-9,]/g, '');

    // Verificar si hay más de una coma y eliminar las extras
    let partes = valor.split(',');
    if (partes.length > 2) {
        valor = partes[0] + ',' + partes[1].slice(0, 1); // Mantener solo un decimal
    }

    // Si el usuario empieza con coma, corregirlo
    if (valor.startsWith(',')) {
        valor = '0' + valor; // Asegurar que "0," sea válido
    }

    // Convertir a número y validar rango
    let numero = parseFloat(valor.replace(',', '.'));

    if (!isNaN(numero) && numero >= 0 && numero <= 5) {
        input.value = valor; // Mantener el valor formateado sin afectar la edición
    } else {
        input.value = ""; // Borrar si está fuera de rango
    }
}


function guardarNota(materiaId, materiaNombre) {
    let notaInput = document.getElementById(materiaId);
    let nota = notaInput.value;

    if (!nota || nota === "") {
        alert("Debe ingresar una nota válida.");
        return;
    }

    console.log(`Nota guardada para ${materiaNombre}: ${nota}`);
    alert(`Nota guardada para ${materiaNombre}: ${nota}`);
}

function toggleNota(inputId) {
    let notaInput = document.getElementById(inputId);
    let checkBox = document.getElementById(`check_${inputId}`);

    if (checkBox.checked) {
        notaInput.disabled = false;
    } else {
        notaInput.value = "";
        notaInput.disabled = true;
    }
}

function saveStep() {
    let notas = [];
    document.querySelectorAll(".nota-input").forEach(input => {
        if (!input.disabled) {
            let materiaLabel = input.closest(".materia-row").querySelector(".materia-label").innerText;
            notas.push({ materia: materiaLabel, nota: input.value || "N/A" });
        }
    });

    console.log("Notas guardadas:", notas);
    alert("Notas guardadas correctamente.");
}


document.getElementById("carrera").addEventListener("change", updateSemestres);
document.getElementById("semestre").addEventListener("change", updateMateriasPorSemestre);
// STEP 4 //


// Asegurar que solo se acepten archivos PDF
document.querySelectorAll("input[type='file']").forEach(input => {
    input.addEventListener("change", function () {
        if (this.files.length > 0 && this.files[0].type !== "application/pdf") {
            alert("Solo se permiten archivos en formato PDF.");
            this.value = ""; // Borra el archivo si no es PDF
        }
    });
});

function confirmarDatos() {
    // Obtener los datos del Step 1
    let tipoIdentificacion = document.getElementById("tipo_identificacion").value;
    let numeroIdentificacion = document.getElementById("numero_identificacion").value;
    let primerNombre = document.getElementById("primer_nombre").value;
    let segundoNombre = document.getElementById("segundo_nombre").value || "(No aplica)";
    let primerApellido = document.getElementById("primer_apellido").value;
    let segundoApellido = document.getElementById("segundo_apellido").value || "(No aplica)";
    let email = document.getElementById("email").value;
    let telefono = document.getElementById("telefono").value;

    // Validar que los campos obligatorios estén llenos
    if (!tipoIdentificacion || !numeroIdentificacion || !primerNombre || !primerApellido || !email || !telefono) {
        alert("Por favor, completa todos los campos obligatorios antes de enviar.");
        return;
    }

    // Crear mensaje de confirmación con los datos ingresados
    let mensaje = `
        <h3>¿Están correctos estos datos?</h3>
        <p><strong>Tipo de Identificación:</strong> ${tipoIdentificacion}</p>
        <p><strong>Número de Identificación:</strong> ${numeroIdentificacion}</p>
        <p><strong>Nombre Completo:</strong> ${primerNombre} ${segundoNombre} ${primerApellido} ${segundoApellido}</p>
        <p><strong>Correo Electrónico:</strong> ${email}</p>
        <p><strong>Teléfono:</strong> ${telefono}</p>
        <br>
        <button onclick="enviarFormulario()">Confirmar</button>
        <button onclick="cerrarModal()">Rechazar</button>
    `;

    // Crear ventana emergente (modal)
    let modal = document.createElement("div");
    modal.id = "modalConfirmacion";
    modal.style.position = "fixed";
    modal.style.top = "0";
    modal.style.left = "0";
    modal.style.width = "100%";
    modal.style.height = "100%";
    modal.style.backgroundColor = "rgba(0,0,0,0.5)";
    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";

    let modalContent = document.createElement("div");
    modalContent.style.background = "#fff";
    modalContent.style.padding = "20px";
    modalContent.style.borderRadius = "10px";
    modalContent.style.textAlign = "center";
    modalContent.innerHTML = mensaje;

    modal.appendChild(modalContent);
    document.body.appendChild(modal);
}

function cerrarModal() {
    document.getElementById("modalConfirmacion").remove();
}

function enviarFormulario() {
    let fecha = new Date();
    let año = fecha.getFullYear();
    let numeroRadicado = `HOM-${año}-0001`; // Aquí puedes hacer que el número consecutivo aumente dinámicamente

    // Crear el contenido del modal de confirmación
    let mensaje = `
        <h3>Solicitud Enviada con Éxito</h3>
        <p><strong>Número de Radicado:</strong> ${numeroRadicado}</p>
        <p>Se ha enviado la información a su correo.</p>
        <button onclick="redirigirAspirante()">Aceptar</button>
    `;

    // Crear el modal
    let modal = document.createElement("div");
    modal.id = "modalConfirmacionEnvio";
    modal.classList.add("modal");

    let modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");
    modalContent.innerHTML = mensaje;

    modal.appendChild(modalContent);
    document.body.appendChild(modal);

    // Mostrar el modal
    modal.style.display = "flex";
}

// Función para redirigir al usuario después de aceptar
function redirigirAspirante() {
    document.getElementById("modalConfirmacionEnvio").remove();
    window.location.href = "interfaz_aspirante.html"; // Cambia esto por la URL correcta
}
function enviarFormulario() {
    let fecha = new Date();
    let año = fecha.getFullYear();
    let numeroRadicado = `HOM-${año}-0001`; // Aquí puedes hacer que el número consecutivo aumente dinámicamente

    // Crear el modal
    let modal = document.createElement("div");
    modal.id = "modalConfirmacionEnvio";
    modal.classList.add("modal", "active"); // Añadimos la clase active para las animaciones

    // Crear el contenido del modal
    let modalContent = document.createElement("div");
    modalContent.classList.add("modal-content");

    modalContent.innerHTML = `
        <span class="close-icon">&times;</span>
        <h3>Solicitud Enviada con Éxito</h3>
        <p><strong>Número de Radicado:</strong> ${numeroRadicado}</p>
        <p>Se ha enviado la información a su correo.</p>
        <div class="btn-container">
            <button onclick="redirigirAspirante()">Aceptar</button>
        </div>
    `;

    modal.appendChild(modalContent);
    document.body.appendChild(modal);

    // Añadir evento al botón de cerrar
    const closeButton = modalContent.querySelector('.close-icon');
    if (closeButton) {
        closeButton.addEventListener('click', redirigirAspirante);
    }
}

function redirigirAspirante() {
    const modal = document.getElementById("modalConfirmacionEnvio");
    if (modal) {
        // Añadir la clase closing para la animación de salida
        modal.classList.add("closing");

        // Eliminar el modal después de la transición
        setTimeout(() => {
            modal.remove();
            window.location.href = "visualizacionhomo.html";
        }, 300);
    }
}

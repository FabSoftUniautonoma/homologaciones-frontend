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
const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|co|edu|org|net|gov|mil|unautonoma\.edu\.co)$/;
const regexTelefono = /^\d{7,10}$/; // Teléfonos de 7 a 10 dígitos
const regexNumeroIdentificacion = /^\d+$/; // Solo números

// Restringir entrada de caracteres no permitidos en Número de Identificación y Teléfono
document.getElementById("numero_identificacion").addEventListener("input", function () {
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
    const campos = ["primer_nombre", "segundo_nombre", "primer_apellido", "segundo_apellido"];

    campos.forEach(id => {
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
    const municipio = document.getElementById("municipio");
    const departamento = document.getElementById("departamento");



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
    validarCampo(municipio, "Ingrese su municipio");
    validarCampo(departamento, "Ingrese su departamento");

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
        mostrarMensaje("Paso 1 finalizado correctamente", "success");
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
            mensaje.textContent = "Usuario, recuerde que para poder hacer una homologación con el SENA tuvo que haber finalizado sus estudios. De lo contrario, no podrá seguir con el proceso de homologación.";
            document.getElementById("finalizo_estudios").parentElement.appendChild(mensaje);
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
    const tipoFormacion = document.getElementById("tipo_formacion");
    const carrera = document.getElementById("carrera");
    const finalizoEstudios = document.getElementById("finalizo_estudios");
    const fechaFinalizacion = document.getElementById("fecha_finalizacion");
    const fechaUltimoSemestre = document.getElementById("fecha_ultimo_semestre");

    // Expresión regular para validar fechas en formato YYYY-MM-DD
    const regexFecha = /^\d{4}-\d{2}-\d{2}$/;

    // Validación extra: Si es del SENA y no finalizó estudios, no permite avanzar
    if (institucion.value === "SENA" && finalizoEstudios.value === "no") {
        alert("No puede continuar con la homologación si no ha finalizado sus estudios en el SENA.");
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
        validarCampo(fechaFinalizacion, "Ingrese la fecha de finalización de sus estudios");
        if (!regexFecha.test(fechaFinalizacion.value)) {
            fechaFinalizacion.classList.add("error");
            fechaFinalizacion.parentElement.querySelector(".error-message").textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
        fechaUltimoSemestre.classList.remove("error");
        if (fechaUltimoSemestre.parentElement.querySelector(".error-message")) {
            fechaUltimoSemestre.parentElement.querySelector(".error-message").textContent = "";
        }
    } else if (finalizoEstudios.value === "no") {
        validarCampo(fechaUltimoSemestre, "Ingrese la fecha del último semestre cursado");
        if (!regexFecha.test(fechaUltimoSemestre.value)) {
            fechaUltimoSemestre.classList.add("error");
            fechaUltimoSemestre.parentElement.querySelector(".error-message").textContent = "Ingrese una fecha válida (YYYY-MM-DD)";
            valido = false;
        }
        fechaFinalizacion.classList.remove("error");
        if (fechaFinalizacion.parentElement.querySelector(".error-message")) {
            fechaFinalizacion.parentElement.querySelector(".error-message").textContent = "";
        }
    }

    // Si la validación es correcta, avanzar al siguiente paso
    if (valido) {
        mostrarMensaje("Paso 2 finalizado correctamente", "success");
        changeStep(1);
    }
}

// Asignar evento de cambio para validar en tiempo real
document.getElementById("institucion").addEventListener("change", validarSENA);
document.getElementById("finalizo_estudios").addEventListener("change", validarSENA);

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
        "Semestre 4": ["Programación en la Nube", "Pruebas de Software", "Metodologías Ágiles", "Desarrollo Seguro de Aplicaciones"],
        "Semestre 5": ["Inteligencia Artificial Básica", "Seguridad Informática", "Administración de Sistemas Operativos", "Análisis de Datos"],
        "Semestre 6": ["Big Data", "Gestión de Proyectos de Software", "Emprendimiento en TIC", "Trabajo de Grado"]
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

function mostrarModalConfirmacion(titulo, mensaje, textoBotonConfirmar = "Aceptar", onConfirm = null) {
    // Crear el modal
    const modal = document.createElement('div');
    modal.id = 'modalConfirmacion'; // Add ID for styling
    modal.classList.add('modal', 'modal-confirmacion');

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
        modal.classList.add('active');
    });

    // Referencias a botones
    const closeIcon = modal.querySelector('.close-icon');
    const btnCancelar = modal.querySelector('.btn-cancelar');
    const btnConfirmar = modal.querySelector('.btn-confirmar');

    // Función para cerrar el modal
    const cerrarModal = () => {
        modal.classList.remove('active');
        modal.classList.add('closing');

        setTimeout(() => {
            modal.remove();
        }, 300);
    };

    // Eventos de cierre
    closeIcon.addEventListener('click', cerrarModal);
    btnCancelar.addEventListener('click', cerrarModal);

    // Evento de confirmación
    btnConfirmar.addEventListener('click', () => {
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
    materias.forEach(input => {
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
            errores.push(`La materia ${materiaNombre} no tiene nota registrada.`);
            return;
        }

        // Convertir la nota, reemplazando coma por punto
        const notaNumero = parseFloat(nota.replace(',', '.'));

        // Validar el formato de la nota
        if (isNaN(notaNumero) || notaNumero < 0 || notaNumero > 5) {
            errores.push(`La nota de ${materiaNombre} no es válida: ${nota}`);
            return;
        }

        // Guardar nota en localStorage
        try {
            let notas = JSON.parse(localStorage.getItem('notas')) || {};
            notas[input.id] = notaNumero.toFixed(1);
            localStorage.setItem('notas', JSON.stringify(notas));

            // Agregar a materias guardadas
            materiasGuardadas.push({
                materia: materiaNombre,
                nota: notaNumero.toFixed(1)
            });
        } catch (error) {
            errores.push(`Error al guardar la nota de ${materiaNombre}: ${error.message}`);
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
        localStorage.setItem('materiasGuardadas', JSON.stringify(materiasGuardadas));
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
        `Materias guardadas correctamente:<br>${materiasGuardadas.map(m => `${m.materia}: ${m.nota}`).join("<br>")}`,
        "Aceptar",
        () => {
            // Verificar que changeStep y mostrarMensaje estén definidas
            if (typeof mostrarMensaje === 'function') {
                mostrarMensaje("Paso 3 finalizado correctamente", "success");
            }
            if (typeof changeStep === 'function') {
                changeStep(1);
            }
        }
    );

    return true;
}
// Agregar estilos para el modal
const estilosModal = document.createElement('style');
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
document.getElementById("semestre").addEventListener("change", updateMateriasPorSemestre);
// STEP 4 //
// Función para verificar si el usuario es extranjero cuando llega al Step 4
document.addEventListener("DOMContentLoaded", function () {
    const tipoIdentificacion = document.getElementById("tipo_identificacion");
    const extraDocsSection = document.getElementById("extra-docs");
    const visaPasaporte = document.getElementById("visa_pasaporte");

    const finalizoEstudios = document.getElementById("finalizo_estudios");
    const fechaFinalizacion = document.getElementById("fecha_finalizacion");
    const certificacionFinalizacion = document.getElementById("certificacion_finalizacion");
    const certificacionFinalizacionContainer = certificacionFinalizacion.parentElement;

    // Función para verificar si se deben mostrar los documentos adicionales (Extranjeros)
    function verificarDocumentosExtranjero() {
        if (tipoIdentificacion.value === "TE") {
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
    tipoIdentificacion.addEventListener("change", verificarDocumentosExtranjero);

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
                mostrarMensaje("Ingrese una fecha válida de finalización (YYYY-MM-DD)", "error");
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
        const contenidoProgramatico = document.getElementById("contenido_programatico");
        const cartaHomologacion = document.getElementById("carta_homologacion");

        // Determinar si el usuario es extranjero
        const esExtranjero = tipoIdentificacion.value === "TE";

        // Validar documentos obligatorios
        valido &= validarArchivo(documentoId, "Debe subir su Documento de Identidad.");
        valido &= validarArchivo(certificadoNotas, "Debe subir su Certificado de Notas.");
        valido &= validarArchivo(contenidoProgramatico, "Debe subir el Contenido Programático.");
        valido &= validarArchivo(cartaHomologacion, "Debe subir la Carta de Solicitud de Homologación.");

        // Validar Certificación de Finalización de Estudios si el usuario finalizó estudios
        if (finalizoEstudios.value === "si") {
            valido &= validarArchivo(certificacionFinalizacion, "Debe subir la Certificación de Finalización de Estudios.");
        }

        // Validar documentos adicionales solo si es extranjero
        if (esExtranjero) {
            extraDocsSection.classList.remove("hidden");
            visaPasaporte.setAttribute("required", "true");
            valido &= validarArchivo(visaPasaporte, "Debe subir una copia de su Visa o Pasaporte.");
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
document.querySelectorAll("input[type='file']").forEach(input => {
    input.addEventListener("change", function () {
        if (this.files.length > 0 && this.files[0].type !== "application/pdf") {
            alert("Solo se permiten archivos en formato PDF.");
            this.value = ""; // Borra el archivo si no es PDF
        }
    });
});
// STEP 5
function confirmarDatos() {
    let tipoIdentificacion = document.getElementById("tipo_identificacion").value;
    let numeroIdentificacion = document.getElementById("numero_identificacion").value;
    let primerNombre = document.getElementById("primer_nombre").value;
    let segundoNombre = document.getElementById("segundo_nombre").value || "(No aplica)";
    let primerApellido = document.getElementById("primer_apellido").value;
    let segundoApellido = document.getElementById("segundo_apellido").value || "(No aplica)";
    let email = document.getElementById("email").value;
    let telefono = document.getElementById("telefono").value;

    if (!tipoIdentificacion || !numeroIdentificacion || !primerNombre || !primerApellido || !email || !telefono) {
        alert("Por favor, completa todos los campos obligatorios antes de enviar.");
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

    // Agregar evento al botón "Confirmar" después de que el modal se haya insertado en el DOM
    setTimeout(() => {
        document.getElementById("confirmarBtn").addEventListener("click", enviarFormulario);
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
    // Llamar a la función al cargar la página o antes de mostrar el modal final
    agregarEstilosModalFinal();
}

function cerrarModal() {
    let modal = document.getElementById("modalConfirmacion");
    if (modal) modal.remove();
}

function enviarFormulario() {
    let fecha = new Date();
    let año = fecha.getFullYear();
    let numeroRadicado = `HOM-${año}-0001`;

    let mensaje = `
        <span class="close-icon">&times;</span>
        <h3>Solicitud Enviada con Éxito</h3>
        <p><strong>Número de Radicado:</strong> ${numeroRadicado}</p>
        <p>Se ha enviado la información a su correo.</p>
        <div class="btn-container">
            <button id="aceptarBtn">Aceptar</button>
        </div>
    `;

    cerrarModal(); // Cierra el modal de confirmación si está abierto
    mostrarModal(mensaje, "modalConfirmacionEnvio");
    agregarEstilosModalFinal();

    setTimeout(() => {
        document.getElementById("aceptarBtn").addEventListener("click", redirigirAspirante);
        document.querySelector(".close-icon").addEventListener("click", redirigirAspirante);
    }, 100);
}

function redirigirAspirante() {
    let modal = document.getElementById("modalConfirmacionEnvio");
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



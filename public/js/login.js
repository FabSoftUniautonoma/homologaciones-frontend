function handleLogin() {
    console.log("handleLogin() ejecutado");

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const errorMessage = document.getElementById('error-message');
    const loginBtn = document.getElementById('login-btn');

    if (!emailInput || !passwordInput || !errorMessage || !loginBtn) {
        console.error("Uno o más elementos no fueron encontrados en el DOM.");
        return;
    }

    const email = emailInput.value.trim().toLowerCase();
    const password = passwordInput.value.trim();

    // Reset del mensaje de error
    errorMessage.style.display = 'none';

    console.log(`Intentando login con: ${email} / ${password}`);

    // Validación de campos vacíos
    if (!email || !password) {
        errorMessage.textContent = 'Por favor complete todos los campos.';
        errorMessage.style.display = 'block';
        return;
    }

    // Simulación de autenticación con diferentes roles
    if (email === 'aspirante@gmail.com' && password === 'Aspirante123') {
        console.log('Login exitoso como Aspirante');
        simulateLogin('/homologaciones/aspirante');
    } else if (email === 'coordinador@gmail.com' && password === 'Coordinador123') {
        console.log('Login exitoso como Coordinador');
        simulateLogin('/coordinador/inicio');
    } else if (email === 'admin@gmail.com' && password === 'Admin123') {
        console.log('Login exitoso como Administrador');
        simulateLogin('/administrador');
    } else {
        console.log('Error: Usuario o contraseña incorrectos');
        errorMessage.textContent = 'Correo o contraseña incorrectos. Intente nuevamente.';
        errorMessage.style.display = 'block';

        loginBtn.classList.add('error');
        setTimeout(() => {
            loginBtn.classList.remove('error');
        }, 1000);
    }
}

// Función para simular el login y redirigir
function simulateLogin(redirectUrl) {
    console.log("Simulando inicio de sesión...");

    const loginBtn = document.getElementById('login-btn');
    if (!loginBtn) {
        console.error("Botón de login no encontrado.");
        return;
    }

    loginBtn.textContent = 'INGRESANDO...';
    loginBtn.disabled = true;

    setTimeout(() => {
        window.location.href = redirectUrl;
    }, 1500);
}

// Función para redirigir a la página de registro
function redirectToRegister() {
    console.log("Redirigiendo a formulario de homologación...");
    window.location.href = '/homologaciones/registroestudiante';
}

document.addEventListener('DOMContentLoaded', function () {
    console.log("Script cargado correctamente");

    // Toggle para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('active'); // Cambia el icono si es necesario
        });
    } else {
        console.error("No se encontró el botón de toggle o el input de contraseña.");
    }

    // Efecto de enfoque en los inputs
    const inputs = document.querySelectorAll('input');
    if (inputs.length > 0) {
        inputs.forEach(input => {
            input.addEventListener('focus', function () {
                if (this.parentElement) this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function () {
                if (this.parentElement) this.parentElement.classList.remove('focused');
            });
        });
    } else {
        console.warn("No se encontraron inputs en el DOM.");
    }

    // Evento para el botón de login
    const loginBtn = document.getElementById('login-btn');
    if (loginBtn) {
        loginBtn.addEventListener('click', handleLogin);
    } else {
        console.error("No se encontró el botón de login.");
    }

    // Evento para el botón de registro
    const registerBtn = document.getElementById('register-btn');
    if (registerBtn) {
        registerBtn.addEventListener('click', redirectToRegister);
    } else {
        console.warn("No se encontró el botón de registro.");
    }
});

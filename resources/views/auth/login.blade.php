<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Homologaciones - UniAutónoma del Cauca</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>
    <!-- Burbujas animadas de fondo -->
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="/api/placeholder/180/60" alt="Universidad Autónoma del Cauca">
            </div>
        </div>

        <div class="form-container">
            <h2 class="form-title">Homologaciones Uniautónoma</h2>

            <!-- Mensajes de alerta -->
            <div id="error-message" class="alert error-message"></div>
            <div id="success-message" class="alert success"></div>

            <!-- Mensaje de error de sesión -->
            @if (session('error'))
                <div class="alert error-message">{{ session('error') }}</div>
            @endif

            <!-- Formulario de login con autocomplete off -->
            <form id="login-form" autocomplete="off">
                @csrf

                <div class="input-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese su correo"
                        value="{{ old('email') }}" required autocomplete="off">
                    <span class="input-icon"></span>

                    @error('email')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required
                        autocomplete="off">
                    <span class="toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></span>

                    @error('password')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordar mis datos</label>
                </div>

                <button type="submit" class="login-btn">INGRESAR</button>
            </form>

            <div class="help-links">
                <a href="{{ route('register') }}">Registrate Aquí</a>
                <a href="{{ route('homologaciones.home') }}">Contactar soporte</a>
            </div>
        </div>

        <div class="footer">
            Universidad Autónoma del Cauca © 2025 - Sistema de Homologaciones
        </div>
    </div>

    <!-- Importar servicio de autenticación -->
    <script src="{{ asset('js/auth-service.js') }}"></script>

    <script>
        // Inicializar servicio de autenticación
        const authService = new AuthService('http://127.0.0.1:8000/api');

        // Referencias a elementos del DOM
        const loginForm = document.getElementById('login-form');
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        // Función para mostrar/ocultar contraseña
        function togglePasswordVisibility() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePassword.classList.add('visible');
            } else {
                passwordInput.type = 'password';
                togglePassword.classList.remove('visible');
            }
        }

        loginForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Limpiar mensajes
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            // Guardar datos si el checkbox está activado
            if (remember) {
                localStorage.setItem('rememberEmail', email);
                localStorage.setItem('rememberPassword', password);
                localStorage.setItem('rememberChecked', true);
            } else {
                localStorage.removeItem('rememberEmail');
                localStorage.removeItem('rememberPassword');
                localStorage.removeItem('rememberChecked');
            }

            try {
                const loginResponse = await authService.login(email, password, remember);

                successMessage.textContent = '¡Inicio de sesión exitoso! Redirigiendo...';
                successMessage.style.display = 'block';

                // En lugar de depender de la redirección automática del servicio,
                // hacemos la redirección aquí de forma explícita
                setTimeout(() => {
                    // Ruta completa y explícita
                    window.location.href =
                        'http://localhost/homologaciones-frontend/public/homologaciones/solicitudhomologacion';
                }, 1500);

            } catch (error) {
                errorMessage.textContent = error.message || 'Error al iniciar sesión. Inténtalo de nuevo.';
                errorMessage.style.display = 'block';
            }
        });
    </script>
</body>

</html>

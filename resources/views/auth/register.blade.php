<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema</title>
        <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <!-- Botón de inicio (fuera del container) -->
    <a href="{{ route('homologaciones.home') }}" class="home-btn">
        <i class="fas fa-home"></i> Inicio
    </a>

    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('img/finanzas.png') }}"  alt="Universidad Autónoma del Cauca">
            </div>
        </div>

        <div class="form-container">
            <h2 class="form-title">Registro de Usuario</h2>

            <div id="error-message" class="alert"></div>
            <div id="success-message" class="alert success"></div>

            <form id="register-form">
                <div class="input-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese su correo" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <div class="input-group">
                    <label for="tipo_identificacion">Tipo de Identificación:</label>
                    <select id="tipo_identificacion" name="tipo_identificacion" required>
                        <option value="" selected disabled>Seleccione un tipo de documento</option>
                        <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                        <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                    </select>
                    <i class="fas fa-id-card input-icon"></i>
                </div>

                <div class="input-group">
                    <label for="numero_identificacion">Número de Identificación:</label>
                    <input
                        type="text"
                        id="numero_identificacion"
                        name="numero_identificacion"
                        placeholder="Ingrese su número de documento"
                        pattern="[0-9]{5,}"
                        title="Solo números, mínimo 5 dígitos"
                        inputmode="numeric"
                        required>
                    <i class="fas fa-hashtag input-icon"></i>
                </div>

                <button type="submit">
                    <i class="fas fa-user-plus"></i> Registrarse
                </button>
            </form>

            <div class="login-link">
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Inicia sesión aquí</a></p>
            </div>
        </div>

        <div class="footer">
            © 2025 - Todos los derechos reservados
        </div>
    </div>

    <!-- Importar servicio de autenticación -->
    <script src="{{ asset('js/authService.js') }}"></script>

    <script>
        // Inicializar servicio de autenticación
        const authService = new AuthService('http://127.0.0.1:8000/api');

        // Referencias a elementos del DOM
        const registerForm = document.getElementById('register-form');
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');

        // Manejar envío del formulario
        registerForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Limpiar mensajes
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            // Validar número de identificación (mínimo 5 dígitos)
            const numeroDocumento = document.getElementById('numero_identificacion');
            if (numeroDocumento.value.length < 5 || !/^\d+$/.test(numeroDocumento.value)) {
                errorMessage.textContent = 'El número de documento debe tener al menos 5 dígitos y solo puede contener números.';
                errorMessage.style.display = 'block';
                return;
            }

            // Validar que se haya seleccionado un tipo de documento
            const tipoDocumento = document.getElementById('tipo_identificacion');
            if (tipoDocumento.value === "" || tipoDocumento.value === null) {
                errorMessage.textContent = 'Debe seleccionar un tipo de documento.';
                errorMessage.style.display = 'block';
                return;
            }

            // Validar el formato del correo
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                errorMessage.textContent = 'El formato del correo electrónico no es válido.';
                errorMessage.style.display = 'block';
                return;
            }

            // Recoger datos del formulario
            const userData = {
                email: email.value,
                tipo_identificacion: tipoDocumento.value,
                numero_identificacion: numeroDocumento.value
            };

            try {
                // Intentar registrar usuario
                const registerResponse = await authService.register(userData);

                // Usar SweetAlert para mostrar el mensaje de éxito
                Swal.fire({
                    title: 'Registro Exitoso',
                    text: registerResponse.message || 'Usuario registrado correctamente. Revisa tu correo para obtener tus credenciales.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0075bf'
                }).then((result) => {
                    // Limpiar formulario
                    registerForm.reset();

                    // Opcional: redirigir a la página de login después de un retraso
                    setTimeout(() => {
                        window.location.href = '{{ route('login') }}';
                    }, 3000);
                });

            } catch (error) {
                // Mostrar mensaje de error
                errorMessage.textContent = error.message || 'Error en el registro. Inténtalo de nuevo.';
                errorMessage.style.display = 'block';
            }
        });

        // Ocultar mensaje de error cuando se modifica algún campo
        const inputs = registerForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                errorMessage.style.display = 'none';
            });
        });
    </script>
</body>
</html>

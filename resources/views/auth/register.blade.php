<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Variables de color */
        :root {
            --azul-oscuro: #19407b;
            --azul-medio: #0075bf;
            --azul-claro: #08dcff;
            --blanco: #ffffff;
            --gris-claro: #f4f4f4;
            --borde: #dddddd;
            --sombra: rgba(0, 0, 0, 0.1);
            --rojo-error: #ff4d4d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--azul-medio), var(--azul-oscuro));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 20px 0;
        }

        /* Burbujas animadas de fondo */
        .bubbles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            width: 40px;
            height: 40px;
            background: var(--azul-claro);
            border-radius: 50%;
            opacity: 0.1;
            animation: rise 15s infinite ease-in;
        }

        .bubble:nth-child(1) {
            width: 40px;
            height: 40px;
            left: 10%;
            animation-duration: 8s;
        }

        .bubble:nth-child(2) {
            width: 20px;
            height: 20px;
            left: 20%;
            animation-duration: 5s;
            animation-delay: 1s;
        }

        .bubble:nth-child(3) {
            width: 50px;
            height: 50px;
            left: 35%;
            animation-duration: 10s;
            animation-delay: 2s;
        }

        .bubble:nth-child(4) {
            width: 80px;
            height: 80px;
            left: 50%;
            animation-duration: 7s;
            animation-delay: 0s;
        }

        .bubble:nth-child(5) {
            width: 35px;
            height: 35px;
            left: 65%;
            animation-duration: 6s;
            animation-delay: 1s;
        }

        .bubble:nth-child(6) {
            width: 45px;
            height: 45px;
            left: 80%;
            animation-duration: 8s;
            animation-delay: 3s;
        }

        .bubble:nth-child(7) {
            width: 25px;
            height: 25px;
            left: 90%;
            animation-duration: 7s;
            animation-delay: 2s;
        }

        @keyframes rise {
            0% {
                bottom: -100px;
                transform: translateX(0);
            }

            50% {
                transform: translateX(100px);
            }

            100% {
                bottom: 1080px;
                transform: translateX(-200px);
            }
        }

        /* Contenedor principal */
        .container {
            background-color: var(--blanco);
            width: 100%;
            max-width: 420px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 0;
            position: relative;
            z-index: 1;
            overflow: hidden;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Encabezado del formulario */
        .header {
            background-color: var(--azul-oscuro);
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shine 5s infinite linear;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--blanco);
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 5px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: auto;
            object-fit: contain;
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Botón de inicio */
        .home-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 10;
            padding: 8px 15px;
            border-radius: 20px;
            background-color: var(--azul-medio);
            color: var(--blanco);
            border: none;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .home-btn:hover {
            background-color: var(--azul-oscuro);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Formulario */
        .form-container {
            padding: 30px;
        }

        .form-title {
            color: var(--azul-oscuro);
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
            position: relative;
            font-weight: 600;
        }

        .form-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 70px;
            height: 3px;
            background: linear-gradient(to right, var(--azul-medio), var(--azul-claro));
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--borde);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: var(--blanco);
        }

        .input-group input:focus, .input-group select:focus {
            border-color: var(--azul-medio);
            box-shadow: 0 0 0 3px rgba(0, 117, 191, 0.2);
            outline: none;
        }

        .input-group input:focus+.input-icon, .input-group select:focus+.input-icon {
            color: var(--azul-medio);
            transform: translateY(-50%) scale(1.1);
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            transition: all 0.3s ease;
        }

        /* Botón de registro */
        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, var(--azul-medio), var(--azul-claro));
            color: var(--blanco);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 117, 191, 0.4);
            position: relative;
            overflow: hidden;
        }

        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
        }

        button[type="submit"]:hover::before {
            left: 100%;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 117, 191, 0.5);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* Enlaces de ayuda */
        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: var(--azul-medio);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: var(--azul-oscuro);
            text-decoration: underline;
        }

        /* Mensaje de error */
        .alert {
            background-color: rgba(255, 77, 77, 0.1);
            border-left: 3px solid var(--rojo-error);
            color: var(--rojo-error);
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            display: none;
            animation: shake 0.5s ease;
        }

        .alert.success {
            background-color: rgba(76, 175, 80, 0.1);
            border-left: 3px solid #4CAF50;
            color: #4CAF50;
        }

        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            20%, 60% {
                transform: translateX(-5px);
            }
            40%, 80% {
                transform: translateX(5px);
            }
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f9f9f9;
            border-top: 1px solid var(--borde);
            font-size: 12px;
            color: #777;
        }

        /* Custom SweetAlert */
        .swal2-popup {
            border-radius: 15px;
        }

        .swal2-icon {
            border-width: 3px;
        }

        .swal2-title {
            color: var(--azul-oscuro);
        }

        .swal2-html-container {
            font-size: 1em;
        }

        .swal2-confirm {
            background: linear-gradient(to right, var(--azul-medio), var(--azul-claro)) !important;
            border-radius: 8px !important;
            box-shadow: 0 5px 15px rgba(0, 117, 191, 0.4) !important;
        }

        /* Responsivo */
        @media (max-width: 480px) {
            .container {
                border-radius: 0;
                min-height: 100vh;
                max-width: 100%;
                display: flex;
                flex-direction: column;
            }

            .header {
                padding: 15px;
            }

            .logo {
                width: 100px;
                height: 100px;
            }

            .form-container {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>
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
                <img src="{{ asset('imagenes/logo.png') }}" alt="Logo">
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
    <script src="{{ asset('js/auth-service.js') }}"></script>

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

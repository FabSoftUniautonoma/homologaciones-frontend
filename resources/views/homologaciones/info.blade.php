<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homologaciones UNIAUTÓNOMA DEL CAUCA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

        .btn-primary {
            background-color: var(--azul-medio);
            border-color: var(--azul-medio);
        }

        .btn-primary:hover {
            background-color: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
        }

        .btn-primary:active, .btn-primary:focus {
            background-color: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
            box-shadow: 0 0 0 0.25rem rgba(0, 117, 191, 0.5);
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
            width: 200%;
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
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .home-btn:hover {
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
            top: 20%;
            transform: translateY(-50%);
            color: #888;
            transition: all 0.3s ease;
        }

        /* reCAPTCHA container */
        .recaptcha-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        /* Botón de registro */
        .registro-btn {
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

        .registro-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
        }

        .registro-btn:hover::before {
            left: 100%;
        }

        .registro-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 117, 191, 0.5);
        }

        .registro-btn:active {
            transform: translateY(0);
        }

        /* Enlaces de ayuda */
        .help-links {
            text-align: center;
            margin-top: 20px;
        }

        .help-links a {
            color: var(--azul-medio);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .help-links a:hover {
            color: var(--azul-oscuro);
            text-decoration: underline;
        }

        .help-links a:first-child {
            margin-right: 15px;
        }

        /* Mensaje de error */
        .error-message {
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

        @keyframes shake {
            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
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
    <a href="index.html" class="btn btn-primary home-btn">
        <i class="fas fa-home"></i> Inicio
    </a>

    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="quimerito.png" alt="Logo" />
            </div>
        </div>
        
        <div class="form-container">
            <h2 class="form-

        <div class="form-container">
            <h2 class="form-title">Homologaciones Uniautonoma</h2>
            
            <!-- Mensaje de error (oculto por defecto) -->
            <div id="errorMessage" class="error-message">
                <i class="fas fa-exclamation-circle"></i> Verifica los datos ingresados e intenta nuevamente.
            </div>
            
            <form id="registroForm">
                <div class="input-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" placeholder="Ingrese su correo" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                
                <div class="input-group">
                    <label for="confirmEmail">Confirmación de correo:</label>
                    <input type="email" id="confirmEmail" placeholder="Confirme su correo" required>
                    <i class="fas fa-envelope-open input-icon"></i>
                </div>
                
                <div class="input-group">
                    <label for="tipoDocumento">Tipo de documento:</label>
                    <select id="tipoDocumento" required>
                        <option value="" selected disabled>Seleccione un tipo de documento</option>
                        <option value="TI">Tarjeta de Identidad (TI)</option>
                        <option value="CC">Cédula de Ciudadanía (CC)</option>
                        <option value="CE">Cédula de Extranjería</option>
                    </select>
                    <i class="fas fa-id-card input-icon"></i>
                </div>
                
                <div class="input-group">
                    <label for="numeroDocumento">Número de documento:</label>
                    <input 
                        type="text" 
                        id="numeroDocumento" 
                        name="numeroDocumento"
                        placeholder="Ingrese su número de documento" 
                        pattern="[0-9]{5,}" 
                        title="Solo números, mínimo 5 dígitos"
                        inputmode="numeric"
                        required>
                    <i class="fas fa-hashtag input-icon"></i>
                </div>
                <!-- reCAPTCHA -->
                <div class="recaptcha-container mb-3">
                    <div class="g-recaptcha" data-sitekey="6Lcqj-cqAAAAAEwiItxDZJqnze8re8ej45zYdM_z"></div>
                </div>
                
                <button type="submit" class="registro-btn">
                    <i class="fas fa-user-plus"></i> Guardar solicitud
                </button>
            </form>
            <div class="help-links">
                <a href="#" id="ayudaLink"><i class="fas fa-question-circle"></i>Información</a>
            
        </div>
        <div class="help-links">
            <a href="#" id="ayudaLink">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </a>
        </div>
        
        <script>
            document.getElementById("ayudaLink").addEventListener("click", function(event) {
                event.preventDefault(); // Evita que el enlace se comporte como uno normal
                window.location.href = 'login.html';
              });
        </script>

        
        
        <div class="footer">
            © 2025 UNIAUTÓNOMA DEL CAUCA - Todos los derechos reservados
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registroForm');
            const email = document.getElementById('email');
            const confirmEmail = document.getElementById('confirmEmail');
            const errorMessage = document.getElementById('errorMessage');
            const ayudaLink = document.getElementById('ayudaLink');
            const contactoLink = document.getElementById('contactoLink');
            
            // Validar el formulario antes de enviar
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar que los correos coincidan
                if (email.value !== confirmEmail.value) {
                    errorMessage.style.display = 'block';
                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Los correos electrónicos no coinciden.';
                    return;
                }
                
                // Validar el formato del correo
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email.value)) {
                    errorMessage.style.display = 'block';
                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> El formato del correo electrónico no es válido.';
                    return;
                }
                
                // Validar que se haya seleccionado un tipo de documento
                const tipoDocumento = document.getElementById('tipoDocumento');
                if (tipoDocumento.value === "") {
                    errorMessage.style.display = 'block';
                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Debe seleccionar un tipo de documento.';
                    return;
                }
                
                // Validar que el número de documento tenga al menos 5 caracteres
                const numeroDocumento = document.getElementById('numeroDocumento');
                if (numeroDocumento.value.length < 5) {
                    errorMessage.style.display = 'block';
                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> El número de documento debe tener al menos 5 caracteres.';
                    return;
                }
                
                // Validar reCAPTCHA
                const recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length === 0) {
                    errorMessage.style.display = 'block';
                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Por favor, complete el reCAPTCHA.';
                    return;
                }
                
                // Si todo está correcto, mostrar mensaje de éxito
                Swal.fire({
                    title: 'Registro Exitoso',
                    text: 'Su solicitud ha sido registrada correctamente. Recibirá información en su correo electrónico.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0075bf'
                }).then((result) => {
                    // Resetear el formulario
                    form.reset();
                    grecaptcha.reset();
                    errorMessage.style.display = 'none';
                });
            });
            
            // Ocultar mensaje de error cuando se modifica algún campo
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    errorMessage.style.display = 'none';
                });
            });
            
            // Enlaces de ayuda
            ayudaLink.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Ayuda',
                    html: `
                        <div style="text-align: left;">
                            <p><strong>¿Cómo funciona el proceso de homologación?</strong></p>
                            <p>1. Complete este formulario con sus datos.</p>
                            <p>2. Recibirá un correo con instrucciones para subir sus documentos.</p>
                            <p>3. La universidad evaluará su solicitud y le notificará el resultado.</p>
                      
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#0075bf'
                });
            });
            
         
                
            
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
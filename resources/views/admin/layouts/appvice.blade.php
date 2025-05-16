<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ env('APP_NAME') }}</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('img/icon.svg') }}" />
    <!-- Core JS Files -->
    <script src="{{ asset('atlantis/assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('atlantis/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('atlantis/assets/js/core/bootstrap.min.js') }}"></script>
    <!-- jQuery UI -->
    <script src="{{ asset('atlantis/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('atlantis/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
    <!-- jQuery Scrollbar -->
    <script src="{{ asset('atlantis/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <!-- Atlantis JS -->
    <script src="{{ asset('atlantis/assets/js/atlantis.min.js') }}"></script>
    <!-- Chart Circle -->
    <script src="{{ asset('atlantis/assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <!-- Chart JS-->
    <script src="{{ asset('atlantis/assets/js/plugin/chart.js/chart.min.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Sparkline -->
    <script src="{{ asset('atlantis/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- AOS Animations -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!--JS personalizado-->
    <script src="{{ asset('js/admin.js') }}"></script>
    <!-- Fonts and icons -->
    <script src="{{ asset('atlantis/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <link href="{{ asset('css/appcoordinador.css') }}" rel="stylesheet">
    <!--Select 2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- CSS Files -->
    <link href="{{ asset('atlantis/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('atlantis/assets/css/atlantis.css') }}" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/appcoordinador.css') }}" rel="stylesheet">
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["{{ asset('atlantis/assets/css/fonts.css') }}"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });

        // Inicializar AOS para animaciones al cargar
        $(document).ready(function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Inicializar Select2 con animación
            $('.select2').select2({
                dropdownCssClass: "animated-dropdown"
            });

            // Efecto hover para elementos del menú
            $('.nav-item').hover(
                function() {
                    $(this).addClass('menu-hover');
                },
                function() {
                    $(this).removeClass('menu-hover');
                }
            );

            // Animación para las tarjetas al entrar en viewport
            const animateCards = () => {
                $('.card').each(function(index) {
                    const card = $(this);
                    setTimeout(function() {
                        card.addClass('card-animated');
                    }, index * 100);
                });
            };

            // Ejecutar animación inicial
            setTimeout(animateCards, 500);

            // Sistema de notificaciones demo
            setTimeout(function() {
                showNotification('¡Bienvenido al sistema!', 'Explora las nuevas funcionalidades.', 'info');
            }, 3000);

            // Efecto para los contenedores con el nuevo esquema de color
            $('.content-container').addClass('container-animated');

            // Toggle para notificaciones
            $('.notification-toggle').click(function() {
                $('.notification-dropdown').toggleClass('show-notification');
                $(this).find('.notification-count').fadeOut(500);
            });

            // Cerrar notificación
            $(document).on('click', '.close-notification', function() {
                $(this).closest('.notification-item').slideUp(300, function() {
                    $(this).remove();
                    updateNotificationCount();
                });
            });
        });

        // Función para mostrar notificaciones dinámicas
        function showNotification(title, message, type) {
            const notificationId = 'notif-' + Math.floor(Math.random() * 10000);
            const types = {
                'success': 'check-circle',
                'danger': 'times-circle',
                'warning': 'exclamation-triangle',
                'info': 'info-circle'
            };

            const icon = types[type] || 'bell';
            const notificationHtml = `
                <div class="notification-item notification-${type}" id="${notificationId}" data-aos="fade-left">
                    <div class="notification-icon">
                        <i class="fas fa-${icon}"></i>
                    </div>
                    <div class="notification-content">
                        <h4>${title}</h4>
                        <p>${message}</p>
                    </div>
                    <div class="notification-close close-notification">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            `;

            // Agregar a la lista de notificaciones
            $('.notification-list').prepend(notificationHtml);

            // Actualizar contador
            updateNotificationCount();

            // Mostrar notificación emergente
            $('.notification-popup-container').append(`
                <div class="notification-popup notification-${type}" id="popup-${notificationId}">
                    <div class="notification-icon">
                        <i class="fas fa-${icon}"></i>
                    </div>
                    <div class="notification-content">
                        <h4>${title}</h4>
                        <p>${message}</p>
                    </div>
                    <div class="notification-close">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            `);

            // Animar entrada y salida de la notificación emergente
            setTimeout(function() {
                $(`#popup-${notificationId}`).addClass('show-popup');

                setTimeout(function() {
                    $(`#popup-${notificationId}`).removeClass('show-popup');
                    setTimeout(function() {
                        $(`#popup-${notificationId}`).remove();
                    }, 300);
                }, 4000);
            }, 100);
        }

        // Actualizar contador de notificaciones
        function updateNotificationCount() {
            const count = $('.notification-list .notification-item').length;
            $('.notification-count').text(count);

            if (count > 0) {
                $('.notification-count').fadeIn(300);
            } else {
                $('.notification-count').fadeOut(300);
            }
        }
    </script>



</head>
<style>
    /*
 * Estilos modernos para el sistema de homologaciones
 * Fondo blanco con predominancia de azules oscuros y medios
 * El azul claro solo para detalles y acentos
 * Con animaciones y transiciones avanzadas
 */

    /* Importación de fuentes inclusivas y legibles */
    @import url('https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
        --azul-oscuro: #19407b;
        /* Color principal para elementos importantes */
        --azul-medio: #0075bf;
        /* Color secundario para elementos destacados */
        --azul-claro: #08dcff;
        /* Solo para detalles y acentos */
        --azul-muy-claro: #e1f5fe;
        /* Fondo para áreas de contenido secundario */
        --azul-contenedor: #f8fbff;
        /* Fondo sutil para contenedores */
        --blanco: #ffffff;
        /* Fondo principal */
        --negro: #000000;
        /* Para alto contraste cuando sea necesario */
        --texto-oscuro: #212121;
        /* Textos principales */
        --texto-medio: #424242;
        /* Subtítulos y contenido secundario */
        --texto-claro: #757575;
        /* Textos de menor importancia */
        --gris-claro: #f9f9f9;
        /* Bordes y separadores sutiles */
        --borde: #e0e0e0;
        /* Líneas divisorias y bordes */
        --sombra: rgba(0, 0, 0, 0.08);
        /* Sombras sutiles */
        --sombra-hover: rgba(25, 64, 123, 0.15);
        /* Sombras al hacer hover */
        --rojo-error: #ff4d4d;
        /* Alertas de error */
        --verde-success: #4CAF50;
        /* Alertas de éxito */
        --naranja-warning: #FF9800;
        /* Alertas de advertencia */
        --azul-info: #2196F3;
        /* Alertas informativas */
    }

    /* ===== ESTILOS GENERALES ===== */
    body {
        background-color: var(--blanco);
        color: var(--texto-oscuro);
        font-family: 'Atkinson Hyperlegible', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        overflow-x: hidden;
        font-size: 16px;
        line-height: 1.5;
        letter-spacing: 0.01em;
    }

    a {
        color: var(--azul-medio);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    a:hover {
        color: var(--azul-oscuro);
        text-decoration: none;
    }

    /* ===== CONTENEDORES Y LAYOUTS ===== */
    .content {
        padding: 25px;
        position: relative;
        animation: fadeInUp 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .content-container {
        background-color: var(--blanco);
        border-radius: 12px;
        box-shadow: 0 6px 15px var(--sombra);
        padding: 25px;
        margin-bottom: 25px;
        border-left: 4px solid var(--azul-oscuro);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        opacity: 0;
        transform: translateY(20px);
        position: relative;
        overflow: hidden;
    }

    .content-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--azul-oscuro), var(--azul-medio));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .content-container:hover::before {
        transform: scaleX(1);
    }

    .container-animated {
        opacity: 1;
        transform: translateY(0);
    }

    .content-container:hover {
        box-shadow: 0 8px 25px var(--sombra-hover);
        transform: translateY(-2px);
    }

    .content-title {
        color: var(--azul-oscuro);
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 12px;
        font-size: 1.5rem;
        letter-spacing: -0.01em;
    }

    .content-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, var(--azul-oscuro), var(--azul-medio));
        transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .content-container:hover .content-title::after {
        width: 100px;
    }

    /* ===== CABECERA Y NAVEGACIÓN ===== */
    .logo-header[data-background-color="dark2"],
    .navbar[data-background-color="dark2"] {
        background: linear-gradient(135deg, var(--azul-oscuro) 0%, #143261 100%) !important;
        transition: background 0.4s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar[data-background-color="dark2"] {
        background: linear-gradient(170deg, var(--azul-oscuro) 0%, #152b4d 100%) !important;
        transition: all 0.4s ease;
    }

    /* Efectos de partículas sutiles en la barra lateral */
    .sidebar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.03) 0%, transparent 6%),
            radial-gradient(circle at 70% 60%, rgba(255, 255, 255, 0.02) 0%, transparent 6%),
            radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 6%),
            radial-gradient(circle at 90% 10%, rgba(255, 255, 255, 0.02) 0%, transparent 6%);
        z-index: 0;
    }

    /* Animaciones para el menú lateral */
    .nav-item {
        position: relative;
        transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-left: 3px solid transparent;
        margin-bottom: 6px;
        overflow: hidden;
        z-index: 1;
    }

    .nav-item.active {
        border-left: 3px solid var(--azul-claro);
        background: rgba(0, 117, 191, 0.15);
    }

    .nav-item::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(8, 220, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        z-index: -1;
    }

    .nav-item:hover::after {
        transform: translateX(0);
    }

    .nav-item.menu-hover:not(.active) {
        background: rgba(25, 64, 123, 0.08);
        border-left: 3px solid var(--azul-medio);
        transform: translateX(5px);
    }

    .nav-item a {
        position: relative;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        color: var(--blanco) !important;
        padding: 12px 15px;
    }

    .nav-item:hover a {
        color: var(--azul-claro) !important;
    }

    /* Efecto de onda para ítems de menú */
    .nav-item a::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 0;
        height: 0;
        background: rgba(8, 220, 255, 0.15);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
        z-index: -1;
        opacity: 0;
    }

    .nav-item:active a::before {
        width: 300px;
        height: 300px;
        opacity: 1;
    }

    /* ===== TIPOGRAFÍA ===== */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        color: var(--azul-oscuro);
        font-weight: 600;
        margin-bottom: 0.8rem;
        letter-spacing: -0.01em;
        line-height: 1.3;
        font-family: 'Atkinson Hyperlegible', 'Inter', sans-serif;
    }

    p {
        color: var(--texto-medio);
        line-height: 1.6;
        margin-bottom: 1.2rem;
        font-size: 1rem;
    }

    .text-section {
        color: var(--blanco) !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        position: relative;
        display: inline-block;
    }

    .text-section::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 30px;
        height: 2px;
        background: var(--azul-claro);
        transition: width 0.3s ease;
    }

    .sidebar-content:hover .text-section::after {
        width: 50px;
    }

    /* ===== LOGO Y BRANDING ===== */
    .logo {
        position: relative;
        display: inline-block;
        overflow: hidden;
    }

    .logo img {
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .logo::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        opacity: 0;
        transform: scale(0.5);
        transition: transform 0.6s ease, opacity 0.6s ease;
    }

    .logo:hover::before {
        opacity: 1;
        transform: scale(1);
    }

    .logo:hover img {
        transform: scale(1.05);
    }

    /* ===== TARJETAS ===== */
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 6px 15px var(--sombra);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        opacity: 0;
        transform: translateY(20px);
        background-color: var(--blanco);
        overflow: hidden;
        position: relative;
    }

    .card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--azul-oscuro), var(--azul-medio));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .card:hover::after {
        transform: scaleX(1);
    }

    .card-header {
        background-color: var(--azul-oscuro);
        border-bottom: none;
        padding: 15px 20px;
        font-weight: 600;
        color: var(--blanco);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .card:hover .card-header::before {
        transform: translateX(100%);
    }

    .card-header i {
        margin-right: 10px;
        font-size: 1.1rem;
        color: var(--blanco);
    }

    .card-body {
        padding: 20px;
    }

    .card-animated {
        opacity: 1;
        transform: translateY(0);
        animation: cardEnter 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }

    @keyframes cardEnter {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card:hover {
        box-shadow: 0 12px 25px var(--sombra-hover);
        transform: translateY(-5px);
    }

    /* ===== BOTONES ===== */
    .btn {
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 8px;
        padding: 10px 18px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
        z-index: -1;
    }

    .btn:hover::before {
        transform: translateX(100%);
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.5);
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        z-index: -1;
    }

    .btn:active::after {
        width: 300px;
        height: 300px;
        opacity: 0.3;
        transform: translate(-50%, -50%) scale(1);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--azul-oscuro), #143261);
        border: none;
        color: var(--blanco);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--azul-medio), var(--azul-oscuro));
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--azul-medio), #006db3);
        border: none;
        color: var(--blanco);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #006db3, var(--azul-medio));
        transform: translateY(-2px);
    }

    .btn i {
        margin-right: 8px;
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: translateX(3px);
    }

    /* ===== SISTEMA DE NOTIFICACIONES ===== */
    .notification-toggle {
        position: relative;
        cursor: pointer;
        padding: 10px;
        transition: all 0.3s ease;
    }

    .notification-toggle i {
        font-size: 18px;
        color: var(--blanco);
        transition: transform 0.3s ease;
    }

    .notification-toggle:hover i {
        transform: scale(1.15);
    }

    .notification-count {
        position: absolute;
        top: 2px;
        right: 2px;
        background: var(--azul-claro);
        color: var(--azul-oscuro);
        font-size: 10px;
        font-weight: bold;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--azul-oscuro);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        animation: notificationPulse 2s infinite;
        transition: all 0.3s ease;
        z-index: 1;
    }

    @keyframes notificationPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(8, 220, 255, 0.5);
        }

        70% {
            box-shadow: 0 0 0 8px rgba(8, 220, 255, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(8, 220, 255, 0);
        }
    }

    .notification-dropdown {
        position: absolute;
        top: 100%;
        right: -50px;
        width: 340px;
        background-color: var(--blanco);
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transform-origin: top right;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .notification-dropdown.show-notification {
        opacity: 1;
        visibility: visible;
        transform: translateY(10px) scale(1);
    }

    .notification-header {
        background: linear-gradient(135deg, var(--azul-oscuro), var(--azul-medio));
        color: var(--blanco);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .notification-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--azul-claro), transparent);
    }

    .notification-title {
        font-weight: 600;
        font-size: 16px;
        margin: 0;
    }

    .notification-list {
        max-height: 350px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        padding: 15px 20px;
        border-bottom: 1px solid var(--borde);
        background-color: var(--blanco);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .notification-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: var(--azul-medio);
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform 0.3s ease;
    }

    .notification-item:hover {
        background-color: var(--azul-muy-claro);
    }

    .notification-item:hover::after {
        transform: scaleY(1);
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .notification-item:hover .notification-icon {
        transform: scale(1.1);
    }

    .notification-success .notification-icon {
        background: linear-gradient(135deg, #43a047, #2e7d32);
        color: white;
    }

    .notification-danger .notification-icon {
        background: linear-gradient(135deg, #e53935, #c62828);
        color: white;
    }

    .notification-warning .notification-icon {
        background: linear-gradient(135deg, #fb8c00, #ef6c00);
        color: white;
    }

    .notification-info .notification-icon {
        background: linear-gradient(135deg, #039be5, #0277bd);
        color: white;
    }

    .notification-content {
        flex-grow: 1;
    }

    .notification-content h4 {
        margin: 0 0 5px;
        font-size: 14px;
        font-weight: 600;
        color: var(--texto-oscuro);
        transition: color 0.3s ease;
    }

    .notification-item:hover .notification-content h4 {
        color: var(--azul-oscuro);
    }

    .notification-content p {
        margin: 0;
        font-size: 13px;
        color: var(--texto-claro);
        line-height: 1.4;
    }

    .notification-close {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--texto-claro);
        transition: all 0.3s ease;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.05);
        margin-left: 10px;
    }

    .notification-close:hover {
        color: var(--rojo-error);
        background-color: rgba(255, 77, 77, 0.1);
        transform: rotate(90deg);
    }

    /* Sistema de notificaciones emergentes */
    .notification-popup-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        width: 320px;
        pointer-events: none;
    }

    .notification-popup {
        display: flex;
        padding: 15px;
        background-color: var(--blanco);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        margin-bottom: 15px;
        transform: translateX(120%);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border-left: 4px solid var(--azul-oscuro);
        opacity: 0.98;
        pointer-events: auto;
        position: relative;
        overflow: hidden;
    }

    .notification-popup::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, var(--azul-oscuro), var(--azul-medio));
    }

    .notification-popup.show-popup {
        transform: translateX(0);
    }

    .notification-popup-success {
        border-left-color: var(--verde-success);
    }

    .notification-popup-success::before {
        background: linear-gradient(to bottom, #43a047, #2e7d32);
    }

    .notification-popup-danger {
        border-left-color: var(--rojo-error);
    }

    .notification-popup-danger::before {
        background: linear-gradient(to bottom, #e53935, #c62828);
    }

    .notification-popup-warning {
        border-left-color: var(--naranja-warning);
    }

    .notification-popup-warning::before {
        background: linear-gradient(to bottom, #fb8c00, #ef6c00);
    }

    .notification-popup-info {
        border-left-color: var(--azul-info);
    }

    .notification-popup-info::before {
        background: linear-gradient(to bottom, #039be5, #0277bd);
    }

    /* ===== ANIMACIONES Y EFECTOS ===== */
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(8, 220, 255, 0.5);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(8, 220, 255, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(8, 220, 255, 0);
        }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ===== ALERTAS Y MENSAJES ===== */
    .alert {
        animation: slideInRight 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-radius: 10px;
        border: none;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        padding: 15px 20px;
        position: relative;
        overflow: hidden;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .alert-success {
        background-color: rgba(76, 175, 80, 0.05);
        color: #2e7d32;
    }

    .alert-success::before {
        background: linear-gradient(to bottom, #43a047, #2e7d32);
    }

    .alert-danger {
        background-color: rgba(255, 77, 77, 0.05);
        color: #d32f2f;
    }

    .alert-danger::before {
        background: linear-gradient(to bottom, #e53935, #c62828);
    }

    .alert-info {
        background-color: rgba(33, 150, 243, 0.05);
        color: #0277bd;
    }

    .alert-info::before {
        background: linear-gradient(to bottom, #039be5, #0277bd);
    }

    .alert-warning {
        background-color: rgba(255, 152, 0, 0.05);
        color: #ef6c00;
    }

    .alert-warning::before {
        background: linear-gradient(to bottom, #fb8c00, #ef6c00);
    }

    /* ===== FORMULARIOS ===== */
    .form-control {
        border: 1px solid var(--borde);
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        color: var(--texto-oscuro);
        background-color: var(--blanco);
        padding: 10px 15px;
        height: auto;
        box-shadow: 0 2px
    }

    /* ===== FORMULARIOS ===== */
    .form-control {
        border: 1px solid var(--borde);
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        color: var(--texto-oscuro);
        background-color: var(--blanco);
        padding: 10px 15px;
        height: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: var(--azul-medio);
        box-shadow: 0 0 0 3px rgba(0, 117, 191, 0.15);
        outline: none;
    }

    .form-control::placeholder {
        color: var(--texto-claro);
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .form-control:focus::placeholder {
        opacity: 0.4;
    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--texto-medio);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-group:focus-within label {
        color: var(--azul-medio);
        transform: translateX(3px);
    }

    .form-group .icon-form {
        position: absolute;
        right: 12px;
        top: 38px;
        color: var(--texto-claro);
        transition: all 0.3s ease;
    }

    .form-group:focus-within .icon-form {
        color: var(--azul-medio);
        transform: scale(1.1);
    }

    /* Estilos para select */
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23757575' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 18px;
        padding-right: 40px;
    }

    select.form-control:focus {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%230075bf' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    }

    /* Checkbox personalizado */
    .custom-checkbox {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        font-size: 16px;
        user-select: none;
        display: inline-block;
        color: var(--texto-medio);
        transition: all 0.3s ease;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: var(--blanco);
        border: 2px solid var(--borde);
        border-radius: 4px;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .custom-checkbox:hover input~.checkmark {
        border-color: var(--azul-medio);
        box-shadow: 0 0 0 3px rgba(0, 117, 191, 0.1);
    }

    .custom-checkbox input:checked~.checkmark {
        background-color: var(--azul-medio);
        border-color: var(--azul-medio);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .custom-checkbox input:checked~.checkmark:after {
        display: block;
    }

    .custom-checkbox .checkmark:after {
        left: 6px;
        top: 2px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Radio buttons personalizados */
    .custom-radio {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        font-size: 16px;
        user-select: none;
        display: inline-block;
        color: var(--texto-medio);
        margin-right: 20px;
        transition: all 0.3s ease;
    }

    .custom-radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .radio-mark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: var(--blanco);
        border: 2px solid var(--borde);
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .custom-radio:hover input~.radio-mark {
        border-color: var(--azul-medio);
        box-shadow: 0 0 0 3px rgba(0, 117, 191, 0.1);
    }

    .custom-radio input:checked~.radio-mark {
        border-color: var(--azul-medio);
    }

    .radio-mark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .custom-radio input:checked~.radio-mark:after {
        display: block;
    }

    .custom-radio .radio-mark:after {
        top: 4px;
        left: 4px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--azul-medio);
        transform: scale(0);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .custom-radio input:checked~.radio-mark:after {
        transform: scale(1);
    }

    /* ===== TABLAS ===== */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: var(--texto-oscuro);
        background-color: var(--blanco);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 6px 15px var(--sombra);
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: var(--azul-oscuro);
        color: var(--blanco);
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 15px;
        position: relative;
    }

    .table thead th:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 25%;
        right: 0;
        height: 50%;
        width: 1px;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .table tbody tr {
        transition: all 0.3s ease;
        position: relative;
        border-bottom: 1px solid var(--borde);
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: var(--azul-muy-claro);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px var(--sombra);
        z-index: 1;
    }

    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-top: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: var(--azul-contenedor);
    }

    .table-hover tbody tr:hover {
        background-color: var(--azul-muy-claro);
    }

    /* Botones de acción en tablas */
    .table .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: var(--blanco);
        color: var(--texto-medio);
        border: 1px solid var(--borde);
        margin-right: 5px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .table .btn-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(0, 117, 191, 0.1) 0%, rgba(0, 117, 191, 0) 70%);
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.4s ease;
    }

    .table .btn-action:hover::before {
        opacity: 1;
        transform: scale(1.5);
    }

    .table .btn-action:hover {
        color: var(--azul-medio);
        border-color: var(--azul-medio);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 117, 191, 0.15);
    }

    .table .btn-action.btn-delete:hover {
        color: var(--rojo-error);
        border-color: var(--rojo-error);
        box-shadow: 0 4px 8px rgba(255, 77, 77, 0.15);
    }

    .table .btn-action.btn-delete:hover::before {
        background: radial-gradient(circle, rgba(255, 77, 77, 0.1) 0%, rgba(255, 77, 77, 0) 70%);
    }

    .table .btn-action.btn-view:hover {
        color: var(--verde-success);
        border-color: var(--verde-success);
        box-shadow: 0 4px 8px rgba(76, 175, 80, 0.15);
    }

    .table .btn-action.btn-view:hover::before {
        background: radial-gradient(circle, rgba(76, 175, 80, 0.1) 0%, rgba(76, 175, 80, 0) 70%);
    }

    /* ===== PAGINACIÓN ===== */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        border-radius: 8px;
        border: none;
        padding: 10px 15px;
        color: var(--texto-medio);
        background-color: var(--blanco);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        font-weight: 500;
        box-shadow: 0 4px 6px var(--sombra);
        position: relative;
        overflow: hidden;
    }

    .pagination .page-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(0, 117, 191, 0.1) 0%, transparent 70%);
        transform: scale(0);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .pagination .page-link:hover::before {
        transform: scale(1.5);
        opacity: 1;
    }

    .pagination .page-item:hover .page-link {
        color: var(--azul-medio);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 117, 191, 0.15);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--azul-oscuro);
        color: var(--blanco);
        z-index: 1;
    }

    .pagination .page-item.active .page-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(25, 64, 123, 0.2);
    }

    .pagination .page-item.disabled .page-link {
        color: var(--texto-claro);
        pointer-events: none;
        background-color: var(--gris-claro);
        box-shadow: none;
    }

    /* ===== MODALES ===== */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--azul-oscuro), #143261);
        border-bottom: none;
        padding: 20px 25px;
        color: var(--blanco);
        position: relative;
    }

    .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--azul-claro), transparent);
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.25rem;
    }

    .modal-header .close {
        color: var(--blanco);
        opacity: 0.8;
        text-shadow: none;
        transition: all 0.3s ease;
    }

    .modal-header .close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid var(--borde);
        padding: 15px 25px;
        background-color: var(--azul-contenedor);
    }

    /* Efectos de apertura del modal */
    .modal.fade .modal-dialog {
        transform: scale(0.95) translateY(-30px);
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .modal.show .modal-dialog {
        transform: scale(1) translateY(0);
    }

    /* ===== LOADERS Y SPINNERS ===== */
    .loader-container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .loader-spinner {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid rgba(0, 117, 191, 0.1);
        border-top-color: var(--azul-medio);
        animation: spin 1s infinite linear;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loader-dots {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loader-dots span {
        width: 10px;
        height: 10px;
        margin: 0 5px;
        background-color: var(--azul-medio);
        border-radius: 50%;
        animation: dotPulse 1.4s infinite ease-in-out;
    }

    .loader-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loader-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes dotPulse {

        0%,
        100% {
            transform: scale(0.3);
            opacity: 0.3;
        }

        50% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Loader de progreso lineal */
    .loader-progress {
        height: 4px;
        width: 100%;
        background-color: rgba(0, 117, 191, 0.1);
        border-radius: 2px;
        overflow: hidden;
        position: relative;
    }

    .loader-progress::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 30%;
        background-color: var(--azul-medio);
        border-radius: 2px;
        animation: progress 2s infinite cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes progress {
        0% {
            left: -30%;
        }

        50% {
            left: 100%;
        }

        100% {
            left: 100%;
        }
    }

    /* ===== TOOLTIPS Y POPOVERS ===== */
    .tooltip {
        font-size: 0.85rem;
        pointer-events: none;
    }

    .tooltip .tooltip-inner {
        background: linear-gradient(135deg, var(--azul-oscuro), #143261);
        border-radius: 6px;
        padding: 8px 12px;
        max-width: 250px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .tooltip .tooltip-inner::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: tooltipShine 2s infinite;
    }

    @keyframes tooltipShine {
        0% {
            transform: translateX(-100%);
        }

        40%,
        100% {
            transform: translateX(100%);
        }
    }

    .tooltip .arrow::before {
        border-top-color: var(--azul-oscuro);
    }

    .popover {
        border: none;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .popover-header {
        background-color: var(--azul-oscuro);
        color: var(--blanco);
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        padding: 12px 15px;
        font-weight: 500;
    }

    .popover-body {
        padding: 15px;
        color: var(--texto-medio);
    }

    /* ===== MEDIA QUERIES ===== */
    @media (max-width: 991px) {
        .content {
            padding: 15px;
        }

        .content-container {
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        .notification-dropdown {
            width: 300px;
            right: -70px;
        }
    }

    @media (max-width: 767px) {
        body {
            font-size: 15px;
        }

        .content-title {
            font-size: 1.3rem;
        }

        .btn {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .table thead th {
            padding: 12px 10px;
            font-size: 0.8rem;
        }

        .table tbody td {
            padding: 12px 10px;
        }

        .notification-dropdown {
            width: 280px;
            right: -100px;
        }

        .modal-dialog {
            margin: 10px;
        }

        .modal-header {
            padding: 15px 20px;
        }

        .modal-body {
            padding: 20px;
        }
    }

    @media (max-width: 575px) {
        .content {
            padding: 10px;
        }

        .content-container {
            padding: 15px;
        }

        .content-title {
            font-size: 1.2rem;
        }

        .navbar-brand {
            font-size: 1.2rem;
        }

        .pagination .page-link {
            padding: 8px 12px;
        }

        .notification-popup {
            width: 100%;
            padding: 12px;
        }
    }

    /* ===== UTILIDADES ===== */
    .text-primary {
        color: var(--azul-oscuro) !important;
    }

    .text-secondary {
        color: var(--azul-medio) !important;
    }

    .text-accent {
        color: var(--azul-claro) !important;
    }

    .bg-primary {
        background-color: var(--azul-oscuro) !important;
    }

    .bg-secondary {
        background-color: var(--azul-medio) !important;
    }

    .bg-accent {
        background-color: var(--azul-claro) !important;
    }

    .bg-light {
        background-color: var(--azul-contenedor) !important;
    }

    .shadow-soft {
        box-shadow: 0 4px 10px var(--sombra) !important;
    }

    .shadow-hover {
        transition: box-shadow 0.3s ease, transform 0.3s ease !important;
    }

    .shadow-hover:hover {
        box-shadow: 0 8px 20px var(--sombra-hover) !important;
        transform: translateY(-3px) !important;
    }

    .border-accent {
        border-color: var(--azul-claro) !important;
    }

    .border-primary {
        border-color: var(--azul-oscuro) !important;
    }

    .rounded-extra {
        border-radius: 12px !important;
    }

    .transition-all {
        transition: all 0.3s ease !important;
    }

    /* ===== TEMA OSCURO ===== */
    body.dark-mode {
        --blanco: #161b22;
        --texto-oscuro: #e6edf3;
        --texto-medio: #c9d1d9;
        --texto-claro: #8b949e;
        --borde: #30363d;
        --gris-claro: #21262d;
        --azul-contenedor: #1c2129;
        --azul-muy-claro: #1f2937;
        --sombra: rgba(0, 0, 0, 0.2);
        --sombra-hover: rgba(0, 0, 0, 0.3);

        background-color: #0d1117;
    }

    body.dark-mode .card,
    body.dark-mode .content-container,
    body.dark-mode .modal-content,
    body.dark-mode .notification-item,
    body.dark-mode .form-control,
    body.dark-mode .table {
        background-color: #161b22;
    }

    body.dark-mode .modal-footer {
        background-color: #1c2129;
    }

    body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
        background-color: #1c2129;
    }

    body.dark-mode .table-hover tbody tr:hover,
    body.dark-mode .notification-item:hover {
        background-color: #1f2937;
    }

    body.dark-mode .form-control {
        color: #c9d1d9;
    }

    body.dark-mode .btn-action {
        background-color: #21262d;
        border-color: #30363d;
    }

    body.dark-mode .checkmark,
    body.dark-mode .radio-mark {
        background-color: #21262d;
        border-color: #30363d;
    }

    body.dark-mode .loader-progress {
        background-color: rgba(8, 220, 255, 0.1);
    }

    /* Ajustes de transición para el cambio de tema */
    body.theme-transition,
    body.theme-transition *,
    body.theme-transition *:before,
    body.theme-transition *:after {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        transition-delay: 0 !important;
    }

    /* ===== ACCESIBILIDAD ===== */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    :focus {
        outline: 3px solid rgba(8, 220, 255, 0.5);
        outline-offset: 2px;
    }

    .skip-link {
        position: absolute;
        top: -40px;
        left: 0;
        background: var(--azul-oscuro);
        color: white;
        padding: 8px 15px;
        z-index: 100;
        transition: top 0.3s ease;
    }

    .skip-link:focus {
        top: 0;
    }

    /* ===== SCROLL BAR PERSONALIZADA ===== */
    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    ::-webkit-scrollbar-track {
        background: var(--gris-claro);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(var(--azul-oscuro), var(--azul-medio));
        border-radius: 10px;
        border: 3px solid var(--gris-claro);
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(var(--azul-medio), var(--azul-oscuro));
    }
    .nav-item a {
    position: relative;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    color: #6098df !important;
    padding: 12px 15px;
    /* border: #c900ef; */
}
</style>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark2">
                <a href="" class="logo">
                    <div class="avatar-sm mr-4" data-aos="zoom-in">
                        <img src="{{ asset('img/quimecara.png') }}" class="navbar-brand" height="40">
                    </div>
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="dark2">
                <div class="container-fluid">
                    <div class="collapse" id="search-nav">
                        <div class="user-box">
                            <div class="u-text">
                                <h2 style="color: white" data-aos="fade-right">Bienvenid@ {{-- {{ Auth::user()->nombre }} --}}</h2>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret" data-aos="fade-left">
                            <a class="nav-link user-link" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fas fa-user user-icon"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <a class="dropdown-item" href="{{-- {{ route('logout') }} --}}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            Cerrar sesión
                                        </a>
                                        <form id="logout-form" action="{{-- {{ route('logout') }} --}}" method="POST"
                                            style="display: none;">@csrf</form>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2" data-background-color="dark2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-primary">
                        <li class="nav-section" data-aos="fade-right" data-aos-delay="100">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Menú</h4>
                        </li>

                        <li class="nav-item" data-aos="fade-right" data-aos-delay="200">
                            <a href="{{ route('admin.homologacionescoordinador.pantallaprincipal') }}">

                                <i class="fas fa-home"></i>
                                <p>Inicio</p>
                            </a>
                        </li>

                        <li class="nav-item" data-aos="fade-right" data-aos-delay="300">
                            <a href="{{ route('admin.homologacionescoordinador.index') }}">

                                <i class="fas fa-university"></i>
                                <p>Gestión de Homologaciones</p>
                            </a>
                        </li>


                        <li class="nav-item" data-aos="fade-right" data-aos-delay="500">
                            <a href="#">
                                <i class="fas fa-cogs"></i>
                                <p>Configuración</p>
                            </a>
                        </li>

                        <li class="nav-item" data-aos="fade-right" data-aos-delay="600">
                            <i class="fas fa-sign-out-alt"></i>
                            <p>Cerrar sesión</p>
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="main-panel">
            <div class="content">
                {{-- @include('sweetalert::alert') --}}
                @yield('content')
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav class="pull-left">
                    </nav>
                    <div class="copyright ml-auto">
                        {{ now()->year }} © Homologaciones uniautonoma<a href="https://www.uniautonoma.edu.co"
                            target="_blank">Uniautónoma</a> v{{ ENV('APP_VERSION') }}
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Eliminar el overlay de carga después de que la página esté completamente cargada
        $(window).on('load', function() {
            setTimeout(function() {
                $('.loading-overlay').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 300);
        });
    </script>
    @yield('scripts')

</body>

</html>

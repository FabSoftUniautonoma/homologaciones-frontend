<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ env('APP_NAME') }}</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
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
    <!--Select 2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- CSS Files -->
    <link href="{{ asset('atlantis/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('atlantis/assets/css/atlantis.css') }}" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <style>
        :root {
            --azul-oscuro: #19407b;
            --azul-medio: #0075bf;
            --azul-claro: #08dcff;
            --azul-muy-claro: #e1f5fe;
            --azul-contenedor: #f0f8ff;
            --blanco: #ffffff;
            --negro: #000000;
            --texto-oscuro: #212121;
            --texto-medio: #424242;
            --texto-claro: #757575;
            --gris-claro: #f4f4f4;
            --borde: #dddddd;
            --sombra: rgba(0, 0, 0, 0.1);
            --rojo-error: #ff4d4d;
            --verde-success: #4CAF50;
            --naranja-warning: #FF9800;
            --azul-info: #2196F3;
        }

        /* Estilos generales con la nueva paleta */
        body {
            background-color: var(--gris-claro);
            transition: background-color 0.5s ease;
            color: var(--texto-oscuro);
            font-family: 'Lato', sans-serif;
        }

        /* Mejora del contraste en los contenedores principales */
        .content {
            padding: 20px;
            animation: fadeInUp 0.5s ease;
        }

        .content-container {
            background-color: var(--azul-contenedor);
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 117, 191, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--azul-medio);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }

        .container-animated {
            opacity: 1;
            transform: translateY(0);
        }

        .content-title {
            color: var(--azul-oscuro);
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .content-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--azul-medio), var(--azul-claro));
        }

        /* Mejoras de contraste en cabecera y menú */
        .logo-header[data-background-color="dark2"],
        .navbar[data-background-color="dark2"] {
            background-color: var(--azul-oscuro) !important;
            transition: background-color 0.3s ease;
        }

        .sidebar[data-background-color="dark2"] {
            background: linear-gradient(180deg, var(--azul-oscuro) 0%, #152b4d 100%) !important;
            transition: background-color 0.3s ease;
        }

        /* Animaciones para el menú lateral */
        .nav-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin-bottom: 5px;
        }

        .nav-item.active {
            border-left: 3px solid var(--azul-claro);
            background: rgba(8, 220, 255, 0.15);
        }

        .nav-item.menu-hover:not(.active) {
            background: rgba(8, 220, 255, 0.05);
            border-left: 3px solid var(--azul-medio);
            transform: translateX(5px);
        }

        .nav-item a {
            transition: color 0.3s ease;
            color: var(--blanco) !important;
        }

        .nav-item:hover a {
            color: var(--azul-claro) !important;
        }

        /* Mejorar contraste en los textos */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--azul-oscuro);
        }

        p,
        span,
        div {
            color: var(--texto-medio);
        }

        .text-section {
            color: var(--blanco) !important;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Animación para el logo */
        .logo img {
            transition: transform 0.5s ease;
        }

        .logo:hover img {
            transform: scale(1.1) rotate(5deg);
        }

        /* Animaciones para tarjetas con mejor contraste */
        .card {
            border-radius: 8px;
            border: 1px solid var(--borde);
            box-shadow: 0 4px 8px var(--sombra);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            background-color: var(--blanco);
        }

        .card-header {
            background-color: var(--azul-muy-claro);
            border-bottom: 2px solid var(--azul-claro);
            font-weight: 600;
            color: var(--azul-oscuro);
        }

        .card-animated {
            opacity: 1;
            transform: translateY(0);
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 117, 191, 0.2);
            transform: translateY(-5px);
        }

        /* Botones con animación y mejor contraste */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 6px;
        }

        .btn:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.6s ease;
        }

        .btn:hover:after {
            left: 100%;
        }

        .btn-primary {
            background-color: var(--azul-medio);
            border-color: var(--azul-medio);
            color: var(--blanco);
        }

        .btn-primary:hover {
            background-color: var(--azul-oscuro);
            border-color: var(--azul-oscuro);
        }

        .btn-secondary {
            background-color: var(--azul-claro);
            border-color: var(--azul-claro);
            color: var(--azul-oscuro);
        }

        .btn-secondary:hover {
            background-color: #00c2e6;
            border-color: #00c2e6;
        }

        /* Sistema de notificaciones innovador */
        .notification-center {
            position: relative;
        }

        .notification-toggle {
            position: relative;
            cursor: pointer;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .notification-toggle i {
            font-size: 18px;
            color: var(--blanco);
        }

        .notification-count {
            position: absolute;
            top: 0;
            right: 0;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            animation: pulse 1.5s infinite;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: -50px;
            width: 320px;
            background-color: var(--blanco);
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .notification-dropdown.show-notification {
            opacity: 1;
            visibility: visible;
            transform: translateY(10px);
        }

        .notification-header {
            background: linear-gradient(135deg, var(--azul-medio), var(--azul-oscuro));
            color: var(--blanco);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--azul-claro);
        }

        .notification-title {
            font-weight: 600;
            font-size: 16px;
            margin: 0;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            padding: 12px 15px;
            border-bottom: 1px solid var(--borde);
            background-color: var(--blanco);
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: var(--azul-muy-claro);
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
        }

        .notification-success .notification-icon {
            background-color: rgba(76, 175, 80, 0.15);
            color: var(--verde-success);
        }

        .notification-danger .notification-icon {
            background-color: rgba(255, 77, 77, 0.15);
            color: var(--rojo-error);
        }

        .notification-warning .notification-icon {
            background-color: rgba(255, 152, 0, 0.15);
            color: var(--naranja-warning);
        }

        .notification-info .notification-icon {
            background-color: rgba(33, 150, 243, 0.15);
            color: var(--azul-info);
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-content h4 {
            margin: 0 0 5px;
            font-size: 14px;
            font-weight: 600;
            color: var(--texto-oscuro);
        }

        .notification-content p {
            margin: 0;
            font-size: 12px;
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
            transition: all 0.2s ease;
        }

        .notification-close:hover {
            color: var(--rojo-error);
            transform: scale(1.2);
        }

        .mark-all-read {
            background: transparent;
            border: none;
            color: var(--azul-claro);
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mark-all-read:hover {
            color: var(--blanco);
            text-decoration: underline;
        }

        /* Sistema de notificaciones emergentes */
        .notification-popup-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 320px;
        }

        .notification-popup {
            display: flex;
            padding: 15px;
            background-color: var(--blanco);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 4px solid var(--azul-medio);
            opacity: 0.95;
        }

        .notification-popup.show-popup {
            transform: translateX(0);
        }

        .notification-popup-success {
            border-left: 4px solid var(--verde-success);
        }

        .notification-popup-danger {
            border-left: 4px solid var(--rojo-error);
        }

        .notification-popup-warning {
            border-left: 4px solid var(--naranja-warning);
        }

        .notification-popup-info {
            border-left: 4px solid var(--azul-info);
        }

        /* Dropdown con animación */
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 5px 15px var(--sombra);
            border: 1px solid var(--borde);
            animation: fadeInDown 0.3s ease;
            background-color: var(--blanco);
        }

        .dropdown-item {
            color: var(--texto-medio);
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--azul-muy-claro);
            color: var(--azul-oscuro);
        }

        .animated-dropdown {
            animation: fadeInUp 0.3s ease;
        }

        /* Animación para alertas */
        .alert {
            animation: slideInRight 0.5s ease;
            border-radius: 8px;
            border-left: 4px solid;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            border-left-color: var(--verde-success);
            color: #2e7d32;
        }

        .alert-danger {
            background-color: rgba(255, 77, 77, 0.1);
            border-left-color: var(--rojo-error);
            color: #d32f2f;
        }

        .alert-warning {
            background-color: rgba(255, 152, 0, 0.1);
            border-left-color: var(--naranja-warning);
            color: #ef6c00;
        }

        .alert-info {
            background-color: rgba(33, 150, 243, 0.1);
            border-left-color: var(--azul-info);
            color: #0277bd;
        }

        /* Input con efectos */
        .form-control {
            border: 1px solid var(--borde);
            border-radius: 6px;
            transition: all 0.3s ease;
            color: var(--texto-oscuro);
            background-color: var(--blanco);
        }

        .form-control:focus {
            border-color: var(--azul-claro);
            box-shadow: 0 0 0 2px rgba(8, 220, 255, 0.25);
            background-color: var(--azul-muy-claro);
        }

        /* Toggle sidebar animado */
        .toggle-sidebar {
            transition: transform 0.3s ease;
            color: var(--blanco);
        }

        .toggle-sidebar:hover {
            transform: rotate(180deg);
            color: var(--azul-claro);
        }

        /* Animaciones para notificaciones */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .notification-pulse {
            animation: pulse 1.5s infinite;
        }

        /* Animaciones de entrada */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Animación para el contenido principal */
        .content {
            animation: fadeInUp 0.5s ease;
        }

        /* Ajustar los íconos del menú */
        .nav-item .fas,
        .nav-item .fa,
        .nav-item .icon-menu {
            color: var(--azul-claro);
            transition: all 0.3s ease;
        }

        .nav-item:hover .fas,
        .nav-item:hover .fa,
        .nav-item:hover .icon-menu {
            transform: translateX(3px);
        }

        /* Animación para el footer */
        .footer {
            background-color: var(--blanco);
            border-top: 1px solid var(--borde);
            transition: all 0.3s ease;
        }

        .footer a {
            color: var(--azul-medio);
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--azul-claro);
            text-decoration: none;
        }

        /* Overlay para loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(25, 64, 123, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeOut 1s ease 1s forwards;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--azul-claro);
            border-left-color: var(--azul-claro);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        /* Estilos para formularios */
        .form-group label,
        .form-check label {
            white-space: normal;
            color: var(--azul-oscuro);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-group input:focus+label {
            color: var(--azul-medio);
        }

        /* Personalización de la barra de desplazamiento */
        ::-webkit-scrollbar {
            width: 8px;
            background-color: #f5f5f5;
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--azul-medio);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: var(--azul-oscuro);
        }

        /* Progreso de carga innovador */
        .progress {
            height: 8px;
            background-color: var(--gris-claro);
            border-radius: 4px;
            overflow: hidden;
            margin: 15px 0;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--azul-medio), var(--azul-claro));
            position: relative;
            border-radius: 4px;
            animation: progressAnimation 1.5s ease-in-out;
        }

        @keyframes progressAnimation {
            0% {
                width: 5%;
            }

            100% {
                width: 100%;
            }
        }

        /* Mejoras visuales para tables */
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: var(--azul-muy-claro);
            color: var(--azul-oscuro);
            border-bottom: 2px solid var(--azul-claro);
            font-weight: 600;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(8, 220, 255, 0.05);
        }

        /* Badge personalizado */
        .badge {
            padding: 5px 10px;
            border-radius: 30px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .badge-primary {
            background-color: var(--azul-medio);
            color: var(--blanco);
        }

        .badge-success {
            background-color: var(--verde-success);
            color: var(--blanco);
        }

        .badge-danger {
            background-color: var(--rojo-error);
            color: var(--blanco);
        }

        .badge-warning {
            background-color: var(--naranja-warning);
            color: var(--blanco);
        }

        .badge-info {
            background-color: var(--azul-info);
            color: var(--blanco);
        }

        /* Tooltips personalizados */
        .tooltip .tooltip-inner {
            background-color: var(--azul-oscuro);
            color: var(--blanco);
            border-radius: 6px;
            font-size: 12px;
            padding: 8px 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            max-width: 250px;
        }

        .tooltip.bs-tooltip-top .arrow::before {
            border-top-color: var(--azul-oscuro);
        }

        .tooltip.bs-tooltip-right .arrow::before {
            border-right-color: var(--azul-oscuro);
        }

        .tooltip.bs-tooltip-bottom .arrow::before {
            border-bottom-color: var(--azul-oscuro);
        }

        .tooltip.bs-tooltip-left .arrow::before {
            border-left-color: var(--azul-oscuro);
        }

        /* Botones de paginación */
        .pagination .page-item .page-link {
            color: var(--azul-medio);
            border: 1px solid var(--borde);
            margin: 0 3px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--azul-medio);
            border-color: var(--azul-medio);
            color: var(--blanco);
        }

        .pagination .page-item .page-link:hover {
            background-color: var(--azul-muy-claro);
            color: var(--azul-oscuro);
            transform: translateY(-2px);
        }

        /* Optimización para dispositivos móviles */
        @media (max-width: 767px) {
            .content-container {
                padding: 15px;
            }

            .card {
                margin-bottom: 15px;
            }

            .notification-dropdown {
                width: 290px;
                right: -70px;
            }

            .btn {
                padding: 8px 15px;
                font-size: 13px;
            }

            .navbar .navbar-nav .dropdown .dropdown-menu {
                width: 100%;
                max-width: 100%;
            }
        }

        /* Modo oscuro (condicional) */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        body.dark-mode .content-container {
            background-color: #1e1e1e;
            border-left: 4px solid var(--azul-medio);
        }

        body.dark-mode .card {
            background-color: #262626;
            border-color: #333;
        }

        body.dark-mode .card-header {
            background-color: #333;
            border-bottom-color: var(--azul-medio);
            color: var(--azul-claro);
        }

        body.dark-mode .form-control {
            background-color: #333;
            border-color: #444;
            color: #e0e0e0;
        }

        body.dark-mode .form-control:focus {
            background-color: #3a3a3a;
        }

        body.dark-mode .table thead th {
            background-color: #333;
            color: var(--azul-claro);
            border-bottom-color: var(--azul-medio);
        }

        body.dark-mode .table {
            color: #e0e0e0;
        }

        body.dark-mode .table tbody tr:hover {
            background-color: #2a2a2a;
        }

        /* Animaciones de transición de página */
        .page-transition-enter {
            opacity: 0;
            transform: translateY(20px);
        }

        .page-transition-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 400ms, transform 400ms;
        }

        .page-transition-exit {
            opacity: 1;
            transform: translateY(0);
        }

        .page-transition-exit-active {
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 400ms, transform 400ms;
        }

        /* Estilos para select2 personalizado */
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--borde);
            border-radius: 6px;
            height: 38px;
            transition: all 0.3s ease;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: var(--azul-claro);
            box-shadow: 0 0 0 2px rgba(8, 220, 255, 0.25);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            color: var(--texto-oscuro);
            padding-left: 12px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--azul-medio);
        }

        /* Estilos para checkboxes y radios personalizados */
        .custom-checkbox .custom-control-input:checked~.custom-control-label::before,
        .custom-radio .custom-control-input:checked~.custom-control-label::before {
            background-color: var(--azul-medio);
            border-color: var(--azul-medio);
        }

        .custom-control-input:focus~.custom-control-label::before {
            box-shadow: 0 0 0 2px rgba(8, 220, 255, 0.25);
        }

        /* Animaciones de entrada para componentes principales */
        .main-header {
            animation: fadeInDown 0.5s ease;
        }

        .sidebar .sidebar-wrapper {
            animation: fadeInLeft 0.5s ease;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Ajustes finales y optimizaciones */
        .pointer {
            cursor: pointer;
        }

        .text-gradient {
            background: linear-gradient(90deg, var(--azul-medio), var(--azul-claro));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        /* Fin de los estilos personalizados */
    </style>
</head>

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
                            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fas fa-user"></i>
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
                            <h4 class="text-section">Estadísticas</h4>
                        </li>

                        <li class="nav-item" data-aos="fade-right" data-aos-delay="200">
                            <a href="{{ url(path: '/admin') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item" data-aos="fade-right" data-aos-delay="200">
                            <a href="{{ url(path: '/instituciones') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-building"></i>
                                <p>Instituciones</p>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item" data-aos="fade-right" data-aos-delay="250">
                                    <a href="{{ url(path: '/programas') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-graduation-cap"></i>
                                        <p>Programas</p>
                                    </a>
                                </li>
                                <li class="nav-item" data-aos="fade-right" data-aos-delay="300">
                                    <a href="{{ url(path: '/materia') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-book"></i>
                                        <p>Materias</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item" data-aos="fade-right" data-aos-delay="200">
                            <a href="{{ url(path: '/admin/usuarios') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-users"></i>
                                <p>Usuarios</p>
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
                        {{ now()->year }} © con❤️<a href="https://www.uniautonoma.edu.co"
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
</body>

</html>

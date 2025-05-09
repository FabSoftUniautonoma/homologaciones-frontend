<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ env('APP_NAME') }}</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/icon.svg') }}" />

    <!-- Protección ANTES de cargar el authService -->
    <script>
        // Verificar autenticación ANTES de cargar la página
        if (!localStorage.getItem('auth_token')) {
            window.location.href = '{{ route('login') }}';
        }
    </script>

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
    <!-- Fonts and icons -->
    <script src="{{ asset('atlantis/assets/js/plugin/webfont/webfont.min.js') }}"></script>
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

    <!-- Servicios de Autenticación - CARGADOS ANTES DEL DOCUMENTO READY -->
    <script src="{{ asset('js/authService.js') }}"></script>
    <script src="{{ asset('js/authMiddleware.js') }}"></script>
    <script src="{{ asset('js/login-script.js') }}"></script>

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
            // VERIFICACIÓN DE ROL - Ejecutar al inicio del documento ready
            if (typeof RoleChecker !== 'undefined') {
                if (!RoleChecker.isCoordinador()) {
                    console.log('Acceso denegado: Esta página es solo para coordinadores');
                    return; // Detener la ejecución del resto del script
                }
            } else {
                console.error('RoleChecker no está disponible');
            }

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

            // Configurar evento de cierre de sesión
            $('#logout-link, .logout-item').click(function(e) {
                e.preventDefault();

                const auth = new AuthService();
                auth.logout()
                    .then(() => {
                        window.location.href = `${auth.getBaseRoute()}/auth/login`;
                    })
                    .catch(error => {
                        console.error('Error al cerrar sesión:', error);
                        // Forzar cierre de sesión si hay error
                        auth.clearSession();
                        window.location.href = `${auth.getBaseRoute()}/auth/login`;
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
                                        <a class="dropdown-item logout-item" href="#">
                                            Cerrar sesión
                                        </a>
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

                        <li class="nav-item logout-item" data-aos="fade-right" data-aos-delay="600">
                            <a href="#">
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

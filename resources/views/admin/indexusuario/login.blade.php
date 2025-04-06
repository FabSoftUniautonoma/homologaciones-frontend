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
                <img src="{{ asset('images/quimerito.png') }}" alt="Universidad Autónoma del Cauca">
            </div>
        </div>

        <div class="form-container">
            <h2 class="form-title">Homologaciones Uniautónoma</h2>

            <!-- Mensaje de error -->
            @if(session('error'))
                <div id="error-message" class="error-message">{{ session('error') }}</div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese su correo" value="{{ old('email') }}" required>
                    <span class="input-icon"></span>

                    @error('email')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                    <span class="input-icon" id="togglePassword"></span>

                    @error('password')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordar mis datos</label>
                </div>

                <button type="submit" id="login-btn" class="login-btn">INGRESAR</button>
            </form>

            <div class="help-links">
                <a href="{{ route('register') }}">Registrate Aquí</a>
                <a href="{{ route('support') }}">Contactar soporte</a>
            </div>
        </div>

        <div class="footer">
            Universidad Autónoma del Cauca © 2025 - Sistema de Homologaciones
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>

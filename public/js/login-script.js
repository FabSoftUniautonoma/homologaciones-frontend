document.addEventListener('DOMContentLoaded', function() {
    // Verificar si ya hay una sesión activa
    const token = localStorage.getItem('auth_token');

    if (token) {
        // Verificar si el token es válido antes de redirigir
        const auth = new AuthService();

        // Solo verificar y redirigir si estamos en la página de login
        if (window.location.pathname.includes('/auth/login')) {
            // Verificar si el token es válido y redirigir
            auth.getUserProfile()
                .then(data => {
                    // Token válido, obtener URL de redirección
                    auth.getRedirectUrl()
                        .then(url => {
                            window.location.href = url;
                        })
                        .catch(err => {
                            console.error('Error obteniendo URL de redirección:', err);
                        });
                })
                .catch(error => {
                    // Token inválido, limpiar y quedarse en login
                    auth.clearSession();
                    console.error('Token inválido:', error);
                });
        }
    }

    // Configurar formulario de login
    const loginForm = document.getElementById('login-form');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember') ? document.getElementById('remember').checked : false;

            const auth = new AuthService();

            // Mostrar indicador de carga
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Iniciando sesión...';

            auth.login(email, password, remember)
                .then(response => {
                    console.log('Login exitoso:', response);
                    // La redirección se maneja en el método login
                })
                .catch(error => {
                    console.error('Error en login:', error);
                    // Mostrar mensaje de error
                    const errorMsg = document.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.textContent = 'Credenciales incorrectas. Por favor, intente nuevamente.';
                        errorMsg.style.display = 'block';
                    }

                    // Restaurar botón
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });
    }
});

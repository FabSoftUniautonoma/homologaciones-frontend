/**
 * Middleware de autenticación para proteger rutas basadas en roles de usuario
 * Incluye verificación de autenticación, control de acceso por rol, y verificación de estado del perfil
 */
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que AuthService está disponible
    if (typeof AuthService !== 'function') {
        console.error('Error: AuthService no está disponible');
        return;
    }

    // Crear instancia del servicio de autenticación
    const auth = new AuthService();

    // Si no hay token, redirigir a login
    if (!auth.getToken()) {
        window.location.href = `${auth.getBaseRoute()}/auth/login`;
        return;
    }

    // Obtener usuario y verificar que exista
    const user = auth.getUser();
    if (!user) {
        console.error('Error: Usuario no encontrado en localStorage');
        auth.clearSession();
        window.location.href = `${auth.getBaseRoute()}/auth/login`;
        return;
    }

    // Verificar si es una ruta pública (no requiere autenticación)
    const currentPath = window.location.pathname;
    const publicRoutes = [
        '/auth/login',
        '/auth/register',
        '/auth/forgot-password',
        '/auth/reset-password',
    ];

    // Si es una ruta pública y hay token, redirigir según rol (para evitar volver al login)
    if (publicRoutes.some(route => currentPath.includes(route)) && auth.isAuthenticated()) {
        auth.getRedirectUrl().then(url => {
            window.location.href = url;
        });
        return;
    }

    // Verificar si el perfil está completo
    auth.checkProfileStatus().then(isComplete => {
        // Si el perfil está incompleto y no estamos en la página de completar perfil
        if (!isComplete && !currentPath.includes('solicitudhomologacion')) {
            window.location.href = `${auth.getBaseRoute()}/homologaciones/solicitudhomologacion`;
            return;
        }

        // Verificar si el usuario tiene acceso a la ruta actual según su rol
        const hasAccess = auth.hasRouteAccess(currentPath);

        if (!hasAccess) {
            console.log('Acceso no permitido a esta ruta para este rol');

            // Redirigir a la página adecuada según el rol
            auth.getRedirectUrl().then(url => {
                window.location.href = url;
            });
            return;
        }

        console.log('Acceso autorizado a la ruta:', currentPath);

        // Si todo está bien, actualizar elementos de la UI
        updateUIBasedOnRole(user);
    }).catch(error => {
        console.error('Error verificando estado del perfil:', error);

        // En caso de error, intentar verificar solo el acceso a la ruta
        if (!auth.hasRouteAccess(currentPath)) {
            auth.getRedirectUrl().then(url => {
                window.location.href = url;
            });
        }
    });

    /**
     * Actualiza los elementos de la interfaz según el rol del usuario
     * @param {Object} user - Datos del usuario
     */
    function updateUIBasedOnRole(user) {
        if (!user) return;

        // Actualizar nombre de usuario en la UI si existe el elemento
        const userNameElement = document.getElementById('user-name');
        if (userNameElement) {
            userNameElement.textContent = `${user.primer_nombre || ''} ${user.primer_apellido || ''}`;
        }

        // Actualizar elementos que muestran el rol del usuario
        const userRoleElement = document.getElementById('user-role');
        if (userRoleElement && user.rol_id) {
            const rolNames = {
                1: 'Aspirante',
                2: 'Coordinador',
                3: 'Administrador'
            };

            userRoleElement.textContent = rolNames[user.rol_id] || 'Usuario';
        }

        // Ocultar/mostrar elementos del menú según el rol (utilizando el atributo data-role)
        const menuItems = document.querySelectorAll('[data-role]');
        if (menuItems.length > 0) {
            menuItems.forEach(item => {
                const rolesPermitidos = item.getAttribute('data-role').split(',').map(r => parseInt(r.trim()));

                if (!rolesPermitidos.includes(user.rol_id)) {
                    // Si el rol del usuario no está en la lista de roles permitidos para este elemento
                    item.style.display = 'none';
                } else {
                    // Asegurarse que sea visible (por si se ha ocultado previamente)
                    item.style.display = '';
                }
            });
        }

        // Ocultar/mostrar secciones completas según el rol (utilizando el atributo data-section-role)
        const sections = document.querySelectorAll('[data-section-role]');
        if (sections.length > 0) {
            sections.forEach(section => {
                const rolesPermitidos = section.getAttribute('data-section-role').split(',').map(r => parseInt(r.trim()));

                if (!rolesPermitidos.includes(user.rol_id)) {
                    section.style.display = 'none';
                } else {
                    section.style.display = '';
                }
            });
        }

        // Configurar logout automático por inactividad
        setupInactivityLogout();
    }

    /**
     * Configura el cierre de sesión automático por inactividad
     * Tiempo de inactividad: 30 minutos
     */
    function setupInactivityLogout() {
        let inactivityTime = 30 * 60 * 1000; // 30 minutos en milisegundos
        let inactivityTimer;

        // Función para reiniciar el temporizador
        const resetTimer = () => {
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(() => {
                console.log('Sesión cerrada por inactividad');

                // Cerrar sesión
                auth.logout()
                    .then(() => {
                        window.location.href = `${auth.getBaseRoute()}/auth/login`;
                    })
                    .catch(error => {
                        console.error('Error al cerrar sesión por inactividad:', error);
                        // Forzar cierre de sesión en caso de error
                        auth.clearSession();
                        window.location.href = `${auth.getBaseRoute()}/auth/login`;
                    });
            }, inactivityTime);
        };

        // Reiniciar el temporizador en eventos de usuario
        const events = ['click', 'keypress', 'scroll', 'mousemove', 'touchstart'];
        events.forEach(event => {
            document.addEventListener(event, resetTimer);
        });

        // Iniciar el temporizador
        resetTimer();
    }
});

/**
 * Función de middleware estática para usar en páginas específicas
 * @param {Array} allowedRoles - Array con los roles permitidos para la página
 * @returns {boolean} - Verdadero si el usuario tiene acceso, falso si no
 */
window.checkRoleAccess = function(allowedRoles) {
    try {
        const auth = new AuthService();

        if (!auth.isAuthenticated()) {
            window.location.href = `${auth.getBaseRoute()}/auth/login`;
            return false;
        }

        const user = auth.getUser();

        if (!user || !user.rol_id) {
            window.location.href = `${auth.getBaseRoute()}/auth/login`;
            return false;
        }

        if (!allowedRoles.includes(user.rol_id)) {
            // Redirigir al usuario a su dashboard correspondiente
            auth.getRedirectUrl().then(url => {
                window.location.href = url;
            });
            return false;
        }

        return true;
    } catch (error) {
        console.error('Error verificando acceso por rol:', error);
        return false;
    }
};

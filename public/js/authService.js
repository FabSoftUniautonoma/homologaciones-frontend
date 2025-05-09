/**
 * Servicio de autenticación mejorado para manejar la comunicación con la API JWT
 * Incluye funciones para verificar perfil completo y redirección inteligente
 */
class AuthService {
    constructor(baseUrl) {
        this.baseUrl = baseUrl || 'http://127.0.0.1:8000/api'; // URL relativa para API en el mismo proyecto Laravel
        this.tokenKey = 'auth_token';
        this.userKey = 'user_data';
        this.profileStatusKey = 'profile_status'; // Nueva clave para almacenar estado del perfil
    }

    /**
 * Iniciar sesión con email y contraseña
 * @param {string} email - Correo electrónico del usuario
 * @param {string} password - Contraseña del usuario
 * @param {boolean} remember - Si se debe recordar la sesión
 * @returns {Promise} - Promesa con resultado de la autenticación
 */
    async login(email, password, remember = false) {
        try {
            const response = await fetch(`${this.baseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ email, password }),
                credentials: 'include' // Para soportar cookies HTTP-only
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error en la autenticación');
            }

            // Guardar token y datos del usuario
            this.setToken(data.access_token);
            this.setUser(data.user);

            // Verificar si el perfil está completo (sin await para evitar bloquear)
            this.checkProfileStatus().then(() => {
                // Determinar redirección según estado del perfil
                this.getRedirectUrl().then(redirectUrl => {
                    // Realizar la redirección después de completar todas las promesas
                    window.location.href = redirectUrl;
                });
            });

            // Retornar datos sin esperar la redirección
            return data;
        } catch (error) {
            console.error('Error durante login:', error);
            throw error;
        }
    }

    /**
   * Verificar estado del perfil del usuario (completo o incompleto)
   * @returns {Promise<boolean>} - Verdadero si el perfil está completo, falso si no
   */
    async checkProfileStatus() {
        try {
            if (!this.getToken()) {
                return false;
            }

            const userData = await this.getUserProfile();
            const user = userData || this.getUser();

            if (!user) {
                return false;
            }

            // Validación mínima requerida
            const isComplete = !!(user.primer_nombre && user.primer_apellido);

            localStorage.setItem(this.profileStatusKey, isComplete ? 'complete' : 'incomplete');
            return isComplete;
        } catch (error) {
            console.error('Error verificando estado del perfil:', error);
            localStorage.setItem(this.profileStatusKey, 'incomplete');
            return false;
        }
    }


    /**
    * Determina la URL a la que se debe redirigir según el estado del perfil y rol
    * @returns {string} - URL para redirección
    */
    async getRedirectUrl() {
        const profileStatus = localStorage.getItem(this.profileStatusKey);
        const user = this.getUser();
        const baseRoute = this.getBaseRoute();

        if (!user) {
            return `${baseRoute}/auth/login`;
        }

        const currentPath = window.location.pathname;
        if (currentPath.includes('/auth/login')) {
            console.log('Ya estamos en login, determinando ruta correcta...');
        }

        const rolId = user.rol_id;

        if (profileStatus === 'incomplete') {
            return `${baseRoute}/homologaciones/solicitudhomologacion`;
        }

        if (rolId === 1) { // Aspirante
            return `${baseRoute}/homologaciones/aspirante`;
        }

        switch (rolId) {
            case 2:
                return `${baseRoute}/coordinador/inicio`;
            case 3:
                return `${baseRoute}/administrador`;
            default:
                return `${baseRoute}/homologaciones/solicitudhomologacion`;
        }
    }


    /**
     * Obtiene la URL base de la aplicación para redirecciones
     * @returns {string} - URL base de la aplicación
     */
    getBaseRoute() {
        // Ajusta esto según sea necesario para tu entorno
        return '/homologaciones-frontend/public';
    }

    /**
      * Registrar un nuevo usuario
      * @param {Object} userData - Datos del usuario a registrar
      * @returns {Promise} - Promesa con resultado del registro
      */
    async register(userData) {
        try {
            const response = await fetch(`${this.baseUrl}/auth/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken() // Para protección CSRF de Laravel
                },
                body: JSON.stringify(userData),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(Object.values(data).join(', ') || 'Error en el registro');
            }

            return data;
        } catch (error) {
            console.error('Error durante registro:', error);
            throw error;
        }
    }

    // Método para proteger rutas
    static middleware() {
        const token = localStorage.getItem('auth_token');
        const baseRoute = '/homologaciones-frontend/public';

        if (!token) {
            // Redirigir al login si no hay token
            window.location.href = `${baseRoute}/auth/login`;
            return false;
        }

        // Verificar si el token es válido
        this.verifyToken(token).catch(() => {
            window.location.href = `${baseRoute}/auth/login`;
        });

        return true;
    }

    // Verificar token con el backend
    static async verifyToken(token) {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/auth/user-profile', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                console.error('Token inválido o expirado');
                throw new Error('Token inválido');
            }

            return await response.json();
        } catch (error) {
            console.error('Error verificando token:', error);
            throw error;
        }
    }
    /**
     * Cerrar sesión del usuario actual
     * @returns {Promise} - Promesa con resultado del cierre de sesión
     */
    async logout() {
        try {
            const token = this.getToken();

            if (!token) {
                throw new Error('No hay sesión activa');
            }

            const response = await fetch(`${this.baseUrl}/auth/logout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': this.getCsrfToken() // Para protección CSRF de Laravel
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error al cerrar sesión');
            }

            // Limpiar datos de sesión
            this.clearSession();

            return data;
        } catch (error) {
            console.error('Error durante logout:', error);
            throw error;
        }
    }

    /**
     * Obtiene el perfil del usuario actual
     * @returns {Promise} - Promesa con datos del usuario
     */
    async getUserProfile() {
        try {
            const token = this.getToken();

            if (!token) {
                throw new Error('No hay sesión activa');
            }

            const response = await fetch(`${this.baseUrl}/auth/user-profile`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error al obtener perfil');
            }

            return data;
        } catch (error) {
            console.error('Error al obtener perfil:', error);
            throw error;
        }
    }

    /**
     * Refresca el token JWT actual
     * @returns {Promise} - Promesa con nuevo token
     */
    async refreshToken() {
        try {
            const token = this.getToken();

            if (!token) {
                throw new Error('No hay token para refrescar');
            }

            const response = await fetch(`${this.baseUrl}/auth/refresh`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': this.getCsrfToken() // Para protección CSRF de Laravel
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error al refrescar token');
            }

            // Actualizar token en localStorage
            this.setToken(data.access_token);
            this.setUser(data.user);

            return data;
        } catch (error) {
            console.error('Error al refrescar token:', error);
            this.clearSession();
            throw error;
        }
    }

    /**
     * Realiza una petición autenticada a cualquier endpoint de la API
     * @param {string} url - URL del endpoint
     * @param {Object} options - Opciones de fetch
     * @returns {Promise} - Promesa con resultado de la petición
     */
    async authenticatedRequest(url, options = {}) {
        const token = this.getToken();

        if (!token) {
            throw new Error('No hay sesión activa');
        }

        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': this.getCsrfToken() // Para protección CSRF de Laravel
            }
        };

        const mergedOptions = {
            ...defaultOptions,
            ...options,
            headers: {
                ...defaultOptions.headers,
                ...options.headers
            }
        };

        try {
            const response = await fetch(`${this.baseUrl}${url}`, mergedOptions);
            const data = await response.json();

            if (!response.ok) {
                if (response.status === 401) {
                    // Token expirado, intentar refrescar
                    try {
                        await this.refreshToken();
                        // Reintentar con nuevo token
                        return this.authenticatedRequest(url, options);
                    } catch (refreshError) {
                        this.clearSession();
                        throw new Error('Sesión expirada');
                    }
                }
                throw new Error(data.error || 'Error en la petición');
            }

            return data;
        } catch (error) {
            console.error('Error en petición autenticada:', error);
            throw error;
        }
    }

    /**
     * Obtiene un usuario específico por ID
     * @param {number} userId - ID del usuario a obtener
     * @returns {Promise} - Promesa con datos del usuario
     */
    async getUser(userId) {
        try {
            // Si no se proporciona ID, usar el ID del usuario actual
            const id = userId || (this.getUser() && this.getUser().id);

            if (!id) {
                throw new Error('ID de usuario no proporcionado');
            }

            return this.authenticatedRequest(`/usuarios/${id}`, {
                method: 'GET'
            });
        } catch (error) {
            console.error('Error obteniendo usuario:', error);
            throw error;
        }
    }



    // Método para obtener el token CSRF de Laravel
    getCsrfToken() {
        // Obtener del meta tag que Laravel suele incluir en sus vistas
        const tokenElement = document.querySelector('meta[name="csrf-token"]');
        return tokenElement ? tokenElement.getAttribute('content') : '';
    }

    // Métodos de utilidad para manejar tokens

    setToken(token) {
        localStorage.setItem(this.tokenKey, token);
    }

    getToken() {
        return localStorage.getItem(this.tokenKey);
    }

    setUser(user) {
        localStorage.setItem(this.userKey, JSON.stringify(user));
    }

    getUser() {
        const userData = localStorage.getItem(this.userKey);
        return userData ? JSON.parse(userData) : null;
    }

    clearSession() {
        localStorage.removeItem(this.tokenKey);
        localStorage.removeItem(this.userKey);
        localStorage.removeItem(this.profileStatusKey);
    }

    isAuthenticated() {
        return !!this.getToken();
    }

    isAuthenticated() {
        return !!this.getToken();
    }

    /**
     * Verifica si el usuario actual tiene acceso a una ruta específica
     * @param {string} route - Ruta que se quiere acceder
     * @returns {boolean} - Verdadero si tiene acceso, falso si no
     */
    hasRouteAccess(route) {
        const user = this.getUser();

        if (!user || !user.rol_id) {
            return false; // Sin usuario o rol, no hay acceso
        }

        const rolId = user.rol_id;
        const baseRoute = this.getBaseRoute();

        // Mapeo de rutas permitidas por rol
        const rutasPermitidas = {
            1: [ // Aspirante
                `${baseRoute}/homologaciones/aspirante`,
                `${baseRoute}/homologaciones/solicitudhomologacion`
            ],
            2: [ // Coordinador
                `${baseRoute}/coordinador/inicio`,
                `${baseRoute}/coordinador`
            ],
            3: [ // Administrador
                `${baseRoute}/administrador`,
                `${baseRoute}/admin`
            ]
        };

        // Función para verificar si la ruta actual está permitida para el rol
        const tieneAcceso = () => {
            // Si el usuario no tiene un rol definido en el mapeo, no tiene acceso
            if (!rutasPermitidas[rolId]) {
                return false;
            }

            // Verificar si alguna de las rutas permitidas coincide con la ruta solicitada
            return rutasPermitidas[rolId].some(rutaPermitida => {
                // Verificar rutas exactas o rutas que sean padres (comienzan con)
                return route === rutaPermitida || route.startsWith(rutaPermitida + '/');
            });
        };

        // Las páginas de perfil y cambio de contraseña son accesibles para todos los roles
        const rutasGenerales = [
            `${baseRoute}/perfil`,
            `${baseRoute}/cambiar-password`,
            `${baseRoute}/auth/logout`
        ];

        // Si la ruta está en las generales, permitir acceso
        if (rutasGenerales.some(r => route === r || route.startsWith(r + '/'))) {
            return true;
        }

        return tieneAcceso();
    }

    /**
     * Redirige al usuario a su ruta por defecto si no tiene acceso a la ruta actual
     * @returns {boolean} - Verdadero si tiene acceso, falso si fue redirigido
     */
    enforceRouteAccess() {
        const currentPath = window.location.pathname;

        if (!this.hasRouteAccess(currentPath)) {
            // El usuario no tiene acceso a esta ruta, redirigir a su dashboard según rol
            this.getRedirectUrl().then(url => {
                window.location.href = url;
            });
            return false;
        }

        return true;
    }

    /**
     * Actualiza el perfil del usuario
     * @param {Object} userData - Datos del usuario a actualizar
     * @returns {Promise} - Promesa con resultado de la actualización
     */
    async updateProfile(userData) {
        try {
            const user = this.getUser();

            if (!user || !user.id) {
                throw new Error('No hay usuario para actualizar');
            }

            const response = await this.authenticatedRequest(`/auth/update-profile`, {
                method: 'POST',
                body: JSON.stringify(userData)
            });

            if (response && response.user) {
                // Actualizar datos del usuario en localStorage
                this.setUser(response.user);

                // Actualizar estado del perfil
                await this.checkProfileStatus();
            }

            return response;
        } catch (error) {
            console.error('Error actualizando perfil:', error);
            throw error;
        }
    }



}

// Exportar servicio para uso en otros archivos
window.AuthService = AuthService;

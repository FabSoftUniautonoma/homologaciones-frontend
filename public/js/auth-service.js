/**
 * Servicio de autenticación para manejar la comunicación con la API JWT
 */
class AuthService {
    constructor(baseUrl) {
      this.baseUrl = baseUrl || 'http://127.0.0.1:8000/api'; // URL relativa para API en el mismo proyecto Laravel
      this.tokenKey = 'auth_token';
      this.userKey = 'user_data';
    }

    /**
     * Iniciar sesión con email y contraseña
     * @param {string} email - Correo electrónico del usuario
     * @param {string} password - Contraseña del usuario
     * @returns {Promise} - Promesa con resultado de la autenticación
     */
     async login(email, password) {
        try {
            const response = await fetch(`${this.baseUrl}/auth/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error en la autenticación');
            }

            // Guardar token y datos del usuario
            this.setToken(data.access_token);
            this.setUser(data.user);

            // Redirigir después del login exitoso
            this.redirectAfterLogin();

            return data;
        } catch (error) {
            console.error('Error durante login:', error);
            throw error;
        }
    }

    // Nuevo método para manejar la redirección
    redirectAfterLogin() {
        // Puedes redirigir basándote en el rol del usuario o una ruta predeterminada
        const user = this.getUser();

        // Redirigir basándote en el rol o simplemente a un dashboard general
        if (user && user.role === 'aspirante') {
            window.location.href = '/homologaciones/aspirante';
        } else if (user && user.role === 'coordinador') {
            window.location.href = '/coordinador';
        } else if (user && user.role === 'administrador') {
            window.location.href = '/administrador';
        } else {
            // Por defecto, redirigir a una vista general
            window.location.href = '/homologaciones/solicitudhomologacion';
        }
    }

    // Método para proteger rutas
    static middleware() {
        const token = localStorage.getItem('auth_token');

        if (!token) {
            // Redirigir al login si no hay token
            window.location.href = '/auth/login';
            return false;
        }

        // Verificar si el token es válido
        this.verifyToken(token).catch(() => {
            window.location.href = '/auth/login';
        });

        return true;
    }

    // Verificar token con el backend
    static async verifyToken(token) {
        const response = await fetch('http://127.0.0.1:8000/api/auth/user-profile', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Token inválido');
        }

        return response.json();
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
    }

    isAuthenticated() {
      return !!this.getToken();
    }
  }

  // Exportar servicio para uso en otros archivos
  window.AuthService = AuthService;

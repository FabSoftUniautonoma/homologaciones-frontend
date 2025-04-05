<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Homologación - Universidad Autónoma del Cauca</title>



</head><!-- Añade este código en la barra de navegación -->
<li class="nav-item dropdown hidden-caret notifications-dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <div class="notification-bell">
            <i class="fas fa-bell"></i>
            <span class="notification-count" id="total-notification-count">0</span>
            <span class="notification-ring"></span>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-animated fadeIn notifications-dropdown-content" aria-labelledby="notificationsDropdown">
        <div class="notifications-header">
            <div class="tabs-container">
                <div class="tab active" data-tab="homologaciones">
                    <i class="fas fa-file-alt"></i> Homologaciones
                    <span class="badge" id="homologaciones-count">0</span>
                </div>
                <div class="tab" data-tab="mensajes">
                    <i class="fas fa-envelope"></i> Mensajes
                    <span class="badge" id="mensajes-count">0</span>
                </div>
                <div class="tab" data-tab="reportes">
                    <i class="fas fa-chart-bar"></i> Reportes
                    <span class="badge" id="reportes-count">0</span>
                </div>
            </div>
        </div>
        <div class="notifications-scroll scrollbar scrollbar-inner">
            <!-- Contenido de Homologaciones -->
            <div class="tab-content active" id="homologaciones-content">
                <h6 class="notification-category">Homologaciones pendientes para Ingeniería de Software</h6>
                <div class="notification-items" id="homologaciones-items">
                    <!-- El contenido se cargará dinámicamente -->
                    <div class="notification-item empty-notification">
                        <i class="fas fa-spinner fa-pulse"></i>
                        <p>Cargando homologaciones...</p>
                    </div>
                </div>
            </div>

            <!-- Contenido de Mensajes -->
            <div class="tab-content" id="mensajes-content">
                <h6 class="notification-category">Mensajes recientes</h6>
                <div class="notification-items" id="mensajes-items">
                    <!-- El contenido se cargará dinámicamente -->
                    <div class="notification-item empty-notification">
                        <i class="fas fa-spinner fa-pulse"></i>
                        <p>Cargando mensajes...</p>
                    </div>
                </div>
            </div>

            <!-- Contenido de Reportes -->
            <div class="tab-content" id="reportes-content">
                <h6 class="notification-category">Reportes de Vicerrectoría</h6>
                <div class="notification-items" id="reportes-items">
                    <!-- El contenido se cargará dinámicamente -->
                    <div class="notification-item empty-notification">
                        <i class="fas fa-spinner fa-pulse"></i>
                        <p>Cargando reportes...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="notifications-footer">
            <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-round btn-shadow btn-block">
                Ver todas las notificaciones
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </ul>
</li>
<style>
    /* Estilos para el sistema de notificaciones */
:root {
    --azul-oscuro: #19407b;
    --azul-medio: #0075bf;
    --azul-claro: #08dcff;
    --blanco: #ffffff;
    --gris-claro: #f4f4f4;
    --borde: #dddddd;
    --sombra: rgba(0, 0, 0, 0.1);
    --rojo-error: #ff4d4d;
    --verde-exito: #2dce89;
    --amarillo-alerta: #ffad46;
}

/* Estilos para la campana de notificaciones */
.notifications-dropdown {
    position: relative;
}

.notification-bell {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 40px;
    width: 40px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.notification-bell:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: scale(1.1);
}

.notification-bell i {
    font-size: 1.3rem;
    color: var(--blanco);
}

.notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background-color: var(--rojo-error);
    color: var(--blanco);
    font-size: 0.7rem;
    font-weight: bold;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* Círculo animado alrededor de la campana */
.notification-ring {
    position: absolute;
    top: 0;
    left: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid var(--azul-claro);
    opacity: 0;
    pointer-events: none;
}

/* Animación del círculo cuando hay notificaciones nuevas */
@keyframes ringPulse {
    0% {
        transform: scale(1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.has-new-notification .notification-ring {
    animation: ringPulse 1.5s infinite;
}

/* Menú desplegable de notificaciones */
.notifications-dropdown-content {
    width: 360px;
    max-height: 500px;
    padding: 0;
    border-radius: 8px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
    border: none;
    overflow: hidden;
}

.notifications-header {
    background-color: var(--azul-oscuro);
    color: var(--blanco);
    padding: 10px;
}

.tabs-container {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.tab {
    flex: 1;
    padding: 10px 8px;
    text-align: center;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    position: relative;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
}

.tab i {
    margin-right: 4px;
}

.tab.active {
    border-bottom: 2px solid var(--azul-claro);
    color: var(--azul-claro);
}

.tab:hover:not(.active) {
    background-color: rgba(255, 255, 255, 0.05);
}

.tab .badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: var(--rojo-error);
    color: white;
    font-size: 0.65rem;
    height: 18px;
    min-width: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    padding: 0 5px;
    transform: scale(0);
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.tab .badge.visible {
    transform: scale(1);
}

/* Contenido de las notificaciones */
.notifications-scroll {
    max-height: 350px;
    overflow-y: auto;
}

.tab-content {
    display: none;
    padding: 0;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

.notification-category {
    padding: 10px 15px;
    margin: 0;
    background-color: var(--gris-claro);
    color: var(--azul-oscuro);
    font-weight: 600;
    font-size: 0.85rem;
    border-bottom: 1px solid var(--borde);
}

.notification-items {
    padding: 0;
}

.notification-item {
    padding: 12px 15px;
    border-bottom: 1px solid var(--borde);
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.notification-item:hover {
    background-color: rgba(8, 220, 255, 0.05);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.notification-icon.primary {
    background-color: rgba(0, 117, 191, 0.1);
    color: var(--azul-medio);
}

.notification-icon.success {
    background-color: rgba(45, 206, 137, 0.1);
    color: var(--verde-exito);
}

.notification-icon.warning {
    background-color: rgba(255, 173, 70, 0.1);
    color: var(--amarillo-alerta);
}

.notification-icon.danger {
    background-color: rgba(255, 77, 77, 0.1);
    color: var(--rojo-error);
}

.notification-content {
    flex-grow: 1;
}

.notification-title {
    margin: 0 0 3px 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #2a2a2a;
}

.notification-text {
    margin: 0;
    font-size: 0.8rem;
    color: #6b6b6b;
}

.notification-time {
    font-size: 0.75rem;
    color: #999;
    margin-top: 3px;
}

.notification-badge {
    height: 8px;
    width: 8px;
    border-radius: 50%;
    background-color: var(--azul-claro);
    position: absolute;
    top: 12px;
    right: 15px;
}

.notification-item.unread {
    background-color: rgba(8, 220, 255, 0.05);
}

.notification-item.unread .notification-title {
    font-weight: 700;
    color: #000;
}

.empty-notification {
    color: #999;
    text-align: center;
    padding: 30px 15px;
    font-size: 0.9rem;
}

.empty-notification i {
    font-size: 1.5rem;
    margin-bottom: 10px;
    display: block;
}

.notifications-footer {
    padding: 10px 15px;
    background-color: var(--gris-claro);
    border-top: 1px solid var(--borde);
}

.btn-shadow {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.btn-shadow:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

/* Animaciones */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

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

.pulse-animation {
    animation: pulse 0.6s ease-in-out;
}

/* Customize scrollbar */
.scrollbar::-webkit-scrollbar {
    width: 5px;
}

.scrollbar::-webkit-scrollbar-track {
    background: var(--gris-claro);
}

.scrollbar::-webkit-scrollbar-thumb {
    background: var(--azul-medio);
    border-radius: 5px;
}

.scrollbar::-webkit-scrollbar-thumb:hover {
    background: var(--azul-oscuro);
}
</style>
<script>
    // Agregar este código en un archivo js o en una sección script de tu vista
document.addEventListener('DOMContentLoaded', function() {
    const notificationSystem = {
        // Elementos DOM
        elements: {
            bell: document.querySelector('.notification-bell'),
            count: document.getElementById('total-notification-count'),
            dropdown: document.querySelector('.notifications-dropdown-content'),
            tabs: document.querySelectorAll('.tab'),
            tabContents: document.querySelectorAll('.tab-content'),
            counters: {
                homologaciones: document.getElementById('homologaciones-count'),
                mensajes: document.getElementById('mensajes-count'),
                reportes: document.getElementById('reportes-count')
            },
            containers: {
                homologaciones: document.getElementById('homologaciones-items'),
                mensajes: document.getElementById('mensajes-items'),
                reportes: document.getElementById('reportes-items')
            }
        },

        // Estado de las notificaciones
        state: {
            homologaciones: [],
            mensajes: [],
            reportes: [],
            unreadCount: {
                homologaciones: 0,
                mensajes: 0,
                reportes: 0
            },
            activeTab: 'homologaciones',
            isLoading: true
        },

        // Inicializar el sistema de notificaciones
        init: function() {
            this.setupEventListeners();
            this.fetchNotifications();
            this.startPolling();
        },

        // Configurar eventos
        setupEventListeners: function() {
            const self = this;

            // Cambio de pestañas
            this.elements.tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    self.changeTab(tabName);
                });
            });

            // Evento para marcar como leídas al abrir el dropdown
            document.addEventListener('click', function(e) {
                const isDropdownButton = e.target.closest('#notificationsDropdown');
                const isInsideDropdown = e.target.closest('.notifications-dropdown-content');

                if (isDropdownButton) {
                    self.elements.bell.classList.add('pulse-animation');
                    setTimeout(() => {
                        self.elements.bell.classList.remove('pulse-animation');
                    }, 600);
                }

                if (!isDropdownButton && !isInsideDropdown) {
                    // Si se hace clic fuera del dropdown, cerrarlo
                    if (self.elements.dropdown.classList.contains('show')) {
                        $(self.elements.dropdown).dropdown('hide');
                    }
                }
            });

            // Delegación de eventos para elementos de notificación
            document.querySelectorAll('.notification-items').forEach(container => {
                container.addEventListener('click', function(e) {
                    const notificationItem = e.target.closest('.notification-item');
                    if (notificationItem && !notificationItem.classList.contains('empty-notification')) {
                        const notificationType = this.id.split('-')[0];
                        const notificationId = notificationItem.getAttribute('data-id');

                        if (notificationItem.classList.contains('unread')) {
                            self.markAsRead(notificationType, notificationId);
                        }

                        self.handleNotificationClick(notificationType, notificationId);
                    }
                });
            });
        },

        // Cambiar entre pestañas
        changeTab: function(tabName) {
            this.state.activeTab = tabName;

            // Actualizar clases de pestañas
            this.elements.tabs.forEach(tab => {
                if (tab.getAttribute('data-tab') === tabName) {
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
                }
            });

            // Actualizar contenido visible
            this.elements.tabContents.forEach(content => {
                if (content.id === `${tabName}-content`) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });

            // Si cambiamos a una pestaña, marcamos como leídas sus notificaciones
            this.markTabAsRead(tabName);
        },

        // Marcar las notificaciones de una pestaña como leídas
        markTabAsRead: function(tabName) {
            const self = this;

            // Enviar solicitud al servidor para marcar como leídas
            fetch(`/api/notifications/${tabName}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el estado local
                    self.state.unreadCount[tabName] = 0;
                    self.updateUnreadCounters();

                    // Marcar visualmente como leídas
                    const container = self.elements.containers[tabName];
                    const unreadItems = container.querySelectorAll('.notification-item.unread');
                    unreadItems.forEach(item => {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.notification-badge');
                        if (badge) {
                            badge.remove();
                        }
                    });
                }
            })
            .catch(error => console.error('Error al marcar notificaciones como leídas:', error));
        },

        // Marcar una notificación específica como leída
        markAsRead: function(type, id) {
            const self = this;

            fetch(`/api/notifications/${type}/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el estado
                    self.state.unreadCount[type]--;
                    if (self.state.unreadCount[type] < 0) self.state.unreadCount[type] = 0;
                    self.updateUnreadCounters();

                    // Actualizar la interfaz
                    const item = document.querySelector(`.notification-item[data-id="${id}"]`);
                    if (item) {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.notification-badge');
                        if (badge) {
                            badge.remove();
                        }
                    }
                }
            })
            .catch(error => console.error('Error al marcar notificación como leída:', error));
        },

        // Manejar clic en una notificación
        handleNotificationClick: function(type, id) {
            // Implementar navegación basada en el tipo de notificación
            let url;

            switch (type) {
                case 'homologaciones':
                    url = `/homologaciones/${id}`;
                    break;
                case 'mensajes':
                    url = `/mensajes/${id}`;
                    break;
                case 'reportes':
                    url = `/reportes/${id}`;
                    break;
                default:
                    url = '/notificaciones';
            }

            window.location.href = url;
        },

        // Obtener notificaciones del servidor
        fetchNotifications: function() {
            const self = this;
            self.state.isLoading = true;

            fetch('/api/notifications/all', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                self.state.isLoading = false;

                if (data.success) {
                    // Guardar datos en el estado
                    self.state.homologaciones = data.homologaciones || [];
                    self.state.mensajes = data.mensajes || [];
                    self.state.reportes = data.reportes || [];

                    // Actualizar contadores
                    self.state.unreadCount.homologaciones = data.unread_counts.homologaciones || 0;
                    self.state.unreadCount.mensajes = data.unread_counts.mensajes || 0;
                    self.state.unreadCount.reportes = data.unread_counts.reportes || 0;

                    // Renderizar notificaciones
                    self.renderNotifications();
                    self.updateUnreadCounters();

                    // Animar campana si hay nuevas notificaciones
                    if (self.getTotalUnread() > 0) {
                        self.elements.bell.classList.add('has-new-notification');
                    } else {
                        self.elements.bell.classList.remove('has-new-notification');
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                self.state.isLoading = false;
                self.renderEmptyState('Error al cargar notificaciones. Intente de nuevo más tarde.');
            });
        },

        // Iniciar polling para actualizar notificaciones
        startPolling: function() {
            const self = this;
            // Actualizar cada 60 segundos
            setInterval(function() {
                self.fetchNotifications();
            }, 60000);
        },

        // Renderizar las notificaciones
        renderNotifications: function() {
            this.renderHomologaciones();
            this.renderMensajes();
            this.renderReportes();
        },

        // Renderizar notificaciones de homologaciones
        renderHomologaciones: function() {
            const container = this.elements.containers.homologaciones;
            container.innerHTML = '';

            if (this.state.homologaciones.length === 0) {
                container.innerHTML = `
                    <div class="notification-item empty-notification">
                        <p>No hay homologaciones pendientes</p>
                    </div>
                `;
                return;
            }

            this.state.homologaciones.forEach(item => {
                const html = `
                    <div class="notification-item ${item.unread ? 'unread' : ''}" data-id="${item.id}">
                        <div class="notification-icon ${this.getStatusClass(item.status)}">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="notification-content">
                            <h6 class="notification-title">${this.escapeHtml(item.title)}</h6>
                            <p class="notification-text">${this.escapeHtml(item.message)}</p>
                            <span class="notification-time">${this.formatTime(item.created_at)}</span>
                        </div>
                        ${item.unread ? '<span class="notification-badge"></span>' : ''}
                    </div>
                `;
                container.innerHTML += html;
            });
        },

        // Renderizar mensajes
        renderMensajes: function() {
            const container = this.elements.containers.mensajes;
            container.innerHTML = '';

            if (this.state.mensajes.length === 0) {
                container.innerHTML = `
                    <div class="notification-item empty-notification">
                        <p>No hay mensajes nuevos</p>
                    </div>
                `;
                return;
            }

            this.state.mensajes.forEach(item => {
                const html = `
                    <div class="notification-item ${item.unread ? 'unread' : ''}" data-id="${item.id}">
                        <div class="notification-icon primary">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="notification-content">
                            <h6 class="notification-title">${this.escapeHtml(item.sender)}</h6>
                            <p class="notification-text">${this.escapeHtml(item.message)}</p>
                            <span class="notification-time">${this.formatTime(item.created_at)}</span>
                        </div>
                        ${item.unread ? '<span class="notification-badge"></span>' : ''}
                    </div>
                `;
                container.innerHTML += html;
            });
        },

        // Renderizar reportes
        renderReportes: function() {
            const container = this.elements.containers.reportes;
            container.innerHTML = '';

            if (this.state.reportes.length === 0) {
                container.innerHTML = `
                    <div class="notification-item empty-notification">
                        <p>No hay reportes nuevos</p>
                    </div>
                `;
                return;
            }

            this.state.reportes.forEach(item => {
                const statusIcon = item.approved ? 'check-circle' : 'times-circle';
                const statusClass = item.approved ? 'success' : 'danger';
                const statusText = item.approved ? 'aprobó' : 'rechazó';

                const html = `
                    <div class="notification-item ${item.unread ? 'unread' : ''}" data-id="${item.id}">
                        <div class="notification-icon ${statusClass}">
                            <i class="fas fa-${statusIcon}"></i>
                        </div>
                        <div class="notification-content">
                            <h6 class="notification-title">Vicerrectoría ${statusText} la homologación</h6>
                            <p class="notification-text">${this.escapeHtml(item.message)}</p>
                            <span class="notification-time">${this.formatTime(item.created_at)}</span>
                        </div>
                        ${item.unread ? '<span class="notification-badge"></span>' : ''}
                    </div>
                `;
                container.innerHTML += html;
            });
        },

        // Actualizar contadores de no leídos
        updateUnreadCounters: function() {
            // Actualizar contadores individuales
            this.elements.counters.homologaciones.textContent = this.state.unreadCount.homologaciones;
            this.elements.counters.mensajes.textContent = this.state.unreadCount.mensajes;
            this.elements.counters.reportes.textContent = this.state.unreadCount.reportes;

            // Mostrar u ocultar badges
            this.toggleCountBadge('homologaciones', this.state.unreadCount.homologaciones);
            this.toggleCountBadge('mensajes', this.state.unreadCount.mensajes);
            this.toggleCountBadge('reportes', this.state.unreadCount.reportes);

            // Actualizar contador total
            const totalUnread = this.getTotalUnread();
            this.elements.count.textContent = totalUnread;

            if (totalUnread > 0) {
                this.elements.count.style.display = 'flex';
            } else {
                this.elements.count.style.display = 'none';
            }
        },

        // Mostrar u ocultar badges de conteo
        toggleCountBadge: function(type, count) {
            const badge = this.elements.counters[type];
            if (count > 0) {
                badge.classList.add('visible');
            } else {
                badge.classList.remove('visible');
            }
        },

        // Obtener el total de notificaciones no leídas
        getTotalUnread: function() {
            return this.state.unreadCount.homologaciones +
                   this.state.unreadCount.mensajes +
                   this.state.unreadCount.reportes;
        },

        // Obtener clase de estado para las notificaciones
        getStatusClass: function(status) {
            switch (status) {
                case 'pending': return 'warning';
                case 'approved': return 'success';
                case 'rejected': return 'danger';
                default: return 'primary';
            }
        },

        // Formatear tiempo para mostrar
        formatTime: function(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.round(diffMs / 60000);

            if (diffMins < 1) return 'Ahora mismo';
            if (diffMins < 60) return `Hace ${diffMins} min`;

            const diffHours = Math.floor(diffMins / 60);
            if (diffHours < 24) return `Hace ${diffHours} h`;

            const diffDays = Math.floor(diffHours / 24);
            if (diffDays < 7) return `Hace ${diffDays} d`;

            return date.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit'
            });
        }
</script>
</body>

</html>

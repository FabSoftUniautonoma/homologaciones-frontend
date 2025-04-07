// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {
    // Toggle del sidebar
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');

    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }

    // Validación de formularios
    const forms = document.querySelectorAll('.needs-validation');

    // Hacer que todos los campos requeridos muestren la validación
    const validateForm = function (form) {
        const formElements = form.querySelectorAll('input, select, textarea');

        formElements.forEach(function (element) {
            if (element.hasAttribute('required')) {
                element.addEventListener('blur', function () {
                    if (!element.checkValidity()) {
                        element.classList.add('is-invalid');
                    } else {
                        element.classList.remove('is-invalid');
                        element.classList.add('is-valid');
                    }
                });

                element.addEventListener('input', function () {
                    if (element.checkValidity()) {
                        element.classList.remove('is-invalid');
                        element.classList.add('is-valid');
                    }
                });
            }
        });
    };

    // Aplicar validación a todos los formularios
    forms.forEach(function (form) {
        validateForm(form);
    });

    // Mostrar modal de confirmación al guardar
    const btnGuardar = document.getElementById('btnGuardar');
    const btnGuardarInstitucion = document.getElementById('btnGuardarInstitucion');
    const btnGuardarPrograma = document.getElementById('btnGuardarPrograma');
    const saveRolePermissions = document.getElementById('saveRolePermissions');

    // Función para manejar el clic en cualquier botón de guardar
    const handleSaveButton = function (event) {
        event.preventDefault();

        // Buscar el formulario relacionado
        const form = event.target.closest('form');

        if (form) {
            // Validar formulario
            let isValid = true;
            const requiredElements = form.querySelectorAll('[required]');

            requiredElements.forEach(function (element) {
                if (!element.checkValidity()) {
                    element.classList.add('is-invalid');
                    isValid = false;
                }
            });

            if (isValid) {
                // Si es válido, mostrar modal de confirmación
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                confirmModal.show();

                // Después de cerrar el modal, se podría enviar el formulario
                document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function () {
                    // Aquí iría la lógica para enviar el formulario al backend
                    // Por ahora solo reseteamos para simular
                    form.reset();

                    // Eliminar clases de validación
                    const validatedElements = form.querySelectorAll('.is-valid, .is-invalid');
                    validatedElements.forEach(function (element) {
                        element.classList.remove('is-valid', 'is-invalid');
                    });
                });
            }
        }
    };

    // Asignar evento a botones de guardar
    if (btnGuardar) {
        btnGuardar.addEventListener('click', handleSaveButton);
    }

    if (btnGuardarInstitucion) {
        btnGuardarInstitucion.addEventListener('click', handleSaveButton);
    }

    if (btnGuardarPrograma) {
        btnGuardarPrograma.addEventListener('click', handleSaveButton);
    }

    if (saveRolePermissions) {
        saveRolePermissions.addEventListener('click', handleSaveButton);
    }

    // Manejar selección de filas en tablas
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(function (row) {
        row.addEventListener('click', function () {
            tableRows.forEach(r => r.classList.remove('table-active'));
            this.classList.add('table-active');
        });
    });

    // Inicializar los tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (tooltipTriggerList.length > 0) {
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    // Manejar eventos del modal de edición de roles
    const editRoleModal = document.getElementById('editRoleModal');
    if (editRoleModal) {
        editRoleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Verificar si el botón tiene el atributo data-role
            const role = button ? button.getAttribute('data-role') : null;
            if (!role) {
                console.error('El atributo data-role no está definido en el botón.');
                return;
            }

            const modalTitle = editRoleModal.querySelector('.modal-title');
            const roleIdInput = document.getElementById('roleId');

            // Actualizar el título del modal y el valor del input oculto
            modalTitle.textContent = 'Editar Permisos del Rol: ' + role.charAt(0).toUpperCase() + role.slice(1);
            roleIdInput.value = role;

            // Obtener los checkboxes dentro del modal
            const checkboxes = editRoleModal.querySelectorAll('input[type="checkbox"]');
            if (checkboxes.length === 0) {
                console.error('No se encontraron checkboxes en el modal.');
                return;
            }

            // Restablecer todos los checkboxes
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            // Simulación de permisos por rol
            if (role === 'administrador') {
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = true;
                });
            } else if (role === 'coordinador') {
                checkboxes[0].checked = true; // Solo un permiso para coordinador
            }
        });
    }
});
const datepickers = document.querySelectorAll('.datepicker');
if (datepickers.length > 0) {
    datepickers.forEach(function(element) {
        new bootstrap.Datepicker(element, {
            format: 'dd/mm/yyyy',
            language: 'es',
            autoclose: true
        });
    });
}

// Manejar paginación dinámica
const paginationLinks = document.querySelectorAll('.pagination .page-link');
paginationLinks.forEach(function(link) {
    link.addEventListener('click', function(event) {
        if (!this.parentElement.classList.contains('disabled')) {
            event.preventDefault();

            // Aquí se podría hacer una llamada AJAX para obtener los datos de la página correspondiente
            // Por ahora solo actualizamos la UI
            paginationLinks.forEach(l => l.parentElement.classList.remove('active'));

            if (!this.getAttribute('aria-label')) {
                this.parentElement.classList.add('active');
            }

            // Podríamos mostrar un indicador de carga mientras se obtienen los datos
            // const loadingIndicator = document.getElementById('loadingIndicator');
            // if (loadingIndicator) loadingIndicator.classList.remove('d-none');

            // Simular carga de datos
            setTimeout(function() {
                // if (loadingIndicator) loadingIndicator.classList.add('d-none');
                console.log('Datos de página cargados');
            }, 500);
        }
    });
});

// Inicializar componentes de selección múltiple
const multiSelects = document.querySelectorAll('.multiple-select');
if (multiSelects.length > 0 && typeof bootstrap.Multiselect !== 'undefined') {
    multiSelects.forEach(function(select) {
        new bootstrap.Multiselect(select, {
            includeSelectAllOption: true,
            buttonWidth: '100%',
            nonSelectedText: 'Seleccionar opciones',
            nSelectedText: 'seleccionados',
            allSelectedText: 'Todos seleccionados',
            selectAllText: 'Seleccionar todos'
        });
    });
}

// Manejo de notificaciones y alertas
const alertCloseButtons = document.querySelectorAll('.alert .close');
alertCloseButtons.forEach(function(button) {
    button.addEventListener('click', function() {
        const alert = this.closest('.alert');
        alert.classList.add('fade');
        setTimeout(function() {
            alert.style.display = 'none';
        }, 150);
    });
});

// Función para mostrar notificaciones temporales
window.showNotification = function(message, type = 'success') {
    const notificationArea = document.getElementById('notificationArea');
    if (notificationArea) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show`;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        notificationArea.appendChild(notification);

        // Auto-cerrar después de 5 segundos
        setTimeout(function() {
            notification.classList.remove('show');
            setTimeout(function() {
                notificationArea.removeChild(notification);
            }, 150);
        }, 5000);
    }
};


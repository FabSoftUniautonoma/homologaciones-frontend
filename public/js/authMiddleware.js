document.addEventListener('DOMContentLoaded', function() {
    // Verificar que AuthService está disponible
    if (typeof AuthService !== 'function') {
        console.error('Error: AuthService no está disponible');
        return;
    }

    // Crear instancia del servicio de autenticación
    const auth = new AuthService();

    // Si no hay token, ya se redirigió en el script inicial de la página
    if (!auth.getToken()) {
        return;
    }

    // Obtener usuario desde localStorage
    const user = auth.getUser();
    if (!user) {
        window.location.href = `${auth.getBaseRoute()}/auth/login`;
        return;
    }

    // Verificar si estamos en una página que requiere perfil completo
    const currentPath = window.location.pathname;

    // Si estamos en el formulario de homologación, verificar si el perfil está completo
    if (currentPath.includes('solicitudhomologacion')) {
        // Verificar si hay datos de usuario en localStorage para prellenar el formulario
        if (user) {
            // Prellenar campos del formulario con datos existentes
            precargarDatosFormulario(user);
        }

        // Verificar si hay campos sin llenar que sean obligatorios en step-1
        configurarEnvioFormulario();
    }
});

/**
 * Precarga los datos del usuario en el formulario
 * @param {Object} userData - Datos del usuario
 */
function precargarDatosFormulario(userData) {
    // Mapeo de campos del usuario a campos del formulario
    const camposFormulario = {
        'tipo_identificacion': document.getElementById('tipo_identificacion'),
        'numero_identificacion': document.getElementById('numero_identificacion'),
        'primer_nombre': document.getElementById('primer_nombre'),
        'segundo_nombre': document.getElementById('segundo_nombre'),
        'primer_apellido': document.getElementById('primer_apellido'),
        'segundo_apellido': document.getElementById('segundo_apellido'),
        'email': document.getElementById('email'),
        'telefono': document.getElementById('telefono'),
        'direccion': document.getElementById('direccion')
    };

    // Llenar campos si existen en el userData
    for (const [campo, elemento] of Object.entries(camposFormulario)) {
        if (elemento && userData[campo]) {
            elemento.value = userData[campo];
        }
    }

    // Si hay departamento seleccionado, cargar y seleccionar municipios
    if (userData.departamento_id && document.getElementById('departamento')) {
        document.getElementById('departamento').value = userData.departamento_id;

        // Ejecutar función de carga de municipios
        if (typeof updateMunicipios === 'function') {
            updateMunicipios();

            // Esperar a que carguen los municipios y seleccionar el correcto
            setTimeout(() => {
                if (userData.municipio_id && document.getElementById('municipio')) {
                    document.getElementById('municipio').value = userData.municipio_id;
                }
            }, 100);
        }
    }
}


/**
 * Configura el envío del formulario para guardar los datos del usuario
 */
function configurarEnvioFormulario() {
    // Reemplazar la función original validarFormularioStep1
    window.validarFormularioStep1Original = window.validarFormularioStep1;

    window.validarFormularioStep1 = function(step) {
        // Validar campos requeridos
        const camposRequeridos = [
            'tipo_identificacion', 'numero_identificacion', 'primer_nombre',
            'primer_apellido', 'email', 'telefono', 'direccion'
        ];

        let formValido = true;

        for (const campo of camposRequeridos) {
            const elemento = document.getElementById(campo);
            if (elemento && (!elemento.value || elemento.value.trim() === '')) {
                // Mostrar error
                const errorMsg = elemento.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.textContent = 'Este campo es requerido';
                }
                formValido = false;
            } else if (elemento) {
                // Limpiar mensaje de error
                const errorMsg = elemento.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.textContent = '';
                }
            }
        }

        // Validar email
        const emailInput = document.getElementById('email');
        if (emailInput && emailInput.value) {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(emailInput.value)) {
                const errorMsg = emailInput.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.textContent = 'Correo electrónico inválido';
                }
                formValido = false;
            }
        }

        // Si el formulario es válido, guardar datos y continuar
        if (formValido) {
            // Recopilar datos del formulario
            const userData = {};

            const elementosFormulario = [
                'tipo_identificacion', 'numero_identificacion', 'primer_nombre', 'segundo_nombre',
                'primer_apellido', 'segundo_apellido', 'email', 'telefono', 'direccion'
            ];

            // Añadir mapeos para departamento y municipio
            if (document.getElementById('departamento')) {
                userData.departamento_id = document.getElementById('departamento').value;
            }

            if (document.getElementById('municipio')) {
                userData.municipio_id = document.getElementById('municipio').value;
            }

            for (const campo of elementosFormulario) {
                const elemento = document.getElementById(campo);
                if (elemento) {
                    userData[campo] = elemento.value;
                }
            }

            // Obtener instancia de AuthService
            const auth = new AuthService();

            // Obtener ID del usuario actual
            const user = auth.getUser();

            if (user && user.id_usuario) {
                // Mostrar spinner o indicador de carga si está disponible
                const submitButton = document.querySelector('.next-button');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Guardando...';
                }

                // Actualizar perfil en el servidor
                auth.updateProfile(userData)
                    .then(response => {
                        console.log('Perfil actualizado:', response);

                        // Avanzar al siguiente paso
                        if (typeof changeStep === 'function') {
                            changeStep(step);
                        }
                    })
                    .catch(error => {
                        console.error('Error al actualizar perfil:', error);
                        alert('Error al guardar los datos. Por favor, inténtelo de nuevo.');
                    })
                    .finally(() => {
                        // Restaurar botón
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.textContent = 'Siguiente';
                        }
                    });
            } else {
                // Si no hay usuario autenticado, solo avanzar
                if (typeof changeStep === 'function') {
                    changeStep(step);
                }
            }
        }
    };
}

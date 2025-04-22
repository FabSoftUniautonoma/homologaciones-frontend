/**
 * Código JavaScript mejorado para el manejo de modales y validación
 */
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Obtener todos los elementos del formulario
    const form = document.getElementById('crearMateriaForm');
    const submitBtn = document.getElementById('submitBtn');
    const retryBtn = document.getElementById('retryBtn');
    const inputs = form.querySelectorAll('input, select, textarea');

    // Configuración de los modales - permitir cerrar
    const successModal = new bootstrap.Modal(document.getElementById('successModal'), {
        backdrop: true,  // Permitir cerrar al hacer clic fuera
        keyboard: true   // Permitir cerrar con ESC
    });

    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'), {
        backdrop: true,  // Permitir cerrar al hacer clic fuera
        keyboard: true   // Permitir cerrar con ESC
    });

    // Configurar contador de caracteres para el textarea de pensum
    const pensumTextarea = document.getElementById('pensum');
    const maxChars = 500; // Máximo de caracteres permitidos

    // Resto del código...
}); // Máximo de caracteres permitidos

    // Función para actualizar el contador
    function updateCounter() {
        if (!pensumTextarea) return;

        const charCount = pensumTextarea.value.length;
        const counterEl = document.getElementById('pensum-char-counter');

        if (!counterEl) return;

        counterEl.textContent = `${charCount}/${maxChars} caracteres`;

        // Cambiar color según la cantidad de caracteres
        counterEl.className = 'char-counter';

        if (charCount > maxChars * 0.8 && charCount <= maxChars) {
            counterEl.classList.add('warning');
        } else if (charCount > maxChars) {
            counterEl.classList.add('danger');
        }
    }

    // Actualizar el contador cuando el usuario escriba
    if (pensumTextarea) {
        pensumTextarea.addEventListener('input', updateCounter);
        // Actualizar en la carga inicial
        updateCounter();
    }

    // Añadir animación a los campos del formulario al cargar la página
    if (typeof anime !== 'undefined') {
        anime({
            targets: '.card-body .row',
            translateY: [20, 0],
            opacity: [0, 1],
            delay: anime.stagger(87),
            easing: 'easeOutQuad',
            duration: 800
        });
    }

    // Validar todos los campos cuando pierden el foco
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });

        // También validar al cambiar (para cualquier campo)
        input.addEventListener('change', function() {
            validateField(this);
        });

        // Validación en tiempo real para todos los campos
        input.addEventListener('input', function() {
            validateField(this);
        });
    });

    // Función para validar un campo individual
    function validateField(field) {
        let isValid = true;
        const fieldName = field.getAttribute('name');
        const errorElement = form.querySelector(`.error-${fieldName}`);

        if (!errorElement) return true; // Si no hay elemento de error, omitimos la validación

        // Validaciones comunes para todos los campos
        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            errorElement.textContent = `Este campo es obligatorio`;
        } else {
            // Validaciones específicas según el tipo de campo
            switch (fieldName) {
                case 'nombre':
                    // Validar que solo contenga letras, espacios y algunos caracteres especiales
                    const nombreRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s\-\.,'()]+$/;
                    if (!nombreRegex.test(field.value.trim())) {
                        isValid = false;
                        errorElement.textContent = 'El nombre debe contener solo letras y caracteres permitidos';
                    }
                    break;

                case 'creditos':
                    const creditosValue = parseInt(field.value);
                    const min = parseInt(field.getAttribute('min'));
                    const max = parseInt(field.getAttribute('max'));

                    if (isNaN(creditosValue) || field.value.trim() === '') {
                        isValid = false;
                        errorElement.textContent = 'Debe ingresar un número válido';
                    } else if (creditosValue < min) {
                        isValid = false;
                        errorElement.textContent = `El valor mínimo permitido es ${min}`;
                    } else if (max && creditosValue > max) {
                        isValid = false;
                        errorElement.textContent = `El valor máximo permitido es ${max}`;
                    }
                    break;

                case 'semestre':
                    const semestreValue = parseInt(field.value);
                    const semestreMin = parseInt(field.getAttribute('min'));
                    const semestreMax = parseInt(field.getAttribute('max'));

                    if (isNaN(semestreValue) || field.value.trim() === '') {
                        isValid = false;
                        errorElement.textContent = 'Debe ingresar un número válido';
                    } else if (semestreValue < semestreMin) {
                        isValid = false;
                        errorElement.textContent = `El valor mínimo permitido es ${semestreMin}`;
                    } else if (semestreMax && semestreValue > semestreMax) {
                        isValid = false;
                        errorElement.textContent = `El valor máximo permitido es ${semestreMax}`;
                    }
                    break;

                case 'tipo':
                    if (field.value !== 'obligatoria' && field.value !== 'electiva') {
                        isValid = false;
                        errorElement.textContent = 'Debe seleccionar un tipo válido';
                    }
                    break;

                case 'pensum':
                    // Validación más flexible para el campo de texto largo
                    if (field.value.trim().length < 10) {
                        isValid = false;
                        errorElement.textContent = 'La descripción del pensum debe tener al menos 10 caracteres';
                    } else if (field.value.trim().length > maxChars) {
                        isValid = false;
                        errorElement.textContent = `La descripción no debe exceder los ${maxChars} caracteres`;
                    } else {
                        // Comprobar que no contenga caracteres extraños o potencialmente maliciosos
                        const pensumRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-\.,'()\r\n:;]+$/;
                        if (!pensumRegex.test(field.value.trim())) {
                            isValid = false;
                            errorElement.textContent = 'El pensum contiene caracteres no permitidos';
                        }
                    }
                    break;
            }
        }

        // Aplicar clases de validación
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
        }

        return isValid;
    }

    // Validar todo el formulario
    function validateForm() {
        let isValid = true;

        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    // Efecto al pasar el mouse sobre los campos
    inputs.forEach(input => {
        input.addEventListener('mouseenter', function() {
            if (!this.classList.contains('is-invalid') && !this.classList.contains('is-valid')) {
                this.style.borderColor = '#80bdff';
                this.style.boxShadow = '0 0 0 0.2rem rgba(0, 123, 255, 0.25)';
            }
        });

        input.addEventListener('mouseleave', function() {
            if (!this.classList.contains('is-invalid') && !this.classList.contains('is-valid')) {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
    });

    // Manejar el envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validar todos los campos antes de enviar
        if (!validateForm()) {
            document.getElementById('errorDetails').textContent = 'Por favor, completa correctamente todos los campos señalados.';
            errorModal.show(); // Mostrar el modal de error

            // Enfocar el primer campo con error
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }

            return;
        }

        // Mostrar animación de carga en el botón
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Guardando...';

        // Simulamos la petición AJAX (en un entorno real, esto sería un fetch o $.ajax)
        simulateServerRequest()
            .then(response => {
                console.log('Éxito: Mostrando modal de éxito');

                // Resetear el formulario y mostrar mensaje de éxito
                form.reset();
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });

                // Restaurar el botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Materia';

                // Mostrar el modal de éxito
                successModal.show();
            })
            .catch(error => {
                console.error('Error:', error);

                // Restaurar el botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Guardar Materia';

                // Mostrar mensaje de error
                document.getElementById('errorDetails').textContent = error.message || 'Ha ocurrido un error al intentar guardar la materia.';

                // Mostrar el modal de error
                errorModal.show();
            });
    });

    // Manejador para el botón "Intentar nuevamente"
    if (retryBtn) {
        retryBtn.addEventListener('click', function() {
            // Enfocar en el primer campo con error
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            } else {
                form.querySelector('input, select, textarea').focus();
            }
        });
    }

    // Función que simula una petición al servidor
    function simulateServerRequest() {
        return new Promise((resolve, reject) => {
            // Simulamos un tiempo de procesamiento
            setTimeout(() => {
                // Simulamos una respuesta exitosa (90% de las veces)
                const isSuccess = Math.random() > 0.1;

                if (isSuccess) {
                    resolve({ success: true, message: 'Materia guardada exitosamente' });
                } else {
                    // Simulamos un error del servidor
                    reject({
                        success: false,
                        message: 'Error al conectar con el servidor. Intente nuevamente.'
                    });
                }
            }, 1500); // Simulamos un retraso de 1.5 segundos
        });
    }

    // Función para animar elementos cuando entran en el viewport
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.animate-on-scroll');

        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('visible');
            }
        });
    };

    // Ejecutar animación al hacer scroll
    window.addEventListener('scroll', animateOnScroll);

    // Ejecutar una vez al cargar para elementos que ya están visibles
    animateOnScroll();

    // Agregar efecto de confirmación visual al guardar
    const saveConfirmation = function() {
        if (typeof anime !== 'undefined') {
            anime({
                targets: '.card',
                translateY: [-10, 0],
                scale: [0.98, 1],
                opacity: [0.8, 1],
                duration: 500,
                easing: 'easeOutElastic(1, .5)'
            });
        }
    };

    // Aplicar efecto cuando se muestra el modal de éxito
    const successModalElement = document.getElementById('successModal');
    if (successModalElement) {
        successModalElement.addEventListener('shown.bs.modal', saveConfirmation);
    }

    // Verificar que los modales estén correctamente inicializados
    console.log('Modales inicializados:', {
        success: successModal,
        error: errorModal
    });


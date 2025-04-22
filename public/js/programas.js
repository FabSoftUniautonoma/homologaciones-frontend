// Archivo: public/js/programa-validation.js
document.addEventListener('DOMContentLoaded', function() {
    const programaForm = document.getElementById('programaForm');

    if (programaForm) {
        // Inicializar variables para modales
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

        // Validación en tiempo real para el campo nombre (solo letras, espacios y algunos caracteres permitidos)
        const nombreInput = document.getElementById('nombre');
        if (nombreInput) {
            nombreInput.addEventListener('input', function() {
                validateNombreField(this);
            });
        }

        // Validación en tiempo real para el código SNIES (solo números)
        const codigoSniesInput = document.getElementById('codigo_snies');
        if (codigoSniesInput) {
            codigoSniesInput.addEventListener('input', function() {
                validateCodigoSniesField(this);
            });
        }

        // Validación de todos los campos al enviar
        programaForm.addEventListener('submit', function(event) {
            event.preventDefault();

            let isValid = true;

            // Validar nombre (solo letras y espacios)
            if (!validateNombreField(nombreInput)) {
                isValid = false;
            }

            // Validar código SNIES (solo números)
            if (!validateCodigoSniesField(codigoSniesInput)) {
                isValid = false;
            }

            // Validar campos de selección
            const selectFields = ['facultad_id', 'tipo_formacion', 'tipo', 'metodologia'];
            selectFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Validar campos numéricos
            const numericFields = ['total_creditos', 'horas_presenciales', 'duracion'];
            numericFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const value = field.value.trim();
                const min = parseInt(field.getAttribute('min') || 0);

                if (!value || isNaN(value) || parseInt(value) < min) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Si todo es válido, simular el envío exitoso
            if (isValid) {
                // Simulación de guardado exitoso
                // En un caso real, aquí enviarías los datos a través de fetch o axios

                // Uso de setTimeout para simular un breve retardo de procesamiento
                setTimeout(() => {
                    // Simular una probabilidad alta de éxito (90%)
                    const success = Math.random() < 0.9;

                    if (success) {
                        // Mostrar modal de éxito
                        successModal.show();

                        // Opcional: Resetear el formulario después de un tiempo
                        setTimeout(() => {
                            programaForm.reset();
                        }, 1000);
                    } else {
                        // Mostrar modal de error
                        document.getElementById('errorMessage').textContent =
                            'Ha ocurrido un error al procesar la solicitud. Por favor intente nuevamente.';
                        errorModal.show();
                    }
                }, 800);
            } else {
                // Si hay errores, mostrar mensaje en el modal de error
                document.getElementById('errorMessage').textContent =
                    'Por favor, corrija los errores en el formulario antes de continuar.';
                errorModal.show();
            }
        });

        // Limpiar validación al cambiar los campos
        const allFields = document.querySelectorAll('input, select');
        allFields.forEach(field => {
            if (field.id !== 'nombre' && field.id !== 'codigo_snies') {
                field.addEventListener('change', function() {
                    this.classList.remove('is-invalid');
                });
            }
        });
    }

    // Función para validar el campo de nombre
    function validateNombreField(field) {
        if (!field) return true;

        const value = field.value.trim();
        // Expresión regular para permitir letras, espacios, acentos, guiones, puntos y paréntesis
        const nameRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-.()\u00C0-\u00FF]+$/;

        if (!value || !nameRegex.test(value)) {
            field.classList.add('is-invalid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            return true;
        }
    }

    // Función para validar el campo de código SNIES
    function validateCodigoSniesField(field) {
        if (!field) return true;

        const value = field.value.trim();
        // Expresión regular para permitir solo números
        const sniesRegex = /^[0-9]+$/;

        if (!value || !sniesRegex.test(value)) {
            field.classList.add('is-invalid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            return true;
        }
    }
});


    document.addEventListener('DOMContentLoaded', function () {
        // URLs generadas desde Blade
    

        // Cambio de estado
        const selectEstado = document.getElementById('estado');
        if (selectEstado) {
            selectEstado.addEventListener('change', function () {
                const badge = document.querySelector('.badge');
                const estado = this.value;

                badge.className = 'badge badge-' + getEstadoClass(estado);
                badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            });
        }

        // Botón: Verificar Documentos
        const btnVerificar = document.getElementById('verificarDocumentos');
        if (btnVerificar) {
            btnVerificar.addEventListener('click', function () {
                window.location.href = verificarDocumentosUrl;
            });
        }

        // Botón: Iniciar Proceso
        const btnIniciar = document.getElementById('iniciarProceso');
        if (btnIniciar) {
            btnIniciar.addEventListener('click', function () {
                if (confirm('¿Está seguro de iniciar el proceso de homologación?')) {
                    fetch(iniciarProcesoUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Proceso iniciado correctamente');
                            window.location.reload();
                        } else {
                            alert('Error al iniciar el proceso: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Ocurrió un error al iniciar el proceso.');
                        console.error(error);
                    });
                }
            });
        }

        // Botón: Guardar Cambios
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const confirmacion = confirm('¿Deseas guardar los cambios realizados?');
                if (!confirmacion) {
                    e.preventDefault();
                }
            });
        }

        // Función para definir estilos de estado
        function getEstadoClass(estado) {
            switch (estado.toLowerCase()) {
                case 'pendiente': return 'warning';
                case 'aprobado': return 'success';
                case 'rechazado': return 'danger';
                case 'en proceso': return 'info';
                default: return 'secondary';
            }
        }
    });

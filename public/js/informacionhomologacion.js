    const csrfToken = '{{ csrf_token() }}';
    const iniciarProcesoUrl = '{{ route("homologacion.iniciarproceso", ["id" => $id]) }}';
    const redireccionProcesoUrl = '{{ route("homologacion.procesohomologacion", ["id" => $id]) }}';
    const verificarDocumentosUrl = '{{ route("homologacion.documentos", ["id" => $id]) }}';
    const rutaEsc = '{{ route("admin.homologacionescoordinador.coordinador") }}';

    document.addEventListener('DOMContentLoaded', function () {
        // Escape redirection
        document.addEventListener('keydown', function (event) {
            if (event.key === "Escape") {
                window.location.href = rutaEsc;
            }
        });

        // Cambiar badge del estado
        const selectEstado = document.getElementById('estado');
        if (selectEstado) {
            selectEstado.addEventListener('change', function () {
                const badge = document.querySelector('.badge');
                const estado = this.value;

                badge.className = 'badge badge-' + getEstadoClass(estado);
                badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            });
        }

        // Verificar Documentos
        const btnVerificar = document.getElementById('verificarDocumentos');
        if (btnVerificar) {
            btnVerificar.addEventListener('click', function () {
                window.location.href = verificarDocumentosUrl;
            });
        }

        // Iniciar Proceso
        document.addEventListener('DOMContentLoaded', function () {
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
                                window.location.href = redireccionProcesoUrl;
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
        });
        document.addEventListener('DOMContentLoaded', function () {
            const btnIniciar = document.getElementById('iniciarProceso');

            const csrfToken = '{{ csrf_token() }}';
            const iniciarProcesoUrl = '{{ route("homologacion.iniciarproceso", ["id" => $homologacion->codigo]) }}';
            const redireccionProcesoUrl = '{{ route("homologacion.procesohomologacion", ["id" => $homologacion->codigo]) }}';

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
                                window.location.href = redireccionProcesoUrl;
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
        });
        // Confirmar envío de formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const confirmacion = confirm('¿Deseas guardar los cambios realizados?');
                if (!confirmacion) {
                    e.preventDefault();
                }
            });
        }

        // Clase de estilo según estado
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

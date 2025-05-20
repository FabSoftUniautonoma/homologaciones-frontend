
        document.addEventListener('DOMContentLoaded', function() {
            const estadoDisplay = document.getElementById('estadoDisplay');
            const btnIniciarProceso = document.getElementById('btnIniciarProceso');
            const estadoActual = document.getElementById('estadoActual').value;
            const rutaEsc = "{{ route('admin.homologacionescoordinador.index') }}";
            const rutaProceso =
                "{{ route('admin.homologacionescoordinador.procesohomologacion', $solicitud['numero_radicado']) }}";
            const radicado = document.getElementById('radicado').value;
            const solicitudId = document.getElementById('id_solicitud').value;
            const csrfToken = document.getElementById('csrf_token').value;

            // Create the base URL for updates - FIXED
            const baseUrl = "{{ url('/coordinador/admin/homologacionescoordinador/actualizar-estado') }}";

            // API URL for direct calls
            const apiBaseUrl = "{{ config('services.api.url', 'http://127.0.0.1:8000/api') }}";

            // For debugging
            console.log('Número de radicado:', radicado);
            console.log('ID solicitud:', solicitudId);
            console.log('Estado actual:', estadoActual);
            console.log('Base URL para actualizaciones:', baseUrl);
            console.log('API Base URL:', apiBaseUrl);

            // Escape redirection
            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    window.location.href = rutaEsc;
                }
            });

            // Iniciar Proceso button handler
            if (btnIniciarProceso) {
                btnIniciarProceso.addEventListener('click', function() {
                    // Get the current state
                    const currentState = document.querySelector('.alert .fw-bold').textContent.trim()
                        .toLowerCase();

                    // If the current state is "radicado", update it to "en revisión" first
                    if (currentState === 'radicado') {
                        // Show a loading indicator
                        btnIniciarProceso.innerHTML =
                            '<i class="fas fa-circle-notch fa-spin me-2"></i> Actualizando...';
                        btnIniciarProceso.disabled = true;

                        // Update the UI first to provide immediate feedback
                        updateStatusUI(estadoDisplay, 'En revisión');
                        document.getElementById('estadoActualText').textContent = 'En revisión';
                        document.getElementById('estadoActual').value = 'En revisión';

                        // Intentar actualizar el estado
                        actualizarEstado(function(success) {
                            if (success) {
                                // Show notification
                                showNotification('Estado actualizado a "En revisión"', 'success');

                                // After updating, navigate to the process page
                                setTimeout(() => {
                                    window.location.href = rutaProceso;
                                }, 1000);
                            } else {
                                showNotification(
                                    'Error al actualizar estado, redirigiendo de todos modos...',
                                    'warning');
                                setTimeout(() => {
                                    window.location.href = rutaProceso;
                                }, 1500);
                            }
                        });
                    } else {
                        // If it's already in another state, just go to the process page
                        window.location.href = rutaProceso;
                    }
                });
            }

            // Function to update estado using multiple methods in order
            function actualizarEstado(callback) {
                // Check if we have necessary identifiers
                if (!solicitudId && !radicado) {
                    console.error('Error: Faltan identificadores para la solicitud');
                    showNotification('Error: Datos insuficientes para actualizar', 'danger');
                    callback(false);
                    return;
                }

                // Generate update URLs - FIXED
                const updateUrlWithId = `${baseUrl}/${solicitudId}`;
                const updateUrlWithRadicado = `${baseUrl}/${radicado}`;

                console.log('URL de actualización con ID:', updateUrlWithId);
                console.log('URL de actualización con radicado:', updateUrlWithRadicado);

                // Try direct API call first
                updateDirectAPI()
                    .then(() => {
                        callback(true);
                    })
                    .catch(error => {
                        console.warn('Actualización API directa falló:', error);

                        // Try with form data as fallback
                        updateWithFormData()
                            .then(() => {
                                callback(true);
                            })
                            .catch(error => {
                                console.warn('Actualización con form data falló:', error);

                                // Try with JSON data as last resort
                                updateWithJsonData()
                                    .then(() => {
                                        callback(true);
                                    })
                                    .catch(error => {
                                        console.error('Actualización con JSON data falló:', error);
                                        callback(false);
                                    });
                            });
                    });

                // NEW METHOD: Direct API call
                function updateDirectAPI() {
                    console.log('Intentando actualización directa con API...');

                    // Use the API endpoint directly
                    const apiUrl = `${apiBaseUrl}/solicitudes/${solicitudId}/estado`;

                    console.log('URL API:', apiUrl);

                    return fetch(apiUrl, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                estado: 'En revisión'
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    try {
                                        const json = JSON.parse(text);
                                        console.error('Detalle del error API:', json);
                                        throw new Error(
                                            `Error HTTP ${response.status}: ${json.message || 'Sin detalles'}`
                                            );
                                    } catch (e) {
                                        console.error('Respuesta API no es JSON:', text);
                                        throw new Error(
                                            `Error HTTP ${response.status}: ${text.substring(0, 100)}`
                                            );
                                    }
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Actualización con API directa exitosa:', data);
                            return data;
                        });
                }

                // Method using traditional form data
                function updateWithFormData() {
                    console.log('Intentando actualización con form data...');

                    // Create form data
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('estado', 'En revisión');
                    formData.append('_method', 'POST'); // Force POST method

                    // Use the identifier with preference for ID
                    const updateUrl = solicitudId ? updateUrlWithId : updateUrlWithRadicado;

                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: updateUrl,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log('Actualización con form data exitosa:', response);
                                resolve(response);
                            },
                            error: function(xhr, status, error) {
                                console.error('Error en actualización con form data:', error);
                                console.error('Estado HTTP:', xhr.status);
                                console.error('Respuesta:', xhr.responseText);
                                reject(new Error(`Error ${xhr.status}: ${error}`));
                            }
                        });
                    });
                }

                // Method using JSON data
                function updateWithJsonData() {
                    console.log('Intentando actualización con JSON data...');

                    // Use the identifier with preference for ID
                    const updateUrl = solicitudId ? updateUrlWithId : updateUrlWithRadicado;

                    return fetch(updateUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                _token: csrfToken,
                                estado: 'En revisión'
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    try {
                                        const json = JSON.parse(text);
                                        console.error('Detalle del error:', json);
                                        throw new Error(
                                            `Error HTTP ${response.status}: ${json.message || 'Sin detalles'}`
                                            );
                                    } catch (e) {
                                        console.error('Respuesta no es JSON:', text);
                                        throw new Error(
                                            `Error HTTP ${response.status}: ${text.substring(0, 100)}`
                                            );
                                    }
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Actualización con JSON exitosa:', data);
                            return data;
                        });
                }
            }

            // Update the status UI (rest of the code remains the same)
            function updateStatusUI(element, estado) {
                // Remove current status classes
                element.classList.remove('alert-success', 'alert-danger', 'alert-primary', 'alert-warning',
                    'alert-info');

                // Update icon and class based on status
                let statusClass, statusIcon;

                switch (estado.toLowerCase()) {
                    case 'aprobado':
                    case 'aprobada':
                        statusClass = 'success';
                        statusIcon = 'check-circle';
                        break;
                    case 'rechazado':
                    case 'rechazada':
                        statusClass = 'danger';
                        statusIcon = 'times-circle';
                        break;
                    case 'en revisión':
                        statusClass = 'info';
                        statusIcon = 'search';
                        break;
                    case 'radicado':
                        statusClass = 'warning';
                        statusIcon = 'file-alt';
                        break;
                    default:
                        statusClass = 'primary';
                        statusIcon = 'clock';
                }

                element.classList.add(`alert-${statusClass}`);

                // Update icon and text
                element.innerHTML = `
            <i class="fas fa-${statusIcon} me-2"></i>
            <span class="fw-bold">${estado.charAt(0).toUpperCase() + estado.slice(1)}</span>
        `;
            }

            // Show notification function (remains the same)
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                notification.style.top = '20px';
                notification.style.right = '20px';
                notification.style.zIndex = '9999';
                notification.style.maxWidth = '400px';
                notification.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';

                notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

                document.body.appendChild(notification);

                // Auto close after 5 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 150);
                }, 5000);
            }
        });


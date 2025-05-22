@extends('admin.layouts.appcoordinacion')

@section('title', 'Homologaciones')

@section('content')
    <div class="container-fluid py-4">
        <!-- Encabezado con Número de Homologación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body bg-gradient-primary text-white rounded-3 position-relative">
                        <h4 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-file-alt me-2"></i>
                            Homologación #{{ $solicitud['numero_radicado'] ?? 'No disponible' }}
                        </h4>
                        <a href="{{ route('admin.homologacionescoordinador.index') }}"
                            class="btn btn-white shadow position-absolute"
                            style="top: 0; right: 0; margin: 10px; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center;">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Panel Izquierdo: Información Personal y Académica -->
            <div class="col-lg-8">
                <!-- Información Personal -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="fas fa-user-circle me-2"></i> Información Personal
                        </h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Tipo de identificación</label>
                            <p class="mb-0 fw-bold">{{ $usuario['tipo_identificacion'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Número de identificación</label>
                            <p class="mb-0 fw-bold">{{ $usuario['numero_identificacion'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nombres completos</label>
                            <p class="mb-0 fw-bold">
                                {{ $usuario['primer_nombre'] ?? '' }}
                                {{ $usuario['segundo_nombre'] ?? '' }}
                                {{ $usuario['primer_apellido'] ?? '' }}
                                {{ $usuario['segundo_apellido'] ?? '' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Correo electrónico</label>
                            <p class="mb-0 fw-bold">{{ $usuario['email'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Teléfono</label>
                            <p class="mb-0 fw-bold">{{ $solicitud['telefono'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Nacionalidad</label>
                            <p class="mb-0 fw-bold">{{ $usuario['pais'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Departamento</label>
                            <p class="mb-0 fw-bold">{{ $usuario['departamento'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Municipio</label>
                            <p class="mb-0 fw-bold">{{ $usuario['municipio'] ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="fas fa-graduation-cap me-2"></i> Información Académica
                        </h5>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Instituto de origen</label>
                            <p class="mb-0 fw-bold">{{ $usuario['institucion_origen'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Programa de destino</label>
                            <p class="mb-0 fw-bold">{{ $solicitud['programa_destino_nombre'] ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Fecha de solicitud</label>
                            @if (!empty($solicitud['fecha_solicitud']))
                                <p class="mb-0 fw-bold">
                                    {{ \Carbon\Carbon::parse($solicitud['fecha_solicitud'])->format('d/m/Y') }}</p>
                            @else
                                <p class="mb-0 fw-bold">No disponible</p>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small text-uppercase">Estado de la solicitud</label>
                            <p class="mb-0 fw-bold" id="estadoActualText">{{ $solicitud['estado'] ?? 'No disponible' }}</p>
                            <input type="hidden" id="estadoActual" value="{{ $solicitud['estado'] ?? 'No disponible' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Gestión del Proceso -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0 text-primary">
                            <i class="fas fa-tasks me-2"></i> Gestión del Proceso
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="text-muted small text-uppercase mb-2">Estado actual</label>

                            @php
                                $estado = strtolower($solicitud['estado'] ?? 'pendiente');
                                if ($estado == 'aprobado' || $estado == 'aprobada') {
                                    $estado_class = 'success';
                                    $estado_icon = 'check-circle';
                                } elseif ($estado == 'rechazado' || $estado == 'rechazada') {
                                    $estado_class = 'danger';
                                    $estado_icon = 'times-circle';
                                } elseif ($estado == 'en revisión') {
                                    $estado_class = 'info';
                                    $estado_icon = 'search';
                                } elseif ($estado == 'radicado') {
                                    $estado_class = 'warning';
                                    $estado_icon = 'file-alt';
                                } elseif ($estado == 'cerrado') {
                                    $estado_class = 'secondary';
                                    $estado_icon = 'lock';
                                } else {
                                    $estado_class = 'primary';
                                    $estado_icon = 'clock';
                                }
                            @endphp

                            <div class="alert alert-{{ $estado_class }} text-center mb-4 border-0" id="estadoDisplay">
                                <i class="fas fa-{{ $estado_icon }} me-2"></i>
                                <span class="fw-bold">{{ ucfirst($solicitud['estado'] ?? 'Pendiente') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('admin.homologacionescoordinador.documentos', $solicitud['numero_radicado']) }}"
                            class="btn btn-primary w-100 mb-3 py-3 shadow-sm">
                            <i class="fas fa-file-pdf me-2"></i> Verificar Documentos
                        </a>

                        <button type="button" id="btnIniciarProceso" class="btn btn-success w-100 mb-3 py-3 shadow-sm">
                            <i class="fas fa-play-circle me-2"></i> Iniciar Proceso
                        </button>

                        <!-- Botón Cerrar Homologación corregido -->
                        <button type="button" id="btn-cerrar-homologacion" class="btn btn-primary w-100 mb-3 py-3 shadow-sm"
                            style="background-color: #6c8ebf; border-color: #6c8ebf;">
                            <i class="fas fa-times-circle me-2"></i> Cerrar Homologación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for storing all solicitud data -->
    <div style="display: none;">
        <input type="hidden" id="id_solicitud" value="{{ $solicitud['id_solicitud'] ?? '' }}">
        <input type="hidden" id="usuario_id" value="{{ $solicitud['usuario_id'] ?? ($solicitud['id_usuario'] ?? '') }}">
        <input type="hidden" id="programa_destino_id" value="{{ $solicitud['programa_destino_id'] ?? '' }}">
        <input type="hidden" id="finalizo_estudios" value="{{ $solicitud['finalizo_estudios'] ?? 'No' }}">
        <input type="hidden" id="fecha_finalizacion_estudios"
            value="{{ $solicitud['fecha_finalizacion_estudios'] ?? '' }}">
        <input type="hidden" id="fecha_ultimo_semestre_cursado"
            value="{{ $solicitud['fecha_ultimo_semestre_cursado'] ?? '' }}">
        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
        <input type="hidden" id="radicado" value="{{ $solicitud['numero_radicado'] ?? '' }}">
    </div>

    <!-- Incluir SweetAlert2 antes de nuestros scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

     // Primer script: Iniciar Proceso

    document.addEventListener('DOMContentLoaded', function() {
    // === Inicialización de variables y elementos ===
    const btnCerrarHomologacion = document.getElementById('btn-cerrar-homologacion');
    const btnIniciarProceso = document.getElementById('btnIniciarProceso');
    const estadoDisplay = document.getElementById('estadoDisplay');
    const estadoActualText = document.getElementById('estadoActualText');
    const estadoActual = document.getElementById('estadoActual').value;
    const rutaEsc = "{{ route('admin.homologacionescoordinador.index') }}";
    const rutaProceso = "{{ route('admin.homologacionescoordinador.procesohomologacion', $solicitud['numero_radicado']) }}";
    const radicado = document.getElementById('radicado').value;
    const solicitudId = document.getElementById('id_solicitud').value;
    const csrfToken = document.getElementById('csrf_token').value;

    // Base URL for updates - USAMOS LA MISMA RUTA QUE FUNCIONA
    const baseUrl = "{{ url('/coordinador/admin/homologacionescoordinador/actualizar-estado') }}";
    const apiBaseUrl = "{{ config('services.api.url', 'http://127.0.0.1:8000/api') }}";

    console.log('Número de radicado:', radicado);
    console.log('ID solicitud:', solicitudId);
    console.log('Estado actual:', estadoActual);
    console.log('URL base para actualizaciones:', baseUrl);
    console.log('URL base de API:', apiBaseUrl);

    // Escape key redirects to index
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            window.location.href = rutaEsc;
        }
    });

    // === INICIAR PROCESO BUTTON ===
    if (btnIniciarProceso) {
        btnIniciarProceso.addEventListener('click', function() {
            // Get current state
            const currentState = document.querySelector('.alert .fw-bold').textContent.trim().toLowerCase();

            // If current state is "radicado", first update to "en revisión"
            if (currentState === 'radicado') {
                // Show loading indicator
                btnIniciarProceso.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Actualizando...';
                btnIniciarProceso.disabled = true;

                // Update UI immediately for feedback
                updateStatusUI(estadoDisplay, 'En revisión');
                estadoActualText.textContent = 'En revisión';
                document.getElementById('estadoActual').value = 'En revisión';

                // Try to update the state
                actualizarEstado('En revisión', function(success) {
                    if (success) {
                        // Show success notification
                        showNotification('Estado actualizado a "En revisión"', 'success');

                        // After updating, navigate to process page
                        setTimeout(() => {
                            window.location.href = rutaProceso;
                        }, 1000);
                    } else {
                        showNotification('Error al actualizar estado, redirigiendo de todos modos...', 'warning');
                        setTimeout(() => {
                            window.location.href = rutaProceso;
                        }, 1500);
                    }
                });
            } else {
                // If already in another state, just go to process page
                window.location.href = rutaProceso;
            }
        });
    }

    // === CERRAR HOMOLOGACIÓN BUTTON ===
    // UTILIZA LA MISMA LÓGICA DE "INICIAR PROCESO" PERO CAMBIA EL ESTADO
    if (btnCerrarHomologacion) {
        btnCerrarHomologacion.addEventListener('click', function() {
            console.log('Botón Cerrar Homologación clickeado');

            // Get current state
            const currentState = document.querySelector('.alert .fw-bold').textContent.trim().toLowerCase();
            console.log('Estado actual:', currentState);

            // Solo permitir cerrar si el estado es "aprobado" o "rechazado"
            if (currentState === 'aprobado' || currentState === 'aprobada' ||
                currentState === 'rechazado' || currentState === 'rechazada') {

                // Mostrar confirmación usando SweetAlert2
                Swal.fire({
                    title: '¿Está seguro?',
                    text: `¿Desea cerrar esta homologación? Esta acción cambiará el estado a "Cerrado".`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cerrar homologación',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Confirmación aceptada, procediendo a cerrar');

                        // Mostrar indicador de carga
                        btnCerrarHomologacion.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Cerrando...';
                        btnCerrarHomologacion.disabled = true;

                        // Actualizar UI primero para retroalimentación inmediata
                        updateStatusUI(estadoDisplay, 'Cerrado');
                        estadoActualText.textContent = 'Cerrado';
                        document.getElementById('estadoActual').value = 'Cerrado';

                        // Intentar actualizar el estado - USAR EXACTAMENTE LA MISMA FUNCIÓN QUE INICIAR PROCESO
                        actualizarEstado('Cerrado', function(success) {
                            if (success) {
                                // Mostrar notificación
                                showNotification('Homologación cerrada exitosamente', 'success');

                                // Deshabilitar el botón permanentemente
                                btnCerrarHomologacion.disabled = true;
                                btnCerrarHomologacion.innerHTML = '<i class="fas fa-check-circle me-2"></i> Homologación Cerrada';
                                btnCerrarHomologacion.classList.remove('btn-primary');
                                btnCerrarHomologacion.classList.add('btn-secondary');
                            } else {
                                // Restaurar botón y mostrar error
                                btnCerrarHomologacion.disabled = false;
                                btnCerrarHomologacion.innerHTML = '<i class="fas fa-times-circle me-2"></i> Cerrar Homologación';
                                showNotification('Error al cerrar la homologación, intente nuevamente', 'danger');
                            }
                        });
                    }
                });
            } else {
                // Si no está en un estado válido, mostrar mensaje
                Swal.fire({
                    title: 'Acción no permitida',
                    text: 'Solo se pueden cerrar homologaciones en estado "Aprobado" o "Rechazado"',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    }

    // === FUNCIÓN DE ACTUALIZACIÓN DE ESTADO ===
    // Esta función la usa tanto "Iniciar Proceso" como "Cerrar Homologación"
    function actualizarEstado(nuevoEstado, callback) {
        // Verificar si tenemos los identificadores necesarios
        if (!solicitudId && !radicado) {
            console.error('Error: Faltan identificadores para la solicitud');
            showNotification('Error: Datos insuficientes para actualizar', 'danger');
            callback(false);
            return;
        }

        // Generar URLs de actualización
        const updateUrlWithId = `${baseUrl}/${solicitudId}`;
        const updateUrlWithRadicado = `${baseUrl}/${radicado}`;
        console.log('URL de actualización con ID:', updateUrlWithId);
        console.log('URL de actualización con radicado:', updateUrlWithRadicado);

        // Intentar actualización con API directa primero
        updateDirectAPI()
            .then(() => {
                callback(true);
            })
            .catch(error => {
                console.warn('Actualización API directa falló:', error);
                // Intentar con form data como respaldo
                updateWithFormData()
                    .then(() => {
                        callback(true);
                    })
                    .catch(error => {
                        console.warn('Actualización con form data falló:', error);
                        // Intentar con datos JSON como último recurso
                        updateWithJsonData()
                            .then(() => {
                                callback(true);
                            })
                            .catch(error => {
                                console.error('Actualización con datos JSON falló:', error);
                                callback(false);
                            });
                    });
            });

        // Función para actualización directa con API
        function updateDirectAPI() {
            console.log('Intentando actualización directa con API...');
            const apiUrl = `${apiBaseUrl}/solicitudes/${solicitudId}/estado`;
            console.log('URL API:', apiUrl);

            return fetch(apiUrl, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    estado: nuevoEstado
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            console.error('Detalles del error API:', json);
                            throw new Error(`Error HTTP ${response.status}: ${json.message || 'Sin detalles'}`);
                        } catch (e) {
                            console.error('Respuesta API no es JSON:', text);
                            throw new Error(`Error HTTP ${response.status}: ${text.substring(0, 100)}`);
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

        // Método usando form data tradicional
        function updateWithFormData() {
            console.log('Intentando actualizar con form data...');
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('estado', nuevoEstado);
            formData.append('_method', 'POST');

            // Usar el identificador con preferencia por ID
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

        // Método usando datos JSON
        function updateWithJsonData() {
            console.log('Intentando actualizar con JSON data...');
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
                    estado: nuevoEstado
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const json = JSON.parse(text);
                            console.error('Detalles del error:', json);
                            throw new Error(`Error HTTP ${response.status}: ${json.message || 'Sin detalles'}`);
                        } catch (e) {
                            console.error('Respuesta no es JSON:', text);
                            throw new Error(`Error HTTP ${response.status}: ${text.substring(0, 100)}`);
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

    // === FUNCIÓN PARA ACTUALIZAR UI ===
    function updateStatusUI(element, estado) {
        // Eliminar clases de estado actuales
        element.classList.remove('alert-success', 'alert-danger', 'alert-primary', 'alert-warning',
            'alert-info', 'alert-secondary');

        // Actualizar ícono y clase basados en el estado
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
            case 'cerrado':
                statusClass = 'secondary';
                statusIcon = 'lock';
                break;
            default:
                statusClass = 'primary';
                statusIcon = 'clock';
        }

        element.classList.add(`alert-${statusClass}`);
        element.innerHTML = `
            <i class="fas fa-${statusIcon} me-2"></i>
            <span class="fw-bold">${estado.charAt(0).toUpperCase() + estado.slice(1)}</span>
        `;
    }

    // === FUNCIÓN PARA MOSTRAR NOTIFICACIONES ===
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

        // Auto-cerrado después de 5 segundos
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 150);
        }, 5000);
    }
});
    </script>
@endsection

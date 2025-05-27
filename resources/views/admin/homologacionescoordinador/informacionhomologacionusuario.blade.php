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
document.addEventListener('DOMContentLoaded', function() {
    // === Inicialización de variables ===
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

    console.log('ID solicitud:', solicitudId);
    console.log('Estado actual:', estadoActual);

    // === ESCAPE KEY ===
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            window.location.href = rutaEsc;
        }
    });

    // === INICIAR PROCESO BUTTON ===
    if (btnIniciarProceso) {
        btnIniciarProceso.addEventListener('click', function() {
            const currentState = document.querySelector('.alert .fw-bold').textContent.trim().toLowerCase();

            if (currentState === 'radicado') {
                btnIniciarProceso.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Actualizando...';
                btnIniciarProceso.disabled = true;

                updateStatusUI(estadoDisplay, 'En revisión');
                estadoActualText.textContent = 'En revisión';
                document.getElementById('estadoActual').value = 'En revisión';

                actualizarEstado('En revisión', function(success) {
                    if (success) {
                        showNotification('Estado actualizado a "En revisión"', 'success');
                        setTimeout(() => {
                            window.location.href = rutaProceso;
                        }, 1000);
                    } else {
                        // Revertir UI en caso de error
                        updateStatusUI(estadoDisplay, currentState);
                        estadoActualText.textContent = currentState;
                        document.getElementById('estadoActual').value = currentState;
                        btnIniciarProceso.innerHTML = '<i class="fas fa-play-circle me-2"></i> Iniciar Proceso';
                        btnIniciarProceso.disabled = false;

                        showNotification('Error al actualizar estado', 'danger');
                    }
                });
            } else {
                window.location.href = rutaProceso;
            }
        });
    }

    // === CERRAR HOMOLOGACIÓN BUTTON ===
    if (btnCerrarHomologacion) {
        btnCerrarHomologacion.addEventListener('click', function() {
            console.log('Botón Cerrar Homologación clickeado');

            const currentState = document.querySelector('.alert .fw-bold').textContent.trim().toLowerCase();
            console.log('Estado actual:', currentState);

            if (currentState === 'aprobado' || currentState === 'aprobada' ||
                currentState === 'rechazado' || currentState === 'rechazada') {

                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¿Desea cerrar esta homologación? Esta acción cambiará el estado a "Cerrado".',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cerrar homologación',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Confirmación aceptada, procediendo a cerrar');

                        btnCerrarHomologacion.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Cerrando...';
                        btnCerrarHomologacion.disabled = true;

                        updateStatusUI(estadoDisplay, 'Cerrado');
                        estadoActualText.textContent = 'Cerrado';
                        document.getElementById('estadoActual').value = 'Cerrado';

                        actualizarEstado('Cerrado', function(success) {
                            if (success) {
                                showNotification('Homologación cerrada exitosamente', 'success');

                                btnCerrarHomologacion.disabled = true;
                                btnCerrarHomologacion.innerHTML = '<i class="fas fa-check-circle me-2"></i> Homologación Cerrada';
                                btnCerrarHomologacion.classList.remove('btn-primary');
                                btnCerrarHomologacion.classList.add('btn-secondary');
                            } else {
                                // Revertir UI en caso de error
                                btnCerrarHomologacion.disabled = false;
                                btnCerrarHomologacion.innerHTML = '<i class="fas fa-times-circle me-2"></i> Cerrar Homologación';

                                updateStatusUI(estadoDisplay, currentState);
                                estadoActualText.textContent = currentState;
                                document.getElementById('estadoActual').value = currentState;

                                showNotification('Error al cerrar la homologación. Intente nuevamente.', 'danger');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Acción no permitida',
                    text: 'Solo se pueden cerrar homologaciones en estado "Aprobado" o "Rechazado". Estado actual: ' + currentState,
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    }

    // === FUNCIÓN DE ACTUALIZACIÓN CORREGIDA ===
    function actualizarEstado(nuevoEstado, callback) {
        console.log('Actualizando estado a:', nuevoEstado);

        // ✅ URL corregida para usar 127.0.0.1:8000 como el servidor de Laravel
        const apiUpdateUrl = `http://127.0.0.1:8000/api/solicitudes/${solicitudId}/estado`;

        console.log('URL:', apiUpdateUrl);

        fetch(apiUpdateUrl, {
            method: 'PUT',  // ✅ PUT según la ruta verificada
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                estado: nuevoEstado
            })
        })
        .then(response => {
            console.log('Respuesta recibida:', response.status);
            console.log('Headers de respuesta:', response.headers);

            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error del servidor:', text);
                    let errorMsg = `HTTP ${response.status}: ${response.statusText}`;
                    try {
                        const errorJson = JSON.parse(text);
                        if (errorJson.mensaje) {
                            errorMsg = errorJson.mensaje;
                        }
                        if (errorJson.error) {
                            errorMsg += ` - ${errorJson.error}`;
                        }
                    } catch (e) {
                        // Si no es JSON válido, usar el texto tal como está
                        if (text) {
                            errorMsg += ` - ${text.substring(0, 100)}`;
                        }
                    }
                    throw new Error(errorMsg);
                });
            }

            return response.json();
        })
        .then(data => {
            console.log('Actualización exitosa:', data);
            callback(true);
        })
        .catch(error => {
            console.error('Error al actualizar estado:', error);
            showNotification(`Error: ${error.message}`, 'danger');
            callback(false);
        });
    }

    // === FUNCIÓN PARA ACTUALIZAR UI ===
    function updateStatusUI(element, estado) {
        element.classList.remove('alert-success', 'alert-danger', 'alert-primary', 'alert-warning', 'alert-info', 'alert-secondary');

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

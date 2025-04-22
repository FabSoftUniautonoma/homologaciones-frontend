@extends('admin.layouts.appadmin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="title-section mx-auto" style="max-width: 1200px;">
        <h1 class="fw-bold text-center" style="color: #1a3a6c; border-bottom: 3px solid #ccd2dd; padding-bottom: 12px; margin-bottom: 8px;">
            Gestión de Usuarios
        </h1>
        <p class="text-muted lead text-center">Administración de usuarios del sistema</p>
    </div>

    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Usuarios registrados</h5>
            <a href="/usuarioscrear" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaUsuarios">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Identificación</th>
                            <th>Institución</th>
                            <th>Facultad</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán desde JavaScript -->
                        <tr>
                            <td colspan="9" class="text-center">Cargando usuarios...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de usuario -->
<div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleUsuarioModalLabel">Detalles del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <span id="detalle-nombre"></span></p>
                            <p><strong>Correo:</strong> <span id="detalle-correo"></span></p>
                            <p><strong>Identificación:</strong> <span id="detalle-identificacion"></span></p>
                            <p><strong>Teléfono:</strong> <span id="detalle-telefono"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Institución:</strong> <span id="detalle-institucion"></span></p>
                            <p><strong>Facultad:</strong> <span id="detalle-facultad"></span></p>
                            <p><strong>Dirección:</strong> <span id="detalle-direccion"></span></p>
                            <p><strong>Ubicación:</strong> <span id="detalle-ubicacion"></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL base de la API - Ajustar según tu configuración
    const API_URL = 'http://127.0.0.1:8000/api';

    // Cargar usuarios al iniciar
    cargarUsuarios();

    // Función para cargar todos los usuarios
    function cargarUsuarios() {
        fetch(`${API_URL}/usuarios`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(usuarios => {
            mostrarUsuariosEnTabla(usuarios);
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('#tablaUsuarios tbody').innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-danger">
                        Error al cargar los usuarios. Por favor, intente nuevamente.
                    </td>
                </tr>
            `;
        });
    }

    // Función para mostrar los usuarios en la tabla
    function mostrarUsuariosEnTabla(usuarios) {
        const tablaBody = document.querySelector('#tablaUsuarios tbody');

        if (usuarios.length === 0) {
            tablaBody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center">No hay usuarios registrados</td>
                </tr>
            `;
            return;
        }

        let htmlFilas = '';

        usuarios.forEach(usuario => {
            const nombreCompleto = [
                usuario.primer_nombre,
                usuario.segundo_nombre,
                usuario.primer_apellido,
                usuario.segundo_apellido
            ].filter(Boolean).join(' ');

            const identificacion = `${usuario.tipo_identificacion}: ${usuario.numero_identificacion}`;

            htmlFilas += `
                <tr>
                    <td>${usuario.id_usuario}</td>
                    <td>${nombreCompleto}</td>
                    <td>${usuario.correo}</td>
                    <td>${identificacion}</td>
                    <td>${usuario.institucion_origen || '-'}</td>
                    <td>${usuario.facultad || '-'}</td>
                    <td>${usuario.telefono || '-'}</td>
                    <td>${getRolNombre(usuario.rol_id)}</td>
                    <td>
                            <button type="button" class="btn btn-info text-white" onclick="verDetalleUsuario(${usuario.id_usuario})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="/editarusuario/${usuario.id_usuario}" class="btn btn-warning text-white">
                                <i class="fas fa-edit"></i>
                            </a>

                        </div>
                    </td>
                </tr>
            `;
        });

        tablaBody.innerHTML = htmlFilas;
    }

    // Función auxiliar para obtener el nombre del rol según ID
    function getRolNombre(rolId) {
        const roles = {
            1: 'Administrador',
            2: 'Usuario',
            3: 'Vicerrector'
        };
        return roles[rolId] || 'Desconocido';
    }

    // Función para ver detalles del usuario
    window.verDetalleUsuario = function(usuarioId) {
        fetch(`${API_URL}/usuarios/${usuarioId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los detalles del usuario');
            }
            return response.json();
        })
        .then(data => {
            if (data.datos) {
                const usuario = data.datos;

                // Completar los campos del modal
                document.getElementById('detalle-nombre').textContent = [
                    usuario.primer_nombre,
                    usuario.segundo_nombre,
                    usuario.primer_apellido,
                    usuario.segundo_apellido
                ].filter(Boolean).join(' ');

                document.getElementById('detalle-correo').textContent = usuario.correo;
                document.getElementById('detalle-identificacion').textContent = `${usuario.tipo_identificacion}: ${usuario.numero_identificacion}`;
                document.getElementById('detalle-telefono').textContent = usuario.telefono || 'No registrado';
                document.getElementById('detalle-institucion').textContent = usuario.institucion_origen || 'No registrada';
                document.getElementById('detalle-facultad').textContent = usuario.facultad || 'No registrada';
                document.getElementById('detalle-direccion').textContent = usuario.direccion || 'No registrada';

                const ubicacion = [
                    usuario.municipio,
                    usuario.departamento,
                    usuario.pais
                ].filter(Boolean).join(', ');
                document.getElementById('detalle-ubicacion').textContent = ubicacion || 'No registrada';

                // Mostrar el modal
                const modal = new bootstrap.Modal(document.getElementById('detalleUsuarioModal'));
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se encontraron detalles del usuario'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al obtener los detalles del usuario'
            });
        });
    };

    // Función para confirmar eliminación
   /*window.confirmarEliminarUsuario = function(usuarioId) {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarUsuario(usuarioId);
            }
        });
    };

    // Función para eliminar usuario
    function eliminarUsuario(usuarioId) {
        fetch(`${API_URL}/usuarios/${usuarioId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al eliminar el usuario');
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.mensaje || 'Usuario eliminado correctamente',
                timer: 2000,
                timerProgressBar: true
            });

            // Recargar la lista de usuarios
            cargarUsuarios();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el usuario. Por favor, intente nuevamente.'
            });
        });
    }*/
});
</script>
@endsection

@extends('admin.layouts.appadmin')

@section('content')

<!-- Header institucional -->
<div class="container-fluid py-3 mb-4" style="background-color: #003366; font-family: 'Source Sans Pro', sans-serif;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
          <class="img-fluid" style="max-height: 60px;">
            </div>
            <div class="col-md-8 text-center">
                <h1 class="display-5 fw-bold mb-0" style="color: white !important;">Sistema de homologaciones</h1>
                <p class="lead mb-0" style="color: white !important;">Gestión de usuarios</p>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid py-3">
    <!-- Título de sección -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold text-primary mb-1">Gestión de usuarios</h2>
                        <p class="text-muted mb-0">Administración de usuarios del sistema</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buscador y filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar usuario...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filtroRol">
                                <option value="">Todos los roles</option>
                                <!-- Se cargará dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ url('/usuarioscrear') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-1"></i> Nuevo usuario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-list me-2"></i>Usuarios registrados
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaUsuarios">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre(email)</th>
                            <th scope="col">Identificación</th>
                            <th scope="col">Institución</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2 mb-0">Cargando usuarios...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Editar Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <input type="hidden" id="editar-id-usuario">

                    <!-- Datos personales -->
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">Información personal</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editar-primer-nombre" class="form-label">Primer Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editar-primer-nombre" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-segundo-nombre" class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control" id="editar-segundo-nombre">
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-primer-apellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editar-primer-apellido" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-segundo-apellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="editar-segundo-apellido">
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-tipo-identificacion" class="form-label">Tipo de Identificación <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editar-tipo-identificacion" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                                        <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                                        <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-numero-identificacion" class="form-label">Número de Identificación <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editar-numero-identificacion" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="editar-email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="editar-telefono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos institucionales -->
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">Información Institucional</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editar-institucion-origen-id" class="form-label">Institución</label>
                                    <select class="form-select" id="editar-institucion-origen-id">
                                        <option value="">Seleccione...</option>
                                        <!-- Se cargará dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-facultad-id" class="form-label">Facultad</label>
                                    <select class="form-select" id="editar-facultad-id">
                                        <option value="">Seleccione...</option>
                                        <!-- Se cargará dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-rol-id" class="form-label">Rol <span class="text-danger">*</span></label>
                                    <select class="form-select" id="editar-rol-id" required>
                                        <option value="">Seleccione...</option>
                                        <!-- Se cargará dinámicamente -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="editar-direccion">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="card-title text-primary mb-3">
                                Cambiar Contraseña
                                <span class="badge bg-warning text-dark ms-2">Opcional</span>
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editar-password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="editar-password">
                                    <div class="form-text">Dejar en blanco para mantener la contraseña actual</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="editar-password-confirmation" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="editar-password-confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="editar-activo" checked>
                        <label class="form-check-label" for="editar-activo">Usuario Activo</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="btnGuardarCambios">
                    <i class="fas fa-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_URL = 'https://homologacionesback.educarenemociones.com/api';
    let usuariosData = [];
    let instituciones = [];
    let facultades = [];
    let roles = [];

    // Inicializar componentes
    initTooltips();
    cargarDatos();

    // Eventos
    document.getElementById('buscarUsuario').addEventListener('keyup', filtrarUsuarios);
    document.getElementById('filtroRol').addEventListener('change', filtrarUsuarios);
    document.getElementById('btnGuardarCambios').addEventListener('click', guardarCambiosUsuario);

    /**
     * Carga todos los datos necesarios
     */
    async function cargarDatos() {
        mostrarCargando('Cargando datos...');

        try {
            // Cargar datos en paralelo
            const [usuarios, insts, facs, rolesData] = await Promise.all([
                obtenerUsuarios(),
                obtenerInstituciones(),
                obtenerFacultades(),
                obtenerRoles()
            ]);

            usuariosData = usuarios;
            instituciones = insts;
            facultades = facs;
            roles = rolesData;

            console.log("Roles cargados:", roles);

            // Actualizar interfaz
            mostrarUsuariosEnTabla(usuariosData);
            cargarSelectores();
            ocultarCargando();

            notificar('success', 'Sistema listo', 'Datos cargados correctamente');
        } catch (error) {
            console.error('Error al cargar datos:', error);
            ocultarCargando();
            notificar('error', 'Error', 'No se pudieron cargar los datos');
        }
    }

    /**
     * Obtiene todos los usuarios
     */
    async function obtenerUsuarios() {
        try {
            mostrarCargandoTabla();
            const response = await fetch(`${API_URL}/usuarios`, {
                method: 'GET',
                headers: getHeaders()
            });

            if (!response.ok) throw new Error('Error al obtener usuarios');
            return await response.json();
        } catch (error) {
            console.error('Error al cargar usuarios:', error);
            mostrarErrorEnTabla('No se pudieron cargar los usuarios');
            return [];
        }
    }

    /**
     * Obtiene todas las instituciones
     */
    async function obtenerInstituciones() {
        try {
            const response = await fetch(`${API_URL}/instituciones`, {
                method: 'GET',
                headers: getHeaders()
            });

            if (!response.ok) throw new Error('Error al obtener instituciones');
            return await response.json();
        } catch (error) {
            console.error('Error al cargar instituciones:', error);
            return [];
        }
    }

    /**
     * Obtiene todas las facultades
     */
    async function obtenerFacultades() {
        try {
            const response = await fetch(`${API_URL}/facultades`, {
                method: 'GET',
                headers: getHeaders()
            });

            if (!response.ok) throw new Error('Error al obtener facultades');
            return await response.json();
        } catch (error) {
            console.error('Error al cargar facultades:', error);
            return [];
        }
    }

    /**
     * Obtiene todos los roles
     */
    async function obtenerRoles() {
        try {
            const response = await fetch(`${API_URL}/roles`, {
                method: 'GET',
                headers: getHeaders()
            });

            if (!response.ok) throw new Error('Error al obtener roles');
            const rolesData = await response.json();
            console.log("Roles obtenidos de la API:", rolesData);
            return rolesData;
        } catch (error) {
            console.error('Error al cargar roles:', error);
            return [];
        }
    }

    /**
     * Carga los selectores
     */
    function cargarSelectores() {
        // Cargar selector de instituciones
        const selectInstitucion = document.getElementById('editar-institucion-origen-id');
        selectInstitucion.innerHTML = '<option value="">Seleccione...</option>';
        instituciones.forEach(inst => {
            selectInstitucion.appendChild(new Option(inst.nombre, inst.id_institucion));
        });

        // Cargar selector de facultades
        const selectFacultad = document.getElementById('editar-facultad-id');
        selectFacultad.innerHTML = '<option value="">Seleccione...</option>';
        facultades.forEach(fac => {
            selectFacultad.appendChild(new Option(fac.nombre, fac.id_facultad));
        });

        // Cargar selector de roles
        const selectRol = document.getElementById('editar-rol-id');
        selectRol.innerHTML = '<option value="">Seleccione...</option>';
        roles.forEach(rol => {
            // Asegurarse de que sea el formato correcto para el id_rol
            const idRol = rol.id_rol || rol.id || '';
            const nombreRol = rol.nombre || 'Sin nombre';
            selectRol.appendChild(new Option(nombreRol, idRol));
        });

        // Actualizar también el filtro de roles
        const filtroRol = document.getElementById('filtroRol');
        filtroRol.innerHTML = '<option value="">Todos los roles</option>';
        roles.forEach(rol => {
            const idRol = rol.id_rol || rol.id || '';
            const nombreRol = rol.nombre || 'Sin nombre';
            filtroRol.appendChild(new Option(nombreRol, idRol));
        });
    }

    /**
     * Filtra los usuarios según criterios
     */
    function filtrarUsuarios() {
        const texto = document.getElementById('buscarUsuario').value.toLowerCase();
        const rolSeleccionado = document.getElementById('filtroRol').value;

        const usuariosFiltrados = usuariosData.filter(usuario => {
            // Filtro por texto
            const nombreCompleto = [
                usuario.primer_nombre,
                usuario.segundo_nombre,
                usuario.primer_apellido,
                usuario.segundo_apellido
            ].filter(Boolean).join(' ').toLowerCase();

            const identificacion = usuario.tipo_identificacion && usuario.numero_identificacion ?
                `${usuario.tipo_identificacion}: ${usuario.numero_identificacion}`.toLowerCase() : '';

            const cumpleTexto = texto === '' ||
                              nombreCompleto.includes(texto) ||
                              (usuario.email && usuario.email.toLowerCase().includes(texto)) ||
                              identificacion.includes(texto);

            // Filtro por rol
            const cumpleRol = rolSeleccionado === '' ||
                (usuario.rol_id && usuario.rol_id.toString() === rolSeleccionado) ||
                (usuario.rol && usuario.rol.id_rol && usuario.rol.id_rol.toString() === rolSeleccionado);

            return cumpleTexto && cumpleRol;
        });

        mostrarUsuariosEnTabla(usuariosFiltrados);
    }

    /**
     * Muestra los usuarios en la tabla
     */
    function mostrarUsuariosEnTabla(usuarios) {
        const tablaBody = document.querySelector('#tablaUsuarios tbody');

        if (usuarios.length === 0) {
            tablaBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            No se encontraron usuarios que coincidan con los criterios de búsqueda.
                        </div>
                    </td>
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

            // Obtener inicial para avatar
            const inicial = (usuario.primer_nombre || '').charAt(0).toUpperCase();
            const colorAvatar = getColorFromId(usuario.id_usuario);

            const identificacion = usuario.tipo_identificacion && usuario.numero_identificacion ?
                `${usuario.tipo_identificacion}: ${usuario.numero_identificacion}` : '-';

            // Obtener nombre del rol
            let rolId = usuario.rol_id;
            if (usuario.rol && usuario.rol.id_rol) {
                rolId = usuario.rol.id_rol;
            }

            const rolNombre = getRolNombre(rolId);
            const badgeColor = getBadgeColor(rolId);

            // Estado (activo/inactivo)
            const estadoHtml = usuario.activo !== false ?
                '<span class="badge bg-success pill-badge"><i class="fas fa-check-circle me-1"></i>Activo</span>' :
                '<span class="badge bg-danger pill-badge"><i class="fas fa-times-circle me-1"></i>Inactivo</span>';

            htmlFilas += `
                <tr class="usuario-fila" data-id="${usuario.id_usuario}">
                    <td width="60" class="text-center">
                        <div class="avatar-circle" style="background-color: ${colorAvatar}">
                            ${inicial}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">${nombreCompleto}</span>
                            <small class="text-muted"><i class="fas fa-envelope me-1"></i>${usuario.email || '-'}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            ${identificacion}
                        </span>
                    </td>
                    <td>
                        <span class="text-truncate d-inline-block" style="max-width: 150px;">
                            <i class="fas fa-university text-secondary me-1"></i>${usuario.institucion_origen || '-'}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-${badgeColor} pill-badge">
                            <i class="fas fa-user-tag me-1"></i>${rolNombre}
                        </span>
                    </td>
                    <td class="text-center">${estadoHtml}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-info" onclick="verDetalleUsuario(${usuario.id_usuario})"
                                    data-bs-toggle="tooltip" title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="editarUsuario(${usuario.id_usuario})"
                                   data-bs-toggle="tooltip" title="Editar usuario">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="eliminarUsuario(${usuario.id_usuario})"
                                   data-bs-toggle="tooltip" title="Eliminar usuario">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tablaBody.innerHTML = htmlFilas;
        initTooltips();
    }
/**
 * Ver detalles de un usuario - Versión mejorada y corregida
 */
window.verDetalleUsuario = async function(usuarioId) {
    try {
        // Mostrar cargando
        Swal.fire({
            title: 'Cargando detalles...',
            html: '<div class="spinner-border text-primary" role="status"></div>',
            showConfirmButton: false,
            allowOutsideClick: false
        });

        console.log("Solicitando detalles para el usuario ID:", usuarioId);

        // Primero, buscamos si ya tenemos el usuario en nuestros datos cargados
        let usuarioPreCargado = null;
        if (typeof usuariosData !== 'undefined' && Array.isArray(usuariosData)) {
            usuarioPreCargado = usuariosData.find(u => u.id_usuario == usuarioId);
            console.log("¿Usuario precargado encontrado?", usuarioPreCargado ? "Sí" : "No");
        } else {
            console.log("No hay datos precargados disponibles");
        }

        // Variable para almacenar el usuario final
        let usuario = null;

        try {
            // Intentamos hacer la petición a la API
            const response = await fetch(`${API_URL}/usuarios/${usuarioId}`, {
                method: 'GET',
                headers: getHeaders()
            });

            console.log("Respuesta de la API status:", response.status);

            if (!response.ok) {
                console.warn("La petición a la API falló con status:", response.status);
                throw new Error(`Error en la petición: ${response.status}`);
            }

            // Procesar la respuesta
            const result = await response.json();
            console.log("Respuesta completa de la API:", result);

            // Determinar cómo extraer el usuario de la respuesta
            // Caso 1: Si la respuesta tiene una propiedad 'datos'
            if (result && result.datos) {
                console.log("Encontrado en result.datos");
                usuario = result.datos;
            }
            // Caso 2: Si la respuesta es un array
            else if (Array.isArray(result)) {
                console.log("La respuesta es un array");
                // Buscar el usuario con el ID correcto en el array
                usuario = result.find(u => u.id_usuario == usuarioId);

                // Si no se encuentra específicamente, tomar el primero
                if (!usuario && result.length > 0) {
                    usuario = result[0];
                }
            }
            // Caso 3: Si la respuesta es directamente el objeto usuario
            else if (result && (result.id_usuario || result.email)) {
                console.log("La respuesta es directamente el objeto usuario");
                usuario = result;
            }
            // Caso 4: Si hay alguna otra estructura desconocida
            else {
                console.log("Estructura desconocida, intentando interpretar...");
                // Buscar cualquier objeto que parezca un usuario
                for (const key in result) {
                    if (typeof result[key] === 'object' && result[key] !== null) {
                        if (result[key].id_usuario || result[key].email) {
                            usuario = result[key];
                            console.log("Usuario encontrado en propiedad:", key);
                            break;
                        }
                    }
                }

                // Si aún no hay usuario, usar result directamente como último recurso
                if (!usuario) {
                    usuario = result;
                }
            }

        } catch (error) {
            console.error("Error al obtener datos de la API:", error);
            // Si falla la petición, usamos los datos precargados si existen
            if (usuarioPreCargado) {
                console.log("Usando datos precargados porque falló la petición");
                usuario = usuarioPreCargado;
            } else {
                throw new Error('No se pudieron obtener los detalles del usuario');
            }
        }

        console.log("Usuario procesado:", usuario);

      
        // Mostrar los detalles
        mostrarDetallesUsuario(usuario);

    } catch (error) {
        console.error('Error global:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al obtener los detalles del usuario'
        });
    }
};

/**
 * Función para mostrar los detalles del usuario
 */
function mostrarDetallesUsuario(usuario) {
    // Añadimos una verificación para asegurarnos de que el usuario existe
    if (!usuario || typeof usuario !== 'object') {
        console.error("Error: No se recibió un objeto de usuario válido", usuario);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los detalles del usuario'
        });
        return;
    }

    // Añadimos logs para depuración
    console.log("Mostrando detalles para usuario:", usuario);
    console.log("ID de usuario:", usuario.id_usuario);
    console.log("Datos completos:", JSON.stringify(usuario));

    // Obtener el rol del usuario - Detecta todos los formatos posibles
    let rolNombre = 'Sin rol asignado';

    // Log adicional para depuración
    console.log("Propiedad rol:", usuario.rol);
    console.log("Tipo de rol:", typeof usuario.rol);

    // Verificar diferentes estructuras posibles para el rol
    if (typeof usuario.rol === 'string' && usuario.rol) {
        // Si el rol viene directamente como texto
        rolNombre = usuario.rol;
        console.log("Rol detectado como string:", rolNombre);
    } else if (usuario.rol_id) {
        // Si viene como ID numérico
        rolNombre = getRolNombre(usuario.rol_id);
        console.log("Rol detectado como ID:", usuario.rol_id, "->", rolNombre);
    } else if (usuario.rol && typeof usuario.rol === 'object' && usuario.rol.id_rol) {
        // Si viene como objeto con ID
        rolNombre = getRolNombre(usuario.rol.id_rol);
        console.log("Rol detectado como objeto con ID:", usuario.rol.id_rol, "->", rolNombre);
    } else if (usuario.rol && typeof usuario.rol === 'object' && usuario.rol.nombre) {
        // Si viene como objeto con nombre
        rolNombre = usuario.rol.nombre;
        console.log("Rol detectado como objeto con nombre:", rolNombre);
    }

    // Nombre completo para mostrar
    const nombreCompleto = [
        usuario.primer_nombre,
        usuario.segundo_nombre,
        usuario.primer_apellido,
        usuario.segundo_apellido
    ].filter(Boolean).join(' ');

    // Obtener inicial y color para avatar
    const inicial = (usuario.primer_nombre || '').charAt(0).toUpperCase();
    const colorAvatar = getColorFromId(usuario.id_usuario || 1); // Valor predeterminado 1 si no hay ID

    // Crear HTML simple sin bootstrap para evitar conflictos
    const detalleHTML = `
        <style>
            /* Definición de estilos básicos */
            .detail-container {
                font-family: Arial, sans-serif;
                max-width: 100%;
                margin: 0 auto;
            }
            .detail-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .avatar-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background-color: ${colorAvatar};
                color: white;
                font-size: 32px;
                font-weight: bold;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 15px;
            }
            .usuario-nombre {
                color: #333;
                font-size: 20px;
                font-weight: bold;
                margin: 5px 0;
            }
            .usuario-rol {
                color: #777;
                margin: 5px 0;
            }
            .detail-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .detail-item {
                background-color: #f8f9fa;
                border-radius: 8px;
                padding: 12px;
                display: flex;
                align-items: center;
            }
            .detail-icon {
                margin-right: 15px;
                color: #4285F4;
                min-width: 20px;
                text-align: center;
            }
            .detail-content {
                flex: 1;
            }
            .detail-label {
                color: #777;
                font-size: 12px;
                display: block;
                margin-bottom: 3px;
            }
            .detail-value {
                margin: 0;
            }
            @media (max-width: 576px) {
                .detail-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <div class="detail-container">
            <div class="detail-header">
                <div class="avatar-circle">${inicial}</div>
                <h3 class="usuario-nombre">${nombreCompleto || 'Usuario'}</h3>
                <p class="usuario-rol">${rolNombre}</p>
            </div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Correo electrónico</span>
                        <p class="detail-value">${usuario.email || '-'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-id-card"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Identificación</span>
                        <p class="detail-value">${usuario.tipo_identificacion || '-'}: ${usuario.numero_identificacion || '-'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-phone"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Teléfono</span>
                        <p class="detail-value">${usuario.telefono || 'No registrado'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-university"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Institución</span>
                        <p class="detail-value">${usuario.institucion_origen || 'No registrada'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Facultad</span>
                        <p class="detail-value">${usuario.facultad || 'No registrada'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Dirección</span>
                        <p class="detail-value">${usuario.direccion || 'No registrada'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-globe"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Ubicación</span>
                        <p class="detail-value">${usuario.pais || '-'}, ${usuario.departamento || '-'}, ${usuario.municipio || '-'}</p>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="detail-content">
                        <span class="detail-label">Estado</span>
                        <p class="detail-value">${usuario.activo !== false ? 'Activo' : 'Inactivo'}</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Mostrar detalles con SweetAlert2 personalizado
    Swal.fire({
        title: 'Detalles del Usuario',
        html: detalleHTML,
        width: 600,
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#3085d6',
        showCancelButton: true,
        cancelButtonText: 'Editar',
        cancelButtonColor: '#ffc107',
        didOpen: () => {
            // Verificar si está visible correctamente
            console.log("Modal abierto - Contenido visible:", document.querySelector('.detail-container').offsetHeight > 0);
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            // Si se presiona "Editar", abrir el modal de edición
            editarUsuario(usuario.id_usuario);
        }
    });
}

/**
 * Obtener el nombre del rol a partir de su ID
 */
function getRolNombre(rolId) {
    // Convertir a número para comparar correctamente
    const id = parseInt(rolId, 10);

    // Buscar en los roles disponibles
    const rol = rolesDisponibles.find(r => parseInt(r.id_rol, 10) === id);

    // Si se encuentra, devolver el nombre, si no, un valor por defecto
    return rol ? rol.nombre : 'Rol ID: ' + rolId;
}

/**
 * Función para obtener un color basado en el ID de usuario
 */
function getColorFromId(id) {
    // Lista de colores para avatares
    const colores = [
        '#4285F4', '#EA4335', '#FBBC05', '#34A853',
        '#7B1FA2', '#C2185B', '#00ACC1', '#FF5722',
        '#3949AB', '#00897B', '#8E24AA', '#D81B60'
    ];

    // Usar el ID como índice para seleccionar un color
    const indice = parseInt(id, 10) % colores.length;
    return colores[indice];
}

/**
 * Cargar roles del sistema - Versión mejorada
 */
async function obtenerRoles() {
    try {
        const response = await fetch(`${API_URL}/roles`, {
            method: 'GET',
            headers: getHeaders()
        });

        if (!response.ok) throw new Error('Error al obtener roles');

        const rolesData = await response.json();
        console.log("Roles obtenidos de la API:", rolesData);

        // Si no hay datos o hay un error, crear algunos roles predeterminados
        if (!rolesData || rolesData.length === 0) {
            console.log("No se encontraron roles en la API, usando predeterminados");
            return [
                { id_rol: 1, nombre: 'Administrador' },
                { id_rol: 2, nombre: 'Usuario' },
                { id_rol: 3, nombre: 'Vicerrector' },
                { id_rol: 4, nombre: 'Aspirante' },
                { id_rol: 5, nombre: 'Coordinador' }
            ];
        }

        // Procesar los datos según el formato
        if (Array.isArray(rolesData)) {
            // Si es un array, lo usamos directamente
            return rolesData;
        } else if (rolesData && typeof rolesData === 'object') {
            // Si es un objeto, lo convertimos a un array
            const rolesArray = [];
            for (const key in rolesData) {
                if (rolesData.hasOwnProperty(key)) {
                    const rol = rolesData[key];
                    // Asegurar que tenga la estructura esperada
                    if (typeof rol === 'string') {
                        rolesArray.push({ id_rol: key, nombre: rol });
                    } else if (typeof rol === 'object') {
                        rolesArray.push(rol);
                    }
                }
            }
            return rolesArray;
        }

        return [];
    } catch (error) {
        console.error('Error al cargar roles:', error);
        // Si falla, crear algunos roles predeterminados para que funcione la interfaz
        return [
            { id_rol: 1, nombre: 'Administrador' },
            { id_rol: 2, nombre: 'Usuario' },
            { id_rol: 3, nombre: 'Vicerrector' },
            { id_rol: 4, nombre: 'Aspirante' },
            { id_rol: 5, nombre: 'Coordinador' }
        ];
    }
}

// Variable global para almacenar los roles disponibles
let rolesDisponibles = [];

// Inicializar los roles al cargar la página
document.addEventListener('DOMContentLoaded', async function() {
    // Cargar los roles disponibles
    rolesDisponibles = await obtenerRoles();
    console.log("Roles disponibles:", rolesDisponibles);
});

/**
 * Función para editar un usuario - Versión final corregida
 */
window.editarUsuario = async function(usuarioId) {
    try {
        // Mostrar loading
        mostrarCargando('Obteniendo datos del usuario...');

        const response = await fetch(`${API_URL}/usuarios/${usuarioId}`, {
            method: 'GET',
            headers: getHeaders()
        });

        if (!response.ok) throw new Error('Error al obtener los datos del usuario');

        const result = await response.json();

        console.log("Datos de edición recibidos:", result);

        // Si la API devuelve los datos dentro de una propiedad 'datos'
        let usuario;
        if (result.datos) {
            usuario = result.datos;
        } else if (Array.isArray(result) && result.length > 0) {
            // Si la API devuelve directamente un array, tomar el primer elemento
            usuario = result[0];
        } else {
            // Si la API devuelve directamente el objeto usuario
            usuario = result;
        }

        if (!usuario) {
            throw new Error('No se encontraron datos del usuario');
        }

        ocultarCargando();

        // Llenar el formulario con los datos del usuario
        document.getElementById('editar-id-usuario').value = usuario.id_usuario;
        document.getElementById('editar-primer-nombre').value = usuario.primer_nombre || '';
        document.getElementById('editar-segundo-nombre').value = usuario.segundo_nombre || '';
        document.getElementById('editar-primer-apellido').value = usuario.primer_apellido || '';
        document.getElementById('editar-segundo-apellido').value = usuario.segundo_apellido || '';
        document.getElementById('editar-email').value = usuario.email || '';
        document.getElementById('editar-tipo-identificacion').value = usuario.tipo_identificacion || '';
        document.getElementById('editar-numero-identificacion').value = usuario.numero_identificacion || '';
        document.getElementById('editar-telefono').value = usuario.telefono || '';
        document.getElementById('editar-direccion').value = usuario.direccion || '';

        // Llenar selectores - Manejo mejorado para múltiples formatos
        if (usuario.institucion_origen_id) {
            document.getElementById('editar-institucion-origen-id').value = usuario.institucion_origen_id;
        }

        if (usuario.facultad_id) {
            document.getElementById('editar-facultad-id').value = usuario.facultad_id;
        }

        // Manejo del rol según la estructura de datos
        const selectRol = document.getElementById('editar-rol-id');

        // Si el rol viene como texto directo, buscamos en las opciones disponibles
        if (typeof usuario.rol === 'string' && usuario.rol) {
            // Buscar la opción que tiene el mismo texto que usuario.rol
            for (let i = 0; i < selectRol.options.length; i++) {
                if (selectRol.options[i].text.toLowerCase() === usuario.rol.toLowerCase()) {
                    selectRol.selectedIndex = i;
                    break;
                }
            }

            // Si no encontramos coincidencia, añadimos la opción
            if (selectRol.value === "") {
                // Obtener el siguiente ID disponible
                const nuevoId = roles.length > 0 ? Math.max(...roles.map(r => parseInt(r.id_rol || r.id || 0))) + 1 : 1;

                // Añadir la opción al select
                const nuevaOpcion = new Option(usuario.rol, nuevoId);
                selectRol.appendChild(nuevaOpcion);
                selectRol.value = nuevoId;

                // Añadir a la lista de roles
                roles.push({
                    id_rol: nuevoId,
                    nombre: usuario.rol
                });
            }
        } else if (usuario.rol_id) {
            selectRol.value = usuario.rol_id;
        } else if (usuario.rol && usuario.rol.id_rol) {
            selectRol.value = usuario.rol.id_rol;
        }

        // Estado del usuario
        document.getElementById('editar-activo').checked = usuario.activo !== false;

        // Limpiar campos de contraseña
        document.getElementById('editar-password').value = '';
        document.getElementById('editar-password-confirmation').value = '';

        // Mostrar el modal de edición
        const modal = new bootstrap.Modal(document.getElementById('editarUsuarioModal'));
        modal.show();

    } catch (error) {
        console.error('Error:', error);
        ocultarCargando();
        notificar('error', 'Error', error.message || 'Error al obtener los datos del usuario');
    }
};

/**
 * Muestra los usuarios en la tabla - Versión actualizada
 */
function mostrarUsuariosEnTabla(usuarios) {
    const tablaBody = document.querySelector('#tablaUsuarios tbody');

    if (usuarios.length === 0) {
        tablaBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        No se encontraron usuarios que coincidan con los criterios de búsqueda.
                    </div>
                </td>
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

        // Obtener inicial para avatar
        const inicial = (usuario.primer_nombre || '').charAt(0).toUpperCase();
        const colorAvatar = getColorFromId(usuario.id_usuario);

        const identificacion = usuario.tipo_identificacion && usuario.numero_identificacion ?
            `${usuario.tipo_identificacion}: ${usuario.numero_identificacion}` : '-';

        // Obtener nombre del rol - AHORA MANEJA EL FORMATO STRING DIRECTO
        let rolNombre;
        if (typeof usuario.rol === 'string' && usuario.rol) {
            rolNombre = usuario.rol;
        } else if (usuario.rol_id) {
            rolNombre = getRolNombre(usuario.rol_id);
        } else if (usuario.rol && usuario.rol.id_rol) {
            rolNombre = getRolNombre(usuario.rol.id_rol);
        } else if (usuario.rol && usuario.rol.nombre) {
            rolNombre = usuario.rol.nombre;
        } else {
            rolNombre = 'Sin rol asignado';
        }

        const badgeColor = getBadgeColorForName(rolNombre);

        // Estado (activo/inactivo)
        const estadoHtml = usuario.activo !== false ?
            '<span class="badge bg-success pill-badge"><i class="fas fa-check-circle me-1"></i>Activo</span>' :
            '<span class="badge bg-danger pill-badge"><i class="fas fa-times-circle me-1"></i>Inactivo</span>';

        htmlFilas += `
            <tr class="usuario-fila" data-id="${usuario.id_usuario}">
                <td width="60" class="text-center">
                    <div class="avatar-circle" style="background-color: ${colorAvatar}">
                        ${inicial}
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">${nombreCompleto}</span>
                        <small class="text-muted"><i class="fas fa-envelope me-1"></i>${usuario.email || '-'}</small>
                    </div>
                </td>
                <td>
                    <span class="badge bg-light text-dark border">
                        ${identificacion}
                    </span>
                </td>
                <td>
                    <span class="text-truncate d-inline-block" style="max-width: 150px;">
                        <i class="fas fa-university text-secondary me-1"></i>${usuario.institucion_origen || '-'}
                    </span>
                </td>
                <td>
                    <span class="badge bg-${badgeColor} pill-badge">
                        <i class="fas fa-user-tag me-1"></i>${rolNombre}
                    </span>
                </td>
                <td class="text-center">${estadoHtml}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-info" onclick="verDetalleUsuario(${usuario.id_usuario})"
                                data-bs-toggle="tooltip" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="editarUsuario(${usuario.id_usuario})"
                               data-bs-toggle="tooltip" title="Editar usuario">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="eliminarUsuario(${usuario.id_usuario})"
                               data-bs-toggle="tooltip" title="Eliminar usuario">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tablaBody.innerHTML = htmlFilas;
    initTooltips();
}

/**
 * Obtiene el color del badge según el nombre del rol
 */
function getBadgeColorForName(rolNombre) {
    if (!rolNombre) return 'secondary';

    const rolLower = rolNombre.toLowerCase();

    // Asignar colores según nombres comunes de roles
    if (rolLower.includes('admin')) return 'primary';
    if (rolLower.includes('coordinador')) return 'info';
    if (rolLower.includes('aspirante')) return 'success';
    if (rolLower.includes('vicerrector')) return 'warning';
    if (rolLower.includes('profesor') || rolLower.includes('docente')) return 'purple';
    if (rolLower.includes('estudiante') || rolLower.includes('alumno')) return 'teal';

    // Utilizar un algoritmo para generar un color consistente basado en el texto
    const sum = rolNombre.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
    const colors = ['primary', 'success', 'info', 'warning', 'danger', 'purple', 'teal', 'pink'];
    return colors[sum % colors.length];
}

/**
 * Muestra los usuarios en la tabla - Versión actualizada
 */
function mostrarUsuariosEnTabla(usuarios) {
    const tablaBody = document.querySelector('#tablaUsuarios tbody');

    if (usuarios.length === 0) {
        tablaBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        No se encontraron usuarios que coincidan con los criterios de búsqueda.
                    </div>
                </td>
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

        // Obtener inicial para avatar
        const inicial = (usuario.primer_nombre || '').charAt(0).toUpperCase();
        const colorAvatar = getColorFromId(usuario.id_usuario);

        const identificacion = usuario.tipo_identificacion && usuario.numero_identificacion ?
            `${usuario.tipo_identificacion}: ${usuario.numero_identificacion}` : '-';

        // Obtener nombre del rol - AHORA MANEJA EL FORMATO STRING DIRECTO
        let rolNombre;
        if (typeof usuario.rol === 'string' && usuario.rol) {
            rolNombre = usuario.rol;
        } else if (usuario.rol_id) {
            rolNombre = getRolNombre(usuario.rol_id);
        } else if (usuario.rol && usuario.rol.id_rol) {
            rolNombre = getRolNombre(usuario.rol.id_rol);
        } else if (usuario.rol && usuario.rol.nombre) {
            rolNombre = usuario.rol.nombre;
        } else {
            rolNombre = 'Sin rol asignado';
        }

        const badgeColor = getBadgeColorForName(rolNombre);

        // Estado (activo/inactivo)
        const estadoHtml = usuario.activo !== false ?
            '<span class="badge bg-success pill-badge"><i class="fas fa-check-circle me-1"></i>Activo</span>' :
            '<span class="badge bg-danger pill-badge"><i class="fas fa-times-circle me-1"></i>Inactivo</span>';

        htmlFilas += `
            <tr class="usuario-fila" data-id="${usuario.id_usuario}">
                <td width="60" class="text-center">
                    <div class="avatar-circle" style="background-color: ${colorAvatar}">
                        ${inicial}
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">${nombreCompleto}</span>
                        <small class="text-muted"><i class="fas fa-envelope me-1"></i>${usuario.email || '-'}</small>
                    </div>
                </td>
                <td>
                    <span class="badge bg-light text-dark border">
                        ${identificacion}
                    </span>
                </td>
                <td>
                    <span class="text-truncate d-inline-block" style="max-width: 150px;">
                        <i class="fas fa-university text-secondary me-1"></i>${usuario.institucion_origen || '-'}
                    </span>
                </td>
                <td>
                    <span class="badge bg-${badgeColor} pill-badge">
                        <i class="fas fa-user-tag me-1"></i>${rolNombre}
                    </span>
                </td>
                <td class="text-center">${estadoHtml}</td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-info" onclick="verDetalleUsuario(${usuario.id_usuario})"
                                data-bs-toggle="tooltip" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="editarUsuario(${usuario.id_usuario})"
                               data-bs-toggle="tooltip" title="Editar usuario">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="eliminarUsuario(${usuario.id_usuario})"
                               data-bs-toggle="tooltip" title="Eliminar usuario">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tablaBody.innerHTML = htmlFilas;
    initTooltips();
}

/**
 * Función para editar un usuario - Versión corregida
 */
window.editarUsuario = async function(usuarioId) {
    try {
        // Mostrar loading
        mostrarCargando('Obteniendo datos del usuario...');

        const response = await fetch(`${API_URL}/usuarios/${usuarioId}`, {
            method: 'GET',
            headers: getHeaders()
        });

        if (!response.ok) throw new Error('Error al obtener los datos del usuario');

        const result = await response.json();

        console.log("Respuesta completa de la API (editar):", result);

        // Determinar cómo extraer el usuario de la respuesta
        let usuario;

        // Caso 1: Si la respuesta tiene una propiedad 'datos'
        if (result && result.datos) {
            console.log("Encontrado en result.datos");
            usuario = result.datos;
        }
        // Caso 2: Si la respuesta es un array
        else if (Array.isArray(result)) {
            console.log("La respuesta es un array");
            // Buscar el usuario con el ID correcto en el array
            usuario = result.find(u => u.id_usuario == usuarioId);

            // Si no se encuentra específicamente, tomar el primero
            if (!usuario && result.length > 0) {
                usuario = result[0];
            }
        }
        // Caso 3: Si la respuesta es directamente el objeto usuario
        else if (result && (result.id_usuario || result.email)) {
            console.log("La respuesta es directamente el objeto usuario");
            usuario = result;
        }
        // Caso 4: Si hay alguna otra estructura desconocida
        else {
            console.log("Estructura desconocida, intentando interpretar...");
            // Buscar cualquier objeto que parezca un usuario
            for (const key in result) {
                if (typeof result[key] === 'object' && result[key] !== null) {
                    if (result[key].id_usuario || result[key].email) {
                        usuario = result[key];
                        console.log("Usuario encontrado en propiedad:", key);
                        break;
                    }
                }
            }

            // Si aún no hay usuario, usar result directamente como último recurso
            if (!usuario) {
                usuario = result;
            }
        }

        console.log("Usuario procesado para edición:", usuario);

        if (!usuario || (typeof usuario === 'object' && Object.keys(usuario).length === 0)) {
            throw new Error('No se pudo extraer la información del usuario de la respuesta');
        }

        ocultarCargando();

        // Llenar el formulario con los datos del usuario
        document.getElementById('editar-id-usuario').value = usuario.id_usuario;
        document.getElementById('editar-primer-nombre').value = usuario.primer_nombre || '';
        document.getElementById('editar-segundo-nombre').value = usuario.segundo_nombre || '';
        document.getElementById('editar-primer-apellido').value = usuario.primer_apellido || '';
        document.getElementById('editar-segundo-apellido').value = usuario.segundo_apellido || '';
        document.getElementById('editar-email').value = usuario.email || '';
        document.getElementById('editar-tipo-identificacion').value = usuario.tipo_identificacion || '';
        document.getElementById('editar-numero-identificacion').value = usuario.numero_identificacion || '';
        document.getElementById('editar-telefono').value = usuario.telefono || '';
        document.getElementById('editar-direccion').value = usuario.direccion || '';

        // Llenar selectores
        if (usuario.institucion_origen_id) {
            document.getElementById('editar-institucion-origen-id').value = usuario.institucion_origen_id;
        }

        if (usuario.facultad_id) {
            document.getElementById('editar-facultad-id').value = usuario.facultad_id;
        }

        // Manejo del rol según la estructura de datos
        const selectRol = document.getElementById('editar-rol-id');

        // Log adicional para depuración
        console.log("Propiedad rol para edición:", usuario.rol);
        console.log("Tipo de rol para edición:", typeof usuario.rol);

        // Si el rol viene como texto directo, buscamos en las opciones disponibles
        if (typeof usuario.rol === 'string' && usuario.rol) {
            console.log("Buscando rol por nombre:", usuario.rol);
            let encontrado = false;

            // Buscar la opción que tiene el mismo texto que usuario.rol
            for (let i = 0; i < selectRol.options.length; i++) {
                if (selectRol.options[i].text.toLowerCase() === usuario.rol.toLowerCase()) {
                    selectRol.selectedIndex = i;
                    encontrado = true;
                    console.log("Rol encontrado en las opciones:", selectRol.options[i].text);
                    break;
                }
            }

            // Si no encontramos coincidencia, añadimos la opción
            if (!encontrado) {
                console.log("Rol no encontrado, añadiendo nueva opción");
                // Obtener el siguiente ID disponible
                const nuevoId = roles.length > 0 ? Math.max(...roles.map(r => parseInt(r.id_rol || r.id || 0))) + 1 : 1;

                // Añadir la opción al select
                const nuevaOpcion = new Option(usuario.rol, nuevoId);
                selectRol.appendChild(nuevaOpcion);
                selectRol.value = nuevoId;

                // Añadir a la lista de roles
                roles.push({
                    id_rol: nuevoId,
                    nombre: usuario.rol
                });

                console.log("Nuevo rol añadido:", usuario.rol, "con ID:", nuevoId);
            }
        } else if (usuario.rol_id) {
            selectRol.value = usuario.rol_id;
            console.log("Seleccionando rol por ID:", usuario.rol_id);
        } else if (usuario.rol && typeof usuario.rol === 'object' && usuario.rol.id_rol) {
            selectRol.value = usuario.rol.id_rol;
            console.log("Seleccionando rol por ID en objeto:", usuario.rol.id_rol);
        }

        // Estado del usuario
        document.getElementById('editar-activo').checked = usuario.activo !== false;

        // Limpiar campos de contraseña
        document.getElementById('editar-password').value = '';
        document.getElementById('editar-password-confirmation').value = '';

        // Mostrar el modal de edición
        try {
            const modal = new bootstrap.Modal(document.getElementById('editarUsuarioModal'));
            modal.show();
        } catch (modalError) {
            console.error("Error al mostrar el modal:", modalError);
            // Intento alternativo de abrir el modal
            const modalElement = document.getElementById('editarUsuarioModal');
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }

    } catch (error) {
        console.error('Error:', error);
        ocultarCargando();
        notificar('error', 'Error', error.message || 'Error al obtener los datos del usuario');
    }
};
    async function guardarCambiosUsuario() {
        // Validar el formulario
        if (!validarFormulario()) {
            notificar('warning', 'Validación', 'Por favor complete todos los campos obligatorios correctamente.');
            return;
        }

        const usuarioId = document.getElementById('editar-id-usuario').value;

        // Construir objeto con datos del usuario
        const usuarioData = {
            primer_nombre: document.getElementById('editar-primer-nombre').value,
            segundo_nombre: document.getElementById('editar-segundo-nombre').value || null,
            primer_apellido: document.getElementById('editar-primer-apellido').value,
            segundo_apellido: document.getElementById('editar-segundo-apellido').value || null,
            email: document.getElementById('editar-email').value,
            tipo_identificacion: document.getElementById('editar-tipo-identificacion').value,
            numero_identificacion: document.getElementById('editar-numero-identificacion').value,
            telefono: document.getElementById('editar-telefono').value || null,
            direccion: document.getElementById('editar-direccion').value || null,
            institucion_origen_id: document.getElementById('editar-institucion-origen-id').value || null,
            facultad_id: document.getElementById('editar-facultad-id').value || null,
            rol_id: document.getElementById('editar-rol-id').value,
            activo: document.getElementById('editar-activo').checked
        };

        // Agregar contraseña solo si se ha completado
        const password = document.getElementById('editar-password').value;
        if (password) {
            const passwordConfirmation = document.getElementById('editar-password-confirmation').value;
            if (password !== passwordConfirmation) {
                notificar('error', 'Error', 'Las contraseñas no coinciden');
                return;
            }
            usuarioData.password = password;
            usuarioData.password_confirmation = passwordConfirmation;
        }

        // Mostrar indicador de carga
        const botonGuardar = document.getElementById('btnGuardarCambios');
        const textoOriginal = botonGuardar.innerHTML;
        botonGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
        botonGuardar.disabled = true;

        try {
            console.log("Datos a enviar:", usuarioData);

            // Enviar datos a la API
            const response = await fetch(`${API_URL}/usuarios/${usuarioId}`, {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify(usuarioData)
            });

            if (!response.ok) throw new Error('Error al actualizar el usuario');

            const result = await response.json();
            console.log("Respuesta del servidor:", result);

            // Cerrar modal
            const modalEl = document.getElementById('editarUsuarioModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            } else {
                // Si no se puede obtener la instancia, intentar cerrar de manera manual
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            }

            notificar('success', 'Éxito', 'Usuario actualizado correctamente');

            // Recargar lista de usuarios
            usuariosData = await obtenerUsuarios();
            mostrarUsuariosEnTabla(usuariosData);

        } catch (error) {
            console.error('Error:', error);
            notificar('error', 'Error', error.message || 'Error al actualizar el usuario');
        } finally {
            // Restaurar botón
            botonGuardar.innerHTML = textoOriginal;
            botonGuardar.disabled = false;
        }
    }

    /**
     * Eliminar usuario
     */
    window.eliminarUsuario = function(usuarioId) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Eliminando...',
                        html: '<div class="spinner-border text-danger" role="status"></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    const response = await fetch(`${API_URL}/usuarios/${usuarioId}`, {
                        method: 'DELETE',
                        headers: getHeaders()
                    });

                    if (!response.ok) throw new Error('Error al eliminar el usuario');

                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: 'El usuario ha sido eliminado correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Recargar lista de usuarios
                    usuariosData = await obtenerUsuarios();
                    mostrarUsuariosEnTabla(usuariosData);
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar el usuario'
                    });
                }
            }
        });
    };

    /**
     * Valida el formulario de edición
     */
    function validarFormulario() {
        let esValido = true;

        // Validar campos requeridos
        const camposRequeridos = [
            'editar-primer-nombre',
            'editar-primer-apellido',
            'editar-email',
            'editar-tipo-identificacion',
            'editar-numero-identificacion',
            'editar-rol-id'
        ];

        // Limpiar validaciones anteriores
        document.querySelectorAll('#formEditarUsuario .form-control, #formEditarUsuario .form-select').forEach(input => {
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
        });

        // Validar campos requeridos
        camposRequeridos.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (!elemento.value.trim()) {
                elemento.classList.add('is-invalid');
                esValido = false;
            } else {
                elemento.classList.add('is-valid');
            }
        });

        // Validar email
        const email = document.getElementById('editar-email').value;
        if (email && !/\S+@\S+\.\S+/.test(email)) {
            document.getElementById('editar-email').classList.remove('is-valid');
            document.getElementById('editar-email').classList.add('is-invalid');
            esValido = false;
        }

        // Validar confirmación de contraseña
        const password = document.getElementById('editar-password').value;
        const passwordConfirm = document.getElementById('editar-password-confirmation').value;

        if (password && password !== passwordConfirm) {
            document.getElementById('editar-password').classList.add('is-invalid');
            document.getElementById('editar-password-confirmation').classList.add('is-invalid');
            esValido = false;
        }

        return esValido;
    }

    /**
     * Muestra notificación
     */
    function notificar(tipo, titulo, mensaje) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${tipo}`;
        toast.innerHTML = `
            <div class="toast-icon"><i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i></div>
            <div class="toast-content">
                <h4>${titulo}</h4>
                <p>${mensaje}</p>
            </div>
            <button class="toast-close">&times;</button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            });
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        }, 100);
    }

    /**
     * Muestra indicador de carga global
     */
    function mostrarCargando(mensaje = 'Cargando...') {
        const loaderExists = document.getElementById('custom-loader');
        if (loaderExists) return;

        const loaderWrapper = document.createElement('div');
        loaderWrapper.className = 'loader-wrapper';
        loaderWrapper.id = 'custom-loader';

        loaderWrapper.innerHTML = `
            <div class="loader-container">
                <div class="loader-spinner"></div>
                <p class="loader-message">${mensaje}</p>
            </div>
        `;

        document.body.appendChild(loaderWrapper);
        setTimeout(() => {
            loaderWrapper.classList.add('visible');
        }, 10);
    }

    /**
     * Oculta indicador de carga global
     */
    function ocultarCargando() {
        const loader = document.getElementById('custom-loader');
        if (loader) {
            loader.classList.remove('visible');
            setTimeout(() => {
                if (document.body.contains(loader)) {
                    document.body.removeChild(loader);
                }
            }, 300);
        }
    }

    /**
     * Muestra indicador de carga en la tabla
     */
    function mostrarCargandoTabla() {
        document.querySelector('#tablaUsuarios tbody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 mb-0">Cargando usuarios...</p>
                </td>
            </tr>
        `;
    }

    /**
     * Muestra mensaje de error en la tabla
     */
    function mostrarErrorEnTabla(mensaje) {
        document.querySelector('#tablaUsuarios tbody').innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-3">
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${mensaje}
                        <button type="button" class="btn btn-sm btn-outline-danger ms-3" onclick="cargarDatos()">
                            <i class="fas fa-sync-alt me-1"></i>Reintentar
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Inicializa los tooltips de Bootstrap
     */
    function initTooltips() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            // Destruir tooltip existente si ya está inicializado
            try {
                const instance = bootstrap.Tooltip.getInstance(tooltip);
                if (instance) {
                    instance.dispose();
                }
            } catch (e) {}

            // Crear nuevo tooltip
            new bootstrap.Tooltip(tooltip, {
                delay: { show: 300, hide: 100 }
            });
        });
    }

    /**
     * Obtiene el color de fondo para un avatar según el ID
     */
    function getColorFromId(id) {
        const colors = [
            '#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6',
            '#1abc9c', '#d35400', '#34495e', '#16a085', '#27ae60',
            '#2980b9', '#8e44ad', '#f1c40f', '#e67e22', '#c0392b'
        ];

        // Generar un índice basado en el ID para seleccionar un color
        const colorIndex = (typeof id === 'number' ? id : parseInt(id, 10)) % colors.length;
        return colors[Math.abs(colorIndex)];
    }

    /**
     * Obtiene el color del badge según el rol
     */
    function getBadgeColor(rolId) {
        const colors = {
            1: 'primary',   // Administrador
            2: 'success',   // Usuario
            3: 'info'       // Vicerrector
        };
        return colors[rolId] || 'secondary';
    }

    /**
     * Obtiene el nombre del rol según ID - Versión mejorada
     */
    function getRolNombre(rolId) {
        // Si no hay un ID de rol, retornar un valor por defecto
        if (!rolId) return 'Sin rol asignado';

        // Transformar a número para comparaciones
        const rolIdNum = parseInt(rolId, 10);

        // Primero buscar en los datos de roles cargados
        if (roles && roles.length > 0) {
            // Buscar el rol por su ID
            const rol = roles.find(r => {
                const currentId = parseInt(r.id_rol || r.id || 0, 10);
                return currentId === rolIdNum;
            });

            if (rol && rol.nombre) {
                return rol.nombre;
            }
        }

        // Si no se encuentra, usar valores predeterminados
        const rolesPredeterminados = {
            1: 'Administrador',
            2: 'Usuario',
            3: 'Vicerrector'
        };

        return rolesPredeterminados[rolIdNum] || `Rol ID: ${rolId}`;
    }

    /**
     * Obtiene los headers para las peticiones
     */
    function getHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
    }

    /**
     * Prepara el CSS necesario para la interfaz
     */
    function inicializarEstilos() {
        // Crear elemento de estilo si no existe
        if (!document.getElementById('sistema-estilos')) {
            const style = document.createElement('style');
            style.id = 'sistema-estilos';
            style.textContent = `
                /* Estilos generales */
                body {
                    background-color: #f5f7fa;
                    font-family: 'Poppins', -apple-system, sans-serif;
                }

                /* Header institucional */
                .bg-primary {
                    background: linear-gradient(135deg, #08355d 0%, #07418b 100%) !important;
                }

                /* Cards */
                .card {
                    border: none;
                    border-radius: 10px;
                    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
                    transition: all 0.3s ease;
                }

                .card:hover {
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    transform: translateY(-3px);
                }

                /* Avatar circular */
                .avatar-circle {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    color: white;
                    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
                }

                .avatar-circle-lg {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                    font-size: 32px;
                    color: white;
                    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
                }

                /* Botones de acción */
                .btn-outline-info, .btn-outline-warning, .btn-outline-danger {
                    border-radius: 8px;
                    width: 32px;
                    height: 32px;
                    padding: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s;
                }

                .btn-outline-info:hover, .btn-outline-warning:hover, .btn-outline-danger:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
                }

                /* Badge personalizado */
                .pill-badge {
                    border-radius: 20px;
                    padding: 6px 12px;
                    font-weight: 500;
                    font-size: 0.75rem;
                }

                /* Tabla de usuarios */
                .table {
                    border-collapse: separate;
                    border-spacing: 0 8px;
                }

                .table thead th {
                    border-bottom: none;
                    font-weight: 600;
                    color: #6c757d;
                    text-transform: uppercase;
                    font-size: 0.75rem;
                    letter-spacing: 1px;
                }

                .table tbody tr {
                    background-color: #fff;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.03);
                    border-radius: 10px;
                    transition: all 0.3s;
                }

                .table tbody tr:hover {
                    box-shadow: 0 5px 12px rgba(0,0,0,0.07);
                    transform: translateY(-2px);
                }

                .table tbody td {
                    border: none;
                    padding: 12px;
                    vertical-align: middle;
                }

                .table tbody tr td:first-child {
                    border-top-left-radius: 10px;
                    border-bottom-left-radius: 10px;
                }

                .table tbody tr td:last-child {
                    border-top-right-radius: 10px;
                    border-bottom-right-radius: 10px;
                }

                /* Formulario de edición */
                #formEditarUsuario .form-label {
                    font-weight: 500;
                    font-size: 0.875rem;
                    color: #495057;
                }

                #formEditarUsuario .form-control,
                #formEditarUsuario .form-select {
                    padding: 10px 15px;
                    border-radius: 8px;
                    transition: all 0.3s;
                }

                #formEditarUsuario .form-control:focus,
                #formEditarUsuario .form-select:focus {
                    box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
                    border-color: #bac8f3;
                }

                /* Botones personalizados */
                .btn-primary {
                    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
                    border: none;
                    box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
                    border-radius: 8px;
                    padding: 10px 20px;
                    font-weight: 500;
                    transition: all 0.3s;
                }

                .btn-primary:hover {
                    background: linear-gradient(135deg, #3d5cca 0%, #1a3a99 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
                }

                .btn-warning {
                    background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
                    border: none;
                    color: white;
                    box-shadow: 0 4px 6px rgba(246, 194, 62, 0.25);
                    border-radius: 8px;
                    padding: 10px 20px;
                    font-weight: 500;
                    transition: all 0.3s;
                }

                .btn-warning:hover {
                    background: linear-gradient(135deg, #e5b636 0%, #c8920a 100%);
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: 0 6px 8px rgba(246, 194, 62, 0.3);
                }

                /* Personalización del modal */
                .modal-content {
                    border-radius: 15px;
                    overflow: hidden;
                }

                .modal-header {
                    background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
                    padding: 15px 20px;
                    border-bottom: none;
                }

                .modal-header .modal-title {
                    color: white;
                    font-weight: 600;
                }

                .modal-body {
                    padding: 20px;
                }

                .modal-footer {
                    border-top: none;
                    padding: 15px 20px;
                    background-color: #f8f9fa;
                }

                /* Notificaciones toast */
                .toast-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    display: flex;
                    align-items: center;
                    max-width: 350px;
                    background: white;
                    color: #333;
                    padding: 15px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                    transform: translateX(400px);
                    opacity: 0;
                    transition: all 0.3s ease;
                    z-index: 9999;
                }

                .toast-notification.show {
                    transform: translateX(0);
                    opacity: 1;
                }

                .toast-notification::before {
                    content: '';
                    position: absolute;
                    left: 0;
                    top: 0;
                    height: 100%;
                    width: 5px;
                    background: #3498db;
                }

                .toast-notification.toast-success::before {
                    background: #2ecc71;
                }

                .toast-notification.toast-error::before {
                    background: #e74c3c;
                }

                .toast-notification.toast-warning::before {
                    background: #f39c12;
                }

                .toast-icon {
                    background: rgba(0,0,0,0.05);
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 15px;
                    font-size: 18px;
                }

                .toast-success .toast-icon {
                    color: #2ecc71;
                }

                .toast-error .toast-icon {
                    color: #e74c3c;
                }

                .toast-warning .toast-icon {
                    color: #f39c12;
                }

                .toast-content {
                    flex: 1;
                }

                .toast-content h4 {
                    margin: 0 0 5px;
                    font-size: 16px;
                    font-weight: 600;
                }

                .toast-content p {
                    margin: 0;
                    font-size: 14px;
                    opacity: 0.8;
                }

                .toast-close {
                    background: none;
                    border: none;
                    font-size: 20px;
                    line-height: 1;
                    color: #999;
                    cursor: pointer;
                    padding: 0 5px;
                }

                .toast-close:hover {
                    color: #333;
                }

                /* Estilos para la tarjeta de detalles */
                .detail-card {
                    display: flex;
                    align-items: center;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    margin-bottom: 10px;
                }

                .detail-icon {
                    width: 36px;
                    height: 36px;
                    background-color: #e9ecef;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 14px;
                    color: #6c757d;
                    margin-right: 12px;
                }

                /* Loader personalizado */
                .loader-wrapper {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: rgba(0,0,0,0.3);
                    backdrop-filter: blur(3px);
                    z-index: 9999;
                    opacity: 0;
                    transition: opacity 0.3s;
                    pointer-events: none;
                }

                .loader-wrapper.visible {
                    opacity: 1;
                    pointer-events: auto;
                }

                .loader-container {
                    background-color: white;
                    border-radius: 15px;
                    padding: 25px 40px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    max-width: 90%;
                    transform: scale(0.9);
                    transition: transform 0.3s;
                }

                .loader-wrapper.visible .loader-container {
                    transform: scale(1);
                }

                .loader-spinner {
                    width: 50px;
                    height: 50px;
                    border: 4px solid rgba(78, 115, 223, 0.1);
                    border-left-color: #4e73df;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin-bottom: 15px;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .loader-message {
                    font-size: 16px;
                    font-weight: 500;
                    color: #333;
                    margin: 0;
                    text-align: center;
                }

                /* Sweetalert personalizado */
                .swal-wide {
                    width: 600px !important;
                }
            `;
            document.head.appendChild(style);
        }

        // Agregar fuente Poppins de Google Fonts
        if (!document.getElementById('google-fonts')) {
            const fontLink = document.createElement('link');
            fontLink.id = 'google-fonts';
            fontLink.href = 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap';
            fontLink.rel = 'stylesheet';
            document.head.appendChild(fontLink);
        }
    }

    // Inicializar estilos
    inicializarEstilos();
});

// Referencias a las funciones globales para que estén disponibles desde los botones
window.cargarDatos = function() {
    document.dispatchEvent(new Event('DOMContentLoaded'));
};
</script>
@endsection

@extends('admin.layouts.appadmin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="title-section mx-auto" style="max-width: 800px;">
        <h1 class="fw-bold text-center" style="color: #1a3a6c; border-bottom: 3px solid #ccd2dd; padding-bottom: 12px; margin-bottom: 8px;">
            Editar Usuario
        </h1>
        <p class="text-muted lead text-center">Actualice la información del usuario</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="formEditarUsuario">
                <input type="hidden" id="usuario_id" name="usuario_id">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                        <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                        <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Correo Electrónico *</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tipo_identificacion" class="form-label">Tipo de Identificación *</label>
                        <select class="form-select" id="tipo_identificacion" name="tipo_identificacion" required>
                            <option value="">Seleccione...</option>
                            <option value="Cédula de Ciudadanía">Cédula de Ciudadanía</option>
                            <option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
                            <option value="Cédula de Extranjería">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="numero_identificacion" class="form-label">Número de Identificación *</label>
                        <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="institucion_origen_id" class="form-label">Institución de Origen</label>
                        <select class="form-select" id="institucion_origen_id" name="institucion_origen_id">
                            <option value="">Seleccione...</option>
                            <!-- Las opciones se cargarán desde JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="facultad_id" class="form-label">Facultad</label>
                        <select class="form-select" id="facultad_id" name="facultad_id">
                            <option value="">Seleccione...</option>
                            <!-- Las opciones se cargarán desde JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion">
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="pais_id" class="form-label">País</label>
                        <select class="form-select" id="pais_id" name="pais_id">
                            <option value="">Seleccione...</option>
                            <!-- Las opciones se cargarán desde JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="departamento_id" class="form-label">Departamento</label>
                        <select class="form-select" id="departamento_id" name="departamento_id">
                            <option value="">Seleccione...</option>
                            <!-- Las opciones se cargarán desde JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="municipio_id" class="form-label">Municipio</label>
                        <select class="form-select" id="municipio_id" name="municipio_id">
                            <option value="">Seleccione...</option>
                            <!-- Las opciones se cargarán desde JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="rol_id" class="form-label">Rol *</label>
                    <select class="form-select" id="rol_id" name="rol_id" required>
                        <option value="">Seleccione...</option>
                        <!-- Las opciones se cargarán desde JavaScript -->
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="/admin/usuarios" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL base de la API
    const API_URL = 'http://127.0.0.1:8000/api';

    // Obtener el ID del usuario de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = window.location.pathname.split('/').pop() || urlParams.get('id');

    if (!userId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'ID de usuario no especificado',
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '/admin/usuarios';
        });
        return;
    }

    // Guardar el ID en el campo oculto
    document.getElementById('usuario_id').value = userId;

    // Cargar datos para los select
    cargarInstituciones();
    cargarFacultades();
    cargarPaises();
    cargarRoles();

    // Cargar datos del usuario
    cargarDatosUsuario(userId);

    // Manejar envío del formulario
    document.getElementById('formEditarUsuario').addEventListener('submit', function(e) {
        e.preventDefault();
        actualizarUsuario(userId);
    });

    // Eventos para cargar departamentos y municipios
    document.getElementById('pais_id').addEventListener('change', function() {
        cargarDepartamentos(this.value);
    });

    document.getElementById('departamento_id').addEventListener('change', function() {
        cargarMunicipios(this.value);
    });

    // Función para cargar los datos del usuario
    function cargarDatosUsuario(userId) {
        fetch(`${API_URL}/usuarios/${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos del usuario');
            }
            return response.json();
        })
        .then(data => {
            if (data.datos) {
                const usuario = data.datos;

                // Completar los campos del formulario
                document.getElementById('primer_nombre').value = usuario.primer_nombre || '';
                document.getElementById('segundo_nombre').value = usuario.segundo_nombre || '';
                document.getElementById('primer_apellido').value = usuario.primer_apellido || '';
                document.getElementById('segundo_apellido').value = usuario.segundo_apellido || '';
                document.getElementById('correo').value = usuario.correo || '';
                document.getElementById('telefono').value = usuario.telefono || '';
                document.getElementById('direccion').value = usuario.direccion || '';

                const tipoIdentificacion = document.getElementById('tipo_identificacion');
                for (let i = 0; i < tipoIdentificacion.options.length; i++) {
                    if (tipoIdentificacion.options[i].value === usuario.tipo_identificacion) {
                        tipoIdentificacion.options[i].selected = true;
                        break;
                    }
                }

                document.getElementById('numero_identificacion').value = usuario.numero_identificacion || '';

                // Para los select que requieren cargar datos asíncronos, configuramos listeners
                const selectsConData = [
                    { selectId: 'institucion_origen_id', valor: usuario.institucion_origen_id },
                    { selectId: 'facultad_id', valor: usuario.facultad_id },
                    { selectId: 'rol_id', valor: usuario.rol_id },
                    { selectId: 'pais_id', valor: usuario.pais_id }
                ];

                selectsConData.forEach(item => {
                    const select = document.getElementById(item.selectId);

                    // Si ya tiene opciones, seleccionamos el valor
                    if (select.options.length > 1) {
                        select.value = item.valor;
                    }
                    // Si no tiene opciones aún, configuramos un listener para cuando se carguen
                    else {
                        const observer = new MutationObserver(function(mutations) {
                            if (select.options.length > 1) {
                                select.value = item.valor;
                                observer.disconnect();

                                // Si es el país, cargamos los departamentos
                                if (item.selectId === 'pais_id' && item.valor) {
                                    cargarDepartamentos(item.valor).then(() => {
                                        // Seleccionamos el departamento y cargamos municipios
                                        if (usuario.departamento_id) {
                                            document.getElementById('departamento_id').value = usuario.departamento_id;
                                            cargarMunicipios(usuario.departamento_id).then(() => {
                                                if (usuario.municipio_id) {
                                                    document.getElementById('municipio_id').value = usuario.municipio_id;
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        });
                        observer.observe(select, { childList: true });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se encontraron datos del usuario'
                }).then(() => {
                    window.location.href = '/admin/usuarios';
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al obtener los datos del usuario. Por favor, intente nuevamente.'
            }).then(() => {
                window.location.href = '/admin/usuarios';
            });
        });
    }

    // Función para actualizar el usuario
    function actualizarUsuario(userId) {
        // Obtener todos los datos del formulario
        const formData = new FormData(document.getElementById('formEditarUsuario'));
        const userData = {};

        // Convertir FormData a objeto JSON
        for (let [key, value] of formData.entries()) {
            // Convertir strings vacíos a null para campos opcionales
            userData[key] = value === '' ? null : value;
        }

        // Enviar petición a la API
        fetch(`${API_URL}/usuarios/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(userData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.mensaje || 'Usuario actualizado correctamente',
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = '/admin/usuarios';
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo actualizar el usuario. Por favor, verifica los datos e intenta nuevamente.'
            });
        });
    }

    // Funciones para cargar los datos en los selects
    function cargarInstituciones() {
        return fetch(`${API_URL}/instituciones`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                // Si la API no existe, usamos datos de ejemplo
                return Promise.resolve([
                    { id_institucion: 1, nombre: 'Universidad Nacional' },
                    { id_institucion: 2, nombre: 'Universidad de Antioquia' },
                    { id_institucion: 3, nombre: 'Universidad de los Andes' }
                ]);
            }
            return response.json();
        })
        .then(instituciones => {
            const select = document.getElementById('institucion_origen_id');
            select.innerHTML = '<option value="">Seleccione...</option>';

            instituciones.forEach(institucion => {
                const option = document.createElement('option');
                option.value = institucion.id_institucion;
                option.textContent = institucion.nombre;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error cargando instituciones:', error);
            // Cargar datos de ejemplo en caso de error
            const instituciones = [
                { id_institucion: 1, nombre: 'Universidad Nacional' },
                { id_institucion: 2, nombre: 'Universidad de Antioquia' },
                { id_institucion: 3, nombre: 'Universidad de los Andes' }
            ];

            const select = document.getElementById('institucion_origen_id');
            select.innerHTML = '<option value="">Seleccione...</option>';

            instituciones.forEach(institucion => {
                const option = document.createElement('option');
                option.value = institucion.id_institucion;
                option.textContent = institucion.nombre;
                select.appendChild(option);
            });
        });
    }

    function cargarFacultades() {
    return fetch(`${API_URL}/facultades`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            // Si la API no existe, usamos datos de ejemplo
            return Promise.resolve([
                { id_facultad: 1, nombre: 'Ingeniería' },
                { id_facultad: 2, nombre: 'Ciencias' },
                { id_facultad: 3, nombre: 'Medicina' }
            ]);
        }
        return response.json();
    })
    .then(facultades => {
        const select = document.getElementById('facultad_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        facultades.forEach(facultad => {
            const option = document.createElement('option');
            option.value = facultad.id_facultad;
            option.textContent = facultad.nombre;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error cargando facultades:', error);
        // Cargar datos de ejemplo en caso de error
        const facultades = [
            { id_facultad: 1, nombre: 'Ingeniería' },
            { id_facultad: 2, nombre: 'Ciencias' },
            { id_facultad: 3, nombre: 'Medicina' }
        ];

        const select = document.getElementById('facultad_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        facultades.forEach(facultad => {
            const option = document.createElement('option');
            option.value = facultad.id_facultad;
            option.textContent = facultad.nombre;
            select.appendChild(option);
        });
    });
}

function cargarPaises() {
    return fetch(`${API_URL}/paises`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return Promise.resolve([
                { id_pais: 1, nombre: 'Colombia' },
                { id_pais: 2, nombre: 'México' },
                { id_pais: 3, nombre: 'Argentina' }
            ]);
        }
        return response.json();
    })
    .then(paises => {
        const select = document.getElementById('pais_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        paises.forEach(pais => {
            const option = document.createElement('option');
            option.value = pais.id_pais;
            option.textContent = pais.nombre;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error cargando países:', error);
        // Cargar datos de ejemplo en caso de error
        const paises = [
            { id_pais: 1, nombre: 'Colombia' },
            { id_pais: 2, nombre: 'México' },
            { id_pais: 3, nombre: 'Argentina' }
        ];

        const select = document.getElementById('pais_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        paises.forEach(pais => {
            const option = document.createElement('option');
            option.value = pais.id_pais;
            option.textContent = pais.nombre;
            select.appendChild(option);
        });
    });
}

function cargarDepartamentos(paisId) {
    if (!paisId) {
        document.getElementById('departamento_id').innerHTML = '<option value="">Seleccione...</option>';
        document.getElementById('municipio_id').innerHTML = '<option value="">Seleccione...</option>';
        return Promise.resolve();
    }

    return fetch(`${API_URL}/departamentos?pais_id=${paisId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            // Si la API no existe, usamos datos de ejemplo
            return Promise.resolve([
                { id_departamento: 1, nombre: 'Cundinamarca' },
                { id_departamento: 2, nombre: 'Antioquia' },
                { id_departamento: 3, nombre: 'Valle del Cauca' }
            ]);
        }
        return response.json();
    })
    .then(departamentos => {
        const select = document.getElementById('departamento_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        departamentos.forEach(departamento => {
            const option = document.createElement('option');
            option.value = departamento.id_departamento;
            option.textContent = departamento.nombre;
            select.appendChild(option);
        });

        // Limpiar municipios
        document.getElementById('municipio_id').innerHTML = '<option value="">Seleccione...</option>';

        return departamentos;
    })
    .catch(error => {
        console.error('Error cargando departamentos:', error);
        // Cargar datos de ejemplo en caso de error
        const departamentos = [
            { id_departamento: 1, nombre: 'Cundinamarca' },
            { id_departamento: 2, nombre: 'Antioquia' },
            { id_departamento: 3, nombre: 'Valle del Cauca' }
        ];

        const select = document.getElementById('departamento_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        departamentos.forEach(departamento => {
            const option = document.createElement('option');
            option.value = departamento.id_departamento;
            option.textContent = departamento.nombre;
            select.appendChild(option);
        });

        // Limpiar municipios
        document.getElementById('municipio_id').innerHTML = '<option value="">Seleccione...</option>';

        return departamentos;
    });
}

function cargarMunicipios(departamentoId) {
    if (!departamentoId) {
        document.getElementById('municipio_id').innerHTML = '<option value="">Seleccione...</option>';
        return Promise.resolve();
    }

    return fetch(`${API_URL}/municipios?departamento_id=${departamentoId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return Promise.resolve([
                { id_municipio: 1, nombre: 'Bogotá' },
                { id_municipio: 2, nombre: 'Medellín' },
                { id_municipio: 3, nombre: 'Cali' }
            ]);
        }
        return response.json();
    })
    .then(municipios => {
        const select = document.getElementById('municipio_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        municipios.forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id_municipio;
            option.textContent = municipio.nombre;
            select.appendChild(option);
        });

        return municipios;
    })
    .catch(error => {
        console.error('Error cargando municipios:', error);
        // Cargar datos de ejemplo en caso de error
        const municipios = [
            { id_municipio: 1, nombre: 'Bogotá' },
            { id_municipio: 2, nombre: 'Medellín' },
            { id_municipio: 3, nombre: 'Cali' }
        ];

        const select = document.getElementById('municipio_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        municipios.forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id_municipio;
            option.textContent = municipio.nombre;
            select.appendChild(option);
        });

        return municipios;
    });
}

function cargarRoles() {
    return fetch(`${API_URL}/roles`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            // Si la API no existe, usamos datos de ejemplo
            return Promise.resolve([
                { id_rol: 1, nombre: 'Usuario' },
                { id_rol: 2, nombre: 'Vicerrector' },
                { id_rol: 3, nombre: 'Estudiante' }
            ]);
        }
        return response.json();
    })
    .then(roles => {
        const select = document.getElementById('rol_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        roles.forEach(rol => {
            const option = document.createElement('option');
            option.value = rol.id_rol;
            option.textContent = rol.nombre;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error cargando roles:', error);
        // Cargar datos de ejemplo en caso de error
        const roles = [
            { id_rol: 1, nombre: 'Usuario' },
            { id_rol: 2, nombre: 'Vicerrector' },
            { id_rol: 3, nombre: 'Estudiante' }
        ];

        const select = document.getElementById('rol_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        roles.forEach(rol => {
            const option = document.createElement('option');
            option.value = rol.id_rol;
            option.textContent = rol.nombre;
            select.appendChild(option);
        });
    });
}
// Función para actualizar el usuario
function actualizarUsuario(userId) {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Actualizando',
        text: 'Por favor espere...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Obtener todos los datos del formulario
    const formData = new FormData(document.getElementById('formEditarUsuario'));
    const userData = {};

    // Convertir FormData a objeto JSON
    for (let [key, value] of formData.entries()) {
        // Convertir strings vacíos a null para campos opcionales
        userData[key] = value === '' ? null : value;
    }

    // Asegurarse de que los IDs sean números
    const numericFields = ['institucion_origen_id', 'facultad_id', 'pais_id', 'departamento_id', 'municipio_id', 'rol_id'];
    numericFields.forEach(field => {
        if (userData[field]) {
            userData[field] = parseInt(userData[field], 10);
        }
    });

    console.log('Datos que se enviarán:', userData);

    // Enviar petición a la API
    fetch(`${API_URL}/usuarios/${userId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(userData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw {
                    status: response.status,
                    data: errorData
                };
            });
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: data.mensaje || 'Usuario actualizado correctamente',
            timer: 2000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '/admin/usuarios';
        });
    })
    .catch(error => {
        console.error('Error:', error);

        let errorMessage = 'No se pudo actualizar el usuario. Por favor, verifica los datos e intenta nuevamente.';

        // Si hay errores de validación, mostrarlos
        if (error.data && error.data.errors) {
            errorMessage = Object.values(error.data.errors).flat().join('<br>');
        } else if (error.data && error.data.mensaje) {
            errorMessage = error.data.mensaje;
        }

        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: errorMessage
        });
    });
}
});
</script>
@endsection

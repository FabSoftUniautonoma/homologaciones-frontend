@extends('admin.layouts.appadmin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="title-section mx-auto" style="max-width: 800px;">
        <h1 class="fw-bold text-center" style="color: #1a3a6c; border-bottom: 3px solid #ccd2dd; padding-bottom: 12px; margin-bottom: 8px;">
            Crear Nuevo Usuario
        </h1>
        <p class="text-muted lead text-center">Complete el formulario para crear un nuevo usuario</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="formCrearUsuario">
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
                        <i class="fas fa-save"></i> Guardar Usuario
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

    // Cargar datos para los select
    cargarInstituciones();
    cargarFacultades();
    cargarPaises();
    cargarRoles();

    // Manejar envío del formulario
    document.getElementById('formCrearUsuario').addEventListener('submit', function(e) {
        e.preventDefault();
        crearUsuario();
    });

    // Eventos para cargar departamentos y municipios
    document.getElementById('pais_id').addEventListener('change', function() {
        cargarDepartamentos(this.value);
    });

    document.getElementById('departamento_id').addEventListener('change', function() {
        cargarMunicipios(this.value);
    });

    function crearUsuario() {
        // Obtener todos los datos del formulario
        const formData = new FormData(document.getElementById('formCrearUsuario'));
        const userData = {};

        // Convertir FormData a objeto JSON
        for (let [key, value] of formData.entries()) {
            // Convertir strings vacíos a null para campos opcionales
            userData[key] = value === '' ? null : value;
        }

        // Enviar petición a la API
        fetch(`${API_URL}/usuarios`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                text: data.mensaje || 'Usuario creado correctamente',
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
                text: 'No se pudo crear el usuario. Por favor, verifica los datos e intenta nuevamente.'
            });
        });
    }

    // Funciones para cargar los datos en los selects
    function cargarInstituciones() {
        // Aquí implementarías la llamada a la API para obtener instituciones
        // Por ahora, simulamos algunos datos
        const instituciones = [
            { id: 1, nombre: 'Universidad Nacional' },
            { id: 2, nombre: 'Universidad de Antioquia' },
            { id: 3, nombre: 'Universidad de los Andes' }
        ];

        const select = document.getElementById('institucion_origen_id');
        instituciones.forEach(institucion => {
            const option = document.createElement('option');
            option.value = institucion.id;
            option.textContent = institucion.nombre;
            select.appendChild(option);
        });
    }

    function cargarFacultades() {
        // Implementar llamada a API para facultades
        const facultades = [
            { id: 1, nombre: 'Ingeniería' },
            { id: 2, nombre: 'Medicina' },
            { id: 3, nombre: 'Ciencias Sociales' }
        ];

        const select = document.getElementById('facultad_id');
        facultades.forEach(facultad => {
            const option = document.createElement('option');
            option.value = facultad.id;
            option.textContent = facultad.nombre;
            select.appendChild(option);
        });
    }

    function cargarPaises() {
        // Implementar llamada a API para países
        const paises = [
            { id: 1, nombre: 'Colombia' },
            { id: 2, nombre: 'Ecuador' },
            { id: 3, nombre: 'México' }
        ];

        const select = document.getElementById('pais_id');
        paises.forEach(pais => {
            const option = document.createElement('option');
            option.value = pais.id;
            option.textContent = pais.nombre;
            select.appendChild(option);
        });
    }

    function cargarDepartamentos(paisId) {
        if (!paisId) return;

        // Implementar llamada a API para departamentos
        const departamentos = [
            { id: 1, nombre: 'Antioquia', pais_id: 1 },
            { id: 2, nombre: 'Cundinamarca', pais_id: 1 },
            { id: 3, nombre: 'Valle del Cauca', pais_id: 1 }
        ];

        const select = document.getElementById('departamento_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        departamentos.filter(d => d.pais_id == paisId).forEach(departamento => {
            const option = document.createElement('option');
            option.value = departamento.id;
            option.textContent = departamento.nombre;
            select.appendChild(option);
        });
    }

    function cargarMunicipios(departamentoId) {
        if (!departamentoId) return;

        // Implementar llamada a API para municipios
        const municipios = [
            { id: 1, nombre: 'Medellín', departamento_id: 1 },
            { id: 2, nombre: 'Envigado', departamento_id: 1 },
            { id: 3, nombre: 'Bogotá', departamento_id: 2 }
        ];

        const select = document.getElementById('municipio_id');
        select.innerHTML = '<option value="">Seleccione...</option>';

        municipios.filter(m => m.departamento_id == departamentoId).forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.id;
            option.textContent = municipio.nombre;
            select.appendChild(option);
        });
    }

    function cargarRoles() {
        // Implementar llamada a API para roles
        const roles = [
            { id: 1, nombre: 'Usuario' },
            { id: 2, nombre: 'Vicerrector' },
            { id: 3, nombre: 'Administrador' }
        ];

        const select = document.getElementById('rol_id');
        roles.forEach(rol => {
            const option = document.createElement('option');
            option.value = rol.id;
            option.textContent = rol.nombre;
            select.appendChild(option);
        });
    }
});
</script>
@endsection

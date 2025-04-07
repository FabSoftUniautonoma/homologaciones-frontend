@extends('admin.layouts.app')
@section('title', 'Gestión de Instituciones')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Instituciones</h1>
        <a href="{{ url('/admin/instituciones/crear') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Institución
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5>Lista de Instituciones</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar institución...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Código SNIES</th>
                            <th>Ubicación</th>
                            <th>Programas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Universidad Nacional de Colombia</td>
                            <td>1101</td>
                            <td>Bogotá, Colombia</td>
                            <td>25</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Universidad de los Andes</td>
                            <td>1204</td>
                            <td>Bogotá, Colombia</td>
                            <td>18</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Universidad del Valle</td>
                            <td>1305</td>
                            <td>Cali, Colombia</td>
                            <td>15</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                        @extends('layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Crear Institución</h1>
        <a href="{{ url('/admin/instituciones') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="institucionForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre de la Institución <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el nombre de la institución.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="codigo_snies" class="form-label">Código SNIES <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="codigo_snies" name="codigo_snies" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el código SNIES.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="pais" class="form-label">País <span class="text-danger">*</span></label>
                        <select class="form-select" id="pais" name="pais" required>
                            <option value="">Seleccione un país</option>
                            <option value="colombia" selected>Colombia</option>
                            <option value="argentina">Argentina</option>
                            <option value="mexico">México</option>
                            <option value="peru">Perú</option>
                            <option value="chile">Chile</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un país.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="departamento" class="form-label">Departamento <span class="text-danger">*</span></label>
                        <select class="form-select" id="departamento" name="departamento" required>
                            <option value="">Seleccione un departamento</option>
                            <option value="bogota">Bogotá D.C.</option>
                            <option value="antioquia">Antioquia</option>
                            <option value="valle">Valle del Cauca</option>
                            <option value="atlantico">Atlántico</option>
                            <option value="santander">Santander</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un departamento.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ciudad" class="form-label">Ciudad <span class="text-danger">*</span></label>
                        <select class="form-select" id="ciudad" name="ciudad" required>
                            <option value="">Seleccione una ciudad</option>
                            <option value="bogota">Bogotá</option>
                            <option value="medellin">Medellín</option>
                            <option value="cali">Cali</option>
                            <option value="barranquilla">Barranquilla</option>
                            <option value="bucaramanga">Bucaramanga</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione una ciudad.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="sitio_web" class="form-label">Sitio Web</label>
                    <input type="url" class="form-control" id="sitio_web" name="sitio_web" placeholder="https://www.ejemplo.edu.co">
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Limpiar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarInstitucion">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="page-header">
        <h4 class="page-title">Sistema de Gestión de Homologaciones</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="fas fa-chevron-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Coordinador</a>
            </li>
            <li class="separator">
                <i class="fas fa-chevron-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Solicitudes</a>
            </li>
        </ul>
    </div>

    <!-- Panel de estadísticas -->
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-primary card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Total</p>
                                <h4 class="card-title">45</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-warning card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Pendientes</p>
                                <h4 class="card-title">15</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-info card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">En revisión</p>
                                <h4 class="card-title">8</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-success card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <p class="card-category">Aprobadas</p>
                                <h4 class="card-title">22</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de filtrado -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filtros de búsqueda</h4>
            <div class="card-category">Busca y filtra solicitudes de homologación</div>
        </div>
        <div class="card-body">
            <form id="filtroForm" class="row">
                <div class="col-md-3 form-group">
                    <label for="estado">Estado</label>
                    <select id="estado" class="form-control select2">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="en-revision">En revisión</option>
                        <option value="aprobada">Aprobadas</option>
                        <option value="rechazada">Rechazadas</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control" id="fecha">
                </div>
                <div class="col-md-3 form-group">
                    <label for="carrera">Carrera</label>
                    <select id="carrera" class="form-control select2">
                        <option value="">Todas</option>
                        <option value="ingenieria">Ingeniería</option>
                        <option value="medicina">Medicina</option>
                        <option value="derecho">Derecho</option>
                        <option value="psicologia">Psicología</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="estudiante">Estudiante</label>
                    <input type="text" class="form-control" id="estudiante" placeholder="Nombre o ID">
                </div>
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-primary btn-round">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <button type="reset" class="btn btn-light btn-round">
                        <i class="fa fa-undo"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de solicitudes -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Listado de Solicitudes</h4>
                <button class="btn btn-primary btn-round ml-auto">
                    <i class="fa fa-plus"></i> Nueva solicitud
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="solicitudes-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Carrera</th>
                            <th>Asignatura</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th style="width: 10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>HOM-2025-001</td>
                            <td>María González</td>
                            <td>Ingeniería Civil</td>
                            <td>Cálculo Diferencial</td>
                            <td>01/04/2025</td>
                            <td>
                                <span class="badge badge-warning">Pendiente</span>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <button type="button" data-toggle="tooltip" title="Ver detalles" class="btn btn-link btn-primary btn-lg">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" data-toggle="tooltip" title="Revisar" class="btn btn-link btn-info btn-lg">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>HOM-2025-002</td>
                            <td>Carlos Rodríguez</td>
                            <td>Medicina</td>
                            <td>Anatomía</td>
                            <td>31/03/2025</td>
                            <td>
                                <span class="badge badge-info">En revisión</span>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <button type="button" data-toggle="tooltip" title="Ver detalles" class="btn btn-link btn-primary btn-lg">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" data-toggle="tooltip" title="Revisar" class="btn btn-link btn-info btn-lg">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>HOM-2025-003</td>
                            <td>Pedro Sánchez</td>
                            <td>Derecho</td>
                            <td>Derecho Civil</td>
                            <td>30/03/2025</td>
                            <td>
                                <span class="badge badge-success">Aprobada</span>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <button type="button" data-toggle="tooltip" title="Ver detalles" class="btn btn-link btn-primary btn-lg">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" data-toggle="tooltip" title="Descargar" class="btn btn-link btn-success btn-lg">
                                        <i class="fa fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>HOM-2025-004</td>
                            <td>Ana López</td>
                            <td>Psicología</td>
                            <td>Psicología Clínica</td>
                            <td>29/03/2025</td>
                            <td>
                                <span class="badge badge-danger">Rechazada</span>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <button type="button" data-toggle="tooltip" title="Ver detalles" class="btn btn-link btn-primary btn-lg">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" data-toggle="tooltip" title="Descargar" class="btn btn-link btn-success btn-lg">
                                        <i class="fa fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>HOM-2025-005</td>
                            <td>Laura Martínez</td>
                            <td>Ingeniería</td>
                            <td>Programación</td>
                            <td>01/04/2025</td>
                            <td>
                                <span class="badge badge-warning">Pendiente</span>
                            </td>
                            <td>
                                <div class="form-button-action">
                                    <button type="button" data-toggle="tooltip" title="Ver detalles" class="btn btn-link btn-primary btn-lg">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="button" data-toggle="tooltip" title="Revisar" class="btn btn-link btn-info btn-lg">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <ul class="pagination pg-primary">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Anterior</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --azul-oscuro: #19407b;
        --azul-medio: #0075bf;
        --azul-claro: #08dcff;
        --blanco: #ffffff;
        --gris-claro: #f4f4f4;
        --borde: #dddddd;
        --sombra: rgba(0, 0, 0, 0.1);
        --rojo-error: #ff4d4d;
    }

    /* Personalización de colores para coincidir con la paleta */
    .btn-primary, .badge-primary, .bg-primary, .btn-primary:hover, .btn-primary:focus {
        background: var(--azul-medio) !important;
        border-color: var(--azul-medio) !important;
    }

    .text-primary {
        color: var(--azul-medio) !important;
    }

    .card .card-header {
        background-color: transparent;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: var(--borde);
    }

    .card {
        border-radius: 5px;
        background-color: var(--blanco);
        margin-bottom: 30px;
        box-shadow: 0 .125rem .25rem var(--sombra);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 .5rem 1rem var(--sombra);
    }

    .card-round {
        border-radius: 5px;
    }

    .btn {
        padding: 8px 15px;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px var(--sombra);
    }

    .btn-round {
        border-radius: 50px;
    }

    .page-title {
        color: var(--azul-oscuro);
        font-weight: 600;
        margin-bottom: 0;
    }

    .breadcrumbs {
        display: flex;
        align-items: center;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .breadcrumbs .nav-home {
        font-size: 16px;
        padding: 0 15px;
    }

    .breadcrumbs .separator {
        font-size: 10px;
        padding: 0 8px;
    }

    .breadcrumbs a {
        color: #575962;
        text-decoration: none;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(var(--azul-claro), 0.1);
    }

    .form-control {
        border: 1px solid var(--borde);
        padding: 8px 15px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--azul-medio);
        box-shadow: 0 0 5px rgba(0, 117, 191, 0.3);
    }

    .badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--borde);
    }

    .pg-primary .page-item.active .page-link {
        background-color: var(--azul-medio) !important;
        border-color: var(--azul-medio) !important;
    }

    .pg-primary .page-link {
        color: var(--azul-medio);
    }

    /* Animaciones y transiciones */
    .btn-link {
        transition: all 0.2s ease;
    }

    .btn-link:hover {
        transform: scale(1.15);
    }

    .card-stats {
        transition: all 0.3s ease;
    }

    .card-stats:hover {
        transform: translateY(-5px);
    }

    .card-stats .icon-big {
        font-size: 2.2rem;
    }

    /* Estilos para Select2 */
    .select2-container--default .select2-selection--single {
        border: 1px solid var(--borde);
        height: 42px;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
        padding-left: 15px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 10px;
    }

    /* Estilos para el panel de botones en la tabla */
    .form-button-action .btn-link {
        margin: 0 3px;
        padding: 8px;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>

<script>
    // Inicializar Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccionar opción",
            allowClear: true
        });

        // Inicializar tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Animación para las tarjetas de estadísticas
        $('.card-stats').each(function(index) {
            $(this).delay(100 * index).animate({opacity: 1}, 500);
        });
    });
</script>
@endsection

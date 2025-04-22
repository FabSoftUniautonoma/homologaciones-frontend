@extends('admin.layouts.appadmin')

@section('content')
<div class="d-flex justify-content-between align-items-center p-3 shadow-sm topbar">

<div class="container-fluid p-0">
    <div class="d-flex flex-column flex-md-row min-vh-100">
        <!-- Sidebar -->




        <!-- Contenido -->
        <div class="flex-fill">
            <!-- Topbar -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 shadow-sm">
               
                <h1 class="h5 mb-0 text-primary">Corporación Universitaria Autónoma del Cauca</h1>
                <div class="dropdown">

                </div>
            </div>

            <!-- Panel principal -->
            <div class="p-4">
                <div class="card shadow rounded-lg">
                    <div class="card-header bg-white">
                        <h4 class="text-primary">Panel de control</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Usuarios -->
                            <div class="col-md-6 mb-4">
                                <div class="quick-card text-center p-4 shadow rounded">
                                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                    <h5>Usuarios</h5>
                                    <p>Gestión de usuarios del sistema</p>
                                    <a href="{{ url('/admin/usuarios') }}" class="btn btn-primary">Acceder</a>
                                </div>
                            </div>
                            <!-- Instituciones -->
                            <div class="col-md-6 mb-4">
                                <div class="quick-card text-center p-4 shadow rounded">
                                    <i class="fas fa-university fa-2x text-primary mb-3"></i>
                                    <h5>Instituciones</h5>
                                    <p>Administración de instituciones</p>
                                    <a href="{{ url('/admin/programas') }}" class="btn btn-primary">Acceder</a>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            Bienvenido al Sistema. Seleccione una de las opciones del menú para comenzar a gestionar la información.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
  body, .container-fluid, .flex-fill {
    background-color: #050570 !important;
    color: #ffffff !important;
}

.sidebar, .sidebar.bg-primary {
    background-color: #031043 !important;
    color: #ffffff !important;
}

.card, .quick-card {
    background-color: #0a1a5a !important;
    color: white !important;
    border: none !important;
}

.card-header {
    background-color: #10246d !important;
    border-bottom: 1px solid #1b2f77 !important;
}

.btn-primary {
    background-color: #3c5eff !important;
    border-color: #3c5eff !important;
    color: white !important;
}

.btn-primary:hover {
    background-color: #2f4dcc !important;
    border-color: #2f4dcc !important;
}

.d-flex.justify-content-between.align-items-center.p-3.shadow-sm,
.topbar {
    background-color: #031043 !important;
    color: white !important;
}

.alert-info {
    background-color: #1e3d85 !important;
    color: #e0eaff !important;
    border-color: #173372 !important;
}

</style>
@endpush

@push('scripts')
<script>
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('toggleSidebar');

    if (openBtn && closeBtn && sidebar) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    }
</script>
@endpush

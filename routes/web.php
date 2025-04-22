<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

use App\Http\Controllers\HomologacionController;
use App\Http\Controllers\InstitucionesController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\UsuarioControllerApi;

use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de bienvenida
Route::get('/', function () {
    return view('admin.layouts.welcome');
});

// Home del usuario autenticado (index general)
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');



/*
|--------------------------------------------------------------------------
| RUTAS PARA ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/

// Registro del estudiante
Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante');
});

// Dashboard del aspirante
Route::get('/homologaciones/aspirante', function () {
    return view('admin.homologacionesaspirante.dashboardAspirante');
});

// Solicitud de homologación
Route::get('/homologaciones/solicitudhomologacion', function () {
    return view('admin.homologacionesaspirante.solicitudhomologacion');
});


/*
|--------------------------------------------------------------------------
| RUTAS PARA COORDINADOR
|--------------------------------------------------------------------------
*/

// Vista principal del coordinador (dashboard)
Route::get('/coordinador', [HomologacionController::class, 'obtenerDatosBack'])
    ->name('admin.homologacionescoordinador.index');

// Pantalla principal de coordinación
Route::get('/inicio/coordinacion', function () {
    return view('admin.homologacionescoordinador.pantallaprincipal');
})->name('admin.homologacionescoordinador.pantallaprincipal');

// Ver documentos de una solicitud
Route::get('/admin/homologacionescoordinador/{id}/documentos', [HomologacionController::class, 'verDocumentos'])
    ->name('admin.homologacionescoordinador.documentos');

// Procesar la homologación - ROUTE CORRECTED HERE
Route::get('/admin/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])
    ->name('admin.homologacionescoordinador.procesohomologacion');

// Guardar homologación procesada
Route::post('/homologaciones/guardar', [HomologacionController::class, 'guardarHomologacion'])
    ->name('admin.homologaciones.guardar');

// Reportes del coordinador
Route::get('/reportes', function () {
    return view('admin.homologacionescoordinador.reportes');
})->name('admin.homologacionescoordinador/reportes');

// Ruta para descargar PDF (usando controlador)
Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])->name('homologacion.pdf');

// Ruta nombrada para el home del admin
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

// Ruta para el dashboard del coordinador
Route::get('/coordinador', [HomologacionController::class, 'obtenerDatosBack']);

Route::get('/administrador', function () {
    return view('admin.homologacionesadministrador.administradorr');
});


Route::get('/administrador/instituciones', function () {
    return view('admin.homologacionesadministrador.instituciones');
});

Route::get('/programas_crear', function () {
    return view('admin.homologacionesadministrador.programas_crear');
});
Route::get('/programas', function () {
    return view('admin.homologacionesadministrador.programas');
});
Route::get('/roles', function () {
    return view('admin.homologacionesadministrador.roles');
});
Route::get('/usuarios', function () {
    return view('admin.homologacionesadministrador.usuarios');
});

Route::get('/usuarios_crear', function () {
    return view('admin.homologacionesadministrador.usuarios_crear');
});
// Notificaciones
Route::get('/notificaciones', function () {
    return view('admin.homologacionescoordinador.componentes.notificaciones');
});
Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])
    ->name('admin.homologaciones.actualizar');

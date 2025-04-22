<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;
use App\Http\Controllers\InstitucionesController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\UsuarioControllerApi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de bienvenida
Route::get('/', fn() => view('admin.layouts.welcome'));

// Ruta home general (usuario autenticado)
Route::get('/homologaciones/home', fn() => view('admin.indexusuario.index'))->name('homologaciones.home');

/*
|--------------------------------------------------------------------------
| RUTAS PARA ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/
Route::prefix('homologaciones')->group(function () {
    Route::get('/registroestudiante', fn() => view('admin.indexusuario.registroestudiante'));
    Route::get('/aspirante', fn() => view('admin.homologacionesaspirante.dashboardAspirante'));
    Route::get('/solicitudhomologacion', fn() => view('admin.homologacionesaspirante.solicitudhomologacion'));
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA COORDINADOR
|--------------------------------------------------------------------------
*/
Route::prefix('coordinador')->group(function () {
    Route::get('/', [HomologacionController::class, 'obtenerDatosBack'])->name('admin.homologacionescoordinador.index');
    Route::get('/inicio', fn() => view('admin.homologacionescoordinador.pantallaprincipal'))->name('admin.homologacionescoordinador.pantallaprincipal');
    Route::get('/notificaciones', fn() => view('admin.homologacionescoordinador.componentes.notificaciones'));
    Route::get('/documentos/{id}', [HomologacionController::class, 'verDocumentos'])->name('admin.homologacionescoordinador.documentos');
    Route::get('/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])->name('admin.homologacionescoordinador.procesohomologacion');
    Route::get('/reportes', fn() => view('admin.homologacionescoordinador.reportes'))->name('admin.homologacionescoordinador.reportes');
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA ADMINISTRADOR
|--------------------------------------------------------------------------
*/
Route::prefix('administrador')->group(function () {
    Route::get('/', fn() => view('admin.homologacionesadministrador.administradorr'));
    Route::get('/instituciones', fn() => view('admin.homologacionesadministrador.instituciones'));
    Route::get('/programas_crear', fn() => view('admin.homologacionesadministrador.programas_crear'));
    Route::get('/programas', fn() => view('admin.homologacionesadministrador.programas'));
    Route::get('/roles', fn() => view('admin.homologacionesadministrador.roles'));
    Route::get('/usuarios', fn() => view('admin.homologacionesadministrador.usuarios'));
    Route::get('/usuarios_crear', fn() => view('admin.homologacionesadministrador.usuarios_crear'));
});

/*
|--------------------------------------------------------------------------
| RUTAS FUNCIONALES / PROCESOS
|--------------------------------------------------------------------------
*/

// Guardar homologación
Route::post('/homologaciones/guardar', [HomologacionController::class, 'guardarHomologacion'])->name('admin.homologaciones.guardar');

// Descargar PDF
Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])->name('homologacion.pdf');

// Actualizar homologación
Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])->name('admin.homologaciones.actualizar');

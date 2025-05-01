<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;
use App\Http\Controllers\InstitucionesController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\PaisesControllerApi;

/*
|--------------------------------------------------------------------------
| RUTA RAÍZ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('admin.layouts.welcome');
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/

Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante');
})->name('admin.indexusuario.registroestudiante');


Route::get('/homologaciones/aspirante', function () {
    return view('admin.homologacionesaspirante.dashboardAspirante');
});
Route::get('/homologaciones/login', function () {
    return view('admin.indexusuario.login');
})->name('admin.indexusuario.login');


Route::get('/homologaciones/solicitudhomologacion', function () {
    return view('admin.homologacionesaspirante.solicitudhomologacion');
});

Route::post('/homologaciones/guardar', [HomologacionController::class, 'guardarHomologacion'])->name('admin.homologaciones.guardar');

/*
|--------------------------------------------------------------------------
| RUTAS PARA COORDINADOR
|--------------------------------------------------------------------------
*/

Route::get('/coordinador', [HomologacionController::class, 'obtenerDatosBack'])->name('admin.homologacionescoordinador.index');

Route::get('/coordinador/inicio', function () {
    return view('admin.homologacionescoordinador.pantallaprincipal');
})->name('admin.homologacionescoordinador.pantallaprincipal');

Route::get('/coordinador/notificaciones', function () {
    return view('admin.homologacionescoordinador.componentes.notificaciones');
});

Route::get('/coordinador/reportes', function () {
    return view('admin.homologacionescoordinador.reportes');
})->name('admin.homologacionescoordinador.reportes');

Route::get('/coordinador/documentos/{id}', [HomologacionController::class, 'verDocumentos'])->name('admin.homologacionescoordinador.documentos');

Route::get('/coordinador/informacion/{id}', [HomologacionController::class, 'verInformacion'])->name('homologacion.Informacion');

Route::get('/coordinador/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])->name('admin.homologacionescoordinador.procesohomologacion');

Route::get('/coordinador/descargar/{documento}', [HomologacionController::class, 'descargarDocumento'])->name('admin.homologacionescoordinador.descargar');

// routes/api.php

// Rutas de API para el proceso de homologación
Route::prefix('homologacion')->group(function () {
    Route::get('materias-cursadas/{solicitud_id}', 'HomologacionController@obtenerMateriasCursadas');
    Route::get('pensum', 'HomologacionController@obtenerPensum');
    Route::post('guardar-homologaciones', 'HomologacionController@guardarHomologaciones');
    Route::post('cerrar-proceso', 'HomologacionController@cerrarProcesoHomologacion');
});

// También necesitas incluir las rutas para la función de descargar documentos
Route::get('homologacion/descargar-documento/{documento}', 'HomologacionController@descargarDocumento')
    ->name('homologacion.descargar-documento');

/*
|--------------------------------------------------------------------------
| RUTAS PARA ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::get('/administrador', function () {
    return view('admin.homologacionesadministrador.administradorr');
});

// Instituciones
Route::get('/administrador/institucioness', [InstitucionesController::class, 'index'])->name('instituciones.index');
Route::get('/administrador/institucioness/{id}', [InstitucionesController::class, 'show'])->name('instituciones.show');

// Programas
Route::get('/administrador/programas', [ProgramasController::class, 'index'])->name('programas.index');
Route::get('/administrador/programas/crear', [ProgramasController::class, 'create'])->name('programas.create');
Route::get('/administrador/programas/{id}', [ProgramasController::class, 'show'])->name('programas.show');

// Asignaturas
Route::get('/administrador/asignaturas/{id}', [AsignaturasController::class, 'show'])->name('asignaturas.show');

// Paises
Route::get('/administrador/paises', [PaisesControllerApi::class, 'index'])->name('paises.index');

// Usuarios y Roles
Route::get('/administrador/roles', function () {
    return view('admin.homologacionesadministrador.roles');
});

Route::get('/administrador/usuarios', function () {
    return view('admin.homologacionesadministrador.usuarios');
});

Route::get('/administrador/usuarios_crear', function () {
    return view('admin.homologacionesadministrador.usuarios_crear');
});

/*
|--------------------------------------------------------------------------
| FUNCIONES COMPARTIDAS (TODOS LOS ROLES)
|--------------------------------------------------------------------------
*/

Route::get('/homologacion/{id}/documentos', [HomologacionController::class, 'verDocumentos'])->name('homologacion.documentos');

Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])->name('homologacion.pdf');

Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])->name('admin.homologaciones.actualizar');

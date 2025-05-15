<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SolicitudHomologacionController;
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
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

/*
|--------------------------------------------------------------------------
| ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/

Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

// Vista adicional del login del antiguo archivo
Route::get('/homologaciones/login', function () {
    return view('admin.indexusuario.login');
})->name('admin.indexusuario.login');

// Vista adicional para registrar estudiante
Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante');
})->name('admin.indexusuario.registroestudiante');

Route::prefix('homologaciones')->group(function () {
    Route::get('/aspirante', function () {
        return view('admin.homologacionesaspirante.dashboardAspirante');
    })->name('homologaciones.aspirante');

    // Usamos el controlador como en el primer archivo (versión funcional)
    Route::get('/solicitudhomologacion', [SolicitudHomologacionController::class, 'index'])->name('admin.homologacionesaspirante.solicitudhomologacion');

    Route::post('/guardar', [HomologacionController::class, 'guardarHomologacion'])->name('admin.homologaciones.guardar');
});

/*
|--------------------------------------------------------------------------
| COORDINADOR
|--------------------------------------------------------------------------
*/

Route::prefix('coordinador')->group(function () {
    Route::get('/', [HomologacionController::class, 'obtenerDatosBack'])->name('admin.homologacionescoordinador.index');

    Route::get('/inicio', function () {
        return view('admin.homologacionescoordinador.pantallaprincipal');
    })->name('admin.homologacionescoordinador.pantallaprincipal');

    Route::get('/notificaciones', function () {
        return view('admin.homologacionescoordinador.componentes.notificaciones');
    });

    Route::get('/reportes', function () {
        return view('admin.homologacionescoordinador.reportes');
    })->name('admin.homologacionescoordinador.reportes');

    Route::get('/documentos/{id}', [HomologacionController::class, 'verDocumentos'])->name('admin.homologacionescoordinador.documentos');

    Route::get('/informacion/{id}', [HomologacionController::class, 'verInformacion'])->name('homologacion.Informacion');

    Route::get('/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])->name('admin.homologacionescoordinador.procesohomologacion');

    Route::get('/descargar/{documento}', [HomologacionController::class, 'descargarDocumento'])->name('admin.homologacionescoordinador.descargar');
});

/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::prefix('administrador')->group(function () {
    Route::get('/', function () {
        return view('admin.homologacionesadministrador.administradorr');
    });

    // Instituciones
    Route::get('/institucioness', [InstitucionesController::class, 'index'])->name('instituciones.index');
    Route::get('/institucioness/{id}', [InstitucionesController::class, 'show'])->name('instituciones.show');

    // Programas
    Route::get('/programas', [ProgramasController::class, 'index'])->name('programas.index');
    Route::get('/programas/crear', [ProgramasController::class, 'create'])->name('programas.create');
    Route::get('/programas/{id}', [ProgramasController::class, 'show'])->name('programas.show');

    // Asignaturas
    Route::get('/asignaturas/{id}', [AsignaturasController::class, 'show'])->name('asignaturas.show');

    // Paises
    Route::get('/paises', [PaisesControllerApi::class, 'index'])->name('paises.index');

    // Usuarios y Roles
    Route::get('/roles', function () {
        return view('admin.homologacionesadministrador.roles');
    });

    Route::get('/usuarios', function () {
        return view('admin.homologacionesadministrador.usuarios');
    });

    Route::get('/usuarios_crear', function () {
        return view('admin.homologacionesadministrador.usuarios_crear');
    });
});

/*
|--------------------------------------------------------------------------
| API DE HOMOLOGACIONES
|--------------------------------------------------------------------------
*/

Route::prefix('homologacion')->group(function () {
    Route::get('materias-cursadas/{solicitud_id}', [HomologacionController::class, 'obtenerMateriasCursadas']);
    Route::get('pensum', [HomologacionController::class, 'obtenerPensum']);
    Route::post('guardar-homologaciones', [HomologacionController::class, 'guardarHomologaciones']);
    Route::post('cerrar-proceso', [HomologacionController::class, 'cerrarProcesoHomologacion']);

    Route::get('descargar-documento/{documento}', [HomologacionController::class, 'descargarDocumento'])
        ->name('homologacion.descargar-documento');
});

/*
|--------------------------------------------------------------------------
| FUNCIONES COMPARTIDAS ENTRE ROLES
|--------------------------------------------------------------------------
*/

Route::get('/homologacion/{id}/documentos', [HomologacionController::class, 'verDocumentos'])->name('homologacion.documentos');

Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])->name('homologacion.pdf');

Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])->name('admin.homologaciones.actualizar');

/*
|--------------------------------------------------------------------------
| DASHBOARD API
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
    Route::get('/usuarios/{id}', [DashboardController::class, 'obtenerPerfilUsuario']);
    Route::get('/solicitudes/usuario/{usuarioId}', [DashboardController::class, 'obtenerSolicitudesUsuario']);
    Route::get('/solicitudes/{id}', [DashboardController::class, 'obtenerDetalleSolicitud']);
    Route::put('/usuarios/{id}', [DashboardController::class, 'actualizarPerfilUsuario']);
    Route::get('/solicitud-asignaturas/{id}', [DashboardController::class, 'obtenerSolicitudAsignaturas']);
});

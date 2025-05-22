<?php

use App\Http\Controllers\HomologacionViceController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SolicitudHomologacionController;
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
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('admin.auth.register');
    })->name('register');
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/

// Pantalla principal
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

// Dashboard aspirante
Route::get('/homologaciones/aspirante', function () {
    return view('admin.homologacionesaspirante.dashboardAspirante');
})->name('homologaciones.aspirante');

// Solicitud homologación - ruta controlada por controlador
Route::get('/homologaciones/solicitudhomologacion', [SolicitudHomologacionController::class, 'index'])
    ->name('homologaciones.solicitud');

// Guardar solicitud homologación
Route::post('/homologaciones/guardar', [HomologacionController::class, 'guardarHomologacion'])
    ->name('admin.homologaciones.guardar');


/*
|--------------------------------------------------------------------------
| RUTAS PARA COORDINADOR
|--------------------------------------------------------------------------
*/

// Panel principal del coordinador
Route::prefix('coordinador')->group(function () {
    // Dashboard principal
    /* Route::get('/', [HomologacionController::class, 'obtenerDatosBack'])
        ->name('admin.homologacionescoordinador.index'); */

    Route::get('/inicio', function () {
        return view('admin.homologacionescoordinador.pantallaprincipal');
    })->name('admin.homologacionescoordinador.pantallaprincipal');

    // Notificaciones y reportes
    Route::get('/notificaciones', function () {
        return view('admin.homologacionescoordinador.componentes.notificaciones');
    });

    Route::get('/reportes', function () {
        return view('admin.homologacionescoordinador.reportes');
    })->name('admin.homologacionescoordinador.reportes');


    // Gestión de documentos y procesos
    Route::get('/documentos/{id}', [HomologacionController::class, 'verDocumentos'])
        ->name('admin.homologacionescoordinador.documentos');


    Route::get('/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])
        ->name('admin.homologacionescoordinador.procesohomologacion');

    Route::get('/descargar/{documento}', [HomologacionController::class, 'descargarDocumento'])
        ->name('admin.homologacionescoordinador.descargar');


    // Página principal del coordinador (lista general de homologaciones)
    Route::get('/admin/homologacionescoordinador', [HomologacionController::class, 'obtenerDatosBack'])
        ->name('admin.homologacionescoordinador.index');
    // Ver información de homologación individual (usada fuera del módulo admin)
    Route::get('/informacion/{id}', [HomologacionController::class, 'verInformacion'])
        ->name('homologacion.informacion');



    // Ver información detallada por número de radicado
    Route::get('/admin/homologacionescoordinador/ver/{radicado}', [HomologacionController::class, 'verInformacion'])
        ->name('admin.homologacionescoordinador.informacionhomologacionusuario');

    // Actualizar el estado de una solicitud - Acepta tanto radicado como ID
    Route::match(['put', 'post', 'patch'], '/admin/homologacionescoordinador/actualizar-estado/{identificador}', [HomologacionController::class, 'actualizarEstado'])
        ->name('admin.homologacionescoordinador.actualizarestado');


});

/*
|--------------------------------------------------------------------------
| RUTAS PARA ADMINISTRADOR
|--------------------------------------------------------------------------
*/

        Route::get('/administrador', function () {
        return view('admin.homologacionesadministrador.administrador');
        })->name('administrador');

        Route::get('/crearinsti', function () {
            return view('admin.homologacionesadministrador.crearinsti');
        }) ->name('crearinsti');

        Route::get('/asignaturas', function () {
            return view('admin.homologacionesadministrador.asignaturas');
        }) ->name('asignaturas');



       Route::get('/administrador/usuarios', function () {
       return view('admin.homologacionesadministrador.usuarios');
        })->name('administrador/usuarios');

        Route::get('/usuarioscrear', function () {
            return view('admin.homologacionesadministrador.usuarios_crear');
          })->name('usuarioscrear');

        Route::get('/sinstituciones', function () {
        return view('admin.homologacionesadministrador.sinstituciones');
         })->name('sinstituciones');

       Route::get('/crearasignatura', function () {
            return view('admin.homologacionesadministrador.asignaturas');
        })->name('crearasignatura');


/*
/*
|--------------------------------------------------------------------------
| RUTAS API PARA PROCESOS DE HOMOLOGACIÓN
|--------------------------------------------------------------------------
*/

/* Route::prefix('homologacion')->group(function () {
    Route::get('materias-cursadas/{solicitud_id}', [HomologacionController::class, 'obtenerMateriasCursadas']);
    Route::get('pensum', [HomologacionController::class, 'obtenerPensum']);
    Route::post('guardar-homologaciones', [HomologacionController::class, 'guardarHomologaciones']);
    Route::post('cerrar-proceso', [HomologacionController::class, 'cerrarProcesoHomologacion']);
    Route::get('descargar-documento/{documento}', [HomologacionController::class, 'descargarDocumento'])
        ->name('homologacion.descargar-documento');
}); */

/*
|--------------------------------------------------------------------------
| RUTAS PARA PROCESOS DE HOMOLOGACIÓN DE VICERRECTORIA
|--------------------------------------------------------------------------
*/
Route::prefix('homologaciones-vicerrectoria')
    ->name('admin.homologaciones.vice.')
    ->group(function () {
        Route::get('/inicio', [HomologacionViceController::class, 'index'])->name('index');
        Route::get('/reportes', [HomologacionViceController::class, 'verReportes'])->name('reportes');
        Route::get('/vicerrector', [HomologacionViceController::class, 'obtenerDatosBack']) ->name('procesohomologacion.vicerrectoria'); // CUIDADO ESTA DOS VECES
         Route::get('/documentos/{radicado}', [HomologacionViceController::class, 'verDocumentos'])->name('documentos');
        Route::get('/informacion/{radicado}', [HomologacionViceController::class, 'verInformacion'])->name('informacion');
        Route::get('/homologaciones/{id}/proceso', [HomologacionViceController::class, 'vicerrectoria'])->name('procesohomologacion.vicerrectoria');
    });

/*
|--------------------------------------------------------------------------
| FUNCIONES COMPARTIDAS (TODOS LOS ROLES)
|--------------------------------------------------------------------------
*/

/* // Documentos y PDFs
Route::get('/homologacion/{id}/documentos', [HomologacionController::class, 'verDocumentos'])
    ->name('homologacion.documentos');

Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])
    ->name('homologacion.pdf');

// Actualización de homologaciones
Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])
    ->name('admin.homologaciones.actualizar');
 */

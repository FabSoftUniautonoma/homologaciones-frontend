<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;
use App\Http\Controllers\Admin\HomologacionViceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SolicitudHomologacionController;

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
        return view('admin.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('admin.auth.register');
    })->name('register');
});


//Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| RUTAS PARA ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/

// Grupo de rutas para aspirantes/estudiantes
Route::prefix('homologaciones')->group(function () {
    // Pantalla principal
    Route::get('/home', function () {
        return view('admin.indexusuario.index');
    })->name('homologaciones.home'); //->middleware('auth');

    // Registro de estudiante
    Route::get('/registroestudiante', function () {
        return view('admin.indexusuario.registroestudiante');
    })->name('admin.indexusuario.registroestudiante');

    // Dashboard y solicitudes
    Route::get('/aspirante', function () {
        return view('admin.homologacionesaspirante.dashboardAspirante');
    })->name('homologaciones.aspirante'); //->middleware('auth', 'role:aspirante');

    Route::get('/solicitudhomologacion', [SolicitudHomologacionController::class, 'index'])
        ->name('admin.homologacionesaspirante.solicitudhomologacion'); //->middleware('auth', 'role:aspirante');

    Route::post('/guardar', [HomologacionController::class, 'guardarHomologacion'])
        ->name('admin.homologaciones.guardar'); //->middleware('auth', 'role:aspirante');
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA COORDINADOR
|--------------------------------------------------------------------------
*/

Route::prefix('coordinador')
    //->middleware(['auth', 'role:coordinador']) // Descomentar en producción
    ->group(function () {
        // Dashboard principal
        Route::get('/', [HomologacionController::class, 'obtenerDatosBack'])
            ->name('admin.homologacionescoordinador.index');

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

        Route::get('/informacion/{id}', [HomologacionController::class, 'verInformacion'])
            ->name('homologacion.Informacion');

        Route::get('/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])
            ->name('admin.homologacionescoordinador.procesohomologacion');

        Route::get('/descargar/{documento}', [HomologacionController::class, 'descargarDocumento'])
            ->name('admin.homologacionescoordinador.descargar');
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA VICERRECTORÍA
|--------------------------------------------------------------------------
*/

Route::prefix('homologaciones-vicerrectoria')
    ->name('admin.homologaciones.vice.')
    //->middleware(['auth', 'role:vicerrector']) // Descomentar en producción
    ->group(function () {
        // Dashboard principal
        Route::get('/inicio', [HomologacionViceController::class, 'obtenerDatosBack'])->name('index');

        // Notificaciones y reportes
        Route::get('/notificaciones', function () {
            return view('admin.homologacionesvice.componentes.notificaciones');
        })->name('notificaciones');

        Route::get('/reportes', [HomologacionViceController::class, 'verReportes'])
            ->name('reportes');

        // Gestión de documentos y procesos
        Route::get('/documentos/{radicado}', [HomologacionViceController::class, 'verDocumentos'])
            ->name('documentos');

        Route::get('/informacion/{radicado}', [HomologacionViceController::class, 'verInformacion'])
            ->name('informacion');

        Route::get('/homologaciones/{id}/proceso', [HomologacionViceController::class, 'procesarHomologacion'])
            ->name('procesohomologacion');
});

/*
|--------------------------------------------------------------------------
| RUTAS PARA ADMINISTRADOR
|--------------------------------------------------------------------------
*/

   Route::get('/administrador', function () {
            return view('admin.homologacionesadministrador.administrador');
        });

        Route::get('/crearinsti', function () {
            return view('admin.homologacionesadministrador.crearinsti');
        });



        Route::get('/administrador/usuarios', function () {
            return view('admin.homologacionesadministrador.usuarios');
        });

        Route::get('/usuarioscrear', function () {
            return view('admin.homologacionesadministrador.usuarios_crear');
        });


/*
|--------------------------------------------------------------------------
| RUTAS API PARA PROCESOS DE HOMOLOGACIÓN
|--------------------------------------------------------------------------
*/

Route::prefix('homologacion')
    //->middleware('auth') // Descomentar en producción
    ->group(function () {
        Route::get('materias-cursadas/{solicitud_id}', [HomologacionController::class, 'obtenerMateriasCursadas']);
        Route::get('pensum', [HomologacionController::class, 'obtenerPensum']);
        Route::post('guardar-homologaciones', [HomologacionController::class, 'guardarHomologaciones']);
        Route::post('cerrar-proceso', [HomologacionController::class, 'cerrarProcesoHomologacion']);
        Route::get('descargar-documento/{documento}', [HomologacionController::class, 'descargarDocumento'])
            ->name('homologacion.descargar-documento');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD API
|--------------------------------------------------------------------------
*/
Route::prefix('api')
    //->middleware('auth') // Descomentar en producción
    ->group(function () {
        Route::get('/usuarios/{id}', [DashboardController::class, 'obtenerPerfilUsuario']);
        Route::get('/solicitudes/usuario/{usuarioId}', [DashboardController::class, 'obtenerSolicitudesUsuario']);
        Route::get('/solicitudes/{id}', [DashboardController::class, 'obtenerDetalleSolicitud']);
        Route::put('/usuarios/{id}', [DashboardController::class, 'actualizarPerfilUsuario']);
        Route::get('/solicitud-asignaturas/{id}', [DashboardController::class, 'obtenerSolicitudAsignaturas']);
});

/*
|--------------------------------------------------------------------------
| FUNCIONES COMPARTIDAS (TODOS LOS ROLES)
|--------------------------------------------------------------------------
*/

Route::group([], function () {
    // Documentos y PDFs
    Route::get('/homologacion/{id}/documentos', [HomologacionController::class, 'verDocumentos'])
        ->name('homologacion.documentos'); //->middleware('auth');

    Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])
        ->name('homologacion.pdf'); //->middleware('auth');

    // Actualización de homologaciones
    Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])
        ->name('admin.homologaciones.actualizar'); //->middleware('auth');
});

<?php


use App\Http\Controllers\HomologacionViceController;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;
use App\Http\Controllers\InstitucionesController;
use App\Http\Controllers\ProgramasController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\PaisesControllerApi;



/*
|--------------------------------------------------------------------------
| RUTAS PRINCIPALES
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


/*
|--------------------------------------------------------------------------
| RUTAS PARA ASPIRANTES / ESTUDIANTES
|--------------------------------------------------------------------------
*/

// Pantalla principal
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');


Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('admin.auth.register');
    })->name('register');
});

// Dashboard y solicitudes
Route::get('/homologaciones/aspirante', function () {
    return view('admin.homologacionesaspirante.dashboardAspirante');
});

Route::get('/homologaciones/solicitudhomologacion', function () {
    return view('admin.homologacionesaspirante.solicitudhomologacion');
});

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


    Route::get('/homologaciones/{id}/proceso', [HomologacionController::class, 'procesarHomologacion'])
        ->name('admin.homologacionescoordinador.procesohomologacion');

    Route::get('/descargar/{documento}', [HomologacionController::class, 'descargarDocumento'])
        ->name('admin.homologacionescoordinador.descargar');


    // Página principal del coordinador (lista general de homologaciones)
    Route::get('/admin/homologacionescoordinador', [HomologacionController::class, 'obtenerDatosBack'])
        ->name('admin.homologacionescoordinador.index');
    // Ver información de homologación individual (usada fuera del módulo admin)
    Route::get('/informacion/{id}', [HomologacionController::class, 'verInformacion'])
        ->name('homologacion.Informacion');



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

Route::prefix('administrador')->group(function () {
    // Dashboard principal
    Route::get('/', function () {
        return view('admin.homologacionesadministrador.administradorr');
    });

    // Gestión de instituciones
    Route::get('/institucioness', [InstitucionesController::class, 'index'])
        ->name('instituciones.index');
    Route::get('/institucioness/{id}', [InstitucionesController::class, 'show'])
        ->name('instituciones.show');

    // Gestión de programas
    Route::get('/programas', [ProgramasController::class, 'index'])
        ->name('programas.index');
    Route::get('/programas/crear', [ProgramasController::class, 'create'])
        ->name('programas.create');
    Route::get('/programas/{id}', [ProgramasController::class, 'show'])
        ->name('programas.show');

    // Gestión de asignaturas
    Route::get('/asignaturas/{id}', [AsignaturasController::class, 'show'])
        ->name('asignaturas.show');

    // Gestión de países
    Route::get('/paises', [PaisesControllerApi::class, 'index'])
        ->name('paises.index');

    // Gestión de usuarios y roles
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
| RUTAS API PARA PROCESOS DE HOMOLOGACIÓN
|--------------------------------------------------------------------------
*/

Route::prefix('homologacion')->group(function () {
    Route::get('materias-cursadas/{solicitud_id}', 'HomologacionController@obtenerMateriasCursadas');
    Route::get('pensum', 'HomologacionController@obtenerPensum');
    Route::post('guardar-homologaciones', 'HomologacionController@guardarHomologaciones');
    Route::post('cerrar-proceso', 'HomologacionController@cerrarProcesoHomologacion');
    Route::get('descargar-documento/{documento}', 'HomologacionController@descargarDocumento')
        ->name('homologacion.descargar-documento');
});
/*
|--------------------------------------------------------------------------
| RUTAS API PARA PROCESOS DE HOMOLOGACIÓN DE VICERRECTORIA
|--------------------------------------------------------------------------
*/
// Ruta para el vice


Route::prefix('homologaciones-vicerrectoria')
    ->name('admin.homologaciones.vice.')
    ->group(function () {
        Route::get('/inicio', [HomologacionViceController::class, 'index'])->name('index');
        Route::get('/reportes', [HomologacionViceController::class, 'verReportes'])->name('reportes');
        Route::get('/vicerrector', [HomologacionViceController::class, 'obtenerDatosBack']) ->name('procesohomologacion.vicerrectoria');
         Route::get('/documentos/{radicado}', [HomologacionViceController::class, 'verDocumentos'])->name('documentos');
        Route::get('/informacion/{radicado}', [HomologacionViceController::class, 'verInformacion'])->name('informacion');
        Route::get('/homologaciones/{id}/proceso', [HomologacionViceController::class, 'vicerrectoria'])->name('procesohomologacion.vicerrectoria');
    });

/*
|--------------------------------------------------------------------------
| FUNCIONES COMPARTIDAS (TODOS LOS ROLES)
|--------------------------------------------------------------------------
*/

// Documentos y PDFs
Route::get('/homologacion/{id}/documentos', [HomologacionController::class, 'verDocumentos'])
    ->name('homologacion.documentos');

Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])
    ->name('homologacion.pdf');

// Actualización de homologaciones
Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])
    ->name('admin.homologaciones.actualizar');


Route::prefix('auth')->group(function () {
    // Vista de login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // Vista de registro
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

});

// RUTAS PROTEGIDAS POR TOKEN
Route::prefix('homologaciones')->group(function () {
    Route::get('/aspirante', function () {
        return view('admin.homologacionesaspirante.dashboardAspirante');
    })->name('homologaciones.aspirante');

    Route::get('/solicitudhomologacion', function () {
        return view('admin.homologacionesaspirante.solicitudhomologacion');
    })->name('homologaciones.solicitud');

    Route::post('/guardar', [HomologacionController::class, 'guardarHomologacion'])->name('admin.homologaciones.guardar');
});

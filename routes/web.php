<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider dentro del grupo
| que contiene el middleware "web".
|
*/

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas para usuarios
Route::get('/registro-usuario', function () {
    return view('admin.usuarios.register');
});

// Rutas de homologaciones para aspirantes
Route::get('/homologaciones/solicitudhomologacion', function () {
    return view('homologacionesaspirante.solicitudhomologacion');
});

Route::get('/homologaciones/aspirante', function () {
    return view('homologacionesaspirante.dashboardAspirante');
});

// Rutas de administración
Route::get('/admin', function () {
    return view('admin.usuarios.register');
});

// Rutas de index de usuario
Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante');
});

// Ruta nombrada para el home del admin
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

// Ruta para el dashboard del coordinador
Route::get('/coordinador', function () {
    return view('admin.homologacionescoordinador.coordinador');
});

// Ruta para ver la información de homologación con parámetro ID
Route::get('/homologacion/{id}', [HomologacionController::class, 'verInformacion'])
    ->name('homologacion.index');

    Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF']);

    
    Route::get('/notificaciones', function () {
        return view('admin.homologacionescoordinador.componentes.notificaciones');
    });

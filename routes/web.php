<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

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

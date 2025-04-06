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

// Rutas de homologaciones
Route::get('/homologaciones/solicitudhomologacion', function () {
    return view('homologacionesaspirante.solicitudhomologacion');
});
Route::get('/homologaciones/aspirante', function () {
    return view('homologacionesaspirante.dashboardAspirante');
});

// Rutas de index de usuario (corregidas)
Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante'); // <-- Corregido
});
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index'); // <-- Corregido
});

// Rutas de administración
Route::get('/admin', function () {
    return view('admin.usuarios.register');
});

// Rutas para coordinador
Route::get('/coordinador', function (): Factory|View {
    return view('admin.homologacionescoordinador.coordinador');
});

Route::get('/homologaciones/administrador/nuevo', function () {
    $usuarios = []; // Puedes reemplazar esto con una consulta real a la base de datos
    return view('admin.homologacionesadministrador.nuevo', compact('usuarios'));
});



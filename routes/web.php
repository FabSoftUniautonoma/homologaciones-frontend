<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro-usuario', function () {
    return view('admin.usuarios.register');
});

Route::get('/registro-homo', function () {
    return view('homologaciones.registro');
});

Route::get('/visuali', function () {
    return view('homologaciones.visualizacion');
});

Route::get('/infor', function () {
    return view('homologaciones.info');
});

Route::get('/princi', function () {
    return view('homologaciones.principal');
});
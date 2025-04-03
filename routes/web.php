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
Route::get('/homologaciones/registro', function () {
    return view('homologaciones.registro');
});
Route::get('/homologaciones/visualizacion', function () {
    return view('homologaciones.visualizacion');
});
Route::get('/homologaciones/informacion', function () {
    return view('homologaciones.info');
});
Route::get('/homologaciones/principal', function () {
    return view('homologaciones.principal');
});

Route::get('/admin', function () {
    return view('admin.usuarios.register');
});

Route::get('/nuevo', function () {
    return view('admin.homologaciones.nuevo');
});

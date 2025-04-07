<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomologacionController;
use Illuminate\Support\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
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

// Rutas de index de usuario
Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante');
});

Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

// Ruta para el dashboard del coordinador
Route::get('/coordinador', function () {
    return view('admin.homologacionescoordinador.coordinador');
});

// Ruta de notificaciones
Route::get('/notificaciones', function () {
    return view('admin.homologacionescoordinador.componentes.notificaciones');
});

// Ruta para ver información de homologación (simulada)
Route::get('/homologacion/{id}', function ($id) {
    $solicitudes = [
        'HOM-2025-001' => [
            'id' => 1,
            'codigo' => 'HOM-2025-001',
            'nombre' => 'María González',
            'instituto' => 'FUP de Popayán',
            'fecha' => '01/04/2025',
            'estado' => 'Pendiente',
            'estado_class' => 'warning',
            'instituto_origen' => 'FUP de Popayán',
            'programa_origen' => 'Tecnología en Sistemas',
            'carrera_interes' => 'Ingeniería de Software',
            'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '01/04/2025'),
            'estudiante' => (object)[
                'tipo_identificacion' => 'Cédula de Ciudadanía',
                'numero_identificacion' => '1234567890',
                'nombre_completo' => 'María González',
                'correo' => 'maria@example.com',
                'telefono' => '3201234567',
                'nacionalidad' => 'Colombiana',
                'departamento' => 'Cauca',
                'municipio' => 'Popayán',
            ],
            'historial' => collect([
                (object)[
                    'accion' => 'Solicitud registrada',
                    'usuario' => 'admin',
                    'created_at' => now()->subDays(3)
                ],
                (object)[
                    'accion' => 'Documentos revisados',
                    'usuario' => 'coordinador',
                    'created_at' => now()->subDays(1)
                ]
            ])
        ],
        'HOM-2025-002' => [
            'id' => 2,
            'codigo' => 'HOM-2025-002',
            'nombre' => 'Carlos Rodríguez',
            'instituto' => 'SENA',
            'fecha' => '31/03/2025',
            'estado' => 'En revisión',
            'estado_class' => 'info',
            'instituto_origen' => 'SENA',
            'programa_origen' => 'Análisis y Desarrollo de Software',
            'carrera_interes' => 'Ingeniería de Software',
            'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '31/03/2025'),
            'estudiante' => (object)[
                'tipo_identificacion' => 'Tarjeta de Identidad',
                'numero_identificacion' => '987654321',
                'nombre_completo' => 'Carlos Rodríguez',
                'correo' => 'carlos@example.com',
                'telefono' => '3124567890',
                'nacionalidad' => 'Colombiano',
                'departamento' => 'Valle del Cauca',
                'municipio' => 'Cali',
            ],
            'historial' => collect([
                (object)[
                    'accion' => 'Solicitud registrada',
                    'usuario' => 'admin',
                    'created_at' => now()->subDays(4)
                ]
            ])
        ],
        'HOM-2025-003' => [
            'id' => 3,
            'codigo' => 'HOM-2025-003',
            'nombre' => 'Ana López',
            'instituto' => 'Colegio Mayor',
            'fecha' => '30/03/2025',
            'estado' => 'Aprobada',
            'estado_class' => 'success',
            'instituto_origen' => 'Colegio Mayor',
            'programa_origen' => 'Gestión Empresarial',
            'carrera_interes' => 'Administración de Empresas',
            'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '30/03/2025'),
            'estudiante' => (object)[
                'tipo_identificacion' => 'Cédula de Ciudadanía',
                'numero_identificacion' => '1122334455',
                'nombre_completo' => 'Ana López',
                'correo' => 'ana@example.com',
                'telefono' => '3112233445',
                'nacionalidad' => 'Colombiana',
                'departamento' => 'Antioquia',
                'municipio' => 'Medellín',
            ],
            'historial' => collect([])
        ],
        'HOM-2025-004' => [
            'id' => 4,
            'codigo' => 'HOM-2025-004',
            'nombre' => 'Luis Martínez',
            'instituto' => 'Universidad del Cauca',
            'fecha' => '29/03/2025',
            'estado' => 'Rechazada',
            'estado_class' => 'danger',
            'instituto_origen' => 'Universidad del Cauca',
            'programa_origen' => 'Derecho',
            'carrera_interes' => 'Ciencias Políticas',
            'fecha_solicitud' => Carbon::createFromFormat('d/m/Y', '29/03/2025'),
            'estudiante' => (object)[
                'tipo_identificacion' => 'Cédula de Extranjería',
                'numero_identificacion' => '77889900',
                'nombre_completo' => 'Luis Martínez',
                'correo' => 'luis@example.com',
                'telefono' => '3134455667',
                'nacionalidad' => 'Ecuatoriano',
                'departamento' => 'Nariño',
                'municipio' => 'Pasto',
            ],
            'historial' => collect([
                (object)[
                    'accion' => 'Solicitud rechazada',
                    'usuario' => 'admin',
                    'created_at' => now()->subDays(2)
                ]
            ])
        ],
    ];

    if (!array_key_exists($id, $solicitudes)) {
        abort(404);
    }
    $homologacion = (object) $solicitudes[$id];
    return view('admin.homologacionescoordinador.informacionhomologacionusuario', compact('homologacion'));

})->name('homologacion.index');


// Ruta para descargar PDF (usando controlador)
// Ruta para ver el formulario de registro de estudiante
Route::get('/homologaciones/registroestudiante', function () {
    return view('admin.indexusuario.registroestudiante');
});

// Ruta para descargar PDF (usando controlador)
Route::get('/homologacion/{id}/descargar', [HomologacionController::class, 'descargarPDF'])->name('homologacion.pdf');

// Ruta nombrada para el home del admin
Route::get('/homologaciones/home', function () {
    return view('admin.indexusuario.index');
})->name('homologaciones.home');

// Ruta para el dashboard del coordinador
Route::get('/coordinador', function () {
    return view('admin.homologacionescoordinador.coordinador');
});

// Notificaciones
Route::get('/notificaciones', function () {
    return view('admin.homologacionescoordinador.componentes.notificaciones');
});
Route::put('/admin/homologaciones/{id}', [HomologacionController::class, 'actualizar'])
    ->name('admin.homologaciones.actualizar');
    Route::get('/admin/homologaciones/{id}/documentos', [HomologacionController::class, 'verificarDocumentos'])
    ->name('admin.homologacionescoordinador.documentos');
    
    Route::post('/admin/homologaciones/{id}/iniciar', [HomologacionController::class, 'iniciarProceso'])
    ->name('admin.homologacionescoordinador.iniciar');

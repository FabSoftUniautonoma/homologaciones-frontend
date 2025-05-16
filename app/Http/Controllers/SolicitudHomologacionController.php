<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SolicitudHomologacionController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'http://127.0.0.1:8000/api';
    }

    public function index()
    {
        $endpoints = ['departamentos', 'municipios', 'instituciones', 'programas', 'asignaturas', 'paises'];
        $data = [];

        try {
            foreach ($endpoints as $endpoint) {
                $response = Http::get("{$this->apiUrl}/{$endpoint}");
                $data[$endpoint] = $response->successful() ? $response->json() : [];

                if (!$response->successful()) {
                    $data['error'] = ($data['error'] ?? 'Error al cargar datos: ') . "$endpoint, ";
                }
            }

            if (isset($data['error'])) {
                $data['error'] = rtrim($data['error'], ', ');
            }
        } catch (\Exception $e) {
            // Inicializar arrays vacíos para evitar errores en la vista
            array_fill_keys($endpoints, []);
            $data['error'] = 'Error al conectar con la API: ' . $e->getMessage();
        }

        return view('admin.homologacionesaspirante.solicitudhomologacion', $data);
    }

    public function store()
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:50',
            'numero_documento' => 'required|string|max:50',
            'telefono' => 'required|string|max:50',
            'correo' => 'required|email|max:255',
            'pais_id' => 'required|integer',
            'institucion_id' => 'required|integer',
            'programa_id' => 'required|integer',
            'asignatura_id' => 'required|integer',
        ];

        // Solo validar departamento y municipio si el país es Colombia (ID 1)
        if (request('pais_id') == 1) {
            $rules['departamento_id'] = 'required|integer';
            $rules['municipio_id'] = 'required|integer';
        }

        $data = request()->validate($rules);

        try {
            $response = Http::post("{$this->apiUrl}/solicitud-homologacion", $data);

            return $response->successful()
                ? redirect()->route('solicitudhomologacion.index')->with('success', 'Solicitud enviada con éxito.')
                : back()->with('error', 'Error al enviar la solicitud: ' . $response->status());
        } catch (\Exception $e) {
            return back()->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}

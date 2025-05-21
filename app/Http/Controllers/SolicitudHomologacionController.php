<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SolicitudHomologacionController extends Controller
{
    protected $apiUrl;
    protected $endpoints = ['departamentos', 'municipios', 'instituciones', 'programas', 'asignaturas', 'paises'];

    public function __construct()
    {
        $this->apiUrl = 'https://homologacionesback.educarenemociones.com/api';
    }

    protected function fetchProgramas()
    {
        try {
            // Intentar con filtro específico para la Autónoma
            $response = Http::get("{$this->apiUrl}/programas", [
                'institucion' => 'Autonoma'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Log para depuración
                Log::info("Programas obtenidos: " . json_encode($data));
                return isset($data['data']) ? $data['data'] : $data;
            } else {
                Log::warning("Error al obtener programas: " . $response->status());
                return [];
            }
        } catch (\Exception $e) {
            Log::error("Excepción al obtener programas: " . $e->getMessage());
            return [];
        }
    }

    public function index()
    {
        $data = [];
        $errors = [];

        try {
            // Cargar los endpoints básicos
            foreach ($this->endpoints as $endpoint) {
                if ($endpoint === 'programas') {
                    // Manejo especial para programas
                    $data[$endpoint] = $this->fetchProgramas();
                    continue;
                }

                $response = Http::get("{$this->apiUrl}/{$endpoint}");

                if ($response->successful()) {
                    $responseData = $response->json();
                    $data[$endpoint] = isset($responseData['data']) ? $responseData['data'] : $responseData;
                } else {
                    $data[$endpoint] = [];
                    $errors[] = $endpoint;
                }
            }

            // Cargar programas específicamente para la Autónoma
            $data['programasAutonoma'] = array_filter($data['programas'], function ($p) {
                return isset($p['institucion']) &&
                    (str_contains(strtolower($p['institucion']), 'autónoma') ||
                        str_contains(strtolower($p['institucion']), 'autonoma'));
            });

            Log::info("Programas Autónoma: " . count($data['programasAutonoma']));

            if (!empty($errors)) {
                $data['error'] = 'Error al cargar datos: ' . implode(', ', $errors);
            }
        } catch (\Exception $e) {
            // Inicializar arrays vacíos y registrar el error
            foreach ($this->endpoints as $endpoint) {
                $data[$endpoint] = [];
            }
            $data['programasAutonoma'] = [];
            $data['error'] = 'Error al conectar con la API: ' . $e->getMessage();
            Log::error('Error en SolicitudHomologacionController: ' . $e->getMessage());
        }

        return view('admin.homologacionesaspirante.solicitudhomologacion', $data);
    }

    public function store(Request $request)
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

        // Validar campos adicionales para Colombia
        if ($request->input('pais_id') == 1) {
            $rules['departamento_id'] = 'required|integer';
            $rules['municipio_id'] = 'required|integer';
        }

        $validatedData = $request->validate($rules);

        try {
            $response = Http::post("{$this->apiUrl}/solicitud-homologacion", $validatedData);

            if ($response->successful()) {
                return redirect()->route('solicitudhomologacion.index')
                    ->with('success', 'Solicitud enviada con éxito.');
            }

            return back()->with('error', 'Error al enviar la solicitud: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Error al enviar solicitud de homologación: ' . $e->getMessage());
            return back()->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}

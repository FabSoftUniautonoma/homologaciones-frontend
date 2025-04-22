<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstitucionesController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        // Configura la URL base de la API desde el archivo .env
        $this->apiUrl = env('API_URL', 'http://localhost:8000/api');
    }

    /**
     * Muestra la página principal con la lista de instituciones
     */
    public function index()
    {
        try {
            // Obtener todas las instituciones desde la API
            $response = Http::get($this->apiUrl . '/instituciones');

            if ($response->successful()) {
                $instituciones = $response->json();
                return view('instituciones.index', compact('instituciones'));
            } else {
                return view('instituciones.index', [
                    'instituciones' => [],
                    'error' => 'Error al cargar las instituciones: ' . $response->status()
                ]);
            }
        } catch (\Exception $e) {
            return view('instituciones.index', [
                'instituciones' => [],
                'error' => 'Error al conectar con la API: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Muestra los detalles de una institución con sus programas
     */
    public function show($id)
    {
        try {
            // Obtener la institución específica
            $responseInstitucion = Http::get($this->apiUrl . '/instituciones/' . $id);

            if (!$responseInstitucion->successful()) {
                return redirect()->route('instituciones.index')
                    ->with('error', 'No se pudo obtener la información de la institución');
            }

            $institucion = $responseInstitucion->json()['datos'];

            // Obtener todos los programas
            $responseProgramas = Http::get($this->apiUrl . '/programas');

            if (!$responseProgramas->successful()) {
                return view('instituciones.show', [
                    'institucion' => $institucion,
                    'programas' => [],
                    'error' => 'Error al cargar los programas'
                ]);
            }

            // Filtrar programas por institución (esto debería hacerse en la API idealmente)
            $programas = collect($responseProgramas->json())
                ->filter(function ($programa) use ($id) {
                    return $programa['institucion_id'] == $id;
                })->values()->all();

            return view('instituciones.show', compact('institucion', 'programas'));
        } catch (\Exception $e) {
            return redirect()->route('instituciones.index')
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}

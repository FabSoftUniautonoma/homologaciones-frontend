<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AsignaturasController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        // Configura la URL base de la API desde el archivo .env
        $this->apiUrl = env('API_URL', 'http://localhost:8000/api');
    }

    /**
     * Muestra los detalles de una asignatura
     */
    public function show($id)
    {
        try {
            // Obtener la asignatura específica
            $response = Http::get($this->apiUrl . '/asignaturas/' . $id);

            if (!$response->successful()) {
                return redirect()->back()
                    ->with('error', 'No se pudo obtener la información de la asignatura');
            }

            $asignatura = $response->json()['datos'];

            return view('asignaturas.show', compact('asignatura'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}

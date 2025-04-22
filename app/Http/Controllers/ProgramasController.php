<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProgramasController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        // Configura la URL base de la API desde el archivo .env
        $this->apiUrl = env('API_URL', 'http://localhost:8000/api');
    }

    /**
     * Muestra los detalles de un programa con sus asignaturas
     */
    public function show($id)
    {
        try {
            // Obtener el programa específico
            $responsePrograma = Http::get($this->apiUrl . '/programas/' . $id);

            if (!$responsePrograma->successful()) {
                return redirect()->back()
                    ->with('error', 'No se pudo obtener la información del programa');
            }

            $programa = $responsePrograma->json()['datos'];

            // Obtener todas las asignaturas
            $responseAsignaturas = Http::get($this->apiUrl . '/asignaturas');

            if (!$responseAsignaturas->successful()) {
                return view('programas.show', [
                    'programa' => $programa,
                    'asignaturas' => [],
                    'error' => 'Error al cargar las asignaturas'
                ]);
            }

            // Filtrar asignaturas por programa (esto debería hacerse en la API idealmente)
            $asignaturas = collect($responseAsignaturas->json())
                ->filter(function ($asignatura) use ($id) {
                    return $asignatura['programa_id'] == $id;
                })->values()->all();

            // Agrupar asignaturas por semestre
            $asignaturasPorSemestre = collect($asignaturas)->groupBy('semestre')->toArray();

            return view('programas.show', compact('programa', 'asignaturasPorSemestre'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }
}

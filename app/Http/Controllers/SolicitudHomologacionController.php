<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SolicitudHomologacionController extends Controller
{
    private $apiUrl;
    private $localUrl;

    public function __construct()
    {
        // URL pública del backend (puedes ponerlo en .env también si prefieres)
        $this->apiUrl = 'https://homologacionesback.educarenemociones.com/api';
        $this->localUrl = 'http://localhost/Backend-Laravel/public/api';
    }

    public function index()
    {
        try {
            $departamentosResponse = Http::get($this->apiUrl . '/departamentos');
            $municipiosResponse = Http::get($this->apiUrl . '/municipios');
            $institucionesResponse = Http::get($this->apiUrl . '/instituciones');
            $programasResponse = Http::get($this->apiUrl . '/programas');
            $asignaturasResponse = Http::get($this->apiUrl . '/asignaturas');

            if ($departamentosResponse->successful() && $municipiosResponse->successful() && $institucionesResponse->successful()  && $programasResponse->successful() && $asignaturasResponse->successful()) {
                $departamentos = $departamentosResponse->json();
                $municipios = $municipiosResponse->json();
                $instituciones = $institucionesResponse->json();
                $programas = $programasResponse->json();
                $asignaturas = $asignaturasResponse->json();

                return view('admin.homologacionesaspirante.solicitudhomologacion', compact('departamentos', 'municipios', 'instituciones', 'programas', 'asignaturas'));
            } else {
                return view('admin.homologacionesaspirante.solicitudhomologacion', [
                    'departamentos' => [],
                    'municipios' => [],
                    'instituciones' => [],
                    'programas' => [],
                    'asignaturas' => [],
                    'error' => 'Error al cargar datos: ' . $departamentosResponse->status() . ' / ' . $municipiosResponse->status() . ' / ' . $institucionesResponse->status() . ' / ' . $programasResponse->status() . ' / ' . $asignaturasResponse->status()
                ]);
            }
        } catch (\Exception $e) {
            return view('admin.homologacionesaspirante.solicitudhomologacion', [
                'departamentos' => [],
                'municipios' => [],
                'instituciones' => [],
                'programas' => [],
                'asignaturas' => [],
                'error' => 'Error al conectar con la API: ' . $e->getMessage()
            ]);
        }
    }

    public function store() {
        $data = request()->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'tipo_documento' => 'required|string|max:50',
            'numero_documento' => 'required|string|max:50',
            'telefono' => 'required|string|max:50',
            'correo' => 'required|email|max:255',
            'departamento_id' => 'required|integer',
            'municipio_id' => 'required|integer',
            'institucion_id' => 'required|integer',
            'programa_id' => 'required|integer',
            'asignatura_id' => 'required|integer',
        ]);

        try {
            $response = Http::post($this->apiUrl . '/solicitud-homologacion', $data);

            if ($response->successful()) {
                return redirect()->route('solicitudhomologacion.index')->with('success', 'Solicitud enviada con éxito.');
            } else {
                return redirect()->back()->with('error', 'Error al enviar la solicitud: ' . $response->status());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al conectar con la API: ' . $e->getMessage());
        }
    }




}
<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Solicitud;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PDF;

class HomologacionController extends Controller
{
    /**
     * Obtiene la URL base del backend.
     */
    private function getBaseUrl()
    {
        return rtrim(env('BASE_URL_BACKEND', 'https://homologacionesback.educarenemociones.com/api/'), '/') . '/';
    }

    /**
     * Realiza una solicitud HTTP de forma segura y con reintentos
     */
    private function safeApiCall($method, $endpoint, $data = [], $timeout = 30)
    {
        $baseUrl = $this->getBaseUrl();
        $url = $baseUrl . $endpoint;

        Log::info("Realizando solicitud $method a: $url");

        try {
            $httpClient = Http::timeout($timeout)
                ->withOptions([
                    'verify' => false, // Desactivar verificación SSL para debug
                    'connect_timeout' => 10,
                    'http_errors' => false
                ]);

            if ($method === 'GET') {
                $response = $httpClient->get($url, $data);
            } elseif ($method === 'POST') {
                $response = $httpClient->post($url, $data);
            } elseif ($method === 'PUT') {
                $response = $httpClient->put($url, $data);
            } else {
                $response = $httpClient->delete($url);
            }

            // Log detalles adicionales si la respuesta no es exitosa
            if (!$response->successful()) {
                Log::error("Error en la solicitud $method a $url: " .
                    "Status code: " . $response->status() .
                    ", Body: " . substr($response->body(), 0, 500));
            }

            return $response;
        } catch (\Exception $e) {
            Log::error("Excepción en la solicitud $method a $url: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualiza el estado de una solicitud.
     */
    public function actualizar(Request $request, $id)
    {
        try {
            $response = $this->safeApiCall('PUT', 'solicitudes/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Solicitud actualizada exitosamente');
            } else {
                return redirect()->back()->with('error', 'Error al actualizar la solicitud: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Excepción al actualizar solicitud: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al comunicarse con el servidor: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la información detallada de una solicitud.
     */
    public function verInformacion($radicado)
    {
        try {
            // Obtener todas las solicitudes
            $responseSolicitud = $this->safeApiCall('GET', 'solicitudes');

            if (!$responseSolicitud->successful()) {
                abort(500, 'Error al obtener datos de solicitudes: ' . $responseSolicitud->body());
            }

            $solicitudes = $responseSolicitud->json();

            // Buscar la solicitud con el número de radicado recibido
            $solicitud = collect($solicitudes)->firstWhere('numero_radicado', $radicado);

            if (!$solicitud) {
                Log::warning("Solicitud no encontrada con radicado: " . $radicado);
                abort(404, 'Solicitud no encontrada.');
            }

            // Obtener todos los usuarios
            $responseUsuario = $this->safeApiCall('GET', 'usuarios');

            if (!$responseUsuario->successful()) {
                abort(500, 'Error al obtener datos de usuarios: ' . $responseUsuario->body());
            }

            $usuarios = $responseUsuario->json();

            // Buscar el usuario asociado a la solicitud
            $usuario = collect($usuarios)->firstWhere('id', $solicitud['id_usuario'] ?? null);

            return view('admin.homologacionescoordinador.informacionhomologacionusuario', compact('solicitud', 'usuario'));

        } catch (\Exception $e) {
            Log::error("Error al obtener información de la solicitud o usuario: " . $e->getMessage());
            abort(500, 'Error al conectarse con el servidor de datos: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene datos del backend (solicitudes y usuarios) para la vista principal
     */
    public function obtenerDatosBack()
    {
        try {
            // Obtener todas las solicitudes
            $responseSolicitudes = $this->safeApiCall('GET', 'solicitudes');

            if (!$responseSolicitudes->successful()) {
                return view('admin.homologacionescoordinador.coordinador')->withErrors([
                    'error' => 'No se pudieron cargar los datos de solicitudes. Error: ' . $responseSolicitudes->body()
                ]);
            }

            $solicitudes = $responseSolicitudes->json();

            return view('admin.homologacionescoordinador.coordinador', [
                'solicitudes' => $solicitudes,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener datos del backend: ' . $e->getMessage());

            return view('admin.homologacionescoordinador.coordinador')->withErrors([
                'error' => 'No se pudieron cargar los datos. Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene el pensum de la universidad autónoma.
     */
    public function obtenerPensumAutonoma()
    {
        try {
            $response = $this->safeApiCall('GET', 'contenidos-programaticos');

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("Error al obtener el pensum autónomo: " . $response->body());
                return [];
            }
        } catch (\Exception $e) {
            Log::error("Excepción al obtener pensum autónomo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene las materias cursadas para una solicitud específica.
     */
    public function obtenerMateriasCursadas($solicitud_id)
    {
        try {
            $response = $this->safeApiCall('GET', 'homologacion-asignaturas/' . $solicitud_id);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("Error al obtener las materias cursadas para solicitud: $solicitud_id - " . $response->body());
                return [];
            }
        } catch (\Exception $e) {
            Log::error("Excepción al obtener materias cursadas para solicitud $solicitud_id: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene una solicitud específica por su ID.
     */
    public function obtenerSolicitud($solicitud_id)
    {
        try {
            $response = $this->safeApiCall('GET', 'solicitudes/' . $solicitud_id);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("Error al obtener la solicitud con ID: $solicitud_id - " . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Excepción al obtener solicitud con ID $solicitud_id: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Alias para mantener compatibilidad
     */
    public function obtenerPensum()
    {
        return $this->obtenerPensumAutonoma();
    }

    /**
     * Procesa la homologación de una solicitud.
     */
    /**
 * Procesa la homologación de una solicitud.
 */
public function procesarHomologacion($solicitud_id)
{
    try {
        // Registrar inicio del proceso
        Log::info("Iniciando procesamiento de homologación para solicitud: " . $solicitud_id);

        // Configurar cliente HTTP con opciones avanzadas para evitar problemas de conexión
        $client = new Client([
            'timeout' => 30,
            'connect_timeout' => 15,
            'verify' => false, // Desactiva verificación SSL para desarrollo
            'http_errors' => false
        ]);

        // Obtener URL base del API
        $baseUrl = $this->getBaseUrl();
        Log::info("Base URL de API: " . $baseUrl);

        // 1. Obtener datos de la solicitud específica
        try {
            $solicitudResponse = $client->get($baseUrl . 'solicitudes/' . $solicitud_id);
            $solicitud = json_decode($solicitudResponse->getBody(), true);
            Log::info("Solicitud obtenida correctamente para ID: " . $solicitud_id);
        } catch (\Exception $e) {
            Log::error("Error al obtener solicitud: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo obtener la información de la solicitud');
        }

        // 2. Obtener materias cursadas
        try {
            $materiasResponse = $client->get($baseUrl . 'homologacion-asignaturas/' . $solicitud_id);
            $materias_cursadas = json_decode($materiasResponse->getBody(), true);
            Log::info("Materias cursadas obtenidas: " . count($materias_cursadas ?? []));
        } catch (\Exception $e) {
            Log::error("Error al obtener materias cursadas: " . $e->getMessage());
            $materias_cursadas = [];
        }

        // 3. Obtener pensum
        try {
            $pensumResponse = $client->get($baseUrl . 'contenidos-programaticos');
            $pensum = json_decode($pensumResponse->getBody(), true);
            Log::info("Pensum obtenido: " . count($pensum ?? []));
        } catch (\Exception $e) {
            Log::error("Error al obtener pensum: " . $e->getMessage());
            $pensum = [];
        }

        // 4. Preparar datos para la vista
        $allData = [
            'solicitud' => $solicitud ?? null,
            'materias_cursadas' => $materias_cursadas ?? [],
            'pensum' => $pensum ?? []
        ];

        // 5. Obtener datos adicionales necesarios
        $endpoints = [
            'instituciones',
            'programas',
            'asignaturas',
            'usuarios',
            'facultades',
            'solicitud-asignaturas',
            'solicitudes'
        ];

        foreach ($endpoints as $endpoint) {
            try {
                $response = $client->get($baseUrl . $endpoint);
                $data = json_decode($response->getBody(), true);
                $allData[$endpoint] = $data ?? [];
                Log::info("Datos de $endpoint obtenidos: " . count($data ?? []));
            } catch (\Exception $e) {
                Log::error("Error al obtener datos de $endpoint: " . $e->getMessage());
                $allData[$endpoint] = [];
            }
        }

        // Verificar que tenemos los datos mínimos necesarios
        if (!isset($solicitud) || !is_array($solicitud)) {
            Log::error("Datos de solicitud inválidos o no disponibles");
            return redirect()->back()->with('error', 'No se pudo cargar la información de la solicitud');
        }

        // Mostrar mensaje informativo si faltan algunos datos secundarios
        $warnings = [];
        if (empty($materias_cursadas)) {
            $warnings[] = 'No se pudieron cargar las materias cursadas.';
        }
        if (empty($pensum)) {
            $warnings[] = 'No se pudo cargar el pensum.';
        }
        //dd($warnings);
        dd($allData);
        dd(
            $allData['solicitud'],
            $allData['materias_cursadas'],
            $allData['pensum'],
            $allData['instituciones'],
            $allData['programas'],
            $allData['asignaturas'],
            $allData['usuarios'],
            $allData['facultades'],
            $allData['solicitud-asignaturas'],
            $allData['solicitudes']
        );
        // Redirigir a la vista con los datos obtenidos
        return view('admin.homologacionescoordinador.procesohomologacion', $allData)
            ->with('warnings', $warnings);

    } catch (\Exception $e) {
        // Capturar cualquier excepción no manejada
        Log::error("Error general en procesarHomologacion: " . $e->getMessage());
        Log::error("Stack trace: " . $e->getTraceAsString());
        return redirect()->back()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
    }
}
}

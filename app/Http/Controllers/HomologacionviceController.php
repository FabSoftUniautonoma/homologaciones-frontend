<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitud;
use Exception;

class HomologacionViceController extends Controller
{
    /**
     * Obtiene la URL base del backend.
     */
    private function getBaseUrl()
    {
        return rtrim(env('BASE_URL_BACKEND', 'http://127.0.0.1:8000'), '/') . '/api/';
    }

    /**
     * Realiza una solicitud HTTP de forma segura y con reintentos
     */
    private function safeApiCall($method, $endpoint, $data = [], $timeout = 30)
    {
        $baseUrl = $this->getBaseUrl();
        $url = $baseUrl . ltrim($endpoint, '/');

        Log::info("Realizando solicitud $method a: $url");

        try {
            $httpClient = Http::timeout($timeout)
                ->withOptions([
                    'verify' => false,
                    'connect_timeout' => 10,
                    'http_errors' => false
                ]);

            switch ($method) {
                case 'GET':
                    $response = $httpClient->get($url, $data);
                    break;
                case 'POST':
                    $response = $httpClient->post($url, $data);
                    break;
                case 'PUT':
                    $response = $httpClient->put($url, $data);
                    break;
                case 'DELETE':
                    $response = $httpClient->delete($url);
                    break;
                default:
                    throw new \Exception("Método HTTP no soportado: $method");
            }

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
            return redirect()->back()->with('error', 'Error al comunicarse con el servidor: ' . $e->getMessage());
        }
    }

    public function buscarSolicitudPorUsuario($idUsuario)
    {
        try {
            $response = $this->safeApiCall('GET', 'solicitudes');

            if (!$response->successful())
                return null;

            foreach ($response->json() as $solicitud) {
                if ($solicitud['id_usuario'] == $idUsuario) {
                    return $solicitud;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function obtenerUsuario($idUsuario)
    {
        try {
            $response = $this->safeApiCall('GET', 'usuarios/' . $idUsuario);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function verInformacion($radicado)
    {
        try {
            $respSolicitudes = $this->safeApiCall('GET', 'solicitudes');
            if (!$respSolicitudes->successful())
                abort(500, 'Error al obtener solicitudes');

            $solicitud = collect($respSolicitudes->json())->firstWhere('numero_radicado', $radicado);
            if (!$solicitud)
                abort(404, 'Solicitud no encontrada');

            $respUsuarios = $this->safeApiCall('GET', 'usuarios');
            if (!$respUsuarios->successful())
                abort(500, 'Error al obtener usuarios');

            $usuario = collect($respUsuarios->json())->firstWhere('numero_identificacion', $solicitud['numero_identificacion']);
            if (!$usuario)
                abort(404, 'Usuario no encontrado');

            return view('admin.homologacionesvice.informacionhomologacionusuario', compact('solicitud', 'usuario'));
        } catch (\Exception $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la vista principal de vicerrectoría con las solicitudes
     */
    public function index()
    {
        try {
            $response = $this->safeApiCall('GET', 'solicitudes');

            if (!$response->successful()) {
                return view('admin.homologacionesvice.index')->withErrors([
                    'error' => 'No se pudieron cargar los datos de solicitudes. Error: ' . $response->body()
                ]);
            }

            // Pass the data as 'solicitudes' to the view
            return view('admin.homologacionesvice.index', [
                'solicitudes' => $response->json(),
            ]);
        } catch (\Exception $e) {
            return view('admin.homologacionesvice.index')->withErrors([
                'error' => 'Error al obtener datos del backend: ' . $e->getMessage()
            ]);
        }
    }


    public function descargarDocumento($documento)
    {
        $ruta = storage_path("app/documentos/{$documento}");

        if (!file_exists($ruta))
            abort(404, 'Documento no encontrado.');

        return response()->download($ruta);
    }

    public function verReportes()
    {
        return view('admin.homologacionesvice.reportes');
    }

public function verDocumentos($id) {
    try {
        // Obtener documentos del usuario
        $respDocumentos = $this->safeApiCall('GET', 'documentos/usuario/' . $id);
        if (!$respDocumentos->successful()) {
            abort(500, 'Error al obtener documentos');
        }

        $respuestaJson = $respDocumentos->json();
        $documentos = $respuestaJson['datos'] ?? [];

        if (empty($documentos)) {
            abort(404, 'No se encontraron documentos para el usuario');
        }

        // Extraer nombre del usuario desde el primer documento
        $primerDocumento = $documentos[0];
        $nombreEstudiante = $primerDocumento['primer_nombre'] . ' ' . $primerDocumento['primer_apellido'];
        $radicado = $primerDocumento['numero_radicado'] ?? '-';

        // Retornar la vista
        return view('admin.homologacionesvice.documentos', [
            'documentos' => $documentos,
            'id' => $id,
            'radicado' => $radicado,
            'nombreEstudiante' => $nombreEstudiante,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en verDocumentos: ' . $e->getMessage());
        return view('admin.homologacionesvice.documentos', [
            'documentos' => [],
            'id' => 0,
            'radicado' => '-',
            'nombreEstudiante' => 'Estudiante'
        ])->withErrors([
            'error' => 'No se pudo cargar la información de los documentos. ' . $e->getMessage()
        ]);
    }
}


    public function obtenerSolicitud($solicitud_id)
    {
        try {
            $response = $this->safeApiCall('GET', 'solicitudes/' . $solicitud_id);
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Procesa la solicitud de homologación y muestra la vista para vicerrectoría
     *
     * @param string|int $id ID de la homologación
     * @return \Illuminate\View\View
     */
    public function procesarHomologacion($id)
    {
        try {
            // Normalizar el ID (eliminar prefijo si existe)
            $idNumerico = $id;
            if (strpos($id, 'HOM-') === 0) {
                $idNumerico = substr($id, 9); // Obtener los últimos dígitos (ej. 0001)
            }

            // Llamar al API de homologaciones usando el método seguro
            $responseHomologacion = $this->safeApiCall('GET', 'homologacion-asignaturas/' . $idNumerico);

            // Verificar si la llamada a la API fue exitosa
            if ($responseHomologacion->successful()) {
                $datosAPI = $responseHomologacion->json();

                // Obtener los datos dentro de la estructura recibida
                $datos = isset($datosAPI['datos']) ? $datosAPI['datos'] :
                        (isset($datosAPI['data']) ? $datosAPI['data'] : null);

                if (!$datos) {
                    throw new Exception('La estructura de datos recibida no es la esperada');
                }

                // Filtrar las asignaturas de origen y destino válidas
                $asignaturasOrigenFiltradas = array_filter($datos['asignaturas_origen'] ?? [], function($item) {
                    return isset($item['id']) && !is_null($item['id']);
                });

                $asignaturasDestinoFiltradas = array_filter($datos['asignaturas_destino'] ?? [], function($item) {
                    return isset($item['id']) && !is_null($item['id']);
                });

                // Construir las correspondencias entre asignaturas (homologaciones)
                $homologacionesExistentes = [];

                // Si hay correspondencias explícitas en la API, usarlas
                if (isset($datos['correspondencias']) && !empty($datos['correspondencias'])) {
                    $homologacionesExistentes = $datos['correspondencias'];
                }
                // Si no hay correspondencias explícitas, crear basadas en los índices
                else {
                    foreach ($asignaturasOrigenFiltradas as $indice => $asignaturaOrigen) {
                        // Buscar una asignatura destino para esta asignatura origen
                        $asignaturaDestinoId = null;
                        $notaDestino = '3.0'; // Valor por defecto

                        // Si hay una asignatura destino en el mismo índice, usarla
                        $asignaturasDestinoArray = array_values($asignaturasDestinoFiltradas);
                        if (isset($asignaturasDestinoArray[$indice])) {
                            $asignaturaDestinoId = $asignaturasDestinoArray[$indice]['id'];
                            $notaDestino = $asignaturasDestinoArray[$indice]['nota_destino'] ?? '3.0';
                        }

                        if ($asignaturaDestinoId) {
                            $homologacionesExistentes[] = [
                                'id' => $indice + 1,
                                'asignatura_origen_id' => $asignaturaOrigen['id'],
                                'asignatura_destino_id' => $asignaturaDestinoId,
                                'nota_origen' => $asignaturaOrigen['nota_origen'] ?? null,
                                'nota_destino' => $notaDestino,
                                'estado' => 'pendiente'
                            ];
                        }
                    }
                }

                // Log para depuración
                Log::info('Datos de homologación procesados correctamente para ID: ' . $idNumerico);
                Log::debug('Homologaciones existentes: ' . json_encode($homologacionesExistentes));

                // Devolver la vista con los datos necesarios
                return view('admin.homologacionesvice.procesohomologacionvice', [
                    'datos' => $datos,
                    'homologacionesExistentes' => $homologacionesExistentes,
                    'homologacionId' => $idNumerico
                ]);
            } else {
                // Si la API retorna un error
                $statusCode = $responseHomologacion->status();
                $errorBody = $responseHomologacion->body();
                throw new Exception("Error en la API (Código: {$statusCode}): {$errorBody}");
            }

        } catch (Exception $e) {
            // Registrar el error
            Log::error('Error al procesar homologación para vicerrectoría: ' . $e->getMessage());

            // Devolver la vista con mensaje de error
            return view('admin.homologacionesvice.procesohomologacionvice', [
                'datos' => null,
                'homologacionesExistentes' => [],
                'errors' => ['Error al procesar homologación: ' . $e->getMessage()],
                'homologacionId' => $id
            ]);
        }
    }

    /**
     * Procesa la solicitud de homologación para vicerrectoría (Método con nombre corregido)
     * Este es un alias del método procesarHomologacion para coincidir con la ruta definida
     *
     * @param string|int $id ID de la homologación
     * @return \Illuminate\View\View
     */
    public function vicerrectoria($id)
    {
        return $this->procesarHomologacion($id);
    }

    public function obtenerPensumAutonoma($programaId = 12)
    {
        try {
            // Inicializar variables
            $asignaturasDestino = [];

            // Llamar a la API para obtener las asignaturas del programa
            $response = Http::get('http://127.0.0.1:8000/api/asignaturas/programa/' . $programaId);

            if ($response->successful()) {
                // Normalizar la estructura de datos
                $asignaturasDestino = isset($response['datos'])
                    ? $response['datos']
                    : (isset($response['data'])
                        ? $response['data']
                        : []);

                return view('admin.homologacionescoordinador.pensumautonoma', [
                    'asignaturasDestino' => $asignaturasDestino,
                    'programaId' => $programaId
                ]);
            } else {
                return view('admin.homologacionescoordinador.pensumautonoma', [
                    'asignaturasDestino' => [],
                    'programaId' => $programaId,
                    'errors' => ['No se encontraron materias para el programa con ID: ' . $programaId]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al obtener pensum de programa: ' . $e->getMessage());
            return view('admin.homologacionescoordinador.pensumautonoma', [
                'asignaturasDestino' => [],
                'programaId' => $programaId,
                'errors' => ['Error al obtener pensum: ' . $e->getMessage()]
            ]);
        }
    }
        public function obtenerDatosBack()
    {
        try {
            $response = $this->safeApiCall('GET', 'solicitudes');

            if (!$response->successful()) {
                return view('admin.homologacionesvice.vicerrector')->withErrors([
                    'error' => 'No se pudieron cargar los datos de solicitudes. Error: ' . $response->body()
                ]);
            }

            return view('admin.homologacionesvice.vicerrector', [
                'solicitudes' => $response->json(),
            ]);
        } catch (\Exception $e) {
            return view('admin.homologacionesvice.vicerrector')->withErrors([
                'error' => 'Error al obtener datos del backend: ' . $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitud;

class HomologacionController extends Controller
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

            return view('admin.homologacionescoordinador.informacionhomologacionusuario', compact('solicitud', 'usuario'));
        } catch (\Exception $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }

    public function obtenerDatosBack()
    {
        try {
            $response = $this->safeApiCall('GET', 'solicitudes');

            if (!$response->successful()) {
                return view('admin.homologacionescoordinador.coordinador')->withErrors([
                    'error' => 'No se pudieron cargar los datos de solicitudes. Error: ' . $response->body()
                ]);
            }

            return view('admin.homologacionescoordinador.coordinador', [
                'solicitudes' => $response->json(),
            ]);
        } catch (\Exception $e) {
            return view('admin.homologacionescoordinador.coordinador')->withErrors([
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
        return view('admin.homologacionescoordinador.reportes');
    }

    public function verDocumentos($solicitud_id)
    {
        try {
            $solicitud = Solicitud::findOrFail($solicitud_id);
            $documentos = $solicitud->documentos;
            return view('admin.homologacionescoordinador.documentos', compact('solicitud', 'documentos'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo obtener la información de los documentos.');
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




    public function procesarHomologacion($id)
    {
        try {
            // Normalizar el ID (eliminar prefijo si existe)
            $idNumerico = $id;
            if (strpos($id, 'HOM-') === 0) {
                $idNumerico = substr($id, 9); // Obtener los últimos dígitos (ej. 0001)
            }

            // Inicializar las variables por defecto
            $solicitud = null;
            $asignaturasOrigen = [];
            $asignaturasDestino = [];

            // Usar HTTP Client para llamar al API endpoint en lugar del procedimiento almacenado
            $response = Http::get('http://127.0.0.1:8000/api/homologacion-asignaturas/' . $idNumerico);

            if ($response->successful() && isset($response['datos'])) {
                // Obtenemos los datos completos de la respuesta API
                $homologacion = $response['datos'];
                $solicitud = $homologacion;

                // Extraer asignaturas_origen y asignaturas_destino del JSON devuelto por el API
                if (isset($homologacion['asignaturas_origen'])) {
                    $asignaturasOrigen = $homologacion['asignaturas_origen'];
                }

                if (isset($homologacion['asignaturas_destino'])) {
                    $asignaturasDestino = $homologacion['asignaturas_destino'];
                }

                // Para debugging (opcional)
                // \Log::info('Datos homologación: ', (array)$homologacion);
                // \Log::info('Asignaturas origen: ', $asignaturasOrigen);
                // \Log::info('Asignaturas destino: ', $asignaturasDestino);

                return view('admin.homologacionescoordinador.procesohomologacion', [
                    'solicitud' => $solicitud,
                    'asignaturasOrigen' => $asignaturasOrigen,
                    'asignaturasDestino' => $asignaturasDestino
                ]);
            } else {
                return view('admin.homologacionescoordinador.procesohomologacion', [
                    'solicitud' => null,
                    'asignaturasOrigen' => [],
                    'asignaturasDestino' => [],
                    'errors' => ['No se encontró la homologación con ID: ' . $id]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al procesar homologación: ' . $e->getMessage());
            return view('admin.homologacionescoordinador.procesohomologacion', [
                'solicitud' => null,
                'asignaturasOrigen' => [],
                'asignaturasDestino' => [],
                'errors' => ['Error al procesar homologación: ' . $e->getMessage()]
            ]);
        }
    }
}

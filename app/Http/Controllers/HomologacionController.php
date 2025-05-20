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


    /**
     * Ver información de homologación por radicado
     */
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
            Log::error('Error en verInformacion: ' . $e->getMessage());
            abort(500, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar estado de una solicitud
     * Este método sirve como intermediario entre el frontend y el API
     */
    public function actualizarEstado(Request $request, $identificador)
    {
        try {
            // Validar la solicitud
            $request->validate([
                'estado' => 'required|string|in:Radicado,En revisión,Aprobado,Rechazado,Cerrado',
            ]);

            Log::info('Actualizando estado para identificador: ' . $identificador);
            Log::info('Estado a actualizar: ' . $request->estado);

            // Determinar si el identificador es un ID directo o un radicado
            $isId = is_numeric($identificador);
            $solicitudId = null;

            if ($isId) {
                // Si es un ID numérico, usamos directamente
                $solicitudId = $identificador;
                Log::info('Usando ID directo: ' . $solicitudId);
            } else {
                // Es un radicado, obtenemos la solicitud correspondiente
                $respSolicitudes = $this->safeApiCall('GET', 'solicitudes');
                if (!$respSolicitudes->successful()) {
                    Log::error('Error al obtener solicitudes: ' . $respSolicitudes->status());
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al obtener solicitudes'
                    ], 500);
                }

                $solicitud = collect($respSolicitudes->json())->firstWhere('numero_radicado', $identificador);
                if (!$solicitud) {
                    Log::error('Solicitud no encontrada con radicado: ' . $identificador);
                    return response()->json([
                        'success' => false,
                        'message' => 'Solicitud no encontrada'
                    ], 404);
                }

                $solicitudId = $solicitud['id_solicitud'];
            }

            // Registrar el ID obtenido
            Log::info('ID de solicitud a actualizar: ' . $solicitudId);

            // Primer intento: Actualizar usando el endpoint específico para estado
            try {
                Log::info('Intentando actualizar estado con endpoint específico...');
                $respuestaEstado = Http::patch(config('services.api.url') . '/api/solicitudes/' . $solicitudId . '/estado', [
                    'estado' => $request->estado
                ]);

                if ($respuestaEstado->successful()) {
                    Log::info('Estado actualizado con éxito usando endpoint específico');
                    return response()->json([
                        'success' => true,
                        'message' => 'Estado actualizado correctamente',
                        'metodo' => 'endpoint_especifico'
                    ]);
                } else {
                    Log::warning('Fallo al actualizar con endpoint específico: ' . $respuestaEstado->status());
                }
            } catch (\Exception $e) {
                Log::warning('Error en actualización con endpoint específico: ' . $e->getMessage());
            }

            // Segundo intento: Actualizar usando el endpoint PUT general
            try {
                Log::info('Intentando actualizar estado con endpoint general...');
                $endpoint = 'solicitudes/' . $solicitudId;

                $respuesta = $this->safeApiCall('PUT', $endpoint, [
                    'estado' => $request->estado,
                    // Incluimos el resto de campos que ya existen en la solicitud
                    'usuario_id' => $request->input('usuario_id'),
                    'programa_destino_id' => $request->input('programa_destino_id'),
                    'finalizo_estudios' => $request->input('finalizo_estudios'),
                    'fecha_finalizacion_estudios' => $request->input('fecha_finalizacion_estudios'),
                    'fecha_ultimo_semestre_cursado' => $request->input('fecha_ultimo_semestre_cursado')
                ]);

                if ($respuesta->successful()) {
                    Log::info('Estado actualizado con éxito usando endpoint general PUT');
                    return response()->json([
                        'success' => true,
                        'message' => 'Estado actualizado correctamente',
                        'metodo' => 'endpoint_general_put'
                    ]);
                } else {
                    Log::warning('Fallo al actualizar con endpoint general PUT: ' . $respuesta->status());
                    // Si hay mensaje de error del API, lo logeamos
                    if ($respuesta->json() && isset($respuesta->json()['mensaje'])) {
                        Log::warning('Mensaje de error API: ' . $respuesta->json()['mensaje']);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Error en actualización con endpoint general PUT: ' . $e->getMessage());
            }

            // Si llegamos aquí, ambos intentos fallaron
            Log::error('Todos los intentos de actualización fallaron');
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el estado después de varios intentos',
                'identificador' => $identificador,
                'solicitudId' => $solicitudId
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error en actualizarEstado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
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
        return view('admin.homologacionescoordinador.documentos', [
            'documentos' => $documentos,
            'id' => $id,
            'radicado' => $radicado,
            'nombreEstudiante' => $nombreEstudiante,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en verDocumentos: ' . $e->getMessage());
        return view('admin.homologacionescoordinador.documentos', [
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

    public function procesarHomologacion($id)
    {
        try {
            // Normalizar el ID (eliminar prefijo si existe)
            $idNumerico = $id;
            if (strpos($id, 'HOM-') === 0) {
                $idNumerico = substr($id, 9); // Obtener los últimos dígitos (ej. 0001)
            }

            // Inicializar variables por defecto
            $solicitud = null;
            $asignaturasOrigen = [];
            $asignaturasDestino = [];
            $homologacionesExistentes = [];
            $solicitudId = null;  // Inicializar la variable solicitudId

            // Llamar al API de homologaciones
            $responseHomologacion = Http::get('http://127.0.0.1:8000/api/homologacion-asignaturas/' . $idNumerico);

            // Llamar al API de pensum de Autónoma (programaId = 12)
            $responsePensum = Http::get('http://127.0.0.1:8000/api/asignaturas/programa/12');

            // Procesar respuesta de homologación
            if ($responseHomologacion->successful()) {
                // Normalizar los datos independientemente de cómo vengan estructurados
                $homologacion = isset($responseHomologacion['datos'])
                    ? $responseHomologacion['datos']
                    : (isset($responseHomologacion['data'])
                        ? $responseHomologacion['data']
                        : []);

                // Obtener el solicitud_id con mayor seguridad
                if (isset($homologacion['solicitud_id'])) {
                    $solicitudId = $homologacion['solicitud_id'];
                } elseif (isset($homologacion['solicitudId'])) {
                    $solicitudId = $homologacion['solicitudId'];
                } elseif (isset($homologacion['id_solicitud'])) {
                    $solicitudId = $homologacion['id_solicitud'];
                }

                // Rellenar los datos de solicitud
                $solicitud = $homologacion;

                // Obtener asignaturas origen
                $asignaturasOrigen = isset($homologacion['asignaturas_origen'])
                    ? $homologacion['asignaturas_origen']
                    : [];

                // Obtener homologaciones existentes para que el frontend pueda actualizar los IDs
                $homologacionesExistentes = isset($homologacion['homologaciones'])
                    ? $homologacion['homologaciones']
                    : [];
            }

            // Procesar respuesta de pensum y asignarla a asignaturasDestino
            if ($responsePensum->successful()) {
                $asignaturasDestino = isset($responsePensum['datos'])
                    ? $responsePensum['datos']
                    : (isset($responsePensum['data'])
                        ? $responsePensum['data']
                        : []);
            }

            // Debug el solicitudId para verificar que se está obteniendo correctamente
            \Log::info('SolicitudId obtenido: ' . $solicitudId);

            // Pasar el solicitudId a la vista y también crear un JavaScript global
            return view('admin.homologacionescoordinador.procesohomologacion', [
                'solicitud' => $solicitud,
                'asignaturasOrigen' => $asignaturasOrigen,
                'asignaturasDestino' => $asignaturasDestino,
                'homologacionesExistentes' => $homologacionesExistentes,
                'solicitudId' => $solicitudId, // Pasar el solicitudId a la vista
                'homologacionId' => $idNumerico, // También pasar el homologacionId
            ])->with('jsVariables', [
                        'solicitudId' => $solicitudId,
                        'homologacionId' => $idNumerico
                    ]);
        } catch (\Exception $e) {
            \Log::error('Error al procesar homologación: ' . $e->getMessage());
            return view('admin.homologacionescoordinador.procesohomologacion', [
                'solicitud' => null,
                'asignaturasOrigen' => [],
                'asignaturasDestino' => [],
                'homologacionesExistentes' => [],
                'errors' => ['Error al procesar homologación: ' . $e->getMessage()],
                'solicitudId' => null // Si ocurre un error, pasamos null
            ]);
        }
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
                $datosRespuesta = isset($response['datos'])
                    ? $response['datos']
                    : (isset($response['data'])
                        ? $response['data']
                        : []);

                // Asegurarnos de que cada asignatura tenga el ID correctamente
                $asignaturasDestino = array_map(function ($asignatura) {
                    // Verificar que el id_asignatura exista
                    if (!isset($asignatura['id_asignatura']) && isset($asignatura['id'])) {
                        $asignatura['id_asignatura'] = $asignatura['id'];
                    } elseif (!isset($asignatura['id_asignatura'])) {
                        // Si no existe ningún ID, asignar uno para evitar errores
                        $asignatura['id_asignatura'] = 0;
                    }

                    return $asignatura;
                }, $datosRespuesta);

                // Registrar los IDs para verificación
                \Log::info(
                    'IDs de asignaturas obtenidos para programa ' . $programaId . ':',
                    array_map(function ($a) {
                        return [
                            'nombre' => $a['nombre'] ?? 'Sin nombre',
                            'id_asignatura' => $a['id_asignatura'] ?? 'No definido'
                        ];
                    }, $asignaturasDestino)
                );

                return view('admin.homologacionescoordinador.pensumautonoma', [
                    'asignaturasDestino' => $asignaturasDestino,
                    'programaId' => $programaId
                ]);
            } else {
                \Log::warning('No se encontraron materias para el programa con ID: ' . $programaId . '. Código de respuesta: ' . $response->status());

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
    public function show($id)
    {
        try {
            // Normalizar el ID
            $idNumerico = $id;
            if (strpos($id, 'HOM-') === 0) {
                $idNumerico = substr($id, 9);
            }

            // Llamar al API de homologaciones
            $responseHomologacion = Http::get('http://127.0.0.1:8000/api/homologacion-asignaturas/' . $idNumerico);

            if (!$responseHomologacion->successful()) {
                throw new \Exception('No se pudo obtener la homologación desde la API');
            }

            $homologacion = $responseHomologacion['datos'] ?? $responseHomologacion['data'] ?? [];

            // Obtener solicitud relacionada con estudiante (consulta por solicitud_id)
            $solicitudId = $homologacion['solicitud_id'] ?? $homologacion['solicitudId'] ?? $homologacion['id_solicitud'] ?? null;

            if (!$solicitudId) {
                throw new \Exception('No se pudo identificar el ID de la solicitud desde los datos de homologación.');
            }

            $solicitud = Solicitud::with('estudiante')->findOrFail($solicitudId);

            // Obtener asignaturas de origen directamente desde la respuesta de API
            $asignaturasOrigen = $homologacion['asignaturas_origen'] ?? [];

            // Obtener asignaturas de destino desde pensum
            $responsePensum = Http::get('http://127.0.0.1:8000/api/asignaturas/programa/12');

            $asignaturasDestino = $responsePensum->successful()
                ? ($responsePensum['datos'] ?? $responsePensum['data'] ?? [])
                : [];

            // Estructurar la respuesta JSON
            $respuesta = [
                "mensaje" => "Homologación de asignatura encontrada",
                "datos" => [
                    "id_homologacion" => $homologacion['id'] ?? null,
                    "solicitud_id" => $solicitud->id,
                    "numero_radicado" => $homologacion['numero_radicado'] ?? null,
                    "estudiante" => $solicitud->estudiante->nombre_completo,
                    "numero_identificacion" => $solicitud->estudiante->numero_identificacion,
                    "programa_destino" => $homologacion['programa_destino'] ?? null,
                    "estado_solicitud" => $homologacion['estado'] ?? null,
                    "fecha" => $homologacion['fecha'] ?? null,
                    "ruta_pdf_resolucion" => $homologacion['ruta_pdf_resolucion'] ?? null,
                    "url_pdf_resolucion" => $homologacion['url_pdf_resolucion'] ?? null,
                    "comentarios" => $homologacion['comentarios'] ?? null,
                    "universidad_origen" => $homologacion['universidad_origen'] ?? null,

                    "asignaturas_origen" => collect($asignaturasOrigen)->map(function ($asig) {
                        return [
                            "id" => $asig['id'] ?? null,
                            "nombre" => $asig['nombre'] ?? null,
                            "codigo" => $asig['codigo'] ?? null,
                            "semestre" => $asig['semestre'] ?? null,
                            "programa" => $asig['programa'] ?? null,
                            "facultad" => $asig['facultad'] ?? null,
                            "institucion" => $asig['institucion'] ?? null,
                            "nota_origen" => $asig['nota'] ?? null,
                            "creditos" => $asig['creditos'] ?? null,
                            "contenido_programatico" => isset($asig['contenido_programatico']) ? [
                                "id" => $asig['contenido_programatico']['id'] ?? null,
                                "tema" => $asig['contenido_programatico']['tema'] ?? null,
                                "resultados_aprendizaje" => $asig['contenido_programatico']['resultados_aprendizaje'] ?? null,
                                "descripcion" => $asig['contenido_programatico']['descripcion'] ?? null
                            ] : null
                        ];
                    }),

                    "asignaturas_destino" => collect($asignaturasDestino)->map(function ($asig) {
                        return [
                            "id" => $asig['id'] ?? null,
                            "nombre" => $asig['nombre'] ?? null,
                            "codigo" => $asig['codigo'] ?? null,
                            "semestre" => $asig['semestre'] ?? null,
                            "programa" => $asig['programa'] ?? null,
                            "facultad" => $asig['facultad'] ?? null,
                            "institucion" => $asig['institucion'] ?? null,
                            "creditos" => $asig['creditos'] ?? null,
                            "nota_destino" => $asig['nota_destino'] ?? null,
                            "contenido_programatico" => isset($asig['contenido_programatico']) ? [
                                "id" => $asig['contenido_programatico']['id'] ?? null,
                                "tema" => $asig['contenido_programatico']['tema'] ?? null,
                                "resultados_aprendizaje" => $asig['contenido_programatico']['resultados_aprendizaje'] ?? null,
                                "descripcion" => $asig['contenido_programatico']['descripcion'] ?? null
                            ] : null
                        ];
                    }),
                ]
            ];

            return response()->json($respuesta, 200);

        } catch (\Exception $e) {
            return response()->json([
                "mensaje" => "Error al obtener la homologación",
                "error" => $e->getMessage()
            ], 500);
        }
    }



}


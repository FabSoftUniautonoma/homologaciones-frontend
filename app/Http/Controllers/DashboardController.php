<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Validator;

class DashboardController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        // Configura la URL base de la API (ajustable desde el .env)
        $this->apiUrl = env('API_URL', 'http://127.0.0.1:8000/api');
    }

    /**
     * Muestra la página principal del dashboard
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Obtiene los datos del perfil del usuario actual para mostrar en el dashboard
     */
    public function obtenerPerfilUsuario($id)
    {
        try {
            Log::info("Obteniendo perfil para usuario ID: {$id}");

            // Verificar que el ID sea válido
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'mensaje' => 'ID de usuario inválido',
                    'error' => 'El ID debe ser un número'
                ], 400);
            }

            $response = Http::timeout(15)->get($this->apiUrl . '/usuarios/' . $id);

            if (!$response->successful()) {
                Log::error("Error al obtener perfil de usuario: {$response->status()}", [
                    'id' => $id,
                    'respuesta' => $response->body()
                ]);

                return response()->json([
                    'mensaje' => 'No se pudo obtener la información del usuario',
                    'error' => $response->status() . ': ' . $response->body()
                ], $response->status());
            }

            // Añadir la contraseña visible al resultado
            $userData = $response->json();
            if (isset($userData['datos'])) {
                // En producción, esto debería manejarse de forma segura
                // Esta es una implementación de ejemplo - en un sistema real se debe usar una solución más segura
                $userData['datos']['password_visible'] = 'Contraseña123';
            }

            Log::info("Perfil de usuario obtenido exitosamente", ['id' => $id]);
            return response()->json($userData);

        } catch (\Exception $e) {
            Log::error("Excepción al obtener perfil de usuario: {$e->getMessage()}", [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'mensaje' => 'Error al conectar con la API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las solicitudes de homologación del usuario
     */
    public function obtenerSolicitudesUsuario($usuarioId)
    {
        try {
            Log::info("Obteniendo solicitudes para usuario ID: {$usuarioId}");

            // Verificar que el ID sea válido
            if (!$usuarioId || !is_numeric($usuarioId)) {
                return response()->json([
                    'mensaje' => 'ID de usuario inválido',
                    'error' => 'El ID debe ser un número'
                ], 400);
            }

            // Usar parámetros de consulta específicos
            $response = Http::timeout(15)->get($this->apiUrl . '/solicitudes', [
                'usuario_id' => $usuarioId
            ]);

            if (!$response->successful()) {
                Log::error("Error al obtener solicitudes de usuario: {$response->status()}", [
                    'id' => $usuarioId,
                    'respuesta' => $response->body()
                ]);

                return response()->json([
                    'mensaje' => 'No se pudieron obtener las solicitudes',
                    'error' => $response->status() . ': ' . $response->body()
                ], $response->status());
            }

            // Obtener datos JSON y asegurar que se devuelve en una estructura consistente
            $responseData = $response->json();
            $solicitudes = isset($responseData['datos']) ? $responseData['datos'] : $responseData;

            Log::info("Solicitudes obtenidas exitosamente", [
                'id' => $usuarioId,
                'cantidad' => is_array($solicitudes) ? count($solicitudes) : 0
            ]);

            return response()->json([
                'mensaje' => 'Solicitudes encontradas',
                'datos' => is_array($solicitudes) ? $solicitudes : []
            ]);

        } catch (\Exception $e) {
            Log::error("Excepción al obtener solicitudes de usuario: {$e->getMessage()}", [
                'id' => $usuarioId,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'mensaje' => 'Error al conectar con la API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene los detalles de una solicitud específica
     */
    public function obtenerDetalleSolicitud($id)
    {
        try {
            Log::info("Obteniendo detalle de solicitud ID: {$id}");

            // Verificar que el ID sea válido
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'mensaje' => 'ID de solicitud inválido',
                    'error' => 'El ID debe ser un número'
                ], 400);
            }

            $response = Http::timeout(15)->get($this->apiUrl . '/solicitudes/' . $id);

            if (!$response->successful()) {
                Log::error("Error al obtener detalle de solicitud: {$response->status()}", [
                    'id' => $id,
                    'respuesta' => $response->body()
                ]);

                return response()->json([
                    'mensaje' => 'No se pudo obtener la información de la solicitud',
                    'error' => $response->status() . ': ' . $response->body()
                ], $response->status());
            }

            Log::info("Detalle de solicitud obtenido exitosamente", ['id' => $id]);
            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error("Excepción al obtener detalle de solicitud: {$e->getMessage()}", [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'mensaje' => 'Error al conectar con la API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las asignaturas asociadas a una solicitud
     */
    public function obtenerSolicitudAsignaturas($id)
    {
        try {
            Log::info("Obteniendo asignaturas para solicitud ID: {$id}");

            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'mensaje' => 'ID de solicitud inválido',
                    'error' => 'El ID debe ser un número'
                ], 400);
            }

            $response = Http::timeout(15)->get($this->apiUrl . '/solicitud-asignaturas/' . $id);

            if (!$response->successful()) {
                Log::error("Error al obtener asignaturas de solicitud: {$response->status()}", [
                    'id' => $id,
                    'respuesta' => $response->body()
                ]);

                return response()->json([
                    'mensaje' => 'No se pudieron obtener las asignaturas de la solicitud',
                    'error' => $response->status() . ': ' . $response->body()
                ], $response->status());
            }

            Log::info("Asignaturas de solicitud obtenidas exitosamente", ['id' => $id]);
            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error("Excepción al obtener asignaturas de solicitud: {$e->getMessage()}", [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'mensaje' => 'Error al conectar con la API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza la información del perfil del usuario
     */
/**
 * Actualiza la información del perfil del usuario
 */
/**
 * Actualiza la información del perfil del usuario
 */
public function actualizarPerfilUsuario(Request $request, $id)
{
    try {
        Log::info("Actualizando perfil para usuario ID: {$id}", [
            'datos' => $request->except(['password'])  // No logueamos contraseñas
        ]);

        // Verificar que el ID sea válido
        if (!$id || !is_numeric($id)) {
            return response()->json([
                'mensaje' => 'ID de usuario inválido',
                'error' => 'El ID debe ser un número'
            ], 400);
        }

        // Lista blanca de campos que se pueden actualizar directamente
        // IMPORTANTE: Solo incluir campos básicos, no campos relacionales
        $camposPermitidos = [
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'email',
            'tipo_identificacion',
            'numero_identificacion',
            'telefono',
            'direccion'
        ];

        // Filtrar datos del request para solo incluir los campos permitidos
        $datosActualizar = [];
        foreach ($camposPermitidos as $campo) {
            if ($request->has($campo)) {
                $datosActualizar[$campo] = $request->input($campo);
            }
        }

        // Validar datos básicos
        $validator = Validator::make($datosActualizar, [
            'email' => 'required|email',
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'mensaje' => 'Datos de usuario inválidos',
                'error' => $validator->errors()
            ], 422);
        }

        // Añadimos logs detallados para ver qué estamos enviando
        Log::info("Datos filtrados para actualizar:", $datosActualizar);

        // Realizar la actualización solo con los campos básicos
        $response = Http::timeout(15)
            ->withHeaders(['Accept' => 'application/json'])
            ->put($this->apiUrl . '/usuarios/' . $id, $datosActualizar);

        if (!$response->successful()) {
            Log::error("Error al actualizar perfil de usuario: {$response->status()}", [
                'id' => $id,
                'respuesta' => $response->body()
            ]);

            return response()->json([
                'mensaje' => 'No se pudo actualizar la información del usuario',
                'error' => $response->status() . ': ' . $response->body()
            ], $response->status());
        }

        Log::info("Perfil de usuario actualizado exitosamente", ['id' => $id]);

        return response()->json([
            'mensaje' => 'Perfil actualizado correctamente',
            'datos' => $response->json()
        ]);

    } catch (\Exception $e) {
        Log::error("Excepción al actualizar perfil de usuario: {$e->getMessage()}", [
            'id' => $id,
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'mensaje' => 'Error al conectar con la API',
            'error' => $e->getMessage()
        ], 500);
    }
}


/**
 * Obtiene las homologaciones de asignaturas asociadas a una solicitud
 */
public function obtenerHomologacionAsignaturas($id)
{
    try {
        Log::info("Obteniendo homologaciones para solicitud ID: {$id}");

        if (!$id || !is_numeric($id)) {
            return response()->json([
                'mensaje' => 'ID de solicitud inválido',
                'error' => 'El ID debe ser un número'
            ], 400);
        }

        $response = Http::timeout(15)->get($this->apiUrl . '/homologacion-asignaturas/' . $id);

        if (!$response->successful()) {
            Log::error("Error al obtener homologaciones de asignatura: {$response->status()}", [
                'id' => $id,
                'respuesta' => $response->body()
            ]);

            return response()->json([
                'mensaje' => 'No se pudieron obtener las homologaciones de la solicitud',
                'error' => $response->status() . ': ' . $response->body()
            ], $response->status());
        }

        Log::info("Homologaciones de asignatura obtenidas exitosamente", ['id' => $id]);
        return response()->json($response->json());

    } catch (\Exception $e) {
        Log::error("Excepción al obtener homologaciones de asignatura: {$e->getMessage()}", [
            'id' => $id,
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'mensaje' => 'Error al conectar con la API',
            'error' => $e->getMessage()
        ], 500);
    }
}
}

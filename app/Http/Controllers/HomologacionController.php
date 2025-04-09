<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PDF;

class HomologacionController extends Controller
{
    /**
     * Actualiza el estado de una solicitud.
     */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en revisión,aprobada,rechazada',
        ]);

        $homologacion = Solicitud::findOrFail($id);
        $homologacion->estado = ucfirst($request->estado);
        $homologacion->save();

        // Registrar historial
        $homologacion->historial()->create([
            'accion' => 'Estado actualizado a: ' . ucfirst($request->estado),
            'usuario' => auth()->user()->name ?? 'admin',
        ]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Muestra información detallada de una solicitud.
     */
    public function verInformacion($id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            return view('admin.homologacionescoordinador.informacionhomologacionusuario', compact('solicitud'));
        } catch (\Exception $e) {
            return view('homologacionesaspirante.error_solicitud_no_encontrada');
        }
    }

    /**
     * Genera y descarga el PDF de una solicitud.
     */
    public function descargarPDF($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $pdf = PDF::loadView('pdf.homologacion', compact('solicitud'));
        return $pdf->download("Homologacion_{$solicitud->id}.pdf");
    }

    /**
     * Verifica los documentos de una solicitud.
     */
    public function verificarDocumentos($id)
    {
        $homologacion = Solicitud::findOrFail($id);

        $documentos = [
            'Documento de identidad' => $homologacion->documento_identidad,
            'Certificado de notas' => $homologacion->certificado_notas,
            'Contenido programático' => $homologacion->contenido_programatico,
            'Carta de solicitud' => $homologacion->carta_solicitud,
            'Hoja de vida' => $homologacion->hoja_vida,
            'Otro documento' => $homologacion->otro_documento,
        ];

        return view('admin.homologacionescoordinador.documentos', compact('documentos'));
    }

    /**
     * Cambia el estado de una solicitud a "En revisión".
     */
    public function iniciarProceso($id)
    {
        $homologacion = Solicitud::findOrFail($id);
        $homologacion->estado = 'En revisión';
        $homologacion->save();

        return response()->json(['mensaje' => 'Proceso iniciado con éxito.']);
    }

    /**
     * Obtiene datos del backend (solicitudes y usuarios).
     */
    public function obtenerDatosBack()
    {
        try {
            $client = new Client([
                'base_uri' => env('BASE_URL_BACKEND'), // Debe terminar en /api/
                'timeout' => 2.0,
            ]);

            $responseSolicitudes = $client->request('GET', 'solicitudes', [
                'headers' => ['Accept' => 'application/json']
            ]);
            $solicitudes = json_decode($responseSolicitudes->getBody()->getContents(), true);
            $responseUsuarios = $client->request('GET', 'usuarios', [
                'headers' => ['Accept' => 'application/json']
            ]);

            return view('admin.homologacionescoordinador.coordinador', [
                'solicitudes' => $solicitudes,
            ]);

        } catch (RequestException $e) {
            \Log::error('Error al obtener datos del backend: ' . $e->getMessage());

             return view('admin.homologacionescoordinador.coordinador')->withErrors([
                 'error' => 'No se pudieron cargar los datos. Verifica que el backend esté corriendo y accesible.'
             ]);
        }
    }

}

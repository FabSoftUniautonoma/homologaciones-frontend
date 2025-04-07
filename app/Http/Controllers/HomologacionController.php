<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class HomologacionController extends Controller
{
    public function actualizar(Request $request, $id)
    {
        // Validación opcional
        $request->validate([
            'estado' => 'required|in:pendiente,en revisión,aprobada,rechazada',
        ]);

        // Buscar la homologación
        $homologacion = HomologacionController::findOrFail($id);

        // Actualizar estado
        $homologacion->estado = ucfirst($request->estado);
        $homologacion->save();

        // Guardar en historial (opcional)
        $homologacion->historial()->create([
            'accion' => 'Estado actualizado a: ' . ucfirst($request->estado),
            'usuario' => auth()->user()->name ?? 'admin',
        ]);

        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    public function verInformacion($id)
    {
        try {
            // Buscar la solicitud por ID
            $solicitud = Solicitud::findOrFail($id);
            return view('admin.homologacionescoordinador.informacionhomologacionusuario', compact('solicitud'));
        } catch (\Exception $e) {
            // Mostrar vista de error personalizada si no se encuentra
            return view('homologacionesaspirante.error_solicitud_no_encontrada');
        }
    }
    public function descargarPDF($id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $pdf = PDF::loadView('pdf.homologacion', compact('solicitud'));

        return $pdf->download("Homologacion_{$solicitud->id}.pdf");
    }
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


    public function iniciarProceso($id)
    {
        $homologacion = Homologacion::findOrFail($id);

        // lógica para iniciar proceso
        $homologacion->estado = 'En revisión';
        $homologacion->save();

        return response()->json(['mensaje' => 'Proceso iniciado con éxito.']);
    }

}


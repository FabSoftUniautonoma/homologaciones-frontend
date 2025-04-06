<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class HomologacionController extends Controller
{
    public function verInformacion($id)
    {
        try {
            // Buscar la solicitud por ID
            $solicitud = Solicitud::findOrFail($id);
            return view('homologacionesaspirante.informacionhomologacionusuario', compact('solicitud'));
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
}

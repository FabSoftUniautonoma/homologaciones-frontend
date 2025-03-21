<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;

class PaisesControllerApi extends Controller
{
    public function getPaises(){
        $paises = Pais::orderBy("nombre","desc")->get();
        return response()->json($paises);
    }
}

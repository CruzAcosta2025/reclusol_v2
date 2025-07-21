<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;

class EntrevistaController extends Controller
{
    public function listadoInicial()
    {
        $postulantes = Postulante::where('estado', 'proceso')->orderBy('fecha_postula', 'asc')->paginate(15);

        return view('entrevistas.listadoInicial', compact('postulantes'));
    }
}

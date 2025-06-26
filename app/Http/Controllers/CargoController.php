<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cargo;

class CargoController extends Controller
{
     public function index()
    {
        return Cargo::all(); // o usar ->select('CODI_TIPO_CARG', 'DESC_TIPO_CARG')->get()
    }

    // Muestra un solo cargo por ID
    public function show($id)
    {
        return Cargo::findOrFail($id);
    }
}

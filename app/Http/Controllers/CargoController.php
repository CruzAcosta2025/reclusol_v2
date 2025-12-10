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

    public function show($id)
    {
        return Cargo::findOrFail($id);
    }
}

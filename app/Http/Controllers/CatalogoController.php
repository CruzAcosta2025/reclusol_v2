<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo;


class CatalogoController extends Controller
{
    public function create()
    {
        $departamentos = Catalogo::obtenerDepartamentos();
        $provincias = Catalogo::obtenerTodasProvincias();

        return view('postulantes.registroPrimario', compact('departamentos', 'provincias'));
    }

    public function createSucursal()
    {
        $sucursales = Catalogo::obtenerSucursal();

        return view('requerimientos.requerimiento', compact('sucursales'));
    }

    public function createCargo()
    {
        $tipoCargos = Catalogo::obtenerTipoCargo();
        $cargos = Catalogo::obtenerCargo();

        return view('postulantes.registroPrimario', compact('tipoCargos', 'cargos'));
    }
}

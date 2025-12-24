<?php

namespace App\Repositories;

use App\Models\Sucursal;
use App\Models\Departamento;
use App\Models\Cargo;

class Repositorio
{
    public function obtenerDepartamentos()
    {
        return Departamento::where('DEPA_VIGENCIA', 'SI')
            ->orderBy('DEPA_DESCRIPCION')
            ->get(['DEPA_CODIGO', 'DEPA_DESCRIPCION']);
    }

    public function obtenerSucursales()
    {
        return Sucursal::where('SUCU_VIGENCIA', 'SI')
            ->orderBy('SUCU_DESCRIPCION')
            ->get(['SUCU_CODIGO', 'SUCU_DESCRIPCION']);
    }

    public function obtenerCargos()
    {
        return Cargo::where('CARG_VIGENCIA', 'SI')
            ->orderBy('DESC_CARGO')
            ->get(['CODI_CARG', 'DESC_CARGO']);
    }
}

<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class Repositorio
{
    public function obtenerDepartamentos()
    {
        return DB::connection('si_solmar')
            ->table('ADMI_DEPARTAMENTO')
            ->select('DEPA_CODIGO', 'DEPA_DESCRIPCION')
            ->where('DEPA_VIGENCIA', 'SI')
            ->orderBy('DEPA_DESCRIPCION')
            ->get();
    }

    public function obtenerSucursales()
    {
        return DB::connection('si_solmar')
            ->table('SISO_SUCURSAL')
            ->select('SUCU_CODIGO', 'SUCU_DESCRIPCION')
            ->where('SUCU_VIGENCIA', 'SI')
            ->orderBy('SUCU_DESCRIPCION')
            ->get();
    }

    public function obtenerCargos()
    {
        return DB::connection('si_solmar')
            ->table('CARGOS')
            ->select('CODI_CARG', 'DESC_CARGO')
            ->where('CARG_VIGENCIA', 'SI')
            ->orderBy('DESC_CARGO')
            ->get();
    }
}

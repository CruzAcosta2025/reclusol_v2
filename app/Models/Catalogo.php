<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Catalogo 
{

    /*

    public static function obtenerDepartamentos()
    {
        return DB::connection('si_solmar')
            ->table('ADMI_DEPARTAMENTO')
            ->select('DEPA_CODIGO', 'DEPA_DESCRIPCION')
            ->where('DEPA_VIGENCIA', 'SI')
            ->orderBy('DEPA_DESCRIPCION')
            ->get();
    }

    public static function obtenerTodasProvincias()
    {
        return DB::connection('si_solmar')
            ->table('ADMI_PROVINCIA')
            ->select('PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO')
            ->where('PROVI_VIGENCIA', 'SI')
            ->orderBy('PROVI_DESCRIPCION')
            ->get();
    }

    public static function obtenerDistritos()
    {
        return DB::connection('si_solmar')
            ->table('ADMI_DISTRITO')
            ->select('DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO')
            ->where('DIST_VIGENCIA', 'SI')
            ->orderBy('DIST_DESCRIPCION')
            ->get();
    }

    public static function obtenerSucursal()
    {
        return DB::connection('si_solmar')
            ->table('SISO_SUCURSAL')
            ->select('SUCU_CODIGO', 'SUCU_DESCRIPCION')
            ->where('SUCU_VIGENCIA', 'SI')
            ->orderBy('SUCU_DESCRIPCION')
            ->get();
    }

    public static function obtenerTipoCargo()
    {
        return DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->select('CODI_TIPO_CARG', 'DESC_TIPO_CARG')
            ->get();
    }

    public static function obtenerCargo()
    {
        return DB::connection('si_solmar')
            ->table('CARGOS')
            ->select('CODI_CARG', 'DESC_CARGO', 'TIPO_CARG')
            ->where('CARG_VIGENCIA', 'SI')
            ->orderBy('DESC_CARGO')
            ->get();
    }

    public static function obtenerNivelEducativo()
    {
        return DB::connection('si_solmar')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            //->where('CARG_VIGENCIA', 'SI')
            //->orderBy('DESC_CARGO')
            ->get();
    }
            */
}

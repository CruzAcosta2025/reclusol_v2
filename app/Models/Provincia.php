<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'ADMI_PROVINCIA';
    protected $primaryKey = 'PROVI_CODIGO';
    public $timestamps = false;
    protected $fillable = ['PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO', 'PROVI_VIGENCIA'];


    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'DEPA_CODIGO', 'DEPA_CODIGO');
    }

    public function distritos()
    {
        return $this->hasMany(Distrito::class, 'PROVI_CODIGO', 'PROVI_CODIGO');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'PROVI_CODIGO', 'PROVI_CODIGO');
    }

    // scope ya existente (si usas otro nombre, ajústalo)
    public function scopeVigentes($q)
    {
        return $q->where('PROVI_VIGENCIA', 'SI')->orderBy('PROVI_DESCRIPCION');
    }

    public static function forSelect()
    {
        return self::vigentes()->pluck('PROVI_DESCRIPCION', 'PROVI_CODIGO');
    }

    /**
     * Devuelve un pluck('PROVI_DESCRIPCION','PROVI_CODIGO') para un departamento dado.
     * Usar: Provincia::forSelectByDepartamento('02');
     */
    public static function forSelectByDepartamento($depaCodigo)
    {
        return self::where('DEPA_CODIGO', $depaCodigo)
            ->where('PROVI_VIGENCIA', 'SI')
            ->orderBy('PROVI_DESCRIPCION')
            ->pluck('PROVI_DESCRIPCION', 'PROVI_CODIGO');
    }

    public static function forSelectByProvincia($provCodigo)
    {
        return self::vigentes()
            ->where('PROVI_CODIGO', $provCodigo)
            ->pluck('DIST_DESCRIPCION', 'DIST_CODIGO');
    }
}

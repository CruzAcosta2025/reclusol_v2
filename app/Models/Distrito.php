<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    // Modelo en SQL Server (conexión separada)
    protected $connection = 'si_solmar';
    protected $table = 'dbo.ADMI_DISTRITO';
    protected $primaryKey = 'DIST_CODIGO';
    public $timestamps = false;
    protected $fillable = ['DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO', 'DIST_VIGENCIA'];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'PROVI_CODIGO', 'PROVI_CODIGO');
    }

    public function scopeVigentes($q)
    {
        return $q->where('DIST_VIGENCIA', 'SI')->orderBy('DIST_DESCRIPCION');
    }

        public static function forSelect()
    {
        return self::vigentes()->pluck('DIST_DESCRIPCION', 'DIST_CODIGO');
    }

    public static function forSelectByProvincia($provCodigo)
    {
        return self::vigentes()
            ->where('PROVI_CODIGO', $provCodigo)
            ->pluck('DIST_DESCRIPCION', 'DIST_CODIGO');
    }
}

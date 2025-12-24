<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Departamento extends Model
{
    protected $connection = 'si_solmar';
    protected $table = 'dbo.ADMI_DEPARTAMENTO';
    protected $primaryKey = 'DEPA_CODIGO';
    public $timestamps = false;
    protected $fillable = ['DEPA_CODIGO', 'DEPA_DESCRIPCION', 'DEPA_VIGENCIA'];

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'DEPA_CODIGO', 'DEPA_CODIGO');
    }

    public function scopeVigentes($q)
    {
        return $q->where('DEPA_VIGENCIA', 'SI')->orderBy('DEPA_DESCRIPCION');
    }

    // Método reutilizable para selects (codigo => descripcion)
    public static function forSelect()
    {
        return self::vigentes()->pluck('DEPA_DESCRIPCION', 'DEPA_CODIGO');
    }

    public static function forSelectPadded()
    {
        return self::vigentes()
            ->get(['DEPA_CODIGO', 'DEPA_DESCRIPCION'])
            ->mapWithKeys(function ($r) {
                $code = (string) $r->DEPA_CODIGO;
                // normaliza: quita ceros a la izquierda y vuelve a poner 2 dígitos
                $padded = str_pad(ltrim($code, '0'), 2, '0', STR_PAD_LEFT);
                return [$padded => $r->DEPA_DESCRIPCION];
            });
    }
}

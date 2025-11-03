<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Sucursal extends Model
{
    protected $table = 'SISO_SUCURSAL';
    protected $primaryKey = 'SUCU_CODIGO';
    public $timestamps = false;
    protected $fillable = ['SUCU_CODIGO', 'SUCU_DESCRIPCION', 'SUCU_VIGENCIA'];

    /*

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'DEPA_CODIGO', 'DEPA_CODIGO');
    }
    */

    public const EXCLUIR_CODIGOS = ['11', '18','25']; // ej.

    public function scopeSinEspeciales($q)
    {
        return $q->whereNotIn('SUCU_CODIGO', self::EXCLUIR_CODIGOS);
    }


    public function scopeVigentes($q)
    {
        return $q->where('SUCU_VIGENCIA', 'SI')->orderBy('SUCU_DESCRIPCION');
    }

    // Método reutilizable para selects (codigo => descripcion)
    public static function forSelect()
    {
        return self::vigentes()
            ->sinEspeciales()
            ->pluck('SUCU_DESCRIPCION', 'SUCU_CODIGO');
    }


    public static function forSelectPadded()
    {
        return self::vigentes()
            ->sinEspeciales()
            ->get(['SUCU_CODIGO', 'SUCU_DESCRIPCION'])
            ->mapWithKeys(function ($r) {
                $code = (string) $r->SUCU_CODIGO;
                // normaliza: quita ceros a la izquierda y vuelve a poner 2 dígitos
                $padded = str_pad(ltrim($code, '0'), 2, '0', STR_PAD_LEFT);
                return [$padded => $r->SUCU_DESCRIPCION];
            });
    }
}

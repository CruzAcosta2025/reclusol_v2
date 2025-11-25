<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = 'CABECERA_SERVICIO_2014';
    protected $primaryKey = 'codigo_servicio';
    public $timestamps = false;
    protected $fillable = ['codigo_cliente', 'codigo_sucursal', 'estado_servicio', 'nombre_sucursal', 'nombre_cliente'];


    public const EXCLUIR_CODIGOS = ['11', '18', '25']; // ej.

    public function scopeSinEspeciales($q)
    {
        return $q->whereNotIn('codigo_sucursal', self::EXCLUIR_CODIGOS);
    }


    public function scopeVigentes($q)
    {
        return $q->where('estado_servicio', 3)->orderBy('nombre_cliente');
    }

    // Método reutilizable para selects (codigo => descripcion)
    public static function forSelect()
    {
        return self::vigentes()
            ->sinEspeciales()
            ->pluck('nombre_cliente', 'codigo_cliente');
    }


    public static function forSelectPadded()
    {
        return self::vigentes()
            ->sinEspeciales()
            ->get(['codigo_cliente', 'nombre_cliente'])
            ->mapWithKeys(function ($r) {
                // OJO: aquí debe usarse codigo_cliente, no nombre_cliente
                $code = (string) $r->codigo_cliente;

                // normaliza a 5 dígitos (00059, 00080, etc.)
                $padded = str_pad(ltrim($code, '0'), 5, '0', STR_PAD_LEFT);

                return [$padded => $r->nombre_cliente];
            });
    }
}

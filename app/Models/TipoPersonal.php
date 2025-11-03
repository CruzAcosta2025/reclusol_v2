<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPersonal extends Model
{
    protected $table = 'ADMI_TIPO_PERSONAL';
    protected $primaryKey = 'TIPE_CODIGO';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['TIPE_CODIGO', 'TIPE_DESCRIPCION', 'TIPE_PARA_VER'];


    public static function forSelect()
    {
        return self::whereIn('TIPE_CODIGO', ['01', '02'])
            ->orderBy('TIPE_CODIGO')
            ->pluck('TIPE_DESCRIPCION', 'TIPE_CODIGO'); // [codigo => descripcion]
    }
}

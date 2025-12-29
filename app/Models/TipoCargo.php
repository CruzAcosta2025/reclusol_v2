<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCargo extends Model
{
    protected $connection = 'si_solmar';
    protected $table = 'dbo.TIPO_CARGO';
    protected $primaryKey = 'CODI_TIPO_CARG';
    public $timestamps = false;
    protected $fillable = ['CODI_TIPO_CARG', 'DESC_TIPO_CARG'];

    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'TIPO_CARG', 'CODI_TIPO_CARG');
    }

    // Para selects: [CODI_TIPO_CARG => DESC_TIPO_CARG]
    public static function forSelect()
    {
        return self::orderBy('DESC_TIPO_CARG')->pluck('DESC_TIPO_CARG', 'CODI_TIPO_CARG');
    }
}
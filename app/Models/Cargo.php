<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'CARGOS';
    protected $primaryKey = 'CODI_CARG';
    public $incrementing = false; // si no es int autoincrement
    public $timestamps = false;   // si la tabla no tiene created_at/updated_at

    protected $fillable = ['CODI_CARG', 'DESC_CARGO', 'TIPO_CARG', 'CARG_VIGENCIA'];

    // Relación con TipoCargo
    public function tipo()
    {
        return $this->belongsTo(TipoCargo::class, 'TIPO_CARG', 'CODI_TIPO_CARG');
    }

    public function scopeVigentes($query)
    {
        return $query->where('CARG_VIGENCIA', 'SI')->orderBy('DESC_CARGO');
    }

    // Scope por tipo
    public function scopePorTipo($query, $tipoCodigo)
    {
        return $tipoCodigo ? $query->where('TIPO_CARG', $tipoCodigo) : $query;
    }

    // Para selects de cargos de un tipo: [CODI_CARG => DESC_CARGO]
    public static function forSelectByTipo($tipoCodigo)
    {
        return self::vigentes()->porTipo($tipoCodigo)->pluck('DESC_CARGO', 'CODI_CARG');
    }

        public static function forSelect()
    {
        return self::vigentes()->pluck('DESC_CARGO', 'CODI_CARG');
    }
}

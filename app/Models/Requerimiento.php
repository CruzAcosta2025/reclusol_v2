<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    protected $table = 'requerimientos';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'area_solicitante',
        'distrito',
        'provincia',
        'departamento',
        'sucursal',
        'cliente',
        'tipo_cargo',
        'cargo_solicitado',
        'cantidad_requerida',
        'fecha_limite',
        'edad_minima',
        'edad_maxima',
        'requiere_licencia_conducir',
        'requiere_sucamec',
        'nivel_estudios',
        'experiencia_minima',
        'requisitos_adicionales',
        'validado_rrhh',
        'escala_remunerativa',
        'prioridad',
        'estado'
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    public function estado()
    {
        return $this->belongsTo(EstadoRequerimiento::class, 'estado'); // 'estado' es la FK en tu tabla requerimientos
    }

    public function prioridad()
    {
        return $this->belongsTo(PrioridadRequerimiento::class, 'prioridad'); // 'estado' es la FK en tu tabla requerimientos
    }


    public function getEstadoAttribute($value)
    {
        // Si la relación ya se cargó, úsala
        if ($this->relationLoaded('estado') && $this->getRelation('estado')) {
            return $this->getRelation('estado');
        }

        // Si no, devuelve el valor crudo de la columna
        return $value;
    }

    public function getPrioridadAttribute($value)
    {
        // Si la relación ya se cargó, úsala
        if ($this->relationLoaded('prioridad') && $this->getRelation('prioridad')) {
            return $this->getRelation('prioridad');
        }

        // Si no, devuelve el valor crudo de la columna
        return $value;
    }
}

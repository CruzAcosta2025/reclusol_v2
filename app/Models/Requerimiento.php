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
        'sucursal',
        'cliente',
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
    ];
}



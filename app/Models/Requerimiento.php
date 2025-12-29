<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    protected $table = 'requerimientos';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'fecha_solicitud',
        'user_id',
        'cargo_usuario',
        'area_solicitante',
        'departamento',
        'provincia',
        'distrito',
        'sucursal',
        'tipo_personal',
        'tipo_cargo',
        'cargo_solicitado',
        'cliente',
        'ubicacion_servicio',
        'sede',
        'fecha_inicio',
        'fecha_fin',
        'urgencia',
        'cantidad_requerida',
        'cantidad_masculino',
        'cantidad_femenino',
        'edad_minima',
        'edad_maxima',
        'experiencia_minima',
        'curso_sucamec_operativo',
        'carne_sucamec_operativo',
        'licencia_armas',
       'servicio_acuartelado',
        'grado_academico',
        'formacion_adicional',
        'validado_rrhh',
        'escala_remunerativa',
        'prioridad',
        'estado',
        'fecha_limite',
        'requiere_licencia_conducir',
        'requiere_sucamec',
        'requisitos_adicionales',
        'sueldo_basico',
        'beneficios'
    ];

    protected $casts = [
        'fecha_solicitud' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_limite' => 'date',
        'validado_rrhh' => 'boolean',
        'requiere_licencia_conducir' => 'boolean',
        'requiere_sucamec' => 'boolean',
        'beneficios' => 'array',
        'servicio_acuartelado' => 'array',
    ];


    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoRequerimiento::class, 'estado'); // 'estado' es la FK en tu tabla requerimientos
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
}

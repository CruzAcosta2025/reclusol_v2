<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $table = 'postulantes';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nombres',
        'apellidos',
        'dni',
        'edad',
        'departamento',
        'provincia',
        'distrito',
        'celular',
        'celular_referencia',
        'estado_civil',
        'nacionalidad',
        'tipo_cargo',
        'cargo',
        'fecha_nacimiento',
        'fecha_postula',
        'experiencia_rubro',
        'sucamec',
        'carne_sucamec',
        'grado_instruccion',
        'servicio_militar',
        'licencia_arma',
        'licencia_conducir',
        'tipo_personal',
        'tipo_personal_codigo',
        'cv',
        'cul',
        'origin',
        'created_by',
        'estado',
        'decision',
        'comentario',
        'requerimiento_id'
    ];

    protected $casts = [
        'fecha_postula'     => 'datetime',
        'fecha_nacimiento'  => 'date',
        'created_by'        => 'integer',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function entrevistas()
    {
        return $this->hasMany(Entrevista::class);
    }

    public function requerimiento()
    {
        return $this->belongsTo(Requerimiento::class);
    }
}

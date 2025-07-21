<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    use HasFactory;

    protected $table = 'entrevistas';

    protected $fillable = [
        'postulante_id',
        'requerimiento_id',
        'entrevistador_id',
        'fecha_entrevista',
        'preguntas_json',
        'comentario_final',
        'resultado',
    ];

    protected $casts = [
        'preguntas' => 'array',
        'fecha_entrevista' => 'datetime',
    ];

    // Relaciones
    public function postulante()
    {
        return $this->belongsTo(Postulante::class);
    }

    public function requerimiento()
    {
        return $this->belongsTo(Requerimiento::class);
    }

    public function entrevistador()
    {
        return $this->belongsTo(User::class, 'entrevistador_id');
    }

    //public function respuestas()
   // {
     //   return $this->hasMany(EntrevistaRespuesta::class);
   // }
}

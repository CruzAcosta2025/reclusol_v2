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
        'preguntas',        // 👈 JSON con todo el detalle de la evaluación
        'comentario_final',
        'resultado',
    ];

    protected $casts = [
        'preguntas'       => 'array',     // 👈 se guarda como JSON
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
}

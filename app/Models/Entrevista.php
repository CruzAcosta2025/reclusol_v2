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
        'formacion',
        'otros_cursos',
        'competencias',
        'fortalezas',
        'oportunidades',
        'es_apto',
        'otro_puesto',
        'comentario',
        'comentario_final', // 👈 añade esto
        'resultado',        // si lo usas luego
    ];

    protected $casts = [
        'fecha_entrevista' => 'datetime',
        // estos dos asumo que los vas a guardar como JSON (checkboxes, etc.)
        'formacion'    => 'array',
        'competencias' => 'array',
    ];

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

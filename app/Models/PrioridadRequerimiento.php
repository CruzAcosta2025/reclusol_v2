<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrioridadRequerimiento extends Model
{
    protected $table = 'prioridad_requerimiento';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function prioridades()
    {
        return $this->hasMany(Requerimiento::class, 'prioridad');
    }
}

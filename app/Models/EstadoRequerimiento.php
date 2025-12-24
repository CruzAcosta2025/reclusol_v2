<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoRequerimiento extends Model
{
    protected $table = 'estado_requerimiento';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];

    public function requerimientos()
    {
        return $this->hasMany(Requerimiento::class, 'estado');
    }
}

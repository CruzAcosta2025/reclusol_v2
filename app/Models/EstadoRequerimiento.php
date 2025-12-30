<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoRequerimiento extends Model
{
    // Nombre de la tabla
    protected $table = 'estado_requerimiento';

    // Clave primaria
    protected $primaryKey = 'id';

    // Si NO usas timestamps en la tabla
    public $timestamps = false;

    // Campos que se pueden asignar en masa (opcional)
    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación inversa con Requerimiento
     */
    public function requerimientos()
    {
        return $this->hasMany(Requerimiento::class, 'estado');
    }
}

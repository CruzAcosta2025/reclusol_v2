<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $connection = 'si_solmar'; // conexión a tu SQL Server
    protected $table      = 'TIPO_CARGO';
    protected $primaryKey = 'CODI_TIPO_CARG';
    public $incrementing  = false; // ya que tu PK no es autoincremental
    protected $keyType    = 'string'; // o 'int' si es numérico

    public $timestamps = false; // si no tienes columnas created_at, updated_at

    // Si deseas exponer las columnas en tus respuestas JSON
    protected $fillable = ['DESC_TIPO_CARG'];
}

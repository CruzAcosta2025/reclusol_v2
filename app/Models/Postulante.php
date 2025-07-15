<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    // Nombre de la tabla si no sigue el plural por defecto
    protected $table = 'postulantes';

    // Clave primaria (Laravel asume 'id' por defecto, así que no es necesario declararla si usas 'id')
    protected $primaryKey = 'id';

    // Si no usas created_at/updated_at, pon false
    public $timestamps = true;

    // Campos que se pueden llenar con asignación masiva
    protected $fillable = [
        'nombres',
        'apellidos',
        'dni',
        'edad',
        'departamento',
        'provincia',
        'celular',
        'celular_referencia',
        'estado_civil',
        'nacionalidad',
        'cargo',
        'fecha_postula',
        'experiencia_rubro',
        'sucamec',
        'grado_instruccion',
        'servicio_militar',
        'licencia_arma',
        'licencia_conducir',
        'cv',
        'cul',
    ];
}

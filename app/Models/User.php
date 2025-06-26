<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Cargo;  

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* ---------- Config básicos ---------- */
    protected $table      = 'users';
    protected $primaryKey = 'id';
    public    $timestamps = true;

    /* Campos que puedes asignar masivamente */
    protected $fillable = [
        'name',
        'usuario',
        'cargo',        // ← aquí guardas el código “01”, “02”, “07”, …
        'contrasena',
    ];

    /* Ocultar al serializar (JSON, etc.) */
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /* Casts (Laravel 10/11 permite 'hashed') */
    protected $casts = [
        'contrasena' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* Laravel usará esto para validar la contraseña */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /* ---------- Relación & helper de cargo ---------- */

    /** Devuelve la fila de TIPO_CARGO con la descripción */
    public function cargoInfo()
    {
        return $this->belongsTo(Cargo::class, 'cargo', 'CODI_TIPO_CARG');
    }

    /** Comprueba si el usuario tiene un cargo específico */
    public function tieneCargo(string $codigo): bool
    {
        return $this->cargo === $codigo;
    }

    public function getCargoDescripcionAttribute(): string
{
    return $this->cargoInfo->DESC_TIPO_CARG ?? 'Sin rol';
}

}

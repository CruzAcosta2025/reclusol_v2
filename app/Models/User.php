<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /* ---------- Config básicos ---------- */
    protected $table      = 'users';
    protected $primaryKey = 'id';
    public    $timestamps = true;

    /* Campos que puedes asignar masivamente */
    protected $fillable = [
        'name',
        'sucursal',
        'contrasena',
        'cargo',
        'usuario',
        'rol',
    ];

    /* Ocultar al serializar (JSON, etc.) */
    protected $hidden = [
        'password',
        'contrasena',
        'remember_token',
    ];

    /* Casts (Laravel 10/11 permite 'hashed') */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        //'password' => 'hashed',
        'habilitado'=>'boolean',
        'contrasena' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* Laravel usará esto para validar la contraseña */
    public function getAuthPassword()
    {
        // Priorizar 'password' estándar de Laravel, fallback a 'contrasena'
        return $this->password ?? $this->contrasena;
    }

    /* ---------- Relación & helper de cargo ---------- */

    /** Devuelve la fila de TIPO_CARGO con la descripción */
    public function cargoInfo()
    {
        if (!$this->cargo) {
            return null;
        }

        // Usar TIPO_CARGO (o la tabla donde realmente está el catálogo de cargos)
        return DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->where('CODI_TIPO_CARG', $this->cargo)
            ->select(['CODI_TIPO_CARG', 'DESC_TIPO_CARG'])
            ->first();
    }

    /** Accessor para obtener información del cargo directamente */
    public function getCargoInfoAttribute()
    {
        if (!$this->cargo) {
            return null;
        }

        return DB::connection('si_solmar')
            ->table('CARGOS')
            ->where('CODI_CARG', $this->cargo)
            ->select(['CODI_CARG', 'DESC_CARGO'])
            ->first();
    }


    /** Comprueba si el usuario tiene un cargo específico */
    public function tieneCargo(string $codigo): bool
    {
        return $this->cargo === $codigo;
    }

    // Accessor para la descripción directa en la vista
    public function getCargoDescripcionAttribute(): string
    {
        return $this->cargoInfo()?->DESC_TIPO_CARG ?? 'Sin rol';
    }
}

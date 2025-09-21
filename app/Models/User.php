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
        'usuario',
        'cargo',
        'sucursal',
        'rol',
        'contrasena',
        'habilitado',
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
        'habilitado' => 'boolean',
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

    /**
     * Relación con TipoCargo (tabla TIPO_CARGO).
     * Asume que el campo `cargo` contiene CODI_TIPO_CARG cuando corresponde.
     * withDefault evita que $this->tipoCargo sea null y facilita el accessor.
     */
    public function tipoCargo()
    {
        return $this->belongsTo(TipoCargo::class, 'cargo', 'CODI_TIPO_CARG')->withDefault();
    }

    /**
     * Relación con Cargo (tabla CARGOS).
     * Asume que el campo `cargo` contiene CODI_CARG cuando corresponde.
     */
    public function cargoRelation()
    {
        return $this->belongsTo(Cargo::class, 'cargo', 'CODI_CARG')->withDefault();
    }

    /**
     * Relación con postulantes creados por este usuario.
     */
    public function postulantesCreados()
    {
        return $this->hasMany(Postulante::class, 'created_by');
    }

    /* -------------------------------
       Accessor simple para descripción del cargo
       ------------------------------- */

    /**
     * Devuelve una descripción legible del cargo:
     * - Prioriza DESC_TIPO_CARG (TipoCargo)
     * - Si no existe, usa DESC_CARGO (Cargo)
     * - Si ninguno existe, devuelve 'Sin rol'
     *
     * Uso en Blade: {{ $user->cargo_descripcion }}
     */
    public function getCargoDescripcionAttribute(): string
    {
        // Si las relaciones están eager-loaded, no hará queries adicionales.
        if ($this->relationLoaded('tipoCargo') && $this->tipoCargo && $this->tipoCargo->DESC_TIPO_CARG) {
            return $this->tipoCargo->DESC_TIPO_CARG;
        }

        if ($this->relationLoaded('cargoRelation') && $this->cargoRelation && $this->cargoRelation->DESC_CARGO) {
            return $this->cargoRelation->DESC_CARGO;
        }

        // Fallback: acceder a la relación (esto ejecutará una consulta sólo si no se hizo eager loading)
        return $this->tipoCargo->DESC_TIPO_CARG
            ?? $this->cargoRelation->DESC_CARGO
            ?? 'Sin rol';
    }

    /* -------------------------------
       Utilitarios
       ------------------------------- */

    /**
     * Comprueba si el usuario tiene un cargo específico (compara códigos).
     */
    public function tieneCargo(string $codigo): bool
    {
        return (string) $this->cargo === (string) $codigo;
    }
}

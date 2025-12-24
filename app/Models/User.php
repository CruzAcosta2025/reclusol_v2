<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $primaryKey = 'id';

    public    $timestamps = true;

    protected $fillable = [
        'name',
        'usuario',
        'cargo',
        'sucursal',
        'rol',
        'contrasena',
        'habilitado',
    ];

    protected $hidden = [
        'password',
        'contrasena',
        'remember_token',
    ];

    protected $casts = [
        //'email_verified_at' => 'datetime',
        //'password' => 'hashed',
        'habilitado' => 'boolean',
        'contrasena' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password ?? $this->contrasena;
    }

    public function tipoCargo()
    {
        return $this->belongsTo(TipoCargo::class, 'cargo', 'CODI_TIPO_CARG')->withDefault();
    }

    public function cargoRelation()
    {
        return $this->belongsTo(Cargo::class, 'cargo', 'CODI_CARG')->withDefault();
    }

    public function postulantesCreados()
    {
        return $this->hasMany(Postulante::class, 'created_by');
    }

    public function getCargoDescripcionAttribute(): string
    {
        if ($this->relationLoaded('tipoCargo') && $this->tipoCargo && $this->tipoCargo->DESC_TIPO_CARG) {
            return $this->tipoCargo->DESC_TIPO_CARG;
        }

        if ($this->relationLoaded('cargoRelation') && $this->cargoRelation && $this->cargoRelation->DESC_CARGO) {
            return $this->cargoRelation->DESC_CARGO;
        }

        return $this->tipoCargo->DESC_TIPO_CARG
            ?? $this->cargoRelation->DESC_CARGO
            ?? 'Sin rol';
    }

    public function tieneCargo(string $codigo): bool
    {
        return (string) $this->cargo === (string) $codigo;
    }
}

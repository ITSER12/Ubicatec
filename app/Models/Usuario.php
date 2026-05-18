<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table        = 'usuarios';
    protected $primaryKey   = 'no_control';
    public    $incrementing = false;
    protected $keyType      = 'string';
    public    $timestamps   = false;

    protected $fillable = [
        'no_control',
        'nombre',
        'ape_paterno',
        'ape_materno',
        'correo',
        'password',
        'cod_carrera',
        'foto',
        'descripcion',
        'rol',
        'codigo_verificacion',
        'codigo_expira',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'codigo_verificacion',
    ];

    protected $casts = [
        'codigo_expira' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | FOTO PERFIL
    |--------------------------------------------------------------------------
    */

    public function getFotoUrlAttribute(): string
    {
        $nombre = trim(
            ($this->nombre ?? '') . ' ' .
            ($this->ape_paterno ?? '')
        );

        return $this->foto
            ? asset('storage/' . $this->foto)
            : 'https://ui-avatars.com/api/?name='
                . urlencode($nombre)
                . '&background=1a3c4e&color=ffffff&size=128&bold=true';
    }

    /*
    |--------------------------------------------------------------------------
    | LABEL ROL
    |--------------------------------------------------------------------------
    */

    public function getRolLabelAttribute(): string
    {
        return match ($this->rol) {
            'administrador'     => 'Administrador',
            'docente'           => 'Docente',
            'jefe_departamento' => 'Jefe de Departamento',
            'estudiante'        => 'Estudiante / Visitante',
            default             => ucfirst($this->rol ?? 'Sin rol'),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDACIONES DE ROLES
    |--------------------------------------------------------------------------
    */

    public function tieneRol(array $roles): bool
    {
        return in_array($this->rol, $roles);
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esDocente(): bool
    {
        return $this->rol === 'docente';
    }

    public function esJefeDepartamento(): bool
    {
        return $this->rol === 'jefe_departamento';
    }

    public function esEstudiante(): bool
    {
        return $this->rol === 'estudiante';
    }
}
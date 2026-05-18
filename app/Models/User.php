<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ──────────────────────────────────────────
    // Campos asignables masivamente
    // ──────────────────────────────────────────
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'descripcion',
        'rol',
    ];

    // ──────────────────────────────────────────
    // Campos ocultos en arrays/JSON
    // ──────────────────────────────────────────
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ──────────────────────────────────────────
    // Castings de tipos
    // ──────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ──────────────────────────────────────────
    // ACCESSORS
    // ──────────────────────────────────────────

    /**
     * URL de la foto de perfil.
     * Si no tiene foto, genera un avatar automático con sus iniciales.
     */
    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
              . '&background=1a3c4e&color=ffffff&size=128&bold=true';
    }

    /**
     * Etiqueta legible del rol para mostrar en vistas.
     */
    public function getRolLabelAttribute(): string
    {
        return match($this->rol) {
            'administrador'     => 'Administrador',
            'docente'           => 'Docente',
            'jefe_departamento' => 'Jefe de Departamento',
            'estudiante'        => 'Estudiante / Visitante',
            default             => ucfirst($this->rol),
        };
    }

    // ──────────────────────────────────────────
    // HELPERS DE ROL
    // ──────────────────────────────────────────

    /**
     * Verifica si el usuario es administrador.
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'administrador';
    }

    /**
     * Verifica si el usuario es docente.
     */
    public function esDocente(): bool
    {
        return $this->rol === 'docente';
    }

    /**
     * Verifica si el usuario es jefe de departamento.
     */
    public function esJefeDepartamento(): bool
    {
        return $this->rol === 'jefe_departamento';
    }

    /**
     * Verifica si el usuario es estudiante o visitante.
     */
    public function esEstudiante(): bool
    {
        return $this->rol === 'estudiante';
    }

    /**
     * Verifica si tiene alguno de los roles indicados.
     * Uso: $user->tieneRol(['administrador', 'docente'])
     */
    public function tieneRol(array $roles): bool
    {
        return in_array($this->rol, $roles);
    }
}
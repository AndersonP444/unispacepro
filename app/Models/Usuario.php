<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'rol_id',
        'carrera_id',
        'nombre',
        'apellido',
        'cedula',
        'email',
        'password',
        'telefono',
        'foto_perfil',
        'biografia',
        'gustos',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verificado_en' => 'datetime',
        'activo'              => 'boolean',
        'password'            => 'hashed',
    ];

    // Laravel auth usa esta columna para verificar email
    public function getEmailVerifiedAtColumn(): string
    {
        return 'email_verificado_en';
    }

    // ─── Relaciones ──────────────────────────────────────────────

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'profesor_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'usuario_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function nombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function esAdmin(): bool
    {
        return $this->rol_id === 1;
    }

    public function esSupervisor(): bool
    {
        return $this->rol_id === 2;
    }

    public function esEstudiante(): bool
    {
        return $this->rol_id === 3;
    }

    public function esProfesor(): bool
    {
        return $this->rol_id === 4;
    }

    public function fotoPerfilUrl(): ?string
    {
        if (!$this->foto_perfil) {
            return null;
        }

        if (str_starts_with($this->foto_perfil, 'http://') || str_starts_with($this->foto_perfil, 'https://')) {
            return $this->foto_perfil;
        }

        if (str_starts_with($this->foto_perfil, 'images/')) {
            return asset($this->foto_perfil);
        }

        return asset('storage/' . ltrim($this->foto_perfil, '/'));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones';

    protected $fillable = [
        'edificio_id',
        'nombre',
        'codigo',
        'filas',
        'columnas',
        'capacidad',
        'foto',
        'equipos',
        'estado',
    ];

    // ─── Relaciones ──────────────────────────────────────────────

    public function edificio()
    {
        return $this->belongsTo(Edificio::class, 'edificio_id');
    }

    public function butacas()
    {
        return $this->hasMany(Butaca::class, 'salon_id');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'salon_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function estaDisponible(): bool
    {
        return $this->estado === 'disponible';
    }

    public function estadoColor(): string
    {
        return match ($this->estado) {
            'disponible'  => 'verde',
            'en_uso'      => 'rojo',
            'mantenimiento' => 'gris',
            default       => 'gris',
        };
    }
}

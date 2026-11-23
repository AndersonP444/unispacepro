<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Butaca extends Model
{
    protected $table = 'butacas';

    protected $fillable = [
        'salon_id',
        'fila',
        'columna',
        'etiqueta',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    // ─── Relaciones ──────────────────────────────────────────────

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'butaca_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function estaOcupada(int $funcion_id): bool
    {
        return $this->tickets()
            ->where('funcion_id', $funcion_id)
            ->where('estado', 'activo')
            ->exists();
    }
}

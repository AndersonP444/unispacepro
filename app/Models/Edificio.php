<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edificio extends Model
{
    protected $table = 'edificios';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'mapa_x',
        'mapa_y',
    ];

    protected $casts = [
        'mapa_x' => 'float',
        'mapa_y' => 'float',
    ];

    // ─── Relaciones ──────────────────────────────────────────────

    public function salones()
    {
        return $this->hasMany(Salon::class, 'edificio_id');
    }
}

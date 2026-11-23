<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carreras';

    protected $fillable = ['nombre', 'codigo'];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'carrera_id');
    }
}

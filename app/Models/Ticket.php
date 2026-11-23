<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Este modelo representa las reservas o tickets de asiento que hacen los estudiantes para asistir a clases.
class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'usuario_id',
        'funcion_id',
        'butaca_id',
        'codigo_ticket',
        'codigo_qr',
        'google_calendar_evento_id',
        'estado',
        'referencia_pago',
        'telefono_pago',
        'banco_pago',
        'monto_pagado',
        'fecha_inscripcion',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
    ];

    // Definimos la relación para obtener los datos del estudiante dueño de este ticket.
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Definimos la relación para conocer la clase o función de curso a la cual corresponde la reserva.
    public function funcion()
    {
        return $this->belongsTo(Funcion::class, 'funcion_id');
    }

    // Definimos la relación para saber cuál asiento específico (butaca) tiene asignado el alumno.
    public function butaca()
    {
        return $this->belongsTo(Butaca::class, 'butaca_id');
    }

    // Método sencillo para verificar rápidamente si el ticket sigue activo en el sistema.
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function estaPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    // Función que genera un código único de ticket estructurado.
    // Combinamos la cédula del estudiante, la etiqueta de la butaca, el código del curso y el horario de inicio.
    // Al final le agregamos un hash único de 6 caracteres para evitar colisiones o falsificaciones.
    public static function generarCodigo(string $cedula, string $etiqueta_butaca, string $codigo_curso, string $horario): string
    {
        $base = strtoupper("{$cedula}-{$etiqueta_butaca}-{$codigo_curso}-{$horario}");
        return $base . '-' . strtoupper(substr(md5($base), 0, 6));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    protected $table = 'funciones';

    protected $fillable = [
        'curso_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'cupo_maximo',
    ];

    // ─── Relaciones ──────────────────────────────────────────────

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'funcion_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function cuposDisponibles(): int
    {
        $ocupados = $this->tickets()->where('estado', 'activo')->count();
        return max(0, $this->cupo_maximo - $ocupados);
    }

    public function estaLlena(): bool
    {
        return $this->cuposDisponibles() === 0;
    }

    public function diaDisplay(): string
    {
        return match ($this->dia_semana) {
            'lunes'     => 'Lunes',
            'martes'    => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves'    => 'Jueves',
            'viernes'   => 'Viernes',
            default     => ucfirst($this->dia_semana),
        };
    }

    public function horario(): string
    {
        return substr($this->hora_inicio, 0, 5) . ' – ' . substr($this->hora_fin, 0, 5);
    }

    public function numeroDiaSemana(): int
    {
        return match (strtolower($this->dia_semana)) {
            'lunes'     => 1,
            'martes'    => 2,
            'miercoles' => 3,
            'jueves'    => 4,
            'viernes'   => 5,
            'sabado'    => 6,
            'domingo'   => 0,
            default     => 1,
        };
    }

    public function generarUrlGoogleCalendar($ticket): string
    {
        $titulo = "Clase: " . $this->curso->nombre;
        $detalles = "Reserva en UnispacePro.\nTicket: " . $ticket->codigo_ticket . "\nButaca: " . $ticket->butaca->etiqueta;
        $ubicacion = "Salón: " . $this->curso->salon->codigo . " - " . $this->curso->salon->nombre;
        
        $diaIngles = match (strtolower($this->dia_semana)) {
            'lunes'     => 'next monday',
            'martes'    => 'next tuesday',
            'miercoles' => 'next wednesday',
            'jueves'    => 'next thursday',
            'viernes'   => 'next friday',
            'sabado'    => 'next saturday',
            'domingo'   => 'next sunday',
            default     => 'next monday',
        };
        
        $fechaInicio = \Carbon\Carbon::parse($diaIngles . ' ' . $this->hora_inicio)->format('Ymd\THis');
        $fechaFin = \Carbon\Carbon::parse($diaIngles . ' ' . $this->hora_fin)->format('Ymd\THis');

        return "https://calendar.google.com/calendar/render?action=TEMPLATE" . 
               "&text=" . urlencode($titulo) . 
               "&details=" . urlencode($detalles) . 
               "&location=" . urlencode($ubicacion) . 
               "&dates=" . $fechaInicio . "/" . $fechaFin;
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

// Esta clase se encarga de crear y enviar el correo electrónico de confirmación
// que recibe un estudiante inmediatamente después de que completa una reserva de asiento de manera exitosa.
class ReservaConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    // Al construir recibimos toda la información del ticket recién creado.
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    // Definimos el asunto que el usuario verá en su bandeja de entrada.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu Reserva Confirmada! - UnispacePro',
        );
    }

    // Vinculamos la vista blade que contiene el diseño y estructura visual del ticket enviado por correo.
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
        );
    }

    // Devolvemos una lista vacía ya que no requerimos adjuntar ningún documento o PDF externo.
    public function attachments(): array
    {
        return [];
    }
}

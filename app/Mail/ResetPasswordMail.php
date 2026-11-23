<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Esta clase Mailable se encarga de estructurar el correo electrónico que le llega al usuario
// cuando este solicita restablecer o recuperar su contraseña en la plataforma.
class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    // Al construir la clase pasamos el token de seguridad y el correo del destinatario.
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    // Definimos el asunto (subject) que verá el usuario en su bandeja de entrada.
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de Contraseña - UniSpace',
        );
    }

    // Asociamos la vista blade específica que contiene el diseño del correo de recuperación.
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
        );
    }

    // Por ahora no necesitamos adjuntar archivos, así que retornamos un arreglo vacío.
    public function attachments(): array
    {
        return [];
    }
}

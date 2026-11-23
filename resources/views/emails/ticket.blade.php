<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Reserva - UnispacePro</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f2; margin: 0; padding: 20px; color: #1a2c3f; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #457b9d; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; }
        .content p { line-height: 1.6; margin-bottom: 20px; }
        .ticket-box { background-color: #f8f9fa; border: 2px dashed #457b9d; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .ticket-box h2 { margin-top: 0; color: #1d3557; font-size: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 10px; }
        .ticket-details { width: 100%; border-collapse: collapse; }
        .ticket-details td { padding: 8px 0; border-bottom: 1px solid #eee; }
        .ticket-details td strong { color: #457b9d; }
        .code { text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 1px; color: #dc3545; font-weight: bold; padding: 15px 0; }
        .footer { background-color: #1d3557; color: #ffffff; text-align: center; padding: 20px; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #457b9d; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UnispacePro</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Universidad Marítima del Caribe</p>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $ticket->usuario->nombre }}</strong>,</p>
            <p>Tu reserva ha sido procesada exitosamente. A continuación, te presentamos los detalles de tu ticket de acceso al espacio universitario.</p>
            
            <div class="ticket-box">
                <h2>{{ $ticket->funcion->curso->nombre }}</h2>
                <table class="ticket-details">
                    <tr>
                        <td width="30%"><strong>Salón:</strong></td>
                        <td>{{ $ticket->funcion->curso->salon->codigo }} ({{ $ticket->funcion->curso->salon->nombre }})</td>
                    </tr>
                    <tr>
                        <td><strong>Día y Hora:</strong></td>
                        <td>{{ $ticket->funcion->diaDisplay() }}, {{ $ticket->funcion->horario() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Profesor:</strong></td>
                        <td>{{ $ticket->funcion->curso->profesor->nombreCompleto() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Butaca:</strong></td>
                        <td>{{ $ticket->butaca->etiqueta }}</td>
                    </tr>
                    @if($ticket->referencia_pago)
                    <tr>
                        <td><strong>Referencia Pago:</strong></td>
                        <td>#{{ $ticket->referencia_pago }} ({{ $ticket->banco_pago }} - ${{ number_format($ticket->monto_pagado, 2) }} USD)</td>
                    </tr>
                    @endif
                </table>
                <div class="code">
                    CÓDIGO: {{ $ticket->codigo_ticket }}
                </div>
                <div style="text-align: center; margin: 10px 0 15px 0;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($ticket->codigo_ticket) }}" alt="QR {{ $ticket->codigo_ticket }}" width="150" height="150" style="border: 4px solid #ffffff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                </div>
                <div style="text-align: center; margin-top: 15px;">
                    <a href="{{ $ticket->funcion->generarUrlGoogleCalendar($ticket) }}" class="btn">Agendar en Google Calendar</a>
                </div>
            </div>
            
            <p>Recuerda presentar este código al ingresar al salón. El código QR también está disponible en tu portal de estudiante en todo momento.</p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a esta dirección.</p>
            <p>Nuestra página web está realizada íntegramente en software libre dando fiel cumplimiento a la Ley de Info-gobierno.</p>
        </div>
    </div>
</body>
</html>

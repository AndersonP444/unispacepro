@extends('layouts.public')

@section('title', 'Mi Portal')

@section('content')
<section class="dashboard-section">
    <div class="container">
        
        <!-- HEADER DEL DASHBOARD -->
        <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div class="user-welcome" style="display: flex; align-items: center; gap: 1rem; min-width: 0; flex: 1;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; flex-shrink: 0;">
                    {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1)) }}
                </div>
                <div style="min-width: 0; flex: 1;">
                    <h2 style="margin:0; overflow-wrap: anywhere; word-break: break-word;">Hola, {{ $usuario->nombre }}</h2>
                    <p style="margin:0; font-size:0.9rem; color:#64748b;">Estudiante | C.I: {{ $usuario->cedula }} | {{ $usuario->carrera ? $usuario->carrera->nombre : 'Sin carrera asignada' }}</p>
                    @if($usuario->biografia)
                        <div style="margin-top: 0.45rem; font-size: 0.85rem; color: #334155; background: #f8fafc; padding: 0.55rem 0.8rem; border-radius: 8px; border-left: 3px solid var(--primary-color); white-space: pre-line; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word; max-width: 100%;">{{ $usuario->biografia }}</div>
                    @endif
                </div>
            </div>
            
            <!-- WIDGET CLIMA -->
            @if(isset($clima))
            <div class="weather-widget" style="background: rgba(255,255,255,0.95); padding: 0.6rem 1.15rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 0.7rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--secondary-color)" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>
                <div>
                    <strong style="color: var(--primary-color); font-size: 1.05rem;">{{ $clima['temperatura'] }}°C ({{ $clima['condicion'] }})</strong>
                    <span style="color: var(--secondary-color); font-size: 0.78rem; display: block; line-height: 1.1;">Campus UMC - Catia la Mar</span>
                </div>
            </div>
            @endif
        </div>

        <!-- MENSAJES DE ALERTA -->
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>¡Hecho!</strong> {{ session('success') }}
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: #0f5132; cursor: pointer; line-height: 1;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>Error:</strong> {{ session('error') }}
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: #842029; cursor: pointer; line-height: 1;">&times;</button>
            </div>
        @endif

        <div class="dashboard-grid">
            
            <!-- PANEL IZQUIERDO: MIS TICKETS ACTIVOS -->
            <div class="dashboard-main">
                @if(isset($ticketsPendientes) && $ticketsPendientes->count() > 0)
                    <h3 class="panel-title" style="color: #b45309;">Solicitudes de Auditorio en Verificacion de Pago</h3>
                    <div class="tickets-list" style="margin-bottom: 2rem;">
                        @foreach($ticketsPendientes as $tp)
                            <div class="ticket-card" style="border-left: 4px solid #d97706; background: #fffbeb; padding: 1.15rem; border-radius: 10px; margin-bottom: 1rem; border: 1px solid #fde68a;">
                                <div class="ticket-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.65rem;">
                                    <div>
                                        <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 4px;">{{ $tp->funcion->curso->tipoBadge() }}</span>
                                        <h4 style="margin: 0.25rem 0 0 0; color: #0f172a;">{{ $tp->funcion->curso->nombre }}</h4>
                                    </div>
                                    <span style="background: #d97706; color: #ffffff; font-size: 0.76rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 50px;">En Verificacion</span>
                                </div>
                                <div style="font-size: 0.88rem; color: #334155;">
                                    <p style="margin: 0.2rem 0;"><strong>Ubicacion:</strong> {{ $tp->funcion->curso->salon->codigo }} ({{ $tp->funcion->curso->salon->nombre }}) | <strong>Butaca Separada:</strong> {{ $tp->butaca->etiqueta }}</p>
                                    <p style="margin: 0.2rem 0;"><strong>Horario:</strong> {{ $tp->funcion->diaDisplay() }}, {{ $tp->funcion->horario() }}</p>
                                    <p style="margin: 0.2rem 0;"><strong>Pago Movil Reportado:</strong> Ref. <span class="mono">#{{ $tp->referencia_pago }}</span> ({{ $tp->banco_pago }}) - Monto: ${{ number_format($tp->monto_pagado, 2) }} USD</p>
                                    <p style="margin: 0.45rem 0 0 0; font-size: 0.8rem; color: #92400e;">El Administrador verificara tu referencia de Pago Movil. Al ser aprobada recibiras tu correo con el codigo QR y se habilitara aqui tu pase con boton a Google Calendar.</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <h3 class="panel-title">Tus Reservas y Entradas Activas</h3>
                
                @if($ticketsActivos->count() > 0)
                    <div class="tickets-list">
                        @foreach($ticketsActivos as $ticket)
                            <div class="ticket-card active">
                                <div class="ticket-header">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="{{ asset($ticket->funcion->curso->foto ?: 'images/carousel/edificio_nautica.jpg') }}" alt="{{ $ticket->funcion->curso->nombre }}" style="width: 44px; height: 44px; border-radius: 8px; object-fit: cover; border: 1px solid #cbd5e1;">
                                        <div>
                                            <span class="badge badge-{{ $ticket->funcion->curso->tipo }}" style="margin-bottom: 0.2rem;">{{ $ticket->funcion->curso->tipoBadge() }}</span>
                                            <h4 style="margin: 0;">{{ $ticket->funcion->curso->nombre }}</h4>
                                        </div>
                                    </div>
                                    <span class="status-badge status-completado">Activo</span>
                                </div>
                                <div class="ticket-body">
                                    <div class="ticket-info">
                                        <p><strong>Ubicación:</strong> {{ $ticket->funcion->curso->salon->codigo }} ({{ $ticket->funcion->curso->salon->nombre }})</p>
                                        <p><strong>Horario:</strong> {{ $ticket->funcion->diaDisplay() }}, {{ $ticket->funcion->horario() }}</p>
                                        <p><strong>Butaca:</strong> {{ $ticket->butaca->etiqueta }}</p>
                                        <p><strong>Código:</strong> <span class="mono">{{ $ticket->codigo_ticket }}</span></p>
                                        @if($ticket->referencia_pago)
                                            <p><strong>Pago Verificado:</strong> Ref. <span class="mono">#{{ $ticket->referencia_pago }}</span> (${{ number_format($ticket->monto_pagado, 2) }} USD)</p>
                                        @endif
                                    </div>
                                    <div class="ticket-qr-placeholder" data-code="{{ $ticket->codigo_ticket }}">
                                        <!-- El QR se inyecta por JS -->
                                    </div>
                                </div>
                                <div class="ticket-footer" style="display: flex; gap: 0.5rem; border-top: 1px solid #eee; padding-top: 1rem; margin-top: 1rem; justify-content: flex-end;">
                                    <a href="{{ $ticket->funcion->generarUrlGoogleCalendar($ticket) }}" target="_blank" class="btn btn-outline btn-sm" style="display: inline-flex; align-items: center; gap: 0.3rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                                        Agendar
                                    </a>
                                    <form action="{{ route('reserva.cancelar', $ticket->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm btn-cancelar" style="color: #dc3545; border-color: #dc3545;" onclick="return confirm('¿Estás seguro de cancelar tu reserva? Esta acción liberará la butaca para otro estudiante.')">Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>No tienes reservas activas en este momento.</p>
                        <a href="{{ route('cursos') }}" class="btn btn-primary">Buscar Cursos y Talleres</a>
                    </div>
                @endif

                <h3 class="panel-title mt-4">Historial de Reservas</h3>
                @if($historialTickets->count() > 0)
                    <div class="tickets-list historical">
                        @foreach($historialTickets as $ht)
                            <div class="ticket-card inactive" style="border-left: 4px solid #64748b; margin-bottom: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                                <div class="ticket-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                    <h4 style="margin: 0; color: #334155;">{{ $ht->funcion->curso->nombre }}</h4>
                                    <span class="status-badge status-{{ $ht->estado }}" style="font-size: 0.8rem; padding: 0.2rem 0.6rem; border-radius: 20px; background: #e2e8f0; color: #475569;">{{ ucfirst($ht->estado) }}</span>
                                </div>
                                <div style="font-size: 0.85rem; color: #64748b;">
                                    <p style="margin: 0;"><strong>Constancia de Asistencia</strong></p>
                                    <p style="margin: 0;">Duración: {{ $ht->funcion->curso->semanas_totales }} semanas</p>
                                    <p style="margin: 0;">Periodo: {{ $ht->funcion->curso->fecha_inicio->format('d/m/Y') }} - {{ $ht->funcion->curso->fecha_fin->format('d/m/Y') }}</p>
                                    <p style="margin: 0;">Código de Verificación: {{ $ht->codigo_ticket }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No hay historial disponible.</p>
                @endif
            </div>

            <!-- PANEL DERECHO: GOOGLE CALENDAR -->
            <div class="dashboard-sidebar" style="min-width: 0;">
                <h3 class="panel-title">Tu Cronograma</h3>
                <div class="calendar-widget">
                    <!-- Contenedor para FullCalendar con barra de desplazamiento interna -->
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<style>
/* Personalización de FullCalendar como Mini Widget */
#calendar {
    font-family: var(--font-main);
    font-size: 0.75rem; /* Letra más pequeña para el sidebar */
    width: 100%;
}
.fc-header-toolbar {
    margin-bottom: 0.5rem !important;
    display: flex;
    flex-wrap: wrap;
    gap: 0.2rem;
    justify-content: space-between;
}
.fc .fc-toolbar-title {
    font-size: 1rem !important;
    font-weight: 700;
    color: var(--primary-hover);
    text-transform: capitalize;
}
.fc .fc-button {
    padding: 0.2rem 0.4rem !important;
    font-size: 0.75rem !important;
}
.fc .fc-button-primary {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    box-shadow: none !important;
}
.fc .fc-col-header-cell-cushion {
    font-size: 0.7rem;
    padding: 4px 0 !important;
}
.fc .fc-daygrid-day-number {
    font-size: 0.8rem;
    padding: 2px !important;
}
.fc-theme-standard td, .fc-theme-standard th {
    border-color: #eaedf1;
}
.fc-scroller {
    overflow-y: auto !important;
}
/* Estilos para modo Lista (Agenda) */
.fc .fc-list-empty {
    padding: 2rem !important;
    text-align: center;
    background-color: var(--light-color);
    color: var(--secondary-color);
}
.fc-theme-standard .fc-list {
    border-color: #eaedf1;
}
.fc .fc-list-day-cushion {
    background-color: #f8f9fa !important;
    font-weight: 700;
    color: var(--primary-hover);
    padding: 8px 12px !important;
}
.fc-list-event-title {
    font-weight: 600;
    color: var(--dark-color);
    overflow-wrap: anywhere;
    word-break: break-word;
}
.fc-list-event-time {
    color: var(--primary-color);
    font-weight: 500;
    white-space: nowrap;
}
/* Estilo del contenedor principal del widget con barra de desplazamiento interna */
.calendar-widget {
    background: #ffffff;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #eaedf1;
    height: auto !important;
    max-height: 440px;
    overflow-y: auto !important;
    overflow-x: hidden !important;
}
.calendar-widget::-webkit-scrollbar {
    width: 7px;
}
.calendar-widget::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 8px;
}
.calendar-widget::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 8px;
}
.calendar-widget::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. GENERADOR DE QR
        const qrContainers = document.querySelectorAll('.ticket-qr-placeholder');
        
        qrContainers.forEach(container => {
            const ticketCode = container.getAttribute('data-code');
            container.innerHTML = '';
            
            new QRCode(container, {
                text: ticketCode,
                width: 90,
                height: 90,
                colorDark : "#1a2c3f",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            
            container.style.border = 'none';
            container.style.background = 'transparent';
        });

        // 2. FULLCALENDAR.JS LEYENDO TICKETS DE LA DB (Modo Widget Compacto / Agenda)
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'listMonth', // Modo Agenda/Lista
            locale: 'es',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,next' 
            },
            height: 'auto',
            contentHeight: 'auto',
            events: [
                @foreach($ticketsActivos as $ticket)
                {
                    title: '{{ $ticket->funcion->curso->nombre }}',
                    startTime: '{{ $ticket->funcion->hora_inicio }}',
                    endTime: '{{ $ticket->funcion->hora_fin }}',
                    daysOfWeek: [ {{ $ticket->funcion->numeroDiaSemana() }} ],
                    startRecur: '{{ $ticket->funcion->curso->fecha_inicio->format("Y-m-d") }}',
                    endRecur: '{{ $ticket->funcion->curso->fecha_fin->copy()->addDay()->format("Y-m-d") }}',
                    color: 'var(--primary-color)'
                },
                @endforeach
            ]
        });
        calendar.render();
    });
</script>
@endpush

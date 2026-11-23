@extends('layouts.public')

@section('title', 'Portal del Profesor')

@section('content')
@php
    $totalHorarios = $cursosImpartidos->sum(fn($c) => $c->funciones->count());
    $totalInscritos = $cursosImpartidos->sum(function($c) {
        return $c->funciones->sum(fn($f) => $f->tickets->where('estado', 'activo')->count());
    });
    $gustosProf = $usuario->gustos ? array_filter(array_map('trim', explode(',', $usuario->gustos))) : [];
@endphp
<section class="dashboard-section">
    <div class="container">
        
        <!-- HEADER DEL DASHBOARD DEL PROFESOR -->
        <div class="dashboard-header prof-dashboard-header">
            <div class="user-welcome" style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; min-width: 0; flex: 1;">
                <div class="prof-header-avatar-ring">
                    @if($usuario->fotoPerfilUrl())
                        <img src="{{ $usuario->fotoPerfilUrl() }}" alt="{{ $usuario->nombreCompleto() }}" class="prof-header-avatar-img">
                    @else
                        <div class="prof-header-avatar-initials">
                            {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="min-width: 0; flex: 1;">
                    <span class="prof-mode-badge">Portal Docente UMC</span>
                    <h2 style="margin: 0.25rem 0 0.2rem 0; color: #0f172a; overflow-wrap: anywhere; word-break: break-word;">Bienvenido, Prof. {{ $usuario->nombre }} {{ $usuario->apellido }}</h2>
                    <p style="margin: 0; font-size: 0.9rem; color: #64748b;">
                        C.I: {{ $usuario->cedula }} | {{ $usuario->email }}
                        @if($usuario->telefono) | Tel: {{ $usuario->telefono }} @endif
                    </p>
                    @if($usuario->biografia)
                        <div style="margin-top: 0.55rem; font-size: 0.88rem; color: #334155; background: #f8fafc; padding: 0.65rem 0.9rem; border-radius: 8px; border-left: 3px solid var(--secondary-color); white-space: pre-line; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word; max-width: 100%;">{{ $usuario->biografia }}</div>
                    @endif
                    @if(count($gustosProf) > 0)
                        <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.5rem;">
                            @foreach($gustosProf as $g)
                                <span style="font-size: 0.75rem; font-weight: 600; background: #eff6ff; color: #1e40af; padding: 0.18rem 0.6rem; border-radius: 999px; border: 1px solid #bfdbfe;">
                                    {{ $g }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('perfil.index') }}" class="btn btn-outline btn-sm">
                    Editar Perfil / Foto
                </a>
                <a href="{{ route('profesores', ['profesor' => $usuario->nombreCompleto()]) }}" class="btn btn-outline btn-sm">
                    Ver mi Ficha Pública
                </a>
                <a href="{{ route('cursos', ['profesor' => $usuario->nombreCompleto()]) }}" class="btn btn-primary btn-sm">
                    Ver en Cursos y Talleres
                </a>
            </div>
        </div>

        <!-- TARJETAS DE ESTADÍSTICAS ANIMADAS -->
        <div class="prof-stats-grid">
            <div class="prof-stat-card" style="animation-delay: 0.05s;">
                <div class="prof-stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div>
                    <span class="prof-stat-number">{{ $cursosImpartidos->count() }}</span>
                    <span class="prof-stat-label">Cursos y Talleres Asignados</span>
                </div>
            </div>
            <div class="prof-stat-card" style="animation-delay: 0.12s;">
                <div class="prof-stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div>
                    <span class="prof-stat-number">{{ $totalHorarios }}</span>
                    <span class="prof-stat-label">Secciones / Horarios Activos</span>
                </div>
            </div>
            <div class="prof-stat-card" style="animation-delay: 0.19s;">
                <div class="prof-stat-icon" style="background: #fefce8; color: #ca8a04;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <span class="prof-stat-number">{{ $totalInscritos }}</span>
                    <span class="prof-stat-label">Estudiantes Inscritos</span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
            
            <!-- PANEL PRINCIPAL: CURSOS ASIGNADOS -->
            <div class="dashboard-main">
                <h3 class="panel-title">Tus Cursos y Talleres Asignados</h3>
                
                @if($cursosImpartidos->count() > 0)
                    <div class="tickets-list">
                        @foreach($cursosImpartidos as $idx => $curso)
                            @php
                                $inscritosCurso = $curso->funciones->sum(fn($f) => $f->tickets->where('estado', 'activo')->count());
                            @endphp
                            <div class="ticket-card active prof-course-card" style="border-left-color: var(--success); animation-delay: {{ 0.1 + ($idx * 0.08) }}s;">
                                <div class="ticket-header" style="flex-wrap: wrap; gap: 0.75rem;">
                                    <div style="display: flex; align-items: center; gap: 0.85rem;">
                                        <img src="{{ asset($curso->foto ?: 'images/carousel/edificio_nautica.jpg') }}" alt="{{ $curso->nombre }}" style="width: 52px; height: 52px; border-radius: 10px; object-fit: cover; border: 1px solid #cbd5e1;">
                                        <div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span class="badge badge-{{ $curso->tipo }}" style="margin-bottom: 0;">{{ $curso->tipoBadge() }}</span>
                                                <span style="font-size: 0.76rem; color: #64748b; background: #e2e8f0; padding: 0.12rem 0.5rem; border-radius: 6px; font-weight: 600;">{{ $curso->codigo }}</span>
                                            </div>
                                            <h4 style="margin: 0.2rem 0 0 0;">{{ $curso->nombre }}</h4>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                                        <span style="font-size: 0.82rem; font-weight: 600; color: #1e40af; background: #dbeafe; padding: 0.25rem 0.75rem; border-radius: 20px;">
                                            {{ $inscritosCurso }} {{ $inscritosCurso === 1 ? 'inscrito' : 'inscritos' }}
                                        </span>
                                        <span class="status-badge status-completado">Activo</span>
                                    </div>
                                </div>

                                <div class="ticket-body" style="flex-direction: column; align-items: flex-start;">
                                    <div class="ticket-info" style="width: 100%; min-width: 0;">
                                        @if($curso->descripcion)
                                            <div style="color: #475569; margin-bottom: 0.9rem; white-space: pre-line; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word; line-height: 1.6;">{{ $curso->descripcion }}</div>
                                        @endif

                                        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 1rem; background: #f8fafc; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                                            <p style="margin: 0;"><strong>Salón Asignado:</strong> {{ $curso->salon->codigo }} ({{ $curso->salon->nombre }})</p>
                                            <p style="margin: 0;"><strong>Periodo:</strong> {{ $curso->fecha_inicio->format('d/m/Y') }} al {{ $curso->fecha_fin->format('d/m/Y') }}</p>
                                            <p style="margin: 0;"><strong>Duración:</strong> {{ $curso->semanas_totales }} semanas</p>
                                        </div>
                                        
                                        <h5 style="margin-top: 0.5rem; margin-bottom: 0.6rem; color: #334155; font-size: 0.92rem;">Horarios y Cupos Disponibles:</h5>
                                        <ul style="list-style: none; padding-left: 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.85rem;">
                                            @foreach($curso->funciones as $funcion)
                                                <li style="background: #ffffff; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #cbd5e1; box-shadow: 0 2px 4px rgba(0,0,0,0.03);">
                                                    <strong style="color: #0f172a;">{{ $funcion->diaDisplay() }}</strong>
                                                    <div style="font-size: 0.85rem; color: #64748b; margin: 0.15rem 0 0.35rem 0;">{{ $funcion->horario() }}</div>
                                                    <span style="font-size: 0.85rem; font-weight: 600; color: {{ $funcion->cuposDisponibles() > 0 ? 'var(--success)' : 'var(--danger)' }}">
                                                        Cupos libres: {{ $funcion->cuposDisponibles() }} / {{ $funcion->cupo_maximo }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="ticket-footer" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                    <button class="btn btn-outline btn-sm" id="btn-alumnos-{{ $curso->id }}" onclick="toggleAlumnos({{ $curso->id }})">
                                        Ver Alumnos Inscritos ({{ $inscritosCurso }})
                                    </button>
                                    @if($curso->tipo === 'evento')
                                        <a href="{{ route('auditorio') }}" class="btn btn-sm" style="background: #fef3c7; color: #92400e; font-weight: 600; border: 1px solid #f59e0b;">
                                            Ver en Auditorio y Eventos
                                        </a>
                                    @else
                                        <a href="{{ route('cursos', ['profesor' => $usuario->nombreCompleto(), 'curso' => $curso->nombre]) }}" class="btn btn-sm" style="background: #eff6ff; color: #1d4ed8; font-weight: 600;">
                                            Ver en Catálogo Público
                                        </a>
                                    @endif
                                </div>

                                <!-- SECCIÓN DESPLEGABLE ANIMADA DE ALUMNOS -->
                                <div id="alumnos-curso-{{ $curso->id }}" class="prof-alumnos-drawer">
                                    <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0; background: #ffffff;">
                                        <h5 style="margin-bottom: 1rem; color: var(--primary-color); font-size: 1rem;">Lista de Alumnos Activos</h5>
                                        @php $hayAlumnos = false; @endphp
                                        
                                        @foreach($curso->funciones as $funcion)
                                            @if($funcion->tickets->where('estado', 'activo')->count() > 0)
                                                @php $hayAlumnos = true; @endphp
                                                <div style="margin-bottom: 1.5rem;">
                                                    <strong style="color: #334155; font-size: 0.92rem; border-bottom: 2px solid var(--secondary-color); padding-bottom: 0.25rem; display: inline-block; margin-bottom: 0.8rem;">
                                                        {{ $funcion->diaDisplay() }} ({{ $funcion->horario() }})
                                                    </strong>
                                                    <ul style="list-style: none; padding-left: 0; margin-top: 0.5rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 0.8rem;">
                                                        @foreach($funcion->tickets->where('estado', 'activo') as $ticket)
                                                            <li style="padding: 0.85rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                                                                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                                                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.82rem; flex-shrink: 0;">
                                                                        {{ strtoupper(substr($ticket->usuario->nombre, 0, 1) . substr($ticket->usuario->apellido, 0, 1)) }}
                                                                    </div>
                                                                    <div style="display: flex; flex-direction: column; min-width: 0;">
                                                                        <strong style="color: #0f172a; overflow-wrap: anywhere;">{{ $ticket->usuario->nombreCompleto() }}</strong> 
                                                                        <small style="color: #64748b; margin-top: 0.1rem; overflow-wrap: anywhere;">C.I: {{ $ticket->usuario->cedula }} | {{ $ticket->usuario->email }}</small>
                                                                    </div>
                                                                </div>
                                                                <span style="background-color: var(--primary-color); color: white; padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.78rem; font-weight: bold; white-space: nowrap;">
                                                                    Asiento {{ $ticket->butaca->etiqueta }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if(!$hayAlumnos)
                                            <p style="color: #64748b; font-size: 0.9rem; font-style: italic; margin: 0;">Aún no hay alumnos inscritos en los horarios de este curso.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>Aún no tienes cursos o talleres asignados en este periodo.</p>
                        <p class="text-muted" style="font-size: 0.9rem;">(Contacta al administrador para que asigne tus clases en el sistema)</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<style>
    @keyframes profFadeUp {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .prof-dashboard-header {
        animation: profFadeUp 0.45s ease both;
    }

    .prof-header-avatar-ring {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        padding: 3px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        flex-shrink: 0;
    }

    .prof-header-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
    }

    .prof-header-avatar-initials {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.4rem;
        border: 2px solid white;
    }

    .prof-mode-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #1d4ed8;
        background: #dbeafe;
        padding: 0.18rem 0.65rem;
        border-radius: 999px;
    }

    .prof-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .prof-stat-card {
        background: white;
        border-radius: 14px;
        padding: 1.25rem 1.4rem;
        border: 1px solid #e2e8f0;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 1rem;
        opacity: 0;
        animation: profFadeUp 0.5s ease forwards;
        transition: transform 0.25s ease;
    }

    .prof-stat-card:hover {
        transform: translateY(-4px);
    }

    .prof-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .prof-stat-number {
        display: block;
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .prof-stat-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
    }

    .prof-course-card {
        opacity: 0;
        animation: profFadeUp 0.5s ease forwards;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .prof-course-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }

    .prof-alumnos-drawer {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    }

    .prof-alumnos-drawer.open {
        max-height: 1200px;
        opacity: 1;
    }
</style>
<script>
    function toggleAlumnos(cursoId) {
        const divAlumnos = document.getElementById('alumnos-curso-' + cursoId);
        const btn = document.getElementById('btn-alumnos-' + cursoId);
        if (!divAlumnos) return;
        const isOpen = divAlumnos.classList.toggle('open');
        if (btn) {
            btn.textContent = isOpen
                ? btn.textContent.replace('Ver Alumnos', 'Ocultar Alumnos')
                : btn.textContent.replace('Ocultar Alumnos', 'Ver Alumnos');
        }
    }
</script>
@endpush

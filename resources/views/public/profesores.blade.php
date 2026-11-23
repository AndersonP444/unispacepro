@extends('layouts.public')

@section('title', 'Profesores')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-profesor');
        const clearBtn = document.getElementById('clear-search-profesor');
        const cards = document.querySelectorAll('.profesor-card');
        const emptyResult = document.getElementById('empty-search-profesores');

        // Animación escalonada de entrada para las tarjetas
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.08}s`;
        });

        function filterProfesores() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibles = 0;

            if (clearBtn) {
                clearBtn.style.display = searchTerm.length > 0 ? 'inline-flex' : 'none';
            }

            cards.forEach((card, idx) => {
                const searchData = card.getAttribute('data-search') || '';
                
                if (searchData.includes(searchTerm)) {
                    card.style.display = 'flex';
                    card.style.animation = 'none';
                    card.offsetHeight; /* reflow */
                    card.style.animation = `profesorCardEntrance 0.4s ease forwards ${(visibles * 0.05)}s`;
                    visibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyResult) {
                emptyResult.style.display = visibles === 0 ? 'block' : 'none';
            }
        }

        searchInput.addEventListener('input', filterProfesores);

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                cards.forEach(c => c.classList.remove('profesor-highlight'));
                filterProfesores();
                const url = new URL(window.location);
                url.searchParams.delete('profesor');
                window.history.replaceState({}, '', url);
            });
        }

        // Si viene redirigido desde Cursos y Talleres con ?profesor=...
        const params = new URLSearchParams(window.location.search);
        const profParam = params.get('profesor');
        if (profParam) {
            searchInput.value = profParam;
            filterProfesores();

            setTimeout(() => {
                cards.forEach(card => {
                    if (card.style.display !== 'none') {
                        card.classList.add('profesor-expanded', 'profesor-highlight');
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }, 250);
        }
    });

    function toggleProfesorCard(card, event) {
        // Evitar que clics en enlaces o botones cierren la tarjeta
        if (event.target.closest('a') || event.target.closest('.no-toggle')) {
            return;
        }
        card.classList.toggle('profesor-expanded');
    }
</script>
<style>
    @keyframes profesorCardEntrance {
        from {
            opacity: 0;
            transform: translateY(24px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes highlightPulseProf {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.55), 0 12px 28px rgba(15, 23, 42, 0.14); }
        70% { box-shadow: 0 0 0 14px rgba(59, 130, 246, 0), 0 12px 28px rgba(15, 23, 42, 0.14); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0), 0 12px 28px rgba(15, 23, 42, 0.14); }
    }

    .profesores-hero-stats {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 2.2rem;
    }

    .stat-pill {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 999px;
        padding: 0.55rem 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.9rem;
        color: #334155;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        animation: profesorCardEntrance 0.5s ease both;
    }

    .stat-pill strong {
        color: var(--primary-color);
        font-size: 1.05rem;
    }

    .filter-container {
        display: flex;
        gap: 0.8rem;
        margin-bottom: 2.5rem;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-wrapper {
        position: relative;
        flex: 1;
        min-width: 260px;
        max-width: 540px;
    }

    .search-wrapper svg {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .filter-input {
        width: 100%;
        padding: 0.85rem 1.2rem 0.85rem 2.8rem;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.98rem;
        outline: none;
        background: white;
        transition: border-color 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    }

    .filter-input:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    }

    .btn-clear-filter {
        display: none;
        align-items: center;
        gap: 0.4rem;
        padding: 0.8rem 1.1rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: white;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-clear-filter:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    /* Grid alineado al inicio para que al expandir una tarjeta no se deformen las demás */
    .profesores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 1.8rem;
        align-items: start;
    }

    .profesor-card {
        cursor: pointer;
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.3s ease;
        opacity: 0;
        animation: profesorCardEntrance 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .profesor-card::before {
        content: '';
        display: block;
        height: 6px;
        width: 100%;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        transition: height 0.3s ease;
    }

    .profesor-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }

    .profesor-card.profesor-expanded {
        border-color: var(--secondary-color);
        box-shadow: 0 18px 36px rgba(37, 99, 235, 0.14);
    }

    .profesor-card.profesor-highlight {
        animation: highlightPulseProf 2s infinite;
    }

    .profesor-card-inner {
        padding: 1.75rem 1.6rem 1.5rem 1.6rem;
        text-align: center;
        position: relative;
    }

    .expand-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #f1f5f9;
        border-radius: 50%;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), background-color 0.3s, color 0.3s;
        color: #475569;
    }

    .profesor-card:hover .expand-icon {
        background: #e0f2fe;
        color: var(--secondary-color);
    }

    .profesor-expanded .expand-icon {
        transform: rotate(180deg);
        background: var(--secondary-color);
        color: white;
    }

    .profesor-avatar-wrap {
        width: 92px;
        height: 92px;
        margin: 0 auto 1rem auto;
        border-radius: 50%;
        padding: 3px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .profesor-card:hover .profesor-avatar-wrap {
        transform: scale(1.07) rotate(2deg);
    }

    .profesor-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        display: block;
    }

    .profesor-avatar-initials {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        font-weight: 700;
        border: 3px solid white;
        letter-spacing: 1px;
    }

    .profesor-name {
        font-size: 1.25rem;
        color: #0f172a;
        margin-bottom: 0.25rem;
        font-weight: 700;
    }

    .profesor-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.28rem 0.85rem;
        border-radius: 999px;
        margin-bottom: 0.85rem;
    }

    .profesor-bio-preview {
        font-size: 0.88rem;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: break-word;
        max-width: 100%;
    }

    .profesor-expanded .profesor-bio-preview {
        display: none;
    }

    .profesor-quick-meta {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.4rem;
    }

    .meta-chip {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.28rem 0.7rem;
        border-radius: 999px;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.25s ease;
    }

    .meta-chip-cursos {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .toggle-hint {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
        font-weight: 500;
        transition: color 0.2s;
    }

    .profesor-card:hover .toggle-hint {
        color: var(--secondary-color);
    }

    .profesor-expanded .toggle-hint .hint-more {
        display: none;
    }

    .profesor-expanded .toggle-hint .hint-less {
        display: inline;
    }

    .toggle-hint .hint-less {
        display: none;
    }

    /* Panel desplegable animado */
    .profesor-extra-info {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        text-align: left;
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.35s ease,
                    margin-top 0.35s ease,
                    padding-top 0.35s ease;
        border-top: 1px solid transparent;
        margin-top: 0;
        padding-top: 0;
    }

    .profesor-expanded .profesor-extra-info {
        max-height: 900px;
        opacity: 1;
        margin-top: 1.2rem;
        padding-top: 1.2rem;
        border-top-color: #e2e8f0;
    }

    .extra-block {
        margin-bottom: 1rem;
        transform: translateY(10px);
        opacity: 0;
        transition: transform 0.35s ease, opacity 0.35s ease;
    }

    .profesor-expanded .extra-block {
        transform: translateY(0);
        opacity: 1;
    }

    .profesor-expanded .extra-block:nth-child(1) { transition-delay: 0.05s; }
    .profesor-expanded .extra-block:nth-child(2) { transition-delay: 0.12s; }
    .profesor-expanded .extra-block:nth-child(3) { transition-delay: 0.19s; }
    .profesor-expanded .extra-block:nth-child(4) { transition-delay: 0.26s; }

    .extra-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 0.35rem;
        display: block;
    }

    .extra-bio-full {
        font-size: 0.9rem;
        color: #334155;
        background: #f8fafc;
        padding: 0.75rem 0.9rem;
        border-radius: 10px;
        border-left: 3px solid var(--secondary-color);
        line-height: 1.55;
        white-space: pre-line;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: break-word;
        max-width: 100%;
    }

    .interest-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .interest-tag {
        font-size: 0.76rem;
        font-weight: 600;
        background: #eff6ff;
        color: #1e40af;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        border: 1px solid #bfdbfe;
        transition: transform 0.2s, background-color 0.2s;
    }

    .interest-tag:hover {
        transform: translateY(-2px);
        background: #dbeafe;
    }

    .contact-list {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        background: #f8fafc;
        padding: 0.75rem 0.9rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.86rem;
        color: #334155;
    }

    .contact-item a {
        color: var(--secondary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .contact-item a:hover {
        text-decoration: underline;
    }

    /* Tarjetas de cursos interactivas que conectan con Cursos y Talleres */
    .profesor-cursos-list {
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        margin-top: 0.4rem;
    }

    .profesor-curso-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        padding: 0.65rem 0.85rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        text-decoration: none;
        color: #0f172a;
        transition: all 0.25s ease;
    }

    .profesor-curso-link:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: var(--secondary-color);
        transform: translateX(4px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.1);
    }

    .profesor-curso-link-left {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .profesor-curso-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .profesor-curso-sub {
        font-size: 0.76rem;
        color: #64748b;
    }

    .profesor-curso-arrow {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--secondary-color);
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        transition: transform 0.25s ease;
    }

    .profesor-curso-link:hover .profesor-curso-arrow {
        transform: translateX(3px);
    }

    .btn-ir-cursos-prof {
        margin-top: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        background: var(--primary-color);
        color: white;
        font-weight: 600;
        font-size: 0.86rem;
        text-decoration: none;
        transition: background-color 0.25s, transform 0.2s;
    }

    .btn-ir-cursos-prof:hover {
        background: var(--secondary-color);
        color: white;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
@php
    $totalCursosActivos = $profesores->sum(fn($p) => $p->cursos ? $p->cursos->count() : 0);
@endphp
<section class="profesores-section" style="padding: 3.5rem 0 5rem 0; min-height: 70vh;">
    <div class="container">
        <h2 class="section-title">Nuestros Profesores</h2>
        <p class="section-subtitle" style="text-align: center; margin-bottom: 1.2rem;">
            Conoce al cuerpo docente de UniSpace y explora los cursos y talleres que imparten.
        </p>

        <!-- Estadísticas rápidas animadas -->
        <div class="profesores-hero-stats">
            <div class="stat-pill">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span><strong>{{ $profesores->count() }}</strong> Docentes Especialistas</span>
            </div>
            <div class="stat-pill">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                <span><strong>{{ $totalCursosActivos }}</strong> Cursos y Talleres Activos</span>
            </div>
            <div class="stat-pill">
                <span>Haz clic en una tarjeta para desplegar perfil y cursos</span>
            </div>
        </div>
        
        <div class="filter-container">
            <div class="search-wrapper">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="search-profesor" class="filter-input" placeholder="Buscar por profesor, especialidad, área de interés o curso...">
            </div>
            <button type="button" id="clear-search-profesor" class="btn-clear-filter">
                Mostrar todos
            </button>
        </div>

        <div class="profesores-grid">
            @forelse($profesores as $profesor)
                @php
                    $nombresCursos = $profesor->cursos ? $profesor->cursos->pluck('nombre')->implode(' ') : '';
                    $searchString = strtolower($profesor->nombreCompleto() . ' ' . ($profesor->gustos ?? '') . ' ' . ($profesor->biografia ?? '') . ' ' . $nombresCursos);
                    $gustosArray = $profesor->gustos ? array_filter(array_map('trim', explode(',', $profesor->gustos))) : [];
                    $cantidadCursos = $profesor->cursos ? $profesor->cursos->count() : 0;
                @endphp
                <article class="profesor-card" data-search="{{ $searchString }}" onclick="toggleProfesorCard(this, event)">
                    <div class="profesor-card-inner">
                        <div class="expand-icon" title="Ver detalles del profesor">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        
                        <div class="profesor-avatar-wrap">
                            @if($profesor->fotoPerfilUrl())
                                <img src="{{ $profesor->fotoPerfilUrl() }}" alt="Prof. {{ $profesor->nombreCompleto() }}" class="profesor-avatar-img">
                            @else
                                <div class="profesor-avatar-initials">
                                    {{ strtoupper(substr($profesor->nombre, 0, 1) . substr($profesor->apellido, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="profesor-name">Prof. {{ $profesor->nombreCompleto() }}</h3>
                        <span class="profesor-role-badge">
                            Docente Especialista UMC
                        </span>
                        
                        @if($profesor->biografia)
                            <p class="profesor-bio-preview">"{{ $profesor->biografia }}"</p>
                        @endif

                        <!-- Resumen de chips visibles siempre -->
                        <div class="profesor-quick-meta">
                            <span class="meta-chip meta-chip-cursos">
                                {{ $cantidadCursos }} {{ $cantidadCursos === 1 ? 'Curso / Taller' : 'Cursos / Talleres' }}
                            </span>
                            @if(count($gustosArray) > 0)
                                <span class="meta-chip">
                                    {{ $gustosArray[0] }}
                                </span>
                            @endif
                        </div>

                        <div class="toggle-hint">
                            <span class="hint-more">Ver ficha completa y cursos</span>
                            <span class="hint-less">Ocultar ficha</span>
                        </div>

                        <!-- Información Desplegable Animada -->
                        <div class="profesor-extra-info">
                            @if($profesor->biografia)
                                <div class="extra-block">
                                    <span class="extra-label">Perfil Académico</span>
                                    <div class="extra-bio-full">
                                        {{ $profesor->biografia }}
                                    </div>
                                </div>
                            @endif

                            @if(count($gustosArray) > 0)
                                <div class="extra-block">
                                    <span class="extra-label">Áreas de Interés y Especialidad</span>
                                    <div class="interest-tags">
                                        @foreach($gustosArray as $gusto)
                                            <span class="interest-tag">{{ $gusto }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="extra-block">
                                <span class="extra-label">Información de Contacto</span>
                                <div class="contact-list">
                                    @if($profesor->email)
                                        <div class="contact-item">
                                            <strong>Correo:</strong>
                                            <a href="mailto:{{ $profesor->email }}">{{ $profesor->email }}</a>
                                        </div>
                                    @endif
                                    @if($profesor->telefono)
                                        <div class="contact-item">
                                            <strong>Teléfono:</strong>
                                            <span>{{ $profesor->telefono }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="extra-block" style="margin-bottom: 0;">
                                <span class="extra-label">Cursos y Talleres que Imparte</span>
                                @if($cantidadCursos > 0)
                                    <div class="profesor-cursos-list">
                                        @foreach($profesor->cursos as $curso)
                                            <a href="{{ $curso->tipo === 'evento' ? route('auditorio') : route('cursos', ['profesor' => $profesor->nombreCompleto(), 'curso' => $curso->nombre]) }}" class="profesor-curso-link" title="Ir a reservar o ver detalles">
                                                <div class="profesor-curso-link-left">
                                                    <span class="profesor-curso-title">
                                                        <span class="badge badge-{{ $curso->tipo }}" style="margin-bottom: 0; font-size: 0.68rem; padding: 0.15rem 0.45rem;">{{ $curso->tipoBadge() }}</span>
                                                        {{ $curso->nombre }}
                                                    </span>
                                                    <span class="profesor-curso-sub">
                                                        Salón {{ $curso->salon->codigo ?? 'Asignado' }} | {{ $curso->semanas_totales }} semanas
                                                    </span>
                                                </div>
                                                <span class="profesor-curso-arrow">
                                                    {{ $curso->tipo === 'evento' ? 'Ver evento' : 'Ver curso' }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </div>

                                    <a href="{{ route('cursos', ['profesor' => $profesor->nombreCompleto()]) }}" class="btn-ir-cursos-prof">
                                        Explorar en Cursos y Talleres
                                    </a>
                                @else
                                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.4rem;">Miembro activo de la facultad de UniSpace.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p style="text-align: center; width: 100%; grid-column: 1 / -1;">No hay profesores registrados en el sistema.</p>
            @endforelse
        </div>

        <div id="empty-search-profesores" style="display: none; text-align: center; padding: 3rem 1rem; background: white; border-radius: 14px; box-shadow: var(--shadow); margin-top: 1rem;">
            <p style="font-size: 1.1rem; color: #475569; margin-bottom: 0.5rem;">No se encontraron profesores que coincidan con tu búsqueda.</p>
            <p style="font-size: 0.9rem; color: #94a3b8;">Intenta con otro nombre, especialidad o curso.</p>
        </div>
    </div>
</section>
@endsection

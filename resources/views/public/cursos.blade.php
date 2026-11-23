@extends('layouts.public')

@section('title', 'Cursos y Talleres')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-curso');
        const typeFilter = document.getElementById('filter-tipo');
        const clearBtn = document.getElementById('clear-filter-cursos');
        const activeFilterBanner = document.getElementById('active-profesor-banner');
        const activeFilterText = document.getElementById('active-profesor-text');
        const cards = document.querySelectorAll('.curso-card');
        const emptyState = document.getElementById('empty-cursos-search');

        // Animación escalonada inicial
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.08}s`;
        });

        function filterCursos() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const filterType = typeFilter.value;
            let visibles = 0;

            if (clearBtn) {
                clearBtn.style.display = (searchTerm.length > 0 || filterType !== 'todos') ? 'inline-flex' : 'none';
            }

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const tipoData = card.getAttribute('data-tipo') || '';
                
                const matchesSearch = searchData.includes(searchTerm);
                const matchesType = filterType === 'todos' || tipoData === filterType;

                if (matchesSearch && matchesType) {
                    card.style.display = 'flex';
                    card.style.animation = 'none';
                    card.offsetHeight; /* reflow */
                    card.style.animation = `cursoCardEntrance 0.4s ease forwards ${(visibles * 0.05)}s`;
                    visibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyState) {
                emptyState.style.display = visibles === 0 ? 'block' : 'none';
            }
        }

        searchInput.addEventListener('input', filterCursos);
        typeFilter.addEventListener('change', filterCursos);

        function resetCursoFilters() {
            searchInput.value = '';
            typeFilter.value = 'todos';
            if (activeFilterBanner) activeFilterBanner.style.display = 'none';
            cards.forEach(card => {
                card.classList.remove('curso-card-highlight');
                const profBox = card.querySelector('.curso-profesor-box');
                if (profBox) profBox.classList.remove('profesor-box-open');
            });
            filterCursos();
            const url = new URL(window.location);
            url.searchParams.delete('profesor');
            url.searchParams.delete('curso');
            window.history.replaceState({}, '', url);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', resetCursoFilters);
        }
        const bannerCloseBtn = document.getElementById('close-profesor-banner');
        if (bannerCloseBtn) {
            bannerCloseBtn.addEventListener('click', resetCursoFilters);
        }

        // Conectar navegación animada desde el apartado de Profesores (?profesor=... o ?curso=...)
        const params = new URLSearchParams(window.location.search);
        const profParam = params.get('profesor');
        const cursoParam = params.get('curso');

        if (profParam || cursoParam) {
            const queryToUse = cursoParam ? `${cursoParam} ${profParam || ''}`.trim() : profParam;
            searchInput.value = cursoParam || profParam;
            filterCursos();

            if (activeFilterBanner && activeFilterText) {
                activeFilterText.textContent = profParam && cursoParam
                    ? `Viendo "${cursoParam}" impartido por Prof. ${profParam}`
                    : (profParam ? `Mostrando cursos y talleres de Prof. ${profParam}` : `Mostrando resultados para "${cursoParam}"`);
                activeFilterBanner.style.display = 'flex';
            }

            setTimeout(() => {
                let firstMatch = null;
                cards.forEach(card => {
                    if (card.style.display !== 'none') {
                        if (!firstMatch) firstMatch = card;
                        card.classList.add('curso-card-highlight');
                        const profBox = card.querySelector('.curso-profesor-box');
                        if (profBox) {
                            profBox.classList.add('profesor-box-open');
                        }
                    }
                });
                if (firstMatch) {
                    firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 250);
        }
    });

    function toggleProfesorEnCurso(box, event) {
        if (event.target.closest('a')) {
            return;
        }
        box.classList.toggle('profesor-box-open');
    }
</script>
<style>
    @keyframes cursoCardEntrance {
        from {
            opacity: 0;
            transform: translateY(22px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes highlightPulseCurso {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5), 0 12px 26px rgba(15, 23, 42, 0.12); }
        70% { box-shadow: 0 0 0 14px rgba(59, 130, 246, 0), 0 12px 26px rgba(15, 23, 42, 0.12); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0), 0 12px 26px rgba(15, 23, 42, 0.12); }
    }

    .filter-container {
        display: flex;
        gap: 0.9rem;
        margin-bottom: 1.8rem;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .filter-input, .filter-select {
        padding: 0.85rem 1.2rem;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 0.98rem;
        outline: none;
        background-color: white;
        transition: border-color 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    }

    .filter-input:focus, .filter-select:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    }

    .filter-input {
        flex: 1;
        min-width: 260px;
        max-width: 440px;
    }

    .filter-select {
        min-width: 170px;
        cursor: pointer;
    }

    .btn-clear-cursos {
        display: none;
        align-items: center;
        gap: 0.4rem;
        padding: 0.85rem 1.15rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: white;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-clear-cursos:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .active-profesor-banner {
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        max-width: 720px;
        margin: 0 auto 2rem auto;
        padding: 0.85rem 1.25rem;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #93c5fd;
        border-radius: 12px;
        color: #1e3a8a;
        font-weight: 600;
        font-size: 0.92rem;
        animation: cursoCardEntrance 0.35s ease both;
    }

    .active-profesor-banner button {
        background: white;
        border: 1px solid #93c5fd;
        color: #1d4ed8;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .active-profesor-banner button:hover {
        background: #1d4ed8;
        color: white;
    }

    /* Grid alineado al inicio para que expandir los datos de un profesor no estire las demás tarjetas */
    .cursos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        gap: 2rem;
        align-items: start;
    }

    .curso-card {
        background: var(--white);
        border-radius: 16px;
        padding: 0;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        display: flex;
        flex-direction: column;
        height: auto;
        overflow: hidden;
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.3s ease;
        opacity: 0;
        animation: cursoCardEntrance 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .curso-card-cover {
        position: relative;
        height: 175px;
        width: 100%;
        overflow: hidden;
        background: #0f172a;
    }

    .curso-card-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .curso-card:hover .curso-card-cover img {
        transform: scale(1.06);
    }

    .curso-card-cover-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.82) 0%, rgba(15, 23, 42, 0.2) 60%, rgba(15, 23, 42, 0.35) 100%);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1rem 1.25rem;
    }

    .curso-card-content {
        padding: 1.35rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .curso-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.11);
        border-color: #cbd5e1;
    }

    .curso-card.curso-card-highlight {
        border-color: var(--secondary-color);
        animation: highlightPulseCurso 2s infinite;
    }

    /* Tarjeta interactiva y animada del Profesor dentro de cada Curso/Taller */
    .curso-profesor-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        position: relative;
    }

    .curso-profesor-box:hover {
        border-color: #93c5fd;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.08);
    }

    .curso-profesor-box.profesor-box-open {
        background: #ffffff;
        border-color: var(--secondary-color);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.1);
    }

    .curso-profesor-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .curso-profesor-main {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        min-width: 0;
    }

    .curso-profesor-avatar-ring {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        padding: 2px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        flex-shrink: 0;
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .curso-profesor-box:hover .curso-profesor-avatar-ring {
        transform: scale(1.08) rotate(3deg);
    }

    .curso-profesor-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        display: block;
    }

    .curso-profesor-avatar-initials {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        border: 2px solid white;
    }

    .curso-profesor-titles {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .curso-profesor-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
        line-height: 1.25;
    }

    .curso-profesor-hint {
        font-size: 0.76rem;
        color: var(--secondary-color);
        font-weight: 600;
        margin-top: 0.15rem;
    }

    .curso-profesor-chevron {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(15, 23, 42, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        flex-shrink: 0;
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), background-color 0.25s, color 0.25s;
    }

    .curso-profesor-box.profesor-box-open .curso-profesor-chevron {
        transform: rotate(180deg);
        background: var(--secondary-color);
        color: white;
    }

    /* Cuerpo desplegable animado con los datos del profesor en Cursos y Talleres */
    .curso-profesor-details {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1),
                    opacity 0.3s ease,
                    margin-top 0.3s ease,
                    padding-top 0.3s ease;
        border-top: 1px dashed transparent;
        margin-top: 0;
        padding-top: 0;
    }

    .curso-profesor-box.profesor-box-open .curso-profesor-details {
        max-height: 500px;
        opacity: 1;
        margin-top: 0.85rem;
        padding-top: 0.85rem;
        border-top-color: #cbd5e1;
    }

    .curso-profesor-bio {
        font-size: 0.84rem;
        color: #334155;
        line-height: 1.5;
        margin-bottom: 0.65rem;
        background: #f8fafc;
        padding: 0.55rem 0.75rem;
        border-radius: 8px;
        border-left: 3px solid var(--secondary-color);
        white-space: pre-line;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: break-word;
        max-width: 100%;
    }

    .curso-profesor-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 0.65rem;
    }

    .curso-profesor-tag {
        font-size: 0.72rem;
        font-weight: 600;
        background: #eff6ff;
        color: #1e40af;
        padding: 0.18rem 0.55rem;
        border-radius: 999px;
        border: 1px solid #bfdbfe;
    }

    .curso-profesor-contact {
        font-size: 0.8rem;
        color: #475569;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
    }

    .curso-profesor-contact a {
        color: var(--secondary-color);
        font-weight: 600;
        text-decoration: none;
    }

    .btn-ver-en-profesores {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        width: 100%;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        font-size: 0.8rem;
        text-decoration: none;
        border: 1px solid #bfdbfe;
        transition: all 0.2s ease;
    }

    .btn-ver-en-profesores:hover {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
        transform: translateX(2px);
    }
</style>
@endpush

@section('content')
<section class="catalogo-section" style="padding: 3.5rem 0 5rem 0;">
    <div class="container">
        <h2 class="section-title">Cursos y Talleres Disponibles</h2>
        <p class="section-subtitle" style="text-align: center; margin-bottom: 1.5rem;">
            Explora nuestra oferta académica, consulta la ficha interactiva de cada profesor y reserva tu butaca.
        </p>
        
        <div class="filter-container">
            <input type="text" id="search-curso" class="filter-input" placeholder="Buscar por curso, profesor, área o salón...">
            <select id="filter-tipo" class="filter-select">
                <option value="todos">Todos los Tipos</option>
                <option value="curso">Solo Cursos</option>
                <option value="taller">Solo Talleres</option>
            </select>
            <button type="button" id="clear-filter-cursos" class="btn-clear-cursos">
                Limpiar filtro
            </button>
        </div>

        <!-- Banner dinámico cuando se navega desde Profesores -->
        <div id="active-profesor-banner" class="active-profesor-banner">
            <span id="active-profesor-text"></span>
            <button type="button" id="close-profesor-banner">Ver todos los cursos</button>
        </div>

        <div class="cursos-grid">
            @forelse($cursos as $curso)
                @php
                    $prof = $curso->profesor;
                    $gustosProf = $prof && $prof->gustos ? array_filter(array_map('trim', explode(',', $prof->gustos))) : [];
                    $searchStr = strtolower(
                        $curso->nombre . ' ' .
                        ($prof ? $prof->nombreCompleto() . ' ' . ($prof->gustos ?? '') : '') . ' ' .
                        ($curso->salon->codigo ?? '') . ' ' .
                        ($curso->salon->nombre ?? '')
                    );
                @endphp
                <article class="curso-card" data-tipo="{{ $curso->tipo }}" data-search="{{ $searchStr }}">
                    <div class="curso-card-cover">
                        <img src="{{ asset($curso->foto ?: 'images/carousel/edificio_nautica.jpg') }}" alt="{{ $curso->nombre }}">
                        <div class="curso-card-cover-overlay">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 0.5rem;">
                                <span class="badge badge-{{ $curso->tipo }}" style="margin-bottom: 0; background: {{ $curso->tipo === 'curso' ? '#2563eb' : '#16a34a' }}; color: #ffffff;">{{ $curso->tipoBadge() }}</span>
                                <span style="font-size: 0.76rem; font-weight: 700; color: #f8fafc; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); padding: 0.22rem 0.6rem; border-radius: 6px;">
                                    {{ $curso->codigo }}
                                </span>
                            </div>
                            <h3 style="margin: 0; color: #ffffff; font-size: 1.3rem; text-shadow: 0 2px 6px rgba(0,0,0,0.4);">{{ $curso->nombre }}</h3>
                        </div>
                    </div>

                    <div class="curso-card-content">
                    <div class="curso-body" style="flex: 1; margin-bottom: 1.2rem;">
                        <p class="curso-desc">{{ $curso->descripcion }}</p>

                        <!-- Tarjeta Animada de Datos del Profesor -->
                        @if($prof)
                            <div class="curso-profesor-box" onclick="toggleProfesorEnCurso(this, event)" title="Haz clic para ver los datos del profesor">
                                <div class="curso-profesor-header">
                                    <div class="curso-profesor-main">
                                        <div class="curso-profesor-avatar-ring">
                                            <div class="curso-profesor-avatar-initials">
                                                {{ strtoupper(substr($prof->nombre, 0, 1) . substr($prof->apellido, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="curso-profesor-titles">
                                            <span class="curso-profesor-name">Prof. {{ $prof->nombreCompleto() }}</span>
                                            <span class="curso-profesor-hint">Ver perfil del docente</span>
                                        </div>
                                    </div>
                                    <div class="curso-profesor-chevron">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>

                                <div class="curso-profesor-details">
                                    @if($prof->biografia)
                                        <div class="curso-profesor-bio">
                                            "{{ $prof->biografia }}"
                                        </div>
                                    @endif

                                    @if(count($gustosProf) > 0)
                                        <div class="curso-profesor-tags">
                                            @foreach(array_slice($gustosProf, 0, 4) as $tag)
                                                <span class="curso-profesor-tag">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="curso-profesor-contact">
                                        @if($prof->email)
                                            <span><strong>Correo:</strong> <a href="mailto:{{ $prof->email }}">{{ $prof->email }}</a></span>
                                        @endif
                                        @if($prof->telefono)
                                            <span><strong>Teléfono:</strong> {{ $prof->telefono }}</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('profesores', ['profesor' => $prof->nombreCompleto()]) }}" class="btn-ver-en-profesores">
                                        Ver ficha completa en Profesores
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="curso-meta" style="margin-bottom: 0.6rem;">
                            <span>Salón: <a href="{{ route('espacios', ['buscar' => $curso->salon->codigo]) }}" style="color: var(--secondary-color); font-weight: 700; text-decoration: none;">{{ $curso->salon->codigo }}</a></span>
                            <span>Duración: <strong>{{ $curso->semanas_totales }} semanas</strong></span>
                        </div>

                        @if($curso->funciones && $curso->funciones->count() > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem;">
                                @foreach($curso->funciones as $funcion)
                                    @auth
                                        <a href="{{ route('reserva.mapa', $funcion->id) }}" style="font-size: 0.76rem; background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a; padding: 0.28rem 0.65rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--secondary-color)';this.style.backgroundColor='#eff6ff'" onmouseout="this.style.borderColor='#cbd5e1';this.style.backgroundColor='#f8fafc'" title="Reservar en este horario">
                                            <strong>{{ $funcion->diaDisplay() }}</strong> {{ $funcion->horario() }}
                                        </a>
                                    @else
                                        <span style="font-size: 0.76rem; background: #f8fafc; border: 1px solid #e2e8f0; color: #334155; padding: 0.25rem 0.6rem; border-radius: 6px;">
                                            <strong>{{ $funcion->diaDisplay() }}</strong> {{ $funcion->horario() }}
                                        </span>
                                    @endauth
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="curso-footer" style="margin-top: auto;">
                        @auth
                            <a href="{{ route('reserva.mapa', $curso->funciones->first()->id ?? 1) }}" class="btn btn-primary btn-block">Reservar Butaca</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline btn-block">Inicia sesión para reservar</a>
                        @endauth
                    </div>
                    </div>
                </article>
            @empty
                <p style="text-align: center; width: 100%; grid-column: 1 / -1;">No hay cursos o talleres activos en este momento.</p>
            @endforelse
        </div>

        <div id="empty-cursos-search" style="display: none; text-align: center; padding: 3rem 1rem; background: white; border-radius: 14px; box-shadow: var(--shadow); margin-top: 1rem;">
            <p style="font-size: 1.1rem; color: #475569; margin-bottom: 0.5rem;">No se encontraron cursos o talleres con ese criterio.</p>
            <p style="font-size: 0.9rem; color: #94a3b8;">Prueba limpiando el filtro o buscando otro término.</p>
        </div>
    </div>
</section>
@endsection

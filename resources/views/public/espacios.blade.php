@extends('layouts.public')

@section('title', 'Espacios')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-espacio');
        const typeFilter = document.getElementById('filter-tipo');
        const clearBtn = document.getElementById('clear-espacios-btn');
        const cards = document.querySelectorAll('.salon-card');
        const emptyState = document.getElementById('empty-espacios-state');

        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.06}s`;
        });

        function filterEspacios() {
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
                    card.style.display = 'block';
                    visibles++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (emptyState) {
                emptyState.style.display = visibles === 0 ? 'block' : 'none';
            }
        }

        searchInput.addEventListener('input', filterEspacios);
        typeFilter.addEventListener('change', filterEspacios);

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                typeFilter.value = 'todos';
                filterEspacios();
                const url = new URL(window.location);
                url.searchParams.delete('tipo');
                url.searchParams.delete('buscar');
                window.history.replaceState({}, '', url);
            });
        }

        // Soporte para filtros por URL (?tipo=aula|multimedia|laboratorio o ?buscar=...)
        const params = new URLSearchParams(window.location.search);
        const tipoParam = params.get('tipo');
        const buscarParam = params.get('buscar');

        if (tipoParam && ['aula', 'multimedia', 'laboratorio'].includes(tipoParam)) {
            typeFilter.value = tipoParam;
        }
        if (buscarParam) {
            searchInput.value = buscarParam;
        }
        if (tipoParam || buscarParam) {
            filterEspacios();
        }
    });

    function toggleSalonCard(card, event) {
        if (event.target.closest('a')) return;
        card.classList.toggle('salon-expanded');
    }
</script>
<style>
    @keyframes salonFadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-container {
        display: flex;
        gap: 0.9rem;
        margin-bottom: 2.2rem;
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
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .filter-input {
        flex: 1;
        min-width: 260px;
        max-width: 420px;
    }

    .filter-select {
        min-width: 170px;
        cursor: pointer;
    }

    .btn-clear-espacios {
        display: none;
        align-items: center;
        padding: 0.85rem 1.15rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: white;
        color: #475569;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-clear-espacios:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .salones-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 1.8rem;
        align-items: start;
    }

    .salon-card {
        cursor: pointer;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        opacity: 0;
        animation: salonFadeUp 0.45s ease forwards;
    }

    .salon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.11);
    }

    .salon-card.salon-expanded {
        border-color: var(--secondary-color);
    }

    .salon-img-placeholder {
        height: 175px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 2rem;
        font-weight: 800;
        position: relative;
        overflow: hidden;
        background: #0f172a;
    }

    .salon-cover-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        z-index: 1;
    }

    .salon-card:hover .salon-cover-img {
        transform: scale(1.07);
    }

    .salon-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.72) 0%, rgba(30, 58, 95, 0.58) 100%);
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .salon-card[data-tipo="laboratorio"] .salon-img-overlay {
        background: linear-gradient(135deg, rgba(15, 118, 110, 0.72) 0%, rgba(15, 23, 42, 0.65) 100%);
    }

    .salon-card[data-tipo="multimedia"] .salon-img-overlay {
        background: linear-gradient(135deg, rgba(49, 46, 129, 0.74) 0%, rgba(15, 23, 42, 0.65) 100%);
    }

    .salon-type-tag {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: rgba(255, 255, 255, 0.18);
        padding: 0.2rem 0.65rem;
        border-radius: 999px;
        margin-top: 0.35rem;
        font-weight: 600;
    }

    .salon-extra-info {
        max-height: 0;
        opacity: 0;
        transition: max-height 0.45s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, padding 0.3s ease, margin 0.3s ease;
        overflow: hidden;
        border-top: 1px solid transparent;
        margin-top: 0;
        padding-top: 0;
    }

    .salon-expanded .salon-extra-info {
        max-height: 550px;
        opacity: 1;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top-color: #e2e8f0;
    }

    .expand-icon {
        position: absolute;
        top: 0.9rem;
        right: 0.9rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.35s ease;
        color: #0f172a;
    }

    .salon-expanded .expand-icon {
        transform: rotate(180deg);
    }

    .salon-curso-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.55rem 0.75rem;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        text-decoration: none;
        color: #0f172a;
        font-size: 0.84rem;
        margin-top: 0.45rem;
        transition: all 0.2s ease;
    }

    .salon-curso-item:hover {
        background: #eff6ff;
        border-color: var(--secondary-color);
        transform: translateX(3px);
    }
</style>
@endpush

@section('content')
<section class="infraestructura-section" style="padding: 3.5rem 0 5rem 0; background-color: var(--bg-color);">
    <div class="container">
        <h2 class="section-title">Espacios Disponibles</h2>
        <p class="section-subtitle" style="margin-bottom: 1.8rem;">Haz clic en cualquier aula, sala multimedia o laboratorio para consultar su capacidad y los cursos que allí se imparten.</p>
        
        <div class="filter-container">
            <input type="text" id="search-espacio" class="filter-input" placeholder="Buscar por código, nombre, edificio o equipos...">
            <select id="filter-tipo" class="filter-select">
                <option value="todos">Todos los Espacios</option>
                <option value="aula">Aulas</option>
                <option value="multimedia">Salas Multimedia</option>
                <option value="laboratorio">Laboratorios</option>
            </select>
            <button type="button" id="clear-espacios-btn" class="btn-clear-espacios">
                Mostrar todos
            </button>
        </div>

        <div class="salones-grid">
            @forelse($salones as $salon)
                @php
                    $tipo = 'aula';
                    $tipoLabel = 'Aula Académica';
                    if(stripos($salon->nombre, 'multimedia') !== false) {
                        $tipo = 'multimedia';
                        $tipoLabel = 'Sala Multimedia';
                    } elseif(stripos($salon->nombre, 'laboratorio') !== false || stripos($salon->nombre, 'lab') !== false) {
                        $tipo = 'laboratorio';
                        $tipoLabel = 'Laboratorio';
                    }
                    $cursosNombres = $salon->cursos ? $salon->cursos->pluck('nombre')->implode(' ') : '';
                @endphp
                <article class="salon-card" data-tipo="{{ $tipo }}" data-search="{{ strtolower($salon->nombre . ' ' . $salon->codigo . ' ' . ($salon->equipos ?? '') . ' ' . ($salon->edificio->nombre ?? '') . ' ' . $cursosNombres) }}" onclick="toggleSalonCard(this, event)">
                    <div class="salon-img-placeholder">
                        <img src="{{ asset($salon->foto ?: 'images/carousel/edificio_nautica.jpg') }}" alt="{{ $salon->nombre }}" class="salon-cover-img">
                        <div class="salon-img-overlay">
                            <span style="text-shadow: 0 2px 8px rgba(0,0,0,0.45);">{{ $salon->codigo }}</span>
                            <span class="salon-type-tag">{{ $tipoLabel }}</span>
                        </div>
                        <div class="expand-icon" style="z-index: 3;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                    <div class="salon-details">
                        <h3>{{ $salon->nombre }}</h3>
                        <p class="salon-building">{{ $salon->edificio->nombre }}</p>
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.85rem;">
                            <div class="salon-status status-{{ $salon->estadoColor() }}" style="margin-bottom: 0;">
                                <span class="status-indicator"></span> 
                                @if($salon->estado === 'mantenimiento')
                                    Cerrado / Mantenimiento
                                @elseif($salon->estado === 'en_uso')
                                    En Uso
                                @else
                                    Disponible
                                @endif
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 600; color: #475569;">
                                Capacidad: {{ $salon->capacidad }} butacas
                            </span>
                        </div>
                        
                        <div class="salon-equipment">
                            <strong>Equipamiento:</strong> {{ $salon->equipamiento ?? $salon->equipos ?? 'Mesas y Pizarras' }}
                        </div>

                        <!-- Información Oculta que se expande -->
                        <div class="salon-extra-info">
                            <p style="font-size: 0.88rem; margin-bottom: 0.35rem;"><strong>Distribución de asientos:</strong> {{ $salon->filas }} filas x {{ $salon->columnas }} columnas ({{ $salon->capacidad }} puestos)</p>
                            
                            @if($salon->cursos && $salon->cursos->count() > 0)
                                <div style="margin-top: 0.8rem;">
                                    <strong style="font-size: 0.82rem; color: #475569; text-transform: uppercase; letter-spacing: 0.04em;">Actividades en este espacio:</strong>
                                    @foreach($salon->cursos as $c)
                                        <a href="{{ $c->tipo === 'evento' ? route('auditorio') : route('cursos', ['curso' => $c->nombre]) }}" class="salon-curso-item">
                                            <div>
                                                <strong style="display: block;">{{ $c->nombre }} ({{ $c->tipoBadge() }})</strong>
                                                @if($c->profesor)
                                                    <span style="font-size: 0.76rem; color: #64748b;">Prof. {{ $c->profesor->nombreCompleto() }}</span>
                                                @endif
                                            </div>
                                            <span style="color: var(--secondary-color); font-weight: 700; font-size: 0.78rem;">Ver</span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p style="font-size: 0.84rem; color: #64748b; margin-top: 0.5rem;">Espacio acondicionado para actividades académicas e institucionales de la UMC.</p>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p style="text-align: center; width: 100%; grid-column: 1 / -1;">No hay salones registrados en el sistema.</p>
            @endforelse
        </div>

        <div id="empty-espacios-state" style="display: none; text-align: center; padding: 3rem 1rem; background: white; border-radius: 14px; box-shadow: var(--shadow); margin-top: 1rem;">
            <p style="font-size: 1.05rem; color: #475569; margin-bottom: 0.3rem;">No se encontraron espacios con ese criterio de búsqueda.</p>
        </div>
    </div>
</section>
@endsection

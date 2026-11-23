/**
 * ========================================================================
 * UnispacePro Global JavaScript
 * Creado por Oscar y Greymel (Frontend)
 * ========================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('UnispacePro: Interfaz inicializada correctamente.');

    // 1. Smooth Scrolling para los enlaces de navegación
    const navLinks = document.querySelectorAll('.main-nav a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Solo hacer scroll suave si estamos en la página principal y el enlace tiene '#'
            if(href && href.includes('#') && (window.location.pathname === '/' || window.location.pathname === '/index.php')) {
                e.preventDefault();
                const targetId = href.split('#')[1];
                if(!targetId) return;

                const targetElement = document.getElementById(targetId);
                if(targetElement) {
                    // Offset para compensar el header sticky
                    const headerHeight = document.querySelector('.main-header').offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerHeight;
      
                    window.scrollTo({
                         top: offsetPosition,
                         behavior: "smooth"
                    });
                } else {
                    // Si el elemento no existe en esta página, navega normalmente
                    window.location.href = href;
                }
            }
        });
    });

    // 2. Interacciones del Mapa (Oscar)
    const hotspots = document.querySelectorAll('.hotspot');
    
    hotspots.forEach(hotspot => {
        hotspot.addEventListener('click', () => {
            const info = hotspot.querySelector('.hotspot-info h4');
            if(info) console.log(`Clic en el ${info.innerText}`);
        });
    });

    // 3. Tarjetas de Salones (Animaciones extra o clics)
    const salonCards = document.querySelectorAll('.salon-card');
    salonCards.forEach(card => {
        card.addEventListener('click', () => {
            card.style.transform = 'scale(0.98)';
            setTimeout(() => {
                card.style.transform = '';
            }, 150);
        });
    });

    // 4. Módulo de Reserva de Butacas (Oscar)
    const butacasDisponibles = document.querySelectorAll('.butaca-disponible');
    const inputButacaId = document.getElementById('butaca_id');
    const labelAsiento = document.getElementById('asiento-label');
    const btnReservar = document.getElementById('btn-reservar');

    if(butacasDisponibles.length > 0) {
        butacasDisponibles.forEach(butaca => {
            butaca.addEventListener('click', function() {
                // Si la butaca está ocupada no hacer nada
                if(this.classList.contains('butaca-ocupada')) return;

                // Desmarcar todas las demás butacas disponibles
                butacasDisponibles.forEach(b => {
                    b.classList.remove('butaca-seleccionada');
                });

                // Marcar la butaca clickeada
                this.classList.add('butaca-seleccionada');

                // Obtener datos
                const butacaId = this.getAttribute('data-id');
                const etiqueta = this.getAttribute('data-etiqueta');

                // Actualizar formulario oculto y UI
                if(inputButacaId) inputButacaId.value = butacaId;
                if(labelAsiento) labelAsiento.innerText = etiqueta;
                
                // Habilitar botón de enviar
                if(btnReservar) btnReservar.removeAttribute('disabled');
            });
        });
    }
});

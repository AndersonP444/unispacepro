<!-- Chatbot Asistente de UniSpacePro -->
<div id="unispace-chatbot" class="usp-chatbot">
    <button type="button" id="usp-chat-toggle" class="usp-chat-toggle" aria-label="Abrir asistente de UniSpacePro" title="Asistente UniSpacePro">
        <svg id="usp-chat-icon-open" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <svg id="usp-chat-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>

    <div id="usp-chat-window" class="usp-chat-window" style="display: none;">
        <div class="usp-chat-header">
            <div>
                <strong>Asistente UniSpacePro</strong>
                <small>Ayuda rápida sobre la página</small>
            </div>
            <button type="button" id="usp-chat-close" class="usp-chat-close" aria-label="Cerrar chat">&times;</button>
        </div>

        <div id="usp-chat-messages" class="usp-chat-messages"></div>

        <div id="usp-chat-suggestions" class="usp-chat-suggestions"></div>

        <form id="usp-chat-form" class="usp-chat-form">
            <input type="text" id="usp-chat-input" maxlength="300" autocomplete="off" placeholder="Pregunta sobre cursos, reservas, espacios...">
            <button type="submit" aria-label="Enviar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    .usp-chatbot {
        position: fixed;
        right: 22px;
        bottom: 22px;
        z-index: 9999;
        font-family: 'Inter', sans-serif;
    }
    .usp-chat-toggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #1e3a5f, #2563eb);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(30, 58, 95, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .usp-chat-toggle:hover {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 14px 30px rgba(30, 58, 95, 0.42);
    }
    .usp-chat-window {
        position: absolute;
        right: 0;
        bottom: 70px;
        width: 345px;
        max-width: calc(100vw - 32px);
        height: 465px;
        max-height: calc(100vh - 110px);
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 16px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.22);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .usp-chat-header {
        background: linear-gradient(135deg, #0f172a, #1e3a5f);
        color: #ffffff;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .usp-chat-header strong {
        display: block;
        font-size: 0.95rem;
    }
    .usp-chat-header small {
        display: block;
        font-size: 0.74rem;
        color: #cbd5e1;
    }
    .usp-chat-close {
        background: none;
        border: none;
        color: #ffffff;
        font-size: 1.5rem;
        line-height: 1;
        cursor: pointer;
        padding: 0 0.25rem;
    }
    .usp-chat-messages {
        flex: 1;
        padding: 0.9rem;
        overflow-y: auto;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }
    .usp-msg {
        max-width: 86%;
        padding: 0.6rem 0.85rem;
        border-radius: 12px;
        font-size: 0.84rem;
        line-height: 1.45;
        white-space: pre-line;
        word-wrap: break-word;
    }
    .usp-msg-bot {
        align-self: flex-start;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
    }
    .usp-msg-user {
        align-self: flex-end;
        background: #2563eb;
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }
    .usp-chat-suggestions {
        padding: 0.45rem 0.75rem;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }
    .usp-chip {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        padding: 0.25rem 0.6rem;
        font-size: 0.73rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .usp-chip:hover {
        background: #dbeafe;
    }
    .usp-chat-form {
        display: flex;
        gap: 0.4rem;
        padding: 0.65rem 0.75rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
    }
    .usp-chat-form input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.84rem;
        outline: none;
    }
    .usp-chat-form input:focus {
        border-color: #2563eb;
    }
    .usp-chat-form button {
        background: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 0 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .usp-chat-form button:hover {
        background: #1d4ed8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('usp-chat-toggle');
    const closeBtn = document.getElementById('usp-chat-close');
    const win = document.getElementById('usp-chat-window');
    const iconOpen = document.getElementById('usp-chat-icon-open');
    const iconClose = document.getElementById('usp-chat-icon-close');
    const messagesBox = document.getElementById('usp-chat-messages');
    const suggestionsBox = document.getElementById('usp-chat-suggestions');
    const form = document.getElementById('usp-chat-form');
    const input = document.getElementById('usp-chat-input');

    const defaultSuggestions = [
        '¿Cómo reservo un cupo?',
        'Cursos disponibles',
        'Eventos del Auditorio',
        '¿Cómo subir foto de perfil?'
    ];

    function setOpen(open) {
        win.style.display = open ? 'flex' : 'none';
        iconOpen.style.display = open ? 'none' : 'block';
        iconClose.style.display = open ? 'block' : 'none';
        if (open) {
            input.focus();
            messagesBox.scrollTop = messagesBox.scrollHeight;
        }
    }

    function appendMessage(text, who) {
        const div = document.createElement('div');
        div.className = 'usp-msg ' + (who === 'user' ? 'usp-msg-user' : 'usp-msg-bot');
        div.textContent = text;
        messagesBox.appendChild(div);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    function renderSuggestions(list) {
        suggestionsBox.innerHTML = '';
        (list || []).forEach(function (q) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'usp-chip';
            btn.textContent = q;
            btn.addEventListener('click', function () {
                enviarMensaje(q);
            });
            suggestionsBox.appendChild(btn);
        });
    }

    function saveHistory() {
        try {
            const items = Array.from(messagesBox.querySelectorAll('.usp-msg')).slice(-16).map(function (el) {
                return {
                    who: el.classList.contains('usp-msg-user') ? 'user' : 'bot',
                    text: el.textContent
                };
            });
            sessionStorage.setItem('usp_chat_history', JSON.stringify(items));
        } catch (e) {}
    }

    function loadHistory() {
        try {
            const raw = sessionStorage.getItem('usp_chat_history');
            if (raw) {
                const items = JSON.parse(raw);
                if (Array.isArray(items) && items.length > 0) {
                    items.forEach(function (m) { appendMessage(m.text, m.who); });
                    renderSuggestions(defaultSuggestions);
                    return;
                }
            }
        } catch (e) {}
        appendMessage('¡Hola! Soy el asistente de UniSpacePro. Puedo responder preguntas fáciles sobre cursos, talleres, Auditorio, espacios, profesores, reservas y tu perfil.', 'bot');
        renderSuggestions(defaultSuggestions);
    }

    async function enviarMensaje(texto) {
        const limpio = (texto || '').trim();
        if (!limpio) return;

        appendMessage(limpio, 'user');
        saveHistory();
        input.value = '';

        try {
            const res = await fetch('{{ route("chatbot.responder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ mensaje: limpio })
            });
            const data = await res.json();
            appendMessage(data.respuesta || 'No pude procesar tu consulta sobre UniSpacePro.', 'bot');
            renderSuggestions(data.sugerencias || defaultSuggestions);
            saveHistory();
        } catch (err) {
            appendMessage('Hubo un problema de conexión. Intenta nuevamente.', 'bot');
        }
    }

    toggleBtn.addEventListener('click', function () {
        setOpen(win.style.display === 'none');
    });
    closeBtn.addEventListener('click', function () {
        setOpen(false);
    });
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        enviarMensaje(input.value);
    });

    loadHistory();
});
</script>


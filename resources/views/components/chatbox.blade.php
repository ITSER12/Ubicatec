{{-- =============================================
     CHATBOX UBICATEC v2 — FAQ + Contacto Admin
     Conversación persistente, respuestas humanas
     Pegar ANTES de </body> en layouts/app.blade.php
     ============================================= --}}

{{-- ---- Botón flotante ---- --}}
<button id="chat-toggle" aria-label="Abrir asistente UbicaTEC" title="¿Necesitas ayuda?">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <span id="chat-badge" class="chat-badge hidden">1</span>
</button>

{{-- ---- Panel del chat ---- --}}
<div id="chat-panel" class="chat-panel" role="dialog" aria-modal="true" aria-label="Asistente UbicaTEC" hidden>

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-header-info">
            <div class="chat-avatar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 12c-5.33 0-8 2.67-8 4v2h16v-2c0-1.33-2.67-4-8-4z"/></svg>
            </div>
            <div>
                <p class="chat-bot-name">Asistente UbicaTEC</p>
                <p class="chat-bot-status"><span class="status-dot"></span> En línea</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px">
            <button id="chat-clear" class="chat-icon-btn" title="Limpiar conversación" aria-label="Limpiar conversación">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </button>
            <button id="chat-close" aria-label="Cerrar chat" class="chat-close-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="chat-tabs" role="tablist">
        <button role="tab" aria-selected="true"  id="tab-faq"     class="chat-tab active"  data-tab="faq">💬 Guía</button>
        <button role="tab" aria-selected="false" id="tab-contact" class="chat-tab"         data-tab="contact">📧 Contactar admin</button>
    </div>

    {{-- ===== TAB: FAQ ===== --}}
    <div id="tab-panel-faq" class="tab-panel" role="tabpanel" aria-labelledby="tab-faq">
        <div id="chat-messages" class="chat-messages" aria-live="polite" aria-relevant="additions"></div>

        <div class="chat-suggestions" id="chat-suggestions">
            <p class="suggestions-label">Temas frecuentes:</p>
            <div class="suggestions-grid">
                <button class="suggestion-chip" data-key="reservar">📅 Reservar aula</button>
                <button class="suggestion-chip" data-key="eventos">🎉 Ver eventos</button>
                <button class="suggestion-chip" data-key="solicitar">📝 Solicitar evento</button>
                <button class="suggestion-chip" data-key="perfil">👤 Mi perfil</button>
                <button class="suggestion-chip" data-key="horarios">🕐 Horarios</button>
                <button class="suggestion-chip" data-key="roles">🔑 Roles del sistema</button>
            </div>
        </div>

        <form class="chat-input-row" id="chat-form" autocomplete="off">
            <input type="text" id="chat-input" placeholder="Escribe tu pregunta…" maxlength="200" aria-label="Escribe tu pregunta"/>
            <button type="submit" aria-label="Enviar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            </button>
        </form>
    </div>

    {{-- ===== TAB: CONTACTO ===== --}}
    <div id="tab-panel-contact" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-contact">
        <div class="contact-body">
            <p class="contact-intro">¿Tu duda no está en la guía? Envía un mensaje directo al administrador y te contactará pronto.</p>

            <div id="contact-success" class="contact-success hidden">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <p>¡Listo! Mensaje enviado. El admin te contactará en breve.</p>
            </div>

            <form id="contact-form" class="contact-form" method="POST" action="{{ route('chat.soporte') }}">
                @csrf
                <input type="hidden" name="usuario_nombre"
                    value="{{ session('usuario') ? (is_array(session('usuario')) ? session('usuario')['nombre'] ?? '' : session('usuario')->nombre ?? '') : 'Invitado' }}">
                <input type="hidden" name="usuario_email"
                    value="{{ session('usuario') ? (is_array(session('usuario')) ? session('usuario')['correo'] ?? '' : session('usuario')->correo ?? '') : '' }}">

                <div class="form-group">
                    <label for="contact-asunto">Asunto</label>
                    <select id="contact-asunto" name="asunto" required>
                        <option value="" disabled selected>Selecciona un tema…</option>
                        <option>Problema al reservar un aula</option>
                        <option>Error en un evento</option>
                        <option>Solicitud rechazada sin motivo</option>
                        <option>No puedo iniciar sesión</option>
                        <option>Cambio de rol / permisos</option>
                        <option>Reporte de bug</option>
                        <option>Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contact-mensaje">Describe tu problema</label>
                    <textarea id="contact-mensaje" name="mensaje" rows="4"
                        placeholder="Explica con detalle qué pasó y en qué parte del sistema…" required maxlength="800"></textarea>
                    <span class="char-count" id="char-count">0 / 800</span>
                </div>

                <button type="submit" class="btn-enviar" id="btn-enviar">
                    <span id="btn-text">Enviar al administrador</span>
                    <span id="btn-loading" class="hidden">Enviando…</span>
                </button>
            </form>
        </div>
    </div>

</div>

{{-- ===================== ESTILOS ===================== --}}
<style>
:root {
    --chat-primary:   #01696f;
    --chat-primary-h: #0c4e54;
    --chat-bg:        #ffffff;
    --chat-surface:   #f7f8fa;
    --chat-border:    #e2e5ea;
    --chat-text:      #1a1d23;
    --chat-muted:     #6b7280;
    --chat-bot-bg:    #f0f4f3;
    --chat-user-bg:   #01696f;
    --chat-user-text: #ffffff;
    --chat-radius:    1rem;
    --chat-shadow:    0 8px 32px rgba(0,0,0,.15), 0 2px 8px rgba(0,0,0,.08);
    --chat-w:         360px;
    --chat-h:         530px;
}
#chat-toggle {
    position: fixed; bottom: 24px; right: 24px;
    width: 56px; height: 56px; border-radius: 50%;
    background: var(--chat-primary); color: #fff;
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 16px rgba(1,105,111,.45);
    transition: transform .2s, box-shadow .2s, background .2s;
    z-index: 9000;
}
#chat-toggle svg { width: 24px; height: 24px; }
#chat-toggle:hover { background: var(--chat-primary-h); transform: scale(1.08); box-shadow: 0 6px 24px rgba(1,105,111,.55); }
#chat-toggle:active { transform: scale(.96); }
.chat-badge {
    position: absolute; top: 4px; right: 4px;
    background: #e53e3e; color: #fff;
    font-size: 10px; font-weight: 700;
    width: 18px; height: 18px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}
.chat-badge.hidden { display: none; }
@keyframes chatBounceIn {
    0%   { opacity:0; transform:scale(.5) translateY(20px); }
    70%  { transform:scale(1.08) translateY(-4px); }
    100% { opacity:1; transform:scale(1) translateY(0); }
}
#chat-toggle { animation: chatBounceIn .5s cubic-bezier(.16,1,.3,1) both; }

.chat-panel {
    position: fixed; bottom: 90px; right: 24px;
    width: var(--chat-w); max-height: var(--chat-h);
    background: var(--chat-bg); border-radius: var(--chat-radius);
    box-shadow: var(--chat-shadow);
    display: flex; flex-direction: column;
    z-index: 8999; overflow: hidden;
    border: 1px solid var(--chat-border);
    transform-origin: bottom right;
}
@keyframes chatPanelIn  { from { opacity:0; transform:scale(.88) translateY(12px); } to { opacity:1; transform:scale(1) translateY(0); } }
@keyframes chatPanelOut { from { opacity:1; transform:scale(1) translateY(0); } to { opacity:0; transform:scale(.88) translateY(12px); } }
.chat-panel.opening { animation: chatPanelIn  .25s cubic-bezier(.16,1,.3,1) forwards; }
.chat-panel.closing { animation: chatPanelOut .2s  cubic-bezier(.4,0,1,1)   forwards; }

.chat-header {
    background: linear-gradient(135deg, var(--chat-primary) 0%, #0c4e54 100%);
    color: #fff; padding: 14px 16px;
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
}
.chat-header-info { display: flex; align-items: center; gap: 10px; }
.chat-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.chat-avatar svg { width: 22px; height: 22px; }
.chat-bot-name { font-size: .875rem; font-weight: 700; margin: 0; }
.chat-bot-status { font-size: .72rem; margin: 2px 0 0; opacity: .85; display: flex; align-items: center; gap: 5px; }
.status-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; display: inline-block; animation: pulse 2s ease-in-out infinite; }
@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }
.chat-icon-btn {
    background: none; border: none; color: rgba(255,255,255,.7); cursor: pointer;
    padding: 4px; border-radius: 6px; transition: background .15s, color .15s;
    display: flex; align-items: center; justify-content: center;
}
.chat-icon-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
.chat-icon-btn svg { width: 15px; height: 15px; }
.chat-close-btn { background: none; border: none; color: rgba(255,255,255,.7); cursor: pointer; padding: 4px; border-radius: 6px; transition: background .15s; display:flex;align-items:center;justify-content:center; }
.chat-close-btn:hover { background: rgba(255,255,255,.15); color: #fff; }
.chat-close-btn svg { width: 18px; height: 18px; }

.chat-tabs { display: flex; border-bottom: 1px solid var(--chat-border); flex-shrink: 0; background: var(--chat-bg); }
.chat-tab { flex: 1; padding: 10px 8px; font-size: .78rem; font-weight: 600; color: var(--chat-muted); background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; transition: color .15s, border-color .15s; }
.chat-tab.active { color: var(--chat-primary); border-bottom-color: var(--chat-primary); }
.chat-tab:hover:not(.active) { color: var(--chat-text); background: var(--chat-surface); }

.tab-panel { display: flex; flex-direction: column; flex: 1; overflow: hidden; min-height: 0; }
.tab-panel.hidden { display: none; }

.chat-messages {
    flex: 1; overflow-y: auto; padding: 14px 14px 6px;
    display: flex; flex-direction: column; gap: 10px;
    scroll-behavior: smooth; min-height: 0;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: var(--chat-border); border-radius: 4px; }

.chat-msg { display: flex; flex-direction: column; max-width: 85%; animation: msgIn .2s ease-out; }
@keyframes msgIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
.chat-msg.bot  { align-self: flex-start; align-items: flex-start; }
.chat-msg.user { align-self: flex-end;   align-items: flex-end; }
.msg-bubble { padding: 9px 13px; border-radius: 16px; font-size: .83rem; line-height: 1.55; }
.chat-msg.bot  .msg-bubble { background: var(--chat-bot-bg); color: var(--chat-text); border-bottom-left-radius: 4px; }
.chat-msg.user .msg-bubble { background: var(--chat-user-bg); color: var(--chat-user-text); border-bottom-right-radius: 4px; }
.msg-time { font-size: .68rem; color: var(--chat-muted); margin-top: 3px; padding: 0 4px; }

/* Botón inline dentro de mensaje */
.msg-action-btn {
    margin-top: 8px; padding: 6px 14px; border-radius: 20px;
    border: 1.5px solid var(--chat-primary); background: transparent;
    color: var(--chat-primary); font-size: .75rem; font-weight: 600;
    cursor: pointer; transition: background .15s, color .15s;
    display: inline-flex; align-items: center; gap: 5px;
}
.msg-action-btn:hover { background: var(--chat-primary); color: #fff; }

.typing-bubble { display: flex; gap: 4px; align-items: center; padding: 10px 14px !important; }
.typing-bubble span { width: 6px; height: 6px; border-radius: 50%; background: var(--chat-muted); animation: typingDot 1.2s ease-in-out infinite; }
.typing-bubble span:nth-child(2) { animation-delay: .2s; }
.typing-bubble span:nth-child(3) { animation-delay: .4s; }
@keyframes typingDot { 0%,60%,100% { transform:translateY(0); } 30% { transform:translateY(-4px); } }

.chat-suggestions { padding: 6px 14px 8px; flex-shrink: 0; }
.suggestions-label { font-size: .7rem; color: var(--chat-muted); margin-bottom: 6px; font-weight: 500; text-transform: uppercase; letter-spacing: .04em; }
.suggestions-grid { display: flex; flex-wrap: wrap; gap: 6px; }
.suggestion-chip {
    padding: 5px 11px; border-radius: 20px;
    border: 1px solid var(--chat-border);
    background: var(--chat-bg); color: var(--chat-text);
    font-size: .75rem; font-weight: 500; cursor: pointer;
    transition: background .15s, border-color .15s, color .15s; white-space: nowrap;
}
.suggestion-chip:hover { background: var(--chat-primary); border-color: var(--chat-primary); color: #fff; }

.chat-input-row { display: flex; gap: 8px; padding: 10px 12px; border-top: 1px solid var(--chat-border); flex-shrink: 0; }
.chat-input-row input {
    flex: 1; padding: 8px 12px; border-radius: 20px;
    border: 1px solid var(--chat-border); font-size: .82rem;
    background: var(--chat-surface); color: var(--chat-text);
    outline: none; transition: border-color .15s;
}
.chat-input-row input:focus { border-color: var(--chat-primary); background: #fff; }
.chat-input-row button {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--chat-primary); color: #fff; border: none;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background .15s, transform .1s; flex-shrink: 0;
}
.chat-input-row button:hover { background: var(--chat-primary-h); }
.chat-input-row button:active { transform: scale(.92); }
.chat-input-row button svg { width: 16px; height: 16px; }

.contact-body { padding: 16px; overflow-y: auto; flex: 1; }
.contact-intro { font-size: .82rem; color: var(--chat-muted); margin-bottom: 14px; line-height: 1.5; }
.contact-form { display: flex; flex-direction: column; gap: 12px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: .78rem; font-weight: 600; color: var(--chat-text); }
.form-group select, .form-group textarea {
    padding: 8px 11px; border-radius: 8px;
    border: 1px solid var(--chat-border); font-size: .82rem;
    background: var(--chat-surface); color: var(--chat-text);
    outline: none; transition: border-color .15s; resize: none; font-family: inherit;
}
.form-group select:focus, .form-group textarea:focus { border-color: var(--chat-primary); background: #fff; }
.char-count { font-size: .68rem; color: var(--chat-muted); align-self: flex-end; }
.btn-enviar {
    padding: 10px; border-radius: 8px;
    background: var(--chat-primary); color: #fff;
    border: none; font-size: .84rem; font-weight: 600;
    cursor: pointer; transition: background .15s;
}
.btn-enviar:hover { background: var(--chat-primary-h); }
.btn-enviar:disabled { opacity: .6; cursor: not-allowed; }
.contact-success {
    background: #f0faf0; border: 1px solid #86efac;
    border-radius: 8px; padding: 14px; display: flex;
    align-items: flex-start; gap: 10px; margin-bottom: 12px;
    color: #166534; font-size: .83rem;
}
.contact-success svg { width: 20px; height: 20px; flex-shrink: 0; color: #16a34a; margin-top: 1px; }
.contact-success.hidden { display: none; }
.hidden { display: none !important; }

@media (max-width: 480px) {
    :root { --chat-w: calc(100vw - 20px); --chat-h: 480px; }
    .chat-panel { right: 10px; bottom: 80px; }
    #chat-toggle { bottom: 16px; right: 16px; }
}
</style>

{{-- ===================== JAVASCRIPT ===================== --}}
<script>
(function () {

    /* ════════════════════════════════════
       BASE DE CONOCIMIENTO — respuestas
       con variaciones para sonar humano
    ════════════════════════════════════ */
    const FAQ = {
        reservar: {
            preguntas: ['¿Cómo reservo un aula?', '¿Cómo aparto un salón?', 'quiero reservar'],
            respuestas: [
                `¡Claro! Para apartar un aula sigue estos pasos:<br><br>
                1️⃣ Ve al <strong>mapa del edificio</strong> (A o B) desde el menú.<br>
                2️⃣ Haz clic sobre el aula que te interesa.<br>
                3️⃣ Si está <span style="color:#16a34a">🟢 libre</span>, verás el botón <strong>"Apartar aula"</strong>.<br>
                4️⃣ Elige tu slot de 1 hora (máx. 2 slots si eres docente).<br>
                5️⃣ Escribe el motivo y confirma.<br><br>
                ⚠️ Los estudiantes no pueden hacer reservaciones directas.`,

                `Para reservar un aula es bastante sencillo 😊<br><br>
                Entra al <strong>mapa del edificio</strong>, da clic en el aula que quieras y si está libre aparecerá la opción de apartarla.<br><br>
                Como docente puedes reservar hasta <strong>2 horas</strong> (2 slots de 1h). Si la ves ocupada pero hay horarios libres, te aparecerá la opción de <em>"Apartar en otro horario"</em>.`
            ]
        },
        eventos: {
            preguntas: ['¿Cómo veo los eventos?', 'ver eventos', 'cartelera'],
            respuestas: [
                `Los eventos están en la sección <strong>Eventos</strong> del menú principal. Ahí verás la cartelera completa del campus.<br><br>
                Puedes:<br>
                • ❤️ Dar like a los que te interesen<br>
                • ⭐ Marcarlos como favoritos<br>
                • Filtrar por fecha para buscar uno específico`,

                `¡Fácil! Ve a <strong>Eventos</strong> en el menú y aparece toda la cartelera. Puedes darle like, guardar favoritos y filtrar por fecha 📅`
            ]
        },
        solicitar: {
            preguntas: ['¿Cómo solicito un evento?', 'crear evento', 'postular evento'],
            respuestas: [
                `Si eres <strong>docente o estudiante</strong>, no puedes crear eventos directamente pero sí puedes proponerlos:<br><br>
                1. Ve a <strong>Eventos</strong> → botón <em>"Postular evento"</em><br>
                2. Llena el formulario (título, descripción, fecha, lugar)<br>
                3. Tu solicitud queda como <strong>⏳ Pendiente</strong><br>
                4. El administrador la revisa y te notifica<br><br>
                Los admins y jefes de departamento sí pueden crear eventos directamente.`,

                `Depende de tu rol 😊<br><br>
                • <strong>Admin / Jefe dpto:</strong> creas eventos directo desde el menú.<br>
                • <strong>Docente / Estudiante:</strong> usas el botón <em>"Postular evento"</em> y el admin lo revisa antes de publicarse.`
            ]
        },
        perfil: {
            preguntas: ['¿Cómo edito mi perfil?', 'cambiar foto', 'mi cuenta'],
            respuestas: [
                `Para editar tu perfil haz clic en tu nombre o avatar (esquina superior) → <strong>Mi perfil</strong>.<br><br>
                Puedes cambiar:<br>
                • 📷 Foto de perfil<br>
                • 📝 Descripción personal<br>
                • 🔒 Contraseña<br><br>
                El rol solo puede cambiarlo el administrador desde el panel de usuarios.`,

                `Da clic en tu avatar arriba → <strong>Mi perfil</strong>. Desde ahí puedes actualizar tu foto, descripción y contraseña. Si necesitas cambiar tu rol, tendrás que pedírselo al administrador 🙂`
            ]
        },
        horarios: {
            preguntas: ['horarios', '¿cómo consulto los horarios?', 'aula libre', 'ocupada'],
            respuestas: [
                `Los horarios se ven en el <strong>mapa de cada edificio</strong>. Los colores te dicen el estado:<br><br>
                🟢 <strong>Verde</strong> = Libre ahora<br>
                🔴 <strong>Rojo</strong> = Ocupada (clase en curso)<br>
                🟡 <strong>Amarillo</strong> = Apartada por alguien<br><br>
                Usa los botones de semana para ver días futuros.`,

                `En el mapa del edificio cada aula tiene un color: verde = libre, rojo = ocupada, amarillo = apartada. También puedes navegar por semana para ver disponibilidad futura 📅`
            ]
        },
        roles: {
            preguntas: ['roles', 'permisos', '¿qué puede hacer cada rol?'],
            respuestas: [
                `UbicaTEC tiene 4 roles con distintos permisos:<br><br>
                🔴 <strong>Administrador</strong> — acceso total: gestiona usuarios, crea eventos, aprueba solicitudes.<br>
                🟠 <strong>Jefe de Departamento</strong> — igual que admin en su área.<br>
                🔵 <strong>Docente</strong> — reserva aulas (máx. 2h) y solicita eventos.<br>
                ⚪ <strong>Estudiante</strong> — visualiza eventos y puede solicitar, pero sin reservas de aulas.`,

                `Hay 4 roles 😊<br>
                Admin y Jefe de Dpto tienen acceso completo. Los docentes pueden reservar aulas hasta 2 horas y proponer eventos. Los estudiantes pueden ver todo y solicitar eventos, pero no reservar aulas directamente.`
            ]
        },
        saludo: {
            preguntas: ['hola', 'buenas', 'hey', 'qué tal', 'buen día'],
            respuestas: [
                `¡Hola! 👋 ¿En qué te puedo ayudar hoy con UbicaTEC?`,
                `¡Hola! Bienvenido/a 😊 ¿Tienes alguna duda sobre el sistema?`,
                `¡Buenas! Estoy aquí para ayudarte. ¿Qué necesitas saber?`
            ]
        },
        gracias: {
            preguntas: ['gracias', 'muchas gracias', 'ok gracias', 'perfecto gracias'],
            respuestas: [
                `¡Con gusto! 😊 Si tienes más dudas aquí estoy.`,
                `¡De nada! Cualquier otra cosa me dices 👍`,
                `¡Para eso estoy! ¿Hay algo más en lo que te pueda ayudar?`
            ]
        }
    };

    const KEYWORDS = [
        { key: 'saludo',    words: ['hola','buenas','hey','buen dia','buenos dias','buenas tardes'] },
        { key: 'gracias',   words: ['gracias','de nada','perfecto','excelente'] },
        { key: 'reservar',  words: ['reserv','apartar','aula','salon','sala','edificio','espacio'] },
        { key: 'eventos',   words: ['event','actividad','cartel','publicad','cartelera'] },
        { key: 'solicitar', words: ['solicit','postul','crear event','proponer','pedir event','nuevo evento'] },
        { key: 'perfil',    words: ['perfil','foto','contrasena','password','descripcion','cuenta','avatar'] },
        { key: 'horarios',  words: ['horario','ocupad','libre','dispon','schedule','verde','rojo','amarillo','semana'] },
        { key: 'roles',     words: ['rol','permiso','admin','docente','estudiante','jefe','acceso'] },
    ];

    /* ════ Estado global persistente ════ */
    const STATE = {
        messages: [],   // [{role, html, time}]
        isOpen: false,
        badgeShown: false,
        currentTab: 'faq'
    };

    /* ════ DOM refs ════ */
    const toggle      = document.getElementById('chat-toggle');
    const panel       = document.getElementById('chat-panel');
    const closeBtn    = document.getElementById('chat-close');
    const clearBtn    = document.getElementById('chat-clear');
    const messagesEl  = document.getElementById('chat-messages');
    const inputEl     = document.getElementById('chat-input');
    const formEl      = document.getElementById('chat-form');
    const tabs        = document.querySelectorAll('.chat-tab');
    const chips       = document.querySelectorAll('.suggestion-chip');
    const textarea    = document.getElementById('contact-mensaje');
    const charCount   = document.getElementById('char-count');
    const contactForm = document.getElementById('contact-form');
    const badge       = document.getElementById('chat-badge');

    /* ════ Utilidades ════ */
    function now() {
        return new Date().toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
    }

    function scrollBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function pick(arr) {
        return arr[Math.floor(Math.random() * arr.length)];
    }

    /* ════ Renderizar todos los mensajes guardados ════ */
    function renderMessages() {
        messagesEl.innerHTML = '';
        if (STATE.messages.length === 0) {
            appendBotMsg(`👋 ¡Hola! Soy el asistente de <strong>UbicaTEC</strong>.<br>Selecciona un tema o escríbeme tu duda directamente 😊`, 'Ahora', false);
        } else {
            STATE.messages.forEach(m => {
                const div = document.createElement('div');
                div.className = `chat-msg ${m.role}`;
                div.innerHTML = `<div class="msg-bubble">${m.html}</div><span class="msg-time">${m.time}</span>`;
                messagesEl.appendChild(div);
            });
        }
        scrollBottom();
    }

    /* ════ Agregar mensaje al DOM + estado ════ */
    function appendMsg(html, role, time, save = true) {
        const t = time || now();
        if (save) STATE.messages.push({ role, html, time: t });
        const div = document.createElement('div');
        div.className = `chat-msg ${role}`;
        div.innerHTML = `<div class="msg-bubble">${html}</div><span class="msg-time">${t}</span>`;
        messagesEl.appendChild(div);
        scrollBottom();
        return div;
    }

    function appendBotMsg(html, time, save = true) {
        return appendMsg(html, 'bot', time, save);
    }

    function appendUserMsg(html) {
        return appendMsg(html, 'user', now(), true);
    }

    /* ════ Typing indicator ════ */
    function showTyping() {
        const el = document.createElement('div');
        el.className = 'chat-msg bot';
        el.id = 'typing-indicator';
        el.innerHTML = `<div class="msg-bubble typing-bubble"><span></span><span></span><span></span></div>`;
        messagesEl.appendChild(el);
        scrollBottom();
    }
    function removeTyping() {
        const el = document.getElementById('typing-indicator');
        if (el) el.remove();
    }

    /* ════ Responder ════ */
    function respondFAQ(key) {
        const item = FAQ[key];
        setTimeout(() => {
            removeTyping();
            if (item) {
                const resp = pick(item.respuestas);
                appendBotMsg(resp);
            } else {
                // Respuesta de fallback con botón para ir a contacto
                const fallback = `Hmm, no tengo información sobre eso por aquí 🤔<br><br>
                    Puedo ayudarte con: reservas de aulas, eventos, horarios, perfil y roles.<br><br>
                    Si tu duda es más específica, puedes contactar directamente al administrador:`;
                appendBotMsg(fallback);
                // Agregar botón acción
                setTimeout(() => {
                    const lastMsg = messagesEl.querySelector('.chat-msg.bot:last-child .msg-bubble');
                    if (lastMsg) {
                        const btn = document.createElement('button');
                        btn.className = 'msg-action-btn';
                        btn.innerHTML = '📧 Escribir al admin';
                        btn.onclick = () => switchTab('contact');
                        lastMsg.appendChild(document.createElement('br'));
                        lastMsg.appendChild(btn);
                    }
                }, 50);
            }
        }, pick([700, 850, 950]));
    }

    function detectKey(text) {
        const t = text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        for (const entry of KEYWORDS) {
            if (entry.words.some(w => t.includes(w))) return entry.key;
        }
        return null;
    }

    function sendMessage(text) {
        if (!text.trim()) return;
        appendUserMsg(text);
        inputEl.value = '';
        showTyping();
        const key = detectKey(text);
        respondFAQ(key);
    }

    /* ════ Tabs ════ */
    function switchTab(name) {
        tabs.forEach(t => {
            const isTarget = t.dataset.tab === name;
            t.classList.toggle('active', isTarget);
            t.setAttribute('aria-selected', isTarget);
        });
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById(`tab-panel-${name}`).classList.remove('hidden');
        STATE.currentTab = name;
    }

    tabs.forEach(tab => tab.addEventListener('click', () => switchTab(tab.dataset.tab)));

    /* ════ Chips ════ */
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            const key = chip.dataset.key;
            const item = FAQ[key];
            const pregunta = item ? pick(item.preguntas) : chip.textContent.replace(/^.{2}/, '').trim();
            appendUserMsg(pregunta);
            showTyping();
            respondFAQ(key);
        });
    });

    /* ════ Form FAQ ════ */
    formEl.addEventListener('submit', e => {
        e.preventDefault();
        sendMessage(inputEl.value);
    });

    /* ════ Abrir / cerrar ════ */
    function openPanel() {
        STATE.isOpen = true;
        panel.hidden = false;
        panel.classList.remove('closing');
        panel.classList.add('opening');
        renderMessages();
        // Restaurar tab activa
        switchTab(STATE.currentTab);
        setTimeout(() => inputEl.focus(), 250);
        badge.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'true');
    }

    function closePanel() {
        STATE.isOpen = false;
        panel.classList.remove('opening');
        panel.classList.add('closing');
        toggle.setAttribute('aria-expanded', 'false');
        setTimeout(() => { panel.hidden = true; panel.classList.remove('closing'); }, 200);
    }

    toggle.addEventListener('click', () => STATE.isOpen ? closePanel() : openPanel());
    closeBtn.addEventListener('click', closePanel);
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && STATE.isOpen) closePanel(); });

    /* ════ Limpiar conversación ════ */
    clearBtn.addEventListener('click', () => {
        if (!confirm('¿Borrar la conversación?')) return;
        STATE.messages = [];
        renderMessages();
    });

    /* ════ Char counter contacto ════ */
    if (textarea) {
        textarea.addEventListener('input', () => {
            charCount.textContent = `${textarea.value.length} / 800`;
        });
    }

    /* ════ Form Contacto AJAX ════ */
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn     = document.getElementById('btn-enviar');
            const btnText = document.getElementById('btn-text');
            const btnLoad = document.getElementById('btn-loading');
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoad.classList.remove('hidden');
            try {
                const res  = await fetch(contactForm.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(contactForm)
                });
                const json = await res.json();
                if (json.success) {
                    contactForm.reset();
                    charCount.textContent = '0 / 800';
                    document.getElementById('contact-success').classList.remove('hidden');
                    setTimeout(() => document.getElementById('contact-success').classList.add('hidden'), 6000);
                    // Volver a FAQ con mensaje de confirmación
                    setTimeout(() => {
                        switchTab('faq');
                        appendBotMsg(`✅ Tu mensaje fue enviado al administrador. Te contactarán pronto por correo 📬`);
                    }, 1500);
                }
            } catch (err) {
                alert('Hubo un error al enviar. Intenta de nuevo.');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoad.classList.add('hidden');
            }
        });
    }

    /* ════ Badge tras 5s si está cerrado ════ */
    setTimeout(() => {
        if (!STATE.isOpen && !STATE.badgeShown) {
            STATE.badgeShown = true;
            badge.textContent = '1';
            badge.classList.remove('hidden');
        }
    }, 5000);

})();
</script>
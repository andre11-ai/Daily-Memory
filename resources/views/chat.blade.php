<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat | Daily Memory</title>
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('CSS/chat.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

    <script>
        window.ChatConfig = {
            authId: {{ auth()->id() }},
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</head>
<body>
<header class="bg_animate">
<nav class="navbar">
    <h1 class="logo">Daily Memory</h1>
    <div class="nav__list">
        <a href="{{ url('/perfil') }}" class="nav-link"><i class="fas fa-user"></i> Perfil</a>
        <a href="{{ url('/menu') }}" class="nav-link"><i class="fas fa-home"></i> Menú</a>
    </div>
</nav>
<main>
    <div class="chat-main-container">
        <div class="chat-tabs-horizontal">
            <button class="chat-tab-btn" id="tab-btn-mundial" data-tab="mundial">
                <i class="fas fa-globe"></i> <span>Mundial</span>
            </button>
            <button class="chat-tab-btn" id="tab-btn-grupos" data-tab="grupos">
                <i class="fas fa-users"></i> <span>Grupos</span>
            </button>
            <button class="chat-tab-btn" id="tab-btn-personal" data-tab="personal">
                <i class="fas fa-user-friends"></i> <span>Personal</span>
            </button>
        </div>
        <div class="chat-card">
            <section class="chat-panel" id="panel-mundial">
                <div class="chat-title"><i class="fas fa-globe"></i> Chat Mundial</div>
                <div class="chat-messages" id="messages-mundial"></div>
                <div class="chat-input-row">
                    <button type="button" class="chat-emoji-btn" id="emoji-btn-mundial">😊</button>
                    <input type="text" class="chat-input" id="mundial-chat-input" placeholder="Escribe tu mensaje...">
                    <button class="chat-send-btn" id="mundial-chat-send"><i class="fas fa-paper-plane"></i></button>
                    <div class="emoji-picker-popup" id="emoji-picker-mundial-popup"></div>
                </div>
            </section>
            <section class="chat-panel" id="panel-grupos">
                <div class="chat-title"><i class="fas fa-users"></i> Chat de Grupos</div>
                <div class="group-bar">
                    <div class="group-bar-row">
                        <select id="group-select"></select>
                        <button class="group-btn group-add" id="open-create-group-modal"><i class="fas fa-plus"></i> Crear grupo</button>
                        <button class="group-btn group-manage" id="open-manage-group-modal"><i class="fas fa-cog"></i> Gestionar grupo</button>
                    </div>
                </div>
                <div class="modal-backdrop" id="create-group-modal" style="display:none;">
                    <div class="modal-content">
                        <span class="modal-close" onclick="document.getElementById('create-group-modal').style.display='none'">&times;</span>
                        <h3 class="modal-title"><i class="fas fa-plus"></i> Crear nuevo grupo</h3>
                        <form id="create-group-form-modal" class="modal-form">
                            <input type="text" id="new-group-name-modal" class="modal-input" placeholder="Nombre del grupo" autocomplete="off" required>
                            <button type="submit" class="group-btn group-add modal-btn">
                                <i class="fas fa-plus"></i> Crear
                            </button>
                        </form>
                    </div>
                </div>
                <div class="modal-backdrop" id="manage-group-modal" style="display:none;">
                    <div class="modal-content">
                        <span class="modal-close" onclick="document.getElementById('manage-group-modal').style.display='none'">&times;</span>
                        <h3 class="modal-title"><i class="fas fa-cog"></i> Gestionar grupo</h3>
                        <form id="invite-user-form-modal" class="modal-form">
                            <div style="position:relative;">
                                <input type="text" id="invite-username-modal" class="modal-input" placeholder="Buscar usuario para invitar..." autocomplete="off">
                                <div class="invite-autocomplete" id="invite-autocomplete" style="display:none;"></div>
                            </div>
                            <button type="submit" class="group-btn group-invite modal-btn">
                                <i class="fas fa-user-plus"></i> Invitar
                            </button>
                        </form>
                        <div class="group-members-list-modal" id="group-members-list-modal"></div>
                        <div class="manage-actions">
                            <button type="button" id="leave-group-btn-modal" class="group-btn group-leave modal-btn">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </button>
                            <button type="button" id="delete-group-btn-modal" class="group-btn group-delete modal-btn">
                                <i class="fas fa-trash"></i> Eliminar grupo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="chat-messages" id="messages-grupos"></div>
                <div class="chat-input-row">
                    <button type="button" class="chat-emoji-btn" id="emoji-btn-grupos">😊</button>
                    <input type="text" class="chat-input" id="group-chat-input" placeholder="Escribe tu mensaje...">
                    <button class="chat-send-btn" id="group-chat-send"><i class="fas fa-paper-plane"></i></button>
                    <div class="group-emoji-picker-popup" id="emoji-picker-grupos-popup"></div>
                </div>
            </section>
            <section class="chat-panel" id="panel-personal">
                <div class="chat-title"><i class="fas a-user-friends"></i> Chat Personal</div>
                <div class="personal-chats-list-row" id="personal-chats-list-row"></div>
                <div class="last-chats-list" id="last-chats-list"></div>
                <div class="user-search-row">
                    <input type="text" id="user-search-input" placeholder="Buscar usuario por nombre...">
                    <button id="user-search-btn" class="chat-send-btn" style="padding: .6rem 1.1rem;"><i class="fas fa-search"></i></button>
                </div>
                <div class="user-list-dropdown" id="user-list-dropdown" style="display:none;"></div>
                <div class="personal-chat-header" id="personal-chat-header" style="margin-bottom:1rem;"></div>
                <div class="chat-messages" id="messages-personal"></div>
                <div class="chat-input-row">
                    <button type="button" class="chat-emoji-btn" id="emoji-btn-personal">😊</button>
                    <input type="text" class="chat-input" id="personal-chat-input" placeholder="Escribe tu mensaje...">
                    <button class="chat-send-btn" id="personal-chat-send"><i class="fas fa-paper-plane"></i></button>
                    <div class="personal-emoji-picker-popup" id="emoji-picker-personal-popup"></div>
                </div>
            </section>
        </div>
    </div>
</main>
<div class="burbujas">
    @for($i = 0; $i < 10; $i++)
        <div class="burbuja"></div>
    @endfor
</div>
</header>

<button id="help-btn" class="help-btn hidden" aria-label="Ayuda sobre el chat">
    <i class="fa-solid fa-question"></i>
</button>

<div id="help-modal" class="intro-overlay">
    <div class="intro-scene">
        <div class="mascot-container">
            <img src="{{ asset('img/default-user.png') }}" alt="Mascota" class="mascot-img" />
        </div>
        <div class="speech-bubble">
            <div class="intro-header">
                <div class="intro-eyebrow">CHAT GLOBAL</div>
                <h2 class="intro-title">¿Cómo funciona?</h2>
            </div>
            <div class="intro-content">
                <p>Comunícate con otros usuarios en tiempo real.</p>
                <ul>
                    <li><strong>Mundial:</strong> Habla con todos los conectados.</li>
                    <li><strong>Grupos:</strong> Crea salas privadas para amigos.</li>
                    <li><strong>Personal:</strong> Envía mensajes directos a un usuario.</li>
                </ul>
            </div>
            <div class="intro-footer">
                <button id="help-close" class="start-btn">Entendido</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('help-modal');
    const helpBtn = document.getElementById('help-btn');
    const closeBtn = document.getElementById('help-close');

    setTimeout(() => modal.classList.add('active'), 500);

    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
        helpBtn.classList.remove('hidden');
    });

    helpBtn.addEventListener('click', () => {
        modal.classList.add('active');
        helpBtn.classList.add('hidden');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
            helpBtn.classList.remove('hidden');
        }
    });
});
</script>

<script src="{{ asset('JS/chat.js') }}"></script>
</body>
</html>

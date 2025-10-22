<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat | Daily Memory</title>
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <style>
        /* --- BURBUJAS Y MENSAJES --- */
        .chat-message-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 13px;
        }
        .chat-message-bubble {
            padding: .7rem 1rem;
            border-radius: 1.2rem;
            background: var(--accent-color);
            color: var(--white);
            max-width: 70%;
            word-break: break-word;
            box-shadow: 0 2px 12px rgba(0,0,0,0.10);
            position: relative;
            animation: fadeIn .5s;
        }
        .chat-message-bubble.mine {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            margin-left: auto;
            color: var(--white);
        }
        .chat-message-meta {
            font-size: .82em;
            color: #e0e0e0;
            margin-bottom: 2px;
        }
        .chat-message-name {
            font-weight: 600;
            color: var(--white);
            margin-right: 8px;
        }
        .chat-message-time {
            font-size: .8em;
            color: #f4f4f4;
        }
        .chat-message-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 7px;
            font-size: 1.1rem;
        }
        .mine-row .chat-message-avatar {
            margin-left: 7px;
            margin-right: 0;
            background: var(--accent-color);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .chat-main-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 2.5rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }
        .chat-tabs-horizontal {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        @media (min-width: 700px) {
            .chat-main-container{
                flex-wrap: nowrap;
            }
            .chat-tabs-horizontal {
                flex-direction: column;
                gap: 1rem;
                min-width: 135px;
                margin-top: .6rem;
            }
        }
        @media (max-width: 699px) {
            .chat-main-container {
                flex-direction: column;
                align-items: center;
            }
            .chat-tabs-horizontal {
                flex-direction: row;
                gap: .7rem;
                min-width: unset;
                margin-bottom: 1.3rem;
                margin-top: 0;
            }
        }
        .chat-tab-btn {
            background: var(--white);
            color: var(--black);
            border: 1.5px solid var(--accent-color);
            padding: .35rem 1.1rem;
            border-radius: 1.2rem;
            font-size: 1rem;
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
            min-width: 110px;
            justify-content: flex-start;
        }
        .chat-tab-btn.active, .chat-tab-btn:hover {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
        .chat-card {
            background: var(--white);
            border-radius: var(--radius-large);
            box-shadow: var(--shadow);
            width: 1050px;
            max-width: 98vw;
            padding: 1.35rem 1.25rem 1.7rem 1.25rem;
            margin: 0 auto;
            min-height: 430px;
            display: flex;
            flex-direction: column;
        }
        .chat-panel {
            display: none;
            flex-direction: column;
        }
        .chat-panel.active {
            display: flex;
        }
        .chat-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: .8rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .chat-messages {
            height: 350px;
            min-height: 260px;
            max-height: 350px;
            overflow-y: auto;
            background: var(--background-light);
            border-radius: var(--radius-small);
            padding: 1.2rem;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        .chat-input-row {
            display: flex;
            gap: .7rem;
            align-items: center;
            position: relative;
        }
        .chat-input {
            flex: 1;
            border-radius: var(--radius-small);
            border: 1.5px solid var(--accent-color);
            padding: .6rem .85rem;
            font-size: 1rem;
        }
        .chat-send-btn {
            background: var(--accent-color);
            color: var(--white);
            border: none;
            border-radius: var(--radius-small);
            padding: .6rem 1.1rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background .17s;
            display: flex;
            align-items: center;
        }
        .chat-emoji-btn {
            background: #fff;
            border: 1.5px solid var(--accent-color);
            border-radius: .7rem;
            font-size: 1.2rem;
            padding: .6rem .8rem;
            margin-right: .6rem;
            cursor: pointer;
            transition: background .18s, border-color .18s;
        }
        .chat-emoji-btn:hover {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }
        .emoji-picker-popup {
            display: none;
            position: absolute;
            z-index: 1010;
            top: -370px;
            left: 0;
        }
        .group-emoji-picker-popup, .personal-emoji-picker-popup {
            display: none;
            position: absolute;
            z-index: 1010;
            top: -370px;
            left: 0;
        }
        .group-bar {
            margin-bottom: 1.1rem;
            display: flex;
            flex-direction: column;
            gap: .7rem;
        }
        .group-bar-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .group-btn {
            border: none;
            border-radius: .7rem;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            padding: .6rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .3rem;
            transition: background .18s, color .18s;
        }
        .group-add {
            background: var(--accent-color);
            color: var(--white);
        }
        .group-add:hover {
            background: var(--primary-color);
        }
        .group-manage {
            background: var(--primary-color);
            color: var(--white);
        }
        .group-manage:hover {
            background: var(--accent-color);
        }
        .group-invite {
            background: var(--primary-color);
            color: var(--white);
        }
        .group-leave {
            background: #f44336;
            color: #fff;
        }
        .group-leave:hover {
            background: #d32f2f;
        }
        .group-delete {
            background: #333;
            color: #fff;
        }
        .group-delete:hover {
            background: #222;
        }
        #group-select {
            width: 240px;
            padding: .5rem .7rem;
            border-radius: .7rem;
            border: 1.5px solid var(--accent-color);
            font-size: 1rem;
            background: var(--white);
        }
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.18);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: var(--white);
            border-radius: 1.2rem;
            padding: 2.2rem 2.4rem 1.8rem 2.4rem;
            box-shadow: 0 0 30px rgba(0,0,0,0.13);
            min-width: 320px;
            max-width: 98vw;
            position: relative;
            text-align: center;
        }
        .modal-close {
            position: absolute;
            top: .9rem;
            right: 1.2rem;
            font-size: 1.7rem;
            color: var(--accent-color);
            cursor: pointer;
            font-weight: bold;
        }
        .modal-title {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.2rem;
            color: var(--primary-color);
            letter-spacing: .02em;
        }
        .modal-form {
            margin-bottom: 1.1rem;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }
        .modal-input {
            padding: .8rem 1.1rem;
            border-radius: .7rem;
            border: 2px solid var(--accent-color);
            font-size: 1.05rem;
            margin-bottom: .5rem;
            width: 100%;
            outline: none;
            transition: border-color .18s;
        }
        .modal-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0,191,165,0.13);
        }
        .modal-btn {
            width: 100%;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: .3rem;
            padding: .8rem 0;
            letter-spacing: .01em;
        }
        .invite-autocomplete {
            position: absolute;
            left: 0;
            right: 0;
            top: 110%;
            background: var(--background-light);
            border-radius: .7rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.09);
            max-height: 180px;
            overflow-y: auto;
            margin-top: .1rem;
            z-index: 12;
        }
        .invite-autocomplete-item {
            padding: .7rem 1.2rem;
            cursor: pointer;
            font-size: 1rem;
            color: var(--primary-color);
            transition: background .18s;
            border-bottom: 1px solid #f4f4f4;
        }
        .invite-autocomplete-item:last-child {
            border-bottom: none;
        }
        .invite-autocomplete-item:hover {
            background: var(--primary-color);
            color: #fff;
        }
        .group-members-list-modal {
            display: flex;
            flex-direction: column;
            gap: .15rem;
            max-height: 230px;
            overflow-y: auto;
            margin-bottom: 1rem;
            background: var(--background-light);
            border-radius: .7rem;
            padding: .5rem .3rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .group-member-row {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .4rem .5rem;
            border-bottom: 1px solid #e6e6e6;
        }
        .group-member-row:last-child {
            border-bottom: none;
        }
        .group-member-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .group-member-name {
            font-size: 1.08rem;
            font-weight: 500;
            color: var(--accent-color);
        }
        .group-member.admin .group-member-avatar {
            border: 2px solid var(--accent-color);
        }
        .group-member.me .group-member-avatar {
            background: var(--accent-color);
        }
        .manage-actions {
            display: flex;
            gap: .8rem;
            justify-content: center;
        }
        .feedback-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.22);
            z-index: 1001;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .feedback-content {
            background: var(--white);
            border-radius: 1.2rem;
            padding: 2rem 2.6rem;
            box-shadow: 0 0 30px rgba(0,0,0,0.13);
            min-width: 320px;
            max-width: 95vw;
            position: relative;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .feedback-btns {
            display: flex;
            gap: 1.7rem;
            justify-content: center;
            margin-top: 1.2rem;
        }
        .feedback-btn {
            background: var(--accent-color);
            color: #fff;
            border: none;
            border-radius: .7rem;
            font-size: 1.08rem;
            font-weight: 500;
            padding: .6rem 2.1rem;
            cursor: pointer;
            transition: background .18s;
        }
        .feedback-btn:hover {
            background: var(--primary-color);
        }
        .feedback-btn.cancel {
            background: #f44336;
        }
        .feedback-btn.cancel:hover {
            background: #d32f2f;
        }
        .feedback-close {
            position: absolute;
            top: .8rem;
            right: 1.3rem;
            font-size: 1.5rem;
            color: var(--accent-color);
            font-weight: 700;
            cursor: pointer;
        }
                .user-search-row {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin-bottom: 1rem;
            background: var(--background-light);
            border-radius: var(--radius-small);
            padding: .7rem 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            position: relative;
        }
        #user-search-input {
            flex: 1;
            border: 1.5px solid var(--accent-color);
            border-radius: .7rem;
            padding: .6rem 1rem;
            font-size: 1.05rem;
            font-family: inherit;
            transition: border-color .17s, box-shadow .17s;
            outline: none;
        }
        #user-search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(0,191,165,0.13);
        }
        #user-search-btn {
            background: var(--accent-color);
            color: #fff;
            border: none;
            border-radius: .7rem;
            font-size: 1.2rem;
            font-weight: 600;
            padding: .6rem 1.1rem;
            cursor: pointer;
            transition: background .18s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        #user-search-btn:hover {
            background: var(--primary-color);
        }
        .user-list-dropdown {
            position: flex;
            left:  40px;
            top: 100%;
            width: 70%;
            background: #fff;
            border-radius: .7rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.13);
            max-height: 210px;
            overflow-y: auto;
            margin-top: .25rem;
            z-index: 1002;
        }
        .user-list-item {
            padding: .7rem 1.1rem;
            cursor: pointer;
            font-size: 1.07rem;
            color: var(--primary-color);
            transition: background .17s, color .17s;
            border-bottom: 1px solid #f4f4f4;
            display: flex;
            align-items: center;
            gap: .7rem;
        }
        .user-list-item:last-child {
            border-bottom: none;
        }
        .user-list-item:hover {
            background: var(--primary-color);
            color: #fff;
        }
        .last-chat-item {
            padding: .6rem 1.1rem;
            cursor: pointer;
            font-size: 1.07rem;
            color: var(--accent-color);
            border-radius: .5rem;
            margin-bottom: .3rem;
            transition: background .17s, color .17s;
            display: flex;
            align-items: center;
            gap: .7rem;
        }
        .last-chat-item:hover {
            background: var(--accent-color);
            color: #fff;
        }
        .personal-chats-list-row {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin-bottom: 1rem;
            margin-top: .3rem;
            flex-wrap: wrap;
        }
        .personal-chat-chip {
            background: var(--background-light);
            color: var(--primary-color);
            border: 1.5px solid var(--accent-color);
            border-radius: 1.2rem;
            padding: .35rem 1.1rem;
            font-size: 1rem;
            font-family: inherit;
            font-weight: 500;
            cursor: pointer;
            transition: background .18s, color .18s, border-color .18s;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .personal-chat-chip.active, .personal-chat-chip:hover {
            background: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
        }
        .personal-chat-chip {
            position: relative;
        }
        .delete-chat-btn {
            background: none;
            border: none;
            color: #f44336;
            font-size: 1.15em;
            cursor: pointer;
            margin-left: .5rem;
            transition: color .15s;
        }
        .delete-chat-btn:hover {
            color: #d32f2f;
        }
    </style>
    <script>
        function showChatTab(tab) {
            document.querySelectorAll('.chat-tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.chat-panel').forEach(panel => panel.classList.remove('active'));
            document.getElementById('tab-btn-' + tab).classList.add('active');
            document.getElementById('panel-' + tab).classList.add('active');
        }
        window.onload = function() { showChatTab('mundial'); }
    </script>
</head>
<body>
<header class="bg_animate">
<nav class="navbar">
    <h1 class="logo">Daily Memory</h1>
    <div class="nav__list">
        <a href="{{ url('/perfil') }}" class="nav-link"><i class="fas fa-user"></i> Perfil</a>
        <a href="{{ url('/menu') }}" class="nav-link"><i class="fas fa-home"></i> Menú</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="nav-link logout-btn">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</nav>
<main>
    <div class="chat-main-container">
        <div class="chat-tabs-horizontal">
            <button class="chat-tab-btn" id="tab-btn-mundial" onclick="showChatTab('mundial')">
                <i class="fas fa-globe"></i> <span>Mundial</span>
            </button>
            <button class="chat-tab-btn" id="tab-btn-grupos" onclick="showChatTab('grupos')">
                <i class="fas fa-users"></i> <span>Grupos</span>
            </button>
            <button class="chat-tab-btn" id="tab-btn-personal" onclick="showChatTab('personal')">
                <i class="fas fa-user-friends"></i> <span>Personal</span>
            </button>
        </div>
        <div class="chat-card">
            <!-- Chat Mundial -->
            <section class="chat-panel" id="panel-mundial">
                <div class="chat-title"><i class="fas fa-globe"></i> Chat Mundial</div>
                <div class="chat-messages" id="messages-mundial"></div>
                <div class="chat-input-row">
                    <button type="button" class="chat-emoji-btn" id="emoji-btn-mundial">😊</button>
                    <input type="text" class="chat-input" placeholder="Escribe tu mensaje...">
                    <button class="chat-send-btn"><i class="fas fa-paper-plane"></i></button>
                    <div class="emoji-picker-popup" id="emoji-picker-mundial-popup"></div>
                </div>
            </section>
            <!-- Chat Grupos -->
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
                        <span class="modal-close" onclick="closeModal('create-group-modal')">&times;</span>
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
                        <span class="modal-close" onclick="closeModal('manage-group-modal')">&times;</span>
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
            <!-- Chat Personal -->
            <section class="chat-panel" id="panel-personal">
                <div class="chat-title"><i class="fas fa-user-friends"></i> Chat Personal</div>
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

<script>
    function createEmojiPicker(pickerId, btnId, inputSelector, popupClass) {
        const btn = document.getElementById(btnId);
        const input = document.querySelector(inputSelector);
        const popup = document.getElementById(pickerId);
        popup.innerHTML = '';
        const pickerElem = document.createElement('emoji-picker');
        popup.appendChild(pickerElem);

        function closePicker() { popup.style.display = 'none'; }
        btn.addEventListener('click', function(e) {
            popup.style.display = 'block';
            const rect = btn.getBoundingClientRect();
            popup.style.left = (btn.offsetLeft) + "px";
            popup.style.top = (-popup.offsetHeight - 10) + "px";
        });
        document.addEventListener('click', function(e) {
            if (!popup.contains(e.target) && e.target !== btn) {
                closePicker();
            }
        });
        pickerElem.addEventListener('emoji-click', function(event) {
            input.value += event.detail.unicode;
            closePicker();
        });
        closePicker();
    }

    // ----------- Chat Mundial -----------
    const chatMessages = document.getElementById('messages-mundial');
    const chatInput = document.querySelector('#panel-mundial .chat-input');
    const sendBtn = document.querySelector('#panel-mundial .chat-send-btn');
    createEmojiPicker('emoji-picker-mundial-popup', 'emoji-btn-mundial', '#panel-mundial .chat-input', 'emoji-picker-popup');

    function linkify(text) {
        return text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" style="color:var(--accent-color);text-decoration:underline;">$1</a>');
    }

    function loadMessages() {
        fetch('/chat/mundial/messages')
            .then(res => res.json())
            .then(data => {
                chatMessages.innerHTML = '';
                data.forEach(msg => {
                    let isMine = msg.user.id === {{ auth()->id() }};
                    chatMessages.innerHTML += `
                        <div class="chat-message-row ${isMine ? 'mine-row' : ''}">
                            ${!isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                            <div class="chat-message-bubble ${isMine ? 'mine' : ''}">
                                <div class="chat-message-meta">
                                    <span class="chat-message-name">${msg.user.name}</span>
                                    <span class="chat-message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                </div>
                                <div>${linkify(msg.message)}</div>
                            </div>
                            ${isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                        </div>
                    `;
                });
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
    }

    function sendMessage() {
        let text = chatInput.value.trim();
        if (!text) return;
        sendBtn.disabled = true;
        fetch('/chat/mundial/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            chatInput.value = '';
            loadMessages();
            sendBtn.disabled = false;
        });
    }
    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
    setInterval(loadMessages, 5000);
    loadMessages();

    // ----------- Chat Grupos ----------
    const groupSelect = document.getElementById('group-select');
    const groupMessages = document.getElementById('messages-grupos');
    const groupInput = document.getElementById('group-chat-input');
    const groupSendBtn = document.getElementById('group-chat-send');
    createEmojiPicker('emoji-picker-grupos-popup', 'emoji-btn-grupos', '#group-chat-input', 'group-emoji-picker-popup');
    let currentGroupId = null;
    let currentGroup = null;
    let currentGroupMembers = [];
    let currentAdminId = null;
    const inviteUsernameModal = document.getElementById('invite-username-modal');
    const inviteAutocomplete = document.getElementById('invite-autocomplete');
    let selectedInviteUsername = null;

    inviteUsernameModal.addEventListener('input', function() {
        let q = this.value.trim();
        if (q.length < 2) {
            inviteAutocomplete.style.display = 'none';
            selectedInviteUsername = null;
            return;
        }
        fetch(`/chat/grupo/${currentGroupId}/search-user?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(users => {
                if (!users.length) { inviteAutocomplete.style.display = 'none'; selectedInviteUsername = null; return; }
                inviteAutocomplete.innerHTML = '';
                users.forEach(u => {
                    inviteAutocomplete.innerHTML += `<div class="invite-autocomplete-item" data-username="${u.name}">${u.name}</div>`;
                });
                inviteAutocomplete.style.display = 'block';
            });
    });

    inviteAutocomplete.addEventListener('click', function(e) {
        if (e.target.classList.contains('invite-autocomplete-item')) {
            selectedInviteUsername = e.target.getAttribute('data-username');
            inviteUsernameModal.value = selectedInviteUsername;
            inviteAutocomplete.style.display = 'none';
        }
    });

    document.getElementById('invite-user-form-modal').addEventListener('submit', function(e){
        e.preventDefault();
        let username = selectedInviteUsername || inviteUsernameModal.value.trim();
        if(!username || !currentGroupId) return;
        fetch(`/chat/grupo/${currentGroupId}/invite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ username })
        })
        .then(res => res.json())
        .then(data => {
            inviteUsernameModal.value = '';
            selectedInviteUsername = null;
            inviteAutocomplete.style.display = 'none';
            loadGroupDetails(currentGroupId);
            showFeedback(data.message || 'Usuario invitado');
        });
    });

    document.getElementById('open-create-group-modal').onclick = () => {
        document.getElementById('create-group-modal').style.display = 'flex';
    }

    document.getElementById('open-manage-group-modal').onclick = () => {
        document.getElementById('manage-group-modal').style.display = 'flex';
        renderGroupMembersModal(currentGroupMembers, currentAdminId);
        updateManageActions(currentGroup);
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function showFeedback(message, options = {closeOnly:true, onOk:null, onCancel:null, okText:'Cerrar', cancelText:'Cancelar'}) {
        let feedbackModal = document.getElementById('feedback-modal');
        let feedbackMsg = document.getElementById('feedback-message');
        let feedbackBtns = document.getElementById('feedback-btns');
        feedbackMsg.innerHTML = message;
        feedbackBtns.innerHTML = '';
        let closeBtn = document.createElement('span');
        closeBtn.className = 'feedback-close';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = () => { feedbackModal.style.display = 'none'; };
        let content = feedbackModal.querySelector('.feedback-content');
        if (content) {
            if (content.querySelector('.feedback-close')) content.querySelector('.feedback-close').remove();
            content.prepend(closeBtn);
        }
        if (options.closeOnly) {
            let btn = document.createElement('button');
            btn.className = 'feedback-btn';
            btn.innerText = options.okText || 'Cerrar';
            btn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onOk) options.onOk(); };
            feedbackBtns.appendChild(btn);
        } else {
            let okBtn = document.createElement('button');
            okBtn.className = 'feedback-btn';
            okBtn.innerText = options.okText || 'Aceptar';
            okBtn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onOk) options.onOk(); };
            feedbackBtns.appendChild(okBtn);
            let cancelBtn = document.createElement('button');
            cancelBtn.className = 'feedback-btn cancel';
            cancelBtn.innerText = options.cancelText || 'Cancelar';
            cancelBtn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onCancel) options.onCancel(); };
            feedbackBtns.appendChild(cancelBtn);
        }
        feedbackModal.style.display = 'flex';
    }

    document.getElementById('create-group-form-modal').addEventListener('submit', function(e){
        e.preventDefault();
        let name = document.getElementById('new-group-name-modal').value.trim();
        if(!name) return;
        fetch('/chat/grupos/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('new-group-name-modal').value = '';
            closeModal('create-group-modal');
            loadGroups();
            showFeedback('Grupo creado exitosamente');
        });
    });

    function loadGroups() {
        fetch('/chat/grupos')
            .then(res => res.json())
            .then(groups => {
                groupSelect.innerHTML = '';
                groups.forEach(g => {
                    groupSelect.innerHTML += `<option value="${g.id}">${g.name}</option>`;
                });
                if (groups.length) {
                    currentGroupId = groups[0].id;
                    loadGroupDetails(currentGroupId);
                }
            });
    }

    groupSelect.addEventListener('change', function() {
        currentGroupId = this.value;
        loadGroupDetails(currentGroupId);
    });

    function loadGroupDetails(groupId) {
        fetch(`/chat/grupo/${groupId}/info`)
            .then(res => res.json())
            .then(data => {
                currentGroup = data;
                currentGroupMembers = data.members;
                currentAdminId = data.admin_id;
                renderGroupMembers(currentGroupMembers, currentAdminId);
                updateManageActions(data);
                loadGroupMessages(groupId);
            });
    }

    function renderGroupMembers(members, adminId) {
        let container = document.getElementById('group-members-list');
        container.innerHTML = '';
        members.forEach(m => {
            let classes = 'group-member';
            if(m.id === parseInt('{{ auth()->id() }}')) classes += ' me';
            if(m.id === adminId) classes += ' admin';
            container.innerHTML += `<span class="${classes}">${m.name}</span>`;
        });
    }
    function renderGroupMembersModal(members, adminId) {
        let container = document.getElementById('group-members-list-modal');
        container.innerHTML = '';
        members.forEach(m => {
            let isAdmin = m.id === adminId;
            let isMe = m.id === parseInt('{{ auth()->id() }}');
            container.innerHTML += `
                <div class="group-member-row group-member${isAdmin ? ' admin' : ''}${isMe ? ' me' : ''}">
                    <div class="group-member-avatar">${m.name.charAt(0).toUpperCase()}</div>
                    <div class="group-member-name">${m.name}${isAdmin ? ' <span style="font-size:0.85em;color:#009688;font-weight:600;">(admin)</span>' : ''}${isMe ? ' <span style="font-size:0.85em;color:#2196f3;">(tú)</span>' : ''}</div>
                </div>
            `;
        });
    }

    function updateManageActions(group) {
        document.getElementById('invite-user-form-modal').style.display = (group.is_admin ? 'flex' : 'none');
        document.getElementById('delete-group-btn-modal').style.display = (group.is_admin ? 'inline-block' : 'none');
        document.getElementById('leave-group-btn-modal').style.display = (group.is_member ? 'inline-block' : 'none');
    }

    function loadGroupMessages(groupId) {
        fetch(`/chat/grupo/${groupId}/messages`)
            .then(res => res.json())
            .then(data => {
                groupMessages.innerHTML = '';
                data.forEach(msg => {
                    let isMine = msg.user.id === parseInt('{{ auth()->id() }}');
                    groupMessages.innerHTML += `
                        <div class="chat-message-row ${isMine ? 'mine-row' : ''}">
                            ${!isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                            <div class="chat-message-bubble ${isMine ? 'mine' : ''}">
                                <div class="chat-message-meta">
                                    <span class="chat-message-name">${msg.user.name}</span>
                                    <span class="chat-message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                </div>
                                <div>${linkify(msg.message)}</div>
                            </div>
                            ${isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                        </div>
                    `;
                });
                groupMessages.scrollTop = groupMessages.scrollHeight;
            });
    }
    groupSendBtn.addEventListener('click', sendGroupMessage);
    groupInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendGroupMessage();
    });

    function sendGroupMessage() {
        let text = groupInput.value.trim();
        if (!text || !currentGroupId) return;
        groupSendBtn.disabled = true;
        fetch(`/chat/grupo/${currentGroupId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            groupInput.value = '';
            loadGroupMessages(currentGroupId);
            groupSendBtn.disabled = false;
        });
    }
    setInterval(function(){
        if(currentGroupId) loadGroupMessages(currentGroupId);
    }, 5000);

    document.getElementById('leave-group-btn-modal').addEventListener('click', function(){
        showFeedback('¿Seguro que deseas salir del grupo?', {
            closeOnly: false,
            onOk: function() {
                fetch(`/chat/grupo/${currentGroupId}/leave`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    closeModal('manage-group-modal');
                    loadGroups();
                    showFeedback('Has salido del grupo');
                });
            },
            okText: 'Salir',
            cancelText: 'Cancelar'
        });
    });

    document.getElementById('delete-group-btn-modal').addEventListener('click', function(){
        showFeedback('¿Seguro que deseas eliminar el grupo?', {
            closeOnly: false,
            onOk: function() {
                fetch(`/chat/grupo/${currentGroupId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    closeModal('manage-group-modal');
                    loadGroups();
                    showFeedback('Grupo eliminado');
                });
            },
            okText: 'Eliminar',
            cancelText: 'Cancelar'
        });
    });
    loadGroups();

    function showChatTab(tab) {
        document.querySelectorAll('.chat-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.chat-panel').forEach(panel => panel.classList.remove('active'));
        document.getElementById('tab-btn-' + tab).classList.add('active');
        document.getElementById('panel-' + tab).classList.add('active');
    }

    window.onload = function() { showChatTab('personal'); }

    // --------- Chat Personal ---------
    const personalChatsListRow = document.getElementById('personal-chats-list-row');
    const userSearchInput = document.getElementById('user-search-input');
    const userSearchBtn = document.getElementById('user-search-btn');
    const userListDropdown = document.getElementById('user-list-dropdown');
    const personalChatHeader = document.getElementById('personal-chat-header');
    const personalMessages = document.getElementById('messages-personal');
    const personalInput = document.getElementById('personal-chat-input');
    const personalSendBtn = document.getElementById('personal-chat-send');
    let selectedUserId = null;
    let selectedUserName = "";

    function loadPersonalChatsList(selectedId = null) {
        fetch('/chat/personal/last-chats')
            .then(res => res.json())
            .then(chats => {
                personalChatsListRow.innerHTML = '';
                if(!chats.length){
                    personalChatsListRow.innerHTML = '<div style="padding:.3rem 1rem;color:#888;">Sin conversaciones personales.</div>';
                    return;
                }
                chats.forEach(u => {
                    personalChatsListRow.innerHTML += `
                        <div style="display:inline-flex;align-items:center;">
                            <button class="personal-chat-chip${selectedId == u.id ? ' active' : ''}"
                                data-id="${u.id}" data-name="${u.name}">
                                <i class="fas fa-user"></i> ${u.name}
                            </button>
                            <button class="delete-chat-btn" title="Eliminar chat" data-id="${u.id}" style="margin-left:4px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                });
            });
    }

    personalChatsListRow.addEventListener('click', function(e){
        const deleteBtn = e.target.closest('.delete-chat-btn');
        if(deleteBtn && personalChatsListRow.contains(deleteBtn)){
            const userId = deleteBtn.getAttribute('data-id');
            showFeedback('¿Seguro que deseas eliminar la conversación?', {
                closeOnly: false,
                okText: 'Eliminar',
                cancelText: 'Cancelar',
                onOk: function() {
                    fetch(`/chat/personal/${userId}/delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(selectedUserId == userId){
                            selectedUserId = null;
                            selectedUserName = "";
                            personalChatHeader.innerHTML = '';
                            personalMessages.innerHTML = '';
                            userSearchInput.value = '';
                        }
                        loadPersonalChatsList();
                        showFeedback('Conversación eliminada');
                    });
                }
            });
            e.stopPropagation();
            return;
        }

        const chip = e.target.closest('.personal-chat-chip');
        if(chip && personalChatsListRow.contains(chip)){
            selectedUserId = chip.getAttribute('data-id');
            selectedUserName = chip.getAttribute('data-name');
            userSearchInput.value = selectedUserName;
            personalChatHeader.innerHTML = `<i class="fas fa-user-circle"></i> Conversando con <span style="color:var(--primary-color);">${selectedUserName}</span>`;
            loadPersonalMessages();
            loadPersonalChatsList(selectedUserId);
            userListDropdown.style.display = 'none';
        }
    });

    userSearchInput.addEventListener('input', function() {
        let q = this.value.trim();
        if (q.length < 2) {
            userListDropdown.style.display = 'none';
            selectedUserId = null;
            return;
        }
        fetch(`/chat/personal/search?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(users => {
                if (!users.length) {
                    userListDropdown.style.display = 'none';
                    selectedUserId = null;
                    return;
                }
                userListDropdown.innerHTML = '';
                users.forEach(u => {
                    userListDropdown.innerHTML += `<div class="user-list-item" data-id="${u.id}" data-name="${u.name}"><i class="fas fa-user"></i> ${u.name}</div>`;
                });
                userListDropdown.style.display = 'block';
                const rect = userSearchInput.getBoundingClientRect();
                userListDropdown.style.left = userSearchInput.offsetLeft + "px";
                userListDropdown.style.top = (userSearchInput.offsetTop + userSearchInput.offsetHeight + 8) + "px";
            });
    });

    userListDropdown.addEventListener('click', function(e) {
        if (e.target.classList.contains('user-list-item')) {
            selectedUserId = e.target.getAttribute('data-id');
            selectedUserName = e.target.getAttribute('data-name');
            userListDropdown.style.display = 'none';
            userSearchInput.value = selectedUserName;
            personalChatHeader.innerHTML = `<i class="fas fa-user-circle"></i> Conversando con <span style="color:var(--primary-color);">${selectedUserName}</span>`;
            loadPersonalMessages();
            loadPersonalChatsList(selectedUserId);
        }
    });

    userSearchBtn.addEventListener('click', function() {
        if(selectedUserId) {
            loadPersonalMessages();
        } else {
            showFeedback('Selecciona un usuario válido antes de conversar.');
        }
    });

    function loadPersonalMessages() {
        if (!selectedUserId) return;
        fetch(`/chat/personal/${selectedUserId}/messages`)
            .then(res => res.json())
            .then(data => {
                personalMessages.innerHTML = '';
                data.forEach(msg => {
                    let isMine = msg.user.id === parseInt('{{ auth()->id() }}');
                    personalMessages.innerHTML += `
                        <div class="chat-message-row ${isMine ? 'mine-row' : ''}">
                            ${!isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                            <div class="chat-message-bubble ${isMine ? 'mine' : ''}">
                                <div class="chat-message-meta">
                                    <span class="chat-message-name">${msg.user.name}</span>
                                    <span class="chat-message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                </div>
                                <div>${msg.message}</div>
                            </div>
                            ${isMine ? `<div class="chat-message-avatar">${msg.user.name.charAt(0).toUpperCase()}</div>` : ''}
                        </div>
                    `;
                });
                personalMessages.scrollTop = personalMessages.scrollHeight;
            });
    }

    function sendPersonalMessage() {
        let text = personalInput.value.trim();
        if (!text) return;
        if (!selectedUserId) {
            showFeedback('Selecciona un usuario válido antes de enviar el mensaje.');
            return;
        }
        personalSendBtn.disabled = true;
        fetch(`/chat/personal/${selectedUserId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            personalInput.value = '';
            loadPersonalMessages();
            personalSendBtn.disabled = false;
            loadPersonalChatsList(selectedUserId);
        });
    }

    personalSendBtn.addEventListener('click', sendPersonalMessage);
    personalInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendPersonalMessage();
    });

    function showFeedback(message, options = {closeOnly:true, onOk:null, onCancel:null, okText:'Cerrar', cancelText:'Cancelar'}) {
        let feedbackModal = document.getElementById('feedback-modal');
        if (!feedbackModal) {
            feedbackModal = document.createElement('div');
            feedbackModal.id = 'feedback-modal';
            feedbackModal.className = 'feedback-modal';
            feedbackModal.style.display = 'none';
            feedbackModal.innerHTML = `
                <div class="feedback-content">
                    <span id="feedback-message"></span>
                    <div class="feedback-btns" id="feedback-btns"></div>
                </div>
            `;
            document.body.appendChild(feedbackModal);
        }
        let feedbackMsg = feedbackModal.querySelector('#feedback-message');
        let feedbackBtns = feedbackModal.querySelector('#feedback-btns');
        feedbackMsg.innerHTML = message;
        feedbackBtns.innerHTML = '';
        let closeBtn = document.createElement('span');
        closeBtn.className = 'feedback-close';
        closeBtn.innerHTML = '&times;';
        closeBtn.onclick = () => { feedbackModal.style.display = 'none'; };
        let content = feedbackModal.querySelector('.feedback-content');
        if (content) {
            if (content.querySelector('.feedback-close')) content.querySelector('.feedback-close').remove();
            content.prepend(closeBtn);
        }
        if (options.closeOnly) {
            let btn = document.createElement('button');
            btn.className = 'feedback-btn';
            btn.innerText = options.okText || 'Cerrar';
            btn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onOk) options.onOk(); };
            feedbackBtns.appendChild(btn);
        } else {
            let okBtn = document.createElement('button');
            okBtn.className = 'feedback-btn';
            okBtn.innerText = options.okText || 'Aceptar';
            okBtn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onOk) options.onOk(); };
            feedbackBtns.appendChild(okBtn);
            let cancelBtn = document.createElement('button');
            cancelBtn.className = 'feedback-btn cancel';
            cancelBtn.innerText = options.cancelText || 'Cancelar';
            cancelBtn.onclick = () => { feedbackModal.style.display = 'none'; if(options.onCancel) options.onCancel(); };
            feedbackBtns.appendChild(cancelBtn);
        }
        feedbackModal.style.display = 'flex';
    }

    document.getElementById('tab-btn-personal').addEventListener('click', function(){
        loadPersonalChatsList(selectedUserId);
        userListDropdown.style.display = 'none';
        if (!selectedUserId) {
            personalChatHeader.innerHTML = '';
            personalMessages.innerHTML = '';
            userSearchInput.value = '';
        } else {
            personalChatHeader.innerHTML = `<i class="fas fa-user-circle"></i> Conversando con <span style="color:var(--primary-color);">${selectedUserName}</span>`;
            loadPersonalMessages();
            userSearchInput.value = selectedUserName;
        }
    });

    loadPersonalChatsList();
</script>

</body>
</html>

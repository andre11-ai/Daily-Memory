<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia Nivel 5 | Daily Memory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Niveles/Nivel-5.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Historia-Nivel 5</h1>
            <div class="nav__list">
                <a href="/story" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="game-top-bar">
        <div class="game-title">Repetir Palabra</div>
        <div class="stats-bar">
            <span id="score-label">Score: 0</span>
            <span id="ronda-label">Ronda: 1</span>
        </div>
    </div>

    <main>
        <div class="game-card">
            <p>Escucha la secuencia y selecciona el orden correcto.</p>
            <div id="game-area">
                <button id="start-btn">Siguiente</button>
                <div id="sound-buttons" class="hidden"></div>
                <div id="user-selection"></div>
                <p id="feedback"></p>
            </div>
        </div>
    </main>

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">HISTORIA · NIVEL 5</div>
                    <h2 id="gov-title" class="intro-title">Repetir Palabra</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        Meta: consigue <strong>18 puntos</strong>. <br>
                        Escucha y repite la secuencia de palabras.
                    </p>
                    <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                        Puntaje final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
                    </p>
                </div>
                <div class="intro-footer">
                    <button id="action-btn" class="start-btn">¡Empezar!</button>
                    <div id="back-menu-container"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="{{ asset('/JS/Niveles/Nivel-5.js') }}"></script>
</body>
</html>
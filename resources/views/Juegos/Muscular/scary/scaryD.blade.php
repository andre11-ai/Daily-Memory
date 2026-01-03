<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Scary Witch Typing - Difícil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Muscular/scary/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Muscular</h1>
            <div class="nav__list">
                <a href="/TiposMemoria/Mmuscular" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <main>
        <div class="game-top-bar">
            <div class="game-info">
                <h2>Scary Witch Typing (Difícil)</h2>
                <p>Palabras cortas - Enemigos pequeños y rápidos</p>
            </div>
            <div class="score-badge">
                Score: <span id="score">0</span>
            </div>
        </div>

        <div class="game-card">
            <button id="pauseBtn">Pausa</button>
            <canvas id="scaryCanvas"></canvas>
        </div>

        <div id="modal-gameover" class="intro-overlay">
            <div class="intro-scene">
                <div class="mascot-container">
                    <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
                </div>
                <div id="gov-bubble" class="speech-bubble">
                    <div class="intro-header">
                        <div id="gov-eyebrow" class="intro-eyebrow">MEMORIA MUSCULAR</div>
                        <h2 id="gov-title" class="intro-title">Nivel Difícil</h2>
                    </div>
                    <div class="intro-content">
                        <p id="gov-msg">
                            Solo para expertos. Las palabras caen muy rápido.
                            <br><strong>¿Podrás sobrevivir?</strong>
                        </p>
                        <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                            Puntaje final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
                        </p>
                    </div>
                    <div class="intro-footer">
                        <button id="action-btn" class="start-btn">¡Empezar!</button>
                        <div id="back-menu-container">
                            <a href="/TiposMemoria/Mmuscular" class="modal-back-link">Volver al menú</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="{{ asset('JS/Juegos/Muscular/scary/appD.js') }}"></script>
</body>
</html>
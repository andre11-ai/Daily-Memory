<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parejas Auditivas - Fácil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Ecoica/sonidoPareja/sonidoParejaB.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Ecoica</h1>
            <div class="nav__list">
                <a href="/TiposMemoria/Mecoica" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="ecoica-title-row">
        <div class="ecoica-title">Parejas Auditivas (Fácil)</div>
        <div class="ecoica-bar">
            <span id="score-label">Score: 0</span>
            <span id="tiempo-label">Tiempo: 60s</span>
        </div>
    </div>

    <main>
        <div class="container-card">
            <button id="reset-game" class="restart-btn" style="display:none;">Reiniciar Juego</button>
            <div id="parejas-juego"></div>
            <p id="feedback"></p>
        </div>

        <div id="modal-gameover" class="intro-overlay">
            <div class="intro-scene">
                <div class="mascot-container">
                    <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
                </div>
                <div id="gov-bubble" class="speech-bubble">
                    <div class="intro-header">
                        <div id="gov-eyebrow" class="intro-eyebrow">MEMORIA ECOICA</div>
                        <h2 id="gov-title" class="intro-title">Nivel Fácil</h2>
                    </div>
                    <div class="intro-content">
                        <p id="gov-msg">
                            Encuentra las parejas de sonidos iguales.
                            <br>Tienes <strong>60 segundos</strong>.
                        </p>
                        <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                            Puntaje final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
                        </p>
                    </div>
                    <div class="intro-footer">
                        <button id="action-btn" class="start-btn">¡Empezar!</button>
                        <div id="back-menu-container">
                            <a href="/TiposMemoria/Mecoica" class="modal-back-link">Volver al menú</a>
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

    <script src="{{ asset('JS/Juegos/Ecoica/sonidoPareja/sonidoParejaB.js') }}"></script>
</body>
</html>
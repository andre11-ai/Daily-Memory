<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lluvia de Letras - Medio | MemoryMaster</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <script src="{{ asset('JS/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM.js') }}" defer></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Muscular</h1>
            <div class="nav__list">
                <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="game-top-bar">
        <div class="game-title">Lluvia de Letras</div>
        <div class="score-vidas-bar">
            <span id="score-label"><b>Score:</b> 0</span>
            <span id="vidas-label"><b>Vidas:</b>
                <span id="vidas-dots">
                    <span class="vida-dot active"></span>
                    <span class="vida-dot active"></span>
                    <span class="vida-dot active"></span>
                </span>
            </span>
        </div>
    </div>

    <main>
        <section id="maincontainer"></section>
    </main>

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">NIVEL MEDIO</div>
                    <h2 id="gov-title" class="intro-title">Lluvia de Letras</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        Las palabras caen más rápido. <br>
                        Meta: <strong>25 puntos</strong>. <br>
                        ¡Prepara tus dedos!
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
</body>
</html>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Velocímetro - Medio | Daily Memory</title>

    <link href="{{ asset('CSS/Juegos/Muscular/Velocimetro/velocimetroM.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('JS/Juegos/Muscular/Velocimetro/velocimetroM.js') }}" defer></script>
</head>

<body class="bg_animate">
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

    <div class="game-title-standalone">
       Velocímetro (Medio)
    </div>

    <main>
        <section id="word-section" class="word-card">
            <div class="waiting">⌛</div>
        </section>

        <section id="type-section" class="type-section">
            <input id="typebox" name="typebox" type="text" tabindex="1" autocomplete="off" placeholder="Esperando..." disabled />
            <div id="timer" class="type-btn"><span>1:00</span></div>
            <button id="restart" class="type-btn" tabindex="2" aria-label="Reiniciar">
                <span id="restart-symbol">↻</span>
            </button>
        </section>

        <div id="modal-gameover" class="intro-overlay">
            <div class="intro-scene">
                <div class="mascot-container">
                    <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
                </div>
                <div id="gov-bubble" class="speech-bubble">
                    <div class="intro-header">
                        <div id="gov-eyebrow" class="intro-eyebrow">VELOCÍMETRO</div>
                        <h2 id="gov-title" class="intro-title">Nivel Medio</h2>
                    </div>
                    <div class="intro-content">
                        <p id="gov-msg">
                            Aumentamos la dificultad. Escribe sin errores.
                            <br>Meta: <strong>35 Palabras por Minuto (WPM)</strong>.
                        </p>
                        <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                            Velocidad final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong> WPM
                        </p>
                    </div>
                    <div class="intro-footer">
                        <button id="action-btn" class="start-btn">¡Empezar!</button>
                        <div id="back-menu-container">
                            <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="modal-back-link">Volver al menú</a>
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
</body>
</html>
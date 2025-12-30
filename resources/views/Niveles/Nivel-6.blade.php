<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historia Nivel 6 | Daily Memory</title>

    <link href="{{ asset('/CSS/Niveles/Nivel-6.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg_animate">
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Historia-Nivel 6</h1>
            <div class="nav__list">
                <a href="{{ url('/story') }}" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="game-title-standalone">
       Velocímetro
    </div>

    <main>
        <section id="word-section" class="word-card">
            </section>

        <section id="type-section" class="type-section">
            <input id="typebox" name="typebox" type="text" tabindex="1" placeholder="Escribe aquí..." autocomplete="off" />
            <div id="timer" class="type-btn"><span>1:00</span></div>
            <button id="restart" class="type-btn" tabindex="2" aria-label="Reiniciar">
                <span id="restart-symbol">↻</span>
            </button>
        </section>
    </main>

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">HISTORIA · NIVEL 6</div>
                    <h2 id="gov-title" class="intro-title">Velocímetro</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        Meta: 20 WPM. <br>
                        ¡Escribe rápido!
                    </p>
                    <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                        Velocidad: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
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

    <script src="{{ asset('/JS/Niveles/Nivel-6.js') }}"></script>
</body>
</html>
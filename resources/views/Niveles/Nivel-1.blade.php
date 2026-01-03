<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia Nivel 1 | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('/CSS/Niveles/Nivel-1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Historia-Nivel 1</h1>
            <div class="nav__list">
                <a href="/story" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="color-title-bar">
        <div class="game-title">Memoriza los colores</div>
        <div class="color-score-bar">
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
        <div class="game-container" id="game-container" data-level="F">
            <div class="memorize-phase" id="memorize-phase">
                <div class="memorize-grid" id="memorize-grid"></div>
                <button class="game-button" id="ready-btn">¡Listo!</button>
            </div>

            <div class="select-phase" id="select-phase" style="display:none;">
                <div class="game-title-standalone">Selecciona los colores correctos</div>
                <div class="color-grid" id="select-grid"></div>
                <button class="game-button" id="verify-btn">Verificar</button>
            </div>
        </div>
    </main>

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">

            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota Resultado" class="mascot-img" />
            </div>

            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">RESULTADO</div>
                    <h2 id="gov-title" class="intro-title">Título del Resultado</h2>
                </div>

                <div class="intro-content">
                    <p id="gov-msg">Mensaje de la mascota aquí...</p>
                    <p style="font-size: 1.1rem; color:#333;">
                        Puntaje final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
                    </p>
                </div>

                <div class="intro-footer">
                    <button id="action-btn" class="start-btn">Acción</button>
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

    <script src="/JS/Niveles/Nivel-1.js"></script>
</body>
</html>

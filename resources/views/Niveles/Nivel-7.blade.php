<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Nivel 7 | Daily Memory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Niveles/Nivel-7.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Historia-Nivel 7</h1>
            <div class="nav__list">
                <a href="/story" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="color-title-bar">
        <div class="game-title">Secuencia de Imágenes</div>
        <div class="color-score-bar">
            <span id="score-label"><b>Score:</b> 0</span>
            <span id="vidas-label"><b>Vidas:</b>
                <span id="vidas-dots">
                    <span class="vida-dot active"></span>
                    <span class="vida-dot active"></span>
                    <span class="vida-dot active"></span>
                </span>
            </span>
            <span id="nivel-label"><b>Nivel:</b> 1 / 10</span>
        </div>
    </div>

    <main class="game-container" id="game-container" data-level="4">
        <section class="memorize-phase" id="memorize-phase">
            <h2 class="game-title-secuencia">Memoriza las imágenes</h2>
            <div class="image-grid" id="memorize-grid"></div>
            <button class="game-button" id="listo-btn" onclick="pressReady()">¡Listo!</button>
        </section>

        <section class="recall-phase" id="recall-phase" style="display:none;">
            <h3 class="game-subtitle-secuencia">Presiona una imagen para acomodarla</h3>
            <div class="image-grid" id="slot-grid"></div>
            <hr class="divider">
            <div class="image-grid" id="pool-grid"></div>
            <button class="game-button" id="verificar-btn" onclick="pressVerify()">Verificar</button>
        </section>
    </main>

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">HISTORIA · NIVEL 7</div>
                    <h2 id="gov-title" class="intro-title">Secuencia de Imágenes</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        Meta: logra <strong>22 puntos</strong>. <br>
                        Memoriza y ordena las secuencias correctamente.
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

    <script src="/JS/Niveles/Nivel-7.js"></script>
</body>
</html>
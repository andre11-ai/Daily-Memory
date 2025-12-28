<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secuencia - Medio</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Secuencia/secuenciaM.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Icónica</h1>
            <div class="nav__list">
                <a href="/TiposMemoria/Miconica" class="nav-link volver-btn">
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

    <main class="game-container" id="game-container" data-level="6">
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

    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Puntaje final: <span id="score-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="/TiposMemoria/Miconica" class="nav-link volver-btn" style="justify-content:center; margin-top:14px;">
                 <i class='bx bx-left-arrow-alt'></i> Volver
            </a>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="/JS/Juegos/Iconica/Secuencia/secuenciaM.js"></script>
</body>
</html>

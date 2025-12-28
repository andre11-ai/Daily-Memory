<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoriza el Color | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('/CSS/Juegos/Iconica/Color/colorB.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
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
        <div class="game-title">Memoriza el color</div>
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

    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Puntaje final: <span id="score-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="/TiposMemoria/Miconica" class="nav-link volver-btn" style="justify-content:center; margin-top:10px;">
                <i class='bx bx-left-arrow-alt'></i> Volver al Menú
            </a>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="/JS/Juegos/Iconica/Color/colorB.js"></script>
</body>
</html>

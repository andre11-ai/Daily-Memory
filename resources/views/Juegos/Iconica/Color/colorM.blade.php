<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoriza el Color | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="/CSS/Juegos/Iconica/Color/colorM.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Color/colorM.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <div class="burbujas">
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>    </div>
    <header class="header-bar">
        <h1 class="logo">Memoria Iconica</h1>
        <div class="header-actions">
            <a href="/TiposMemoria/Miconica" class="volver-link">← Volver</a>
        </div>
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
        <div class="game-container" id="game-container" data-level="M">
            <div class="memorize-phase" id="memorize-phase">
                <div class="memorize-grid" id="memorize-grid"></div>
                <button class="game-button" id="ready-btn">¡Listo!</button>
            </div>
            <div class="select-phase" id="select-phase" style="display:none;">
                <div class="game-title-standalone">Selecciona los colores que aparecían</div>
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
            <a href="/TiposMemoria/Miconica" class="volver-link" style="display:block;margin-top:14px;">← Volver</a>
        </div>
    </div>
    <script src="/JS/Juegos/Iconica/Color/colorM.js"></script>
</body>
</html>

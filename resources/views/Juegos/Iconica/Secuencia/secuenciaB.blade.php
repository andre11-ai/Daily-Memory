<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secuencia - Fácil</title>
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Secuencia/secuenciaB.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="burbujas">
        @for($i = 0; $i < 10; $i++) <div class="burbuja"></div> @endfor
    </div>
    <header class="header-bar">
        <h1 class="logo">Memoria Iconica</h1>
        <div class="header-actions">
            <a href="/TiposMemoria/Miconica" class="volver-link">← Volver</a>
        </div>
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
    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Puntaje final: <span id="score-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="/TiposMemoria/Miconica" class="volver-link" style="display:block;margin-top:14px;">← Volver</a>
        </div>
    </div>
    <script src="/JS/Juegos/Iconica/Secuencia/secuenciaB.js"></script>
</body>
</html>
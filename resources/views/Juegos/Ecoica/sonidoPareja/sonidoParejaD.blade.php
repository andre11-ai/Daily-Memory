<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parejas Auditivas - Nivel Difícil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/sonidoPareja/sonidoParejaD.css') }}">
</head>
<body>
    <div class="burbujas">
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
    </div>
    <header class="header-bar">
        <span class="logo">Memoria Ecoica</span>
        <div class="header-actions">
            <a href="/TiposMemoria/Mecoica" class="volver-link">← Volver</a>
        </div>
    </header>

    <div class="ecoica-title-row">
        <div class="ecoica-title">
            Parejas Auditivas
        </div>
        <div class="ecoica-bar">
            <span id="score-label">Score: 0</span>
            <span id="tiempo-label">Tiempo: 40s</span>
        </div>
    </div>

    <div class="container-card">
        <button id="reset-game" class="restart-btn">Reiniciar</button>
        <div id="parejas-juego"></div>
        <p id="feedback"></p>
    </div>
    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Puntaje: <span id="score-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="/TiposMemoria/Mecoica" class="volver-link" style="display:block;margin-top:14px;">← Volver</a>
        </div>
    </div>
    <script src="{{ asset('JS/Juegos/Ecoica/sonidoPareja/sonidoParejaD.js') }}"></script>
</body>
</html>

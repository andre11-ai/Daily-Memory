<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Ecoica - Difícil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/repetirPalabra/repetirPalabraD.css') }}">
</head>
<body>
    <div class="burbujas">
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>     </div>
    <header class="header-bar">
        <span class="logo">Memoria Ecoica</span>
        <div class="header-actions">
            <a href="/TiposMemoria/Mecoica" class="volver-link">← Volver</a>
        </div>
    </header>
    <div class="ecoica-title-row">
        <div class="ecoica-title">Repetir Palabra</div>
        <div class="ecoica-bar">
            <span id="score-label">Score: 0</span>
            <span id="ronda-label">Ronda: 1</span>
        </div>
    </div>
    <div class="container">
        <p>Escucha la secuencia y selecciona el orden correcto.</p>
        <div id="game-area">
            <button id="start-btn">Siguiente</button>
            <div id="sound-buttons" class="hidden"></div>
            <div id="user-selection"></div>
            <p id="feedback"></p>
        </div>
    </div>
    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Puntaje: <span id="score-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="/TiposMemoria/Mecoica" class="volver-link" style="display:block;margin-top:14px;">← Volver</a>
        </div>
    </div>
    <script>
        window.nivelActual = 'dificil';
    </script>
    <script src="{{ asset('/JS/Juegos/Ecoica/repetirPalabra/repetirPalabraD.js') }}"></script>
</body>
</html>

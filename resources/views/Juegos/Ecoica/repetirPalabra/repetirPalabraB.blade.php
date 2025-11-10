<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Ecoica - Repetir Palabra</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/repetirPalabra/repetirPalabraB.css') }}">
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
    <div class="game-title-standalone">
        Repetir Palabra
    </div>
    <div class="container">
        <p>Escucha la secuencia y selecciona el orden correcto.</p>
        <div id="level-selector">
            <button class="selected">Fácil</button>
        </div>
        <div id="game-area">
            <button id="start-btn">Empezar</button>
            <div id="sound-buttons" class="hidden"></div>
            <div id="user-selection"></div>
            <p id="feedback"></p>
        </div>
    </div>
    <script src="{{ asset('/JS/Juegos/Ecoica/repetirPalabra/repetirPalabraB.js') }}"></script>
</body>
</html>

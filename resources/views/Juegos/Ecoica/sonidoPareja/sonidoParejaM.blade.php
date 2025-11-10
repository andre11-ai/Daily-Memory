<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parejas Auditivas - Nivel Medio</title>
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/sonidoPareja/sonidoParejaM.css') }}">
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
    <div class="game-title-standalone">Parejas Auditivas</div>
    <div class="container-card">
        <button id="reset-game" class="restart-btn">Reiniciar</button>
        <div id="parejas-juego"></div>
        <p id="feedback"></p>
    </div>
    <script src="{{ asset('JS/Juegos/Ecoica/sonidoPareja/sonidoParejaM.js') }}"></script>
</body>
</html>

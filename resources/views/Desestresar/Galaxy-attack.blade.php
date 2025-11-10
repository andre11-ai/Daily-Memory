<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galaxy attack</title>
    <link rel="stylesheet" href="{{ asset('/CSS/Desestresar/Galaxy-attack.css') }}">
    <script type="text/javascript" src="{{ asset('/JS/Desestresar/Galaxy-attack.js') }}"></script>
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
        <span class="logo">Galaxy Attack</span>
        <div class="header-actions">
            <a href="/Desestresar" class="volver-link">← Volver</a>
        </div>
    </header>
    <div class="container-card">
        <canvas id="miCanvas" width="600" height="500">
            Galaxy attack
        </canvas>
        <div class="botonesDiv">
            <button class="botonesMover" onclick="verifica(true,37)">&lt;-</button>
            <button class="botonDisparo" onclick="verifica(true,32)">Fire</button>
            <button class="botonesMover" onclick="verifica(true,39)">-&gt;</button>
        </div>
    </div>
</body>
</html>

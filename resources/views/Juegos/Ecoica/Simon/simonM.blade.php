<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Memoria Ecoica — Simon Medio (5 botones)</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
  <link rel="stylesheet" href="/CSS/Juegos/Ecoica/Simon/simonM.css">
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
    Simon Medio
  </div>
  <div class="container simon-container" role="application" aria-label="Simon Medio - 5 botones">
    <svg id="board" viewBox="-260 -260 520 520" aria-hidden="true"></svg>
    <div class="center">
      <div class="title">SIMON</div>
      <div class="controls">
        <div class="count" id="count">--</div>
        <button id="start" class="btn">Start</button>
        <button id="strict" class="btn toggle" title="Modo estricto">Strict</button>
        <button id="sound" class="btn toggle" title="Sonidos">Sonido</button>
      </div>
    </div>
  </div>
  <script src="/JS/Juegos/Ecoica/Simon/simonM.js"></script>
</body>
</html>

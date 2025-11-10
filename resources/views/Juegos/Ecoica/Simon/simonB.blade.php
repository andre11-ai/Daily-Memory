<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Memoria Ecoica — Simon Fácil</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
  <link rel="stylesheet" href="/CSS/Juegos/Ecoica/Simon/simonB.css">
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
    Simon Fácil
  </div>
  <div class="container simon-container" role="application" aria-label="Simon juego: fácil">
    <svg id="board" viewBox="-260 -260 520 520" aria-hidden="true" focusable="false"></svg>
    <div class="center" aria-hidden="false">
      <div class="title">SIMON</div>
      <div class="controls" role="group" aria-label="Controles">
        <div class="count" id="count" aria-live="polite">--</div>
        <button class="btn" id="start">Start</button>
        <button class="btn toggle" id="strict" title="Modo estricto">Strict</button>
        <button class="btn toggle" id="soundToggle" title="Sonidos">Sonido</button>
      </div>
    </div>
  </div>
  <script src="/JS/Juegos/Ecoica/Simon/simonB.js"></script>
</body>
</html>

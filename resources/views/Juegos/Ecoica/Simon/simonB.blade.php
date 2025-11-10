<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Memoria Ecoica — Simon Fácil</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
  <div class="ecoica-title-row">
    <div class="ecoica-title">Simon Fácil</div>
    <div class="ecoica-bar">
      <span id="score-label">Score: 0</span>
    </div>
  </div>
  <div class="container simon-container">
    <svg id="board" viewBox="-260 -260 520 520"></svg>
    <div class="center">
      <div class="title">SIMON</div>
      <div class="controls">
        <div class="count" id="count">--</div>
        <button class="btn" id="start">Start</button>
        <button class="btn toggle" id="strict" title="Modo estricto">Strict</button>
        <button class="btn toggle" id="soundToggle" title="Sonidos">Sonido</button>
      </div>
    </div>
  </div>
  <div id="modal-gameover" class="modal-gameover" style="display:none;">
    <div class="modal-content">
      <h2>¡Fin del juego!</h2>
      <p>Puntaje máximo: <span id="score-modal">0</span></p>
      <button id="restart-btn">Reiniciar</button>
      <a href="/TiposMemoria/Mecoica" class="volver-link" style="display:block;margin-top:14px;">← Volver</a>
    </div>
  </div>
  <script src="/JS/Juegos/Ecoica/Simon/simonB.js"></script>
</body>
</html>

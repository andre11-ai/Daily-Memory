<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Memoria Ecoica — Simon Medio</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/Simon/simonM.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
  <header class="site-header">
    <nav class="navbar">
      <h1 class="logo">Memoria Ecoica</h1>
      <div class="nav__list">
        <a href="/TiposMemoria/Mecoica" class="nav-link volver-btn">
          <i class='bx bx-left-arrow-alt'></i> Volver
        </a>
      </div>
    </nav>
  </header>

  <div class="ecoica-title-row">
    <div class="ecoica-title">Simon Medio</div>
    <div class="ecoica-bar">
      <span id="score-label">Score: 0</span>
    </div>
  </div>

  <main class="container simon-container" role="application" aria-label="Simon Medio - 5 botones">
    <svg id="board" viewBox="-260 -260 520 520"></svg>
    <div class="center">
      <div class="title">SIMON</div>
      <div class="controls">
        <div class="count" id="count">--</div>
        <button id="start" class="btn">Start</button>
        <button id="strict" class="btn toggle" title="Modo estricto">Strict</button>
        <button id="sound" class="btn toggle" title="Sonidos">Sonido</button>
      </div>
    </div>
  </main>

  <div id="modal-gameover" class="modal-gameover" style="display:none;">
    <div class="modal-content">
      <h2>¡Fin del juego!</h2>
      <p>Puntaje máximo: <span id="score-modal">0</span></p>
      <button id="restart-btn">Reiniciar</button>
      <a href="/TiposMemoria/Mecoica" class="nav-link volver-btn" style="justify-content:center; margin-top:14px;">
           <i class='bx bx-left-arrow-alt'></i> Volver
      </a>
    </div>
  </div>

  <div class="burbujas">
      @for($i = 0; $i < 10; $i++)
          <div class="burbuja"></div>
      @endfor
  </div>

  <script src="/JS/Juegos/Ecoica/Simon/simonM.js"></script>
</body>
</html>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Memoria Ecoica — Simon Medio</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('CSS/Juegos/Ecoica/Simon/simonM.css') }}">
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

  <main class="container simon-container">
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

    <div id="modal-gameover" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">MEMORIA ECOICA</div>
                    <h2 id="gov-title" class="intro-title">Simon Medio</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        5 colores para memorizar.<br>
                        Meta: <strong>15 rondas</strong>.
                    </p>
                    <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                        Puntaje final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
                    </p>
                </div>
                <div class="intro-footer">
                    <button id="action-btn" class="start-btn">¡Empezar!</button>
                    <div id="back-menu-container">
                        <a href="/TiposMemoria/Mecoica" class="modal-back-link">Volver al menú</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>

  <div class="burbujas">
    @for($i = 0; $i < 10; $i++)
        <div class="burbuja"></div>
    @endfor
  </div>

  <script src="{{ asset('JS/Juegos/Ecoica/Simon/simonM.js') }}"></script>
</body>
</html>
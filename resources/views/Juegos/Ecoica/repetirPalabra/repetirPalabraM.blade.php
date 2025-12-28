<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Repetir Palabra - Medio | Memoria Ecoica</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/repetirPalabra/repetirPalabraM.css') }}">
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

    <div class="game-top-bar">
        <div class="game-title">Repetir Palabra</div>
        <div class="stats-bar">
            <span id="score-label">Score: 0</span>
            <span id="ronda-label">Ronda: 1</span>
        </div>
    </div>

    <main>
        <div class="game-card">
            <p>Escucha la secuencia y selecciona el orden correcto.</p>
            <div id="game-area">
                <button id="start-btn">Siguiente</button>
                <div id="sound-buttons" class="hidden"></div>
                <div id="user-selection"></div>
                <p id="feedback"></p>
            </div>
        </div>
    </main>

    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Puntaje: <span id="score-modal">0</span></p>
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

    <script>
        window.nivelActual = 'medio';
    </script>
    <script src="{{ asset('/JS/Juegos/Ecoica/repetirPalabra/repetirPalabraM.js') }}"></script>
</body>
</html>

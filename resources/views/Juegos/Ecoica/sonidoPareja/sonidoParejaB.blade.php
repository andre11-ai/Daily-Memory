<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parejas Auditivas - Nivel Bajo | Memoria Ecoica</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Ecoica/sonidoPareja/sonidoParejaB.css') }}">
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
        <div class="ecoica-title">Parejas Auditivas</div>
        <div class="ecoica-bar">
            <span id="score-label">Score: 0</span>
            <span id="tiempo-label">Tiempo: 60s</span>
        </div>
    </div>

    <main>
        <div class="container-card">
            <button id="reset-game" class="restart-btn">Reiniciar Juego</button>
            <div id="parejas-juego">
                </div>
            <p id="feedback"></p>
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

    <script src="{{ asset('JS/Juegos/Ecoica/sonidoPareja/sonidoParejaB.js') }}"></script>
</body>
</html>

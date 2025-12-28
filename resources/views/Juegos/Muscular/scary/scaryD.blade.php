<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Scary Witch Typing - Difícil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Muscular/scary/style.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Muscular</h1>
            <div class="nav__list">
                <a href="/TiposMemoria/Mmuscular" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <main>
        <div class="game-top-bar">
            <div class="game-info">
                <h2>Scary Witch Typing</h2>
                <p>Teclado, agilidad y memoria muscular</p>
            </div>
            <div class="score-badge">
                Score: <span id="score">0</span>
            </div>
        </div>

        <div class="game-card">
            <button id="pauseBtn">Pausa</button>
            <canvas id="scaryCanvas"></canvas>
        </div>

        <div id="modal" class="modal-overlay" style="display:flex;">
            <div class="modal-content">
                <h1 id="scoreH1">0</h1>
                <p class="score-label">Puntos</p>
                <p class="ready-label">¿PREPARADO?</p>
                <button id="startGameBtn" class="btn-primary">Empezar</button>
                <a href="/TiposMemoria/Mmuscular" class="link-secondary">Volver al menú</a>
            </div>
        </div>
    </main>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="{{ asset('JS/Juegos/Muscular/scary/appD.js') }}"></script>
</body>
</html>

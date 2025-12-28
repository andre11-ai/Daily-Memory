<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lluvia de Letras - Medio | MemoryMaster</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

    <script src="{{ asset('JS/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM.js') }}" defer></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Muscular</h1>
            <div class="nav__list">
                <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="game-top-bar">
        <div class="game-title">
            Lluvia de Letras
        </div>
        <div class="score-vidas-bar">
            <span id="score-label"><b>Score:</b> 0</span>
            <span id="vidas-label"><b>Vidas:</b>
                <span id="vidas-dots">
                    <span class="vida-dot active"></span>
                    <span class="vida-dot active"></span>
                    <span class="vida-dot active"></span>
                </span>
            </span>
        </div>
    </div>

    <main>
        <section id="maincontainer">
            </section>
    </main>

    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Tu puntaje: <span id="score-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="nav-link volver-btn" style="justify-content:center; margin-top:14px;">
                 <i class='bx bx-left-arrow-alt'></i> Volver
            </a>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>
</body>
</html>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Velocímetro | Daily Memory</title>

    <link href="{{ asset('CSS/Juegos/Muscular/Velocimetro/velocimetroD.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" />

    <script src="{{ asset('JS/Juegos/Muscular/Velocimetro/velocimetroD.js') }}" defer></script>
</head>
<body class="bg_animate" onload="addWords()">
    <div class="burbujas">
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
    </div>

    <header class="header-bar">
        <h1 class="logo">Memoria Muscular</h1>
        <div class="header-actions">
            <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="volver-link">
                ← Volver
            </a>
        </div>
    </header>
    <div class="game-title-standalone">
       Velocímetro
    </div>

    <main>
        <section id="word-section" class="word-card">
            <div class="waiting">⌛</div>
        </section>

        <section id="type-section" class="type-section">
            <input id="typebox" name="typebox" type="text" tabindex="1" autofocus onkeyup="typingTest(event)" placeholder="Escribe aquí..." />
            <div id="timer" class="type-btn"><span>1:00</span></div>
            <button id="restart" class="type-btn" tabindex="2" onclick="restartTest()" aria-label="Reiniciar">
                <span id="restart-symbol">↻</span>
            </button>
        </section>
    </main>
</body>
</html>

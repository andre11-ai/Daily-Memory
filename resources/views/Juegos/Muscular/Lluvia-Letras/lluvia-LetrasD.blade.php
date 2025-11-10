<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MemoryMaster | Daily Memory</title>

    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasD.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" />

    <script src="{{ asset('JS/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasD.js') }}" defer></script>
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
        <h1 class="logo">Memoria Muscular</h1>
        <div class="header-actions">
            <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="volver-link">
                ← Volver
            </a>
        </div>
    </header>
    <div class="game-title-standalone">
        Lluvia de letras
    </div>

    <main>
        <section id="maincontainer">
        </section>
    </main>
</body>
</html>

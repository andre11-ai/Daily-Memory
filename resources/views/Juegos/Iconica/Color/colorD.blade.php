<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoriza el Color | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ asset('/CSS/Juegos/Iconica/Color/colorD.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>

<a href="/TiposMemoria/Miconica" class="back-button">← Volver a Juegos</a>

    <div class="game-container" id="game-container" data-level="D">

        <div class="memorize-phase" id="memorize-phase">
            <h1>Memoriza bien los colores</h1>

            <div class="memorize-grid" id="memorize-grid">
                </div>

            <button class="game-button" onclick="pressReady()">¡Listo!</button>
        </div>

        <div class="select-phase" id="select-phase">
            <h1>Selecciona los colores que aparecían</h1>

            <div class="color-grid" id="select-grid">
                </div>

            <button class="game-button" onclick="pressVerify()">Verificar</button>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('/JS/Juegos/Iconica/Color/colorD.js') }}"></script>
</body>
</html>

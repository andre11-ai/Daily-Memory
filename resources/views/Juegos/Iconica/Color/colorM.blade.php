<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoriza el Color | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CSS/Juegos/Iconica/Color/colorM.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
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
        <h1 class="logo">Memoria Iconica</h1>
        <div class="header-actions">
            <a href="/TiposMemoria/Miconica" class="volver-link">← Volver</a>
        </div>
    </header>

    <main>
        <div class="game-title-standalone">Memoriza bien los colores</div>
        <div class="game-container" id="game-container" data-level="M">
            <div class="memorize-phase" id="memorize-phase">
                <div class="memorize-grid" id="memorize-grid">
                    <!-- aquí van las imágenes o los bloques de color -->
                </div>
                <button class="game-button" onclick="pressReady()">¡Listo!</button>
            </div>
            <div class="select-phase" id="select-phase" style="display:none;">
                <div class="game-title-standalone">Selecciona los colores que aparecían</div>
                <div class="color-grid" id="select-grid">
                    <!-- tarjetas de selección de colores -->
                </div>
                <button class="game-button" onclick="pressVerify()">Verificar</button>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/JS/Juegos/Iconica/Color/colorM.js"></script>
</body>
</html>

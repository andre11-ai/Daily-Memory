<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secuencia - Difícil</title>

    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Secuencia/secuenciaD.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="burbujas">
        @for($i = 0; $i < 10; $i++) <div class="burbuja"></div> @endfor
    </div>

    <a href="/TiposMemoria/Miconica" class="back-button">← Volver a Juegos</a>

    <!-- Contenedor principal para DIFICIL -->
    <main class="game-container" id="game-container" data-level="8">

        <!-- Fase 1: Memorizar -->
        <section class="memorize-phase" id="memorize-phase">
            <h1>Memoriza bien las imágenes</h1>
            <div class="image-grid" id="memorize-grid">
                <!-- Poner las iamgenes -->
            </div>
            <button class="game-button" id="listo-btn" onclick="pressReady()">¡Listo!</button>
        </section>

        <!-- Fase 2: Recordar -->
        <section class="recall-phase" id="recall-phase">
            <h2>Presiona una imagen para acomodarla</h2>
            <div class="image-grid" id="slot-grid">
                <!-- cuadros vacios -->
            </div>
            <hr class="divider">
            <div class="image-grid" id="pool-grid">
                <!-- Imagenes generadas para seleccionar -->
            </div>
            <button class="game-button" id="verificar-btn" onclick="pressVerify()">Verificar</button>
        </section>

    </main>

    <script src="{{ asset('/JS/Juegos/Iconica/Secuencia/secuenciaD.js') }}"></script>
</body>
</html>

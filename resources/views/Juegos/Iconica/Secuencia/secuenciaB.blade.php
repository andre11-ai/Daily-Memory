<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secuencia - Fácil</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Secuencia/secuenciaB.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Fondo de burbujas -->
    <div class="burbujas">
        @for($i = 0; $i < 10; $i++) <div class="burbuja"></div> @endfor
    </div>

   <a href="/TiposMemoria/Miconica" class="back-button">← Volver a Juegos</a>


    <!-- Contenedor principal para  nivel FÁCIL -->
    <main class="game-container" id="game-container" data-level="4">

        <!-- Fase 1: Memorizar -->
        <section class="memorize-phase" id="memorize-phase">
            <h1>Memoriza bien las imágenes</h1>
            <div class="image-grid" id="memorize-grid">
                <!-- Poner 4 imagenes aleatorias-->
            </div>
            <!-- Llama a pressReady() con onclick -->
            <button class="game-button" id="listo-btn" onclick="pressReady()">¡Listo!</button>
        </section>

        <!-- Fase 2: Recordar-->
        <section class="recall-phase" id="recall-phase">
            <h2>Presiona una imagen para acomodarla</h2>

            <div class="image-grid" id="slot-grid">
                <!-- cuadros a poner imagenes-->
            </div>

            <hr class="divider">

            <!-- Piscina de imágenes (abajo) -->
            <div class="image-grid" id="pool-grid">
                <!-- iamgenes generadas para seleccionar-->
            </div>

            <!-- Llama a pressVerify() con onclick -->
            <button class="game-button" id="verificar-btn" onclick="pressVerify()">Verificar</button>
        </section>

    </main>

    <!-- JS -->
    <script src="{{ asset('/JS/Juegos/Iconica/Secuencia/secuenciaB.js') }}"></script>
</body>
</html>

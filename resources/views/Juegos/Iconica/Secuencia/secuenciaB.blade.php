<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secuencia - Fácil</title>
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Secuencia/secuenciaB.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="burbujas">
        @for($i = 0; $i < 10; $i++) <div class="burbuja"></div> @endfor
    </div>
    <header class="header-bar">
        <h1 class="logo">Memoria Iconica</h1>
        <div class="header-actions">
            <a href="/TiposMemoria/Miconica" class="volver-link">← Volver</a>
        </div>
    </header>
    <div class="game-main-title">Secuencia de Imágenes</div>

    <main class="game-container" id="game-container" data-level="4">
        <!-- Fase 1: Memorizar -->
        <section class="memorize-phase" id="memorize-phase">
            <h2 class="game-title-secuencia">Memoriza bien las imágenes</h2>
            <div class="image-grid" id="memorize-grid">
                <!-- Aquí van 4 imágenes aleatorias -->
            </div>
            <button class="game-button" id="listo-btn" onclick="pressReady()">¡Listo!</button>
        </section>
        <!-- Fase 2: Recordar/acomodar -->
        <section class="recall-phase" id="recall-phase">
            <h3 class="game-subtitle-secuencia">Presiona una imagen para acomodarla</h3>
            <div class="image-grid" id="slot-grid">
                <!-- Slots donde se acomodarán las imágenes -->
            </div>
            <hr class="divider">
            <div class="image-grid" id="pool-grid">
                <!-- Imágenes para seleccionar/mover -->
            </div>
            <button class="game-button" id="verificar-btn" onclick="pressVerify()">Verificar</button>
        </section>
    </main>
    <script src="{{ asset('/JS/Juegos/Iconica/Secuencia/secuenciaB.js') }}"></script>
    <script>
        // Esta parte asegura el flujo correcto
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("recall-phase").style.display = "none";
            document.getElementById("memorize-phase").style.display = "block";
        });
        // Este método debe mostrarse tras presionar ¡Listo!
        function pressReady() {
            document.getElementById("memorize-phase").style.display = "none";
            document.getElementById("recall-phase").style.display = "block";
            // Aquí puedes ejecutar cualquier lógica extra para inicializar la segunda fase
            if(typeof handleStartRecall === 'function') handleStartRecall();
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorama - Fácil</title>
    <!-- CSS -->
    <link rel="stylesheet" href="/CSS/Juegos/Iconica/Memorama/memoramaB.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <!-- sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Fondo burbujas -->
    <div class="burbujas">
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
        <div class="burbuja"></div><div class="burbuja"></div>
    </div>
    <!-- Header -->
    <header class="header-bar">
        <h1 class="logo">Memoria Iconica</h1>
        <div class="header-actions">
            <a href="/TiposMemoria/Miconica" class="volver-link">← Volver</a>
        </div>
    </header>
    <!-- Área principal con flexbox, tablero a la izquierda y estadísticas a la derecha -->
    <main class="main-memorama">
        <!-- Tablero y título de juego -->
        <section class="section-memorama" id="game-container" data-time="120">
            <div class="game-title-standalone game-title-memorama">Memorama</div>
            <section class="section1">
                <table>
                    <tr>
                        <td><button id="0" onclick="show(0)"></button></td>
                        <td><button id="1" onclick="show(1)"></button></td>
                        <td><button id="2" onclick="show(2)"></button></td>
                        <td><button id="3" onclick="show(3)"></button></td>
                    </tr>
                    <tr>
                        <td><button id="4" onclick="show(4)"></button></td>
                        <td><button id="5" onclick="show(5)"></button></td>
                        <td><button id="6" onclick="show(6)"></button></td>
                        <td><button id="7" onclick="show(7)"></button></td>
                    </tr>
                    <tr>
                        <td><button id="8" onclick="show(8)"></button></td>
                        <td><button id="9" onclick="show(9)"></button></td>
                        <td><button id="10" onclick="show(10)"></button></td>
                        <td><button id="11" onclick="show(11)"></button></td>
                    </tr>
                    <tr>
                        <td><button id="12" onclick="show(12)"></button></td>
                        <td><button id="13" onclick="show(13)"></button></td>
                        <td><button id="14" onclick="show(14)"></button></td>
                        <td><button id="15" onclick="show(15)"></button></td>
                    </tr>
                </table>
            </section>
        </section>
        <!-- Estadísticas a la derecha del tablero -->
        <section class="section2">
            <div class="estadisticas" id="t-restante"></div>
            <div class="estadisticas" id="Movimientos"></div>
            <div class="estadisticas" id="Aciertos"></div>
        </section>
    </main>
    <!-- JS -->
    <script src="/JS/Juegos/Iconica/Memorama/memoramaB.js"></script>
</body>
</html>

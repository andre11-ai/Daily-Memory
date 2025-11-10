<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorama - Fácil</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Memorama/memoramaD.css') }}">

    <!--sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Fondo de burbujas -->
    <div class="burbujas">
        @for($i = 0; $i < 10; $i++) <div class="burbuja"></div> @endfor
    </div>

    <!-- Botón para volver -->
    <a href="/TiposMemoria/Miconica" class="back-button">← Volver a Juegos</a>

    <!--Contenedor principal -->
    <main class="game-card" id="game-container" data-time="60">

        <!-- Aquí está el título y la tabla del juego-->
        <section class="game-board">
            <h1>MEMORAMA</h1>
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

        <section class="game-stats">
            <div class="stat-box">
                <h2 id="t-restante" class="estadisticas">Tiempo: 120 s</h2>
            </div>
            <div class="stat-box">
                <h2 id="Movimientos" class="estadisticas">Movimientos: 0</h2>
            </div>
            <div class="stat-box">
                <h2 id="Aciertos" class="estadisticas">Aciertos: 0</h2>
            </div>
        </section>
    </main>
    <!-- JS-->
    <script src="{{ asset('/JS/Juegos/Iconica/Memorama/memoramaD.js') }}"></script>
</body>
</html>

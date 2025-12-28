<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorama - Fácil</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Memorama/memoramaB.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Icónica</h1>
            <div class="nav__list">
                <a href="/TiposMemoria/Miconica" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <div class="game-title-memorama">Memorama</div>

    <main class="main-memorama">
        <section class="section-memorama" id="game-container" data-time="120">
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

        <section class="section2">
            <div class="estadisticas" id="Score">Score: 0</div>
            <div class="estadisticas" id="t-restante">Tiempo: 0</div>
            <div class="estadisticas" id="Movimientos">Movimientos: 0</div>
            <div class="estadisticas" id="Aciertos">Aciertos: 0</div>
        </section>
    </main>

    <div id="modal-gameover" class="modal-gameover" style="display:none;">
        <div class="modal-content">
            <h2>¡Fin del juego!</h2>
            <p>Score final: <span id="score-modal">0</span></p>
            <p>Movimientos: <span id="mov-modal">0</span></p>
            <button id="restart-btn">Reiniciar</button>
            <a href="/TiposMemoria/Miconica" class="nav-link volver-btn" style="justify-content:center; margin-top:14px;">
                 <i class='bx bx-left-arrow-alt'></i> Volver
            </a>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="/JS/Juegos/Iconica/Memorama/memoramaB.js"></script>
</body>
</html>

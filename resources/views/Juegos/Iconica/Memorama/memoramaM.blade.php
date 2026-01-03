<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memorama - Medio</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Juegos/Iconica/Memorama/memoramaM.css') }}">
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
        <section class="section-memorama" id="game-container">
            <section class="section1">
                <table>
                    <tr>
                        <td><button class="card-btn" id="0" onclick="show(0)"></button></td>
                        <td><button class="card-btn" id="1" onclick="show(1)"></button></td>
                        <td><button class="card-btn" id="2" onclick="show(2)"></button></td>
                        <td><button class="card-btn" id="3" onclick="show(3)"></button></td>
                    </tr>
                    <tr>
                        <td><button class="card-btn" id="4" onclick="show(4)"></button></td>
                        <td><button class="card-btn" id="5" onclick="show(5)"></button></td>
                        <td><button class="card-btn" id="6" onclick="show(6)"></button></td>
                        <td><button class="card-btn" id="7" onclick="show(7)"></button></td>
                    </tr>
                    <tr>
                        <td><button class="card-btn" id="8" onclick="show(8)"></button></td>
                        <td><button class="card-btn" id="9" onclick="show(9)"></button></td>
                        <td><button class="card-btn" id="10" onclick="show(10)"></button></td>
                        <td><button class="card-btn" id="11" onclick="show(11)"></button></td>
                    </tr>
                    <tr>
                        <td><button class="card-btn" id="12" onclick="show(12)"></button></td>
                        <td><button class="card-btn" id="13" onclick="show(13)"></button></td>
                        <td><button class="card-btn" id="14" onclick="show(14)"></button></td>
                        <td><button class="card-btn" id="15" onclick="show(15)"></button></td>
                    </tr>
                </table>
            </section>
        </section>

        <section class="section2">
            <div class="estadisticas" id="Score">Score: 0</div>
            <div class="estadisticas" id="t-restante">Tiempo: 90 s</div>
            <div class="estadisticas" id="Movimientos">Movimientos: 0</div>
            <div class="estadisticas" id="Aciertos">Aciertos: 0</div>
        </section>
    </main>

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">NIVEL MEDIO</div>
                    <h2 id="gov-title" class="intro-title">Memorama</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        El tiempo se reduce. <br>
                        Encuentra todos los pares en <strong>90 segundos</strong>.
                    </p>
                    <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                        Score final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
                    </p>
                </div>
                <div class="intro-footer">
                    <button id="action-btn" class="start-btn">¡Empezar!</button>
                    <div id="back-menu-container"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="/JS/Juegos/Iconica/Memorama/memoramaM.js"></script>
</body>
</html>

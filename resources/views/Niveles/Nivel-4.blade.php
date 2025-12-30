<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Nivel 4 | Daily Memory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/CSS/Niveles/Nivel-4.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Historia-Nivel 4</h1>
            <div class="nav__list">
                <a href="/story" class="nav-link volver-btn">
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

    <div id="modal-gameover" class="intro-overlay hidden">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div id="gov-bubble" class="speech-bubble">
                <div class="intro-header">
                    <div id="gov-eyebrow" class="intro-eyebrow">HISTORIA · NIVEL 4</div>
                    <h2 id="gov-title" class="intro-title">Memorama (Fácil)</h2>
                </div>
                <div class="intro-content">
                    <p id="gov-msg">
                        Meta: encuentra las <strong>8 parejas</strong>. <br>
                        Tiempo límite: 120 segundos.
                    </p>
                    <p id="score-container" class="hidden" style="font-size: 1.1rem; color:#333;">
                        Puntaje final: <strong id="score-modal-display" style="font-size:1.3rem;">0</strong>
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

    <script src="/JS/Niveles/Nivel-4.js"></script>
</body>
</html>
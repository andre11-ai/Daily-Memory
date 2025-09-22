<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Muscular | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/Memorias.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="bg_animate">
        <nav class="navbar">
            <h1 class="logo">Memoria Muscular</h1>
            <div class="nav__list">
                <a href="/tipomemoria" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
        <div class="subtitulo">
            <h2>Escoge un juego</h2>
        </div>
        <main class="memoria-main-menu">
            <section class="niveles-cards-grid">
                <div class="niveles-card card-scary">
                    <div class="niveles-icon"><i class='bx bx-text'></i></div>
                    <div class="niveles-content">
                        <h1>Scary Witch Typing</h1>
                        <span>Teclado, agilidad y memoria muscular.</span>
                        <div class="niveles-levels">
                            <a href="/Juegos/Muscular/scary/scary" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="/Juegos/Muscular/scary/scaryM" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="/Juegos/Muscular/scary/scaryD" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
                <div class="niveles-card card-velocimetro">
                    <div class="niveles-icon"><i class='bx bx-joystick-alt'></i></div>
                    <div class="niveles-content">
                        <h1>Velocímetro</h1>
                        <span>Velocidad y precisión con las manos.</span>
                        <div class="niveles-levels">
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
                <div class="niveles-card card-hexamano">
                    <div class="niveles-icon"><i class='bx bx-font'></i></div>
                    <div class="niveles-content">
                        <h1>Juego Hexamano</h1>
                        <span>Memoria de patrones y coordinación manual.</span>
                        <div class="niveles-levels">
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="niveles-image-section">
                <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro meditando" class="niveles-img-cerebro">
            </section>
        </main>
        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </header>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Ecoica | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/Memorias.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="bg_animate">
        <nav class="navbar">
            <h1 class="logo">Memoria Ecoica</h1>
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
                <div class="niveles-card card-repetir">
                    <div class="niveles-icon"><i class='bx bx-bold'></i></div>
                    <div class="niveles-content">
                        <h1>Repetir la palabra</h1>
                        <span>Escucha y repite palabras para entrenar tu memoria auditiva.</span>
                        <div class="niveles-levels">
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
                <div class="niveles-card card-memorama">
                    <div class="niveles-icon"><i class='bx bx-collection'></i></div>
                    <div class="niveles-content">
                        <h1>Memorama</h1>
                        <span>Encuentra pares de sonidos y palabras.</span>
                        <div class="niveles-levels">
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
                <div class="niveles-card card-codificando">
                    <div class="niveles-icon"><i class='bx bx-search'></i></div>
                    <div class="niveles-content">
                        <h1>Codificando mensajes</h1>
                        <span>Reconoce y descifra mensajes auditivos.</span>
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

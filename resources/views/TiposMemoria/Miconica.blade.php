<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Iconica | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/Memorias.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="bg_animate">
        <nav class="navbar">
            <h1 class="logo">Memoria Iconica</h1>
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
                <div class="niveles-card card-simon">
                    <div class="niveles-icon"><i class='bx bx-palette'></i></div>
                    <div class="niveles-content">
                        <h1>Simon</h1>
                        <span>Juego de colores y memoria visual.</span>
                        <div class="niveles-levels">
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
                <div class="niveles-card card-palabras">
                    <div class="niveles-icon"><i class='bx bx-sort-z-a'></i></div>
                    <div class="niveles-content">
                        <h1>Palabras Invertidas</h1>
                        <span>Memoriza y repite palabras al revés.</span>
                        <div class="niveles-levels">
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                            <a href="" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                        </div>
                    </div>
                </div>
                <div class="niveles-card card-sodoku">
                    <div class="niveles-icon"><i class='bx bx-border-all'></i></div>
                    <div class="niveles-content">
                        <h1>Sodoku</h1>
                        <span>Entrena tu memoria numérica y lógica.</span>
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

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Juegos para el estrés | Daily Memory</title>
    <link rel="stylesheet" href="{{ asset('CSS/Desestresar.css') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <header class="bg_animate">
        <nav class="navbar" role="navigation" aria-label="Barra principal">
            <h1 class="logo">Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt' aria-hidden="true"></i>
                    Volver
                </a>
            </div>
        </nav>
        <div class="burbujas" aria-hidden="true">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </header>

    <main class="main-menu desestresar-main" role="main">
        <section class="desestresar-section" aria-labelledby="titulo-estres">
            <div class="desestresar-left">
                <div class="intro-text">
                    <h2 id="titulo-estres">Juegos para el estrés</h2>
                    <p>
                        Debes estar algo estresado para llegar hasta aquí. Esta sección reúne juegos rápidos para distraerte y liberar tensión.
                    </p>
                </div>

                <div class="desestresar-grid" role="list">
                    <div class="game-card" role="listitem">
                        <a href="/Desestresar/Tetris" class="game-link" target="_blank" rel="noopener noreferrer" aria-label="Abrir Tetris en nueva pestaña">
                            <div class="card-icon"><i class='bx bx-cube' aria-hidden="true"></i></div>
                            <div class="card-title">Tetris</div>
                        </a>
                    </div>

                    <div class="game-card" role="listitem">
                        <a href="" class="game-link" target="_blank" rel="noopener noreferrer" aria-label="Abrir Conecta 4 en nueva pestaña">
                            <div class="card-icon"><i class='bx bx-radio-circle' aria-hidden="true"></i></div>
                            <div class="card-title">Conecta 4</div>
                        </a>
                    </div>

                    <div class="game-card" role="listitem">
                        <a href="" class="game-link" target="_blank" rel="noopener noreferrer" aria-label="Abrir Pinturillo en nueva pestaña">
                            <div class="card-icon"><i class='bx bx-mouse' aria-hidden="true"></i></div>
                            <div class="card-title">Pinturillo</div>
                        </a>
                    </div>

                    <div class="game-card" role="listitem">
                        <a href="/Desestresar/Galaxy-attack" class="game-link" aria-label="Abrir Galaxy Attack">
                            <div class="card-icon"><i class='bx bx-planet' aria-hidden="true"></i></div>
                            <div class="card-title">Galaxy-attack</div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="desestresar-right" aria-hidden="true">
                <div class="image-wrap">
                    <img src="{{ asset('img/pngwing.com.png') }}" alt="Ilustración de cerebro meditando" class="main-image">
                </div>
            </div>
        </section>
    </main>
</body>
</html>

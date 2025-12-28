<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Juegos para el estrés | Daily Memory</title>
    <link rel="stylesheet" href="{{ asset('CSS/Desestresar.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}" class="nav-link">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <section class="desestresar-section">
            <div class="desestresar-content">
                <div class="intro-text">
                    <h2>Juegos para el estrés</h2>
                    <p>
                        Debes estar algo estresado para llegar hasta aquí. Esta sección reúne juegos rápidos para distraerte y liberar tensión.
                    </p>
                </div>

                <div class="games-grid">
                    <div class="game-card">
                        <a href="/Desestresar/Tetris" class="game-link" target="_blank">
                            <div class="card-icon"><i class='bx bx-cube'></i></div>
                            <div class="card-title">Tetris</div>
                        </a>
                    </div>

                    <div class="game-card">
                        <a href="#" class="game-link" target="_blank"> <div class="card-icon"><i class='bx bx-radio-circle'></i></div>
                            <div class="card-title">Conecta 4</div>
                        </a>
                    </div>

                    <div class="game-card">
                        <a href="#" class="game-link" target="_blank"> <div class="card-icon"><i class='bx bx-mouse'></i></div>
                            <div class="card-title">Pinturillo</div>
                        </a>
                    </div>

                    <div class="game-card">
                        <a href="/Desestresar/Galaxy-attack" class="game-link">
                            <div class="card-icon"><i class='bx bx-planet'></i></div>
                            <div class="card-title">Galaxy Attack</div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="desestresar-image">
                <img src="{{ asset('img/pngwing.com.png') }}" alt="Ilustración cerebro zen" class="main-image">
            </div>
        </section>
    </main>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>
</body>
</html>

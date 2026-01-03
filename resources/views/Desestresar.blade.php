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
                        <a href="#" class="game-link" target="_blank">
                            <div class="card-icon"><i class='bx bx-radio-circle'></i></div>
                            <div class="card-title">Conecta 4</div>
                        </a>
                    </div>

                    <div class="game-card">
                        <a href="#" class="game-link" target="_blank">
                            <div class="card-icon"><i class='bx bx-mouse'></i></div>
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

    <button id="help-btn" class="help-btn hidden" aria-label="Ayuda sobre la sección de juegos anti-estrés">
        <i class='bx bx-question-mark'></i>
    </button>

    <div id="help-modal" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="{{ asset('img/default-user.png') }}" alt="Mascota" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">ANTI-ESTRÉS</div>
                    <h2 class="intro-title">¿Qué puedes hacer aquí?</h2>
                </div>
                <div class="intro-content">
                    <p>Elige un juego rápido para distraerte y bajar la tensión.</p>
                    <ul>
                        <li><strong>Tetris:</strong> organiza piezas y enfoca tu mente.</li>
                        <li><strong>Conecta 4:</strong> alinea fichas con estrategia.</li>
                        <li><strong>Pinturillo:</strong> dibuja y adivina con amigos.</li>
                        <li><strong>Galaxy Attack:</strong> acción arcade para soltar estrés.</li>
                    </ul>
                </div>
                <div class="intro-footer">
                    <button id="help-close" class="start-btn">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('help-modal');
        const helpBtn = document.getElementById('help-btn');
        const closeBtn = document.getElementById('help-close');

        setTimeout(() => modal.classList.add('active'), 500);

        closeBtn.addEventListener('click', () => {
            modal.classList.remove('active');
            helpBtn.classList.remove('hidden');
        });

        helpBtn.addEventListener('click', () => {
            modal.classList.add('active');
            helpBtn.classList.add('hidden');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                modal.classList.remove('active');
                helpBtn.classList.remove('hidden');
            }
        });
    });
    </script>
</body>
</html>

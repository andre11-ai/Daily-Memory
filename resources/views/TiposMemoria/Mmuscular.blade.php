<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Muscular | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/Memorias.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Muscular</h1>
            <div class="nav__list">
                <a href="/tipomemoria" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

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
                        <a href="/Juegos/Muscular/Velocimetro/velocimetroB" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                        <a href="/Juegos/Muscular/Velocimetro/velocimetroM" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                        <a href="/Juegos/Muscular/Velocimetro/velocimetroD" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                    </div>
                </div>
            </div>
            <div class="niveles-card card-hexamano">
                <div class="niveles-icon"><i class='bx bx-font'></i></div>
                <div class="niveles-content">
                    <h1>Lluvia de letras</h1>
                    <span>Mejora la velocidad y precisión al escribir.</span>
                    <div class="niveles-levels">
                        <a href="/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasB" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                        <a href="/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                        <a href="/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasD" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
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

    <button id="help-btn" class="help-btn hidden" aria-label="Ayuda sobre Memoria Muscular">
        <i class='bx bx-question-mark'></i>
    </button>

    <div id="help-modal" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="{{ asset('img/default-user.png') }}" alt="Mascota" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">MEMORIA MUSCULAR</div>
                    <h2 class="intro-title">¿Cómo funciona?</h2>
                </div>
                <div class="intro-content">
                    <p>Mejora tus reflejos y coordinación motora.</p>
                    <p><strong>Niveles de dificultad:</strong></p>
                    <ul style="list-style: none; padding-left: 0;">
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <span class="niveles-level-link" style="cursor: default;"><i class='bx bx-signal-1'></i> Fácil</span>
                            <span>Velocidad lenta, palabras cortas y más tiempo.</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <span class="niveles-level-link" style="cursor: default;"><i class='bx bx-signal-2'></i> Medio</span>
                            <span>Ritmo constante y palabras de longitud media.</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <span class="niveles-level-link" style="cursor: default;"><i class='bx bx-signal-3'></i> Difícil</span>
                            <span>Velocidad extrema, palabras largas y poco tiempo.</span>
                        </li>
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

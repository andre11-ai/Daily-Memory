<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Iconica | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/Memorias.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Memoria Icónica</h1>
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
            <div class="niveles-card card-simon">
                <div class="niveles-icon"><i class='bx bx-palette'></i></div>
                <div class="niveles-content">
                    <h1>Color</h1>
                    <span>Juego de colores y memoria visual.</span>
                    <div class="niveles-levels">
                        <a href="/Juegos/Iconica/Color/colorB" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                        <a href="/Juegos/Iconica/Color/colorM" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                        <a href="/Juegos/Iconica/Color/colorD" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                    </div>
                </div>
            </div>
            <div class="niveles-card card-palabras">
                <div class="niveles-icon"><i class='bx bx-sort-z-a'></i></div>
                <div class="niveles-content">
                    <h1>Memorama</h1>
                    <span>Encuentra pares de imágenes iguales.</span>
                    <div class="niveles-levels">
                        <a href="/Juegos/Iconica/Memorama/memoramaB" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                        <a href="/Juegos/Iconica/Memorama/memoramaM" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                        <a href="/Juegos/Iconica/Memorama/memoramaD" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
                    </div>
                </div>
            </div>
            <div class="niveles-card card-sodoku">
                <div class="niveles-icon"><i class='bx bx-border-all'></i></div>
                <div class="niveles-content">
                    <h1>Secuencia de Imagenes</h1>
                    <span>Entrena tu memoria visual con secuencias de imágenes.</span>
                    <div class="niveles-levels">
                        <a href="/Juegos/Iconica/Secuencia/secuenciaB" class="niveles-level-link"><i class='bx bx-signal-1'></i>Fácil</a>
                        <a href="/Juegos/Iconica/Secuencia/secuenciaM" class="niveles-level-link"><i class='bx bx-signal-2'></i>Medio</a>
                        <a href="/Juegos/Iconica/Secuencia/secuenciaD" class="niveles-level-link"><i class='bx bx-signal-3'></i>Difícil</a>
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

    <button id="help-btn" class="help-btn hidden" aria-label="Ayuda sobre Memoria Icónica">
        <i class='bx bx-question-mark'></i>
    </button>

    <div id="help-modal" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="{{ asset('img/default-user.png') }}" alt="Mascota" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">MEMORIA ICÓNICA</div>
                    <h2 class="intro-title">¿Cómo jugar?</h2>
                </div>
                <div class="intro-content">
                    <p>Entrena tu memoria visual con Color, Memorama y Secuencias.</p>
                    <p><strong>Niveles de dificultad:</strong></p>
                    <ul style="list-style: none; padding-left: 0;">
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <span class="niveles-level-link" style="cursor: default;"><i class='bx bx-signal-1'></i> Fácil</span>
                            <span>Pocos elementos y más tiempo para aprender.</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <span class="niveles-level-link" style="cursor: default;"><i class='bx bx-signal-2'></i> Medio</span>
                            <span>Velocidad estándar y más objetos para recordar.</span>
                        </li>
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <span class="niveles-level-link" style="cursor: default;"><i class='bx bx-signal-3'></i> Difícil</span>
                            <span>Muy rápido, secuencias largas y menos tiempo.</span>
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

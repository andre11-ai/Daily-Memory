<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Memoria | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/niveles.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
    </header>

    <main class="memoria-main-menu">
        <section class="memoria-cards-grid">
            <div class="memoria-card memoria-card-iconica" onclick="location.href='{{ url('/TiposMemoria/Miconica') }}'">
                <div class="card-icon"><i class='bx bx-image'></i></div>
                <div>
                    <div class="card-title">Memoria Icónica</div>
                    <div class="card-desc">Ejercicios para mejorar tu capacidad de recordar imágenes y estímulos visuales.</div>
                    <a href="{{ url('/TiposMemoria/Miconica') }}" class="btn-memoria">Ver niveles</a>
                </div>
            </div>

            <div class="memoria-card memoria-card-muscular" onclick="location.href='{{ url('/TiposMemoria/Mmuscular') }}'">
                <div class="card-icon"><i class='bx bx-dumbbell'></i></div>
                <div>
                    <div class="card-title">Memoria Muscular</div>
                    <div class="card-desc">Desafía tu mente y cuerpo con ejercicios y juegos que estimulan la memoria muscular.</div>
                    <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="btn-memoria">Ver niveles</a>
                </div>
            </div>

            <div class="memoria-card memoria-card-ecoica" onclick="location.href='{{ url('/TiposMemoria/Mecoica') }}'">
                <div class="card-icon"><i class='bx bx-microphone'></i></div>
                <div>
                    <div class="card-title">Memoria Ecoica</div>
                    <div class="card-desc">Entrena tu memoria para reconocer y recordar patrones de sonidos y palabras.</div>
                    <a href="{{ url('/TiposMemoria/Mecoica') }}" class="btn-memoria">Ver niveles</a>
                </div>
            </div>
        </section>

        <section class="memoria-image-section">
            <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro meditando" class="memoria-img-cerebro">
        </section>
    </main>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <button id="help-btn" class="help-btn hidden" aria-label="Ayuda">
        <i class='bx bx-question-mark'></i>
    </button>

    <div id="help-modal" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">INFORMACIÓN</div>
                    <h2 class="intro-title">Tipos de Memoria</h2>
                </div>
                <div class="intro-content">
                    <p>Selecciona una categoría para ver sus niveles:</p>
                    <ul>
                        <li><strong>Icónica:</strong> Estímulos visuales e imágenes.</li>
                        <li><strong>Muscular:</strong> Coordinación y reflejos.</li>
                        <li><strong>Ecoica:</strong> Sonidos y palabras.</li>
                    </ul>
                </div>
                <div class="intro-footer">
                    <button id="help-close" class="start-btn">¡Entendido!</button>
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Historia - Daily Memory</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/CSS/story.css') }}">
</head>
<body>

    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Historia</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}" class="nav-link">
                    <i class="bx bx-game"></i> Menú
                </a>
            </div>
        </nav>
    </header>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <div class="mascot-fixed is-left">
        <img src="/img/default-user.png" alt="Mascota Izquierda" style="width: 100%; display: block;">
    </div>

    <div class="mascot-fixed is-right">
        <img src="/img/default-user.png" alt="Mascota Derecha" style="width: 100%; display: block;">
    </div>

    <div class="story-container">
        <h2 class="story-header" style="margin-top: 2rem;">Nivel {{ $level }}</h2>

        <div class="map-wrapper">
            @for ($i = 1; $i <= 20; $i++)
                @php
                    $state = $i <= $level ? ($i == $level ? 'current-level' : 'unlocked') : 'locked';
                    $isCurrent = $i == $level;
                @endphp

                @if ($isCurrent)
                    <a href="{{ route('niveles.show', ['level' => $i]) }}"
                       class="level-node {{ $state }}">
                        {{ $i }}
                    </a>
                @else
                    <div class="level-node {{ $state }}">
                        {{ $i }}
                    </div>
                @endif
            @endfor
        </div>
    </div>

    <button id="help-btn" class="help-btn hidden" aria-label="Ayuda">
        <i class='bx bx-question-mark'></i>
    </button>

    <div id="story-help-modal" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Mascota" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">HISTORIA</div>
                    <h2 class="intro-title">¿Cómo jugar?</h2>
                </div>
                <div class="intro-content">
                    <p>Este es tu mapa de progreso en Daily Memory.</p>
                    <ul>
                        <li>Los nodos <strong>azules</strong> son niveles disponibles.</li>
                        <li>El nodo <strong>resaltado</strong> es tu nivel actual.</li>
                        <li>Completa cada nivel para desbloquear el siguiente.</li>
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
        const modal = document.getElementById('story-help-modal');
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

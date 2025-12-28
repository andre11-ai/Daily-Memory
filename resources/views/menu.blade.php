<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menu | Daily Memory</title>
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="bg_animate">
        <nav class="navbar">
            <h1 class="logo">Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/perfil') }}" class="nav-link"><i class="fas fa-user"></i> Perfil</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>

            <main class="main-menu">
                @php $user = auth()->user(); @endphp

                <div class="welcome-banner" role="status" aria-live="polite">
                    Bienvenido, <strong>{{ $user ? ($user->username ?? $user->name) : 'Usuario' }}</strong>
                </div>

                <section class="games-section games-section-grid">
                    <div class="game-card">
                        <a href="{{ url('/Desestresar') }}" class="game-link">
                            <div class="card-icon"><i class="fas fa-gamepad"></i></div>
                            <div class="card-title">Juegos para Desestresar</div>
                        </a>
                    </div>
                    <div class="game-card">
                        <a href="{{ url('/tipomemoria') }}" class="game-link">
                            <div class="card-icon"><i class="fas fa-brain"></i></div>
                            <div class="card-title">Juegos de Memoria</div>
                        </a>
                    </div>
                    <div class="game-card">
                        <a href="/chat" class="game-link" target="_blank">
                            <div class="card-icon"><i class="fas fa-comments"></i></div>
                            <div class="card-title">Chat</div>
                        </a>
                    </div>
                    <div class="game-card">
                        <a href="{{ url('/story') }}" class="game-link">
                            <div class="card-icon"><i class="fas fa-book-open"></i></div>
                            <div class="card-title">Historia</div>
                        </a>
                    </div>
                </section>
                <section class="image-section">
                    <img src="{{ asset('img/kisspng-4-pics-1-word-word-brain-thought-action-game-snoring-transparent-png-5a76bf36785379.6988479815177316384929.png') }}"
                        alt="relax" class="main-image">
                </section>
            </main>

        <footer class="footer">
            <div class="footer-container">
                <div class="footer-row">
                    <div class="footer-col logo-col">
                        <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro" class="cerebroo">
                    </div>
                    <div class="footer-col purpose-col" style="flex: 2;">
                        <h4>Propósito</h4>
                        <p>
                        Daily Memory es una plataforma web de estimulación cognitiva centrada en la memoria. Combina juegos diseñados para trabajar distintos tipos de memoria (muscular, ecoica, icónica). Su objetivo es brindar una experiencia accesible, medible y administrable para fortalecer la memoria a través de actividades lúdicas y seguimiento de resultados.                    </div>
                </div>
            </div>
        </footer>
        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </header>
</body>
</html>

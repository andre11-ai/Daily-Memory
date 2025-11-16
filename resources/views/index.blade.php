<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="{{ asset('CSS/styleIndex.css') }}" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <h1 class="logo"><i class='bx bx-brain'></i> Daily Memory</h1>

        </nav>
    </header>
    <main class="hero">
        <div class="hero-content">
            <h2 class="hero-title">¿Necesitas ayuda con tu mente?</h2>
            <p class="hero-subtitle">Entrena tu memoria de manera divertida y profesional.</p>
            <a href="/login" class="main-btn">Empezar ahora</a>
        </div>
        <div class="hero-img">
            <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro">
        </div>
        <div class="bubbles">
            @for($i = 0; $i < 10; $i++)
                <div class="bubble"></div>
            @endfor
        </div>
    </main>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Daily Memory. Todos los derechos reservados.</p>

    </footer>
</body>
</html>

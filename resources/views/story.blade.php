<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Historia - Daily Memory</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap">
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

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia</title>
    <link rel="stylesheet" href="/CSS/story.css">
</head>
<body>
    <div class="story-container">
        <h2>Historia: Nivel {{ $level }}</h2>
        <div class="map">

            @for ($i = 1; $i <= 20; $i++)
                <div class="level {{ $i <= $level ? 'unlocked' : 'locked' }}">
                    Nivel {{ $i }}
                </div>
            @endfor
        </div>
        @if ($level < 20)
            <form action="{{ route('story.advance') }}" method="POST">
                @csrf
                <button class="btn-advance">Avanzar al siguiente nivel</button>
            </form>
        @else
            <p>¡Has completado todos los niveles!</p>
        @endif
    </div>
</body>
</html>

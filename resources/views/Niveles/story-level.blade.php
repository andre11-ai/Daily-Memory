<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nivel {{ $level }} - Historia</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/CSS/Niveles/level.css') }}">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Nivel {{ $level }}</h1>
            <div class="nav__list">
                <a href="{{ route('story.index') }}" class="nav-link">
                    <i class="bx bx-left-arrow-alt"></i> Mapa
                </a>
            </div>
        </nav>
    </header>

    <main class="level-hero">
        <div class="level-card glass">
            <div class="badge">Historia · Nivel {{ $level }}</div>
            <h2>{{ $game['name'] }}</h2>
            <p class="muted">{{ $game['description'] }}</p>

            <div class="pill-row">
                <span class="pill"><i class='bx bx-dumbbell'></i> Dificultad: {{ ucfirst($game['difficulty']) }}</span>
                <span class="pill"><i class='bx bx-brain'></i> Memoria: {{ $game['type'] }}</span>
                <span class="pill"><i class='bx bx-target-lock'></i> Puntaje mínimo: {{ $game['target_score'] }}</span>
            </div>

            <div class="cta-row">
                <a class="btn-primary" href="{{ $game['url'] }}">Jugar ahora</a>
                <a class="btn-ghost" href="{{ route('story.index') }}">Volver al mapa</a>
            </div>
        </div>

        <div class="side-card glass">
            <h3>Cómo avanzar</h3>
            <ul class="goal-list">
                <li><i class='bx bx-check-circle'></i> Juega: {{ $game['name'] }}.</li>
                <li><i class='bx bx-check-circle'></i> Logra al menos {{ $game['target_score'] }} puntos.</li>
                <li><i class='bx bx-check-circle'></i> El juego debe llamar al endpoint de historia para subir de nivel.</li>
            </ul>
            <div class="reward">
                <div class="reward-title">Recompensa</div>
                <div class="reward-body">
                    <span class="reward-xp">Avanza al Nivel {{ $level + 1 }} al cumplir la meta.</span>
                    <span class="reward-tip">La dificultad aumenta conforme subes de nivel.</span>
                </div>
            </div>
            <div class="muted" style="margin-top:.6rem; font-size:.95rem;">
                Snippet para el juego (JS):
            </div>
            <pre style="background:#f6f8fa;border-radius:10px;padding:10px;overflow:auto;font-size:.9rem;">
                        fetch("{{ route('story.complete') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ level: {{ $level }}, score: TU_PUNTAJE })
                        }).then(r => r.json()).then(console.log);
            </pre>
        </div>
    </main>
</body>
</html>

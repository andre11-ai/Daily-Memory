<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Perfil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('CSS/Perfil.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>
    <header class="site-header">
        <nav class="navbar">
            <h1 class="logo">Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}" class="nav-link">
                    <i class="bx bx-game"></i> Menú
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">
                        <i class="bx bx-log-out"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <div class="burbujas" aria-hidden="true">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja" style="--i:{{$i}}"></div>
        @endfor
    </div>

    <main class="container" role="main">
        @php $user = auth()->user(); @endphp

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any() && !session('modal_open'))
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="profile-top">
            <div class="profile-left">
                <img class="profile-avatar" id="profile-avatar-img"
                    src="{{ $user && $user->profile_image ? route('user.avatar', ['path' => $user->profile_image]) : asset('img/default-user.png') }}"
                    alt="Avatar de {{ $user ? $user->username : 'usuario' }}" />
                <div class="profile-meta">
                    <div class="profile-username">{{ $user ? $user->username : 'Usuario' }}</div>
                    <div class="profile-email">{{ $user ? $user->email : 'email@ejemplo.com' }}</div>
                </div>
            </div>

            <div class="profile-actions">
                <button id="btn-edit-profile" class="btn btn-primary">Editar Perfil</button>
            </div>
        </section>

        <section id="profile-stats" class="profile-stats">
            <h3>Estadísticas Generales</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Juegos jugados</div>
                    <div class="stat-value">{{ $juegosJugados ?? 0 }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">Nivel</div>
                    <div class="stat-value" id="level-value">{{ $levelInfo['level'] ?? 1 }}</div>

                    <div class="level-progress">
                        <div id="level-progress-bar" class="level-progress-bar"
                            style="width: {{ $levelInfo['progress'] ?? 0 }}%;">
                        </div>
                    </div>
                    <div class="muted small" id="level-meta" style="margin-top:8px;">
                        {{ $levelInfo['xp_into_level'] ?? 0 }} / {{ $levelInfo['xp_for_next'] ?? 0 }} XP
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">Puntos totales</div>
                    <div class="stat-value" id="total-points">{{ $scoreGeneral ?? 0 }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Nivel Historia</div>
                    <div class="stat-value">{{ $storyLevel ?? 0 }}</div>
                </div>
            </div>

            <div class="memory-section">
                <h4>Detalle por tipo de memoria</h4>
                @if(isset($memoryTypes) && isset($memoryCounts))
                    <div class="memory-grid">
                    @foreach($memoryTypes as $mem)
                        @php
                            $displayLabel = ucfirst($mem);
                            $countsForMem = $memoryCounts[$mem] ?? [];
                            $totalForMem = $memoryTotals[$mem] ?? 0;
                        @endphp
                        <div class="memory-card">
                            <div class="memory-card-header">
                                <div class="memory-card-title">{{ $displayLabel }}</div>
                                <div class="memory-card-total">Total: {{ $totalForMem }}</div>
                            </div>
                            <div class="memory-card-body">
                                @foreach($difficulties as $d)
                                    <div class="memory-row">
                                        <span class="memory-diff">{{ ucfirst($d) }}</span>
                                        <span class="memory-count">{{ $countsForMem[$d] ?? 0 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    </div>
                @else
                    <p class="muted">No hay datos de memorias disponibles.</p>
                @endif
            </div>

            <div class="more-stats">
                <div class="scores-and-chart">
                    <div class="scores-summary">
                        <div class="scores-header">
                            <h4>Mejores puntuaciones</h4>
                        </div>
                        <div class="scores-badges">
                            @if(!empty($scoresPorJuego))
                                @foreach($scoresPorJuego as $game => $best)
                                    <span class="score-badge">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $game)) }}: <strong>{{ $best }}</strong></span>
                                @endforeach
                            @else
                                <div class="muted">Aún no has jugado partidas.</div>
                            @endif
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <h4>Progreso de puntajes</h4>
                        <div class="chart-container">
                            <canvas id="scoresChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="scores-and-chart" style="margin-top:24px;">
                     <div class="scores-summary">
                        <h4>Resumen Global</h4>
                        <h5 style="margin-top:12px">Por Memoria</h5>
                        <ul class="simple-list">
                            @foreach($memoryTotals as $mem => $tot)
                                <li>{{ ucfirst($mem) }}: <strong>{{ $tot }}</strong></li>
                            @endforeach
                        </ul>
                        <h5 style="margin-top:12px;">Por Dificultad</h5>
                        <ul class="simple-list">
                            @foreach($difficultyTotals as $diff => $tot)
                                <li>{{ ucfirst($diff) }}: <strong>{{ $tot }}</strong></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="chart-wrap">
                        <h4>Distribución</h4>
                        <p class="muted small">Izquierda: Memoria | Derecha: Dificultad</p>
                        <div style="display: flex; gap:10px; flex-wrap: wrap;">
                            <div class="chart-container half">
                                <canvas id="chartMemory"></canvas>
                            </div>
                            <div class="chart-container half">
                                <canvas id="chartDifficulty"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <button id="help-btn" class="help-btn hidden" aria-label="Ayuda sobre la página de perfil">
        <i class='bx bx-question-mark'></i>
    </button>

    <div id="help-modal" class="intro-overlay">
        <div class="intro-scene">
            <div class="mascot-container">
                <img src="{{ asset('img/default-user.png') }}" alt="Mascota" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">MI PERFIL</div>
                    <h2 class="intro-title">¿Qué ves aquí?</h2>
                </div>
                <div class="intro-content">
                    <p>Aquí puedes ver tu progreso y editar tu cuenta.</p>
                    <ul>
                        <li><strong>Estadísticas:</strong> Juegos jugados, nivel y puntajes.</li>
                        <li><strong>Gráficas:</strong> Tu rendimiento por tipo de memoria.</li>
                        <li><strong>Editar Perfil:</strong> Cambia tu nombre, correo o contraseña.</li>
                    </ul>
                </div>
                <div class="intro-footer">
                    <button id="help-close" class="start-btn">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-backdrop" class="modal-backdrop" style="display:none">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="modal-header">
                <h3 id="modal-title">Editar perfil</h3>
                <button id="modal-close" class="modal-close-btn">✕</button>
            </div>
            <form id="profile-edit-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="modal-error" class="modal-error {{ $errors->any() ? 'visible' : '' }}">
                    @if($errors->any())
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="modal-body">
                    <div class="modal-left">
                        <img class="preview-avatar" id="preview-avatar"
                            src="{{ $user && $user->profile_image ? route('user.avatar', ['path' => $user->profile_image]) : asset('/img/default-user.png') }}"
                            alt="Previsualización">
                        <label class="file-label">
                            Cambiar imagen
                            <input type="file" id="profile_image" name="profile_image" accept="image/*" onchange="previewImage(event)">
                        </label>
                    </div>

                    <div class="modal-right">
                        <div class="form-row">
                            <label for="name">Nombre</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user ? $user->name : '') }}" required>
                        </div>
                        <div class="form-row">
                            <label for="appe">Apellido</label>
                            <input id="appe" name="appe" type="text" value="{{ old('appe', $user ? $user->appe : '') }}" required>
                        </div>
                        <div class="form-row">
                            <label for="email">Correo</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user ? $user->email : '') }}" required>
                        </div>
                        <div class="form-row">
                            <label for="username">Usuario</label>
                            <input id="username" name="username" type="text" value="{{ old('username', $user ? $user->username : '') }}" required>
                        </div>

                        <div class="form-row">
                            <label for="password">Nueva contraseña <span class="muted small">(Opcional)</span></label>
                            <div class="input-icon-wrapper">
                                <input id="password" name="password" type="password" placeholder="Mínimo 8 caracteres">
                                <button type="button" class="password-toggle"><i class='bx bx-show'></i></button>
                            </div>
                        </div>
                        <div class="form-row">
                            <label for="password_confirmation">Confirmar contraseña</label>
                            <div class="input-icon-wrapper">
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repite la contraseña">
                                <button type="button" class="password-toggle"><i class='bx bx-show'></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btn-cancel" class="btn btn-ghost">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.__PERFIL_DATA__ = {
            chartLabels: @json($chartLabels ?? []),
            chartDatasets: @json($chartDatasets ?? []),
            chartMemoryDatasets: @json($chartMemoryDatasets ?? []),
            chartDifficultyDatasets: @json($chartDifficultyDatasets ?? []),
            statsUrl: "{{ route('profile.stats') }}",
            errorsAny: {{ $errors->any() ? 'true' : 'false' }},
            scoreGeneral: @json($scoreGeneral ?? 0),
            levelInfo: @json($levelInfo ?? null)
        };
    </script>
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
    <script src="{{ asset('/JS/perfil.js') }}"></script>
</body>
</html>

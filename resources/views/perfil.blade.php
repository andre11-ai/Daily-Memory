<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Perfil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="{{ asset('CSS/Perfil.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>

<body>
    <header class="site-header">
        <div class="header-inner">
            <h1 class="logo">Daily Memory</h1>
            <nav class="header-nav" role="navigation" aria-label="Navegación principal">
                <a href="{{ url('/menu') }}" class="nav-link"><i class="bx bx-game"></i> Menu</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-link logout-btn"><i class="bx bx-log-out"></i> Cerrar Sesión</button>
                </form>
            </nav>
        </div>
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

        <section class="profile-top" role="region" aria-label="Perfil usuario">
                <div class="profile-left">
                    <img class="profile-avatar" id="profile-avatar-img"
                        src="{{ $user && $user->profile_image ? route('user.avatar', ['path' => $user->profile_image]) : asset('img/default-user.png') }}"
                        alt="Foto de {{ $user ? $user->username : 'usuario' }}" />
                    <div class="profile-meta">
                        <div class="profile-username">{{ $user ? $user->username : 'Usuario' }}</div>
                        <div class="profile-email">{{ $user ? $user->email : 'email@ejemplo.com' }}</div>
                    </div>
                </div>

                <div class="profile-actions">
                    <button id="btn-edit-profile" class="btn btn-primary">Editar</button>
                </div>
        </section>

        <section id="profile-stats" class="profile-stats">
            <h3>Estadísticas</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Juegos jugados</div>
                    <div class="stat-value">{{ $juegosJugados ?? 0 }}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">Nivel</div>
                    <div class="stat-value" id="level-value">{{ $levelInfo['level'] ?? 1 }}</div>
                    <div class="level-progress" aria-hidden="false" style="margin-top:8px;">
                        <div
                            id="level-progress-bar"
                            class="level-progress-bar"
                            role="progressbar"
                            aria-valuemin="0"
                            aria-valuemax="{{ $levelInfo['xp_for_next'] ?? 100 }}"
                            aria-valuenow="{{ $levelInfo['xp_into_level'] ?? 0 }}"
                            style="width: {{ $levelInfo['progress'] ?? 0 }}%;">
                        </div>
                    </div>
                    <div class="muted small" id="level-meta" style="margin-top:8px;">
                        {{ $levelInfo['xp_into_level'] ?? 0 }} / {{ $levelInfo['xp_for_next'] ?? 0 }} pts
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-title">Puntos totales</div>
                    <div class="stat-value" id="total-points">{{ $scoreGeneral ?? 0 }}</div>
                </div>
            </div>

            <div class="memory-section" style="margin-top:18px;">
                <h4>Partidas por tipo de memoria y dificultad</h4>
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
                                <div class="memory-card-total">Total: <strong>{{ $totalForMem }}</strong></div>
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
                    <p class="simple-list">No hay datos de memorias desglosados disponibles.</p>
                @endif
            </div>

            <div class="more-stats">
                <div class="scores-and-chart">
                    <div class="scores-summary">
                        <div class="scores-header">
                            <h4>Scores por juego (mejor por partida)</h4>
                        </div>
                        <div class="scores-badges" id="scores-badges">
                            @if(!empty($scoresPorJuego))
                                @foreach($scoresPorJuego as $game => $best)
                                    <span class="score-badge" data-game="{{ $game }}">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $game)) }}: <strong>{{ $best }}</strong></span>
                                @endforeach
                            @else
                                <div class="muted">No hay registros aún.</div>
                            @endif
                        </div>
                        <div id="scores-list-full" class="scores-list-full" aria-hidden="true" style="display:none;">
                            <ul>
                                @if(!empty($scoresPorJuego))
                                    @foreach($scoresPorJuego as $game => $best)
                                        <li><strong>{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $game)) }}</strong>: {{ $best }}</li>
                                    @endforeach
                                @else
                                    <li>No hay registros aún.</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <h4>Progreso del mejor score por juego</h4>
                        <div class="chart-container">
                            <canvas id="scoresChart" aria-label="Gráfica de progreso de mejores scores"></canvas>
                        </div>
                    </div>
                </div>

                <div class="scores-and-chart" style="margin-top:12px;">
                    <div class="scores-summary">
                        <div class="scores-header">
                            <h4>Scores por juego (mejor por partida)</h4>
                        </div>
                        <div class="scores-badges" id="scores-badges-2" style="display:none;">
                            @if(!empty($scoresPorJuego))
                                @foreach($scoresPorJuego as $game => $best)
                                    <span class="score-badge">{{ \Illuminate\Support\Str::title(str_replace('-',' ',$game)) }}: <strong>{{ $best }}</strong></span>
                                @endforeach
                            @else
                                <div class="muted">No hay registros aún.</div>
                            @endif
                        </div>
                        <div id="scores-list-full-2" class="scores-list-full" style="display:none" aria-hidden="true">
                            <ul>
                                @if(!empty($scoresPorJuego))
                                    @foreach($scoresPorJuego as $game => $best)
                                        <li><strong>{{ \Illuminate\Support\Str::title(str_replace('-',' ',$game)) }}</strong>: {{ $best }}</li>
                                    @endforeach
                                @else
                                    <li>No hay registros aún.</li>
                                @endif
                            </ul>
                        </div>

                        <hr style="margin:12px 0;">
                        <h5>Totales por memoria</h5>
                        <ul class="simple-list">
                            @foreach($memoryTotals as $mem => $tot)
                                <li>{{ ucfirst($mem) }}: <strong>{{ $tot }}</strong></li>
                            @endforeach
                        </ul>
                        <h5 style="margin-top:8px;">Totales por dificultad</h5>
                        <ul class="simple-list">
                            @foreach($difficultyTotals as $diff => $tot)
                                <li>{{ ucfirst($diff) }}: <strong>{{ $tot }}</strong></li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="chart-wrap">
                        <h4>Gráficas</h4>
                        <p class="muted small">Arriba: progreso por memoria. Abajo: progreso por dificultad.</p>
                        <div class="chart-container" style="margin-bottom:14px;">
                            <canvas id="chartMemory" aria-label="Score por memoria"></canvas>
                        </div>
                        <div class="chart-container">
                            <canvas id="chartDifficulty" aria-label="Score por dificultad"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

     <div id="modal-backdrop" class="modal-backdrop" style="display:none">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="modal-header">
                <h3 id="modal-title">Editar perfil</h3>
                <button id="modal-close" class="modal-close-btn" title="Cerrar" aria-label="Cerrar">✕</button>
            </div>
            <form id="profile-edit-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div id="modal-error" class="modal-error" role="alert" aria-live="assertive" style="@if($errors->any()) display:block; @else display:none; @endif">
                    @if($errors->any())
                        <ul style="margin:0;padding-left:1.1rem;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="modal-body">
                    <div class="modal-left">
                        <img
                            class="preview-avatar"
                            id="preview-avatar"
                            src="{{ $user && $user->profile_image ? route('user.avatar', ['path' => $user->profile_image]) : asset('/img/default-user.png') }}"
                            alt="preview">
                        <label class="file-label" for="profile_image">
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

                        <div class="form-row input-with-icon">
                            <label for="password">Nueva contraseña <span style="font-weight:600;color:#6b7280;font-size:.85rem;">(Dejar en blanco para mantener la actual)</span></label>
                            <div class="input-icon-wrapper">
                                <input id="password" name="password" type="password" placeholder="Nueva contraseña (mín. 8 caracteres)">
                                <button type="button" class="password-toggle" aria-label="Mostrar contraseña" aria-pressed="false"></button>
                            </div>
                        </div>

                        <div class="form-row input-with-icon">
                            <label for="password_confirmation">Confirmar nueva contraseña</label>
                            <div class="input-icon-wrapper">
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repite la nueva contraseña">
                                <button type="button" class="password-toggle" aria-label="Mostrar contraseña" aria-pressed="false"></button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btn-cancel" class="btn btn-ghost">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-backdrop" class="modal-backdrop" style="display:none"></div>

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

    <script src="{{ asset('/JS/perfil.js') }}"></script>
</body>
</html>

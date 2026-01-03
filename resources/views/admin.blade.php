<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Panel de Administrador | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('/CSS/Admin.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header class="site-header">
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
    </header>

    <main class="container" role="main">
        <h1 style="margin-bottom:18px;">Panel de Administrador</h1>

        <div class="admin-cards" style="margin-bottom:8px;">
            <div class="admin-card">
                <div class="admin-card-title">Usuarios</div>
                <div class="admin-card-value">{{ $usersCount ?? 0 }}</div>
            </div>
            <div class="admin-card">
                <div class="admin-card-title">Juegos</div>
                <div class="admin-card-value">{{ $gamesCount ?? 0 }}</div>
            </div>
            <div class="admin-card">
                <div class="admin-card-title">Partidas totales</div>
                <div class="admin-card-value">{{ $playsTotal ?? 0 }}</div>
            </div>
            <div class="admin-card">
                <div class="admin-card-title">Fácil (total)</div>
                <div class="admin-card-value">{{ $playsPerDifficulty['facil'] ?? 0 }}</div>
            </div>
            <div class="admin-card">
                <div class="admin-card-title">Medio (total)</div>
                <div class="admin-card-value">{{ $playsPerDifficulty['medio'] ?? 0 }}</div>
            </div>
            <div class="admin-card">
                <div class="admin-card-title">Difícil (total)</div>
                <div class="admin-card-value">{{ $playsPerDifficulty['dificil'] ?? 0 }}</div>
            </div>
             <div class="admin-card">
                <div class="admin-card-title">Niveles Historia</div>
                <div class="admin-card-value">20</div>
            </div>
        </div>

        <section class="admin-table-box" aria-labelledby="users-title" style="margin-top:8px;">
            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
                <h2 id="users-title" style="font-size:1.05rem;">Usuarios registrados</h2>
                <div style="display:flex; gap:8px; align-items:center;">
                    <input id="admin-users-search" name="q" placeholder="Buscar por nombre o email" value="{{ $q ?? '' }}" class="search-input" aria-label="Buscar usuarios">
                    <button id="admin-users-search-btn" class="btn btn-small btn-ghost">Buscar</button>
                </div>
            </div>

            <div style="overflow:auto;">
                <table aria-describedby="tabla-usuarios" style="min-width:760px;">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Admin</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="admin-users-tbody">
                        @forelse($users as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->is_admin ? 'Sí' : 'No' }}</td>
                                <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
                                <td class="actions-cell">
                                    <button class="admin-action-btn edit btn small" data-id="{{ $u->id }}" data-name="{{ e($u->name) }}" data-email="{{ e($u->email) }}" data-username="{{ e($u->username ?? '') }}">Editar</button>
                                    <button class="admin-action-btn delete btn small btn-ghost" data-id="{{ $u->id }}" data-name="{{ e($u->name) }}" data-email="{{ e($u->email) }}">Borrar</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No hay usuarios registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="admin-users-pagination" style="display:flex; justify-content:flex-end; margin-top:12px; gap:8px;">
                @if($users->onFirstPage() == false)
                    <a class="btn btn-ghost" href="{{ $users->previousPageUrl() }}">« Anterior</a>
                @endif
                <div class="muted" style="align-self:center;">Página {{ $users->currentPage() }} de {{ $users->lastPage() }}</div>
                @if($users->hasMorePages())
                    <a class="btn btn-ghost" href="{{ $users->nextPageUrl() }}">Siguiente »</a>
                @endif
            </div>
        </section>

        <section style="margin-top:18px;">
            <h3 style="margin-bottom:8px;">Top juegos más jugados</h3>
            <div style="background:#fff; border-radius:10px; padding:12px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                <canvas id="top-games-chart" style="width:100%; height:240px;"></canvas>
            </div>
        </section>

        <section style="margin-top:18px;">
            <h3 style="margin-bottom:12px;">Distribuciones</h3>
            <div style="display:flex; gap:16px; flex-wrap:wrap;">
                <div style="flex:1; min-width:280px; background:#fff; border-radius:10px; padding:12px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                    <h4 style="margin-bottom:8px;">Por tipo de memoria</h4>
                    <canvas id="memory-types-pie" style="width:100%; height:220px;"></canvas>
                </div>
                <div style="flex:1; min-width:280px; background:#fff; border-radius:10px; padding:12px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                    <h4 style="margin-bottom:8px;">Por dificultad</h4>
                    <canvas id="difficulty-pie" style="width:100%; height:220px;"></canvas>
                </div>
            </div>
        </section>

        <section style="margin-top:18px;">
            <h3 style="margin-bottom:12px;">Dispersión de partidas por juego</h3>

            <div class="scatter-wrapper">
                <div class="scatter-filters">
                    <label>Memoria:
                        <select id="scatter-memory">
                            <option value="">Todas</option>
                        </select>
                    </label>
                    <label>Dificultad:
                        <select id="scatter-difficulty">
                            <option value="">Todas</option>
                        </select>
                    </label>
                    <button id="scatter-apply" type="button" class="btn btn-primary btn-apply">Aplicar</button>
                </div>
                <div style="position: relative; width: 100%; height: 300px;">
                    <canvas id="scatter-chart"></canvas>
                </div>
            </div>
        </section>
        <section id="user-level-scatter-box" style="margin-top: 18px; background: #fff; border-radius: 10px; padding: 12px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);">
            <h2 style="font-size:1.05rem; text-align:center; margin-bottom: 12px;">Dispersión de niveles por usuario</h2>
            <canvas id="user-level-scatter" style="max-height: 320px;"></canvas>
            <script type="application/json" id="scatter-data">
                {!! json_encode($scatterData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK) !!}
            </script>
        </section>
    </main>

    <div id="admin-user-modal-backdrop" class="modal-backdrop" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-edit-title">
            <div class="modal-header">
                <h3 id="modal-edit-title">Editar usuario</h3>
                <button id="admin-user-modal-close" class="modal-close-btn" aria-label="Cerrar">✕</button>
            </div>
            <div class="modal-body">
                <div class="modal-left">
                    <img id="modal-user-avatar" class="preview-avatar" src="{{ asset('img/default-user.png') }}" alt="Avatar">
                    <label class="file-label" for="modal-avatar-input">Cambiar imagen</label>
                    <input id="modal-avatar-input" type="file" accept="image/*" style="display:none">
                    <div style="font-size:.85rem;color:var(--muted);margin-top:8px;">ID: <span id="modal-user-id-display"></span></div>
                </div>

                <div class="modal-right">
                    <form id="admin-user-modal-form">
                        <input type="hidden" id="modal-user-id" />
                        <div class="form-row">
                            <label for="modal-user-name">Nombre</label>
                            <input id="modal-user-name" type="text" />
                        </div>

                        <div class="form-row">
                            <label for="modal-user-email">Email</label>
                            <input id="modal-user-email" type="email" />
                        </div>

                        <div class="form-row">
                            <label for="modal-user-username">Usuario</label>
                            <input id="modal-user-username" type="text" />
                        </div>

                        <div class="form-row">
                            <label for="modal-user-password">Nueva contraseña (dejar vacío para no cambiar)</label>
                            <div class="input-icon-wrapper">
                                <input id="modal-user-password" type="password" />
                                <button id="toggle-password-visibility" type="button" class="password-toggle" aria-label="Mostrar contraseña">👁</button>
                            </div>
                        </div>

                        <div class="form-row" style="display:flex;align-items:center;gap:8px;">
                            <input id="modal-user-isadmin" type="checkbox" />
                            <label for="modal-user-isadmin" style="margin:0;">Es administrador</label>
                        </div>

                        <div class="modal-footer">
                            <button id="admin-user-modal-cancel" type="button" class="btn btn-danger">Cancelar</button>
                            <button id="admin-user-modal-save" type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="admin-delete-modal-backdrop" class="modal-backdrop" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-delete-title">
            <div class="modal-header">
                <h3 id="modal-delete-title">Confirmar eliminación</h3>
                <button id="admin-delete-modal-close" class="modal-close-btn" aria-label="Cerrar">✕</button>
            </div>
            <div class="modal-body" style="flex-direction:column;gap:12px;">
                <p id="delete-user-desc">¿Estás seguro de eliminar este usuario? Esta acción es irreversible.</p>
                <div style="display:flex;justify-content:flex-end;gap:8px;">
                    <button id="admin-delete-cancel" type="button" class="btn btn-ghost">Cancelar</button>
                    <button id="admin-delete-confirm" type="button" class="btn btn-save" style="background:#e53e3e;">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="{{ asset('/JS/admin-users.js') }}" defer></script>
</body>
</html>

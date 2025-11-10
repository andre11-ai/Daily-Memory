<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sudoku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Estilos usando helpers de Blade -->
    <link rel="stylesheet" href="{{ asset('CSS/Juegos/Iconica/sudoku/sudokuB.css') }}">
</head>
<body>

<nav class="app-bar">
    <!-- Eliminado el botón de menú (hamburger) -->
    <div class="bar-font title">Sudoku</div>
    <!-- Dejé la app-bar limpia sin los controles movidos -->
</nav>

<!-- Eliminado el bloque de hamburger-menu y el floating + -->

<div class="body" id="sudoku">
    <!-- Nuevo panel izquierdo para controles -->
    <div class="card controls" id="left-controls">
        <div class="controls-header">Controles</div>

        <div class="controls-body">
            <button id="hint-btn" onclick="hintButtonClick()" class="btn control-btn">
                <span class="material-icons">help</span> Pista
            </button>
            <button id="pause-btn" onclick="pauseGameButtonClick()" class="btn control-btn">
                <span class="material-icons">pause</span> Pausa
            </button>
            <button id="surrender-btn" onclick="SurrenderButtonClick()" class="btn control-btn">
                <span class="material-icons">outlined_flag</span> Rendirse
            </button>
            <button id="restart-btn" onclick="restartButtonClick()" class="btn control-btn">
                <span class="material-icons">refresh</span> Reintentar
            </button>
            <button id="review-btn" onclick="checkButtonClick()" class="btn control-btn">
                <span class="material-icons">done_all</span> Revisar
            </button>
        </div>
    </div>

    <div class="card game">
        <table id="puzzle-grid">
            <!-- Garantizado 9 filas x 9 columnas -->
            <!-- Cada fila tiene 9 celdas con input -->
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
            <tr>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
                <td><input type="text" maxlength="1" inputmode="numeric" pattern="[1-9]" /></td>
            </tr>
        </table>
    </div>

    <div class="card status">
        <div id="game-number"></div>

        <div id="game-difficulty-label">Game difficulty</div>
        <div id="game-difficulty">Unknown</div>

        <ul class="game-status">
            <li>
                <div class="vertical-adjust">
                    <span class="material-icons">timer</span>
                    <span id="timer-label">Temporizador</span>
                </div>
                <div id="timer" class="timer">00:00</div>
            </li>

            <li></li>

            <li>
                <div class="vertical-adjust">
                    <span class="material-icons">grid_on</span>
                    <span>Números restantes</span>
                </div>
                <div class="remain-table">
                    <div class="remain-column">
                        <div class="remain-cell">
                            <div class="remain-cell-header">1</div>
                            <div id="remain-1" class="remain-cell-content">0</div>
                        </div>
                        <div class="remain-cell">
                            <div class="remain-cell-header">4</div>
                            <div id="remain-4" class="remain-cell-content">0</div>
                        </div>
                        <div class="remain-cell">
                            <div class="remain-cell-header">7</div>
                            <div id="remain-7" class="remain-cell-content">0</div>
                        </div>
                    </div>
                    <div class="remain-column">
                        <div class="remain-cell">
                            <div class="remain-cell-header">2</div>
                            <div id="remain-2" class="remain-cell-content">0</div>
                        </div>
                        <div class="remain-cell">
                            <div class="remain-cell-header">5</div>
                            <div id="remain-5" class="remain-cell-content">0</div>
                        </div>
                        <div class="remain-cell">
                            <div class="remain-cell-header">8</div>
                            <div id="remain-8" class="remain-cell-content">0</div>
                        </div>
                    </div>
                    <div class="remain-column">
                        <div class="remain-cell">
                            <div class="remain-cell-header">3</div>
                            <div id="remain-3" class="remain-cell-content">0</div>
                        </div>
                        <div class="remain-cell">
                            <div class="remain-cell-header">6</div>
                            <div id="remain-6" class="remain-cell-content">0</div>
                        </div>
                        <div class="remain-cell">
                            <div class="remain-cell-header">9</div>
                            <div id="remain-9" class="remain-cell-content">0</div>
                        </div>
                    </div>
                </div>
            </li>

            <li>
                <div class="vertical-adjust-custom">
                    <label for="difficulty-select" style="margin-right:8px;">Dificultad:</label>
                    <select id="difficulty-select">
                        <option value="facil">Fácil</option>
                        <option value="medio" selected>Medio</option>
                        <option value="dificil">Difícil</option>
                    </select>

                    <button id="start-btn" onclick="startGameButtonClick(document.getElementById('difficulty-select').value)" class="button bar-button more-button-custom" style="margin-left:12px;">
                        <span class="material-icons">play_arrow</span>
                        <span>Iniciar</span>
                    </button>
                </div>

                <div id="volver" style="margin-top:12px;">
                    <a href="{{ url('/niveleseconica') }}" id="vuevle" class="btn solid">Volver</a>
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="burbujas">
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
    <div class="burbuja"></div>
</div>

<!-- Scripts usando helpers de Blade -->
<script src="{{ asset('JS/Juegos/Iconica/sudoku/sudokuB.js') }}"></script>
</body>
</html>

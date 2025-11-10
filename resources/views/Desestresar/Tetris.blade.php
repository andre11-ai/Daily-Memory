<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tetris Profesional</title>
    <link rel="stylesheet" href="{{ asset('/CSS/Desestresar/Tetris.css') }}">
</head>
<body>
    <div class="tetris-bg">
        <div class="tetris-container">
            <canvas id="tetrisCanvas" width="320" height="640"></canvas>
            <div class="sidebar">
                <h2>Nivel: <span id="level">1</span></h2>
                <h2>Puntaje: <span id="score">0</span></h2>
                <h2>Siguiente pieza:</h2>
                <canvas id="nextPiece" width="80" height="80"></canvas>
            </div>
        </div>
    </div>
    <script src="{{ asset('/JS/Desestresar/Tetris.js') }}"></script>
</body>
</html>

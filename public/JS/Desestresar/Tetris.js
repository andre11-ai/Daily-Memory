//-------------------------------------------------------
// Estructura profesional de Tetris JS (listo para expandir)
//-------------------------------------------------------

// Configuración de tablero y piezas
const COLS = 10, ROWS = 20; // Tetris clásico
const BLOCK_SIZE = 32;      // px
const shapes = [
    [], // [0] Nada
    [[1,1,1,1]],                        // [1] I-tetromino
    [[1,1,0],[0,1,1]],                  // [2] S-tetromino
    [[0,1,1],[1,1,0]],                  // [3] Z-tetromino
    [[1,1],[1,1]],                      // [4] O-tetromino
    [[1,0,0],[1,1,1]],                  // [5] J-tetromino
    [[0,0,1],[1,1,1]],                  // [6] L-tetromino
    [[0,1,0],[1,1,1]],                  // [7] T-tetromino
];

// Colores avanzados para cada pieza
const colors = [
    "#00000000", // 0 - vacío
    "#38E1FF",   // I
    "#53DF83",   // S
    "#FF6B6B",   // Z
    "#F9DF5F",   // O
    "#5163C2",   // J
    "#F59C31",   // L
    "#CE47A0",   // T
];

// Canvas principal y siguiente pieza
const canvas = document.getElementById('tetrisCanvas');
const ctx = canvas.getContext('2d');
const nextCanvas = document.getElementById('nextPiece');
const nextCtx = nextCanvas.getContext('2d');

let board = [];
let score = 0, level = 1;
let current, next;

// Inicializa el tablero
function resetBoard() {
    board = Array.from({length: ROWS}, () => Array(COLS).fill(0));
}

// Crea nueva pieza
function newPiece() {
    const id = Math.floor(1 + Math.random() * 7);
    const shape = shapes[id];
    return {
        id,
        shape,
        row: 0,
        col: Math.floor(COLS / 2) - Math.ceil(shape[0].length / 2)
    };
}

// Dibuja el tablero y las piezas
function drawBoard() {
    ctx.clearRect(0,0,canvas.width,canvas.height);

    // Tablero
    for(let r=0; r<ROWS; r++)
        for(let c=0; c<COLS; c++)
            if(board[r][c])
                drawBlock(c, r, colors[board[r][c]]);

    // Pieza actual
    if(current)
        for(let r=0; r<current.shape.length; r++)
            for(let c=0; c<current.shape[r].length; c++)
                if(current.shape[r][c])
                    drawBlock(c + current.col, r + current.row, colors[current.id]);
}

// Dibuja un bloque con efectos de brillo y animación
function drawBlock(x, y, color) {
    ctx.save();
    ctx.fillStyle = color;
    ctx.strokeStyle = "#fff2";
    ctx.shadowColor = color;
    ctx.shadowBlur = 16;
    ctx.fillRect(x*BLOCK_SIZE, y*BLOCK_SIZE, BLOCK_SIZE-2, BLOCK_SIZE-2);
    ctx.strokeRect(x*BLOCK_SIZE, y*BLOCK_SIZE, BLOCK_SIZE-2, BLOCK_SIZE-2);
    ctx.restore();
}

// Dibuja la siguiente pieza
function drawNextPiece() {
    nextCtx.clearRect(0,0,nextCanvas.width,nextCanvas.height);
    if(next)
        for(let r=0; r<next.shape.length; r++)
            for(let c=0; c<next.shape[r].length; c++)
                if(next.shape[r][c])
                    drawNextBlock(c, r, colors[next.id]);
}

// Animación del bloque siguiente
function drawNextBlock(x, y, color) {
    nextCtx.save();
    nextCtx.fillStyle = color;
    nextCtx.shadowColor = color;
    nextCtx.shadowBlur = 10;
    nextCtx.fillRect(x*20, y*20, 18, 18);
    nextCtx.restore();
}

// Actualiza la UI
function updateUI() {
    document.getElementById('score').innerText = score;
    document.getElementById('level').innerText = level;
    drawBoard();
    drawNextPiece();
}

// Sistema de niveles (cada 10 líneas sube nivel, velocidades diferentes)
function updateLevel(lines) {
    level = 1 + Math.floor(lines / 10);
}

// Detecta colisiones y movimientos válidos
function isValidMove(shape, row, col) {
    for(let r=0; r<shape.length; r++)
        for(let c=0; c<shape[r].length; c++)
            if(shape[r][c]) {
                let nr = row + r, nc = col + c;
                if(nr >= ROWS || nc < 0 || nc >= COLS || (nr >= 0 && board[nr][nc]))
                    return false;
            }
    return true;
}

// Pegado de pieza al tablero
function freezePiece() {
    for(let r=0; r<current.shape.length; r++)
        for(let c=0; c<current.shape[r].length; c++)
            if(current.shape[r][c]) {
                let nr = current.row + r, nc = current.col + c;
                if(nr >= 0) board[nr][nc] = current.id;
            }
}

// Elimina líneas completas, suma puntaje y gestiona niveles
function removeLines() {
    let lines = 0;
    for(let r=ROWS-1; r>=0; r--) {
        if(board[r].every(v=>v)) {
            board.splice(r,1);
            board.unshift(Array(COLS).fill(0));
            lines++;
            r++;
        }
    }
    if(lines) {
        score += lines * 100;
        updateLevel(score/100);
    }
}

// Gira la pieza (profesional: matriz transpuesta y reverse)
function rotate(shape) {
    return shape[0].map((_,i)=>shape.map(row=>row[i])).reverse();
}

// Mueve la pieza hacia abajo
function moveDown() {
    if(isValidMove(current.shape, current.row+1, current.col)) {
        current.row++;
    } else {
        freezePiece();
        removeLines();
        current = next;
        next = newPiece();
        if(!isValidMove(current.shape, current.row, current.col)) {
            alert("Fin del juego. ¡Tu puntaje es " + score + "!");
            resetBoard();
            score = 0;
            level = 1;
        }
    }
    updateUI();
}

// Mueve lateral y rotación por teclado
document.addEventListener('keydown', e => {
    if(!current) return;
    switch(e.code) {
        case 'ArrowLeft':
            if(isValidMove(current.shape, current.row, current.col-1))
                current.col--;
            break;
        case 'ArrowRight':
            if(isValidMove(current.shape, current.row, current.col+1))
                current.col++;
            break;
        case 'ArrowDown':
            moveDown();
            break;
        case 'ArrowUp':
            let newShape = rotate(current.shape);
            if(isValidMove(newShape, current.row, current.col))
                current.shape = newShape;
            break;
        case 'Space':
            while(isValidMove(current.shape, current.row+1, current.col))
                current.row++;
            moveDown();
            break;
    }
    updateUI();
});

// Loop principal
let dropCounter = 0, dropInterval = 900;

function gameLoop(time=0) {
    dropCounter += level > 1 ? (3 + level) : 1;
    if(dropCounter >= dropInterval-level*80) {
        moveDown();
        dropCounter = 0;
    }
    requestAnimationFrame(gameLoop);
}

// Inicia el juego
function startGame() {
    resetBoard();
    current = newPiece();
    next = newPiece();
    score = 0;
    level = 1;
    updateUI();
    requestAnimationFrame(gameLoop);
}
startGame();

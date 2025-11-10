// sudoku_solver_mod.js
// Versión completa y consolidada con correcciones y mejoras:
// - ensureGrid9x9(): reconstruye la tabla si no es 9x9 (evita mostrar un tablero cortado)
// - Corrección de operadores bitwise -> lógicos en getGridInit
// - Validaciones con '0' (las filas se manejan como strings "0123...")
// - Eliminado uso de handlers inline; binding de eventos en window.onload
// - handleCellChange() gestiona validación y actualización de remaining
// - startGameButtonClick acepta 'facil'|'medio'|'dificil' o número
// - Comprobaciones DOM defensivas y logs para depuración
// - Mantiene el solver/backtracking original, adaptado a las correcciones

// ---------------------- Variables globales ----------------------
var table;                     // referencia DOM a #puzzle-grid
var gameId = 0;
var puzzle = [];
var solution = [];
var remaining = [9,9,9,9,9,9,9,9,9];
var isSolved = false;
var canSolved = true;
var timer = 0;
var pauseTimer = false;
var intervalId;
var gameOn = false;

function ensureGrid9x9() {
    table = document.getElementById("puzzle-grid");
    if (!table) {
        console.warn("ensureGrid9x9: No se encontró #puzzle-grid en el DOM.");
        return false;
    }
    var rows = table.rows.length;
    var cols = (rows > 0 && table.rows[0].cells) ? table.rows[0].cells.length : 0;
    var ok = (rows === 9);
    if (ok) {
        for (var r = 0; r < 9; r++) {
            if (!table.rows[r] || table.rows[r].cells.length !== 9) { ok = false; break; }
        }
    }
    if (ok) return true;
    table.innerHTML = "";
    for (var r = 0; r < 9; r++) {
        var tr = table.insertRow();
        for (var c = 0; c < 9; c++) {
            var td = tr.insertCell();
            var input = document.createElement("input");
            input.type = "text";
            input.maxLength = 1;
            input.setAttribute("inputmode", "numeric");
            input.setAttribute("pattern", "[1-9]");
            input.value = "";
            td.appendChild(input);
        }
    }
    return true;
}

// ---------------------- Generador / Solver ----------------------
// newGame, getGridInit, getColumns, getBlocks, replaceCharAt,
// generatePossibleNumber, solveGrid, nextStep, generatePossibleRows,
// makeItPuzzle
function newGame(difficulty) {
    var grid = getGridInit();
    var rows = grid;
    var cols = getColumns(grid);
    var blks = getBlocks(grid);
    var psNum = generatePossibleNumber(rows, cols, blks);
    solution = solveGrid(psNum, rows, true);
    timer = 0;
    for (var i = 0; i < remaining.length; i++) remaining[i] = 9;
    puzzle = makeItPuzzle(solution, difficulty);
    gameOn = (difficulty >= 0 && difficulty < 5);
    ViewPuzzle(puzzle);
    updateRemainingTable();
    if (gameOn) startTimer();
}
function getGridInit() {
    var rand = [];
    // colocar un 'seed' aleatorio para los números 1..9
    for (var i = 1; i <= 9; i++) {
        var row = Math.floor(Math.random() * 9);
        var col = Math.floor(Math.random() * 9);
        var accept = true;
        for (var j = 0; j < rand.length; j++) {
            // CORRECCIÓN: usar operadores lógicos
            if (rand[j][0] == i || (rand[j][1] == row && rand[j][2] == col)) {
                accept = false;
                i--;
                break;
            }
        }
        if (accept) rand.push([i, row, col]);
    }

    var result = [];
    for (var r = 0; r < 9; r++) result.push("000000000");

    for (var k = 0; k < rand.length; k++) {
        var digit = String(rand[k][0]);
        var r = rand[k][1];
        var c = rand[k][2];
        result[r] = replaceCharAt(result[r], c, digit);
    }
    return result;
}

function getColumns(grid) {
    var result = ["","","","","","","","",""];
    for (var i = 0; i < 9; i++) {
        for (var j = 0; j < 9; j++) result[j] += grid[i][j];
    }
    return result;
}

function getBlocks(grid) {
    var result = ["","","","","","","","",""];
    for (var i = 0; i < 9; i++) {
        for (var j = 0; j < 9; j++) {
            var idx = Math.floor(i/3)*3 + Math.floor(j/3);
            result[idx] += grid[i][j];
        }
    }
    return result;
}

function replaceCharAt(string, index, char) {
    if (index > string.length - 1) return string;
    return string.substr(0, index) + char + string.substr(index + 1);
}

function generatePossibleNumber(rows, columns, blocks) {
    var psb = [];
    for (var i = 0; i < 9; i++) {
        for (var j = 0; j < 9; j++) {
            psb[i*9 + j] = "";
            if (rows[i][j] != '0') {
                psb[i*9 + j] += rows[i][j];
            } else {
                for (var k = '1'; k <= '9'; k++) {
                    if (!rows[i].includes(k))
                        if (!columns[j].includes(k))
                            if (!blocks[Math.floor(i/3)*3 + Math.floor(j/3)].includes(k))
                                psb[i*9 + j] += k;
                }
            }
        }
    }
    return psb;
}

function solveGrid(possibleNumber, rows, startFromZero) {
    var solution = [];
    var result = nextStep(0, possibleNumber, rows, solution, startFromZero);
    if (result == 1) return solution;
}

function nextStep(level, possibleNumber, rows, solution, startFromZero) {
    var x = possibleNumber.slice(level*9, (level+1)*9);
    var y = generatePossibleRows(x);
    if (y.length == 0) return 0;

    var start = startFromZero ? 0 : y.length - 1;
    var stop = startFromZero ? y.length - 1 : 0;
    var step = startFromZero ? 1 : -1;

    for (var num = start; (startFromZero ? num <= stop : num >= stop); num += step) {
        for (var i = level+1; i < 9; i++) solution[i] = rows[i];
        solution[level] = y[num];
        if (level < 8) {
            var cols = getColumns(solution);
            var blocks = getBlocks(solution);
            var poss = generatePossibleNumber(solution, cols, blocks);
            if (nextStep(level + 1, poss, rows, solution, startFromZero) == 1) return 1;
        }
        if (level == 8) return 1;
    }
    return -1;
}

function generatePossibleRows(possibleNumber) {
    var result = [];
    function step(level, PossibleRow) {
        if (level == 9) {
            result.push(PossibleRow);
            return;
        }
        for (var i = 0; i < possibleNumber[level].length; i++) {
            var ch = possibleNumber[level][i];
            if (PossibleRow.includes(ch)) continue;
            step(level+1, PossibleRow + ch);
        }
    }
    step(0, "");
    return result;
}

function makeItPuzzle(grid, difficulty) {
    // difficulty: 0..4 valid; else treated as solved (13)
    if (!(difficulty < 5 && difficulty > -1)) difficulty = 13;
    var remainedValues = 81;
    var puzzle = grid.slice(0);

    function getSymmetry(x,y) { return [8-x, 8-y]; }

    function clearValue(grid, x, y, remainedValues) {
        var sym = getSymmetry(x,y);
        if (grid[y][x] != '0') {
            grid[y] = replaceCharAt(grid[y], x, "0");
            remainedValues--;
            if (!(x == sym[0] && y == sym[1])) {
                grid[sym[1]] = replaceCharAt(grid[sym[1]], sym[0], "0");
                remainedValues--;
            }
        }
        return remainedValues;
    }

    while (remainedValues > (difficulty * 5 + 20)) {
        var x = Math.floor(Math.random()*9);
        var y = Math.floor(Math.random()*9);
        remainedValues = clearValue(puzzle, x, y, remainedValues);
    }
    return puzzle;
}

// ---------------------- UI: view / read / validate ----------------------
function ViewPuzzle(grid) {
    if (!table) return;
    for (var i = 0; i < grid.length; i++) {
        for (var j = 0; j < grid[i].length; j++) {
            var input = table.rows[i].cells[j].getElementsByTagName('input')[0];
            if (!input) continue;
            addClassToCell(input);
            if (grid[i][j] == "0") {
                input.disabled = false;
                input.value = "";
            } else {
                input.disabled = true;
                input.value = grid[i][j];
                var val = parseInt(grid[i][j], 10);
                if (!isNaN(val)) remaining[val - 1]--;
            }
        }
    }
}

function readInput() {
    var result = [];
    if (!table) return result;
    for (var i = 0; i < 9; i++) {
        result.push("");
        for (var j = 0; j < 9; j++) {
            var input = table.rows[i].cells[j].getElementsByTagName('input')[0];
            if (!input) {
                result[i] += "0";
                continue;
            }
            if (input.value == "" || input.value.length > 1 || input.value == "0") {
                input.value = "";
                result[i] += "0";
            } else result[i] += input.value;
        }
    }
    return result;
}

function checkValue(value, row, column, block, defaultValue, currectValue) {
    if (value === "" || value === '0') return 0;
    if (!(value > '0' && value < ':')) return 4;
    if (value === defaultValue) return 0;
    if ((row.indexOf(value) != row.lastIndexOf(value)) ||
        (column.indexOf(value) != column.lastIndexOf(value)) ||
        (block.indexOf(value) != block.lastIndexOf(value))) {
        return 3;
    }
    if (value !== currectValue) return 2;
    return 1;
}

function addClassToCell(input, className) {
    input.classList.remove("right-cell");
    input.classList.remove("worning-cell");
    input.classList.remove("wrong-cell");
    if (className) input.classList.add(className);
}

function updateRemainingTable() {
    for (var i = 1; i < 10; i++) {
        var item = document.getElementById("remain-" + i);
        if (!item) continue;
        item.innerText = remaining[i-1];
        item.classList.remove("red");
        item.classList.remove("gray");
        if (remaining[i-1] === 0) item.classList.add("gray");
        else if (remaining[i-1] < 0 || remaining[i-1] > 9) item.classList.add("red");
    }
}

// ---------------------- Temporizador ----------------------
function startTimer() {
    var timerDiv = document.getElementById("timer");
    clearInterval(intervalId);
    pauseTimer = false;
    intervalId = setInterval(function() {
        if (!pauseTimer) {
            timer++;
            var min = Math.floor(timer/60);
            var sec = timer % 60;
            if (timerDiv) timerDiv.innerText = ((""+min).length < 2 ? ("0"+min) : min) + ":" + ((""+sec).length < 2 ? ("0"+sec) : sec);
        }
    }, 1000);
}

// ---------------------- Solve / Check ----------------------
function solveSudoku(changeUI) {
    puzzle = readInput();

    var columns = getColumns(puzzle);
    var blocks = getBlocks(puzzle);

    var errors = 0;
    var correct = 0;

    for (var i = 0; i < puzzle.length; i++) {
        for (var j = 0; j < puzzle[i].length; j++) {
            var result = checkValue(puzzle[i][j], puzzle[i], columns[j], blocks[Math.floor(i/3)*3 + Math.floor(j/3)], -1, -1);
            correct += ((result === 2) ? 1 : 0);
            errors += ((result > 2) ? 1 : 0);
            addClassToCell(table.rows[i].cells[j].getElementsByTagName('input')[0], result > 2 ? "wrong-cell" : undefined);
        }
    }

    if (errors > 0) {
        canSolved = false;
        return 2;
    }

    canSolved = true;
    isSolved = true;

    if (correct === 81) return 1;

    var time = Date.now();
    solution = solveGrid(generatePossibleNumber(puzzle, columns, blocks), puzzle, true);
    time = Date.now() - time;

    if (changeUI) {
        var timerEl = document.getElementById("timer");
        if (timerEl) timerEl.innerText = Math.floor(time/1000) + "." + ("000" + (time % 1000)).slice(-3);
    }

    if (solution === undefined) {
        isSolved = false;
        canSolved = false;
        return 3;
    }

    if (changeUI) {
        remaining = [0,0,0,0,0,0,0,0,0];
        updateRemainingTable();
        ViewPuzzle(solution);
    }
    return 0;
}

// ---------------------- UI acciones ----------------------
function hideMoreOptionMenu() {
    var moreOptionList = document.getElementById("more-option-list");
    if (!moreOptionList) return;
    if (moreOptionList.style.visibility == "visible") {
        moreOptionList.style.maxWidth = "40px";
        moreOptionList.style.minWidth = "40px";
        moreOptionList.style.maxHeight = "10px";
        moreOptionList.style.opacity = "0";
        setTimeout(function() { moreOptionList.style.visibility = "hidden"; }, 175);
    }
}

function handleCellChange() {
    addClassToCell(this);

    if (this.value && this.value.length > 0) {
        var ch = this.value[0];
        if (ch < '1' || ch > '9') {
            if (ch != '?' && ch != '؟') {
                this.value = "";
                alert("only numbers [1-9] and question mark '?' are allowed!!");
                this.focus();
                return;
            }
        } else {
            this.value = ch;
        }
    }

    if (this.value > 0 && this.value < 10) remaining[this.value - 1]--;
    if (typeof this.oldvalue !== 'undefined' && this.oldvalue !== "") {
        if (this.oldvalue > 0 && this.oldvalue < 10) remaining[this.oldvalue - 1]++;
    }

    canSolved = true;
    updateRemainingTable();
}

function showDialogClick(dialogId) {
    var dialog = document.getElementById(dialogId);
    var dialogBox = document.getElementById(dialogId + "-box");
    if (!dialog) return;
    if (dialogBox) dialogBox.focus();
    dialog.style.opacity = 0;
    if (dialogBox) dialogBox.style.marginTop = "-500px";
    dialog.style.display = "block";
    dialog.style.visibility = "visible";
    setTimeout(function() {
        dialog.style.opacity = 1;
        if (dialogBox) dialogBox.style.marginTop = "64px";
    }, 200);
}

function pauseGameButtonClick() {
    var icon = document.getElementById("pause-icon");
    var label = document.getElementById("pause-text");

    if (pauseTimer) {
        if (icon) icon.innerText = "pause";
        if (label) label.innerText = "Pause";
        if (table) table.style.opacity = 1;
    } else {
        if (icon) icon.innerText = "play_arrow";
        if (label) label.innerText = "Continue";
        if (table) table.style.opacity = 0;
    }
    pauseTimer = !pauseTimer;
}

function checkButtonClick() {
    if (gameOn) {
        timer += 60;
        var currentGrid = readInput();

        var columns = getColumns(currentGrid);
        var blocks = getBlocks(currentGrid);

        var errors = 0;
        var currects = 0;

        for (var i = 0; i < currentGrid.length; i++) {
            for (var j = 0; j < currentGrid[i].length; j++) {
                if (currentGrid[i][j] == "0") continue;
                var result = checkValue(currentGrid[i][j], currentGrid[i], columns[j], blocks[Math.floor(i/3)*3 + Math.floor(j/3)], puzzle[i][j], solution[i][j]);
                addClassToCell(table.rows[i].cells[j].getElementsByTagName('input')[0],
                    result === 1 ? "right-cell" : (result === 2 ? "worning-cell" : (result === 3 ? "wrong-cell" : undefined)));
                if (result === 1 || result === 0) currects++;
                else if (result === 3) errors++;
            }
        }

        if (currects === 81) {
            gameOn = false;
            pauseTimer = true;
            var gd = document.getElementById("game-difficulty");
            if (gd) gd.innerText = "Solved";
            clearInterval(intervalId);
            alert("Congrats, You solved it.");
        } else if (errors === 0 && currects === 0) {
            alert("Congrats, You solved it, but this is not the solution that I want.");
        }
    }
}

function restartButtonClick() {
    if (gameOn) {
        for (var i = 0; i < remaining.length; i++) remaining[i] = 9;
        ViewPuzzle(puzzle);
        updateRemainingTable();
        timer = -1;
    }
}

function SurrenderButtonClick() {
    if (gameOn) {
        for (var i = 0; i < remaining.length; i++) remaining[i] = 9;
        ViewPuzzle(solution);
        updateRemainingTable();
        gameOn = false;
        pauseTimer = true;
        clearInterval(intervalId);
        var gd = document.getElementById("game-difficulty");
        if (gd) gd.innerText = "Solved";
    }
}

function hintButtonClick() {
    if (!gameOn) return;

    var empty_cells_list = [];
    var wrong_cells_list = [];

    for (var i = 0; i < 9; i++) {
        for (var j = 0; j < 9; j++) {
            var input = table.rows[i].cells[j].getElementsByTagName('input')[0];
            if (!input) continue;
            if (input.value == "" || input.value.length > 1 || input.value == "0") {
                empty_cells_list.push([i,j]);
            } else {
                if (input.value !== solution[i][j]) wrong_cells_list.push([i,j]);
            }
        }
    }

    if (empty_cells_list.length === 0 && wrong_cells_list.length === 0) {
        gameOn = false;
        pauseTimer = true;
        var gd = document.getElementById("game-difficulty");
        if (gd) gd.innerText = "Solved";
        clearInterval(intervalId);
        alert("Congrats, You solved it.");
        return;
    }

    timer += 60;

    var input;
    if ((Math.random() < 0.5 && empty_cells_list.length > 0) || wrong_cells_list.length === 0) {
        var index = Math.floor(Math.random() * empty_cells_list.length);
        input = table.rows[empty_cells_list[index][0]].cells[empty_cells_list[index][1]].getElementsByTagName('input')[0];
        input.oldvalue = input.value;
        input.value = solution[empty_cells_list[index][0]][empty_cells_list[index][1]];
        if (input.value > 0 && input.value < 10) remaining[input.value - 1]--;
    } else {
        var index2 = Math.floor(Math.random() * wrong_cells_list.length);
        input = table.rows[wrong_cells_list[index2][0]].cells[wrong_cells_list[index2][1]].getElementsByTagName('input')[0];
        input.oldvalue = input.value;
        if (input.value > 0 && input.value < 10) remaining[input.value - 1]++;
        input.value = solution[wrong_cells_list[index2][0]][wrong_cells_list[index2][1]];
        if (input.value > 0 && input.value < 10) remaining[input.value - 1]--;
    }

    updateRemainingTable();

    var count = 0;
    for (var i = 0; i < 6; i++) {
        (function(i) {
            setTimeout(function() {
                if (count % 2 == 0) input.classList.add("right-cell");
                else input.classList.remove("right-cell");
                count++;
            }, i * 750);
        })(i);
    }
}

// ---------------------- Menu / Solver helpers ----------------------
function moreOptionButtonClick() {
    var moreOptionList = document.getElementById("more-option-list");
    if (!moreOptionList) return;
    setTimeout(function() {
        if (moreOptionList.style.visibility == "hidden") {
            moreOptionList.style.visibility = "visible";
            setTimeout(function() {
                moreOptionList.style.maxWidth = "160px";
                moreOptionList.style.minWidth = "160px";
                moreOptionList.style.maxHeight = "160px";
                moreOptionList.style.opacity = "1";
            }, 50);
        }
    }, 50);
}

function hideDialogButtonClick(dialogId) {
    var dialog = document.getElementById(dialogId);
    var dialogBox = document.getElementById(dialogId + "-box");
    if (!dialog) return;
    dialog.style.opacity = 0;
    if (dialogBox) dialogBox.style.marginTop = "-500px";
    setTimeout(function() { dialog.style.visibility = "collapse"; }, 500);
}

function hideHamburgerClick() {
    var div = document.getElementById("hamburger-menu");
    var menu = document.getElementById("nav-menu");
    if (menu) menu.style.left = "-256px";
    setTimeout(function() {
        if (div) {
            div.style.opacity = 0;
            div.style.visibility = "collapse";
        }
    }, 200);
}

function sudokuSolverMenuClick() {
    hideHamburgerClick();
    if (gameOn) {
        gameOn = false;
        clearInterval(intervalId);
    }

    solution = [];
    canSolved = true;
    isSolved = false;

    var grid = [];
    for (var i = 0; i < 9; i++) {
        grid.push("");
        for (var j = 0; j < 9; j++) grid[i] += "0";
    }

    ViewPuzzle(grid);
    remaining = [9,9,9,9,9,9,9,9,9];
    updateRemainingTable();

    var mo = document.getElementById("moreoption-sec"); if (mo) mo.style.display = "none";
    var pbtn = document.getElementById("pause-btn"); if (pbtn) pbtn.style.display = "none";
    var chkbtn = document.getElementById("review-btn"); if (chkbtn) chkbtn.style.display = "none";
    var isuniqueBtn = document.getElementById("isunique-btn"); if (isuniqueBtn) isuniqueBtn.style.display = "block";
    var solveBtn = document.getElementById("solve-btn"); if (solveBtn) solveBtn.style.display = "block";

    var tlabel = document.getElementById("timer-label"); if (tlabel) tlabel.innerText = "Solve time";
    var timerEl = document.getElementById("timer"); if (timerEl) timerEl.innerText = "00:00";
    var gdl = document.getElementById("game-difficulty-label"); if (gdl) gdl.innerText = "Is unique";
    var gd = document.getElementById("game-difficulty"); if (gd) gd.innerText = "Unknown";
    var gn = document.getElementById("game-number"); if (gn) gn.innerText = "#Soduko_Solver";

    var first = document.getElementById("puzzle-grid").rows[0].cells[0].getElementsByTagName('input')[0];
    if (first) first.focus();
}

function solveButtonClick() {
    if (gameOn) {
        gameOn = false;
        clearInterval(intervalId);
    }
    var result = solveSudoku(true);
    switch (result) {
        case 0: alert("SOLVED"); break;
        case 1: alert("This grid is already solved"); break;
        case 2: alert("This grid can't be solved because of an invalid input"); break;
        case 3: alert("this grid has no solution"); break;
    }
}

function isUniqueButtonClick() {
    if (!isSolved) {
        if (canSolved) solveSudoku(false);
    }
    if (!isSolved) {
        alert("Can't check this grid because it is unsolvable!");
        return;
    }
    var columns = getColumns(puzzle);
    var blocks = getBlocks(puzzle);
    var solution2 = solveGrid(generatePossibleNumber(puzzle, columns, blocks), puzzle, false);

    var unique = true;
    for (var i = 0; i < solution.length; i++) {
        for (var j = 0; j < solution[i].length; j++) {
            if (solution[i][j] !== solution2[i][j]) {
                unique = false;
                break;
            }
        }
        if (!unique) break;
    }

    var gd = document.getElementById("game-difficulty");
    if (gd) gd.innerText = unique ? "Yes" : "No";
}

// ---------------------- Inicio de partida desde UI ----------------------
function startGameButtonClick(difficultyOverride) {
    var difficulties = document.getElementsByName('difficulty');
    var difficulty = 5;

    if (typeof difficultyOverride !== 'undefined' && difficultyOverride !== null) {
        if (typeof difficultyOverride === 'string') {
            var map = { 'facil': 3, 'medio': 2, 'dificil': 1, 'easy': 3, 'medium': 2, 'hard': 1 };
            var mapped = map[difficultyOverride.toLowerCase()];
            newGame(typeof mapped !== 'undefined' ? mapped : 5);
            difficulty = (typeof mapped !== 'undefined') ? mapped : difficulty;
        } else if (typeof difficultyOverride === 'number') {
            newGame(difficultyOverride);
            difficulty = difficultyOverride;
        } else {
            newGame(5);
        }
    } else {
        for (var i = 0; i < difficulties.length; i++) {
            if (difficulties[i].checked) {
                newGame(4 - i);
                difficulty = i;
                break;
            }
        }
        if (difficulty > 4) newGame(5);
    }

    try { hideDialogButtonClick('dialog'); } catch (e) { /* ignore */ }

    gameId++;
    var gn = document.getElementById("game-number");
    if (gn) gn.innerText = "game #" + gameId;

    var mo = document.getElementById("moreoption-sec"); if (mo) mo.style.display = "block";
    var pbtn = document.getElementById("pause-btn"); if (pbtn) pbtn.style.display = "block";
    var chkbtn = document.getElementById("review-btn"); if (chkbtn) chkbtn.style.display = "inline-block";
    var isuniqueBtn = document.getElementById("isunique-btn"); if (isuniqueBtn) isuniqueBtn.style.display = "none";
    var solveBtn = document.getElementById("solve-btn"); if (solveBtn) solveBtn.style.display = "none";

    var tlabel = document.getElementById("timer-label"); if (tlabel) tlabel.innerText = "Time";
    var timerEl = document.getElementById("timer"); if (timerEl) timerEl.innerText = "00:00";
    var gdl = document.getElementById("game-difficulty-label"); if (gdl) gdl.innerText = "Game difficulty";

    var gdElem = document.getElementById("game-difficulty");
    if (gdElem) {
        if (typeof difficulty === 'number' && difficulty < 5) {
            var disp = (difficulty === 4) ? "Very easy" : (difficulty === 3 ? "Easy" : (difficulty === 2 ? "Normal" : (difficulty === 1 ? "Hard" : (difficulty === 0 ? "Expert" : "Solved"))));
            gdElem.innerText = disp;
        } else {
            if (typeof difficulty === 'string') gdElem.innerText = difficulty;
            else gdElem.innerText = "solved";
        }
    }
}

// ---------------------- Binding en carga ----------------------
window.onload = function() {
    var ok = ensureGrid9x9();
    if (!ok) {
        console.error("window.onload: No se pudo asegurar la tabla 9x9. Revisar HTML.");
        return;
    }
    table = document.getElementById("puzzle-grid");
    var rippleButtons = document.getElementsByClassName("button");
    for (var i = 0; i < rippleButtons.length; i++) {
        rippleButtons[i].onmousedown = function(e) {
            var x = e.pageX - this.offsetLeft;
            var y = e.pageY - this.offsetTop;
            var rippleItem = document.createElement("div");
            rippleItem.classList.add('ripple');
            rippleItem.setAttribute("style", "left: " + x + "px; top: " + y + "px");
            var rippleColor = this.getAttribute('ripple-color');
            if (rippleColor) rippleItem.style.background = rippleColor;
            this.appendChild(rippleItem);
            setTimeout(function() {
                if (rippleItem.parentElement) rippleItem.parentElement.removeChild(rippleItem);
            }, 1500);
        };
    }
    for (var r = 0; r < 9; r++) {
        for (var c = 0; c < 9; c++) {
            var input = table.rows[r].cells[c].getElementsByTagName('input')[0];
            if (!input) continue;
            input.addEventListener('input', handleCellChange);
            input.onfocus = function() { this.oldvalue = this.value; };
        }
    }

    // INICIA AUTOMÁTICO EN FÁCIL
    startGameButtonClick('facil');
};

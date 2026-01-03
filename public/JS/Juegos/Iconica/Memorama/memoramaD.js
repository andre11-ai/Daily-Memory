let tarjetasDestapadas = 0;
let tarjeta1 = null;
let tarjeta2 = null;
let primerResultado = null;
let segundoResultado = null;
let primeraCartaId = null;
let score = 0;
let movimientos = 0;
let aciertos = 0;
let timer = 60;
let tiempoInicial = 60;
let tiempoRegresivoId = null;
let juegoIniciado = false;
let bloqueoTablero = false;

const RUTA_IMAGENES = '/img/imageMemorama/';

let showScore = document.getElementById('Score');
let showTi = document.getElementById('t-restante');
let showMov = document.getElementById('Movimientos');
let showAc = document.getElementById('Aciertos');

const modalGO = document.getElementById('modal-gameover');
const govBubble = document.getElementById('gov-bubble');
const govEyebrow = document.getElementById('gov-eyebrow');
const govTitle = document.getElementById('gov-title');
const govMsg = document.getElementById('gov-msg');
const sContainer = document.getElementById('score-container');
const sDisplay = document.getElementById('score-modal-display');
const actionBtn = document.getElementById('action-btn');
const backCont = document.getElementById('back-menu-container');

let numbers = [1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8];

function initGame() {
    bloqueoTablero = false;
    tarjetasDestapadas = 0;
    score = 0;
    movimientos = 0;
    aciertos = 0;
    timer = tiempoInicial;
    numbers = numbers.sort(() => Math.random() - 0.5);

    showScore.innerHTML = `Score: 0`;
    showTi.innerHTML = `Tiempo: ${timer} s`;
    showMov.innerHTML = `Movimientos: 0`;
    showAc.innerHTML = `Aciertos: 0`;

    for (let i = 0; i <= 15; i++) {
        let tarjeta = document.getElementById(i);
        tarjeta.innerHTML = '';
        tarjeta.disabled = false;
    }

    showIntro();
}

function contarTiempo() {
    tiempoRegresivoId = setInterval(() => {
        timer--;
        showTi.innerHTML = `Tiempo: ${timer} s`;
        if (timer <= 0) {
            clearInterval(tiempoRegresivoId);
            bloquearTarjetas();
            mostrarModal(false);
        }
    }, 1000);
}

function bloquearTarjetas() {
    for (let i = 0; i <= 15; i++) {
        let tarjetaBloqueada = document.getElementById(i);
        if(!tarjetaBloqueada.innerHTML.includes('img')) {
            tarjetaBloqueada.innerHTML = `<img src="${RUTA_IMAGENES}${numbers[i]}.png" alt="">`;
        }
        tarjetaBloqueada.disabled = true;
    }
}

function show(id) {
    if (!juegoIniciado) return;
    if (bloqueoTablero) return;

    let tarjetaSeleccionada = document.getElementById(id);

    if (tarjetaSeleccionada.disabled) return;

    if (tarjetasDestapadas == 0) {
        tarjeta1 = tarjetaSeleccionada;
        primeraCartaId = id;
        primerResultado = numbers[id];

        tarjeta1.innerHTML = `<img src="${RUTA_IMAGENES}${primerResultado}.png" alt="">`;
        tarjeta1.disabled = true;

        tarjetasDestapadas++;

    } else if (tarjetasDestapadas == 1) {
        if (id == primeraCartaId) return;

        tarjeta2 = tarjetaSeleccionada;
        segundoResultado = numbers[id];

        tarjeta2.innerHTML = `<img src="${RUTA_IMAGENES}${segundoResultado}.png" alt="">`;
        tarjeta2.disabled = true;

        movimientos++;
        showMov.innerHTML = `Movimientos: ${movimientos}`;

        if (primerResultado == segundoResultado) {
            tarjetasDestapadas = 0;
            aciertos++;
            showAc.innerHTML = `Aciertos: ${aciertos}`;
            score++;
            showScore.innerHTML = `Score: ${score}`;

            if (aciertos == 8) {
                clearInterval(tiempoRegresivoId);
                mostrarModal(true);
            }
        } else {
            bloqueoTablero = true;

            setTimeout(() => {
                tarjeta1.innerHTML = '';
                tarjeta2.innerHTML = '';
                tarjeta1.disabled = false;
                tarjeta2.disabled = false;
                tarjetasDestapadas = 0;
                bloqueoTablero = false;
            }, 800);
        }
    }
}


function showIntro() {
    modalGO.classList.remove('hidden');
    setTimeout(() => modalGO.classList.add('active'), 10);

    govBubble.classList.remove('win-theme', 'lose-theme');
    sContainer.classList.add('hidden');

    govEyebrow.textContent = "NIVEL DIFÍCIL";
    govTitle.textContent = "Memorama";
    govMsg.innerHTML = "Desafío máximo.<br>Encuentra los pares en solo <strong>60 segundos</strong>.";

    actionBtn.textContent = "¡Empezar!";
    actionBtn.onclick = () => {
        modalGO.classList.remove('active');
        setTimeout(() => {
            modalGO.classList.add('hidden');
            juegoIniciado = true;
            contarTiempo();
        }, 300);
    };
    backCont.innerHTML = '';
}

function mostrarModal(gano) {
    modalGO.classList.remove('hidden');
    setTimeout(() => modalGO.classList.add('active'), 10);

    govBubble.classList.remove('win-theme', 'lose-theme');
    sContainer.classList.remove('hidden');
    sDisplay.textContent = score;

    backCont.innerHTML = '';
    const link = document.createElement('a');
    link.className = 'modal-back-link';
    link.innerHTML = "<i class='bx bx-left-arrow-alt'></i> Volver al Menú";
    link.href = "/TiposMemoria/Miconica";
    backCont.appendChild(link);

    if (gano) {
        govBubble.classList.add('win-theme');
        govEyebrow.textContent = "¡IMPRESIONANTE!";
        govTitle.textContent = "¡Memoria Fotográfica!";
        govMsg.innerHTML = `Increíble, has terminado en ${tiempoInicial - timer} segundos. <br>¡Eres un experto!`;
        actionBtn.textContent = "Jugar de nuevo";
    } else {
        govBubble.classList.add('lose-theme');
        govEyebrow.textContent = "¡TIEMPO AGOTADO!";
        govTitle.textContent = "¡Buen intento!";
        govMsg.innerHTML = "Se acabó el tiempo. <br>Sigue practicando para mejorar.";
        actionBtn.textContent = "Reintentar";
    }

    actionBtn.onclick = () => {
        initGame();
    };

    guardarScore(score);
}

function guardarScore(scoreVal) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    fetch('/memorama-game/score', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            score: scoreVal,
            difficulty: "hard"
        })
    }).catch(err => console.error('Error guardando score:', err));
}

document.addEventListener('DOMContentLoaded', initGame);

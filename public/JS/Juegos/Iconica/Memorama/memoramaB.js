let showTarjet = 0;
let tarjet1 = null;
let tarjet2 = null;
let primerResultado = null;
let segundoResultado = null;
let score = 0;
let movimientos = 0;
let aciertos = 0;
let time = false;
let timer = 120;
let tiempoRegresivoId = null;

const RUTA_IMAGENES = '/img/imageMemorama/';

let showScore = document.getElementById('Score');
let showTi = document.getElementById('t-restante');
let showMov = document.getElementById('Movimientos');
let showAc = document.getElementById('Aciertos');

let modalGameover = document.getElementById('modal-gameover');
let scoreModal = document.getElementById('score-modal');
let movModal = document.getElementById('mov-modal');
let restartBtn = document.getElementById('restart-btn');

let numbers = [1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8];
numbers = numbers.sort(() => Math.random() - 0.5);

document.addEventListener('DOMContentLoaded', () => {
    const gameContainer = document.getElementById('game-container');
    if (gameContainer) {
        const attTimer = parseInt(gameContainer.dataset.time);
        timer = (!isNaN(attTimer) && attTimer > 0) ? attTimer : 120;
    } else {
        timer = 120;
    }

    showScore.innerHTML = `Score: 0`;
    showTi.innerHTML = `Tiempo: ${timer} s`;
    showMov.innerHTML = `Movimientos: 0`;
    showAc.innerHTML = `Aciertos: 0`;
});

function countTime() {
    showTi.innerHTML = `Tiempo: ${timer} s`;
    tiempoRegresivoId = setInterval(() => {
        if (timer > 0) {
            timer--;
            showTi.innerHTML = `Tiempo: ${timer} s`;
        }
        if (timer <= 0) {
            showTi.innerHTML = `Tiempo: 0 s`;
            clearInterval(tiempoRegresivoId);
            blockTarjet();
            showModal('timeout');
        }
    }, 1000)
}

function blockTarjet() {
    for (let i = 0; i < numbers.length; i++) {
        let blockTarjet = document.getElementById(i);
        blockTarjet.innerHTML = `<img src="${RUTA_IMAGENES}${numbers[i]}.png" alt="">`;
        blockTarjet.disabled = true;
    }
}

function showModal(tipo) {
    modalGameover.style.display = 'flex';
    scoreModal.textContent = score;
    movModal.textContent = movimientos;
    guardarScore(score);

    if (tipo === 'timeout') {
        modalGameover.querySelector('.modal-content h2').innerText = "¡Tiempo Agotado!";
        modalGameover.querySelector('.modal-content p').innerText = "No lograste completar el juego.";
    } else {
        modalGameover.querySelector('.modal-content h2').innerText = "¡Fin del juego!";
    }
}

function show(id) {
    if (!time) {
        countTime();
        time = true;
    }
    showTarjet++;

    if (showTarjet == 1) {
        tarjet1 = document.getElementById(id);
        primerResultado = numbers[id];
        tarjet1.innerHTML = `<img src="${RUTA_IMAGENES}${primerResultado}.png" alt="">`;
        tarjet1.disabled = true;
    }
    else if (showTarjet == 2) {
        tarjet2 = document.getElementById(id);
        segundoResultado = numbers[id];
        tarjet2.innerHTML = `<img src="${RUTA_IMAGENES}${segundoResultado}.png" alt="">`;
        tarjet2.disabled = true;

        movimientos++;
        showMov.innerHTML = `Movimientos: ${movimientos}`;

        if (primerResultado == segundoResultado) {
            showTarjet = 0;

            aciertos++;
            showAc.innerHTML = `Aciertos: ${aciertos}`;
            score++;
            showScore.innerHTML = `Score: ${score}`;

            if (aciertos == 8) {
                clearInterval(tiempoRegresivoId);
                showModal();
            }
        } else {
            setTimeout(() => {
                tarjet1.innerHTML = '';
                tarjet2.innerHTML = '';
                tarjet1.disabled = false;
                tarjet2.disabled = false;
                showTarjet = 0;
            }, 800);
        }
    }
}

restartBtn && restartBtn.addEventListener('click', function() {
    location.reload();
});

function guardarScore(score) {
    fetch('/memorama-game/score', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            score: score,
            difficulty: "easy"
        })
    })
    .then(res => res.json())
    .then(data => console.log('Score guardado', data))
    .catch(err => console.error('Error guardar score:', err));
}

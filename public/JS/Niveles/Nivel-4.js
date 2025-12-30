(function() {
    const TARGET_SCORE = 8;
    const CURRENT_LEVEL = 4;
    const NEXT_LEVEL_URL = '/niveles/5';
    const MAP_URL = '/story';
    const RUTA_IMAGENES = '/img/imageMemorama/';
    const TIMER_DEFAULT = 120;

    let showTarjet = 0;
    let tarjet1 = null;
    let tarjet2 = null;
    let primerResultado = null;
    let segundoResultado = null;
    let score = 0;
    let movimientos = 0;
    let aciertos = 0;
    let time = false;
    let timer = TIMER_DEFAULT;
    let tiempoRegresivoId = null;
    let gameEnded = false;

    let numbers = [1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7, 8, 8];

    const showScore = document.getElementById('Score');
    const showTi = document.getElementById('t-restante');
    const showMov = document.getElementById('Movimientos');
    const showAc = document.getElementById('Aciertos');

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sContainer = document.getElementById('score-container');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');


    function init() {
        const gameContainer = document.getElementById('game-container');
        if (gameContainer) {
            const attTimer = parseInt(gameContainer.dataset.time);
            timer = (!isNaN(attTimer) && attTimer > 0) ? attTimer : TIMER_DEFAULT;
        } else {
            timer = TIMER_DEFAULT;
        }

        showTarjet = 0;
        tarjet1 = null;
        tarjet2 = null;
        primerResultado = null;
        segundoResultado = null;
        score = 0;
        movimientos = 0;
        aciertos = 0;
        time = false;
        gameEnded = false;

        if (tiempoRegresivoId) clearInterval(tiempoRegresivoId);

        numbers = numbers.sort(() => Math.random() - 0.5);

        showScore.innerHTML = `Score: 0`;
        showTi.innerHTML = `Tiempo: ${timer} s`;
        showMov.innerHTML = `Movimientos: 0`;
        showAc.innerHTML = `Aciertos: 0`;

        for (let i = 0; i < numbers.length; i++) {
            const btn = document.getElementById(i);
            if (btn) {
                btn.innerHTML = '';
                btn.disabled = false;
            }
        }
    }

    function startTimer() {
        showTi.innerHTML = `Tiempo: ${timer} s`;
        tiempoRegresivoId = setInterval(() => {
            if (gameEnded) return;
            timer--;
            showTi.innerHTML = `Tiempo: ${timer} s`;

            if (timer <= 0) {
                clearInterval(tiempoRegresivoId);
                blockTarjet();
                endGame(false, 'timeout');
            }
        }, 1000);
    }

    function blockTarjet() {
        for (let i = 0; i < numbers.length; i++) {
            let blockTarjet = document.getElementById(i);
            if (blockTarjet) {
                blockTarjet.innerHTML = `<img src="${RUTA_IMAGENES}${numbers[i]}.png" alt="">`;
                blockTarjet.disabled = true;
            }
        }
    }

    window.show = function(id) {
        if (gameEnded) return;

        if (!time) {
            startTimer();
            time = true;
        }

        showTarjet++;

        if (showTarjet === 1) {
            tarjet1 = document.getElementById(id);
            primerResultado = numbers[id];
            tarjet1.innerHTML = `<img src="${RUTA_IMAGENES}${primerResultado}.png" alt="">`;
            tarjet1.disabled = true;

        } else if (showTarjet === 2) {
            tarjet2 = document.getElementById(id);
            segundoResultado = numbers[id];
            tarjet2.innerHTML = `<img src="${RUTA_IMAGENES}${segundoResultado}.png" alt="">`;
            tarjet2.disabled = true;

            movimientos++;
            showMov.innerHTML = `Movimientos: ${movimientos}`;

            if (primerResultado === segundoResultado) {
                showTarjet = 0;
                aciertos++;
                showAc.innerHTML = `Aciertos: ${aciertos}`;
                score++;
                showScore.innerHTML = `Score: ${score}`;

                if (aciertos === 8) {
                    endGame(true);
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
    };

    function endGame(win = false, tipo = '') {
        if (gameEnded) return;
        gameEnded = true;
        if (tiempoRegresivoId) clearInterval(tiempoRegresivoId);
        
        mostrarGameOver(win, tipo);
        guardarScore(score);
        if (win) completeStoryLevel();
    }


    function showIntro() {
        init();
        
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden'); 

        govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
        govTitle.textContent = "Memorama (Fácil)";
        govMsg.innerHTML = `
            ¡Vamos a ejercitar la memoria visual! <br>
            Meta: Encuentra las <strong>8 parejas</strong>. <br>
            Tiempo límite: <strong>${TIMER_DEFAULT} segundos</strong>.
        `;

        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
            }, 300);
        };
        
        backCont.innerHTML = ''; 
    }

    function mostrarGameOver(win, tipo) {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.remove('hidden');
        sDisplay.textContent = score;

        if (win) {
            govBubble.classList.add('win-theme');
            govEyebrow.textContent = "¡VICTORIA!";
            govTitle.textContent = "¡Memoria Perfecta!";
            govMsg.innerHTML = "Has encontrado todas las parejas a tiempo. <br>¡Excelente trabajo!";

            actionBtn.textContent = "Siguiente Nivel";
            actionBtn.onclick = () => window.location.href = NEXT_LEVEL_URL;
        } else {
            govBubble.classList.add('lose-theme');
            if (tipo === 'timeout') {
                govEyebrow.textContent = "¡TIEMPO AGOTADO!";
                govTitle.textContent = "¡Se acabó el tiempo!";
                govMsg.innerHTML = "No logramos encontrar todas las parejas. <br>Intenta ser más rápido.";
            } else {
                govEyebrow.textContent = "¡INTÉNTALO DE NUEVO!";
                govTitle.textContent = "¡Ups!";
                govMsg.innerHTML = "Algo salió mal. Respira y vuelve a intentar.";
            }

            actionBtn.textContent = "Reintentar";
            actionBtn.onclick = () => {
                showIntro(); 
            };
        }

        if (!document.getElementById('back-link-btn')) {
            backCont.innerHTML = '';
            const link = document.createElement('a');
            link.id = 'back-link-btn';
            link.className = 'modal-back-link';
            link.innerHTML = "<i class='bx bx-map'></i> Volver al Mapa";
            link.href = MAP_URL;
            backCont.appendChild(link);
        }
    }

    function guardarScore(scoreVal) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/memorama-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: "easy" })
        }).catch(err => console.error('Error guardar score:', err));
    }

    function completeStoryLevel() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/story/complete-level', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ level: CURRENT_LEVEL, score: score })
        }).catch(err => console.error('Historia error:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        showIntro();
    });

})();
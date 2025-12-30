document.addEventListener('DOMContentLoaded', function() {
    const TARGET_SCORE = 15;        
    const CURRENT_LEVEL = 9;        
    const NEXT_LEVEL_URL = '/niveles/10'; 
    const MAP_URL = '/story';
    const SEGUNDOS = 45;           


    const palabras = ["Palma", "Golpe", "Chasquido", "Viento", "Lluvia"];
    let pares = [];
    let score = 0;
    let seleccionados = [];
    let timer = null;
    let tiempoRestante = SEGUNDOS;
    let juegoTerminado = false;

    const tiempoLabel = document.getElementById('tiempo-label');
    const scoreLabel = document.getElementById('score-label');
    const feedback = document.getElementById('feedback');
    const contenedorJuego = document.getElementById('parejas-juego');
    const resetGameBtn = document.getElementById('reset-game');

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');
    const sContainer = document.getElementById('score-container');

    function mezclar(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    function hablar(txt) {
        if ('speechSynthesis' in window) {
            let msg = new window.SpeechSynthesisUtterance(txt);
            msg.lang = "es-ES";
            window.speechSynthesis.speak(msg);
        }
    }

    function iniciarTimer() {
        if (timer) clearInterval(timer);
        tiempoRestante = SEGUNDOS;
        tiempoLabel.textContent = `Tiempo: ${tiempoRestante}s`;
        
        timer = setInterval(() => {
            if(juegoTerminado) return;
            tiempoRestante--;
            if (tiempoRestante < 0) tiempoRestante = 0;
            tiempoLabel.textContent = `Tiempo: ${tiempoRestante}s`;
            
            if (tiempoRestante <= 0) {
                if (score >= TARGET_SCORE) {
                    terminarJuego(true);
                } else {
                    terminarJuego(false);
                }
            }
        }, 1000);
    }

    function terminarJuego(win=false) {
        if (juegoTerminado) return;
        juegoTerminado = true;
        clearInterval(timer);
        
        mostrarGameOver(win);
        guardarScore(Math.max(score, 0));
        
        if (win) {
            completarHistoria(Math.max(score, 0));
        }
    }

    function crearJuego() {
        if (timer) clearInterval(timer);
        
        pares = palabras.concat(palabras);
        mezclar(pares);
        score = 0;
        seleccionados = [];
        juegoTerminado = false;
        
        contenedorJuego.innerHTML = '';
        contenedorJuego.classList.remove('bloqueado');
        feedback.innerText = '';
        scoreLabel.textContent = `Score: 0`;
        
        pares.forEach((palabra, idx) => {
            const btn = document.createElement('button');
            btn.className = 'pareja-btn';
            btn.dataset.palabra = palabra;
            btn.dataset.idx = idx;
            btn.innerText = "🔊"; 
            
            btn.onclick = function() {
                if (juegoTerminado) return;
                if (btn.classList.contains('found') || btn.classList.contains('selected')) return;
                
                btn.classList.add('selected');
                hablar(palabra);
                seleccionados.push({idx, palabra, btn});

                if (seleccionados.length === 2) {
                    contenedorJuego.classList.add('bloqueado'); 

                    if (seleccionados[0].palabra === seleccionados[1].palabra) {
                        seleccionados[0].btn.classList.add('found');
                        seleccionados[1].btn.classList.add('found');
                        seleccionados[0].btn.classList.remove('selected');
                        seleccionados[1].btn.classList.remove('selected');
                        
                        score += 5; 
                        feedback.innerText = "¡Pareja encontrada!";
                        scoreLabel.textContent = `Score: ${score}`;
                        
                        seleccionados = [];
                        contenedorJuego.classList.remove('bloqueado');

                        const encontrados = document.querySelectorAll('.pareja-btn.found').length;
                        if (encontrados === pares.length) {
                            setTimeout(() => terminarJuego(true), 500);
                        }

                    } else {
                        feedback.innerText = "No es pareja.";
                        setTimeout(function() {
                            seleccionados[0].btn.classList.remove('selected');
                            seleccionados[1].btn.classList.remove('selected');
                            seleccionados = [];
                            contenedorJuego.classList.remove('bloqueado');
                        }, 800);
                    }
                }
            };
            contenedorJuego.appendChild(btn);
        });
        
        iniciarTimer();
    }

    if(resetGameBtn) resetGameBtn.onclick = crearJuego;

    function showIntro() {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden'); 

        govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
        govTitle.textContent = "Parejas Auditivas";
        govMsg.innerHTML = `
            Pon a prueba tu oído y memoria. <br>
            Meta: consigue <strong>${TARGET_SCORE} puntos</strong>. <br>
            Tiempo límite: <strong>${SEGUNDOS} segundos</strong>.
        `;

        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                crearJuego();
            }, 300);
        };
        backCont.innerHTML = '';
    }

    function mostrarGameOver(win) {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.remove('hidden'); 
        sDisplay.textContent = score;

        if (win) {
            govBubble.classList.add('win-theme');
            govEyebrow.textContent = "¡VICTORIA!";
            govTitle.textContent = "¡Oído Perfecto!";
            govMsg.innerHTML = "Has encontrado las parejas necesarias. <br>¡Excelente trabajo!";

            actionBtn.textContent = "Siguiente Nivel";
            actionBtn.onclick = () => {
                window.location.href = NEXT_LEVEL_URL;
            };
        } else {
            govBubble.classList.add('lose-theme');
            govEyebrow.textContent = "¡SE ACABÓ EL TIEMPO!";
            govTitle.textContent = "¡Casi lo logras!";
            govMsg.innerHTML = "No alcanzaste la meta de puntos a tiempo. <br>¡Inténtalo de nuevo!";

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
        fetch('/sonido-pareja-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: 'hard' })
        }).catch(err => console.error(err));
    }

    function completarHistoria(scoreVal) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/story/complete-level', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ level: CURRENT_LEVEL, score: scoreVal })
        })
        .then(r=>r.json())
        .then(d=>{
            console.log('Nivel completado:', d);
        })
        .catch(err => console.error(err));
    }

    showIntro();
});
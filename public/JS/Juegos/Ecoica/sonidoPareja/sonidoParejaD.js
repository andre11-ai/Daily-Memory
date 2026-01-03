document.addEventListener('DOMContentLoaded', function() {
    const SEGUNDOS = 40;
    const palabras = ["Palma", "Golpe", "Chasquido", "Viento", "Lluvia", "Guitarra", "Humano", "Leon", "Peluche"];
    let pares = [];
    let score = 0;
    let seleccionados = [];
    let timer = null;
    let tiempoRestante = SEGUNDOS;
    let juegoTerminado = false;

    const modal = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const scoreContainer = document.getElementById('score-container');
    const scoreDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backMenuContainer = document.getElementById('back-menu-container');

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
        document.getElementById('tiempo-label').textContent = `Tiempo: ${tiempoRestante}s`;
        
        timer = setInterval(() => {
            tiempoRestante--;
            if (tiempoRestante < 0) tiempoRestante = 0;
            document.getElementById('tiempo-label').textContent = `Tiempo: ${tiempoRestante}s`;
            if (tiempoRestante <= 0) {
                terminarJuego(false);
            }
        }, 1000);
    }

    function crearJuego() {
        const contenedor = document.getElementById('parejas-juego');
        contenedor.innerHTML = '';
        document.getElementById('feedback').textContent = '';
        document.getElementById('reset-game').style.display = 'block';
        
        juegoTerminado = false;
        score = 0;
        document.getElementById('score-label').textContent = `Score: ${score}`;
        
        pares = palabras.concat(palabras);
        mezclar(pares);

        pares.forEach((palabra, index) => {
            const btn = document.createElement('button');
            btn.classList.add('pareja-btn');
            btn.textContent = '?';
            btn.dataset.palabra = palabra;
            btn.onclick = function() {
                if (seleccionados.length < 2 && !btn.classList.contains('found') && !btn.classList.contains('selected')) {
                    hablar(palabra);
                    btn.classList.add('selected');
                    seleccionados.push({ btn, palabra });

                    if (seleccionados.length === 2) {
                        document.querySelectorAll('.pareja-btn:not(.found)').forEach(b => b.disabled = true);
                        
                        if (seleccionados[0].palabra === seleccionados[1].palabra) {
                            score++;
                            document.getElementById('score-label').textContent = `Score: ${score}`;
                            document.getElementById('feedback').textContent = "¡Encontrado!";
                            
                            seleccionados.forEach(sel => {
                                sel.btn.classList.remove('selected');
                                sel.btn.classList.add('found');
                                sel.btn.textContent = "✔";
                            });
                        } else {
                            document.getElementById('feedback').textContent = "Incorrecto.";
                            setTimeout(() => {
                                seleccionados.forEach(sel => {
                                    sel.btn.classList.remove('selected');
                                });
                            }, 900);
                        }

                        setTimeout(() => {
                            document.querySelectorAll('.pareja-btn:not(.found)').forEach(b => b.disabled = false);
                            seleccionados = [];
                            if (score === palabras.length) {
                                terminarJuego(true);
                            }
                        }, 950);
                    }
                }
            };
            contenedor.appendChild(btn);
        });
        iniciarTimer();
    }

    function terminarJuego(win) {
        clearInterval(timer);
        juegoTerminado = true;
        guardarScore(score);
        showGameOver(win);
    }

    function showIntro() {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);

        govBubble.className = "speech-bubble";
        scoreContainer.classList.add('hidden');
        document.getElementById('reset-game').style.display = 'none';

        govEyebrow.textContent = "MEMORIA ECOICA";
        govTitle.textContent = "Nivel Difícil";
        govMsg.innerHTML = `Muchos sonidos parecidos.<br>Tienes <strong>${SEGUNDOS} segundos</strong>.`;
        actionBtn.textContent = "¡Empezar!";

        actionBtn.onclick = () => {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.classList.add('hidden');
                crearJuego();
            }, 300);
        };
        injectBackLink();
    }

    function showGameOver(win) {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);

        scoreContainer.classList.remove('hidden');
        scoreDisplay.textContent = score;

        if (win) {
            govBubble.className = "speech-bubble win-theme";
            govEyebrow.textContent = "¡GENIAL!";
            govTitle.textContent = "¡Eres un experto!";
            govMsg.innerHTML = "Has completado el nivel más difícil.";
            actionBtn.textContent = "Jugar de nuevo";
        } else {
            govBubble.className = "speech-bubble lose-theme";
            govEyebrow.textContent = "FIN DEL JUEGO";
            govTitle.textContent = "Sigue practicando";
            govMsg.innerHTML = "Se acabó el tiempo. No te rindas.";
            actionBtn.textContent = "Reintentar";
        }

        actionBtn.onclick = () => {
            showIntro();
        };
        injectBackLink();
    }

    function injectBackLink() {
        if (!document.querySelector('.modal-back-link')) {
            const backLink = document.createElement('a');
            backLink.className = 'modal-back-link';
            backLink.textContent = "Volver al menú principal";
            backLink.href = "/TiposMemoria/Mecoica";
            backMenuContainer.appendChild(backLink);
        }
    }

    document.getElementById('reset-game').onclick = () => showIntro();

    function guardarScore(score) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/sonido-pareja-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: score, difficulty: 'hard' })
        }).catch(err => console.error(err));
    }

    showIntro();
});
document.addEventListener('DOMContentLoaded', function() {
    const TARGET_SCORE = 8;
    const CURRENT_LEVEL = 5;
    const NEXT_LEVEL_URL = '/niveles/6';
    const MAP_URL = '/story';

    let sonidos = [
        {nombre: 'Gato'}, {nombre: 'Perro'}, {nombre: 'Pato'}
    ];
    let rondaActual = 1, score = 0, rondasMax = 10;
    let secuencia = [], eleccionUsuario = [];
    let jugando = false;

    const scoreLabel = document.getElementById('score-label');
    const rondaLabel = document.getElementById('ronda-label');
    const feedback = document.getElementById('feedback');
    const startBtnGame = document.getElementById('start-btn');
    const soundButtonsContainer = document.getElementById('sound-buttons');
    const userSelectionContainer = document.getElementById('user-selection');

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sContainer = document.getElementById('score-container');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');

    function hablar(texto) {
        if ('speechSynthesis' in window) {
            const msg = new window.SpeechSynthesisUtterance(texto);
            msg.lang = 'es-ES';
            window.speechSynthesis.speak(msg);
        }
    }

    function actualizarBarra() {
        scoreLabel.textContent = `Score: ${score}`;
        rondaLabel.textContent = `Ronda: ${rondaActual}`;
    }

    function siguienteRonda() {
        if (rondaActual > rondasMax) {
            finalizarJuego(score >= TARGET_SCORE);
            return;
        }

        jugando = true;
        secuencia = [];
        eleccionUsuario = [];
        feedback.innerText = 'Escuchando...';
        soundButtonsContainer.classList.add('hidden');
        userSelectionContainer.innerText = '';
        actualizarBarra();

        for (let i=0; i<rondaActual+1; i++) {
            let idx = Math.floor(Math.random() * sonidos.length);
            secuencia.push(idx);
        }

        let i = 0;
        function playNext() {
            if (i < secuencia.length) {
                hablar(sonidos[secuencia[i]].nombre);
                i++;
                setTimeout(playNext, 1100);
            } else {
                feedback.innerText = 'Tu turno:';
                mostrarBotones();
            }
        }
        playNext();
    }

    function mostrarBotones() {
        soundButtonsContainer.classList.remove('hidden');
        soundButtonsContainer.innerHTML = "";

        sonidos.forEach((s, idx) => {
            let btn = document.createElement('button');
            btn.innerText = s.nombre;
            btn.onclick = function() {
                if (!jugando) return;
                hablar(s.nombre);
                eleccionUsuario.push(idx);

                btn.classList.add('selected');
                setTimeout(()=>btn.classList.remove('selected'), 200);

                actualizarSeleccion();

                if (eleccionUsuario.length === secuencia.length) {
                    verificarRespuesta();
                }
            };
            soundButtonsContainer.appendChild(btn);
        });
    }

    function actualizarSeleccion() {
        let txt = eleccionUsuario.map(idx => sonidos[idx].nombre).join(' → ');
        userSelectionContainer.innerText = txt;
    }

    function verificarRespuesta() {
        jugando = false;
        let correcto = JSON.stringify(secuencia) === JSON.stringify(eleccionUsuario);

        if (correcto) {
            feedback.innerText = "¡Correcto! Pulsa Siguiente.";
            score += 2;

            if (score >= TARGET_SCORE) {
                finalizarJuego(true);
                return;
            }

            rondaActual++;
            actualizarBarra();
        } else {
            feedback.innerText = "Incorrecto.";
            setTimeout(()=> finalizarJuego(false), 800);
        }
    }

    function finalizarJuego(win) {
        jugando = false;
        mostrarGameOver(win);
        guardarScore(score);
        if (win) completarHistoria();
    }

    startBtnGame.onclick = function() {
        if (!jugando) siguienteRonda();
    };

    function showIntro() {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden');

        govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
        govTitle.textContent = "Repetir Palabra";
        govMsg.innerHTML = `
            Vamos a entrenar tu memoria auditiva. <br>
            Meta: consigue <strong>${TARGET_SCORE} puntos</strong>. <br>
            Escucha la secuencia y repítela en orden.
        `;

        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                rondaActual = 1;
                score = 0;
                jugando = false;
                actualizarBarra();
                feedback.innerText = 'Pulsa "Siguiente" para comenzar';
                userSelectionContainer.innerText = '';
                soundButtonsContainer.innerHTML = '';
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
            govTitle.textContent = "¡Oído Agudo!";
            govMsg.innerHTML = "Has completado las secuencias auditivas. <br>¡Excelente trabajo!";

            actionBtn.textContent = "Siguiente Nivel";
            actionBtn.onclick = () => window.location.href = NEXT_LEVEL_URL;
        } else {
            govBubble.classList.add('lose-theme');
            govEyebrow.textContent = "¡SECUENCIA ROTA!";
            govTitle.textContent = "¡Ups!";
            govMsg.innerHTML = "No coincidió la secuencia. <br>Escucha con atención e inténtalo de nuevo.";

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

    function guardarScore(scoreVal){
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/repetir-palabra-game/score',{
          method:'POST',
          headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
          body:JSON.stringify({ score:scoreVal, difficulty:"easy" })
        }).catch(err=>console.error('Error guardar score:',err));
    }

    function completarHistoria(){
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/story/complete-level',{
          method:'POST',
          headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify({ level: CURRENT_LEVEL, score: score })
        }).catch(err=>console.error('Historia error:', err));
    }

    actualizarBarra();
    showIntro();
});

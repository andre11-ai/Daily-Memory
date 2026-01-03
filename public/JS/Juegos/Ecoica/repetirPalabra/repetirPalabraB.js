document.addEventListener('DOMContentLoaded', function() {
    const TARGET_ROUND = 10; 
    let sonidos = [
        {nombre: 'Gato'},
        {nombre: 'Perro'},
        {nombre: 'Pato'}
    ];
    let rondaActual = 1, score = 0;
    let secuencia = [], eleccionUsuario = [];
    let jugando = false;

    const modal = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const scoreContainer = document.getElementById('score-container');
    const scoreDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backMenuContainer = document.getElementById('back-menu-container');
    
    const startBtnGame = document.getElementById('start-btn');
    const soundButtons = document.getElementById('sound-buttons');
    const userSelection = document.getElementById('user-selection');
    const feedback = document.getElementById('feedback');

    function hablar(texto) {
        if ('speechSynthesis' in window) {
            const msg = new window.SpeechSynthesisUtterance(texto);
            msg.lang = 'es-ES';
            window.speechSynthesis.speak(msg);
        }
    }

    function actualizarBarra() {
        document.getElementById('score-label').textContent = `Score: ${score}`;
        document.getElementById('ronda-label').textContent = `Ronda: ${rondaActual}`;
    }

    function siguienteRonda() {
        jugando = true;
        secuencia = [];
        eleccionUsuario = [];
        feedback.innerText = 'Escucha...';
        soundButtons.classList.add('hidden');
        userSelection.innerText = '';
        startBtnGame.style.display = 'none'; 
        
        actualizarBarra();

        for (let i = 0; i < rondaActual + 1; i++) {
            let idx = Math.floor(Math.random() * sonidos.length);
            secuencia.push(idx);
        }

        let i = 0;
        let interval = setInterval(() => {
            hablar(sonidos[secuencia[i]].nombre);
            i++;
            if (i >= secuencia.length) {
                clearInterval(interval);
                feedback.innerText = "¡Tu turno!";
                mostrarBotones();
            }
        }, 1500);
    }

    function mostrarBotones() {
        soundButtons.innerHTML = '';
        soundButtons.classList.remove('hidden');
        sonidos.forEach((obj, idx) => {
            let btn = document.createElement('button');
            btn.innerText = obj.nombre;
            btn.onclick = () => procesarEleccion(idx);
            soundButtons.appendChild(btn);
        });
    }

    function procesarEleccion(idx) {
        if (!jugando) return;
        eleccionUsuario.push(idx);
        actualizarSeleccionVisual();

        if (eleccionUsuario.length === secuencia.length) {
            verificarRespuesta();
        }
    }

    function actualizarSeleccionVisual() {
        let txt = eleccionUsuario.map(idx => sonidos[idx].nombre).join(' → ');
        userSelection.innerText = txt;
    }

    function verificarRespuesta() {
        jugando = false;
        let correcto = JSON.stringify(secuencia) === JSON.stringify(eleccionUsuario);
        
        if (correcto) {
            score++;
            rondaActual++;
            feedback.innerText = "¡Correcto! Preparando siguiente ronda...";
            feedback.style.color = "green";
            
            if (score >= TARGET_ROUND) {
                setTimeout(() => showGameOver(true), 1000);
            } else {
                setTimeout(siguienteRonda, 1500);
            }
        } else {
            feedback.innerText = "Incorrecto.";
            feedback.style.color = "red";
            setTimeout(() => showGameOver(false), 1000);
        }
        actualizarBarra();
    }

    function showIntro() {
        rondaActual = 1; score = 0;
        actualizarBarra();
        
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        govBubble.className = "speech-bubble";
        scoreContainer.classList.add('hidden');

        govEyebrow.textContent = "MEMORIA ECOICA";
        govTitle.textContent = "Nivel Fácil";
        govMsg.innerHTML = `Escucha y repite la secuencia de palabras.<br>Meta: <strong>${TARGET_ROUND} rondas</strong>.`;
        actionBtn.textContent = "¡Empezar!";

        actionBtn.onclick = () => {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.classList.add('hidden');
                siguienteRonda();
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
            govEyebrow.textContent = "¡MUY BIEN!";
            govTitle.textContent = "¡Nivel Completado!";
            govMsg.innerHTML = "Gran memoria auditiva.";
            actionBtn.textContent = "Jugar de nuevo";
            guardarScore(score);
        } else {
            govBubble.className = "speech-bubble lose-theme";
            govEyebrow.textContent = "FALLASTE";
            govTitle.textContent = "Secuencia incorrecta";
            govMsg.innerHTML = "Intenta concentrarte más en el sonido.";
            actionBtn.textContent = "Reintentar";
            guardarScore(score);
        }
        actionBtn.onclick = () => showIntro();
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

    function guardarScore(score) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/repetir-palabra-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: score, difficulty: "easy" })
        }).catch(err => console.error(err));
    }

    showIntro();
});
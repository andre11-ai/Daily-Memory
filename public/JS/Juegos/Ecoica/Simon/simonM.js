document.addEventListener('DOMContentLoaded', function() {
    const NUM_BUTTONS = 5;
    const SPEED_BASE = 800;
    const TARGET_ROUND = 100;

    const colors = ["#28cf5f","#f54f4f","#ffd43b","#2fb7ff","#b06cff"];
    const freqs = [329.63, 261.63, 392.00, 220.00, 440.00];

    const svg = document.getElementById('board');
    const countEl = document.getElementById('count');
    const startBtn = document.getElementById('start');
    const strictBtn = document.getElementById('strict');
    const soundToggle = document.getElementById('sound');
    const scoreLabel = document.getElementById('score-label');

    const modal = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const scoreContainer = document.getElementById('score-container');
    const scoreDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backMenuContainer = document.getElementById('back-menu-container');

    let audioCtx = null;
    let soundEnabled = true;
    let sequence = [];
    let playerPos = 0;
    let playing = false; 
    let strictMode = false;
    let unlocked = false; 
    let score = 0;

    function ensureAudio(){
        if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }

    function playTone(freq, duration=300){
        if(!soundEnabled) return;
        ensureAudio();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
        gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.00001, audioCtx.currentTime + duration/1000);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + duration/1000);
    }

    function buildBoard(){
        svg.innerHTML = '';
        const radius = 240;
        const thickness = 90;
        for(let i=0; i<NUM_BUTTONS; i++){
            const startAngle = (i * 360 / NUM_BUTTONS) - 90; 
            const endAngle = ((i+1) * 360 / NUM_BUTTONS) - 90;
            
            const x1 = Math.cos(startAngle * Math.PI/180) * radius;
            const y1 = Math.sin(startAngle * Math.PI/180) * radius;
            const x2 = Math.cos(endAngle * Math.PI/180) * radius;
            const y2 = Math.sin(endAngle * Math.PI/180) * radius;
            const x3 = Math.cos(endAngle * Math.PI/180) * (radius - thickness);
            const y3 = Math.sin(endAngle * Math.PI/180) * (radius - thickness);
            const x4 = Math.cos(startAngle * Math.PI/180) * (radius - thickness);
            const y4 = Math.sin(startAngle * Math.PI/180) * (radius - thickness);

            const d = `M ${x1} ${y1} A ${radius} ${radius} 0 0 1 ${x2} ${y2} L ${x3} ${y3} A ${radius-thickness} ${radius-thickness} 0 0 0 ${x4} ${y4} Z`;
            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("d", d);
            path.setAttribute("class", "segment");
            path.style.fill = colors[i];
            path.style.stroke = "#181d25";
            path.style.strokeWidth = "8";
            path.addEventListener('pointerdown', (e) => { e.preventDefault(); handlePlayerInput(i); });
            svg.appendChild(path);
        }
    }

    function activate(idx){
        const segs = document.querySelectorAll('.segment');
        segs[idx].classList.add('active');
        playTone(freqs[idx]);
        setTimeout(() => segs[idx].classList.remove('active'), 250);
    }

    function playSequence(){
        if(playing) return;
        playing = true;
        unlocked = false;
        countEl.textContent = sequence.length;
        scoreLabel.textContent = `Score: ${score}`;

        let i = 0;
        const speed = Math.max(180, SPEED_BASE - (sequence.length * 35));
        
        const interval = setInterval(() => {
            activate(sequence[i]);
            i++;
            if(i >= sequence.length){
                clearInterval(interval);
                setTimeout(() => {
                    playing = false;
                    unlocked = true;
                    playerPos = 0;
                }, speed);
            }
        }, speed);
    }

    function addStep(){
        sequence.push(Math.floor(Math.random() * NUM_BUTTONS));
    }

    function handlePlayerInput(idx){
        if(!unlocked || playing) return;
        activate(idx);
        
        if(idx !== sequence[playerPos]){
            countEl.textContent = "!!";
            playTone(150, 600);
            guardarScore(score);
            showGameOver(false); 
            return;
        }

        playerPos++;
        if(playerPos >= sequence.length){
            score++;
            scoreLabel.textContent = `Score: ${score}`;
            if(score >= TARGET_ROUND){
                guardarScore(score);
                showGameOver(true);
                return;
            }
            unlocked = false;
            setTimeout(() => { addStep(); playSequence(); }, 1000);
        }
    }

    function resetGame(){
        sequence = []; playerPos = 0; score = 0;
        scoreLabel.textContent = `Score: 0`; countEl.textContent = "--";
        playing = false; unlocked = false;
    }

    function showIntro() {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        govBubble.className = "speech-bubble";
        scoreContainer.classList.add('hidden');
        resetGame();

        govEyebrow.textContent = "MEMORIA ECOICA";
        govTitle.textContent = "Simon Medio";
        govMsg.innerHTML = `5 colores. Secuencias más largas.<br>Meta: <strong>${TARGET_ROUND} rondas</strong>.`;
        actionBtn.textContent = "¡Empezar!";

        actionBtn.onclick = () => {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.classList.add('hidden');
                addStep();
                setTimeout(playSequence, 500);
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
            govTitle.textContent = "¡Nivel Superado!";
            govMsg.innerHTML = "Gran concentración.";
            actionBtn.textContent = "Jugar de nuevo";
        } else {
            govBubble.className = "speech-bubble lose-theme";
            govEyebrow.textContent = "FALLASTE";
            govTitle.textContent = "Juego Terminado";
            govMsg.innerHTML = "La secuencia se rompió.";
            actionBtn.textContent = "Reintentar";
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

    function guardarScore(scoreVal) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/simondice-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: "medium" })
        }).catch(console.error);
    }

    startBtn.addEventListener('click', () => { resetGame(); addStep(); playSequence(); });
    strictBtn.addEventListener('click', () => { strictMode = !strictMode; strictBtn.classList.toggle('active'); });
    soundToggle.addEventListener('click', () => { soundEnabled = !soundEnabled; soundToggle.classList.toggle('active'); soundToggle.textContent = soundEnabled ? 'Sonido' : 'Mute'; });

    buildBoard();
    showIntro();
});
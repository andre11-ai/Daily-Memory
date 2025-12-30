document.addEventListener('DOMContentLoaded', function() {
    const TARGET_SCORE = 6;
    const CURRENT_LEVEL = 1;
    const NEXT_LEVEL_URL = '/niveles/2'; 
    const MAP_URL = '/story';           

    const colores = ["#F44336","#2196F3","#4CAF50","#FFEB3B","#E91E63","#FF9800","#9C27B0","#00bcd4"];
    let rondaActual = 1, score = 0, vidas = 3, rondaColores = 3;
    let colorsToMemorize = [], userSelected = [];
    let jugando = false;

    const scoreLabel = document.getElementById('score-label');
    const vidasDots = document.getElementById('vidas-dots');
    const memorizePhase = document.getElementById('memorize-phase');
    const selectPhase = document.getElementById('select-phase');
    const memorizeGrid = document.getElementById('memorize-grid');
    const selectGrid = document.getElementById('select-grid');
    const readyBtn = document.getElementById('ready-btn');
    const verifyBtn = document.getElementById('verify-btn');

    let activeModal = document.getElementById('modal-gameover'); 
    
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const scoreDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backContainer = document.getElementById('back-menu-container');


    function actualizarBarra() {
        scoreLabel.innerHTML = `<b>Score:</b> ${score}`;
        let dots = '';
        for(let i=1; i<=3; i++){
            dots += `<span class="vida-dot${i<=vidas?' active':''}"></span>`;
        }
        vidasDots.innerHTML = dots;
    }

    function iniciarRonda() {
        if(vidas <= 0) { 
            mostrarGameOver(false); 
            jugando = false; 
            return; 
        }

        jugando = true;
        userSelected = [];
        colorsToMemorize = [];
        
        memorizePhase.style.display = 'flex';
        selectPhase.style.display = 'none';
        memorizeGrid.innerHTML = '';
        selectGrid.innerHTML = '';

        while(colorsToMemorize.length < rondaColores){
            let col = colores[Math.floor(Math.random()*colores.length)];
            if(!colorsToMemorize.includes(col)) colorsToMemorize.push(col);
        }

        colorsToMemorize.forEach(col => {
            let div = document.createElement('div');
            div.className = 'color-card-option';
            div.style.background = col;
            memorizeGrid.appendChild(div);
        });
        actualizarBarra();
    }

    readyBtn.onclick = function() {
        memorizePhase.style.display = 'none';
        selectPhase.style.display = 'flex';
        userSelected = [];
        selectGrid.innerHTML = '';
        
        colores.forEach((col) => {
            let div = document.createElement('div');
            div.className = 'color-card-option';
            div.style.background = col;
            div.onclick = function(){
                if(!jugando) return;
                if(userSelected.length < colorsToMemorize.length && !userSelected.includes(col)){
                    userSelected.push(col);
                    div.classList.add('selected');
                }
            }
            selectGrid.appendChild(div);
        });
    };

    verifyBtn.onclick = function() {
        if(!jugando || userSelected.length !== colorsToMemorize.length) return;
        
        let aciertos = 0;
        for(let i=0; i<colorsToMemorize.length; i++){
            if(userSelected[i] === colorsToMemorize[i]) aciertos++;
        }
        
        score += aciertos;
        let errores = colorsToMemorize.length - aciertos;
        vidas -= errores;
        actualizarBarra();

        if(score >= TARGET_SCORE){
            mostrarGameOver(true);
            return;
        }

        if(vidas <= 0){
            mostrarGameOver(false); 
            return;
        }

        rondaActual++;
        rondaColores = Math.min(colores.length-1, rondaColores+1);
        setTimeout(iniciarRonda, 950);
    };


    function createIntro() {
        if (document.getElementById('intro-modal')) return;
        
        const m = document.createElement('div');
        m.id = 'intro-modal';
        m.className = 'intro-overlay';
        m.innerHTML = `
          <div class="intro-scene">
            <div class="mascot-container">
                <img src="/img/default-user.png" alt="Brain Mascot" class="mascot-img" />
            </div>
            <div class="speech-bubble">
                <div class="intro-header">
                    <div class="intro-eyebrow">HISTORIA · NIVEL 1</div>
                    <h2 class="intro-title">¡Memoriza el Color!</h2>
                </div>
                <div class="intro-content">
                    <p>¡Hola! Ayúdame a recordar. <br>Meta: logra <strong>${TARGET_SCORE} puntos</strong> para avanzar.</p>
                    <ul class="intro-list">
                        <li>Observa y memoriza los colores.</li>
                        <li>Selecciónalos en el orden correcto.</li>
                        <li>Cuidado, las vidas bajan por errores.</li>
                    </ul>
                </div>
                <div class="intro-footer">
                    <button id="intro-close" class="start-btn">¡Vamos a jugar!</button>
                </div>
            </div>
          </div>
        `;
        document.body.appendChild(m);
        document.getElementById('intro-close').addEventListener('click', () => {
            m.style.transition = 'opacity 0.3s ease';
            m.style.opacity = '0';
            setTimeout(() => {
                m.style.display = 'none';
                iniciarRonda();
            }, 300);
        });
    }

    function showIntro() {
        let m = document.getElementById('intro-modal');
        if (!m) {
            createIntro();
            m = document.getElementById('intro-modal');
        }
        m.style.display = 'flex';
        requestAnimationFrame(() => {
            m.style.opacity = '1';
        });
    }

    function mostrarGameOver(gano) {
        jugando = false;
        
        const modal = document.getElementById('modal-gameover');
        if(!modal) return; 

        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        modal.style.opacity = '1';

        const bubble = document.getElementById('gov-bubble');
        const eyebrow = document.getElementById('gov-eyebrow');
        const title = document.getElementById('gov-title');
        const msg = document.getElementById('gov-msg');
        const sDisplay = document.getElementById('score-modal-display');
        const btn = document.getElementById('action-btn');
        const backCont = document.getElementById('back-menu-container');

        bubble.classList.remove('win-theme', 'lose-theme');

        sDisplay.textContent = score;

        if (gano) {
            bubble.classList.add('win-theme');
            eyebrow.textContent = "¡NIVEL COMPLETADO!";
            title.textContent = "¡Excelente Trabajo!";
            msg.innerHTML = "Has recuperado este recuerdo con éxito. <br>¡Sigamos avanzando!";
            btn.textContent = "Siguiente Nivel";
            btn.onclick = () => window.location.href = NEXT_LEVEL_URL;
            
            completarHistoria();
        } else {
            bubble.classList.add('lose-theme');
            eyebrow.textContent = "¡NO TE RINDAS!";
            title.textContent = "¡Casi lo logras!";
            msg.innerHTML = "La memoria necesita práctica. <br>Respira profundo e inténtalo de nuevo.";
            btn.textContent = "Intentar de nuevo";
            btn.onclick = reiniciarJuego;
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

        guardarScore(score);
    }

    function reiniciarJuego() {
        const modal = document.getElementById('modal-gameover');
        
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden'); 
            modal.style.display = 'none';
            
            rondaActual = 1; 
            score = 0; 
            vidas = 3; 
            rondaColores = 3; 
            
            showIntro();
            actualizarBarra();
        }, 300);
    }

    function completarHistoria(){
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfMeta ? csrfMeta.content : '';
        
        fetch('/story/complete-level',{
            method:'POST',
            headers:{ 
                'Content-Type':'application/json', 
                'X-CSRF-TOKEN': token 
            },
            body: JSON.stringify({ level: CURRENT_LEVEL, score: score })
        })
        .then(r => r.json())
        .then(d => console.log('Nivel completado:', d))
        .catch(err => console.error(err));
    }

    function guardarScore(scoreVal){
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfMeta ? csrfMeta.content : '';

        fetch('/color-game/score',{
            method:'POST',
            headers:{ 
                'Content-Type':'application/json', 
                'X-CSRF-TOKEN': token 
            },
            body: JSON.stringify({ score: scoreVal, difficulty: "easy" })
        })
        .catch(err => console.error('Error guardando score:', err));
    }

    createIntro();
    setTimeout(() => {
        const m = document.getElementById('intro-modal');
        if(m) m.style.opacity = '1';
    }, 100);
});
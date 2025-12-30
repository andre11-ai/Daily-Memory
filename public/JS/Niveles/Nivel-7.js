document.addEventListener('DOMContentLoaded', function() {
    const TARGET_SCORE = 16;   
    const CURRENT_LEVEL = 7;
    const NEXT_LEVEL_URL = '/niveles/8';
    const MAP_URL = '/story';
    const RUTA_IMAGENES = '/img/imageMemorama/';

    let nivel = 4; 
    let todasLasImagenes = [1,2,3,4,5,6,7,8];
    let score = 0, vidas = 3;
    let secuenciaCorrecta = [], imagenesPiscina = [], secuenciaUsuario = [];
    let nivelActual = 1, NIVEL_MAX = 10;

    const rejillaMemorizar = document.getElementById('memorize-grid');
    const rejillaSlots = document.getElementById('slot-grid');
    const rejillaPiscina = document.getElementById('pool-grid');
    const scoreLabel = document.getElementById('score-label');
    const vidasDots = document.getElementById('vidas-dots');
    const nivelLabel = document.getElementById('nivel-label');
    const faseMemorizar = document.getElementById('memorize-phase');
    const faseRecordar = document.getElementById('recall-phase');

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sContainer = document.getElementById('score-container');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');

    function shuffle(array) { return array.sort(() => Math.random() - 0.5); }

    function actualizarBarra() {
        scoreLabel.innerHTML = `<b>Score:</b> ${score}`;
        let dots = '';
        for (let i=1; i<=3; i++) dots += `<span class="vida-dot${i<=vidas?' active':''}"></span>`;
        vidasDots.innerHTML = dots;
        nivelLabel.textContent = `Nivel: ${nivelActual} / ${NIVEL_MAX}`;
    }

    function initRonda() {
        secuenciaUsuario = [];
        let imagenesJuego = shuffle([...todasLasImagenes]).slice(0, nivel);
        secuenciaCorrecta = shuffle([...imagenesJuego]);
        imagenesPiscina = shuffle([...imagenesJuego]);
        
        for (let i=0; i<nivel; i++) secuenciaUsuario.push(null);
        
        drawBoard();
        actualizarBarra();
    }

    function drawBoard() {
        rejillaMemorizar.innerHTML = '';
        secuenciaCorrecta.forEach(idImagen => {
            rejillaMemorizar.innerHTML += `<div class="image-box"><img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image"></div>`;
        });

        rejillaSlots.innerHTML = '';
        for (let i=0; i<nivel; i++) {
            let slot = document.createElement('div');
            slot.className = 'slot-box';
            slot.id = `slot_${i}`;
            slot.onclick = () => pressSlot(i);
            rejillaSlots.appendChild(slot);
        }

        rejillaPiscina.innerHTML = '';
        imagenesPiscina.forEach((idImagen, i) => {
            let imgBox = document.createElement('div');
            imgBox.className = 'image-box';
            imgBox.id = `pool_${i}`;
            imgBox.innerHTML = `<img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image">`;
            imgBox.onclick = () => pressPoolImage(i, idImagen);
            rejillaPiscina.appendChild(imgBox);
        });
    }

    window.pressReady = function() {
        faseMemorizar.style.display = 'none';
        faseRecordar.style.display = 'flex'; 
    };

    function pressPoolImage(indicePiscina, idImagen) {
        let indiceSlot = secuenciaUsuario.indexOf(null);
        if (indiceSlot === -1 || vidas<=0) return;
        if (secuenciaUsuario.includes(idImagen)) return; 
        
        secuenciaUsuario[indiceSlot] = idImagen;
        const slot = document.getElementById("slot_" + indiceSlot);
        slot.classList.add('filled');
        slot.innerHTML = `<img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image">`;
        document.getElementById("pool_" + indicePiscina).style.visibility = 'hidden';
    }

    function pressSlot(indiceSlot) {
        let idImagen = secuenciaUsuario[indiceSlot];
        if (idImagen == null || vidas<=0) return;
        
        secuenciaUsuario[indiceSlot] = null;
        const slot = document.getElementById("slot_" + indiceSlot);
        slot.innerHTML = '';
        slot.classList.remove('filled');
        
        for (let i=0; i<imagenesPiscina.length; i++) {
            if (imagenesPiscina[i] == idImagen) {
                document.getElementById("pool_" + i).style.visibility = 'visible';
                break;
            }
        }
    }

    window.pressVerify = function() {
        if (vidas<=0) return;
        
        if (secuenciaUsuario.includes(null)) {
            if(typeof Swal !== 'undefined') Swal.fire('¡Espera!', 'Completa la secuencia.', 'warning');
            else alert('Completa la secuencia antes de verificar.');
            return;
        }

        let aciertos = 0;
        for (let i=0; i<nivel; i++) {
            if (secuenciaUsuario[i] == secuenciaCorrecta[i]) aciertos++;
        }
        
        score += aciertos;
        let errores = nivel - aciertos;
        vidas -= errores;
        actualizarBarra();

        if (vidas <= 0) {
            mostrarGameOver(false);
            return;
        }

        if (nivelActual >= NIVEL_MAX || score >= TARGET_SCORE) {
            mostrarGameOver(true);
            return;
        }

        nivelActual++;
        faseMemorizar.style.display='flex';
        faseRecordar.style.display='none';
        initRonda();
    };

    function showIntro() {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden'); 

        govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
        govTitle.textContent = "Secuencia de Imágenes";
        govMsg.innerHTML = `
            Pon a prueba tu memoria visual. <br>
            Meta: consigue <strong>${TARGET_SCORE} puntos</strong>. <br>
            Memoriza el orden y repítelo.
        `;

        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                score=0; vidas=3; nivelActual=1;
                faseMemorizar.style.display='flex';
                faseRecordar.style.display='none';
                initRonda();
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
            govTitle.textContent = "¡Memoria Fotográfica!";
            govMsg.innerHTML = "Has completado la secuencia con éxito. <br>¡Excelente trabajo!";

            actionBtn.textContent = "Siguiente Nivel";
            actionBtn.onclick = () => window.location.href = NEXT_LEVEL_URL;
            
            completarHistoria();
        } else {
            govBubble.classList.add('lose-theme');
            govEyebrow.textContent = "¡TE EQUIVOCASTE!";
            govTitle.textContent = "¡Inténtalo de nuevo!";
            govMsg.innerHTML = "Se acabaron las vidas. <br>Concéntrate y memoriza bien el orden.";

            actionBtn.textContent = "Reintentar";
            actionBtn.onclick = () => {
                showIntro();
            };
        }
        
        guardarScore(score);

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
        fetch('/secuencia-color-game/score',{
            method:'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score:scoreVal, difficulty:"easy" })
        }).catch(err=>console.error(err));
    }

    function completarHistoria(){
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/story/complete-level', {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ level: CURRENT_LEVEL, score: score })
        }).catch(err=>console.error(err));
    }

    showIntro();
});
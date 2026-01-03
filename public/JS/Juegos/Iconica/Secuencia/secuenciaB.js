const RUTA_IMAGENES = '/img/imageMemorama/';
let nivel = 4;
let todasLasImagenes = [1,2,3,4,5,6,7,8];
let score = 0, vidas = 3;
let secuenciaCorrecta = [], imagenesPiscina = [], secuenciaUsuario = [];
let nivelActual = 1, NIVEL_MAX = 10;

let rejillaMemorizar = document.getElementById('memorize-grid');
let rejillaSlots = document.getElementById('slot-grid');
let rejillaPiscina = document.getElementById('pool-grid');
let scoreLabel = document.getElementById('score-label');
let vidasDots = document.getElementById('vidas-dots');
let nivelLabel = document.getElementById('nivel-label');
let faseMemorizar = document.getElementById('memorize-phase');
let faseRecordar = document.getElementById('recall-phase');

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
    for(let i=1;i<=3;i++){
        dots += `<span class="vida-dot${i<=vidas?' active':''}"></span>`;
    }
    vidasDots.innerHTML = dots;
    nivelLabel.textContent = `Nivel: ${nivelActual} / ${NIVEL_MAX}`;
}

function initRonda() {
    secuenciaUsuario = [];
    let imagenesJuego = shuffle([...todasLasImagenes]).slice(0, nivel);
    secuenciaCorrecta = shuffle([...imagenesJuego]);
    imagenesPiscina = shuffle([...imagenesJuego]);
    for (let i = 0; i < nivel; i++) secuenciaUsuario.push(null);
    drawBoard();
    actualizarBarra();
}

function drawBoard() {
    rejillaMemorizar.innerHTML = '';
    for (let i = 0; i < secuenciaCorrecta.length; i++) {
        let idImagen = secuenciaCorrecta[i];
        rejillaMemorizar.innerHTML += `<div class="image-box"><img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image"></div>`;
    }
    rejillaSlots.innerHTML = '';
    for (let i = 0; i < nivel; i++) {
        rejillaSlots.innerHTML += `<div class="slot-box" id="slot_${i}" onclick="pressSlot(${i})"></div>`;
    }
    rejillaPiscina.innerHTML = '';
    for (let i = 0; i < imagenesPiscina.length; i++) {
        let idImagen = imagenesPiscina[i];
        rejillaPiscina.innerHTML += `<div class="image-box" id="pool_${i}" onclick="pressPoolImage(${i}, ${idImagen})"><img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image"></div>`;
    }
}

function pressReady() {
    faseMemorizar.style.display = 'none';
    faseRecordar.style.display = 'flex';
}

function pressPoolImage(indicePiscina, idImagen) {
    let indiceSlot = secuenciaUsuario.indexOf(null);
    if (indiceSlot === -1 || vidas<=0) return;
    if (secuenciaUsuario.includes(idImagen)) return;
    secuenciaUsuario[indiceSlot] = idImagen;
    let slot = document.getElementById("slot_" + indiceSlot);
    slot.classList.add('filled');
    slot.innerHTML = `<img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image">`;
    document.getElementById("pool_" + indicePiscina).style.visibility = 'hidden';
}

function pressSlot(indiceSlot) {
    let idImagen = secuenciaUsuario[indiceSlot];
    if (idImagen == null || vidas<=0) return;
    secuenciaUsuario[indiceSlot] = null;
    let slot = document.getElementById("slot_" + indiceSlot);
    slot.innerHTML = '';
    slot.classList.remove('filled');
    for (let i = 0; i < imagenesPiscina.length; i++) {
        if (imagenesPiscina[i] == idImagen) {
            document.getElementById("pool_" + i).style.visibility = 'visible';
            break;
        }
    }
}

function pressVerify() {
    if (vidas<=0) return;
    if (secuenciaUsuario.includes(null)) {
        Swal.fire('¡Espera!', 'Debes llenar todos los espacios antes de verificar.', 'warning');
        return;
    }
    let aciertos = 0;
    for (let i = 0; i < nivel; i++) {
        if (secuenciaUsuario[i] == secuenciaCorrecta[i]) aciertos++;
    }
    score += aciertos;
    let errores = nivel - aciertos;
    vidas -= errores;
    actualizarBarra();

    if (vidas<=0) {
        mostrarModal(false);
        return;
    }

    if (nivelActual >= NIVEL_MAX) {
        mostrarModal(true);
        return;
    }

    nivelActual++;
    faseMemorizar.style.display='flex';
    faseRecordar.style.display='none';
    initRonda();
}

function showIntro() {
    modalGO.classList.remove('hidden');
    setTimeout(() => modalGO.classList.add('active'), 10);

    govBubble.classList.remove('win-theme', 'lose-theme');
    sContainer.classList.add('hidden');

    govEyebrow.textContent = "NIVEL FÁCIL";
    govTitle.textContent = "Secuencia de Imágenes";
    govMsg.innerHTML = "Observa el orden de las imágenes y repítelo en las casillas vacías.<br>¡Buena suerte!";

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
        govEyebrow.textContent = "¡FELICIDADES!";
        govTitle.textContent = "¡Juego Completado!";
        govMsg.innerHTML = "Has completado los 10 niveles con éxito. <br>¡Tu memoria visual es excelente!";
        actionBtn.textContent = "Jugar de nuevo";
    } else {
        govBubble.classList.add('lose-theme');
        govEyebrow.textContent = "¡GAME OVER!";
        govTitle.textContent = "¡Buen intento!";
        govMsg.innerHTML = "Se acabaron las vidas. <br>¡No te rindas y vuelve a intentarlo!";
        actionBtn.textContent = "Reintentar";
    }

    actionBtn.onclick = () => {
        showIntro();
    };

    guardarScore(score);
}

function guardarScore(score){
    fetch('/secuencia-color-game/score',{
        method:'POST',
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ score:score, difficulty:"easy" })
    }).catch(err=>console.error('Error guardar score:',err));
}

window.pressReady = pressReady;
window.pressPoolImage = pressPoolImage;
window.pressSlot = pressSlot;
window.pressVerify = pressVerify;

showIntro();

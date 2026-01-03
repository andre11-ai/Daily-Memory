document.addEventListener('DOMContentLoaded', function() {
    const TARGET_SCORE = 30;
    const colores = ["#F44336","#2196F3","#4CAF50","#FFEB3B","#E91E63","#FF9800","#9C27B0","#00bcd4"];
    let rondaActual = 1, score = 0, vidas = 3, rondaColores = 3;
    let colorsToMemorize = [], userSelected = [];
    let jugando = false;

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sContainer = document.getElementById('score-container');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');

    function actualizarBarra() {
        document.getElementById('score-label').innerHTML = `<b>Score:</b> ${score}`;
        let dots = '';
        for(let i=1;i<=3;i++){
            dots += `<span class="vida-dot${i<=vidas?' active':''}"></span>`;
        }
        document.getElementById('vidas-dots').innerHTML = dots;
    }

    function iniciarRonda() {
        if(vidas<=0) { mostrarModal(false); return; }

        jugando = true;
        userSelected = [];
        colorsToMemorize = [];
        document.getElementById('memorize-phase').style.display = 'flex';
        document.getElementById('select-phase').style.display = 'none';
        document.getElementById('memorize-grid').innerHTML = '';
        document.getElementById('select-grid').innerHTML = '';

        while(colorsToMemorize.length < rondaColores){
            let col = colores[Math.floor(Math.random()*colores.length)];
            if(!colorsToMemorize.includes(col)) colorsToMemorize.push(col);
        }

        colorsToMemorize.forEach(col=>{
            let div=document.createElement('div');
            div.className='color-card-option';
            div.style.background=col;
            document.getElementById('memorize-grid').appendChild(div);
        });
        actualizarBarra();
    }

    document.getElementById('ready-btn').onclick = function() {
        document.getElementById('memorize-phase').style.display='none';
        document.getElementById('select-phase').style.display='flex';
        userSelected = [];
        document.getElementById('select-grid').innerHTML = '';

        colores.forEach((col)=>{
            let div=document.createElement('div');
            div.className='color-card-option';
            div.style.background=col;
            div.onclick = function(){
                if(!jugando) return;
                if(userSelected.length < colorsToMemorize.length && !userSelected.includes(col)){
                    userSelected.push(col);
                    div.classList.add('selected');
                }
            }
            document.getElementById('select-grid').appendChild(div);
        });
    }

    document.getElementById('verify-btn').onclick = function() {
        if(!jugando || userSelected.length !== colorsToMemorize.length) return;

        let aciertos = 0;
        for(let i=0;i<colorsToMemorize.length;i++){
            if(userSelected[i] === colorsToMemorize[i]) aciertos++;
        }

        score += aciertos;
        let errores = colorsToMemorize.length - aciertos;
        vidas -= errores;
        actualizarBarra();

        if (score >= TARGET_SCORE) {
            mostrarModal(true);
            return;
        }

        if(vidas<=0){
            mostrarModal(false);
            return;
        }

        rondaActual++;
        rondaColores = Math.min(colores.length-1, rondaColores+1);
        setTimeout(iniciarRonda, 950);
    }


    function showIntro() {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden');
        govEyebrow.textContent = "NIVEL FÁCIL";
        govTitle.textContent = "Memoriza el Color";
        govMsg.innerHTML = `
            Bienvenido. <br>
            Meta: consigue <strong>${TARGET_SCORE} puntos</strong>. <br>
            Memoriza los colores y selecciónalos en orden. <br>
            Tienes tres vidas. <br>
        `;
        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                iniciarRonda();
            }, 300);
        };
        backCont.innerHTML = '';
    }

    function mostrarModal(gano) {
        jugando = false;
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
            govEyebrow.textContent = "¡VICTORIA!";
            govTitle.textContent = "¡Memoria Excelente!";
            govMsg.innerHTML = "Has alcanzado la meta de puntos. <br>¡Gran trabajo!";
            actionBtn.textContent = "Jugar de nuevo";
        } else {
            govBubble.classList.add('lose-theme');
            govEyebrow.textContent = "¡SE ACABARON LAS VIDAS!";
            govTitle.textContent = "¡Buen intento!";
            govMsg.innerHTML = "La memoria se entrena con práctica. <br>¡Inténtalo otra vez!";
            actionBtn.textContent = "Reintentar";
        }

        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                reiniciarJuego();
            }, 300);
        };

        guardarScore(score);
    }

    function reiniciarJuego() {
        rondaActual=1; score=0; vidas=3; rondaColores=3;
        showIntro();
    }

    function guardarScore(scoreVal){
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/color-game/score',{
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
            body:JSON.stringify({ score:scoreVal, difficulty:"easy" })
        }).catch(err=>console.error('Error:',err));
    }

    showIntro();
});

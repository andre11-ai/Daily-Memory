document.addEventListener('DOMContentLoaded', function() {
    const colores = ["#F44336","#2196F3","#4CAF50","#FFEB3B","#E91E63",
                      "#FF9800","#9C27B0","#00bcd4", "#795548", "#8bc34a"];
    let rondaActual = 1,
        score = 0,
        vidas = 3,
        rondaColores = 7;
    let colorsToMemorize = [], userSelected = [];
    let jugando = true;

    function actualizarBarra() {
        document.getElementById('score-label').innerHTML = `<b>Score:</b> ${score}`;
        let dots = '';
        for(let i=1;i<=3;i++){
            dots += `<span class="vida-dot${i<=vidas?' active':''}"></span>`;
        }
        document.getElementById('vidas-dots').innerHTML = dots;
    }

    function mostrarModal() {
        document.getElementById('modal-gameover').style.display='flex';
        document.getElementById('score-modal').textContent = score;
        guardarScore(score);
    }

    function iniciarRonda() {
        if(vidas<=0) { mostrarModal(); jugando = false; return; }
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
        colores.forEach((col,i)=>{
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
        if(vidas<=0){
            mostrarModal();
            return;
        }
        rondaActual++;
        rondaColores = Math.min(colores.length-1, rondaColores+1); 
        setTimeout(iniciarRonda, 950);
    }

    document.getElementById('restart-btn').onclick = function() {
        rondaActual=1; score=0; vidas=3; rondaColores=7; jugando=true;
        document.getElementById('modal-gameover').style.display='none';
        iniciarRonda();
    }

    iniciarRonda();

    function guardarScore(score){
        fetch('/color-game/score',{
            method:'POST',
            headers:{'Content-Type':'application/json',
                     'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
            body:JSON.stringify({
                score:score,
                difficulty:"hard"
            })
        })
        .then(res=>res.json())
        .then(data=>console.log('Score guardado',data))
        .catch(err=>console.error('Error guardar score:',err));
    }
});

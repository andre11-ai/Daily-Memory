document.addEventListener('DOMContentLoaded', function() {
    let sonidosDificil = [
        {nombre: 'Palma'},
        {nombre: 'Golpe'},
        {nombre: 'Chasquido'},
        {nombre: 'Viento'},
        {nombre: 'Lluvia'}
    ];
    let rondaActual = 1, score = 0, rondasMax = 15;
    let secuencia = [], eleccionUsuario = [];
    let jugando = false;

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

    function gameOver() {
        jugando = false;
        document.getElementById('modal-gameover').style.display="flex";
        document.getElementById('score-modal').textContent = score;
        guardarScore(score);
    }

    function siguienteRonda() {
        if (rondaActual > rondasMax) {
            gameOver(); return;
        }
        jugando = true;
        secuencia = [];
        eleccionUsuario = [];
        document.getElementById('feedback').innerText = '';
        document.getElementById('sound-buttons').classList.remove('hidden');
        document.getElementById('user-selection').innerText = '';
        actualizarBarra();

        for (let i=0; i<rondaActual+3; i++) { // Secuencia más larga
            let idx = Math.floor(Math.random() * sonidosDificil.length);
            secuencia.push(idx);
        }

        let i = 0;
        function playNext() {
            if (i < secuencia.length) {
                hablar(sonidosDificil[secuencia[i]].nombre);
                i++;
                setTimeout(playNext, 1100);
            } else {
                mostrarBotones();
            }
        }
        playNext();

        function mostrarBotones() {
            let con = document.getElementById('sound-buttons');
            con.innerHTML = "";
            sonidosDificil.forEach((s, idx) => {
                let btn = document.createElement('button');
                btn.innerText = s.nombre;
                btn.onclick = function() {
                    if (!jugando) return;
                    hablar(s.nombre);
                    eleccionUsuario.push(idx);
                    btn.classList.add('selected');
                    actualizarSeleccion();

                    if (eleccionUsuario.length === secuencia.length) {
                        verificarRespuesta();
                    }
                };
                con.appendChild(btn);
            });
        }
    }
    function actualizarSeleccion() {
        let txt = eleccionUsuario.map(idx => sonidosDificil[idx].nombre).join(' → ');
        document.getElementById('user-selection').innerText = txt;
    }
    function verificarRespuesta() {
        jugando = false;
        let correcto = JSON.stringify(secuencia) === JSON.stringify(eleccionUsuario);
        document.getElementById('feedback').innerText = correcto ? "¡Correcto! Pulsa Siguiente." : "Incorrecto. Fin de juego.";
        if (correcto) {
            score++;
            rondaActual++;
        } else {
            setTimeout(gameOver, 1000); return;
        }
        actualizarBarra();
    }

    document.getElementById('start-btn').onclick = function() {
        if (!jugando) siguienteRonda();
    };
    document.getElementById('restart-btn').onclick = function() {
        rondaActual = 1; score = 0; jugando = false;
        document.getElementById('modal-gameover').style.display="none";
        actualizarBarra();
        siguienteRonda();
    };

    actualizarBarra();
});
function guardarScore(score){
    fetch('/repetir-palabra-game/score',{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
      },
      body:JSON.stringify({
        score:score,
        difficulty:"hard"
      })
    })
    .then(res=>res.json())
    .then(data=>console.log('Score guardado',data))
    .catch(err=>console.error('Error guardar score:',err));
}

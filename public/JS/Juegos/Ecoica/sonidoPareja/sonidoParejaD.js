document.addEventListener('DOMContentLoaded', function() {
    const SEGUNDOS = 40;
    const palabras = ["Palma", "Golpe", "Chasquido", "Viento", "Lluvia"];
    let pares = palabras.concat(palabras);
    let score = 0;
    let seleccionados = [];
    let timer, tiempoRestante;
    let juegoTerminado = false;

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
        tiempoRestante = SEGUNDOS;
        document.getElementById('tiempo-label').textContent = `Tiempo: ${tiempoRestante}s`;
        timer = setInterval(() => {
            tiempoRestante--;
            document.getElementById('tiempo-label').textContent = `Tiempo: ${tiempoRestante}s`;
            if (tiempoRestante <= 0) {
                terminarJuego();
            }
        }, 1000);
    }
    function terminarJuego() {
        if (juegoTerminado) return;
        juegoTerminado = true;
        clearInterval(timer);
        document.getElementById('modal-gameover').style.display = 'flex';
        document.getElementById('score-modal').textContent = Math.max(score, 0);
        guardarScore(Math.max(score, 0));
    }
    function crearJuego() {
        pares = palabras.concat(palabras);
        mezclar(pares);
        score = 0;
        seleccionados = [];
        juegoTerminado = false;
        document.getElementById('parejas-juego').innerHTML = '';
        document.getElementById('feedback').innerText = '';
        document.getElementById('score-label').textContent = `Score: 0`;
        document.getElementById('modal-gameover').style.display = 'none';

        pares.forEach((palabra, idx) => {
            const btn = document.createElement('button');
            btn.className = 'pareja-btn';
            btn.dataset.palabra = palabra;
            btn.dataset.idx = idx;
            btn.innerText = "🔊";
            btn.disabled = false;
            btn.onclick = function() {
                if (juegoTerminado) return;
                if (btn.classList.contains('found') || btn.classList.contains('selected')) return;
                btn.classList.add('selected');
                hablar(palabra);

                seleccionados.push({idx, palabra, btn});
                if (seleccionados.length === 2) {
                    document.querySelectorAll('.pareja-btn:not(.found)').forEach(b => b.disabled = true);

                    if (seleccionados[0].palabra === seleccionados[1].palabra) {
                        seleccionados[0].btn.classList.add('found');
                        seleccionados[1].btn.classList.add('found');
                        seleccionados[0].btn.classList.remove('selected');
                        seleccionados[1].btn.classList.remove('selected');
                        score++;
                        document.getElementById('feedback').innerText = "¡Pareja encontrada!";
                        document.getElementById('score-label').textContent = `Score: ${score}`;
                    } else {
                        document.getElementById('feedback').innerText = "No es pareja. Intenta de nuevo.";
                        setTimeout(function() {
                            seleccionados[0].btn.classList.remove('selected');
                            seleccionados[1].btn.classList.remove('selected');
                        }, 900);
                    }
                    setTimeout(function() {
                        document.querySelectorAll('.pareja-btn:not(.found)').forEach(b => b.disabled = false);
                        seleccionados = [];
                        if (score === palabras.length) {
                            terminarJuego();
                        }
                    }, 950);
                }
            };
            document.getElementById('parejas-juego').appendChild(btn);
        });
        iniciarTimer();
    }
    document.getElementById('reset-game').onclick = crearJuego;
    document.getElementById('restart-btn').onclick = crearJuego;
    crearJuego();

    function guardarScore(score) {
        fetch('/sonido-pareja-game/score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                score: score,
                difficulty: 'hard'
            })
        })
        .then(res => res.json())
        .then(data => console.log(data.message))
        .catch(err => console.error('Error al guardar score:', err));
    }
});

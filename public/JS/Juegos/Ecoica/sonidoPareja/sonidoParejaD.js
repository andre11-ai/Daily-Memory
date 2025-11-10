
document.addEventListener('DOMContentLoaded', function() {
    const palabras = ["Palma", "Golpe", "Chasquido", "Viento", "Lluvia"];
    let pares = palabras.concat(palabras);
    let seleccionados = [];
    let encontrados = 0;

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

    function crearJuego() {
        pares = palabras.concat(palabras);
        mezclar(pares);
        seleccionados = [];
        encontrados = 0;
        const juegoDiv = document.getElementById('parejas-juego');
        juegoDiv.innerHTML = '';
        document.getElementById('feedback').innerText = '';

        pares.forEach((palabra, idx) => {
            const btn = document.createElement('button');
            btn.className = 'pareja-btn';
            btn.dataset.palabra = palabra;
            btn.dataset.idx = idx;
            btn.innerText = "🔊";
            btn.disabled = false;
            btn.onclick = function() {
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
                        encontrados += 2;
                        document.getElementById('feedback').innerText = "¡Pareja encontrada!";
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
                        if (encontrados === 10) {
                            document.getElementById('feedback').innerText = "¡Has encontrado todas las parejas!";
                        }
                    }, 950);
                }
            };
            juegoDiv.appendChild(btn);
        });
    }

    document.getElementById('reset-game').onclick = crearJuego;
    crearJuego();
});

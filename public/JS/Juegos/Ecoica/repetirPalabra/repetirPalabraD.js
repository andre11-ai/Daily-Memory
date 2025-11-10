document.addEventListener('DOMContentLoaded', function() {
    let sonidosDificil = [
        {nombre: 'Palma'},
        {nombre: 'Golpe'},
        {nombre: 'Chasquido'},
        {nombre: 'Viento'},
        {nombre: 'Lluvia'}
    ];
    let secuencia = [];
    let eleccionUsuario = [];

    function hablar(texto) {
        if ('speechSynthesis' in window) {
            const msg = new window.SpeechSynthesisUtterance(texto);
            msg.lang = 'es-ES';
            window.speechSynthesis.speak(msg);
        } else {
            alert("Tu navegador no soporta síntesis de voz.");
        }
    }

    document.getElementById('start-btn').onclick = function() {
        if (window.nivelActual && window.nivelActual !== 'dificil') return;

        secuencia = [];
        eleccionUsuario = [];
        document.getElementById('feedback').innerText = '';
        document.getElementById('sound-buttons').classList.remove('hidden');
        document.getElementById('user-selection').innerText = '';

        for (let i=0; i<5; i++) {
            let idx = Math.floor(Math.random() * sonidosDificil.length);
            secuencia.push(idx);
        }

        let i = 0;
        function playNext() {
            if (i < secuencia.length) {
                hablar(sonidosDificil[secuencia[i]].nombre);
                i++;
                setTimeout(playNext, 1200);
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
        function actualizarSeleccion() {
            let txt = eleccionUsuario.map(idx => sonidosDificil[idx].nombre).join(' → ');
            document.getElementById('user-selection').innerText = txt;
        }
        function verificarRespuesta() {
            let correcto = JSON.stringify(secuencia) === JSON.stringify(eleccionUsuario);
            document.getElementById('feedback').innerText = correcto ? "¡Genial, acertaste el nivel difícil!" : "Ups, incorrecto. Sigue intentando.";
        }
    }
});

'use strict';

class Juego {
    constructor() {
        this.vista = new Vista();
        this.modelo = new Modelo();
        this.generador = null;
        this.animador = null;
        this.maincontainer = null;
        this.terminado = false;

        this.modalGO = document.getElementById('modal-gameover');
        this.govBubble = document.getElementById('gov-bubble');
        this.govEyebrow = document.getElementById('gov-eyebrow');
        this.govTitle = document.getElementById('gov-title');
        this.govMsg = document.getElementById('gov-msg');
        this.sContainer = document.getElementById('score-container');
        this.sDisplay = document.getElementById('score-modal-display');
        this.actionBtn = document.getElementById('action-btn');
        this.backCont = document.getElementById('back-menu-container');

        this.TARGET_SCORE = 40;

        window.onload = this.iniciar.bind(this);
    }

    iniciar() {
        this.terminado = false;
        this.maincontainer = document.getElementById("maincontainer");
        this.vista.palabra = this.maincontainer;
        this.showIntro();
    }

    comenzarPartida() {
        this.terminado = false;
        this.modelo.puntuacion = 0;
        this.modelo.vidas = 3;
        this.maincontainer.innerHTML = '';
        this.actualizarStatus();
        this.actualizarVidasDots();

        this.generador = window.setInterval(this.generarPalabra.bind(this), 1500);
        this.animador = window.setInterval(() => this.vista.moverPalabra(this, this.modelo), 100);
        window.onkeypress = this.pulsar.bind(this);
    }

    generarPalabra() {
        if(this.terminado) return;
        let palabraEnviada = this.modelo.crearPalabra();
        this.vista.dibujar(palabraEnviada);
    }

    pulsar(e) {
        if(this.terminado) return;
        let letra = e.key.toLowerCase();
        let palabras = this.maincontainer.querySelectorAll(".palabra");

        for (let palabra of palabras) {
            let span = palabra.querySelector("span");
            let letraObjetivo = span.innerText.charAt(0).toLowerCase();

            if (letra === letraObjetivo) {
                palabra.remove();
                this.modelo.puntuacion++;
                this.actualizarStatus();

                let scoreLabel = document.getElementById("score-label");
                scoreLabel.classList.remove("score-animate");
                void scoreLabel.offsetWidth;
                scoreLabel.classList.add("score-animate");

                if(this.modelo.puntuacion >= this.TARGET_SCORE) {
                    this.terminarJuego(true);
                }
                return;
            }
        }
    }

    actualizarStatus() {
        document.getElementById("score-label").innerHTML = "<b>Score: </b>" + this.modelo.puntuacion;
        this.actualizarVidasDots();
    }

    actualizarVidasDots() {
        let dotsHTML = '';
        for(let i = 1; i <= 3; i++) {
            dotsHTML += `<span class="vida-dot${i <= this.modelo.vidas ? ' active' : ''}"></span>`;
        }
        document.getElementById("vidas-dots").innerHTML = dotsHTML;
    }

    terminarJuego(gano = false) {
        this.terminado = true;
        clearInterval(this.generador);
        clearInterval(this.animador);
        window.onkeypress = null;
        this.mostrarModal(gano);
    }

    showIntro() {
        this.modalGO.classList.remove('hidden');
        setTimeout(() => this.modalGO.classList.add('active'), 10);

        this.govBubble.classList.remove('win-theme', 'lose-theme');
        this.sContainer.classList.add('hidden');

        this.govEyebrow.textContent = "NIVEL FÁCIL";
        this.govTitle.textContent = "Lluvia de Letras";
        this.govMsg.innerHTML = `Destruye <strong>${this.TARGET_SCORE} palabras</strong>.<br>Presiona la letra inicial.`;

        this.actionBtn.textContent = "¡Empezar!";
        this.actionBtn.onclick = () => {
            this.modalGO.classList.remove('active');
            setTimeout(() => {
                this.modalGO.classList.add('hidden');
                this.comenzarPartida();
            }, 300);
        };
        this.backCont.innerHTML = '';
    }

    mostrarModal(gano) {
        this.modalGO.classList.remove('hidden');
        setTimeout(() => this.modalGO.classList.add('active'), 10);

        this.govBubble.classList.remove('win-theme', 'lose-theme');
        this.sContainer.classList.remove('hidden');
        this.sDisplay.textContent = this.modelo.puntuacion;

        this.backCont.innerHTML = '';
        const link = document.createElement('a');
        link.className = 'modal-back-link';
        link.innerHTML = "<i class='bx bx-left-arrow-alt'></i> Volver al Menú";
        link.href = "/TiposMemoria/Mmuscular";
        this.backCont.appendChild(link);

        if (gano) {
            this.govBubble.classList.add('win-theme');
            this.govEyebrow.textContent = "¡FELICIDADES!";
            this.govTitle.textContent = "¡Manos Rápidas!";
            this.govMsg.innerHTML = "Has alcanzado la meta. <br>¡Tus reflejos son excelentes!";
            this.actionBtn.textContent = "Jugar de nuevo";
        } else {
            this.govBubble.classList.add('lose-theme');
            this.govEyebrow.textContent = "¡GAME OVER!";
            this.govTitle.textContent = "¡Buen intento!";
            this.govMsg.innerHTML = "Las palabras cayeron demasiado rápido. <br>¡Inténtalo otra vez!";
            this.actionBtn.textContent = "Reintentar";
        }

        this.actionBtn.onclick = () => {
            this.showIntro();
        };

        this.guardarScore(this.modelo.puntuacion);
    }

    guardarScore(scoreVal) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/lluvia-letras-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: "easy" })
        }).catch(err => console.error(err));
    }
}

class Vista {
    constructor() {
        this.palabra = null;
    }
    dibujar(palabraEnviada) {
        let palabra = document.createElement("div");
        let span = document.createElement("span");
        this.palabra.appendChild(palabra);
        palabra.classList.add("palabra");
        palabra.style.top = "0px";
        palabra.style.left = Math.floor(Math.random() * 80) + 5 + "%";
        palabra.appendChild(span);
        span.innerText = palabraEnviada.charAt(0);
        if (palabraEnviada.length > 1) {
            palabra.appendChild(document.createTextNode(palabraEnviada.slice(1)));
        }
    }
    moverPalabra(juego, modelo) {
        let palabras = this.palabra.querySelectorAll(".palabra");
        for (let palabra of palabras) {
            let top = parseInt(palabra.style.top);
            top += 2;
            palabra.style.top = top + "px";
            if (top > 430) {
                palabra.remove();
                modelo.quitarVida();
                juego.actualizarStatus();
                if (modelo.vidas === 0 && !juego.terminado) {
                    juego.terminarJuego(false);
                }
            }
        }
    }
}

class Modelo {
    constructor() {
        this.palabras = ["luz", "mar", "sol", "pan", "ave", "pez", "red", "sal", "gas", "tren", "uno", "dos"];
        this.puntuacion = 0;
        this.vidas = 3;
    }
    crearPalabra() {
        return this.palabras[Math.floor(Math.random() * this.palabras.length)];
    }
    quitarVida() {
        this.vidas--;
    }
}

let juego = new Juego();

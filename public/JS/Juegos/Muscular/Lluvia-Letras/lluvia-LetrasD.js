'use strict';

class Juego {
    constructor() {
        this.vista = new Vista();
        this.modelo = new Modelo();
        this.generador = null;
        this.animador = null;
        this.maincontainer = null;
        this.terminado = false;

        this.palabraActiva = null;
        this.indiceEscritura = 0;

        this.modalGO = document.getElementById('modal-gameover');
        this.govBubble = document.getElementById('gov-bubble');
        this.govEyebrow = document.getElementById('gov-eyebrow');
        this.govTitle = document.getElementById('gov-title');
        this.govMsg = document.getElementById('gov-msg');
        this.sContainer = document.getElementById('score-container');
        this.sDisplay = document.getElementById('score-modal-display');
        this.actionBtn = document.getElementById('action-btn');
        this.backCont = document.getElementById('back-menu-container');

        this.TARGET_SCORE = 70;

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
        this.palabraActiva = null;
        this.indiceEscritura = 0;
        this.modelo.puntuacion = 0;
        this.modelo.vidas = 3;
        this.maincontainer.innerHTML = '';
        this.actualizarStatus();
        this.actualizarVidasDots();

        this.generador = window.setInterval(this.generarPalabra.bind(this), 600);
        this.animador = window.setInterval(() => this.vista.moverPalabra(this, this.modelo), 50);

        window.addEventListener('keydown', (e) => this.pulsar(e));
    }

    generarPalabra() {
        if(this.terminado) return;
        let palabraEnviada = this.modelo.crearPalabra();
        this.vista.dibujar(palabraEnviada);
    }

    pulsar(e) {
        if (this.terminado) return;
        if (e.key.length !== 1) return;

        let letra = e.key.toLowerCase();

        if (this.palabraActiva) {
            let textoCompleto = this.palabraActiva.dataset.word.toLowerCase();
            let letraEsperada = textoCompleto.charAt(this.indiceEscritura);

            if (letra === letraEsperada) {
                this.indiceEscritura++;
                this.vista.actualizarProgreso(this.palabraActiva, this.indiceEscritura);

                if (this.indiceEscritura >= textoCompleto.length) {
                    this.destruirPalabra(this.palabraActiva);
                }
            }
        } else {
            let palabras = this.maincontainer.querySelectorAll(".palabra");
            for (let palabra of palabras) {
                let texto = palabra.dataset.word.toLowerCase();
                if (texto.startsWith(letra)) {
                    this.palabraActiva = palabra;
                    this.indiceEscritura = 1;
                    this.vista.actualizarProgreso(this.palabraActiva, this.indiceEscritura);
                    if (texto.length === 1) this.destruirPalabra(this.palabraActiva);
                    break;
                }
            }
        }
    }

    destruirPalabra(elemento) {
        elemento.remove();
        this.palabraActiva = null;
        this.indiceEscritura = 0;
        this.modelo.puntuacion++;
        this.actualizarStatus();

        let scoreLabel = document.getElementById("score-label");
        scoreLabel.classList.remove("score-animate");
        void scoreLabel.offsetWidth;
        scoreLabel.classList.add("score-animate");

        if(this.modelo.puntuacion >= this.TARGET_SCORE) {
            this.terminarJuego(true);
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
        this.mostrarModal(gano);
    }

    showIntro() {
        this.modalGO.classList.remove('hidden');
        setTimeout(() => this.modalGO.classList.add('active'), 10);
        this.govBubble.classList.remove('win-theme', 'lose-theme');
        this.sContainer.classList.add('hidden');
        this.govEyebrow.textContent = "NIVEL DIFÍCIL";
        this.govTitle.textContent = "Lluvia de Letras";
        this.govMsg.innerHTML = `Destruye <strong>${this.TARGET_SCORE} palabras</strong>.<br>¡Escribe la palabra <strong>completa</strong>!`;
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
            this.govEyebrow.textContent = "¡INCREÍBLE!";
            this.govTitle.textContent = "¡Maestro del Teclado!";
            this.govMsg.innerHTML = "Has superado el nivel más difícil.";
            this.actionBtn.textContent = "Jugar de nuevo";
        } else {
            this.govBubble.classList.add('lose-theme');
            this.govEyebrow.textContent = "¡GAME OVER!";
            this.govTitle.textContent = "¡Demasiado rápido!";
            this.govMsg.innerHTML = "Necesitas más práctica en este nivel.";
            this.actionBtn.textContent = "Reintentar";
        }
        this.actionBtn.onclick = () => { this.showIntro(); };
        this.guardarScore(this.modelo.puntuacion);
    }

    guardarScore(scoreVal) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/lluvia-letras-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: "hard" })
        }).catch(err => console.error(err));
    }
}

class Vista {
    constructor() { this.palabra = null; }

    dibujar(palabraEnviada) {
        let div = document.createElement("div");
        this.palabra.appendChild(div);
        div.classList.add("palabra");
        div.style.top = "0px";
        div.style.left = Math.floor(Math.random() * 80) + 5 + "%";
        div.dataset.word = palabraEnviada;
        div.innerHTML = palabraEnviada;
    }

    actualizarProgreso(elemento, indice) {
        let texto = elemento.dataset.word;
        let parteEscrita = texto.substring(0, indice);
        let parteRestante = texto.substring(indice);
        elemento.innerHTML = `<span class="typed">${parteEscrita}</span>${parteRestante}`;
    }

    moverPalabra(juego, modelo) {
        let palabras = this.palabra.querySelectorAll(".palabra");
        for (let palabra of palabras) {
            let top = parseInt(palabra.style.top);
            top += 4;
            palabra.style.top = top + "px";

            if (top > 450) {
                if (palabra === juego.palabraActiva) {
                    juego.palabraActiva = null;
                    juego.indiceEscritura = 0;
                }
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
        this.palabras = [
            "computadora", "teclado", "impresora", "pantalla", "raton",
            "programacion", "desarrollo", "javascript", "internet",
            "algoritmo", "base", "datos", "sistema", "red", "codigo"
        ];
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

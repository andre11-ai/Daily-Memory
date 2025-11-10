'use strict';

class Juego {
    constructor() {
        this.vista = new Vista();
        this.modelo = new Modelo();
        this.generador = null;
        this.animador = null;
        this.maincontainer = null;
        this.terminado = false;
        window.onload = this.iniciar.bind(this);
    }

    iniciar() {
        this.terminado = false;
        this.maincontainer = document.getElementById("maincontainer");
        this.vista.palabra = this.maincontainer;
        this.generador = window.setInterval(this.generarPalabra.bind(this), 1500);
        this.animador = window.setInterval(() => this.vista.moverPalabra(this, this.modelo), 100);
        window.onkeypress = this.pulsar.bind(this);
        this.actualizarStatus();
        this.ocultarModal();
        // Reiniciar dots de vidas
        this.actualizarVidasDots();
        // Botón restart
        document.getElementById("restart-btn").onclick = () => {
            this.reiniciar();
        };
    }

    generarPalabra() {
        let palabraEnviada = this.modelo.crearPalabra();
        this.vista.dibujar(palabraEnviada);
    }

    pulsar(e) {
        let letra = e.key;
        let palabras = this.maincontainer.querySelectorAll(".palabra");
        for (let palabra of palabras) {
            let span = palabra.children.item(0);
            let nodoTexto = palabra.childNodes[1];
            let texto = nodoTexto.nodeValue;
            let caracterTexto = texto.charAt(0)
            if (letra == caracterTexto) {
                span.textContent += letra;
                nodoTexto.nodeValue = texto.substring(1);
            } else {
                nodoTexto.nodeValue = span.innerHTML + texto;
                span.textContent = "";
            }
            if (nodoTexto.nodeValue.length == 0) {
                palabra.remove();
                this.modelo.sumarPuntuacion();
                this.actualizarStatus();
                if (this.modelo.vidas === 0 && !this.terminado) {
                    this.terminarJuego();
                }
            }
        }
    }

    actualizarStatus() {
        document.getElementById("score-label").innerHTML = `<b>Score:</b> ${this.modelo.puntuacion}`;
        document.getElementById("vidas-label").innerHTML =
          `<b>Vidas:</b> <span id="vidas-dots"></span>`;
        this.actualizarVidasDots();
    }

    actualizarVidasDots() {
        const dotsContainer = document.getElementById('vidas-dots');
        dotsContainer.innerHTML = '';
        const vidas = this.modelo.vidas;
        for(let i=1;i<=3;i++) {
            const dot=document.createElement('span');
            dot.className='vida-dot'+(i<=vidas?' active':'');
            dotsContainer.appendChild(dot);
        }
    }

    terminarJuego() {
        if (this.terminado) return;
        this.terminado = true;
        clearInterval(this.generador);
        clearInterval(this.animador);
        window.onkeypress = null;
        // Modal puntaje
        this.mostrarModal(this.modelo.puntuacion);
        this.guardarScore(this.modelo.puntuacion);
    }

    mostrarModal(score) {
        document.getElementById('score-modal').textContent = score;
        document.getElementById('modal-gameover').style.display='flex';
    }
    ocultarModal() {
        document.getElementById('modal-gameover').style.display='none';
    }
    reiniciar() {
        // Eliminas palabras activas
        this.maincontainer.innerHTML = '';
        this.modelo.puntuacion = 0;
        this.modelo.vidas = 3;
        this.actualizarStatus();
        this.iniciar();
    }

    guardarScore(score) {
        fetch('/lluvia-letras-game/score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                score: score,
                difficulty: 'easy', // Cambia según el nivel
            })
        })
        .then(res => res.json())
        .then(data => console.log(data.message))
        .catch(err => console.error('Error al guardar score:', err));
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
        palabra.style.left = Math.floor(Math.random() * 600) + "px";
        palabra.appendChild(span);
        palabra.appendChild(document.createTextNode(palabraEnviada));
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
                    juego.terminarJuego();
                }
            }
        }
    }
}

class Modelo {
    constructor() {
        this.palabras = ["l", "a", "p", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m"];
        this.puntuacion = 0;
        this.vidas = 3;
    }
    crearPalabra() {
        return this.palabras[Math.floor(Math.random() * this.palabras.length)];
    }
    sumarPuntuacion() {
        this.puntuacion++;
    }
    quitarVida() {
        this.vidas--;
        if (this.vidas < 0) this.vidas = 0;
    }
}

let juego = new Juego();

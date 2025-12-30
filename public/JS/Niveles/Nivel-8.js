document.addEventListener('DOMContentLoaded', () => {
    const TARGET_SCORE = 25;      
    const CURRENT_LEVEL = 8;     
    const NEXT_LEVEL_URL = '/niveles/9';
    const MAP_URL = '/story';

    let score = 0;
    let vidas = 3;
    let spawnInterval = null;
    let gameActive = false;
    let palabrasEnPantalla = [];
    
    const mainContainer = document.getElementById('maincontainer');
    const scoreLabel = document.getElementById('score-label');
    const vidasDots = document.getElementById('vidas-dots');

    const palabrasBase = [
        "cielo", "nube", "sol", "luna", "mar", "rio", "pez", "ave",
        "flor", "arbol", "hoja", "raiz", "fruta", "rojo", "azul",
        "verde", "gris", "mesa", "silla", "casa", "puerta", "reloj"
    ];

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sContainer = document.getElementById('score-container');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');

    function actualizarHUD() {
        scoreLabel.innerHTML = `<b>Score:</b> ${score}`;
        let dots = '';
        for (let i = 1; i <= 3; i++) {
            dots += `<span class="vida-dot${i <= vidas ? ' active' : ''}"></span>`;
        }
        vidasDots.innerHTML = dots;
    }

    function crearPalabra() {
        if (!gameActive) return;

        const texto = palabrasBase[Math.floor(Math.random() * palabrasBase.length)];
        const el = document.createElement('div');
        el.classList.add('palabra');
        
        const primeraLetra = texto.charAt(0).toUpperCase();
        const resto = texto.slice(1);
        el.innerHTML = `<span>${primeraLetra}</span>${resto}`;
        
        const containerWidth = mainContainer.offsetWidth;
        const x = Math.random() * (containerWidth - 100); 
        el.style.left = `${x}px`;
        el.style.top = '-50px';

        mainContainer.appendChild(el);

        const palabraObj = {
            element: el,
            text: texto,
            key: primeraLetra,
            y: -50,
            speed: 1.5 + (score * 0.1) 
        };

        palabrasEnPantalla.push(palabraObj);
    }

    function gameLoop() {
        if (!gameActive) return;

        palabrasEnPantalla.forEach((p, index) => {
            p.y += p.speed;
            p.element.style.top = `${p.y}px`;

            if (p.y > mainContainer.offsetHeight - 40) {
                eliminarPalabra(index);
                vidas--;
                actualizarHUD();
                
                mainContainer.style.borderColor = "#ff4f68";
                setTimeout(() => mainContainer.style.borderColor = "rgba(0, 200, 163, 0.2)", 200);

                if (vidas <= 0) {
                    endGame(false);
                }
            }
        });

        requestAnimationFrame(gameLoop);
    }

    function eliminarPalabra(index) {
        if (palabrasEnPantalla[index]) {
            if(palabrasEnPantalla[index].element) {
                palabrasEnPantalla[index].element.remove();
            }
            palabrasEnPantalla.splice(index, 1);
        }
    }

    function checkInput(key) {
        if (!gameActive) return;

        let indexToRemove = -1;
        let maxY = -999;

        palabrasEnPantalla.forEach((p, index) => {
            if (p.key === key.toUpperCase()) {
                if (p.y > maxY) {
                    maxY = p.y;
                    indexToRemove = index;
                }
            }
        });

        if (indexToRemove !== -1) {
            eliminarPalabra(indexToRemove);
            score++;
            actualizarHUD();
            
            scoreLabel.classList.add('score-animate');
            setTimeout(() => scoreLabel.classList.remove('score-animate'), 200);

            if (score >= TARGET_SCORE) {
                endGame(true);
            }
        }
    }

    function startGame() {
        mainContainer.innerHTML = '';
        palabrasEnPantalla = [];
        score = 0;
        vidas = 3;
        gameActive = true;
        actualizarHUD();

        if (spawnInterval) clearInterval(spawnInterval);
        spawnInterval = setInterval(crearPalabra, 1500); 
        requestAnimationFrame(gameLoop);
    }

    function endGame(win) {
        gameActive = false;
        clearInterval(spawnInterval);
        
        mostrarGameOver(win);
        
        guardarScore(score);
        if (win) {
            completarHistoria();
        }
    }

    function showIntro() {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden'); 

        govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
        govTitle.textContent = "Lluvia de Letras";
        govMsg.innerHTML = `
            Ejercita tu memoria muscular y reflejos. <br>
            Meta: destruye <strong>${TARGET_SCORE} palabras</strong>. <br>
            Presiona la tecla inicial de cada palabra antes de que toque el suelo.
        `;

        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                startGame();
            }, 300);
        };
        
        backCont.innerHTML = '';
    }

    function mostrarGameOver(win) {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.remove('hidden'); 
        sDisplay.textContent = score;

        if (win) {
            govBubble.classList.add('win-theme');
            govEyebrow.textContent = "¡VICTORIA!";
            govTitle.textContent = "¡Reflejos de Acero!";
            govMsg.innerHTML = "Has protegido la zona con éxito. <br>¡Excelente trabajo!";

            actionBtn.textContent = "Siguiente Nivel";
            actionBtn.onclick = () => {
                window.location.href = NEXT_LEVEL_URL;
            };
        } else {
            govBubble.classList.add('lose-theme');
            govEyebrow.textContent = "¡TE INVADIERON!";
            govTitle.textContent = "¡Inténtalo de nuevo!";
            govMsg.innerHTML = "Demasiadas palabras tocaron el suelo. <br>Necesitas ser más rápido.";

            actionBtn.textContent = "Reintentar";
            actionBtn.onclick = () => {
                showIntro();
            };
        }

        if (!document.getElementById('back-link-btn')) {
            backCont.innerHTML = '';
            const link = document.createElement('a');
            link.id = 'back-link-btn';
            link.className = 'modal-back-link';
            link.innerHTML = "<i class='bx bx-map'></i> Volver al Mapa";
            link.href = MAP_URL;
            backCont.appendChild(link);
        }
    }

    window.addEventListener('keydown', (e) => {
        if (gameActive) {
            if (e.key.length === 1) {
                checkInput(e.key);
            }
        }
    });

    function guardarScore(scoreVal) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/lluvia-letras-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: "easy" })
        }).catch(err => console.error('Error guardando score:', err));
    }

    function completarHistoria() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('/story/complete-level', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ level: CURRENT_LEVEL, score: score })
        })
        .then(r => r.json())
        .then(d => {
            console.log('Nivel completado:', d);
            if(!d.ok) console.warn(d.message);
        })
        .catch(err => console.error('Error completando historia:', err));
    }

    showIntro();
});
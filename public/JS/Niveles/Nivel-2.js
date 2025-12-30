document.addEventListener('DOMContentLoaded', function() {
    const TARGET_SCORE = 12;
    const CURRENT_LEVEL = 2;
    const NEXT_LEVEL_URL = '/niveles/3'; 
    const MAP_URL = '/story';

    const diccionario = [
      "sol","mar","luz","paz","rey","tren","flor","agua","gato","perro","casa","luna","arbol","libro","queso","juego",
      "fruta","verde","abrir","baile","cielo","dulce","enano","feliz","genio","huevo","isla","jarra","koala","lapiz",
      "magia","nube","oreja","piano","roca","sopa","tigre","uva","vaso","yogur","zapato","amarillo","barco","cuchara",
      "delfin","estrella","familia","guitarra","helado","invierno","jardin","lampara","montaña","naranja","oceano",
      "pelota","quesadilla","regalo","sonrisa","tortuga","universo","ventana","xilofono","payaso","amigo","escuela",
      "planeta","corazon","caramelo","elefante","hermano","maquina","naturaleza","obstaculo","primavera","relampago",
      "saltamontes","telefono","volcan","bienvenida","calendario","dificultad","electricidad","fantasma","gasolina",
      "hamburguesa","importante","jirafa","kilogramo","luciernaga","mantequilla","necesidad","operacion","pantalones",
      "radiografia","serpiente","television","velocidad"
    ];

    const canvas = document.getElementById('scaryCanvas');
    const c = canvas.getContext('2d');
    const scoreEl = document.getElementById('score');
    const pauseBtn = document.getElementById('pauseBtn');
    
    let player, projectiles, enemies, particles, animationID, score, isPaused, enemyInterval;
    let gameEnded = false;
    let gameStarted = false;

    const modalGO = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const sContainer = document.getElementById('score-container');
    const sDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const backCont = document.getElementById('back-menu-container');

    class Player {
        constructor(x, y, radius, color) {this.x = x; this.y = y; this.radius = radius; this.color = color;}
        draw() {c.beginPath(); c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false); c.fillStyle = this.color; c.fill();}
    }

    class Projectile {
        constructor(x, y, radius, color, velocity) {this.x = x; this.y = y; this.radius = radius; this.color = color; this.velocity = velocity;}
        draw() {c.beginPath(); c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false); c.fillStyle = this.color; c.fill();}
        update() {this.draw(); this.x += this.velocity.x; this.y += this.velocity.y;}
    }

    class Enemy {
        constructor(x, y, radius, color, velocity, text, fontSize = 28) {
            this.x = x; this.y = y; this.radius = radius; this.color = color;
            this.velocity = velocity; this.text = text; this.fontSize = fontSize;
        }
        draw() {
            c.beginPath(); c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
            c.fillStyle = this.color; c.fill();
            c.fillStyle = 'black'; c.textAlign = 'center';
            c.font = `italic small-caps bold ${this.fontSize}px arial`;
            c.fillText(this.text, this.x, this.y + this.radius*2, 300);
        }
        update() {this.draw(); this.x += this.velocity.x; this.y += this.velocity.y;}
    }

    const friction = 0.99;
    class Particle {
        constructor(x, y, radius, color, velocity) {
            this.x = x; this.y = y; this.radius = radius; this.color = color;
            this.velocity = velocity; this.alpha = 1;
        }
        draw() {c.save(); c.globalAlpha = this.alpha; c.beginPath(); c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false); c.fillStyle = this.color; c.fill(); c.restore();}
        update() {this.draw(); this.velocity.x *= friction; this.velocity.y *= friction; this.x += this.velocity.x; this.y += this.velocity.y; this.alpha -= 0.01;}
    }

    function getPalabraReal(minLen, maxLen) {
        const filtradas = diccionario.filter(w => w.length >= minLen && w.length <= maxLen);
        if (filtradas.length === 0) return "PALABRA";
        return filtradas[Math.floor(Math.random() * filtradas.length)].toUpperCase();
    }

    function resizeCanvas() {
        const parent = canvas.parentElement;
        if(parent){
            canvas.width = parent.clientWidth;
            canvas.height = parent.clientHeight;
        }
        if(gameStarted && !gameEnded && player) { 
            player.x = canvas.width/2; player.y = canvas.height/2; 
        }
    }
    window.addEventListener('resize', resizeCanvas);

    function init(){
        resizeCanvas();
        score = 0;
        player = new Player(canvas.width / 2, canvas.height / 2, 10, 'black');
        projectiles = [];
        enemies = [];
        particles = [];
        scoreEl.innerHTML = 0;
        isPaused = false;
        gameEnded = false;
        gameStarted = true;
        if (enemyInterval) clearInterval(enemyInterval);
    }

    function spawnEnemy() {
        enemyInterval = setInterval(() => {
            if (isPaused || gameEnded) return;
            const palabraRandom = getPalabraReal(3, 10); 
            const radius = Math.max(18, palabraRandom.length * 2);
            const color = `hsl(${Math.random() * 360}, 50%, 50%)`;
            let x, y;
            if(Math.random() < 0.5){
                x = Math.random() < 0.5 ? 0 - radius : canvas.width + radius;
                y = Math.random() * canvas.height;
            }else{
                x = Math.random() * canvas.width;
                y = Math.random() < 0.5 ? 0 - radius : canvas.height + radius;
            }
            const angle = Math.atan2(canvas.height / 2 - y, canvas.width / 2 - x);
            const velocity = {x: Math.cos(angle) * 0.45, y: Math.sin(angle) * 0.45}; 
            enemies.push(new Enemy(x, y, radius, color, velocity, palabraRandom, 18));
        }, 1800);
    }

    function animate() {
        if (isPaused || gameEnded) return;
        animationID = requestAnimationFrame(animate);
        c.clearRect(0,0,canvas.width,canvas.height);

        player.draw();
        
        particles.forEach((particle, index)=>{
            if(particle.alpha <= 0){particles.splice(index, 1);} else{particle.update();}
        });

        projectiles.forEach((projectile, projectileIndex) => {
            projectile.update();
            if(projectile.x + projectile.radius < 0 || projectile.x - projectile.radius > canvas.width || 
               projectile.y + projectile.radius < 0 || projectile.y - projectile.radius > canvas.height){
                setTimeout(() => {projectiles.splice(projectileIndex, 1)}, 0);
            }
        });

        enemies.forEach((enemy, index) => {
            enemy.update();
            const dist = Math.hypot(player.x - enemy.x, player.y - enemy.y);
            

            if(dist - enemy.radius - player.radius < 0.7 && enemy.text.length > 0){
                endGame(false);
            }

            projectiles.forEach((projectile, projectileIndex) => {
                const dist = Math.hypot(projectile.x - enemy.x, projectile.y - projectile.radius - enemy.radius);
                
                if(dist - enemy.radius - projectile.radius < 1 ){
                    for(let i = 0; i< enemy.radius * 2; i++){
                        particles.push(new Particle(projectile.x,projectile.y,Math.random() * 3,enemy.color,{x: (Math.random() - 0.5 ) * Math.random() * 8, y: (Math.random() - 0.5 ) * Math.random() * 8}));
                    }
                    if(enemy.radius > 10 ){
                        score += 1; 
                        if(typeof gsap !== 'undefined') gsap.to(enemy, {radius: enemy.radius - 6});
                        setTimeout(() => {projectiles.splice(projectileIndex, 1)}, 0);
                    }
                    
                    if(enemy.text.length <= 0 ){
                        score += 5; 
                        setTimeout(() => {enemies.splice(index, 1); projectiles.splice(projectileIndex, 1)}, 0);
                    }
                    scoreEl.innerHTML = score;
                    
                    if (score >= TARGET_SCORE && !gameEnded) {
                        endGame(true);
                    }
                }
            });
        });
    }

    function endGame(win){
        gameEnded = true;
        cancelAnimationFrame(animationID);
        clearInterval(enemyInterval);
        isPaused = true;
        
        mostrarGameOver(win);
        guardarScoreEnBD(score, 'easy');
        if (win) completarHistoria();
    }

    window.addEventListener('keydown', (e) => {
        if (isPaused || gameEnded || !gameStarted) return;
        
        const key = e.key.toUpperCase();

        for (let i = enemies.length - 1; i >= 0; i--) {
            const enemy = enemies[i];

            if(enemy.text[0] === key){
                enemy.text = enemy.text.substr(1, enemy.text.length);
                
                if(enemy.text.length === 0){
                    enemies.splice(i, 1);
                    
                    score += 5;
                    scoreEl.innerHTML = score;

                    if (score >= TARGET_SCORE && !gameEnded) {
                        endGame(true);
                    }
                    return; 
                }

                const angle = Math.atan2(enemy.y - canvas.height / 2, enemy.x - canvas.width / 2);
                const velocity = {x: Math.cos(angle) * 12, y: Math.sin(angle) * 12};
                projectiles.push(new Projectile(canvas.width / 2, canvas.height / 2, 5, 'white', velocity));
                return;
            }
        }
    });

    pauseBtn.addEventListener('click', () => {
        if (gameEnded) return;
        if (!isPaused) {
            isPaused = true;
            pauseBtn.textContent = 'Reanudar';
            pauseBtn.style.backgroundColor = "#86e7c3"; 
            pauseBtn.style.color = "#4568DC";
        } else {
            isPaused = false;
            pauseBtn.textContent = 'Pausa';
            pauseBtn.style.backgroundColor = "#ffb347";
            pauseBtn.style.color = "#fff";
            animate();
        }
    });

    function showIntro() {
        modalGO.classList.remove('hidden');
        setTimeout(() => modalGO.classList.add('active'), 10);
        
        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.add('hidden'); 

        govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
        govTitle.textContent = "Scary Witch Typing";
        govMsg.innerHTML = `
            ¡Las palabras nos atacan! <br>
            Meta: consigue <strong>${TARGET_SCORE} puntos</strong>. <br>
            Escribe rápido para destruirlas antes de que te toquen.
        `;

        actionBtn.textContent = "¡Empezar!";
        actionBtn.onclick = () => {
            modalGO.classList.remove('active');
            setTimeout(() => {
                modalGO.classList.add('hidden');
                resetAndStart(); o
            }, 300);
        };
        backCont.innerHTML = '';
    }

    function mostrarGameOver(gano) {
        modalGO.classList.remove('hidden');
        setTimeout(()=> modalGO.classList.add('active'), 10);

        govBubble.classList.remove('win-theme', 'lose-theme');
        sContainer.classList.remove('hidden'); 
        sDisplay.textContent = score;

        if(gano) {
            govBubble.classList.add('win-theme');
            govEyebrow.textContent = "¡VICTORIA!";
            govTitle.textContent = "¡Increíble Agilidad!";
            govMsg.innerHTML = "Has protegido tu memoria muscular. <br>¡Sigamos avanzando!";
            
            actionBtn.textContent = "Siguiente Nivel";
            actionBtn.onclick = () => window.location.href = NEXT_LEVEL_URL;
        } else {
            govBubble.classList.add('lose-theme');
            govEyebrow.textContent = "¡TE ALCANZARON!";
            govTitle.textContent = "¡Inténtalo de nuevo!";
            govMsg.innerHTML = "Un enemigo logró tocarte. <br>Mantén los dedos listos en el teclado.";
            
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

    function resetAndStart(){
        init();
        spawnEnemy();
        animate();
    }

    function completarHistoria(){
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/story/complete-level',{
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ level: CURRENT_LEVEL, score: score })
        }).catch(err => console.error(err));
    }

    function guardarScoreEnBD(scoreVal, diff) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/scary-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: scoreVal, difficulty: diff })
        }).catch(err => console.error(err));
    }

    showIntro(); 
});
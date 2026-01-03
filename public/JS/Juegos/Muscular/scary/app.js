document.addEventListener('DOMContentLoaded', () => {
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

    const META_PUNTOS = 10000;

    function getPalabraReal(minLen, maxLen) {
        const filtradas = diccionario.filter(w => w.length >= minLen && w.length <= maxLen);
        if (filtradas.length === 0) return "PALABRA";
        return filtradas[Math.floor(Math.random() * filtradas.length)].toUpperCase();
    }

    const canvas = document.getElementById('scaryCanvas');
    const c = canvas.getContext('2d');
    const scorelbl = document.querySelector('#score');
    const pauseBtn = document.getElementById('pauseBtn');

    const modal = document.getElementById('modal-gameover');
    const actionBtn = document.getElementById('action-btn');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const scoreContainer = document.getElementById('score-container');
    const scoreDisplay = document.getElementById('score-modal-display');
    const bubble = document.getElementById('gov-bubble');
    const backMenuContainer = document.getElementById('back-menu-container');

    function resizeCanvas() {
        const width = Math.min(window.innerWidth * 0.95, 1200);
        const height = Math.max(window.innerHeight * 0.6, 400);
        canvas.width = width;
        canvas.height = height;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

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

    let player, projectiles, enemies, particles, animationID, score, isPaused, enemyInterval;

    function getRandomWord() {
        return getPalabraReal(3, 23);
    }

    function init(){
        resizeCanvas();
        score = 0;
        player = new Player(canvas.width / 2, canvas.height / 2, 10, 'black');
        projectiles = [];
        enemies = [];
        particles = [];
        scorelbl.innerHTML = 0;
        isPaused = false;
        if (enemyInterval) clearInterval(enemyInterval);
    }

    function spawnEnemy() {
        enemyInterval = setInterval(() => {
            if (isPaused) return;
            const parabraRandom = getRandomWord();
            const radius = parabraRandom.length * 2;
            const color = `hsl(${Math.random() * 360}, 50%, 50%)`;
            let x=0, y=0;
            if(Math.random() < 0.5){
                x = Math.random() < 0.5 ? 0 - radius : canvas.width + radius;
                y = Math.random() * canvas.height;
            }else{
                x = Math.random() * canvas.width;
                y = Math.random() < 0.5 ? 0 - radius : canvas.height + radius;
            }
            const angle = Math.atan2(canvas.height / 2 - y, canvas.width / 2 - x);
            const velocity = {x: Math.cos(angle) * 0.25, y: Math.sin(angle) * 0.25};
            enemies.push(new Enemy(x, y, radius, color, velocity, parabraRandom, 14));
        }, 2000);
    }

    function animate() {
        if (isPaused) return;
        animationID = requestAnimationFrame(animate);
        c.clearRect(0,0,canvas.width,canvas.height);
        
        player.draw();
        particles.forEach((particle, index)=>{
            if(particle.alpha <= 0){particles.splice(index, 1);} else{particle.update();}
        });
        projectiles.forEach((projectile, projectileIndex) => {
            projectile.update();
            if(projectile.x - projectile.radius < 0 || projectile.x + projectile.radius > canvas.width || projectile.y + projectile.radius < 0 || projectile.y + projectile.radius > canvas.height){
                setTimeout(() => {projectiles.splice(projectileIndex, 1)}, 0);
            }
        });
        enemies.forEach((enemy, index) => {
            enemy.update();
            const dist = Math.hypot(player.x - enemy.x, player.y - enemy.y);
            
            if(dist - enemy.radius - player.radius < 0.7 ){
                cancelAnimationFrame(animationID);
                clearInterval(enemyInterval);
                guardarScoreEnBD(score, 'easy');
                showGameOver(false); 
            }
            
            projectiles.forEach((projectile, projectileIndex) => {
                const dist = Math.hypot(projectile.x - enemy.x, projectile.y - enemy.y);
                if(dist - enemy.radius - projectile.radius < 1 ){
                    for(let i = 0; i< enemy.radius * 2; i++){
                        particles.push(new Particle(projectile.x,projectile.y,Math.random() * 3,enemy.color,{x: (Math.random() - 0.5 ) * Math.random() * 8, y: (Math.random() - 0.5 ) * Math.random() * 8}));
                    }
                    if(enemy.radius > 10 ){
                        score += 1;
                        gsap.to(enemy, {radius: enemy.radius - 6});
                        setTimeout(() => {projectiles.splice(projectileIndex, 1)}, 0);
                        animateScore();
                    }
                    if(enemy.text.length <= 0 ){
                        score += 10;
                        setTimeout(() => {enemies.splice(index, 1); projectiles.splice(projectileIndex, 1)}, 0);
                        animateScore();
                    }
                    scorelbl.innerHTML = score;

                    if (score >= META_PUNTOS) {
                        cancelAnimationFrame(animationID);
                        clearInterval(enemyInterval);
                        guardarScoreEnBD(score, 'easy');
                        showGameOver(true);
                    }
                }
            });
        });
    }

    function animateScore() {
        scorelbl.classList.add('score-animate');
        setTimeout(() => scorelbl.classList.remove('score-animate'), 400);
    }

    function showIntro() {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        
        bubble.className = "speech-bubble";
        scoreContainer.classList.add('hidden');
        
        govEyebrow.textContent = "MEMORIA MUSCULAR";
        govTitle.textContent = "¡A escribir!";
        govMsg.innerHTML = "Escribe las palabras para destruir a los enemigos antes de que te toquen.<br><strong>Dificultad: Fácil</strong>";
        actionBtn.textContent = "¡Empezar!";
        
        actionBtn.onclick = () => {
            modal.classList.remove('active');
            setTimeout(() => modal.classList.add('hidden'), 300);
            init();
            animate();
            spawnEnemy();
        };
        
        injectBackLink();
    }

    function showGameOver(win) {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        
        scoreContainer.classList.remove('hidden');
        scoreDisplay.textContent = score;
        
        if (win) {
            bubble.className = "speech-bubble win-theme";
            govEyebrow.textContent = "¡VICTORIA!";
            govTitle.textContent = "¡Lo lograste!";
            govMsg.innerHTML = "Has destruido suficientes enemigos. ¡Tus dedos son rápidos!";
            actionBtn.textContent = "Jugar de nuevo";
        } else {
            bubble.className = "speech-bubble lose-theme";
            govEyebrow.textContent = "¡TE ALCANZARON!";
            govTitle.textContent = "Fin del Juego";
            govMsg.innerHTML = "Un enemigo ha llegado a la base.";
            actionBtn.textContent = "Reintentar";
        }
        
        actionBtn.onclick = () => {
            showIntro(); 
        };
        injectBackLink();
    }

    function injectBackLink() {
        if (!document.querySelector('.modal-back-link')) {
            const backLink = document.createElement('a');
            backLink.className = 'modal-back-link';
            backLink.textContent = "Volver al menú principal";
            backLink.href = "/story";
            backMenuContainer.appendChild(backLink);
        }
    }

    pauseBtn.addEventListener('click', () => {
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

    window.addEventListener('keydown', (e) => {
        if (isPaused) return;
        enemies.forEach((enemy)=>{
            if(enemy.text[0] == e.key.toUpperCase()){
                enemy.text =  enemy.text.substr(1,enemy.text.length);
                const angle = Math.atan2(enemy.y - canvas.height / 2, enemy.x - canvas.width / 2);
                const velocity = {x: Math.cos(angle) * 12, y: Math.sin(angle) * 12};
                projectiles.push(new Projectile(canvas.width / 2,canvas.height / 2,5,'white',velocity));
            }
        });
    });

    function guardarScoreEnBD(score, dificultad) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/scary-game/score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ score: score, difficulty: dificultad })
        }).catch(err => console.error('Error al guardar score:', err));
    }

    showIntro();
});
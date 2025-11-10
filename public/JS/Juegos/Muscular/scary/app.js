// DIFICULTAD: FÁCIL - palabras largas, enemigos medianos

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

function getPalabraReal(minLen, maxLen) {
    const filtradas = diccionario.filter(w => w.length >= minLen && w.length <= maxLen);
    if (filtradas.length === 0) return "PALABRA";
    return filtradas[Math.floor(Math.random() * filtradas.length)].toUpperCase();
}

const canvas = document.getElementById('scaryCanvas') || document.querySelector('canvas');
const c = canvas.getContext('2d');
const scorelbl = document.querySelector('#score');
const startGameBtn = document.querySelector('#startGameBtn');
const modal = document.querySelector('#modal');
const scoreH1 = document.querySelector('#scoreH1');
const pauseBtn = document.getElementById('pauseBtn');

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
    scoreH1.innerHTML = 0;
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
    }, 2000);// aqui es el tiempo de aparicion de los enemigos
}

function animate() {
    if (isPaused) return;
    animationID = requestAnimationFrame(animate);
    c.clearRect(0,0,canvas.width,canvas.height);
    c.fillStyle = '#affcef';
    c.fillRect(0, 0, canvas.width, canvas.height);
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
            modal.style.display = 'flex';
            scoreH1.innerHTML = score;
            clearInterval(enemyInterval);
            guardarScoreEnBD(score, 'easy');
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
            }
        });
    });
}

function animateScore() {
    const scoreDiv = document.getElementById('score');
    scoreDiv.classList.add('score-animate');
    setTimeout(() => scoreDiv.classList.remove('score-animate'), 400);
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

startGameBtn.addEventListener('click',()=>{
    modal.style.display = 'none';
    init();
    animate();
    spawnEnemy();
});

function guardarScoreEnBD(score, dificultad) {
    fetch('/scary-game/score', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            score: score,
            difficulty: dificultad
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log(data.message);
    })
    .catch(err => {
        console.error('Error al guardar score:', err);
    });
}

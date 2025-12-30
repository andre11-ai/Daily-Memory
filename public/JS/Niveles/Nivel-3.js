(function(){
  const NUM_BUTTONS = 4;
  const SPEED_BASE = 900;
  const TARGET_SCORE = 7; 
  const CURRENT_LEVEL = 3;
  const NEXT_LEVEL_URL = '/niveles/4';
  const MAP_URL = '/story';
  
  const speedByRound = r => Math.max(240, SPEED_BASE - r*28);
  const colors = ["#28cf5f","#f54f4f","#ffd43b","#2fb7ff"];
  const freqs = [329.63, 261.63, 392.00, 220.00];

  const svg = document.getElementById('board');
  const countEl = document.getElementById('count');
  const startBtn = document.getElementById('start');
  const strictBtn = document.getElementById('strict');
  const soundToggle = document.getElementById('soundToggle');
  const scoreLabel = document.getElementById('score-label');

  const modalGO = document.getElementById('modal-gameover');
  const govBubble = document.getElementById('gov-bubble');
  const govEyebrow = document.getElementById('gov-eyebrow');
  const govTitle = document.getElementById('gov-title');
  const govMsg = document.getElementById('gov-msg');
  const sContainer = document.getElementById('score-container');
  const sDisplay = document.getElementById('score-modal-display');
  const actionBtn = document.getElementById('action-btn');
  const backCont = document.getElementById('back-menu-container');

  let audioCtx = null;
  let soundEnabled = true;
  
  let sequence = [];
  let playerPos = 0;
  let playing = false;
  let strictMode = false;
  let unlocked = true;

  let score = 0;
  let gameEnded = false;

  function ensureAudio(){
    if(!audioCtx){
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }
  }

  function playTone(freq, duration=350, when=0){
    if(!soundEnabled) return;
    ensureAudio();
    const ctx = audioCtx;
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    o.type = 'sine';
    o.frequency.value = freq;
    g.gain.value = 0.0001;
    o.connect(g); g.connect(ctx.destination);
    
    const now = ctx.currentTime + when/1000;
    g.gain.setValueAtTime(0.0001, now);
    g.gain.exponentialRampToValueAtTime(0.32, now + 0.02);
    o.start(now);
    g.gain.exponentialRampToValueAtTime(0.0001, now + duration/1000 - 0.02);
    o.stop(now + duration/1000 + 0.02);
  }

  function playErrorTone(){
    if(!soundEnabled) return;
    ensureAudio();
    const ctx = audioCtx;
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    o.type = 'sawtooth';
    o.frequency.value = 110; 
    g.gain.value = 0.0001;
    o.connect(g); g.connect(ctx.destination);
    const now = ctx.currentTime;
    g.gain.setValueAtTime(0.0001, now);
    g.gain.linearRampToValueAtTime(0.4, now + 0.05);
    g.gain.linearRampToValueAtTime(0.0001, now + 0.5);
    o.start(now);
    o.stop(now + 0.6);
  }

  function polarToCartesian(cx, cy, r, angleDeg){
    const rad = (angleDeg-90) * Math.PI/180.0;
    return { x: cx + r * Math.cos(rad), y: cy + r * Math.sin(rad) };
  }

  function describeArc(cx, cy, r, startAngle, endAngle){
    const start = polarToCartesian(cx,cy,r,endAngle);
    const end = polarToCartesian(cx,cy,r,startAngle);
    const largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
    return ["M", cx, cy, "L", start.x, start.y, "A", r, r, 0, largeArcFlag, 0, end.x, end.y, "Z"].join(" ");
  }

  function buildBoard(){
    svg.innerHTML = "";
    const radius = 230;
    for(let i=0;i<NUM_BUTTONS;i++){
      const start = i * (360/NUM_BUTTONS);
      const end = (i+1) * (360/NUM_BUTTONS);
      
      const path = document.createElementNS("http://www.w3.org/2000/svg","path");
      path.setAttribute("d", describeArc(0,0,radius,start,end));
      path.setAttribute("fill", colors[i % colors.length]);
      path.setAttribute("data-index", i);
      path.classList.add('segment');
      path.setAttribute("role","button");
      path.setAttribute("aria-label","Botón " + (i+1));
      
      const overlay = document.createElementNS("http://www.w3.org/2000/svg","path");
      overlay.setAttribute("d", describeArc(0,0,radius-18,start,end));
      overlay.setAttribute("fill","rgba(0,0,0,0.06)");
      overlay.classList.add('segment-overlay');
      
      svg.appendChild(path);
      svg.appendChild(overlay);
    }
  }

  function updateCount(){
    countEl.textContent = sequence.length ? String(sequence.length).padStart(2,'0') : "--";
    score = Math.max(score, sequence.length > 0 ? sequence.length - 1 : 0);
    scoreLabel.textContent = `Score: ${score}`;
  }

  function flash(index, duration=500){
    const segs = svg.querySelectorAll('.segment');
    const seg = segs[index];
    if(!seg) return;
    seg.classList.add('active');
    playTone(freqs[index % freqs.length], duration);
    setTimeout(()=>seg.classList.remove('active'), duration+60);
  }

  async function playSequence(){
    unlocked = false;
    playing = true;
    playerPos = 0;
    updateCount();
    
    const delay = speedByRound(sequence.length);
    for(let i=0;i<sequence.length;i++){
      if(gameEnded) return; 
      const idx = sequence[i];
      flash(idx, Math.min(420, delay-60));
      await new Promise(r => setTimeout(r, delay));
    }
    unlocked = true;
    playing = false;
  }

  function handlePlayerInput(idx){
    if(!unlocked || gameEnded) return;
    if(!audioCtx) try{ audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }catch(e){}
    
    flash(idx, Math.max(200, speedByRound(sequence.length)-60));
    
    if(sequence[playerPos] === idx){
      playerPos++;
      
      if(playerPos === sequence.length){
        if(score + 1 >= TARGET_SCORE) {
            score++; 
            updateCount();
            endGame(true);
            return;
        }
        
        setTimeout(()=> { 
          addStep(); 
          playSequence(); 
        }, 700);
      }
    } else {
      unlocked = false;
      countEl.textContent = "!!";
      playErrorTone(); 
      
      setTimeout(() => {
          endGame(false);
      }, 500); 
    }
  }

  function addStep(){
    const next = Math.floor(Math.random()*NUM_BUTTONS);
    sequence.push(next);
    updateCount();
  }

  function resetGame(){
    sequence = [];
    playerPos = 0;
    unlocked = true;
    playing = false;
    score = 0;
    gameEnded = false;
    updateCount();
  }

  function startRound(){
    if(!audioCtx && window.AudioContext) { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }
    resetGame();
    addStep();
    setTimeout(()=>playSequence(), 250);
  }

  function endGame(win=false){
    gameEnded = true;
    unlocked = false;
    playing = false;
    mostrarModal(win);
    guardarScore(score);
    if(win) completeStoryLevel(CURRENT_LEVEL, score);
  }

  function showIntro() {
    modalGO.classList.remove('hidden');
    setTimeout(() => modalGO.classList.add('active'), 10);
    
    govBubble.classList.remove('win-theme', 'lose-theme');
    sContainer.classList.add('hidden'); 

    govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
    govTitle.textContent = "Simón (Fácil)";
    govMsg.innerHTML = `
        ¡Hola! Vamos a ejercitar el oído. <br>
        Meta: logra <strong>${TARGET_SCORE} rondas</strong>. <br>
        Escucha y repite la secuencia de colores.
    `;

    actionBtn.textContent = "¡Empezar!";
    actionBtn.onclick = () => {
        modalGO.classList.remove('active');
        setTimeout(() => {
            modalGO.classList.add('hidden');
            startRound();
        }, 300);
    };
    backCont.innerHTML = '';
  }

  function mostrarModal(win=false) {
    modalGO.classList.remove('hidden');
    setTimeout(()=> modalGO.classList.add('active'), 10);

    govBubble.classList.remove('win-theme', 'lose-theme');
    sContainer.classList.remove('hidden'); 
    sDisplay.textContent = score;

    if(win) {
        govBubble.classList.add('win-theme');
        govEyebrow.textContent = "¡VICTORIA!";
        govTitle.textContent = "¡Oído Absoluto!";
        govMsg.innerHTML = "Has completado la secuencia perfectamente. <br>¡Sigamos avanzando!";
        
        actionBtn.textContent = "Siguiente Nivel";
        actionBtn.onclick = () => window.location.href = NEXT_LEVEL_URL;
    } else {
        govBubble.classList.add('lose-theme');
        govEyebrow.textContent = "¡TE EQUIVOCASTE!";
        govTitle.textContent = "¡Ups!";
        govMsg.innerHTML = "La secuencia se rompió. <br>¡Inténtalo de nuevo!";
        
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

  svg.addEventListener('click', (e)=>{
    const p = e.target;
    if(!p || !p.classList.contains('segment')) return;
    const idx = Number(p.getAttribute('data-index'));
    handlePlayerInput(idx);
  });
  
  startBtn.addEventListener('click', ()=>{ showIntro(); });
  
  strictBtn.addEventListener('click', ()=>{
    strictMode = !strictMode;
    strictBtn.classList.toggle('active', strictMode);
  });
  
  soundToggle.addEventListener('click', ()=>{
    soundEnabled = !soundEnabled;
    soundToggle.classList.toggle('active', soundEnabled);
    soundToggle.textContent = soundEnabled ? 'Sonido' : 'Mute';
  });

  window.addEventListener('keydown', (e)=>{
    const map = {'1':0,'2':1,'3':2,'4':3};
    if(map[e.key] !== undefined) handlePlayerInput(map[e.key]);
  });

  function completeStoryLevel(level, scoreVal){
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    fetch('/story/complete-level', {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
      body: JSON.stringify({ level, score: scoreVal })
    }).catch(e=>console.error('Historia error:', e));
  }

  function guardarScore(scoreVal){
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    fetch('/simondice-game/score',{
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
      body:JSON.stringify({ score:scoreVal, difficulty:"easy" })
    }).catch(err=>console.error('Error guardar score:',err));
  }

  buildBoard();
  updateCount();
  
  document.addEventListener('DOMContentLoaded', ()=>{
    showIntro();
  });

  let warmed=false;
  document.addEventListener('pointerdown', ()=>{
    if(warmed) return; warmed=true;
    if(!audioCtx && window.AudioContext) { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }
    if(audioCtx && soundEnabled){
      playTone(60, 80);
    }
  }, {once:true});

})();
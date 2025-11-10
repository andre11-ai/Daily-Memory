  (function(){
    const NUM_BUTTONS = 4;
    const SPEED_BASE = 900;
    const speedByRound = r => Math.max(240, SPEED_BASE - r*28);
    const colors = ["#28cf5f","#f54f4f","#ffd43b","#2fb7ff"];
    const freqs = [329.63, 261.63, 392.00, 220.00];

    const svg = document.getElementById('board');
    const countEl = document.getElementById('count');
    const startBtn = document.getElementById('start');
    const strictBtn = document.getElementById('strict');
    const soundToggle = document.getElementById('soundToggle');

    let audioCtx = null;
    let soundEnabled = true;

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
      const gap = 6;
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

    let sequence = [];
    let playerPos = 0;
    let playing = false;
    let strictMode = false;
    let unlocked = true;
    let maxRounds = 20;

    function updateCount(){
      countEl.textContent = sequence.length ? String(sequence.length).padStart(2,'0') : "--";
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
        const idx = sequence[i];
        flash(idx, Math.min(420, delay-60));
        await new Promise(r => setTimeout(r, delay));
      }
      unlocked = true;
      playing = false;
    }

    function handlePlayerInput(idx){
      if(!unlocked) return;
      if(!audioCtx) try{ audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }catch(e){}
      flash(idx,Math.max(200, speedByRound(sequence.length)-60));
      if(sequence[playerPos] === idx){
        playerPos++;
        if(playerPos === sequence.length){
          if(sequence.length >= maxRounds){
            countEl.textContent = "WIN";
            setTimeout(()=>resetGame(), 1600);
            return;
          }
          setTimeout(()=> { addStep(); playSequence(); }, 700);
        }
      } else {
        unlocked = false;
        countEl.textContent = "!!";
        if(soundEnabled){ ensureAudio(); const ctx=audioCtx; const o=ctx.createOscillator(); const g=ctx.createGain(); o.type='sawtooth'; o.frequency.value=110; g.gain.value=0.0001; o.connect(g); g.connect(ctx.destination); const now=ctx.currentTime; g.gain.exponentialRampToValueAtTime(0.25, now+0.02); o.start(now); g.gain.exponentialRampToValueAtTime(0.0001, now+0.4); o.stop(now+0.45);}
        setTimeout(()=> {
          if(strictMode){
            resetGame();
          } else {
            playerPos = 0;
            playSequence();
          }
        }, 900);
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
      updateCount();
    }

    svg.addEventListener('click', (e)=>{
      const p = e.target;
      if(!p || !p.classList.contains('segment')) return;
      const idx = Number(p.getAttribute('data-index'));
      handlePlayerInput(idx);
    });

    startBtn.addEventListener('click', ()=>{
      if(!audioCtx && window.AudioContext) { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }
      resetGame();
      addStep();
      setTimeout(()=>playSequence(), 250);
    });

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
      if(e.key === ' '){ startBtn.click(); }
    });

    buildBoard();
    updateCount();

    let warmed=false;
    document.addEventListener('pointerdown', ()=>{
      if(warmed) return; warmed=true;
      if(!audioCtx && window.AudioContext) { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); }
      if(audioCtx && soundEnabled){
        playTone(60, 80);
      }
    }, {once:true});

  })();

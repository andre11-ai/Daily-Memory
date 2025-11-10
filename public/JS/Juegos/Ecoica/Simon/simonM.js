  (function(){
    const NUM_BUTTONS = 5;
    const SPEED_BASE = 820;
    const speedByRound = r => Math.max(220, SPEED_BASE - r*30);
    const colors = ["#28cf5f","#f54f4f","#ffd43b","#2fb7ff","#b06cff"];
    const freqs = [329.63, 261.63, 392.00, 220.00, 440.00];

    const svg = document.getElementById('board');
    const countEl = document.getElementById('count');
    const startBtn = document.getElementById('start');
    const strictBtn = document.getElementById('strict');
    const soundBtn = document.getElementById('sound');

    let audioCtx = null;
    let soundEnabled = true;

    function ensureAudio(){
      if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }
    function playTone(freq, duration=350, when=0){
      if(!soundEnabled) return;
      ensureAudio();
      const ctx = audioCtx;
      const o = ctx.createOscillator();
      const g = ctx.createGain();
      o.type = 'triangle';
      o.frequency.value = freq;
      g.gain.value = 0.0001;
      o.connect(g); g.connect(ctx.destination);
      const now = ctx.currentTime + when/1000;
      g.gain.setValueAtTime(0.0001, now);
      g.gain.exponentialRampToValueAtTime(0.28, now + 0.02);
      o.start(now);
      g.gain.exponentialRampToValueAtTime(0.0001, now + duration/1000 - 0.01);
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

    function build(){
      svg.innerHTML = "";
      const r = 230;
      for(let i=0;i<NUM_BUTTONS;i++){
        const start = i*(360/NUM_BUTTONS);
        const end = (i+1)*(360/NUM_BUTTONS);
        const p = document.createElementNS("http://www.w3.org/2000/svg","path");
        p.setAttribute("d", describeArc(0,0,r,start,end));
        p.setAttribute("fill", colors[i%colors.length]);
        p.setAttribute("data-index", i);
        p.classList.add('segment');
        svg.appendChild(p);
      }
    }

    let sequence = [];
    let playerPos = 0;
    let unlocked = true;
    let strictMode = false;
    const MAX = 20;

    function updateCount(){ countEl.textContent = sequence.length ? String(sequence.length).padStart(2,'0') : "--"; }

    function flash(idx, duration=360){
      const segs = svg.querySelectorAll('.segment');
      const s = segs[idx];
      if(!s) return;
      s.classList.add('active');
      playTone(freqs[idx % freqs.length], duration);
      setTimeout(()=> s.classList.remove('active'), duration+50);
    }

    async function playSequence(){
      unlocked = false;
      for(let i=0;i<sequence.length;i++){
        const idx = sequence[i];
        flash(idx, Math.min(420, speedByRound(sequence.length)-60));
        await new Promise(r => setTimeout(r, speedByRound(sequence.length)));
      }
      unlocked = true;
    }

    function addStep(){ sequence.push(Math.floor(Math.random()*NUM_BUTTONS)); updateCount(); }

    function reset(){
      sequence=[]; playerPos=0; unlocked=true; updateCount();
    }

    function handleInput(idx){
      if(!unlocked) return;
      if(!audioCtx && window.AudioContext) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      flash(idx, Math.max(160, speedByRound(sequence.length)-60));
      if(sequence[playerPos] === idx){
        playerPos++;
        if(playerPos === sequence.length){
          if(sequence.length >= MAX){
            countEl.textContent="WIN";
            setTimeout(()=> reset(), 1400);
            return;
          }
          setTimeout(()=>{ addStep(); playSequence(); }, 600);
        }
      } else {
        unlocked=false;
        countEl.textContent="!!";
        if(soundEnabled){ ensureAudio(); const ctx=audioCtx; const o=ctx.createOscillator(); const g=ctx.createGain(); o.type='sawtooth'; o.frequency.value=100; g.gain.value=0.0001; o.connect(g); g.connect(ctx.destination); const now=ctx.currentTime; g.gain.exponentialRampToValueAtTime(0.25, now+0.02); o.start(now); g.gain.exponentialRampToValueAtTime(0.0001, now+0.45); o.stop(now+0.5); }
        setTimeout(()=> {
          if(strictMode) reset();
          else { playerPos=0; playSequence(); }
        }, 900);
      }
    }

    svg.addEventListener('click', (e)=> {
      const p = e.target;
      if(!p || !p.classList.contains('segment')) return;
      handleInput(Number(p.getAttribute('data-index')));
    });

    startBtn.addEventListener('click', ()=>{
      if(!audioCtx && window.AudioContext) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      reset(); addStep(); setTimeout(()=>playSequence(), 200);
    });

    strictBtn.addEventListener('click', ()=>{
      strictMode = !strictMode;
      strictBtn.classList.toggle('active', strictMode);
    });
    soundBtn.addEventListener('click', ()=>{
      soundEnabled = !soundEnabled;
      soundBtn.classList.toggle('active', soundEnabled);
      soundBtn.textContent = soundEnabled ? 'Sonido' : 'Mute';
    });

    window.addEventListener('keydown', (e)=>{
      const map = {'1':0,'2':1,'3':2,'4':3,'5':4};
      if(map[e.key] !== undefined) handleInput(map[e.key]);
      if(e.key===' ') startBtn.click();
    });

    build(); updateCount();

    document.addEventListener('pointerdown', ()=>{
      if(!audioCtx && window.AudioContext) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    }, {once:true});

  })();

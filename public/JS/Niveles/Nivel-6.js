(() => {
  const TARGET_SCORE = 20; 
  const CURRENT_LEVEL = 6;
  const NEXT_LEVEL_URL = '/niveles/7';
  const MAP_URL = '/story';

  let wordData = {
      seconds: 60,
      correct: 0,
      incorrect: 0,
      total: 0,
      typed: 0
  };
  let finished = false;
  let timerInterval = null; 

  const wordList = ['Electroencefalografista','Esternocleidomastoideo','Anticonstitucionalidad','Electroencefalografía','HUMANIDADContrarrevolucionario','Interdisciplinariedad','Desoxirribonucleótido','Otorrinolaringológico','Otorrinolaringología','Electroencefalógrafo','Anticonstitucionalmente','Litofotográficamente','Circunstanciadamente','Electrocardiográficamente','Magnetoencefalografía','Aminotransferasa','Desproporcionadamente','Extraterritorialidad','Extraterritorialidad','Esternocleidooccipitomastoideo','Nacionalsindicalista','Craneofaringioma','Encephalitozoonidae','Antitauromaquia','Incomprehensibilidad','Antigubernamentalisticamente','Equisatisfactibilidad','Hipogammaglobulinemia','Bioluminiscencia','Pseudohermafroditismo','Auriculoventriculostomía','Magnetohidrodinámica',
    "consigna", "flamear", "importante", "nervios", "pelo", "señales", "piso", "temprano", "vaca",
    "zanahoria", "acierto", "bibliografía", "chupete", "defender", "eso", "pensar", "doblar", "decir",
    "encuadrar", "huso", "indeciso", "bajo", "era", "linea", "para", "antes", "en",
    "encender", "morro", "porque", "paredes", "mismo", "como", "significado", "yo", "diferenciar",
    "suyo", "mover", "ellos", "derechos", "son", "chaval", "presupuesto", "viejo", "regenerar",
    "también", "son", "rural", "salado", "decir", "procedente", "frase", "sensacional",
    "colocar", "tuvo", "tres", "por", "querer", "caliente", "aire", "simple", "bueno",
    "algunos", "sitio", "como", "jugar", "lugar", "pequeño", "nosotros", "final", "poder",
    "poner", "fuera", "casa", "otros", "leer", "donde", "mano", "todo", "puerto",
    "tuyo", "largo", "cuando", "deletrear", "arriba", "añadir", "usar", "aunque", "palabra",
    "tierra", "como", "aqui", "dijo", "debe", "un", "gran", "cada", "altura",
    "ella", "como", "con", "seguir", "hacer", "actuar", "suyo", "objeto", "tiempo",
    "preguntar", "si", "significado", "puede", "cambiar", "manera", "fueron", "sobre", "luz",
    "muchas", "generoso", "entonces", "apagar", "pupila", "necesidad", "deber", "casa",
    "escribir", "pintura", "gustar", "intentar", "asentir", "importe", "nada", "repetir",
    "perdido", "animal", "largo", "punto", "hacer", "madre", "cosa", "mundo",
    "ver", "cerca", "punto", "construir", "dos", "remoto", "viajar", "tierra", "yuxtapuesto"];

  const $ = (sel) => document.querySelector(sel);
  const $$ = (sel) => document.querySelectorAll(sel);

  const modalGO = document.getElementById('modal-gameover');
  const govBubble = document.getElementById('gov-bubble');
  const govEyebrow = document.getElementById('gov-eyebrow');
  const govTitle = document.getElementById('gov-title');
  const govMsg = document.getElementById('gov-msg');
  const sContainer = document.getElementById('score-container');
  const sDisplay = document.getElementById('score-modal-display');
  const actionBtn = document.getElementById('action-btn');
  const backCont = document.getElementById('back-menu-container');

  function shuffle(array) {
      let m = array.length, t, i;
      while (m) {
          i = Math.floor(Math.random() * m--);
          t = array[m]; array[m] = array[i]; array[i] = t;
      }
      return array;
  }

  function addWords() {
      let wordSection = $("#word-section");
      wordSection.innerHTML = "";
      $("#typebox").value = "";

      for (let i = 0; i < 200; i++) {
          let words = shuffle([...wordList]); 
          let wordSpan = document.createElement("span");
          wordSpan.textContent = words[0];
          wordSection.appendChild(wordSpan);
      }
      wordSection.firstChild.classList.add("current-word");
  }

  function checkWord(wordInput) {
      let current = $(".current-word");
      if (!current) return false;
      let currentText = current.textContent;
      let inputVal = wordInput.value;
      
      if (currentText.indexOf(inputVal.trim()) !== 0) {
          current.classList.add("incorrect-word-bg");
          return false;
      } else {
          current.classList.remove("incorrect-word-bg");
          return true;
      }
  }

  function submitWord(wordInput) {
      let current = $(".current-word");
      let val = wordInput.value.trim();
      
      if (val === current.textContent) {
          current.classList.remove("current-word", "incorrect-word-bg");
          current.classList.add("correct-word-c");
          wordData.correct += 1;
      } else {
          current.classList.remove("current-word", "incorrect-word-bg");
          current.classList.add("incorrect-word-c");
          wordData.incorrect += 1;
      }
      wordData.total = wordData.correct + wordData.incorrect;

      if (current.nextElementSibling) {
          current.nextElementSibling.classList.add("current-word");
          let ws = $("#word-section");
          if (current.offsetTop > ws.offsetHeight + ws.scrollTop - 50) {
             ws.scrollTop = current.offsetTop - 50;
          }
      }
  }

  function startTimer() {
      if (timerInterval) return;

      let timeSpan = $("#timer span");
      
      timerInterval = setInterval(() => {
          wordData.seconds--;
          let time = wordData.seconds;
          
          if (time <= 0) {
              clearInterval(timerInterval);
              timerInterval = null; 
              timeSpan.textContent = "0:00";
              finishTest();
          } else {
              let timePad = (time < 10) ? ("0" + time) : time;
              timeSpan.textContent = `0:${timePad}`;
          }
      }, 1000);
  }

  function finishTest() {
      if (finished) return;
      finished = true;
      
      if(timerInterval) {
          clearInterval(timerInterval);
          timerInterval = null;
      }

      $("#typebox").disabled = true;
      calculateWPM();
  }

  function calculateWPM() {
      let {correct, incorrect, total, typed} = wordData;
      let wpm = Math.ceil((typed / 5) - incorrect);
      let accuracy = total > 0 ? Math.ceil((correct / total) * 100) : 0;
      if (wpm < 0) wpm = 0;

      let resultsHTML = `
          <div style="text-align:center;">
            <h3>Resultados:</h3>
            <p>WPM: <strong>${wpm}</strong> | Precisión: <strong>${accuracy}%</strong></p>
          </div>
      `;
      $("#word-section").innerHTML = resultsHTML;

      const win = (wpm >= TARGET_SCORE);
      mostrarGameOver(win, wpm, accuracy);
      
      guardarScoreVelocimetro(wpm, 'easy');
      if (win) completeStoryLevel(wpm);
  }

  function typingTest(e) {
      if (finished) return;
      
      let wordInput = $("#typebox");
      
      if (!timerInterval && wordData.seconds === 60) {
          startTimer();
      }

      if (e.keyCode === 32) {
          e.preventDefault(); 
          if(wordInput.value.trim().length > 0) {
              submitWord(wordInput);
              wordInput.value = "";
          }
      } else {
          checkWord(wordInput);
      }
      
      if(e.keyCode !== 32 && e.key.length === 1) {
          wordData.typed++;
      }
  }

  function restartTest(fromIntro=false) {
      if(timerInterval) {
          clearInterval(timerInterval);
          timerInterval = null;
      }

      finished = false;
      wordData = { seconds: 60, correct: 0, incorrect: 0, total: 0, typed: 0 };
      
      $("#timer span").textContent = "1:00";
      $("#typebox").value = "";
      $("#typebox").disabled = false;
      
      if(!fromIntro) {
          $("#typebox").focus();
      }
      
      addWords();
  }

  function showIntro() {
      modalGO.classList.remove('hidden');
      setTimeout(() => modalGO.classList.add('active'), 10);

      govBubble.classList.remove('win-theme', 'lose-theme');
      sContainer.classList.add('hidden'); 

      govEyebrow.textContent = `HISTORIA · NIVEL ${CURRENT_LEVEL}`;
      govTitle.textContent = "Velocímetro";
      govMsg.innerHTML = `
          Pon a prueba tu agilidad mental y muscular. <br>
          Meta: alcanza <strong>${TARGET_SCORE} WPM</strong>. <br>
          Escribe tantas palabras como puedas en 1 minuto.
      `;

      actionBtn.textContent = "¡Empezar!";
      actionBtn.onclick = () => {
          modalGO.classList.remove('active');
          setTimeout(() => {
              modalGO.classList.add('hidden');
              restartTest(false);
              $("#typebox").focus();
          }, 300);
      };
      backCont.innerHTML = '';
  }

  function mostrarGameOver(win, wpm, acc) {
      modalGO.classList.remove('hidden');
      setTimeout(() => modalGO.classList.add('active'), 10);

      govBubble.classList.remove('win-theme', 'lose-theme');
      sContainer.classList.remove('hidden'); 
      sDisplay.textContent = wpm + " WPM"; 

      if (win) {
          govBubble.classList.add('win-theme');
          govEyebrow.textContent = "¡VICTORIA!";
          govTitle.textContent = "¡Dedos Rápidos!";
          govMsg.innerHTML = `Gran velocidad. Precisión: ${acc}%. <br>¡Sigamos avanzando!`;

          actionBtn.textContent = "Siguiente Nivel";
          actionBtn.onclick = () => window.location.href = NEXT_LEVEL_URL;
      } else {
          govBubble.classList.add('lose-theme');
          govEyebrow.textContent = "¡INTÉNTALO DE NUEVO!";
          govTitle.textContent = "¡Casi!";
          govMsg.innerHTML = `Velocidad insuficiente. Precisión: ${acc}%. <br>Relaja las manos y prueba otra vez.`;

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

  function guardarScoreVelocimetro(score, dificultad) {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      fetch('/velocimetro-game/score', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify({ score: score, difficulty: dificultad })
      }).catch(err => console.error(err));
  }

  function completeStoryLevel(scoreVal){
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      fetch('/story/complete-level', {
          method:'POST',
          headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify({ level: CURRENT_LEVEL, score: scoreVal })
      }).catch(err=>console.error(err));
  }

  $("#typebox").addEventListener('keydown', typingTest);
  $("#restart").addEventListener('click', () => {
      showIntro();
  });

  addWords();
  showIntro();

})();
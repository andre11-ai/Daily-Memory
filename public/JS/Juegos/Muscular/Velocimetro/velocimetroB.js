document.addEventListener('DOMContentLoaded', () => {
    window.$ = document.querySelectorAll.bind(document);
    
    const TARGET_WPM = 200;

    let wordList = ['Electroencefalografista','Esternocleidomastoideo','Anticonstitucionalidad','Electroencefalografía','HUMANIDADContrarrevolucionario','Interdisciplinariedad','Desoxirribonucleótido','Otorrinolaringológico','Otorrinolaringología','Electroencefalógrafo','Anticonstitucionalmente','Litofotográficamente','Circunstanciadamente','Electrocardiográficamente','Magnetoencefalografía','Aminotransferasa','Desproporcionadamente','Extraterritorialidad','Extraterritorialidad','Esternocleidooccipitomastoideo','Nacionalsindicalista','Craneofaringioma','Encephalitozoonidae','Antitauromaquia','Incomprehensibilidad','Antigubernamentalisticamente','Equisatisfactibilidad','Hipogammaglobulinemia','Bioluminiscencia','Pseudohermafroditismo','Auriculoventriculostomía','Magnetohidrodinámica',
        "consigna", "flamear", "importante", "nervios", "pelo", "señales", "piso", "temprano", "vaca",
        "zanahoria", "acierto", "bibliografía", "chupetin", "defensa", "ese", "fink", "doblar", "dije",
        "marco", "huso", "despertar", "trocar", "amarillo", "billar", "ciudadano", "patinaje", "facilidad",
        "flash", "formacion", "adelante", "gigante", "tripa", "kit", "capas", "significado", "clavo",
        "opcion", "tierra", "lluvia", "sombra", "pronto", "sed", "desgarro", "tigre", "titulo",
        "salvaje", "animado", "ciego", "boceto", "el", "caer", "hueso", "juguete", "paseo", "viento"
    ];

    let wordData = { seconds: 60, correct: 0, incorrect: 0, total: 0, typed: 0 };
    let timer = null; 
    let gameActive = false;

    const modal = document.getElementById('modal-gameover');
    const govBubble = document.getElementById('gov-bubble');
    const govTitle = document.getElementById('gov-title');
    const govMsg = document.getElementById('gov-msg');
    const govEyebrow = document.getElementById('gov-eyebrow');
    const scoreContainer = document.getElementById('score-container');
    const scoreDisplay = document.getElementById('score-modal-display');
    const actionBtn = document.getElementById('action-btn');
    const typebox = document.getElementById('typebox');
    const restartBtn = document.getElementById('restart');


    function shuffle(array) {
        let m = array.length, t, i;
        while (m) {
            i = Math.floor(Math.random() * m--);
            t = array[m];
            array[m] = array[i];
            array[i] = t;
        }
        return array;
    }

    function addWords() {
        $("#word-section")[0].innerHTML = "";
        $("#typebox")[0].value = "";
        
        shuffle(wordList);
        wordList.forEach(word => {
            let span = document.createElement("span");
            span.innerHTML = word;
            $("#word-section")[0].appendChild(span);
        });
        $("#word-section")[0].firstChild.classList.add("current-word");
    }

    function isTimer(seconds) {
        let time = seconds;
        if (timer !== null) return false;

        timer = setInterval(() => {
            if (time > 0) {
                time--;
                $("#timer")[0].innerHTML = time < 10 ? "0:0" + time : "0:" + time;
                wordData.seconds = time;
            } else {
                clearInterval(timer);
                timer = null;
                finishTest();
            }
        }, 1000);
        return true;
    }

    function checkWord(word) {
        let w = $("#word-section")[0].children[0]; 
        let val = word.value;
        let wVal = w.innerHTML;

        if (word.value.length > w.innerHTML.length) {
            w.classList.add("incorrect-word-c");
        } else if (wVal.indexOf(val) === 0) {
            w.classList.remove("incorrect-word-c");
        } else {
            w.classList.add("incorrect-word-c");
        }
    }

    function submitWord(word) {
        let w = $("#word-section")[0].children[0];
        let val = word.value.trim();
        let wVal = w.innerHTML;

        if (val === wVal) {
            w.classList.add("correct-word-c");
            wordData.correct += 1;
        } else {
            w.classList.add("incorrect-word-c");
            wordData.incorrect += 1;
        }
        wordData.total += 1;
        
        $("#word-section")[0].removeChild(w);
        $("#word-section")[0].appendChild(w);
        $("#word-section")[0].children[0].classList.add("current-word");
    }

    function clearLine() {
        let wordSection = $("#word-section")[0];
        if (wordSection.scrollTop + wordSection.offsetHeight >= wordSection.scrollHeight) {
            wordSection.scrollTop = 0;
        }
    }

    function typingTest(e) {
        if (!gameActive) return; 

        e = e || window.event;
        let kcode = e.keyCode;
        let word = $("#typebox")[0];

        if (word.value.match(/^\s/g)) {
            word.value = "";
        } else {
            isTimer(wordData.seconds);
            
            checkWord(word);
            if (kcode == 32) { 
                submitWord(word);
                clearLine();
                $("#typebox")[0].value = "";
            }
            wordData.typed += 1;
        }
    }


    function finishTest() {
        gameActive = false;
        typebox.disabled = true;

        let wpm = Math.floor((wordData.typed / 5) - wordData.incorrect);
        if (wpm < 0) wpm = 0;
        
        guardarScoreVelocimetro(wpm, 'easy');

        showGameOver(wpm);
    }

    function showIntro() {
        wordData = { seconds: 60, correct: 0, incorrect: 0, total: 0, typed: 0 };
        if(timer) { clearInterval(timer); timer = null; }
        $("#timer")[0].innerHTML = "1:00";
        addWords();

        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);
        
        bubble = govBubble; 
        bubble.className = "speech-bubble";
        scoreContainer.classList.add('hidden');

        govEyebrow.textContent = "VELOCÍMETRO";
        govTitle.textContent = "Nivel Fácil";
        govMsg.innerHTML = `Escribe las palabras lo más rápido que puedas.<br>Meta: <strong>${TARGET_WPM} Palabras por Minuto (WPM)</strong>.`;
        actionBtn.textContent = "¡Empezar!";

        actionBtn.onclick = () => {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.classList.add('hidden');
                gameActive = true;
                typebox.disabled = false;
                typebox.value = "";
                typebox.focus();
            }, 300);
        };
    }

    function showGameOver(wpm) {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('active'), 10);

        scoreContainer.classList.remove('hidden');
        scoreDisplay.textContent = wpm;

        let win = wpm >= TARGET_WPM;

        if (win) {
            govBubble.className = "speech-bubble win-theme";
            govEyebrow.textContent = "¡EXCELENTE!";
            govTitle.textContent = "¡Meta Superada!";
            govMsg.innerHTML = "Tus dedos vuelan sobre el teclado.";
            actionBtn.textContent = "Jugar de nuevo";
        } else {
            govBubble.className = "speech-bubble lose-theme";
            govEyebrow.textContent = "SIGUE PRACTICANDO";
            govTitle.textContent = "Buen intento";
            govMsg.innerHTML = "Intenta escribir con más precisión y velocidad.";
            actionBtn.textContent = "Reintentar";
        }

        actionBtn.onclick = () => {
            showIntro();
        };
    }

    function guardarScoreVelocimetro(score, dificultad) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        fetch('/velocimetro-game/score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ score: score, difficulty: dificultad })
        }).catch(err => console.error(err));
    }

    typebox.addEventListener('keyup', typingTest);
    
    restartBtn.addEventListener('click', () => {
        showIntro(); 
    });

    showIntro();
});
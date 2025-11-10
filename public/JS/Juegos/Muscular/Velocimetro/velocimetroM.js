window.$ = document.querySelectorAll.bind(document);

if (navigator.userAgent.match(/firefox/i)) {
    let ffBtn = "font-weight: normal; font-size: 2em; margin-left: 0.3em;";
    $("#restart-symbol")[0].setAttribute("style", ffBtn);

    let ffwait = "line-height: 1em; font-size: 4em;";
    $(".waiting")[0].setAttribute("style", ffwait);
}

let wordList = ['Electroencefalografista','Esternocleidomastoideo','Anticonstitucionalidad','Electroencefalografía','HUMANIDADContrarrevolucionario','Interdisciplinariedad','Desoxirribonucleótido','Otorrinolaringológico','Otorrinolaringología','Electroencefalógrafo','Anticonstitucionalmente','Litofotográficamente','Circunstanciadamente','Electrocardiográficamente','Magnetoencefalografía','Aminotransferasa','Desproporcionadamente','Extraterritorialidad','Extraterritorialidad','Esternocleidooccipitomastoideo','Nacionalsindicalista','Craneofaringioma','Encephalitozoonidae','Antitauromaquia','Incomprehensibilidad','Antigubernamentalisticamente','Equisatisfactibilidad','Hipogammaglobulinemia','Bioluminiscencia','Pseudohermafroditismo','Auriculoventriculostomía','Magnetohidrodinámica',
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
    "ver", "cerca", "punto", "construir", "dos", "remoto", "viajar", "tierra", "yuxtapuesto",
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
    "ver", "cerca", "punto", "construir", "dos", "remoto", "viajar", "tierra", "yuxtapuesto",
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
    let wordSection = $("#word-section")[0];
    wordSection.innerHTML = "";
    $("#typebox")[0].value = "";

    for (let i = 350; i > 0; i--) {
        let words = shuffle(wordList);
        let wordSpan = `<span>${words[i]}</span>`;
        wordSection.innerHTML += wordSpan;
    }
    wordSection.firstChild.classList.add("current-word");


}



let colorCurrentWord =" #dddddd";
let colorCorrectWord = "#93C572";
let colorIncorrectWord = "#e50000";

let wordData = {
    seconds: 60,
    correct: 0,
    incorrect: 0,
    total: 0,
    typed: 0
};



function checkWord(word) {
    let wlen = word.value.length;
    let current = $(".current-word")[0];
    let currentSubstring = current.innerHTML.substring(0, wlen);
    if (word.value.trim() != currentSubstring) {
        current.classList.add("incorrect-word-bg");
        return false;
    } else {
        current.classList.remove("incorrect-word-bg");
        return true;
    }
}

function submitWord(word) {

    let current = $(".current-word")[0];

    if (checkWord(word)) {
        current.classList.remove("current-word");
        current.classList.add("correct-word-c");
        wordData.correct += 1;
    } else {
        current.classList.remove("current-word", "incorrect-word-bg");
        current.classList.add("incorrect-word-c");
        wordData.incorrect += 1;
    }
    wordData.total = wordData.correct + wordData.incorrect;

    current.nextSibling.classList.add("current-word");
}

function clearLine() {
    let wordSection = $("#word-section")[0];
    let current = $(".current-word")[0];
    let previous = current.previousSibling;
    let children = $(".correct-word-c, .incorrect-word-c").length;

    if (current.offsetTop > previous.offsetTop) {
        for (let i = 0; i < children; i++) {
            wordSection.removeChild(wordSection.firstChild);
        }
    }
}

function isTimer(seconds) {

    let time = seconds;
    let one = $("#timer > span")[0].innerHTML;
    if (one == "1:00") {
        let typingTimer = setInterval(() => {
            if (time <= 0) {
                clearInterval(typingTimer);
                finishTest();
            } else {
                time -= 1;
                let timePad = (time < 10) ? ("0" + time) : time; // zero padded
                $("#timer > span")[0].innerHTML = `0:${timePad}`;
            }
        }, 1000);
    } else if (one == "0:00") {return false;}
    return true;
}

function sendAnalytics(wpm, accuracy, total, correct, incorrect, typed) {
    result = wpm+"-"+accuracy+"-"+total+"-"+correct+"-"+incorrect+"-"+typed;

    //ga('send', 'event', 'Typetest', 'result', result);

    //clicky.log('typetest/#result', result);
}

var finished = false;
function finishTest() {
    if (finished) {
        return;
    } else {
        finished = true;
    }

    calculateWPM(wordData);
}

function calculateWPM(data) {
    let {seconds, correct, incorrect, total, typed} = data;
    let min = (seconds / 60);
    let wpm = Math.ceil((typed / 5) - (incorrect) / min);
    let accuracy = Math.ceil((correct / total) * 100);

    if (wpm < 0) {wpm = 0;}
    let results = `<ul id="results">
        <li>Puntos: <span class="wpm-value">${wpm}</span></li>
        <li>Porcentaje: <span class="wpm-value">${accuracy}%</span></li>
        <li id="results-stats">
        Total de palabras: <span>${total}</span> |
        Palabras Correctas: <span>${correct}</span> |
        Palabras Incorrectas: <span>${incorrect}</span> |
        Tipos de Caracteres: <span>${typed}</span>
        </li>
        </ul>`;

    $("#word-section")[0].innerHTML = results;

    let wpmClass = $("li:nth-child(2) .wpm-value")[0].classList;
    if (accuracy > 80) {wpmClass.add("correct-word-c");}
    else { wpmClass.add("incorrect-word-c");}

    sendAnalytics(wpm, accuracy, total, correct, incorrect, typed);
    console.log(wordData);
    guardarScoreVelocimetro(wpm, 'medium');
}

function typingTest(e) {

    e = e || window.event;
    let kcode = e.keyCode;
    let word = $("#typebox")[0];

    if (word.value.match(/^\s/g)) {
        word.value = "";
    } else {
        if (isTimer(wordData.seconds)) {
            checkWord(word);
            if (kcode == 32) {
                submitWord(word);
                clearLine();
                $("#typebox")[0].value = "";
            }
            wordData.typed += 1;
        } else {
            finishTest();
        }
    }
}

function restartTest() {
    finished = false;
    $("#typebox")[0].value = "";
    location.reload();
}

function guardarScoreVelocimetro(score, dificultad) {
    fetch('/velocimetro-game/score', {
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
    .then(data => console.log(data.message))
    .catch(err => console.error('Error al guardar score:', err));
}

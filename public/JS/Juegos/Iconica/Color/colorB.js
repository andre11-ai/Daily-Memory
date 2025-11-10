// Constantes y Variables Globales
const RUTA_IMAGENES = '/img/colorImages/';
const TODOS_COLORES = [
    'amarillo', 'anaranjado', 'azul', 'cafe', 'morado',
    'negro', 'rojo', 'rosa', 'turquesa', 'verde'
];

let coloresCorrectos = [];
let seleccionUsuario = [];
let cantidadCorrecta = 0;
let imagenNivel = '';

// Elementos del DOM
let faseMemorizar = document.getElementById('memorize-phase');
let faseSeleccionar = document.getElementById('select-phase');
let rejillaMemorizar = document.getElementById('memorize-grid');
let rejillaSeleccionar = document.getElementById('select-grid');

// Funciones Principales ---
function initGame() {
    let contenedorJuego = document.getElementById('game-container');
    let levelCode = contenedorJuego.dataset.level; // 'F', 'M' o 'D'

    if (levelCode === 'M') {
        coloresCorrectos = ['negro', 'amarillo', 'cafe', 'turquesa', 'rojo'];
        cantidadCorrecta = 5;
        imagenNivel = 'level2.jpeg';
    } else if (levelCode === 'D') {
        coloresCorrectos = ['morado', 'anaranjado', 'amarillo', 'verde', 'negro', 'rosa'];
        cantidadCorrecta = 6;
        imagenNivel = 'level3.jpeg';
    } else {
        // Nivel Fácil
        coloresCorrectos = ['turquesa', 'rojo', 'amarillo', 'azul'];
        cantidadCorrecta = 4;
        imagenNivel = 'level1.jpeg';
    }

    drawBoard();
}

function drawBoard() {
    //Fase Memorizar: Dibujar la imagen de nivel
    rejillaMemorizar.innerHTML = `
        <img src="${RUTA_IMAGENES}${imagenNivel}" alt="Memoriza los colores" class="level-image">
    `;

    // Fase Seleccionar: Dibujar las 10 opciones
    rejillaSeleccionar.innerHTML = ''; // Limpiar
    let coloresOpciones = shuffle([...TODOS_COLORES]);

    for (let i = 0; i < coloresOpciones.length; i++) {
        let colorName = coloresOpciones[i];

        rejillaSeleccionar.innerHTML += `
            <button class="color-card-option"
                    id="card_${colorName}"
                    onclick="pressColorCard('${colorName}')">
                <img src="${RUTA_IMAGENES}${colorName}.png" alt="${colorName}">
            </button>
        `;
    }
}

// Funciones de Eventos (Onclick) ---

function pressReady() {
    faseMemorizar.style.display = 'none';
    faseSeleccionar.style.display = 'block';
}

function pressColorCard(colorName) {
    let card = document.getElementById('card_' + colorName);
    let index = seleccionUsuario.indexOf(colorName);

    if (index > -1) {
        seleccionUsuario.splice(index, 1);
        card.classList.remove('selected');
    } else {
        seleccionUsuario.push(colorName);
        card.classList.add('selected');
    }
}

function pressVerify() {
    if (seleccionUsuario.length !== cantidadCorrecta) {
        Swal.fire('¡Espera!', `Debes seleccionar ${cantidadCorrecta} colores.`, 'warning');
        return;
    }

    // Ya no se detiene el temporizador
    let sortedUser = [...seleccionUsuario].sort();
    let sortedCorrect = [...coloresCorrectos].sort();

    let esCorrecto = true;
    for (let i = 0; i < cantidadCorrecta; i++) {
        if (sortedUser[i] !== sortedCorrect[i]) {
            esCorrecto = false;
            break;
        }
    }

    if (esCorrecto) {
        Swal.fire({
            title: '¡Buen trabajo!',
            text: '¡Has acertado todos los colores!',
            icon: 'success',
            confirmButtonText: '¡Genial!'
        }).then(() => { location.reload(); });
    } else {
        Swal.fire({
            title: 'Vuelve a intentarlo',
            text: 'Esa no es la combinación correcta.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            // En lugar de recargar, se puede resetear la selección
            location.reload();
        });
    }

    lockGame();
}

// Funciones Auxiliares

function lockGame() {
    let cards = document.getElementsByClassName('color-card-option');
    for (let i = 0; i < cards.length; i++) {
        cards[i].disabled = true; // Deshabilitar el botón
        cards[i].onclick = null;
    }
}

function shuffle(array) {
    return array.sort(() => Math.random() - 0.5);
}

// --- 5. Inicialización ---
document.addEventListener('DOMContentLoaded', initGame);

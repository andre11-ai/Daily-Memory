const RUTA_IMAGENES = '/img/imageMemorama/';
let nivel = 4; // Nivel por defecto (4, 6, u 8)
let todasLasImagenes = [1, 2, 3, 4, 5, 6, 7, 8];

let secuenciaCorrecta = []; // La secuencia a memorizar (ej. [3, 1, 4, 2])
let imagenesPiscina = [];  // La secuencia de abajo (ej. [1, 2, 3, 4])
let secuenciaUsuario = []; // Los slots de arriba (ej. [null, null, null, null])

// --- Elementos del DOM (como en memorama.js) ---
let rejillaMemorizar = document.getElementById('memorize-grid');
let rejillaSlots = document.getElementById('slot-grid');
let rejillaPiscina = document.getElementById('pool-grid');
let faseMemorizar = document.getElementById('memorize-phase');
let faseRecordar = document.getElementById('recall-phase');


//  Toma un array y lo desordena al azar.
function shuffle(array) {
    return array.sort(function() { return Math.random() - 0.5 });
}


// Prepara el juego al cargar la página. Lee el nivel, crea las secuencias de imágenes y llama a drawBoard.

function initGame() {

    // 1. Leer el nivel del HTML
    let contenedorJuego = document.getElementById('game-container');

    // Usamos 'if / else if' para leer el 'data-level'
    if (contenedorJuego.dataset.level == 6) {
        nivel = 6;
    } else if (contenedorJuego.dataset.level == 8) {
        nivel = 8;
    } else {
        nivel = 4; // Nivel fácil
    }

    // 2. Crear las secuencias
    // Selecciona N imágenes al azar de las 8 totales
    let imagenesJuego = shuffle([...todasLasImagenes]).slice(0, nivel);

    // Crea la secuencia a memorizar
    secuenciaCorrecta = shuffle([...imagenesJuego]);

    // Crea la secuencia de la piscina
    imagenesPiscina = shuffle([...imagenesJuego]);

    // 3. Llenar la secuencia del usuario con 'null' (espacios vacíos)
    for (let i = 0; i < nivel; i++) {
        secuenciaUsuario.push(null);
    }

    // 4. Dibujar el tablero
    drawBoard();
}

// Pone el HTML de las imágenes en las 3 secciones (memorizar, slots vacíos, piscina).

function drawBoard() {

    // 1. Dibujar fase de memorizar (las imágenes de arriba)
    rejillaMemorizar.innerHTML = ''; // Limpiar por si acaso
    for (let i = 0; i < secuenciaCorrecta.length; i++) {
        let idImagen = secuenciaCorrecta[i];
        // Añade el HTML de la imagen
        rejillaMemorizar.innerHTML += `
            <div class="image-box">
                <img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image">
            </div>
        `;
    }

    // 2. Dibujar slots vacíos (con onclick="pressSlot(i)")
    rejillaSlots.innerHTML = '';
    for (let i = 0; i < nivel; i++) {
        rejillaSlots.innerHTML += `
            <div class="slot-box" id="slot_${i}" onclick="pressSlot(${i})">
                <!-- Vacío -->
            </div>
        `;
    }

    // 3. Dibujar piscina de imágenes
    rejillaPiscina.innerHTML = '';
    for (let i = 0; i < imagenesPiscina.length; i++) {
        let idImagen = imagenesPiscina[i];
        // Usamos IDs únicos (pool_0, pool_1...)
        rejillaPiscina.innerHTML += `
            <div class="image-box" id="pool_${i}" onclick="pressPoolImage(${i}, ${idImagen})">
                <img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image">
            </div>
        `;
    }
}

// --- Funciones de Botones (llamadas desde el HTML) ---

// Oculta la fase de memorizar y muestra la de juego.
function pressReady() {
    faseMemorizar.style.display = 'none'; // Oculta la sección de memorizar
    faseRecordar.style.display = 'block';  // Muestra la sección de juego
}

// Mueve una imagen de la piscina (abajo) al primer slot vacío (arriba).
function pressPoolImage(indicePiscina, idImagen) {

    // 1. Encontrar el primer slot vacío (que tenga 'null')
    let indiceSlot = -1; // -1 significa "no encontrado"
    for (let i = 0; i < secuenciaUsuario.length; i++) {
        if (secuenciaUsuario[i] == null) {
            indiceSlot = i;
            break; // Detener el bucle, ya encontramos uno
        }
    }

    // 2. Si no hay slots vacíos (indiceSlot sigue -1), no hacer nada
    if (indiceSlot == -1) {
        return;
    }

    // 3. Poner la imagen en el slot (en nuestro array de JS)
    secuenciaUsuario[indiceSlot] = idImagen;

    // 4. Actualizar el HTML (estilo memorama, con getElementById)
    let cajaSlot = document.getElementById("slot_" + indiceSlot);
    cajaSlot.innerHTML = `<img src="${RUTA_IMAGENES}${idImagen}.png" alt="Imagen ${idImagen}" class="game-image">`;

    // 5. Ocultar el botón de la piscina (no deshabilitar, solo ocultar)
    let cajaPiscina = document.getElementById("pool_" + indicePiscina);
    cajaPiscina.style.visibility = 'hidden';
}

//  Mueve una imagen de un slot (arriba) de vuelta a su lugar en la piscina (abajo).
function pressSlot(indiceSlot) {

    // 1. Obtener la imagen que está en el slot
    let idImagen = secuenciaUsuario[indiceSlot];

    // 2. Si el slot está vacío (idImagen es null)
    if (idImagen == null) {
        return;
    }

    // 3. Quitar la imagen del array del usuario
    secuenciaUsuario[indiceSlot] = null;

    // 4. Actualizar el HTML del slot
    let cajaSlot = document.getElementById("slot_" + indiceSlot);
    cajaSlot.innerHTML = ''; // Borra la imagen

    // 5. Encontrar el botón de la piscina que coincide con esa imagen y volver a mostrarlo
    for (let i = 0; i < imagenesPiscina.length; i++) {

        // Compara si la imagen de la piscina (ej. 3) es la misma que la del slot (ej. 3)
        if (imagenesPiscina[i] == idImagen) {
            let cajaPiscina = document.getElementById("pool_" + i);
            cajaPiscina.style.visibility = 'visible'; // Vuelve a mostrarla
            break; // Detener el bucle
        }
    }
}

// Compara la secuencia del usuario con la secuencia correcta y muestra un mensaje de victoria o derrota.
function pressVerify() {

    // 1. Revisar si el usuario llenó todos los slots
    for (let i = 0; i < secuenciaUsuario.length; i++) {
        if (secuenciaUsuario[i] == null) {
            Swal.fire('¡Espera!', 'Debes llenar todos los espacios antes de verificar.', 'warning');
            return; // Salir de la función
        }
    }

    // 2. Comparar las secuencias (la correcta vs la del usuario)
    let esCorrecto = true; // Asumir que está bien
    for (let i = 0; i < nivel; i++) {

        // if (3 != 1) -> esCorrecto se vuelve 'false'
        if (secuenciaUsuario[i] != secuenciaCorrecta[i]) {
            esCorrecto = false;
            break; // Salir del bucle, ya sabemos que está mal
        }
    }

    // 3. Mostrar resultado (usando 'if / else' como en tu memorama)
    if (esCorrecto == true) {
        Swal.fire({
            title: '¡Excelente!',
            text: '¡Memorizaste la secuencia perfectamente!',
            icon: 'success',
            confirmButtonText: 'Jugar de nuevo'
        }).then(function() { // Uso 'function()' en lugar de '=>'
            location.reload();
        });
    } else { // Si esCorrecto es 'false'
        Swal.fire({
            title: '¡Casi!',
            text: 'Esa no es la secuencia correcta. ¡Inténtalo de nuevo!',
            icon: 'error',
            confirmButtonText: 'Reintentar'
        }).then(function() {
            // Reiniciar solo los slots (vacía todo lo que el usuario puso)
            for (let i = 0; i < nivel; i++) {
                pressSlot(i); // Llama a la función para vaciar cada slot
            }
        });
    }
}

//Llamar a la función principal
initGame();

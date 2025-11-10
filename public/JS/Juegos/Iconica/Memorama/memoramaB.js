// inicializacion de variables
let showTarjet = 0;
let tarjet1 = null;
let tarjet2 = null;
let primerResultado = null;
let segundoResultado = null;
let movimientos = 0;
let aciertos = 0;
let time = false;
let timer = 120; // por defecto inicia en 120
let tiempoRegresivoId = null;

// Constante para la ruta de imágenes
const RUTA_IMAGENES = '/img/imageMemorama/';

// Del documento html
let showMov = document.getElementById('Movimientos');
let showAc = document.getElementById('Aciertos');
let showTi = document.getElementById('t-restante');

// Esta función se ejecuta cuando el HTML está listo
document.addEventListener('DOMContentLoaded', () => {
    // Busca el contenedor del juego para leer el data-time
    const gameContainer = document.getElementById('game-container');
    if (gameContainer) {
        // Obtiene el tiempo del atributo 'data-time' y lo convierte a número
        // 120 (fácil), 90 (medio), 60 (difícil)
        const attTimer = parseInt(gameContainer.dataset.time);
        timer = (!isNaN(attTimer) && attTimer > 0) ? attTimer : 120;
    } else {
        timer = 120;
    }

    // Inicializa los marcadores en la pantalla
    showTi.innerHTML = `Tiempo: ${timer} s`;
    showMov.innerHTML = `Movimientos: 0`;
    showAc.innerHTML = `Aciertos: 0`;
});

// Array para 8 imágenes (16 tarjetas)
let numbers = [1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8];
numbers = numbers.sort(()=>{return Math.random()-0.5});

// Funciones
function countTime(){
    showTi.innerHTML = `Tiempo: ${timer} s`; // Refresca en pantalla, por si no fue inicializado
    tiempoRegresivoId = setInterval(()=>{
        if(timer > 0){
            timer--;
            showTi.innerHTML = `Tiempo: ${timer} s`;
        }
        if(timer <= 0){
            showTi.innerHTML = `Tiempo: 0 s`; // Nunca negativo
            clearInterval(tiempoRegresivoId);
            blockTarjet();
            // Alerta de SweetAlert
            Swal.fire({
                title: '¡Tiempo Agotado!',
                text: 'No lograste completar el juego.',
                icon: 'error',
                confirmButtonText: 'Reintentar'
            }).then(() => {
                location.reload(); // Recargar la página
            });
        }
    }, 1000)
}

function blockTarjet(){
    for(let i=0; i<numbers.length; i++){ // Bucle hasta numbers.length (16)
        let blockTarjet = document.getElementById(i);
        // Ruta de imagen y extensión .png
        blockTarjet.innerHTML = `<img src="${RUTA_IMAGENES}${numbers[i]}.png" alt="">`;
        blockTarjet.disabled = true;
    }
}

//FUNCION PRINCIPAL
function show(id){
    if(time == false){
        countTime();
        time = true;
    }
    showTarjet++;

    if(showTarjet == 1){
        //mostrar tarjeta 1
        tarjet1 = document.getElementById(id);
        primerResultado = numbers[id];
        // Ruta de imagen y extensión .png
        tarjet1.innerHTML = `<img src="${RUTA_IMAGENES}${primerResultado}.png" alt="">`;
        tarjet1.disabled = true;
    }
    //mostrar segunda tarjeta
    else if(showTarjet == 2){
        tarjet2 = document.getElementById(id);
        segundoResultado = numbers[id];
        // Ruta de imagen y extensión .png
        tarjet2.innerHTML = `<img src="${RUTA_IMAGENES}${segundoResultado}.png" alt="">`;
        tarjet2.disabled = true;

        //Incerementar movimientos
        movimientos++;
        showMov.innerHTML = `Movimientos: ${movimientos}`;

        if(primerResultado == segundoResultado){
            showTarjet = 0;

            //Aumentar aciertos
            aciertos++;
            showAc.innerHTML = `Aciertos: ${aciertos}`;

            //VERIFICAR SI GANO
            if(aciertos == 8){
                clearInterval(tiempoRegresivoId);
                showAc.innerHTML = `Aciertos: ${aciertos}`
                showMov.innerHTML = `Movimientos: ${movimientos}`
                Swal.fire({
                    title: '¡Felicidades, Ganaste!',
                    text: `Lo lograste en ${movimientos} movimientos.`,
                    icon: 'success',
                    confirmButtonText: '¡Genial!'
                });
            }
        }
        else{
            setTimeout(()=>{
                tarjet1.innerHTML = '';
                tarjet2.innerHTML = '';
                tarjet1.disabled = false;
                tarjet2.disabled = false;
                showTarjet = 0;
            }, 800);
        }
    }
}

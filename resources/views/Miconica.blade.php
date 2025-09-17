<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Iconica | MemoryMaster</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/niveles.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="bg_animate">
         <nav>
            <h1>Memoria Iconica</h1>
            <div class="nav__list">
                <a href="/tipodememoria">
                    <i class='bx bx-left-arrow-alt'></i>
                    Volver
                </a>
            </div>
        </nav>

        <div class="subtitulo">
            <h2>Escoge un juego</h2>
            <p>
                <i class='bx bx-signal-1'></i>Fácil
                <i class='bx bx-signal-2' ></i>Medio
                <i class='bx bx-signal-3' ></i>Difícil
            </p>
        </div>

        <section>
            <div class="drop">
                <div class="drop__container" id="drop-items">
                    <div class="drop__card">
                        <div class="drop__data">
                            <i class='bx bx-palette drop__img'></i>
                            <div>
                                <h1 class="drop__name">Simon</h1>
                                <span class="drop__profession">Escoge un nivel</span>
                            </div>
                        </div>

                        <div>
                          <a href="/games/visual/Simon/simonbajo" class="drop__social"><i class='bx bx-signal-1'></i></a>
                          <a href="/games/visual/Simon/simonmedio" class="drop__social"><i class='bx bx-signal-2' ></i></a>
                          <a href="/games/visual/Simon/simondificil" class="drop__social"><i class='bx bx-signal-3' ></i></a>
                        </div>
                    </div>

                    <div class="drop__card">
                        <div class="drop__data">
                            <i class='bx bx-sort-z-a drop__img'></i>
                            <div>
                                <h1 class="drop__name">Palabras Invertidas</h1>
                                <span class="drop__profession">Escoge un nivel</span>
                            </div>
                        </div>

                        <div>
                            <a href="/games/visual/PalabrasInvertidas/inverbajo" class="drop__social"><i class='bx bx-signal-1'></i></a>
                            <a href="/games/visual/PalabrasInvertidas/invermedio" class="drop__social"><i class='bx bx-signal-2' ></i></a>
                            <a href="/games/visual/PalabrasInvertidas/inveralto" class="drop__social"><i class='bx bx-signal-3' ></i></a>
                        </div>
                    </div>

                    <div class="drop__card">
                        <div class="drop__data">
                            <i class='bx bx-border-all drop__img'></i>
                            <div>
                                <h1 class="drop__name">Sodoku</h1>
                                <span class="drop__profession">Escoge un nivel</span>
                            </div>
                        </div>

                        <div>
                            <a href="/games/visual/Sodoku/sodindex" class="drop__social"><i class='bx bx-signal-1'></i></a>
                            <a href="/games/visual/Sodoku/sodmedio" class="drop__social"><i class='bx bx-signal-2' ></i></a>
                            <a href="/games/visual/Sodoku/sodalto" class="drop__social"><i class='bx bx-signal-3' ></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dos">
                <img src="./img/pngwing.com.png" alt="cerebro" class="cerebro2">
            </div>
        </section>

        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </header>
</body>
</html>

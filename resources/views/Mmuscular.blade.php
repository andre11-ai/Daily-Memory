<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Memoria Muscular | MemoryMaster</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('CSS/niveles.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    <header class="bg_animate">
        <nav>
            <h1>Memoria Muscular</h1>
            <div class="nav__list">
                <a href="{{ url('/tipodememoria') }}">
                    <i class='bx bx-left-arrow-alt'></i>
                    Volver
                </a>
            </div>
        </nav>

        <div class="subtitulo">
            <h2>Escoge un juego</h2>
            <p>
                <i class='bx bx-signal-1'></i>Fácil
                <i class='bx bx-signal-2'></i>Medio
                <i class='bx bx-signal-3'></i>Difícil
            </p>
        </div>

        <section>
            <div class="drop">
                <div class="drop__container" id="drop-items">
                    <div class="drop__card">
                        <div class="drop__data">
                            <i class='bx bx-text drop__img'></i>
                            <div>
                                <h1 class="drop__name">Scary Witch Typing</h1>
                                <span class="drop__profession">Escoge un nivel</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('/games/muscular/Sacry/scaryfacil') }}" class="drop__social"><i class='bx bx-signal-1'></i></a>
                            <a href="{{ url('/games/muscular/Sacry/scarymedio') }}" class="drop__social"><i class='bx bx-signal-2'></i></a>
                            <a href="{{ url('/games/muscular/Sacry/scarydificil') }}" class="drop__social"><i class='bx bx-signal-3'></i></a>
                        </div>
                    </div>
                    <div class="drop__card">
                        <div class="drop__data">
                            <i class='bx bx-joystick-alt drop__img'></i>
                            <div>
                                <h1 class="drop__name">Velocímetro</h1>
                                <span class="drop__profession">Escoge un nivel</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('/games/muscular/test/testfacil') }}" class="drop__social"><i class='bx bx-signal-1'></i></a>
                            <a href="{{ url('/games/muscular/test/testmedio') }}" class="drop__social"><i class='bx bx-signal-2'></i></a>
                            <a href="{{ url('/games/muscular/test/testdificil') }}" class="drop__social"><i class='bx bx-signal-3'></i></a>
                        </div>
                    </div>
                    <div class="drop__card">
                        <div class="drop__data">
                            <i class='bx bx-font drop__img'></i>
                            <div>
                                <h1 class="drop__name">Juego Hexamano</h1>
                                <span class="drop__profession">Escoge un nivel</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ url('/games/muscular/Hexamano/hexbajo') }}" class="drop__social"><i class='bx bx-signal-1'></i></a>
                            <a href="{{ url('/games/muscular/Hexamano/hexmedio') }}" class="drop__social"><i class='bx bx-signal-2'></i></a>
                            <a href="{{ url('/games/muscular/Hexamano/hexdificil') }}" class="drop__social"><i class='bx bx-signal-3'></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dos">
                <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro" class="cerebro2">
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

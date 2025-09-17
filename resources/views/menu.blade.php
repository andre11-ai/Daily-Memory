<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menu | Daily Memory</title>
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
    <!-- FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="bg_animate">
        <nav>
            <h1>Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/perfil') }}">Perfil</a>
                <a href="{{ url('/logout') }}">Cerrar Sesión</a>
            </div>
        </nav>

        <section>
            <div class="one contenedor">
                <a href="{{ url('/estresado') }}" class="llamanos">
                    <div class="caja">
                        Juegos para Desestresar
                    </div>
                </a>
                <a href="{{ url('/tipodememoria') }}" class="llamanos">
                    <div class="caja">
                        Juegos de Memoria
                    </div>
                </a>
                <a href="https://charsito12.herokuapp.com" class="llamanos" target="_blank">
                    <div class="caja">
                        Chat
                    </div>
                </a>
            </div>
            <div class="dos">
                <img src="{{ asset('img/kisspng-4-pics-1-word-word-brain-thought-action-game-snoring-transparent-png-5a76bf36785379.6988479815177316384929.png') }}" alt="relax">
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="footer-col">
                        <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro" class="cerebroo">
                    </div>
                    <div class="footer-coll">
                        <h4>Proposito</h4>
                        <ul>
                            <a>Daily Memory es un proyecto creado por la compañia SpiderBytes que busca el desarrollo e implementación de la estimulación cognitiva de la memoria por medio de una plataforma de juegos que te ayudarán a estimular diferentes tipos de memoria.</a>
                        </ul>
                    </div>
                    <div class="footer-col" style="padding-top: 220px;">
                        <h4>Conocenos</h4>
                        <div class="social-links">
                            <a href="https://www.facebook.com/Spyderbytes/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/Spiderbytes9" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/spyderbytes9/" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </header>
</body>
</html>

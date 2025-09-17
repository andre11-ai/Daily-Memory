<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Memoria | MemoryMaster</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/niveles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .bg_animate {
            min-height: 100vh;
            width: 100vw;
            position: relative;
            background: linear-gradient(to right, #ffff, #affcef);
            overflow: hidden;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 2rem 1rem 2rem;
            background: transparent;
            position: relative;
            z-index: 2;
        }
        nav h1 {
            font-size: 2.5rem;
            color: #303333;
            font-weight: 700;
        }
        .nav__list {
            display: flex;
            gap: 2rem;
        }
        .nav__list a {
            color: #666;
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav__list i { font-size: 1.3rem; }
        .burbujas {
            position: absolute;
            width: 100vw;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1;
            pointer-events: none;
        }
        .burbuja {
            border-radius: 50%;
            background: rgb(102, 255, 171);
            opacity: .3;
            position: absolute;
            bottom: -150px;
            animation: burbujas 8s linear infinite;
        }
        .burbuja:nth-child(1) { width: 80px; height: 80px; left: 5%; animation-delay: 3s;}
        .burbuja:nth-child(2) { width: 100px; height: 100px; left: 35%; animation-delay: 5s;}
        .burbuja:nth-child(3) { width: 20px; height: 20px; left: 15%; animation-delay: 7s;}
        .burbuja:nth-child(4) { width: 50px; height: 50px; left: 90%; animation-delay: 3s;}
        .burbuja:nth-child(5) { width: 70px; height: 70px; left: 65%; animation-delay: 1s;}
        .burbuja:nth-child(6) { width: 20px; height: 20px; left: 50%; animation-delay: 5s;}
        .burbuja:nth-child(7) { width: 20px; height: 20px; left: 80%; animation-delay: 2s;}
        .burbuja:nth-child(8) { width: 100px; height: 100px; left: 52%; animation-delay: 5s;}
        .burbuja:nth-child(9) { width: 65px; height: 65px; left: 51%; animation-delay: 2s;}
        .burbuja:nth-child(10){ width: 40px; height: 40px; left: 35%; animation-delay: 4s;}
        @keyframes burbujas {
            0% { bottom: -150px; opacity: 0;}
            40% { opacity: .4;}
            100% { bottom: 100vh; opacity: 0;}
        }

        /* GRID PRINCIPAL */
        .tipos-memoria-grid {
            display: grid;
            grid-template-columns: 1fr 0.9fr;
            align-items: center;
            justify-items: center;
            min-height: 73vh;
            width: 100%;
            gap: 0;
        }
        .tipos-memoria-col1 {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            width: 100%;
        }
        .tipos-memoria-title {
            text-align: left;
            font-size: 2.2rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 1.2rem;
            margin-left: 2rem;
        }
        .tipos-memoria-cards {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: flex-end;
            width: 100%;
        }
        .memoria-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 0.8rem 1.2rem 0.6rem 1.2rem;
            max-width: 370px;
            min-width: 200px;
            width: 95%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: box-shadow 0.2s, transform 0.2s;
            height: 110px;
            justify-content: center;
        }
        .memoria-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,0.14);
            transform: scale(1.03);
        }
        .memoria-card h2 {
            margin-bottom: 0.3rem;
            font-size: 1.06rem;
            font-weight: bold;
        }
        .memoria-card p {
            font-size: 0.93rem;
            margin-bottom: 0.3rem;
            color: #444;
            line-height: 1.2;
        }
        .memoria-card .btn-memoria {
            background: #3e4eff;
            color: #fff;
            border-radius: 8px;
            padding: 0.42rem 1.1rem;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
            margin-top: 0.1rem;
            display: inline-block;
            font-size: 0.88rem;
        }
        .memoria-card .btn-memoria:hover {
            background: #232b8d;
        }

        .tipos-memoria-col2 {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }
        .memoria-img-cerebro {
            max-width: 240px;
            width: 70%;
            height: auto;
            margin: 0 auto;
            display: block;
        }
        @media (max-width: 1000px) {
            .tipos-memoria-grid {
                grid-template-columns: 1fr;
                min-height: unset;
            }
            .tipos-memoria-col1, .tipos-memoria-cards {
                align-items: center;
            }
            .tipos-memoria-title {
                text-align: center;
                margin-left: 0;
            }
            .memoria-img-cerebro {
                margin: 2rem auto 1rem auto;
                max-width: 150px;
            }
        }
        @media (max-width: 600px) {
            nav h1 { font-size: 1.2rem; }
            .tipos-memoria-title { font-size: 1.1rem; }
            .memoria-card { min-width: unset; max-width: 98vw; padding: 0.7rem 0.3rem; height: 120px;}
            .memoria-card h2 { font-size: 0.98rem; }
            .memoria-img-cerebro { max-width: 90px; }
        }
    </style>
</head>
<body>
    <div class="bg_animate">
        <nav>
            <h1>MemoryMaster</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}">
                    <i class='bx bx-left-arrow-alt'></i>
                    Volver
                </a>
            </div>
        </nav>
        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
        <div class="tipos-memoria-grid">
            <div class="tipos-memoria-col1">
                <div class="tipos-memoria-title">Tipos de Memoria</div>
                <div class="tipos-memoria-cards">
                    <div class="memoria-card">
                        <h2>Memoria Iconica</h2>
                        <p>Ejercicios para mejorar tu capacidad de recordar imágenes y estímulos visuales.</p>
                        <a href="{{ url('/Miconica') }}" class="btn-memoria">Ver niveles</a>
                    </div>
                    <div class="memoria-card">
                        <h2>Memoria Muscular</h2>
                        <p>Desafía tu mente y cuerpo con ejercicios y juegos que estimulan la memoria muscular.</p>
                        <a href="{{ url('/Mmuscular') }}" class="btn-memoria">Ver niveles</a>
                    </div>
                    <div class="memoria-card">
                        <h2>Memoria Ecoica</h2>
                        <p>Entrena tu memoria para reconocer y recordar patrones de sonidos y palabras.</p>
                        <a href="{{ url('/Mecoica') }}" class="btn-memoria">Ver niveles</a>
                    </div>
                </div>
            </div>
            <div class="tipos-memoria-col2">
                <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro meditando" class="memoria-img-cerebro">
            </div>
        </div>
    </div>
</body>
</html>

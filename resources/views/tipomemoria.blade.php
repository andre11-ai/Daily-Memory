<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipos de Memoria | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/niveles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body, html { height: 100%; margin: 0; padding: 0; }
        .bg_animate {
            min-height: 100vh;
            width: 100vw;
            position: relative;
            background: linear-gradient(90deg, #e0fffa 0%, #affcef 100%);
            overflow: hidden;
        }
        /* ====== Navbar igual que el menú principal ====== */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 0 2rem;
            height: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #00c8a3;
            letter-spacing: 1px;
        }
        .nav__list {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .nav-link.volver-btn {
            font-size: 1rem;
            font-weight: 500;
            color: #303333;
            padding: .5rem 1rem;
            border-radius: .5rem;
            transition: background .2s, color .2s;
            display: flex;
            align-items: center;
            gap: .5rem;
            border: none;
            background: none;
            cursor: pointer;
            text-decoration: none;
        }
        .nav-link.volver-btn:hover {
            background: #00c8a3;
            color: #fff;
        }
        /* Animación de burbujas */
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
            background: #45b6fe;
            opacity: .18;
            position: absolute;
            bottom: -150px;
            animation: burbujas 8s linear infinite;
        }
        .burbuja:nth-child(1) { width: 80px; height: 80px; left: 5%; animation-delay: 1s;}
        .burbuja:nth-child(2) { width: 100px; height: 100px; left: 35%; animation-delay: 2.5s;}
        .burbuja:nth-child(3) { width: 20px; height: 20px; left: 15%; animation-delay: .8s;}
        .burbuja:nth-child(4) { width: 50px; height: 50px; left: 90%; animation-delay: 2.2s;}
        .burbuja:nth-child(5) { width: 70px; height: 70px; left: 65%; animation-delay: 2.8s;}
        .burbuja:nth-child(6) { width: 20px; height: 20px; left: 50%; animation-delay: 1.5s;}
        .burbuja:nth-child(7) { width: 20px; height: 20px; left: 50%; animation-delay: 1.9s;}
        .burbuja:nth-child(8) { width: 100px; height: 100px; left: 52%; animation-delay: 2.8s;}
        .burbuja:nth-child(9) { width: 65px; height: 65px; left: 51%; animation-delay: 2.5s;}
        .burbuja:nth-child(10){ width: 40px; height: 40px; left: 35%; animation-delay: 1.8s;}
        @keyframes burbujas {
            0% { bottom: 0; opacity: 0; }
            50% { opacity: .3; transform: translateX(30px);}
            100% { bottom: 100vh; opacity: 0;}
        }
        /* ====== Main grid igual que menú principal ====== */
        .memoria-main-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;  /* centrado horizontal */
            align-items: center;      /* centrado vertical */
            gap: 2rem;
            width: 100%;
            max-width: 1300px;
            margin: 0 auto 2rem auto;
            min-height: 85vh;         /* asegura centrado vertical */
        }
        /* ====== Tarjetas en grid 2x2 ====== */
        .memoria-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 2rem;
            align-items: center;
            justify-items: center;
        }
        .memoria-card {
            background: #fff;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            border-radius: 1.5rem;
            padding: 1rem 1.5rem;
            width: 100%;
            max-width: 350px;
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            transition: transform .18s;
            min-height: 120px;
        }
        .memoria-card1 {
            background: #fff;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            border-radius: 1.5rem;
            padding: 1rem 1.5rem;
            width: 100%;
            max-width: 350px;
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            transition: transform .18s;
            min-height: 120px;
        }
        .memoria-card:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.13);
        }
        .memoria-card1:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.13);
        }
        .empty-card {
            background: transparent;
            box-shadow: none;
            pointer-events: none;
        }
        .card-icon {
            font-size: 2.2rem;
            color: #00c8a3;
            background: #f8f9fa;
            border-radius: .5rem;
            padding: .7rem;
            box-shadow: 0 2px 8px rgba(0, 200, 163, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: .3rem;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: .2rem;
        }
        .card-desc {
            font-size: 1rem;
            color: #444;
            margin-bottom: .4rem;
        }
        .btn-memoria {
            background: #3e4eff;
            color: #fff;
            border-radius: 8px;
            padding: 0.42rem 1.1rem;
            font-weight: bold;
            transition: background 0.2s;
            font-size: 0.88rem;
            display: inline-block;
            margin-top: .3rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn-memoria:hover {
            background: #232b8d;
        }
        .memoria-image-section {
            flex: 1 1 350px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .memoria-img-cerebro {
            width: 100%;
            max-width: 400px;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }
        /* ======= Responsive igual que menú principal ====== */
        @media (max-width: 900px) {
            .memoria-main-menu {
                flex-direction: column;
                align-items: center;
                gap: 1.5rem;
                min-height: unset;
            }
            .memoria-cards-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
                gap: 1rem;
                width: 100%;
            }
            .memoria-image-section {
                width: 100%;
                justify-content: center;
                align-items: center;
            }
            .memoria-img-cerebro {
                max-width: 85vw;
                margin: 2rem auto 0 auto;
                display: block;
            }
            .memoria-card {
                max-width: 95vw;
            }
            .memoria-card1 {
                max-width: 95vw;
            }

        }
        @media (max-width: 600px) {
            .navbar {
                flex-direction: column;
                gap: .5rem;
                padding: .5rem 1rem;
                height: auto;
            }
            .logo {
                font-size: 2rem;
            }
            .nav__list {
                flex-direction: column;
                gap: .7rem;
                align-items: center;
            }
            .memoria-cards-grid {
                gap: 1rem;
            }
            .memoria-card {
                padding: 1rem 1.2rem;
                font-size: 1rem;
            }
             .memoria-card1 {
                padding: 1rem 1.2rem;
                font-size: 1rem;
            }
            .memoria-img-cerebro {
                max-width: 98vw;
            }
        }
    </style>
</head>
<body>
    <header class="bg_animate">
        <nav class="navbar">
            <h1 class="logo">Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}" class="nav-link volver-btn">
                    <i class='bx bx-left-arrow-alt'></i> Volver
                </a>
            </div>
        </nav>
        <main class="memoria-main-menu">
           <section class="memoria-cards-section memoria-cards-grid">
                <div class="memoria-card memoria-card-iconica">
                    <div class="card-icon"><i class='bx bx-image'></i></div>
                    <div>
                        <div class="card-title">Memoria Iconica</div>
                        <div class="card-desc">Ejercicios para mejorar tu capacidad de recordar imágenes y estímulos visuales.</div>
                        <a href="{{ url('/TiposMemoria/Miconica') }}" class="btn-memoria">Ver niveles</a>
                    </div>
                </div>
                <div class="memoria-card memoria-card-muscular">
                    <div class="card-icon"><i class='bx bx-dumbbell'></i></div>
                    <div>
                        <div class="card-title">Memoria Muscular</div>
                        <div class="card-desc">Desafía tu mente y cuerpo con ejercicios y juegos que estimulan la memoria muscular.</div>
                        <a href="{{ url('/TiposMemoria/Mmuscular') }}" class="btn-memoria">Ver niveles</a>
                    </div>
                </div>
                <div class="memoria-card memoria-card-ecoica">
                    <div class="card-icon"><i class='bx bx-microphone'></i></div>
                    <div>
                        <div class="card-title">Memoria Ecoica</div>
                        <div class="card-desc">Entrena tu memoria para reconocer y recordar patrones de sonidos y palabras.</div>
                        <a href="{{ url('/TiposMemoria/Mecoica') }}" class="btn-memoria">Ver niveles</a>
                    </div>
                </div>
            </section>
            <section class="memoria-image-section">
                <img src="{{ asset('img/pngwing.com.png') }}" alt="cerebro meditando" class="memoria-img-cerebro">
            </section>
        </main>
        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </header>
</body>
</html>

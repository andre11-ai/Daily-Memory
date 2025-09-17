<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
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
            font-size: 2.2rem;
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
        .perfil-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 70vh;
            padding-top: 1.5rem;
            z-index: 2;
            position: relative;
        }
        .perfil-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.10);
            padding: 2rem 2rem 1.5rem 2rem;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        .perfil-img-box {
            position: relative;
            margin-bottom: 1rem;
        }
        .perfil-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #affcef;
            background: #eee;
        }
        .perfil-edit-icon {
            position: absolute;
            right: 4px;
            bottom: 8px;
            background: #3e4eff;
            color: #fff;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 2px 8px #affcef;
        }
        .perfil-edit-input {
            display: none;
        }
        .perfil-nombre {
            font-size: 1.4rem;
            font-weight: 700;
            color: #303333;
            margin-bottom: 0.3rem;
        }
        .perfil-email {
            color: #666;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .perfil-estadisticas {
            margin-top: 1rem;
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 1.2rem;
        }
        .perfil-estadistica {
            background: #eafff9;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1.05rem;
            color: #303333;
            text-align: center;
            box-shadow: 0 2px 8px #71ffd8;
        }
        .perfil-btn {
            background: #3e4eff;
            color: #fff;
            border-radius: 8px;
            padding: 0.7rem 1.5rem;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
            margin-top: 1.2rem;
            border: none;
            font-size: 1rem;
            cursor: pointer;
        }
        .perfil-btn:hover {
            background: #232b8d;
        }
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
        @media (max-width: 600px) {
            .perfil-card { max-width: 98vw; padding: 1rem 0.5rem;}
            .perfil-nombre { font-size: 1.1rem; }
            .perfil-estadisticas { flex-direction: column; gap: 0.8rem; }
        }
    </style>
    <script>
        function triggerEditImg() {
            document.getElementById('perfil-img-edit').click();
        }
        function previewImg(event) {
            const img = document.getElementById('perfil-img');
            img.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
</head>
<body>
    <div class="bg_animate">
        <nav>
            <h1>Daily Memory</h1>
            <div class="nav__list">
                <a href="{{ url('/menu') }}">Menú Principal</a>
            </div>
        </nav>
        <div class="perfil-container">
            <div class="perfil-card">
                <div class="perfil-img-box">
                    <img id="perfil-img" src="{{ asset('img/default-user.png') }}" alt="Usuario" class="perfil-img">
                    <span class="perfil-edit-icon" onclick="triggerEditImg()">
                        <i class='bx bx-edit'></i>
                        <input type="file" id="perfil-img-edit" class="perfil-edit-input" accept="image/*" onchange="previewImg(event)">
                    </span>
                </div>
                <div class="perfil-nombre">Nombre del Usuario</div>
                <div class="perfil-email">usuario@email.com</div>
                <div class="perfil-estadisticas">
                    <div class="perfil-estadistica">
                        <div>Puntos</div>
                        <strong>1850</strong>
                    </div>
                    <div class="perfil-estadistica">
                        <div>Juegos Jugados</div>
                        <strong>23</strong>
                    </div>
                    <div class="perfil-estadistica">
                        <div>Nivel</div>
                        <strong>7</strong>
                    </div>
                </div>
                <button class="perfil-btn" disabled>Guardar cambios</button>
            </div>
        </div>
        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </div>
</body>
</html>

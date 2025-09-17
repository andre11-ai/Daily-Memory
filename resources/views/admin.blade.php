<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <style>
       body, html {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .bg_animate {
            width: 100vw;
            background: linear-gradient(to right, #ffff, #affcef);
            position: relative;
            overflow: visible; /* Permite scroll si el contenido crece */
            min-height: 100vh; /* Permite ver el fondo si el contenido es menor */
            height: auto;      /* Permite crecer el contenido */
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

        .admin-container {
            z-index: 2;
            position: relative;
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        .admin-title {
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 2rem;
            text-align: center;
        }

        .admin-cards {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin-bottom: 3rem;
        }
        .admin-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.10);
            padding: 1.5rem 2rem;
            min-width: 170px;
            text-align: center;
            flex: 1 1 180px;
        }
        .admin-card-title {
            font-size: 1.1rem;
            color: #303333;
            font-weight: 600;
        }
        .admin-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #3e4eff;
            margin-top: 0.5rem;
        }

        .admin-table-box {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.10);
            padding: 2rem;
            margin-top: 2rem;
            overflow-x: auto;
        }
        .admin-table-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            padding: 0.75rem 0.5rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #eafff9;
            color: #303333;
            font-weight: 600;
        }
        td {
            color: #333;
        }
        .admin-action-btn {
            background: #3e4eff;
            color: #fff;
            border-radius: 6px;
            padding: 0.3rem 0.9rem;
            border: none;
            font-size: 0.94rem;
            margin-right: 0.4rem;
            cursor: pointer;
        }
        .admin-action-btn.delete {
            background: #e91e63;
        }
        .admin-action-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        @media (max-width: 900px) {
            .admin-cards { flex-direction: column; gap: 1.2rem;}
            .admin-container { padding: 1.2rem 0.2rem;}
            .admin-table-box { padding: 1rem 0.2rem;}
        }
    </style>
</head>
<body>
    <div class="bg_animate">
        <nav>
            <h1>Daily Memory</h1>
            <div class="nav__list">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #666; font-size: 1.1rem; font-family: inherit; cursor: pointer;">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
        <div class="admin-container">
            <div class="admin-title">Panel de Administrador</div>
            <div class="admin-cards">
                <div class="admin-card">
                    <div class="admin-card-title">Usuarios</div>
                    <div class="admin-card-value">24</div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-title">Juegos</div>
                    <div class="admin-card-value">7</div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-title">Visitas</div>
                    <div class="admin-card-value">120</div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-title">Reportes</div>
                    <div class="admin-card-value">3</div>
                </div>
            </div>
            <div class="admin-table-box">
                <div class="admin-table-title">Usuarios registrados</div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Nivel</th>
                            <th>Puntos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Juan Pérez</td>
                            <td>juan@email.com</td>
                            <td>7</td>
                            <td>1850</td>
                            <td>
                                <button class="admin-action-btn" disabled><i class='bx bx-edit-alt'></i></button>
                                <button class="admin-action-btn delete" disabled><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Ana García</td>
                            <td>ana@email.com</td>
                            <td>5</td>
                            <td>1200</td>
                            <td>
                                <button class="admin-action-btn" disabled><i class='bx bx-edit-alt'></i></button>
                                <button class="admin-action-btn delete" disabled><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Pedro Ruiz</td>
                            <td>pedro@email.com</td>
                            <td>3</td>
                            <td>900</td>
                            <td>
                                <button class="admin-action-btn" disabled><i class='bx bx-edit-alt'></i></button>
                                <button class="admin-action-btn delete" disabled><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

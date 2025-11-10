<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/CSS/Perfil.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
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
        <header class="navbar">
            <div class="logo">Perfil</div>
            <nav>
                <ul class="nav__list">
                    <li><a class="nav-link" href="menu.html"><i class='bx bx-home-alt-2'></i> Menú Principal</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <section class="perfil-container">
                <div class="perfil-card">
                    <div class="perfil-img-box">
                        <img id="perfil-img" src="img/default-user.png" alt="Usuario" class="perfil-img">
                        <span class="perfil-edit-icon" onclick="triggerEditImg()">
                            <i class='bx bx-edit'></i>
                            <input type="file" id="perfil-img-edit" class="perfil-edit-input" accept="image/*" onchange="previewImg(event)">
                        </span>
                    </div>
                    <div class="perfil-nombre">Nombre del Usuario</div>
                    <div class="perfil-email">usuario@email.com</div>

                    <form class="perfil-form">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" value="Nombre del Usuario">
                        </div>
                        <div class="form-group">
                            <label>Apellido:</label>
                            <input type="text" value="Apellido del Usuario">
                        </div>
                        <div class="form-group">
                            <label>Correo:</label>
                            <input type="email" value="usuario@email.com">
                        </div>
                        <div class="form-group">
                            <label>Usuario:</label>
                            <input type="text" value="usuario123">
                        </div>
                    </form>

                    <div class="perfil-estadisticas">
                        <div class="perfil-estadistica">
                            <div>Puntos totales</div>
                            <strong>1850</strong>
                        </div>
                        <div class="perfil-estadistica">
                            <div>Juegos jugados</div>
                            <strong>23</strong>
                        </div>
                        <div class="perfil-estadistica">
                            <div>Nivel</div>
                            <strong>7</strong>
                        </div>
                    </div>

                    <div class="scores-juegos">
                        <h4>Scores por juego</h4>
                        <ul>
                            <li>Lluvia de Letras: <b>350</b></li>
                            <li>Repetir Palabra: <b>400</b></li>
                            <li>Simondice: <b>250</b></li>
                            <li>Scary: <b>500</b></li>
                            <li>Sonido Pareja: <b>200</b></li>
                            <li>Velocímetro: <b>150</b></li>
                        </ul>
                    </div>

                    <div class="scores-memoria">
                        <h4>Score por memoria</h4>
                        <b>950</b>
                    </div>

                    <div class="perfil-extra-estadisticas">
                        <ul>
                            <li>Promedio de score por partida: <b>80</b></li>
                            <li>Mejor puntuación en un juego: <b>230</b></li>
                            <li>Tiempo total jugado: <b>03:24:55</b></li>
                        </ul>
                    </div>

                    <button class="perfil-btn">Guardar cambios</button>
                </div>
            </section>
        </main>
        <div class="burbujas">
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
            <div class="burbuja"></div>
        </div>
    </div>
</body>
</html>

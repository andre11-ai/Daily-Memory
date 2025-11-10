<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('CSS/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="{{ asset('/CSS/Perfil.css') }}" rel="stylesheet">
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
                    <li><a class="nav-link" href="{{ url('/menu') }}"><i class='bx bx-home-alt-2'></i> Menú Principal</a></li>
                </ul>
            </nav>
        </header>
        <main>
            <section class="perfil-container">
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
            </section>
        </main>
        <div class="burbujas">
            @for($i = 0; $i < 10; $i++)
                <div class="burbuja"></div>
            @endfor
        </div>
    </div>
</body>
</html>

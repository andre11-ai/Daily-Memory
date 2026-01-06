<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En Mantenimiento | Daily Memory</title>
    <link href="{{ asset('CSS/Mantenimiento.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <nav class="navbar">
        <h1 class="logo">Daily Memory</h1>
        <div class="nav__list">
            <a href="{{ url('/Desestresar') }}" class="nav-link">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </nav>

    <main class="maintenance-container">
        <div class="icon-container">
            <i class="fas fa-tools"></i>
        </div>
        <h1 class="maintenance-title">En Mantenimiento</h1>
        <p class="maintenance-text">
            Estamos realizando mejoras en nuestra plataforma para brindarte una mejor experiencia.
            <br>¡Volveremos pronto!
        </p>
    </main>

    <div class="burbujas">
        @for($i = 0; $i < 10; $i++)
            <div class="burbuja"></div>
        @endfor
    </div>

</body>
</html>

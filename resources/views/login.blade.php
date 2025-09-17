<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Daily Memory</title>
    <link rel="stylesheet" href="{{ asset('CSS/styleLoginSigup.css') }}">
    <!-- FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">

          <!-- Formulario de inicio de sesión -->
          <form action="/signin" method="POST" class="sign-in-form">
            @csrf
            <h2 class="title">Inicio de sesión</h2>
            <div class="formulario__grupo" id="grupo__username2">
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Nombre de usuario" name="username" id="username2" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error"></p>
            </div>
            <div class="formulario__grupo" id="grupo__password2">
              <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Contraseña" name="password" id="password2" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error"></p>
            </div>
            <div class='cuerpo'>
              <a class="btn solid" onclick='abrirAviso();' id="btn_privacy" href='javascript:void(0);'>Aviso de privacidad</a>
            </div>
            <button id="btn_login" class="btn solid">LOGIN</button>
            <br>
            <a href="/registeradmin">¡Quiero ser admin!</a>
            <br>
            <a href="/forget">Olvide mi contraseña</a>
          </form>

          <!-- Formulario de registro -->
          <form action="/register" method="POST" class="sign-up-form" id="registrarse">
            @csrf
            <h2 class="title">Registrarse</h2>
            <div class="formulario__grupo" id="grupo__name">
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Nombre/s" name="name" id="name" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error">Los nombres solo puede llevar mayúsculas, minúsculas y ser de 1 a 20 dígitos.</p>
            </div>
            <div class="formulario__grupo" id="grupo__appe">
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Apellido/s" name="appe" id="appe" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error">Los apellidos solo puede llevar mayúsculas, minúsculas y ser de 1 a 40 dígitos.</p>
            </div>
            <div class="formulario__grupo" id="grupo__email">
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="email" placeholder="Correo" name="email" id="email" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error">El correo solo puede contener minúsculas, números, puntos, guiones y guion bajo.</p>
            </div>
            <div class="formulario__grupo" id="grupo__username">
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" placeholder="Nombre de usuario" name="username" id="username" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error">El usuario puede contener números, letras, guion bajo y ser de 4 a 16 dígitos.</p>
            </div>
            <div class="formulario__grupo" id="grupo__password">
              <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Contraseña" name="password" id="password" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error">La contraseña debe ser de 8 a 32 dígitos.</p>
            </div>
            <!-- Campo de confirmación de contraseña -->
            <div class="formulario__grupo" id="grupo__password_confirmation">
              <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Confirmar contraseña" name="password_confirmation" id="password_confirmation" required/>
                <i class="formulario__validacion-estado fas fa-times-circle"></i>
              </div>
              <p class="formulario__input-error">Las contraseñas deben coincidir.</p>
            </div>
            <div class="formulario__grupo" id="grupo__terminos">
              <label>
                <input class="formulario__checkbox" type="checkbox" name="terminos" id="terminos" required>
                Acepto los términos y condiciones
              </label>
            </div>
            <br>
            <!-- Muestra errores de back-end si existen -->
            @if($errors->any())
              <div class="formulario__mensaje formulario__mensaje-activo">
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @else
              <div class="formulario__mensaje" id="formulario__mensaje" style="display:none;">
                <p><i class="fas fa-exclamation-circle"><b>Error: El formulario no se ha llenado correctamente</b></i></p>
              </div>
            @endif
            <button type="submit" class="btn">SIGN UP</button>
          </form>

        </div>
      </div>
      <!--Moverse entre las páginas-->
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>¿Eres nuevo aquí?</h3>
            <p>¡Ven y regístrate!</p>
            <button class="btn transparent" id="sign-up-btn">Regístrate</button>
          </div>
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>¿Eres uno de nosotros?</h3>
            <p>¡Bienvenido de vuelta!</p>
            <button class="btn transparent" id="sign-in-btn">Iniciar Sesión</button>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('JS/accionesLogin.js') }}"></script>
    <script src="{{ asset('JS/formulario.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login & Registro | Daily Memory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('CSS/styleLoginSigup.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="bubbles">
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
        <div class="bubble"></div>
    </div>
    <div class="container">
        <div class="forms-container">
            <div class="logo">
                <img src="{{ asset('img/pngwing.com.png') }}" alt="Logo Daily Memory">
                <span>Daily Memory</span>
            </div>
            <div class="signin-signup" id="forms-wrapper">
                <!-- FORMULARIO LOGIN -->
                <form action="/signin" method="POST" class="sign-in-form" id="sign-in-form">
                    @csrf
                    <h2 class="title">Inicio de sesión</h2>
                    <div class="formulario__grupo" id="grupo__username2">
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Nombre de usuario" name="username" id="username2" required autocomplete="username"/>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__password2">
                        <div class="input-field" style="position:relative;">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Contraseña" name="password" id="password2" required autocomplete="current-password"/>
                            <button type="button" class="toggle-password" data-toggle="password2"><i class="fas fa-eye"></i></button>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <button id="btn_login" class="btn solid" type="submit">LOGIN</button>
                    <div class="switch-form">
                        ¿No tienes cuenta?
                        <button type="button" id="to-register">Regístrate</button>
                        <hr>
                    </div>
                    <a href="/forget" align="center" style="color:var(--accent);font-size:0.97rem;">¿Olvidaste tu contraseña?</a>
                    <div class="formulario__mensaje" id="formulario__mensaje" style="display:none;">
                        <p><i class="fas fa-exclamation-circle"><b>Error: El formulario no se ha llenado correctamente</b></i></p>
                    </div>
                </form>
                <!-- FORMULARIO REGISTRO -->
                <form action="/register" method="POST" class="sign-up-form" id="sign-up-form" style="display:none;">
                    @csrf
                    <h2 class="title">Registrarse</h2>
                    <div class="formulario__grupo" id="grupo__name">
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Nombre/s" name="name" id="name" required/>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__appe">
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Apellido/s" name="appe" id="appe" required/>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__email">
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" placeholder="Correo" name="email" id="email" required/>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__username">
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Nombre de usuario" name="username" id="username" required/>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__password">
                        <div class="input-field" style="position:relative;">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Contraseña" name="password" id="password" required/>
                            <button type="button" class="toggle-password" data-toggle="password"><i class="fas fa-eye"></i></button>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__password-confirmation">
                        <div class="input-field" style="position:relative;">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Confirmar contraseña" name="password_confirmation" id="password_confirmation" required/>
                            <button type="button" class="toggle-password" data-toggle="password_confirmation"><i class="fas fa-eye"></i></button>
                            <i class="formulario__validacion-estado fas fa-times-circle"></i>
                        </div>
                        <p class="formulario__input-error"></p>
                    </div>
                    <div class="formulario__grupo" id="grupo__terminos">
                        <label>
                            <input class="formulario__checkbox" type="checkbox" name="terminos" id="terminos" required>
                            Acepto los términos y condiciones
                        </label>
                    </div>
                    <br>
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
                    <div class="switch-form">
                        ¿Ya tienes cuenta?
                        <button type="button" id="to-login">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('to-register').onclick = function() {
            document.getElementById('sign-in-form').style.display = 'none';
            document.getElementById('sign-up-form').style.display = 'flex';
        }
        document.getElementById('to-login').onclick = function() {
            document.getElementById('sign-up-form').style.display = 'none';
            document.getElementById('sign-in-form').style.display = 'flex';
        }
    </script>
    <script src="{{ asset('JS/accionesLogin.js') }}"></script>
    <script src="{{ asset('JS/formulario.js') }}"></script>
</body>
</html>

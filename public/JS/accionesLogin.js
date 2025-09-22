document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
      const input = document.getElementById(this.dataset.toggle);
      if (input.type === 'password') {
        input.type = 'text';
        this.classList.add('active');
        this.querySelector('i').classList.remove('fa-eye');
        this.querySelector('i').classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        this.classList.remove('active');
        this.querySelector('i').classList.remove('fa-eye-slash');
        this.querySelector('i').classList.add('fa-eye');
      }
    });
  });

  function validarCampo(input, regex, errorMsg) {
    const value = input.value.trim();
    const grupo = input.closest('.formulario__grupo');
    const error = grupo.querySelector('.formulario__input-error');
    if (!value) {
      grupo.classList.add('error-anim');
      error.textContent = 'Este campo es obligatorio';
      error.classList.add('formulario__input-error-activo');
      return false;
    }
    if (regex && !regex.test(value)) {
      grupo.classList.add('error-anim');
      error.textContent = errorMsg;
      error.classList.add('formulario__input-error-activo');
      return false;
    }
    grupo.classList.remove('error-anim');
    error.textContent = '';
    error.classList.remove('formulario__input-error-activo');
    return true;
  }

  const loginForm = document.querySelector('.sign-in-form');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      let valid = true;
      valid &= validarCampo(document.getElementById('username2'), /^[a-zA-Z0-9_]{4,16}$/, 'Usuario inválido');
      valid &= validarCampo(document.getElementById('password2'), /^[\s\S]{8,32}$/, 'Contraseña inválida');
      if (!valid) {
        e.preventDefault();
        document.getElementById('formulario__mensaje').classList.add('formulario__mensaje-activo');
      }
    });
  }

  const regForm = document.getElementById('sign-up-form');
  if (regForm) {
    regForm.addEventListener('submit', function(e) {
      let valid = true;
      valid &= validarCampo(document.getElementById('name'), /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,20}$/, 'Nombre inválido');
      valid &= validarCampo(document.getElementById('appe'), /^[a-zA-ZáéíóúÁÉÍÓÚ\s]{1,40}$/, 'Apellido inválido');
      valid &= validarCampo(document.getElementById('email'), /^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/, 'Correo inválido');
      valid &= validarCampo(document.getElementById('username'), /^[a-zA-Z0-9_]{4,16}$/, 'Usuario inválido');
      valid &= validarCampo(document.getElementById('password'), /^[\s\S]{8,32}$/, 'Contraseña inválida');

      const pass1 = document.getElementById('password').value;
      const pass2 = document.getElementById('password_confirmation').value;
      const grupoConfirm = document.getElementById('grupo__password-confirmation');
      const errorConfirm = grupoConfirm.querySelector('.formulario__input-error');
      if (pass1 !== pass2 || !pass2) {
        valid = false;
        grupoConfirm.classList.add('error-anim');
        errorConfirm.textContent = 'Las contraseñas deben coincidir';
        errorConfirm.classList.add('formulario__input-error-activo');
      } else {
        grupoConfirm.classList.remove('error-anim');
        errorConfirm.textContent = '';
        errorConfirm.classList.remove('formulario__input-error-activo');
      }

      if (!document.getElementById('terminos').checked) {
        valid = false;
        document.getElementById('grupo__terminos').classList.add('error-anim');
      } else {
        document.getElementById('grupo__terminos').classList.remove('error-anim');
      }

      if (!valid) {
        e.preventDefault();
        document.getElementById('formulario__mensaje').classList.add('formulario__mensaje-activo');
      }
    });
  }
});

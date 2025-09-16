const formulario = document.getElementById('registrarse');
const inputs = document.querySelectorAll('#registrarse input');

const expresiones = {
	username: /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, numeros, guion y guion_bajo
	name: /^[a-zzA-ZÀ-ÿ\s]{1,20}$/, // Letras y espacios, pueden llevar acentos.
  appe: /^[a-zA-ZÀ-ÿ\s]{1,30}$/, // Letras y espacios, pueden llevar acentos.
	password: /^.{8,32}$/, // 8 a 32 digitos.
	email: /^[a-z0-9_.+-]+@[a-z0-9-]+\.[a-z0-9-.]+$/
}

const campos = {
	name: false,
	appe: false,
	email: false,
	username: false,
	password: false
}

const validarFormulario = (e) => {
  switch (e.target.name) {
		case "name":
			validarCampo(expresiones.name, e.target, 'name');
		break;
		case "appe":
			validarCampo(expresiones.appe, e.target, 'appe');
		break;
		case "email":
			validarCampo(expresiones.email, e.target, 'email');
		break;
		case "username":
			validarCampo(expresiones.username, e.target, 'username');
		break;
		case "password":
			validarCampo(expresiones.password, e.target, 'password');
		break;
	}
}

const validarCampo = (expresion, input, campo) => {
	if(expresion.test(input.value)){
		document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-incorrecto');
		document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-correcto');
		document.querySelector(`#grupo__${campo} .formulario__validacion-estado`).classList.add('fa-check-circle');
		document.querySelector(`#grupo__${campo} .formulario__validacion-estado`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.remove('formulario__input-error-activo');
		campos[campo] = true;
	}else{
		document.getElementById(`grupo__${campo}`).classList.add('formulario__grupo-incorrecto');
		document.getElementById(`grupo__${campo}`).classList.remove('formulario__grupo-correcto');
		document.querySelector(`#grupo__${campo} .formulario__validacion-estado`).classList.add('fa-times-circle');
		document.querySelector(`#grupo__${campo} .formulario__validacion-estado`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${campo} .formulario__input-error`).classList.add('formulario__input-error-activo');
		campos[campo] = false;
	}
}

inputs.forEach((input) => {
  input.addEventListener('keyup', validarFormulario);
  input.addEventListener('blur', validarFormulario);
});

formulario.addEventListener('submit', (e) => {
  e.preventDefault();

	if(campos.name && campos.appe && campos.password && campos.email && campos.username && terminos.checked){
		formulario.submit();
		document.getElementById('formulario__mensaje').classList.remove('formulario__mensaje-activo');
		document.querySelectorAll('.formulario__grupo-correcto').forEach((icono) => {
			icono.classList.remove('formulario__grupo-correcto');
		});
	}else{
		document.getElementById('formulario__mensaje').classList.add('formulario__mensaje-activo');
	}
});

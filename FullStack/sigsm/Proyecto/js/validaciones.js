
function validarLogin() {
    let usuario = document.getElementById("usuario").value.trim();
    let password = document.getElementById("password").value;

    if (usuario == "") {
        alert("Debe ingresar el correo electrónico");
        return false;
    }

    if (password == "") {
        alert("Debe ingresar la contraseña");
        return false;
    }

    if (password.length < 4) {
        alert("La contraseña debe tener al menos 4 caracteres");
        return false;
    }

    return true;
}

// Iniciar sesión
async function iniciarSesion(event) {
    event.preventDefault();

    if (validarLogin() == false) {
        return;
    }

    let formulario = document.getElementById("formLogin");
    let datos = new FormData(formulario);

    try {
        let respuesta = await fetch("procesar_login.php", {
            method: "POST",
            body: datos
        });

        let resultado = await respuesta.text();

        if (resultado.trim() == "correcto") {
            window.location.href = "panel-admin.php";

        } else if (resultado.trim() == "incorrecto") {
            alert("Correo o contraseña incorrectos");

        } else {
            alert("Ocurrió un error al iniciar sesión");
        }

    } catch (error) {
        alert("No se pudo conectar con el servidor");
    }
}

// Validar encuesta
function validarEncuesta() {
    let calificacion = document.getElementById("calificacion").value;
    let marcoSi = document.getElementById("claridadSi").checked;
    let marcoNo = document.getElementById("claridadNo").checked;

    if (calificacion == "") {
        alert("Debe seleccionar una calificación");
        return false;
    }

    if (marcoSi == false && marcoNo == false) {
        alert("Debe indicar si la información fue clara");
        return false;
    }

    return true;
}

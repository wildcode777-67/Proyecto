// S.I.G.S.M. - Validaciones del lado del cliente
// Mismo estilo visto en clase: una función por formulario, que se
// llama desde el atributo onsubmit del <form>. Si devuelve false,
// el formulario NO se envía. Si devuelve true, se envía normal.

function validarLogin() {
    let usuario = String(document.getElementById("usuario").value);
    let password = String(document.getElementById("password").value);

    if (usuario == "") {
        alert("Debe ingresar el usuario o cédula");
        return false;
    }

    if (password == "") {
        alert("Debe ingresar la contraseña");
        return false;
    } else if (password.length < 4) {
        alert("La contraseña debe tener al menos 4 caracteres");
        return false;
    }

    return true;
}

function validarEncuesta() {
    let calificacion = String(document.getElementById("calificacion").value);
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

// S.I.G.S.M. - Accesibilidad

// Botones para cambiar el tamaño del texto
const btnFuenteMas = document.getElementById("btnFuenteMas");
const btnFuenteMenos = document.getElementById("btnFuenteMenos");

// Tamaño inicial
let tamanoActual = 100;

// Aumentar letra
if (btnFuenteMas) {
  btnFuenteMas.addEventListener("click", function () {
    if (tamanoActual < 130) {
      tamanoActual = tamanoActual + 10;

      document.documentElement.style.fontSize =
        tamanoActual + "%";
    }
  });
}

// Disminuir letra
if (btnFuenteMenos) {
  btnFuenteMenos.addEventListener("click", function () {
    if (tamanoActual > 100) {
      tamanoActual = tamanoActual - 10;

      document.documentElement.style.fontSize =
        tamanoActual + "%";
    }
  });
}

// Botón de alto contraste
const btnContraste = document.getElementById("btnContraste");

// Recuperar la preferencia guardada
try {
  if (localStorage.getItem("sigsm-alto-contraste") === "1") {
    document.body.classList.add("alto-contraste");
  }
} catch (error) {
  // El sistema sigue funcionando si no hay almacenamiento
}

// Activar o desactivar el alto contraste
if (btnContraste) {
  btnContraste.setAttribute(
    "aria-pressed",
    document.body.classList.contains("alto-contraste")
      ? "true"
      : "false"
  );

  btnContraste.addEventListener("click", function () {
    const activo = document.body.classList.toggle("alto-contraste");

    btnContraste.setAttribute(
      "aria-pressed",
      activo ? "true" : "false"
    );

    // Mantener la preferencia al cambiar de página
    try {
      localStorage.setItem(
        "sigsm-alto-contraste",
        activo ? "1" : "0"
      );
    } catch (error) {
      // El botón funciona aunque no se pueda guardar
    }
  });
}

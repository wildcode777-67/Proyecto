// S.I.G.S.M. - Accesibilidad (RNF-05 tamaño de fuente, RNF-06 alto contraste)
// Cada botón cambia una clase o un estilo en la página. El CSS (estilo.css)
// es el que define cómo se ve cada clase.

// Botones de tamaño de letra
const btnFuenteMas = document.getElementById("btnFuenteMas");
const btnFuenteMenos = document.getElementById("btnFuenteMenos");

// Empezamos en tamaño normal (100%)
let tamanoActual = 100;

if (btnFuenteMas) {
  btnFuenteMas.addEventListener("click", function () {
    if (tamanoActual < 130) {
      tamanoActual = tamanoActual + 10;
      document.documentElement.style.fontSize = tamanoActual + "%";
    }
  });
}

if (btnFuenteMenos) {
  btnFuenteMenos.addEventListener("click", function () {
    if (tamanoActual > 100) {
      tamanoActual = tamanoActual - 10;
      document.documentElement.style.fontSize = tamanoActual + "%";
    }
  });
}

// Botón de alto contraste: agrega o quita una clase en el body
const btnContraste = document.getElementById("btnContraste");

if (btnContraste) {
  btnContraste.addEventListener("click", function () {
    document.body.classList.toggle("alto-contraste");
  });
}
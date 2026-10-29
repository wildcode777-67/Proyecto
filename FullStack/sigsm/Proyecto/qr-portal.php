
<?php
session_start();

// Comprobar sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}

// Solo administrativos
if (($_SESSION["rol"] ?? "") !== "administrativo") {
    header("Location: panel-admin.php");
    exit;
}

// Dirección utilizada para la simulación del QR
$direccion = "http://localhost/sigsm/proyecto/portal.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Código QR del portal - S.I.G.S.M.</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link href="css/estilo.css" rel="stylesheet">
</head>

<body>

    <!-- Accesibilidad -->
    <div class="barra-accesibilidad">
        <div class="container d-flex justify-content-end gap-2">

            <button id="btnFuenteMenos"
                    class="btn btn-outline-light btn-sm">
                A-
            </button>

            <button id="btnFuenteMas"
                    class="btn btn-outline-light btn-sm">
                A+
            </button>

            <button id="btnContraste"
                    class="btn btn-outline-light btn-sm">
                Alto contraste
            </button>

        </div>
    </div>

    <!-- Menú compartido -->
    <?php require "menu.php"; ?>

    <main class="container my-4">

        <h1 class="h3 mb-3">
            Código QR del portal
        </h1>

        <p class="text-muted mb-4">
            Este código representa el acceso al portal público
            de documentos informativos y encuestas del S.I.G.S.M.
        </p>

        <div class="card shadow-sm mx-auto"
             style="max-width: 600px;">

            <div class="card-body text-center p-4">

                <h2 class="h4 mb-3">
                    QR del portal de pacientes
                </h2>

                <span class="badge bg-warning text-dark mb-3">
                    Simulación académica
                </span>

                <p class="text-muted">
                    En una implementación real, el hospital
                    colocaría este código en sus pantallas
                    informativas para que los pacientes
                    accedan al portal.
                </p>

                <!-- Código QR -->
                <div
                    id="codigoQR"
                    class="d-flex justify-content-center my-4"
                ></div>

                <button
                    class="btn btn-primary"
                    type="button"
                    onclick="descargarQR()"
                >
                    Descargar código QR
                </button>

                <hr>

                <p class="mb-2">
                    <strong>Dirección de demostración:</strong>
                </p>

                <p class="small text-break">
                    <?php
                    echo htmlspecialchars(
                        $direccion,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </p>

                <div class="alert alert-info text-start mt-3 mb-0">
                    <strong>Importante:</strong>
                    esta es una simulación para la entrega
                    académica. La dirección utiliza localhost,
                    por lo que no permite ingresar desde
                    cualquier celular. Para usarlo públicamente
                    se necesita una dirección web accesible.
                </div>

            </div>
        </div>

    </main>

    <footer class="pie-sigsm text-center">
        Sistema Informático de Gestión de Servicios Médicos -
        Hospital de Clínicas
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Accesibilidad -->
    <script src="js/accesibilidad.js"></script>

    <!-- Librería para crear el QR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        const direccion = <?php echo json_encode($direccion); ?>;

        // Crear el QR
        if (typeof QRCode !== "undefined") {

            new QRCode(
                document.getElementById("codigoQR"),
                {
                    text: direccion,
                    width: 240,
                    height: 240,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                }
            );

        } else {

            document.getElementById("codigoQR").textContent =
                "No se pudo cargar el código QR.";
        }

        // Descargar el QR con un margen blanco
        function descargarQR() {

            const contenedor = document.getElementById("codigoQR");

            const canvasOriginal = contenedor.querySelector("canvas");
            const imagenOriginal = contenedor.querySelector("img");

            if (!canvasOriginal && !imagenOriginal) {
                alert("El código QR todavía no está disponible.");
                return;
            }

            const canvas = document.createElement("canvas");
            canvas.width = 280;
            canvas.height = 280;

            const contexto = canvas.getContext("2d");

            contexto.fillStyle = "#ffffff";
            contexto.fillRect(0, 0, 280, 280);

            contexto.drawImage(
                canvasOriginal || imagenOriginal,
                20,
                20,
                240,
                240
            );

            const enlace = document.createElement("a");

            enlace.href = canvas.toDataURL("image/png");
            enlace.download = "QR-Portal-SIGSM.png";

            enlace.click();
        }
    </script>

</body>
</html>

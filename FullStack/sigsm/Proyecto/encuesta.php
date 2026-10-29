
<?php
session_start();

require_once "datos/conexion.php";

// Crear token de seguridad para la encuesta
if (!isset($_SESSION["token_encuesta"])) {
    $_SESSION["token_encuesta"] = bin2hex(random_bytes(32));
}

// Obtener el ID del documento
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Documento no válido.");
}

// Comprobar que el documento exista y esté activo
$sql = "SELECT titulo
        FROM documento
        WHERE id_documento = ?
        AND activo = 1";

$consulta = $pdo->prepare($sql);
$consulta->execute([$id]);

$documento = $consulta->fetch();

if (!$documento) {
    http_response_code(404);
    exit("Esta encuesta no está disponible.");
}

// Mensaje después de enviar
$enviada = (($_GET["enviada"] ?? "") === "1");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Encuesta de satisfacción - S.I.G.S.M.</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link href="css/estilo.css" rel="stylesheet">
</head>

<body>

    <!-- Barra de accesibilidad -->
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

    <!-- Barra superior -->
    <nav class="navbar navbar-dark navbar-sigsm">
        <div class="container">

            <span class="navbar-brand">
                S.I.G.S.M. - Hospital de Clínicas
            </span>

        </div>
    </nav>

    <main class="container my-4">

        <!-- Botones para regresar -->
        <div class="d-flex flex-wrap gap-2 mb-3">

            <a href="portal.php"
               class="btn btn-outline-primary">
                &larr; Volver al portal
            </a>

            <a href="documento.php?id=<?php echo (int) $id; ?>&amp;origen=portal"
               class="btn btn-outline-secondary">
                Volver al documento
            </a>

        </div>

        <div class="card shadow-sm mx-auto"
             style="max-width: 650px;">

            <div class="card-body p-4">

                <h1 class="h4 mb-3">
                    Encuesta de satisfacción
                </h1>

                <p>
                    Documento:
                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $documento["titulo"],
                            ENT_QUOTES,
                            "UTF-8"
                        );
                        ?>
                    </strong>
                </p>

                <p class="text-muted">
                    Esta encuesta es anónima.
                    No necesitás iniciar sesión.
                </p>

                <?php if ($enviada) { ?>

                    <div class="alert alert-success">
                        ¡Gracias! Tu respuesta se guardó correctamente.
                    </div>

                <?php } ?>

                <!-- Formulario -->
                <form
                    action="guardar_encuesta.php"
                    method="post"
                >

                    <!-- Token CSRF -->
                    <input
                        type="hidden"
                        name="token_encuesta"
                        value="<?php
                            echo htmlspecialchars(
                                $_SESSION["token_encuesta"],
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>"
                    >

                    <!-- Documento -->
                    <input
                        type="hidden"
                        name="id_documento"
                        value="<?php echo (int) $id; ?>"
                    >

                    <!-- Calificación -->
                    <div class="mb-3">

                        <label for="calificacion"
                               class="form-label">
                            ¿Qué tan útil te resultó
                            la información?
                        </label>

                        <select
                            class="form-select"
                            id="calificacion"
                            name="calificacion"
                            required
                        >

                            <option value="">
                                Seleccioná una calificación
                            </option>

                            <option value="1">
                                1 - Muy mala
                            </option>

                            <option value="2">
                                2 - Mala
                            </option>

                            <option value="3">
                                3 - Regular
                            </option>

                            <option value="4">
                                4 - Buena
                            </option>

                            <option value="5">
                                5 - Muy buena
                            </option>

                        </select>

                    </div>

                    <!-- Comentario -->
                    <div class="mb-3">

                        <label for="comentario"
                               class="form-label">
                            ¿Tenés algún comentario?
                            (opcional)
                        </label>

                        <textarea
                            class="form-control"
                            id="comentario"
                            name="comentario"
                            rows="4"
                            maxlength="1000"
                        ></textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Enviar encuesta
                    </button>

                </form>

            </div>
        </div>

    </main>

    <footer class="pie-sigsm text-center">
        Sistema Informático de Gestión de Servicios Médicos -
        Hospital de Clínicas
    </footer>

    <script src="js/accesibilidad.js"></script>

</body>
</html>

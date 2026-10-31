
<?php
session_start();

require_once "datos/conexion.php";

// Obtener ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    die("Documento no válido.");
}

// Buscar solamente documentos activos
$sql = "SELECT d.*,
               c.nombre AS categoria
        FROM documento d
        INNER JOIN categoria c
            ON d.id_categoria = c.id_categoria
        WHERE d.id_documento = ?
        AND d.activo = 1";

$consulta = $pdo->prepare($sql);
$consulta->execute([$id]);

$documento = $consulta->fetch();

if (!$documento) {
    http_response_code(404);
    die("Este documento no se encuentra disponible.");
}

// Saber si entró desde el panel
$origen = $_GET["origen"] ?? "portal";

$desdePanel = (
    isset($_SESSION["id_usuario"]) &&
    $origen === "panel"
);

// Comprobar si tiene un archivo asociado
$tieneArchivo = !empty($documento["ruta_archivo"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Documento - S.I.G.S.M.</title>

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

        <!-- Botón de regreso -->
        <div class="mb-3">

            <?php if ($desdePanel) { ?>

                <a
                    href="panel-admin.php"
                    class="btn btn-outline-primary"
                >
                    &larr; Volver al panel
                </a>

            <?php } else { ?>

                <a
                    href="portal.php"
                    class="btn btn-outline-primary"
                >
                    &larr; Volver al portal
                </a>

            <?php } ?>

        </div>

        <!-- Información del documento -->
        <div class="card shadow-sm mx-auto"
             style="max-width: 700px;">

            <div class="card-body p-4">

                <span class="badge bg-primary mb-2">
                    <?php echo htmlspecialchars(
                        $documento["categoria"],
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>
                </span>

                <h1 class="h4">
                    <?php echo htmlspecialchars(
                        $documento["titulo"],
                        ENT_QUOTES,
                        "UTF-8"
                    ); ?>
                </h1>

                <p class="text-muted">
                    Publicado el
                    <?php echo date(
                        "d/m/Y",
                        strtotime($documento["fecha_subida"])
                    ); ?>
                </p>

                <hr>

                <p style="white-space: pre-wrap;"><?php
                    echo htmlspecialchars(
                        $documento["descripcion"] ?? "",
                        ENT_QUOTES,
                        "UTF-8"
                    );
                ?></p>

                <!-- Archivo adjunto -->
                <?php if ($tieneArchivo) { ?>

                    <div class="mt-4">

                        <h2 class="h5">
                            Archivo adjunto
                        </h2>

                        <a
                            href="archivo.php?id=<?php echo (int) $id; ?>"
                            class="btn btn-primary mb-2"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Ver archivo
                        </a>

                        <a
                            href="archivo.php?id=<?php echo (int) $id; ?>&amp;descargar=1"
                            class="btn btn-outline-primary mb-2"
                        >
                            Descargar archivo
                        </a>

                    </div>

                <?php } ?>

                <hr>

                <!-- Encuesta -->
                <a
                    href="encuesta.php?id=<?php echo (int) $id; ?>"
                    class="btn btn-primary w-100"
                >
                    Completar encuesta de satisfacción
                </a>

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

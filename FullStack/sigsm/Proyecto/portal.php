
<?php
session_start();

require_once "datos/conexion.php";

// Comprobar si hay una sesión iniciada
$sesionIniciada = isset($_SESSION["id_usuario"]);

// Obtener categorías
$consulta = $pdo->query(
    "SELECT id_categoria, nombre
     FROM categoria
     ORDER BY nombre"
);

$categorias = $consulta->fetchAll();

// Obtener solamente los documentos activos
$sql = "SELECT d.id_documento,
               d.titulo,
               d.descripcion,
               d.id_categoria,
               c.nombre AS categoria
        FROM documento d
        INNER JOIN categoria c
            ON d.id_categoria = c.id_categoria
        WHERE d.activo = 1
        ORDER BY d.titulo";

$consulta = $pdo->query($sql);
$documentos = $consulta->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Portal de pacientes - S.I.G.S.M.</title>

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

            <button
                id="btnFuenteMenos"
                class="btn btn-outline-light btn-sm"
            >
                A-
            </button>

            <button
                id="btnFuenteMas"
                class="btn btn-outline-light btn-sm"
            >
                A+
            </button>

            <button
                id="btnContraste"
                class="btn btn-outline-light btn-sm"
            >
                Alto contraste
            </button>

        </div>
    </div>

    <!-- Menú según el tipo de usuario -->
    <?php if ($sesionIniciada) { ?>

        <!-- Funcionarios: menú compartido -->
        <?php require "menu.php"; ?>

    <?php } else { ?>

        <!-- Pacientes: barra pública -->
        <nav class="navbar navbar-dark navbar-sigsm">
            <div class="container">

                <span class="navbar-brand">
                    S.I.G.S.M. - Hospital de Clínicas
                </span>

            </div>
        </nav>

    <?php } ?>

    <!-- Contenido del portal -->
    <main class="container my-4">

        <div class="text-center mb-5">

            <h1 class="h2">
                Portal de información para pacientes
            </h1>

            <p class="text-muted">
                Consultá guías, indicaciones y documentos
                informativos de las diferentes
                especialidades médicas.
            </p>

        </div>

        <!-- Accesos principales -->
        <div class="row g-3 mb-5">

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-body p-4 text-center">

                        <h2 class="h4">
                            Documentos médicos
                        </h2>

                        <p>
                            Consultá las guías informativas disponibles.
                        </p>

                        <a href="#documentos" class="btn btn-primary">
                            Ver documentos
                        </a>

                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-body p-4 text-center">

                        <h2 class="h4">
                            Encuestas de satisfacción
                        </h2>

                        <p>
                            Compartí tu opinión sobre la información recibida.
                        </p>

                        <a href="#documentos" class="btn btn-primary">
                            Responder encuesta
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!-- Sección de documentos -->
        <section id="documentos">

            <h2 class="h3 mb-3">
                Documentos disponibles
            </h2>

            <!-- Filtros -->
            <div class="row g-2 mb-4">

                <div class="col-md-5">

                    <label for="filtroCategoria"
                           class="form-label">
                        Especialidad médica
                    </label>

                    <select
                        class="form-select"
                        id="filtroCategoria"
                        onchange="filtrarDocumentos()"
                    >

                        <option value="">
                            Todas las especialidades
                        </option>

                        <?php foreach ($categorias as $categoria) { ?>

                            <option
                                value="<?php echo (int) $categoria["id_categoria"]; ?>"
                            >
                                <?php
                                echo htmlspecialchars(
                                    $categoria["nombre"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="col-md-7">

                    <label for="buscarDocumento"
                           class="form-label">
                        Buscar documento
                    </label>

                    <input
                        type="search"
                        class="form-control"
                        id="buscarDocumento"
                        placeholder="Buscar por título..."
                        oninput="filtrarDocumentos()"
                    >

                </div>

            </div>

            <!-- Tarjetas de documentos -->
            <div class="row g-3">

                <?php foreach ($documentos as $doc) { ?>

                    <div
                        class="col-md-6 col-lg-4 tarjeta-documento"
                        data-categoria="<?php echo (int) $doc["id_categoria"]; ?>"
                    >

                        <div class="card shadow-sm h-100">

                            <div class="card-body d-flex flex-column">

                                <span class="badge bg-primary mb-2 align-self-start">
                                    <?php
                                    echo htmlspecialchars(
                                        $doc["categoria"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </span>

                                <h3 class="h5 titulo-documento">
                                    <?php
                                    echo htmlspecialchars(
                                        $doc["titulo"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </h3>

                                <p class="text-muted">
                                    <?php
                                    echo htmlspecialchars(
                                        $doc["descripcion"] ?? "",
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </p>

                                <div class="mt-auto d-flex flex-wrap gap-2">

                                    <!-- Documento desde el portal -->
                                    <a
                                        href="documento.php?id=<?php echo (int) $doc["id_documento"]; ?>&amp;origen=portal"
                                        class="btn btn-primary"
                                    >
                                        Ver documento
                                    </a>

                                    <!-- Encuesta -->
                                    <a
                                        href="encuesta.php?id=<?php echo (int) $doc["id_documento"]; ?>"
                                        class="btn btn-outline-primary"
                                    >
                                        Encuesta
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

            <p
                id="sinResultados"
                class="text-center text-muted mt-4"
                style="display:none;"
            >
                No se encontraron documentos.
            </p>

        </section>

    </main>

    <!-- Pie -->
    <footer class="pie-sigsm text-center">
        Sistema Informático de Gestión de Servicios Médicos -
        Hospital de Clínicas
    </footer>

    <!-- Bootstrap JS: necesario para el menú -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Accesibilidad -->
    <script src="js/accesibilidad.js"></script>

    <!-- Búsqueda y filtros -->
    <script>
        function filtrarDocumentos() {

            let buscar = document
                .getElementById("buscarDocumento")
                .value
                .toLowerCase();

            let categoria = document
                .getElementById("filtroCategoria")
                .value;

            let tarjetas = document.querySelectorAll(
                ".tarjeta-documento"
            );

            let encontrados = 0;

            tarjetas.forEach(function(tarjeta) {

                let titulo = tarjeta
                    .querySelector(".titulo-documento")
                    .textContent
                    .toLowerCase();

                let categoriaTarjeta = tarjeta.getAttribute(
                    "data-categoria"
                );

                if (
                    titulo.includes(buscar) &&
                    (categoria === "" || categoria === categoriaTarjeta)
                ) {
                    tarjeta.style.display = "";
                    encontrados++;
                } else {
                    tarjeta.style.display = "none";
                }

            });

            document.getElementById("sinResultados").style.display =
                encontrados === 0 ? "block" : "none";
        }

        filtrarDocumentos();
    </script>

</body>
</html>

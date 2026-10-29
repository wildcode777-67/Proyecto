
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

require_once "datos/conexion.php";

// Estadísticas generales
$sql = "SELECT
            COUNT(*) AS total,
            AVG(calificacion) AS promedio,
            SUM(CASE WHEN calificacion = 1 THEN 1 ELSE 0 END) AS uno,
            SUM(CASE WHEN calificacion = 2 THEN 1 ELSE 0 END) AS dos,
            SUM(CASE WHEN calificacion = 3 THEN 1 ELSE 0 END) AS tres,
            SUM(CASE WHEN calificacion = 4 THEN 1 ELSE 0 END) AS cuatro,
            SUM(CASE WHEN calificacion = 5 THEN 1 ELSE 0 END) AS cinco
        FROM encuesta";

$consulta = $pdo->query($sql);
$general = $consulta->fetch();

// Estadísticas por documento
$sql = "SELECT
            d.id_documento,
            d.titulo,
            d.activo,
            c.nombre AS categoria,
            COUNT(e.id_encuesta) AS cantidad,
            AVG(e.calificacion) AS promedio,
            SUM(CASE WHEN e.calificacion = 1 THEN 1 ELSE 0 END) AS uno,
            SUM(CASE WHEN e.calificacion = 2 THEN 1 ELSE 0 END) AS dos,
            SUM(CASE WHEN e.calificacion = 3 THEN 1 ELSE 0 END) AS tres,
            SUM(CASE WHEN e.calificacion = 4 THEN 1 ELSE 0 END) AS cuatro,
            SUM(CASE WHEN e.calificacion = 5 THEN 1 ELSE 0 END) AS cinco
        FROM documento d
        INNER JOIN categoria c
            ON d.id_categoria = c.id_categoria
        LEFT JOIN encuesta e
            ON d.id_documento = e.id_documento
        GROUP BY
            d.id_documento,
            d.titulo,
            d.activo,
            c.nombre
        ORDER BY d.titulo";

$consulta = $pdo->query($sql);
$resultados = $consulta->fetchAll();

// Respuestas individuales
$sql = "SELECT
            e.id_encuesta,
            e.calificacion,
            e.comentario,
            e.fecha,
            d.titulo,
            d.activo,
            c.nombre AS categoria
        FROM encuesta e
        INNER JOIN documento d
            ON e.id_documento = d.id_documento
        INNER JOIN categoria c
            ON d.id_categoria = c.id_categoria
        ORDER BY e.fecha DESC, e.id_encuesta DESC";

$consulta = $pdo->query($sql);
$respuestas = $consulta->fetchAll();

// Categorías para el filtro
$consulta = $pdo->query(
    "SELECT id_categoria, nombre
     FROM categoria
     ORDER BY nombre"
);

$categorias = $consulta->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Resultados de encuestas - S.I.G.S.M.</title>

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
            Resultados de encuestas
        </h1>

        <p class="text-muted mb-4">
            En esta sección se pueden consultar las opiniones
            de los pacientes sobre los documentos informativos.
        </p>

        <!-- Estadísticas generales -->
        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">

                        <h2 class="h5">
                            Total de respuestas
                        </h2>

                        <p class="display-6 mb-0">
                            <?php echo (int) $general["total"]; ?>
                        </p>

                    </div>
                </div>

            </div>

            <div class="col-md-6">

                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">

                        <h2 class="h5">
                            Calificación promedio
                        </h2>

                        <p class="display-6 mb-0">
                            <?php
                            echo $general["promedio"] !== null
                                ? number_format((float) $general["promedio"], 2)
                                : "Sin datos";
                            ?>
                        </p>

                        <small class="text-muted">
                            Escala del 1 al 5
                        </small>

                    </div>
                </div>

            </div>

        </div>

        <!-- Cantidad por calificación -->
        <h2 class="h4 mb-3">
            Cantidad de respuestas por calificación
        </h2>

        <div class="row g-2 mb-4">

            <?php
            $calificaciones = [
                1 => "uno",
                2 => "dos",
                3 => "tres",
                4 => "cuatro",
                5 => "cinco"
            ];
            ?>

            <?php foreach ($calificaciones as $numero => $campo) { ?>

                <div class="col-6 col-md">

                    <div class="card shadow-sm text-center">
                        <div class="card-body">

                            <p class="mb-1">
                                <?php echo $numero; ?> estrella<?php
                                echo $numero === 1 ? "" : "s";
                                ?>
                            </p>

                            <strong class="fs-4">
                                <?php echo (int) $general[$campo]; ?>
                            </strong>

                        </div>
                    </div>

                </div>

            <?php } ?>

        </div>

        <!-- Filtro de estadísticas -->
        <h2 class="h4 mb-3">
            Resultados por documento
        </h2>

        <div class="row g-2 mb-3">

            <div class="col-md-6">

                <label for="buscarEncuesta" class="form-label">
                    Buscar por título o categoría
                </label>

                <input
                    type="search"
                    id="buscarEncuesta"
                    class="form-control"
                    placeholder="Buscar documento..."
                    oninput="filtrarEncuestas()"
                >

            </div>

            <div class="col-md-6">

                <label for="filtroCategoria" class="form-label">
                    Filtrar por categoría
                </label>

                <select
                    id="filtroCategoria"
                    class="form-select"
                    onchange="filtrarEncuestas()"
                >

                    <option value="">
                        Todas las categorías
                    </option>

                    <?php foreach ($categorias as $categoria) { ?>

                        <option value="<?php
                            echo htmlspecialchars(
                                $categoria["nombre"],
                                ENT_QUOTES,
                                "UTF-8"
                            );
                        ?>">
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

        </div>

        <div class="table-responsive mb-5">

            <table class="table table-hover table-bordered bg-white align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Documento</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Respuestas</th>
                        <th>Promedio</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($resultados as $fila) { ?>

                        <tr
                            class="fila-encuesta"
                            data-categoria="<?php
                                echo htmlspecialchars(
                                    $fila["categoria"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                            ?>"
                        >

                            <td class="texto-buscable">
                                <?php
                                echo htmlspecialchars(
                                    $fila["titulo"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $fila["categoria"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </td>

                            <td>
                                <?php if ((int) $fila["activo"] === 1) { ?>
                                    <span class="badge bg-success">
                                        Activo
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">
                                        De baja
                                    </span>
                                <?php } ?>
                            </td>

                            <td>
                                <?php echo (int) $fila["cantidad"]; ?>
                            </td>

                            <td>
                                <?php
                                echo $fila["promedio"] !== null
                                    ? number_format((float) $fila["promedio"], 2)
                                    : "-";
                                ?>
                            </td>

                            <td><?php echo (int) $fila["uno"]; ?></td>
                            <td><?php echo (int) $fila["dos"]; ?></td>
                            <td><?php echo (int) $fila["tres"]; ?></td>
                            <td><?php echo (int) $fila["cuatro"]; ?></td>
                            <td><?php echo (int) $fila["cinco"]; ?></td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <p id="sinResultados" class="text-muted text-center"
               style="display:none;">
                No se encontraron resultados.
            </p>

        </div>

        <!-- Comentarios -->
        <h2 class="h4 mb-3">
            Respuestas individuales
        </h2>

        <div class="table-responsive">

            <table class="table table-hover bg-white align-middle">

                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Documento</th>
                        <th>Categoría</th>
                        <th>Calificación</th>
                        <th>Comentario</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($respuestas as $respuesta) { ?>

                        <tr
                            class="fila-respuesta"
                            data-categoria="<?php
                                echo htmlspecialchars(
                                    $respuesta["categoria"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                            ?>"
                        >

                            <td>
                                <?php
                                echo date(
                                    "d/m/Y",
                                    strtotime($respuesta["fecha"])
                                );
                                ?>
                            </td>

                            <td class="texto-buscable">
                                <?php
                                echo htmlspecialchars(
                                    $respuesta["titulo"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $respuesta["categoria"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo (int) $respuesta["calificacion"]; ?>/5
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $respuesta["comentario"] ?: "Sin comentario",
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <p id="sinRespuestas" class="text-muted text-center"
               style="display:none;">
                No se encontraron respuestas.
            </p>

        </div>

    </main>

    <footer class="pie-sigsm text-center">
        Sistema Informático de Gestión de Servicios Médicos -
        Hospital de Clínicas
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/accesibilidad.js"></script>

    <script>
        function filtrarEncuestas() {

            let buscar = document
                .getElementById("buscarEncuesta")
                .value.toLowerCase();

            let categoria = document
                .getElementById("filtroCategoria")
                .value;

            let filas = document.querySelectorAll(".fila-encuesta");
            let respuestas = document.querySelectorAll(".fila-respuesta");

            let encontrados = 0;
            let respuestasEncontradas = 0;

            filas.forEach(function(fila) {

                let texto = fila.textContent.toLowerCase();
                let categoriaFila = fila.dataset.categoria;

                let coincide = texto.includes(buscar) &&
                    (categoria === "" || categoria === categoriaFila);

                fila.style.display = coincide ? "" : "none";

                if (coincide) encontrados++;
            });

            respuestas.forEach(function(fila) {

                let texto = fila.textContent.toLowerCase();
                let categoriaFila = fila.dataset.categoria;

                let coincide = texto.includes(buscar) &&
                    (categoria === "" || categoria === categoriaFila);

                fila.style.display = coincide ? "" : "none";

                if (coincide) respuestasEncontradas++;
            });

            document.getElementById("sinResultados").style.display =
                encontrados === 0 ? "block" : "none";

            document.getElementById("sinRespuestas").style.display =
                respuestasEncontradas === 0 ? "block" : "none";
        }

        filtrarEncuestas();
    </script>

</body>
</html>

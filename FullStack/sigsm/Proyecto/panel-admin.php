
<?php
session_start();

// Comprobar sesión
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}

require_once "datos/conexion.php";

// Token para dar de baja documentos
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = bin2hex(random_bytes(16));
}

$esAdministrativo = (
    ($_SESSION["rol"] ?? "") === "administrativo"
);

// Obtener documentos activos
$sql = "SELECT d.id_documento,
               d.titulo,
               d.fecha_subida,
               d.id_categoria,
               c.nombre AS categoria
        FROM documento d
        INNER JOIN categoria c
            ON d.id_categoria = c.id_categoria
        WHERE d.activo = 1
        ORDER BY d.fecha_subida DESC,
                 d.id_documento DESC";

$consulta = $pdo->query($sql);
$documentos = $consulta->fetchAll();

// Obtener categorías
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

    <title>Panel de documentos - S.I.G.S.M.</title>

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
            Panel de gestión de documentos
        </h1>

        <p class="text-muted mb-4">
            Desde este panel podés administrar y consultar
            los documentos informativos del sistema.
        </p>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

            <h2 class="h4 mb-0">
                Documentos para pacientes
            </h2>

            <?php if ($esAdministrativo) { ?>

                <a
                    href="documento-form.php"
                    class="btn btn-success btn-lg-accesible"
                >
                    + Nuevo documento
                </a>

            <?php } ?>

        </div>

        <!-- Búsqueda y filtros -->
        <div class="row g-2 mb-3">

            <div class="col-md-4">

                <label for="filtroCategoria" class="form-label">
                    Filtrar por categoría
                </label>

                <select
                    class="form-select"
                    id="filtroCategoria"
                    onchange="filtrarDocumentos()"
                >

                    <option value="">
                        Todas las categorías
                    </option>

                    <?php foreach ($categorias as $categoria) { ?>

                        <option value="<?php echo (int) $categoria["id_categoria"]; ?>">
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

            <div class="col-md-4">

                <label for="buscarDocumento" class="form-label">
                    Buscar documento
                </label>

                <input
                    type="search"
                    id="buscarDocumento"
                    class="form-control"
                    placeholder="Buscar por título..."
                    oninput="filtrarDocumentos()"
                >

            </div>

        </div>

        <!-- Tabla -->
        <div class="table-responsive">

            <table class="table table-hover align-middle tabla-documentos bg-white">

                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Fecha de carga</th>
                        <th>Documento</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($documentos as $doc) { ?>

                        <tr
                            class="fila-documento"
                            data-categoria="<?php echo (int) $doc["id_categoria"]; ?>"
                        >

                            <td class="titulo-documento">
                                <?php
                                echo htmlspecialchars(
                                    $doc["titulo"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </td>

                            <td>
                                <span class="badge badge-categoria">
                                    <?php
                                    echo htmlspecialchars(
                                        $doc["categoria"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    );
                                    ?>
                                </span>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d/m/Y",
                                    strtotime($doc["fecha_subida"])
                                );
                                ?>
                            </td>

                            <td>
                                <a href="documento.php?id=<?php echo (int) $doc["id_documento"]; ?>&amp;origen=panel">
                                    Ver
                                </a>
                            </td>

                            <td class="text-end">

                                <?php if ($esAdministrativo) { ?>

                                    <!-- Editar -->
                                    <a
                                        href="documento-form.php?id=<?php echo (int) $doc["id_documento"]; ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Editar
                                    </a>

                                    <!-- Baja lógica -->
                                    <form
                                        action="eliminar_documento.php"
                                        method="post"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Dar de baja este documento?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $doc["id_documento"]; ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="token"
                                            value="<?php
                                            echo htmlspecialchars(
                                                $_SESSION["token"],
                                                ENT_QUOTES,
                                                "UTF-8"
                                            );
                                            ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Dar de baja
                                        </button>

                                    </form>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <p
                id="sinResultados"
                class="text-center text-muted"
                style="display:none;"
            >
                No se encontraron documentos.
            </p>

        </div>

    </main>

    <footer class="pie-sigsm text-center">
        Sistema Informático de Gestión de Servicios Médicos -
        Hospital de Clínicas
    </footer>

    <!-- Bootstrap JS para el menú -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/accesibilidad.js"></script>

    <script>
        function filtrarDocumentos() {

            let buscar = document
                .getElementById("buscarDocumento")
                .value
                .toLowerCase();

            let categoria = document
                .getElementById("filtroCategoria")
                .value;

            let filas = document.querySelectorAll(".fila-documento");

            let encontrados = 0;

            filas.forEach(function(fila) {

                let titulo = fila
                    .querySelector(".titulo-documento")
                    .textContent
                    .toLowerCase();

                let categoriaFila = fila.getAttribute("data-categoria");

                if (
                    titulo.includes(buscar) &&
                    (categoria === "" || categoria === categoriaFila)
                ) {
                    fila.style.display = "";
                    encontrados++;
                } else {
                    fila.style.display = "none";
                }

            });

            document.getElementById("sinResultados").style.display =
                encontrados === 0 ? "block" : "none";
        }

        filtrarDocumentos();
    </script>

</body>
</html>

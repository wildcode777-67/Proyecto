
<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.html');
    exit;
}

if (($_SESSION['rol'] ?? '') !== 'administrativo') {
    header('Location: panel-admin.php');
    exit;
}

require_once __DIR__ . '/datos/conexion.php';

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$categorias = $pdo->query(
    'SELECT id_categoria, nombre FROM categoria ORDER BY nombre'
)->fetchAll();

$id = 0;
$titulo = '';
$descripcion = '';
$idCategoria = '';
$rutaArchivo = '';
$editando = isset($_GET['id']);

if ($editando) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        http_response_code(400);
        exit('ID de documento no válido.');
    }

    $consulta = $pdo->prepare(
        'SELECT * FROM documento
         WHERE id_documento = ? AND activo = 1'
    );
    $consulta->execute([$id]);
    $documento = $consulta->fetch();

    if (!$documento) {
        http_response_code(404);
        exit('El documento no existe o está dado de baja.');
    }

    $titulo = $documento['titulo'];
    $descripcion = $documento['descripcion'] ?? '';
    $idCategoria = $documento['id_categoria'];
    $rutaArchivo = $documento['ruta_archivo'] ?? '';
}

function escapar($valor) {
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $editando ? 'Editar' : 'Nuevo' ?> documento - S.I.G.S.M.
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link href="css/estilo.css" rel="stylesheet">
</head>

<body>

    <div class="barra-accesibilidad">
        <div class="container d-flex justify-content-end gap-2">
            <button id="btnFuenteMenos" class="btn btn-outline-light btn-sm">
                A-
            </button>
            <button id="btnFuenteMas" class="btn btn-outline-light btn-sm">
                A+
            </button>
            <button id="btnContraste" class="btn btn-outline-light btn-sm">
                Alto contraste
            </button>
        </div>
    </div>

    <?php require __DIR__ . '/menu.php'; ?>

    <main class="container my-4">

        <h1 class="h3 mb-4">
            <?= $editando ? 'Editar documento' : 'Nuevo documento' ?>
        </h1>

        <div class="card shadow-sm mx-auto" style="max-width: 750px;">
            <div class="card-body p-4">

                <form
                    action="guardar_documento.php"
                    method="post"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="token"
                        value="<?= escapar($_SESSION['token']) ?>"
                    >

                    <?php if ($editando): ?>
                        <input
                            type="hidden"
                            name="id"
                            value="<?= (int) $id ?>"
                        >
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">
                            Título del documento
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="titulo"
                            name="titulo"
                            maxlength="150"
                            required
                            value="<?= escapar($titulo) ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">
                            Especialidad o categoría
                        </label>

                        <select
                            class="form-select"
                            id="id_categoria"
                            name="id_categoria"
                            required
                        >
                            <option value="">
                                Seleccioná una categoría
                            </option>

                            <?php foreach ($categorias as $categoria): ?>
                                <option
                                    value="<?= (int) $categoria['id_categoria'] ?>"
                                    <?= (string) $idCategoria ===
                                        (string) $categoria['id_categoria']
                                        ? 'selected' : '' ?>
                                >
                                    <?= escapar($categoria['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            Descripción
                        </label>

                        <textarea
                            class="form-control"
                            id="descripcion"
                            name="descripcion"
                            rows="5"
                            maxlength="5000"
                        ><?= escapar($descripcion) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="archivo" class="form-label">
                            Archivo adjunto
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="archivo"
                            name="archivo"
                            accept=".pdf,.jpg,.jpeg,application/pdf,image/jpeg"
                        >

                        <div class="form-text">
                            Se permiten PDF e imágenes JPG/JPEG de hasta 5 MB.
                        </div>

                        <?php if ($editando && $rutaArchivo !== ''): ?>
                            <div class="alert alert-info mt-3">

                                Este documento ya tiene un archivo adjunto.

                                <div class="mt-2">
                                    <a
                                        class="btn btn-sm btn-outline-primary"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        href="archivo.php?id=<?= (int) $id ?>"
                                    >
                                        Ver archivo actual
                                    </a>
                                </div>

                                <p class="small mb-0 mt-2">
                                    Si no seleccionás otro archivo,
                                    se conservará el actual.
                                </p>

                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <?= $editando ? 'Guardar cambios' : 'Guardar documento' ?>
                        </button>

                        <a
                            href="panel-admin.php"
                            class="btn btn-outline-secondary"
                        >
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <footer class="pie-sigsm text-center">
        Sistema Informático de Gestión de Servicios Médicos -
        Hospital de Clínicas
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/accesibilidad.js"></script>

</body>
</html>

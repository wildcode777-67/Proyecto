<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE documento
                           SET titulo = ?, tipo = ?, cedula_paciente = ?, fecha_emision = ?, ruta_archivo = ?
                           WHERE id = ?");
    $stmt->execute([
        $_POST['titulo'],
        $_POST['tipo'],
        $_POST['cedula_paciente'],
        $_POST['fecha_emision'],
        $_POST['ruta_archivo'],
        $_POST['id'],
    ]);
    header('Location: documentos.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM documento WHERE id = ?");
$stmt->execute([$_GET['id'] ?? 0]);
$doc = $stmt->fetch();

if (!$doc) {
    header('Location: documentos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar documento - S.I.G.S.M.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width: 640px;">

    <form method="post">
        <input type="hidden" name="id" value="<?= (int)$doc['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" maxlength="150" required
                   value="<?= htmlspecialchars($doc['titulo']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="indicacion" <?= $doc['tipo'] === 'indicacion' ? 'selected' : '' ?>>Indicación médica</option>
                <option value="informacion" <?= $doc['tipo'] === 'informacion' ? 'selected' : '' ?>>Información general</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Cédula del paciente</label>
            <input type="text" name="cedula_paciente" class="form-control" maxlength="8" required
                   value="<?= htmlspecialchars($doc['cedula_paciente']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de emisión</label>
            <input type="date" name="fecha_emision" class="form-control" required
                   value="<?= htmlspecialchars($doc['fecha_emision']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Ruta del archivo</label>
            <input type="text" name="ruta_archivo" class="form-control" required
                   value="<?= htmlspecialchars($doc['ruta_archivo']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="documentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>

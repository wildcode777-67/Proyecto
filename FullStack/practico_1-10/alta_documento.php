<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO documento (titulo, tipo, cedula_paciente, fecha_emision, ruta_archivo)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['titulo'],
        $_POST['tipo'],
        $_POST['cedula_paciente'],
        $_POST['fecha_emision'],
        $_POST['ruta_archivo'],
    ]);
    header('Location: documentos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo documento - S.I.G.S.M.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4" style="max-width: 640px;">

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" maxlength="150" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="indicacion">Indicación médica</option>
                <option value="informacion">Información general</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Cédula del paciente</label>
            <input type="text" name="cedula_paciente" class="form-control" maxlength="8" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de emisión</label>
            <input type="date" name="fecha_emision" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ruta del archivo</label>
            <input type="text" name="ruta_archivo" class="form-control"
                   placeholder="documentos/indicacion_45678912_001.pdf" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="documentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>

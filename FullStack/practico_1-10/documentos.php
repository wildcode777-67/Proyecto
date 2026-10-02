<?php
require 'conexion.php';

$stmt = $pdo->query("SELECT * FROM documento WHERE activo = 1 ORDER BY id");
$documentos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documentos - S.I.G.S.M.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-end mb-3">
        <a href="alta_documento.php" class="btn btn-primary">Nuevo documento</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Título</th><th>Tipo</th><th>Cédula</th>
                <th>Fecha</th><th>Archivo</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($documentos as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['id']) ?></td>
                <td><?= htmlspecialchars($d['titulo']) ?></td>
                <td><?= htmlspecialchars($d['tipo']) ?></td>
                <td><?= htmlspecialchars($d['cedula_paciente']) ?></td>
                <td><?= htmlspecialchars($d['fecha_emision']) ?></td>
                <td><?= htmlspecialchars($d['ruta_archivo']) ?></td>
                <td>
                    <a href="editar_documento.php?id=<?= (int)$d['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                    <form action="baja_documento.php" method="post" class="d-inline">
                        <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Baja</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

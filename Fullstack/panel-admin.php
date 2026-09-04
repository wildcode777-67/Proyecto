<?php

require_once "datos/conexion.php";



$sql = "SELECT documento.id_documento,
               documento.titulo,
               documento.fecha_subida,
               categoria.nombre AS categoria
        FROM documento
        JOIN categoria ON documento.id_categoria = categoria.id_categoria
        ORDER BY documento.fecha_subida DESC";

$consulta = $pdo->query($sql);
$documentos = $consulta->fetchAll(); // trae todas las filas como array asociativo
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de administración - S.I.G.S.M.</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

  <div class="barra-accesibilidad">
    <div class="container d-flex justify-content-end gap-2">
      <button id="btnFuenteMenos" class="btn btn-outline-light btn-sm" aria-label="Disminuir tamaño de letra">A-</button>
      <button id="btnFuenteMas" class="btn btn-outline-light btn-sm" aria-label="Aumentar tamaño de letra">A+</button>
      <button id="btnContraste" class="btn btn-outline-light btn-sm" aria-pressed="false" aria-label="Alternar alto contraste">Alto contraste</button>
    </div>
  </div>

  <nav class="navbar navbar-dark navbar-sigsm navbar-expand-md">
    <div class="container">
      <span class="navbar-brand">S.I.G.S.M.</span>
      <div class="d-flex align-items-center gap-3">
        <span class="text-white d-none d-sm-inline">Admin: Recepción</span>
        <a href="login.html" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <main class="container my-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
      <h1 class="h3 mb-0">Documentos para pacientes</h1>
      <a href="documento-form.html" class="btn btn-success btn-lg-accesible">+ Nuevo documento</a>
    </div>

    <!-- Filtro simple por categoría (todavía visual, sin lógica) -->
    <div class="row g-2 mb-3">
      <div class="col-md-4">
        <select class="form-select" id="filtroCategoria">
          <option selected>Todas las categorías</option>
          <option>Nefrología</option>
          <option>Cardiología</option>
          <option>Trasplantes</option>
          <option>Estudios imagenológicos</option>
        </select>
      </div>
      <div class="col-md-4">
        <input type="search" class="form-control" placeholder="Buscar documento...">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle tabla-documentos bg-white">
        <thead>
          <tr>
            <th>Título</th>
            <th>Categoría</th>
            <th>Fecha de carga</th>
            <th>Código QR</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($documentos) === 0): ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No hay documentos cargados todavía.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($documentos as $doc): ?>
              <tr>
                <td><?php echo htmlspecialchars($doc["titulo"]); ?></td>
                <td><span class="badge badge-categoria"><?php echo htmlspecialchars($doc["categoria"]); ?></span></td>
                <td><?php echo date("d/m/Y", strtotime($doc["fecha_subida"])); ?></td>
                <td><a href="documento-qr.html?id=<?php echo $doc["id_documento"]; ?>">Ver</a></td>
                <td class="text-end">
                  <a href="documento-form.html?id=<?php echo $doc["id_documento"]; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                  <a href="#" class="btn btn-sm btn-outline-danger">Eliminar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <p class="text-muted"><small>Datos cargados en vivo desde la base de datos mediante PDO.</small></p>
  </main>

  <footer class="pie-sigsm text-center">
    Sistema Informático de Gestión de Servicios Médicos - Hospital de Clínicas
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/accesibilidad.js"></script>
</body>
</html>

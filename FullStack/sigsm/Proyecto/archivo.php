
<?php
require_once "datos/conexion.php";

// Obtener el documento solicitado
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit("Documento no válido.");
}

// Buscar solamente documentos activos
$sql = "SELECT ruta_archivo
        FROM documento
        WHERE id_documento = ?
        AND activo = 1";

$consulta = $pdo->prepare($sql);
$consulta->execute([$id]);

$documento = $consulta->fetch();

if (!$documento) {
    http_response_code(404);
    exit("Este documento no está disponible.");
}

// Comprobar que tenga archivo
$ruta = $documento["ruta_archivo"] ?? "";

if ($ruta === "") {
    http_response_code(404);
    exit("Este documento no tiene archivo adjunto.");
}

// Comprobar que esté dentro de la carpeta documentos
$carpeta = realpath(__DIR__ . "/documentos");
$archivo = realpath(__DIR__ . "/" . $ruta);

if (
    $carpeta === false ||
    $archivo === false ||
    !str_starts_with(
        $archivo,
        $carpeta . DIRECTORY_SEPARATOR
    ) ||
    !is_file($archivo)
) {
    http_response_code(404);
    exit("Archivo no encontrado.");
}

// Comprobar el tipo real del archivo
$tipo = mime_content_type($archivo);

$tiposPermitidos = [
    "application/pdf",
    "image/jpeg"
];

if (!in_array($tipo, $tiposPermitidos, true)) {
    http_response_code(403);
    exit("Tipo de archivo no permitido.");
}

// Elegir si se abre o se descarga
$descargar = (
    ($_GET["descargar"] ?? "") === "1"
);

$disposicion = $descargar ? "attachment" : "inline";

// Nombre seguro para la descarga
$extension = $tipo === "application/pdf" ? "pdf" : "jpg";
$nombre = "documento-" . $id . "." . $extension;

// Enviar el archivo al navegador
header("Content-Type: " . $tipo);
header("Content-Length: " . filesize($archivo));
header("Content-Disposition: " . $disposicion .
       '; filename="' . $nombre . '"');
header("X-Content-Type-Options: nosniff");
header("Cache-Control: no-store, private");

readfile($archivo);
exit;
?>


<?php
session_start();

// Solo aceptar formularios POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: portal.php");
    exit;
}

// Comprobar token CSRF
$tokenRecibido = $_POST["token_encuesta"] ?? "";

if (
    !isset($_SESSION["token_encuesta"]) ||
    !is_string($tokenRecibido) ||
    !hash_equals(
        $_SESSION["token_encuesta"],
        $tokenRecibido
    )
) {
    http_response_code(403);
    exit("Solicitud no válida. Token de seguridad incorrecto.");
}

require_once "datos/conexion.php";

// Obtener datos
$id = filter_input(
    INPUT_POST,
    "id_documento",
    FILTER_VALIDATE_INT
);

$calificacion = filter_input(
    INPUT_POST,
    "calificacion",
    FILTER_VALIDATE_INT
);

$comentario = trim($_POST["comentario"] ?? "");

// Validar documento
if (!$id || $id < 1) {
    http_response_code(400);
    exit("Documento no válido.");
}

// Validar calificación
if (
    !$calificacion ||
    $calificacion < 1 ||
    $calificacion > 5
) {
    http_response_code(400);
    exit("La calificación debe estar entre 1 y 5.");
}

// Validar comentario
if (mb_strlen($comentario, "UTF-8") > 1000) {
    http_response_code(400);
    exit("El comentario es demasiado largo.");
}

// Comprobar documento activo
$sql = "SELECT id_documento
        FROM documento
        WHERE id_documento = ?
        AND activo = 1";

$consulta = $pdo->prepare($sql);
$consulta->execute([$id]);

if (!$consulta->fetch()) {
    http_response_code(404);
    exit("Esta encuesta ya no está disponible.");
}

// Guardar respuesta
$sql = "INSERT INTO encuesta
        (id_documento, calificacion, comentario, fecha)
        VALUES (?, ?, ?, NOW())";

$consulta = $pdo->prepare($sql);

$consulta->execute([
    $id,
    $calificacion,
    $comentario
]);

// Renovar token después de guardar
$_SESSION["token_encuesta"] = bin2hex(random_bytes(32));

// Volver a la encuesta
header(
    "Location: encuesta.php?id=" . $id . "&enviada=1"
);
exit;
?>

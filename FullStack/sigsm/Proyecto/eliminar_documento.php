
<?php
session_start();

// Solo los administrativos pueden dar de baja
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}

if ($_SESSION["rol"] != "administrativo") {
    header("Location: panel-admin.php");
    exit;
}

// Solo aceptar datos enviados por formulario
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: panel-admin.php");
    exit;
}

// Comprobar el token de seguridad
if (!isset($_SESSION["token"]) ||
    !hash_equals($_SESSION["token"], $_POST["token"] ?? "")) {
    die("Solicitud no válida.");
}

require_once "datos/conexion.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Documento incorrecto.");
}

// Dar de baja el documento sin borrarlo
$sql = "UPDATE documento SET activo = 0
        WHERE id_documento = ? AND activo = 1";

$consulta = $pdo->prepare($sql);
$consulta->execute([$id]);

header("Location: panel-admin.php");
exit;
?>

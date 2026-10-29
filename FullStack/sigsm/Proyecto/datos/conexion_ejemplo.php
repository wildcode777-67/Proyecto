<?php
// S.I.G.S.M. - Ejemplo de conexión a MySQL/MariaDB
// Para instalar el sistema, copiá este archivo en datos/conexion.php
// y reemplazá CAMBIAR_CONTRASENA con la contraseña del servidor.
// Nunca publiques datos/conexion.php con contraseñas reales.

$host = "localhost";
$puerto = "3306";
$bd = "sigsm";
$usuario = "sigsm_app";
$clave = "CAMBIAR_CONTRASENA";

$dsn = "mysql:host=$host;port=$puerto;dbname=$bd;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $usuario, $clave, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $error) {
    error_log("Error de conexión a la base de datos: " . $error->getMessage());
    http_response_code(500);
    exit("No se pudo conectar con la base de datos.");
}

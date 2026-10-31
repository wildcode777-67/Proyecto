
<?php

session_start();

// Conexión con la base de datos
require_once __DIR__ . "/datos/conexion.php";

// Recibir los datos del formulario
$correo = trim($_POST["usuario"] ?? "");
$password = $_POST["password"] ?? "";

// Verificar que los campos no estén vacíos
if ($correo == "" || $password == "") {
    echo "incorrecto";
    exit;
}

// Buscar el usuario en la base de datos
$sql = "SELECT * FROM usuario WHERE email = :correo AND activo = 1";

$consulta = $pdo->prepare($sql);
$consulta->execute(["correo" => $correo]);

$usuario = $consulta->fetch();

// Comprobar la contraseña
if ($usuario && password_verify($password, $usuario["contrasenia"])) {

    session_regenerate_id(true);

    // Guardar los datos del usuario
    $_SESSION["id_usuario"] = $usuario["id"];
    $_SESSION["nombre"] = $usuario["nombre"];
    $_SESSION["rol"] = $usuario["rol"];

    echo "correcto";

} else {

    echo "incorrecto";

}

?>


<?php

session_start();

// Borramos los datos de la sesión
session_unset();

// Cerramos la sesión
session_destroy();

// Volvemos al login
header("Location: login.html");
exit;

?>

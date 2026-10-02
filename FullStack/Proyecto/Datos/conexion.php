<?php


$host    = "localhost";
$puerto  = "3306";
$bd      = "sigsm";
$usuario = "root";
$clave   = "";        

$dsn = "mysql:host=$host;port=$puerto;dbname=$bd;charset=utf8mb4";

try {
   
    $pdo = new PDO($dsn, $usuario, $clave, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $error) {
    
    die("Error al conectar con la base de datos: " . $error->getMessage());
}

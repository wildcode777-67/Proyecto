<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE documento SET activo = 0 WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header('Location: documentos.php');
exit;

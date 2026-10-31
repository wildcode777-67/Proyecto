
<?php
session_start();

// Controlar inicio de sesión y permisos
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.html');
    exit;
}

if (($_SESSION['rol'] ?? '') !== 'administrativo') {
    header('Location: panel-admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panel-admin.php');
    exit;
}

// Verificar token de seguridad
if (
    !isset($_SESSION['token']) ||
    !is_string($_POST['token'] ?? null) ||
    !hash_equals($_SESSION['token'], $_POST['token'])
) {
    http_response_code(403);
    exit('Solicitud no válida. Token de seguridad incorrecto.');
}

require_once __DIR__ . '/datos/conexion.php';

// Datos recibidos
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

$idCategoria = filter_input(
    INPUT_POST,
    'id_categoria',
    FILTER_VALIDATE_INT
);

$idUsuario = (int) $_SESSION['id_usuario'];
$editando = isset($_POST['id']);
$id = 0;

// Comprobar ID al editar
if ($editando) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        http_response_code(400);
        exit('ID de documento incorrecto.');
    }
}

// Validar título: máximo 150 caracteres
if (!preg_match('//u', $titulo)) {
    exit('El título contiene caracteres no válidos.');
}

$longitudTitulo = function_exists('mb_strlen')
    ? mb_strlen($titulo, 'UTF-8')
    : preg_match_all('/./us', $titulo);

if ($titulo === '' || $longitudTitulo > 150) {
    exit('El título es obligatorio y no puede superar los 150 caracteres.');
}

// Validar descripción
if (strlen($descripcion) > 5000) {
    exit('La descripción es demasiado larga.');
}

// Validar categoría
if (!$idCategoria || $idCategoria < 1) {
    exit('Seleccioná una categoría válida.');
}

$consulta = $pdo->prepare(
    'SELECT id_categoria FROM categoria WHERE id_categoria = ?'
);

$consulta->execute([$idCategoria]);

if (!$consulta->fetch()) {
    exit('La categoría seleccionada no existe.');
}

// Comprobar si se adjuntó un archivo
$hayArchivo = (
    isset($_FILES['archivo']) &&
    $_FILES['archivo']['error'] !== UPLOAD_ERR_NO_FILE
);

$archivoTemporal = '';
$extension = '';

if ($hayArchivo) {

    $archivo = $_FILES['archivo'];

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        exit('No se pudo subir el archivo.');
    }

    // Máximo 5 MB
    if (
        $archivo['size'] <= 0 ||
        $archivo['size'] > 5 * 1024 * 1024
    ) {
        exit('El archivo debe tener un tamaño máximo de 5 MB.');
    }

    $archivoTemporal = $archivo['tmp_name'];

    if (!is_uploaded_file($archivoTemporal)) {
        exit('El archivo recibido no es válido.');
    }

    // Verificar el tipo real del archivo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipo = $finfo->file($archivoTemporal);

    $tiposPermitidos = [
        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg'
    ];

    if (!isset($tiposPermitidos[$tipo])) {
        exit('Solo se permiten archivos PDF y JPG/JPEG.');
    }

    $extension = $tiposPermitidos[$tipo];

    // Verificar contenido básico del archivo
    if ($tipo === 'application/pdf') {

        $fp = fopen($archivoTemporal, 'rb');
        $cabecera = $fp ? fread($fp, 5) : '';

        if ($fp) {
            fclose($fp);
        }

        if ($cabecera !== '%PDF-') {
            exit('El archivo PDF no es válido.');
        }

    } elseif (@getimagesize($archivoTemporal) === false) {
        exit('La imagen JPG no es válida.');
    }
}

$rutaNueva = '';
$archivoNuevo = '';

try {

    $pdo->beginTransaction();

    // No permitir editar documentos dados de baja
    if ($editando) {

        $consulta = $pdo->prepare(
            'SELECT id_documento
             FROM documento
             WHERE id_documento = ?
             AND activo = 1
             FOR UPDATE'
        );

        $consulta->execute([$id]);

        if (!$consulta->fetch()) {
            throw new Exception(
                'No se puede editar un documento que no existe o está dado de baja.'
            );
        }
    }

    // Guardar nuevo archivo, si corresponde
    if ($hayArchivo) {

        $carpeta = __DIR__ . '/documentos';

        if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true)) {
            throw new Exception(
                'No se pudo crear la carpeta de documentos.'
            );
        }

        $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extension;

        $archivoNuevo = $carpeta . '/' . $nombreArchivo;
        $rutaNueva = 'documentos/' . $nombreArchivo;

        if (!move_uploaded_file($archivoTemporal, $archivoNuevo)) {
            throw new Exception(
                'No se pudo guardar el archivo adjunto.'
            );
        }
    }

    // Editar documento existente
    if ($editando) {

        if ($hayArchivo) {

            $consulta = $pdo->prepare(
                'UPDATE documento
                 SET titulo = ?,
                     descripcion = ?,
                     id_categoria = ?,
                     ruta_archivo = ?
                 WHERE id_documento = ?
                 AND activo = 1'
            );

            $consulta->execute([
                $titulo,
                $descripcion,
                $idCategoria,
                $rutaNueva,
                $id
            ]);

        } else {

            // Mantener el archivo anterior
            $consulta = $pdo->prepare(
                'UPDATE documento
                 SET titulo = ?,
                     descripcion = ?,
                     id_categoria = ?
                 WHERE id_documento = ?
                 AND activo = 1'
            );

            $consulta->execute([
                $titulo,
                $descripcion,
                $idCategoria,
                $id
            ]);
        }

    } else {

        // Crear documento nuevo
        $consulta = $pdo->prepare(
            'INSERT INTO documento (
                titulo,
                descripcion,
                id_categoria,
                id_usuario,
                fecha_subida,
                ruta_archivo
            ) VALUES (?, ?, ?, ?, NOW(), ?)'
        );

        $consulta->execute([
            $titulo,
            $descripcion,
            $idCategoria,
            $idUsuario,
            $rutaNueva
        ]);
    }

    $pdo->commit();

    header('Location: panel-admin.php');
    exit;

} catch (Throwable $error) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Eliminar solo el archivo nuevo si falló el guardado
    if ($archivoNuevo !== '' && is_file($archivoNuevo)) {
        unlink($archivoNuevo);
    }

    error_log(
        'Error al guardar documento: ' . $error->getMessage()
    );

    http_response_code(500);

    exit(
        'No se pudo guardar el documento. Revisá los datos e intentá nuevamente.'
    );
}


<?php
// Iniciar sesión solamente si todavía no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo funcionarios con sesión iniciada
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit;
}

$esAdministrativo = (
    ($_SESSION["rol"] ?? "") === "administrativo"
);

// Detectar automáticamente la página actual
$paginaActual = basename($_SERVER["SCRIPT_NAME"]);

$nombreUsuario = htmlspecialchars(
    $_SESSION["nombre"] ?? "Usuario",
    ENT_QUOTES,
    "UTF-8"
);
?>

<style>
    .boton-menu {
        background: transparent;
        color: white;
        border: 1px solid rgba(255,255,255,0.7);
        border-radius: 6px;
        padding: 6px 11px;
        font-size: 22px;
        line-height: 1;
    }

    .boton-menu:hover,
    .boton-menu:focus {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .menu-sigsm .list-group-item {
        border: none;
        border-radius: 8px;
        margin-bottom: 6px;
        padding: 12px 15px;
        color: #24324a;
        text-decoration: none;
    }

    .menu-sigsm .list-group-item:hover {
        background: #e9f2ff;
        color: #0d6efd;
    }

    .menu-sigsm .opcion-actual {
        background: #e9f2ff;
        color: #0d6efd;
        font-weight: bold;
    }

    .menu-sigsm .cerrar-sesion {
        color: #dc3545;
    }

    .menu-sigsm .cerrar-sesion:hover {
        background: #fff1f2;
        color: #b02a37;
    }
</style>

<!-- Barra superior -->
<nav class="navbar navbar-dark navbar-sigsm">
    <div class="container">

        <div class="d-flex align-items-center gap-3">

            <button
                class="boton-menu"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal"
                aria-label="Abrir menú principal"
            >
                &#9776;
            </button>

            <span class="navbar-brand mb-0">
                S.I.G.S.M.
            </span>

        </div>

        <span class="text-white d-none d-sm-inline">
            <?php echo $nombreUsuario; ?>
        </span>

    </div>
</nav>

<!-- Menú lateral -->
<div
    class="offcanvas offcanvas-start menu-sigsm"
    tabindex="-1"
    id="menuPrincipal"
    aria-labelledby="tituloMenu"
    style="max-width: 320px;"
>

    <div class="offcanvas-header border-bottom">

        <h2 class="offcanvas-title h5" id="tituloMenu">
            Menú principal
        </h2>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar menú"
        ></button>

    </div>

    <div class="offcanvas-body d-flex flex-column">

        <p class="text-muted small mb-3">
            <?php echo $nombreUsuario; ?>
        </p>

        <div class="list-group">

            <!-- Panel de documentos -->
            <a
                href="panel-admin.php"
                class="list-group-item <?php
                    echo $paginaActual === "panel-admin.php"
                        ? "opcion-actual"
                        : "";
                ?>"
            >
                &#9638; Panel de documentos
            </a>

            <!-- Portal público -->
           <a
    href="portal.php"
    class="list-group-item <?php
        echo $paginaActual === "portal.php"
            ? "opcion-actual"
            : "";
    ?>"
>
    &#9678; Portal de pacientes
</a>

            <?php if ($esAdministrativo) { ?>

                <!-- Resultados de encuestas -->
                <a
                    href="resultados-encuestas.php"
                    class="list-group-item <?php
                        echo $paginaActual === "resultados-encuestas.php"
                            ? "opcion-actual"
                            : "";
                    ?>"
                >
                    &#9776; Resultados de encuestas
                </a>

                <!-- QR -->
                <a
                    href="qr-portal.php"
                    class="list-group-item <?php
                        echo $paginaActual === "qr-portal.php"
                            ? "opcion-actual"
                            : "";
                    ?>"
                >
                    &#9635; Código QR del portal
                </a>

            <?php } ?>

        </div>

        <!-- Cerrar sesión -->
        <div class="mt-auto border-top pt-3">

            <a
                href="cerrar_sesion.php"
                class="list-group-item cerrar-sesion d-block"
            >
                Cerrar sesión
            </a>

        </div>

    </div>
</div>

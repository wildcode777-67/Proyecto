
-- ==========================================
-- S.I.G.S.M. - WILD CODE
-- Usuarios y permisos de MySQL
-- ==========================================

-- IMPORTANTE:
-- Ejecutar despues de importar sigsm_final.sql.
-- Cambiar las contraseñas de ejemplo antes de ejecutar.
-- Ejecutar con un usuario administrador de MySQL.

-- Crear usuario administrador
CREATE USER IF NOT EXISTS
'sigsm_admin'@'localhost'
IDENTIFIED BY 'CAMBIAR_CLAVE_ADMIN';

-- Crear usuario de la aplicacion
CREATE USER IF NOT EXISTS
'sigsm_app'@'localhost'
IDENTIFIED BY 'CAMBIAR_CLAVE_APP';

-- Administrador: permisos sobre toda la base
GRANT ALL PRIVILEGES
ON sigsm.*
TO 'sigsm_admin'@'localhost';

-- Aplicacion: consultar usuarios para el login
GRANT SELECT
ON sigsm.usuario
TO 'sigsm_app'@'localhost';

-- Aplicacion: consultar categorias
GRANT SELECT
ON sigsm.categoria
TO 'sigsm_app'@'localhost';

-- Aplicacion: gestionar documentos
GRANT SELECT, INSERT, UPDATE
ON sigsm.documento
TO 'sigsm_app'@'localhost';

-- Aplicacion: guardar y consultar encuestas
GRANT SELECT, INSERT
ON sigsm.encuesta
TO 'sigsm_app'@'localhost';

-- Acceso de lectura a las vistas
GRANT SELECT
ON sigsm.vista_documentos_activos
TO 'sigsm_app'@'localhost';

GRANT SELECT
ON sigsm.vista_resultados_encuestas
TO 'sigsm_app'@'localhost';

-- Comprobar permisos
SHOW GRANTS FOR 'sigsm_admin'@'localhost';

SHOW GRANTS FOR 'sigsm_app'@'localhost';

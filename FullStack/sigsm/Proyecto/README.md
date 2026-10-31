
# S.I.G.S.M.
## Sistema Informático de Gestión de Servicios Médicos

### 1. Información del proyecto

Empresa: Wild Code

Integrantes:
- Bryan Ferreira
- Thiago Sánchez

Cliente del proyecto académico: Hospital de Clínicas, Uruguay.

El S.I.G.S.M. es un sistema web desarrollado para facilitar la gestión y consulta de documentos informativos destinados a pacientes.

Su objetivo es mejorar la organización de la documentación y reducir la necesidad de entregar información impresa.

### 2. Tecnologías utilizadas

- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- PHP
- MySQL/MariaDB
- XAMPP
- phpMyAdmin

### 3. Funcionalidades

- Inicio y cierre de sesión.
- Control de acceso según el rol.
- Panel administrativo.
- Registro de documentos.
- Edición de documentos.
- Baja lógica de documentos.
- Organización por especialidades médicas.
- Búsqueda y filtrado de documentos.
- Portal público para pacientes.
- Visualización de archivos PDF.
- Encuestas de satisfacción anónimas.
- Resultados y estadísticas de encuestas.
- Código QR general del portal (simulación).
- Opciones de accesibilidad.
- Validación de datos y controles de seguridad.

### 4. Requisitos de instalación

Se necesita:

- Una computadora con XAMPP instalado.
- Apache habilitado.
- MySQL o MariaDB habilitado.
- PHP con extensión PDO MySQL.
- Un navegador web.

### 5. Instalación

1. Instalar XAMPP.
2. Encender Apache y MySQL.
3. Copiar la carpeta Proyecto dentro de:

   C:\xampp\htdocs\sigsm\

4. Abrir phpMyAdmin:

   http://localhost/phpmyadmin

5. Importar el archivo sigsm_final.sql que se
   encuentra en Base_de_datos.

6. Configurar los usuarios de MySQL utilizando
   usuarios_permisos.sql con un administrador
   de la base de datos.

7. Revisar el archivo:

   Proyecto/datos/conexion.php

8. Configurar el usuario y contraseña de
   MySQL correspondientes a sigsm_app.

9. Comprobar que la carpeta documentos
   contiene los PDF necesarios.

10. Abrir la aplicación en el navegador:

    http://localhost/sigsm/Proyecto/login.html

### 6. Acceso al sistema

El sistema dispone de dos roles:

Administrativo:
- Gestiona los documentos.
- Consulta resultados de encuestas.
- Accede al código QR.

Médico:
- Consulta documentos.
- No puede realizar tareas administrativas.

Los pacientes pueden acceder al portal
público sin iniciar sesión.

Portal de pacientes:

http://localhost/sigsm/Proyecto/portal.php

Las credenciales de prueba deben entregarse
por separado y no publicarse en este archivo.

### 7. Base de datos

Nombre: sigsm

Tablas principales:

- usuario
- categoria
- documento
- encuesta

Vistas SQL:

- vista_documentos_activos
- vista_resultados_encuestas

La aplicación utiliza un usuario de MySQL
con permisos restringidos.

### 8. Seguridad

El sistema incorpora:

- Contraseñas protegidas mediante hash.
- Verificación de sesiones.
- Control de permisos según el rol.
- Consultas preparadas mediante PDO.
- Validación de formularios.
- Protección CSRF.
- Restricción del acceso directo a PDF.
- Baja lógica de documentos.

### 9. Acceso mediante QR

El sistema incluye un QR general para
acceder al portal de pacientes.

En el entorno local, se utiliza una
simulación académica.

Para acceder desde otros dispositivos
sería necesario configurar una dirección
de red accesible.

### 10. Estado del proyecto

El módulo de gestión documental, el portal
de pacientes y las encuestas se encuentran
implementados.

La instalación en otra computadora y las
pruebas finales deben verificarse antes
de considerar finalizada la entrega.

Este proyecto utiliza datos ficticios
con fines educativos.

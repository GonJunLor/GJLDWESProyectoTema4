<?php 
// Lee las variables de entorno para DSN, USERNAME y PASSWORD
define('DSN', getenv('DB_DSN'));
define('USERNAME', getenv('DB_USERNAME'));
define('PASSWORD', getenv('DB_PASSWORD'));

// Verificar si las variables se cargaron correctamente (opcional pero recomendado)
if (!DSN || !USERNAME || !PASSWORD) {
    // Aquí puedes lanzar una excepción o registrar un error si faltan las credenciales
    error_log("ERROR: Las variables de entorno de la base de datos no están definidas.");
}
?>
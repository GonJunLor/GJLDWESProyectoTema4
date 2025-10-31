<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../webroot/media/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="../webroot/css/estilos.css">
    <title>Gonzalo Junquera Lorenzo</title>
</head>
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 01 MySQLi</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 01/11/2025
        * 1. Conexión a la base de datos con la cuenta usuario y tratamiento de errores. Utilizar excepciones automáticas siempre que sea posible en todos los ejercicios.
        */

        /* activar notificación */
        $controlador = new mysqli_driver();
        $controlador->report_mode = MYSQLI_REPORT_ALL;
        /* Con esto hacemos que salte la excepcion automáticamente, ya que sino no saltaría por el catch */

        try {
            $aConfig = require '../tmp/configConexion.php';
        } catch (Exception $e) {
            echo 'Error Fatal: No se pudo cargar el archivo de configuración. ' . $e->getMessage();
        }

        try {
            $miDB = new mysqli($aConfig['host'],$aConfig['username'],$aConfig['password'],$aConfig['dbname']);
            echo 'Conectado a la BBDD con éxito';

        } catch (mysqli_sql_exception $miExceptionMySQLi) {
            echo 'Error: '.$miExceptionMySQLi->getMessage();
            echo '<br>';
            echo 'Código de error: '.$miExceptionMySQLi->getCode();
        } finally {
            $miDB->close();
        }
       ?>
    </main>
</body>
</html>
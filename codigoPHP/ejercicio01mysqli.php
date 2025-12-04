<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../webroot/media/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="../webroot/css/estilos.css">
    <title>Gonzalo Junquera Lorenzo</title>
        <style>
        main{
            margin: 20px 20px 20px 100px;
        }
        h3{
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .rojo{color: red;}
        .azul{color: #0401a5ff}
    </style>
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

        // preparación de los datos de conexión para luego usarlos en el mysqli
        const HOSTNAME = "localhost";
        const USERNAME = 'userGJLDWESProyectoTema4';
        // const PASSWORD = 'paso';
        const PASSWORD = '5813Libro-Puro';
        const DATABASE = 'DBGJLDWESProyectoTema4';

        // Aqui están las propiedades https://www.php.net/manual/es/class.mysqli.php
        $aPropiedadesMySQLi = [
            // --- Propiedades de Información de Conexión / Versión ---
            'host_info'        => 'Información del Host (Tipo de conexión)',
            'server_info'      => 'Información del Servidor (Versión en cadena)',
            'server_version'   => 'Versión del Servidor (Entero)',
            'protocol_version' => 'Versión del Protocolo MySQL',
            'client_info'      => 'Información del Cliente (Librería)',
            'client_version'   => 'Versión del Cliente (Entero)',
            'thread_id'        => 'ID de Hilo en el Servidor',

            // --- Propiedades de Estado de la Conexión ---
            'connect_errno'    => 'Código de Error de Conexión',
            'connect_error'    => 'Mensaje de Error de Conexión',

            // --- Propiedades de Resultados de Consultas (Se llenan después de una operación) ---
            'affected_rows'    => 'Filas Afectadas por la última consulta',
            'insert_id'        => 'ID Generado por la última inserción (AUTO_INCREMENT)',
            'field_count'      => 'Número de campos devueltos',
            'warning_count'    => 'Número de Advertencias de la última consulta',
            'info'             => 'Información Adicional de la última consulta (e.g., filas encontradas)',

            // --- Propiedades de Error de la Última Consulta (Se llenan después de una operación) ---
            'errno'            => 'Código de Error de la Última Consulta',
            'error'            => 'Mensaje de Error de la Última Consulta',
            'error_list'       => 'Lista completa de errores de la última consulta',
            'sqlstate'         => 'Código SQLSTATE de la Última Consulta',
        ];
      
        /* activar notificación */
        $controlador = new mysqli_driver();
        $controlador->report_mode = MYSQLI_REPORT_ALL;
        /* Con esto hacemos que salte la excepcion automáticamente, ya que sino no saltaría por el catch */

        // Establecimiento de conexión con valores correctos
        echo '<h3>Conexión a '.DATABASE.' correctamente: </h3>';

        try {
            $miDB = new mysqli(HOSTNAME,USERNAME,PASSWORD,DATABASE);

            echo 'Conectado a la BBDD con éxito';
            echo '<br><br>';

            echo '<p><b>Atributos de la conexión: </b></p>';
            // --- Mostrar Propiedades de MySQLi ---
            foreach ( $aPropiedadesMySQLi as $propiedad => $descripcion ) {
                // Accedemos a las propiedades públicas del objeto $miDB (MySQLi)
                try {
                    echo "$descripcion ($propiedad): <span class=\"azul\">" . $miDB->$propiedad . "</span><br>";
                } catch (mysqli_sql_exception $miExceptionMySQLi) {
                    echo "$descripcion ($propiedad): <span class=\"rojo\">";
                    echo 'Error: '.$miExceptionMySQLi->getMessage();
                    echo ' Código de error: '.$miExceptionMySQLi->getCode();
                    echo "</span><br>";
                }
                
            }

        } catch (mysqli_sql_exception $miExceptionMySQLi) {
            echo 'Error: '.$miExceptionMySQLi->getMessage();
            echo '<br>';
            echo 'Código de error: '.$miExceptionMySQLi->getCode();
        } finally {
            $miDB->close();
        }

        // Establecimiento de conexión con valores incorrectos
        echo '<h3>Conexión a '.DATABASE.' correctamente: </h3>';

        try {
            $miDB = new mysqli(HOSTNAME,USERNAME,"error",DATABASE);

            echo 'Conectado a la BBDD con éxito';
            echo '<br><br>';

            echo '<p><b>Atributos de la conexión: </b></p>';
            // --- Mostrar Propiedades de MySQLi ---
            foreach ( $aPropiedadesMySQLi as $propiedad => $descripcion ) {
                // Accedemos a las propiedades públicas del objeto $miDB (MySQLi)
                try {
                    echo "$descripcion ($propiedad): <span class=\"azul\">" . $miDB->$propiedad . "</span><br>";
                } catch (mysqli_sql_exception $miExceptionMySQLi) {
                    echo "$descripcion ($propiedad): <span class=\"rojo\">";
                    echo 'Error: '.$miExceptionMySQLi->getMessage();
                    echo ' Código de error: '.$miExceptionMySQLi->getCode();
                    echo "</span><br>";
                }
                
            }

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
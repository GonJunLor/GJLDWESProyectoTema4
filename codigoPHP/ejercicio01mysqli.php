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

        // preparación de los datos de conexión para luego usarlos en el PDO
        const HOSTNAME = "10.199.8.153";
        const USERNAME = 'userGJLDWESProyectoTema4';
        const PASSWORD = 'paso';
        const DATABASE = 'DBGJLDWESProyectoTema4';

        // Atributos de la conexión para usar después al mostrar
        $aPropiedadesMySQLi = [
            'host_info' => 'Información del Host',
            'server_version' => 'Versión del Servidor',
            'protocol_version' => 'Versión del Protocolo',
            'client_info' => 'Información del Cliente',
            'thread_id' => 'ID de Hilo',
            'server_info' => 'Información del servidor'
        ];
        Investigar de donde sacamoestas propiedades

        /* activar notificación */
        $controlador = new mysqli_driver();
        $controlador->report_mode = MYSQLI_REPORT_ALL;
        /* Con esto hacemos que salte la excepcion automáticamente, ya que sino no saltaría por el catch */

        // Establecimiento de conexión con valores correctos
        echo '<h3>Conexión a BBDD correctamente: </h3>';

        try {
            $miDB = new mysqli(HOSTNAME,USERNAME,PASSWORD,DATABASE);

            echo 'Conectado a la BBDD con éxito';
            echo '<br><br>';

            echo '<p><b>Atributos de la conexión: </b></p>';
            // --- Mostrar Propiedades de MySQLi ---
            foreach ( $aPropiedadesMySQLi as $propiedad => $descripcion ) {
                // Accedemos a las propiedades públicas del objeto $miDB (MySQLi)
                try {
                    echo "$descripcion ($propiedad): <span class=\"rojo\">" . $miDB->$propiedad . "</span><br>";
                } catch (mysqli_sql_exception $miExceptionMySQLi) {
                    echo "$descripcion ($propiedad): <span class=\"rojo\">";
                    echo 'Error: '.$miExceptionMySQLi->getMessage();
                    echo ' Código de error: '.$miExceptionMySQLi->getCode();
                    echo "</span><br>";
                }
                
            }
            // foreach ( $aAtributos as $atributo ) {
            //     echo "PDO::ATTR_$atributo: ";
            //     try {
            //         echo '<span class="rojo">'.$miDB->getAttribute( constant( "PDO::ATTR_$atributo" ) ) . "</span><br>";
            //     } catch ( PDOException $miExceptionPDO ) {
            //         echo '<span class="rojo"> <b>Error: </b>'.$miExceptionPDO->getMessage().' <b>con código de error:</b> '.$miExceptionPDO->getCode()."</span><br>";
            //     }
            // }

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
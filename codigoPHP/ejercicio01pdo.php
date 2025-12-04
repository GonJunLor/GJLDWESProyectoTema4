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
            text-align: right;
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
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 01 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 09/11/2025
        * 1. Conexión a la base de datos con la cuenta usuario y tratamiento de errores. Utilizar excepciones automáticas siempre que sea posible en todos los ejercicios.
        */
        // importamos el archivo con los datos de conexión
        require_once '../conf/confDBPDO.php';

        // Atributos de la conexión para usar después al mostrar
        $aAtributos = array(
            "AUTOCOMMIT", "ERRMODE", "CASE", "CLIENT_VERSION", "CONNECTION_STATUS",
            "ORACLE_NULLS", "PERSISTENT", "PREFETCH", "SERVER_INFO", "SERVER_VERSION",
            "TIMEOUT"
        );

        // Establecimiento de conexión con valores correctos
        echo '<h3>Conexión a DBGJLDWESProyectoTema4 correctamente: </h3>';
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            echo 'Conectado a la BBDD con éxito';
            echo '<br><br>';

            echo '<p><b>Atributos de la conexión: </b></p>';
            foreach ( $aAtributos as $atributo ) {
                echo "PDO::ATTR_$atributo: ";
                try {
                    echo '<span class="azul">'.$miDB->getAttribute( constant( "PDO::ATTR_$atributo" ) ) . "</span><br>";
                } catch ( PDOException $miExceptionPDO ) {
                    echo '<span class="rojo"> <b>Error: </b>'.$miExceptionPDO->getMessage().' <b>con código de error:</b> '.$miExceptionPDO->getCode()."</span><br>";
                }
            }

        } catch (PDOException $miExceptionPDO) {
            echo 'Error: '.$miExceptionPDO->getMessage();
            echo '<br>';
            echo 'Código de error: '.$miExceptionPDO->getCode();
        } finally {
            unset($miDB);
        }

        // Establecimiento de conexión con valores incorrectos
        echo '<h3>Conexión a DBGJLDWESProyectoTema4 incorrectamente: </h3>';
        try {
            $miDB = new PDO(DSN,USERNAME,'error');
            echo 'Conectado a la BBDD con éxito';
            echo '<br><br>';

            echo '<p><b>Atributos de la conexión: </b></p>';
            foreach ( $aAtributos as $atributo ) {
                echo "PDO::ATTR_$atributo: ";
                try {
                    echo '<span class="azul">'.$miDB->getAttribute( constant( "PDO::ATTR_$atributo" ) ) . "</span><br>";
                } catch ( PDOException $miExceptionPDO ) {
                    echo '<span class="rojo"> <b>Error: </b>'.$miExceptionPDO->getMessage().' <b>con código de error:</b> '.$miExceptionPDO->getCode()."</span><br>";
                }
            }

        } catch (PDOException $miExceptionPDO) {
            echo 'Error: '.$miExceptionPDO->getMessage();
            echo '<br>';
            echo 'Código de error: '.$miExceptionPDO->getCode();
        } finally {
            unset($miDB);
        }
        ?>
    </main>
</body>
</html>
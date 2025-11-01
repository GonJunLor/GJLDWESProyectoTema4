<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 02 MySQLi</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 01/11/2025
        * 2. Mostrar el contenido de la tabla Departamento y el número de registros
        */
        // Carga del Archivo de Configuración 
        try {
            $aConfig = require '../tmp/configConexion.php';
        } catch (Exception $e) {
            echo 'Error Fatal: No se pudo cargar el archivo de configuración. ' . $e->getMessage();
        }

        // variable para contar el numero de registros recuperados de la BBDD
        $numRegistros = 0;

        try {
            $miDB = new mysqli($aConfig['host'],$aConfig['username'],$aConfig['password'],$aConfig['dbname']);
            
            // devuelve un objeto de la clase mysql_stmt, que sirve para hacer la consulta preparada
            $consulta = $miDB->stmt_init();
            $consulta->prepare("select * from T02_Departamento");
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>T02_CodDepartamento</th>';
            echo '<th>T02_DescDepartamento</th>';
            echo '<th>T02_FechaCreacionDepartamento</th>';
            echo '<th>T02_VolumenDeNegocio</th>';
            echo '<th>T02_FechaBajaDepartamento</th>';
            echo '</tr>';

            // recuperamos los datos de la consulta y asignamos variables a cada columna
            $consulta->bind_result($codigo,$descripcion,$fechaCreacion,$volumen,$fechaBaja);
            while ($consulta->fetch()) {
                echo '<tr>';
                echo '<td>'.$codigo.'</td>';
                echo '<td>'.$descripcion.'</td>';
                echo '<td>'.$fechaCreacion.'</td>';
                // formateamos el float para que se vea en €
                echo '<td>'.number_format($volumen,2,',','.').' €</td>';
                echo '<td>'.$fechaBaja.'</td>';
                echo '</tr>';
                $numRegistros++;
            }
            echo '</table>';

            echo '<h3>Número de registros: '.$numRegistros.'</h3>';

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
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../webroot/media/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="../webroot/css/estilos.css">
    <title>Gonzalo Junquera Lorenzo</title>
</head>
</html>
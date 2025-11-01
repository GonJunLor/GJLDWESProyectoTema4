<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 02 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 01/11/2025
        * 2. Mostrar el contenido de la tabla Departamento y el número de registros.
        */
        // Carga del Archivo de Configuración 
        try {
            $aConfig = require '../tmp/configConexion.php';
        } catch (Exception $e) {
            echo 'Error Fatal: No se pudo cargar el archivo de configuración. ' . $e->getMessage();
        }

        // preparación de los datos de conexión para luego usarlos en el PDO
        $dsn = "mysql:host=".$aConfig['host']."; dbname=".$aConfig['dbname'];
        $username = $aConfig['username'];
        $password = $aConfig['password'];
        // variable para contar el numero de registros recuperados de la BBDD
        $numRegistros = 0;

        try {
            $miDB = new PDO($dsn,$username,$password);
            
            $consulta = $miDB->prepare("select * from T02_Departamento");
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>T02_CodDepartamento</th>';
            echo '<th>T02_DescDepartamento</th>';
            echo '<th>T02_FechaCreacionDepartamento</th>';
            echo '<th>T02_VolumenDeNegocio</th>';
            echo '<th>T02_FechaBajaDepartamento</th>';
            echo '</tr>';

            while ($registro = $consulta->fetch()) {
                echo '<tr>';
                echo '<td>'.$registro['T02_CodDepartamento'].'</td>';
                echo '<td>'.$registro["T02_DescDepartamento"].'</td>';
                echo '<td>'.$registro["T02_FechaCreacionDepartamento"].'</td>';
                // formateamos el float para que se vea en €
                echo '<td>'.number_format($registro["T02_VolumenDeNegocio"],2,',','.').' €</td>';
                echo '<td>'.$registro["T02_FechaBajaDepartamento"].'</td>';
                echo '</tr>';
                $numRegistros++;
            }
            echo '</table>';

            echo '<h3>Número de registros: '.$numRegistros.'</h3>';

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
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../webroot/media/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="../webroot/css/estilos.css">
    <title>Gonzalo Junquera Lorenzo</title>
</head>
</html>
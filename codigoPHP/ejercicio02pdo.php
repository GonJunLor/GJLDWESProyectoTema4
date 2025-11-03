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
        // preparación de los datos de conexión para luego usarlos en el PDO
        const DSN = "mysql:host=10.199.8.153; dbname=DBGJLDWESProyectoTema4";
        const USERNAME = 'userGJLDWESProyectoTema4';
        const PASSWORD = '5813Libro-Puro';
        // const PASSWORD = 'paso';

        // uso una variable para que la misma línea de codigo me sirva en casa y en clase al usar server_addr
        $DSN = 'mysql:host='.$_SERVER['SERVER_ADDR'].'; dbname=DBGJLDWESProyectoTema4';

        echo '<h3>Tabla usando consultas preparadas</h3>';
        // variable para contar el numero de registros recuperados de la BBDD
        $numRegistros = 0;
        try {
            $miDB = new PDO($DSN,USERNAME,PASSWORD);
            
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

        echo '<h3>Tabla usando consultas con query</h3>';
        try {
            $miDB = new PDO($DSN,USERNAME,PASSWORD);
            
            // No se puede usar exec para consultas de select https://www.php.net/manual/es/pdo.exec.php
            // $numRegistros = $miDB->exec('select * from T02_Departamento');
            $numRegistros = 0;
            $consulta = $miDB->query("select * from T02_Departamento");

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
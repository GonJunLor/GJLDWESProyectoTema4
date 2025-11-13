<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 06 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 01/11/2025
        * 6. Pagina web que cargue registros en la tabla Departamento desde un array departamentosnuevos utilizando una consulta preparada. (Después de programar y entender este ejercicio, modificar los ejercicios anteriores para que utilicen consultas preparadas). Probar consultas preparadas sin bind, pasando los parámetros en un array a execute.
        */
        // importamos el archivo con los datos de conexión
        require_once '../conf/confDBPDO.php';

        $aRespuestas=[ // Partimos de una array bidimensional con los datos a introducir en la BBDD
            [
                'CodDepartamentoGuardar' => 'AAA', 
                'DescDepartamentoGuardar' => 'aaaaaaa'
            ],
            [
                'CodDepartamentoGuardar' => 'BBB', 
                'DescDepartamentoGuardar' => 'bbbbbbbb'
            ],
            [
                'CodDepartamentoGuardar' => 'CCC', // este código ya esta en la BBDD 
                'DescDepartamentoGuardar' => 'ccccccccc'
            ]
        ]; // la fecha es la actual, el volumen todos a 1000€ y la fecha de baja vacía


        // *****************************************************************************
        // Carga de tabla inicial
        // *****************************************************************************
        echo '<h2>Tabla inicial</h2>';
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $sql = "select * from T02_Departamento";
            
            $consulta = $miDB->prepare($sql);
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>Código▼</th>';
            echo '<th>Departamento</th>';
            echo '<th>Fecha de Creacion</th>';
            echo '<th>Volumen de Negocio</th>';
            echo '<th>Fecha de Baja</th>';
            echo '</tr>';

            while ($registro = $consulta->fetch()) {
                echo '<tr>';
                echo '<td>'.$registro['T02_CodDepartamento'].'</td>';
                echo '<td>'.$registro["T02_DescDepartamento"].'</td>';
                // construimos la fecha a partir de la que hay en la bbdd y luego mostramos sólo dia mes y año
                $oFecha = new DateTime($registro["T02_FechaCreacionDepartamento"]);
                echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                // formateamos el float para que se vea en €
                echo '<td>'.number_format($registro["T02_VolumenDeNegocio"],2,',','.').' €</td>';
                if (is_null($registro["T02_FechaBajaDepartamento"])) {
                    echo '<td></td>';
                } else {
                    $oFecha = new DateTime($registro["T02_FechaBajaDepartamento"]);
                    echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

        } catch (PDOException $miExceptionPDO) {
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }


        // *****************************************************************************
        // Sección para consulta preparada con ? y bindParam(índice,valor)
        // *****************************************************************************
        echo "<h2>Carga registro consulta preparada con ? y bindParam(índice,valor) </h2>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();

            $sql = 'insert into T02_Departamento values (?,?,now(),1000,null)';
            $consulta = $miDB->prepare($sql);

            foreach ($aRespuestas as $registro) {
                $consulta->bindParam(1,$registro['CodDepartamentoGuardar']);
                $consulta->bindParam(2,$registro['DescDepartamentoGuardar']);
                $consulta -> execute();
            }

            $miDB->commit();

            $sql = "select * from T02_Departamento";
            
            $consulta = $miDB->prepare($sql);
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>Código▼</th>';
            echo '<th>Departamento</th>';
            echo '<th>Fecha de Creacion</th>';
            echo '<th>Volumen de Negocio</th>';
            echo '<th>Fecha de Baja</th>';
            echo '</tr>';

            while ($registro = $consulta->fetch()) {
                echo '<tr>';
                echo '<td>'.$registro['T02_CodDepartamento'].'</td>';
                echo '<td>'.$registro["T02_DescDepartamento"].'</td>';
                // construimos la fecha a partir de la que hay en la bbdd y luego mostramos sólo dia mes y año
                $oFecha = new DateTime($registro["T02_FechaCreacionDepartamento"]);
                echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                // formateamos el float para que se vea en €
                echo '<td>'.number_format($registro["T02_VolumenDeNegocio"],2,',','.').' €</td>';
                if (is_null($registro["T02_FechaBajaDepartamento"])) {
                    echo '<td></td>';
                } else {
                    $oFecha = new DateTime($registro["T02_FechaBajaDepartamento"]);
                    echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. Cambios deshechos (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }

        echo "<h3>Reset:</h3>";
        echo "<p>Para poder volver a probar borro los 3 insert </p>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();
            
            foreach ($aRespuestas as $registro) {
                $sql = 'delete from T02_Departamento where T02_CodDepartamento="'.$registro["CodDepartamentoGuardar"].'"';
                $miDB->exec($sql);
            }

            $miDB->commit();

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. No ha borrado (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }


        // *****************************************************************************
        // Sección para consulta preparada con :param y bindParam(:param,valor)
        // *****************************************************************************
        echo "<h2>Carga registro consulta preparada con :param y bindParam(:param,valor) </h2>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();

            $sql = 'insert into T02_Departamento values (:codigo,:descripcion,now(),1000,null)';
            $consulta = $miDB->prepare($sql);

            foreach ($aRespuestas as $registro) {
                $consulta->bindParam(':codigo',$registro['CodDepartamentoGuardar']);
                $consulta->bindParam(":descripcion",$registro['DescDepartamentoGuardar']);
                $consulta -> execute();
            }

            $miDB->commit();

            $sql = "select * from T02_Departamento";
            
            $consulta = $miDB->prepare($sql);
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>Código▼</th>';
            echo '<th>Departamento</th>';
            echo '<th>Fecha de Creacion</th>';
            echo '<th>Volumen de Negocio</th>';
            echo '<th>Fecha de Baja</th>';
            echo '</tr>';

            while ($registro = $consulta->fetch()) {
                echo '<tr>';
                echo '<td>'.$registro['T02_CodDepartamento'].'</td>';
                echo '<td>'.$registro["T02_DescDepartamento"].'</td>';
                // construimos la fecha a partir de la que hay en la bbdd y luego mostramos sólo dia mes y año
                $oFecha = new DateTime($registro["T02_FechaCreacionDepartamento"]);
                echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                // formateamos el float para que se vea en €
                echo '<td>'.number_format($registro["T02_VolumenDeNegocio"],2,',','.').' €</td>';
                if (is_null($registro["T02_FechaBajaDepartamento"])) {
                    echo '<td></td>';
                } else {
                    $oFecha = new DateTime($registro["T02_FechaBajaDepartamento"]);
                    echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. Cambios deshechos (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }

        echo "<h3>Reset:</h3>";
        echo "<p>Para poder volver a probar borro los 3 insert </p>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();
            
            foreach ($aRespuestas as $registro) {
                $sql = 'delete from T02_Departamento where T02_CodDepartamento="'.$registro["CodDepartamentoGuardar"].'"';
                $miDB->exec($sql);
            }

            $miDB->commit();

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. No ha borrado (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }


        // *****************************************************************************
        // Sección para consulta preparada con array(":param"=>"valor") y execute(array)
        // *****************************************************************************
        echo '<h2>Carga registro consulta preparada con array(":param"=>"valor") y execute(array) </h2>';
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();

            $sql = 'insert into T02_Departamento values (:codigo,:descripcion,now(),1000,null)';
            $consulta = $miDB->prepare($sql);

            foreach ($aRespuestas as $registro) {
                $parametros = [
                    ":codigo"=>$registro['CodDepartamentoGuardar'],
                    ":descripcion"=>$registro['DescDepartamentoGuardar']
                ];
                $consulta -> execute($parametros);
            }
            
            $miDB->commit();

            $sql = "select * from T02_Departamento";
            
            $consulta = $miDB->prepare($sql);
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>Código▼</th>';
            echo '<th>Departamento</th>';
            echo '<th>Fecha de Creacion</th>';
            echo '<th>Volumen de Negocio</th>';
            echo '<th>Fecha de Baja</th>';
            echo '</tr>';

            while ($registro = $consulta->fetch()) {
                echo '<tr>';
                echo '<td>'.$registro['T02_CodDepartamento'].'</td>';
                echo '<td>'.$registro["T02_DescDepartamento"].'</td>';
                // construimos la fecha a partir de la que hay en la bbdd y luego mostramos sólo dia mes y año
                $oFecha = new DateTime($registro["T02_FechaCreacionDepartamento"]);
                echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                // formateamos el float para que se vea en €
                echo '<td>'.number_format($registro["T02_VolumenDeNegocio"],2,',','.').' €</td>';
                if (is_null($registro["T02_FechaBajaDepartamento"])) {
                    echo '<td></td>';
                } else {
                    $oFecha = new DateTime($registro["T02_FechaBajaDepartamento"]);
                    echo '<td>'.$oFecha->format('d/m/Y').'</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. Cambios deshechos (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }

        echo "<h3>Reset:</h3>";
        echo "<p>Para poder volver a probar borro los 3 insert </p>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();
            
            foreach ($aRespuestas as $registro) {
                $sql = 'delete from T02_Departamento where T02_CodDepartamento="'.$registro["CodDepartamentoGuardar"].'"';
                $miDB->exec($sql);
            }

            $miDB->commit();

            

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. No ha borrado (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
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
    <style>
        main h3, p{
            display: inline-block;
            margin-top: 0;
        }
        main h3{margin-left: 10vw;}
        main h2{
            text-align: center;
            background-color: #ebe5e5ff;
            margin: 30px 10vw 0px 10vw;
            padding: 5px 0;
        }
        table{margin-bottom: 0;}
    </style>
</head>
</html>
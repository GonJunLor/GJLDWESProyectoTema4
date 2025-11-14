<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 05 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 09/11/2025
        * 5. Pagina web que añade tres registros a nuestra tabla Departamento utilizando tres instrucciones 
        * insert y una transacción, de tal forma que se añadan los tres registros o no se añada ninguno.
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

        echo "<h2>Transacción correcta</h2>";
        
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            
            $miDB->beginTransaction();

            foreach ($aRespuestas as $registro) {
                $sql = 'insert into T02_Departamento values ("'.$registro["CodDepartamentoGuardar"].'","'.$registro["DescDepartamentoGuardar"].'",now(),1000,null)';
                echo '<p>'.$sql.'</p>';
                $miDB->exec($sql);
            }

            $miDB->commit();
            echo '<p class="correcto">Transacción COMPLETADA y cambios guardados (COMMIT).</p>';

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo '<p class="error">Transacción fallida. Cambios deshechos (ROLLBACK).</p>';
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }

        echo "<h2>Transacción incorrecta</h2>";
        echo "<p>Volver a intentar los mismos insert de antes</p>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            
            $miDB->beginTransaction();
            
            foreach ($aRespuestas as $registro) {
                $sql = 'insert into T02_Departamento values ("'.$registro["CodDepartamentoGuardar"].'","'.$registro["DescDepartamentoGuardar"].'",now(),1000,null)';
                echo '<p>'.$sql.'</p>';
                $miDB->exec($sql);
            }

            $miDB->commit();
            echo '<p class="correcto">Transacción COMPLETADA y cambios guardados (COMMIT).</p>';

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo '<p class="error">Transacción fallida. Cambios deshechos (ROLLBACK).</p>';
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }

        echo "<h2>Reset</h2>";
        echo "<p>Para poder volver a probar borro los 3 insert </p>";
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);

            $miDB->beginTransaction();
            
            foreach ($aRespuestas as $registro) {
                $sql = 'delete from T02_Departamento where T02_CodDepartamento="'.$registro["CodDepartamentoGuardar"].'"';
                echo '<p>'.$sql.'</p>';
                $miDB->exec($sql);
            }

            $miDB->commit();
            echo '<p class="correcto">Transacción COMPLETADA y campos borrados (COMMIT)</p>.';

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo '<p class="error">Transacción fallida. No ha borrado (ROLLBACK).</p>';
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
        main{
            margin-left: 50px;
        }
        main h2{
            margin: 30px 10vw 0px 10vw;
            padding: 5px 0;
            text-align: center;
            background-color: #ebe5e5ff;
        }
        main p{
            text-align: center;
            padding: 3px;
            margin: 0 10vw;
        }
        .correcto{background-color: #dce9d5ff;}
        .error{background-color: #e9b8b8ff;}
    </style>
</head>
</html>
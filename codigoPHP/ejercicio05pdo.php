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
        * @since: 01/11/2025
        * 5. Pagina web que añade tres registros a nuestra tabla Departamento utilizando tres instrucciones 
        * insert y una transacción, de tal forma que se añadan los tres registros o no se añada ninguno.
        */
        // preparación de los datos de conexión para luego usarlos en el PDO
        define('DSN', 'mysql:host=' . $_SERVER['SERVER_ADDR'] . '; dbname=DBGJLDWESProyectoTema4');
        define('USERNAME','userGJLDWESProyectoTema4');
        define('PASSWORD','5813Libro-Puro');

        // $aRespuestas=[ // Partimos de una array bidimensional con los datos a introducir en la BBDD
        //     [
        //         'T02_CodDepartamento' => 'AAA', 
        //         'T02_DescDepartamento' => 'aaaaaaa'
        //     ],
        //     [
        //         'T02_CodDepartamento' => 'BBB', 
        //         'T02_DescDepartamento' => 'bbbbbbbb'
        //     ],
        //     [
        //         'T02_CodDepartamento' => 'CCC', // este código ya esta en la BBDD 
        //         'T02_DescDepartamento' => 'ccccccccc'
        //     ]
        // ]; // la fecha es la actual, el volumen todos a 1000€ y la fecha de baja vacía


        // Pruebas de la estrcutura básica de transacción
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            echo "Conectado a la BBDD";
            $miDB->beginTransaction();
            echo "en transacción";
            $sql = 'insert into T02_Departamento values ("AAA","aaaaaa",now(),1000,null)';
            $miDB->exec($sql);
            $sql = 'insert into T02_Departamento values ("BBB","bbbbbb",now(),1000,null)';
            $miDB->exec($sql);
            $sql = 'insert into T02_Departamento values ("ING","cccccc",now(),1000,null)';
            // $miDB->exec($sql);

            $miDB->commit();
            echo "<br>Transacción COMPLETADA y cambios guardados (COMMIT).";

        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();
            echo "<br>Transacción fallida. Cambios deshechos (ROLLBACK).";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['T02_CodDepartamento']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
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
</head>
</html>
<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 07 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 13/11/2025
        * 7. Página web que toma datos (código y descripción) de un fichero xml y los añade a la tabla Departamento de nuestra base de datos. (IMPORTAR). El fichero importado se encuentra en el directorio .../tmp/ del servidor.
        */
        // importamos el archivo con los datos de conexión
        require_once '../conf/confDBPDO.php';

        // Antes de empezar hay que comprobar si el archivo existe
        if (!file_exists('../tmp/datos.json')) {
            echo '<h2>El archivo JSON NO existe</h2>';
            echo '<p>Ejecuta el ejercicio 8 antes que éste</p>';

        // si existe el archivo continuamos
        }else {
            // recuperamos el archivo de la carpeta tmp
            $json = file_get_contents('../tmp/datos.json');

            // lo convertimos a un array
            $aDepartamentos = json_decode($json,true);

            try {
                $miDB = new PDO(DSN,USERNAME,PASSWORD);

                
                echo '<h3>Vacío la tabla antes de cargar el JSON</h3>';
                // vacio la tabla antes de volver a cargar los registros del JSON
                $vaciarTabla = $miDB->prepare('TRUNCATE TABLE T02_Departamento');
                $vaciarTabla->execute();

                $miDB->beginTransaction();
                
                $sql = 'insert into T02_Departamento values (:codigo,:descripcion,:fAlta,:volumen,:fBaja)';

                // Esta línea esta para probar a que de error al importar al a bbdd
                // $sql = 'insert into T02_Departamento values (:codigo,:descripcion,:fAlta,:volumen,null)';
                
                $consulta = $miDB->prepare($sql);

                foreach ($aDepartamentos as $registro) {
                    $consulta->bindParam(":codigo",$registro['CodDepartamento']);
                    $consulta->bindParam(":descripcion",$registro['DescDepartamento']);

                    // La bbdd necesita pasarle un objeto fecha, lo creamos y lo formateamos correctamente para la bbdd
                    $oFechaCreacion = new DateTime($registro['FechaCreacionDepartamento']);
                    $oFechaCreacion = $oFechaCreacion->format('Y-m-d H:i:s');
                    $consulta->bindParam(":fAlta",$oFechaCreacion);

                    $consulta->bindParam(":volumen",$registro['VolumenDeNegocio']);

                    // Si no hay fecha devolvemos null para la base de datos
                    if ($registro['FechaBajaDepartamento'] === 'null') {
                        $ofechaBaja = null;

                    // Si existe fecha la creamos y la formateamos antes de pasarla a la BBDD
                    } else {
                        $ofechaBaja = new DateTime($registro['FechaBajaDepartamento']);
                        $ofechaBaja = $ofechaBaja->format('Y-m-d H:i:s');
                    }
                    $consulta->bindParam(":fBaja",$ofechaBaja);

                    $consulta -> execute();
                }

                $miDB->commit();
                echo '<p class="correcto">Datos importados correctamente del JSON a la BBDD</p>';

            } catch (PDOException $miExceptionPDO) {
                $miDB->rollBack();
                echo '<p class="error">Transacción fallida. No se ha importado ningún registro a la BBDD.';
                // temporalmente ponemos estos errores para que se muestren en pantalla
                $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
                $entradaOK = false;
            } finally {
                unset($miDB);
            }
        }

        echo '<h2>Datos actuales en la BBDD </h2>';
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
            $miDB->rollBack();
            echo "<br>Transacción fallida. No se ha importado ningún registro a la BBDD.";
            // temporalmente ponemos estos errores para que se muestren en pantalla
            $aErrores['CodDepartamentoGuardar']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
            $entradaOK = false;
        } finally {
            unset($miDB);
        }

        if (file_exists('../tmp/datos.json')) {
            echo '<h2>Datos en el JSON a importar</h2>';
            echo '<pre>';
            highlight_file('../tmp/datos.json');
            echo '</pre>';
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
        main h2{
            text-align: center;
            background-color: #ebe5e5ff;
            margin: 30px 10vw 0px 10vw;
            padding: 5px 0;
        }
        main h3{
            margin-top: 0;
        }
        main p{
            text-align: center;
            padding: 3px;
            margin: 0 10vw;
        }
        .correcto{background-color: #dce9d5ff;}
        .error{background-color: #e9b8b8ff;}
        main pre{
            margin-left: 10vw;
        }
    </style>
</head>
</html>
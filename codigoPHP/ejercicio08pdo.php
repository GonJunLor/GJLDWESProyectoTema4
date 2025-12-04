<!DOCTYPE html>
<html lang="es">
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
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 08 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 13/11/2025
        * 8. Página web que toma datos (código y descripción) de la tabla Departamento y guarda en un fichero departamento.xml. (COPIA DE SEGURIDAD / EXPORTAR). El fichero exportado se encuentra en el directorio .../tmp/ del servidor. 
        * Si el alumno dispone de tiempo probar distintos formatos de importación - exportación: XML, JSON, CSV, TXT,... 
        * Si el alumno dispone de tiempo probar a exportar e importar a o desde un directorio (a elegir) en el equipo cliente. 
        * Si el alumno dispone de tiempo probar importación parcial con log de errores.
        */
        // importamos el archivo con los datos de conexión
        require_once '../conf/confDBPDO.php';
        $numRegistros = 0;
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            $sql = "select * from T02_Departamento";
            
            $consulta = $miDB->prepare($sql);
            $consulta->execute();

            // inicializo el array vacio para que no me de error después en json_encode en caso de que no haya registros en la bbdd.
            $aDepartamentos=[];
            $indice = 0;
            while ($registro = $consulta->fetchObject()) {
                $aDepartamentos[$indice]=[
                    'CodDepartamento' => $registro->T02_CodDepartamento,
                    'DescDepartamento' => $registro->T02_DescDepartamento,
                    'FechaCreacionDepartamento' => $registro->T02_FechaCreacionDepartamento,
                    'VolumenDeNegocio' => $registro->T02_VolumenDeNegocio,
                    // Si la fecha de baja es NULL en la DB, la convertimos a 'NULL' para la exportación
                    'FechaBajaDepartamento' => $registro->T02_FechaBajaDepartamento ?? 'null'
                ];
                $indice++;
            }
            
            // https://www.php.net/manual/es/json.constants.php
            // JSON_PRETTY_PRINT sirve para que después al visualizarlo haga saltos de linea, sino se ve todo en una sola
            $json = json_encode($aDepartamentos,JSON_PRETTY_PRINT);
            file_put_contents('../tmp/datos.json',$json);
            echo '<p class="correcto">Datos exportados correctamente a JSON</p>';
            
            // Visualizamos el archivo JSON para ver si se ha creado bien
            echo '<h2>Contenido del archivo JSON</h2>';
            echo '<pre>';
            highlight_file("../tmp/datos.json");
            echo '</pre>';

        } catch (PDOException $miExceptionPDO) {
            echo '<p class="error">ERROR al exportar datos a JSON</p>';
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../webroot/media/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="../webroot/css/estilos.css">
    <title>Gonzalo Junquera Lorenzo</title>
    <style>
        #telefono, #nombre {
            background-color: lightgoldenrodyellow;
        }
        main{
            width:600px;
            height: 200px;
            margin: auto;
            background-color: #eeeeee;
            border: 2px solid lightgray;
            border-radius: 20px;
            margin-top: 20px;
            padding: 10px;
        }
        main h2{
            font-family: 'Times New Roman', Times, serif;
            text-align: center;
            margin: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #335d7fff;
        }
        main p{margin:10px 20px;}
        form *{
            margin-top: 10px; 
        }
        label{
            font-family: 'Times New Roman', Times, serif;
            display: inline-block;
            width: 100%;
            text-align: center;
            font-size: 1.2rem;
        }
        label[for="aceptarRgpd"]{width: 200px;}
        label[for="fecha_nacimiento"]{width: 170px;}
        .aviso{
            font-size: 0.75rem;
            margin-left: 20px;
        }
        input{
            padding: 5px 10px;
            margin-top: 20px;
            margin-left: 170px; 
            font-size: 1.2rem;
            border-radius: 5px;
            font-family: 'Times New Roman', Times, serif;
            border: 0px solid grey;
        }
        input[readonly]{
            background-color: #d3d3d3ff;
            color: #6e6e6eff;
        }
        input[type="date"]{width: 190px;}
        input[type="checkbox"]{
            width: 20px;
            height: 18px;
        }
        input[name="enviar"], button, .cancelar{
            padding: 10px 25px;
            font-size: 1.2rem;
            margin: 20px 90px;
            border-radius: 20px;
            background-color: #4988bbff;
            color: white;
            font-family: 'Times New Roman', Times, serif;
            border: 0px solid #252525ff;
            text-decoration: none;
        }
        .error{
            font-family: 'Times New Roman', Times, serif;
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 04 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 09/11/2025
        * 4. Formulario de búsqueda de departamentos por descripción (por una parte del campo DescDepartamento, 
        * si el usuario no pone nada deben aparecer todos los departamentos).
        */

        require_once "../core/231018libreriaValidacion.php"; // importamos nuestra libreria
        // importamos el archivo con los datos de conexión
        require_once '../conf/confDBPDO.php';

        $terminoBusqueda = '%%'; // termino de busqueda explicado al usarlo
       
        $entradaOK = true; //Variable que nos indica que todo va bien
        $aErrores = [  //Array donde recogemos los mensajes de error
            'DescDepartamentoBuscado' => ''
        ];
        $aRespuestas=[ //Array donde recogeremos la respuestas correctas (si $entradaOK)
            'DescDepartamentoBuscado' => ''
        ]; 
        
        //Para cada campo del formulario: Validar entrada y actuar en consecuencia
        if (isset($_REQUEST["enviar"])) {//Código que se ejecuta cuando se envía el formulario

            // Solo queremos validar se introduce algo, sino mostraremos despues todos los registros
            if (!empty($_REQUEST['DescDepartamentoBuscado'])) {
                // Validamos los datos del formulario
                $aErrores['DescDepartamentoBuscado']= validacionFormularios::comprobarAlfabetico($_REQUEST['DescDepartamentoBuscado'],255,0,1);
                
                foreach($aErrores as $campo => $valor){
                    if(!empty($valor)){ // Comprobar si el valor es válido
                        $entradaOK = false;
                    } 
                }
            }
            
        } else {//Código que se ejecuta antes de rellenar el formulario
            $entradaOK = false;
        }


        //Tratamiento del formulario
        if($entradaOK){ //Cargar la variable $aRespuestas y tratamiento de datos OK
            
            // Recuperar los valores del formulario
            $aRespuestas['DescDepartamentoBuscado'] = $_REQUEST['DescDepartamentoBuscado'] ?? ''; // Usamos el operador ?? para asegurar un valor si no existe
           
            // Preparamos el término de búsqueda con comodines y en minúsculas para la búsqueda LIKE. 
            // Los % indica que puede tener cualquier cosa antes y después.
            // Si la descripción está vacía, el término será '%%', devolviendo todos los resultados.
            $terminoBusqueda = '%'.strtolower($aRespuestas['DescDepartamentoBuscado']).'%';
            // Usamos LOWER() en el campo de la DB y en el término de búsqueda para garantizar que la búsqueda sea insensible a mayúsculas/minúsculas.
            
        }

       ?>
        <h2>Buscar departamento</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <label for="DescDepartamentoBuscado">Introduce Departamento a Buscar: </label>
            <br>
            <input type="text" name="DescDepartamentoBuscado" class="obligatorio" value="<?php echo $_REQUEST['DescDepartamentoBuscado']??'' ?>">
            <span class="error"><?php echo $aErrores['DescDepartamentoBuscado'] ?></span>
            <br>
            <input type="submit" value="Buscar" name="enviar">
            <a href="../indexProyectoTema4.php" class="cancelar">Cancelar</a>
        </form>
    </main>
    
    <?php 
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            $sql = <<<sql
                select * from T02_Departamento
                where lower(T02_DescDepartamento) like ?
            sql;
            
            $consulta = $miDB->prepare($sql);
            $consulta->bindParam(1, $terminoBusqueda);
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
            echo 'Error: '.$miExceptionPDO->getMessage();
            echo '<br>';
            echo 'Código de error: '.$miExceptionPDO->getCode();
        } finally {
            unset($miDB);
        }
    ?>
</body>
</html>
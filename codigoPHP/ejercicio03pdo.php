<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 03 PDO</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 09/11/2025
        * 3. Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.
        */
        include_once "../core/231018libreriaValidacion.php";
        
        // preparación de los datos de conexión para luego usarlos en el PDO
        define('DSN', 'mysql:host=' . $_SERVER['SERVER_ADDR'] . '; dbname=DBGJLDWESProyectoTema4');
        define('USERNAME','userGJLDWESProyectoTema4');
        define('PASSWORD','5813Libro-Puro');
       
        $entradaOK = true; //Variable que nos indica que todo va bien
        $aErrores = [  //Array donde recogemos los mensajes de error
            'T02_CodDepartamento' => '', 
            'T02_DescDepartamento' => '',
            'T02_FechaCreacionDepartamento' => '',
            'T02_VolumenDeNegocio' => '',
            'T02_FechaBajaDepartamento' => ''
        ];
        $aRespuestas=[ //Array donde recogeremos la respuestas correctas (si $entradaOK)
            'T02_CodDepartamento' => '', 
            'T02_DescDepartamento' => '',
            'T02_FechaCreacionDepartamento' => '',
            'T02_VolumenDeNegocio' => '',
            'T02_FechaBajaDepartamento' => ''
        ]; 
        
        //Para cada campo del formulario: Validar entrada y actuar en consecuencia
        if (isset($_REQUEST["enviar"])) {//Código que se ejecuta cuando se envía el formulario

            // Validamos los datos del formulario
            $aErrores['T02_CodDepartamento']= validacionFormularios::comprobarAlfabetico($_REQUEST['T02_CodDepartamento'],3,0,1,);
            $aErrores['T02_DescDepartamento']= validacionFormularios::comprobarAlfabetico($_REQUEST['T02_DescDepartamento'],255,0,1);

            // Reemplazar la coma por un punto para estandarizar el formato numérico
            $volumenNegocioPunto = str_replace(',', '.', $_REQUEST['T02_VolumenDeNegocio']);
            $aErrores['T02_VolumenDeNegocio']= validacionFormularios::comprobarFloat($volumenNegocioPunto);
            
            foreach($aErrores as $campo => $valor){
                if(!empty($valor)){ // Comprobar si el valor es válido
                    $entradaOK = false;
                } 
            }

            // Validación de la parte de la bbdd, comprobar si existe el código en ella
            if (empty($aErrores['T02_CodDepartamento'])) {
                
                try {
                    $miDB = new PDO(DSN,USERNAME,PASSWORD);
                    $sql = <<<sql
                        select * from T02_Departamento 
                        where T02_CodDepartamento=?
                    sql;
                    
                    $consulta = $miDB->prepare($sql);
                    $consulta->bindParam(1,$_REQUEST['T02_CodDepartamento']);
                    $consulta->execute();
                    
                    $registro = $consulta->fetch();
                    if ($registro!=false) {// si no devuelve nada que no compruebe el código
                        // Comprobamos si existe el T02_CodDepartamento en la BBDD
                        if($registro[0]==$_REQUEST['T02_CodDepartamento']){
                            $aErrores['T02_CodDepartamento']='El código ya existe un la BBDD';
                            $entradaOK = false;
                        } // si existe guardamos un error para mostrarlo en el formulario
                    }
                    
                } catch (PDOException $miExceptionPDO) {
                    // temporalmente ponemos estos errores para que se muestren en pantalla
                    $aErrores['T02_CodDepartamento']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
                    $entradaOK = false;
                } finally {
                    unset($miDB);
                }
            }
            
        } else {//Código que se ejecuta antes de rellenar el formulario
            $entradaOK = false;
        }


        //Tratamiento del formulario
        if($entradaOK){ //Cargar la variable $aRespuestas y tratamiento de datos OK
            
            // Recuperar los valores del formulario
            $aRespuestas['T02_CodDepartamento'] = $_REQUEST['T02_CodDepartamento'];
            $aRespuestas['T02_DescDepartamento'] = "Departamento de ".$_REQUEST['T02_DescDepartamento'];
            $aRespuestas['T02_VolumenDeNegocio'] = str_replace(',', '.', $_REQUEST['T02_VolumenDeNegocio']);
            
            try {
                    $miDB = new PDO(DSN,USERNAME,PASSWORD);
                    $sql = <<<sql
                        insert into T02_Departamento 
                        values (?,?,now(),?,null)
                    sql;

                    // conexion a la BBDD e insertar un registro
                    $consulta = $miDB->prepare($sql);
                    $consulta->bindParam(1,$aRespuestas['T02_CodDepartamento']);
                    $consulta->bindParam(2,$aRespuestas['T02_DescDepartamento']);
                    $consulta->bindParam(3,$aRespuestas['T02_VolumenDeNegocio']);
                    
                    if($consulta->execute()){
                        echo 'Nuevo departamento creado con éxito';
                    } else {
                        echo 'Error al crear el departamento';
                    }
                    
                    
                } catch (PDOException $miExceptionPDO) {
                    // temporalmente ponemos estos errores para que se muestren en pantalla
                    $aErrores['T02_CodDepartamento']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
                    $entradaOK = false;
                } finally {
                    unset($miDB);
                }

            // Botón para volver a recargar el formulario inicial
            echo '<a href="' . $_SERVER['PHP_SELF'] . '"><button>Volver</button></a>';
            
        } else { //Mostrar el formulario hasta que lo rellenemos correctamente
            //Mostrar formulario
            //Mostrar los datos tecleados correctamente en intentos anteriores
            //Mostrar mensajes de error (si los hay y el formulario no se muestra por primera vez)
            $oFechaActual = new DateTime();
            ?>
                <h2>Nuevo departamento</h2>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"> 
                    <label for="T02_CodDepartamento">Código:</label>
                    <input 
                        type="text" id="T02_CodDepartamento" class="obligatorio" name="T02_CodDepartamento" 
                        value="<?php echo $_REQUEST['T02_CodDepartamento']??'' ?>"
                        style="text-transform: uppercase;" 
                        oninput="this.value = this.value.toUpperCase()"
                    >
                    <span class="error"><?php echo $aErrores['T02_CodDepartamento'] ?></span>
                    <br>
                    <label for="T02_DescDepartamento">Descripción: Departamento de </label>
                    <input type="text" id="T02_DescDepartamento" name="T02_DescDepartamento" class="obligatorio" value="<?php echo $_REQUEST['T02_DescDepartamento']??'' ?>">
                    <span class="error"><?php echo $aErrores['T02_DescDepartamento'] ?></span>
                    <br>
                    <label for="T02_FechaCreacionDepartamento">Fecha de alta:</label>
                    <input type="text" id="T02_FechaCreacionDepartamento" name="T02_FechaCreacionDepartamento" value="<?php echo $oFechaActual->format("d/m/Y") ?>" readonly>
                    <br>
                    <label for="T02_VolumenDeNegocio">Volumen de negocio:</label>
                    <input type="text" id="T02_VolumenDeNegocio" name="T02_VolumenDeNegocio" value="<?php echo $_REQUEST['T02_VolumenDeNegocio']??'' ?>">€
                    <span class="error"><?php echo $aErrores['T02_VolumenDeNegocio'] ?></span>
                    <br>
                    <input type="submit" value="Aceptar" name="enviar">
                    <a href="../indexProyectoTema4.php" class="cancelar">Cancelar</a>
                </form>

            <?php
            
        }
       ?>
    </main>
    <?php 
        try {
            $miDB = new PDO(DSN,USERNAME,PASSWORD);
            $sql = "select * from T02_Departamento";
            
            $consulta = $miDB->prepare($sql);
            $consulta->execute();

            echo '<table>';
            echo '<tr>';
            echo '<th>Código</th>';
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
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../webroot/media/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="../webroot/css/estilos.css">
    <title>Gonzalo Junquera Lorenzo</title>
    <style>
        .obligatorio {
            background-color: lightgoldenrodyellow;
        }
        main{
            width:600px;
            height: 400px;
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
            width: 240px;
            margin-left: 20px;
            font-size: 1.2rem;
            text-align: right;
        }
        /* label[for="T02_CodDepartamento"]{width: 70px;}
        label[for="T02_DescDepartamento"]{width: 240px;}
        label[for="T02_VolumenDeNegocio"]{width: 170px;} */
        .aviso{
            font-size: 0.75rem;
            margin-left: 20px;
        }
        input{
            padding: 5px 10px;
            margin-top: 20px;
            margin-right: 5px;
            font-size: 1.2rem;
            border-radius: 5px;
            font-family: 'Times New Roman', Times, serif;
            border: 0px solid grey;
        }
        #T02_CodDepartamento{width: 70px;}
        #T02_DescDepartamento{width: 200px;}
        #T02_FechaCreacionDepartamento{width: 100px;}
        #T02_VolumenDeNegocio{width: 100px;}
        input[readonly]{
            background-color: #d3d3d3ff;
            color: #6e6e6eff;
        }
        input[type="date"]{width: 190px;}
        input[type="checkbox"]{
            width: 20px;
            height: 18px;
        }
        input[type="submit"], button, .cancelar{
            padding: 10px 25px;
            font-size: 1.2rem;
            margin: 30px 90px;
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
</html>
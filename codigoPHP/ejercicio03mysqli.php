<!DOCTYPE html>
<html lang="es">
<body>
    <div id="aviso">CURSO 2025/2026 -- DAW 2 -- I.E.S. LOS SAUCES</div>
    <nav>
        <div><a href="../indexProyectoTema4.php">Volver</a></div>
        <h2> <a href="../indexProyectoTema4.php">Tema 4</a> - Ejercicio 03 MySQLi</h2>
        <h2>Gonzalo Junquera Lorenzo</h2>
    </nav>
    <main>
       <?php
       /**
        * @author: Gonzalo Junquera Lorenzo
        * @since: 01/11/2025
        * 3. Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.
        */
       
        require_once "../core/231018libreriaValidacion.php"; // importamos nuestra libreria
        
        // preparación de los datos de conexión para luego usarlos en el mysqli
        const HOSTNAME = "10.199.8.153";
        const USERNAME = 'userGJLDWESProyectoTema4';
        // const PASSWORD = 'paso';
        const PASSWORD = '5813Libro-Puro';
        const DATABASE = 'DBGJLDWESProyectoTema4';
        // uso una variable para que la misma línea de codigo me sirva en casa y en clase al usar server_addr
        $HOSTNAME = $_SERVER['SERVER_ADDR'];

        $entradaOK = true; //Variable que nos indica que todo va bien
        $aErrores = [  //Array donde recogemos los mensajes de error
            'codigo' => '', 
            'descripcion' => ''
        ];
        $aRespuestas=[ //Array donde recogeremos la respuestas correctas (si $entradaOK)
            'codigo' => '', 
            'descripcion' => ''
        ]; 

        //Para cada campo del formulario: Validar entrada y actuar en consecuencia
        if (isset($_REQUEST["enviar"])) {//Código que se ejecuta cuando se envía el formulario

            // Validamos los datos del formulario
            $aErrores['codigo']= validacionFormularios::comprobarAlfabetico($_REQUEST['codigo'],3,0,1,);
            $aErrores['descripcion']= validacionFormularios::comprobarAlfabetico($_REQUEST['descripcion'],255,0,1);
            
            foreach($aErrores as $campo => $valor){
                if(!empty($valor)){ // Comprobar si el valor es válido
                    $entradaOK = false;
                } 
            }

            if (empty($aErrores['codigo'])) {
                
                try {
                    $miDB = new mysqli($HOSTNAME,USERNAME,PASSWORD,DATABASE);
                    
                    // devuelve un objeto de la clase mysql_stmt, que sirve para hacer la consulta preparada
                    $consulta = $miDB->stmt_init();
                    $consulta->prepare("select T02_CodDepartamento from T02_Departamento where T02_CodDepartamento=?");
                    $consulta->bind_Param('s',$_REQUEST['codigo']);
                    $consulta->execute();
                    
                    // Comprobamos si existe el codigo en la BBDD
                    $consulta->bind_result($codigo);
                    $registro = $consulta->fetch();
                    if($codigo==strtoupper($_REQUEST['codigo'])){
                        $aErrores['codigo']='El código ya existe un la BBDD';
                        $entradaOK = false;
                    } // si existe guardamos un error para mostrarlo en el formulario
                    
                } catch (mysqli_sql_exception $miExceptionMySQLi) {
                    // temporalmente ponemos estos errores para que se muestren en pantalla
                    $aErrores['codigo']= 'Error: '.$miExceptionMySQLi->getMessage().'con código de error: '.$miExceptionMySQLi->getCode();
                    $entradaOK = false;
                } finally {
                    $miDB->close();
                }
            }
            
        } else {//Código que se ejecuta antes de rellenar el formulario
            $entradaOK = false;
        }

        //Tratamiento del formulario
        if($entradaOK){ //Cargar la variable $aRespuestas y tratamiento de datos OK
            
            // Recuperar los valores del formulario
            $aRespuestas['codigo'] = strtoupper($_REQUEST['codigo']);
            $aRespuestas['descripcion'] = "Departamento de ".$_REQUEST['descripcion'];
            
            try {
                    $miDB = new mysqli($HOSTNAME,USERNAME,PASSWORD,DATABASE);
                    
                    // devuelve un objeto de la clase mysql_stmt, que sirve para hacer la consulta preparada
                    $consulta = $miDB->stmt_init();
                    $consulta->prepare("insert into T02_Departamento values (?,?,now(),0,null)");
                    $consulta->bind_Param('ss',$aRespuestas['codigo'],$aRespuestas['descripcion']);
                    
                    if($consulta->execute()){
                        echo 'Nuevo departamento creado con éxito';
                    } else {
                        echo 'Error al crear el departamento';
                    }
                    
                    
                } catch (mysqli_sql_exception $miExceptionMySQLi) {
                    // temporalmente ponemos estos errores para que se muestren en pantalla
                    $aErrores['codigo']= 'Error: '.$miExceptionMySQLi->getMessage().'con código de error: '.$miExceptionMySQLi->getCode();
                    $entradaOK = false;
                } finally {
                    $miDB->close();
                }

            // Botón para volver a recargar el formulario inicial
            echo '<a href="' . $_SERVER['PHP_SELF'] . '"><button>Volver</button></a>';
            
        } else { //Mostrar el formulario hasta que lo rellenemos correctamente
            //Mostrar formulario
            //Mostrar los datos tecleados correctamente en intentos anteriores
            //Mostrar mensajes de error (si los hay y el formulario no se muestra por primera vez)
            ?>
                <h2>Nuevo departamento</h2>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"> 
                    <label for="codigo">Código:</label>
                    <input type="text" id="codigo" class="obligatorio" name="codigo" value="<?php echo $_REQUEST['codigo']??'' ?>"><span class="error"><?php echo $aErrores['codigo'] ?></span>
                    <br>
                    <label for="descripcion">Descripción: Departamento de </label>
                    <input type="text" name="descripcion" class="obligatorio" value="<?php echo $_REQUEST['descripcion']??'' ?>"><span class="error"><?php echo $aErrores['descripcion'] ?></span>
                    <br>
                    <input type="submit" value="Crear" name="enviar">
                </form>

            <?php
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
        #telefono, #nombre {
            background-color: lightgoldenrodyellow;
        }
        main{
            width:600px;
            height: 450px;
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
            width: 120px;
            margin-left: 20px;
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
            margin-right: 5px;
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
        input[name="enviar"], button{
            padding: 10px 25px;
            font-size: 1.2rem;
            margin: 20px 120px;
            border-radius: 20px;
            background-color: #4988bbff;
            color: white;
            font-family: 'Times New Roman', Times, serif;
            border: 0px solid #252525ff;
        }
        .error{
            font-family: 'Times New Roman', Times, serif;
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
</html>
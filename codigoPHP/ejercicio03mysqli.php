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
       usar forma de conectar sin archivo externo como ej1
        require_once "../core/231018libreriaValidacion.php"; // importamos nuestra libreria
        // Carga del Archivo de Configuración de la BBDD
        try {
            $aConfig = require '../tmp/configConexion.php';
        } catch (Exception $e) {
            echo 'Error Fatal: No se pudo cargar el archivo de configuración. ' . $e->getMessage();
        }

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
                    $miDB = new mysqli($aConfig['host'],$aConfig['username'],$aConfig['password'],$aConfig['dbname']);
                    
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
                    $miDB = new mysqli($aConfig['host'],$aConfig['username'],$aConfig['password'],$aConfig['dbname']);

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
        main{
            width: 500px;
            height: 300px;
            margin: auto;
            background-color: #eeeeee;
            border: 2px solid lightgray;
            border-radius: 20px;
            margin-top: 20px;
            padding: 10px;
        }
        main h2{
            text-align: center;
            margin: 10px;
        }
        form *{
            margin-top: 10px; 
        }
        label{
            display: inline-block;
            width: 90px;
            margin-left: 20px;
        }
        label[for="descripcion"]{width: 220px;}
        .aviso{font-size: 0.75em;}
        input, label{margin-bottom: 20px;}
        input[type="text"]{width: 150px;}
        .obligatorio {
            background-color: lightgoldenrodyellow;
        }
        #codigo{width: 50px;}
        input[name="enviar"], button{
            padding: 5px 15px;
            margin: 10px 200px;
            border-radius: 20px;
            background-color: rgb(73, 136, 187);
            color: white;
        }
        .error{color: red;}
    </style>
</head>
</html>
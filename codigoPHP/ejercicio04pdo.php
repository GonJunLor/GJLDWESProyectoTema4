<!DOCTYPE html>
<html lang="es">
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
        * @since: 01/11/2025
        * 4. Formulario de búsqueda de departamentos por descripción (por una parte del campo DescDepartamento, si el usuario no pone nada deben aparecer todos los departamentos).
        */

        require_once "../core/231018libreriaValidacion.php"; // importamos nuestra libreria

        // preparación de los datos de conexión para luego usarlos en el PDO
        const DSN = "mysql:host=10.199.8.153; dbname=DBGJLDWESProyectoTema4";
        const USERNAME = 'userGJLDWESProyectoTema4';
        const PASSWORD = '5813Libro-Puro';
        // const PASSWORD = 'paso';

        // uso una variable para que la misma línea de codigo me sirva en casa y en clase al usar server_addr
        $DSN = 'mysql:host='.$_SERVER['SERVER_ADDR'].'; dbname=DBGJLDWESProyectoTema4';
       
        $entradaOK = true; //Variable que nos indica que todo va bien
        $aErrores = [  //Array donde recogemos los mensajes de error
            'descripcion' => ''
        ];
        $aRespuestas=[ //Array donde recogeremos la respuestas correctas (si $entradaOK)
            'descripcion' => ''
        ]; 
        
        //Para cada campo del formulario: Validar entrada y actuar en consecuencia
        if (isset($_REQUEST["enviar"])) {//Código que se ejecuta cuando se envía el formulario

            // Solo queremos validar se introduce algo, sino mostraremos despues todos los registros
            if (!empty($_REQUEST['descripcion'])) {
                // Validamos los datos del formulario
                $aErrores['descripcion']= validacionFormularios::comprobarAlfabetico($_REQUEST['descripcion'],255,0,1);
                
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
            $aRespuestas['descripcion'] = $_REQUEST['descripcion'] ?? ''; // Usamos el operador ?? para asegurar un valor si no existe
            
            try {
                $miDB = new PDO($DSN,USERNAME,PASSWORD);

                // Preparamos el término de búsqueda con comodines y en minúsculas para la búsqueda LIKE.
                // Si la descripción está vacía, el término será '%%', devolviendo todos los resultados.
                $terminoBusqueda = '%'.strtolower($aRespuestas['descripcion']).'%';
                
                // Usamos LOWER() en el campo de la DB y en el término de búsqueda para garantizar que la búsqueda sea insensible a mayúsculas/minúsculas.
                $consulta = $miDB->prepare('SELECT T02_DescDepartamento FROM T02_Departamento WHERE LOWER(T02_DescDepartamento) LIKE ?');
                $consulta->bindParam(1, $terminoBusqueda);
                $consulta->execute();
                
                $aDepartamentos = $consulta->fetchAll();
                
                echo '<h3>Resultados de la búsqueda: "'.($aRespuestas['descripcion'] ? ($aRespuestas['descripcion']) : 'Todos').'"</h3>';
                
                if (count($aDepartamentos) > 0) {
                    foreach ($aDepartamentos as $departamento) {
                        echo '<P> --> '.$departamento[0].'</P>';
                    }
                } else {
                    echo '<p>No se encontraron departamentos que coincidan con su búsqueda.</p>';
                }
            } catch (PDOException $miExceptionPDO) {
                // temporalmente ponemos estos errores para que se muestren en pantalla
                $aErrores['codigo']= 'Error: '.$miExceptionPDO->getMessage().'con código de error: '.$miExceptionPDO->getCode();
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
            ?>
                <h2>Buscar departamento</h2>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <label for="descripcion">Introduce Departamento a Buscar: </label>
                    <input type="text" name="descripcion" class="obligatorio" value="<?php echo $_REQUEST['descripcion']??'' ?>"><span class="error"><?php echo $aErrores['descripcion'] ?></span>
                    <br>
                    <input type="submit" value="Buscar" name="enviar">
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
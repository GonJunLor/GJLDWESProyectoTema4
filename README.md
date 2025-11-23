<h1>EJERCICIOS TEMA 4</h1>

<h2>Datos previos</h2>
Para poder hacer los ejercicios hay que configurar los archivos de la carpeta scriptBD para la creacion de la BBDD y los de la carpeta conf para la conexión a la BBDD desde cada ejercicio, estos archivos son propios de cada entorno de desarrollo, están ignorados de git y no se suben a github. 

<h2>Lista de ejercicios</h2>

1. Conexión a la base de datos con la cuenta usuario y tratamiento de errores. Utilizar excepciones automáticas siempre que sea posible en todos los ejercicios.

1. Mostrar el contenido de la tabla Departamento y el número de registros.

2. Formulario para añadir un departamento a la tabla Departamento con validación de entrada y control de errores.

3. Formulario de búsqueda de departamentos por descripción (por una parte del campo DescDepartamento, si el usuario no pone nada deben aparecer todos los departamentos).

4. Pagina web que añade tres registros a nuestra tabla Departamento utilizando tres instrucciones insert y una transacción, de tal forma que se añadan los tres registros o no se añada ninguno.

5. Pagina web que cargue registros en la tabla Departamento desde un array departamentosnuevos utilizando una consulta preparada. (Después de programar y entender este ejercicio, modificar los ejercicios anteriores para que utilicen consultas preparadas). Probar consultas preparadas sin bind, pasando los parámetros en un array a execute.

6. Página web que toma datos (código y descripción) de un fichero xml y los añade a la tabla Departamento de nuestra base de datos. (IMPORTAR). El fichero importado se encuentra en el directorio .../tmp/ del servidor.

7. Página web que toma datos (código y descripción) de la tabla Departamento y guarda en un fichero departamento.xml. (COPIA DE SEGURIDAD / EXPORTAR). El fichero exportado se encuentra en el directorio .../tmp/ del servidor.

   - Si el alumno dispone de tiempo probar distintos formatos de importación - exportación: XML, JSON, CSV, TXT,...

   - Si el alumno dispone de tiempo probar a exportar e importar a o desde un directorio (a elegir) en el equipo cliente.

   - Si el alumno dispone de tiempo probar importación parcial con log de errores.
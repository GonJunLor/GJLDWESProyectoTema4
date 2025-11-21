/**
 * Author:  gonzalo.junlor
 * Created: 21 nov. 2025
 * Script de creación de tabla.
 */

create table if not exists DBGJLDWESProyectoTema4.T02_Departamento(
    T02_CodDepartamento varchar(3) primary key,
    T02_DescDepartamento varchar(255),
    T02_FechaCreacionDepartamento datetime not null,
    T02_VolumenDeNegocio float null,
    T02_FechaBajaDepartamento datetime null
)engine=innodb;

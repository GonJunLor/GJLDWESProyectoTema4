/**
 * Author:  gonzalo.junlor
 * Created: 30 oct. 2025
 * Script de borrado de base de datos
 */


drop database if exists DBGJLDWESProyectoTema4;

drop user if exists 'userGJLDWESProyectoTema4'@'%';

create database if not exists DBGJLDWESProyectoTema4;

create table if not exists DBGJLDWESProyectoTema4.T02_Departamento(
    T02_CodDepartamento varchar(3) primary key,
    T02_DescDepartamento varchar(255),
    T02_FechaCreacionDepartamento datetime not null,
    T02_VolumenDeNegocio float null,
    T02_FechaBajaDepartamento datetime null
)engine=innodb;

create user if not exists 'userGJLDWESProyectoTema4'@'%' identified by '5813Libro-Puro';
-- create user if not exists 'userGJLDWESProyectoTema4'@'%' identified by 'paso';

grant all privileges on *.* to 'userGJLDWESProyectoTema4'@'%' with grant option;

flush privileges;

use DBGJLDWESProyectoTema4;

insert into T02_Departamento (T02_CodDepartamento,T02_DescDepartamento,T02_FechaCreacionDepartamento,T02_VolumenDeNegocio,T02_FechaBajaDepartamento) values
        ('INF','Departamento de informatica.',now(),1235.5,null),
        ('AUT','Departamento de automocion.',now(),5235.8,null),
        ('ELE','Departamento de electricidad.',now(),2275.1,null),
        ('MAT','Departamento de matematicas.',now(),735.2,null),
        ('ING','Departamento de ingles.',now(),235.9,now()
);

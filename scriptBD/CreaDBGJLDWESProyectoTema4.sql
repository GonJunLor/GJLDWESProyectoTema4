/**
 * Author:  gonzalo.junlor
 * Created: 30 oct. 2025
 * Script de creación de tablas y usuarios
 */
create database if not exists DBGJLDWESProyectoTema4;

create table if not exists DBGJLDWESProyectoTema4.T02_Departamento(
    T02_CodDepartamento varchar(3) primary key,
    T02_DescDepartamento varchar(255),
    T02_FechaCreacionDepartamento datetime not null,
    T02_VolumenDeNegocio float null,
    T02_FechaBajaDepartamento datetime null
)engine=innodb;

create user if not exists 'userGJLDWESProyectoTema4'@'%' identified by 'paso';

grant all privileges on *.* to 'userGJLDWESProyectoTema4'@'%' with grant option;
flush privileges;
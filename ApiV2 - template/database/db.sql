DROP DATABASE IF EXISTS cursos_sistema;
CREATE DATABASE IF NOT EXISTS cursos_sistema;
USE cursos_sistema;

CREATE TABLE IF NOT EXISTS tb_persona(
  id_persona INT PRIMARY KEY AUTO_INCREMENT,
  nombre_persona varchar(100) not null,
  telefono_persona varchar(20) not null,
  correo_persona varchar(50) not null unique,
  contrasenia_persona varchar(50) not null,
  rol_persona ENUM('Administrador', 'Profesor', 'Alumno')
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tb_sesion (
  id_sesion INT PRIMARY KEY AUTO_INCREMENT,
  fk_persona int not null,
  hora_ini_sesion datetime DEFAULT CURRENT_TIMESTAMP,
  hora_fin_sesion datetime on update CURRENT_TIMESTAMP,
  origen varchar(100) NOT NULL,
  estado int(11) NOT NULL,

  FOREIGN KEY (fk_persona) REFERENCES tb_persona(id_persona)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tb_carrera (
  id_carrera INT PRIMARY KEY AUTO_INCREMENT,
  nombre_carrera varchar(100) not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tb_curso (
  id_curso INT PRIMARY KEY AUTO_INCREMENT,
  fk_profesor int not null,
  fk_carrera int not null,
  nombre_curso varchar(100) not null,
  descripcion_curso text NOT NULL,

  FOREIGN KEY (fk_profesor) REFERENCES tb_persona(id_persona),
  FOREIGN KEY (fk_carrera) REFERENCES tb_carrera(id_carrera)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tb_inscripcion (
  id_inscripcion INT PRIMARY KEY AUTO_INCREMENT,
  fk_curso int not null,
  fk_alumno int not null,
  validado int(10) not null,

  FOREIGN KEY (fk_curso) REFERENCES tb_curso(id_curso),
  FOREIGN KEY (fk_alumno) REFERENCES tb_persona(id_persona)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `logs` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`uri` VARCHAR(255) NOT NULL,
`method` VARCHAR(6) NOT NULL,
`params` TEXT DEFAULT NULL,
`api_key` VARCHAR(40) NOT NULL,
`ip_address` VARCHAR(45) NOT NULL,
`time` INT(11) NOT NULL,
`rtime` FLOAT DEFAULT NULL,
`authorized` VARCHAR(1) NOT NULL,
`response_code` smallint(3) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
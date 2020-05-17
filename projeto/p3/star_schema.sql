
DROP TABLE IF EXISTS d_Utilizador;
create table d_Utilizador(
	id_Utilizador SERIAL PRIMARY KEY NOT NULL UNIQUE, 
	tipo varchar(100),
	email varchar(100) NOT NULL
);

DROP TABLE IF EXISTS d_tempo;
create table d_tempo(
	id_tempo SERIAL PRIMARY KEY NOT NULL UNIQUE,
	dia int, 
	dia_da_semana int, 
	mes int, 
	trimestre int, 
	ano int NOT NULL
);

DROP TABLE IF EXISTS d_local;
create table d_local(
	id_local SERIAL PRIMARY KEY NOT NULL UNIQUE,
	nome VARCHAR(255),
	latitude DECIMAL(8,6),
	longitude DECIMAL(9,6)
);

DROP TABLE IF EXISTS d_lingua;
create table d_lingua(
	id_lingua SERIAL PRIMARY KEY NOT NULL UNIQUE, 
	lingua VARCHAR(30)
);


DROP TABLE IF EXISTS f_anomalia;
create table f_anomalia( 
	id_Utilizador int, 
	id_tempo int, 
	id_local int, 
	id_lingua int, 
	tipo_anomalia varchar(50), 
	com_proposta boolean,
	PRIMARY KEY(id_Utilizador, id_tempo, id_local, id_lingua),
	FOREIGN KEY(id_Utilizador) REFERENCES d_Utilizador(id_Utilizador),
	FOREIGN KEY(id_tempo) REFERENCES d_tempo(id_tempo),
	FOREIGN KEY(id_local) REFERENCES d_local(id_local),
	FOREIGN KEY(id_lingua) REFERENCES d_lingua(id_lingua)
);

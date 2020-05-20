DROP TABLE IF EXISTS d_Utilizador;
create table d_Utilizador(
	id_Utilizador SERIAL PRIMARY KEY UNIQUE NOT NULL,
	tipo varchar(30) NOT NULL,
	email varchar(60) NOT NULL
);

DROP TABLE IF EXISTS d_tempo;
create table d_tempo(
	id_tempo SERIAL PRIMARY KEY UNIQUE,
	dia int NOT NULL, 
	dia_da_semana int NOT NULL, 
	mes int NOT NULL, 
	trimestre int NOT NULL, 
	ano int NOT NULL
);

DROP TABLE IF EXISTS d_local;
create table d_local(
	id_local SERIAL PRIMARY KEY UNIQUE NOT NULL,
	nome VARCHAR(50) NOT NULL,
	latitude DECIMAL(8,6) NOT NULL,
	longitude DECIMAL(9,6) NOT NULL
);

DROP TABLE IF EXISTS d_lingua;
create table d_lingua(
	id_lingua SERIAL PRIMARY KEY UNIQUE NOT NULL, 
	lingua VARCHAR(30)
);


DROP TABLE IF EXISTS f_anomalia;
create table f_anomalia( 
	id_Utilizador int NOT NULL, 
	id_tempo int NOT NULL, 
	id_local int NOT NULL, 
	id_lingua int NOT NULL, 
	tipo_anomalia varchar(50) NOT NULL, 
	com_proposta boolean NOT NULL,
	PRIMARY KEY(id_Utilizador, id_tempo, id_local, id_lingua),
	FOREIGN KEY(id_Utilizador) REFERENCES d_Utilizador(id_Utilizador),
	FOREIGN KEY(id_tempo) REFERENCES d_tempo(id_tempo),
	FOREIGN KEY(id_local) REFERENCES d_local(id_local),
	FOREIGN KEY(id_lingua) REFERENCES d_lingua(id_lingua)
);

--1: https://www.w3schools.com/sql/sql_primarykey.ASP
--2: https://www.w3schools.com/sql/sql_check.asp

create table local_publico(
	latitude DECIMAL(8,6) not null unique,
	longitude DECIMAL(9,6) not null unique,
	nome varchar(255) not null,
	primary key(latitude, longitude),
	constraint latitude check(latitude between -90.000000 and +90.000000),
	constraint longitude check(longitude between -180.000000 and +180.000000)
);

create table item(
	id serial primary key unique,
	descricao varchar(255),
	localizacao varchar(255),
	latitude DECIMAL(8,6),
	longitude DECIMAL(9,6),
	FOREIGN KEY (latitude) REFERENCES local_publico(latitude),
	FOREIGN KEY (longitude) REFERENCES local_publico(longitude)
);

--https://www.journaldev.com/16774/sql-data-types
create table anomalia(
	id serial primary key not null unique,
	zona varchar(20) not null,	--VERIFICAR ISTO
	imagem bytea not null,
	lingua varchar(30) not null,
	ts TIMESTAMP default CURRENT_TIMESTAMP not null,
	descricao varchar(500) not null,
	tem_anomaila_redacao boolean,
);

create table anomalia_traducao(
	id int not null,
	zona2 varchar(20) not null,	--VERIFICAR ISTO
	lingua2 varchar(30) not null,
	FOREIGN KEY (id) REFERENCES anomalia(id)
);

create table duplicado(
	item1 int not null,
	item2 int not null,
	PRIMARY KEY(item1,item2),
    FOREIGN KEY (item1) REFERENCES item(id),
	FOREIGN KEY (item2) REFERENCES item(id),
	constraint ordem check(item1 < item2)
);

create table Utilizador(
	email varchar(255) unique not null,
	password varchar(64) NOT NULL,
	primary key(email),						   			    --1
	constraint validate_email check(email like '%_@_%._%') 	--2
);

create table Utilizador_Qualificado(
	email varchar(255) primary key not null,
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

create table Utilizador_Regular(
	email varchar(255) primary key not null,
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

create table incidencia(
	anomalia_id serial not null primary key unique,
	item_id int not null,
	email varchar(255) not null,
	FOREIGN KEY (anomalia_id) REFERENCES Anomalia(id),
	FOREIGN KEY (item_id) REFERENCES Item(id),
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

create table proposta_de_correcao(
	email varchar(255) not null,
	nro serial not null,
	data_hora TIMESTAMP not null,
	texto varchar(500) not null,
	PRIMARY KEY(email, nro),
	constraint nro check(nro>0),
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

create table correcao(
	email varchar(255) not null,
	nro int not null,
	anomalia_id int not null,
    --unique(nro,email),
	PRIMARY KEY(email,nro,anomalia_id),
	FOREIGN KEY (email) REFERENCES proposta_de_correcao(email),
	FOREIGN KEY (nro) REFERENCES proposta_de_correcao(nro),
	FOREIGN KEY (anomalia_id) REFERENCES incidencia(anomalia_id)
);


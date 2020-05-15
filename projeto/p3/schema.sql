CREATE TABLE local_publico(
	latitude DECIMAL(8,6) NOT NULL UNIQUE,
	longitude DECIMAL(9,6) NOT NULL UNIQUE,
	nome VARCHAR(255) NOT NULL,
	PRIMARY KEY(latitude, longitude),
	CONSTRAINT latitude CHECK(latitude between -90.000000 and +90.000000),
	CONSTRAINT longitude CHECK(longitude between -180.000000 and +180.000000)
);

CREATE TABLE item(
	id SERIAL PRIMARY KEY UNIQUE,
	descricao VARCHAR(40),
	localizacao VARCHAR(255),
	latitude DECIMAL(8,6),
	longitude DECIMAL(9,6),
	FOREIGN KEY (latitude) REFERENCES local_publico(latitude),
	FOREIGN KEY (longitude) REFERENCES local_publico(longitude)
);

--https://www.journaldev.com/16774/sql-data-types
CREATE TABLE anomalia(
	id SERIAL PRIMARY KEY NOT NULL UNIQUE,
	zona VARCHAR(21) NOT NULL,	--(0000,0000,0000,0000)
	imagem VARCHAR(100) NOT NULL,
	lingua VARCHAR(30) NOT NULL,
	ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	descricao VARCHAR(500) NOT NULL,
	tem_anomalia_traducao BOOLEAN NOT NULL
);

CREATE TABLE anomalia_traducao(
	id int NOT NULL,
	zona2 VARCHAR(21) NOT NULL,
	lingua2 VARCHAR(30) NOT NULL,
	FOREIGN KEY (id) REFERENCES anomalia(id)
);

CREATE TABLE duplicado(
	item1 int NOT NULL,
	item2 int NOT NULL,
	PRIMARY KEY(item1,item2),
    FOREIGN KEY (item1) REFERENCES item(id),
	FOREIGN KEY (item2) REFERENCES item(id),
	CONSTRAINT ordem check(item1 < item2)
);

CREATE TABLE Utilizador(
	email VARCHAR(100) UNIQUE NOT NULL,
	password VARCHAR(64) NOT NULL,
	PRIMARY KEY(email),						   			    --https://www.w3schools.com/sql/sql_primarykey.ASP
	CONSTRAINT validate_email check(email like '%_@_%._%')  --https://www.w3schools.com/sql/sql_check.asp
);

CREATE TABLE Utilizador_Qualificado(
	email VARCHAR(100) PRIMARY KEY NOT NULL,
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

CREATE TABLE Utilizador_Regular(
	email VARCHAR(100) PRIMARY KEY NOT NULL,
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

CREATE TABLE incidencia(
	anomalia_id SERIAL NOT NULL PRIMARY KEY UNIQUE,
	item_id int NOT NULL,
	email VARCHAR(100) NOT NULL,
	FOREIGN KEY (anomalia_id) REFERENCES Anomalia(id),
	FOREIGN KEY (item_id) REFERENCES Item(id),
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

CREATE TABLE proposta_de_correcao(
	email VARCHAR(100) NOT NULL,
	nro SERIAL NOT NULL,
	data_hora TIMESTAMP NOT NULL DEFAULT current_timestamp,
	texto VARCHAR(500) NOT NULL,
	PRIMARY KEY(email, nro),
	CONSTRAINT nro check(nro>0),
	FOREIGN KEY (email) REFERENCES Utilizador_Qualificado(email)
);

CREATE TABLE correcao(
	email VARCHAR(100) NOT NULL,
	nro int NOT NULL,
	anomalia_id int NOT NULL UNIQUE,
    --UNIQUE(nro,email),
	UNIQUE(anomalia_id),
	PRIMARY KEY (email,nro,anomalia_id),
	FOREIGN KEY (email,nro) REFERENCES proposta_de_correcao(email,nro),
    FOREIGN KEY (anomalia_id) REFERENCES incidencia(anomalia_id)
);

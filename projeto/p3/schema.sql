--1: https://www.w3schools.com/sql/sql_primarykey.ASP
--2: https://www.w3schools.com/sql/sql_check.asp
create table Utilizador(
	email varchar(255),
	password varchar(64) NOT NULL,
	primary key(email),						    			--1
	constraint validate_email check(email like '%_@_%._%') 	--2
);


--falta disjunção
--https://stackoverflow.com/questions/26091990/how-to-implement-total-disjoint-specialization-in-database
create table Utilizador_Qualificado(
	email varchar(255),
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

create table Utilizador_Regular(
	email varchar(255),
	FOREIGN KEY (email) REFERENCES Utilizador(email)
);

--falta a cena dos duplicados
create table item(
	id serial primary key,
	descricao varchar(255),
	localizacao varchar(255),
	constraint localizacao_valida check(localizacao like '(%.%,%.%)')
);



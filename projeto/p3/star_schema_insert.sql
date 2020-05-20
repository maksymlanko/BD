

insert into d_utilizador(email, tipo)
select email, 'qualificado'
from Utilizador_Qualificado
union
select email, 'regular'
from Utilizador_Regular;


CREATE OR REPLACE FUNCTION load_d_tempo()
	RETURNS VOID AS
	$$
	DECLARE date_value TIMESTAMP;
	BEGIN
		date_value := '2019-01-01 00:00:00';
		WHILE date_value < '2021-01-01 00:00:00' LOOP
	INSERT INTO d_tempo(
		id_tempo,
		dia,
		dia_da_semana,
		mes,
		trimestre,
		ano
		) VALUES (
			EXTRACT(YEAR FROM date_value) * 10000
				+ EXTRACT(MONTH FROM date_value)*100
				+ EXTRACT(DAY FROM date_value),
			CAST(EXTRACT(DAY FROM date_value) AS INTEGER),
			CAST(EXTRACT(DOW FROM date_value) AS INTEGER),
			CAST(EXTRACT(MONTH FROM date_value) AS INTEGER),
			CAST(EXTRACT(QUARTER FROM date_value) AS INTEGER),
			EXTRACT(YEAR FROM date_value)
		);
	date_value := date_value + INTERVAL '1 DAY';
	END LOOP;
	END;
$$ LANGUAGE plpgsql;

select load_d_tempo();


insert into d_lingua(lingua)
select lingua from anomalia union select lingua2 from anomalia_traducao;


insert into d_local(latitude, longitude, nome)
select latitude, longitude, nome from local_publico;


insert into f_anomalia(id_utilizador,id_tempo, id_local,id_lingua, tipo_anomalia, com_proposta)
select id_utilizador, 
				EXTRACT(YEAR FROM T.ts) * 10000
				+ EXTRACT(MONTH FROM T.ts)*100
				+ EXTRACT(DAY FROM T.ts), id_local, id_lingua, 'traducao', false
from (select anomalia.id as anomalia_id, ts, lingua, lingua2, email_incidencia, email_utilizador,latitude, longitude
		from anomalia 
		inner join anomalia_traducao 
			on anomalia.id = anomalia_traducao.id
		inner join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo 
			on anomalia.id = foo.anomalia_id
		inner join (select email as email_utilizador from utilizador) AS fee 
			on foo.email_incidencia = fee.email_utilizador
		inner join (select id, latitude, longitude from item) AS faa 
			on foo.item_id = faa.id) as T
	left outer join d_lingua d1
		on d1.lingua = T.lingua
		or d1.lingua = T.lingua2
	left outer join d_utilizador d2
		on d2.email = T.email_incidencia
	left outer join d_local d3
		on d3.latitude = T.latitude
		and d3.longitude = T.longitude
where T.anomalia_id not in (select anomalia_id from correcao);

insert into f_anomalia(id_utilizador,id_tempo, id_local,id_lingua, tipo_anomalia, com_proposta)
select id_utilizador, 
				EXTRACT(YEAR FROM T.ts) * 10000
				+ EXTRACT(MONTH FROM T.ts)*100
				+ EXTRACT(DAY FROM T.ts), id_local, id_lingua, 'redacao', false
from (select anomalia.id as anomalia_id, ts, lingua, email_incidencia, email_utilizador,latitude, longitude, tem_anomalia_traducao
		from anomalia 
		inner join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo 
			on anomalia.id = foo.anomalia_id
		inner join (select email as email_utilizador from utilizador) AS fee 
			on foo.email_incidencia = fee.email_utilizador
		inner join (select id, latitude, longitude from item) AS faa 
			on foo.item_id = faa.id) as T
	left outer join d_lingua d1
		on d1.lingua = T.lingua
	left outer join d_utilizador d2
		on d2.email = T.email_incidencia
	left outer join d_local d3
		on d3.latitude = T.latitude
		and d3.longitude = T.longitude
where tem_anomalia_traducao = false and T.anomalia_id not in (select anomalia_id from correcao);

insert into f_anomalia(id_utilizador,id_tempo, id_local,id_lingua, tipo_anomalia, com_proposta)
select id_utilizador, 
				EXTRACT(YEAR FROM T.ts) * 10000
				+ EXTRACT(MONTH FROM T.ts)*100
				+ EXTRACT(DAY FROM T.ts), id_local, id_lingua, 'redacao', true
from (select ts, lingua, email_incidencia, email_utilizador,latitude, longitude, tem_anomalia_traducao
		from anomalia 
		inner join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo 
			on anomalia.id = foo.anomalia_id
		inner join (select email as email_utilizador from utilizador) AS fee 
			on foo.email_incidencia = fee.email_utilizador
		inner join (select anomalia_id as id_anomalia_correcao from correcao) AS fii 
			on anomalia.id = fii.id_anomalia_correcao
		inner join (select id, latitude, longitude from item) AS faa 
			on foo.item_id = faa.id) as T
	left outer join d_lingua d1
		on d1.lingua = T.lingua
	left outer join d_utilizador d2
		on d2.email = T.email_incidencia
	left outer join d_local d3
		on d3.latitude = T.latitude
		and d3.longitude = T.longitude
where tem_anomalia_traducao = false;

insert into f_anomalia(id_utilizador,id_tempo, id_local,id_lingua, tipo_anomalia, com_proposta)
select id_utilizador, 
				EXTRACT(YEAR FROM T.ts) * 10000
				+ EXTRACT(MONTH FROM T.ts)*100
				+ EXTRACT(DAY FROM T.ts), id_local, id_lingua, 'traducao', true
from (select ts, lingua, lingua2, email_incidencia, email_utilizador,latitude, longitude
		from anomalia 
		inner join anomalia_traducao 
			on anomalia.id = anomalia_traducao.id
		inner join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo 
			on anomalia.id = foo.anomalia_id
		inner join (select email as email_utilizador from utilizador) AS fee 
			on foo.email_incidencia = fee.email_utilizador
		inner join (select anomalia_id as id_anomalia_correcao from correcao) AS fii 
			on anomalia.id = fii.id_anomalia_correcao	
		inner join (select id, latitude, longitude from item) AS faa 
			on foo.item_id = faa.id) as T
	left outer join d_lingua d1
		on d1.lingua = T.lingua
		or d1.lingua = T.lingua2
	left outer join d_utilizador d2
		on d2.email = T.email_incidencia
	left outer join d_local d3
		on d3.latitude = T.latitude
		and d3.longitude = T.longitude;




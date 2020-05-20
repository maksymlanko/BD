

insert into d_utilizador(email)
select email
from Utilizador_Qualificado;

update d_utilizador
set tipo = 'qualificado'
where tipo is null;



insert into d_utilizador(email)
select email
from Utilizador_Regular;

update d_utilizador
set tipo = 'regular'
where tipo is null;



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


insert into f_anomalia(id_tempo)
select id_tempo
from d_tempo;

insert into f_anomalia(id_utilizador,id_local,id_lingua)
select id_utilizador, id_local, id_lingua
from (select ts as data_anomalia, lingua, lingua2, email_incidencia, email_utilizador, email_proposta, latitude, longitude
from anomalia full outer join anomalia_traducao on anomalia.id = anomalia_traducao.id
full outer join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo on anomalia.id = foo.anomalia_id
full outer join (select email as email_utilizador from utilizador) AS fee on foo.email_incidencia = fee.email_utilizador
full outer join (select email as email_proposta from proposta_de_correcao) AS fii on fee.email_utilizador = fii.email_proposta
full outer join (select id, latitude, longitude from item) AS faa on foo.item_id = faa.id) as T
	left outer join d_utilizador d1
		on d1.email = T.email_utilizador
	left outer join d_local d2
		on d2.latitude = T.latitude
		and d2.longitude = T.longitude
	left outer join d_lingua d3
		on d3.lingua = T.lingua
		or d3.lingua = T.lingua2;



UPDATE f_anomalia
SET com_proposta = true
WHERE id_utilizador in (select id_utilizador from d_utilizador inner join (select ts as data_anomalia, lingua, lingua2, email_incidencia, email_utilizador, email_proposta, latitude, longitude
from anomalia full outer join anomalia_traducao on anomalia.id = anomalia_traducao.id
full outer join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo on anomalia.id = foo.anomalia_id
full outer join (select email as email_utilizador from utilizador) AS fee on foo.email_incidencia = fee.email_utilizador
full outer join (select email as email_proposta from proposta_de_correcao) AS fii on fee.email_utilizador = fii.email_proposta
full outer join (select id, latitude, longitude from item) AS faa on foo.item_id = faa.id) as T on d_utilizador.email = T.email_proposta); 

UPDATE f_anomalia
SET com_proposta = true
WHERE id_tempo in (select id_tempo from f_anomalia inner join (select ts as data_anomalia, lingua, lingua2, email_incidencia, email_utilizador, email_proposta, latitude, longitude
from anomalia full outer join anomalia_traducao on anomalia.id = anomalia_traducao.id
full outer join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo on anomalia.id = foo.anomalia_id
full outer join (select email as email_utilizador from utilizador) AS fee on foo.email_incidencia = fee.email_utilizador
full outer join (select email as email_proposta from proposta_de_correcao) AS fii on fee.email_utilizador = fii.email_proposta
full outer join (select id, latitude, longitude from item) AS faa on foo.item_id = faa.id) as T on f_anomalia.id_tempo 
= EXTRACT(YEAR FROM T.data_anomalia) * 10000
				+ EXTRACT(MONTH FROM T.data_anomalia)*100
				+ EXTRACT(DAY FROM T.data_anomalia)); 

UPDATE f_anomalia
SET tipo_anomalia = 'traducao'
where id_lingua in (select id_lingua from d_lingua inner join (select ts as data_anomalia, lingua, lingua2, email_incidencia, email_utilizador, email_proposta, latitude, longitude, tem_anomalia_traducao
from anomalia full outer join anomalia_traducao on anomalia.id = anomalia_traducao.id
full outer join (select anomalia_id,item_id, email as email_incidencia from incidencia) AS foo on anomalia.id = foo.anomalia_id
full outer join (select email as email_utilizador from utilizador) AS fee on foo.email_incidencia = fee.email_utilizador
full outer join (select email as email_proposta from proposta_de_correcao) AS fii on fee.email_utilizador = fii.email_proposta
full outer join (select id, latitude, longitude from item) AS faa on foo.item_id = faa.id) as T on d_lingua.lingua = T.lingua2 and T.tem_anomalia_traducao=true);

UPDATE f_anomalia
SET tipo_anomalia = 'redacao'
where id_lingua is not null and tipo_anomalia is null;


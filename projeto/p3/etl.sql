---insert d_utilizador anomalia tipo traducao---

insert into d_utilizador(email)
select email, 
from incidencia i 
	join anomalia a
		on i.anomalia_id = a.id
where a.tem_anomalia_traducao = true;

update d_utilizador
set tipo = 'traducao';

---insert d_utilizador anomalia tipo redacao---

insert into d_utilizador(email)
select email
from incidencia i 
	join anomalia a
		on i.anomalia_id = a.id
where a.tem_anomalia_traducao = false;

update d_utilizador
set tipo = 'redacao'
where tipo is null;

---insert d_tempo---

CREATE OR REPLACE FUNCTION load_date_dim()
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


---insert d_lingua---

insert into d_lingua(lingua)
select lingua from anomalia union select lingua2 from anomalia_traducao;

---insert d_local---
insert into d_local(latitude, longitude, nome)
select latitude, longitude, nome from local_publico;

---insert f_anomalia---

insert into f_anomalia(id_utilizador, id_tempo, id_local, id_lingua)
select id_utilizador, id_tempo, id_local, id_lingua
from d_utilizador
natural join d_tempo
natural join d_local
natural join d_lingua
group by id_Utilizador, id_tempo, id_local, id_lingua;
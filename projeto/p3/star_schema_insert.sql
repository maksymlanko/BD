---insert d_utilizador tipo qualificado---

insert into d_utilizador(email)
select email
from utilizador
intersect
select email
from utilizador_qualificado;

update d_utilizador
set tipo = 'qualificado';

---insert d_utilizador tipo regular---

insert into d_utilizador(email)
select email
from utilizador
intersect
select email
from utilizador_regular;

update d_utilizador
set tipo = 'regular'
where tipo is null;

---insert d_tempo---
insert into d_tempo(ano,dia,dia_da_semana,mes,trimestre)
select extract(YEAR from ts), extract(DAY from ts), extract(DOW from ts), extract(MONTH from ts), extract(QUARTER from ts)
from anomalia;


---insert d_lingua---

insert into d_lingua(lingua)
select lingua from anomalia;

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
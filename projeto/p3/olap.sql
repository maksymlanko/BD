select id_lingua, sum(qty), tipo_anomalia, sum(qty2), dia_da_semana, sum(qty3)
from (select tipo_anomalia, (count(tipo_anomalia)) as qty2, id_lingua, (count(id_lingua)) as qty, dia_da_semana, (count(dia_da_semana)) from d_lingua natural join f_anomalia natural join d_tempo group by id_lingua, tipo_anomalia, dia_da_semana) as foo
group by cube (id_lingua, tipo_anomalia, dia_da_semana);
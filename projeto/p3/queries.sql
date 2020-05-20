--1
SELECT l
FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico l
GROUP BY l
HAVING COUNT(*) <= ALL (
	SELECT COUNT(*) as cnt
    FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico l
    GROUP BY l
);

--2
SELECT l.nome, l.latitude, l.longitude
FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico l
WHERE anom.tem_anomalia_traducao is true
	AND anom.ts BETWEEN '2020-01-01' AND '2020-06-30 23:59:59.999999'
GROUP BY l.latitude, l.longitude
HAVING COUNT(*) >= ALL (
	SELECT COUNT(*)
	FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico l
	WHERE anom.tem_anomalia_traducao is true
	AND anom.ts BETWEEN '2020-01-01' AND '2020-06-30 23:59:59.999999'
	GROUP BY inc.item_id
);

--3
SELECT pdc.email
FROM proposta_de_correcao pdc
NATURAL JOIN correcao c
LEFT JOIN incidencia i ON c.anomalia_id=i.anomalia_id
LEFT JOIN item it on i.item_id=it.id
WHERE it.latitude < 39.336775 and pdc.data_hora BETWEEN '2020-01-01 00:00:00' and '2020-12-31 23:59:59.999999'
GROUP BY pdc.email;

--4



--Local publico com menos anomalias registadas
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

--Locais publicos com mais anomalias no primeiro semestre de 2020
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

--Utilizadores que registaram propostas de correcao a sul de rio maior em 2020
SELECT pdc.email
FROM proposta_de_correcao pdc
    NATURAL JOIN correcao c
    LEFT JOIN incidencia i ON c.anomalia_id=i.anomalia_id
    LEFT JOIN item it on i.item_id=it.id
WHERE it.latitude < 39.336775 
    AND pdc.data_hora BETWEEN '2020-01-01 00:00:00' AND '2020-12-31 23:59:59.999999'
GROUP BY pdc.email;

--Utilizadores que submeteram propostas de correção para todas as suas incidencias em 2020

--todos os que registaram pelo menos uma igual
SELECT pdc.email
FROM proposta_de_correcao pdc
    NATURAL JOIN correcao c
    LEFT JOIN incidencia i ON c.anomalia_id=i.anomalia_id
    LEFT JOIN utilizador u ON i.email=u.email
    LEFT JOIN anomalia a ON a.id=i.anomalia_id
WHERE ts BETWEEN '2020-01-01' AND '2020-12-31 23:59:59.999999'
    AND pdc.email = u.email
GROUP BY pdc.email

EXCEPT
--todos os que não registaram alguma igual
SELECT pdc.email
FROM proposta_de_correcao pdc
    NATURAL JOIN correcao c
    LEFT JOIN incidencia i ON c.anomalia_id=i.anomalia_id
    LEFT JOIN utilizador u ON i.email=u.email
    LEFT JOIN anomalia a ON a.id=i.anomalia_id
WHERE ts BETWEEN '2020-01-01' AND '2020-12-31 23:59:59.999999'
    AND pdc.email <> u.email
GROUP BY pdc.email;

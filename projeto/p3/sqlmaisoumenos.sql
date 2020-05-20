/*SELECT local_publico.nome
FROM local_publico NATURAL JOIN 
	(SELECT count(*)
	FROM incidencia NATURAL JOIN
		item NATURAL JOIN
		local_publico
	GROUP BY item.id
	HAVING COUNT(anomalia_id) <= ALL(
		SELECT COUNT(anomalia_id)
		FROM incidencia
		GROUP BY item_id)
	  ) sub
*/
	
SELECT local_publico, count(*)
FROM incidencia 
	NATURAL JOIN item 		-- tirar item?
	NATURAL JOIN local_publico
GROUP BY local_publico
HAVING count(*) <= ALL (
	SELECT COUNT(*)
	FROM incidencia
		NATURAL JOIN item
		NATURAL JOIN local_publico
	GROUP BY local_publico
	);

SELECT local_publico, count(anomalia_id)
FROM incidencia
	NATURAL JOIN item 		-- tirar item?
	NATURAL JOIN local_publico 			-- join ON? item_id.latitude,longitude = local_publico ?
GROUP BY local_publico;
/*
HAVING count(*) <= ALL (
	SELECT COUNT(*)
	FROM incidencia
		NATURAL JOIN item
		NATURAL JOIN local_publico
	GROUP BY local_publico
	);
*/

-- 2.1: 

SELECT l
FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico l
GROUP BY l
HAVING COUNT(*) <= ALL (
	SELECT COUNT(*)
	FROM incidencia
	GROUP BY incidencia.item_id
	);

-- nao sei ta mal o de cima pq isto?
/*
SELECT local_publico, count(*)
FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico
GROUP BY local_publico;
*/
/*
SELECT incidencia.item_id iid, COUNT(*)
FROM incidencia
GROUP BY incidencia.item_id
*/

-- 2.2:

SELECT l
FROM incidencia inc
	JOIN anomalia anom ON inc.anomalia_id = anom.id
	JOIN item it ON inc.item_id = it.id
	NATURAL JOIN local_publico l
WHERE anom.tem_anomalia_traducao is true
	AND anom.ts BETWEEN '2020-01-01' AND '2020-07-01'
GROUP BY l
HAVING COUNT(*) >= ALL (
	SELECT COUNT(*)
	FROM incidencia
	GROUP BY incidencia.item_id
	);

-- 2.3:
-- tentar com DIVIDE

SELECT utilizador
FROM proposta_de_correcao
	NATURAL JOIN utilizador
	NATURAL JOIN incidencia inc
	JOIN item ON inc.item_id = item.id
	NATURAL JOIN local_publico
WHERE local_publico.latitude < (
	SELECT latitude
	FROM local_publico
	WHERE nome = 'Rio Maior'
	)
	AND data_hora >= '2020-01-01'
GROUP by utilizador
HAVING COUNT(*) < (
	SELECT count(*)
	FROM local_publico
	WHERE latitude < (
		SELECT latitude
		FROM local_publico
		WHERE nome = 'Rio Maior'
		)
	)


/*
SELECT local_publico, count(*)
FROM incidencia 
	
	NATURAL JOIN local_publico
	NATURAL JOIN anomalia
	NATURAL JOIN anomalia_traducao
WHERE anomalia.tem_anomalia_traducao is true 		-- falta mudar para isto depois .tem_anomalia_redacao
	AND anomalia.ts BETWEEN '2020-01-01' AND '2020-07-01' -- fechado aberto [data[
GROUP BY local_publico
HAVING count(*) >= ALL (
	SELECT COUNT(*)
	FROM incidencia
		NATURAL JOIN item
		NATURAL JOIN local_publico
	GROUP BY local_publico
	);
*/

/*
SELECT utilizador
FROM proposta_de_correcao
	NATURAL JOIN incidencia
	NATURAL JOIN item
	NATURAL JOIN utilizador
WHERE proposta_de_correcao.data_hora >= '2020'
	AND item.latitude < ( 		-- confirmar se latitude ou longitude
		SELECT latitude
		FROM local_publico
		WHERE nome = 'Rio Maior'
	)
GROUP BY utilizador
HAVING COUNT(*) = (
	SELECT COUNT(*)
	FROM local_publico
	WHERE latitude < ( 		-- confirmar se latitude ou longitude
		SELECT latitude
		FROM local_publico
		WHERE nome = 'Rio Maior'
	)
	)

*/


	
	

/*
SELECT local_publico.nome
FROM local_publico NATURAL JOIN (
	SELECT COUNT(*) as ct
	FROM incidencia
	NATURAL JOIN item
	NATURAL JOIN local_publico
	GROUP BY item.id ) as sub
	/*
	HAVING COUNT(*) <= ALL(
		SELECT COUNT(*)
		FROM incidencia
		GROUP BY item_id)
	) AS SUB
	
	NATURAL JOIN item
	NATURAL JOIN local_publico;
	*/
	
*/

/*
SELECT item_id, anomalia_id, ct
FROM incidencia NATURAL JOIN (
	SELECT count(*) ct
	FROM incidencia
	GROUP BY incidencia
	) SUB
*/

DROP TRIGGER check_zona
DROP TRIGGER check_utilizador_r
DROP TRIGGER check_utilizador_q
DROP TRIGGER check_utilizador

create or replace function insert_proposta(mail varchar(60), fix varchar(500))
	returns int as 
$$
	declare next_nro int;
begin
 	select max_nro+1 into next_nro
	from ( 
		select max(nro) as max_nro, email
		from proposta_de_correcao
		group by email
	) t
	where email = mail;
	
    if next_nro is null then next_nro := 1; end if;

    INSERT INTO proposta_de_correcao (email,nro, data_hora, texto)
	VALUES (mail, next_nro, now(), fix);

    return next_nro;
end;
$$ language plpgsql;

create or replace function check_zona()
	returns TRIGGER as 
$$
	declare zona1 varchar(21);
	declare zona1x1 int;
	declare zona1y1 int;
	declare zona1x2 int;
	declare zona1y2 int;
	declare zona2 varchar(21);
	declare zona2x1 int;
	declare zona2y1 int;
	declare zona2x2 int;
	declare zona2y2 int;
begin
 	-- a zona da anomalia traducao nao se pode sobrepor a zona da anomalia corresponde
 	select zona into zona1
	from ( 
		select zona
		from anomalia
		where anomalia.id = NEW.id
	) z;
	
	SELECT split_part(zona1::TEXT, ')', 1)::TEXT into zona1;
	
	SELECT split_part(zona1::TEXT, '(', 2) into zona1;
	
	
	SELECT split_part(zona1, ',', 1) into zona1x1;
	SELECT split_part(zona1, ',', 2) into zona1y1;
	SELECT split_part(zona1, ',', 3) into zona1x2;
	SELECT split_part(zona1, ',', 4) into zona1y2;
	
	SELECT NEW.zona2 into zona2;
	SELECT split_part(zona2::TEXT, ')', 1)::TEXT into zona2;
	SELECT split_part(zona2::TEXT, '(', 2)::TEXT into zona2;
	SELECT split_part(zona2, ',', 1) into zona2x1;
	SELECT split_part(zona2, ',', 2) into zona2y1;
	SELECT split_part(zona2, ',', 3) into zona2x2;
	SELECT split_part(zona2, ',', 4) into zona2y2;


	if (not((zona2x1 BETWEEN zona1x1 and zona1x2 AND zona2y1 BETWEEN zona1y1 and zona1y2)
		OR((zona2x2 BETWEEN zona1x1 and zona1x2 AND zona2y2 BETWEEN zona1y1 and zona1y2))) 
		)THEN
			return NULL;
	end if;
	return NEW;
end;
$$ language plpgsql;


create or replace function check_utilizador()
	returns TRIGGER as 
$$
	
begin
	if exists(
		select email
		from ( 
			select email
			from utilizador_qualificado
			where utilizador_qualificado.email = NEW.email
			) u_q
		UNION (
		select email
		from(
			select email
			from utilizador_regular
			where utilizador_regular.email = NEW.email
			) u_r
		)
		)THEN
		return NULL;
	end if;

    return NEW;
end;
$$ language plpgsql;

create or replace function check_utilizador_e()
	returns TRIGGER as 
$$
	
begin
	if exists(
		select email
		from ( 
			select email
			from utilizador_qualificado
			where utilizador_qualificado.email = NEW.email
			) u_q
		UNION (
		select email
		from(
			select email
			from utilizador_regular
			where utilizador_regular.email = NEW.email
			) u_r
		)
		)THEN
		return NEW;
	end if;

    return NULL;
end;
$$ language plpgsql;


CREATE TRIGGER check_zona
BEFORE INSERT ON anomalia_traducao
FOR EACH ROW EXECUTE PROCEDURE check_zona();

CREATE TRIGGER check_utilizador_r
BEFORE INSERT ON utilizador_regular
FOR EACH ROW EXECUTE PROCEDURE check_utilizador();

CREATE TRIGGER check_utilizador_q
BEFORE INSERT ON utilizador_qualificado
FOR EACH ROW EXECUTE PROCEDURE check_utilizador();

--CREATE TRIGGER check_utilizador
--BEFORE INSERT ON utilizador
--FOR EACH ROW EXECUTE PROCEDURE check_utilizador_e();

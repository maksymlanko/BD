create or replace function insert_proposta(mail varchar(100), fix varchar(500))
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
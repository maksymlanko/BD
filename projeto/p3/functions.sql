create or replace function insert_proposta(mail varchar(100), fix varchar(500))
	returns int as 
$$
	declare next_nro int;
 begin
    select max(nro) into next_nro
    from proposta_de_correcao group by email
	WHERE email=mail;
	
    if next_nro is null then next_nro := 1; end if;

    UPDATE proposta_de_correcao
	SET data_hora=now(), texto=fix
	WHERE email=mail and nro=next_nro;

    return next_nro;
end;
$$ language plpgsql;
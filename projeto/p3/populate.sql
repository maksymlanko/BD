INSERT INTO utilizador (email, password) VALUES('samuel.barata@ist.utl.pt', 'oçlaisdfhuasdhi');
INSERT INTO utilizador_regular (email) VALUES('samuel.barata@ist.utl.pt');
INSERT INTO utilizador (email, password) VALUES('test@ist.utl.pt', 'password');
INSERT INTO utilizador_qualificado (email) VALUES('test@ist.utl.pt');
INSERT INTO utilizador (email, password) VALUES('Maksym@ist.utl.pt', 'password2');
INSERT INTO utilizador_qualificado (email) VALUES('Maksym@ist.utl.pt');
INSERT INTO utilizador (email, password) VALUES('samuel@ist.utl.pt', 'password');
INSERT INTO utilizador_qualificado (email) VALUES('samuel@ist.utl.pt');
INSERT INTO utilizador (email, password) VALUES('mariaines@ist.utl.pt', 'password3');
INSERT INTO utilizador_qualificado (email) VALUES('mariaines@ist.utl.pt');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('-90.0', '-180.0', 'min');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('90.0', '180.0', 'max');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('37.103834', '-8.136378', 'Vilamoura');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('38.774465', '-9.157023', 'Lisboa');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('39.336775', '-8.936379', 'Rio Maior');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('40.064718', '-8.179064', 'Coentral');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('40.203759', '-8.407733', 'Coimbra');
INSERT INTO local_publico (latitude, longitude, nome) VALUES('41.188929', '-8.495278', 'Valongo');
INSERT INTO item (descricao, localizacao, latitude, longitude) VALUES('parquimetro', 'Rua', '38.774465', '-9.157023');
INSERT INTO item (descricao, localizacao, latitude, longitude) VALUES('Menu', 'Restaurante', '40.203759', '-8.407733');
INSERT INTO anomalia (zona, imagem, lingua, descricao, tem_anomalia_traducao) VALUES('(10,10,50,50)','https://b.zmtcdn.com/data/menus/474/8211474/65beb1bd0d32d8ca65dcd503ca48d62f.jpg', 'PT', 'nº59', 'False');
INSERT INTO anomalia (zona, imagem, lingua, descricao, tem_anomalia_traducao) VALUES('(100,100,120,120)','https://meusroteiros.com/wp-content/uploads/2014/11/Dica_Parquimetro_Foto01a.jpg', 'PT', 'Tarifa', 'True');
INSERT INTO anomalia_traducao(id, zona2, lingua2) VALUES ('2', '(100,120,120,140)', 'EN');
INSERT INTO incidencia (anomalia_id, item_id, email) VALUES('2', '1', 'test@ist.utl.pt');
INSERT INTO proposta_de_correcao (email, data_hora, texto) VALUES ('test@ist.utl.pt','2020-01-04 12:23:05' ,'Proposta antiga');
INSERT INTO proposta_de_correcao (email, data_hora, texto) VALUES ('test@ist.utl.pt','2020-05-12 00:00:00' ,'Proposta recente');



drop table autor;
CREATE TABLE autor(
	id_user			serial not null,	
	nome		varchar(40) not null,
	email		varchar(40) not null,
	senha		varchar(40) not null,
	areaCNPQ	varchar(20) not null,
	revisor	 		boolean,
	organizador		boolean,

	PRIMARY KEY(id_user),
	FOREIGN KEY (areaCNPQ) references areaCNPQ(nome_areaCNPQ)
);

drop table trabalho;
CREATE TABLE trabalho (

	id_trabalho		serial not null,
	id_autor		integer not null,
	
	titulo_resumo 		varchar(60) not null,
	nome_autores		varchar(60) not null,
	
	areacnpq		varchar(60) not null,
	resumo			varchar(500) not null,
	datatrabalho		date not null default CURRENT_DATE ,
	status			integer not null,  
	id_revisor		integer ,
	
	PRIMARY KEY(id_trabalho),
	FOREIGN KEY (id_autor) references autor(id_user),
	FOREIGN KEY (areaCNPQ) references areaCNPQ(nome_areaCNPQ)
);

drop table avaliacao;

CREATE TABLE avaliacao (

	id_avaliacao 	serial not null ,
	id_autor		integer not null,
	id_trabalho		integer not null,
	id_revisor		integer not null,
	titulo_resumo		varchar(60),
	
	notaGeral 		float not null,
	notaOrtografia		float not null,
	notaClareza		float not null,
	notaRelevancia		float not null,
	notaOriginalidade	float not null,

	comentario		varchar(100),
	aceito			boolean,
	recusado		boolean,
	dataAvaliacao		date not null default CURRENT_DATE ,

	PRIMARY KEY(id_avaliacao),
	FOREIGN KEY (id_trabalho) REFERENCES trabalho(id_trabalho),
	FOREIGN KEY (id_revisor) REFERENCES autor(id_user)

);
drop table areaCNPQ;
CREATE TABLE areaCNPQ(
	id_areaCNPQ		serial not null, --cria id automaticamente para uma areaCNPQ
	nome_areaCNPQ		varchar(30) not null, --variavel de nome para a area
	
	PRIMARY KEY(nome_areaCNPQ)
);
drop table datas_limite;
alter table datas_limite add column datalimiteExibir date;

create table datas_limite (
	id					  serial not null,
	datalimiteEnviar		date,
	datalimiteEditar		date,
	datalimiteAvaliar		date,
	datalimiteExibir		date
);

insert into areaCNPQ (nome_areaCNPQ) VALUES('Matematica');
insert into areaCNPQ (nome_areaCNPQ) VALUES('Algebra');
insert into areaCNPQ (nome_areaCNPQ) VALUES('Analise');
insert into areaCNPQ (nome_areaCNPQ) VALUES('Logica Matematica');

SELECT * FROM autor
SELECT MAX(id) FROM datas_limite

SELECT MAX(id),datalimiteEnviar FROM datas_limite group by datas_limite.datalimiteenviar order by MAX(id) desc limit 1;

SELECT MAX(id),datalimiteEditar FROM datas_limite group by datas_limite.datalimiteeditar order by MAX(id) desc limit 1;

SELECT MAX(id),datalimiteAvaliar FROM datas_limite group by datas_limite.datalimiteavaliar order by MAX(id) desc limit 1;
SELECT nome,areacnpq,revisor FROM autor

SELECT areacnpq FROM autor where id_user=1


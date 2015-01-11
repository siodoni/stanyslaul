drop table if exists snb_dicionario_detalhe;
drop table if exists snb_dicionario;

create table if not exists snb_dicionario (
  id              int(11)     not null AUTO_INCREMENT,
  nome_tabela     varchar(60) not null,
  campo_id        varchar(60) not null,
  campo_descricao varchar(60) not null,
  PRIMARY KEY (id)
) ENGINE=InnoDB  default CHARSET=latin1 AUTO_INCREMENT=3;

insert into snb_dicionario
(id,nome_tabela,campo_id,campo_descricao)
values
(1,'SNB_DICIONARIO','ID','NOME_TABELA'),
(2,'SNB_DICIONARIO_DETALHE','ID','NOME_COLUNA');

create table if not exists snb_dicionario_detalhe (
  id                 int(10) not null AUTO_INCREMENT,
  id_dicionario      int(10) not null,
  nome_coluna        varchar(60) not null,
  titulo_coluna      varchar(60) not null,
  ordem              numeric(5,2) not null,
  tipo_dado          enum('SENHA','NUMÉRICO','DATA','ARQUIVO','TEXTO','TEXTO LONGO','LISTA VALOR','ENUM') not null,
  tamanho_campo      int(3) not null default 10,
  qtd_caracteres     int(3) not null default 10,
  precisao_numero    int(1) default null,
  formato_data       varchar(40) default null,
  id_dicionario_lov  int(10) default null,
  valor_enum         longtext default null,
  fg_obrigatorio     enum('SIM','NÃO') not null default 'NÃO',
  fg_auto_incremento enum('SIM','NÃO') not null default 'NÃO',
  hint_campo         longtext,
  PRIMARY KEY (id)
) ENGINE=InnoDB  default CHARSET=latin1 AUTO_INCREMENT=20;

insert into snb_dicionario_detalhe
(id,id_dicionario,nome_coluna,titulo_coluna,tipo_dado,fg_obrigatorio,ordem,precisao_numero,tamanho_campo,qtd_caracteres,valor_enum,id_dicionario_lov,hint_campo,fg_auto_incremento,formato_data)
values
(1,2,'ID','Id','NUMÉRICO','SIM',1,null,5,5,null,null,null,'SIM',''),
(2,2,'ID_DICIONARIO','Id Dicionário','NUMÉRICO','SIM',2,null,5,5,null,1,null,'NÃO',''),
(3,2,'NOME_COLUNA','Nome Coluna','TEXTO','SIM',3,null,30,50,null,null,null,'NÃO',''),
(4,2,'TITULO_COLUNA','Título Coluna','TEXTO','SIM',4,null,30,60,null,null,null,'NÃO',null),
(5,2,'ORDEM','Ordem','NUMÉRICO','SIM',5,2,5,5,null,null,null,'NÃO',null),
(6,2,'TIPO_DADO','Tipo Dado','ENUM','SIM',6,null,10,10,'SENHA,NUMÉRICO,DATA,ARQUIVO,TEXTO,TEXTO LONGO,LISTA VALOR,ENUM',null,null,'NÃO',''),
(7,2,'TAMANHO_CAMPO','Tamanho Campo','NUMÉRICO','SIM',7,0,5,5,null,null,null,'NÃO',null),
(8,2,'QTD_CARACTERES','Qtd Caracteres','NUMÉRICO','SIM',8,0,5,5,null,null,null,'NÃO',null),
(9,2,'PRECISAO_NUMERO','Precisão Número','NUMÉRICO','NÃO',9,0,5,5,null,null,null,'NÃO',null),
(10,2,'FORMATO_DATA','Formato Data','TEXTO','NÃO',10,null,30,60,null,null,null,'NÃO',null),
(11,2,'ID_DICIONARIO_LOV','Tabela Ref.','LISTA VALOR','SIM',11,null,10,10,null,1,null,'NÃO',''),
(12,2,'VALOR_ENUM','Valor Enum','TEXTO LONGO','NÃO',12,null,100,1000,null,null,null,'NÃO',null),
(13,2,'FG_OBRIGATORIO','Obrigatório?','ENUM','SIM',13,null,10,10,'SIM,NÃO',null,null,'NÃO',null),
(14,2,'FG_AUTO_INCREMENTO','Auto Incremento?','ENUM','SIM',14,null,10,10,'SIM,NÃO',null,null,'NÃO',null),
(15,2,'HINT_CAMPO','Hint Campo','TEXTO LONGO','SIM',15,null,100,1000,null,null,null,'NÃO',''),
(16,1,'ID','Id','NUMÉRICO','SIM',1,0,4,4,'',null,null,'SIM',''),
(17,1,'NOME_TABELA','Nome Tabela','TEXTO','SIM',2,null,30,60,null,null,null,'NÃO',''),
(18,1,'CAMPO_ID','Campo Id','TEXTO','SIM',3,null,30,60,null,null,null,'NÃO',''),
(19,1,'CAMPO_DESCRICAO','Campo Descrição','TEXTO','SIM',4,null,30,60,null,null,null,'NÃO','');

-- =======================================
-- Insere o dicionário das demais tabelas.
-- =======================================
insert into snb_dicionario
select null id,
       upper(a.table_name) nome_tabela,
       'ID' campo_id,
       '?' campo_descricao
  from information_schema.tables a
 where upper(a.table_schema) = upper('stanyslaul')
   and upper(a.table_type)  <> upper('view')
   and not exists (select 1
                     from snb_dicionario b
                    where b.nome_tabela = upper(a.table_name));
					
insert into snb_dicionario_detalhe
select null id,
       c.id_dicionario,
       c.nome_coluna,
       c.titulo_coluna,
       c.ordem,
       if(c.id_dicionario_lov is not null,'LISTA VALOR',c.tipo_dado) tipo_dado,
       c.tamanho_campo,
       c.qtd_caracteres,
       c.precisao_numero,
       c.formato_data,
       c.id_dicionario_lov,
       c.valor_enum,
       c.fg_obrigatorio,
       c.fg_auto_incremento,
       c.hint_campo
  from (select upper(a.table_name) nome_tabela,
               (select t.id from stanyslaul.snb_dicionario t where upper(t.nome_tabela) = upper(a.table_name)) id_dicionario,
               upper(a.column_name) nome_coluna,
               lower(replace(a.column_name,'_',' ')) titulo_coluna,
               a.ordinal_position ordem,
               if(a.column_name = 'senha','SENHA',
                  if(a.column_name like 'img_%','ARQUIVO',
                     if(a.data_type in ('int','bigint','decimal','double','smallint','float'),'NUMÉRICO',
                        if(a.data_type = 'longtext','TEXTO LONGO',
                           if(a.data_type in ('date','datetime','time'),'DATA',
                              if(a.data_type = 'enum','ENUM','TEXTO')
                           )
                        )
                     )
                  )
               ) tipo_dado,
               if(a.data_type='date',14,0) + if(a.data_type='time',10,0) + if(a.data_type='datetime',20,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo,
               if(a.data_type='date',14,0) + if(a.data_type='time',10,0) + if(a.data_type='datetime',20,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtd_caracteres,
               a.numeric_scale precisao_numero,
               if(a.data_type='date','%d/%m/%Y',if(a.data_type='time','%k:%i',if(a.data_type='datetime','%d/%m/%Y %H:%i',''))) formato_data,
               (select t.id from stanyslaul.snb_dicionario t where upper(t.nome_tabela) = upper(b.referenced_table_name)) id_dicionario_lov,
               replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum,
               if(a.is_nullable='NO','NÃO','SIM') fg_obrigatorio,
               if(a.extra='auto_increment','SIM','NÃO') fg_auto_incremento,
               '' hint_campo
          from information_schema.columns a 
          left join information_schema.key_column_usage b 
            on a.table_schema           = b.table_schema
           and a.table_name             = b.table_name 
           and a.column_name            = b.column_name
           and b.referenced_table_name is not null
         where exists (select 1
                         from snb_dicionario bb
                        where bb.nome_tabela = upper(a.table_name))
           and upper(a.table_schema)    = upper('stanyslaul')
         order by a.ordinal_position) c
 where not exists (select 1
                     from snb_dicionario_detalhe bb
                    where bb.id_dicionario = c.id_dicionario
                      and bb.nome_coluna   = c.nome_coluna)
 order by c.id_dicionario, c.ordem;
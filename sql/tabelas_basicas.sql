SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ==== --
-- DROP --
-- ==== --

drop view if exists vsnb_filial;
drop view if exists vsnb_endereco;
drop view if exists vsnb_telefone;
drop view if exists vsnb_end_eletronico;
drop view if exists vsnb_usuario;
drop view if exists vsnb_autorizacao;
drop view if exists vsnb_menu;
drop view if exists vsnb_dicionario_detalhe;

drop table if exists snb_autorizacao;
drop table if exists snb_menu;
drop table if exists snb_dicionario_detalhe;
drop table if exists snb_dicionario;
drop table if exists snb_end_eletronico;
drop table if exists snb_telefone;
drop table if exists snb_endereco;
drop table if exists snb_filial;
drop table if exists snb_usuario;
drop table if exists snb_pessoa;
drop table if exists snb_cidade;
drop table if exists snb_unid_fed;
drop table if exists snb_tp_pessoa;
drop table if exists snb_tp_logradouro;

-- ====== --
-- TABLES --
-- ====== --

CREATE TABLE IF NOT EXISTS snb_tp_logradouro (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(30) NOT NULL,
  sigla varchar(5) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (nome)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

CREATE TABLE IF NOT EXISTS snb_tp_pessoa (
  id enum('J','F') COLLATE latin1_general_cs NOT NULL,
  descricao varchar(45) COLLATE latin1_general_cs NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (descricao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

CREATE TABLE IF NOT EXISTS snb_unid_fed (
  id varchar(2) NOT NULL,
  nome varchar(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS snb_cidade (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL,
  ddd int(2) NOT NULL,
  id_unid_fed varchar(2) NOT NULL,
  cod_ibge varchar(45) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE (nome),
  KEY fksnb_cidade_snb_unid_fed (id_unid_fed)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS snb_pessoa (
  id int(11) NOT NULL AUTO_INCREMENT,
  cnpj_cpf int(11) NOT NULL,
  id_tp_pessoa enum('J','F') NOT NULL,
  nome varchar(100) NOT NULL,
  dt_nascimento date DEFAULT NULL,
  sexo enum('Feminino','Masculino') DEFAULT NULL,
  img_foto varchar(1000) DEFAULT NULL,
  rg varchar(20) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE (cnpj_cpf,id_tp_pessoa),
  KEY fksnb_pessoa_snb_tp_pessoa (id_tp_pessoa)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS snb_usuario (
  id int(11) NOT NULL AUTO_INCREMENT,
  usuario varchar(40) NOT NULL,
  id_pessoa int(11) NOT NULL,
  fg_ativo enum('SIM','NÃO') NOT NULL,
  senha varchar(1000) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (usuario),
  KEY fksnb_usuario_snb_pessoa (id_pessoa)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS snb_filial (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_pessoa int(11) NOT NULL,
  filial int(4) NOT NULL,
  digito int(2) NOT NULL,
  nome_filial varchar(45) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (id_pessoa,filial),
  KEY fksnb_filial_snb_pessoa (id_pessoa)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_endereco (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_filial int(11) NOT NULL,
  id_tp_logradouro int(11) NOT NULL,
  logradouro varchar(100) NOT NULL,
  numero varchar(5) NOT NULL,
  complemento varchar(45) DEFAULT NULL,
  id_cidade int(11) NOT NULL,
  bairro varchar(100) NOT NULL,
  fg_principal enum('SIM','NÃO') NOT NULL,
  cep int(8) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE (id,id_filial),
  KEY fksnb_endereco_snb_filial (id_filial),
  KEY fksnb_endereco_snb_tp_logradouro (id_tp_logradouro),
  KEY fksnb_endereco_snb_cidade (id_cidade)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_telefone (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_filial int(11) NOT NULL,
  ddd int(2) NOT NULL,
  telefone int(9) NOT NULL,
  nm_contato varchar(45) NOT NULL,
  fg_principal enum('SIM','NÃO') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (id,id_filial),
  KEY fksnb_telefone_snb_filial (id_filial)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_end_eletronico (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_filial int(11) NOT NULL,
  endereco varchar(100) NOT NULL,
  fg_principal enum('SIM','NÃO') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (id,id_filial),
  KEY fksnb_end_eletronico_snb_filial (id_filial)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_modulo (
  id int(11) NOT NULL AUTO_INCREMENT,
  descricao varchar(100) NOT NULL,
  img_icone varchar(1000) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE (descricao)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS snb_dicionario (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome_tabela varchar(60) NOT NULL,
  campo_id varchar(60) NOT NULL,
  campo_descricao varchar(60) NOT NULL,
  condicao_filtro longtext,
  ordem longtext,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

CREATE TABLE IF NOT EXISTS snb_dicionario_detalhe (
  id int(10) NOT NULL AUTO_INCREMENT,
  id_dicionario int(10) NOT NULL,
  nome_coluna varchar(60) NOT NULL,
  titulo_coluna varchar(60) NOT NULL,
  ordem decimal(5,2) NOT NULL,
  tipo_dado enum('SENHA','NUMÉRICO','DATA','DATA HORA','HORA','ARQUIVO','TEXTO','TEXTO LONGO','LISTA VALOR','ENUM') NOT NULL,
  tamanho_campo int(3) NOT NULL DEFAULT '10',
  qtd_caracteres int(3) NOT NULL DEFAULT '10',
  precisao_numero int(1) DEFAULT NULL,
  formato_data varchar(40) DEFAULT NULL,
  id_dicionario_lov int(10) DEFAULT NULL,
  valor_enum longtext,
  fg_obrigatorio enum('SIM','NÃO') NOT NULL DEFAULT 'NÃO',
  fg_auto_incremento enum('SIM','NÃO') NOT NULL DEFAULT 'NÃO',
  hint_campo longtext,
  PRIMARY KEY (id),
  KEY fksnb_dicion_det_snb_dicion (id_dicionario)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=158 ;

CREATE TABLE IF NOT EXISTS snb_menu (
  id int(11) NOT NULL AUTO_INCREMENT,
  cod_aplicacao varchar(45) NOT NULL,
  id_dicionario_tabela int(11) DEFAULT NULL,
  id_dicionario_view int(11) DEFAULT NULL,
  nm_menu varchar(100) NOT NULL,
  nm_pagina varchar(100) DEFAULT NULL,
  fg_ativo enum('SIM','NÃO') NOT NULL,
  sequencia int(4) NOT NULL,
  id_menu_proximo int(11) DEFAULT NULL,
  id_modulo int(11) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE (cod_aplicacao),
  KEY fksnb_menu_snb_menu (id_menu_proximo),
  KEY fksnb_menu_snb_modulo (id_modulo),
  KEY fksnb_menu_snb_dicionario_tab (id_dicionario_tabela),
  KEY fksnb_menu_snb_dicionario_view (id_dicionario_view)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

CREATE TABLE IF NOT EXISTS snb_autorizacao (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_menu int(11) NOT NULL,
  id_usuario int(11) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (id_menu,id_usuario),
  KEY fksnb_autorizacao_snb_usuario (id_usuario)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- ===== --
-- VIEWS --
-- ===== --

create or replace view vsnb_filial as
select a.id,
       a.id_pessoa,
       b.nome,
       b.id_tp_pessoa,
       b.cnpj_cpf,
       a.filial,
       a.digito,
       b.rg,
       b.dt_nascimento,
       b.sexo,
       a.nome_filial
  from snb_pessoa b,
       snb_filial a
 where b.id = a.id_pessoa;

create or replace view vsnb_endereco as
select a.id,
       a.id_filial,
       b.nome nome_pessoa,
       b.nome_filial,
       a.id_tp_logradouro,
       c.nome tp_logradouro,
       a.logradouro,
       a.numero,
       a.complemento,
       a.id_cidade,
       d.nome nome_cidade,
       a.bairro,
       a.cep,
       a.fg_principal
  from snb_cidade d,
       snb_tp_logradouro c,
       vsnb_filial b,
       snb_endereco a
 where b.id = a.id_filial
   and c.id = a.id_tp_logradouro
   and d.id = a.id_cidade;

create or replace view vsnb_telefone as
select a.id,
       a.id_filial,
       b.nome nome_pessoa,
       b.nome_filial,
       a.ddd,
       a.telefone,
       a.nm_contato,
       a.fg_principal
  from vsnb_filial b,
       snb_telefone a
 where b.id = a.id_filial;

create or replace view vsnb_end_eletronico as
select a.id,
       a.id_filial,
       b.nome nome_pessoa,
       b.nome_filial,
       a.endereco,
       a.fg_principal
  from vsnb_filial b,
       snb_end_eletronico a
 where b.id = a.id_filial;

create or replace view vsnb_usuario as
select a.id,
       a.usuario,
       a.id_pessoa,
       b.nome nome_usuario,
       a.fg_ativo
  from snb_pessoa b,
       snb_usuario a
 where b.id = a.id_pessoa;
 
create or replace view vsnb_autorizacao as
select a.id,
       a.id_menu,
       b.cod_aplicacao,
       b.nm_menu,
       a.id_usuario,
       c.usuario,
       c.nome_usuario
  from vsnb_usuario c,
       snb_menu b,
       snb_autorizacao a
 where b.id = a.id_menu
   and c.id = a.id_usuario; 

create or replace view vsnb_menu as
select a.id,
       a.cod_aplicacao,
       a.id_dicionario_tabela,
       (select b.nome_tabela from snb_dicionario b where b.id = a.id_dicionario_tabela) nm_tabela,
       a.id_dicionario_view,
       (select b.nome_tabela from snb_dicionario b where b.id = a.id_dicionario_view) nm_view,
       a.nm_menu,
       a.nm_pagina,
       a.fg_ativo,
       a.sequencia,
       a.id_menu_proximo,
       a.id_modulo,
       b.descricao nome_modulo
  from snb_modulo b,
       snb_menu a
 where b.id = a.id_modulo;

create or replace view vsnb_dicionario_detalhe as
select a.id,
       a.id_dicionario,
       b.nome_tabela,
       a.nome_coluna,
       a.ordem,
       a.tipo_dado,
       a.fg_obrigatorio,
       a.fg_auto_incremento
  from snb_dicionario b,
       snb_dicionario_detalhe a
 where b.id = a.id_dicionario;

-- ======= --
-- INSERTS --
-- ======= --

INSERT INTO snb_autorizacao (id, id_menu, id_usuario) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1);

INSERT INTO snb_cidade (id, nome, ddd, id_unid_fed, cod_ibge) VALUES
(1, 'SERRANA', 16, 'SP', NULL),
(2, 'RIBEIRÃO PRETO', 16, 'SP', NULL);

INSERT INTO snb_dicionario (id, nome_tabela, campo_id, campo_descricao, condicao_filtro, ordem) VALUES
(1, 'SNB_DICIONARIO', 'ID', 'NOME_TABELA', NULL, 'NOME_TABELA'),
(2, 'SNB_DICIONARIO_DETALHE', 'ID', 'NOME_COLUNA', NULL, 'ORDEM'),
(3, 'SNB_AUTORIZACAO', 'ID', 'ID_MENU,ID_USUARIO', NULL, 'ID_USUARIO,ID_MENU'),
(4, 'SNB_CIDADE', 'ID', 'NOME,ID_UNID_FED', NULL, 'ID_UNID_FED,NOME'),
(5, 'SNB_END_ELETRONICO', 'ID', 'ID_FILIAL,ENDERECO', NULL, 'ID_FILIAL,ENDERECO'),
(6, 'SNB_ENDERECO', 'ID', 'ID_FILIAL,LOGRADOURO', NULL, 'ID_FILIAL,LOGRADOURO'),
(7, 'SNB_FILIAL', 'ID', 'NOME_FILIAL', NULL, 'ID_PESSOA,NOME_FILIAL'),
(8, 'SNB_MENU', 'ID', 'COD_APLICACAO,NM_MENU', NULL, 'COD_APLICACAO,NM_MENU'),
(9, 'SNB_MODULO', 'ID', 'DESCRICAO', NULL, 'DESCRICAO'),
(10, 'SNB_PESSOA', 'ID', 'NOME,CNPJ_CPF', NULL, 'NOME,CNPJ_CPF'),
(11, 'SNB_TELEFONE', 'ID', 'ID_FILIAL,TELEFONE', NULL, 'ID_FILIAL,TELEFONE'),
(12, 'SNB_TP_LOGRADOURO', 'ID', 'NOME', NULL, 'NOME'),
(13, 'SNB_TP_PESSOA', 'ID', 'DESCRICAO', NULL, 'ID'),
(14, 'SNB_UNID_FED', 'ID', 'NOME', NULL, 'ID,NOME'),
(15, 'SNB_USUARIO', 'ID', 'USUARIO', NULL, 'USUARIO'),
(16, 'VSNB_AUTORIZACAO', 'ID', 'COD_APLICACAO,NM_MENU,ID_USUARIO,USUARIO', NULL, 'COD_APLICACAO,NM_MENU,ID_USUARIO,USUARIO'),
(17, 'VSNB_DICIONARIO_DETALHE', 'ID', 'NOME_TABELA,NOME_COLUNA', NULL, 'NOME_TABELA,ORDEM'),
(18, 'VSNB_END_ELETRONICO', 'ID', 'NOME_PESSOA,ENDERECO', NULL, 'NOME_PESSOA,ENDERECO'),
(19, 'VSNB_ENDERECO', 'ID', 'NOME_PESSOA,TP_LOGRADOURO,LOGRADOURO,NUMERO', NULL, 'NOME_PESSOA,TP_LOGRADOURO,LOGRADOURO,NUMERO'),
(20, 'VSNB_FILIAL', 'ID', 'NOME,NOME_FILIAL', NULL, 'NOME,NOME_FILIAL'),
(21, 'VSNB_MENU', 'ID', 'COD_APLICACAO,NM_MENU', NULL, 'COD_APLICACAO,NM_MENU'),
(22, 'VSNB_TELEFONE', 'ID', 'NOME_PESSOA,DDD,TELEFONE', NULL, 'NOME_PESSOA,DDD,TELEFONE'),
(23, 'VSNB_USUARIO', 'ID', 'USUARIO,NOME_USUARIO', NULL, 'USUARIO,NOME_USUARIO');

INSERT INTO snb_dicionario_detalhe (id, id_dicionario, nome_coluna, titulo_coluna, ordem, tipo_dado, tamanho_campo, qtd_caracteres, precisao_numero, formato_data, id_dicionario_lov, valor_enum, fg_obrigatorio, fg_auto_incremento, hint_campo) VALUES
(1, 2, 'ID', 'Id', '1.00', 'NUMÉRICO', 5, 5, NULL, NULL, NULL, NULL, 'SIM', 'SIM', NULL),
(2, 2, 'ID_DICIONARIO', 'Id Dicionário', '2.00', 'LISTA VALOR', 5, 5, NULL, NULL, 1, NULL, 'SIM', 'NÃO', NULL),
(3, 2, 'NOME_COLUNA', 'Nome Coluna', '3.00', 'TEXTO', 30, 50, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(4, 2, 'TITULO_COLUNA', 'Título Coluna', '4.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(5, 2, 'ORDEM', 'Ordem', '5.00', 'NUMÉRICO', 5, 5, 2, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(6, 2, 'TIPO_DADO', 'Tipo Dado', '6.00', 'ENUM', 10, 10, NULL, NULL, NULL, 'SENHA,NUMÉRICO,DATA,DATA HORA,HORA,ARQUIVO,TEXTO,TEXTO LONGO,LISTA VALOR,ENUM', 'SIM', 'NÃO', NULL),
(7, 2, 'TAMANHO_CAMPO', 'Tamanho Campo', '7.00', 'NUMÉRICO', 5, 5, 0, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(8, 2, 'QTD_CARACTERES', 'Qtd Caracteres', '8.00', 'NUMÉRICO', 5, 5, 0, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(9, 2, 'PRECISAO_NUMERO', 'Precisão Número', '9.00', 'NUMÉRICO', 5, 5, 0, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(10, 2, 'FORMATO_DATA', 'Formato Data', '10.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(11, 2, 'ID_DICIONARIO_LOV', 'Tabela Ref.', '11.00', 'LISTA VALOR', 10, 10, NULL, NULL, 1, NULL, 'SIM', 'NÃO', NULL),
(12, 2, 'VALOR_ENUM', 'Valor Enum', '12.00', 'TEXTO LONGO', 100, 1000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(13, 2, 'FG_OBRIGATORIO', 'Obrigatório?', '13.00', 'ENUM', 10, 10, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(14, 2, 'FG_AUTO_INCREMENTO', 'Auto Incremento?', '14.00', 'ENUM', 10, 10, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(15, 2, 'HINT_CAMPO', 'Hint Campo', '15.00', 'TEXTO LONGO', 100, 1000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(16, 1, 'ID', 'Id', '1.00', 'NUMÉRICO', 4, 4, 0, NULL, NULL, NULL, 'SIM', 'SIM', NULL),
(17, 1, 'NOME_TABELA', 'Nome Tabela', '2.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(18, 1, 'CAMPO_ID', 'Campo Id', '3.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(19, 1, 'CAMPO_DESCRICAO', 'Campo Descrição', '4.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(20, 1, 'CONDICAO_FILTRO', 'Condição', '5.00', 'TEXTO LONGO', 100, 4000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', 'Condição para o filtro da LOV (Where)'),
(21, 1, 'ORDEM', 'Ordem', '6.00', 'TEXTO LONGO', 100, 4000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', 'Ordem de apresentação da LOV (Order by)'),
(22, 3, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(23, 3, 'ID_MENU', 'id menu', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 8, '', 'SIM', 'NÃO', NULL),
(24, 3, 'ID_USUARIO', 'id usuario', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 15, '', 'SIM', 'NÃO', NULL),
(25, 4, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(26, 4, 'NOME', 'nome', '2.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(27, 4, 'DDD', 'ddd', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(28, 4, 'ID_UNID_FED', 'id unid fed', '4.00', 'LISTA VALOR', 2, 2, NULL, NULL, 14, '', 'SIM', 'NÃO', NULL),
(29, 4, 'COD_IBGE', 'cod ibge', '5.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(30, 5, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(31, 5, 'ID_FILIAL', 'id filial', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 7, '', 'SIM', 'NÃO', NULL),
(32, 5, 'ENDERECO', 'endereco', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(33, 5, 'FG_PRINCIPAL', 'fg principal', '4.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(34, 6, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(35, 6, 'ID_FILIAL', 'id filial', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 7, '', 'SIM', 'NÃO', NULL),
(36, 6, 'ID_TP_LOGRADOURO', 'id tp logradouro', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 12, '', 'SIM', 'NÃO', NULL),
(37, 6, 'LOGRADOURO', 'logradouro', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(38, 6, 'NUMERO', 'numero', '5.00', 'TEXTO', 5, 5, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(39, 6, 'COMPLEMENTO', 'complemento', '6.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(40, 6, 'ID_CIDADE', 'id cidade', '7.00', 'LISTA VALOR', 10, 10, 0, NULL, 4, '', 'SIM', 'NÃO', NULL),
(41, 6, 'BAIRRO', 'bairro', '8.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(42, 6, 'FG_PRINCIPAL', 'fg principal', '9.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(43, 6, 'CEP', 'cep', '10.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(44, 7, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(45, 7, 'ID_PESSOA', 'id pessoa', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 10, '', 'SIM', 'NÃO', NULL),
(46, 7, 'FILIAL', 'filial', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(47, 7, 'DIGITO', 'digito', '4.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(48, 7, 'NOME_FILIAL', 'nome filial', '5.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(49, 8, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(50, 8, 'COD_APLICACAO', 'cod aplicacao', '2.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(51, 8, 'NM_TABELA', 'nm tabela', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(52, 8, 'NM_VIEW', 'nm view', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(53, 8, 'NM_MENU', 'nm menu', '5.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(54, 8, 'NM_PAGINA', 'nm pagina', '6.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(55, 8, 'FG_ATIVO', 'fg ativo', '7.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(56, 8, 'SEQUENCIA', 'sequencia', '8.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(57, 8, 'ID_MENU_PROXIMO', 'id menu proximo', '9.00', 'LISTA VALOR', 10, 10, 0, NULL, 8, '', 'NÃO', 'NÃO', NULL),
(58, 8, 'ID_MODULO', 'id modulo', '10.00', 'LISTA VALOR', 10, 10, 0, NULL, 9, '', 'NÃO', 'NÃO', NULL),
(59, 9, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(60, 9, 'DESCRICAO', 'descricao', '2.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(61, 9, 'IMG_ICONE', 'img icone', '3.00', 'ARQUIVO', 1000, 1000, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(62, 10, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(63, 10, 'CNPJ_CPF', 'cnpj cpf', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(64, 10, 'ID_TP_PESSOA', 'id tp pessoa', '3.00', 'LISTA VALOR', 1, 1, NULL, NULL, 13, 'J,F', 'SIM', 'NÃO', NULL),
(65, 10, 'NOME', 'nome', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(66, 10, 'DT_NASCIMENTO', 'dt nascimento', '5.00', 'DATA', 14, 14, NULL, '%d/%m/%Y', NULL, '', 'NÃO', 'NÃO', NULL),
(67, 10, 'SEXO', 'sexo', '6.00', 'ENUM', 9, 9, NULL, NULL, NULL, 'Feminino,Masculino', 'NÃO', 'NÃO', NULL),
(68, 10, 'IMG_FOTO', 'img foto', '7.00', 'ARQUIVO', 1000, 1000, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(69, 10, 'RG', 'rg', '8.00', 'TEXTO', 20, 20, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(70, 11, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(71, 11, 'ID_FILIAL', 'id filial', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 7, '', 'SIM', 'NÃO', NULL),
(72, 11, 'DDD', 'ddd', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(73, 11, 'TELEFONE', 'telefone', '4.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(74, 11, 'NM_CONTATO', 'nm contato', '5.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(75, 11, 'FG_PRINCIPAL', 'fg principal', '6.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(76, 12, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(77, 12, 'NOME', 'nome', '2.00', 'TEXTO', 30, 30, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(78, 12, 'SIGLA', 'sigla', '3.00', 'TEXTO', 5, 5, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(79, 13, 'ID', 'id', '1.00', 'ENUM', 1, 1, NULL, NULL, NULL, 'J,F', 'SIM', 'NÃO', NULL),
(80, 13, 'DESCRICAO', 'descricao', '2.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(81, 14, 'ID', 'id', '1.00', 'TEXTO', 2, 2, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(82, 14, 'NOME', 'nome', '2.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(83, 15, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(84, 15, 'USUARIO', 'usuario', '2.00', 'TEXTO', 40, 40, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(85, 15, 'ID_PESSOA', 'id pessoa', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 10, '', 'SIM', 'NÃO', NULL),
(86, 15, 'FG_ATIVO', 'fg ativo', '4.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(87, 15, 'SENHA', 'senha', '5.00', 'SENHA', 1000, 1000, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(88, 16, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(89, 16, 'ID_MENU', 'id menu', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(90, 16, 'COD_APLICACAO', 'cod aplicacao', '3.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(91, 16, 'NM_MENU', 'nm menu', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(92, 16, 'ID_USUARIO', 'id usuario', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(93, 16, 'USUARIO', 'usuario', '6.00', 'TEXTO', 40, 40, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(94, 16, 'NOME_USUARIO', 'nome usuario', '7.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(95, 17, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(96, 17, 'ID_DICIONARIO', 'id dicionario', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(97, 17, 'NOME_TABELA', 'nome tabela', '3.00', 'TEXTO', 60, 60, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(98, 17, 'NOME_COLUNA', 'nome coluna', '4.00', 'TEXTO', 60, 60, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(99, 17, 'ORDEM', 'ordem', '5.00', 'NUMÉRICO', 7, 7, 2, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(100, 17, 'TIPO_DADO', 'tipo dado', '6.00', 'ENUM', 11, 11, NULL, NULL, NULL, 'SENHA,NUMÉRICO,DATA,DATA HORA,HORA,ARQUIVO,TEXTO,TEXTO LONGO,LISTA VALOR,ENUM', 'SIM', 'NÃO', NULL),
(101, 17, 'FG_OBRIGATORIO', 'fg obrigatorio', '7.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(102, 17, 'FG_AUTO_INCREMENTO', 'fg auto incremento', '8.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(103, 18, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(104, 18, 'ID_FILIAL', 'id filial', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(105, 18, 'NOME_PESSOA', 'nome pessoa', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(106, 18, 'NOME_FILIAL', 'nome filial', '4.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(107, 18, 'ENDERECO', 'endereco', '5.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(108, 18, 'FG_PRINCIPAL', 'fg principal', '6.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(109, 19, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(110, 19, 'ID_FILIAL', 'id filial', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(111, 19, 'NOME_PESSOA', 'nome pessoa', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(112, 19, 'NOME_FILIAL', 'nome filial', '4.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(113, 19, 'ID_TP_LOGRADOURO', 'id tp logradouro', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(114, 19, 'TP_LOGRADOURO', 'tp logradouro', '6.00', 'TEXTO', 30, 30, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(115, 19, 'LOGRADOURO', 'logradouro', '7.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(116, 19, 'NUMERO', 'numero', '8.00', 'TEXTO', 5, 5, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(117, 19, 'COMPLEMENTO', 'complemento', '9.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(118, 19, 'ID_CIDADE', 'id cidade', '10.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(119, 19, 'NOME_CIDADE', 'nome cidade', '11.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(120, 19, 'BAIRRO', 'bairro', '12.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(121, 19, 'CEP', 'cep', '13.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(122, 19, 'FG_PRINCIPAL', 'fg principal', '14.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(123, 20, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(124, 20, 'ID_PESSOA', 'id pessoa', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(125, 20, 'NOME', 'nome', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(126, 20, 'ID_TP_PESSOA', 'id tp pessoa', '4.00', 'ENUM', 1, 1, NULL, NULL, NULL, 'J,F', 'SIM', 'NÃO', NULL),
(127, 20, 'CNPJ_CPF', 'cnpj cpf', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(128, 20, 'FILIAL', 'filial', '6.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(129, 20, 'DIGITO', 'digito', '7.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(130, 20, 'RG', 'rg', '8.00', 'TEXTO', 20, 20, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(131, 20, 'DT_NASCIMENTO', 'dt nascimento', '9.00', 'DATA', 14, 14, NULL, '%d/%m/%Y', NULL, '', 'NÃO', 'NÃO', NULL),
(132, 20, 'SEXO', 'sexo', '10.00', 'ENUM', 9, 9, NULL, NULL, NULL, 'Feminino,Masculino', 'NÃO', 'NÃO', NULL),
(133, 20, 'NOME_FILIAL', 'nome filial', '11.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(134, 21, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(135, 21, 'COD_APLICACAO', 'cod aplicacao', '2.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(136, 21, 'NM_TABELA', 'nm tabela', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(137, 21, 'NM_VIEW', 'nm view', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(138, 21, 'NM_MENU', 'nm menu', '5.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(139, 21, 'NM_PAGINA', 'nm pagina', '6.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(140, 21, 'FG_ATIVO', 'fg ativo', '7.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(141, 21, 'SEQUENCIA', 'sequencia', '8.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(142, 21, 'ID_MENU_PROXIMO', 'id menu proximo', '9.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(143, 21, 'ID_MODULO', 'id modulo', '10.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(144, 21, 'NOME_MODULO', 'nome modulo', '11.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(145, 22, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(146, 22, 'ID_FILIAL', 'id filial', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(147, 22, 'NOME_PESSOA', 'nome pessoa', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(148, 22, 'NOME_FILIAL', 'nome filial', '4.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(149, 22, 'DDD', 'ddd', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(150, 22, 'TELEFONE', 'telefone', '6.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(151, 22, 'NM_CONTATO', 'nm contato', '7.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(152, 22, 'FG_PRINCIPAL', 'fg principal', '8.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(153, 23, 'ID', 'id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(154, 23, 'USUARIO', 'usuario', '2.00', 'TEXTO', 40, 40, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(155, 23, 'ID_PESSOA', 'id pessoa', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(156, 23, 'NOME_USUARIO', 'nome usuario', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(157, 23, 'FG_ATIVO', 'fg ativo', '5.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL);

INSERT INTO snb_menu (id, cod_aplicacao, id_dicionario_tabela, id_dicionario_view, nm_menu, nm_pagina, fg_ativo, sequencia, id_menu_proximo, id_modulo) VALUES
(1, 'BAS001', 14, NULL, 'UF', NULL, 'SIM', 1, NULL, 1),
(2, 'BAS002', 4, NULL, 'Cidade', NULL, 'SIM', 2, NULL, 1),
(3, 'BAS003', 12, NULL, 'Tipo de Logradouro', NULL, 'SIM', 3, NULL, 1),
(4, 'PES001', 13, NULL, 'Tipo de Pessoa', NULL, 'SIM', 4, NULL, 1),
(5, 'PES002', 10, NULL, 'Pessoa', NULL, 'SIM', 5, 6, 2),
(6, 'PES003', 7, 20, 'Filial', NULL, 'SIM', 6, 7, 2),
(7, 'PES004', 6, 19, 'Endereço', NULL, 'SIM', 7, 8, 2),
(8, 'PES005', 11, 22, 'Telefone', NULL, 'SIM', 8, 9, 2),
(9, 'PES006', 5, 18, 'Endereço Eletrônico', NULL, 'SIM', 9, NULL, 2),
(10, 'SIS001', 8, 21, 'Menu', NULL, 'SIM', 9, NULL, 3),
(11, 'SIS002', 15, 23, 'Usuário', NULL, 'SIM', 10, NULL, 3),
(12, 'SIS003', 3, 16, 'Autorização ao Sistema', NULL, 'SIM', 11, NULL, 3),
(13, 'SIS004', 9, NULL, 'Módulo Sistema', NULL, 'SIM', 12, NULL, 3),
(14, 'SIS005', 1, NULL, 'Dicionário', NULL, 'SIM', 13, NULL, 3),
(15, 'SIS006', 2, 17, 'Dicionário Detalhe', NULL, 'SIM', 14, NULL, 3);

INSERT INTO snb_modulo (id, descricao, img_icone) VALUES
(1, 'Cadastro Básico', ''),
(2, 'Cadastro Clientes', ''),
(3, 'Administração do Sistema', '');

INSERT INTO snb_pessoa (id, cnpj_cpf, id_tp_pessoa, nome, dt_nascimento, sexo, img_foto, rg) VALUES
(1, 999999999, 'F', 'ADMINISTRADOR DO SISTEMA', '2015-04-01', 'Masculino', NULL, NULL);

INSERT INTO snb_tp_logradouro (id, nome, sigla) VALUES
(1, 'RUA', 'R'),
(2, 'AVENIDA', 'AV'),
(3, 'FAZENDA', 'FAZ'),
(4, 'RODOVIA', 'ROD'),
(5, 'SÍTIO', 'SITIO'),
(6, 'TRAVESSA', 'TRAV'),
(7, 'ESTRADA', 'ESTR'),
(8, 'ALAMEDA', 'AL'),
(9, 'AEROPORTO', 'AERO'),
(10, 'ÁREA', 'AREA'),
(11, 'CAMPO', 'CAMPO'),
(12, 'CHACARA', 'CHAC'),
(13, 'COLÔNIA', 'COL'),
(14, 'CONDOMÍNIO', 'COND'),
(15, 'CONJUNTO', 'CONJ'),
(16, 'DISTRITO', 'DIST'),
(17, 'ESPLANADA', 'ESPL'),
(18, 'ESTAÇÃO', 'EST'),
(19, 'FAVELA', 'FAV'),
(20, 'FEIRA', 'FEIRA'),
(21, 'JARDIM', 'JRD'),
(22, 'LADEIRA', 'LAD'),
(23, 'LAGO', 'LAGO'),
(24, 'LAGOA', 'LAGOA'),
(25, 'LARGO', 'LARGO'),
(26, 'LOTEAMENTO', 'LOTM'),
(27, 'MORRO', 'MORRO'),
(28, 'NÚCLEO', 'NUCL'),
(29, 'OUTROS', 'OUT'),
(30, 'PARQUE', 'PARQ'),
(31, 'PASSARELA', 'PASS'),
(32, 'PATIO', 'PATIO'),
(33, 'PRACA', 'PRACA'),
(34, 'QUADRA', 'QUAD'),
(35, 'RECANTO', 'RECT'),
(36, 'RESIDENCIAL', 'RES'),
(37, 'SETOR', 'SET'),
(38, 'TRECHO', 'TREC'),
(39, 'TREVO', 'TREVO'),
(40, 'VALE', 'VALE'),
(41, 'VEREDA', 'VERD'),
(42, 'VIA', 'VIA'),
(43, 'VIADUTO', 'VIAD'),
(44, 'VIELA', 'VIELA'),
(45, 'VILA', 'VILA'),
(46, 'USINA', 'USINA'),
(47, 'PAVIMENTAÇÃO', 'PAV'),
(48, 'RECINTO', 'REC'),
(49, 'ESTÂNCIA', 'ESTN');

INSERT INTO snb_tp_pessoa (id, descricao) VALUES
('F', 'FÍSICA'),
('J', 'JURÍDICA');

INSERT INTO snb_unid_fed (id, nome) VALUES
('AC', 'ACRE'),
('AL', 'ALAGOAS'),
('AP', 'AMAPA'),
('AM', 'AMAZONAS'),
('BA', 'BAHIA'),
('CE', 'CEARA'),
('DF', 'DISTRITO FEDERAL'),
('ES', 'ESPIRITO SANTO'),
('EX', 'EXTERIOR'),
('GO', 'GOIÁS'),
('MA', 'MARANHÃO'),
('MT', 'MATO GROSSO'),
('MS', 'MATO GROSSO DO SUL'),
('MG', 'MINAS GERAIS'),
('PA', 'PARÁ'),
('PB', 'PARAÍBA'),
('PR', 'PARANÁ'),
('PE', 'PERNAMBUCO'),
('PI', 'PIAUÍ'),
('RJ', 'RIO DE JANEIRO'),
('RN', 'RIO GRANDE DO NORTE'),
('RS', 'RIO GRANDE DO SUL'),
('RO', 'RONDÔNIA'),
('RR', 'RORAIMA'),
('SC', 'SANTA CATARINA'),
('SP', 'SÃO PAULO'),
('SE', 'SERGIPE'),
('TO', 'TOCANTINS');

INSERT INTO snb_usuario (id, usuario, id_pessoa, fg_ativo, senha) VALUES
(1, 'admin', 1, 'SIM', 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- ============ --
-- FOREIGN KEYS --
-- ============ --

ALTER TABLE snb_autorizacao
  ADD CONSTRAINT fksnb_autorizacao_snb_menu FOREIGN KEY (id_menu) REFERENCES snb_menu (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_autorizacao_snb_usuario FOREIGN KEY (id_usuario) REFERENCES snb_usuario (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_cidade
  ADD CONSTRAINT fksnb_cidade_snb_unid_fed FOREIGN KEY (id_unid_fed) REFERENCES snb_unid_fed (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_dicionario_detalhe
  ADD CONSTRAINT fksnb_dicion_det_snb_dicion FOREIGN KEY (id_dicionario) REFERENCES snb_dicionario (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_endereco
  ADD CONSTRAINT fksnb_endereco_snb_cidade FOREIGN KEY (id_cidade) REFERENCES snb_cidade (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_endereco_snb_filial FOREIGN KEY (id_filial) REFERENCES snb_filial (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_endereco_snb_tp_logradouro FOREIGN KEY (id_tp_logradouro) REFERENCES snb_tp_logradouro (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_end_eletronico
  ADD CONSTRAINT fksnb_end_eletronico_snb_filial FOREIGN KEY (id_filial) REFERENCES snb_filial (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_filial
  ADD CONSTRAINT fksnb_filial_snb_pessoa FOREIGN KEY (id_pessoa) REFERENCES snb_pessoa (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_menu
  ADD CONSTRAINT fksnb_menu_snb_dicionario_view FOREIGN KEY (id_dicionario_view) REFERENCES snb_dicionario (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_menu_snb_dicionario_tab FOREIGN KEY (id_dicionario_tabela) REFERENCES snb_dicionario (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_menu_snb_menu FOREIGN KEY (id_menu_proximo) REFERENCES snb_menu (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_menu_snb_modulo FOREIGN KEY (id_modulo) REFERENCES snb_modulo (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_pessoa
  ADD CONSTRAINT fksnb_pessoa_snb_tp_pessoa FOREIGN KEY (id_tp_pessoa) REFERENCES snb_tp_pessoa (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_telefone
  ADD CONSTRAINT fksnb_telefone_snb_filial FOREIGN KEY (id_filial) REFERENCES snb_filial (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_usuario
  ADD CONSTRAINT fksnb_usuario_snb_pessoa FOREIGN KEY (id_pessoa) REFERENCES snb_pessoa (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ==================================== --
-- INSERT DICIONARIO DE OUTRAS TABELAS. --
-- ==================================== --

insert into snb_dicionario
select null id,
       upper(a.table_name) nome_tabela,
       'ID' campo_id,
       '' campo_descricao,
       '' condicao_filtro,
       '' ordem
  from information_schema.tables a
 where upper(a.table_schema) = 'STANYSLAUL'
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
               (select t.id from snb_dicionario t where upper(t.nome_tabela) = upper(a.table_name)) id_dicionario,
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
               if(a.data_type='date','%d/%m/%Y',if(a.data_type='time','%k:%i',if(a.data_type='datetime','%d/%m/%Y %H:%i',null))) formato_data,
               (select t.id from snb_dicionario t where upper(t.nome_tabela) = upper(b.referenced_table_name)) id_dicionario_lov,
               replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum,
               if(a.is_nullable='NO','SIM','NÃO') fg_obrigatorio,
               if(a.extra='auto_increment','SIM','NÃO') fg_auto_incremento,
               null hint_campo
          from information_schema.columns a 
          left join information_schema.key_column_usage b 
            on a.table_schema           = b.table_schema
           and a.table_name             = b.table_name 
           and a.column_name            = b.column_name
           and b.referenced_table_name is not null
         where exists (select 1
                         from snb_dicionario bb
                        where bb.nome_tabela = upper(a.table_name))
           and upper(a.table_schema)    = 'STANYSLAUL'
         order by a.ordinal_position) c
 where not exists (select 1
                     from snb_dicionario_detalhe bb
                    where bb.id_dicionario = c.id_dicionario
                      and bb.nome_coluna   = c.nome_coluna)
 order by c.id_dicionario, c.ordem;

/*
update snb_menu a
   set a.id_dicionario_tabela = (select b.id
                                   from snb_dicionario b
                                  where b.nome_tabela = upper(a.nm_tabela)),
       a.id_dicionario_view   =  (select b.id
                                   from snb_dicionario b
                                  where b.nome_tabela = upper(a.nm_view));
*/
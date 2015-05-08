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
drop table if exists snb_modulo;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=205 ;

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
(1, 'snb_dicionario', 'id', 'nome_tabela', null, 'nome_tabela'),
(2, 'snb_dicionario_detalhe', 'id', 'nome_coluna', null, 'ordem'),
(3, 'snb_autorizacao', 'id', 'id_menu,id_usuario', null, 'id_usuario,id_menu'),
(4, 'snb_cidade', 'id', 'nome,id_unid_fed', null, 'id_unid_fed,nome'),
(5, 'snb_end_eletronico', 'id', 'id_filial,endereco', null, 'id_filial,endereco'),
(6, 'snb_endereco', 'id', 'id_filial,logradouro', null, 'id_filial,logradouro'),
(7, 'snb_filial', 'id', 'nome_filial', null, 'id_pessoa,nome_filial'),
(8, 'snb_menu', 'id', 'cod_aplicacao,nm_menu', null, 'cod_aplicacao,nm_menu'),
(9, 'snb_modulo', 'id', 'descricao', null, 'descricao'),
(10, 'snb_pessoa', 'id', 'nome,cnpj_cpf', null, 'nome,cnpj_cpf'),
(11, 'snb_telefone', 'id', 'id_filial,telefone', null, 'id_filial,telefone'),
(12, 'snb_tp_logradouro', 'id', 'nome', null, 'nome'),
(13, 'snb_tp_pessoa', 'id', 'descricao', null, 'id'),
(14, 'snb_unid_fed', 'id', 'nome', null, 'id,nome'),
(15, 'snb_usuario', 'id', 'usuario', null, 'usuario'),
(16, 'vsnb_autorizacao', 'id', 'cod_aplicacao,nm_menu,id_usuario,usuario', null, 'cod_aplicacao,nm_menu,id_usuario,usuario'),
(17, 'vsnb_dicionario_detalhe', 'id', 'nome_tabela,nome_coluna', null, 'nome_tabela,ordem'),
(18, 'vsnb_end_eletronico', 'id', 'nome_pessoa,endereco', null, 'nome_pessoa,endereco'),
(19, 'vsnb_endereco', 'id', 'nome_pessoa,tp_logradouro,logradouro,numero', null, 'nome_pessoa,tp_logradouro,logradouro,numero'),
(20, 'vsnb_filial', 'id', 'nome,nome_filial', null, 'nome,nome_filial'),
(21, 'vsnb_menu', 'id', 'cod_aplicacao,nm_menu', null, 'cod_aplicacao,nm_menu'),
(22, 'vsnb_telefone', 'id', 'nome_pessoa,ddd,telefone', null, 'nome_pessoa,ddd,telefone'),
(23, 'vsnb_usuario', 'id', 'usuario,nome_usuario', null, 'usuario,nome_usuario');

INSERT INTO snb_dicionario_detalhe (id, id_dicionario, nome_coluna, titulo_coluna, ordem, tipo_dado, tamanho_campo, qtd_caracteres, precisao_numero, formato_data, id_dicionario_lov, valor_enum, fg_obrigatorio, fg_auto_incremento, hint_campo) VALUES
/*snb_dicionario_detalhe*/
(1, 2, 'id',                    'Id', '1.00', 'NUMÉRICO', 5, 5, NULL, NULL, NULL, NULL, 'SIM', 'SIM', NULL),
(2, 2, 'id_dicionario',         'Id Dicionário', '2.00', 'LISTA VALOR', 5, 5, NULL, NULL, 1, NULL, 'SIM', 'NÃO', NULL),
(3, 2, 'nome_coluna',           'Nome Coluna', '3.00', 'TEXTO', 30, 50, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(4, 2, 'titulo_coluna',         'Título Coluna', '4.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(5, 2, 'ordem',                 'Ordem', '5.00', 'NUMÉRICO', 5, 5, 2, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(6, 2, 'tipo_dado',             'Tipo Dado', '6.00', 'ENUM', 10, 10, NULL, NULL, NULL, 'SENHA,NUMÉRICO,DATA,DATA HORA,HORA,ARQUIVO,TEXTO,TEXTO LONGO,LISTA VALOR,ENUM', 'SIM', 'NÃO', NULL),
(7, 2, 'tamanho_campo',         'Tamanho Campo', '7.00', 'NUMÉRICO', 5, 5, 0, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(8, 2, 'qtd_caracteres',        'Qtd Caracteres', '8.00', 'NUMÉRICO', 5, 5, 0, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(9, 2, 'precisao_numero',       'Precisção Número', '9.00', 'NUMÉRICO', 5, 5, 0, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(10, 2, 'formato_data',         'Formato Data', '10.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(11, 2, 'id_dicionario_lov',    'Tabela Ref.', '11.00', 'LISTA VALOR', 10, 10, NULL, NULL, 1, NULL, 'SIM', 'NÃO', NULL),
(12, 2, 'valor_enum',           'Valor Enum', '12.00', 'TEXTO LONGO', 100, 1000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
(13, 2, 'fg_obrigatorio',       'Obrigatório?', '13.00', 'ENUM', 10, 10, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(14, 2, 'fg_auto_incremento',   'Auto Incremento?', '14.00', 'ENUM', 10, 10, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(15, 2, 'hint_campo',           'Hint Campo', '15.00', 'TEXTO LONGO', 100, 1000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', NULL),
/*snb_dicionario*/
(16, 1, 'id',                   'Id', '1.00', 'NUMÉRICO', 4, 4, 0, NULL, NULL, NULL, 'SIM', 'SIM', NULL),
(17, 1, 'nome_tabela',          'Nome Tabela', '2.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(18, 1, 'campo_id',             'Campo Id', '3.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(19, 1, 'campo_descricao',      'Campo Descrição', '4.00', 'TEXTO', 30, 60, NULL, NULL, NULL, NULL, 'SIM', 'NÃO', NULL),
(20, 1, 'condicao_filtro',      'Condição', '5.00', 'TEXTO LONGO', 100, 4000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', 'Condição para o filtro da LOV (Where)'),
(21, 1, 'ordem',                'Ordem', '6.00', 'TEXTO LONGO', 100, 4000, NULL, NULL, NULL, NULL, 'NÃO', 'NÃO', 'Ordem de apresentação da LOV (Order by)'),
/*snb_autorizacao*/
(22, 3, 'id',                   'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(23, 3, 'id_menu',              'Id Menu', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 8, '', 'SIM', 'NÃO', NULL),
(24, 3, 'id_usuario',           'Id Usuario', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 15, '', 'SIM', 'NÃO', NULL),
/*snb_cidade*/
(25, 4, 'id',                   'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(26, 4, 'nome',                 'Nome', '2.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(27, 4, 'ddd',                  'DDD', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(28, 4, 'id_unid_fed',          'Unidade Fed', '4.00', 'LISTA VALOR', 2, 2, NULL, NULL, 14, '', 'SIM', 'NÃO', NULL),
(29, 4, 'cod_ibge',             'Cod IBGE', '5.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
/*snb_end_eletronico*/
(30, 5, 'id',                   'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(31, 5, 'id_filial',            'Filial', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 7, '', 'SIM', 'NÃO', NULL),
(32, 5, 'endereco',             'Endereço', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(33, 5, 'fg_principal',         'Endereço Principal?', '4.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*snb_endereco*/
(34, 6, 'id',                   'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(35, 6, 'id_filial',            'Filial', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 7, '', 'SIM', 'NÃO', NULL),
(36, 6, 'id_tp_logradouro',     'Tipo Logradouro', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 12, '', 'SIM', 'NÃO', NULL),
(37, 6, 'logradouro',           'Logradouro', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(38, 6, 'numero',               'Número', '5.00', 'TEXTO', 5, 5, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(39, 6, 'complemento',          'Complemento', '6.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(40, 6, 'id_cidade',            'Cidade', '7.00', 'LISTA VALOR', 10, 10, 0, NULL, 4, '', 'SIM', 'NÃO', NULL),
(41, 6, 'bairro',               'Bairro', '8.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(43, 6, 'cep',                  'CEP', '9.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(42, 6, 'fg_principal',         'Endereço Principal?', '10.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*snb_filial*/
(44, 7, 'id',                   'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(45, 7, 'id_pessoa',            'Pessoa', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 10, '', 'SIM', 'NÃO', NULL),
(46, 7, 'filial',               'Filial', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(47, 7, 'digito',               'Digito', '4.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(48, 7, 'nome_filial',          'Nome Filial', '5.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*snb_menu*/
(173, 8, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(174, 8, 'cod_aplicacao',       'Código Aplicação', '2.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(175, 8, 'id_dicionario_tabela','Dicionario Tabela', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 1, '', 'NÃO', 'NÃO', NULL),
(176, 8, 'id_dicionario_view',  'Dicionário View', '4.00', 'LISTA VALOR', 10, 10, 0, NULL, 1, '', 'NÃO', 'NÃO', NULL),
(177, 8, 'nm_menu',             'Nome Menu', '5.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(178, 8, 'nm_pagina',           'Nome Página PHP', '6.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(179, 8, 'fg_ativo',            'Ativo', '7.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(180, 8, 'sequencia',           'Sequencia', '8.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(181, 8, 'id_menu_proximo',     'Próximo Menu', '9.00', 'LISTA VALOR', 10, 10, 0, NULL, 8, '', 'NÃO', 'NÃO', NULL),
(182, 8, 'id_modulo',           'Módulo', '10.00', 'LISTA VALOR', 10, 10, 0, NULL, 9, '', 'NÃO', 'NÃO', NULL),
/*snb_modulo*/
(59, 9, 'id',                   'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(60, 9, 'descricao',            'Descrição', '2.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(61, 9, 'img_icone',            'Ícone', '3.00', 'ARQUIVO', 1000, 1000, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
/*snb_pessoa*/
(62, 10, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(63, 10, 'cnpj_cpf',            'CNPJ/CPF', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(64, 10, 'id_tp_pessoa',        'Tipo Pessoa', '3.00', 'LISTA VALOR', 1, 1, NULL, NULL, 13, 'J,F', 'SIM', 'NÃO', NULL),
(65, 10, 'nome',                'Nome', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(66, 10, 'dt_nascimento',       'Data Nascimento', '5.00', 'DATA', 14, 14, NULL, '%d/%m/%Y', NULL, '', 'NÃO', 'NÃO', NULL),
(67, 10, 'sexo',                'Sexo', '6.00', 'ENUM', 9, 9, NULL, NULL, NULL, 'Feminino,Masculino', 'NÃO', 'NÃO', NULL),
(68, 10, 'img_foto',            'Foto', '7.00', 'ARQUIVO', 1000, 1000, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(69, 10, 'rg',                  'RG', '8.00', 'TEXTO', 20, 20, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
/*snb_telefone*/
(70, 11, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(71, 11, 'id_filial',           'Filial', '2.00', 'LISTA VALOR', 10, 10, 0, NULL, 7, '', 'SIM', 'NÃO', NULL),
(72, 11, 'ddd',                 'DDD', '3.00', 'NUMÉRICO', 2, 2, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(73, 11, 'telefone',            'Telefone', '4.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(74, 11, 'nm_contato',          'Nome Contato', '5.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(75, 11, 'fg_principal',        'Telefone Principal?', '6.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*snb_tp_logradouro*/
(76, 12, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(77, 12, 'nome',                'Nome', '2.00', 'TEXTO', 30, 30, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(78, 12, 'sigla',               'Sigla', '3.00', 'TEXTO', 5, 5, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*snb_tp_pessoa*/
(79, 13, 'id',                  'Id', '1.00', 'ENUM', 1, 1, NULL, NULL, NULL, 'J,F', 'SIM', 'NÃO', NULL),
(80, 13, 'descricao',           'Descrição', '2.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*snb_unid_fed*/
(81, 14, 'id',                  'Id', '1.00', 'TEXTO', 2, 2, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(82, 14, 'nome',                'Nome', '2.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*snb_usuario*/
(83, 15, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'SIM', NULL),
(84, 15, 'usuario',             'Usuário', '2.00', 'TEXTO', 40, 40, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(85, 15, 'id_pessoa',           'Pessoa', '3.00', 'LISTA VALOR', 10, 10, 0, NULL, 10, '', 'SIM', 'NÃO', NULL),
(87, 15, 'senha',               'Senha', '4.00', 'SENHA', 1000, 1000, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(86, 15, 'fg_ativo',            'Ativo?', '5.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*vsnb_autorizacao*/
(88, 16, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(89, 16, 'id_menu',             'Menu', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(90, 16, 'cod_aplicacao',       'Código Aplicação', '3.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(91, 16, 'nm_menu',             'Nome Menu', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(92, 16, 'id_usuario',          'Id Usuário', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(93, 16, 'usuario',             'Usuário', '6.00', 'TEXTO', 40, 40, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(94, 16, 'nome_usuario',        'Nome Usuário', '7.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*vsnb_dicionario_detalhe*/
(95, 17, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(96, 17, 'id_dicionario',       'Dicionário', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(97, 17, 'nome_tabela',         'Nome Tabela', '3.00', 'TEXTO', 60, 60, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(98, 17, 'nome_coluna',         'Nome Coluna', '4.00', 'TEXTO', 60, 60, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(99, 17, 'ordem',               'Ordem', '5.00', 'NUMÉRICO', 7, 7, 2, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(100, 17, 'tipo_dado',          'Tipo Dado', '6.00', 'ENUM', 11, 11, NULL, NULL, NULL, 'SENHA,NUMÉRICO,DATA,DATA HORA,HORA,ARQUIVO,TEXTO,TEXTO LONGO,LISTA VALOR,ENUM', 'SIM', 'NÃO', NULL),
(101, 17, 'fg_obrigatorio',     'Obrigatório?', '7.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(102, 17, 'fg_auto_incremento', 'Auto Incremento?', '8.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*vsnb_end_eletronico*/
(103, 18, 'id',                 'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(104, 18, 'id_filial',          'Filial', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(105, 18, 'nome_pessoa',        'Nome Pessoa', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(106, 18, 'nome_filial',        'Nome Filial', '4.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(107, 18, 'endereco',           'Endereço', '5.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(108, 18, 'fg_principal',       'Endereço Principal?', '6.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*vsnb_endereco*/
(109, 19, 'id',                 'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(110, 19, 'id_filial',          'Filial', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(111, 19, 'nome_pessoa',        'Nome Pessoa', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(112, 19, 'nome_filial',        'Nome Filial', '4.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(113, 19, 'id_tp_logradouro',   'Id Tipo Logradouro', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(114, 19, 'tp_logradouro',      'Tp Logradouro', '6.00', 'TEXTO', 30, 30, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(115, 19, 'logradouro',         'Logradouro', '7.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(116, 19, 'numero',             'Número', '8.00', 'TEXTO', 5, 5, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(117, 19, 'complemento',        'Complemento', '9.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(118, 19, 'id_cidade',          'Id Cidade', '10.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(119, 19, 'nome_cidade',        'Nome Cidade', '11.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(120, 19, 'bairro',             'Bairro', '12.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(121, 19, 'cep',                'CEP', '13.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(122, 19, 'fg_principal',       'Endereço Principal?', '14.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*vsnb_filial*/
(123, 20, 'id',                 'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(124, 20, 'id_pessoa',          'Id Pessoa', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(125, 20, 'nome',               'Nome', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(126, 20, 'id_tp_pessoa',       'Tipo Pessoa', '4.00', 'ENUM', 1, 1, NULL, NULL, NULL, 'J,F', 'SIM', 'NÃO', NULL),
(127, 20, 'cnpj_cpf',           'CNPJ/CPF', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(128, 20, 'filial',             'Filial', '6.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(129, 20, 'digito',             'Dígito', '7.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(130, 20, 'rg',                 'RG', '8.00', 'TEXTO', 20, 20, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(131, 20, 'dt_nascimento',      'Data Nascimento', '9.00', 'DATA', 14, 14, NULL, '%d/%m/%Y', NULL, '', 'NÃO', 'NÃO', NULL),
(132, 20, 'sexo',               'Sexo', '10.00', 'ENUM', 9, 9, NULL, NULL, NULL, 'Feminino,Masculino', 'NÃO', 'NÃO', NULL),
(133, 20, 'nome_filial',        'Nome Filial', '11.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*vsnb_menu*/
(183, 21, 'id',                  'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(184, 21, 'cod_aplicacao',       'Código Aplicação', '2.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(185, 21, 'id_dicionario_tabela','Dicionário tabela', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(186, 21, 'nm_tabela',           'Nome Tabela', '4.00', 'TEXTO', 60, 60, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(187, 21, 'id_dicionario_view',  'Dicionário View', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(188, 21, 'nm_view',             'Nome view', '6.00', 'TEXTO', 60, 60, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(189, 21, 'nm_menu',             'Nome Aplicação', '7.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(190, 21, 'nm_pagina',           'Nome Página', '8.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(191, 21, 'fg_ativo',            'Ativo?', '9.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
(192, 21, 'sequencia',           'Sequencia', '10.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(193, 21, 'id_menu_proximo',     'Próximo Menu', '11.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(194, 21, 'id_modulo',           'Módulo', '12.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'NÃO', 'NÃO', NULL),
(195, 21, 'nome_modulo',         'Nome Módulo', '13.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
/*vsnb_telefone*/
(145, 22, 'id',                 'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(146, 22, 'id_filial',          'Filial', '2.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(147, 22, 'nome_pessoa',        'Nome Pessoa', '3.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(148, 22, 'nome_filial',        'Nome Filial', '4.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(149, 22, 'ddd',                'DDD', '5.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(150, 22, 'telefone',           'Telefone', '6.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(151, 22, 'nm_contato',         'Nome Contato', '7.00', 'TEXTO', 45, 45, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(152, 22, 'fg_principal',       'Telefone Principal?', '8.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL),
/*vsnb_usuario*/
(153, 23, 'id',                 'Id', '1.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(154, 23, 'usuario',            'Usuário', '2.00', 'TEXTO', 40, 40, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(155, 23, 'id_pessoa',          'Pessoa', '3.00', 'NUMÉRICO', 10, 10, 0, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(156, 23, 'nome_usuario',       'Nome Usuário', '4.00', 'TEXTO', 100, 100, NULL, NULL, NULL, '', 'SIM', 'NÃO', NULL),
(157, 23, 'fg_ativo',           'Ativo?', '5.00', 'ENUM', 3, 3, NULL, NULL, NULL, 'SIM,NÃO', 'SIM', 'NÃO', NULL);

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

alter table snb_autorizacao
  add constraint fksnb_autorizacao_snb_menu foreign key (id_menu) references snb_menu (id) on delete no action on update no action,
  add constraint fksnb_autorizacao_snb_usuario foreign key (id_usuario) references snb_usuario (id) on delete no action on update no action;

alter table snb_cidade
  add constraint fksnb_cidade_snb_unid_fed foreign key (id_unid_fed) references snb_unid_fed (id) on delete no action on update no action;

alter table snb_dicionario_detalhe
  add constraint fksnb_dicion_det_snb_dicion foreign key (id_dicionario) references snb_dicionario (id) on delete no action on update no action;

alter table snb_endereco
  add constraint fksnb_endereco_snb_cidade foreign key (id_cidade) references snb_cidade (id) on delete no action on update no action,
  add constraint fksnb_endereco_snb_filial foreign key (id_filial) references snb_filial (id) on delete no action on update no action,
  add constraint fksnb_endereco_snb_tp_logradouro foreign key (id_tp_logradouro) references snb_tp_logradouro (id) on delete no action on update no action;

alter table snb_end_eletronico
  add constraint fksnb_end_eletronico_snb_filial foreign key (id_filial) references snb_filial (id) on delete no action on update no action;

alter table snb_filial
  add constraint fksnb_filial_snb_pessoa foreign key (id_pessoa) references snb_pessoa (id) on delete no action on update no action;

alter table snb_menu
  add constraint fksnb_menu_snb_dicionario_view foreign key (id_dicionario_view) references snb_dicionario (id) on delete no action on update no action,
  add constraint fksnb_menu_snb_dicionario_tab foreign key (id_dicionario_tabela) references snb_dicionario (id) on delete no action on update no action,
  add constraint fksnb_menu_snb_menu foreign key (id_menu_proximo) references snb_menu (id) on delete no action on update no action,
  add constraint fksnb_menu_snb_modulo foreign key (id_modulo) references snb_modulo (id) on delete no action on update no action;

alter table snb_pessoa
  add constraint fksnb_pessoa_snb_tp_pessoa foreign key (id_tp_pessoa) references snb_tp_pessoa (id) on delete no action on update no action;

alter table snb_telefone
  add constraint fksnb_telefone_snb_filial foreign key (id_filial) references snb_filial (id) on delete no action on update no action;

alter table snb_usuario
  add constraint fksnb_usuario_snb_pessoa foreign key (id_pessoa) references snb_pessoa (id) on delete no action on update no action;

-- ==================================== --
-- INSERT DICIONARIO DE OUTRAS TABELAS. --
-- ==================================== --

insert into snb_dicionario
select null id,
       lower(a.table_name) nome_tabela,
       'id' campo_id,
       '' campo_descricao,
       '' condicao_filtro,
       '' ordem
  from information_schema.tables a
 where lower(a.table_schema) = 'stanyslaul'
   and not exists (select 1
                     from snb_dicionario b
                    where b.nome_tabela = lower(a.table_name));

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
  from (select lower(a.table_name) nome_tabela,
               (select t.id from snb_dicionario t where lower(t.nome_tabela) = lower(a.table_name)) id_dicionario,
               lower(a.column_name) nome_coluna,
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
               (select t.id from snb_dicionario t where lower(t.nome_tabela) = lower(b.referenced_table_name)) id_dicionario_lov,
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
                        where bb.nome_tabela = lower(a.table_name))
           and lower(a.table_schema)    = 'stanyslaul'
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
                                  where b.nome_tabela = lower(a.nm_tabela)),
       a.id_dicionario_view   =  (select b.id
                                   from snb_dicionario b
                                  where b.nome_tabela = lower(a.nm_view));
*/

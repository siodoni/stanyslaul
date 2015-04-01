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
drop table if exists snb_dicionario_detalhe;
drop table if exists snb_dicionario;
drop table if exists snb_autorizacao;
drop table if exists snb_menu;
drop table if exists snb_modulo;
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
  id int(11) NOT null AUTO_INCREMENT,
  nome varchar(30) NOT null,
  sigla varchar(5) NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (nome)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

CREATE TABLE IF NOT EXISTS snb_tp_pessoa (
  id enum('J','F') COLLATE latin1_general_cs NOT null,
  descricao varchar(45) COLLATE latin1_general_cs NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (descricao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

CREATE TABLE IF NOT EXISTS snb_unid_fed (
  id varchar(2) NOT null,
  nome varchar(100) NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (nome)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS snb_cidade (
  id int(11) NOT null AUTO_INCREMENT,
  nome varchar(100) NOT null,
  ddd int(2) NOT null,
  id_unid_fed varchar(2) NOT null,
  cod_ibge varchar(45) DEFAULT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (nome),
  KEY fksnb_cidade_snb_unid_fed (id_unid_fed)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS snb_pessoa (
  id int(11) NOT null AUTO_INCREMENT,
  cnpj_cpf int(11) NOT null,
  id_tp_pessoa enum('J','F') NOT null,
  nome varchar(100) NOT null,
  dt_nascimento date DEFAULT null,
  sexo enum('Feminino','Masculino') DEFAULT null,
  img_foto varchar(1000) DEFAULT null,
  rg varchar(20) DEFAULT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (cnpj_cpf,id_tp_pessoa),
  KEY fksnb_pessoa_snb_tp_pessoa (id_tp_pessoa)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS snb_usuario (
  id int(11) NOT null AUTO_INCREMENT,
  usuario varchar(40) NOT null,
  id_pessoa int(11) NOT null,
  fg_ativo enum('SIM','NÃO') NOT null,
  senha varchar(1000) NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (usuario),
  KEY fksnb_usuario_snb_pessoa (id_pessoa)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS snb_filial (
  id int(11) NOT null AUTO_INCREMENT,
  id_pessoa int(11) NOT null,
  filial int(4) NOT null,
  digito int(2) NOT null,
  nome_filial varchar(45) NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (id_pessoa,filial),
  KEY fksnb_filial_snb_pessoa (id_pessoa)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_endereco (
  id int(11) NOT null AUTO_INCREMENT,
  id_filial int(11) NOT null,
  id_tp_logradouro int(11) NOT null,
  logradouro varchar(100) NOT null,
  numero varchar(5) NOT null,
  complemento varchar(45) DEFAULT null,
  id_cidade int(11) NOT null,
  bairro varchar(100) NOT null,
  fg_principal enum('SIM','NÃO') NOT null,
  cep int(8) DEFAULT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (id,id_filial),
  KEY fksnb_endereco_snb_filial (id_filial),
  KEY fksnb_endereco_snb_tp_logradouro (id_tp_logradouro),
  KEY fksnb_endereco_snb_cidade (id_cidade)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_telefone (
  id int(11) NOT null AUTO_INCREMENT,
  id_filial int(11) NOT null,
  ddd int(2) NOT null,
  telefone int(9) NOT null,
  nm_contato varchar(45) NOT null,
  fg_principal enum('SIM','NÃO') NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (id,id_filial),
  KEY fksnb_telefone_snb_filial (id_filial)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_end_eletronico (
  id int(11) NOT null AUTO_INCREMENT,
  id_filial int(11) NOT null,
  endereco varchar(100) NOT null,
  fg_principal enum('SIM','NÃO') NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (id,id_filial),
  KEY fksnb_end_eletronico_snb_filial (id_filial)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS snb_modulo (
  id int(11) NOT null  AUTO_INCREMENT,
  descricao varchar(100) NOT null,
  img_icone varchar(1000),
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (descricao)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS snb_menu (
  id int(11) NOT null AUTO_INCREMENT,
  cod_aplicacao varchar(45) NOT null,
  nm_tabela varchar(100) DEFAULT null,
  nm_view varchar(100) DEFAULT null,
  nm_menu varchar(100) NOT null,
  nm_pagina varchar(100) DEFAULT null,
  fg_ativo enum('SIM','NÃO') NOT null,
  sequencia int(4) NOT null,
  id_menu_proximo int(11) DEFAULT null,
  id_modulo int(11) DEFAULT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (nm_tabela,cod_aplicacao),
  KEY fksnb_menu_snb_menu (id_menu_proximo),
  KEY fksnb_menu_snb_modulo (id_modulo)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS snb_autorizacao (
  id int(11) NOT null AUTO_INCREMENT,
  id_menu int(11) NOT null,
  id_usuario int(11) NOT null,
  PRIMARY KEY (id),
  UNIQUE INDEX `UNIQUE` (id_menu,id_usuario),
  KEY fksnb_autorizacao_snb_usuario (id_usuario)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

create table if not exists snb_dicionario (
  id int(11) not null AUTO_INCREMENT,
  nome_tabela varchar(60) not null,
  campo_id varchar(60) not null,
  campo_descricao varchar(60) not null,
  condicao_filtro longtext,
  ordem longtext,
  PRIMARY KEY (id)
) ENGINE=InnoDB  default CHARSET=latin1 AUTO_INCREMENT=3;

create table if not exists snb_dicionario_detalhe (
  id int(10) not null AUTO_INCREMENT,
  id_dicionario int(10) not null,
  nome_coluna varchar(60) not null,
  titulo_coluna varchar(60) not null,
  ordem numeric(5,2) not null,
  tipo_dado enum('SENHA','NUMÉRICO','DATA','ARQUIVO','TEXTO','TEXTO LONGO','LISTA VALOR','ENUM') not null,
  tamanho_campo int(3) not null default 10,
  qtd_caracteres int(3) not null default 10,
  precisao_numero int(1) default null,
  formato_data varchar(40) default null,
  id_dicionario_lov int(10) default null,
  valor_enum longtext default null,
  fg_obrigatorio enum('SIM','NÃO') not null default 'NÃO',
  fg_auto_incremento enum('SIM','NÃO') not null default 'NÃO',
  hint_campo longtext,
  PRIMARY KEY (id)
) ENGINE=InnoDB  default CHARSET=latin1 AUTO_INCREMENT=20;

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
       a.nm_tabela,
       a.nm_view,
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

INSERT INTO snb_tp_pessoa (id, descricao) VALUES
('F', 'FÍSICA'),
('J', 'JURÍDICA');

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

INSERT INTO snb_cidade (id, nome, ddd, id_unid_fed, cod_ibge) VALUES
(1, 'SERRANA', 16, 'SP', null),
(2, 'RIBEIRÃO PRETO', 16, 'SP', null);

INSERT INTO snb_pessoa (id, cnpj_cpf, id_tp_pessoa, nome, dt_nascimento, sexo, img_foto, rg) VALUES
(1, 999999999, 'F', 'ADMINISTRADOR DO SISTEMA', curdate(), 'Masculino', null, null);

INSERT INTO snb_usuario (id, usuario, id_pessoa, fg_ativo, senha) VALUES
(1, 'admin', 1, 'SIM', 'd033e22ae348aeb5660fc2140aec35850c4da997');

INSERT INTO snb_modulo (id, descricao, img_icone) VALUES
(1, 'Cadastro Básico', ''),
(2, 'Cadastro Clientes', ''),
(3, 'Administração do Sistema', '');

INSERT INTO snb_menu (id, cod_aplicacao, nm_tabela, nm_view, nm_menu, nm_pagina, fg_ativo, sequencia, id_menu_proximo, id_modulo) VALUES
(1, 'BAS001', 'snb_unid_fed', null, 'UF', null, 'SIM', 1, null, 1),
(2, 'BAS002', 'snb_cidade', null, 'Cidade', null, 'SIM', 2, null, 1),
(3, 'BAS003', 'snb_tp_logradouro', null, 'Tipo de Logradouro', null, 'SIM', 3, null, 1),
(4, 'PES001', 'snb_tp_pessoa', null, 'Tipo de Pessoa', null, 'SIM', 4, null, 1),
(5, 'PES002', 'snb_pessoa', null, 'Pessoa', null, 'SIM', 5, 6, 2),
(6, 'PES003', 'snb_filial', 'vsnb_filial', 'Filial', null, 'SIM', 6, 7, 2),
(7, 'PES004', 'snb_endereco', 'vsnb_endereco', 'Endereço', null, 'SIM', 7, 8, 2),
(8, 'PES005', 'snb_telefone', 'vsnb_telefone', 'Telefone', null, 'SIM', 8, 9, 2),
(9, 'PES006', 'snb_end_eletronico', 'vsnb_end_eletronico', 'Endereço Eletrônico', null, 'SIM', 9, null, 2),
(10, 'SIS001', 'snb_menu', 'vsnb_menu', 'Menu', null, 'SIM', 9, null, 3),
(11, 'SIS002', 'snb_usuario', 'vsnb_usuario', 'Usuário', null, 'SIM', 10, null, 3),
(12, 'SIS003', 'snb_autorizacao', 'vsnb_autorizacao', 'Autorização ao Sistema', null, 'SIM', 11, null, 3),
(13, 'SIS004', 'snb_modulo', null, 'Módulo Sistema', null, 'SIM', 12, null, 3),
(14, 'SIS005', 'snb_dicionario', null, 'Dicionário', null, 'SIM', 13, null, 3),
(15, 'SIS006', 'snb_dicionario_detalhe', 'vsnb_dicionario_detalhe', 'Dicionário Detalhe', null, 'SIM', 14, null, 3);

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

-- ============ --
-- FOREIGN KEYS --
-- ============ --

ALTER TABLE snb_autorizacao
  ADD CONSTRAINT fksnb_autorizacao_snb_menu FOREIGN KEY (id_menu) REFERENCES snb_menu (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_autorizacao_snb_usuario FOREIGN KEY (id_usuario) REFERENCES snb_usuario (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_cidade
  ADD CONSTRAINT fksnb_cidade_snb_unid_fed FOREIGN KEY (id_unid_fed) REFERENCES snb_unid_fed (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_endereco
  ADD CONSTRAINT fksnb_endereco_snb_cidade FOREIGN KEY (id_cidade) REFERENCES snb_cidade (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_endereco_snb_filial FOREIGN KEY (id_filial) REFERENCES snb_filial (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_endereco_snb_tp_logradouro FOREIGN KEY (id_tp_logradouro) REFERENCES snb_tp_logradouro (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_end_eletronico
  ADD CONSTRAINT fksnb_end_eletronico_snb_filial FOREIGN KEY (id_filial) REFERENCES snb_filial (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_filial
  ADD CONSTRAINT fksnb_filial_snb_pessoa FOREIGN KEY (id_pessoa) REFERENCES snb_pessoa (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_menu
  ADD CONSTRAINT fksnb_menu_snb_menu FOREIGN KEY (id_menu_proximo) REFERENCES snb_menu (id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT fksnb_menu_snb_modulo FOREIGN KEY (id_modulo) REFERENCES snb_modulo (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_pessoa
  ADD CONSTRAINT fksnb_pessoa_snb_tp_pessoa FOREIGN KEY (id_tp_pessoa) REFERENCES snb_tp_pessoa (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_telefone
  ADD CONSTRAINT fksnb_telefone_snb_filial FOREIGN KEY (id_filial) REFERENCES snb_filial (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_usuario
  ADD CONSTRAINT fksnb_usuario_snb_pessoa FOREIGN KEY (id_pessoa) REFERENCES snb_pessoa (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE snb_dicionario_detalhe
  ADD CONSTRAINT fksnb_dicion_det_snb_dicion FOREIGN KEY (id_dicionario) REFERENCES snb_dicionario (id) ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ========== --
-- DICIONARIO --
-- ========== --

insert into snb_dicionario
(id,nome_tabela,campo_id,campo_descricao)
values
(1,'SNB_DICIONARIO','ID','NOME_TABELA'),
(2,'SNB_DICIONARIO_DETALHE','ID','NOME_COLUNA');

insert into snb_dicionario_detalhe
(id,
  id_dicionario,
   nome_coluna,
    titulo_coluna,
     tipo_dado,
      fg_obrigatorio,
       ordem,
        precisao_numero,
         tamanho_campo,
          qtd_caracteres,
           valor_enum,
            id_dicionario_lov,
             hint_campo,
              fg_auto_incremento,
               formato_data)
values
(1,
  2,
   'ID',
    'Id',
     'NUMÉRICO',
      'SIM',
       1,
        null,
         5,
          5,
           null,
            null,
             null,
              'SIM',
               null),
(2,
  2,
   'ID_DICIONARIO',
    'Id Dicionário',
     'LISTA VALOR',
      'SIM',
       2,
        null,
         5,
          5,
           null,
            1,
             null,
              'NÃO',
               null),
(3,
  2,
   'NOME_COLUNA',
    'Nome Coluna',
     'TEXTO',
      'SIM',
       3,
        null,
         30,
          50,
           null,
            null,
             null,
              'NÃO',
               null),
(4,
  2,
   'TITULO_COLUNA',
    'Título Coluna',
     'TEXTO',
      'SIM',
       4,
        null,
         30,
          60,
           null,
            null,
             null,
              'NÃO',
               null),
(5,
  2,
   'ORDEM',
    'Ordem',
     'NUMÉRICO',
      'SIM',
       5,
        2,
         5,
          5,
           null,
            null,
             null,
              'NÃO',
               null),
(6,
  2,
   'TIPO_DADO',
    'Tipo Dado',
     'ENUM',
      'SIM',
       6,
        null,
         10,
          10,
           'SENHA,NUMÉRICO,DATA,ARQUIVO,TEXTO,TEXTO LONGO,LISTA VALOR,ENUM',
            null,
             null,
              'NÃO',
               null),
(7,
  2,
   'TAMANHO_CAMPO',
    'Tamanho Campo',
     'NUMÉRICO',
      'SIM',
       7,
        0,
         5,
          5,
           null,
            null,
             null,
              'NÃO',
               null),
(8,
  2,
   'QTD_CARACTERES',
    'Qtd Caracteres',
     'NUMÉRICO',
      'SIM',
       8,
        0,
         5,
          5,
           null,
            null,
             null,
              'NÃO',
               null),
(9,
  2,
   'PRECISAO_NUMERO',
    'Precisão Número',
     'NUMÉRICO',
      'NÃO',
       9,
        0,
         5,
          5,
           null,
            null,
             null,
              'NÃO',
               null),
(10,
  2,
   'FORMATO_DATA',
    'Formato Data',
     'TEXTO',
      'NÃO',
       10,
        null,
         30,
          60,
           null,
            null,
             null,
              'NÃO',
               null),
(11,
  2,
   'ID_DICIONARIO_LOV',
    'Tabela Ref.',
     'LISTA VALOR',
      'SIM',
       11,
        null,
         10,
          10,
           null,
            1,
             null,
              'NÃO',
               null),
(12,
  2,
   'VALOR_ENUM',
    'Valor Enum',
     'TEXTO LONGO',
      'NÃO',
       12,
        null,
         100,
          1000,
           null,
            null,
             null,
              'NÃO',
               null),
(13,
  2,
   'FG_OBRIGATORIO',
    'Obrigatório?',
     'ENUM',
      'SIM',
       13,
        null,
         10,
          10,
           'SIM,NÃO',
            null,
             null,
              'NÃO',
               null),
(14,
  2,
   'FG_AUTO_INCREMENTO',
    'Auto Incremento?',
     'ENUM',
      'SIM',
       14,
        null,
         10,
          10,
           'SIM,NÃO',
            null,
             null,
              'NÃO',
               null),
(15,
  2,
   'HINT_CAMPO',
    'Hint Campo',
     'TEXTO LONGO',
      'NÃO',
       15,
        null,
         100,
          1000,
           null,
            null,
             null,
              'NÃO',
               null),
(16,
  1,
   'ID',
    'Id',
     'NUMÉRICO',
      'SIM',
       1,
        0,
         4,
          4,
           null,
            null,
             null,
              'SIM',
               null),
(17,
  1,
   'NOME_TABELA',
    'Nome Tabela',
     'TEXTO',
      'SIM',
       2,
        null,
         30,
          60,
           null,
            null,
             null,
              'NÃO',
               null),
(18,
  1,
   'CAMPO_ID',
    'Campo Id',
     'TEXTO',
      'SIM',
       3,
        null,
         30,
          60,
           null,
            null,
             null,
              'NÃO',
               null),
(19,
  1,
   'CAMPO_DESCRICAO',
    'Campo Descrição',
     'TEXTO',
      'SIM',
       4,
        null,
         30,
          60,
           null,
            null,
             null,
              'NÃO',
               null),
(20,
  1,
   'CONDICAO_FILTRO',
    'Condição',
     'TEXTO LONGO',
      'NÃO',
       5,
        null,
         100,
          4000,
           null,
            null,
             'Condição para o filtro da LOV (Where)',
              'NÃO',
               null),
(21,
  1,
   'ORDEM',
    'Ordem',
     'TEXTO LONGO',
      'NÃO',
       6,
        null,
         100,
          4000,
           null,
            null,
             'Ordem de apresentação da LOV (Order by)',
              'NÃO',
               null);

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
                     from stanyslaul.snb_dicionario b
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
               if(a.data_type='date','%d/%m/%Y',if(a.data_type='time','%k:%i',if(a.data_type='datetime','%d/%m/%Y %H:%i',null))) formato_data,
               (select t.id from stanyslaul.snb_dicionario t where upper(t.nome_tabela) = upper(b.referenced_table_name)) id_dicionario_lov,
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
                         from stanyslaul.snb_dicionario bb
                        where bb.nome_tabela = upper(a.table_name))
           and upper(a.table_schema)    = 'STANYSLAUL'
         order by a.ordinal_position) c
 where not exists (select 1
                     from stanyslaul.snb_dicionario_detalhe bb
                    where bb.id_dicionario = c.id_dicionario
                      and bb.nome_coluna   = c.nome_coluna)
 order by c.id_dicionario, c.ordem;
CREATE TABLE IF NOT EXISTS `snb_dicionario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_tabela` varchar(60) NOT NULL,
  `campo_id` varchar(60) NOT NULL,
  `campo_descricao` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_tabela` (`nome_tabela`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `snb_dicionario` (`id`, `nome_tabela`, `campo_id`, `campo_descricao`) VALUES
(1, 'SNB_DICIONARIO', 'ID', 'NOME_TABELA');

CREATE TABLE IF NOT EXISTS `snb_dicionario_detalhe` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_dicionario` int(10) NOT NULL,
  `nome_coluna` varchar(60) NOT NULL,
  `tipo_dado` enum('SENHA','NUMÉRICO','DATA','ARQUIVO','TEXTO','TEXTO LONGO','LISTA VALOR','ENUM') NOT NULL,
  `fg_obrigatorio` enum('SIM','NÃO') NOT NULL,
  `ordem` int(5) NOT NULL,
  `precisao_numero` int(1) DEFAULT NULL,
  `tamanho_campo` int(3) NOT NULL,
  `qtd_caracteres` int(3) NOT NULL,
  `valor_enum` longtext,
  `id_dicionario_lov` int(10) DEFAULT NULL,
  `hint_campo` longtext,
  `fg_auto_incremento` enum('SIM','NÃO') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `snb_dicionario_detalhe` (`id`, `id_dicionario`, `nome_coluna`, `tipo_dado`, `fg_obrigatorio`, `ordem`, `precisao_numero`, `tamanho_campo`, `qtd_caracteres`, `valor_enum`, `id_dicionario_lov`, `hint_campo`, `fg_auto_incremento`) VALUES
(1, 1, 'ID', 'NUMÉRICO', 'SIM', 1, 0, 4, 4, '', NULL, NULL, 'SIM'),
(2, 1, 'NOME_TABELA', 'TEXTO', 'SIM', 2, NULL, 30, 50, NULL, NULL, NULL, 'NÃO'),
(3, 1, 'CAMPO_ID', 'TEXTO', 'SIM', 3, NULL, 30, 50, NULL, NULL, NULL, 'NÃO'),
(4, 1, 'CAMPO_DESCRICAO', 'TEXTO', 'SIM', 4, NULL, 30, 50, NULL, NULL, NULL, 'NÃO');
<?php

class Constantes {

    //Conexão Base Dados
    const HOST = 'localhost';
    const DBNAME = 'stanyslaul';
    const USER = 'root';
    const PASSWORD = 'vertrigo';

    //Estrutura
    const TITLE = 'Stanyslaul';

    //Formato Data
    const DATE_FORMAT = '%d/%m/%Y';
    const DATETIME_FORMAT = '%d/%m/%Y %H:%i';
    const TIME_FORMAT = '%k:%i';

    //Upload
    const FILE_SIZE = 204800; //200Kb
    const FILE_FOLDER = "upload/";
    const FILE_EXTENSION = "png,jpg";

    //Login
    const TABLE_USER = 'snb_usuario';
    const COLUMN_USER = 'usuario';
    const COLUMN_PASS = 'senha';

    // Mensagens
    const GRAVAR = 'Registro gravado com sucesso.';
    const ATUALIZAR = 'Registro atualizado com sucesso.';

    //Menu
    const QUERY_MODULE = "select distinct
                                 d.id,
                                 d.descricao,
                                 d.icone
                            from #db.snb_modulo d, 
                                 #db.snb_menu a 
                           where upper(a.fg_ativa) in ('S','SIM') 
                             and d.id               = a.id_modulo
                             and exists (select 1 
                                           from #db.snb_autorizacao b 
                                          where b.id_menu    = a.id 
                                            and b.id_usuario = (select c.id 
                                                                  from #db.snb_usuario c 
                                                                 where c.usuario = ?)) 
                           order by d.id ";

    const QUERY_MENU = "select a.nm_tabela as tabela,
                               a.cod_aplicacao codigo, 
                               a.nm_menu titulo,
                               a.nm_view view,
                               a.nm_pagina pagina
                          from #db.snb_menu a 
                         where upper(a.fg_ativa) in ('S','SIM') 
                           and a.id_modulo = ? 
                           and exists (select 1 
                                         from #db.snb_autorizacao b 
                                        where b.id_menu    = a.id 
                                          and b.id_usuario = (select c.id 
                                                                from #db.snb_usuario c 
                                                               where c.usuario = ?)) 
                         order by a.sequencia ";

    const QUERY_LOGIN = "select b.nome 
                           from #db.snb_pessoa b, 
                                #db.snb_usuario a 
                          where a.usuario = ?
                            and a.senha   = ?
                            and b.id      = a.id_pessoa ";
}
<?php

class Constantes {

    //Formato Data
    const DATE_FORMAT = '%d/%m/%Y';
    const DATETIME_FORMAT = '%d/%m/%Y %H:%i';
    const TIME_FORMAT = '%k:%i';

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

    const QUERY_TABLE = "select a.ordinal_position id_coluna,
                                a.column_name coluna,
                                a.is_nullable nulo,
                                if(a.column_name='senha','password',if(a.column_name like 'img_%','file',a.data_type)) tipo_dado,
                                a.numeric_precision numerico,
                                if(a.data_type='date',14,0) + if(a.data_type='time',10,0) + if(a.data_type='datetime',20,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo,
                                if(a.data_type='date',14,0) + if(a.data_type='time',10,0) + if(a.data_type='datetime',20,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtde_caracteres,
                                replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum,
                                a.column_type enum,
                                if (a.extra = 'auto_increment',1,null) auto_increment,
                                a.column_key tipo_chave,
                                b.referenced_table_name tabela_ref
                           from information_schema.columns a 
                           left join information_schema.key_column_usage b 
                             on a.table_schema           = b.table_schema
                            and a.table_name             = b.table_name 
                            and a.column_name            = b.column_name
                            and b.referenced_table_name is not null
                          where a.table_schema           = ?
                            and a.table_name             = ? 
                          order by a.ordinal_position";

    const QUERY_PROX_MENU = "  select a.nm_view as view,
                                      a.nm_menu as titulo,
                                      a.cod_aplicacao codigo,
                                      a.id_menu_proximo prox_menu,
                                      a.nm_tabela as tabela
                                 from #db.snb_menu a
                                 where a.id = ?";
}
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
                                 d.img_icone
                            from #db.snb_modulo d, 
                                 #db.snb_menu a 
                           where upper(a.fg_ativo) in ('S','SIM') 
                             and d.id               = a.id_modulo
                             and exists (select 1 
                                           from #db.snb_autorizacao b 
                                          where b.id_menu    = a.id 
                                            and b.id_usuario = (select c.id 
                                                                  from #db.snb_usuario c 
                                                                 where c.usuario = ?)) 
                           order by d.id ";

    const QUERY_MENU = "select a.id_dicionario_tabela as tabela,
                               a.cod_aplicacao codigo, 
                               a.nm_menu titulo,
                               a.id_dicionario_view as view,
                               a.nm_pagina pagina,
                               a.id as id_menu
                          from #db.snb_menu a 
                         where upper(a.fg_ativo) in ('S','SIM') 
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
    
    const QUERY_TABLEV2 = "select a.id id_dicionario,
                                  a.nome_tabela,
                                  a.campo_id,
                                  a.campo_descricao,
                                  lower(b.nome_coluna) nome_coluna,
                                  b.titulo_coluna,
                                  b.tipo_dado,
                                  b.fg_obrigatorio,
                                  b.ordem,
                                  b.precisao_numero,
                                  b.tamanho_campo,
                                  b.qtd_caracteres,
                                  b.valor_enum,
                                  b.id_dicionario_lov,
                                  c.nome_tabela tabela_ref,
                                  c.campo_id id_tabela_ref,
                                  c.campo_descricao desc_tabela_ref,
                                  b.hint_campo,
                                  b.fg_auto_incremento,
                                  b.formato_data
                             from #db.snb_dicionario              a
                            inner join #db.snb_dicionario_detalhe b
                               on b.id_dicionario                 = a.id
                             left join #db.snb_dicionario         c
                               on c.id                            = b.id_dicionario_lov
                            where a.id                            = ?
                            order by b.ordem";

    const QUERY_PROX_MENU = "select (select b.nome_tabela from #db.snb_dicionario b where b.id = a.id_dicionario_view) as view,
                                    a.nm_menu as titulo,
                                    a.cod_aplicacao codigo,
                                    a.id_menu_proximo prox_menu,
                                    (select b.nome_tabela from #db.snb_dicionario b where b.id = a.id_dicionario_tabela) as tabela
                               from #db.snb_menu a
                              where a.id = ?";

    const QUERY_DATA_TABLE = "select a.id_dicionario_view, 
                                     a.id_dicionario_tabela,
                                     a.nm_menu as titulo, 
                                     a.cod_aplicacao codigo, 
                                     a.id_menu_proximo prox_menu 
                                from #db.snb_menu a 
                               where a.id = ?";
    
    const QUERY_NM_TAB_DICIONARIO = "select lower(a.nome_tabela) as nome_tabela
                                       from #db.snb_dicionario a 
                                      where a.id = ?";

    const QUERY_DICIONARIO_LOV = "select lower(a.campo_id) as campo_id, 
                                         lower(a.campo_descricao) as campo_descricao, 
                                         a.condicao_filtro, 
                                         lower(a.ordem) as ordem 
                                         from #db.snb_dicionario a 
                                   where a.nome_tabela = upper(?)";

    const QUERY_DICIONARIO_COL = "select lower(b.nome_coluna) nome_coluna, 
                                         b.titulo_coluna, 
                                         b.formato_data, 
                                         b.tipo_dado 
                                    from #db.snb_dicionario_detalhe b, 
                                         #db.snb_dicionario a 
                                   where a.id = ? 
                                     and b.id_dicionario = a.id";
}
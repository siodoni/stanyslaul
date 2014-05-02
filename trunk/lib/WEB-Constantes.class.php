<?php

class Contantes {

    //Conexão Base Dados
    const HOST = 'localhost';
    const DBNAME = 'newyork_db';
    const USER = 'newyork_user';
    const PASSWORD = 'insterTED7609';
    
    //Estrutura
    const TITLE = 'New York Idiomas - &Aacute;rea Administrativa';
    
    //Login
    const TABLE_USER = 'snb_usuario';
    const COLUMN_USER = 'usuario';
    const COLUMN_PASS = 'senha';
    
    // Mensagens
    const GRAVAR = 'Registro gravado com sucesso.';
    const ATUALIZAR = 'Registro atualizado com sucesso.';
    
    //Menu
    const QUERY_MENU = "select a.nm_tabela as tabela,
                               a.cod_aplicacao codigo, 
                               a.nm_menu titulo,
                               a.nm_view view
                          from #db.snb_menu a 
                         where a.fg_ativa  = 'S' 
                           and a.id_modulo = #idModulo 
                           and exists (select 1 
                                         from #db.snb_autorizacao b 
                                        where b.id_menu    = a.id 
                                          and b.id_usuario = (select c.id 
                                                                from #db.snb_usuario c 
                                                               where c.usuario = '#usuario')) 
                         order by a.sequencia ";
    const QUERY_LOGIN = "select b.nome 
                           from #db.snb_pessoa b, 
                                #db.snb_usuario a 
                          where a.usuario = '#usuario'
                            and a.senha   = '#senha'
                            and b.id      = a.id_pessoa ";

}
<?php

class Contantes {

    //Conexão Base Dados
    const HOST     = 'localhost';
    const DBNAME   = 'newyork';
    const USER     = 'root';
    const PASSWORD = 'vertrigo';
    
    //Estrutura
    const TITLE = 'Stanyslaul';  
    
    //Login
    const TABLE_USER  = 'snb_usuario';
    const COLUMN_USER = 'usuario';
    const COLUMN_PASS = 'senha';
    
    //Menu
    const QUERY_MENU = "select a.nm_tabela as tabela,
                               a.cod_aplicacao codigo, 
                               a.nm_menu titulo,
                               a.nm_view view
                          from newyork.snb_menu a 
                         where a.fg_ativa  = 'S' 
                           and a.id_modulo = #idModulo 
                           and exists (select 1 
                                         from newyork.snb_autorizacao b 
                                        where b.id_menu    = a.id 
                                          and b.id_usuario = (select c.id 
                                                                from newyork.snb_usuario c 
                                                               where c.usuario = '#usuario')) 
                         order by a.sequencia ";
}
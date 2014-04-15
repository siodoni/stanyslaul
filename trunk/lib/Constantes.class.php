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
    const TABLE_MENU        = "snb_menu";
    const COLUMN_CODE_APP   = "cod_aplicacao";
    const COLUMN_TITLE      = "nm_menu";
    const COLUMN_NAME_VIEW  = "nm_view";
    const COLUMN_NAME_TABLE = "nm_tabela";
    const WHERE_MENU        = "where a.fg_ativa = 'S' and a.id_modulo = ? ";
    const ORDER_BY_MENU     = "order by a.sequencia";
}
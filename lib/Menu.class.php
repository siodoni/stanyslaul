<?php

include_once 'lib/Estrutura.class.php';
include_once 'lib/Conexao.class.php';
include_once 'lib/Constantes.class.php';

class Menu extends Contantes {

    private $estrutura;
    private $con;

    public function __construct() {
        $this->estrutura = new Estrutura();
        $this->con = new Conexao();
        
        $this->con->connect();
        $this->buildMenu();
        $this->con->disconnect();
    }

    private function buildMenu() {
        echo "\n<html>";
        echo $this->estrutura->head();
        echo "\n<body>";
        echo $this->form();
        echo "\n</body>";
        echo "\n</html>";
    }
    
    private function form(){
        $qtde = 0;
        $button = "";
        $form = "\n<form name='form' method='post' action='list.php'>";
        $query = mysql_query(
                "select if(length(".parent::COLUMN_NAME_VIEW.")=0,".parent::COLUMN_NAME_TABLE.",".parent::COLUMN_NAME_VIEW.") as tabela, ".
                "       ".parent::COLUMN_CODE_APP." codigo, ".
                "       ".parent::COLUMN_TITLE." titulo ".
                "  from ".$this->con->getDbName().".".parent::TABLE_MENU.
                " ".parent::WHERE_MENU." ".
                " ".parent::ORDER_BY_MENU." ");
        while ($campo = mysql_fetch_array($query)) {
            $qtde++;
            $form = $form . "\n<button id='btn" . $qtde . "' type='submit' name='nomeTabela' value='" . $campo['tabela'] . "' class='menu ".substr($campo['codigo'], 0, 3)."'>" . $campo['titulo'] . "</button>";
            $button = $button . "\n$('#btn" . $qtde . "').puibutton();";
        }
        $form = $form . "\n</form>" . $this->script($button);
        return $form;
    }
    
    private function script($button){
        return "\n<script type='text/javascript'>"
             . "\n$(function() {"
             . $button
             . "\n});"
             . "\n</script>";
    }
}

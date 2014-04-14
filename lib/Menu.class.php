<?php

include_once 'lib/Estrutura.class.php';
include_once 'lib/Conexao.class.php';
include_once 'lib/Constantes.class.php';

class Menu extends Contantes {

    private $estrutura;
    private $con;
    private $button = "";
    private $panel = "";
    private $qtde = 0;
    private $nomeUsuario = "";

    public function __construct($nome = "") {
        $this->setNomeUsuario($nome);
        $this->estrutura = new Estrutura();
        $this->con = new Conexao();
        
        $this->con->connect();
        $this->buildMenu();
        $this->con->disconnect();
    }

    private function buildMenu() {
        echo "<!DOCTYPE html>";
        echo "\n<html>";
        echo $this->estrutura->head();
        echo "\n<body>";
        echo $this->form();
        echo "\n</body>";
        echo "\n</html>";
    }
    
    private function form(){
        $form = "\n<div class='st-div-main'>"
              . "\n<form name='form' method='post' action='list.php'>"
              . $this->menuBar();
        
        $sqlModulo = mysql_query("select id, descricao, icone from snb_modulo order by id");
        while ($i = mysql_fetch_array($sqlModulo)) {
            $form = $form 
                    . "\n<div id='panel".$i["id"]."' class='st-menu' title='".$i["descricao"]."'>";
            $this->panel = $this->panel . "\n$('#panel".$i["id"]."').puipanel({toggleable: true})";
            $form = $form . $this->buttons($i["id"]);
            $form = $form . "\n</div>";
        }
        
        $form = $form
              . "\n</form>"
              . "\n</div>"
              . $this->script($this->button);
        return $form;
    }
    
    private function buttons($idModulo = 0){
        $form = "";
        $query = mysql_query(
              //"select if(length(".parent::COLUMN_NAME_VIEW.")=0,".parent::COLUMN_NAME_TABLE.",".parent::COLUMN_NAME_VIEW.") as tabela, ".
                "select ".parent::COLUMN_NAME_TABLE." as tabela, ".
                "       ".parent::COLUMN_CODE_APP." codigo, ".
                "       ".parent::COLUMN_TITLE." titulo ".
                "  from ".$this->con->getDbName().".".parent::TABLE_MENU.
                " ".str_replace("?", $idModulo, parent::WHERE_MENU)." ".
                " ".parent::ORDER_BY_MENU." ");
            
        while ($j = mysql_fetch_array($query)) {
            $this->qtde++;
            $form = $form . "\n<button id='btn" . $this->qtde . "' type='submit' name='nomeTabela' value='" . $j['tabela'] . "' class='st-menu-button'>" . $j['titulo'] . "</button><br/>";
            $this->button = $this->button . "\n$('#btn" . $this->qtde . "').puibutton({icon: 'ui-icon-newwin'});";
        }
        return $form;
    }
    
    private function menuBar(){
        
        return "<ul id='toolbar'>"
             . "<li><a data-icon='ui-icon-person'>Bem vindo ".$this->nomeUsuario."</a></li>"
             . "<li><a data-icon='ui-icon-gear'>Alterar Senha</a></li>"
             . "<li><a data-icon='ui-icon-close' href='logout.php'>Sair</a></li>"  
             . "</ul>";
    }
    
    private function script(){
        return "\n<script type='text/javascript'>"
             . "\n$(function() {"
             . $this->button
             . "\n$('#toolbar').puimenubar();"
             . $this->panel
             . "\n});"
             . "\n</script>";
    }
    
    public function setNomeUsuario($nome = ""){
        $this->nomeUsuario = $nome;
    }
}

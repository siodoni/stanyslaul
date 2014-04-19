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
    private $usuario = "";
    private $nomeUsuario = "";

    public function __construct($nome = "", $usuario = "") {
        $this->setNomeUsuario($nome);
        $this->setUsuario($usuario);
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
        echo $this->dialog();
        echo $this->form();
        echo "\n</body>";
        echo "\n</html>";
    }
    
    private function form(){
        $form = "\n<div class='st-div-main'>"
              . "\n<form name='form' method='post' action='list.php'>"
              . $this->menuBar();
        
        $sqlModulo = mysql_query("select id, descricao, icone from ".$this->con->getDbName().".snb_modulo order by id");
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
        $query = mysql_query(str_replace("#usuario",$this->usuario,(
                             str_replace("#idModulo",$idModulo,
                             parent::QUERY_MENU))));
        
        while ($j = mysql_fetch_array($query)) {
            $this->qtde++;
            $form = $form . "\n<button id='btn" . $this->qtde . "' type='submit' name='nomeTabela' value='" . $j['tabela'] . "' class='st-menu-button'>" . $j['titulo'] . "</button><br/>";
            $this->button = $this->button . "\n$('#btn" . $this->qtde . "').puibutton({icon: 'ui-icon-newwin'});";
        }
        return $form;
    }
    
    private function menuBar(){
        
        return "\n<ul id='toolbar'>"
             . "\n<li><a data-icon='ui-icon-person'>Bem vindo ".$this->nomeUsuario."</a></li>"
             . "\n<li><a data-icon='ui-icon-key' onclick='$(\"#dlgChangePass\").puidialog(\"show\");'>Alterar Senha</a></li>"
             . "\n<li><a data-icon='ui-icon-close' href='logout.php'>Sair</a></li>"  
             . "\n</ul>";
    }
    
    private function script(){
        return "\n<script type='text/javascript'>"
             . "\n$(function() {"
             . $this->button
             . "\n$('#toolbar').puimenubar();"
             . $this->dialogJS()
             . "\n$('#senha1').puipassword({inline:true,promptLabel:'',weakLabel:'fraca',goodLabel:'media',strongLabel:'forte'});"
             . "\n$('#senha2').puipassword({inline:true,promptLabel:'',weakLabel:'fraca',goodLabel:'media',strongLabel:'forte'});"
             . $this->panel
             . "\n});"
             . "\n</script>";
    }
    
    private function dialogJS(){
        return "\n$('#dlgChangePass').puidialog({"
             . "\nmodal: true,"
             . "\nresizable: false,"
             . "\nwidth: 200,"
             . "\nbuttons: [{"
             . "\ntext: 'OK',"
             . "\nicon: 'ui-icon-circle-check',"
             . "\nclick: function() {"
             . "\n$('#dlgChangePass').puidialog('hide');"
             . "\n}"
             . "\n},"
             . "\n{"
             . "\ntext: 'Cancelar',"
             . "\nicon: 'ui-icon-circle-close',"
             . "\nclick: function() {"
             . "\n$('#dlgChangePass').puidialog('hide');"
             . "\n}"
             . "\n}"
             . "\n]"
             . "\n});";
    }
    
    private function dialog(){
        return "\n<div id='dlgChangePass' title='Alterar Senha' class='st-div-dlg-change-pass'>"
             . "\n<p>Informe  a nova senha: <input id='senha1' name='senha2' type='password' class='st-input-change-pass'/></p>"
             . "\n<p>Confirme a nova senha: <input id='senha2' name='senha2' type='password' class='st-input-change-pass'/></p>"
             . "\n</div>";
    }
    
    public function setNomeUsuario($nome = ""){
        $this->nomeUsuario = $nome;
    }
    
    public function setUsuario($usuario = ""){
        $this->usuario = $usuario;
    }
}

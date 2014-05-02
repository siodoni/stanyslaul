<?php

include_once 'lib/Estrutura.class.php';
include_once 'lib/Conexao.class.php';
include_once 'lib/Constantes.class.php';
include_once 'lib/Crud.class.php';

class Menu extends Contantes {

    private $estrutura;
    private $con;
    private $button = "";
    private $panel = "";
    private $qtde = 0;
    private $usuario = "";
    private $nomeUsuario = "";
    private $onload = "";

    public function __construct($nome = "", $usuario = "") {
        $this->onLoad();
        $this->setNomeUsuario($nome);
        $this->setUsuario($usuario);
        $this->estrutura = new Estrutura();
        $this->con = new Conexao();
        $this->con->connect();
        $this->buildMenu();
        $this->alteraSenhaUsuario();
        $this->con->disconnect();
    }

    private function buildMenu() {
        echo "<!DOCTYPE html>";
        echo "\n<html>";
        echo $this->estrutura->head();
        echo "\n<body $this->onload>";
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
            $form = $form . "\n<div id='panel".$i["id"]."' class='st-menu' title='".$i["descricao"]."'>";
            $this->panel = $this->panel . "\n$('#panel".$i["id"]."').puipanel({toggleable: true})";
            $form = $form . $this->buttons($i["id"]);
            $form = $form . "\n</div>";
        }
        
        $form = $form
              . "\n</form>"
              . "\n</div>"
              . "\n<div id='mensagens'></div>"
              . $this->script($this->button);
        return $form;
    }
    
    private function buttons($idModulo = 0){
        $form = "";
        $query = mysql_query(
                str_replace("#db",parent::DBNAME,(
                str_replace("#usuario",$this->usuario,(
                str_replace("#idModulo",$idModulo,parent::QUERY_MENU))))));
        
        while ($j = mysql_fetch_array($query)) {
            $this->qtde++;
            $form = $form . "\n<button id='btn" . $this->qtde . "' type='submit' name='nomeTabela' value='" . $j['tabela'] . "' class='st-menu-button'>" . $j['titulo'] . "</button><br/>";
            $this->button = $this->button . "\n$('#btn" . $this->qtde . "').puibutton({icon: 'ui-icon-newwin'});";
        }
        return $form;
    }
    
    private function menuBar(){
        
        return "\n<ul id='toolbar'>"
             . "\n<li><a><img src='res/images/topo.png' alt='".parent::TITLE."' class='st-img-logo'/></a></li>"
             . "\n<li><a data-icon='ui-icon-person'>Bem vindo ".$this->nomeUsuario."</a></li>"
             . "\n<li><a data-icon='ui-icon-key' onclick='$(\"#dlgChangePass\").puidialog(\"show\");'>Alterar Senha</a></li>"
             . "\n<li><a data-icon='ui-icon-close' href='logout.php'>Sair</a></li>"  
             . "\n</ul>";
    }
    
    private function script(){
        return "\n<script type='text/javascript'>"
             . "\n$(function() {"
             . $this->button
             . "\n$('#mensagens').puigrowl();"
             . "\n$('#toolbar').puimenubar();"
             . "\n$('#toolbar').parent().puisticky();"
             . $this->dialogJS()
             . "\n$('#senha1').puipassword({inline:true,promptLabel:'Informe a nova senha', weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});"
             . "\n$('#senha2').puipassword({inline:true,promptLabel:'Confirme a nova senha',weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});"
             . "\n$('#btnConfSenha').puibutton({icon:'ui-icon-circle-check'});"
             . "\n$('#btnCancSenha').puibutton({icon:'ui-icon-circle-close'});"
             . $this->panel
             . "\n});"
             . "\n</script>";
    }
    
    private function dialogJS(){
        return "\n$('#dlgChangePass').puidialog({"
             . "\nmodal: true,"
             . "\nresizable: false,"
             . "\nwidth: 220,"
             . "\n});";
    }
    
    private function dialog(){
        return "\n<form method='post' action='menu.php'>"
             . "\n<div id='dlgChangePass' title='Alterar Senha' class='st-div-dlg-change-pass'>"
             . "\n<p>Informe  a nova senha: <input id='senha1' name='senha1' type='password' class='st-input-change-pass'/></p>"
             . "\n<p>Confirme a nova senha: <input id='senha2' name='senha2' type='password' class='st-input-change-pass'/></p>"
             . "\n<p>"
             . "\n<button id='btnConfSenha' type='submit'>Ok</button>"
             . "\n<button id='btnCancSenha' type='reset' onclick=\"$('#dlgChangePass').puidialog('hide');\">Cancelar</button>"
             . "\n</p>"
             . "\n</div>"
             . "\n</form>";
    }
    
    public function setNomeUsuario($nome = ""){
        $this->nomeUsuario = $nome;
    }
    
    public function setUsuario($usuario = ""){
        $this->usuario = $usuario;
    }
    
    private function alteraSenhaUsuario(){
        $senha1 = isset($_POST["senha1"]) ? sha1($_POST["senha1"]) : "";
        $senha2 = isset($_POST["senha2"]) ? sha1($_POST["senha2"]) : "";
        $crud = new Crud(parent::TABLE_USER);
        
        if (!empty($_POST["senha1"]) && !empty($_POST["senha2"])) {
            if ($senha1 == $senha2){
                $crud->atualizar(
                        parent::COLUMN_PASS." = '".$senha1."'",
                        parent::COLUMN_USER." = '".$this->usuario."'",
                        false);
                unset($_POST["senha1"]);
                unset($_POST["senha2"]);
                $_SESSION["returnPass"] = "info";
                print "<script>location='menu.php';</script>";
            } else {
                $_SESSION["returnPass"] = "error";
                print "<script>location='menu.php';</script>";
            }
        }
    }
    
    private function onLoad(){
        if (isset($_SESSION["returnPass"]) && $_SESSION["returnPass"] == "error"){
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'error', summary: 'Erro', detail: 'Erro ao alterar a senha. Senhas n&atilde;o conferem!'}]);\"";                       
        } else if (isset($_SESSION["returnPass"]) && $_SESSION["returnPass"] == "info") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Informa&ccedil;&atilde;o', detail: 'Senha alterada com sucesso!'}]);\"";
        } else if (isset($_SESSION["returnPass"]) && $_SESSION["returnPass"] == "warn") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'warn', summary: 'Aten&ccedil;&atilde;o', detail: 'Informe a nova senha e a confirma&ccedil;&atilde;o!'}]);\"";
        } else {
            $this->onload = "";
        }
        unset($_SESSION["returnPass"]);
    }
}
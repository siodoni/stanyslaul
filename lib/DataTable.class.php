<?php

include_once 'Estrutura.class.php';
include_once 'Conexao.class.php';
include_once 'JSON.class.php';

class DataTable {

    private $estrutura;
    private $con;
    private $json;
    private $tabela;
    private $titulo;
    private $view;
    private $tabelaJSON;
    private $mensagemRetorno;
    
    public function __construct($tabela){
        $this->tabela = $tabela;
        $this->estrutura = new Estrutura();
        $this->con = new Conexao();
        
        $this->con->connect();
        $this->verificaParametro();
        $this->buildDataTable();
        $this->con->disconnect();
        $this->mensagemRetorno = $_SESSION['mensagemRetorno'];
    }   
    
    private function verificaParametro(){
        $sql = mysql_query("select a.nm_view as view, "
                         . "       a.nm_menu as titulo"
                         . "  from " . $this->con->getDbName() . ".snb_menu a "
                         . " where a.nm_tabela = '" . $this->tabela . "' ");
        $a = mysql_fetch_assoc($sql);
        
        if (empty($a)) {
            die("Parametro incorreto, nao sera possivel montar a lista.");
            //unset($_SESSION["nomeTabela"]);
            //unset($_POST["nomeTabela"]);
            //unset($_GET["nomeTabela"]);
            //unset($_REQUEST["nomeTabela"]);
        }
        
        $this->titulo = $a["titulo"];
        $this->view = $a["view"];
        $this->tabelaJSON = ($this->view == "" || $this->view == null ? $this->tabela : $this->view);
        $this->json = new JSON($this->tabelaJSON);
        
        $_SESSION["nomeTabela"] = $this->tabela;
        $_SESSION["nomeTabelaJSON"] = $this->tabelaJSON;
    }
    
    private function buildDataTable(){
        echo "<!DOCTYPE html>";
        echo "\n<html>";
        echo $this->estrutura->head();
        echo "\n<body id='admin'>";
        echo $this->script();
        echo $this->divMain();
        //echo "<br>Tabela: ". $this->tabela;
        //echo "<br>Tabela JSON: " . $this->tabelaJSON;
        echo "\n</body>";
        echo "\n</html>";
    }
    // Tentei colocar a mensagem no growl para quando retornar do update mostrar o resultado da operação mas ainda nao consegui
    private function script(){
        return "\n<script type='text/javascript'>"
             . "\n$(function() {"
             . "\n// MENSAGENS"
             . "\n$('#mensagens').puigrowl(" . (isset($this->mensagemRetorno)) ? ("'show',".$this->mensagemRetorno) : '' . ";"
             . "\n//TOOLBAR"
             . "\n$('#toolbar').puimenubar();"
             . "\n$('#toolbar').parent().puisticky();"
             . "\n// DATATABLE"
             . "\n$('#tabela').puidatatable({"
             . $this->captionDataSource(10)
             . "\n".$this->dataSource('json.php')
             . "\n".$this->json->columns()
             . "\nselectionMode: 'single',"
             . "\nrowSelect: function(event, data) {"
             . "\nwindow.open('update.php?id='+data.id,'_self');"
             //. "\n$('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Selected', detail: ('ID: ' + data.id)}]);"
             . "\n},"
             . "\nrowUnselect: function(event, data) {"
             //. "\n$('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Unselected', detail: ('ID: ' + data.id)}]);"
             . "\n}"
             . "\n});"
             . "\n});"
             . "\n</script>";       
    }
    
    private function toolbar(){
        return "\n<ul id='toolbar'>"
             . "\n<li><a data-icon='ui-icon-home' onclick=\"window.location = 'menu.php';\" >Menu</a></li>"
             . "\n<li><a data-icon='ui-icon-document' onclick=\"window.location = 'update.php';\">Novo</a></li>"
             . "\n<li><a data-icon='ui-icon-pencil' onclick=\"window.location = 'update.php';\">Editar</a></li>"
             . "\n<li><a data-icon='ui-icon-trash' onclick=\"return excluir();\">Excluir</a></li>"
             . "\n</ul>";
    }
    
    private function divMain(){
        return "\n<div class='st-div-main'>"
             . $this->toolbar()
             . "\n<div id='mensagens'></div>"
             . "\n<div id='tabela'></div>"
             . "\n</div>";
    }
    
    private function captionDataSource($qtdPaginas) {
        return "\ncaption: '".$this->titulo."',"
              ."\npaginator: {" 
              ."\nrows:".$qtdPaginas
              ."\n},";
    }

    private function dataSource($url) {
        return "\ndatasource: function(callback) {"
              ."\n$.ajax({"
              ."\ntype: \"GET\","
              ."\nurl: '".$url."',"
              ."\ndataType: \"json\","
              ."\ncontext: this,"
              ."\nsuccess: function(response) {"
              ."\ncallback.call(this, response);"
              ."\n}"
              ."\n});"
              ."\n},";
    }
}

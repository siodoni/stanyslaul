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
    private $proximoMenu;

    public function __construct($tabela) {
        $this->tabela = $tabela;
        $this->estrutura = new Estrutura();
        $this->con = new Conexao();

        $this->con->connect();
        $this->verificaParametro();
        $this->buildDataTable();
        $this->con->disconnect();
        $this->mensagemRetorno = isset($_SESSION['mensagemRetorno']) ? $_SESSION['mensagemRetorno'] : "";
    }

    private function verificaParametro() {
        $sql = mysql_query(
                "  select a.nm_view as view, "
                . "       a.nm_menu as titulo, "
                . "       a.cod_aplicacao codigo, "
                . "       a.id_menu_proximo prox_menu "
                . "  from " . $this->con->getDbName() . ".snb_menu a "
                . " where a.nm_tabela = '" . $this->tabela . "' ");
        $a = mysql_fetch_assoc($sql);

        if (empty($a)) {
            die("Parametro incorreto, nao sera possivel montar a lista.");
        }

        $this->titulo = $a["codigo"] . " - " . $a["titulo"];
        $this->view = $a["view"];
        $this->proximoMenu = $a["prox_menu"];
        $this->tabelaJSON = ($this->view == "" || $this->view == null ? $this->tabela : $this->view);
        $this->json = new JSON($this->tabelaJSON);

        $_SESSION["nomeTabela"] = $this->tabela;
        $_SESSION["nomeTabelaJSON"] = $this->tabelaJSON;
        $_SESSION["tituloForm"] = $this->titulo;
        $_SESSION["proxMenu"] = $this->proximoMenu;
    }

    private function buildDataTable() {
        echo "<!DOCTYPE html>";
        echo "\n<html>";
        echo $this->estrutura->head();
        echo "\n<body id='admin'>";
        echo $this->script();
        echo $this->divMain();
        echo "\n</body>";
        echo "\n</html>";
    }

    // Tentei colocar a mensagem no growl para quando retornar do update mostrar o resultado da operação mas ainda nao consegui
    private function script() {
        return "\n<script type='text/javascript'>"
                . "\n$(function() {"
                . "\n// MENSAGENS"
                . "\n$('#mensagens').puigrowl();"
                // não consegui funcionar ainda
                //. "\naddMessage = function() {"
                //. "\n$(#mensagens).puigrowl('show', '$this->mensagemRetorno');"
                //. "\n};"
                . "\n//TOOLBAR"
                . "\n$('#toolbar').puimenubar();"
                . "\n$('#toolbar').parent().puisticky();"
                . "\n// DATATABLE"
                . "\n$('#tabela').puidatatable({"
                . $this->captionDataSource(10)
                . "\n" . $this->dataSource('json.php')
                . "\n" . $this->json->columns(true)
                . "\nselectionMode: 'single',"
                . "\nrowSelect: function(event, data) {"
                . "\nwindow.open('update.php?id='+data.id,'_self');"
                . "\n},"
                . "\nrowUnselect: function(event, data) {"
                . "\n}"
                . "\n});"
                . "\n});"
                . "\n"
                . "\n</script>";
    }

    private function toolbar() {
        return "\n<ul id='toolbar'>"
                . "\n<li><a data-icon='ui-icon-home'     onclick=\"window.location = 'menu.php';\"   title='Voltar ao menu'>Menu</a></li>"
                . "\n<li><a data-icon='ui-icon-document' onclick=\"window.location = 'update.php';\" title='Novo'>Novo</a></li>"
                . "\n</ul>";
    }

    private function divMain() {
        return "\n<div class='st-div-main'>"
                . $this->toolbar()
                . "\n<div id='mensagens'></div>"
                . "\n<div id='tabela'></div>"
                . "\n</div>";
    }

    private function captionDataSource($qtdPaginas) {
        return "\ncaption: '" . $this->titulo . "',"
                . "\npaginator: {"
                . "\nrows:" . $qtdPaginas
                . "\n},";
    }

    private function dataSource($url) {
        return "\ndatasource: function(callback) {"
                . "\n$.ajax({"
                . "\ntype: \"GET\","
                . "\nurl: '" . $url . "',"
                . "\ndataType: \"json\","
                . "\ncontext: this,"
                . "\nsuccess: function(response) {"
                . "\ncallback.call(this, response);"
                . "\n}"
                . "\n});"
                . "\n},";
    }
    
    public function getProxMenu(){
        return $this->proximoMenu;
    }
}

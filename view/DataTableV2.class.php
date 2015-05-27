<?php

class DataTableV2 {

    private $estrutura;
    private $json;
    private $idMenu;
    private $dicionarioJSON;
    private $titulo;
    private $mensagemRetorno;
    private $proximoMenu;
    private $onload = "";

    public function __construct($idMenu) {
        $this->onLoad();
        $this->idMenu = $idMenu;
        $this->estrutura = new Estrutura();
        $this->verificaParametro();
        $this->buildDataTable();
        $this->mensagemRetorno = isset($_SESSION['mensagemRetorno']) ? $_SESSION['mensagemRetorno'] : "";
    }

    private function verificaParametro() {
        $sql = str_replace("#db",Config::DBNAME,Constantes::QUERY_DATA_TABLE);
        try {
            $pdo = new ConexaoPDO("DataTable.class.php");
            $con = $pdo->connect();
            $rs = $con->prepare($sql);
            $idMenu = $this->idMenu;
            $rs->bindParam(1, $idMenu);
            $rs->execute();
            $a = $rs->fetch(PDO::FETCH_OBJ);

            if (empty($a)) {
                die("<p>Parametro incorreto, nao sera possivel montar a lista.<p/><p>".$sql."</p><p>".$idMenu."</p>");
            }

            $this->titulo = $a->codigo . " - " . $a->titulo;
            $this->proximoMenu = $a->prox_menu;
            $this->dicionarioJSON = ($a->id_dicionario_view == "" || $a->id_dicionario_view == null ? $a->id_dicionario_tabela : $a->id_dicionario_view);
            $this->json = new JSON($this->dicionarioJSON,$con,false,true);

            $_SESSION["idMenu"] = $this->idMenu;
            $_SESSION["dicionarioJSON"] = $this->dicionarioJSON;
            $_SESSION["tituloForm"] = $this->titulo;
            $_SESSION["proxMenu"] = $this->proximoMenu;

            $pdo->disconnect();
        } catch (Exception $e){
            die("<p>Problema no metodo DataTable.verificaParametro</p><p>".$sql."</p><p>".$e."</p>");
        }
    }

    public function columnsV2(){
        return $this->json->columnsV2();
    }

    public function getColumnsTableV2(){
        return $this->json->getColumnsTableV2();
    }

    private function buildDataTable() {
        echo "<!DOCTYPE html>";
        echo "\n<html>";
        echo $this->estrutura->head(true);
        echo "\n<body id='admin' $this->onload>";
        echo $this->script();
        echo $this->divMain();
        echo $this->estrutura->dialogAguarde();
        echo "\n</body>";
        echo "\n</html>";
    }

    private function script() {
        return    "\n<script type='text/javascript'>"
                . "\n$(function() {"
                . "\n// MENSAGENS"
                . "\n$('#mensagens').puigrowl();\n"
                . "\n//TOOLBAR"
                . "\n$('#toolbar').puimenubar();"
                . "\n$('#toolbar').parent().puisticky();\n"
                . "\n});"
                . "\n".$this->columnsV2()
                . "\n</script>";
    }

    private function toolbar() {
        return "\n<ul id='toolbar'>"
                . "\n<li><a data-icon='ui-icon-home'     onclick=\"window.location = 'menu.php';\"     title='Voltar ao menu'>Menu</a></li>"
                . "\n<li><a data-icon='ui-icon-document' onclick=\"window.location = 'updateV2.php';\" title='Novo'>Novo</a></li>"
                . "\n<li><a data-icon='ui-icon-close'    href='logout.php'>Sair</a></li>"
                . "\n</ul>";
    }

    private function divMain() {
        return "\n<div class='st-div-main'>"
                . $this->toolbar()
                . "\n<div id='mensagens'></div>"
                . "\n<table id=\"dataTable\" class=\"cell-border hover order-column\" cellspacing=\"0\" width=\"100%\">"
                . "\n<thead>"
                . "\n<tr>"
                . "\n" . $this->getColumnsTableV2()
                . "\n</tr>"
                . "\n</thead>"
                . "\n<tfoot>"
                . "\n<tr>"
                . "\n" . $this->getColumnsTableV2()
                . "\n</tr>"
                . "\n</tfoot>"
                . "\n</table>"
                . "\n</div>";
    }

    public function getProxMenu(){
        return $this->proximoMenu;
    }

    private function onLoad() {
        if (isset($_SESSION["returnCrud"]) && $_SESSION["returnCrud"] == "error") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'error', summary: 'Erro', detail: '".$_SESSION['mensagemRetorno']."'}]);\"";
        } else if (isset($_SESSION["returnCrud"]) && $_SESSION["returnCrud"] == "info") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Informa&ccedil;&atilde;o', detail: '".$_SESSION['mensagemRetorno']."'}]);\"";
        } else if (isset($_SESSION["returnCrud"]) && $_SESSION["returnCrud"] == "warn") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'warn', summary: 'Aten&ccedil;&atilde;o', detail: '".$_SESSION['mensagemRetorno']."'}]);\"";
        } else {
            $this->onload = "";
        }
        unset($_SESSION["returnCrud"]);
    }    
}

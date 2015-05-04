<?php

class DataTable {

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
            $this->json = new JSON($this->dicionarioJSON,$con);

            $_SESSION["idMenu"] = $this->idMenu;
            $_SESSION["dicionarioJSON"] = $this->dicionarioJSON;
            $_SESSION["tituloForm"] = $this->titulo;
            $_SESSION["proxMenu"] = $this->proximoMenu;

            //echo "idMenu " . $_SESSION["idMenu"]."<br>";
            //echo "dicionarioJSON " . $_SESSION["dicionarioJSON"]."<br>";
            //echo "tituloForm " . $_SESSION["tituloForm"]."<br>";
            //echo "proxMenu " . $_SESSION["proxMenu"]."<br>";
            //echo "sql " . $sql . "<br>";
            
            $pdo->disconnect();
        } catch (Exception $e){
            die("<p>Problema no metodo DataTable.verificaParametro</p><p>".$sql."</p><p>".$e."</p>");
        }
    }

    private function buildDataTable() {
        echo "<!DOCTYPE html>";
        echo "\n<html>";
        echo $this->estrutura->head();
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
                . "\n\t// MENSAGENS"
                . "\n\t$('#mensagens').puigrowl();\n"
                . "\n\t//TOOLBAR"
                . "\n\t$('#toolbar').puimenubar();"
                . "\n\t$('#toolbar').parent().puisticky();\n"
                . "\n\t// DATATABLE"
                . "\n\t$('#tabela').puidatatable({"
                . $this->captionDataSource(10)
                . $this->dataSource('json.php')
                . $this->json->columns()
                . "\n\t\tselectionMode: 'single',"
                . "\n\t\trowSelect: function(event, data) {"
                . "\n\t\t\twindow.open('updateV2.php?id='+data.id,'_self');"
                . "\n\t\t},"
                . "\n\t\trowUnselect: function(event, data) {"
                . "\n\t\t}"
                . "\n\t});"
                . "\n});"
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
                . "\n<div id='tabela'></div>"
                . "\n</div>";
    }

    private function captionDataSource($qtdPaginas) {
        return "\n\t\tcaption: '" . $this->titulo . "',"
             . "\n\t\tpaginator: {"
             . "\n\t\t\trows:" . $qtdPaginas
             . "\n\t\t},";
    }

    private function dataSource($url) {
        return    "\n\t\tdatasource: function(callback) {"
                . "\n\t\t\t$.ajax({"
                . "\n\t\t\t\ttype: \"GET\","
                . "\n\t\t\t\turl: '" . $url . "',"
                . "\n\t\t\t\tdataType: \"json\","
                . "\n\t\t\t\tcontext: this,"
                . "\n\t\t\t\tsuccess: function(response) {"
                . "\n\t\t\t\t\tcallback.call(this, response);"
                . "\n\t\t\t\t\tatualizaDataTable();"
                . "\n\t\t\t\t}"
                . "\n\t\t\t});"
                . "\n\t\t},";
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

<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

$tabela = "";
if (isset($_POST["nomeTabela"])) {
    $tabela = $_POST["nomeTabela"];
} else {
    $tabela = $_SESSION["nomeTabela"];
}

require_once 'common/Constantes.class.php';
include_once 'lib/Estrutura.class.php';
include_once 'lib/ConexaoPDO.class.php';
include_once 'lib/JSON.class.php';
require_once 'lib/DataTable.class.php';
new DataTable($tabela);
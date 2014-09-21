<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

$tabela = "";
if (isset($_POST["nomeTabela"])) {
    $tabela = $_POST["nomeTabela"];
} else {
    $tabela = $_SESSION["nomeTabela"];
}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'view/Estrutura.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/JSON.class.php';
require_once 'view/DataTable.class.php';
new DataTable($tabela);
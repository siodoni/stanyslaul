<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

$tabela = "";

if (isset($_SESSION["nomeTabelaJSON"])){
    $tabela = $_SESSION["nomeTabelaJSON"];
} elseif (isset($_POST["nomeTabelaJSON"])) {
    $tabela = $_POST["nomeTabelaJSON"];
} elseif (isset($_GET["nomeTabelaJSON"])){
    $tabela = $_GET["nomeTabelaJSON"];
} elseif (isset($_REQUEST["nomeTabelaJSON"])){
    $tabela = $_REQUEST["nomeTabelaJSON"];
}

require_once 'common/Constantes.class.php';
require_once 'lib/ConexaoPDO.class.php';
require_once 'lib/JSONv2.class.php';
$json = new JSONv2($tabela);
echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json->json());
<?php
session_start("stanyslaul");
require_once 'lib/JSON.class.php';
$tabela = "";
if (isset($_SESSION["nomeTabela"])){
    $tabela = $_SESSION["nomeTabela"];
} elseif (isset($_POST["nomeTabela"])) {
    $tabela = $_POST["nomeTabela"];
} elseif (isset($_GET["nomeTabela"])){
    $tabela = $_GET["nomeTabela"];
} elseif (isset($_REQUEST["nomeTabela"])){
    $tabela = $_REQUEST["nomeTabela"];
}
$json = new JSON($tabela);
echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json->json());
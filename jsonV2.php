<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

$dicionarioJSON = "";
$dataTable = true;

if (isset($_SESSION["dicionarioJSON"])){
    $dicionarioJSON = $_SESSION["dicionarioJSON"];
} elseif (isset($_POST["dicionarioJSON"])) {
    $dicionarioJSON = $_POST["dicionarioJSON"];
} elseif (isset($_GET["dicionarioJSON"])){
    $dicionarioJSON = $_GET["dicionarioJSON"];
} elseif (isset($_REQUEST["dicionarioJSON"])){
    $dicionarioJSON = $_REQUEST["dicionarioJSON"];
}

if (isset($_SESSION["dataTable"])){
    $dataTable = $_SESSION["dataTable"] == "false" ? false : true;
} elseif (isset($_POST["dataTable"])) {
    $dataTable = $_POST["dataTable"]    == "false" ? false : true;
} elseif (isset($_GET["dataTable"])){
    $dataTable = $_GET["dataTable"]     == "false" ? false : true;
} elseif (isset($_REQUEST["dataTable"])){
    $dataTable = $_REQUEST["dataTable"] == "false" ? false : true;
}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/JSON.class.php';

$json = new JSON($dicionarioJSON,null,false,$dataTable);
echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json->json());
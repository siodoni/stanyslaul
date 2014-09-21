<?php

session_start();
if (!isset($_SESSION["usuario"])) {
    header('location:index.php');
}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/CrudPDO.class.php';
require_once 'view/Estrutura.class.php';

$estrutura = new Estrutura();

echo "<!DOCTYPE html>";
echo "\n<html>";
echo $estrutura->head();
echo "\n<body id='admin'>";
echo $estrutura->dialogAguarde();
echo "<form id='insert' action='' method='get'>";
$pdo = new ConexaoPDO("delete.php");
$con = $pdo->connect();
$campoId = "id";
$nomeTabela = "";
$id = "";

if (isset($_POST["nomeTabela"])) {
    $nomeTabela = $_POST["nomeTabela"];
} else {
    $nomeTabela = $_SESSION["nomeTabela"];
}

if ($nomeTabela != "") {
    $crud = new CrudPDO($con, $nomeTabela, true);
} else {
    die("Informe o parametro nomeTabela.");
}

if (isset($_REQUEST["id"])) {
    $id = $_REQUEST["id"];
} else {
    $id = $_SESSION["id"];
}

if ($id == "") {
    die("Informe o parametro id.");
}

$crud->excluir("" . $campoId . " = '" . $id . "'");
$pdo->disconnect();

print "<script>location='list.php';</script>";
echo "</form>";
echo "\n</body>";
echo "\n</html>";
?>
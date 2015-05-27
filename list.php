<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

$idMenu = "";
if (isset($_POST["idMenu"])) {
    $idMenu = $_POST["idMenu"];
} else {
    $idMenu = $_SESSION["idMenu"];
}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'view/Estrutura.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/JSON.class.php';
require_once 'view/DataTableV2.class.php';
new DataTableV2($idMenu);
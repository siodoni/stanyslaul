<?php
session_start();

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'conexao/ConexaoPDO.class.php';

$usuario = $_POST["usuario"];
$senha   = sha1($_POST["senha"]);

$pdo = new ConexaoPDO("logar.php");
$con = $pdo->connect();
$rs = $con->prepare(str_replace("#db",Config::DBNAME,Constantes::QUERY_LOGIN));
$rs->bindParam(1, $usuario);
$rs->bindParam(2, $senha);
$rs->execute();
$a = $rs->fetch(PDO::FETCH_OBJ);
$pdo->disconnect();

if (!empty($a)){
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nomeUsuario"] = $a->nome;
    $_SESSION["schema"] = Config::DBNAME;
    header('location:menu.php');
} else {
    unset($_SESSION["usuario"]);
    unset($_SESSION["nomeUsuario"]);
    header('location:index.php?return=error');
}
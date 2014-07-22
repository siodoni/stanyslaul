<?php
session_start();

require_once 'common/Constantes.class.php';
require_once 'lib/ConexaoPDO.class.php';

$usuario = $_POST["usuario"];
$senha   = sha1($_POST["senha"]);

$pdo = new ConexaoPDO();
$con = $pdo->connect();
$rs = $con->prepare(str_replace("#db",Constantes::DBNAME,Constantes::QUERY_LOGIN));
$rs->bindParam(1, $usuario);
$rs->bindParam(2, $senha);
$rs->execute();
$a = $rs->fetch(PDO::FETCH_OBJ);
$pdo->disconnect();

if (!empty($a)){
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nomeUsuario"] = $a->nome;
    $_SESSION["schema"] = Constantes::DBNAME;
    header('location:menu.php');
} else {
    unset($_SESSION["usuario"]);
    unset($_SESSION["nomeUsuario"]);
    header('location:index.php?return=error');
}
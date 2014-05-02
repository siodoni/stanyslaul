<?php
session_start();

require_once 'lib/Conexao.class.php';

$con = new Conexao();
$con->connect();

$usuario = $_POST["usuario"];
$senha   = sha1($_POST["senha"]);

$sql = mysql_query(
        str_replace("#db",$con->getDbName(),(
        str_replace("#usuario",$usuario,(
        str_replace("#senha",$senha,$con->getQueryLogin()))))));

$a = mysql_fetch_assoc($sql);

if (!empty($a)){
    $_SESSION["usuario"] = $usuario;
    $_SESSION["nomeUsuario"] = $a["nome"];
    $_SESSION["schema"] = $con->getDbName(); // TEMOS QUE VERIFICAR SE É A MELHOR OPÇÃO
    header('location:menu.php');
} else {
    unset($_SESSION["usuario"]);
    unset($_SESSION["nomeUsuario"]);
    header('location:index.php?return=error');
}

$con->disconnect();
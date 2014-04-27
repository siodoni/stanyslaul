<?php
session_start();

require_once 'lib/Conexao.class.php';
$con = new Conexao();
$con->connect();

$usuario = $_POST["usuario"];
$senha   = sha1($_POST["senha"]);

$sql = mysql_query("select b.nome "
                  ."  from newyork.snb_pessoa b, "
                  .      " newyork.snb_usuario a "
                  ." where a.usuario = '".$usuario."'"
                  ."   and a.senha   = '".$senha."'"
                  ."   and b.id      = a.id_pessoa ");

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
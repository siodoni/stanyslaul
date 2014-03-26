<?php
/*
session_start();
if (empty($_SESSION['usuario_id'])) {
    header('Location: index.php?r=2');
} else {
    $usuario_id = $_SESSION['usuario_id'];
    $usuario_nome = $_SESSION['usuario_nome'];
}
*/
?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="ABC 3 WebDesign"/>
    </head>
    <body id="admin">
        <form id="insert" action="" method="get">
            <?php 
            require_once 'lib/Conexao.class.php';
            require_once 'lib/Crud.class.php';

            $con = new conexao();
            $con->connect();

            if($con->connect() == true) {
                echo "";
            }else {
                die('Não conectado. Erro: '.mysql_error());
            }

            if (isset($_REQUEST["nomeTabela"])) {
                $crud = new crud($_REQUEST["nomeTabela"]);
            } else {
                die("Informe o parametro nomeTabela.");
            }

            if (isset($_REQUEST["campoId"])) {
                $campoId = $_REQUEST["campoId"];
            }else {
                die("Informe o parametro campoId.");
            }

            if (isset($_REQUEST["id"])) {
                $id = $_REQUEST["id"];
            }else {
                die("Informe o parametro id.");
            }

            $crud->excluir("".$campoId." = '".$id."'");
            $con->disconnect();

            print "<script>location='list.php?nomeTabela=".$_REQUEST["nomeTabela"]."';</script>";
            ?>
        </form>
    </body>
</html>
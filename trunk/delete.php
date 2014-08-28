<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body id="admin">
        <form id="insert" action="" method="get">
            <?php 
            require_once 'common/Constantes.class.php';
            require_once 'lib/ConexaoPDO.class.php';
            require_once 'lib/CrudPDO.class.php';

            $pdo = new ConexaoPDO("delete.php");
            $con = $pdo->connect();
            $campoId = "id";
            $nomeTabela = "";
            $id = "";

            if (isset($_POST["nomeTabela"])) {
                $nomeTabela = $_POST["nomeTabela"];
            } else{
                $nomeTabela = $_SESSION["nomeTabela"];
            }
            
            if ($nomeTabela != ""){
                $crud = new CrudPDO($con,$nomeTabela,true);
            } else {
                die("Informe o parametro nomeTabela.");
            }
            
            if (isset($_REQUEST["id"])) {
                $id = $_REQUEST["id"];
            } else{
                $id = $_SESSION["id"];
            }

            if ($id == "") {
                die("Informe o parametro id.");
            }

            $crud->excluir("".$campoId." = '".$id."'");
            $pdo->disconnect();
            print "<script>location='list.php';</script>";
            ?>
        </form>
    </body>
</html>
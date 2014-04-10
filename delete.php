<?php
session_start("stanyslaul");
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body id="admin">
        <form id="insert" action="" method="get">
            <?php 
            require_once 'lib/Conexao.class.php';
            require_once 'lib/Crud.class.php';

            $con = new conexao();
            $con->connect();
            $campoId = "id";
            $nomeTabela = "";
            $id = "";

            if($con->connect() == true) {
                echo "";
            }else {
                die('Não conectado. Erro: '.mysql_error());
            }

            if (isset($_POST["nomeTabela"])) {
                $nomeTabela = $_POST["nomeTabela"];
            } else{
                $nomeTabela = $_SESSION["nomeTabela"];
            }
            
            if ($nomeTabela != ""){
                $crud = new Crud($nomeTabela);
            } else {
                die("Informe o parametro nomeTabela.");
            }
            
            if (isset($_POST["id"])) {
                $id = $_POST["id"];
            } else{
                $id = $_SESSION["id"];
            }

            if ($id == "") {
                die("Informe o parametro id.");
            }

            $crud->excluir("".$campoId." = '".$id."'");
            $con->disconnect();
            print "<script>location='list.php';</script>";
            ?>
        </form>
    </body>
</html>
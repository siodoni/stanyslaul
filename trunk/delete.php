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

            if($con->connect() == true) {
                echo "";
            }else {
                die('Não conectado. Erro: '.mysql_error());
            }

            if (isset($_SESSION["nomeTabela"])) {
                $crud = new crud($_SESSION["nomeTabela"]);
            } else {
                die("Informe o parametro nomeTabela.");
            }

            if (isset($_SESSION["campoId"])) {
                $campoId = $_SESSION["campoId"];
            }else {
                die("Informe o parametro campoId.");
            }

            if (isset($_SESSION["id"])) {
                $id = $_SESSION["id"];
            }else {
                die("Informe o parametro id.");
            }

            $crud->excluir("".$campoId." = '".$id."'");
            $con->disconnect();

            print "<script>location='list.php?nomeTabela=".$_SESSION["nomeTabela"]."';</script>";
            ?>
        </form>
    </body>
</html>
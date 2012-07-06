<!DOCTYPE html>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="siodoni.com.br"/>

        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <link rel="stylesheet" type="text/css" href="css/table.css"/>
        <link rel="stylesheet" type="text/css" href="kendo/styles/kendo.common.css"/>
        <link rel="stylesheet" type="text/css" href="kendo/styles/kendo.blueopal.css"/>

        <script type="text/javascript" src="js/site.js"></script>
        <script type="text/javascript" src="kendo/js/jquery.min.js"></script>
        <script type="text/javascript" src="kendo/js/kendo.web.js"></script>
        <script type="text/javascript" src="kendo/js/cultures/kendo.culture.pt-BR.js"></script>
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

            header("Location: index.php?nomeTabela=".$_REQUEST["nomeTabela"]);
            ?>
        </form>
    </body>
</html>
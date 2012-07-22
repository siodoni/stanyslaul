<!DOCTYPE html>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="siodoni.com.br"/>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <script type="text/javascript" src="js/site.js"></script>

        <script src="kendo/js/jquery.min.js"></script>
        <script src="kendo/js/kendo.web.min.js"></script>
        <link href="kendo/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="kendo/styles/kendo.default.min.css" rel="stylesheet" />
    </head>
    <body id="admin">
        <form id="select" action="" method="get">
            <?php
            require_once 'lib/Conexao.class.php';
            require_once 'lib/Crud.class.php';

            $con = new conexao();
            $con->connect();

            if($con->connect() == false) {
                die('Não conectou');
            }
            $nomeTabela = $_REQUEST["nomeTabela"];
            $orderBy = " order by 1";

            $query = mysql_query("select column_name from information_schema.columns where table_name='".$nomeTabela."'");
            $sql = null;

            $cont = 0;

            while($campo = mysql_fetch_array($query)) {
                if ($sql == null) {
                    $sql = $campo['column_name'];
                }else {
                    $sql = $sql.", ".$campo['column_name'];
                }
                $cont += 1;

                $arrColuna[] = $campo['column_name'];

                if ($cont == 1) {
                    $campoId = $campo['column_name'];
                }
            }

            $cont = 0;

            while($campo = mysql_fetch_array($query)) {
                if ($sql == null) {
                    $sql = $campo['column_name'];
                }else {
                    $sql = $sql.", ".$campo['column_name'];
                }
//        $cont += 1;
//
//        $arrColuna[] = $campo['column_name'];
//
//        if ($cont == 1){
//            $campoId = $campo['column_name'];
//        }
            }

            $sql = "select " . $sql . " from ".$nomeTabela.$orderBy;
            $c = mysql_query($sql);
            $linha = array();
            while($r = mysql_fetch_assoc($c)) {
                $linha[] = $r;
            }
            $var = json_encode($linha);
            $con->disconnect();

            echo "<div id=\"grid\"></div>";
            echo "
    <script>
        var txt = $var;
        $(\"#grid\").kendoGrid({
            dataSource: {
                data : txt,
                pageSize: 10
            },
            selectable: true,
            pageable: true,
            sortable: true,
            scrollable: false,
            navigatable: true,
            editable: true,
            toolbar: [\"create\"]
        });
    </script>";
            ?>
        </form>
    </body>
</html>
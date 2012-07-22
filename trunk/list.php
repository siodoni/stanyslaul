<?php
session_start();
if (empty($_SESSION['usuario_id'])) {
    header('Location: index.php?r=2');
} else {
    $usuario_id = $_SESSION['usuario_id'];
    $usuario_nome = $_SESSION['usuario_nome'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Câmara Municipal de Altinópolis</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="ABC 3 WebDesign"/>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <script type="text/javascript" src="js/site.js"></script>

        <script src="../../kendo/js/jquery.min.js"></script>
        <script src="../../kendo/js/kendo.web.min.js"></script>
        <link href="../../kendo/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="../../kendo/styles/kendo.default.min.css" rel="stylesheet" />
    </head>
    <body id="admin">
        <?php
        echo "Seja bem vindo $usuario_nome";
        include 'menu.php';
        ?>
        <form id="select" action="" method="get">
            <?php
            require_once 'lib/Conexao.class.php';
            require_once 'lib/Crud.class.php';

            $con = new conexao();
            $con->connect();

            if ($con->connect() == false) {
                die('Não conectou');
            }

            if (!isset($_REQUEST["nomeTabela"])) {
                die("Informe o parametro nomeTabela para que a pagina seja renderizada.");
            }

            $nomeTabela = $_REQUEST["nomeTabela"];
            $orderBy = " order by 1";

            $query = mysql_query("select column_name, column_key, data_type from information_schema.columns where table_name='" . $nomeTabela . "'");
            $sql = null;

            $cont = 0;

            while ($campo = mysql_fetch_array($query)) {
                if ($sql == null) {
                    $sql = $campo['column_name'];
                } else {
                    $sql = $sql . ", " . $campo['column_name'];
                }

                if ($campo['data_type'] != 'longtext') {
                    $arrColuna[] = $campo['column_name'];
                }
                if ($campo['column_key'] == 'PRI') {
                    $campoId = $campo['column_name'];
                }
            }

            $sql = "select " . $sql . " from " . $nomeTabela . $orderBy;
            $query = mysql_query($sql);
            echo("<table id='grid'>\n");
            echo("<thead>\n");
            foreach ($arrColuna as $arrayColuna) {
                echo "<th data-field='$arrayColuna'>" . ucwords(str_replace("_", " ", $arrayColuna)) . "</th>\n";
            }
            echo("<th>Editar/Excluir</th>\n");
            echo("</thead>\n");
            echo("<tbody>\n");

            $cont = 0;
            $id = null;
            while ($campo = mysql_fetch_array($query)) {

                echo("<tr>\n");

                $cont = 0;

                foreach ($arrColuna as $arrayColuna) {
                    echo "<td>{$campo[$arrayColuna]}</td>\n";

                    $cont += 1;
                    if ($cont == 1) {
                        $id = $campo[$arrayColuna];
                    }
                }

                echo("<td>\n" .
                        "<a href='update.php?nomeTabela=" . $nomeTabela . "&id=$id&campoId=" . $campoId . "'><img src='img/ico-edit.png'   alt='Editar' /></a>&nbsp;&nbsp;\n" .
                        "<a href='delete.php?nomeTabela=" . $nomeTabela . "&id=$id&campoId=" . $campoId . "' onclick='return excluir();'><img src='img/ico-delete.png' alt='Excluir'/></a>\n" .
                        "<a href='visualizar.php?id_artigo=" . $campoId . "<img src='img/visualizar.jpg' alt='Visualizar'/></a>\n" .
                        "</td>\n");
                echo("</tr>\n");
            }
            echo("</tbody>\n");
            $cont += 1;
            echo("<tfoot><tr><td colspan=$cont><a href='update.php?nomeTabela=" . $nomeTabela . "'><img src='img/ico-add.png' alt='Novo Registro' />&nbsp;Novo&nbsp;</a></td></tr></tfoot>");
            echo("</table>\n");

            $con->disconnect();
            ?>
            <script>
                var caminho = "update.php?nomeTabela=";
                $("#grid").kendoGrid({
                    dataSource: {
                        pageSize: 10
                    },
                    selectable: true,
                    pageable: true,
                    sortable: true,
                    scrollable: false,
                    navigatable: true
                });
            </script>
        </form>
    </body>
</html>
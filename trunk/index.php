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

        <script type="text/javascript">
            kendo.culture("pt-BR");
            $(document).ready(function(){
                $("#grid").kendoGrid({
                    dataSource: {
                        pageSize: 10
                    },
                    selectable: true,
                    pageable: true,
                    sortable: true,
                    scrollable: false,
                    navigatable: true,
                    filterable: true
                });
            });
        </script>
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

            if (!isset($_REQUEST["nomeTabela"])) {
                die ("Informe o parametro nomeTabela para que a pagina seja renderizada.");
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

            $sql = "select ".$sql." from ".$nomeTabela.$orderBy;

            echo("<table id='grid'>\n");

            $query = mysql_query($sql);

            echo("<thead>\n");
            foreach($arrColuna as $arrayColuna) {
                echo "<th data-field='$arrayColuna'>".ucwords(str_replace("_"," ",$arrayColuna))."</th>\n";
            }
            echo("<th id='opcoes'>Opções</th>\n");
            echo("</thead>\n");

            $cont = 0;
            $id = null;

            echo("<tbody>\n");

            while($campo = mysql_fetch_array($query)) {

                echo("<tr>\n");

                $cont = 0;

                foreach($arrColuna as $arrayColuna) {
                    echo "<td>{$campo[$arrayColuna]}</td>\n";

                    $cont += 1;

                    if ($cont == 1) {
                        $id = $campo[$arrayColuna];
                    }
                }

                echo("<td>\n".
                        "<a href='update.php?nomeTabela=".$nomeTabela."&id=$id&campoId=".$campoId."'><img src='img/ico-edit.png'   alt='Editar' /></a>&nbsp;&nbsp;\n".
                        "<a href='delete.php?nomeTabela=".$nomeTabela."&id=$id&campoId=".$campoId."' onclick='return excluir();'><img src='img/ico-delete.png' alt='Excluir'/></a>\n".
                        "</td>\n");
                echo("</tr>\n");
            }
            echo("</tbody>\n");
            $cont += 1;
            echo("<tfoot><tr><td colspan=$cont><a href='update.php?nomeTabela=".$nomeTabela."'><img src='img/ico-add.png' alt='Novo Registro' />&nbsp;Novo&nbsp;</a></td></tr></tfoot>");
            echo("</table>\n");

            $con->disconnect();
            ?>
        </form>
    </body>
</html>
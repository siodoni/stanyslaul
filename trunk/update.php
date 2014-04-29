<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

require_once 'lib/Conexao.class.php';
require_once 'lib/Crud.class.php';
require_once 'lib/Estrutura.class.php';
require_once 'lib/JSON.class.php';
require_once 'lib/Update.class.php';

$estrutura = new Estrutura();
$update = new Update();
?>
<!DOCTYPE html>
<html>
    <?php
    echo $estrutura->head();
    ?>
    <body id="admin">
        <?php
        if (isset($_SESSION["nomeTabela"])) {
            echo "nome tabela " . $_SESSION["nomeTabela"];
        } else {
            echo "nome tabela ??? ";
        }
        ?>
        <script type="text/javascript">
            $(function() {
                // MENSAGENS
                $('#mensagens').puigrowl();

                //TOOLBAR
                $('#toolbar').puimenubar();
                $('#toolbar').parent().puisticky();
            });
        </script>
        <div class="st-div-main">
            <ul id='toolbar'>
                <li>
                    <a data-icon='ui-icon-home' onclick="window.location='menu.php';" >
                        Menu
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-document' onclick="window.location='update.php';">
                        Novo
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-pencil' onclick="window.location='update.php';">
                        Editar
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-trash' onclick="return excluir();">
                        Excluir
                    </a>
                </li>
            </ul>

            <div id="mensagens"></div>  
            <div id="tabela"></div>
        <?php
        $con = new conexao();
        $con->connect();

        if ($con->connect() == false) {
            die('Não conectado. Erro: ' . mysql_error());
        }

        if (isset($_SESSION["nomeTabela"])) {
            $nomeTabela = $_SESSION["nomeTabela"];
        } else {
            die("Informe o parametro nomeTabela.");
        }

        $comando = "";
        if (isset($_REQUEST["id"])) {
            $id = $_REQUEST["id"];
            $campoId = "id";
            $consultaUpdate = mysql_query("select * from " . $con->getDbName() . "." . $nomeTabela . " where " . $campoId . " = '" . $id . "'");
            $valorCampo = mysql_fetch_array($consultaUpdate);
            $comando = "update&campoId=" . $campoId . "&id=" . $id;
        } else {
            $id = "";
            $comando = "insert";
        }
        //$campos = json_encode(mysql_fetch_array($query));
        ?>
            <fieldset id="panel" class="pui-menu">
                <legend><?php echo "Cadastro de " . $nomeTabela ?></legend>
                <form id="formInsert" action="update.php?comando=<?php echo $comando?>" method="post">
                    <table id='hor-minimalist-a'>
                        <tbody>
                            <?php
                            $contador = 0;

                            $q = mysql_query($update->retornaQueryTabela());
                            while ($campo = mysql_fetch_array($q)) {

                                $valor = "";

                                $required = ($campo['nulo'] == "NO") ? "required=\"required\"" : "";
                                $tabelaRef = ($campo['tabela_ref'] != null) ? $campo['tabela_ref'] : null;

                                if (isset($valorCampo)) {
                                    $valor = $valorCampo[$contador];
                                }

                                echo $update->label($campo);
                                $update->montarCampo($campo,$valor);

                                //echo "<td><label class='error' generated='true' for='" . $campo['coluna'] . "'></label></td></tr>";
                                $contador += 1;
                            }
                            echo "<tr><td>&nbsp;</td>";
                            echo "<td>" . $update->button("salvar", "submit","Salvar","");
                            echo $update->button("cancelar","button", "Cancelar", "onclick='window.location=\"list.php\"'") . "</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </form>
            </fieldset>
        </div>
    </body>
    <script type="text/javascript">
        $(function() {
            $('#panel').puifieldset();
            <?php echo $update->retornaJS(); ?>
        });
    </script>
</html>
<?php
$valores = "";

foreach ($_POST as $post) {
    if ($valores == "") {
        if ($qtdAi > 0) {
            $valores .= "\"null\", '" . $post . "'";
        } else {
            $valores .= "'" . $post . "'";
        }
    } else {
        $valores .= ",'" . $post . "'";
    }
};

if (isset($_REQUEST['comando']) && $_REQUEST['comando'] == "insert") {  // caso nao seja passado o id via GET cadastra
//    if ($qtdArq > 0) {
//        include 'upload.php';
//        $up = new Upload();
//        $up->inserir($arquivo);
//    }
    echo $update->retornaColuna() ." - " .$valores;
    $crud = new crud($nomeTabela);
    $crud->inserir($update->retornaColuna(), $valores);
    header("Location: list.php");
}

if (isset($_REQUEST['comando']) && $_REQUEST['comando'] == "update") {  // caso nao seja passado o id via GET cadastra
    $camposUpdate = explode(",", $update->retornaColuna());
    $valoresUpdate = explode(",", $valores);
    $comandoUpdate = "";

    $contador = 0;
    foreach ($camposUpdate as $x) {

        if ($comandoUpdate == "") {
            if ($x != $campoId) {
                $comandoUpdate .= $x . " = " . $valoresUpdate[$contador] . " ";
            }
        } else {
            $comandoUpdate .= ", " . $x . " = " . $valoresUpdate[$contador] . " ";
        }
        $contador += 1;
    }
    $crud = new crud($nomeTabela);
    $crud->atualizar($comandoUpdate, $campoId . " = '" . $id . "' ");
    print "<script>location='list.php';</script>";
}

$con->disconnect();

echo (
"<pre>select a.ordinal_position id_coluna," .
"\n        a.column_name coluna," .
"\n        a.is_nullable nulo," .
"\n        a.data_type tipo_dado," .
"\n        a.numeric_precision numerico," .
"\n        if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo," .
"\n        if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtde_caracteres," .
"\n        replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum," .
"\n        a.column_type enum," .
"\n        if (a.extra = 'auto_increment',1,null) auto_increment," .
"\n        a.column_key tp_chave," .
"\n        b.REFERENCED_TABLE_NAME tabela_ref" .
"\n   from information_schema.columns a " .
"\n   left join information_schema.key_column_usage b " .
"\n     on a.TABLE_SCHEMA           = b.TABLE_SCHEMA" .
"\n    and a.TABLE_NAME             = b.TABLE_NAME " .
"\n    and a.COLUMN_NAME            = b.COLUMN_NAME" .
"\n    and b.REFERENCED_TABLE_NAME is not null" .
"\n  where a.table_schema = '" . $con->getDbName() . "'" .
"\n    and a.table_name   = '" . $nomeTabela . "' " .
"\n  order by a.ordinal_position</pre>");
?>
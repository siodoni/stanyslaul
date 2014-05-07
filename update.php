<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

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
        <script type="text/javascript">
            $(function() {
                // MENSAGENS
                $('#mensagens').puigrowl();

                //TOOLBAR
                $('#toolbar').puimenubar();
                $('#toolbar').parent().puisticky();
            });
        </script>
        <div id="mensagens"></div>
        <div class="st-div-main">
            <ul id='toolbar'>
                <li>
                    <a data-icon='ui-icon-home' onclick="window.location = 'menu.php';">
                        Menu
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-newwin' onclick="window.location = 'list.php';">
                        Lista
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-document' onclick="window.location = 'update.php';">
                        Novo
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-trash' href='delete.php' onclick="return excluir();">
                        Excluir
                    </a>
                </li>
            </ul>
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
                $_SESSION["id"] = $id;
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
                <legend><?php echo $_SESSION["tituloForm"] ?></legend>
                <form id="formInsert" action="update.php?comando=<?php echo $comando ?>" method="post">
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
                                $update->montarCampo($campo, $valor);

                                //echo "<td><label class='error' generated='true' for='" . $campo['coluna'] . "'></label></td></tr>";
                                $contador += 1;
                            }
                            echo "<tr><td>&nbsp;</td>";
                            echo "<td>".$update->button("salvar","submit","Salvar","","ui-icon-disk");
                            echo $update->button("cancelar","button","Cancelar","onclick='window.location=\"list.php\"'","ui-icon-circle-close") . "</td></tr>";
                            //echo $update->retornaQueryTabela();
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
    <?php
    //echo $update->retornaQueryTabela();
    ?>
</html>
<?php
$valores = "";

foreach ($_POST as $post) {
    $qtdAi = 0;
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
    //if ($qtdArq > 0) {
    //include 'upload.php';
    //$up = new Upload();
    //$up->inserir($arquivo);
    //}
    //echo $update->retornaColuna() . " - " . $valores;
    $crud = new crud($nomeTabela);
    $crud->inserir($update->retornaColuna(), $valores);
    print "<script>location='list.php';</script>";
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
    $crud->atualizar($comandoUpdate, $campoId . " = '" . $id . "' ", true);
    print "<script>location='list.php';</script>";
}

$con->disconnect();
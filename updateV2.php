<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header('location:index.php');
}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/CrudPDO.class.php';
require_once 'view/Estrutura.class.php';
require_once 'crud/JSON.class.php';
require_once 'view/UpdateV2.class.php';
require_once 'util/Upload.class.php';

$pdo = new ConexaoPDO("updateV2.php");
$con = $pdo->connect();
$estrutura = new Estrutura();
$update = new UpdateV2($con);
$valores = "";
$dbName = Config::DBNAME;
?>
<!DOCTYPE html>
<html>
    <?php
    echo $estrutura->head();
    ?>
    <body id="admin">
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
                    <a data-icon='ui-icon-document' onclick="window.location = 'updateV2.php';">
                        Novo
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-trash' href='delete.php' onclick="return excluir();">
                        Excluir
                    </a>
                </li>
                <li>
                    <a data-icon='ui-icon-close' href='logout.php'>
                        Sair
                    </a>
                </li>
            </ul>
            <div id="tabela"></div>
            <?php
            $idMenu = isset($_SESSION["idMenu"]) ? $_SESSION["idMenu"] : $_REQUEST["idMenu"];
            
            if ($idMenu == null || $idMenu == "") {
                die("Informe o parametro idMenu.");
            }
            
            //echo $idMenu;
            
            $rsT = $con->prepare(str_replace("#db",$dbName,"select (select b.nome_tabela from #db.snb_dicionario b where b.id = a.id_dicionario_tabela) as tabela, a.id_dicionario_tabela from #db.snb_menu a where id = ?"));
            $rsT->bindParam(1, $idMenu);
            $rsT->execute();
            $tabelaDic = $rsT->fetch(PDO::FETCH_OBJ);
            $_SESSION["nomeTabela"] = $tabelaDic->tabela;

            $comando = "";
            if (isset($_REQUEST["id"])) {
                $id = $_REQUEST["id"];
                $_SESSION["id"] = $id;
                $campoId = "id";

                $sql = "select * "
                      . " from " . $dbName . "." . $tabelaDic->tabela
                     . " where " . $campoId . " = '" . $id . "'";

                $rs = $con->prepare($sql);
                $rs->execute();
                $valorCampo = $rs->fetch(PDO::FETCH_NUM);

                $comando = "update&campoId=" . $campoId . "&id=" . $id;
            } else {
                $id = "";
                $comando = "insert";
            }
            ?>
            <fieldset id="panel" class="pui-menu st-fieldset">
                <legend><?php echo isset($_SESSION["tituloForm"]) ? $_SESSION["tituloForm"] : "Cadastro" ?></legend>
                <form id="formInsert" action="updateV2.php?comando=<?php echo $comando ?>" method="post" enctype="multipart/form-data">
                    <table id='hor-minimalist-a'>
                        <tbody>
                            <?php
                            $contador = 0;

                            //echo str_replace("#db",$dbName,$update->retornaQueryTabela());
                            
                            $q = $con->prepare(str_replace("#db",$dbName,$update->retornaQueryTabela()));
                            $q->bindParam(1, $tabelaDic->id_dicionario_tabela);
                            $q->execute();
                            $nomeTabela = $tabelaDic->tabela;

                            while ($campo = $q->fetch(PDO::FETCH_ASSOC)) {
                                $valor = "";

                                $required = ($campo["fg_obrigatorio"] == "SIM") ? "required=\"required\"" : "";
                                $tabelaRef = ($campo["tabela_ref"] != null) ? $campo["tabela_ref"] : null;

                                if (isset($valorCampo)) {
                                    $valor = $valorCampo[$contador];
                                }

                                echo $update->label($campo,$campo["fg_obrigatorio"]);
                                $update->montarCampo($campo, $valor);

                                //echo "<td><label class='error' generated='true' for='" . $campo['coluna'] . "'></label></td></tr>";
                                $contador += 1;
                            }
                            //$onclickSalvar = (isset($_SESSION["proxMenu"]) && $_SESSION["proxMenu"] != null ? 'onclick="$(\'#dlgRedirect\').puidialog(\'show\');"' : "");

                            if ($contador == 0){
                                echo "<tr><td>Tabela ($tabelaDic->tabela) n&atilde;o cadastrada no dicionario</td></tr>";
                            }
                            
                            echo "<tr><td>&nbsp;</td>";
                            echo "<td>" . $update->button("salvar", "submit", "Salvar", "", "ui-icon-disk");
                            echo $update->button("cancelar", "button", "Cancelar", "onclick='window.location=\"list.php\"'", "ui-icon-circle-close") . "</td></tr>";
                            ?>
                        </tbody>
                    </table>
                </form>
            </fieldset>
        </div>
        <!--
        <div id='dlgRedirect' title='Redirecionar'>
            <p>Deseja ir para o pr&oacute;ximo cadastro?</p>
            <p>
                <button id='btnConfRedirect' type='button'>Sim</button>
                <button id='btnCancRedirect' type='button'>N&atilde;o</button>
            </p>
        </div>
        -->
        <?php
        echo $estrutura->dialogAguarde();
        ?>
    </body>
    <script type="text/javascript">
        $(function() {
            <?php
            echo "\t// MENSAGENS";
            echo "\n\t\t$('#mensagens').puigrowl();\n";
            echo "\n\t\t//TOOLBAR";
            echo "\n\t\t$('#toolbar').puimenubar();";
            echo "\n\t\t$('#toolbar').parent().puisticky();\n";
            /*
            echo "\n\t\t//DIALOG";
            echo "\n\t\t$('#dlgRedirect').puidialog({modal:true,resizable:false,width:220});";
            echo "\n\t\t$('#btnConfRedirect').puibutton({icon:'ui-icon-circle-check'});";
            echo "\n\t\t$('#btnCancRedirect').puibutton({icon:'ui-icon-circle-close'});\n";
            */
            echo "\n\t\t//PANEL";
            echo "\n\t\t$('#panel').puifieldset();\n";
            echo "\n\t\t//INPUTS\n";
            echo $update->retornaJS();
            ?>
            $( "#formInsert" ).submit(function(event) {
                var qtde = 0;
                $(".input-required").each(function(i) {
                    if ($(this).val() === "") {
                        qtde += 1;
                        $(this).addClass("msg-error");
                        $(".span-msg-error").each(function(j) {
                            if (i===j){
                                $(this).text("Campo obrigatorio");
                            }
                        });
                    } else {
                        $(this).removeClass("msg-error");
                        $(".span-msg-error").each(function(j) {
                            if (i===j){
                                $(this).text("");
                            }
                        });
                    }
                });

                if (qtde > 0){
                    $('#mensagens').puigrowl('show',[{severity:'error',summary:'Erro',detail:'Existem campos obrigat&oacute;rios n&atilde;o informados.'}]);
                    event.preventDefault(); //mantém na página
                } else {
                    return; //faz submit do form
                }
            });
        });
    </script>
</html>
<?php
/*
 * Por enquanto criei esse método de verificação para converter as datas. Mas a idéia é que
 * essa informação seja buscada através do array em JSON que foi recuperado com as informações da tabela
 * OBS: As informações de nome de tabela e schema devem ser recuperados da sessão */

function verificaCampoDeData($con, $nomeTabela, $campo) {
    $schema = Config::DBNAME;
    $rs = $con->prepare(
            " select a.data_type tipo_dado "
            . " from information_schema.columns a "
            . " where a.table_schema = ? "
            . " and a.table_name   = ? "
            . " and a.column_name  = ? ");
    $rs->bindParam(1, $schema);
    $rs->bindParam(2, $nomeTabela);
    $rs->bindParam(3, $campo);
    $rs->execute();
    $tipoDado = $rs->fetch(PDO::FETCH_NUM);
    return $tipoDado[0];
}

if (isset($_REQUEST['comando']) && ($_REQUEST['comando'] == "insert" || $_REQUEST['comando'] == "update")) {

    if ($update->getInputFile() != null) {
        $arquivos = explode(",", $update->getInputFile());
        $upload = new Upload();
        foreach ($arquivos as $x) {
            if ($_FILES["$x"]["name"] != null) {
                $retorno = $upload->inserir($_FILES["$x"]["name"], "$x", null, true);
                if (!$retorno) {
                    die($upload->getMsgErro());
                }
                $_POST[$x] = $upload->getNomeFinal();
            } else {
                $_POST[$x] = $_POST["_" . $x];
            }
        }
    }

    $camposUpdate = explode(",", $update->retornaColuna());
    foreach ($camposUpdate as $y) {
        $qtdAi = 0;
        $vlr = (isset($_POST[$y]) ? $_POST[$y] : "");

        if ($y == "senha" || $y == "password") {
            $_vlr = (isset($_POST["_" . $y]) ? $_POST["_" . $y] : "");
            if ($_vlr == $vlr) {
                $vlr = $_vlr;
            } else {
                $vlr = sha1(trim($vlr));
            }
        }

        if ($valores == "") {
            if ($qtdAi > 0) {
                $valores .= "\"null\"" . chr(38) . " '" . $vlr . "'";
            } else {
                $valores .= "'" . $vlr . "'";
            }
        } else {
            $valores .= "" . chr(38) . "'" . $vlr . "'";
        }
        $valores = str_replace("''", "null", $valores);
    }

    $valoresUpdate = explode(chr(38), $valores);
    $contador = 0;
    $valores = "";
    $comandoUpdate = "";

    $crud = new CrudPDO($con, $nomeTabela, true);

    if ($_REQUEST['comando'] == "insert") {
        foreach ($camposUpdate as $z) {
            if (verificaCampoDeData($con, $nomeTabela, $z) == 'date') {
                $valores .= " str_to_date(" . $valoresUpdate[$contador] . ",'" . $update->getDateFormat() . "'),";
            } else if (verificaCampoDeData($con, $nomeTabela, $z) == 'datetime') {
                $valores .= " str_to_date(" . $valoresUpdate[$contador] . ",'" . $update->getDateTimeFormat() . "'),";
            } else if (verificaCampoDeData($con, $nomeTabela, $z) == 'time') {
                $valores .= " str_to_date(" . $valoresUpdate[$contador] . ",'" . $update->getTimeFormat() . "'),";
            } else {
                $valores .= " " . $valoresUpdate[$contador] . ",";
            }
            $contador += 1;
        }
        //die("<br><br>insert<br>colunas " . $update->retornaColuna() . "<br>valores " . substr($valores,0,(strlen($valores)-1)));
        $crud->inserir($update->retornaColuna(), substr($valores, 0, (strlen($valores) - 1)));
    } else if ($_REQUEST['comando'] == "update") {
        foreach ($camposUpdate as $y) {
            if ($y != $campoId) {
                if (verificaCampoDeData($con, $nomeTabela, $y) == 'date') {
                    $comandoUpdate .= " " . $y . " = str_to_date(" . $valoresUpdate[$contador] . ",'" . $update->getDateFormat() . "' ),";
                } else if (verificaCampoDeData($con, $nomeTabela, $y) == 'datetime') {
                    $comandoUpdate .= " " . $y . " = str_to_date(" . $valoresUpdate[$contador] . ",'" . $update->getDateTimeFormat() . "' ),";
                } else if (verificaCampoDeData($con, $nomeTabela, $y) == 'time') {
                    $comandoUpdate .= " " . $y . " = str_to_date(" . $valoresUpdate[$contador] . ",'" . $update->getTimeFormat() . "' ),";
                } else {
                    $comandoUpdate .= " " . $y . " = " . $valoresUpdate[$contador] . ",";
                }
            }
            $contador += 1;
        }
        //die("<br><br>update<br>comando " . substr($comandoUpdate,0,(strlen($comandoUpdate)-1)) . "<br>id " . $campoId . " = '" . $id . "' ");
        $crud->atualizar(substr($comandoUpdate, 0, (strlen($comandoUpdate) - 1)), $campoId . " = '" . $id . "' ", true);
    }
    redirectProxMenu($con);
}

function redirectProxMenu($con) {
    if (isset($_SESSION["proxMenu"]) && $_SESSION["proxMenu"] != null) {
        $proxMenu = $_SESSION["proxMenu"];
        $rs = $con->prepare(str_replace("#db", Config::DBNAME, Constantes::QUERY_PROX_MENU));
        $rs->bindParam(1, $proxMenu);
        $rs->execute();
        $a = $rs->fetch(PDO::FETCH_OBJ);

        $_SESSION["nomeTabela"] = $a->tabela;
        $_SESSION["nomeTabelaJSON"] = ($a->view == "" || $a->view == null ? $a->tabela : $a->view);
        $_SESSION["tituloForm"] = $a->codigo . " - " . $a->titulo;
        $_SESSION["proxMenu"] = $a->prox_menu;

        print "<script>location='updateV2.php';</script>";
    } else {
        print "<script>location='list.php';</script>";
    }
}

$pdo->disconnect();
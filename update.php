<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

include_once 'common/Constantes.class.php';
require_once 'lib/Conexao.class.php';
require_once 'lib/Conexao.class.php';
require_once 'lib/ConexaoPDO.class.php';
require_once 'lib/Crud.class.php';
require_once 'lib/Estrutura.class.php';
require_once 'lib/JSON.class.php';
require_once 'lib/Update.class.php';
require_once 'lib/Upload.class.php';

$estrutura = new Estrutura();
$update = new Update();
$const = new Constantes();
$valores = "";
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
                
                //DIALOG
                /*
                $('#dlgRedirect').puidialog({modal:true,resizable:false,width:220});
                $('#btnConfRedirect').puibutton({icon:'ui-icon-circle-check'});
                $('#btnCancRedirect').puibutton({icon:'ui-icon-circle-close'});
                */
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
                <li>
                    <a data-icon='ui-icon-close' href='logout.php'>
                        Sair
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
            <fieldset id="panel" class="pui-menu st-fieldset">
                <legend><?php echo $_SESSION["tituloForm"] ?></legend>
                <form id="formInsert" action="update.php?comando=<?php echo $comando ?>" method="post" enctype="multipart/form-data">
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
                            //$onclickSalvar = (isset($_SESSION["proxMenu"]) && $_SESSION["proxMenu"] != null ? 'onclick="$(\'#dlgRedirect\').puidialog(\'show\');"' : "");
                            
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
            $('#panel').puifieldset();
            <?php echo $update->retornaJS(); ?>
        });
    </script>
</html>
<?php
/*
 * Por enquanto criei esse método de verificação para converter as datas. Mas a idéia é que
 * essa informação seja buscada através do array em JSON que foi recuperado com as informações da tabela
 * OBS: As informações de nome de tabela e schema devem ser recuperados da sessão */
function verificaCampoDeData($nomeTabela, $campo) {
    $query = mysql_query(
            " select a.data_type tipo_dado
                from information_schema.columns a
               where a.table_schema = '".$_SESSION["schema"]."'
                 and a.table_name   = '$nomeTabela' 
                 and a.column_name  = '$campo'");
    $tipoDado = mysql_fetch_array($query);
    
    return $tipoDado[0];
}

if (isset($_REQUEST['comando'])
&&       ($_REQUEST['comando'] == "insert"
||        $_REQUEST['comando'] == "update")) {
    
    if ($update->getInputFile() != null) {
        $arquivos = explode(",", $update->getInputFile());
        $upload = new Upload();
        foreach ($arquivos as $x) {
            if ($_FILES["$x"]["name"] != null) {
                $retorno = $upload->inserir($_FILES["$x"]["name"],"$x",null,true);
                if (!$retorno){
                    die ($upload->getMsgErro());
                }
                $_POST[$x] = $upload->getNomeFinal();
            } else {
                $_POST[$x] = $_POST["_".$x];
            }
        }
    }
    
    $camposUpdate = explode(",", $update->retornaColuna());
    foreach ($camposUpdate as $y) {
        $qtdAi = 0;
        $vlr = (isset($_POST[$y]) ? $_POST[$y] : "");

        if ($y=="senha"||$y=="password"){
            $_vlr = (isset($_POST["_".$y]) ? $_POST["_".$y] : "");
            if ($_vlr == $vlr){
                $vlr = $_vlr;
            } else {
                $vlr = sha1(trim($vlr));
            }
        }

        if ($valores == "") {
            if ($qtdAi > 0) {
                $valores .= "\"null\"".chr(38)." '" . $vlr . "'";
            } else {
                $valores .= "'" . $vlr . "'";
            }
        } else {
            $valores .= "".chr(38)."'" . $vlr . "'";
        }
        $valores = str_replace("''", "null", $valores);
    }

    $valoresUpdate = explode(chr(38), $valores);
    $contador = 0;
    $valores = "";
    $comandoUpdate = "";
    $crud = new crud($nomeTabela,true);
    
    if ($_REQUEST['comando'] == "insert") {
        foreach ($camposUpdate as $z) {
            if (verificaCampoDeData($nomeTabela, $z) == 'date') {
                $valores .= " str_to_date(" . $valoresUpdate[$contador] . ",'".$update->getDateFormat()."'),";
            } else if (verificaCampoDeData($nomeTabela, $z) == 'datetime') {
                $valores .= " str_to_date(" . $valoresUpdate[$contador] . ",'".$update->getDateTimeFormat()."'),";
            } else if (verificaCampoDeData($nomeTabela, $z) == 'time') {
                $valores .= " str_to_date(" . $valoresUpdate[$contador] . ",'".$update->getTimeFormat()."'),";
            } else {
                $valores .= " " . $valoresUpdate[$contador] . ",";
            }
            $contador += 1;
        }
        //die("<br><br>insert<br>colunas " . $update->retornaColuna() . "<br>valores " . substr($valores,0,(strlen($valores)-1)));
        $crud->inserir($update->retornaColuna(),substr($valores,0,(strlen($valores)-1)));

    } else if ($_REQUEST['comando'] == "update") {
        foreach ($camposUpdate as $y) {
            if ($y != $campoId) {
                if (verificaCampoDeData($nomeTabela, $y) == 'date') {
                    $comandoUpdate .= " " . $y . " = str_to_date(" . $valoresUpdate[$contador] . ",'".$update->getDateFormat()."' ),";
                } else if (verificaCampoDeData($nomeTabela, $y) == 'datetime') {
                    $comandoUpdate .= " " . $y . " = str_to_date(" . $valoresUpdate[$contador] . ",'".$update->getDateTimeFormat()."' ),";
                } else if (verificaCampoDeData($nomeTabela, $y) == 'time') {
                    $comandoUpdate .= " " . $y . " = str_to_date(" . $valoresUpdate[$contador] . ",'".$update->getTimeFormat()."' ),";
                } else {
                    $comandoUpdate .= " " . $y . " = " . $valoresUpdate[$contador] . ",";
                }
            }
            $contador += 1;
        }
        //die("<br><br>update<br>comando " . substr($comandoUpdate,0,(strlen($comandoUpdate)-1)) . "<br>id " . $campoId . " = '" . $id . "' ");
        $crud->atualizar(substr($comandoUpdate,0,(strlen($comandoUpdate)-1)), $campoId . " = '" . $id . "' ", true);
    }
    redirectProxMenu();
}

function redirectProxMenu(){
    if (isset($_SESSION["proxMenu"]) && $_SESSION["proxMenu"] != null){
        $sql = mysql_query(
                "  select a.nm_view as view, "
                . "       a.nm_menu as titulo, "
                . "       a.cod_aplicacao codigo, "
                . "       a.id_menu_proximo prox_menu, "
                . "       a.nm_tabela as tabela "
                . "  from " . $_SESSION["schema"] . ".snb_menu a "
                . " where a.id = " . $_SESSION["proxMenu"] );
        $a = mysql_fetch_assoc($sql);

        $_SESSION["nomeTabela"] = $a["tabela"];
        $_SESSION["nomeTabelaJSON"] = ($a["view"] == "" || $a["view"] == null ? $a["tabela"] : $a["view"]);
        $_SESSION["tituloForm"] = $a["codigo"] . " - " . $a["titulo"];
        $_SESSION["proxMenu"] = $a["prox_menu"];

        print "<script>location='update.php';</script>";
    } else {
        print "<script>location='list.php';</script>";
    }
}

$con->disconnect();
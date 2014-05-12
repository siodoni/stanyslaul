<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

require_once 'lib/Conexao.class.php';
require_once 'lib/Crud.class.php';
require_once 'lib/Estrutura.class.php';
require_once 'lib/JSON.class.php';

$estrutura = new Estrutura();
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
        </div>
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
        if (isset($_SESSION["id"])) {
            $id = $_SESSION["id"];
            $campoId = "id";
            $consultaUpdate = mysql_query("select * from " . $con->getDbName() . "." . $nomeTabela . " where " . $campoId . " = '" . $id . "'");
            $valorCampo = mysql_fetch_array($consultaUpdate);
            $comando = "update&campoId=" . $campoId . "&id=" . $id;
        } else {
            $id = "";
            $comando = "insert";
        }

        $query = mysql_query
                ("select a.ordinal_position id_coluna,
                         a.column_name coluna,
                         a.is_nullable nulo,
                         a.data_type tipo_dado,
                         a.numeric_precision numerico,
                         if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo,
                         if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtde_caracteres,
                         replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum,
                         a.column_type enum,
                         if (a.extra = 'auto_increment',1,null) auto_increment,
                         a.column_key tp_chave,
                         b.REFERENCED_TABLE_NAME tabela_ref
                    from information_schema.columns a 
                    left join information_schema.key_column_usage b 
                      on a.TABLE_SCHEMA           = b.TABLE_SCHEMA
                     and a.TABLE_NAME             = b.TABLE_NAME 
                     and a.COLUMN_NAME            = b.COLUMN_NAME
                     and b.REFERENCED_TABLE_NAME is not null
                   where a.table_schema = '" . $con->getDbName() . "'
                     and a.table_name   = '" . $nomeTabela . "' 
                   order by a.ordinal_position");
        die(json_encode(mysql_fetch_array($query)));
        ?>
        <div id="panel" class="st-menu"><?="Cadastro de " . $nomeTabela ?>
                
        </div>
        <form id="formInsert" action="update.php?comando=<?php echo($comando . "&nomeTabela=" . $nomeTabela); ?>" method="post">
            <table id='hor-minimalist-a'>
                <tbody>
                    <?php
                    $contador = 0;
                    $colunas = "";
                    // se existe campo de ID com Auto increment
                    $qtdAi = 0;
                    // se exsite campo do tipo "file"
                    $qtdArq = 0;
                    $arquivo = "";
                    $campoData = "";

                    while ($campo = mysql_fetch_array($query)) {

                        // zerar variáveis
                        $ai = "";
                        $required = "";
                        $tamCampo = "";
                        $valor = "";
                        $selected = "";
                        
                        montarCampo($campo);
                        $estrutura->montarJS("$('#".$campo['coluna']."').puiinputtext();\n");
                        
                        if ($campo['tamanho_campo'] > 100) {
                            $tamCampo = 80;
                        } else {
                            $tamCampo = $campo['tamanho_campo'];
                        }

                        if ($campo['auto_increment'] != "") {
                            $ai = "disabled=\"disabled\"";
                            $qtdAi = 1;
                        }

                        if ($campo['nulo'] == "NO") {
                            $required = "required=\"required\"";
                        }

                        // label
                        echo "<tr><td>" . ucwords(str_replace("_", " ", str_replace("fi_", "", $campo['coluna'])
                        )) . "</td>\n";

                        if (isset($valorCampo)) {
                            $valor = $valorCampo[$contador];
                        }

                        if ($campo['tipo_dado'] == 'enum') {
                            $enum = explode(',', $campo['valor_enum']);
                            echo "<td><select name='" . $campo['coluna'] . "' class='inputForm'>";
                            foreach ($enum as $enum) {
                                if ($valor == $enum) {
                                    $selected = "selected";
                                }
                                echo "<option value='" . $enum . "' $selected >" . ucfirst(strtolower($enum)) . "</option>";
                            }
                            echo "</select></td>\n";
                        } elseif (($campo['tipo_dado'] == 'date')) {
                            echo "<td><input type='text' id='" . $campo['coluna'] . "' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . "/></td>\n";
                            $campoData = $campoData . "\n$('#" . $campo['coluna'] . "').datepicker({dateFormat:'dd/mm/yy'});";
                        } elseif ($campo['tabela_ref'] != null) {
                            /*
                             * 
                             * ========================================
                             * 
                             * PORQUE DESSE JEITO NÃO DÁ CERTO!!!!!!!!!
                             * 
                             * ========================================
                             * 
                             * 
                            $json = new JSON($campo["tabela_ref"]);
                            $array = json_decode($json->json(false),true);
                            
                            echo "<td><select name='" . $campo['coluna'] . "' class='inputForm'>\n";
                            echo "<option value='' $selected >Escolha...</option>\n";
                            $option = "";
                            
                            foreach ($array as $i => $value) {
                                $option = "<option $selected value='";
                                foreach ($value as $j => $valor) {
                                    $option = $option . ($j=="id" ? $value[$j]."'>" : "") . $value[$j]." ";
                                }
                                echo $option = trim($option) . "</option>\n";
                            }
                            echo "</select></td>\n";
                            */
                            
                            ///*
                            $query_ = mysql_query("select * from ".$con->getDbName().".".$campo["tabela_ref"]." order by id");
                            echo "<td><select name='" . $campo['coluna'] . "' class='inputForm'>";
                            echo "<option value='' $selected >Escolha...</option>\n";
                            while ($campo_ref = mysql_fetch_row($query_)) {
                                echo "<option value='" . $campo_ref[0] . "' >" . $campo_ref[1] . "</option>\n";
                            }
                            echo "</select></td>\n";
                            //*/
                            //montarCampo($campo['coluna'],$campo['coluna'],null,null,null,null,$campo['tipo_dado']);
                            $estrutura->montarJS("$('#".$campo['coluna']."').puidropdown();\n");

                        } elseif ($campo['tipo_dado'] == 'longtext') {
                            echo "<td><textarea id=\"editor\" rows=\"10\" cols=\"30\" name=\"conteudo\" style=\"width:100%;height:440px\" ></textarea></td>\n";
                        } elseif (substr($campo['coluna'], 0, 3) == "fi_") {
                            echo "<td><input type='file' name=" . $campo['coluna'] . "/></td>\n";
                            $qtdArq = 1;
                        } elseif (substr($campo['coluna'], 0, 3) == "pw_") {
                            //echo "<td><input type='password' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . " /></td>\n";
                            montarCampo($campo['coluna'],$campo['coluna'],$tamCampo,$campo['qtde_caracteres'],$valor,$ai,$campo['tipo_dado']);
                            $estrutura->montarJS("$('#".$campo['coluna']."').puipassword();\n");
                        } else {
                            montarCampo($campo['coluna'],$campo['coluna'],$tamCampo,$campo['qtde_caracteres'],$valor,$ai,$campo['tipo_dado']);
                            $estrutura->montarJS("$('#".$campo['coluna']."').puiinputtext();\n");
                            //echo "<td><input type='text' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . " /></td>\n";
                        }

                        //echo "<td><label class='error' generated='true' for='" . $campo['coluna'] . "'></label></td></tr>";
                        // montar query de insert
                        if ($colunas == "") {
                            $colunas .= $campo['coluna'];
                        } else {
                            $colunas .= "," . $campo['coluna'];
                            if (substr($campo['coluna'], 0, 3) == "fi_") {
                                $arquivo = isset($_FILES[$campo['coluna']]);
                            }
                        }
                        $contador += 1;
                    }
                    echo "<input hidden='comando' value='$comando' />";
                    echo "<tr><td>&nbsp;</td>";
                    echo "<td><input value='Salvar'   type='submit' class='inputForm'/>\n";
                    echo "    <input value='Cancelar' type='button' class='inputForm' onclick='window.location=\"list.php\"'/></td></tr>";
                    ?>
                </tbody>
            </table>
            <?php
            if ($campoData != "") {
                echo "\n<script type='text/javascript'>\n$(function() {" . $campoData . "\n});\n</script>\n";
            }
            ?>
        </form>
    </body>
    <script type="text/javascript">
        $(function() {
            $('#panel').puipanel();
            <?=$estrutura->retornaJS();?>
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
    if ($qtdArq > 0) {
        include 'upload.php';
        $up = new Upload();
        $up->inserir($arquivo);
    }
    echo $valores;
    $crud = new crud($nomeTabela);
    $crud->inserir($colunas, $valores);
    header("Location: list.php");
}

if (isset($_REQUEST['comando']) && $_REQUEST['comando'] == "update") {  // caso nao seja passado o id via GET cadastra
    $camposUpdate = explode(",", $colunas);
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

/* Campos */
function inputText($id, $name, $size, $maxLength, $value, $enable) {
    return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' class='inputForm' value='$value' $enable /></td>\n";
}

function montarCampo($id, $name, $size, $maxLength, $value, $enable, $tipo) {
    $campoTexto = array("int", "bigint", "varchar");
    //$campoSenha = array("passord")
    
    if (in_array($tipo, $campoTexto)) {
        echo inputText($id, $name, $size, $maxLength, $value, $enable);
    } elseif (in_array($tipo, $campoSenha)) {
        echo inputPassword($id, $name, $size, $maxLength, $value, $enable);
    }
}

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
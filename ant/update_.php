<!DOCTYPE html>
<?php include 'menu.php'; ?>
<?php
require_once 'lib/Conexao.class.php';
require_once 'lib/Crud.class.php';

$con = new conexao();
$con->connect();

if ($con->connect() == true) {
    echo "";
} else {
    die('Não conectado. Erro: ' . mysql_error());
}

if (isset($_REQUEST["nomeTabela"])) {
    $nomeTabela = $_REQUEST["nomeTabela"];
} else {
    die("Informe o parametro nomeTabela.");
}

if (isset($_REQUEST["id"])) {
    $id = $_REQUEST["id"];
    $campoId = $_REQUEST["campoId"];
    $consultaUpdate = mysql_query("select * from " . $nomeTabela . " where " . $campoId . " = '" . $id . "'");
    $valorCampo = mysql_fetch_array($consultaUpdate);
} else {
    $id = "";
}

$query = mysql_query
        ("select a.ordinal_position id_coluna, "
        . "       a.column_name coluna, "
        . "       a.is_nullable nulo, "
        . "       a.data_type tipo_dado, "
        . "       a.numeric_precision numerico, "
        . "       if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo, "
        . "       if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtde_caracteres, "
        . "       replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum, "
        . "       a.column_type enum,"
        . "       if (a.extra = 'auto_increment',1,null) auto_increment"
        . "  from information_schema.columns a "
        . " where table_schema = '".$con->getDbName()."' "
        . "   and a.table_name = '" . $nomeTabela . "' "
        . " order by a.ordinal_position ");

$corpo = "";
$corpo .= "<table id='hor-minimalist-a'> \n";
$corpo .= "<tbody>\n";

$colunas = "";
$sp = "    ";

//$validacao = "";
//$validacao .= $sp . $sp . $sp . "$(document).ready(function() { \n";
//$validacao .= $sp . $sp . $sp . $sp . "$('#formInsert').validate({ \n";
//$validacao .= $sp . $sp . $sp . $sp . $sp . "submitHandler:function(form) { \n";
//$validacao .= $sp . $sp . $sp . $sp . $sp . "SubmittingForm(); \n";
//$validacao .= $sp . $sp . $sp . $sp . "}, \n";
//$validacao .= $sp . $sp . $sp . $sp . $sp . "rules: { \n";

$validacaoCampo = "";
$contador = -1;
$qtdAi = 0;

while ($campo = mysql_fetch_array($query)) {

    $contador += 1;

    if ($campo['tamanho_campo'] > 100) {
        $tamCampo = 80;
    } else {
        $tamCampo = $campo['tamanho_campo'];
    }

    if ($campo['numerico'] != "") {
        $numerico = ", number: true ";
    } else {
        $numerico = "";
    }

    if ($campo['auto_increment'] != "") {
        $ai = "disabled=\"disabled\"";
        $qtdAi = 1;
    } else {
        $ai = "";
    }

    if ($campo['nulo'] == "NO") {

        if ($validacaoCampo == "") {
            $validacaoCampo = $sp . $sp . $sp . $sp . $sp . $sp . $campo['coluna'] . ": { required: true $numerico} ";
        } else {
            $validacaoCampo .= ",\n" . $sp . $sp . $sp . $sp . $sp . $sp . $campo['coluna'] . ": { required: true $numerico} ";
        }
    }

    $corpo .= "<tr><td>" . ucwords(str_replace("_", " ", $campo['coluna'])) . "</td> \n";

    if (isset($valorCampo)) {
        $valor = $valorCampo[$contador];
    } else {
        $valor = "";
    }

    if ($campo['tipo_dado'] == 'enum') {
        $enum = explode(',', $campo['valor_enum']);

        $corpo .= "<td><select name='" . $campo['coluna'] . "' class='inputForm'>\n";

        foreach ($enum as $enum) {

            if ($valor == $enum) {
                $selected = "selected";
            } else {
                $selected = "";
            }

            $corpo .= "<option value='" . $enum . "' $selected >" . ucfirst(strtolower($enum)) . "</option>\n";
        }
        $corpo .= "</select></td>\n";
    } elseif ($campo['tipo_dado'] == 'longtext') {
        $corpo .= "<td><textarea id=\"editor\" rows=\"10\" cols=\"30\" name=\"conteudo\" style=\"width:100%;height:440px\" ></textarea></td> \n";
    } else {
        $corpo .= "<td><input type='text' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "'" . $ai . " /></td> \n";
    }

    $corpo .= "<td><label class='error' generated='true' for='" . $campo['coluna'] . "'></label></td></tr>";

    if ($colunas == "") {
        $colunas .= $campo['coluna'];
    } else {
        $colunas .= "," . $campo['coluna'];
    }
}

//$validacao .= $validacaoCampo . "\n";
//$validacao .= $sp . $sp . $sp . $sp . $sp . "} \n";
//$validacao .= $sp . $sp . $sp . $sp . "}); \n";
//$validacao .= $sp . $sp . $sp . "}); \n";

$corpo .= "<tr><td>&nbsp;</td>";
$corpo .= "<td><input value='Salvar'   type='submit' class='inputForm'/>\n";
$corpo .= "    <input value='Cancelar' type='button' class='inputForm' onclick='window.location.href=\"index.php?nomeTabela=" . $nomeTabela . "\"'/></td></tr>\n";
$corpo .= "</tbody>\n";
$corpo .= "</table>\n";

$con->disconnect();

if (isset($_REQUEST["id"])) {
    $comando = "update&campoId=" . $campoId . "&id=" . $id;
} else {
    $comando = "insert";
}
?>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author"       content="siodoni.com.br"/>
        <link rel="stylesheet"    type="text/css" href="css/style.css"/>
        <link rel="stylesheet"    type="text/css" href="css/table.css"/>
        <script type="text/javascript" src="js/site.js"></script>
        <script type="text/javascript" src="js/jquery.validation.ajax.js"></script>
        <script type="text/javascript" src="js/jquery.validation.js"></script>

        <script src="kendo/js/jquery.min.js"></script>
        <script src="kendo/js/kendo.web.min.js"></script>
        <link href="kendo/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="kendo/styles/kendo.default.min.css" rel="stylesheet" />

        <script type="text/javascript">
<?php
//echo($validacao);
?>
        </script>
    </head>
    <body id="admin">
        <form id="formInsert" action="update.php?comando=<?php echo($comando . "&nomeTabela=" . $nomeTabela); ?>" method="post">
            <?php
            echo ($corpo);
            ?>
        </form>
        <script>                    
            $(document).ready(function() {                    
                $("#editor").kendoEditor();
            });
        </script>
    </body>
</html>
<?php
$con = new conexao();
$con->connect();

$valores = "";
foreach ($_POST as $post) {
    if ($valores == "") {
        if ($qtdAi == 1) {
            $valores .= "\"null\", '" . $post . "'";
        } else {
            $valores .= "'" . $post . "'";
        }
    } else {
        $valores .= ",'" . $post . "'";
    }
};

if (isset($_REQUEST['comando']) && $_REQUEST['comando'] == "insert") {  // caso nao seja passado o id via GET cadastra
    $crud = new crud($nomeTabela);
    $crud->inserir($colunas, $valores);
    header("Location: index.php?nomeTabela=" . $nomeTabela);
}

if (isset($_REQUEST['comando']) && $_REQUEST['comando'] == "update") {  // caso nao seja passado o id via GET cadastra
    $teste = explode(",", $colunas);
    $teste2 = explode(",", $valores);
    $comandoUpdate = "";

    $contador = -1;
    foreach ($teste as $x) {
        $contador += 1;

        if ($comandoUpdate == "") {
            if ($x != $campoId) {
                $comandoUpdate .= $x . " = " . $teste2[$contador] . " ";
            }
        } else {
            $comandoUpdate .= ", " . $x . " = " . $teste2[$contador] . " ";
        }
    }

    $crud = new crud($nomeTabela);
    $crud->atualizar($comandoUpdate, $campoId . " = '" . $id . "' ");
    header("Location: index.php?nomeTabela=" . $nomeTabela);
}

$con->disconnect();
?>
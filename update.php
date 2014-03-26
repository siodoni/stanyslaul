<?php
/*
session_start();
if (empty($_SESSION['usuario_id'])) {
    header('Location: index.php?r=2');
} else {
    $usuario_id = $_SESSION['usuario_id'];
    $usuario_nome = $_SESSION['usuario_nome'];
}*/
?>
<!DOCTYPE html>
<html>
    <head>        
        <title></title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="ABC 3 WebDesign"/>
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
        </script>
    </head>
    <body id="admin">
        <?php
        require_once 'lib/Conexao.class.php';
        require_once 'lib/Crud.class.php';

        $con = new conexao();
        $con->connect();

        if ($con->connect() == false) {
            die('Não conectado. Erro: ' . mysql_error());
        }

        if (isset($_REQUEST["nomeTabela"])) {
            $nomeTabela = $_REQUEST["nomeTabela"];
        } else {
            die("Informe o parametro nomeTabela.");
        }

        $comando = "";
        if (isset($_REQUEST["id"])) {
            $id = $_REQUEST["id"];
            $campoId = $_REQUEST["campoId"];
            $consultaUpdate = mysql_query("select * from " . $nomeTabela . " where " . $campoId . " = '" . $id . "'");
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
                   where a.table_schema = '".$con->getDbName()."'
                     and a.table_name   = '" . $nomeTabela . "' 
                   order by a.ordinal_position");
        ?>
        <table id='hor-minimalist-a'>
            <thead>
                <tr>
                    <th colspan="2">Cadastro de <?php echo ucfirst(str_replace("_", " ", $nomeTabela)) ?></th>
                </tr>
            </thead>
            <tbody>
            <form id="formInsert" action="update.php?comando=<?php echo($comando . "&nomeTabela=" . $nomeTabela); ?>" method="post">
                <?php
                $contador = 0;
                $colunas = "";
                // se existe campo de ID com Auto increment
                $qtdAi = 0;
                // se exsite campo do tipo "file"
                $qtdArq = 0;
                $arquivo = "";
                while ($campo = mysql_fetch_array($query)) {

                    // zerar variáveis
                    $ai = "";
                    $required = "";
                    $tamCampo = "";
                    $valor = "";
                    $selected = "";

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
                    echo "<tr><td>" . ucwords(str_replace("_", " ",
                            str_replace("fi_", "", $campo['coluna'])
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
                    } elseif ($campo['tp_chave'] == 'MUL') {
                        $query_ = mysql_query("select * from " . $campo["tabela_ref"]);
                        echo "<td><select name='" . $campo['coluna'] . "' class='inputForm'>";
                        echo "<option value='' $selected >Escolha...</option>\n";
                        while ($campo_ref = mysql_fetch_row($query_)) {
                            echo "<option value='" . $campo_ref[0] . "' >" . $campo_ref[1] . "</option>\n";
                        }
                        echo "</select></td>\n";
                    } elseif ($campo['tipo_dado'] == 'longtext') {
                        echo "<td><textarea id=\"editor\" rows=\"10\" cols=\"30\" name=\"conteudo\" style=\"width:100%;height:440px\" ></textarea></td>\n";
                    } elseif (substr($campo['coluna'], 0, 3) == "fi_") {
                        echo "<td><input type='file' name=" . $campo['coluna'] . "   /></td>\n";
                        $qtdArq = 1;
                    } elseif (substr($campo['coluna'], 0, 3) == "pw_") {
                        echo "<td><input type='password' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . " /></td>\n";
                    }
                    else {
                        echo "<td><input type='text' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . " /></td>\n";
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
                echo "<tr><td>&nbsp;</td>";
                echo "<td><input value='Salvar'   type='submit' class='inputForm'/>\n";
                echo "    <input value='Cancelar' type='button' class='inputForm' onclick='window.location.href=\"list.php?nomeTabela=" . $nomeTabela . "\"'/></td></tr>";
                ?>
            </form>
            <script>                    
                $(document).ready(function() {                    
                    $("#editor").kendoEditor();
                });
            </script>
        </tbody>
    </table>
</body>
</html>

<?php

$valores = "";
;
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
    header("Location: list.php?nomeTabela=" . $nomeTabela);
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
    print "<script>location='list.php?nomeTabela=".$nomeTabela."';</script>";
}

$con->disconnect();
?>
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
        <link rel="stylesheet"    type="text/css" href="css/style.css"/>
        <link rel="stylesheet"    type="text/css" href="css/table.css"/>

        <script src="kendo/js/jquery.min.js"></script>
        <script src="kendo/js/kendo.web.min.js"></script>
        <link href="kendo/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="kendo/styles/kendo.default.min.css" rel="stylesheet" />

        <script type="text/javascript">
        </script>
    </head>
    <body id="admin">
        <?php include 'menu.php'; ?>
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
                 a.COLUMN_KEY tp_chave,
                 a.column_comment comentario,
                 b.REFERENCED_TABLE_NAME tabela_ref
            from information_schema.columns a 
                 left join information_schema.key_column_usage b 
                 on   a.TABLE_NAME = b.TABLE_NAME 
                 and  a.COLUMN_NAME = b.COLUMN_NAME
                 and  b.REFERENCED_TABLE_NAME is not null
           where a.table_name = '" . $nomeTabela . "' 
           order by a.ordinal_position");
        ?>
        <table id='hor-minimalist-a'>
            <thead>
                <tr>
                    <th colspan="2">Cadastro de <?php echo ucfirst(str_replace("_", " ", substr($nomeTabela, 4))) ?></th>
                </tr>
            </thead>
            <tbody>
            <form id="formInsert" action="update.php?comando=<?php echo($comando . "&nomeTabela=" . $nomeTabela); ?>" method="post" enctype="multipart/form-data">
                <?php
                $contador = 0;
                $colunas = "";
                // se existe campo de ID com Auto increment
                $qtdAi = 0;
                // se exsite campo do tipo "file"
                $qtdArq = 0;
//                $arquivo = "";
                // veriricar qual o campo de senha
                $cont_pw = 0;
                $cont_arq = 0;
                $campoArq = "";
                $nomeArq = "";

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
                    echo "<tr><td>" . ucwords(str_replace("_", " ", $campo['coluna'])) . "</td>\n";

                    if (isset($valorCampo)) {
                        $valor = $valorCampo[$contador];
                    }

                    //
                    //  Montar os vários tipos de campos
                    //
                    // ENUM
                    if ($campo['tipo_dado'] == 'enum') {
                        $enum = explode(',', $campo['valor_enum']);

                        echo "<td><select name='" . $campo['coluna'] . "' class='inputForm'>";

                        foreach ($enum as $enum) {
                            // trazer valor selecionado quando for edição
                            ($valor == $enum) ? $selected = "selected" : $selected = "";
                            echo "<option value='" . $enum . "' $selected >" . ucfirst(strtolower($enum)) . "</option>";
                        }
                        echo "</select></td>\n";
                        // CHAVE ESTRANGEIRA
                    } elseif ($campo['tp_chave'] == 'MUL') {
                        $q = "select a.column_name
                                from information_schema.columns a 
                               where a.table_name = '" . $campo['tabela_ref'] . "'
                                 and (a.column_key = 'PRI'
                                  or instr(a.column_comment,'{NAME}'))
                                order by a.ordinal_position";
                        $mq = mysql_query($q);
                        $sql = null;
                        while ($c = mysql_fetch_array($mq)) {
                            // montar query
                            ($sql == null) ? $sql = $c['column_name'] : $sql .= ", " . $c['column_name'];
                        }
                        $query_ = mysql_query("select $sql from " . $campo["tabela_ref"]);
                        // montar campo select
                        echo "<td><select name='" . $campo['coluna'] . "' class='inputForm'>\n";
                        while ($campo_ref = mysql_fetch_row($query_)) {
                            // trazer campo selecionado quando for edição
                            ($campo_ref[0] == $valor) ? $selected = "selected='selected'" : $selected = "";
                            //
                            echo "<option value='" . $campo_ref[0] . "' $selected>" . $campo_ref[1] . "</option>\n";
                        }
                        echo "</select></td>\n";
                        // CAMPO DE EDIÇÃO
                    } elseif ($campo['tipo_dado'] == 'longtext') {
                        echo "<td><textarea id=\"editor\" rows=\"10\" cols=\"30\" name='" . $campo['coluna'] . "' style=\"width:100%;height:440px\" ></textarea></td>\n";
                        // DATA
                    } elseif ($campo['tipo_dado'] == 'date') {
                        echo "<td><input type='date' name='" . $campo['coluna'] . "' class='inputForm' value='" . $valor . "' $required /> Formato (aaaa-mm-dd)</td>\n";
                        // ARQUIVO
                    } elseif ($campo['comentario'] == "{FILE}") {
                        echo "<td><input type='file' name=" . $campo['coluna'] . " size='" . $tamCampo . "' $required class='inputForm' />";
                        echo "<p>Arquivo atual: $valor</p></td>\n";
                        $qtdArq = 1;
                        $nomeArq = $valor;
                        // SENHA
                    } elseif (strstr($campo['comentario'], '{PASSWORD}') == '{PASSWORD}') {
                        echo "<td><input type='password' name='" . md5($campo['coluna']) . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' $required /></td>\n";
                        // NUMERICO
                    } elseif ($campo['numerico'] > 0) {
                        echo "<td><input type='number' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . " $required /></td>\n";
                        // TEXTO
                    } else {
                        echo "<td><input type='text' name='" . $campo['coluna'] . "' size='" . $tamCampo . "' maxlength='" . $campo['qtde_caracteres'] . "' class='inputForm' value='" . $valor . "' " . $ai . " $required /></td>\n";
                    }

                    //echo "<td><label class='error' generated='true' for='" . $campo['coluna'] . "'></label></td></tr>";
                    // montar a query de insert
                    if ($colunas == "") {
                        $colunas .= $campo['coluna'];
                    } else {
                        if (strstr($campo['comentario'], '{FILE}') == '{FILE}') {
                            $cont_arq = $contador;
                            $campoArq = $campo['coluna'];
                        } elseif (strstr($campo['comentario'], '{PASSWORD}') == '{PASSWORD}') {
                            $cont_pw = $contador;
                        }
                        // não incluir o campo de arquivo no query
                        if (!strstr($campo['comentario'], '{FILE}') == '{FILE}') {
                            $colunas .= "," . $campo['coluna'];
                        }
                    }
                    $contador++;
                }
                echo "<tr><td>&nbsp;</td>";
                echo "<td><input value='Salvar'   type='submit' class='inputForm'/>\n";
                echo "    <input value='Cancelar' type='button' class='inputForm' onclick='window.location.href=\"list.php?nomeTabela=" . $nomeTabela . "\"'/></td></tr>";
                ?>                
            </form>
            <script>                    
                $(document).ready(function() {
                    var validar = $("#formInsert").kendoValidator().data("kendoValidator");
                    $("#editor").kendoEditor();
                    $("#datepicker").kendoDatePicker();
                    $("#formInsert").kendoValidator({
                        message: {
                            required: "Campo obrigatório"
                        }
                    });
                    $("button").click(function() {
                        if (validar.validate() === false) {
                            var errors = validatable.errors();
                            $(errors).each(function() {
                                $("#errors").html(this);
                            });
                        }
                    });
                });
            </script>
        </tbody>
    </table>
</body>
</html>
<?php
$contador = 0;
$valores = "";
$f_name = "";

foreach ($_POST as $post) {
    if ($valores == "") {
        if ($qtdAi > 0) {
            $valores .= "'null', '" . $post . "'";
        } else {
            //encriptar a senha
            if ($contador == $cont_pw - 1) {
                $valores .= "'" . md5($post) . "'";
            } else {
                $valores .= "'" . $post . "'";
            }
        }
    } else {
        //encriptar a senha
        if ($contador == $cont_pw - 1) {
            $valores .= ",'" . md5($post) . "'";
        } else {
            $valores .= ",'" . $post . "'";
        }
        // definir caminho quando existir
        if (isset($_POST['caminho'])) {
            $caminho = $_POST['caminho'];
        }
    }
    $contador++;
};

/* Se o arquivo estiver preenchido, os dados serão enviados no insert ou update */
if ($qtdArq > 0 && isset($_FILES['arquivo'])) {
    $f_name = str_replace(" ", "_", $_FILES['arquivo']['name']);
    $f_tmp = $_FILES['arquivo']['tmp_name'];
    if ($comando == "update") {
        if (($f_name != null) && ($nomeArq != $f_name)) {
            $colunas .= ", " . $campoArq;
            $valores .= ", '" . $nomeArq . "'";
        }
    } else {
        $colunas .= ", " . $campoArq;
        $valores .= ", '" . (($f_name == "") ? $nomeArq : $f_name) . "'";
    }
} elseif ($qtdArq > 0 && $valor != "") {
    if ($comando == "update") {
        $colunas .= ", " . $campoArq;
        $valores .= ", '" . $nomeArq . "'";
    }
}

if (isset($_REQUEST['comando']) && $_REQUEST['comando'] == "insert") {  // caso nao seja passado o id via GET cadastra
    // se existir arquivo faz upload e insere
    if ($f_name != null) {
        include('upload.php');
        $upload = new Upload();
        $r = $upload->inserir($f_tmp, $campoArq, $caminho);
        if ($r == 1) {
            $crud = new crud($nomeTabela);
            $crud->inserir($colunas, $valores);
        } else {
            echo "<p>$r. <br> Clique aqui para não perder suas informações:<a href='javascript:history.go(-1)'>Voltar</a></p>";
        }
    } else {
        $crud = new crud($nomeTabela);
        $crud->inserir($colunas, $valores);
    }
    echo "<script>location='principal.php';</script>";
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
        $contador++;
    }
    //
    if (($f_name != null) && ($nomeArq != $f_name)) {
        require_once 'upload.php';
        $upload = new Upload();
        $r = $upload->inserir($f_tmp, $campoArq, $caminho);
        if ($r) {
            $crud = new crud($nomeTabela);
            $crud->atualizar($comandoUpdate, $campoId . " = '" . $id . "' ");
        } else {
            die($r);
        }
    } else {
        $crud = new crud($nomeTabela);
        $crud->atualizar($comandoUpdate, $campoId . " = '" . $id . "' ");
    }
    echo "<script>location='principal.php';</script>";
}
$con->disconnect();
?>
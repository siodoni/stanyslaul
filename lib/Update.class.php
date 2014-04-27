<?php

class Update {
    
    private $coluna;
    private $tabela;
    private $schema;
    private $javaScript;
    
    public function __construct() {
        $this->tabela = $_SESSION["nomeTabela"];
        $this->schema = $_SESSION["schema"];
    }
    
    public function adicionarColuna($coluna) {
        if ($this->coluna == null) {
        $this->coluna .= $coluna;
        } else {
            $this->coluna .= "," . $coluna;
    //        if (substr($colunaAtual, 0, 3) == "fi_") {
    //            $arquivo = isset($_FILES[$campo['coluna']]);
    //        }
        }
    }
    
    public function retornaColuna() {
        return $this->coluna;
    }
    
    public function montarJS($texto) {
        $this->javaScript .= $texto;
    }
    
    public function retornaJS() {
        return $this->javaScript;
    }

    public function retornaQueryTabela() {
        return "select a.ordinal_position id_coluna,
                        a.column_name coluna,
                        a.is_nullable nulo,
                        a.data_type tipo_dado,
                        a.numeric_precision numerico,
                        if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo,
                        if(a.data_type='date',10,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtde_caracteres,
                        replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum,
                        a.column_type enum,
                        if (a.extra = 'auto_increment',1,null) auto_increment,
                        a.column_key tipo_chave,
                        b.REFERENCED_TABLE_NAME tabela_ref
                   from information_schema.columns a 
                   left join information_schema.key_column_usage b 
                     on a.TABLE_SCHEMA           = b.TABLE_SCHEMA
                    and a.TABLE_NAME             = b.TABLE_NAME 
                    and a.COLUMN_NAME            = b.COLUMN_NAME
                    and b.REFERENCED_TABLE_NAME is not null
                  where a.table_schema = '" . $this->schema . "'
                    and a.table_name   = '" . $this->tabela . "' 
                  order by a.ordinal_position";
    }

    function montarCampo($arrayCampo, $valorCampo) {
        $campoNumero = array("int", "bigint");
        $campoSenha = array("password");
        $campoArquivo = array("file");
        $campoTextArea = array("longtext");
        $campoData = array("date");
        $campoEnum = array("enum");
        $ai = "";
        if ($arrayCampo['tamanho_campo'] > 100) {
            $tamCampo = 80;
        } else {
            $tamCampo = $arrayCampo['tamanho_campo'];
        }

        if ($arrayCampo['auto_increment'] != "") {
            $ai = "disabled=\"disabled\"";
        } else {
            $this->adicionarColuna($arrayCampo['coluna']);
        }

//        switch ($arrayCampo['tipo_dado']) {
//            case "password":
//                echo $this->inputPassword($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
//                break;
//            case "file":
//                echo $this->inputFile($arrayCampo['coluna'], $arrayCampo['coluna'], $valorCampo);
//                break;
//            case "longtext":
//                echo $this->inputTextArea($arrayCampo['coluna'], $arrayCampo['coluna']);
//                break;
//            case "date";
//                echo $this->inputDate($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
//                break;
//            case "enum";
//                echo $this->selectMenuEnum($arrayCampo['coluna'], $arrayCampo['valor_enum'], $valorCampo);
//                break;
//            case "fk":
//                echo $this->selectMenu($arrayCampo['coluna'], $arrayCampo['coluna'], $arrayCampo['tabela_ref'], $valorCampo);
//                break;
//            default:
//                echo $this->inputText($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
//                break;
//        };
        if (in_array($arrayCampo['tipo_dado'], $campoSenha)) {
            echo $this->inputPassword($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
        } elseif (in_array ($arrayCampo['tipo_dado'], $campoArquivo)) {
            echo $this->inputFile($arrayCampo['coluna'], $arrayCampo['coluna'], $valorCampo);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoTextArea)) {
            echo $this->inputTextArea($arrayCampo['coluna'], $arrayCampo['coluna']);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoData)) {
            echo $this->inputDate($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoEnum)) {
            echo $this->selectMenuEnum($arrayCampo['coluna'], $arrayCampo['valor_enum'], $valorCampo);
        } elseif ($arrayCampo['tipo_chave'] == 'MUL') {
            echo $this->selectMenu($arrayCampo['coluna'], $arrayCampo['coluna'], $arrayCampo['tabela_ref'], $valorCampo);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoNumero)) {
            echo $this->inputNumber($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
        } else {
            echo $this->inputText($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
        }
        
    }
    
    /* Campos */
    function label($arrayCampo) {
        return "<tr><td>" . ucwords(str_replace("_", " ", str_replace("fi_", "", $arrayCampo['coluna']))) . "</td>\n";
    }

    function button($id, $tipo, $valor, $acao) {
        $this->montarJS("$('#$id').puibutton();\n");
        return "<input id='$id' value='$valor' type='$tipo' class='inputForm'/ $acao >\n";
    }

    function inputText($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#".$id."').puiinputtext();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' class='inputForm' value='$value' $enable /></td>\n";
    }

    function inputNumber($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#".$id."').puispinner();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' class='inputForm' value='$value' $enable /></td>\n";
    }

    function inputPassword($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#".$id."').puipassword();\n");
        return "<td><input type='password' id='$id' name='$name' size='$size' maxlength='$maxLength' class='inputForm' value='$value' $enable /></td>\n";
    }

    function inputHidden($valor) {
        return "<td><input hidden='comando' value='$valor' /></td>\n";
    }

    function inputFile($id, $name, $valor) {
        return "<td><input type='file' id='$id' name='$name' value='$valor' /></td>\n";
    }

    function inputTextArea($id, $name) {
        $this->montarJS("$('#".$id."').puiinputtextarea();\n");
        return "<td><textarea id='$id' rows=\"10\" cols=\"30\" name='$name' style=\"width:100%;height:440px\" ></textarea></td>\n";
    }

    function inputDate($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#".$id."').datepicker({dateFormat:'dd/mm/yy'}).puiinputtext()   ;\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' class='inputForm' value='$value' $enable /></td>\n";
    }

    function selectMenuEnum($id, $valoresSelect, $valorSelecionado) {

        $enum = explode(',', $valoresSelect);
        $selectMenu = "\n<td><select id='$id' name='$id' class='inputForm'>";
        foreach ($enum as $enum) {
            $selected = ($valorSelecionado == $enum) ? "selected" : "";
            $selectMenu .= "\n<option value='$enum' $selected >" . ucfirst($enum) . "</option>";
        }
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("$('#".$id."').puidropdown();\n");
        return $selectMenu;
    }

    function selectMenu($id, $name, $tabelaRef, $valorSelecionado) {
        
        $selectMenu = "\n<td><select id='$id' name='$name' class='inputForm'>\n";
        $selectMenu .= "\n<option value='' >Escolha...</option>\n";
        $q = mysql_query("select * from $tabelaRef");

        while ($c = mysql_fetch_array($q)) {
            $selected = ($valorSelecionado == $c[0]) ? "selected" : "";
            $option = "\n<option value='$c[0]' $selected >";
            $option .= trim($c[1]);
            $option .= "\n</option>\n";
        }
        $selectMenu .= $option;
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("$('#".$id."').puidropdown();\n");
        return $selectMenu;
    }

}
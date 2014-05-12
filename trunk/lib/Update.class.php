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
            //if (substr($colunaAtual, 0, 3) == "fi_") {
            //$arquivo = isset($_FILES[$campo['coluna']]);
            //}
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
    
    public function verificaSeEhData($nomeCampo) {
        return (in_array($nomeCampo, $this->camposDeData)) ? true : false;
    }

    public function retornaQueryTabela() {
        //if(a.column_name='senha','password,a.data_type) tipo_dado,
        return "select a.ordinal_position id_coluna,
                       a.column_name coluna,
                       a.is_nullable nulo,
                       if(a.column_name='senha','password',a.data_type) tipo_dado,
                       a.numeric_precision numerico,
                       if(a.data_type='date' or a.data_type='time',14,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) tamanho_campo,
                       if(a.data_type='date' or a.data_type='time',14,0) + ifnull(a.character_maximum_length,0) + ifnull(a.numeric_precision,0) + ifnull(a.numeric_scale,0) qtde_caracteres,
                       replace(replace(replace(if(a.data_type='enum',a.column_type,''),'enum(',''),')',''),'''','') valor_enum,
                       a.column_type enum,
                       if (a.extra = 'auto_increment',1,null) auto_increment,
                       a.column_key tipo_chave,
                       b.referenced_table_name tabela_ref
                  from information_schema.columns a 
                  left join information_schema.key_column_usage b 
                    on a.table_schema           = b.table_schema
                   and a.table_name             = b.table_name 
                   and a.column_name            = b.column_name
                   and b.referenced_table_name is not null
                 where a.table_schema           = '" . $this->schema . "'
                   and a.table_name             = '" . $this->tabela . "' 
                 order by a.ordinal_position";
    }

    function montarCampo($arrayCampo, $valorCampo) {
        $campoNumero = array("int","bigint","decimal","double","smallint","float");
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

        if (in_array($arrayCampo['tipo_dado'], $campoSenha)) {
            echo $this->inputPassword($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoArquivo)) {
            echo $this->inputFile($arrayCampo['coluna'], $arrayCampo['coluna'], $valorCampo);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoTextArea)) {
            echo $this->inputTextArea($arrayCampo['coluna'], $arrayCampo['coluna']);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoData)) {
            echo $this->inputDate($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai);
        } elseif (in_array($arrayCampo['tipo_dado'], $campoEnum)) {
            echo $this->selectMenuEnum($arrayCampo['coluna'], $arrayCampo['valor_enum'], $valorCampo);
        } elseif (($arrayCampo['tipo_chave'] == 'MUL' || $arrayCampo['tipo_chave'] == 'UNI') && $arrayCampo['tabela_ref'] != null) {
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

    function button($id, $tipo, $valor, $acao, $icone) {
        $ico = $icone != null || $icone != "" ? "{icon:'" . $icone . "'}" : "";
        $this->montarJS("$('#$id').puibutton(" . $ico . ");\n");
        return "<button id='$id' type='$tipo' $acao >$valor</button>\n";
    }

    function inputText($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable /></td>\n";
    }

    function inputNumber($id, $name, $size, $maxLength, $value, $enable) {
        $size = $size + 2;
        $this->montarJS("$('#" . $id . "').puispinner();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable /></td>\n";
    }

    function inputPassword($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#" . $id . "').puipassword({inline:true,promptLabel:'Informe a nova senha', weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});\n");
        return "<td><input type='password' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable /></td>\n";
    }

    function inputHidden($valor) {
        return "<td><input hidden='comando' value='$valor' /></td>\n";
    }

    function inputFile($id, $name, $valor) {
        return "<td><input type='file' id='$id' name='$name' value='$valor' /></td>\n";
    }

    function inputTextArea($id, $name) {
        $this->montarJS("$('#" . $id . "').puiinputtextarea();\n");
        return "<td><textarea id='$id' rows=\"10\" cols=\"30\" name='$name' style=\"width:100%;height:440px\" ></textarea></td>\n";
    }

    function inputDate($id, $name, $size, $maxLength, $value, $enable) {
        $this->montarJS("$('#" . $id . "').datepicker({dateFormat:'dd/mm/yy'}).puiinputtext();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable /></td>\n";
    }

    function selectMenuEnum($id, $valoresSelect, $valorSelecionado) {
        $enum = explode(',', $valoresSelect);
        $selectMenu = "\n<td><select id='$id' name='$id'>";
        foreach ($enum as $enum) {
            $selected = ($valorSelecionado == $enum) ? "selected" : "";
            $selectMenu .= "\n<option value='$enum' $selected >" . ucfirst($enum) . "</option>";
        }
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("$('#" . $id . "').puidropdown({filter: true});\n");
        return $selectMenu;
    }

    function selectMenu($id, $name, $tabelaRef, $valorSelecionado) {

        $selectMenu = "\n<td><select id='$id' name='$name'>\n";
        $selectMenu .= "\n<option value='' >Escolha...</option>\n";

        $json = new JSON($this->retornaView($tabelaRef));
        $array = json_decode($json->json(false, false), true);
        $option = "";

        foreach ($array as $i => $value) {
            foreach ($value as $j => $valor) {
                $selected = ($j == "id" && $valorSelecionado == $value[$j]) ? "selected" : "";
                $this->i0 = $j == "id" ? utf8_decode($value[$j]) : "";

                $option .= ($j == "id" ? "\n<option $selected value='".utf8_decode($value[$j])."'>" : "") . utf8_decode($value[$j]) . " | ";
            }
            $option = trim(substr($option, 0, (strlen($option) - 2))) . "</option>";
        }
        $selectMenu .= trim($option);
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("$('#" . $id . "').puidropdown({filter: true, filterMatchMode: 'contains'});\n");
        return $selectMenu;
    }

    private function retornaView($tabelaRef) {
        $view = mysql_query(
                "  select 1 ret "
                . "  from information_schema.views a "
                . " where a.table_schema = '" . $this->schema . "'"
                . "   and a.table_name   = 'v" . $tabelaRef . "'");
        $ret = mysql_fetch_row($view);

        if ($ret[0] == 1) {
            return "v" . $tabelaRef;
        } else {
            return $tabelaRef;
        }
    }
    
    public function verificaCampoDeData($campo) {
        $query = mysql_query(
                " select a.data_type tipo_dado
                    from information_schema.columns a
                   where a.table_schema = '$this->schema'
                     and a.table_name   = '$this->tabela' 
                     and a.column_name  = '$campo'");
        $tipoDado = mysql_fetch_array($query);
        return $tipoDado[0];
    }

}

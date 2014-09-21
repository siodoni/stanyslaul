<?php

class Update {

    private $coluna;
    private $tabela;
    private $schema;
    private $javaScript;
    private $inputFile;
    private $con;

    public function __construct($con) {
        $this->tabela = $_SESSION["nomeTabela"];
        $this->schema = $_SESSION["schema"];
        $this->con = $con;
    }

    public function adicionarColuna($coluna) {
        if ($this->coluna == null) {
            $this->coluna .= $coluna;
        } else {
            $this->coluna .= "," . $coluna;
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
        return Constantes::QUERY_TABLE;
    }

    function montarCampo($arrayCampo, $valorCampo) {
        $campoNumero = array("int","bigint","decimal","double","smallint","float");
        $campoSenha = array("password");
        $campoArquivo = array("file");
        $campoTextArea = array("longtext");
        $campoData = array("date","datetime","time");
        $campoEnum = array("enum");
        $ai = "";
        $required = "";

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
        
        if ($arrayCampo['nulo'] == "NO"){
            $required = "required";
        } else {
            $required = "";
        }

        if (in_array($arrayCampo['tipo_dado'], $campoSenha)) {
            echo $this->inputPassword($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai, $required);

        } elseif (in_array($arrayCampo['tipo_dado'], $campoArquivo)) {
            echo $this->inputFile($arrayCampo['coluna'], $arrayCampo['coluna'], $valorCampo);
        
        } elseif (in_array($arrayCampo['tipo_dado'], $campoTextArea)) {
            echo $this->inputTextArea($arrayCampo['coluna'], $arrayCampo['coluna'], $valorCampo, $required);

        } elseif (in_array($arrayCampo['tipo_dado'], $campoData)) {
            if ($arrayCampo['tipo_dado'] == 'date') {
                $valorCampo = date(str_replace("%","",Constantes::DATE_FORMAT), strtotime($valorCampo));
            } else if ($arrayCampo['tipo_dado'] == 'datetime') {
                $valorCampo = date(str_replace("%","",Constantes::DATETIME_FORMAT), strtotime($valorCampo));
            } else if ($arrayCampo['tipo_dado'] == 'time') {
                $valorCampo = date(str_replace("%","",Constantes::TIME_FORMAT), strtotime($valorCampo));
            }
            echo $this->inputDate($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai, $arrayCampo['tipo_dado'], $required);
        
        } elseif (in_array($arrayCampo['tipo_dado'], $campoEnum)) {
            echo $this->selectMenuEnum($arrayCampo['coluna'], $arrayCampo['valor_enum'], $valorCampo, $required);

        } elseif (($arrayCampo['tipo_chave'] == 'MUL' || $arrayCampo['tipo_chave'] == 'UNI') && $arrayCampo['tabela_ref'] != null) {
            echo $this->selectMenu($arrayCampo['coluna'], $arrayCampo['coluna'], $arrayCampo['tabela_ref'], $valorCampo, $required);

        } elseif (in_array($arrayCampo['tipo_dado'], $campoNumero)) {
            echo $this->inputNumber($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai, $required);

        } else {
            echo $this->inputText($arrayCampo['coluna'], $arrayCampo['coluna'], $tamCampo, $arrayCampo['qtde_caracteres'], $valorCampo, $ai, $required);
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

    function inputText($id, $name, $size, $maxLength, $value, $enable, $required) {
        $this->montarJS("$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable $required /></td>\n";
    }

    function inputNumber($id, $name, $size, $maxLength, $value, $enable, $required) {
        $size = $size + 2;
        $this->montarJS("$('#" . $id . "').puispinner();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable $required /></td>\n";
    }

    function inputPassword($id, $name, $size, $maxLength, $value, $enable, $required) {
        $this->montarJS("$('#" . $id . "').puipassword({inline:true,promptLabel:'Informe a nova senha', weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});\n");
        return "<td>"
             . "<input type='password' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable $required/>"
             . $this->inputHidden("_$id", "_$name", $value)
             . "</td>\n";
    }

    function inputHidden($id, $name, $valor) {
        return "<input id='$id' name='$name' type='hidden' value='$valor'/>\n";
    }

    function inputFile($id, $name, $valor) {
        if ($this->inputFile == null) {
            $this->inputFile = $id;
        } else {
            $this->inputFile .= $this->inputFile . "," . $id;
        }
        $this->montarJS("$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='file' id='$id' name='$name' value='$valor'/>".
                //$this->button("up".$id, "button", "Escolher...", "onclick=\"\"", "ui-icon-circle-plus") .
               ($valor != null ? $this->button("btn".$id, "button", "Visualizar", "onclick=\"window.open('".Config::FILE_FOLDER.$valor."');\"", "ui-icon-search") : "") .
               $this->inputHidden("_".$id, "_".$name, $valor) .
               " </td>\n";
    }

    function inputTextArea($id, $name, $valor, $required) {
        $this->montarJS("$('#" . $id . "').puiinputtextarea();\n");
        return "<td><textarea id='$id' rows=\"10\" cols=\"30\" name='$name' style=\"width:100%;height:440px\" $required>$valor</textarea></td>\n";
    }

    function inputDate($id, $name, $size, $maxLength, $value, $enable, $tipoDado, $required) {
        if ($tipoDado == "date") {
            $this->montarJS("$('#" . $id . "').datepicker({dateFormat:'dd/mm/yy'}).puiinputtext();\n");
        } else if ($tipoDado == "datetime") {
            $this->montarJS("$('#" . $id . "').datetimepicker({dateFormat:'dd/mm/yy',timeFormat:'HH:mm'}).puiinputtext();\n");
        } else if ($tipoDado == "time") {
            $this->montarJS("$('#" . $id . "').timepicker({timeFormat:'HH:mm'}).puiinputtext();\n");
        }
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='$value' $enable $required/></td>\n";
    }

    function selectMenuEnum($id, $valoresSelect, $valorSelecionado, $required) {
        $enum = explode(',', $valoresSelect);
        $selectMenu = "\n<td><select id='$id' name='$id' $required>";
        foreach ($enum as $enum) {
            $selected = ($valorSelecionado == $enum) ? "selected" : "";
            $selectMenu .= "\n<option value='$enum' $selected >" . ucfirst($enum) . "</option>";
        }
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("$('#" . $id . "').puidropdown({filter: true});\n");
        return $selectMenu;
    }

    function selectMenu($id, $name, $tabelaRef, $valorSelecionado, $required) {

        $selectMenu = "\n<td><select id='$id' name='$name' $required>\n";
        $selectMenu .= "\n<option value='' >Escolha...</option>\n";

        $json = new JSON($this->retornaView($this->con,$tabelaRef));
        $array = json_decode($json->json(false, false), true);
        $option = "";

        foreach ($array as $i => $value) {
            foreach ($value as $j => $valor) {
                $selected = ($j == "id" && $valorSelecionado == $value[$j]) ? "selected" : "";
                $this->i0 = $j == "id" ? utf8_decode($value[$j]) : "";
                $valor = utf8_decode(strlen($value[$j])>50?substr($value[$j],0,30)."...":$value[$j]);
                $option .= ($j == "id" ? "\n<option $selected value='".$value[$j]."'>" : "").$valor. " | ";
            }
            $option = trim(substr($option, 0, (strlen($option) - 2))) . "</option>";
        }
        $selectMenu .= trim($option);
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("$('#" . $id . "').puidropdown({filter: true, filterMatchMode: 'contains'});\n");
        return $selectMenu;
    }

    private function retornaView($con, $tabelaRef) {
        $view = "v".$tabelaRef;
        $rs = $con->prepare(
                "  select 1 ret "
                . "  from information_schema.views a "
                . " where a.table_schema = ? "
                . "   and a.table_name   = ? ");
        $rs->bindParam(1, $this->schema);
        $rs->bindParam(2, $view);
        $rs->execute();
        $ret = $rs->fetch(PDO::FETCH_NUM);

        if ($ret[0] == 1) {
            return $view;
        } else {
            return $tabelaRef;
        }
    }
    
    function getDateFormat(){
        return Constantes::DATE_FORMAT;
    }

    function getDateTimeFormat(){
        return Constantes::DATETIME_FORMAT;
    }

    function getTimeFormat(){
        return Constantes::TIME_FORMAT;
    }    
    
    function getInputFile(){
        return $this->inputFile;
    }
}
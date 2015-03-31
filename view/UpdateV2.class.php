<?php

class UpdateV2 {

    private $coluna;
    private $tabela;
    private $schema;
    private $javaScript;
    private $inputFile;
    private $con;

    public function __construct($con) {
        $this->tabela = isset($_SESSION["nomeTabela"]) ? $_SESSION["nomeTabela"] : $_REQUEST["nomeTabela"];
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
        return Constantes::QUERY_TABLEV2;
    }

    function montarCampo($arrayCampo, $valorCampo) {
        $ai            = "";
        $required      = "";

        if ($arrayCampo['tamanho_campo'] > 100) {
            $tamCampo = 80;
        } else {
            $tamCampo = $arrayCampo['tamanho_campo'];
        }

        if ($arrayCampo['fg_auto_incremento'] == "SIM") {
            $ai = "disabled=\"disabled\"";
        } else {
            $this->adicionarColuna($arrayCampo['nome_coluna']);
        }
        
        if ($arrayCampo['fg_obrigatorio'] == "SIM"){
            $required = "required";
        } else {
            $required = "";
        }

        if ($arrayCampo['tipo_dado'] == 'SENHA') {
            echo $this->inputPassword($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$required);

        } elseif ($arrayCampo['tipo_dado'] == 'ARQUIVO') {
            echo $this->inputFile($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$valorCampo);

        } elseif ($arrayCampo['tipo_dado'] == 'TEXTO LONGO') {
            echo $this->inputTextArea($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$valorCampo,$required);

        } elseif ($arrayCampo['tipo_dado'] == 'DATA') {
            $valorCampo = date(str_replace("%","",$arrayCampo["formato_data"]), strtotime($valorCampo));
            echo $this->inputDate($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$arrayCampo['formato_data'],$required);

        } elseif ($arrayCampo['tipo_dado'] == 'ENUM') {
            echo $this->selectMenuEnum($arrayCampo['nome_coluna'],$arrayCampo['valor_enum'],$valorCampo,$required);

        } elseif ($arrayCampo['tipo_dado'] == 'LISTA VALOR') {
            echo $this->selectMenu($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$arrayCampo['tabela_ref'],$valorCampo,$required);

        } elseif ($arrayCampo['tipo_dado'] == 'NUMÉRICO') {
            echo $this->inputNumber($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$required);

        } else {
            echo $this->inputText($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$required);
        }
    }

    /* Campos */

    function label($arrayCampo) {
        return "<tr><td>" . ucwords(str_replace("_", " ", str_replace("fi_", "", $arrayCampo['titulo_coluna']))) . "</td>\n";
    }

    function button($id, $tipo, $valor, $acao, $icone) {
        $ico = $icone != null || $icone != "" ? "{icon:'" . $icone . "'}" : "";
        $this->montarJS("\t\t$('#$id').puibutton(" . $ico . ");\n");
        return "<button id='$id' type='$tipo' $acao >$valor</button>\n";
    }

    function inputText($id, $name, $size, $maxLength, $value, $enable, $required) {
        $this->montarJS("\t\t$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required /></td>\n";
    }

    function inputNumber($id, $name, $size, $maxLength, $value, $enable, $required) {
        $size = $size + 2;
        $this->montarJS("\t\t$('#" . $id . "').puispinner();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required /></td>\n";
    }

    function inputPassword($id, $name, $size, $maxLength, $value, $enable, $required) {
        $this->montarJS("\t\t$('#" . $id . "').puipassword({inline:true,promptLabel:'Informe a nova senha', weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});\n");
        return "<td><input type='password' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required/>" . $this->inputHidden("_$id", "_$name", $value) . "</td>\n";
    }

    function inputHidden($id, $name, $valor) {
        return "<input id='$id' name='$name' type='hidden' value='". (isset($_POST[$name]) ? $_POST[$name] : $valor) ."'/>\n";
    }

    function inputFile($id, $name, $valor) {
        if ($this->inputFile == null) {
            $this->inputFile = $id;
        } else {
            $this->inputFile .= $this->inputFile . "," . $id;
        }
        $this->montarJS("\t\t$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='file' id='$id' name='$name' value='". (isset($_POST[$name]) ? $_POST[$name] : $valor) ."'/>".
                //$this->button("up".$id, "button", "Escolher...", "onclick=\"\"", "ui-icon-circle-plus") .
               ($valor != null ? $this->button("btn".$id, "button", "Visualizar", "onclick=\"window.open('".Config::FILE_FOLDER.$valor."');\"", "ui-icon-search") : "") .
               $this->inputHidden("_".$id, "_".$name, $valor) . " </td>\n";
    }

    function inputTextArea($id, $name, $valor, $required) {
        $this->montarJS("\t\t$('#" . $id . "').puiinputtextarea();\n");
        return "<td><textarea id='$id' rows=\"10\" cols=\"30\" name='$name' style=\"width:100%;height:440px\" $required>".(isset($_POST[$name])?$_POST[$name]:$valor)."</textarea></td>\n";
    }

    function inputDate($id, $name, $size, $maxLength, $value, $enable, $formato, $required) {
        if ($formato == "%d/%m/%Y") {
            $this->montarJS("\t\t$('#" . $id . "').datepicker({dateFormat:'dd/mm/yy'}).puiinputtext();\n");
        } else if ($formato == "%d/%m/%Y %H:%i") {
            $this->montarJS("\t\t$('#" . $id . "').datetimepicker({dateFormat:'dd/mm/yy',timeFormat:'HH:mm'}).puiinputtext();\n");
        } else if ($formato == "%k:%i") {
            $this->montarJS("\t\t$('#" . $id . "').timepicker({timeFormat:'HH:mm'}).puiinputtext();\n");
        }
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required/></td>\n";
    }

    function selectMenuEnum($id, $valoresSelect, $valorSelecionado, $required) {
        $enum = explode(',', $valoresSelect);
        $selectMenu = "\n<td><select id='$id' name='$id' $required>";
        foreach ($enum as $enum) {
            $selected = ((isset($_POST[$id]) ? $_POST[$id] : $valorSelecionado) == $enum) ? "selected" : "";
            $selectMenu .= "\n<option value='$enum' $selected >" . ucfirst($enum) . "</option>";
        }
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("\t\t$('#" . $id . "').puidropdown({filter: true});\n");
        return $selectMenu;
    }

    function selectMenu($id, $name, $tabelaRef, $valorSelecionado, $required) {

        $selectMenu = "\n<td><select id='$id' name='$name' $required>\n";
        $selectMenu .= "\n<option value='' >Escolha...</option>\n";

        $json = new JSON($this->retornaView($this->con,$tabelaRef),$this->con,true);
        $array = json_decode($json->json(false), true);
        $option = "";

        foreach ($array as $i => $value) {
            foreach ($value as $j => $valor) {
                $selected = ($j == "id" && (isset($_POST[$name]) ? $_POST[$name] : $valorSelecionado) == $value[$j]) ? "selected" : "";
                $this->i0 = $j == "id" ? utf8_decode($value[$j]) : "";
                $valor = utf8_decode(strlen($value[$j])>50?substr($value[$j],0,30)."...":$value[$j]);
                $option .= ($j == "id" ? "\n<option $selected value='".$value[$j]."'>" : "").$valor. " | ";
            }
            $option = trim(substr($option, 0, (strlen($option) - 2))) . "</option>";
        }
        $selectMenu .= trim($option);
        $selectMenu .= "\n</select></td>\n";
        $this->montarJS("\t\t$('#" . $id . "').puidropdown({filter: true, filterMatchMode: 'contains'});\n");
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
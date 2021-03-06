<?php

class UpdateV2 {

    private $coluna;
    private $idMenu;
    private $schema;
    private $javaScript;
    private $inputFile;
    private $con;

    public function __construct($con) {
        $this->idMenu = isset($_SESSION["idMenu"]) ? $_SESSION["idMenu"] : $_REQUEST["idMenu"];
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
        
        if ($arrayCampo['fg_obrigatorio']     == "SIM"
        &&  $arrayCampo['fg_auto_incremento'] != "SIM"){
            $required = "class='input-required'";
            $msgErro  = "<span class='span-msg-error'></span>";
        } else {
            $required = "class='input-normal'";
            $msgErro  = "<span class='span-msg-normal'></span>";
        }
        
        if ($arrayCampo['tipo_dado'] == 'SENHA') {
            echo $this->inputPassword($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$required,$msgErro,$arrayCampo['hint_campo']);

        } elseif ($arrayCampo['tipo_dado'] == 'ARQUIVO') {
            echo $this->inputFile($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$valorCampo,$msgErro,$arrayCampo['hint_campo']);

        } elseif ($arrayCampo['tipo_dado'] == 'TEXTO LONGO') {
            echo $this->inputTextArea($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$valorCampo,$required,$msgErro,$arrayCampo['hint_campo']);

        } elseif ($arrayCampo['tipo_dado'] == 'DATA'
               || $arrayCampo['tipo_dado'] == 'DATA HORA'
               || $arrayCampo['tipo_dado'] == 'HORA') {
            $valorCampo = date(str_replace("%","",$arrayCampo["formato_data"]), strtotime($valorCampo));
            echo $this->inputDate($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$arrayCampo['tipo_dado'],$required,$msgErro,$arrayCampo['hint_campo']);

        } elseif ($arrayCampo['tipo_dado'] == 'ENUM') {
            echo $this->selectMenuEnum($arrayCampo['nome_coluna'],$arrayCampo['valor_enum'],$valorCampo,$required,$msgErro,$arrayCampo['hint_campo']);

        } elseif ($arrayCampo['tipo_dado'] == 'LISTA VALOR') {
            echo $this->selectMenu($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$arrayCampo['tabela_ref'],$valorCampo,$required,$msgErro,$arrayCampo['hint_campo']);

        } elseif ($arrayCampo['tipo_dado'] == 'NUMÉRICO') {
            echo $this->inputNumber($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$required,$msgErro,$arrayCampo['hint_campo']);

        } else {
            echo $this->inputText($arrayCampo['nome_coluna'],$arrayCampo['nome_coluna'],$tamCampo,$arrayCampo['qtd_caracteres'],$valorCampo,$ai,$required,$msgErro,$arrayCampo['hint_campo']);
        }
    }

    /* Campos */

    function label($arrayCampo,$required) {
        return "<tr><td>" . $arrayCampo['titulo_coluna'] . ($required == "SIM" ? " (*)" : "") . "</td>\n";
    }

    function button($id, $tipo, $valor, $acao, $icone,$hint=null) {
        $ico = $icone != null || $icone != "" ? "{icon:'" . $icone . "'}" : "";
        $this->montarJS("\t\t$('#$id').puibutton(" . $ico . ");\n");
        return "<button id='$id' type='$tipo' $acao title='$hint'>$valor</button>\n";
    }

    function inputText($id, $name, $size, $maxLength, $value, $enable, $required, $msgErro,$hint=null) {
        $this->montarJS("\t\t$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required title='$hint'/></td><td>".$msgErro."</td>\n";
    }

    function inputNumber($id, $name, $size, $maxLength, $value, $enable, $required, $msgErro,$hint=null) {
        $size = $size + 2;
        $this->montarJS("\t\t$('#" . $id . "').puispinner();\n");
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required title='$hint'/></td><td>".$msgErro."</td>\n";
    }

    function inputPassword($id, $name, $size, $maxLength, $value, $enable, $required, $msgErro,$hint=null) {
        $this->montarJS("\t\t$('#" . $id . "').puipassword({inline:true,promptLabel:'Informe a nova senha', weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});\n");
        return "<td><input type='password' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required title='$hint'/>" . $this->inputHidden("_$id", "_$name", $value) . "</td><td>".$msgErro."</td>\n";
    }

    function inputHidden($id, $name, $valor,$hint=null) {
        return "<input id='$id' name='$name' type='hidden' value='". (isset($_POST[$name]) ? $_POST[$name] : $valor) ."' title='$hint'/>\n";
    }

    function inputFile($id, $name, $valor, $msgErro,$hint=null) {
        if ($this->inputFile == null) {
            $this->inputFile = $id;
        } else {
            $this->inputFile .= $this->inputFile . "," . $id;
        }
        $this->montarJS("\t\t$('#" . $id . "').puiinputtext();\n");
        return "<td><input type='file' id='$id' name='$name' value='". (isset($_POST[$name]) ? $_POST[$name] : $valor) ."' title='$hint'/>".
               ($valor != null ? $this->button("btn".$id, "button", "Visualizar", "onclick=\"window.open('".Config::FILE_FOLDER.$valor."');\"", "ui-icon-search",$hint) : "") .
               $this->inputHidden("_".$id, "_".$name, $valor) . " </td><td>".$msgErro."</td>\n";
    }

    function inputTextArea($id, $name, $valor, $required, $msgErro,$hint=null) {
        $this->montarJS("\t\t$('#" . $id . "').puiinputtextarea();\n");
        return "<td><textarea id='$id' rows=\"10\" cols=\"30\" name='$name' style=\"width:100%;height:440px\" $required title='$hint'>".(isset($_POST[$name])?$_POST[$name]:$valor)."</textarea></td><td>".$msgErro."</td>\n";
    }

    function inputDate($id, $name, $size, $maxLength, $value, $enable, $formato, $required, $msgErro,$hint=null) {
        if ($formato == "DATA") {
            $this->montarJS("\t\t$('#" . $id . "').datepicker({dateFormat:'dd/mm/yy'}).puiinputtext();\n");
        } else if ($formato == "DATA HORA") {
            $this->montarJS("\t\t$('#" . $id . "').datetimepicker({dateFormat:'dd/mm/yy',timeFormat:'HH:mm'}).puiinputtext();\n");
        } else if ($formato == "HORA") {
            $this->montarJS("\t\t$('#" . $id . "').timepicker({timeFormat:'HH:mm'}).puiinputtext();\n");
        }
        return "<td><input type='text' id='$id' name='$name' size='$size' maxlength='$maxLength' value='". (isset($_POST[$name]) ? $_POST[$name] : $value) ."' $enable $required title='$hint'/></td><td>".$msgErro."</td>\n";
    }

    function selectMenuEnum($id, $valoresSelect, $valorSelecionado, $required, $msgErro,$hint=null) {
        $enum = explode(',', $valoresSelect);
        $selectMenu = "\n<td><select id='$id' name='$id' $required title='$hint'>";
        foreach ($enum as $enum) {
            $selected = ((isset($_POST[$id]) ? $_POST[$id] : $valorSelecionado) == $enum) ? "selected" : "";
            $selectMenu .= "\n<option value='$enum' $selected >" . ucfirst($enum) . "</option>";
        }
        $selectMenu .= "\n</select></td><td>".$msgErro."</td>\n";
        $this->montarJS("\t\t$('#" . $id . "').puidropdown({filter: true});\n");
        return $selectMenu;
    }

    function selectMenu($id, $name, $tabelaRef, $valorSelecionado, $required, $msgErro,$hint=null) {

        $selectMenu = "\n<td><select id='$id' name='$name' $required title='$hint'>\n";
        $selectMenu .= "\n<option value='' >Escolha...</option>\n";

        $json = new JSON($this->retornaView($this->con,$tabelaRef),$this->con,true);
        $array = json_decode($json->json(false), true);
        $option = "";

        foreach ($array as $i => $value) {
            foreach ($value as $j => $valor) {
                $selected = ($j == "id__tabela__lov" && (isset($_POST[$name]) ? $_POST[$name] : $valorSelecionado) == $value[$j]) ? "selected" : "";
                $this->i0 = $j == "id__tabela__lov" ? utf8_decode($value[$j]) : "";
                $valor = utf8_decode(strlen($value[$j])>50?substr($value[$j],0,30)."...":$value[$j]);
                $option .= ($j == "id__tabela__lov" ? "\n<option $selected value='".$value[$j]."'>" : "").$valor. " | ";
            }
            $option = trim(substr($option, 0, (strlen($option) - 2))) . "</option>";
        }
        $selectMenu .= trim($option);
        $selectMenu .= "\n</select></td><td>".$msgErro."</td>\n";
        $this->montarJS("\t\t$('#" . $id . "').puidropdown({filter: true, filterMatchMode: 'contains'});\n");

        //echo "$id, $name, $tabelaRef, $valorSelecionado, $required, $msgErro, $hint <br>";
        
        return $selectMenu;
    }

    private function retornaView($con, $tabelaRef) {
        //TODO verificar esse método (UpdateV2.retornaView)
        $view = "v".$tabelaRef;
        //TODO passar select para a classe de Constantes
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
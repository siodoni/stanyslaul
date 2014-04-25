<?php

include_once 'Constantes.class.php';

class Estrutura extends Contantes {

    private $javaScript;
    
    public function head() {
        return "\n<head>" .
               $this->title() .
               $this->meta() .
               $this->script() .
               $this->css() .
               "\n</head>\n";
    }

    public function title() {
        return "\n<title>" . parent::TITLE . "</title>";
    }
    
    public function meta(){
        return "\n<meta charset='iso-8859-1'></meta>";
    }

    public function script() {
        return "\n<script type='text/javascript' src='".$this->nomeArquivo("res/jquery-1.11.0.min.js")        ."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/jquery-ui.min.js")            ."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/primeui-1.0-min.js")          ."'></script>".
             //"\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/jquery.validation.js")     ."'></script>".
             //"\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/jquery.validation.ajax.js")."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/stanyslaul.js")            ."'></script>";
    }

    public function css() {
        return "\n<link href='".$this->nomeArquivo("res/primeui-1.0-min.css")          . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/jquery-ui.min.css")            . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/primeui.all.css")          . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/stanyslaul.css")           . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/stanyslaul.table.css")     . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/stanyslaul.all.css")       . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/themes/redmond/theme.css") . "' rel='stylesheet'>";
    }
    
    public function montarJS($texto) {
        $this->javaScript .= $texto;
    }
    
    public function retornaJS() {
        return $this->javaScript;
    }

    private function nomeArquivo($file){
        if (file_exists($file)) {
            return $file."?st=".filemtime($file);
        }
    }
}
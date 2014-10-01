<?php

class Estrutura {

    private $diretorio = "";
    
    public function __construct($dir = "") {
        $this->diretorio = $dir;
    }

    public function head() {
        return "\n<head>" .
               $this->title() .
               $this->meta() .
               $this->script() .
               $this->css() .
               "\n</head>\n";
    }

    public function title() {
        return "\n<title>" . Config::TITLE . "</title>";
    }
    
    public function meta(){
        return "\n<meta charset='iso-8859-1'></meta>";
    }

    public function script() {
        return "\n<script type='text/javascript' src='".$this->nomeArquivo("res/jquery-1.11.0.min.js")                ."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/jquery-ui.min.js")                    ."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/jquery.ui.timepicker.addon.min.js")."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/primeui-1.1-min.js")                  ."'></script>".
             //"\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/jquery.validation.js")             ."'></script>".
             //"\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/jquery.validation.ajax.js")        ."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/stanyslaul.js")                    ."'></script>";
    }

    public function css() {
        return "\n<link href='".$this->nomeArquivo("res/primeui-1.1-min.css")          . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/jquery-ui.min.css")            . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/primeui.all.css")          . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/stanyslaul.css")           . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/stanyslaul.table.css")     . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/stanyslaul.all.css")       . "' rel='stylesheet'>".
               "\n<link href='".$this->nomeArquivo("res/css/themes/redmond/theme.css") . "' rel='stylesheet'>";
    }

    public function dialogAguarde(){
        return "\n<div id='dlgCarregando' title='Carregando...' class='st-dlg-carregando'>"
             . "\n<img src='res/images/ico-loading.gif'/>"
             . "</div>";
    }

    public function nomeArquivo($file){
        return file_exists($this->diretorio.$file) ? $this->diretorio.$file."?st=".filemtime($this->diretorio.$file) : "";
    }
}
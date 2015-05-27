<?php

class Estrutura {

    private $diretorio = "";
    
    public function __construct($dir = "") {
        $this->diretorio = $dir;
    }

    public function head($dataTable=false) {
        return "\n<head>" .
               $this->title() .
               $this->meta() .
               $this->script() .
               $this->css() .
              ($dataTable ? $this->scriptDT() : "") .
              ($dataTable ? $this->cssDT()    : "") .
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
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/stanyslaul.js")                    ."'></script>";
    }

    public function scriptDT() {
        return "\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/jquery.dataTables.min.js")."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/js/dataTables.jqueryui.js")  ."'></script>".
               "\n<script type='text/javascript' src='".$this->nomeArquivo("res/plugins/date-uk.js")         ."'></script>";
    }

    public function css() {
        return "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/primeui-1.1-min.css")         ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/jquery-ui.min.css")           ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/primeui.all.css")         ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/stanyslaul.css")          ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/stanyslaul.table.css")    ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/stanyslaul.all.css")      ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/themes/redmond/theme.css")."'>";
    }

    public function cssDT() {
        return "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/dataTables.jqueryui.css") ."'>".
               "\n<link rel='stylesheet' href='".$this->nomeArquivo("res/css/themes/redmond/theme.css")."'>";
    }
    
    public function dialogAguarde(){
        return "\n<div id='dlgCarregando' title='Carregando...' class='st-dlg-carregando'>"
             . "\n<img src='res/images/ico-loading.gif'/>"
             . "\n</div>";
    }

    public function nomeArquivo($file){
        return file_exists($this->diretorio.$file) ? $this->diretorio.$file."?st=".filemtime($this->diretorio.$file) : "";
    }
}
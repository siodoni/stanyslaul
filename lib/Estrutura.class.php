<?php
include_once 'Constantes.class.php';

class Estrutura extends Contantes {

    public function head() {
        return "\n<head>" .
               $this->title() .
               $this->script() .
               $this->css() .
               "\n</head>\n";
    }

    public function title() {
        return "\n<title>" . parent::TITLE . "</title>";
    }

    public function script() {
        return "\n<script type='text/javascript' src='jquery/jquery-1.11.0.min.js'> </script>" .
               "\n<script type='text/javascript' src='jquery/jquery-ui.min.js'>     </script>" .
               "\n<script type='text/javascript' src='prime/primeui-1.0-min.js'>    </script>";
    }

    public function css() {
        return "\n<link href='prime/primeui-1.0-min.css' rel='stylesheet'>" .
               "\n<link href='jquery/jquery-ui.min.css'  rel='stylesheet'>" .
               "\n<link href='prime/css/all.css'         rel='stylesheet'>";
    }
}
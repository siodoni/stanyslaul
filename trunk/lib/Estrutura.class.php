<?php
include_once 'Constantes.class.php';

class Estrutura extends Contantes {

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
        return "\n<script type='text/javascript' src='res/jquery-1.11.0.min.js'>        </script>".
               "\n<script type='text/javascript' src='res/jquery-ui.min.js'>            </script>".
               "\n<script type='text/javascript' src='res/primeui-1.0-min.js'>          </script>".
             //"\n<script type='text/javascript' src='res/js/jquery.validation.js'>     </script>".
             //"\n<script type='text/javascript' src='res/js/jquery.validation.ajax.js'></script>".
               "\n<script type='text/javascript' src='res/js/stanyslaul.js'>            </script>"
            ;
    }

    public function css() {
        return "\n<link href='res/primeui-1.0-min.css'          rel='stylesheet'>".
               "\n<link href='res/jquery-ui.min.css'            rel='stylesheet'>".
               "\n<link href='res/css/primeui.all.css'          rel='stylesheet'>".
               "\n<link href='res/css/stanyslaul.css'           rel='stylesheet'>".
               "\n<link href='res/css/stanyslaul.table.css'     rel='stylesheet'>".
               "\n<link href='res/css/stanyslaul.all.css'       rel='stylesheet'>".
               "\n<link href='res/css/themes/redmond/theme.css' rel='stylesheet'>";
    }
}
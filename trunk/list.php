<?php
session_start("stanyslaul");

require_once 'lib/Estrutura.class.php';
$estrutura = new Estrutura();
?>
<!DOCTYPE html>
<html>
    <?php
    echo $estrutura->head();
    ?>
    <body id="admin">
        <script type="text/javascript">
            $(function() {
                $('#menu').puibutton({
                    icon: 'ui-icon-home'
                });
                $('#novo').puibutton({
                    icon: 'ui-icon-document'
                });
                $('#editar').puibutton({
                    icon: 'ui-icon-pencil'
                });
                $('#excluir').puibutton({
                    icon: 'ui-icon-trash'
                });

                // MENSAGENS
                $('#mensagens').puigrowl();

                // DATATABLE
                $('#tabela').puidatatable({
            <?php
                require_once 'lib/JSON.class.php';
                $tabela = $_POST["nomeTabela"];
                $json = new JSON($tabela);
                $_SESSION["nomeTabela"] = $tabela;

                echo montarCabecalho($json->getTabela(), 10);
                
                echo dataSource("json.php");
                
                echo $json->columns();
                echo "selectionMode: 'single',\n";
                echo "rowSelect: function(event, data) {\n";
                echo "  $('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Selected', detail: ('ID: ' + data.id)}]);\n";
                echo "},\n";
                echo "rowUnselect: function(event, data) {\n";
                echo "  $('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Unselected', detail: ('ID: ' + data.id)}]);\n";
                echo "}\n";
            ?>
                });
            });
        </script>
        <div id="mensagens"></div>  
        <div id="tabela"></div>

        <button id="menu"   onclick="window.location='index.php';" >Menu</button>
        <button id="novo"   onclick="window.location='update.php';">Novo</button>
        <button id="editar" >Editar</button>
        <button id="excluir">Excluir</button>

        <br/>
        
        <a href="http://www.pm-consultant.fr/primeui/">http://www.pm-consultant.fr/primeui/</a>
    </body>
</html>
<?php

function montarCabecalho($nomeTabela, $qtdPaginas) {

    $cabecalho = "caption: '".ucwords(str_replace("_", " ", $nomeTabela))."', \n";
    $cabecalho .= "paginator: {\n";
    $cabecalho .= "  rows: $qtdPaginas\n";
    $cabecalho .= "},\n";

    return $cabecalho;
}

function dataSource($url) {
    
    $ds = "datasource: function(callback) {\n";
    $ds .= "  $.ajax({\n";
    $ds .=  "      type: \"GET\",\n";
    $ds .=  "      url: '$url',\n";
    $ds .=  "      dataType: \"json\",\n";
    $ds .=  "      context: this,\n";
    $ds .=  "      success: function(response) {\n";
    $ds .=  "          callback.call(this, response);\n";
    $ds .=  "      }\n";
    $ds .=  "  });\n";
    $ds .=  "},\n";
    
    return $ds;
}

<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="jquery/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="prime/primeui-1.0-min.js"></script>

        <link href="prime/primeui-1.0-min.css" rel="stylesheet">
        <link href="jquery/jquery-ui.min.css" rel="stylesheet">
        <link href="prime/css/all.css" rel="stylesheet">
    </head>
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
                    $json = new JSON($_POST["nomeTabela"]);
                    $_SESSION["nomeTabela"] = $_POST["nomeTabela"];

                    echo "caption: '".$json->getTabela()."',\n";
                    echo "paginator: {\n";
                    echo "  rows: 10\n";
                    echo "},\n";
                    echo "datasource: function(callback) {\n";
                    echo "  $.ajax({\n";
                    echo "      type: \"GET\",\n";
                    echo "      url: 'json.php',\n";
                    echo "      dataType: \"json\",\n";
                    echo "      context: this,\n";
                    echo "      success: function(response) {\n";
                    echo "          callback.call(this, response);\n";
                    echo "      }\n";
                    echo "  });\n";
                    echo "},\n";
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

        <button id="menu" onclick="window.location='index.php';">Menu</button>
        <button id="novo">Novo</button>
        <button id="editar">Editar</button>
        <button id="excluir">Excluir</button>
        
        <br/>
        <a href="http://www.pm-consultant.fr/primeui/">http://www.pm-consultant.fr/primeui/</a>
    </body>
</html>
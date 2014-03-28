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
                // ************************* MENSAGENS *************************
                $('#messages').puigrowl();
                
                // ************************* DATATABLE *************************
                $('#tblremoteeager').puidatatable({
                    caption: 'Remote Restful Webservice',
                    paginator: {
                        rows: 10
                    },
                    columns: [
                        {field: 'id', headerText: 'Vin', sortable: true},
                        {field: 'descricao', headerText: 'Brand', sortable: true},
                        {field: 'tabela02', headerText: 'Year', sortable: true}
                    ],
                    datasource: function(callback) {
                        $.ajax({
                            type: "GET",
                            url: 'http://localhost/stanyslaul/json.php?nomeTabela=tabela01',
                            dataType: "json",
                            context: this,
                            success: function(response) {
                                callback.call(this, response);
                            }
                        });
                    },
                    selectionMode: 'single',
                    rowSelect: function(event, data) {
                        $('#messages').puigrowl('show', [{severity: 'info', summary: 'Row Selected', detail: (data.id + ' ' + data.descricao)}]);
                    },
                    rowUnselect: function(event, data) {
                        $('#messages').puigrowl('show', [{severity: 'info', summary: 'Row Unselected', detail: (data.id + ' ' + data.descricao)}]);
                    }
                });              
            });
        </script>  
        <div id="messages"></div>  
        <div id="tblremoteeager"></div>

        <a href="http://www.pm-consultant.fr/primeui/">http://www.pm-consultant.fr/primeui/</a>
    </body>
</html>
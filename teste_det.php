<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header('location:index.php');
}
?>
<script type='text/javascript' src='res/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='res/js/dataTables.jqueryui.js'></script>
<script type='text/javascript' src='res/plugins/date-uk.js'></script>

<link rel='stylesheet' href='res/css/dataTables.jqueryui.css'>
<link rel='stylesheet' href='res/css/themes/redmond/theme.css'>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            ajax: "jsonV2.php",
            columns: [
                {data: "id"},
                {data: "nome"},
                {data: "data_cad"}
            ],
            deferRender: true,
            stateSave: true,
            language: {
                url: "res/portuguese-brasil.json"
            },
            columnDefs: [
                {type: 'de_date', targets: 2}
            ]
        });
        $('#dataTable tbody').on('click', 'tr', function() {
            window.open('updateV2.php?id=' + $('td', this).eq(0).text(), '_self');
        });
    });
</script>
<table id="dataTable" class="cell-border hover order-column" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Data</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Data</th>
        </tr>
    </tfoot>
</table>
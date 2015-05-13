<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header('location:index.php');
}
?>
<script type='text/javascript' src='res/js/jquery.dataTables.min.js'></script>
<script type='text/javascript' src='res/js/dataTables.jqueryui.js'></script>

<link rel='stylesheet' href='res/css/dataTables.jqueryui.css'>
<link rel='stylesheet' href='res/css/themes/redmond/theme.css'>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            ajax: "jsonV2.php",
            processing: true,
            columns: [
                {data: "id"},
                {data: "nome"}
            ],
            deferRender: true,
            stateSave: true,
            language: {
                url: "res/portuguese-brasil.json"
            }
        });
        $('#example tbody').on('click', 'tr', function() {
            window.open('updateV2.php?id='+$('td', this).eq(0).text(),'_self');
        });
    });
</script>
<table id="example" class="cell-border hover order-column" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nome</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Id</th>
            <th>Nome</th>
        </tr>
    </tfoot>
</table>
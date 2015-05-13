<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header('location:index.php');
}
?>
<html>
    <head>
        <script type='text/javascript' src='res/js/stanyslaul.js'></script>
        <script type='text/javascript' src='res/js/jquery-1.11.1.min.js'></script>
        <script type='text/javascript' src='res/jquery-ui.min.js'></script>
        <script type='text/javascript' src='res/dt/js/jquery.dataTables.min.js'></script>
        <script type='text/javascript' src='res/dt/plugins/jquerytheme/dataTables.jqueryui.js'></script>

        <link href='res/css/stanyslaul.css' rel='stylesheet'>
        <link href='res/css/stanyslaul.table.css' rel='stylesheet'>
        <link href='res/css/stanyslaul.all.css' rel='stylesheet'>
        <link href='res/dt/plugins/jquerytheme/dataTables.jqueryui.css' rel='stylesheet'>
        <link href='res/jquery-ui.min.css' rel='stylesheet'>
        <link href='res/css/themes/redmond/theme.css' rel='stylesheet'>
    </head>
    <body>
        <script>
            $(document).ready(function() {
                $('#example').DataTable();
            });
        </script>

        <table id="example" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
            </tfoot>

            <tbody>
                <tr>
                    <td>Tiger Nixon</td>
                    <td>System Architect</td>
                    <td>Edinburgh</td>
                    <td>61</td>
                    <td>2011/04/25</td>
                    <td>$320,800</td>
                </tr>
                <tr>
                    <td>Garrett Winters</td>
                    <td>Accountant</td>
                    <td>Tokyo</td>
                    <td>63</td>
                    <td>2011/07/25</td>
                    <td>$170,750</td>
                </tr>
                <tr>
                    <td>Ashton Cox</td>
                    <td>Junior Technical Author</td>
                    <td>San Francisco</td>
                    <td>66</td>
                    <td>2009/01/12</td>
                    <td>$86,000</td>
                </tr>
                <tr>
                    <td>Cedric Kelly</td>
                    <td>Senior Javascript Developer</td>
                    <td>Edinburgh</td>
                    <td>22</td>
                    <td>2012/03/29</td>
                    <td>$433,060</td>
                </tr>
                <tr>
                    <td>Airi Satou</td>
                    <td>Accountant</td>
                    <td>Tokyo</td>
                    <td>33</td>
                    <td>2008/11/28</td>
                    <td>$162,700</td>
                </tr>
                <tr>
                    <td>Brielle Williamson</td>
                    <td>Integration Specialist</td>
                    <td>New York</td>
                    <td>61</td>
                    <td>2012/12/02</td>
                    <td>$372,000</td>
                </tr>
            </tbody>
        </table>        
    </body>
</html>
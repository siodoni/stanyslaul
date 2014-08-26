<!DOCTYPE html>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta charset='iso-8859-1'></meta>
        <script type='text/javascript' src='../res/jquery-1.11.0.min.js?st=1405115246'></script>
        <script type='text/javascript' src='../res/jquery-ui.min.js?st=1405115246'></script>
        <script type='text/javascript' src='../res/js/jquery.ui.timepicker.addon.min.js?st=1405115256'></script>
        <script type='text/javascript' src='../res/primeui-1.0-min.js?st=1405115246'></script>
        <script type='text/javascript' src='../res/js/stanyslaul.js?st=1405115256'></script>
        <link href='../res/primeui-1.0-min.css?st=1405115246' rel='stylesheet'>
        <link href='../res/jquery-ui.min.css?st=1405115245' rel='stylesheet'>
        <link href='../res/css/primeui.all.css?st=1405115258' rel='stylesheet'>
        <link href='../res/css/stanyslaul.css?st=1405115259' rel='stylesheet'>
        <link href='../res/css/stanyslaul.table.css?st=1405115259' rel='stylesheet'>
        <link href='../res/css/stanyslaul.all.css?st=1405115259' rel='stylesheet'>
        <link href='../res/css/themes/redmond/theme.css?st=1405115266' rel='stylesheet'>
    </head>
    <body>
        <script type='text/javascript'>
            $(function() {
                $('#dlgCarregando').puidialog({
                    modal: true,
                    resizable: false,
                    width: 220
                });
            });
            
            $(document).ready(function() {
               $('#dlgCarregando').puidialog('show'); 
            });
            
            $(document).ready(function() {
                //$('#dlgCarregando').puidialog('hide');
            });
        </script>
        <div id='dlgCarregando' title='Carregando...'>
            <p>Testando...</p>
        </div>
    </body>
</html>
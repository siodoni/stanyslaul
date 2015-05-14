
<!DOCTYPE html>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta charset='iso-8859-1'></meta>
        <script type='text/javascript' src='res/jquery-1.11.0.min.js?st=1427366869'></script>
        <script type='text/javascript' src='res/jquery-ui.min.js?st=1427366869'></script>
        <script type='text/javascript' src='res/js/jquery.ui.timepicker.addon.min.js?st=1427366868'></script>
        <script type='text/javascript' src='res/primeui-1.1-min.js?st=1427366869'></script>
        <script type='text/javascript' src='res/js/stanyslaul.js?st=1427366868'></script>
        <link href='res/primeui-1.1-min.css?st=1427366869' rel='stylesheet'>
        <link href='res/jquery-ui.min.css?st=1427366869' rel='stylesheet'>
        <link href='res/css/primeui.all.css?st=1427366863' rel='stylesheet'>
        <link href='res/css/stanyslaul.css?st=1431433808' rel='stylesheet'>
        <link href='res/css/stanyslaul.table.css?st=1427366863' rel='stylesheet'>
        <link href='res/css/stanyslaul.all.css?st=1431538303' rel='stylesheet'>
        <link href='res/css/themes/redmond/theme.css?st=1427366862' rel='stylesheet'>
    </head>

    <body id='admin' >
        <script type='text/javascript'>
            $(function() {
                // MENSAGENS
                $('#mensagens').puigrowl();

                //TOOLBAR
                $('#toolbar').puimenubar();
                $('#toolbar').parent().puisticky();
            });
        </script>
        <div class='st-div-main'>
            <ul id='toolbar'>
                <li><a data-icon='ui-icon-home'     onclick="window.location = 'menu.php';"     title='Voltar ao menu'>Menu</a></li>
                <li><a data-icon='ui-icon-document' onclick="window.location = 'updateV2.php';" title='Novo'>Novo</a></li>
                <li><a data-icon='ui-icon-close'    href='logout.php'>Sair</a></li>
            </ul>
            <div id='mensagens'></div>
            <?php require_once 'teste_det.php'; ?>
        </div>
        <div id='dlgCarregando' title='Carregando...' class='st-dlg-carregando'>
            <img src='res/images/ico-loading.gif'/>
        </div>
    </body>
</html>
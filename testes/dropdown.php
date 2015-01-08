<!DOCTYPE html>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta charset='iso-8859-1'></meta>
        <script type='text/javascript' src='../res/jquery-1.11.0.min.js?st=1420728357'></script>
        <script type='text/javascript' src='../res/jquery-ui.min.js?st=1420728357'></script>
        <script type='text/javascript' src='../res/js/jquery.ui.timepicker.addon.min.js?st=1420728359'></script>
        <script type='text/javascript' src='../res/primeui-1.1-min.js?st=1420728357'></script>
        <script type='text/javascript' src='../res/js/stanyslaul.js?st=1420728360'></script>
        
        <link href='../res/primeui-1.1-min.css?st=1420728357' rel='stylesheet'>
        <link href='../res/jquery-ui.min.css?st=1420728357' rel='stylesheet'>
        <link href='../res/css/primeui.all.css?st=1420728360' rel='stylesheet'>
        <link href='../res/css/stanyslaul.css?st=1420728360' rel='stylesheet'>
        <link href='../res/css/stanyslaul.table.css?st=1420728360' rel='stylesheet'>
        <link href='../res/css/stanyslaul.all.css?st=1420728361' rel='stylesheet'>
        <link href='../res/css/themes/redmond/theme.css?st=1420728364' rel='stylesheet'>

        <script type="text/javascript">
            $(function() {
                var themes = new Array('afterdark', 'afternoon', 'afterwork', 'aristo', 'black-tie', 'blitzer', 'bluesky', 'bootstrap', 'casablanca', 'cruze', 'cupertino', 'dark-hive', 'dot-luv', 'eggplant', 'excite-bike', 'flick', 'glass-x', 'home', 'hot-sneaks', 'humanity', 'le-frog', 'midnight', 'mint-choc', 'overcast', 'pepper-grinder', 'redmond', 'rocket', 'sam', 'smoothness', 'south-street', 'start', 'sunny', 'swanky-purse', 'trontastic', 'ui-darkness', 'ui-lightness', 'vader');

                $('#custom').puidropdown({
                    data: themes,
                    content: function(option) {
                        return '<span>' + option + '</span><span>Teste</span>';
                    }
                });
            });
        </script>  
    </head>
    <body>
        http://www.jeasyui.com/demo/main/index.php?plugin=ComboGrid&theme=default&dir=ltr&pitem=<br>
        http://www.jqwidgets.com/jquery-widgets-demo/demos/jqxdropdownlist/index.htm<br>
        http://www.pm-consultant.fr/primeui/
        <h3 class="title title-short">Custom Content</h3>  
        <select id="custom" name="custom"></select>
    </body>
</html>
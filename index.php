<html>
    <head>
        <script type="text/javascript" src="jquery/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="prime/primeui-1.0-min.js"></script>

        <link href="prime/primeui-1.0-min.css" rel="stylesheet">
        <link href="jquery/jquery-ui.min.css" rel="stylesheet">
        <link href="prime/css/all.css" rel="stylesheet">
    </head>

    <body>
        <form name="form" method="post" action="listPrime.php"> 
            <?php
            require_once 'lib/Conexao.class.php';
            $con = new Conexao();
            $con->connect();
            
            $query = mysql_query("select table_name ".
                                  " from information_schema.tables ".
                                 " where table_schema = '".$con->getDbName()."'");

            $qtde = 0;
            $button = "";
            
            while ($campo = mysql_fetch_array($query)) {
                $qtde++;
                echo "\n<button id='btn".$qtde."' type='submit' name='nomeTabela' value='".$campo['table_name']."'>".$campo['table_name']."</button><br/>\n";
                $button = $button."\n$('#btn".$qtde."').puibutton();";
            }
            
            $con->disconnect();
            ?>
        </form> 
        <script type="text/javascript">  
            $(function() {  
                <?php
                echo $button;
                ?>
            });
        </script>        
    </body>
</html>
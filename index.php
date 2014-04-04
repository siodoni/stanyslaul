<?php
require_once 'lib/Estrutura.class.php';
require_once 'lib/Conexao.class.php';

$estrutura = new Estrutura();
$con = new Conexao();
?>
<html>
    <?php
    echo $estrutura->head();
    ?>
    <body>
        <form name="form" method="post" action="list.php"> 
            <?php
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
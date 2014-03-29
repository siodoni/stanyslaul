<html>
    <head>
        <script type="text/javascript" src="jquery/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="prime/primeui-1.0-min.js"></script>

        <link href="prime/primeui-1.0-min.css" rel="stylesheet">
        <link href="jquery/jquery-ui.min.css" rel="stylesheet">
        <link href="prime/css/all.css" rel="stylesheet">

        <script type="text/javascript">  
            $(function() {  
                $('#btn01').puibutton();
                $('#btn02').puibutton();
            });
        </script>  
    </head>

    <body>
        <form name="form" method="post" action="listPrime.php"> 
            <button id="btn01" type="submit" name="nomeTabela" value="tabela01">Tabela 01</button><br/>
            <button id="btn02" type="submit" name="nomeTabela" value="tabela02">Tabela 02</button><br/>
        </form> 
    </body>
</html>
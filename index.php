<html>
    <body>
        <form action="logar.php" method="post">
            Usuario<br/>
            <input type="text" name="usuario"/><br/>
            
            Senha<br/>
            <input type="password" name="senha"/><br/>
            
            <input type="submit" value="Enviar"/>
            <input type="reset"  value="Cancelar"/>
        </form>
        <?php
            if (isset($_GET["return"]) && $_GET["return"] == "error"){
                echo "Usuario ou senha invalidos.<br>";
            }
        ?>
    </body>
</html>
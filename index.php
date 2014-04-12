<?php
require_once 'lib/Estrutura.class.php';
$estrutura = new Estrutura();
?>
<html>
    <?php
    echo $estrutura->head();
    ?>
    <body>
        <form action="logar.php" method="post">
            <div id="panel" title="Area Administrativa" class="st-panel-login">
                <label for="usuario">Usuario</label><br/>
                <input id="usuario" type="text" name="usuario" class="st-input-login"/><br/>

                <label for="senha">Senha</label><br/>
                <input id="senha" type="password" name="senha" class="st-input-login"/><br/>

                <br/>
                <button id="logar" type="submit" class="st-button-login">Entrar</button>
                <button id="cancelar" type="reset" class="st-button-login">Limpar</button>
            </div>
        </form>
        <?php
            if (isset($_GET["return"]) && $_GET["return"] == "error"){
                echo "Usuario ou senha invalidos.<br>";
            }
        ?>
    </body>
    <script type="text/javascript">
        $(function() {
            $('#panel').puipanel();  
            $('#usuario').puiinputtext();
            $('#senha').puiinputtext();
            $('#logar').puibutton({
                icon: 'ui-icon-circle-check'
            });  
            $('#cancelar').puibutton({
                icon: 'ui-icon-circle-close'
            });
        });
    </script>
</html>
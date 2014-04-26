<?php
require_once 'lib/Estrutura.class.php';
$estrutura = new Estrutura();
?>
<!DOCTYPE html>
<html>
    <?php
    echo $estrutura->head();
    ?>
    <body>
        <form action="logar.php" method="post">
            <div id="panel" title="&Aacute;rea Administrativa" class="st-panel-login">
                <label for="usuario">Usuario</label><br/>
                <input id="usuario" type="text" name="usuario" class="st-input-login" autofocus="autofocus"/><br/>

                <label for="senha">Senha</label><br/>
                <input id="senha" type="password" name="senha" class="st-input-login"/><br/>

                <br/>
                <button id="logar" type="submit" class="st-button-login">Entrar</button>
                <button id="cancelar" type="reset" class="st-button-login" onclick="window.location='menu.php';">Limpar</button>
            </div>
            <?php
            if (isset($_GET["return"]) && $_GET["return"] == "error"){
                echo "<div class='ui-state-error st-panel-login-error'>"
                    ."<p>"
                    ."<span class='ui-icon ui-icon-alert' style='float:left;margin-left:30px;'>"
                    ."</span>"
                    ."<strong>Usuario ou senha invalidos.</strong>"
                    ."</p>"
                    ."</div>";
            }
            ?>
        </form>
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
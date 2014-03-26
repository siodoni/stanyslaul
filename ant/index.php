<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author" content="ABC 3 WebDesign"/>
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
    </head>
    <body>
        <div id="conteudoAdmin">
            <p>Administração do Site</p>
            <form action='inc/autenticacao.php' id="formLogin" method="post">
                <?php
                $erro = 0;
                $erro = isset($_GET['r']);
                if ($erro == 1) {
                    echo "Usuário ou senha inválidos";
                } elseif ($erro == 2) {
                    echo "Necessário login";
                }
                ?>
                <label for="login">Login</label>
                <input type="text" name="login" />
                <label for="senha">Senha</label>
                <input type="password" name="senha" /><br />
                <input name="enviar" type="submit" id="enviar" value="Logar" />
            </form>
            <p class="info">Qualquer problema para acessar entre em contato com os desenvolvedores.<br />
                <a href="mailto:contato@abc3webdesign.com">
                    <img src="img/email.png" alt="Email ABC 3" />
                </a></p>
        </div>
    </body>
</html>

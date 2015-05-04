<?php

class Menu {

    private $estrutura;
    private $button = "";
    private $panel = "";
    private $qtde = 0;
    private $usuario = "";
    private $nomeUsuario = "";
    private $onload = "";
    private $pdo;
    private $con;

    public function __construct($nome = "", $usuario = "") {
        try {
            $this->onLoad();
            $this->setNomeUsuario($nome);
            $this->setUsuario($usuario);
            $this->pdo = new ConexaoPDO("Menu.class.php");
            $this->con = $this->pdo->connect();
            $this->estrutura = new Estrutura();
            $this->buildMenu();
            $this->alteraSenhaUsuario();
        } catch (Exception $e) {
            die("Problema no metodo Menu.__construct</p><p>".$e."</p>");            
        }
    }

    public function __destruct() {
        $this->pdo->disconnect();
    }

    private function buildMenu() {
        try {
            echo "<!DOCTYPE html>";
            echo "\n<html>";
            echo $this->estrutura->head();
            echo "\n<body $this->onload>";
            echo $this->form();
            echo $this->dialog();
            echo $this->estrutura->dialogAguarde();
            echo "\n</body>";
            echo "\n</html>";
        } catch (Exception $e) {
            die("Problema no metodo Menu.buildMenu</p><p>".$e."</p>");            
        }
    }

    private function form() {
        $sql = str_replace("#db", Config::DBNAME, Constantes::QUERY_MODULE);
        try {
            $form = "\n<div class='st-div-main'>"
                    . "\n<form name='form' method='post' action='list.php'>"
                    . $this->menuBar();
            $rs = $this->con->prepare($sql);
            $rs->bindParam(1, $this->usuario);
            $rs->execute();
            while ($i = $rs->fetch(PDO::FETCH_OBJ)) {
                $form = $form . "\n<div id='panel" . $i->id . "' class='st-menu' title='" . $i->descricao . "'>";
                $this->panel = $this->panel . "\n\t$('#panel" . $i->id . "').puipanel({toggleable: true})";
                $form = $form . $this->buttons($i->id);
                $form = $form . "\n</div>";
            }
            $form = $form
                    . "\n</form>"
                    . "\n</div>"
                    . "\n<div id='mensagens'></div>"
                    . $this->script($this->button);
            return $form;
        } catch (Exception $e) {
            die("Problema no metodo Menu.form</p><p>".$sql."</p><p>".$e."</p>");            
        }
    }

    private function buttons($idModulo = 0) {
        $sql = str_replace("#db", Config::DBNAME, Constantes::QUERY_MENU);
        try {
            $form = "";
            $valor = "";
            $tipo = "";

            $rs = $this->con->prepare($sql);
            $rs->bindParam(1, $idModulo);
            $rs->bindParam(2, $this->usuario);
            $rs->execute();
            while ($j = $rs->fetch(PDO::FETCH_OBJ)) {
                $this->qtde++;
                $valor = $j->tabela != null ? "value='" . $j->id_menu . "'" : "onclick='window.location=\"" . $j->pagina . "\"'";
                $tipo = $j->tabela != null ? "submit" : "button";
                $form = $form . "\n<button id='btn" . $this->qtde . "' type='$tipo' name='idMenu' " . $valor . " class='st-menu-button'>" . $j->codigo . " - " . $j->titulo . "</button><br/>";
                $this->button = $this->button . "\n\t$('#btn" . $this->qtde . "').puibutton({icon: 'ui-icon-newwin'});";
            }
            return $form;
        } catch (Exception $e) {
            die("Problema no metodo Menu.buttons</p><p>".$sql."</p><p>".$e."</p>");            
        }
    }

    private function menuBar() {
        return "\n<ul id='toolbar'>"
                . "\n<li><a><img src='" . Config::LOGO . "' alt='" . Config::TITLE . "' class='st-img-logo'/></a></li>"
                . "\n<li><a data-icon='ui-icon-person'>Bem vindo " . $this->nomeUsuario . "</a></li>"
                . "\n<li><a data-icon='ui-icon-key' onclick='$(\"#dlgChangePass\").puidialog(\"show\");'>Alterar Senha</a></li>"
                . "\n<li><a data-icon='ui-icon-close' href='logout.php'>Sair</a></li>"
                . "\n</ul>";
    }

    private function script() {
        return "\n<script type='text/javascript'>"
                . "\n$(function() {"
                . $this->button . "\n"
                . "\n\t$('#mensagens').puigrowl();"
                . "\n\t$('#toolbar').puimenubar();"
                . "\n\t$('#toolbar').parent().puisticky();\n"
                . $this->dialogJS()
                . "\n\t$('#senha1').puipassword({inline:true,promptLabel:'Informe a nova senha', weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});"
                . "\n\t$('#senha2').puipassword({inline:true,promptLabel:'Confirme a nova senha',weakLabel:'fraca',mediumLabel:'media',goodLabel:'media',strongLabel:'forte'});"
                . "\n\t$('#btnConfSenha').puibutton({icon:'ui-icon-circle-check'});"
                . "\n\t$('#btnCancSenha').puibutton({icon:'ui-icon-circle-close'});\n"
                . $this->panel
                . "\n});"
                . "\n</script>";
    }

    private function dialogJS() {
        return "\n\t$('#dlgChangePass').puidialog({"
             . "\n\t\tmodal: true,"
             . "\n\t\tresizable: false,"
             . "\n\t\twidth: 220,"
             . "\n\t});\n";
    }

    private function dialog() {
        return "\n<form method='post' action='menu.php'>"
                . "\n<div id='dlgChangePass' title='Alterar Senha' class='st-div-dlg-change-pass'>"
                . "\n<p>Informe  a nova senha: <input id='senha1' name='senha1' type='password' class='st-input-change-pass'/></p>"
                . "\n<p>Confirme a nova senha: <input id='senha2' name='senha2' type='password' class='st-input-change-pass'/></p>"
                . "\n<p>"
                . "\n<button id='btnConfSenha' type='submit'>Ok</button>"
                . "\n<button id='btnCancSenha' type='reset' onclick=\"$('#dlgChangePass').puidialog('hide');\">Cancelar</button>"
                . "\n</p>"
                . "\n</div>"
                . "\n</form>";
    }

    public function setNomeUsuario($nome = "") {
        $this->nomeUsuario = $nome;
    }

    public function setUsuario($usuario = "") {
        $this->usuario = $usuario;
    }

    private function alteraSenhaUsuario() {
        $senha1 = isset($_POST["senha1"]) ? sha1($_POST["senha1"]) : "";
        $senha2 = isset($_POST["senha2"]) ? sha1($_POST["senha2"]) : "";
        $crud = new CrudPDO($this->con, Constantes::TABLE_USER, true);

        if (!empty($_POST["senha1"]) && !empty($_POST["senha2"])) {
            if ($senha1 == $senha2) {
                $crud->atualizar(
                        Constantes::COLUMN_PASS . " = '" . $senha1 . "'", Constantes::COLUMN_USER . " = '" . $this->usuario . "'", true);
                unset($_POST["senha1"]);
                unset($_POST["senha2"]);
                $_SESSION["returnPass"] = "info";
                print "<script>location='menu.php';</script>";
            } else {
                $_SESSION["returnPass"] = "error";
                print "<script>location='menu.php';</script>";
            }
        }
    }

    private function onLoad() {
        if (isset($_SESSION["returnPass"]) && $_SESSION["returnPass"] == "error") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'error', summary: 'Erro', detail: 'Erro ao alterar a senha. Senhas n&atilde;o conferem!'}]);\"";
        } else if (isset($_SESSION["returnPass"]) && $_SESSION["returnPass"] == "info") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'info', summary: 'Informa&ccedil;&atilde;o', detail: 'Senha alterada com sucesso!'}]);\"";
        } else if (isset($_SESSION["returnPass"]) && $_SESSION["returnPass"] == "warn") {
            $this->onload = "onload=\"$('#mensagens').puigrowl('show', [{severity: 'warn', summary: 'Aten&ccedil;&atilde;o', detail: 'Informe a nova senha e a confirma&ccedil;&atilde;o!'}]);\"";
        } else {
            $this->onload = "";
        }
        unset($_SESSION["returnPass"]);
    }

}

<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

/*
 * ************************************************************************************* *
 * Essa pagina deverá ficar na pasta common, porém estou tendo problema com os includes. *
 * ************************************************************************************* *
 */

//include_once 'lib/Constantes.class.php';
include_once 'lib/Conexao.class.php';
include_once 'lib/Estrutura.class.php';

$con = new Conexao();
$con->connect();

echo "<html>";
$est = new Estrutura("");
echo $est->head();
echo "<body>";

echo "\n<script type='text/javascript'>";
echo "\n$(function() {";
echo "\n$('#toolbar').puimenubar();";
echo "\n$('#toolbar').parent().puisticky();";
echo "\n});";
echo "\n</script>";

echo "\n<ul id='toolbar'>"
   . "\n<li><a data-icon='ui-icon-home'  onclick=\"window.location = 'menu.php';\" title='Voltar ao menu'>Menu</a></li>"
   . "\n<li><a data-icon='ui-icon-close' href='logout.php'>Sair</a></li>"
   . "\n</ul>";

$sqlAluno = "select a.cod_aluno,"
          .       " a.nome_aluno,"
          .       " a.nome_responsavel,"
          .       " a.dia_vencto,"
          .       " if (a.fg_ativo = 'S','Sim','N&atilde;o') as fg_ativo,"
          .       " a.id_pessoa,"
          .       " a.id_responsavel, "
          .       " b.sexo, "
          .       " date_format(b.dt_nascimento,'%d/%m/%Y') as dt_nascimento, "
          .       " date_format(now(),'%Y') - date_format(b.dt_nascimento,'%Y') idade "
          .  " from ".$con->getDbName().".snb_pessoa b, "
          .       " ".$con->getDbName().".vsnb_aluno a "
          . " where a.id_pessoa = b.id "
          . " order by a.nome_aluno ";
//echo $sqlAluno . "<br><br><br>";

$sqlEndereco = "select a.id, "
             .       " b.tp_logradouro, "
             .       " b.logradouro, "
             .       " b.numero, "
             .       " b.complemento, "
             .       " b.bairro, "
             .       " b.nome_cidade, "
             .       " if (b.fg_principal = 'S','Sim','N&atilde;o') as fg_principal, "
             .       " a.cnpj_cpf, "
             .       " a.digito "
             .  " from ".$con->getDbName().".vsnb_endereco b, "
             .       " ".$con->getDbName().".vsnb_filial a "
             . " where (a.id_pessoa = #idPessoa or a.id = #idResponsavel) "
             .   " and b.id_filial  = a.id ";

$sqlTelefone = "select a.id, "
             .       " b.ddd, "
             .       " b.telefone, "
             .       " b.nm_contato, "
             .       " if (b.fg_principal = 'S','Sim','N&atilde;o') as fg_principal "
             .  " from ".$con->getDbName().".vsnb_telefone b, "
             .       " ".$con->getDbName().".vsnb_filial a "
             . " where (a.id_pessoa = #idPessoa or a.id = #idResponsavel) "
             .   " and b.id_filial  = a.id"
             . " order by b.fg_principal ";

$responsavel = "";
$endereco = "";

$resultAluno = mysql_query($sqlAluno);
while ($i = mysql_fetch_array($resultAluno)) {
    //<img src='res/images/topo.png' style='width:50px;'/>;
    echo "\n<table border='0' width='50%'>";
    echo "\n<tr><td>&nbsp;</td><td>&nbsp;</td><tr/>";
    echo "\n<tr><td class='pui-panel-titlebar ui-widget-header ui-helper-clearfix ui-corner-all' colspan=2>Ficha do Aluno</td></tr>";
    echo "\n<tr><td class='pui-panel-titlebar ui-widget-header ui-helper-clearfix ui-corner-all' colspan=2>Dados Pessoais</td><tr/>";
    echo "\n<tr><td class='st-bold'>Codigo</td><td>"             . $i["cod_aluno"]        . "</td><tr/>";
    echo "\n<tr><td class='st-bold'>Nome</td><td>"               . $i["nome_aluno"]       . "</td><tr/>";
    echo "\n<tr><td class='st-bold'>Dt Nascimento</td><td>"      . $i["dt_nascimento"]    . "</td><tr/>";
    echo "\n<tr><td class='st-bold'>Idade</td><td>"              . $i["idade"]." anos"    . "</td><tr/>";
    echo "\n<tr><td class='st-bold'>Dia Vencimento</td><td>"     . $i["dia_vencto"]       . "</td><tr/>";
    echo "\n<tr><td class='st-bold'>Ativo</td><td>"              . $i["fg_ativo"]         . "</td><tr/>";
    
    $responsavel = "\n<tr><td class='st-bold'>Nome</td><td>" . $i["nome_responsavel"] . "</td><tr/>";
    
    $resultEndereco = mysql_query(str_replace("#idResponsavel",$i["id_responsavel"],str_replace("#idPessoa",$i["id_pessoa"],$sqlEndereco)));
    while ($j = mysql_fetch_array($resultEndereco)) {
        $endereco = "\n<tr><td class='st-bold'>Endere&ccedil;o</td><td>" . $j["tp_logradouro"] . ": " . $j["logradouro"] . ", " . $j["numero"] . ($j["complemento"] != "" ? " - " . $j["complemento"] : "") . "</td></tr>"
                  . "\n<tr><td class='st-bold'>Bairro</td><td>"          . $j["bairro"]        . "</td><tr/>" 
                  . "\n<tr><td class='st-bold'>Cidade</td><td>"          . $j["nome_cidade"]   . "</td><tr/>"
                  . "\n<tr><td class='st-bold'>Principal?</td><td>"      . $j["fg_principal"]  . "</td><tr/>";
        
        $responsavel = $responsavel . "\n<tr><td class='st-bold'>CPF</td><td>" . $j["cnpj_cpf"] . "-" . $j["digito"] . "</td><tr/>";
    }

    echo "\n<tr><td class='pui-panel-titlebar ui-widget-header ui-helper-clearfix ui-corner-all' colspan=2>Respons&aacute;vel</td><tr/>";
    echo $responsavel;
    echo "\n<tr><td class='pui-panel-titlebar ui-widget-header ui-helper-clearfix ui-corner-all' colspan=2>Endere&ccedil;o</td><tr/>";
    echo $endereco;
    echo "\n<tr><td class='pui-panel-titlebar ui-widget-header ui-helper-clearfix ui-corner-all' colspan=2>Telefone</td><tr/>";
    
    $resultTelefone = mysql_query(str_replace("#idResponsavel",$i["id_responsavel"],str_replace("#idPessoa",$i["id_pessoa"],$sqlTelefone)));
    while ($k = mysql_fetch_array($resultTelefone)) {
        echo "\n<tr><td class='st-bold'>" . $k["nm_contato"] . "</td><td>(" . $k["ddd"] . ") " . $k["telefone"] . "</td><tr/>";
    }
    echo "\n<tr><td>&nbsp;</td><td>&nbsp;</td><tr/>";
    echo "\n</table>";
    echo "\n<hr>";
}
$con->disconnect(); 

echo "\n</body>";
echo "\n</html>";
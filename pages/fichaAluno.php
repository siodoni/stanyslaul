<?php
session_start();
if (!isset($_SESSION["usuario"])) {header('location:index.php');}

require_once '../lib/Conexao.class.php';
require_once '../lib/Estrutura.class.php';

$con = new Conexao();
$con->connect();

echo "<html>";
$est = new Estrutura("../");
echo $est->head();
echo "<body>";

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
             .       " if (b.fg_principal = 'S','Sim','N&atilde;o') as fg_principal "
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

$resultAluno = mysql_query($sqlAluno);
while ($i = mysql_fetch_array($resultAluno)) {
    echo "<table border=0>";
    echo "<tr><td><img src='../res/images/topo.png' style='width:50px;'/></td><td><h2>Ficha do Aluno</h2></td></tr>";
    echo "<tr><td class='st-bold'>Codigo</td><td>"             . $i["cod_aluno"]        . "</td><tr/>";
    echo "<tr><td class='st-bold'>Nome</td><td>"               . $i["nome_aluno"]       . "</td><tr/>";
    echo "<tr><td class='st-bold'>Dt Nascimento</td><td>"      . $i["dt_nascimento"]    . "</td><tr/>";
    echo "<tr><td class='st-bold'>Idade</td><td>"              . $i["idade"]." anos"    . "</td><tr/>";
    echo "<tr><td class='st-bold'>Respons&aacute;vel</td><td>" . $i["nome_responsavel"] . "</td><tr/>";
    echo "<tr><td class='st-bold'>Dia Vencimento</td><td>"     . $i["dia_vencto"]       . "</td><tr/>";
    echo "<tr><td class='st-bold'>Ativo</td><td>"              . $i["fg_ativo"]         . "</td><tr/>";
    echo "<tr><td class='st-bold'>&nbsp;</td><td>&nbsp;</td><tr/>";
    
    $resultEndereco = mysql_query(str_replace("#idResponsavel",$i["id_responsavel"],str_replace("#idPessoa",$i["id_pessoa"],$sqlEndereco)));
    while ($j = mysql_fetch_array($resultEndereco)) {
        echo "<tr><td class='st-bold'>Endere&ccedil;o</td><td>" . $j["tp_logradouro"] . ": " . $j["logradouro"] . ", " . $j["numero"] . ($j["complemento"] != "" ? " - " . $j["complemento"] : "") . "</td></tr>";
        echo "<tr><td class='st-bold'>Cidade</td><td>"          . $j["nome_cidade"]   . "</td><tr/>";
        echo "<tr><td class='st-bold'>Principal?</td><td>"      . $j["fg_principal"]  . "</td><tr/>";
    }
    
    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><tr/>";
    
    $resultTelefone = mysql_query(str_replace("#idResponsavel",$i["id_responsavel"],str_replace("#idPessoa",$i["id_pessoa"],$sqlTelefone)));
    while ($k = mysql_fetch_array($resultTelefone)) {
        echo "<tr><td class='st-bold'>" . $k["nm_contato"] . "</td><td>(" . $k["ddd"] . ") " . $k["telefone"] . "</td><tr/>";
    }
    echo "</table>";
    echo "<hr>";
}
$con->disconnect(); 

echo "</body>";
echo "</html>";
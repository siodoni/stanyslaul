<?php

require_once 'lib/Conexao.class.php';
require_once 'lib/Crud.class.php';
header('Content-type: application/json');

$con = new conexao();
$con->connect();

if ($con->connect() == false) {
    die('Não conectou');
}
$nomeTabela = $_REQUEST["nomeTabela"];
$orderBy = " order by 1";

$query = mysql_query("select column_name from information_schema.columns where table_schema = '" . $con->getDbName() . "' and table_name='" . $nomeTabela . "'");
$sql = null;

$cont = 0;

while ($campo = mysql_fetch_array($query)) {
    if ($sql == null) {
        $sql = $campo['column_name'];
    } else {
        $sql = $sql . ", " . $campo['column_name'];
    }
    $cont += 1;

    $arrColuna[] = $campo['column_name'];

    if ($cont == 1) {
        $campoId = $campo['column_name'];
    }
}

$cont = 0;

while ($campo = mysql_fetch_array($query)) {
    if ($sql == null) {
        $sql = $campo['column_name'];
    } else {
        $sql = $sql . ", " . $campo['column_name'];
    }
}

$sql = "select " . $sql . " from " . $nomeTabela . $orderBy;
$c = mysql_query($sql);
$linha = array();
while ($r = mysql_fetch_assoc($c)) {
    $linha[] = $r;
}
$var = json_encode($linha);
$con->disconnect();

echo $var;

?>
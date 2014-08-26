<?php
require_once '../common/Constantes.class.php';
require_once '../lib/ConexaoPDO.class.php';

$pdo = new ConexaoPDO();
$con = $pdo->connect();

$rs = $con->prepare("select id, nome from snb_unid_fed where nome like ? and nome like ? ");
$nome = "%";
$rs->bindParam(1,$nome);
$rs->bindParam(2,$nome);
if ($rs->execute()) {
    if ($rs->rowCount() > 0) {
        while ($row = $rs->fetch(PDO::FETCH_OBJ)) {
            echo $row->id . "<br />";
            echo $row->nome . "<br />";
        }
    }
}

$pdo->disconnect();
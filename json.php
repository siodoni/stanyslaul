<?php
session_start();
require_once 'lib/JSON.class.php';
$json = new JSON($_SESSION["nomeTabela"]);
echo $json->json();
?>
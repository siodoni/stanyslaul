<?php
session_start("stanyslaul");
require_once 'lib/JSON.class.php';
$json = new JSON($_SESSION["nomeTabela"]);
echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json->json());
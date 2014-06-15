<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

require_once 'common/Constantes.class.php';
require_once 'lib/Estrutura.class.php';
require_once 'lib/Conexao.class.php';
require_once 'lib/Crud.class.php';
require_once 'lib/Menu.class.php';
$menu = new Menu($_SESSION["nomeUsuario"],$_SESSION["usuario"]); 
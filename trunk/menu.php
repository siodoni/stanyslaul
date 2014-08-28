<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

require_once 'common/Constantes.class.php';
require_once 'lib/Estrutura.class.php';
require_once 'lib/ConexaoPDO.class.php';
require_once 'lib/CrudPDO.class.php';
require_once 'lib/Menu.class.php';
new Menu($_SESSION["nomeUsuario"],$_SESSION["usuario"]); 
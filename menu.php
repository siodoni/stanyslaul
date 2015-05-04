<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'view/Estrutura.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/CrudPDO.class.php';
require_once 'view/Menu.class.php';
require_once 'util/Base64.class.php';

new Menu($_SESSION["nomeUsuario"],$_SESSION["usuario"]); 
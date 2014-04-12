<?php
session_start();
if (!isset($_SESSION["usuario"])){header('location:index.php');}

require_once 'lib/Menu.class.php';
$menu = new Menu();
$menu->setNomeUsuario($_SESSION["usuario"]);
<?php
session_start();
include_once './config/Config.php';
include_once './backend/Usuario.php';

$usuario = new Usuario($db);

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_SESSION['usuario_id'];
$usuario->deletar($id);
session_destroy();
header('location: index.php');

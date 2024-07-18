<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

include_once './config/Config.php';
include_once './backend/Usuario.php';
include_once './backend/Feedback.php'; // Se necessário, ajuste o caminho correto

$usuario = new Usuario($db);
$feedback = new Feedback($db);

if (isset($_GET['idFeed'])) {
    $id = $_GET['idFeed'];
    // Supondo que $id seja o id do usuário a ser deletado
    $feedback->deletarFeed($id);
    header('Location: CRUDFeedback.php');
    exit();
} else {
    // Caso $_GET['usuario_id'] não esteja definido, tratar adequadamente ou redirecionar para outra página de erro
    header('Location: index.php');
    exit();
}
?>

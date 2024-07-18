<?php
session_start();
include_once './config/Config.php';
include_once './backend/Usuario.php';
include_once './backend/Feedback.php'; // Certifique-se de incluir o Feedback.php aqui se não estiver incluído no Usuario.php

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar se o parâmetro ID foi passado via GET
if (!isset($_GET['id'])) {
    $_SESSION['message'] = 'ID do usuário não especificado.';
    $_SESSION['message_type'] = 'error';
    header('Location: CRUDUsuario.php');
    exit();
}

$id = $_GET['id'];

try {
    // Inicializar a conexão com o banco de dados
    $db = new Database();
    $conn = $db->getConnection();

    // Criar objeto da classe Usuario
    $usuario = new Usuario($conn);

    // Excluir o usuário (inclui exclusão de feedbacks)
    $resultado = $usuario->deletar($id);

    if ($resultado) {
        $_SESSION['message'] = 'Usuário excluído com sucesso!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Erro ao excluir usuário. Tente novamente mais tarde.';
        $_SESSION['message_type'] = 'error';
    }

} catch (PDOException $e) {
    $_SESSION['message'] = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
}

// Redirecionar de volta para a página CRUDUsuario.php
header('Location: CRUDUsuario.php');
exit();
?>

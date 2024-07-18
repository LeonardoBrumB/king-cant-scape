<?php
include_once './config/Config.php';
include_once './backend/Usuario.php';
include_once './backend/Feedback.php';
include_once './backend/Database.php';

session_start();
$idUsu = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null; // Obtém o ID do usuário logado
$conteudo = isset($_POST['conteudo']) ? $_POST['conteudo'] : null; // Obtém o conteúdo do feedback

if (!$idUsu) {
    // Caso o usuário não esteja logado, redireciona para a página de login
    header('Location: login.php');
    exit;
}

if (!$conteudo) {
    // Caso o conteúdo do feedback não tenha sido enviado, redireciona para a página anterior com mensagem de erro
    $_SESSION['message'] = 'Por favor, escreva seu feedback.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php#feedbacks');
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Cria um objeto Feedback e insere o feedback no banco de dados
    $feedback = new Feedback($conn);
    $resultado = $feedback->inserirFeedback($conteudo, $idUsu);

    if ($resultado) {
        // Feedback inserido com sucesso
        $_SESSION['message'] = 'Feedback enviado com sucesso!';
        $_SESSION['message_type'] = 'success';
    } else {
        // Erro ao inserir feedback
        $_SESSION['message'] = 'Erro ao enviar o feedback. Tente novamente mais tarde.';
        $_SESSION['message_type'] = 'error';
    }

    // Redireciona de volta para a página principal
    header('Location: index.php#feedbacks');
    exit;

} catch (PDOException $e) {
    // Em caso de erro de conexão com o banco de dados
    $_SESSION['message'] = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
    $_SESSION['message_type'] = 'error';
    header('Location: index.php#feedbacks');
    exit;
}
?>

<?php
session_start();

// Verifica se o usuário está logado
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

// Verifica se o ID do feedback foi passado via GET
if (isset($_GET['id']) && $logged_in) {
    $idFeed = $_GET['id'];

    // Incluir os arquivos necessários
    include_once './config/Config.php';
    include_once './backend/Feedback.php';
    include_once './backend/Database.php';

    try {
        // Conectar ao banco de dados
        $db = new PDO("mysql:host=localhost;dbname=kcsdb;charset=utf8", 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Inicializar o objeto Feedback
        $feedback = new Feedback($db);

        // Busca o feedback pelo idFeed para verificar se o usuário pode excluí-lo
        $feedbackData = $feedback->lerPorId($idFeed);

        if ($feedbackData) {
            // Verifica se o usuário logado é o autor do feedback
            if ($logged_in && $_SESSION['usuario_id'] == $feedbackData['idUsu']) {
                // Chame a função para deletar o feedback
                $feedback->deletarFeed($idFeed);

                // Redireciona de volta para a página de feedbacks após a exclusão
                header('Location: index.php');
                exit;
            } else {
                echo "Você não tem permissão para excluir este feedback.";
                exit;
            }
        } else {
            echo "Feedback não encontrado.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        exit;
    }
} else {
    echo "ID de feedback inválido ou usuário não logado.";
    exit;
}
?>

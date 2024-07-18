<?php
session_start();

// Verifica se o usuário está logado
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

if (!$logged_in) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Verifica se os dados do formulário foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idFeed'], $_POST['conteudo'])) {
    $idFeed = $_POST['idFeed'];
    $novoConteudo = $_POST['conteudo'];

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

        // Buscar o feedback pelo idFeed para verificação e atualização
        $feedbackData = $feedback->lerPorId($idFeed);

        if ($feedbackData) {
            // Verifica se o usuário logado é o autor do feedback
            if ($_SESSION['usuario_id'] == $feedbackData['idUsu']) {
                // Atualiza o feedback no banco de dados
                $atualizacao = $feedback->atualizarFeed($idFeed, $novoConteudo);

                if ($atualizacao) {
                    // Feedback atualizado com sucesso
                    header("Location: index.php");
                    exit;
                } else {
                    echo "Erro ao atualizar o feedback.";
                    exit;
                }
            } else {
                echo "Você não tem permissão para editar este feedback.";
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
    echo "Dados do formulário não recebidos corretamente.";
    exit;
}
?>

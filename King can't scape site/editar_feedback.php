<?php
session_start();

// Verifica se o usuário está logado
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

if (!$logged_in) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// Verifica se o ID do feedback foi enviado via GET
if (!isset($_GET['id'])) {
    echo "ID de feedback inválido.";
    exit;
}

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

    // Buscar o feedback pelo idFeed para verificação
    $feedbackData = $feedback->lerPorId($idFeed);

    if (!$feedbackData) {
        echo "ID de feedback inválido.";
        exit;
    }

    // Verifica se o usuário logado é o autor do feedback
    if ($_SESSION['usuario_id'] != $feedbackData['idUsu']) {
        echo "Você não tem permissão para editar este feedback.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Editar Feedback</title>
</head>

<body>
    <?php include_once "header.php"; ?>

    <main class="container">
        <section class="editar-feedback">
            <h2>Editar Feedback</h2>
            <form action="processar_edicao_feedback.php" method="POST">
                <input type="hidden" name="idFeed" value="<?php echo $idFeed; ?>">
                <label for="conteudo">Novo Conteúdo:</label><br>
                <textarea name="conteudo" cols="70" rows="5"><?php echo $feedbackData['feedback']; ?></textarea><br>
                <input type="submit" class="input_enviar" value="Salvar">
            </form>
        </section>
    </main>

    <?php include_once "footer.php"; ?>
</body>

</html>

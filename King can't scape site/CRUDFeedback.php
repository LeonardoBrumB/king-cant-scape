<?php
session_start();
include_once './config/Config.php';
include_once './backend/Feedback.php';
include_once './backend/Usuario.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$feedback = new Feedback($db);
$usuario = new Usuario($db);

// Processar exclusão de notícias
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $feedback->deletarFeed($id);
    header('Location: CRUDFeedback.php');
    exit();
}

// Obter dados do usuário logado
$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
$idUsu = $dados_usuario['id'];
$nome_usuario = $dados_usuario['nome'];


// Obter dados das noticias do usuário logado
$dados_feedback = $feedback->lerFeed();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <link rel="stylesheet" href="CRUDFeedback.css">
</head>

<body>
    <?php include_once 'header.php'; ?>
    <main>
    <div class="feedbacks-publicados">
            <?php while ($row = $dados_feedback->fetch(PDO::FETCH_ASSOC)) : ?>
                <table class="table">
                    <td><?php echo $row['nickname']; ?></td>
                    <td><?php echo $row['data']; ?></td>
                </table>
                <table class="table-2">
                    <tr>
                        <td><?php echo nl2br($row['feedback']); ?></td>
                        <td><a href="editarFeedback.php?idFeed=<?php echo $row['idFeed']; ?>">Editar</a></td>
                        <td><a href="deletarFeed.php?idFeed=<?php echo $row['idFeed']; ?>">Deletar</a></td>
                    </tr>
                </table><br><br>
            <?php endwhile; ?>

        </div>
        
        
    </main>
    <?php include_once 'footer.php'; ?>
</body>

</html>
<?php
session_start();
include_once './config/Config.php';
include_once './backend/Usuario.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario = new Usuario($db);

// Obter dados do usuário logado
$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);

$id_usuario = $_SESSION['usuario_id'];

$nome_usuario = $dados_usuario['nome'];
$nickname_usuario = $dados_usuario['nickname'];
$dataNasc_usuario = $dados_usuario['datanasc'];
$email_usuario = $dados_usuario['email'];
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    // Verifica se o usuário tem permissão para deletar o usuário
    if ($id == $_SESSION['usuario_id']) {
        $usuario->deletar($id);
        session_destroy(); // Destroi a sessão após deletar o usuário
        header('Location: index.php');
        exit();
    }
}

// Função para determinar a saudação
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registrar.css">
    <title>King Can't Scape!</title>
</head>

<body class="body_consult">
    <?php include_once 'header.php'; ?>

    <main container>
        <form class="registro">
            <p> Perfil </p><br>
            <div>
                <label><strong>Nome:</strong></label><br>
                <label><?php echo $nome_usuario ?></label>
            </div><br><br>
            <div>
                <label><strong>nickname:</strong></label><br>
                <label><?php echo $nickname_usuario ?></label>
            </div><br><br>
            <div>
                <div>
                    <label><strong>Data de nascimento:</strong></label><br>
                    <label><?php echo $dataNasc_usuario ?></label>
                </div><br><br>
                <div>
                    <label><strong>E-mail:</strong></label><br>
                    <label><?php echo $email_usuario ?></label>
                </div><br><br>
        </form>

        <a href="editar.php"><button>Editar</button></a>

        <a href="deletarUsu.php?id=<?php echo $dados_usuario['id']; ?>">Apagar minha conta</a>

    </main>

    <footer>
        <?php include_once 'footer.php'; ?>
    </footer>

</body>

</html>
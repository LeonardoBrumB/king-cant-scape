<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

include_once './config/Config.php';
include_once './backend/Usuario.php';

$usuario = new Usuario($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
    $idUsu = $dados_usuario['id'];

    $nome = $_POST['nome'];
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];

    $usuario->atualizar($idUsu, $nome, $nickname, $email);
    header('Location: consultUsu.php');
    exit();
}

$row = $usuario->lerPorId($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuário</title>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <main>
        <div>
            <div>
                <div>
                    <h2>Editar</h2>
                </div>
            </div>
            <div>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div>
                        <label for="nome">Nome:</label><br><br>
                        <input type="text" name="nome" value="<?php echo $row['nome']; ?>" required>
                        <br><br>
                    </div>
                    <div>
                        <label for="nickname">nickname:</label><br><br>
                        <input required class="form-control" name="nickname" id="nickname" type="text" maxlength="16" value="<?php echo $row['nickname']; ?>">
                    </div>
                    <div>
                        <label for="email">E-mail:</label><br><br>
                        <input required name="email" id="email" type="email" value="<?php echo $row['email']; ?>">
                    </div>
                    <input style="margin-left: 31%" type="submit" name="login" value="Atualizar"><br><br>
                </form>
                <div>
                    <?php if (isset($mensagem_erro)) {
                        echo '<br><p> <strong>' . $mensagem_erro . '</strong></p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php include_once 'footer.php'; ?>
</body>

</html>
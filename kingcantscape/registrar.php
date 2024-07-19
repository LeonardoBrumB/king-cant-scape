<?php
include_once './config/Config.php';
include_once './backend/Usuario.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($db);
    $nickname = $_POST['nickname'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $dataNasc = $_POST['dataNasc'];

    $nick = $usuario->verificarNick($nickname);
    if ($nick) {
        $mensagem = 'Esse nickname já existe';
    } else {
        $usuario->registrar($nome,$nickname,$dataNasc, $email , $senha);
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="registrar.css" />
    <title>Registre-se</title>
</head>

<body>
    <?php include_once "header.php"; ?>
    <main class="container_consult">
        <form method="POST" class="registro">
            <p> Registro </p><br>
            <label for="nickname">Nickname</label>
            <input type="text" name="nickname" required maxlength="16">

            <label for="nome">Nome</label>
            <input type="text" name="nome" required maxlength="100">

            <label for="email">Email</label>
            <input type="email" name="email" required maxlength="255">

            <label for="senha">Senha</label>
            <input type="password" name="senha" required maxlength="255">

            <label for="dataNasc">Data de Nascimento</label>
            <input type="date" name="dataNasc" required>

            <button type="submit">Registrar</button>
            <div class="mensagem">
                <?php
                if (isset($mensagem)) {
                    echo '<p><strong>' . $mensagem . '</strong></p>';
                }
                ?>
            </div>
        </form>
    </main>
        <?php include_once "footer.php"; ?>
</body>

</html>
<?php
session_start();
include_once './config/Config.php';
include_once './backend/Usuario.php';

$usuario = new Usuario($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $usuario = new Usuario($db);
    $codigo = $usuario->gerarCodigoVerificacao($email);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylelogin.css">
    <title>King Can't Scape!</title>
</head>

<body>
    <?php include_once "header.php"; ?>

    <div class="seila">
        <form method="POST">
            <p>Autenticação</p>
            <label>E-mail</label>
            <input type="email" name="email" required />

            <a href="verificar_token"><button type="submit" name="recupSenha">Recuperar senha</button></a>
        </form>
        <div class="mensagem">
            <?php
            if (isset($mensagem_erro)) {
                echo '<p>' . $mensagem_erro . '</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>
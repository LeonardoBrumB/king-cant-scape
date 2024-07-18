<?php
include_once './config/config.php';
include_once './backend/Usuario.php';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nova_senha = $_POST['nova_senha'];
    $usuario = new Usuario($db);
    if ($usuario->redefinirSenha($codigo, $nova_senha)) {
        $mensagem = 'Senha redefinida com sucesso.';
    } else {
        $mensagem = 'Código de verificação inválido.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylelogin.css">
    <title>King Can't Scape!</title>
</head>

<body>
    <?php include_once './header.php';?>
    <h1>Redefinir Senha</h1>
    <form method="POST">
        <label for="codigo">Código de Verificação:</label>
        <input type="text" name="codigo" value="Seu código aqui" required><br><br>
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" required><br><br>
        <input type="submit" value="Redefinir Senha">
    </form>
    <p><?php echo $mensagem; ?></p>
</body>

</html>
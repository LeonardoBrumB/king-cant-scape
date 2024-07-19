<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

// Verifica se o usuário está logado e é administrador
if ($logged_in) {
    // Inclui o arquivo de conexão com o banco de dados
    include_once './backend/Database.php';

    // Cria uma instância da classe Database
    $database = new Database();
    $conn = $database->getConnection(); // Obtém a conexão PDO

    $usuario_id = $_SESSION['usuario_id']; // Obtém o ID do usuário da sessão

    try {
        // Prepara a consulta SQL
        $stmt = $conn->prepare("SELECT adm FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $usuario_id);
        $stmt->execute();

        // Verifica se há resultados
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $adm = $usuario['adm'];

            // Verifica se o usuário é administrador
            if ($adm == 1) {
                $exibirBotoesAdmin = true;
            } else {
                $exibirBotoesAdmin = false;
            }
        } else {
            // Caso o usuário não seja encontrado
            $exibirBotoesAdmin = false;
        }
    } catch (PDOException $e) {
        echo "Erro na consulta: " . $e->getMessage();
        $exibirBotoesAdmin = false; // Define como falso em caso de erro
    }
} else {
    $exibirBotoesAdmin = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="headerstyle.css">
    <title>King Can't Scape!</title>
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="index.php">
                <div class="imagemLogo">
                    <img class="logoi" src="cabeca.png" alt="Logo do jogo. Rei." width="60px" style="margin-top: 5px;">
                </div>
                <div class="nomeLogo">
                    <span class="Nome">King Can't Scape!</span>
                </div>
            </a>
        </div>

        <div class="btnHeader">
            <?php if($logged_in): ?>
                <a href="downloads/King Can't Scape.exe" class="btn-download" download>Baixar</a>
            <?php else: ?>
                <a href="login.php" class="btn-download">Baixar</a>
            <?php endif; ?>
        </div>

        <?php if($exibirBotoesAdmin): ?>
        <div class="admin-buttons">
            <a href="CRUDUsuario.php" class="btn-admin">CRUD Usuário</a>
        </div>
        <?php endif; ?>
        <div class="login">
            <?php if($logged_in): ?>
                <span>
                    <a href="consultUsu.php"> <?php echo $_SESSION['nickname']; ?> </a>
                    <a href="logout.php">
                        <div class="iconLogin">
                            Logout <!-- Você pode adicionar um ícone de logout aqui se desejar -->
                        </div>
                    </a>
                </span>
            <?php else: ?>
                <a href="login.php">
                    <span>
                        Login
                        <div class="iconLogin">
                            <!-- Se desejar, adicione um ícone de login aqui -->
                        </div>
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </header>
</body>
</html>

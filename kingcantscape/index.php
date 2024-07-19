<?php
include_once './config/Config.php';
include_once './backend/Usuario.php';
include_once './backend/Feedback.php';
include_once './backend/Database.php';

session_start();
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$idUsu = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null; // Verifica se 'usuario_id' está definido

try {
    $db = new PDO("mysql:host=localhost;dbname=kcsdb;charset=utf8", 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit;
}

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

$feedback = new Feedback($db);
$dados_feedback = $feedback->lerFeed();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>King Can't Scape!</title>
</head>

<body>
    <?php include_once "header.php"; ?>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <div class="hero-logo">
                    <div class="hero-text-1">
                        <p class="hero-text-left">Faça parte</p>
                    </div>
                    <div class="hero-img">
                        <img src="cabeca.png" alt="Logo do reizinho" class="site-logo">
                    </div>
                    <div class="hero-text-2">
                        <p class="hero-text-right">da aventura</p>
                    </div>
                </div>

                <a href="#about-game" class="btn-scroll">Saiba mais</a>
            </div>
        </section>
    </main>
    <section id="about-game" class="about-game">
        <div class="about-game-content">
            <div class="about-game-text">
                <h2>Sobre o Jogo</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget lorem in urna mattis aliquet.</p>

                <?php if ($logged_in) : ?>
                    <a href="downloads/King Can't Scape.rar" class="btn-download" download>Baixar</a>
                <?php else : ?>
                    <a href="login.php" class="btn-download">Baixar</a>
                <?php endif; ?>

            </div>
            <div class="game-image-container">
                <img src="fundo.jpeg" alt="Imagem do jogo" class="game-image">
                <div class="diagonal-line"></div>
            </div>
        </div>
    </section>
    <div id="feedbacks" class="feedbacks">
        <div class="feedbacks-titulo">
            <h1>Feedbacks</h1>
            <p>Publique seu feedback sobre o nosso primeiro jogo feito em 15 dias!</p>
        </div>

        <!-- Formulário para publicar feedback -->
        <?php if ($logged_in) : ?>
            <div class="feedback-publicar">
                <form method="POST" action="processar_feedback.php" class="form_feed">
                    <label for="conteudo">Escreva o feedback:</label><br>
                    <textarea name="conteudo" rows="10" cols="50" placeholder="Digite no máximo 500 caracteres" maxlength="500"></textarea><br>

                    <!-- Campo oculto para armazenar o idUsu -->
                    <input type="hidden" name="idUsu" value="<?php echo $idUsu; ?>">

                    <div class="enviar">
                        <input type="submit" class="input_enviar" value="Enviar">
                    </div>
                </form>
            </div>
        <?php else : ?>
            <p style="text-align: center;">Você precisa estar logado para enviar um feedback.</p>
        <?php endif; ?>

        <!-- Feedbacks publicados -->
        <div class="feedbacks-publicados">
            <?php while ($row = $dados_feedback->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="feedback-item">
                    <table class="table" border="1">
                        <tr>
                            <td><?php echo $row['nickname']; ?></td>
                            <td><?php echo $row['data']; ?></td>
                            <?php if ($row['idUsu'] == $idUsu) : ?>
                                <td>
                                    <a href="editar_feedback.php?id=<?php echo $row['idFeed']; ?>" class="btn-download">Editar</a>
                                    <a href="excluir_feedback.php?id=<?php echo $row['idFeed']; ?>" class="btn-download">Excluir</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    </table>
                    <table class="table-2" border="1">
                        <tr>
                            <td><?php echo $row['feedback']; ?></td>
                        </tr>
                    </table>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include_once "footer.php"; ?>
</body>

</html>
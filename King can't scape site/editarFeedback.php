<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

include_once './config/Config.php';
include_once './backend/Usuario.php';
include_once './backend/Feedback.php';

$usuario = new Usuario($db);
$f = new Feedback($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
    // $idFeed = $dados_usuario['id'];

    $idFeed = $_POST['id'];

    
    $date = date('Y-m-d');
    $feedback = $_POST['feedback'];

    $f->atualizarFeed($idFeed, $feedback);
    header('Location: CRUDFeedback.php');
    exit();
}

if (isset($_GET['idFeed'])) {
    $idFeed = $_GET['idFeed'];
    $row = $f->lerPorId($idFeed);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>King Can't Scape!</title>
</head>

<body>
<?php include_once "header.php"; ?>
    <header>
        <h1>Editar Feedback</h1>
    </header>
    <main class="main_editar">
        <div class="card">
            <div class="card-header">
                <div class="text-header">
                </div>
            </div>
            <div class="card-body">
                <form method="POST" class="form_feed">
                <input type="hidden" name="id" value="<?php echo $row['idFeed']; ?>">
                    <div class="form-group">
                        <textarea cols="38" rows="20" required name="feedback" <?php echo $row['feedback']; ?>></textarea>
                    </div>

                    <input type="submit" name="login" class="btn" value="Editar"><br>
                    <a href="CRUDUsuario.php"><button class="btn">Voltar</button></a>
                </form>
            </div>
        </div>
    </main>
    <script>
        function myFunction() {
            var element = document.body;
            element.classList.toggle("dark-mode");
        }
    </script>
    <?php include_once "footer.php"; ?>
</body>

</html>
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
// Processar exclusão de usuário
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $usuario->deletar($id);
    header('Location: CRUDUsuario.php');
    exit();
}

// Obter parâmetros de pesquisa e filtros
$search = isset($_GET['search']) ? $_GET['search'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '';

// Obter dados dos usuários com filtros
if ($search || $order_by) {
    $dados = $usuario->lerPesquisar($search, $order_by);
} else {
    $dados = $usuario->ler();
}



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="crudusuario.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
</head>

<body>
    <?php include_once 'header.php'; ?>
    
    <main>
        <table>
            <form method="GET">

                <input type="text" name="search" placeholder="Pesquisar por nick, nome ou email" value="<?php echo htmlspecialchars($search); ?>">

                <div>
                    <input value="" name="order_by" id="value-1" type="radio" <?php if ($order_by == "") echo 'checked'; ?>>
                    <label for="value-1">Normal</label>

                    <input value="nickname" name="order_by" id="value-2" type="radio" <?php if ($order_by == 'nickname') echo 'checked'; ?>>
                    <label for="value-2">Nick</label>

                    <input value="nome" name="order_by" id="value-3" type="radio" <?php if ($order_by == 'nome') echo 'checked'; ?>>
                    <label for="value-3">Nome</label>
                </div>
                <button type="submit">Pesquisar</button>
            </form><br><br>
            <thead>
                <tr>
                    <th>
                        <h1>&nbsp;ID&nbsp;</h1>
                    </th>
                    <th>
                        <h1>Nome</h1>
                    </th>
                    <th>
                        <h1>Nickname</h1>
                    </th>
                    <th>
                        <h1>Email</h1>
                    </th>
                    <th>
                        <h1>Ações</h1>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $row) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['nickname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="deletar.php?id=<?php echo $row['id']; ?>">Banir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <?php include_once 'footer.php'; ?>
    </footer>
    
</body>


</html>

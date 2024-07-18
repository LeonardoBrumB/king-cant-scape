<?php
// Dentro do arquivo Usuario.php
include_once './backend/Feedback.php';

class Usuario
{
    private $conn;

    private $table_name = "usuarios"; // nome da tabela

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // coisa da apostila
    public function lerPesquisar($search = '', $order_by = '')
    {
        $query = "SELECT * FROM " . $this->table_name;
        $conditions = [];
        $params = [];

        if ($search) {
            $conditions[] = "(nickname LIKE :search OR nome LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if ($order_by === 'nickname' || $order_by === 'nome') {
            $query .= " ORDER BY $order_by";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params); // Passa o array de parâmetros diretamente para execute
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($nickname, $nome, $email, $senha, $dataNasc)
    {
        $query = "INSERT INTO " . $this->table_name . " (nome, nickname, datanasc, email, senha) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($senha, PASSWORD_BCRYPT);
        $stmt->execute([$nome, $nickname, $dataNasc, $email, $hashed_password]);
        return $stmt;
    }

    public function criar($nickname, $nome, $email, $senha, $dataNasc)
    {
        return $this->registrar($nickname, $nome, $email, $senha, $dataNasc);
    }

    public function login($nickname, $senha)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE nickname = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nickname]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    } // certo

    public function ler()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } // certo

    public function lerPorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $nickname, $email)
    {
        $query = "UPDATE " . $this->table_name . " SET nome = ?, nickname = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nome, $nickname, $email, $id]);
        return $stmt;
    } // certo

    public function deletar($id)
    {
        try {
            $this->conn->beginTransaction();

            // Excluir os feedbacks associados ao usuário, se necessário
            // $feedback = new Feedback($this->conn);
            // $feedback->deletarFeedByUserId($id);

            // Excluir o usuário
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Erro ao excluir usuário: " . $e->getMessage();
            return false;
        }
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function gerarCodigoVerificacao($email)
    {
        $codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 10);

        $query = "UPDATE " . $this->table_name . " SET codigo_verificacao = ? WHERE email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$codigo, $email]);

        if ($stmt->rowCount() > 0) {
            // Enviar e-mail com o código de verificação
            $assunto = "Código de Verificação para Recuperação de Senha";
            $mensagem = "Seu código de verificação é: " . $codigo;
            $headers = "From: no-reply@KingCan'tScape.com" . "\r\n" .
                "Reply-To: no-reply@KingCan'tScape.com" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            if (mail($email, $assunto, $mensagem, $headers)) {
                return $codigo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function verificarCodigo($codigo)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE codigo_verificacao = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$codigo]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function redefinirSenha($codigo, $senha)
    {
        $query = "UPDATE " . $this->table_name . " SET senha = ?, codigo_verificacao = NULL WHERE codigo_verificacao = ?";

        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($senha, PASSWORD_BCRYPT);
        $stmt->execute([$hashed_password, $codigo]);
        return $stmt->rowCount() > 0;
    }
}

// tudo certo nesse aqui
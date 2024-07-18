<?php
    include_once './backend/Database.php';
class Feedback {
    private $conn;
    private $table_name = "tbfeedback";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    

    public function criar($idUsu, $feedback, $data)
    {
        return $this->registrar($idUsu, $feedback, $data);
    }

    public function inserirFeedback($conteudo, $idUsu) {
        try {
            // Verifica se o ID do usuário é válido
            $query = "SELECT COUNT(*) as count FROM usuarios WHERE id = :idUsu";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idUsu', $idUsu, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result['count'] == 0) {
                // O ID do usuário não existe na tabela `usuarios`
                echo "Erro: ID de usuário inválido.";
                return false;
            }
    
            // Prepara a query para inserir o feedback
            $query = "INSERT INTO tbfeedback (idUsu, data, feedback) VALUES (:idUsu, NOW(), :feedback)";
            $stmt = $this->conn->prepare($query);
    
            // Defina os parâmetros
            $stmt->bindParam(':idUsu', $idUsu, PDO::PARAM_INT);
            $stmt->bindParam(':feedback', $conteudo, PDO::PARAM_STR);
    
            // Executa a query
            $stmt->execute();
    
            // Verifica se o feedback foi inserido com sucesso
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // Em caso de erro, você pode registrar o erro ou lançar uma exceção
            echo "Erro ao inserir o feedback: " . $e->getMessage();
            return false;
        }
    }
    

    public function lerFeed() {
        $query = "SELECT tbfeedback.idFeed, usuarios.id AS idUsu, usuarios.nickname AS nickname, tbfeedback.feedback, tbfeedback.data  
                  FROM tbfeedback 
                  INNER JOIN usuarios ON tbfeedback.idUsu = usuarios.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    

    public function lerPorId($idUsu)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE idFeed = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUsu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarFeed($idfeed, $feedback)
    {
        $query = "UPDATE " . $this->table_name . " SET feedback = ? WHERE idFeed = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$feedback, $idfeed]);
        return $stmt;
    }

    public function deletarFeed($idfeed)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE idfeed = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idfeed]);
        return $stmt;
    }
    
    public function registrar($idUsu, $data, $feedback)
    {
        $query = "INSERT INTO " . $this->table_name . " (idusu, data, feedback) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$idUsu, $data, $feedback]);
        return $stmt;
    }
    // Dentro da classe Feedback, no arquivo Feedback.php

    public function deletarFeedByUserId($userId)
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE idUsu = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId]);
            return true;
        } catch (PDOException $e) {
            echo "Erro ao excluir feedbacks do usuário: " . $e->getMessage();
            return false;
        }
    }


}
?>
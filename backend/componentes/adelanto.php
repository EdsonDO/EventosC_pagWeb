<?php
require_once 'conexion.php';

class Adelanto {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

    public function listar() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Adelanto");
            $adelantos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($adelantos);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Adelanto WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $adelanto = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($adelanto);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Adelanto (valor) VALUES (:valor)");
            $stmt->execute([':valor' => $data['valor']]);
            echo json_encode(['success' => true, 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function actualizar($id, $data) {
        try {
            $stmt = $this->pdo->prepare("UPDATE Adelanto SET valor = :valor WHERE id = :id");
            $stmt->execute([
                ':id' => $id,
                ':valor' => $data['valor']
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Adelanto WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

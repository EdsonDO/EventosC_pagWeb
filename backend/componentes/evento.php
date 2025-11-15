<?php
require_once 'conexion.php';

class Evento {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

    public function listar() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Evento");
            $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($eventos);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Evento WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $evento = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($evento);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Evento (nombre, estado) 
                VALUES (:nombre, :estado)
            ");
            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':estado' => $data['estado'] ?? 'Disponible'
            ]);
            echo json_encode(['success' => true, 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function actualizar($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE Evento 
                SET nombre = :nombre, estado = :estado
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':nombre' => $data['nombre'],
                ':estado' => $data['estado'] ?? 'Disponible'
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Evento WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

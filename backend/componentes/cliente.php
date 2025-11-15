<?php
require_once 'conexion.php';

class Cliente {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

    public function listar() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Cliente");
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($clientes);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Cliente WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($cliente);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Cliente (nombre, apellidos, telefono, dni) 
                VALUES (:nombre, :apellidos, :telefono, :dni)
            ");
            $stmt->execute([
                ':nombre' => $data['nombre'],
                ':apellidos' => $data['apellidos'],
                ':telefono' => $data['telefono'],
                ':dni' => $data['dni']
            ]);
            echo json_encode(['success' => true, 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function actualizar($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE Cliente 
                SET nombre = :nombre, apellidos = :apellidos, telefono = :telefono, dni = :dni 
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':nombre' => $data['nombre'],
                ':apellidos' => $data['apellidos'],
                ':telefono' => $data['telefono'],
                ':dni' => $data['dni']
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Cliente WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

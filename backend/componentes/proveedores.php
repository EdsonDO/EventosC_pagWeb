<?php
require_once 'conexion.php';

class Proveedores {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

    public function listar() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Proveedores");
            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($proveedores);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Proveedores WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($proveedor);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Proveedores (nombre_empresa, nombre_responsable, telefono, email, direccion, estado)
                VALUES (:nombre_empresa, :nombre_responsable, :telefono, :email, :direccion, :estado)
            ");
            $stmt->execute([
                ':nombre_empresa' => $data['nombre_empresa'],
                ':nombre_responsable' => $data['nombre_responsable'],
                ':telefono' => $data['telefono'],
                ':email' => $data['email'],
                ':direccion' => $data['direccion'],
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
                UPDATE Proveedores SET
                    nombre_empresa = :nombre_empresa,
                    nombre_responsable = :nombre_responsable,
                    telefono = :telefono,
                    email = :email,
                    direccion = :direccion,
                    estado = :estado
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':nombre_empresa' => $data['nombre_empresa'],
                ':nombre_responsable' => $data['nombre_responsable'],
                ':telefono' => $data['telefono'],
                ':email' => $data['email'],
                ':direccion' => $data['direccion'],
                ':estado' => $data['estado'] ?? 'Disponible'
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Proveedores WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

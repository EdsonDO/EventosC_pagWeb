<?php
require_once 'conexion.php';

class Pagos {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

    public function listar() {
        try {
            $stmt = $this->pdo->query("
                SELECT p.*, tp.nombre AS tipo_pago, a.valor AS adelanto
                FROM Pagos p
                LEFT JOIN Tipo_Pago tp ON p.id_tipo_pago = tp.id
                LEFT JOIN Adelanto a ON p.id_adelanto = a.id
            ");
            $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($pagos);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Pagos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $pago = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($pago);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Pagos (numero_tarjeta, fecha_vencimiento, cvv, voucer, id_tipo_pago, id_adelanto)
                VALUES (:numero_tarjeta, :fecha_vencimiento, :cvv, :voucer, :id_tipo_pago, :id_adelanto)
            ");
            $stmt->execute([
                ':numero_tarjeta' => $data['numero_tarjeta'] ?? null,
                ':fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
                ':cvv' => $data['cvv'] ?? null,
                ':voucer' => $data['voucer'] ?? null,
                ':id_tipo_pago' => $data['id_tipo_pago'],
                ':id_adelanto' => $data['id_adelanto']
            ]);
            echo json_encode(['success' => true, 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function actualizar($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE Pagos SET
                    numero_tarjeta = :numero_tarjeta,
                    fecha_vencimiento = :fecha_vencimiento,
                    cvv = :cvv,
                    voucer = :voucer,
                    id_tipo_pago = :id_tipo_pago,
                    id_adelanto = :id_adelanto
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':numero_tarjeta' => $data['numero_tarjeta'] ?? null,
                ':fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
                ':cvv' => $data['cvv'] ?? null,
                ':voucer' => $data['voucer'] ?? null,
                ':id_tipo_pago' => $data['id_tipo_pago'],
                ':id_adelanto' => $data['id_adelanto']
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Pagos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

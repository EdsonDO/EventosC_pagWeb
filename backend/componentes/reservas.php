<?php
require_once 'conexion.php';
require_once __DIR__ . '/../validations/reservaValidation.php';

class Reservas {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

    public function listar() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM Reservas");
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($reservas);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function obtener($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Reservas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($reserva);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
     $errores = ReservasValidation::validar($data);
    if (!empty($errores)) {
        echo json_encode(['errores' => $errores]);
        return;
    }
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO Reservas (fecha, numero_asistentes, total, estado, id_cliente, id_pagos, id_evento, id_ubicacion)
                VALUES (:fecha, :numero_asistentes, :total, :estado, :id_cliente, :id_pagos, :id_evento, :id_ubicacion)
            ");
            $stmt->execute([
                ':fecha' => $data['fecha'],
                ':numero_asistentes' => $data['numero_asistentes'] ?? 0,
                ':total' => $data['total'] ?? 0,
                ':estado' => $data['estado'] ?? 'Por Pagar',
                ':id_cliente' => $data['id_cliente'],
                ':id_pagos' => $data['id_pagos'],
                ':id_evento' => $data['id_evento'],
                ':id_ubicacion' => $data['id_ubicacion']
            ]);
            echo json_encode(['success' => true, 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function actualizar($id, $data) {
    $errores = ReservasValidation::validar($data);
      if (!empty($errores)) {
        echo json_encode(['errores' => $errores]);
        return;
    }
        try {
            $stmt = $this->pdo->prepare("
                UPDATE Reservas SET
                    fecha = :fecha,
                    numero_asistentes = :numero_asistentes,
                    total = :total,
                    estado = :estado,
                    id_cliente = :id_cliente,
                    id_pagos = :id_pagos,
                    id_evento = :id_evento,
                    id_ubicacion = :id_ubicacion
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':fecha' => $data['fecha'],
                ':numero_asistentes' => $data['numero_asistentes'] ?? 0,
                ':total' => $data['total'] ?? 0,
                ':estado' => $data['estado'] ?? 'Por Pagar',
                ':id_cliente' => $data['id_cliente'],
                ':id_pagos' => $data['id_pagos'],
                ':id_evento' => $data['id_evento'],
                ':id_ubicacion' => $data['id_ubicacion']
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM Reservas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

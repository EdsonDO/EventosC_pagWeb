<?php
require_once 'conexion.php';
require_once '../validations/pagos.validations.php';

class Pagos {
    private $pdo;

    public function __construct() {
        $db = new Conexion();
        $this->pdo = $db->iniciar();
    }

  public function listar() {
    $sql = "SELECT 
                p.id,
                p.numero_tarjeta,
                p.fecha_vencimiento,
                p.cvv,
                p.voucer,
                p.id_tipo_pago,
                tp.nombre AS tipo_pago_nombre,
                p.id_adelanto,
                a.valor AS adelanto_valor
            FROM Pagos p
            LEFT JOIN Tipo_Pago tp ON p.id_tipo_pago = tp.id
            LEFT JOIN Adelanto a ON p.id_adelanto = a.id";

    $stmt = $this->pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}

    public function obtener($id) {
        $errores = validarPagoId($id);
        if (!empty($errores)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos no válidos', 'detalles' => $errores]);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM Pagos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $pago = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($pago);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function crear($data) {
        $errores = validarPagoDatos($data);
        if (!empty($errores)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos no válidos', 'detalles' => $errores]);
            return;
        }

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
            http_response_code(201);
            echo json_encode(['success' => true, 'id' => $this->pdo->lastInsertId()]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function actualizar($id, $data) {
        $errores_id = validarPagoId($id);
        $errores_data = validarPagoDatos($data);
        $errores = array_merge($errores_id, $errores_data);

        if (!empty($errores)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos no válidos', 'detalles' => $errores]);
            return;
        }

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
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function eliminar($id) {
        $errores = validarPagoId($id);
        if (!empty($errores)) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos no válidos', 'detalles' => $errores]);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM Pagos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function listarTiposPago() {
    try {
        $sql = "SELECT * FROM Tipo_Pago";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}

public function listarAdelantos() {
    try {
        $sql = "SELECT * FROM Adelanto";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);

    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}



public function estadisticas() {
    $hoy = date('Y-m-d');
    $tresDias = date('Y-m-d', strtotime('+3 days'));

    // Total Adelantos
    $sql1 = $this->pdo->query("SELECT SUM(a.valor) AS total_adelantos
                               FROM Pagos p
                               INNER JOIN Adelanto a ON p.id_adelanto = a.id");

    // Pagos A Tiempo
    $sql2 = $this->pdo->query("SELECT COUNT(*) AS a_tiempo
                               FROM Pagos WHERE fecha_vencimiento > '$hoy'");

    // Por Vencerse
    $sql3 = $this->pdo->query("SELECT COUNT(*) AS por_vencerse
                               FROM Pagos 
                               WHERE fecha_vencimiento BETWEEN '$hoy' AND '$tresDias'");

    // Vencidos
    $sql4 = $this->pdo->query("SELECT COUNT(*) AS vencidos
                               FROM Pagos WHERE fecha_vencimiento < '$hoy'");

    return [
        'total_adelantos' => $sql1->fetch(PDO::FETCH_ASSOC)['total_adelantos'],
        'a_tiempo'        => $sql2->fetch(PDO::FETCH_ASSOC)['a_tiempo'],
        'por_vencerse'    => $sql3->fetch(PDO::FETCH_ASSOC)['por_vencerse'],
        'vencidos'        => $sql4->fetch(PDO::FETCH_ASSOC)['vencidos'],
    ];
}
}
?>
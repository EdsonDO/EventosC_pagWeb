<?php
require_once __DIR__ . '/../config/Conexion.php';

class Reserva
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->iniciar();
    }

    public function registrar($datos)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->conn->beginTransaction();

            $sqlEvento = "INSERT INTO evento (id_cliente, id_sede, id_tipo_evento, nombre_evento, fecha_evento, hora_inicio, hora_fin, estado) 
                          VALUES (:id_cliente, :id_sede, :id_tipo_evento, :nombre, :fecha, :inicio, :fin, 'Confirmado')";

            $stmt = $this->conn->prepare($sqlEvento);

            $stmt->execute([
                ':id_cliente' => $datos['id_usuario'],
                ':id_sede' => $datos['id_sede'],
                ':id_tipo_evento' => $datos['id_tipo_evento'],
                ':nombre' => $datos['nombre_evento'],
                ':fecha' => $datos['fecha'],
                ':inicio' => $datos['hora_inicio'],
                ':fin' => $datos['hora_fin']
            ]);

            $idEventoCreado = $this->conn->lastInsertId();

            if (!empty($datos['servicios'])) {
                $sqlServicio = "INSERT INTO detalle_servicio (id_evento, id_servicio) VALUES (:id_evento, :id_servicio)";
                $stmtServicio = $this->conn->prepare($sqlServicio);
                foreach ($datos['servicios'] as $idServicio) {
                    $stmtServicio->execute([
                        ':id_evento' => $idEventoCreado,
                        ':id_servicio' => $idServicio
                    ]);
                }
            }

            $sqlReserva = "INSERT INTO reserva (id_evento, fecha_reserva, costo_total, monto_pagado, estado_pago) 
                           VALUES (:id_evento, CURDATE(), :total, :pagado, 'Pagado')";

            $stmt2 = $this->conn->prepare($sqlReserva);
            $stmt2->execute([
                ':id_evento' => $idEventoCreado,
                ':total' => $datos['costo'],
                ':pagado' => $datos['costo']
            ]);

            $idReservaCreada = $this->conn->lastInsertId();

            $this->conn->commit();
            return $idReservaCreada;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("ERROR SQL DETECTADO: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return false;
        }
    }

    public function listarPorCliente($id_cliente)
    {
        $sql = "SELECT e.*, s.nombre as nombre_sede, r.costo_total, r.monto_pagado, r.estado_pago, r.id_reserva
                FROM evento e
                JOIN sede s ON e.id_sede = s.id_sede
                LEFT JOIN reserva r ON e.id_evento = r.id_evento
                WHERE e.id_cliente = :id_cliente
                ORDER BY e.fecha_evento DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_cliente' => $id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalle($id_reserva)
    {
        $sql = "SELECT r.*, e.nombre_evento, e.fecha_evento, e.hora_inicio, e.hora_fin, s.nombre as nombre_sede, s.direccion 
                FROM reserva r
                JOIN evento e ON r.id_evento = e.id_evento
                JOIN sede s ON e.id_sede = s.id_sede
                WHERE r.id_reserva = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_reserva]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
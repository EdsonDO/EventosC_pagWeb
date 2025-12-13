<?php


class Admin {
    private $db;

    public function __construct() {
        $this->db = new Conexion(); 
    }

    
    public function listarReservas() {
        try {
            $conexion = $this->db->iniciar(); 
            
            
            $sql = "SELECT 
                        r.id_reserva,
                        r.fecha_reserva,
                        r.monto_pagado,
                        r.costo_total,
                        r.estado_pago,
                        r.id_evento,
                        e.id_evento,
                        e.nombre_evento,
                        e.fecha_evento,
                        e.hora_inicio,
                        e.hora_fin,
                        e.estado as estado_evento,
                        e.id_cliente,
                        e.id_sede,
                        u.id_usuario,
                        u.nombre_completo as cliente,
                        u.email as email_cliente,
                        s.id_sede,
                        s.nombre as nombre_sede,
                        s.direccion as direccion_sede,
                        s.capacidad
                    FROM reserva r
                    INNER JOIN evento e ON r.id_evento = e.id_evento
                    INNER JOIN usuario u ON e.id_cliente = u.id_usuario
                    INNER JOIN sede s ON e.id_sede = s.id_sede
                    ORDER BY r.fecha_reserva DESC, e.fecha_evento DESC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $resultado;

        } catch (PDOException $e) {
            error_log("Error en listarReservas: " . $e->getMessage());
            return [];
        }
    }

    
    public function cancelarEvento($id_reserva) {
        try {
            $conexion = $this->db->iniciar();
            
            
            $sql = "UPDATE evento e 
                    INNER JOIN reserva r ON e.id_evento = r.id_evento 
                    SET e.estado = 'Cancelado' 
                    WHERE r.id_reserva = :id";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id_reserva, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en cancelarEvento: " . $e->getMessage());
            return false;
        }
    }

    
    public function reactivarEvento($id_reserva) {
        try {
            $conexion = $this->db->iniciar();
            
            
            $sql = "UPDATE evento e 
                    INNER JOIN reserva r ON e.id_evento = r.id_evento 
                    SET e.estado = 'Confirmado' 
                    WHERE r.id_reserva = :id";
            
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id', $id_reserva, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error en reactivarEvento: " . $e->getMessage());
            return false;
        }
    }

   
    public function obtenerEstadisticas() {
        try {
            $conexion = $this->db->iniciar();
            $stats = [];
          
            $sql = "SELECT COUNT(*) as total FROM reserva";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $stats['total_reservas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
          
            $sql = "SELECT COUNT(*) as total FROM reserva WHERE estado_pago = 'Pagado'";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $stats['reservas_pagadas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            
            $sql = "SELECT COUNT(*) as total FROM reserva r
                    INNER JOIN evento e ON r.id_evento = e.id_evento
                    WHERE e.estado != 'Cancelado'";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $stats['eventos_activos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            
            $sql = "SELECT COALESCE(SUM(monto_pagado), 0) as total FROM reserva WHERE estado_pago = 'Pagado'";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['ingresos_totales'] = $result['total'];
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Error en obtenerEstadisticas: " . $e->getMessage());
            return [
                'total_reservas' => 0, 
                'reservas_pagadas' => 0,
                'eventos_activos' => 0,
                'ingresos_totales' => 0
            ];
        }
    }


    public function verificarSolapamiento($id_sede, $fecha, $hora_inicio, $hora_fin, $id_evento_excluir = null) {
        try {
            $conexion = $this->db->iniciar();
            
            $sql = "SELECT COUNT(*) as conflictos 
                    FROM evento e
                    WHERE e.id_sede = :id_sede
                    AND e.fecha_evento = :fecha
                    AND e.estado != 'Cancelado'
                    AND (
                        (:hora_inicio >= e.hora_inicio AND :hora_inicio < e.hora_fin)
                        OR (:hora_fin > e.hora_inicio AND :hora_fin <= e.hora_fin)
                        OR (:hora_inicio <= e.hora_inicio AND :hora_fin >= e.hora_fin)
                    )";
            
            if ($id_evento_excluir) {
                $sql .= " AND e.id_evento != :id_evento_excluir";
            }
            
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_sede', $id_sede, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora_inicio', $hora_inicio);
            $stmt->bindParam(':hora_fin', $hora_fin);
            
            if ($id_evento_excluir) {
                $stmt->bindParam(':id_evento_excluir', $id_evento_excluir, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['conflictos'] > 0;
            
        } catch (PDOException $e) {
            error_log("Error en verificarSolapamiento: " . $e->getMessage());
            return false;
        }
    }
}
?>
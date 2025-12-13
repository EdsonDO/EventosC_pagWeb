<?php

class Servicio
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function listarServicios()
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT 
                        s.*,
                        p.nombre_empresa,
                        p.nombre_contacto,
                        COUNT(ds.id_evento) as veces_usado
                    FROM servicio s
                    INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                    LEFT JOIN detalle_servicio ds ON s.id_servicio = ds.id_servicio
                    GROUP BY s.id_servicio
                    ORDER BY s.nombre_servicio ASC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al listar servicios: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerServicioPorId($id_servicio)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT s.*, p.nombre_empresa
                    FROM servicio s
                    INNER JOIN proveedor p ON s.id_proveedor = p.id_proveedor
                    WHERE s.id_servicio = :id_servicio";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener servicio: " . $e->getMessage());
            return null;
        }
    }

    public function crearServicio($datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "INSERT INTO servicio (id_proveedor, nombre_servicio, costo) 
                    VALUES (:id_proveedor, :nombre_servicio, :costo)";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_proveedor', $datos['id_proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre_servicio', $datos['nombre_servicio'], PDO::PARAM_STR);
            $stmt->bindParam(':costo', $datos['costo'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al crear servicio: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarServicio($id_servicio, $datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "UPDATE servicio 
                    SET id_proveedor = :id_proveedor,
                        nombre_servicio = :nombre_servicio,
                        costo = :costo
                    WHERE id_servicio = :id_servicio";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
            $stmt->bindParam(':id_proveedor', $datos['id_proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre_servicio', $datos['nombre_servicio'], PDO::PARAM_STR);
            $stmt->bindParam(':costo', $datos['costo'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al actualizar servicio: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarServicio($id_servicio)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM detalle_servicio WHERE id_servicio = :id_servicio";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            $sql = "DELETE FROM servicio WHERE id_servicio = :id_servicio";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_servicio', $id_servicio, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al eliminar servicio: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerProveedores()
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT id_proveedor, nombre_empresa FROM proveedor ORDER BY nombre_empresa ASC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener proveedores: " . $e->getMessage());
            return [];
        }
    }
}
?>
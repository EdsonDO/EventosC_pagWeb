<?php

class Proveedor
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function listarProveedores()
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT 
                        p.*,
                        COUNT(s.id_servicio) as total_servicios
                    FROM proveedor p
                    LEFT JOIN servicio s ON p.id_proveedor = s.id_proveedor
                    GROUP BY p.id_proveedor
                    ORDER BY p.nombre_empresa ASC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al listar proveedores: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerProveedorPorId($id_proveedor)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT * FROM proveedor WHERE id_proveedor = :id_proveedor";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener proveedor: " . $e->getMessage());
            return null;
        }
    }

    public function crearProveedor($datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "INSERT INTO proveedor (nombre_contacto, nombre_empresa, direccion, telefono) 
                    VALUES (:nombre_contacto, :nombre_empresa, :direccion, :telefono)";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre_contacto', $datos['nombre_contacto'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre_empresa', $datos['nombre_empresa'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $datos['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al crear proveedor: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarProveedor($id_proveedor, $datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "UPDATE proveedor 
                    SET nombre_contacto = :nombre_contacto,
                        nombre_empresa = :nombre_empresa,
                        direccion = :direccion,
                        telefono = :telefono
                    WHERE id_proveedor = :id_proveedor";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_contacto', $datos['nombre_contacto'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre_empresa', $datos['nombre_empresa'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $datos['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al actualizar proveedor: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarProveedor($id_proveedor)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM servicio WHERE id_proveedor = :id_proveedor";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            $sql = "DELETE FROM proveedor WHERE id_proveedor = :id_proveedor";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_proveedor', $id_proveedor, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al eliminar proveedor: " . $e->getMessage());
            return false;
        }
    }
}
?>
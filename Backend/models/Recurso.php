<?php

class Recurso
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function listarRecursos()
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT 
                        r.*,
                        COUNT(dr.id_evento) as veces_usado
                    FROM recurso r
                    LEFT JOIN detalle_recurso dr ON r.id_recurso = dr.id_recurso
                    GROUP BY r.id_recurso
                    ORDER BY r.nombre_recurso ASC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al listar recursos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerRecursoPorId($id_recurso)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT * FROM recurso WHERE id_recurso = :id_recurso";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener recurso: " . $e->getMessage());
            return null;
        }
    }

    public function crearRecurso($datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "INSERT INTO recurso (id_proveedor, nombre_recurso, descripcion, costounidad, stock) 
                    VALUES (:id_proveedor, :nombre_recurso, :descripcion, :costounidad, :stock)";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_proveedor', $datos['id_proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre_recurso', $datos['nombre_recurso'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':costounidad', $datos['costounidad'], PDO::PARAM_STR);
            $stmt->bindParam(':stock', $datos['stock'], PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al crear recurso: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarRecurso($id_recurso, $datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "UPDATE recurso 
                    SET id_proveedor = :id_proveedor,
                        nombre_recurso = :nombre_recurso,
                        descripcion = :descripcion,
                        costounidad = :costounidad,
                        stock = :stock
                    WHERE id_recurso = :id_recurso";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);
            $stmt->bindParam(':id_proveedor', $datos['id_proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(':nombre_recurso', $datos['nombre_recurso'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(':costounidad', $datos['costounidad'], PDO::PARAM_STR);
            $stmt->bindParam(':stock', $datos['stock'], PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al actualizar recurso: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarRecurso($id_recurso)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM detalle_recurso WHERE id_recurso = :id_recurso";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            $sql = "DELETE FROM recurso WHERE id_recurso = :id_recurso";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_recurso', $id_recurso, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al eliminar recurso: " . $e->getMessage());
            return false;
        }
    }
}
?>
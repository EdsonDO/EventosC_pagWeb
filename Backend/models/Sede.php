<?php

class Sede
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function listarSedes()
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT 
                        s.*,
                        COUNT(e.id_evento) as total_eventos
                    FROM sede s
                    LEFT JOIN evento e ON s.id_sede = e.id_sede
                    GROUP BY s.id_sede
                    ORDER BY s.nombre ASC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al listar sedes: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerSedePorId($id_sede)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT * FROM sede WHERE id_sede = :id_sede";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_sede', $id_sede, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener sede: " . $e->getMessage());
            return null;
        }
    }

    public function crearSede($datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "INSERT INTO sede (nombre, direccion, capacidad, precio_base) 
                    VALUES (:nombre, :direccion, :capacidad, :precio_base)";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $datos['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':capacidad', $datos['capacidad'], PDO::PARAM_INT);
            $stmt->bindParam(':precio_base', $datos['precio_base'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al crear sede: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarSede($id_sede, $datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "UPDATE sede 
                    SET nombre = :nombre,
                        direccion = :direccion,
                        capacidad = :capacidad,
                        precio_base = :precio_base
                    WHERE id_sede = :id_sede";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_sede', $id_sede, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $datos['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(':capacidad', $datos['capacidad'], PDO::PARAM_INT);
            $stmt->bindParam(':precio_base', $datos['precio_base'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al actualizar sede: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarSede($id_sede)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM evento WHERE id_sede = :id_sede";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_sede', $id_sede, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            $sql = "DELETE FROM sede WHERE id_sede = :id_sede";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_sede', $id_sede, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al eliminar sede: " . $e->getMessage());
            return false;
        }
    }
}
?>
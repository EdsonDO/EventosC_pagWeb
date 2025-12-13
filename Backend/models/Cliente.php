<?php


class Cliente
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function listarClientes()
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT 
                        u.*,
                        COUNT(e.id_evento) as total_eventos,
                        COALESCE(SUM(r.monto_pagado), 0) as total_gastado
                    FROM usuario u
                    LEFT JOIN evento e ON u.id_usuario = e.id_cliente
                    LEFT JOIN reserva r ON e.id_evento = r.id_evento
                    WHERE u.rol = 'Cliente'
                    GROUP BY u.id_usuario
                    ORDER BY u.nombre_completo ASC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al listar clientes: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerClientePorId($id_usuario)
    {
        try {
            $conexion = $this->db->iniciar();

            $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario AND rol = 'Cliente'";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener cliente: " . $e->getMessage());
            return null;
        }
    }

    public function crearCliente($datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM usuario WHERE email = :email";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':email', $datos['email'], PDO::PARAM_STR);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuario (nombre_completo, email, password_hash, rol) 
                    VALUES (:nombre_completo, :email, :password_hash, 'Cliente')";

            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':nombre_completo', $datos['nombre_completo'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $datos['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al crear cliente: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarCliente($id_usuario, $datos)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM usuario WHERE email = :email AND id_usuario != :id_usuario";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':email', $datos['email'], PDO::PARAM_STR);
            $stmtCheck->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            if (!empty($datos['password'])) {
                $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE usuario 
                        SET nombre_completo = :nombre_completo,
                            email = :email,
                            password_hash = :password_hash
                        WHERE id_usuario = :id_usuario AND rol = 'Cliente'";

                $stmt = $conexion->prepare($sql);
                $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
            } else {
                $sql = "UPDATE usuario 
                        SET nombre_completo = :nombre_completo,
                            email = :email
                        WHERE id_usuario = :id_usuario AND rol = 'Cliente'";

                $stmt = $conexion->prepare($sql);
            }

            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_completo', $datos['nombre_completo'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $datos['email'], PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al actualizar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCliente($id_usuario)
    {
        try {
            $conexion = $this->db->iniciar();

            $sqlCheck = "SELECT COUNT(*) as total FROM evento WHERE id_cliente = :id_usuario";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                return false;
            }

            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario AND rol = 'Cliente'";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return false;
        }
    }
}
?>
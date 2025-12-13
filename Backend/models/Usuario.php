<?php

require_once __DIR__ . '/../config/Conexion.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->iniciar();
    }

    public function login($email, $password)
    {
        try {
            $sql = "SELECT * FROM usuario WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $password_db = $resultado['password_hash'];

                if (password_verify($password, $password_db)) {
                    return $resultado;
                }
            }
            return false;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function register($nombre_completo, $email, $password_hash)
    {
        try {
            $sql = "INSERT INTO usuario(nombre_completo, email, password_hash) VALUES (:nombre, :email, :contrasena)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre_completo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':contrasena', $password_hash);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}

<?php
require_once __DIR__ . '/../config/Conexion.php';

class TipoEvento
{
    private $db;

    public function __construct()
    {
        $this->db = new Conexion();
    }

    public function listar()
    {
        try {
            $conexion = $this->db->iniciar();
            $sql = "SELECT * FROM tipo_evento ORDER BY nombre_tipo ASC";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al listar tipos de evento: " . $e->getMessage());
            return [];
        }
    }
}
?>
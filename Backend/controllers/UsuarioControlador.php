<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioControlador
{

    public function register(array $datos)
    {
        $usuario = new Usuario;
        $resultado = $usuario->register(
            $datos['nombre_completo'],
            $datos['email'],
            password_hash($datos['password_hash'], PASSWORD_DEFAULT)
        );

        return $resultado;
    }

    public function login(array $datos)
    {
        $usuario = new Usuario;
        $resultado = $usuario->login(
            $datos['email'],
            $datos['password_hash']
        );

        if ($resultado) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['id_usuario'] = $resultado['id_usuario'];
            $_SESSION['nombre'] = $resultado['nombre_completo'];
            $_SESSION['rol'] = $resultado['rol'];
        }

        return $resultado;
    }

}

?>
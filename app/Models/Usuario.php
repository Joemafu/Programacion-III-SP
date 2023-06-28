<?php

use Slim\Exception\HttpResponseException;


class Usuario
{
    public $id;
    public $usuario;
    public $mail;
    public $clave;
    public $rol;

    public function __construct()
    {
        
    }

    public function crearUsuario()
    {
        if ($this->usuarioExiste($this->usuario)) {
            return false;
        }

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, clave, rol) VALUES (:usuario, :clave, :rol)");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, mail, rol FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerTodosCSV()
    {
        $usuarios = Usuario::obtenerTodos();

        $csvData = '';
        foreach ($usuarios as $usuario) {
            $csvData .= $usuario->id . ',' . $usuario->usuario . ',' . $usuario->mail . ',' . $usuario->rol . "\n";
        }

        return $csvData;
    }

    private function usuarioExiste($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn() > 0;
    }

    public static function getNombreById($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuario FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }

    public static function traerClientesPorId($idClientes) 
    {
        $clientes = [];
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        foreach ($idClientes as $idCliente) {
            $consultaCliente = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE id = :idCliente");
            $consultaCliente->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
            $consultaCliente->execute();

            $cliente = $consultaCliente->fetch(PDO::FETCH_ASSOC);
            $clientes[] = $cliente;
        }
        return $clientes;
    }
}

?>
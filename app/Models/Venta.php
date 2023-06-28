<?php

class Venta
{
    public $id;
    public $fecha;
    public $cantidad;
    public $idCliente;
    public $idProducto;
    public $foto;

    public function __construct()
    {
        
    }

    function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas (fecha, cantidad, idCliente, idProducto, foto) VALUES (:fecha, :cantidad, :idCliente, :idProducto, :foto)");
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':idCliente', $this->idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindvalue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ventas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerIdClientesPorIdProducto($idProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idCliente FROM ventas WHERE idProducto = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_COLUMN);
    }




    // // REFACTORIZAR PARA QUE USE TABLAS RELACIONALES
    
    // public static function obtenerClientesPorProducto($producto)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT cliente FROM ventas WHERE producto = :producto");
    //     $consulta->bindValue(':producto', $producto, PDO::PARAM_STR);
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_COLUMN);
    // }

    
    
    
    
    // ESTO LO TENGO QUE ARREGLAR CON LAS RELACIONES ENTRE TABLAS!

    public static function obtenerVentasPorPeriodo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT v.* FROM ventas v INNER JOIN productos p ON v.idProducto = p.id WHERE p.nacionalidad = :nacionalidad AND v.fecha BETWEEN :fechaInicio AND :fechaFin");
        $consulta->bindValue(':nacionalidad', 'EEUU', PDO::PARAM_STR);
        $consulta->bindValue(':fechaInicio', '2022-11-13', PDO::PARAM_STR);
        $consulta->bindValue(':fechaFin', '2022-11-16', PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }





    // public static function ActualizarEstado($id, $estado) : bool {

    //     try {
    //         if(Venta::ventaExiste($id))
    //         {
    //             $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //             $consulta = $objAccesoDatos->prepararConsulta('UPDATE ventas SET estado = :estado WHERE id = :id');
    //             $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
    //             $consulta->bindValue(':id', $id, PDO::PARAM_INT);
    //             $consulta->execute();
    //         }
    //         else
    //         {
    //             return false;
    //         }            
    //     } catch (Exception $e) {
    //         return false;
    //     }

    //     return true;
    // }

    // private static function ventaExiste($id)
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) FROM ventas WHERE id = :id");
    //     $consulta->bindValue(':id', $id, PDO::PARAM_STR);
    //     $consulta->execute();

    //     return $consulta->fetchColumn() > 0;
    // }
}
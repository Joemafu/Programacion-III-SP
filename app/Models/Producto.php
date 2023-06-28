<?php
class Producto
{
    public $id;
    public $nombre;
    public $tipo;
    public $precio;
    public $nacionalidad;
    public $foto;

    public function __construct()
    {
        
    }

    public function crearProducto()
    {
        if ($this->productoExiste($this->nombre)) {
            return false;
        }

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('INSERT INTO productos (nombre, tipo, precio, nacionalidad, foto) VALUES (:nombre,:tipo,:precio,:nacionalidad,:foto)');
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, precio, nacionalidad, foto FROM productos");
        $consulta->execute();


        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerPorNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, precio, nacionalidad, foto FROM productos WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();


        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, tipo, precio, nacionalidad, foto FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();


        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public function productoExiste($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) FROM productos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn() > 0;
    }

    public function idProductoExiste($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT COUNT(*) FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchColumn() > 0;
    }

    public static function getNombreById($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT nombre FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }

    public static function getIdByNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id FROM productos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchColumn();
    }

    public static function deletePorId($id)
    {
        // if(Producto::softDeleteFoto($id))
        // {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM productos WHERE id = :id");
            $consulta->bindValue(':id', $id, PDO::PARAM_STR);
            $retorno=$consulta->execute();
        // }
        // else{
        //     $retorno = "No se pudo mover la foto.";
        // }

        return $retorno;
    }

    public static function softDeleteFoto($id)
    {
        $nombre = Producto::getNombreById($id);

        $rutaOrigen = "../ImagenesProductos/".$nombre.".jpg";

        $rutaDestino = "../Backup_2023/".$nombre.".jpg";

        $directorioDestino = '../Backup_2023';

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        return rename($rutaOrigen, $rutaDestino);
    } 

    public static function obtenerTodosCSV()
    {
        $productos = Producto::obtenerTodos();

        $csvData = '';
        foreach ($productos as $producto) {
            $csvData .= $producto->id . ',' . $producto->nombre . ',' . $producto->tipo . ',' . $producto->precio . ',' . $producto->nacionalidad . "," . $producto->foto . "\n";
        }

        return $csvData;
    }

    public function modificarProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta('UPDATE productos SET nombre = :nombre, tipo = :tipo, precio = :precio, nacionalidad = :nacionalidad, foto = :foto WHERE id = :id');
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
}
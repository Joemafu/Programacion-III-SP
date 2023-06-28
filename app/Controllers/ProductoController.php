<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $precio = $parametros['precio'];
        $nacionalidad = $parametros['nacionalidad'];
        $uploadedFiles = $request->getUploadedFiles();
        $foto = $uploadedFiles['foto'];
        $nombreArchivo = "/".$nombre.".jpg";

        $directorioDestino = '../ImagenesProductos';

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }
        $rutaDestino = $directorioDestino . $nombreArchivo;
        $foto->moveTo($rutaDestino);

        $producto = new Producto();
        $producto->nombre=$nombre;
        $producto->tipo=$tipo;
        $producto->precio=$precio;
        $producto->nacionalidad=$nacionalidad;
        $producto->foto=$rutaDestino;
        
        $producto->crearProducto();

        $payload = json_encode(array("Ok" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("Productos" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorNacionalidad($request, $response, $args)
    {
        $nacionalidad = $args['nacionalidad'];
        $lista = Producto::obtenerPorNacionalidad($nacionalidad);
        $payload = json_encode(array("Productos de origan ".$nacionalidad => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorId($request, $response, $args)
    {
        $id = $args['id'];
        $lista = Producto::obtenerPorId($id);
        $payload = json_encode(array("Producto ID ".$id => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarPorId($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        if(Producto::deletePorId($id))
        {
            $payload = json_encode(array("Ok" => "Producto eliminado."));
        }
        else
        {
            $payload = json_encode(array("Error" => "No se pudo eliminar el producto."));// o no se pudo mover su foto."));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPorId($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $nombre = $parametros['nombre'];
        $tipo = $parametros['tipo'];
        $precio = $parametros['precio'];
        $nacionalidad = $parametros['nacionalidad'];

        // $uploadedFiles = $request->getUploadedFiles();
        // $foto = $uploadedFiles['foto'];
        // $nombreArchivo = "/".$nombre.".jpg";

        // $directorioDestino = '../ImagenesProductos';

        if(Producto::idProductoExiste($id))
        {
            // Producto::softDeleteFoto($id);
            // if (!is_dir($directorioDestino)) {
            //     mkdir($directorioDestino, 0777, true);
            // }
            // $rutaDestino = $directorioDestino . $nombreArchivo;
            // $foto->moveTo($rutaDestino);
    
            $producto = new Producto();
            $producto->id = $id;
            $producto->nombre=$nombre;
            $producto->tipo=$tipo;
            $producto->precio=$precio;
            $producto->nacionalidad=$nacionalidad;
            $producto->foto="ruta prueba";

            $producto->modificarProducto();
            $payload = json_encode(array("Ok" => "Producto modificado."));
        }
        else
        {
            $payload = json_encode(array("Error" => "El ID no existe en la base de datos."));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function DescargarTodosCSV($request, $response)
    {
        $productosCSV = Producto::obtenerTodosCSV();

        $response = $response->withHeader('Content-Type', 'text/csv')
        ->withHeader('Content-Disposition', 'attachment; filename="productos.csv"');
        $response->getBody()->write($productosCSV);
        return $response;
    }
}
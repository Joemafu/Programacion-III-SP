<?php

require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../interfaces/IApiUsable.php';

class VentaController extends Venta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $cantidad = $parametros['cantidad'];
        $idCliente = $parametros['idCliente'];
        $nombreCliente = Usuario::getNombreById($idCliente); 
        $idProducto = $parametros['idProducto'];
        $nombreProducto = Producto::getNombreById($idProducto);  
        $uploadedFiles = $request->getUploadedFiles();
        $foto = $uploadedFiles['foto'];
        $nombreArchivo = "/".$nombreProducto." - ".$nombreCliente." ".date("Y-m-d H.i.s")."Hs.jpg";


        $directorioDestino = '../FotosArma2023';

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }
        $rutaDestino = $directorioDestino . $nombreArchivo;
        $foto->moveTo($rutaDestino);

        $venta = new Venta();
        $venta->fecha=date('Y-m-d');
        $venta->cantidad=$cantidad;
        $venta->idCliente = $idCliente;
        $venta->idProducto = $idProducto;        
        $venta->foto=$rutaDestino;
        
        if($venta->crearVenta()!==false)
        {
            $payload = json_encode(array("Ok" => "Venta creada con exito."));
        }
        else
        {
            $payload = json_encode(array("Error" => "No se pudo escribir en la base de datos."));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Venta::obtenerTodos();
        $payload = json_encode(array("Ventas" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerClientesPorProducto($request, $response, $args)
    {
        $nombreProducto = $args['producto'];

        $idProducto = Producto::getIdByNombre($nombreProducto);
        
        $idClientes = Venta::obtenerIdClientesPorIdProducto($idProducto);

        $jsonClientes = Usuario::traerClientesPorId($idClientes);

        $payload = json_encode(array("Clientes que compraron ".$nombreProducto => $jsonClientes));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerVentasPorPeriodo($request, $response, $args)
    {
        $lista = Venta::obtenerVentasPorPeriodo();
        $payload = json_encode(array("Ventas de armamento de EEUU realizadas entre el 13 y el 16 de noviembre de 2022" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
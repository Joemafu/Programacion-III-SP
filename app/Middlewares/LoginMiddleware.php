<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once './db/AccesoDatos.php';

class LoginMiddleware
{
    public function __invoke (Request $request, RequestHandler $handler): Response {
        
        $data = $request->getParsedBody();
        $mail = $data['mail'] ?? '';
        $rol = $data['rol'] ?? '';
        $clave = $data['clave'] ?? '';

        $rol = LoginMiddleware::validarCredenciales($mail, $rol, $clave);
    
        if ($rol!==false) {

            $request = $request->withParsedBody(['rol'=> $rol]);
            $response = $handler->handle($request);

            echo "Ok - ".$rol;
    
            return $response;

        } else {

            $response = new Response();
            $response->getBody()->write('Credenciales inválidas!');
            $response = $response->withStatus(401);
    
            return $response;

        }
    }
    
    static function validarCredenciales($mail, $rol, $clave) {

        try {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta('SELECT * FROM usuarios WHERE mail = :mail AND rol = :rol AND clave = :clave');
            $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
            $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
            $consulta->execute();


            $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($usuario)
            {
                return $usuario['rol'];
            }
            else{
                return false;
            }

        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
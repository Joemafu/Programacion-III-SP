<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseClass;
use Firebase\JWT\JWT;
use Exception;
use Illuminate\Database\Console\Migrations\RollbackCommand;

class JwtTokenValidatorMiddleware
{
    private $secretKey;
    private $rolRequerido;

    public function __construct($secretKey, $rolRequerido)
    {
        $this->secretKey = $secretKey;
        $this->rolRequerido = $rolRequerido;
    }

    public function __invoke(Request $request, RequestHandler $handler): ResponseClass
    {
        $token = $this->obtenerTokenDelRequest($request);
        $rolValido = false;

        if ($token===null) {
            $response = new ResponseClass();
            $response->getBody()->write(json_encode(['error' => 'Token necesario']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        try {
            $decodedToken = JWT::decode($token, $this->secretKey, ['HS256']);

            $request = $request->withAttribute('jwtPayload', (array) $decodedToken);
        } catch (Exception $e) {
            $response = new ResponseClass();
            $response->getBody()->write(json_encode(['error' => 'Token inválido']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        foreach ($this->rolRequerido as $rolAutorizado)
        {
            if ($decodedToken->rol==$rolAutorizado)
            {
                $rolValido=true;
                break ;
            }
        }

        if ($rolValido===false)
        {
            $response = new ResponseClass();
            $response->getBody()->write(json_encode(['error' => 'No posee privilegios para realizar esta accion']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $response = $handler->handle($request);
        return $response;
    }

    private function obtenerTokenDelRequest(Request $request): ?string
    {
        $authorizationHeader = $request->getHeaderLine('Authorization');

        if (empty($authorizationHeader)) {
            return null;
        }

        $partesDelHeader = explode(' ', $authorizationHeader);

        if (count($partesDelHeader) !== 2 || $partesDelHeader[0] !== 'Bearer') {
            return null;
        }

        return $partesDelHeader[1];
    }
}

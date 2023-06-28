<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Firebase\JWT\JWT;

class JwtTokenGeneratorMiddleware
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        $data = $request->getParsedBody();


        $rol = $data['rol'] ?? '';

        $payload = [
            'rol' => $rol,
        ];

        try {
            $token = JWT::encode($payload, $this->secretKey, 'HS256');
            
            $response = $handler->handle($request);
            $response = $response->withHeader('Authorization', 'Bearer ' . $token);
            
            return $response;
        } catch (Exception $e) {

            $response = new Response();
            $response->getBody()->write('Error al generar el token: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }
}
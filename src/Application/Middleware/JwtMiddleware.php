<?php

namespace App\Application\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;

class JwtMiddleware implements MiddlewareInterface
{
    private $container;
    private $jwtSecret;

    public function __construct(ContainerInterface $container, string $jwtSecret)
    {
        $this->container = $container;
        $this->jwtSecret = $jwtSecret;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $authHeader = $request->getHeader('Authorization');

        if ($authHeader) {
            $token = trim(str_replace('Bearer', '', $authHeader[0]));


            try {
                $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
                $request = $request->withAttribute('jwt', $decoded);
            } catch (\Exception $e) {
                return $this->unauthorizedResponse($request);
            }
        } else {
            return $this->unauthorizedResponse($request);
        }


        return $handler->handle($request);
    }

    private function unauthorizedResponse(Request $request): Response
    {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;

class ApiKeyMiddleware implements Middleware
{
    private $apiKey;
    
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    public function process(Request $request, RequestHandler $handler): Response
    {
        $authHeader = $request->getHeaderLine('X-API-Key');
        
        if (empty($authHeader)) {
            return $this->unauthorized('API key not provided');
        }
        
        $providedKey = $authHeader;
        
        // Verifica se a API key é válida
        if ($providedKey !== $this->apiKey) {
            return $this->unauthorized('Invalid API key');
        }
        
        // Se a API key for válida, continue com a requisição
        return $handler->handle($request);
    }
    
    private function unauthorized(string $message): Response
    {
        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse(401);
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => $message
        ]));
        
        return $response->withHeader('Content-Type', 'application/json');
    }
}
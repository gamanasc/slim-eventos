<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

// Middleware de validação com Valitron
class ValitronMiddleware
{
    private $setup;

    public function __construct(callable $setup)
    {
        $this->setup = $setup;
    }

    public function __invoke(Request $request, $handler): Response
    {
        $data = $request->getParsedBody();

        // Se dados estiverem vazios, defina array vazio para evitar erros
        if ($data === null) {
            $data = [];
        }
        
        // Criar validador
        $v = new Validator($data);
        
        // Configurar regras (função de callback personalizada)
        call_user_func($this->setup, $v);
        
        // Executar validação
        if (!$v->validate()) {
            $response = new \Slim\Psr7\Response();
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422)
                ->withBody((new \Slim\Psr7\Factory\StreamFactory())->createStream(
                    json_encode(['errors' => $v->errors()])
                ));
        }
        
        return $handler->handle($request);
    }
}
<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Application\Actions\Event\SaveAction;
use App\Application\Actions\Event\UpdateAction;
use App\Application\Actions\Event\DeleteAction;
use App\Application\Actions\Event\FindAllAction;
use App\Application\Actions\Event\FindAction;

use App\Application\Middleware\ApiKeyMiddleware;
use App\Application\Middleware\ValitronMiddleware;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    // Função de validação para ser reutilizada
    function validateFields($v, $data = null) {
        // Validações comuns
        $v->rule('required', ['name', 'description', 'datetime', 'location', 'capacity']);
        $v->rule('lengthMin', 'name', 3);
        $v->rule('lengthMax', 'name', 500);
        $v->rule('integer', 'capacity');
        $v->rule('min', 'capacity', 1);

        // Validação de datetime (apenas se não for nulo)
        if (isset($data['datetime']) && $data['datetime'] !== null) {
            $v->rule('dateFormat', 'datetime', 'Y-m-d H:i:s');
        }
    }

    // GRUPO DE ROTAS PARA EVENTOS
    $app->group('/events', function (Group $group) {
        // Rota POST
        $group->post('', SaveAction::class)
            ->add(new ValitronMiddleware(function($v) { validateFields($v); }));
        
        // Rota PUT
        $group->put('/{id}', UpdateAction::class)
            ->add(new ValitronMiddleware(function($v) { validateFields($v); }));
        
        // Rota DELETE
        $group->delete('/{id}', DeleteAction::class);
        
        // Rotas GET
        $group->get('', FindAllAction::class);
        $group->get('/{id}', FindAction::class);
    })
    ->add(ApiKeyMiddleware::class);
};

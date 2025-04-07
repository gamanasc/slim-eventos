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

    // GRUPO DE ROTAS PARA EVENTOS
    $app->group('/events', function (Group $group) {
        // Rota POST
        $group->post('', SaveAction::class);
        
        // Rota PUT
        $group->put('/{id}', UpdateAction::class);
        
        // Rota DELETE
        $group->delete('/{id}', DeleteAction::class);
        
        // Rotas GET
        $group->get('', FindAllAction::class)->add(ApiKeyMiddleware::class);
        $group->get('/{id}', FindAction::class);
    });
};

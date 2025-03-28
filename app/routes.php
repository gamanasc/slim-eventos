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

    $app->group('/events', function (Group $group) {
        $group->post('', SaveAction::class);
    });

    $app->group('/events', function (Group $group) {
        $group->put('/{id}', UpdateAction::class);
    });

    $app->group('/events', function (Group $group) {
        $group->delete('/{id}', DeleteAction::class);
    });

    $app->group('/events', function (Group $group) {
        $group->get('', FindAllAction::class);
    })->add(ApiKeyMiddleware::class);

    $app->group('/events', function (Group $group) {
        $group->get('/{id}', FindAction::class);
    });
};
